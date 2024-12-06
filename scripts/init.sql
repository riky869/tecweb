BEGIN;

DROP DATABASE IF EXISTS tecweb;

CREATE DATABASE IF NOT EXISTS tecweb;

USE tecweb;

DROP TABLE IF EXISTS review,
crew,
cast,
movie_category,
movie,
people,
category,
user;

CREATE TABLE IF NOT EXISTS user (
    username VARCHAR(255) PRIMARY KEY,
    password TEXT NOT NULL,
    name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    is_admin BOOLEAN NOT NULL
);

CREATE TABLE IF NOT EXISTS category (
    name VARCHAR(255) PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS people (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name TEXT NOT NULL,
    profile_image TEXT
);

CREATE TABLE IF NOT EXISTS movie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name TEXT NOT NULL,
    original_name TEXT NOT NULL,
    original_language VARCHAR(255) NOT NULL,
    release_date DATE,
    runtime INT NOT NULL,
    phase VARCHAR(255) NOT NULL,
    budget INT NOT NULL,
    revenue INT NOT NULL,
    description TEXT,
    image_path TEXT
);

CREATE TABLE IF NOT EXISTS movie_category (
    movie_id INT,
    category_name VARCHAR(255),
    PRIMARY KEY (movie_id, category_name),
    FOREIGN KEY (movie_id) REFERENCES movie(id),
    FOREIGN KEY (category_name) REFERENCES category(name)
);

CREATE TABLE IF NOT EXISTS cast (
    movie_id INT,
    person_id INT,
    role VARCHAR(255) NOT NULL,
    PRIMARY KEY (movie_id, person_id),
    FOREIGN KEY (movie_id) REFERENCES movie(id),
    FOREIGN KEY (person_id) REFERENCES people(id)
);

CREATE TABLE IF NOT EXISTS crew (
    movie_id INT,
    person_id INT,
    role VARCHAR(255) NOT NULL,
    PRIMARY KEY (movie_id, person_id),
    FOREIGN KEY (movie_id) REFERENCES movie(id),
    FOREIGN KEY (person_id) REFERENCES people(id)
);

CREATE TABLE IF NOT EXISTS review (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    data DATE NOT NULL,
    rating INT NOT NULL,
    username VARCHAR(255),
    movie_id INT,
    FOREIGN KEY (username) REFERENCES user(username),
    FOREIGN KEY (movie_id) REFERENCES movie(id)
);

INSERT INTO
    user (username, password, name, last_name, is_admin)
VALUES
    ('admin', 'admin', 'mario', 'rossi', true),
    ('user', 'user', 'luca', 'agostino', false);

INSERT INTO category (name) VALUES ('Animazione');
INSERT INTO category (name) VALUES ('Avventura');
INSERT INTO category (name) VALUES ('Famiglia');
INSERT INTO category (name) VALUES ('Commedia');
INSERT INTO category (name) VALUES ('Fantascienza');
INSERT INTO category (name) VALUES ('Azione');
INSERT INTO category (name) VALUES ('Thriller');
INSERT INTO category (name) VALUES ('Crime');
INSERT INTO category (name) VALUES ('Romance');
INSERT INTO category (name) VALUES ('televisione film');
INSERT INTO category (name) VALUES ('Storia');
INSERT INTO category (name) VALUES ('Horror');
INSERT INTO category (name) VALUES ('Mistero');
INSERT INTO category (name) VALUES ('Dramma');
INSERT INTO category (name) VALUES ('Fantasy');
INSERT INTO people (id, name, profile_image) VALUES ('1', 'Auliʻi Cravalho', 'vEroqcnM2g6yY7qXDAie7hx2Cyp.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('2', 'Dwayne Johnson', '5QApZVV8FUFlVxQpIK3Ew6cqotq.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('3', 'Hualālai Chung', 'x2g5fdHqETY9dZgL4aB0QDP0boR.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('4', 'Rose Matafeo', 'zQa39fMjbOTIsovbh1TBTJVlToz.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('5', 'David Fane', 'tcozyaTgAa8rRmzc5qeht0loni6.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('6', 'Awhimai Fraser', '276OUDPl2iIsz772HQw3tiz2JN2.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('7', 'Khaleesi Lambert-Tsuda', '3LHXDjy9UijbtR7X2EReX5H57kk.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('8', 'Temuera Morrison', '1ckHDFgKXJ8pazmvLCW7DeOKqA0.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('9', 'Nicole Scherzinger', 'sB6TNkTdLCkK6DVd5NlBPtfssyD.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('10', 'Rachel House', 'm8D9XlTGfI0ZmauMKtYp5tw8eNi.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('11', 'David G. Derrick Jr.', 'j5JOtRua5KduoPsQVix0rwY3jOo.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('12', 'Jason Hand', 'gepbkgavGdDXdNbQzdFaxayTpoH.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('13', 'Dana Ledoux Miller', 'wKqVtkgOv6iMcv1P0YPxV7UtQS9.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('14', 'Jared Bush', '2gIwj1cnqZIKWaFg0ihmZnuZypR.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('15', 'Tom Hardy', 'd81K0RH8UX7tZj49tZaQhZ9ewH.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('16', 'Chiwetel Ejiofor', 'pbnWjBsze67Fbr3gZAP1ZP407ZU.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('17', 'Juno Temple', 'wMpZcKp7zaHnmNQooqbve33577Q.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('18', 'Clark Backo', 'd24KKFxfoql6PBsBPsejFgzhSlH.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('19', 'Rhys Ifans', '1D670EEsbky3EtO7XLG32A09p92.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('20', 'Stephen Graham', 'hRq4Rq8IbLL4nGCu11N5ePESdI6.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('21', 'Peggy Lu', 'ng5eaDcOf9kSwIYGNmwF9wEfIHp.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('22', 'Alanna Ubach', 'ffyBAEoW3bDgVJQV3GaHsZ9x29W.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('23', 'Hala Finley', 'cVLLrES860YANUMzJUo20HUR7TI.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('24', '达什·麦克劳德', '8WmRoUrM5S7rA0TVSdKGOSh9uq8.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('25', 'Kelly Marcel', 'thpdVW7O1975GcA3eNs1H8UIlmd.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('26', 'Anthony Mackie', 'kDKaBf5GJuK42N9zKeftokcbMap.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('27', 'Morena Baccarin', 'kBSKKaOtsqIzZPhtEeHfCBmhWl9.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('28', 'Maddie Hasson', 'atEIKsp1uyccPRChGkpGTWI1i1q.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('29', 'Danny Boyd Jr.', 'k6ewXmzmCJa71bQmmEifmy11ezt.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('30', 'Rachel Nicks', 'wJx6DHJ5S5IGvj61yUUPtKzr9cf.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('31', 'Shauna Earp', 'cpAOnH902U7S9uBNzHOB7tOSvRq.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('32', 'Tyler Grey', '3yuB34p1EXx8273HnMrng7kgolA.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('33', 'Drexel Malkoff', NULL);
INSERT INTO people (id, name, profile_image) VALUES ('34', 'David Malkoff', 'dVo8W6TR2nF9vdRic8OWizutSEe.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('35', 'Mike Hickman', NULL);
INSERT INTO people (id, name, profile_image) VALUES ('36', 'George Nolfi', 'dYr20UCtki5iRg0k2zSKjG36QKy.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('37', 'Kenny Ryan', NULL);
INSERT INTO people (id, name, profile_image) VALUES ('38', 'Jacob Roman', NULL);
INSERT INTO people (id, name, profile_image) VALUES ('39', 'John Glenn', 'xDrtvEY2bpdFnRNqW0YNeiCfdv4.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('40', 'Liam Neeson', 'sRLev3wJioBgun3ZoeAUFpkLy0D.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('41', 'Ron Perlman', '9riPBfsWpzEzh2y9ucxTW22iakI.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('42', 'Yolonda Ross', 'ifu8J2Je89W2O2MwOO8P5GW7fYb.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('43', 'Frankie Shaw', 'yY8wTV6PCnkuW333D15yqsdBfYg.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('44', 'Daniel Diemer', 'sBMsEkBqQhAQtiCmpBgyZRUns3m.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('45', 'Javier Molina', '40cs8RRYgg9z1CcQApsVLKqoPSE.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('46', 'Jimmy Gonzales', 'jEJ5TdOeqOJyopKMEc8w9qDtsuN.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('47', 'Josh Drennen', 'fQ6zkA9AbEoA0KFqlyPNQ6eQxjp.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('48', 'Deanna Tarraza', 'loZuTtsJmFsMxuaOoa2gY4pV8bA.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('49', 'Terrence Pulliam', 'fV5rlcCScCcRxkkEd36fLKJLQDR.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('50', 'Hans Petter Moland', 'A7llCvBlfl5UGmiuunqPT2YhpcG.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('51', 'Tony Gayton', NULL);
INSERT INTO people (id, name, profile_image) VALUES ('52', 'Sylvester Stallone', 'gn3pDWthJqR0VDYGViGD3048og7.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('53', 'Jason Patric', 'iv21ZjE90f9QNiNONKF9Lp6ZD68.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('54', 'Josh Wiggins', '8ibSjOrx62IRfcBVssu6q3lzlQp.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('55', 'Dash Mihok', 'jnruNUJv57nNtO66SR3oJ5tQuM5.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('56', 'Erin Ownbey', 'oQTx5FXse1SLLXLHaqwhu0ZwPVn.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('57', 'Laney Stiebing', 'eAB8foYMD8o7b5yqIlQRJUhdkCr.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('58', 'Jeff Chase', 'Ahj74X5BioIUDRhdWD8i43j0pXM.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('59', 'Josh Whites', 'pJAN0zyuX7lwW97uYDmAe081DHA.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('60', 'Joel Cohen', '26khzGvkwfTdR3f6rNAtQ39Z8OF.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('61', 'Beau Bommarito', 'y4Bnur5patiUtPXtZhinlKJNKzk.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('62', 'Justin Routt', 'zNLexOeUwf5F37ZkLvhkfZfabF6.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('63', 'Adrian Speckert', NULL);
INSERT INTO people (id, name, profile_image) VALUES ('64', 'Cory Todd Hughes', NULL);
INSERT INTO people (id, name, profile_image) VALUES ('65', 'Noah Beck', '67BJ1o03CpMbRj40jjiAG13TqPe.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('66', 'Siena Agudong', 'mxHTjQOCPJ8pcxp5tK5lG40u7o3.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('67', 'Drew Ray Tanner', 'hEDc4uUmrx0pE93bwYoUWE6jvSB.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('68', 'James Van Der Beek', '5CzRqYzRMNevIu6rB8gUFKpsKZL.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('69', 'Deborah Cox', '1gP8DmE0Tp2TrbHaE6WUu692pdT.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('70', 'Asia Lizardo', NULL);
INSERT INTO people (id, name, profile_image) VALUES ('71', 'Jake Foy', 'zGdd4rmmyab9YXcsI6un06kzbJ9.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('72', 'Jason Fernandes', 'uNO3t6ePWj8ELXTXOMNmvrFRPpm.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('73', 'Kendall Cross', '97d4hAGHcgExV1QR3nTXdChu7Ll.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('74', 'Josh Zaharia', 'jiPVcr2zLOwapACbkxPl3YzccrM.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('75', 'Justin Wu', 'gLFkaiQls6lHJRuSS1KKNCksYHT.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('76', 'Paul Mescal', 'pKqoQEWFygvEJeiBfLmmO3vM5Fs.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('77', 'Denzel Washington', '9Iyt3wbsla5bM6IzbICDVnBhkER.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('78', 'Pedro Pascal', '9VYK7oxcqhjd5LAH6ZFJ3XzOlID.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('79', 'Connie Nielsen', 'lvQypTfeH2Gn2PTbzq6XkT2PLmn.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('80', 'Joseph Quinn', 'zshhuioZaH8S5ZKdMcojzWi1ntl.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('81', 'Fred Hechinger', '43uR4nynhrMZOqhN5og0xBt4QpV.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('82', 'Lior Raz', 'bl3KLFUQ4Q0zC9lCU4qP1Jf4qHS.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('83', 'Derek Jacobi', 'htc4eCYmNlVotcu8AFTbDiLBzsJ.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('84', 'Peter Mensah', 't94TFc6f71AUmZFqdaQfjr7LTRp.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('85', 'Matt Lucas', '2OhGLJqiknaWlbTkG2KDwT935km.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('86', 'Ridley Scott', 'zABJmN9opmqD4orWl3KSdCaSo7Q.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('87', 'Lupita Nyong''o', 'y40Wu1T742kynOqtwXASc5Qgm49.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('88', 'Kit Connor', 'gCIdbnV9D3lzTaOB0YtuKDz6Nt0.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('89', 'Bill Nighy', 'ixFI2YCGNGJfwlpI8iyhvVZRg8C.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('90', 'Stephanie Hsu', '8gb3lfIHKQAGOQyeC4ynQPsCiHr.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('91', 'Matt Berry', '7a1sWg1W7ZmNF8bLSnyAlJgQQGD.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('92', 'Ving Rhames', '4gpLVNKPZlVucc4fT2fSZ7DksTK.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('93', 'Mark Hamill', '2ZulC2Ccq1yv3pemusks6Zlfy2s.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('94', 'Catherine O''Hara', 'cMBxHeztNVc8YXKcj084Mdd3f3U.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('95', 'Boone Storm', NULL);
INSERT INTO people (id, name, profile_image) VALUES ('96', 'Chris Sanders', '6CtrIOCxggJ5eIAWeFQqd4Hs9FP.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('97', 'Naomi Scott', 'nNqqgP2yF1jkLZq9ndYcXOzTD2G.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('98', 'Rosemarie DeWitt', '44sxIdGtYN24R14OmnZbCpcd8J8.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('99', 'Lukas Gage', 'j7Zub5J9PgCnsfgEC5QCr160JtH.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('100', 'Miles Gutierrez-Riley', '22JmiXEKoIHTKAdZaxOiS5wVHnM.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('101', 'Peter Jacobson', 'pGi9CnzEG4cLa2viUP89yvlPCyR.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('102', 'Ray Nicholson', 'f0MRbGIyTEJLJgHedJS8pRFhGn4.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('103', 'Dylan Gelula', 'nqXd0gVNlma8knaykJh5ArXSYqy.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('104', 'Raúl Castillo', 'mNGyFYRTbxjgUwsDKCdohm6o9g.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('105', 'Kyle Gallner', 'wfdywrw6K3ti1uW1IYDWbUtU8se.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('106', 'Drew Barrymore', '9xMu2GLC5otUcC11sEWC5aEAERQ.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('107', 'Parker Finn', 'lw1I0voNLS2llYTlDgd6qZzAMZ6.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('108', 'Cynthia Erivo', '4cpvSGrJg2hwddkTPMyDKj0c3O.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('109', 'Ariana Grande', 'cslFyOh3sTWDeWXgsxmjJ1uqE0P.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('110', 'Jonathan Bailey', 'mZNzekZo8eaHMuXKgDTNLp0EvYM.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('111', 'Michelle Yeoh', 'v74Mkt4dml2LhUv7IfPvBfZFTrV.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('112', 'Jeff Goldblum', 'o3PahuK7OmCI0RAQUq38CUBWYZ9.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('113', 'Marissa Bode', '3ZoBNPpXsM35HrGMkRxU9Ez5YR0.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('114', 'Ethan Slater', 'xIgqyrM78FPt7Pb2Vv3IvJcnOWS.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('115', 'Bowen Yang', 'lrebxaz4BGJucBW79cakZ0HsSa1.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('116', 'Bronwyn James', 'clD8t9Oqu8mCTLsqjSTNWi6sSZC.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('117', 'Peter Dinklage', '9CAd7wr8QZyIN0E7nm8v1B6WkGn.jpg');
INSERT INTO people (id, name, profile_image) VALUES ('118', 'Jon M. Chu', '85kOxm7w4nGRwyhquj9wtUM8KUW.jpg');
INSERT INTO movie (id, name, original_name, original_language, release_date, runtime, phase, budget, revenue, description, image_path) VALUES ('1', 'Oceania 2', 'Moana 2', 'en', '2024-11-27', '100', 'Rilasciato', '150000000', '423586580', 'A tre anni dal suo primo viaggio, Vaiana è impegnata in un lungo viaggio alla ricerca di persone oltre le coste di Motunui. Assieme a Maui e a un nuovissimo equipaggio di improbabili marittimi, Vaiana si imbarca alla volta dei lontani mari dell''Oceania in acque pericolose per un''avventura diversa da qualsiasi cosa abbia mai affrontato.', 'dPnRomNzww9G7dgS84f6HSevVLc.jpg');
INSERT INTO movie (id, name, original_name, original_language, release_date, runtime, phase, budget, revenue, description, image_path) VALUES ('2', 'Venom: The Last Dance', 'Venom: The Last Dance', 'en', '2024-10-22', '109', 'Rilasciato', '120000000', '468425476', 'In Venom: The Last Dance, Tom Hardy torna a interpretare Venom, uno dei più celebri e complessi personaggi della Marvel. Nel film conclusivo della trilogia, Eddie e Venom sono in fuga. Mentre il cerchio si stringe attorno a loro, i due protagonisti sono costretti a prendere una decisione sconvolgente che farà calare il sipario sul loro “ultimo ballo”.', '37POW7X4SIbdfbqWznG3ceU5DTQ.jpg');
INSERT INTO movie (id, name, original_name, original_language, release_date, runtime, phase, budget, revenue, description, image_path) VALUES ('3', 'Elevation', 'Elevation', 'en', '2024-11-07', '91', 'Rilasciato', '0', '0', 'Un padre single e due donne escono dalla sicurezza delle loro case per affrontare creature mostruose e salvare la vita di un ragazzino.', 'uQhYBxOVFU6s9agD49FnGHwJqG5.jpg');
INSERT INTO movie (id, name, original_name, original_language, release_date, runtime, phase, budget, revenue, description, image_path) VALUES ('4', 'Absolution', 'Absolution', 'en', '2024-10-31', '112', 'Rilasciato', '30000000', '0', 'Informazione non disponibile', 'cNtAslrDhk1i3IOZ16vF7df6lMy.jpg');
INSERT INTO movie (id, name, original_name, original_language, release_date, runtime, phase, budget, revenue, description, image_path) VALUES ('5', 'Armor', 'Armor', 'en', '2024-10-30', '89', 'Rilasciato', '11500000', '0', 'La guardia di sicurezza del furgone blindato James Brody sta lavorando con suo figlio Casey per trasportare milioni di dollari tra le banche quando una squadra di ladri guidata da Rook orchestra un''acquisizione del loro furgone per impossessarsi delle ricchezze. Dopo un violento inseguimento in auto, Rook circonda presto il furgone blindato e James e Casey si ritrovano alle strette su un ponte decrepito.', 'pnXLFioDeftqjlCVlRmXvIdMsdP.jpg');
INSERT INTO movie (id, name, original_name, original_language, release_date, runtime, phase, budget, revenue, description, image_path) VALUES ('6', 'Sidelined: The QB and Me', 'Sidelined: The QB and Me', 'en', '2024-11-29', '99', 'Rilasciato', '0', '0', 'Informazione non disponibile', 'sIWv5HtDlUFvacsuA1fRNWZ5GFH.jpg');
INSERT INTO movie (id, name, original_name, original_language, release_date, runtime, phase, budget, revenue, description, image_path) VALUES ('7', 'Il gladiatore II', 'Gladiator II', 'en', '2024-11-13', '148', 'Rilasciato', '310000000', '320307811', 'Anni dopo aver assistito alla tragica morte del venerato eroe Massimo per mano del suo perfido zio, Lucio (Paul Mescal) si trova costretto a combattere nel Colosseo dopo che la sua patria viene conquistata da parte di due tirannici imperatori, che ora governano Roma.  Con il cuoreardente di rabbia e il destino dell''Impero appeso a un filo, Lucio deve affrontare pericoli e nemici, riscoprendo nel suo passato la forza e l''onore necessari per riportare la gloria di Roma al suo popolo. Preparatevi per un viaggio epico di coraggio e vendetta nella sanguinosa arena del Colosseo.', 'v1ffLD5yQqpJW1VXYy1n6sp5wgX.jpg');
INSERT INTO movie (id, name, original_name, original_language, release_date, runtime, phase, budget, revenue, description, image_path) VALUES ('8', 'Il robot selvaggio', 'The Wild Robot', 'en', '2024-09-12', '102', 'Rilasciato', '78000000', '321836235', 'L''epica avventura segue il viaggio di un robot - l''unità ROZZUM 7134, abbreviato ""Roz"" - che dopo un naufragio si ritrova su un''isola disabitata dove dovrà imparare ad adattarsi all’ostile ambiente circostante, costruendo gradualmente relazioni con gli altri animali dell’isola e adottando un''ochetta orfana.', 'j48mq3yF4Ghi8CclXqqZMWVByk1.jpg');
INSERT INTO movie (id, name, original_name, original_language, release_date, runtime, phase, budget, revenue, description, image_path) VALUES ('9', 'Smile 2', 'Smile 2', 'en', '2024-10-16', '127', 'Rilasciato', '28000000', '137717344', 'Sul punto di iniziare un nuovo tour mondiale, la star internazionale del pop Skye Riley (Naomi Scott) inizia a vivere esperienze sempre più terrificanti e incomprensibili. Schiacciata dalle crescenti angosce e dalle pressioni della notorietà, Skye è costretta a confrontarsi con il suo passato oscuro per riprendere in mano la sua vita prima che vada in pezzi.', 'z4H7lTRezsXaEjuzWmT1ztuEsb4.jpg');
INSERT INTO movie (id, name, original_name, original_language, release_date, runtime, phase, budget, revenue, description, image_path) VALUES ('10', 'Wicked', 'Wicked', 'en', '2024-11-20', '160', 'Rilasciato', '145000000', '359326510', 'La storia ruota attorno all’amicizia tra le due streghe. Elphaba, soprannominata ingiustamente Malvagia Strega dell’Ovest, è in realtà una donna rivoluzionaria e anticonformista con la pelle verde che sfida lo strapotere del Mago di Oz, imbroglione dalle mire imperialiste. Glinda è invece la Strega Buona del Nord, amica di Elphaba dai tempi dell’Università di Shiz.', '3al2ouBCaZN5UcCNr8HKSiNji28.jpg');
INSERT INTO movie_category (movie_id, category_name) VALUES ('1', 'Animazione');
INSERT INTO movie_category (movie_id, category_name) VALUES ('1', 'Avventura');
INSERT INTO movie_category (movie_id, category_name) VALUES ('1', 'Famiglia');
INSERT INTO movie_category (movie_id, category_name) VALUES ('1', 'Commedia');
INSERT INTO movie_category (movie_id, category_name) VALUES ('2', 'Fantascienza');
INSERT INTO movie_category (movie_id, category_name) VALUES ('2', 'Azione');
INSERT INTO movie_category (movie_id, category_name) VALUES ('2', 'Avventura');
INSERT INTO movie_category (movie_id, category_name) VALUES ('3', 'Azione');
INSERT INTO movie_category (movie_id, category_name) VALUES ('3', 'Fantascienza');
INSERT INTO movie_category (movie_id, category_name) VALUES ('3', 'Thriller');
INSERT INTO movie_category (movie_id, category_name) VALUES ('4', 'Azione');
INSERT INTO movie_category (movie_id, category_name) VALUES ('4', 'Crime');
INSERT INTO movie_category (movie_id, category_name) VALUES ('4', 'Thriller');
INSERT INTO movie_category (movie_id, category_name) VALUES ('5', 'Azione');
INSERT INTO movie_category (movie_id, category_name) VALUES ('5', 'Crime');
INSERT INTO movie_category (movie_id, category_name) VALUES ('5', 'Thriller');
INSERT INTO movie_category (movie_id, category_name) VALUES ('6', 'Commedia');
INSERT INTO movie_category (movie_id, category_name) VALUES ('6', 'Romance');
INSERT INTO movie_category (movie_id, category_name) VALUES ('6', 'televisione film');
INSERT INTO movie_category (movie_id, category_name) VALUES ('7', 'Azione');
INSERT INTO movie_category (movie_id, category_name) VALUES ('7', 'Avventura');
INSERT INTO movie_category (movie_id, category_name) VALUES ('7', 'Storia');
INSERT INTO movie_category (movie_id, category_name) VALUES ('8', 'Animazione');
INSERT INTO movie_category (movie_id, category_name) VALUES ('8', 'Fantascienza');
INSERT INTO movie_category (movie_id, category_name) VALUES ('8', 'Famiglia');
INSERT INTO movie_category (movie_id, category_name) VALUES ('9', 'Horror');
INSERT INTO movie_category (movie_id, category_name) VALUES ('9', 'Mistero');
INSERT INTO movie_category (movie_id, category_name) VALUES ('10', 'Dramma');
INSERT INTO movie_category (movie_id, category_name) VALUES ('10', 'Fantasy');
INSERT INTO movie_category (movie_id, category_name) VALUES ('10', 'Romance');
INSERT INTO cast (movie_id, person_id, role) VALUES ('1', '1', 'Moana (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('1', '2', 'Maui (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('1', '3', 'Moni (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('1', '4', 'Loto (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('1', '5', 'Kele (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('1', '6', 'Matangi (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('1', '7', 'Simea (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('1', '8', 'Chief Tui (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('1', '9', 'Sina (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('1', '10', 'Gramma Tala (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('2', '15', 'Eddie Brock / Venom');
INSERT INTO cast (movie_id, person_id, role) VALUES ('2', '16', 'General Rex Strickland');
INSERT INTO cast (movie_id, person_id, role) VALUES ('2', '17', 'Dr. Teddy Paine');
INSERT INTO cast (movie_id, person_id, role) VALUES ('2', '18', 'Sadie Christmas');
INSERT INTO cast (movie_id, person_id, role) VALUES ('2', '19', 'Martin Moon');
INSERT INTO cast (movie_id, person_id, role) VALUES ('2', '20', 'Detective Mulligan');
INSERT INTO cast (movie_id, person_id, role) VALUES ('2', '21', 'Mrs. Chen');
INSERT INTO cast (movie_id, person_id, role) VALUES ('2', '22', 'Nova Moon');
INSERT INTO cast (movie_id, person_id, role) VALUES ('2', '23', 'Echo Moon');
INSERT INTO cast (movie_id, person_id, role) VALUES ('2', '24', 'Leaf Moon');
INSERT INTO cast (movie_id, person_id, role) VALUES ('3', '26', 'Will');
INSERT INTO cast (movie_id, person_id, role) VALUES ('3', '27', 'Nina');
INSERT INTO cast (movie_id, person_id, role) VALUES ('3', '28', 'Katie');
INSERT INTO cast (movie_id, person_id, role) VALUES ('3', '29', 'Hunter');
INSERT INTO cast (movie_id, person_id, role) VALUES ('3', '30', 'Tara');
INSERT INTO cast (movie_id, person_id, role) VALUES ('3', '31', 'Hannah');
INSERT INTO cast (movie_id, person_id, role) VALUES ('3', '32', 'Tim');
INSERT INTO cast (movie_id, person_id, role) VALUES ('3', '33', 'Nina’s Son (uncredited)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('3', '34', 'Nina’s Husband (uncredited)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('3', '35', 'Refugee (uncredited)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('4', '40', 'Thug');
INSERT INTO cast (movie_id, person_id, role) VALUES ('4', '41', 'Charlie Conner');
INSERT INTO cast (movie_id, person_id, role) VALUES ('4', '42', 'Woman');
INSERT INTO cast (movie_id, person_id, role) VALUES ('4', '43', 'Daisy');
INSERT INTO cast (movie_id, person_id, role) VALUES ('4', '44', 'Kyle Conner');
INSERT INTO cast (movie_id, person_id, role) VALUES ('4', '45', 'Gamberro');
INSERT INTO cast (movie_id, person_id, role) VALUES ('4', '46', 'Diego Machado');
INSERT INTO cast (movie_id, person_id, role) VALUES ('4', '47', 'Thug''s Dad');
INSERT INTO cast (movie_id, person_id, role) VALUES ('4', '48', 'Araceli');
INSERT INTO cast (movie_id, person_id, role) VALUES ('4', '49', 'Dre');
INSERT INTO cast (movie_id, person_id, role) VALUES ('5', '52', 'Rook');
INSERT INTO cast (movie_id, person_id, role) VALUES ('5', '53', 'James Brody');
INSERT INTO cast (movie_id, person_id, role) VALUES ('5', '54', 'Casey Brody');
INSERT INTO cast (movie_id, person_id, role) VALUES ('5', '55', 'Smoke');
INSERT INTO cast (movie_id, person_id, role) VALUES ('5', '56', 'Trisha Brody');
INSERT INTO cast (movie_id, person_id, role) VALUES ('5', '57', 'Sara Brody');
INSERT INTO cast (movie_id, person_id, role) VALUES ('5', '58', 'Viper');
INSERT INTO cast (movie_id, person_id, role) VALUES ('5', '59', 'Echo');
INSERT INTO cast (movie_id, person_id, role) VALUES ('5', '60', 'Bank Manager');
INSERT INTO cast (movie_id, person_id, role) VALUES ('5', '61', 'Speeding Driver');
INSERT INTO cast (movie_id, person_id, role) VALUES ('6', '65', 'Drayton Lahey');
INSERT INTO cast (movie_id, person_id, role) VALUES ('6', '66', 'Dallas Bryan');
INSERT INTO cast (movie_id, person_id, role) VALUES ('6', '67', 'Nathan Bryan');
INSERT INTO cast (movie_id, person_id, role) VALUES ('6', '68', 'Leroy Lahey');
INSERT INTO cast (movie_id, person_id, role) VALUES ('6', '69', 'Miss Alicia');
INSERT INTO cast (movie_id, person_id, role) VALUES ('6', '70', 'Gabby');
INSERT INTO cast (movie_id, person_id, role) VALUES ('6', '71', 'Mr. Justin Douglas');
INSERT INTO cast (movie_id, person_id, role) VALUES ('6', '72', 'Josh Lahey');
INSERT INTO cast (movie_id, person_id, role) VALUES ('6', '73', 'Ellie Lahey');
INSERT INTO cast (movie_id, person_id, role) VALUES ('6', '74', 'Chase');
INSERT INTO cast (movie_id, person_id, role) VALUES ('7', '76', 'Lucius Verus / Hanno');
INSERT INTO cast (movie_id, person_id, role) VALUES ('7', '77', 'Macrinus');
INSERT INTO cast (movie_id, person_id, role) VALUES ('7', '78', 'General Acacius');
INSERT INTO cast (movie_id, person_id, role) VALUES ('7', '79', 'Lucilla');
INSERT INTO cast (movie_id, person_id, role) VALUES ('7', '80', 'Emperor Geta');
INSERT INTO cast (movie_id, person_id, role) VALUES ('7', '81', 'Emperor Caracalla');
INSERT INTO cast (movie_id, person_id, role) VALUES ('7', '82', 'Viggo');
INSERT INTO cast (movie_id, person_id, role) VALUES ('7', '83', 'Gracchus');
INSERT INTO cast (movie_id, person_id, role) VALUES ('7', '84', 'Jugurtha');
INSERT INTO cast (movie_id, person_id, role) VALUES ('7', '85', 'Master of Ceremonies');
INSERT INTO cast (movie_id, person_id, role) VALUES ('8', '87', 'Roz / Rummage (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('8', '78', 'Fink (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('8', '88', 'Brightbill (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('8', '89', 'Longneck (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('8', '90', 'Vontra (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('8', '91', 'Paddler (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('8', '92', 'Thunderbolt (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('8', '93', 'Thorn (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('8', '94', 'Pinktail (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('8', '95', 'Baby Brightbill (voice)');
INSERT INTO cast (movie_id, person_id, role) VALUES ('9', '97', 'Skye Riley');
INSERT INTO cast (movie_id, person_id, role) VALUES ('9', '98', 'Elizabeth Riley');
INSERT INTO cast (movie_id, person_id, role) VALUES ('9', '99', 'Lewis');
INSERT INTO cast (movie_id, person_id, role) VALUES ('9', '100', 'Joshua');
INSERT INTO cast (movie_id, person_id, role) VALUES ('9', '101', 'Morris');
INSERT INTO cast (movie_id, person_id, role) VALUES ('9', '102', 'Paul Hudson');
INSERT INTO cast (movie_id, person_id, role) VALUES ('9', '103', 'Gemma');
INSERT INTO cast (movie_id, person_id, role) VALUES ('9', '104', 'Darius');
INSERT INTO cast (movie_id, person_id, role) VALUES ('9', '105', 'Joel');
INSERT INTO cast (movie_id, person_id, role) VALUES ('9', '106', 'Drew Barrymore');
INSERT INTO cast (movie_id, person_id, role) VALUES ('10', '108', 'Elphaba');
INSERT INTO cast (movie_id, person_id, role) VALUES ('10', '109', 'Galinda / Glinda');
INSERT INTO cast (movie_id, person_id, role) VALUES ('10', '110', 'Fiyero');
INSERT INTO cast (movie_id, person_id, role) VALUES ('10', '111', 'Madame Morrible');
INSERT INTO cast (movie_id, person_id, role) VALUES ('10', '112', 'The Wonderful Wizard of Oz');
INSERT INTO cast (movie_id, person_id, role) VALUES ('10', '113', 'Nessarose');
INSERT INTO cast (movie_id, person_id, role) VALUES ('10', '114', 'Boq');
INSERT INTO cast (movie_id, person_id, role) VALUES ('10', '115', 'Pfannee');
INSERT INTO cast (movie_id, person_id, role) VALUES ('10', '116', 'ShenShen');
INSERT INTO cast (movie_id, person_id, role) VALUES ('10', '117', 'Dr. Dillamond (voice)');
INSERT INTO crew (movie_id, person_id, role) VALUES ('1', '11', 'Director');
INSERT INTO crew (movie_id, person_id, role) VALUES ('1', '12', 'Director');
INSERT INTO crew (movie_id, person_id, role) VALUES ('1', '13', 'Director');
INSERT INTO crew (movie_id, person_id, role) VALUES ('1', '14', 'Writer');
INSERT INTO crew (movie_id, person_id, role) VALUES ('1', '13', 'Writer');
INSERT INTO crew (movie_id, person_id, role) VALUES ('2', '25', 'Director');
INSERT INTO crew (movie_id, person_id, role) VALUES ('3', '36', 'Director');
INSERT INTO crew (movie_id, person_id, role) VALUES ('3', '37', 'Writer');
INSERT INTO crew (movie_id, person_id, role) VALUES ('3', '38', 'Writer');
INSERT INTO crew (movie_id, person_id, role) VALUES ('3', '39', 'Writer');
INSERT INTO crew (movie_id, person_id, role) VALUES ('4', '50', 'Director');
INSERT INTO crew (movie_id, person_id, role) VALUES ('4', '51', 'Writer');
INSERT INTO crew (movie_id, person_id, role) VALUES ('5', '62', 'Director');
INSERT INTO crew (movie_id, person_id, role) VALUES ('5', '63', 'Writer');
INSERT INTO crew (movie_id, person_id, role) VALUES ('5', '64', 'Writer');
INSERT INTO crew (movie_id, person_id, role) VALUES ('6', '75', 'Director');
INSERT INTO crew (movie_id, person_id, role) VALUES ('7', '86', 'Director');
INSERT INTO crew (movie_id, person_id, role) VALUES ('8', '96', 'Director');
INSERT INTO crew (movie_id, person_id, role) VALUES ('9', '107', 'Writer');
INSERT INTO crew (movie_id, person_id, role) VALUES ('9', '107', 'Director');
INSERT INTO crew (movie_id, person_id, role) VALUES ('10', '118', 'Director');

COMMIT;