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

$projects = [];
$tasks = [];

/** @var mysqli $link */
if ($link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    $projects = get_user_projects($link, $user['id']);
    $tasks = get_user_tasks($link, $user['id']);
}

// Валидация формы
$errors = [];
$args = [
    'name' => FILTER_UNSAFE_RAW,
];
$form_fields_value = filter_input_array(INPUT_POST, $args);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form_fields_value as $key => $field_value) {
        if ($key === 'name') {
            $errors[$key] = validateEmpty($field_value);
        }

        if (!$errors['name']) {
            $errors[$key] = validateProjectName($link, $user['id'], $field_value);
        }
    }

    $errors = array_filter($errors, function ($elem) {
        return !empty($elem);
    });

    if (empty($errors) && !empty($form_fields_value)) {
        if (createProject(
            $link,
            $user['id'],
            $form_fields_value['name']
        )) {
            header("Location: /index.php", true, 301);
            exit();
        }
    }
}

$content = include_template(
    'add-project.php',
    array(
        'projects' => $projects,
        'tasks' => $tasks,
        'form_fields_value' => $form_fields_value,
        'errors' => $errors,
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
