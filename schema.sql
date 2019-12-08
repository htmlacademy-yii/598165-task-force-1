CREATE DATABASE taskforce
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE taskforce;

CREATE TABLE  user (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    avatar VARCHAR(255),
    city_id INT NOT NULL,
    address VARCHAR(255) NOT NULL,
    latitude INT NOT NULL,
    longitude INT NOT NULL,
    about VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(255),
    skypeid VARCHAR(255),
    messenger VARCHAR(255),
    last_seen DATETIME,
    notify_message TINYINT(1)  DEFAULT 1,
    notify_action TINYINT(1)  DEFAULT 1,
    notify_review TINYINT(1)  DEFAULT 1,
    show_only_owner TINYINT(1)  DEFAULT 0,
    hidden TINYINT(1)  DEFAULT 0,
    FOREIGN KEY (city_id) REFERENCES city(id)
);
CREATE INDEX u_email ON user(email);
CREATE FULLTEXT INDEX u_name ON user(name);


CREATE TABLE task (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description VARCHAR(255),
    latitude INT NOT NULL,
    longitude INT NOT NULL,
    budget INT,
    publication_date DATETIME,
    due_date DATETIME,
    owner_id INT NOT NULL,
    contractor_id INT,
    skill_id INT NOT NULL,
    review_id INT,
    FOREIGN KEY (owner_id) REFERENCES user(id),
    FOREIGN KEY (contractor_id) REFERENCES user(id),
    FOREIGN KEY (review_id) REFERENCES review(id)
);
CREATE FULLTEXT INDEX t_title ON task(title);
CREATE INDEX t_pub_date ON task(publication_date);
CREATE INDEX t_due_date ON task(due_date);


CREATE TABLE city (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) UNIQUE NOT NULL
);

CREATE TABLE user_skill (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill ENUM(
        'Курьерские услуги',
        'Ремонт транспорта',
        'Грузоперевозки',
        'Удалённая помощь',
        'Перевод текстов',
        'Выезд на стрелку')
);
CREATE INDEX us_user ON user_skill(user_id);


CREATE TABLE review (
 id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
 text VARCHAR(255) NOT NULL,
 rating INT NOT NULL,
 user_id INT NOT NULL,
 FOREIGN KEY (user_id) REFERENCES user(id)
);
CREATE INDEX r_user ON review(user_id);

CREATE TABLE file (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    url VARCHAR(255),
    FOREIGN KEY (task_id) REFERENCES task(id)
);
CREATE INDEX f_task ON file(task_id);

CREATE TABLE message (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  text VARCHAR(255),
  date DATETIME DEFAULT NOW(),
  user_id INT NOT NULL,
  task_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (task_id) REFERENCES task(id)
);
CREATE INDEX m_user ON message(user_id);
CREATE INDEX m_task ON message(task_id);

CREATE TABLE response (
   id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
   user_id INT NOT NULL,
   task_id INT NOT NULL,
   text VARCHAR(255) NOT NULL,
   budget INT,
   date DATETIME DEFAULT NOW(),
   FOREIGN KEY (user_id) REFERENCES user(id),
   FOREIGN KEY (task_id) REFERENCES task(id)
);
CREATE INDEX r_task ON response(task_id);

CREATE TABLE favorite (
   id INT NOT NULL PRIMARY KEY,
   user_id INT NOT NULL,
   favuser_id INT NULL,
   FOREIGN KEY (user_id) REFERENCES user(id),
   FOREIGN KEY (favuser_id) REFERENCES user(id)
);
CREATE INDEX f_user ON favorite(user_id);

