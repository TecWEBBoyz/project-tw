DROP TABLE IF EXISTS animal;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS booking;
CREATE TABLE animal (
    id CHAR(36) PRIMARY KEY DEFAULT UUID(),
    specie VARCHAR(50) NOT NULL,
    name VARCHAR(50) NOT NULL DEFAULT '',
    age INT NOT NULL,
    description TEXT DEFAULT '',
    image VARCHAR(255) NOT NULL DEFAULT ''
);
CREATE TABLE user (
    id CHAR(36) PRIMARY KEY DEFAULT UUID(),
    name VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM("Administrator", "User") NOT NULL DEFAULT "User"
);
CREATE TABLE booking (
    id CHAR(36) PRIMARY KEY DEFAULT UUID(),
    user_id CHAR(36) NOT NULL,
    date DATE NOT NULL,
    start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id)
);
INSERT INTO animal (specie, name, age, description, image) VALUES
('Dog', 'Buddy', 3, 'A friendly dog', 'https://example.com/dog.jpg'),
('Cat', 'Whiskers', 2, 'A playful cat', 'https://example.com/cat.jpg'),
('Rabbit', 'Fluffy', 1, 'A cute rabbit', 'https://example.com/rabbit.jpg'),
('Parrot', 'Polly', 4, 'A talkative parrot', './static/images/dog.jpg'),
('Hamster', 'Nibbles', 1, 'A small hamster', 'https://example.com/hamster.jpg');