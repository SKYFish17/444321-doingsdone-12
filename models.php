<?php

/**
 * Получает список проектов у текущего пользователя.
 * @param $link
 * @param $user_id
 * @return array
 */
function get_user_projects($link, $user_id)
{
    $sql = 'SELECT p.id, p.title, COUNT(t.user_id) as tasks_num FROM projects as p JOIN tasks as t ON p.id = t.project_id WHERE p.user_id = ? GROUP BY p.id';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $projects = [];

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
    } else {
        $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return $projects;
}

/**
 * Получает проект пользователя по id
 * @param $link
 * @param $user_id
 * @param $project_id
 * @return array
 */
function get_user_project($link, $user_id, $project_id)
{
    $sql = 'SELECT id, title FROM projects WHERE user_id = ? AND id = ?';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $project_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $project = [];

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
    } else {
        $project = mysqli_fetch_assoc($result);
    }

    return $project;
}

/**
 * Получает проект пользователя по id
 * @param $link
 * @param $user_id
 * @param $project_id
 * @return array
 */
function get_user_project_by_name($link, $user_id, $project_name)
{
    $sql = 'SELECT id, title FROM projects WHERE user_id = ? AND title = ?';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'is', $user_id, $project_name);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $project = [];

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
    } else {
        $project = mysqli_fetch_assoc($result);
    }

    return $project;
}

/**
 * Получает спискок из всех задач пользователя
 * @param $link
 * @param $user_id
 * @return array
 */
function get_user_tasks($link, $user_id)
{
    $sql = 'SELECT t.id, t.title, t.file_path, t.dt_deadline, t.status, p.title as project FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.user_id = ? ORDER BY t.id DESC';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $tasks = [];

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
    } else {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return $tasks;
}

/**
 * @param $link
 * @param $user_id
 * @param $date_filter
 * @return array
 */
function get_user_tasks_by_date_filter($link, $user_id, $date_filter)
{
    $sql = 'SELECT t.id, t.title, t.file_path, t.dt_deadline, t.status, p.title as project FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.user_id = ?';

    switch ($date_filter) {
        case 'tod' :
            $sql .= ' AND t.dt_deadline = "' . date_format(date_create(), 'Y-m-d') . '"';
            break;
        case 'tom' :
            $sql .= ' AND t.dt_deadline = "' . date_format(date_modify(date_create(), '+1 day'), 'Y-m-d') . '"';
            break;
        case 'expired' :
            $sql .= ' AND t.dt_deadline < "' . date_format(date_create(), 'Y-m-d') . '"';
            break;
    }

    $sql .= ' ORDER BY t.id DESC';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $tasks = [];

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
    } else {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return $tasks;
}


/**
 * Получает список задач пользователя по проекту
 * @param $link
 * @param $user_id
 * @param $project_id
 * @return array
 */
function get_user_tasks_by_project($link, $user_id, $project_id = null)
{
    $sql = 'SELECT t.id, t.title, t.file_path, t.dt_deadline, t.status, p.title as project FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.user_id = ? AND t.project_id = ? ORDER BY t.id DESC';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $project_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $tasks = [];

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
    } else {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return $tasks;
}

function createTask($link, $title, $project_id, $deadline_date = null, $file_path = null, $user_id)
{
    $sql = 'INSERT INTO tasks (title, file_path, dt_add, dt_deadline, status, user_id, project_id) VALUES (?, ?, NOW(), ?, 0, ?, ?)';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'sssii', $title, $file_path, $deadline_date, $user_id, $project_id);
    mysqli_stmt_execute($stmt);

    $error = mysqli_error($link);

    if ($error) {
        print("Ошибка MySQL: " . $error);
        return false;
    } else {
        return true;
    }
}

function get_user_by_email($link, $email)
{
    $sql = 'SELECT * from users where email = ?';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);

    $error = mysqli_error($link);
    $result = mysqli_stmt_get_result($stmt);
    $user = [];

    if ($error) {
        print("Ошибка MySQL: " . $error);
        return false;
    } else {
        $user = mysqli_fetch_assoc($result);
    }

    return $user;
}

/**
 * @param $link
 * @param $name
 * @param $email
 * @param $password
 * @return bool
 */
function createUser($link, $name, $email, $password)
{
    $sql = 'INSERT INTO users (name, email, password) VALUES (?, ?, ?)';
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $password_hash);
    mysqli_stmt_execute($stmt);

    $error = mysqli_error($link);

    if ($error) {
        print("Ошибка MySQL: " . $error);
        return false;
    } else {
        return true;
    }
}

/**
 * Осуществляет fulltext-поиск задач пользователя
 * @param $link
 * @param $user_id
 * @param $search_query
 * @return array
 */
function get_user_tasks_by_search($link, $user_id, $search_query) {
    $sql = 'SELECT t.id, t.title, t.file_path, t.dt_deadline, t.status, p.title as project FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.user_id = ? AND MATCH(t.title) AGAINST(?) ORDER BY t.id DESC';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'is', $user_id, $search_query);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $tasks = [];

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
    } else {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return $tasks;
}

/**
 * @param $link
 * @param $user_id
 * @param $project_name
 * @return bool
 */
function createProject($link, $user_id, $project_name) {
    $sql = 'INSERT INTO projects (title, user_id) VALUES (?, ?)';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $project_name, $user_id);
    mysqli_stmt_execute($stmt);

    $error = mysqli_error($link);

    if ($error) {
        print("Ошибка MySQL: " . $error);
        return false;
    } else {
        return true;
    }
}

/**
 * @param $link
 * @param $task_id
 * @param $status
 * @param $user_id
 * @return bool
 */
function changeTaskStatus($link, $task_id, $status, $user_id): bool
{
    $sql = 'UPDATE tasks SET status = ? WHERE user_id = ? AND id = ?';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $status, $user_id, $task_id);
    mysqli_stmt_execute($stmt);

    $error = mysqli_error($link);

    if ($error) {
        print("Ошибка MySQL: " . $error);
        return false;
    } else {
        return true;
    }
}