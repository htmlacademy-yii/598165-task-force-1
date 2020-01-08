CREATE DATABASE taskforce
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE taskforce;

CREATE TABLE city
(
    id   INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE
);


CREATE TABLE user
(
    id                 INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    role               ENUM ('CLIENT', 'CONTRACTOR'),
    email              VARCHAR(320) NOT NULL UNIQUE,
    name               TEXT         NOT NULL,
    avatar             TEXT,
    city_id            INT,
    address            TEXT,
    latitude           DECIMAL(9, 6),
    longitude          DECIMAL(9, 6),
    about              TEXT,
    birthday_at        DATE,
    password           TEXT NOT NULL,
    phone              VARCHAR(11),
    skypeid            VARCHAR(255),
    messenger          VARCHAR(255),
    last_seen_at       DATETIME,
    is_notify_message  TINYINT(1) DEFAULT 1,
    is_notify_action   TINYINT(1) DEFAULT 1,
    is_notify_review   TINYINT(1) DEFAULT 1,
    is_show_only_owner TINYINT(1) DEFAULT 0,
    is_hidden          TINYINT(1) DEFAULT 0,
    FOREIGN KEY (city_id) REFERENCES city (id),
    INDEX user_email_idx (email),
    FULLTEXT INDEX user_name_idx (name)
);



CREATE TABLE task
(
    id            INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title         TEXT,
    description   TEXT,
    status        ENUM ('NEW', 'PENDING', 'CANCELED', 'FAILED', 'DONE'),
    city_id          INT,
    latitude      DECIMAL(9, 6),
    longitude     DECIMAL(9, 6),
    budget        INT DEFAULT NULL,
    created_at    DATETIME DEFAULT NOW(),
    updated_at    DATETIME,
    due_date_at   DATETIME,
    client_id     INT NOT NULL,
    contractor_id INT,
    skill_id      INT NOT NULL,
    FOREIGN KEY (skill_id) REFERENCES skill (id),
    FOREIGN KEY (city_id) REFERENCES city (id),
    FOREIGN KEY (client_id) REFERENCES user (id),
    FOREIGN KEY (contractor_id) REFERENCES user (id),
    FULLTEXT INDEX task_title_idx (title),
    INDEX task_created_at_idx (created_at),
    INDEX task_due_at_idx (due_date_at)
);


CREATE TABLE skill
(
    id    INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    skill VARCHAR(255) NOT NULL UNIQUE,
    INDEX skill_skill_idx (skill)
);

CREATE TABLE user_has_skill
(
    user_id  INT,
    skill_id INT,
    PRIMARY KEY (user_id, skill_id),
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (skill_id) REFERENCES skill(id)
);

CREATE TABLE review
(
    id         INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    task_id    INT,
    comment    TEXT,
    rating     INT,
    user_id    INT,
    created_at DATETIME DEFAULT NOW(),
    updated_at DATETIME,
    FOREIGN KEY (task_id) REFERENCES task (id),
    FOREIGN KEY (user_id) REFERENCES user (id),
    INDEX review_user_id_idx (user_id),
    INDEX user_user_id_idx (user_id)
);

CREATE TABLE file
(
    id      INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    src     TEXT,
    FOREIGN KEY (task_id) REFERENCES task (id),
    INDEX file_task_id_idx (task_id)
);


CREATE TABLE message
(
    id         INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    text       TEXT,
    created_at DATETIME DEFAULT NOW(),
    user_id    INT NOT NULL,
    task_id    INT NOT NULL,

    FOREIGN KEY (user_id) REFERENCES user (id),
    FOREIGN KEY (task_id) REFERENCES task (id),
    INDEX message_user_id_idx (user_id),
    INDEX message_task_id_idx (task_id)
);


CREATE TABLE response
(
    id         INT  NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id    INT  NOT NULL,
    task_id    INT  NOT NULL,
    text       TEXT NOT NULL,
    budget     INT,
    created_at DATETIME DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES user (id),
    FOREIGN KEY (task_id) REFERENCES task (id),
    INDEX response_task_id_idx (task_id)
);


CREATE TABLE favorite
(
    id          INT NOT NULL PRIMARY KEY,
    user_id     INT NOT NULL,
    favorite_id INT NULL,
    FOREIGN KEY (user_id) REFERENCES user (id),
    FOREIGN KEY (favorite_id) REFERENCES user (id),
    INDEX favorite_user_id_idx (user_id)
);
