CREATE DATABASE taskforce
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE taskforce;

CREATE TABLE IF NOT EXISTS users (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(45) NOT NULL UNIQUE,
  avatar VARCHAR(45),
  cities_id INT NOT NULL,
  address VARCHAR(45) NOT NULL,
  coords VARCHAR(45) NOT NULL,
  about VARCHAR(255) NOT NULL,
  birthday DATE NOT NULL,
  password VARCHAR(45) NOT NULL,
  phone VARCHAR(10),
  skypeid VARCHAR(45),
  messenger VARCHAR(45),
  rating INT,
  last_seen DATETIME,
  notify_message TINYINT(1)  DEFAULT 1,
  notify_action TINYINT(1)  DEFAULT 1,
  notify_review TINYINT(1)  DEFAULT 1,
  show_only_owner TINYINT(1)  DEFAULT 0,
  hidden TINYINT(1)  DEFAULT 0
);

CREATE TABLE tasks (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(45),
  description VARCHAR(255),
  coords VARCHAR(45),
  budget INT,
  publication_date DATETIME,
  due_date DATETIME,
  owner_id INT NOT NULL,
  contractor_id INT,
  skill_id INT NOT NULL,
  reviews_id INT
);

CREATE TABLE city (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(45) UNIQUE NOT NULL
);

CREATE TABLE skill (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    skill VARCHAR(45) NOT NULL UNIQUE
);

CREATE TABLE users_has_skills (
    user_id INT NOT NULL,
    skill_id INT NOT NULL,
    PRIMARY KEY (user_id, skill_id)
);

CREATE TABLE reviews (
 id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
 text VARCHAR(255) NOT NULL,
 rating INT NOT NULL,
 user_id INT NOT NULL
);

CREATE TABLE files (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    url VARCHAR(128)
);

CREATE TABLE messages (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  text VARCHAR(255),
  date DATETIME,
  user_id INT NOT NULL,
  task_id INT NOT NULL
);

CREATE TABLE responses (
   id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
   user_id INT NOT NULL,
   task_id INT NOT NULL,
   text VARCHAR(255) NOT NULL,
   budget INT,
   date DATETIME
);


