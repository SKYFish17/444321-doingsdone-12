DROP DATABASE IF EXISTS doingsdone;
CREATE DATABASE doingsdone
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE users (
  `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`     VARCHAR(255) NOT NULL,
  `email`    VARCHAR(255) NOT NULL,
  `password` CHAR(64)     NOT NULL,
  `dt_add`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
  id      INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title   VARCHAR(255) NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE tasks (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  file_path VARCHAR(255) NULL,
  dt_add TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  dt_deadline TIMESTAMP NULL,
  status TINYINT(1) NOT NULL DEFAULT 0,
  user_id INT UNSIGNED NOT NULL,
  project_id INT UNSIGNED,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (project_id) REFERENCES projects (id)
);