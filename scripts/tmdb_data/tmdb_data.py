import os
import shutil
import datetime
import tmdbsimple as tmdb
import pandas as pd
import requests
from tqdm import tqdm

# python3 -m venv venv
# source venv/bin/activate
# pip install -r requirements.txt
# python3 tmdb_data.py
# deactivate

API_KEY = os.getenv("TMDB_API_KEY", "")
MAX_MOVIES = 10
OUTPUT_FILE = "output/output.sql"
movie_images_dir = "output/film"
people_images_dir = "output/persone"

genres_data = []
genre_ids = []
people_data = []
person_ids = []
collections_data = []
collection_ids = []
movies_data = []
movie_ids = []
movie_genres = []
movie_cast = []
movie_crew = []

crew_roles_of_interest = ["Director", "Writer"]


def execute_sql(data, table_name):
    with open(OUTPUT_FILE, "a", encoding="utf-8") as file:
        if data:
            keys = ", ".join(data[0].keys())
            file.write(f"INSERT INTO {table_name} ({keys}) VALUES\n")
            for record in data:
                values = ", ".join(
                    [
                        (
                            f"'{str(value).replace('\'', '\'\'')}'"
                            if value is not None
                            else "NULL"
                        )
                        for value in record.values()
                    ]
                )
                file.write(f"({values})" + ("" if record == data[-1] else ",") + "\n")
            file.write(";\n")


def delete_output_file():
    if os.path.exists(OUTPUT_FILE):
        os.remove(OUTPUT_FILE)


def download_and_save_image(url, save_path):
    response = requests.get(url)
    if response.status_code == 200:
        with open(save_path, "wb") as file:
            file.write(response.content)
        return True
    return False


def add_new_person(person):
    try:
        return next(
            item["id"] for item in person_ids if item["tmdb_id"] == person["id"]
        )
    except StopIteration:
        new_id = len(people_data) + 1
        person_ids.append({"id": new_id, "tmdb_id": person["id"]})
        people_data.append(
            {
                "id": new_id,
                "name": person["name"],
                "profile_image": (
                    person["profile_path"][1:] if person["profile_path"] else None
                ),
            }
        )
        if person["profile_path"]:
            image_url = f"https://image.tmdb.org/t/p/h632/{person['profile_path'][1:]}"
            save_path = os.path.join(people_images_dir, f"{person['profile_path'][1:]}")
            download_and_save_image(image_url, save_path)
        return new_id


def fetch_movies():
    status_mapping = {
        "Rumored": "Voci",
        "Planned": "Pianificato",
        "In Production": "In produzione",
        "In-Production": "In produzione",
        "Post Production": "Post produzione",
        "Post-Production": "Post produzione",
        "Released": "Rilasciato",
        "Canceled": "Cancellato",
        "TBA": "Da annunciare",
        "Announced": "Annunciato",
        "Pre Production": "Pre produzione",
        "Pre-Production": "Pre produzione",
        "Filming": "In produzione",
        "Completed": "Completato",
    }

    movies_downloaded = 0

    for page in range(1, 100):
        movies = tmdb.Movies().now_playing(language="it", page=page)["results"]
        if not movies:
            break

        for idx, movie in enumerate(movies):
            if movies_downloaded >= MAX_MOVIES:
                break
            movie_ids.append({"id": idx + 1, "tmdb_id": movie["id"]})
            movies_data.append({"id": idx + 1})
            movies_downloaded += 1

        if movies_downloaded >= MAX_MOVIES:
            break

    for movie in tqdm(movies_data, desc="Processing Movies", unit="movie"):
        movie_tmdb_id = next(
            item["tmdb_id"] for item in movie_ids if item["id"] == movie["id"]
        )
        movie_info = tmdb.Movies(movie_tmdb_id).info(language="it")

        movie.update(
            {
                "name": movie_info["title"],
                "original_name": movie_info["original_title"],
                "original_language": movie_info["original_language"],
                "release_date": (
                    movie_info["release_date"] if movie_info["release_date"] else None
                ),
                "runtime": movie_info["runtime"] if movie_info["runtime"] else 0,
                "phase": status_mapping.get(movie_info["status"], "Unknown"),
                "budget": movie_info["budget"] if movie_info["budget"] > 0 else 0,
                "revenue": movie_info["revenue"] if movie_info["revenue"] > 0 else 0,
                "description": (
                    movie_info["overview"]
                    if movie_info["overview"]
                    else "Informazione non disponibile"
                ),
                "image_path": (
                    movie_info["poster_path"][1:] if movie_info["poster_path"] else None
                ),
            }
        )

        if movie["image_path"]:
            image_url = f"https://image.tmdb.org/t/p/w780/{movie['image_path']}"
            save_path = os.path.join(movie_images_dir, f"{movie['image_path']}")
            download_and_save_image(image_url, save_path)

        for genre in movie_info["genres"]:
            genre_name = genre["name"]
            if genre["id"] not in [g["tmdb_id"] for g in genre_ids]:
                genre_ids.append({"id": len(genre_ids), "tmdb_id": genre["id"]})
                genres_data.append({"name": genre_name})
            movie_genres.append({"movie_id": movie["id"], "category_name": genre_name})

        credits = tmdb.Movies(movie_tmdb_id).credits(language="it")

        for actor in credits.get("cast", [])[:10]:
            person_id = add_new_person(actor)
            movie_cast.append(
                {
                    "movie_id": movie["id"],
                    "person_id": person_id,
                    "role": actor.get("character", "N/A"),
                }
            )

        for crew_member in credits.get("crew", []):
            if crew_member.get("job") in crew_roles_of_interest:
                person_id = add_new_person(crew_member)
                movie_crew.append(
                    {
                        "movie_id": movie["id"],
                        "person_id": person_id,
                        "role": crew_member.get("job"),
                    }
                )


def clean_directories():
    for directory in [movie_images_dir, people_images_dir]:
        if os.path.exists(directory):
            shutil.rmtree(directory)
        os.makedirs(directory, exist_ok=True)


def main():
    tmdb.API_KEY = API_KEY

    clean_directories()
    delete_output_file()
    fetch_movies()

    execute_sql(genres_data, "category")
    execute_sql(people_data, "people")
    execute_sql(movies_data, "movie")
    execute_sql(movie_genres, "movie_category")
    execute_sql(movie_cast, "cast")
    execute_sql(movie_crew, "crew")


if __name__ == "__main__":
    main()
