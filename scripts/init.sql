BEGIN;

DROP DATABASE IF EXISTS tecweb;

CREATE DATABASE IF NOT EXISTS tecweb;

USE tecweb;

DROP TABLE IF EXISTS review,
movie,
category,
user;

CREATE TABLE IF NOT EXISTS user (
    username VARCHAR(255) PRIMARY KEY,
    password TEXT NOT NULL,
    name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    is_admin BOOLEAN NOT NULL
);

CREATE TABLE IF NOT EXISTS category (name VARCHAR(255) PRIMARY KEY);

CREATE TABLE IF NOT EXISTS movie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name TEXT NOT NULL,
    category VARCHAR(255) REFERENCES category(name),
    description TEXT NOT NULL,
    image_path TEXT
);

CREATE TABLE IF NOT EXISTS review (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    data DATE NOT NULL,
    rating INT NOT NULL,
    username VARCHAR(255) REFERENCES user(username),
    movie_id INT REFERENCES movie(id)
);

INSERT INTO
    category (name)
VALUES
    ('Horror'),
    ('Dramma');

INSERT INTO
    movie (name, description, category)
VALUES
    ('Blade Runner', 'blade runner', 'Horror'),
    (
        'Odissea Nello Spazio',
        'odissea nello spazio',
        'Dramma'
    );

INSERT INTO
    user (username, password, name, last_name, is_admin)
VALUES
    ('admin', 'admin', 'mario', 'rossi', true),
    ('user', 'user', 'luca', 'agostino', false);

COMMIT;