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
    'project' => FILTER_SANITIZE_NUMBER_INT,
    'date' => FILTER_UNSAFE_RAW,
];
$form_fields_value = filter_input_array(INPUT_POST, $args);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form_fields_value as $key => $field_value) {
        if ($key === 'name') {
            $errors[$key] = validateEmpty($field_value);
        } elseif ($key === 'project') {
            $errors[$key] = validateProject($link, $user['id'], $field_value);
        } elseif ($key === 'date') {
            $errors[$key] = validateDate($field_value);
        }
    }

    // Сохранение файла
    $file = $_FILES['file'] ?? '';
    $saved_file_path = null;

    if (!empty($file['name'])) {
        $saved_file_path = saveFile($file);

        // Доп. функционал с валидацией не по ТЗ
        $errors['file'] = validateFile($file);

        if (empty($errors['file'])) {
            saveFile($file);
        }
    }

    $errors = array_filter($errors, function ($elem) { return !empty($elem); });

    if (empty($errors) && !empty($form_fields_value)) {
        if (createTask(
            $link,
            $form_fields_value['name'],
            $form_fields_value['project'],
            !empty($form_fields_value['date']) ? $form_fields_value['date'] : null,
            $saved_file_path ?? null,
            $user['id']
        )) {
            header("Location: /index.php", true, 301);
            exit();
        }
    }
}

$content = include_template(
    'add.php',
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
