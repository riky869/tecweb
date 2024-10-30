BEGIN;

DROP DATABASE IF EXISTS techweb;

CREATE DATABASE IF NOT EXISTS techweb;

USE techweb;

DROP TABLE IF EXISTS review,
movie,
user;

CREATE TABLE IF NOT EXISTS user (
    id INT PRIMARY KEY,
    username TEXT,
    password TEXT,
    name TEXT,
    lastname TEXT
);


CREATE TABLE IF NOT EXISTS movie (
    id INT PRIMARY KEY,
    name TEXT,
    description TEXT
);

CREATE TABLE IF NOT EXISTS review (
    id INT PRIMARY KEY,
    title TEXT,
    content TEXT,
    user_id INT REFERENCES user(id),
    movie_id INT REFERENCES movie(id)
);

COMMIT;