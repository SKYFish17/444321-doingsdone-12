<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('models.php');

// temporary
$user_id = 1;

$project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);

/** @var mysqli $link */
if ($link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    $active_project = get_user_project($link, $user_id, $project_id);

    if (!is_null($project_id) && empty($active_project)) {
        http_response_code(404);
        die();
    }

    $projects = get_user_projects($link, $user_id);
    $tasks = get_user_tasks($link, $user_id);
    $tasks_by_project = get_user_tasks_by_project($link, $user_id, $project_id);
}

$content = include_template(
    'main.php',
    array(
        'projects' => $projects,
        'active_project' => $active_project,
        'tasks' => $tasks,
        'tasks_by_project' => $tasks_by_project,
        'show_complete_tasks' => is_show_complete_task()
    )
);

$page = include_template(
    'layout.php',
    array(
        'site_title' => 'Дела в порядке',
        'user_name' => 'Константин',
        'site_content' => $content
    )
);

print($page);
