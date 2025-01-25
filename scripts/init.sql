BEGIN;

-- QUERY CONFIGURATION START --
set
    character_set_client = 'utf8';

set
    character_set_connection = 'utf8';

set
    character_set_database = 'utf8';

set
    character_set_results = 'utf8';

set
    character_set_server = 'utf8';

show variables like 'char%';

-- TABLES START -- 
DROP TABLE IF EXISTS review,
movie_crew,
movie_cast,
movie_category,
movie,
people,
category,
user;

CREATE TABLE
    IF NOT EXISTS user (
        username VARCHAR(255) PRIMARY KEY,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NOT NULL,
        is_admin BOOLEAN NOT NULL
    );

CREATE TABLE
    IF NOT EXISTS category (
        name VARCHAR(255) PRIMARY KEY,
        html_name VARCHAR(255)
    );

CREATE TABLE
    IF NOT EXISTS people (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        html_name VARCHAR(255) DEFAULT name,
        profile_image TEXT
    );

CREATE TABLE
    IF NOT EXISTS movie (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        original_name VARCHAR(255),
        original_language VARCHAR(255),
        release_date DATE,
        runtime INT,
        phase VARCHAR(255) NOT NULL,
        budget INT,
        revenue INT,
        description TEXT NOT NULL,
        image_path TEXT
    );

CREATE TABLE
    IF NOT EXISTS movie_category (
        movie_id INT,
        category_name VARCHAR(255),
        PRIMARY KEY (movie_id, category_name),
        FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE,
        FOREIGN KEY (category_name) REFERENCES category (name)
    );

CREATE TABLE
    IF NOT EXISTS movie_cast (
        movie_id INT,
        person_id INT,
        role VARCHAR(255),
        PRIMARY KEY (movie_id, person_id, role),
        FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE,
        FOREIGN KEY (person_id) REFERENCES people (id)
    );

CREATE TABLE
    IF NOT EXISTS movie_crew (
        movie_id INT,
        person_id INT,
        role VARCHAR(255),
        PRIMARY KEY (movie_id, person_id, role),
        FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE,
        FOREIGN KEY (person_id) REFERENCES people (id)
    );

CREATE TABLE
    IF NOT EXISTS review (
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        data DATE NOT NULL,
        rating INT NOT NULL,
        username VARCHAR(255),
        movie_id INT,
        FOREIGN KEY (username) REFERENCES user (username),
        FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE,
        PRIMARY KEY (username, movie_id)
    );

-- GENERATED_INSERT_START --
INSERT INTO
    category (name, html_name)
VALUES
    ('Avventura', 'Avventura'),
    ('Famiglia', 'Famiglia'),
    ('Animazione', 'Animazione'),
    ('Azione', 'Azione'),
    ('Fantascienza', 'Fantascienza'),
    ('Commedia', 'Commedia'),
    ('Dramma', 'Dramma'),
    ('Romance', '<span lang="en">Romance</span>'),
    ('Fantasy', '<span lang="en">Fantasy</span>'),
    ('Horror', '<span lang="en">Horror</span>'),
    ('Thriller', '<span lang="en">Thriller</span>');

INSERT INTO
    people (id, name, profile_image)
VALUES
    (
        '1',
        'Aaron Pierre',
        'hNwZWdT2KxKj1YLbipvtUhNjfAp.webp'
    ),
    (
        '2',
        'Kelvin Harrison Jr.',
        '6kpDyaZzmSbqCNYuXZUfeMwS1bq.webp'
    ),
    (
        '3',
        'Tiffany Boone',
        '9LwqRFdSzxVtnutDUg98YLq0bSz.webp'
    ),
    (
        '4',
        'Kagiso Lediga',
        'nfqx3CqFVsAMelk6ry560SuN7Y0.webp'
    ),
    (
        '5',
        'Preston Nyman',
        'eidKvLDCRw68tG3CN6fGhvHUnW.webp'
    ),
    (
        '6',
        'Blue Ivy Carter',
        'mnaFedkdW9TFCkky7fiiT5dfXye.webp'
    ),
    (
        '7',
        'John Kani',
        'g7tqg3q128a5O2qXMCwVnXsow9I.webp'
    ),
    (
        '8',
        'Mads Mikkelsen',
        'ntwPvV4GKGGHO3I7LcHMwhXfsw9.webp'
    ),
    (
        '9',
        'Seth Rogen',
        '2dPFskUtoiG0xafsSEGl9Oz4teA.webp'
    ),
    (
        '10',
        'Billy Eichner',
        'kScO4moqNlDbyCTZuIoBqyaml4l.webp'
    ),
    (
        '11',
        'Barry Jenkins',
        '6nld5eQwiJmuLmyesk4EUeCaoo3.webp'
    ),
    (
        '12',
        'Jim Carrey',
        'u0AqTz6Y7GHPCHINS01P7gPvDSb.webp'
    ),
    (
        '13',
        'Ben Schwartz',
        'lJVYjPj0P6uvVxNrTy4xO2645D0.webp'
    ),
    (
        '14',
        'Keanu Reeves',
        '8RZLOyYGsoRe9p44q3xin9QkMHv.webp'
    ),
    (
        '15',
        'Idris Elba',
        'be1bVF7qGX91a6c5WeRPs5pKXln.webp'
    ),
    (
        '16',
        'Colleen O''Shaughnessey',
        'y3Kl5tCX1XD6uyL9wefTRbEXTwj.webp'
    ),
    (
        '17',
        'James Marsden',
        'mk142GG0saiSXALY6V4wWcmPROW.webp'
    ),
    (
        '18',
        'Tika Sumpter',
        '1zTXufyuQFPXVthryH7KVoZAfb7.webp'
    ),
    (
        '19',
        'Lee Majdoub',
        'vpF3R2YRCGHseGevmDAhftmOPkO.webp'
    ),
    (
        '20',
        'Krysten Ritter',
        'd39VsFGyWq7f5fkUrCI9k3XpZl2.webp'
    ),
    (
        '21',
        'Adam Pally',
        'yY13PEaVbPoXT5MkitVxTfdAZnU.webp'
    ),
    (
        '22',
        'Jeff Fowler',
        'wExdubFgeBkEUP8MojKPKoOcgdZ.webp'
    ),
    (
        '23',
        'Paul Mescal',
        'vrzZ41TGNAFgfmZjC2sOJySzBLd.webp'
    ),
    (
        '24',
        'Denzel Washington',
        '9Iyt3wbsla5bM6IzbICDVnBhkER.webp'
    ),
    (
        '25',
        'Pedro Pascal',
        '9VYK7oxcqhjd5LAH6ZFJ3XzOlID.webp'
    ),
    (
        '26',
        'Connie Nielsen',
        'lvQypTfeH2Gn2PTbzq6XkT2PLmn.webp'
    ),
    (
        '27',
        'Joseph Quinn',
        'zshhuioZaH8S5ZKdMcojzWi1ntl.webp'
    ),
    (
        '28',
        'Fred Hechinger',
        '99ctABEIEwNl4qjIZcLODgwnx0M.webp'
    ),
    (
        '29',
        'Lior Raz',
        'bl3KLFUQ4Q0zC9lCU4qP1Jf4qHS.webp'
    ),
    (
        '30',
        'Derek Jacobi',
        'htc4eCYmNlVotcu8AFTbDiLBzsJ.webp'
    ),
    (
        '31',
        'Peter Mensah',
        't94TFc6f71AUmZFqdaQfjr7LTRp.webp'
    ),
    (
        '32',
        'Matt Lucas',
        '2OhGLJqiknaWlbTkG2KDwT935km.webp'
    ),
    (
        '33',
        'Ridley Scott',
        'zABJmN9opmqD4orWl3KSdCaSo7Q.webp'
    ),
    (
        '34',
        'Auliʻi Cravalho',
        'aLYuYXqjESo8IWu2nfQjauGrPUT.webp'
    ),
    (
        '35',
        'Dwayne Johnson',
        'kuqFzlYMc2IrsOyPznMd1FroeGq.webp'
    ),
    (
        '36',
        'Hualālai Chung',
        'x2g5fdHqETY9dZgL4aB0QDP0boR.webp'
    ),
    (
        '37',
        'Rose Matafeo',
        'zQa39fMjbOTIsovbh1TBTJVlToz.webp'
    ),
    (
        '38',
        'David Fane',
        'tcozyaTgAa8rRmzc5qeht0loni6.webp'
    ),
    (
        '39',
        'Awhimai Fraser',
        '276OUDPl2iIsz772HQw3tiz2JN2.webp'
    ),
    (
        '40',
        'Khaleesi Lambert-Tsuda',
        '3LHXDjy9UijbtR7X2EReX5H57kk.webp'
    ),
    (
        '41',
        'Temuera Morrison',
        '1ckHDFgKXJ8pazmvLCW7DeOKqA0.webp'
    ),
    (
        '42',
        'Nicole Scherzinger',
        'pOu2al9UBvBYCHaMQFcmGbPNXeF.webp'
    ),
    (
        '43',
        'Rachel House',
        'm8D9XlTGfI0ZmauMKtYp5tw8eNi.webp'
    ),
    (
        '44',
        'David G. Derrick Jr.',
        'j5JOtRua5KduoPsQVix0rwY3jOo.webp'
    ),
    (
        '45',
        'Jason Hand',
        'gepbkgavGdDXdNbQzdFaxayTpoH.webp'
    ),
    (
        '46',
        'Dana Ledoux Miller',
        'wKqVtkgOv6iMcv1P0YPxV7UtQS9.webp'
    ),
    (
        '47',
        'Cynthia Erivo',
        'oQcFwHu50upW6Soz4Ycc9iHge1k.webp'
    ),
    (
        '48',
        'Ariana Grande',
        'cslFyOh3sTWDeWXgsxmjJ1uqE0P.webp'
    ),
    (
        '49',
        'Jeff Goldblum',
        'o3PahuK7OmCI0RAQUq38CUBWYZ9.webp'
    ),
    (
        '50',
        'Michelle Yeoh',
        'nrbHNzSMydpWK9um5VqWIFJihB5.webp'
    ),
    (
        '51',
        'Jonathan Bailey',
        'mZNzekZo8eaHMuXKgDTNLp0EvYM.webp'
    ),
    (
        '52',
        'Ethan Slater',
        'xIgqyrM78FPt7Pb2Vv3IvJcnOWS.webp'
    ),
    (
        '53',
        'Marissa Bode',
        'uqifog9p62A6dW9T4yivfWnSFXQ.webp'
    ),
    (
        '54',
        'Peter Dinklage',
        '9CAd7wr8QZyIN0E7nm8v1B6WkGn.webp'
    ),
    (
        '55',
        'Andy Nyman',
        '9bN9RVoPWmsmV3VBI7hp4VKD9Kg.webp'
    ),
    (
        '56',
        'Courtney Mae-Briggs',
        'ofOEXvhJpbFV7v8ZnH0ztTPGKkr.webp'
    ),
    (
        '57',
        'Jon M. Chu',
        '85kOxm7w4nGRwyhquj9wtUM8KUW.webp'
    ),
    (
        '58',
        'Brian Cox',
        'yvoAgJTOvuNSPSKegcIcD62ySY9.webp'
    ),
    (
        '59',
        'Gaia Wise',
        'vyjUZOrhto3PKq4PuHG7270cum5.webp'
    ),
    (
        '60',
        'Luke Pasqualino',
        'egwyqohJ3KLag4GUyTzXGngwTc1.webp'
    ),
    (
        '61',
        'Laurence Ubong Williams',
        'wKc9PHuuqrFRIAepLm87AUDdvgt.webp'
    ),
    (
        '62',
        'Lorraine Ashbourne',
        '4cOOwDYkKcCf9G6fdGGDyAR4jru.webp'
    ),
    (
        '63',
        'Miranda Otto',
        'szME1IBVTLgiKrO5D5wvOGnvUDW.webp'
    ),
    (
        '64',
        'Shaun Dooley',
        'uWiw28ARiLkvL6xUjKDLDlSsfkY.webp'
    ),
    (
        '65',
        'Benjamin Wainwright',
        'hdU9RbfcSBUHhUPcPTildubV0bZ.webp'
    ),
    (
        '66',
        'Yazdan Qafouri',
        'yzDZMMziiqdrSyWecOv8v2O56HL.webp'
    ),
    (
        '67',
        'Michael Wildman',
        '7JzaKisWtdqYmuuEunjTkU8zDf8.webp'
    ),
    ('68', '神山健治', '1irWT1aUVYdeoZCtnxNVS9wfrks.webp'),
    (
        '69',
        'Frank Grillo',
        'br2nPzelch2Tb3pZHnYAbXng7cz.webp'
    ),
    (
        '70',
        'Katrina Law',
        'y7LzCN8BaoPPSXDBo0UwPQ4UwSB.webp'
    ),
    (
        '71',
        'Ilfenesh Hadera',
        'ba6APgDd74vMu7kvBYQwYOpvoEM.webp'
    ),
    (
        '72',
        'Jimmy Cummings',
        'fVedSxKuTiF4tJcC2qqkQ8M4jSf.webp'
    ),
    (
        '73',
        'Lou Diamond Phillips',
        'yqGZwaGe8XgoxfO7zmx7weGBaXZ.webp'
    ),
    (
        '74',
        'Kamdynn Gary',
        '4g97ms0dbKALnhgwSwwEXxZGV2k.webp'
    ),
    (
        '75',
        'Lydia Styslinger',
        'd5R9WgB4wvUmFyoiBgXbVVJuax2.webp'
    ),
    ('76', 'Daniel Fernandez', 'DanielFernandez.webp'),
    (
        '77',
        'James Kyson',
        'jpV8eFgigwHbhyEL9eZ4czbgMoy.webp'
    ),
    (
        '78',
        'Betzaida Landín',
        '6ZMhy5QxQAD6U0iRME5psZTO4Rb.webp'
    ),
    (
        '79',
        'Steven C. Miller',
        'lilw96APKmmFouMiDl7Hd5tlw6t.webp'
    ),
    ('80', 'Matthew Kennedy', 'MatthewKennedy.webp'),
    (
        '81',
        'Nicole Wallace',
        'xlvq6OYCN6yQef4fpJQtwVyQxqr.webp'
    ),
    (
        '82',
        'Gabriel Guevara',
        'pviRYKEEmoPUfLYwP1VHJ6LQcRg.webp'
    ),
    (
        '83',
        'Marta Hazas',
        '1dbeTFRCbWBt70dIGjYHKVLnpaG.webp'
    ),
    (
        '84',
        'Iván Sánchez',
        'woVz8D7t1VUKjFJnsTAdc8tyz5C.webp'
    ),
    (
        '85',
        'Eva Ruiz',
        'bcu0nmQvhxwTzh4csc4kuxJsQee.webp'
    ),
    (
        '86',
        'Víctor Varona',
        'lcwFAjHjhJXkxf59TXGSjGGOlLj.webp'
    ),
    (
        '87',
        'Gabriela Andrada',
        'h8cAdDqbDK2ayGIuxQQdNCzyCsb.webp'
    ),
    (
        '88',
        'Alex Bejar',
        'kPeFa6xX57IyhAfoT8dtgfPMgmX.webp'
    ),
    (
        '89',
        'Goya Toledo',
        'dihdQxVr2sFA1XznPK4orzC2m7i.webp'
    ),
    (
        '90',
        'Javier Morgade',
        'hcDV10bTZlm1rTi0NRhoGIpdicw.webp'
    ),
    (
        '91',
        'Domingo González',
        'xpjPbZnSBvYjqgIKMvc835Hf6PJ.webp'
    ),
    (
        '92',
        'Chris Evans',
        'a52ncG9JskJtqrryE0KakPPBkHJ.webp'
    ),
    (
        '93',
        'Lucy Liu',
        '9nbtjqsx3De7hO2XDtrBQ7M9VCH.webp'
    ),
    (
        '94',
        'J.K. Simmons',
        'ScmKoJ9eiSUOthAt1PDNLi8Fkw.webp'
    ),
    (
        '95',
        'Bonnie Hunt',
        '7z0GaJW9WyHcjX3hVfzZXRAkZEz.webp'
    ),
    (
        '96',
        'Kristofer Hivju',
        'bACL39GihNmBnFRay78rS3PUHsH.webp'
    ),
    (
        '97',
        'Kiernan Shipka',
        't2FWVLTIhVRIa398mQAfN4thO5R.webp'
    ),
    (
        '98',
        'Mary Elizabeth Ellis',
        '8tf8w69Bl6MO5P8r8Vk6dFnHKxx.webp'
    ),
    (
        '99',
        'Wesley Kimmel',
        'lN3VfrQiYmU9ldZRHie8PQtnOe2.webp'
    ),
    (
        '100',
        'Nick Kroll',
        'vdgpGtSXqTBnIKrKNMZocdFu7pX.webp'
    ),
    (
        '101',
        'Jake Kasdan',
        'pIpY7zcBSpq6Z3Q1eADPtKDB043.webp'
    ),
    (
        '102',
        'Anthony Mackie',
        'kfTwOYr3iUucmYz8kPjhYy07G2Z.webp'
    ),
    (
        '103',
        'Morena Baccarin',
        'kBSKKaOtsqIzZPhtEeHfCBmhWl9.webp'
    ),
    (
        '104',
        'Maddie Hasson',
        'eH6SnpMGDC9CQScmIchSnKkZAbc.webp'
    ),
    (
        '105',
        'Danny Boyd Jr.',
        'k6ewXmzmCJa71bQmmEifmy11ezt.webp'
    ),
    (
        '106',
        'Rachel Nicks',
        'wJx6DHJ5S5IGvj61yUUPtKzr9cf.webp'
    ),
    (
        '107',
        'Shauna Earp',
        'cpAOnH902U7S9uBNzHOB7tOSvRq.webp'
    ),
    (
        '108',
        'Tyler Grey',
        '3yuB34p1EXx8273HnMrng7kgolA.webp'
    ),
    ('109', 'Drexel Malkoff', NULL),
    (
        '110',
        'David Malkoff',
        'dVo8W6TR2nF9vdRic8OWizutSEe.webp'
    ),
    ('111', 'Mike Hickman', NULL),
    (
        '112',
        'George Nolfi',
        'dYr20UCtki5iRg0k2zSKjG36QKy.webp'
    ),
    ('113', 'Kenny Ryan', NULL),
    ('114', 'Jacob Roman', NULL),
    (
        '115',
        'John Glenn',
        'xDrtvEY2bpdFnRNqW0YNeiCfdv4.webp'
    );

UPDATE people
SET
    profile_image = CONCAT ('images/persone/', profile_image)
WHERE
    profile_image IS NOT NULL;

UPDATE people
SET
    html_name = CASE
        WHEN id = '1' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '2' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '3' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '4' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '5' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '6' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '7' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '8' THEN CONCAT ('<span lang="da">', name, '</span>')
        WHEN id = '9' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '10' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '11' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '12' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '13' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '14' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '15' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '16' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '17' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '18' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '19' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '20' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '21' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '22' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '23' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '24' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '25' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '26' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '27' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '28' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '29' THEN CONCAT ('<span lang="he">', name, '</span>')
        WHEN id = '30' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '31' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '32' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '33' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '34' THEN CONCAT ('<span lang="haw">', name, '</span>')
        WHEN id = '35' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '36' THEN CONCAT ('<span lang="haw">', name, '</span>')
        WHEN id = '37' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '38' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '39' THEN CONCAT ('<span lang="ma">', name, '</span>')
        WHEN id = '40' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '41' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '42' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '43' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '44' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '45' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '46' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '47' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '48' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '49' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '50' THEN CONCAT ('<span lang="zh">', name, '</span>')
        WHEN id = '51' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '52' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '53' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '54' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '55' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '56' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '57' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '58' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '59' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '60' THEN CONCAT ('<span lang="it">', name, '</span>')
        WHEN id = '61' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '62' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '63' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '64' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '65' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '66' THEN CONCAT ('<span lang="fa">', name, '</span>')
        WHEN id = '67' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '68' THEN CONCAT ('<span lang="ja">', name, '</span>')
        WHEN id = '69' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '70' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '71' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '72' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '73' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '74' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '75' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '76' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '77' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '78' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '79' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '80' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '81' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '82' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '83' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '84' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '85' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '86' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '87' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '88' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '89' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '90' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '91' THEN CONCAT ('<span lang="es">', name, '</span>')
        WHEN id = '92' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '93' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '94' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '95' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '96' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '97' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '98' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '99' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '100' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '101' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '102' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '103' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '104' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '105' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '106' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '107' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '108' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '109' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '110' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '111' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '112' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '113' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '114' THEN CONCAT ('<span lang="en">', name, '</span>')
        WHEN id = '115' THEN CONCAT ('<span lang="en">', name, '</span>')
        ELSE name
    END;

INSERT INTO
    movie (
        id,
        name,
        original_name,
        original_language,
        release_date,
        runtime,
        phase,
        budget,
        revenue,
        description,
        image_path
    )
VALUES
    (
        '1',
        'Mufasa - Il re leone',
        'Mufasa: The Lion King',
        'Inglese',
        '2024-12-18',
        '118',
        'Rilasciato',
        '200000000',
        '539677479',
        'Rafiki narra la leggenda di Mufasa alla giovane leoncina Kiara, figlia di Simba e Nala, con Timon e Pumbaa che offrono il loro caratteristico spettacolo. Raccontata attraverso flashback, la storia presenta Mufasa, un cucciolo orfano, perso e solo fino a quando incontra un leone compassionevole di nome Taka, erede di una stirpe reale. L''incontro casuale dà il via al viaggio di uno straordinario gruppo di sventurati alla ricerca del proprio destino: i loro legami saranno messi alla prova mentre lavorano insieme per sfuggire a un nemico minaccioso e letale.',
        'images/film/eZS2taWdadP8lh22xJOVrWT0lTn.webp'
    ),
    (
        '2',
        'Sonic  3 - Il film',
        'Sonic the Hedgehog 3',
        'Inglese',
        '2024-12-19',
        '110',
        'Rilasciato',
        '122000000',
        '384815196',
        'Sonic, Knuckles e Tails si ritrovano a dover fronteggiare un nuovo e formidabile nemico:  Shadow, un misterioso villain con poteri incredibili mai visti prima. Con le loro abilità messe alla prova in ogni modo, il Team Sonic dovra cercare un''alleanza inaspettata per fermare Shadow e proteggere il pianeta.',
        'images/film/hArbAOlNZX0qfd2Udpr6RCTflpg.webp'
    ),
    (
        '3',
        'Il gladiatore II',
        'Gladiator II',
        'Inglese',
        '2024-11-05',
        '148',
        'Rilasciato',
        '310000000',
        '455388841',
        'Anni dopo aver assistito alla tragica morte del venerato eroe Massimo per mano del suo perfido zio, Lucio (Paul Mescal) si trova costretto a combattere nel Colosseo dopo che la sua patria viene conquistata da parte di due tirannici imperatori, che ora governano Roma.  Con il cuore ardente di rabbia e il destino dell''Impero appeso a un filo, Lucio deve affrontare pericoli e nemici, riscoprendo nel suo passato la forza e l''onore necessari per riportare la gloria di Roma al suo popolo. Preparatevi per un viaggio epico di coraggio e vendetta nella sanguinosa arena del Colosseo.',
        'images/film/chnuw5cQMzbrGTK2F7bPUDdmyQF.webp'
    ),
    (
        '4',
        'Oceania 2',
        'Moana 2',
        'Inglese',
        '2024-11-21',
        '100',
        'Rilasciato',
        '150000000',
        '989755208',
        'A tre anni dal suo primo viaggio, Vaiana è impegnata in un lungo viaggio alla ricerca di persone oltre le coste di Motunui. Assieme a Maui e a un nuovissimo equipaggio di improbabili marittimi, Vaiana si imbarca alla volta dei lontani mari dell''Oceania in acque pericolose per un''avventura diversa da qualsiasi cosa abbia mai affrontato.',
        'images/film/dPnRomNzww9G7dgS84f6HSevVLc.webp'
    ),
    (
        '5',
        'Wicked',
        'Wicked',
        'Inglese',
        '2024-11-20',
        '162',
        'Rilasciato',
        '150000000',
        '697615060',
        'La storia ruota attorno all’amicizia tra le due streghe. Elphaba, soprannominata ingiustamente Malvagia Strega dell’Ovest, è in realtà una donna rivoluzionaria e anticonformista con la pelle verde che sfida lo strapotere del Mago di Oz, imbroglione dalle mire imperialiste. Glinda è invece la Strega Buona del Nord, amica di Elphaba dai tempi dell’Università di Shiz.',
        'images/film/3al2ouBCaZN5UcCNr8HKSiNji28.webp'
    ),
    (
        '6',
        'Il Signore degli Anelli - La guerra dei Rohirrim',
        'The Lord of the Rings: The War of the Rohirrim',
        'Inglese',
        '2024-12-05',
        '134',
        'Rilasciato',
        '30000000',
        '19891554',
        '183 anni prima degli aventi del Signore degli Anelli, Helm Mandimartello, re di Rohan, e la sua famiglia difendono il loro regno da un esercito di Dunlandiani. A prendersi il ruolo di protagonista della battaglia, però, è soprattutto Hèra, figlia di Helm dal temperamento ribelle che non esiterà a scendere in campo per difendere il proprio popolo.',
        'images/film/vNVAJb5gmQP0wjd1LDxu0eI6Thl.webp'
    ),
    (
        '7',
        'Werewolves',
        'Werewolves',
        'Inglese',
        '2024-12-04',
        '94',
        'Rilasciato',
        '0',
        '1052998',
        'Una Superluna ha innescato il gene latente in ogni essere umano sulla Terra, trasformando in lupo mannaro chiunque si fosse esposto alla luce lunare durante quella notte. Si è scatenato il caos, e quasi un miliardo di persone sono morte. Ora, un anno dopo, la Superluna è tornata…',
        'images/film/xZv09zZ3Rhe84JFmJB7dzb7iSeJ.webp'
    ),
    (
        '8',
        'È colpa tua?',
        'Culpa tuya',
        'Spagnolo',
        '2024-12-26',
        '121',
        'Rilasciato',
        '0',
        '0',
        'Noah deve lasciare la sua città, il fidanzato e gli amici per trasferirsi a casa del nuovo marito di sua madre. Lì conosce il fratellastro Nick e le loro personalità sono sin da subito in contrasto. Tuttavia, l''attrazione che provano li porterà a vivere una relazione proibita, in cui i caratteri ribelli e tormentati metteranno a soqquadro i rispettivi mondi, facendoli innamorare follemente.',
        'images/film/cKwId4BiGtYPIUCYtKxVphtclrt.webp'
    ),
    (
        '9',
        'Uno Rosso',
        'Red One',
        'Inglese',
        '2024-10-31',
        '124',
        'Rilasciato',
        '250000000',
        '185700759',
        'Dopo che Babbo Natale - nome in codice: UNO ROSSO - viene rapito, il capo della sicurezza del Polo Nord (Dwayne Johnson) deve fare squadra con il più famigerato cacciatore di taglie del mondo (Chris Evans) in una missione travolgente e ricca di azione per salvare il Natale.',
        'images/film/xjmVhig5Y856SCDFwvUMLaqi8hH.webp'
    ),
    (
        '10',
        'Elevation',
        'Elevation',
        'Inglese',
        '2024-11-07',
        '91',
        'Rilasciato',
        '18000000',
        '3300000',
        'Un padre single e due donne escono dalla sicurezza delle loro case per affrontare creature mostruose e salvare la vita di un ragazzino.',
        'images/film/tnfc0NJ3BzhJrGJhkkEd6MHBdq5.webp'
    );

INSERT INTO
    movie_category (movie_id, category_name)
VALUES
    ('1', 'Avventura'),
    ('1', 'Famiglia'),
    ('1', 'Animazione'),
    ('2', 'Azione'),
    ('2', 'Fantascienza'),
    ('2', 'Commedia'),
    ('2', 'Famiglia'),
    ('3', 'Azione'),
    ('3', 'Avventura'),
    ('3', 'Dramma'),
    ('4', 'Animazione'),
    ('4', 'Avventura'),
    ('4', 'Famiglia'),
    ('4', 'Commedia'),
    ('5', 'Dramma'),
    ('5', 'Romance'),
    ('5', 'Fantasy'),
    ('6', 'Animazione'),
    ('6', 'Fantasy'),
    ('6', 'Azione'),
    ('6', 'Avventura'),
    ('7', 'Azione'),
    ('7', 'Horror'),
    ('7', 'Thriller'),
    ('8', 'Romance'),
    ('8', 'Dramma'),
    ('9', 'Azione'),
    ('9', 'Fantasy'),
    ('9', 'Commedia'),
    ('10', 'Azione'),
    ('10', 'Fantascienza'),
    ('10', 'Thriller');

INSERT INTO
    movie_cast (movie_id, person_id, role)
VALUES
    ('1', '1', 'Mufasa (voce)'),
    ('1', '2', 'Taka (voce)'),
    ('1', '3', 'Sarabi (voce)'),
    ('1', '4', 'Young Rafiki (voce)'),
    ('1', '5', 'Zazu (voce)'),
    ('1', '6', 'Kiara (voce)'),
    ('1', '7', 'Rafiki (voce)'),
    ('1', '8', 'Kiros (voce)'),
    ('1', '9', 'Pumbaa (voce)'),
    ('1', '10', 'Timon (voce)'),
    ('2', '12', 'Ivo Robotnik / Gerald Robotnik'),
    ('2', '13', 'Sonic (voce)'),
    ('2', '14', 'Shadow (voce)'),
    ('2', '15', 'Knuckles (voce)'),
    ('2', '16', 'Tails (voce)'),
    ('2', '17', 'Tom'),
    ('2', '18', 'Maddie'),
    ('2', '19', 'Agent Stone'),
    ('2', '20', 'Director Rockwell'),
    ('2', '21', 'Wade Whipple'),
    ('3', '23', 'Lucius'),
    ('3', '24', 'Macrinus'),
    ('3', '25', 'General Acacius'),
    ('3', '26', 'Lucilla'),
    ('3', '27', 'Emperor Geta'),
    ('3', '28', 'Emperor Caracalla'),
    ('3', '29', 'Viggo'),
    ('3', '30', 'Gracchus'),
    ('3', '31', 'Jugurtha'),
    ('3', '32', 'Master of Ceremonies'),
    ('4', '34', 'Moana (voce)'),
    ('4', '35', 'Maui (voce)'),
    ('4', '36', 'Moni (voce)'),
    ('4', '37', 'Loto (voce)'),
    ('4', '38', 'Kele (voce)'),
    ('4', '39', 'Matangi (voce)'),
    ('4', '40', 'Simea (voce)'),
    ('4', '41', 'Chief Tui (voce)'),
    ('4', '42', 'Sina (voce)'),
    ('4', '43', 'Gramma Tala (voce)'),
    ('5', '47', 'Elphaba'),
    ('5', '48', 'Galinda / Glinda'),
    ('5', '49', 'The Wonderful Wizard of Oz'),
    ('5', '50', 'Madame Morrible'),
    ('5', '51', 'Fiyero'),
    ('5', '52', 'Boq'),
    ('5', '53', 'Nessarose'),
    ('5', '54', 'Dr. Dillamond (voce)'),
    ('5', '55', 'Governor Thropp'),
    ('5', '56', 'Mrs. Thropp'),
    ('6', '58', 'Helm Hammerhand (voce)'),
    ('6', '59', 'Héra (voce)'),
    ('6', '60', 'Wulf (voce)'),
    ('6', '61', 'Fréaláf (voce)'),
    ('6', '62', 'Olwyn (voce)'),
    ('6', '63', 'Éowyn (voce)'),
    ('6', '64', 'Freca (voce)'),
    ('6', '65', 'Haleth (voce)'),
    ('6', '66', 'Háma (voce)'),
    ('6', '67', 'General Targg (voce)'),
    ('7', '69', 'Dr. Wesley Marshall'),
    ('7', '70', 'Dr. Amy Chen'),
    ('7', '71', 'Lucy Marshall'),
    ('7', '72', 'Cody'),
    ('7', '73', 'Dr. Aranda'),
    ('7', '74', 'Emma Marshall'),
    ('7', '75', 'Reagan'),
    ('7', '76', 'Evan Radcliffe'),
    ('7', '77', 'Miles Chen'),
    ('7', '78', 'Dr. Vasquez'),
    ('8', '81', 'Noah Morgan'),
    ('8', '82', 'Nick Leister'),
    ('8', '83', 'Rafaela'),
    ('8', '84', 'William Leister'),
    ('8', '85', 'Jenna'),
    ('8', '86', 'Lion'),
    ('8', '87', 'Sofía'),
    ('8', '88', 'Briar'),
    ('8', '89', 'Anabel'),
    ('8', '90', 'Michael'),
    ('9', '35', 'Callum Drift'),
    ('9', '92', 'Jack O''Malley'),
    ('9', '93', 'Zoe'),
    ('9', '94', 'Nick'),
    ('9', '95', 'Mrs. Claus'),
    ('9', '96', 'Krampus'),
    ('9', '97', 'Gryla'),
    ('9', '98', 'Olivia'),
    ('9', '99', 'Dylan'),
    ('9', '100', 'Ted'),
    ('10', '102', 'Will'),
    ('10', '103', 'Nina'),
    ('10', '104', 'Katie'),
    ('10', '105', 'Hunter'),
    ('10', '106', 'Tara'),
    ('10', '107', 'Hannah'),
    ('10', '108', 'Tim'),
    ('10', '109', 'Nina’s Son (non creditato)'),
    ('10', '110', 'Nina’s Husband (non creditato)'),
    ('10', '111', 'Refugee (non creditato)');

INSERT INTO
    movie_crew (movie_id, person_id, role)
VALUES
    ('1', '11', 'Regista'),
    ('2', '22', 'Regista'),
    ('3', '33', 'Regista'),
    ('4', '44', 'Regista'),
    ('4', '45', 'Regista'),
    ('4', '46', 'Regista'),
    ('5', '57', 'Regista'),
    ('6', '68', 'Regista'),
    ('7', '79', 'Regista'),
    ('7', '80', 'Scrittore'),
    ('8', '91', 'Regista'),
    ('9', '101', 'Regista'),
    ('10', '112', 'Regista'),
    ('10', '113', 'Scrittore'),
    ('10', '114', 'Scrittore'),
    ('10', '115', 'Scrittore');

-- GENERATED_INSERT_END --
-- MANUAL_INSERT_START --
INSERT INTO
    user (username, password, name, last_name, is_admin)
VALUES
    (
        'admin',
        '$2a$12$.w3WfJMmXnV3Ap3H598wMOk/0bd7gk/OvCSx8QngaNkIs/VtgHDwq', -- BCRYPT12 HASH OF: 'admin'
        'mario',
        'rossi',
        true
    ),
    (
        'user',
        '$2a$12$SwD9B93YDNIExvZAR1qbteLCY6uWwO0NrvrknQqE9WLHUty.ZAQki', -- BCRYPT12 HASH OF: 'user',
        'luca',
        'agostino',
        false
    ),
    (
        'franco',
        '$2a$12$9dO1KqY62WmW.CAfDU2UW.iI22UinkN5C0Nsn1dUqrAneW6o5Z1sq', -- BCRYPT12 HASH OF: 'franco',
        'franco',
        'bianchi',
        false
    );

INSERT INTO
    review (title, content, data, rating, username, movie_id)
VALUES
    (
        'Un Re Leone da Oscar!',
        'Mufasa è tornato e non delude! Un film che ti fa ruggire di gioia. Animazioni spettacolari e una storia che tocca il cuore. Assolutamente da vedere!',
        CURRENT_TIMESTAMP,
        10,
        'franco',
        1
    ),
    (
        'Sonic 3: Velocità e Risate',
        'Sonic e i suoi amici sono tornati con un nuovo nemico! Shadow è incredibile e le battute sono esilaranti. Un mix perfetto di azione e comicità!',
        CURRENT_TIMESTAMP,
        8,
        'franco',
        2
    ),
    (
        'Gladiatore II: Epico!',
        'Lucio è un degno successore di Massimo. Le scene di combattimento sono mozzafiato e la trama è avvincente. Un film che ti tiene incollato alla sedia!',
        CURRENT_TIMESTAMP,
        9,
        'franco',
        3
    ),
    (
        'Oceania 2: Avventura senza fine',
        'Vaiana e Maui sono tornati per un\'altra epica avventura. Le canzoni sono fantastiche e l\'animazione è splendida. Un film che fa sognare!',
        CURRENT_TIMESTAMP,
        8,
        'franco',
        4
    ),
    (
        'Wicked: Magia e Amicizia',
        'Elphaba e Glinda ci portano in un viaggio magico. La storia è emozionante e i personaggi sono indimenticabili. Un film che incanta!',
        CURRENT_TIMESTAMP,
        9,
        'franco',
        5
    );

-- MANUAL_INSERT_END
COMMIT;