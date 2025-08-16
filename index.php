<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('models.php');

$user = [];
if (isAuth()) {
    $user = getAuthUserSessionData();
} else {
    renderGuestPage();
}

$is_search = isset($_GET['search']) && trim($_GET['search']) !== '';
$is_show_completed = isset($_GET['show_completed']) && $_GET['show_completed'] == 1;
$project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);
$projects = [];
$tasks = [];
$search_result = [];
$tasks_by_project = [];
$active_project = [];
$is_task_check = isset($_GET['check']);

// Фильтры
$is_filter = isset($_GET['date']);

/** @var mysqli $link */
if ($link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    $active_project = get_user_project($link, $user['id'], $project_id);

    if (!is_null($project_id) && empty($active_project)) {
        http_response_code(404);
        die();
    }

    if ($is_search) {
        $search_result = get_user_tasks_by_search($link, $user['id'], htmlspecialchars(trim($_GET['search'])));
    }

    if ($is_task_check) {
        changeTaskStatus($link, $_GET['task_id'], $_GET['check'], $user['id']);
    }

    if ($is_filter && $_GET['date'] === 'all' || !$is_filter) {
        $tasks = get_user_tasks($link, $user['id']);
    } else {
        $tasks = get_user_tasks_by_date_filter($link, $user['id'], htmlspecialchars($_GET['date']));
    }

    $projects = get_user_projects($link, $user['id']);
    $tasks_by_project = get_user_tasks_by_project($link, $user['id'], $project_id);
}

$content = include_template(
    'main.php',
    array(
        'projects' => $projects,
        'active_project' => $active_project,
        'tasks' => $tasks,
        'tasks_by_project' => $tasks_by_project,
        'show_complete_tasks' => $is_show_completed,
        'is_search' => $is_search,
        'is_filter' => $is_filter,
        'search_result' => $search_result,
    )
);

$page = include_template(
    'layout.php',
    array(
        'site_title' => 'Дела в порядке',
        'user' => $user,
        'site_content' => $content
    )
);

print($page);
