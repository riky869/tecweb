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

-- DATABASE START --
DROP DATABASE IF EXISTS tecweb;

CREATE DATABASE IF NOT EXISTS tecweb;

USE tecweb;

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
    IF NOT EXISTS category (name VARCHAR(255) PRIMARY KEY);

CREATE TABLE
    IF NOT EXISTS people (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        profile_image TEXT
    );

CREATE TABLE
    IF NOT EXISTS movie (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        original_name VARCHAR(255) NOT NULL,
        original_language VARCHAR(255) NOT NULL,
        release_date DATE,
        runtime INT NOT NULL,
        phase VARCHAR(255) NOT NULL,
        budget INT NOT NULL,
        revenue INT NOT NULL,
        description TEXT NOT NULL,
        image_path TEXT
    );

CREATE TABLE
    IF NOT EXISTS movie_category (
        movie_id INT,
        category_name VARCHAR(255),
        PRIMARY KEY (movie_id, category_name),
        FOREIGN KEY (movie_id) REFERENCES movie (id),
        FOREIGN KEY (category_name) REFERENCES category (name)
    );

CREATE TABLE
    IF NOT EXISTS movie_cast (
        movie_id INT,
        person_id INT,
        role VARCHAR(255) NOT NULL,
        PRIMARY KEY (movie_id, person_id),
        FOREIGN KEY (movie_id) REFERENCES movie (id),
        FOREIGN KEY (person_id) REFERENCES people (id)
    );

CREATE TABLE
    IF NOT EXISTS movie_crew (
        movie_id INT,
        person_id INT,
        role VARCHAR(255) NOT NULL,
        PRIMARY KEY (movie_id, person_id, role),
        FOREIGN KEY (movie_id) REFERENCES movie (id),
        FOREIGN KEY (person_id) REFERENCES people (id)
    );

CREATE TABLE
    IF NOT EXISTS review (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        data DATE NOT NULL,
        rating INT NOT NULL,
        username VARCHAR(255),
        movie_id INT,
        FOREIGN KEY (username) REFERENCES user (username),
        FOREIGN KEY (movie_id) REFERENCES movie (id)
    );

-- INSERT_START --
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
    );

INSERT INTO
    category (name)
VALUES
    ('Fantascienza'),
    ('Azione'),
    ('Avventura'),
    ('Animazione'),
    ('Famiglia'),
    ('Commedia'),
    ('Thriller'),
    ('Crime'),
    ('Romance'),
    ('televisione film'),
    ('Mistero'),
    ('Storia'),
    ('Dramma'),
    ('Fantasy');

INSERT INTO
    people (id, name, profile_image)
VALUES
    (
        '1',
        'Tom Hardy',
        'd81K0RH8UX7tZj49tZaQhZ9ewH.jpg'
    ),
    (
        '2',
        'Chiwetel Ejiofor',
        'pbnWjBsze67Fbr3gZAP1ZP407ZU.jpg'
    ),
    (
        '3',
        'Juno Temple',
        'wMpZcKp7zaHnmNQooqbve33577Q.jpg'
    ),
    (
        '4',
        'Clark Backo',
        'd24KKFxfoql6PBsBPsejFgzhSlH.jpg'
    ),
    (
        '5',
        'Rhys Ifans',
        '1D670EEsbky3EtO7XLG32A09p92.jpg'
    ),
    (
        '6',
        'Stephen Graham',
        'hRq4Rq8IbLL4nGCu11N5ePESdI6.jpg'
    ),
    (
        '7',
        'Peggy Lu',
        'ng5eaDcOf9kSwIYGNmwF9wEfIHp.jpg'
    ),
    (
        '8',
        'Alanna Ubach',
        'ffyBAEoW3bDgVJQV3GaHsZ9x29W.jpg'
    ),
    (
        '9',
        'Hala Finley',
        'cVLLrES860YANUMzJUo20HUR7TI.jpg'
    ),
    (
        '10',
        '达什·麦克劳德',
        '8WmRoUrM5S7rA0TVSdKGOSh9uq8.jpg'
    ),
    (
        '11',
        'Kelly Marcel',
        'thpdVW7O1975GcA3eNs1H8UIlmd.jpg'
    ),
    (
        '12',
        'Auliʻi Cravalho',
        'vEroqcnM2g6yY7qXDAie7hx2Cyp.jpg'
    ),
    (
        '13',
        'Dwayne Johnson',
        '5QApZVV8FUFlVxQpIK3Ew6cqotq.jpg'
    ),
    (
        '14',
        'Hualālai Chung',
        'x2g5fdHqETY9dZgL4aB0QDP0boR.jpg'
    ),
    (
        '15',
        'Rose Matafeo',
        'zQa39fMjbOTIsovbh1TBTJVlToz.jpg'
    ),
    (
        '16',
        'David Fane',
        'tcozyaTgAa8rRmzc5qeht0loni6.jpg'
    ),
    (
        '17',
        'Awhimai Fraser',
        '276OUDPl2iIsz772HQw3tiz2JN2.jpg'
    ),
    (
        '18',
        'Khaleesi Lambert-Tsuda',
        '3LHXDjy9UijbtR7X2EReX5H57kk.jpg'
    ),
    (
        '19',
        'Temuera Morrison',
        '1ckHDFgKXJ8pazmvLCW7DeOKqA0.jpg'
    ),
    (
        '20',
        'Nicole Scherzinger',
        'sB6TNkTdLCkK6DVd5NlBPtfssyD.jpg'
    ),
    (
        '21',
        'Rachel House',
        'm8D9XlTGfI0ZmauMKtYp5tw8eNi.jpg'
    ),
    (
        '22',
        'David G. Derrick Jr.',
        'j5JOtRua5KduoPsQVix0rwY3jOo.jpg'
    ),
    (
        '23',
        'Jason Hand',
        'gepbkgavGdDXdNbQzdFaxayTpoH.jpg'
    ),
    (
        '24',
        'Dana Ledoux Miller',
        'wKqVtkgOv6iMcv1P0YPxV7UtQS9.jpg'
    ),
    (
        '25',
        'Jared Bush',
        '2gIwj1cnqZIKWaFg0ihmZnuZypR.jpg'
    ),
    (
        '26',
        'Anthony Mackie',
        'kDKaBf5GJuK42N9zKeftokcbMap.jpg'
    ),
    (
        '27',
        'Morena Baccarin',
        'w7azo5rPMzcJE8uyEtu9hiqeliV.jpg'
    ),
    (
        '28',
        'Maddie Hasson',
        'eH6SnpMGDC9CQScmIchSnKkZAbc.jpg'
    ),
    (
        '29',
        'Danny Boyd Jr.',
        'k6ewXmzmCJa71bQmmEifmy11ezt.jpg'
    ),
    (
        '30',
        'Rachel Nicks',
        'wJx6DHJ5S5IGvj61yUUPtKzr9cf.jpg'
    ),
    (
        '31',
        'Shauna Earp',
        'cpAOnH902U7S9uBNzHOB7tOSvRq.jpg'
    ),
    (
        '32',
        'Tyler Grey',
        '3yuB34p1EXx8273HnMrng7kgolA.jpg'
    ),
    ('33', 'Drexel Malkoff', NULL),
    (
        '34',
        'David Malkoff',
        'dVo8W6TR2nF9vdRic8OWizutSEe.jpg'
    ),
    ('35', 'Mike Hickman', NULL),
    (
        '36',
        'George Nolfi',
        'dYr20UCtki5iRg0k2zSKjG36QKy.jpg'
    ),
    ('37', 'Kenny Ryan', NULL),
    ('38', 'Jacob Roman', NULL),
    (
        '39',
        'John Glenn',
        'xDrtvEY2bpdFnRNqW0YNeiCfdv4.jpg'
    ),
    (
        '40',
        'Liam Neeson',
        'sRLev3wJioBgun3ZoeAUFpkLy0D.jpg'
    ),
    (
        '41',
        'Ron Perlman',
        '9riPBfsWpzEzh2y9ucxTW22iakI.jpg'
    ),
    (
        '42',
        'Yolonda Ross',
        'ifu8J2Je89W2O2MwOO8P5GW7fYb.jpg'
    ),
    (
        '43',
        'Frankie Shaw',
        'yY8wTV6PCnkuW333D15yqsdBfYg.jpg'
    ),
    (
        '44',
        'Daniel Diemer',
        'sBMsEkBqQhAQtiCmpBgyZRUns3m.jpg'
    ),
    (
        '45',
        'Javier Molina',
        '40cs8RRYgg9z1CcQApsVLKqoPSE.jpg'
    ),
    (
        '46',
        'Jimmy Gonzales',
        'jEJ5TdOeqOJyopKMEc8w9qDtsuN.jpg'
    ),
    (
        '47',
        'Josh Drennen',
        'fQ6zkA9AbEoA0KFqlyPNQ6eQxjp.jpg'
    ),
    (
        '48',
        'Deanna Tarraza',
        'loZuTtsJmFsMxuaOoa2gY4pV8bA.jpg'
    ),
    (
        '49',
        'Terrence Pulliam',
        'fV5rlcCScCcRxkkEd36fLKJLQDR.jpg'
    ),
    (
        '50',
        'Hans Petter Moland',
        'A7llCvBlfl5UGmiuunqPT2YhpcG.jpg'
    ),
    ('51', 'Tony Gayton', NULL),
    (
        '52',
        'Noah Beck',
        '67BJ1o03CpMbRj40jjiAG13TqPe.jpg'
    ),
    (
        '53',
        'Siena Agudong',
        'mxHTjQOCPJ8pcxp5tK5lG40u7o3.jpg'
    ),
    (
        '54',
        'Drew Ray Tanner',
        'hEDc4uUmrx0pE93bwYoUWE6jvSB.jpg'
    ),
    (
        '55',
        'James Van Der Beek',
        '5CzRqYzRMNevIu6rB8gUFKpsKZL.jpg'
    ),
    (
        '56',
        'Deborah Cox',
        '1gP8DmE0Tp2TrbHaE6WUu692pdT.jpg'
    ),
    ('57', 'Asia Lizardo', NULL),
    (
        '58',
        'Jake Foy',
        'zGdd4rmmyab9YXcsI6un06kzbJ9.jpg'
    ),
    (
        '59',
        'Jason Fernandes',
        'uNO3t6ePWj8ELXTXOMNmvrFRPpm.jpg'
    ),
    (
        '60',
        'Kendall Cross',
        '97d4hAGHcgExV1QR3nTXdChu7Ll.jpg'
    ),
    (
        '61',
        'Josh Zaharia',
        'jiPVcr2zLOwapACbkxPl3YzccrM.jpg'
    ),
    (
        '62',
        'Justin Wu',
        'gLFkaiQls6lHJRuSS1KKNCksYHT.jpg'
    ),
    (
        '63',
        'Troy Baker',
        'k3eNrfCd6tEH7RKDwn6ZUpmjMnf.jpg'
    ),
    (
        '64',
        'Adrienne Barbeau',
        'vlpRCDwne79zaLE0H14bs9NwX8p.jpg'
    ),
    (
        '65',
        'Michael Cerveris',
        'vahLGniF2yY8I11ZtzhhhXVTbDc.jpg'
    ),
    (
        '66',
        'Zehra Fazal',
        'gEL0hqxcckQth8ogIEByxirXa7f.jpg'
    ),
    (
        '67',
        'Phil Fondacaro',
        'vJbPx90IzOdE6L1maYu1HIJayjG.jpg'
    ),
    (
        '68',
        'Grey DeLisle',
        'vrUHaXe1pG56yZkgH7Hs3LGRLTT.jpg'
    ),
    (
        '69',
        'John Marshall Jones',
        '8aQ8qxVOeUx23Jw89ouj9gOwy1W.jpg'
    ),
    ('70', 'Max Koch', NULL),
    (
        '71',
        'Phil LaMarr',
        'l5w0qABfsFBxjfWNnpFiaXnh4Nm.jpg'
    ),
    (
        '72',
        'Yuri Lowenthal',
        'aLgXm0kbbIC8I5OBmnsK8TgVKwy.jpg'
    ),
    (
        '73',
        'Brandon Vietti',
        '3aQCKuGIGzs7yJiD3z12EatL7P8.jpg'
    ),
    (
        '74',
        'J. Michael Straczynski',
        'ezbudNhoJcgIGZdx74D6kPmu1Yp.jpg'
    ),
    (
        '75',
        'Sylvester Stallone',
        'gn3pDWthJqR0VDYGViGD3048og7.jpg'
    ),
    (
        '76',
        'Jason Patric',
        'iv21ZjE90f9QNiNONKF9Lp6ZD68.jpg'
    ),
    (
        '77',
        'Josh Wiggins',
        '8ibSjOrx62IRfcBVssu6q3lzlQp.jpg'
    ),
    (
        '78',
        'Dash Mihok',
        'jnruNUJv57nNtO66SR3oJ5tQuM5.jpg'
    ),
    (
        '79',
        'Erin Ownbey',
        'oQTx5FXse1SLLXLHaqwhu0ZwPVn.jpg'
    ),
    (
        '80',
        'Laney Stiebing',
        'eAB8foYMD8o7b5yqIlQRJUhdkCr.jpg'
    ),
    (
        '81',
        'Jeff Chase',
        'Ahj74X5BioIUDRhdWD8i43j0pXM.jpg'
    ),
    (
        '82',
        'Josh Whites',
        'pJAN0zyuX7lwW97uYDmAe081DHA.jpg'
    ),
    (
        '83',
        'Joel Cohen',
        '26khzGvkwfTdR3f6rNAtQ39Z8OF.jpg'
    ),
    (
        '84',
        'Beau Bommarito',
        'y4Bnur5patiUtPXtZhinlKJNKzk.jpg'
    ),
    (
        '85',
        'Justin Routt',
        'zNLexOeUwf5F37ZkLvhkfZfabF6.jpg'
    ),
    ('86', 'Adrian Speckert', NULL),
    ('87', 'Cory Todd Hughes', NULL),
    (
        '88',
        'Paul Mescal',
        'pKqoQEWFygvEJeiBfLmmO3vM5Fs.jpg'
    ),
    (
        '89',
        'Denzel Washington',
        '9Iyt3wbsla5bM6IzbICDVnBhkER.jpg'
    ),
    (
        '90',
        'Pedro Pascal',
        '9VYK7oxcqhjd5LAH6ZFJ3XzOlID.jpg'
    ),
    (
        '91',
        'Connie Nielsen',
        'lvQypTfeH2Gn2PTbzq6XkT2PLmn.jpg'
    ),
    (
        '92',
        'Joseph Quinn',
        'zshhuioZaH8S5ZKdMcojzWi1ntl.jpg'
    ),
    (
        '93',
        'Fred Hechinger',
        '43uR4nynhrMZOqhN5og0xBt4QpV.jpg'
    ),
    (
        '94',
        'Lior Raz',
        'bl3KLFUQ4Q0zC9lCU4qP1Jf4qHS.jpg'
    ),
    (
        '95',
        'Derek Jacobi',
        'htc4eCYmNlVotcu8AFTbDiLBzsJ.jpg'
    ),
    (
        '96',
        'Peter Mensah',
        't94TFc6f71AUmZFqdaQfjr7LTRp.jpg'
    ),
    (
        '97',
        'Matt Lucas',
        '2OhGLJqiknaWlbTkG2KDwT935km.jpg'
    ),
    (
        '98',
        'Ridley Scott',
        'zABJmN9opmqD4orWl3KSdCaSo7Q.jpg'
    ),
    (
        '99',
        'Lupita Nyong''o',
        'y40Wu1T742kynOqtwXASc5Qgm49.jpg'
    ),
    (
        '100',
        'Kit Connor',
        'gCIdbnV9D3lzTaOB0YtuKDz6Nt0.jpg'
    ),
    (
        '101',
        'Bill Nighy',
        'ixFI2YCGNGJfwlpI8iyhvVZRg8C.jpg'
    ),
    (
        '102',
        'Stephanie Hsu',
        '8gb3lfIHKQAGOQyeC4ynQPsCiHr.jpg'
    ),
    (
        '103',
        'Matt Berry',
        '7a1sWg1W7ZmNF8bLSnyAlJgQQGD.jpg'
    ),
    (
        '104',
        'Ving Rhames',
        '4gpLVNKPZlVucc4fT2fSZ7DksTK.jpg'
    ),
    (
        '105',
        'Mark Hamill',
        '2ZulC2Ccq1yv3pemusks6Zlfy2s.jpg'
    ),
    (
        '106',
        'Catherine O''Hara',
        'cMBxHeztNVc8YXKcj084Mdd3f3U.jpg'
    ),
    (
        '107',
        'Boone Storm',
        'kn5JY9C0oHq09MFLB5vFpoqgop7.jpg'
    ),
    (
        '108',
        'Chris Sanders',
        '6CtrIOCxggJ5eIAWeFQqd4Hs9FP.jpg'
    ),
    (
        '109',
        'Cynthia Erivo',
        '4cpvSGrJg2hwddkTPMyDKj0c3O.jpg'
    ),
    (
        '110',
        'Ariana Grande',
        'cslFyOh3sTWDeWXgsxmjJ1uqE0P.jpg'
    ),
    (
        '111',
        'Jonathan Bailey',
        'mZNzekZo8eaHMuXKgDTNLp0EvYM.jpg'
    ),
    (
        '112',
        'Michelle Yeoh',
        'klATAW2KEfV2Glm5uhWAhJWt2ip.jpg'
    ),
    (
        '113',
        'Jeff Goldblum',
        'o3PahuK7OmCI0RAQUq38CUBWYZ9.jpg'
    ),
    (
        '114',
        'Marissa Bode',
        'uqifog9p62A6dW9T4yivfWnSFXQ.jpg'
    ),
    (
        '115',
        'Ethan Slater',
        'xIgqyrM78FPt7Pb2Vv3IvJcnOWS.jpg'
    ),
    (
        '116',
        'Bowen Yang',
        'lrebxaz4BGJucBW79cakZ0HsSa1.jpg'
    ),
    (
        '117',
        'Bronwyn James',
        'clD8t9Oqu8mCTLsqjSTNWi6sSZC.jpg'
    ),
    (
        '118',
        'Peter Dinklage',
        '9CAd7wr8QZyIN0E7nm8v1B6WkGn.jpg'
    ),
    (
        '119',
        'Jon M. Chu',
        '85kOxm7w4nGRwyhquj9wtUM8KUW.jpg'
    );

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
        'Venom: The Last Dance',
        'Venom: The Last Dance',
        'en',
        '2024-10-22',
        '109',
        'Rilasciato',
        '120000000',
        '468513700',
        'In Venom: The Last Dance, Tom Hardy torna a interpretare Venom, uno dei più celebri e complessi personaggi della Marvel. Nel film conclusivo della trilogia, Eddie e Venom sono in fuga. Mentre il cerchio si stringe attorno a loro, i due protagonisti sono costretti a prendere una decisione sconvolgente che farà calare il sipario sul loro “ultimo ballo”.',
        'ukaWgY06OvxuLIU6j7E086FN8RX.jpg'
    ),
    (
        '2',
        'Oceania 2',
        'Moana 2',
        'en',
        '2024-11-21',
        '100',
        'Rilasciato',
        '150000000',
        '465055655',
        'A tre anni dal suo primo viaggio, Vaiana è impegnata in un lungo viaggio alla ricerca di persone oltre le coste di Motunui. Assieme a Maui e a un nuovissimo equipaggio di improbabili marittimi, Vaiana si imbarca alla volta dei lontani mari dell''Oceania in acque pericolose per un''avventura diversa da qualsiasi cosa abbia mai affrontato.',
        'dPnRomNzww9G7dgS84f6HSevVLc.jpg'
    ),
    (
        '3',
        'Elevation',
        'Elevation',
        'en',
        '2024-11-07',
        '91',
        'Rilasciato',
        '0',
        '0',
        'Un padre single e due donne escono dalla sicurezza delle loro case per affrontare creature mostruose e salvare la vita di un ragazzino.',
        'uQhYBxOVFU6s9agD49FnGHwJqG5.jpg'
    ),
    (
        '4',
        'Absolution',
        'Absolution',
        'en',
        '2024-10-31',
        '112',
        'Rilasciato',
        '30000000',
        '0',
        'Informazione non disponibile',
        'cNtAslrDhk1i3IOZ16vF7df6lMy.jpg'
    ),
    (
        '5',
        'Sidelined: The QB and Me',
        'Sidelined: The QB and Me',
        'en',
        '2024-11-29',
        '99',
        'Rilasciato',
        '0',
        '0',
        'Informazione non disponibile',
        'f8H9sLin46B7ka4DEqjemGuiCOB.jpg'
    ),
    (
        '6',
        'Watchmen: Chapter II',
        'Watchmen: Chapter II',
        'en',
        '2024-11-25',
        '89',
        'Rilasciato',
        '0',
        '0',
        'Sospettosi degli eventi che hanno intrappolato i loro ex colleghi, Nite Owl e Silk Spectre vengono spinti a uscire dal pensionamento per indagare. Mentre sono alle prese con l''etica personale, i demoni interiori e una società rivolta contro di loro, corrono contro il tempo per scoprire un complotto sempre più profondo che potrebbe scatenare una guerra nucleare globale.',
        '4rBObJFpiWJOG7aIlRrOUniAkBs.jpg'
    ),
    (
        '7',
        'Armor',
        'Armor',
        'en',
        '2024-10-30',
        '89',
        'Rilasciato',
        '11500000',
        '0',
        'La guardia di sicurezza del furgone blindato James Brody sta lavorando con suo figlio Casey per trasportare milioni di dollari tra le banche quando una squadra di ladri guidata da Rook orchestra un''acquisizione del loro furgone per impossessarsi delle ricchezze. Dopo un violento inseguimento in auto, Rook circonda presto il furgone blindato e James e Casey si ritrovano alle strette su un ponte decrepito.',
        'pnXLFioDeftqjlCVlRmXvIdMsdP.jpg'
    ),
    (
        '8',
        'Il gladiatore II',
        'Gladiator II',
        'en',
        '2024-11-05',
        '148',
        'Rilasciato',
        '310000000',
        '329092895',
        'Anni dopo aver assistito alla tragica morte del venerato eroe Massimo per mano del suo perfido zio, Lucio (Paul Mescal) si trova costretto a combattere nel Colosseo dopo che la sua patria viene conquistata da parte di due tirannici imperatori, che ora governano Roma.  Con il cuoreardente di rabbia e il destino dell''Impero appeso a un filo, Lucio deve affrontare pericoli e nemici, riscoprendo nel suo passato la forza e l''onore necessari per riportare la gloria di Roma al suo popolo. Preparatevi per un viaggio epico di coraggio e vendetta nella sanguinosa arena del Colosseo.',
        'v1ffLD5yQqpJW1VXYy1n6sp5wgX.jpg'
    ),
    (
        '9',
        'Il robot selvaggio',
        'The Wild Robot',
        'en',
        '2024-09-08',
        '102',
        'Rilasciato',
        '78000000',
        '321836235',
        'L''epica avventura segue il viaggio di un robot - l''unità ROZZUM 7134, abbreviato ""Roz"" - che dopo un naufragio si ritrova su un''isola disabitata dove dovrà imparare ad adattarsi all’ostile ambiente circostante, costruendo gradualmente relazioni con gli altri animali dell’isola e adottando un''ochetta orfana.',
        'j48mq3yF4Ghi8CclXqqZMWVByk1.jpg'
    ),
    (
        '10',
        'Wicked',
        'Wicked',
        'en',
        '2024-11-20',
        '160',
        'Rilasciato',
        '150000000',
        '382799270',
        'La storia ruota attorno all’amicizia tra le due streghe. Elphaba, soprannominata ingiustamente Malvagia Strega dell’Ovest, è in realtà una donna rivoluzionaria e anticonformista con la pelle verde che sfida lo strapotere del Mago di Oz, imbroglione dalle mire imperialiste. Glinda è invece la Strega Buona del Nord, amica di Elphaba dai tempi dell’Università di Shiz.',
        '3al2ouBCaZN5UcCNr8HKSiNji28.jpg'
    );

INSERT INTO
    movie_category (movie_id, category_name)
VALUES
    ('1', 'Fantascienza'),
    ('1', 'Azione'),
    ('1', 'Avventura'),
    ('2', 'Animazione'),
    ('2', 'Avventura'),
    ('2', 'Famiglia'),
    ('2', 'Commedia'),
    ('3', 'Azione'),
    ('3', 'Fantascienza'),
    ('3', 'Thriller'),
    ('4', 'Azione'),
    ('4', 'Crime'),
    ('4', 'Thriller'),
    ('5', 'Commedia'),
    ('5', 'Romance'),
    ('5', 'televisione film'),
    ('6', 'Animazione'),
    ('6', 'Mistero'),
    ('6', 'Fantascienza'),
    ('6', 'Azione'),
    ('7', 'Azione'),
    ('7', 'Crime'),
    ('7', 'Thriller'),
    ('8', 'Azione'),
    ('8', 'Avventura'),
    ('8', 'Storia'),
    ('9', 'Animazione'),
    ('9', 'Azione'),
    ('9', 'Fantascienza'),
    ('9', 'Famiglia'),
    ('10', 'Dramma'),
    ('10', 'Fantasy'),
    ('10', 'Romance');

INSERT INTO
    movie_cast (movie_id, person_id, role)
VALUES
    ('1', '1', 'Eddie Brock / Venom'),
    ('1', '2', 'General Rex Strickland'),
    ('1', '3', 'Dr. Teddy Paine'),
    ('1', '4', 'Sadie Christmas'),
    ('1', '5', 'Martin Moon'),
    ('1', '6', 'Detective Mulligan'),
    ('1', '7', 'Mrs. Chen'),
    ('1', '8', 'Nova Moon'),
    ('1', '9', 'Echo Moon'),
    ('1', '10', 'Leaf Moon'),
    ('2', '12', 'Moana (voice)'),
    ('2', '13', 'Maui (voice)'),
    ('2', '14', 'Moni (voice)'),
    ('2', '15', 'Loto (voice)'),
    ('2', '16', 'Kele (voice)'),
    ('2', '17', 'Matangi (voice)'),
    ('2', '18', 'Simea (voice)'),
    ('2', '19', 'Chief Tui (voice)'),
    ('2', '20', 'Sina (voice)'),
    ('2', '21', 'Gramma Tala (voice)'),
    ('3', '26', 'Will'),
    ('3', '27', 'Nina'),
    ('3', '28', 'Katie'),
    ('3', '29', 'Hunter'),
    ('3', '30', 'Tara'),
    ('3', '31', 'Hannah'),
    ('3', '32', 'Tim'),
    ('3', '33', 'Nina’s Son (uncredited)'),
    ('3', '34', 'Nina’s Husband (uncredited)'),
    ('3', '35', 'Refugee (uncredited)'),
    ('4', '40', 'Thug'),
    ('4', '41', 'Charlie Conner'),
    ('4', '42', 'Woman'),
    ('4', '43', 'Daisy'),
    ('4', '44', 'Kyle Conner'),
    ('4', '45', 'Gamberro'),
    ('4', '46', 'Diego Machado'),
    ('4', '47', 'Thug''s Dad'),
    ('4', '48', 'Araceli'),
    ('4', '49', 'Dre'),
    ('5', '52', 'Drayton Lahey'),
    ('5', '53', 'Dallas Bryan'),
    ('5', '54', 'Nathan Bryan'),
    ('5', '55', 'Leroy Lahey'),
    ('5', '56', 'Miss Alicia'),
    ('5', '57', 'Gabby'),
    ('5', '58', 'Mr. Justin Douglas'),
    ('5', '59', 'Josh Lahey'),
    ('5', '60', 'Ellie Lahey'),
    ('5', '61', 'Chase'),
    (
        '6',
        '63',
        'Adrian Veidt / Ozymandias / Derf / Laurence Schexnayder / Gerald Grice / News Announcer (voice)'
    ),
    (
        '6',
        '64',
        'Sally Jupiter / Police Dispatcher (voice)'
    ),
    (
        '6',
        '65',
        'Dr. Manhattan / Prison Guard #2 (voice)'
    ),
    ('6', '66', 'Hira Manish / Newscaster (voice)'),
    ('6', '67', 'Tom Ryan / Big Figure (voice)'),
    (
        '6',
        '68',
        'Female Citizen #1 / Advisor #1 (voice)'
    ),
    (
        '6',
        '69',
        'Otis / Malcolm Long / General #1 (voice)'
    ),
    (
        '6',
        '70',
        'Bernard / President Nixon / Lawrence Andrews (voice)'
    ),
    (
        '6',
        '71',
        'Comic Book Narrator / Bernie / Male Citizen #1 (voice)'
    ),
    (
        '6',
        '72',
        'Seymour / Knot Top #2 / Bully #1 (voice)'
    ),
    ('7', '75', 'Rook'),
    ('7', '76', 'James Brody'),
    ('7', '77', 'Casey Brody'),
    ('7', '78', 'Smoke'),
    ('7', '79', 'Trisha Brody'),
    ('7', '80', 'Sara Brody'),
    ('7', '81', 'Viper'),
    ('7', '82', 'Echo'),
    ('7', '83', 'Bank Manager'),
    ('7', '84', 'Speeding Driver'),
    ('8', '88', 'Lucius Verus / Hanno'),
    ('8', '89', 'Macrinus'),
    ('8', '90', 'General Acacius'),
    ('8', '91', 'Lucilla'),
    ('8', '92', 'Emperor Geta'),
    ('8', '93', 'Emperor Caracalla'),
    ('8', '94', 'Viggo'),
    ('8', '95', 'Gracchus'),
    ('8', '96', 'Jugurtha'),
    ('8', '97', 'Master of Ceremonies'),
    ('9', '99', 'Roz / Rummage (voice)'),
    ('9', '90', 'Fink (voice)'),
    ('9', '100', 'Brightbill (voice)'),
    ('9', '101', 'Longneck (voice)'),
    ('9', '102', 'Vontra (voice)'),
    ('9', '103', 'Paddler (voice)'),
    ('9', '104', 'Thunderbolt (voice)'),
    ('9', '105', 'Thorn (voice)'),
    ('9', '106', 'Pinktail (voice)'),
    ('9', '107', 'Baby Brightbill (voice)'),
    ('10', '109', 'Elphaba'),
    ('10', '110', 'Galinda / Glinda'),
    ('10', '111', 'Fiyero'),
    ('10', '112', 'Madame Morrible'),
    ('10', '113', 'The Wonderful Wizard of Oz'),
    ('10', '114', 'Nessarose'),
    ('10', '115', 'Boq'),
    ('10', '116', 'Pfannee'),
    ('10', '117', 'ShenShen'),
    ('10', '118', 'Dr. Dillamond (voice)');

INSERT INTO
    movie_crew (movie_id, person_id, role)
VALUES
    ('1', '11', 'Director'),
    ('2', '22', 'Director'),
    ('2', '23', 'Director'),
    ('2', '24', 'Director'),
    ('2', '24', 'Writer'),
    ('2', '25', 'Writer'),
    ('3', '36', 'Director'),
    ('3', '37', 'Writer'),
    ('3', '38', 'Writer'),
    ('3', '39', 'Writer'),
    ('4', '50', 'Director'),
    ('4', '51', 'Writer'),
    ('5', '62', 'Director'),
    ('6', '73', 'Director'),
    ('6', '74', 'Writer'),
    ('7', '85', 'Director'),
    ('7', '86', 'Writer'),
    ('7', '87', 'Writer'),
    ('8', '98', 'Director'),
    ('9', '108', 'Director'),
    ('10', '119', 'Director');

-- INSERT_END --
COMMIT;