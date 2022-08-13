-- запросы для добавления информации в БД:
-- придумайте пару пользователей;
INSERT INTO users (name, email, password) VALUES ('Dima', 'dima@mail.com', 'coolpass01');

INSERT INTO users (name, email, password) VALUES ('Ilya', 'ilya@mail.com', 'coolpass02');

INSERT INTO users (name, email, password) VALUES ('Julia', 'julia@mail.com', 'coolpass03');

-- существующий список проектов;
INSERT INTO projects (title, user_id) VALUES ('Входящие', 3);
INSERT INTO projects (title, user_id) VALUES ('Домашние дела', 3);

INSERT INTO projects (title, user_id) VALUES ('Входящие', 1);
INSERT INTO projects (title, user_id) VALUES ('Учеба', 1);
INSERT INTO projects (title, user_id) VALUES ('Работа', 1);

INSERT INTO projects (title, user_id) VALUES ('Входящие', 2);
INSERT INTO projects (title, user_id) VALUES ('Авто', 2);

-- существующий список задач.
INSERT INTO tasks (title, file_path, dt_deadline, status, user_id, project_id) VALUES ('Собеседование в IT компании', '', '2022.05.12', 0, 1, 5);

INSERT INTO tasks (title, file_path, dt_deadline, status, user_id, project_id) VALUES ('Выполнить тестовое задание', '', '2019.12.25', 0, 1, 5);

INSERT INTO tasks (title, file_path, dt_deadline, status, user_id, project_id) VALUES ('Сделать задание первого раздела', '', '2019.12.21', 1, 1, 4);

INSERT INTO tasks (title, file_path, dt_deadline, status, user_id, project_id) VALUES ('Встреча с другом', '', '2019.12.22', 0, 2, 6);

INSERT INTO tasks (title, file_path, dt_deadline, status, user_id, project_id) VALUES ('Купить корм для кота', '', null, 0, 3, 2);

INSERT INTO tasks (title, file_path, dt_deadline, status, user_id, project_id) VALUES ('Заказать пиццу', '', null, 1, 3, 2);

-- запросы для этих действий:
-- получить список из всех проектов для одного пользователя;
SELECT title FROM projects WHERE user_id = 1;

-- получить список из всех задач для одного проекта;
SELECT title FROM tasks WHERE project_id = 1;

-- пометить задачу как выполненную;
UPDATE tasks SET status = 1 WHERE id = 1;

-- обновить название задачи по её идентификатору.
UPDATE tasks SET title = 'Сделать 5-ю задачу курса PHP' WHERE id = 1;

