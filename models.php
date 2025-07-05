<?php

// запрос для получения списка проектов у текущего пользователя.
/**
 * @param $link
 * @param $user_id
 * @return array
 */
function get_user_projects($link, $user_id)
{
    $sql = 'SELECT title FROM projects WHERE user_id = ?';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result( $stmt );
    $projects = [];

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
    } else {
        $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $projects = array_column($projects, 'title');
    }

    return $projects;
}

//  запрос для получения списка из всех задач у текущего пользователя
/**
 * @param $link
 * @param $user_id
 * @return array
 */
function get_user_tasks($link, $user_id)
{
    $sql = 'SELECT t.title, t.file_path, t.dt_deadline, t.status, p.title as project  FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.user_id = ?';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute( $stmt );

    $result = mysqli_stmt_get_result( $stmt );
    $tasks = [];

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
    } else {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return $tasks;
}