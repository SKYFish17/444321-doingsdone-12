<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('models.php');

// temporary
$user_id = 1;

/** @var mysqli $link */
if ($link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    $projects = get_user_projects($link, $user_id);
    $tasks = get_user_tasks($link, $user_id);
}

$content = include_template(
    'add.php',
    array(
        'projects' => $projects,
        'tasks' => $tasks,
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
