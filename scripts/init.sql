BEGIN;

DROP DATABASE IF EXISTS tecweb;

CREATE DATABASE IF NOT EXISTS tecweb;

USE tecweb;

DROP TABLE IF EXISTS review,
movie,
user;

CREATE TABLE IF NOT EXISTS user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username TEXT,
    password TEXT,
    name TEXT,
    last_name TEXT
);

CREATE TABLE IF NOT EXISTS movie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name TEXT,
    description TEXT
);

CREATE TABLE IF NOT EXISTS review (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title TEXT,
    content TEXT,
    user_id INT REFERENCES user(id),
    movie_id INT REFERENCES movie(id)
);

INSERT INTO
    movie (name, description)
VALUES
    ('Blade Runner', 'blade runner'),
    ('Odissea Nello Spazio', 'odissea nello spazio');

COMMIT;