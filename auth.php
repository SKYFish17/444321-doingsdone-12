<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('models.php');

session_start();

if (isAuth()) {
    header("Location: /index.php", true, 301);
    exit();
}

/** @var mysqli $link */
if ($link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

// Валидация формы
$errors = [];
$args = [
    'email' => FILTER_UNSAFE_RAW,
    'password' => FILTER_UNSAFE_RAW,
];
$form_fields_value = filter_input_array(INPUT_POST, $args);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form_fields_value as $key => $field_value) {
        if (validateEmpty($field_value)) {
            $errors[$key] = validateEmpty($field_value);
            continue;
        }

        if ($key === 'email') {
            if (!filter_var($field_value, FILTER_VALIDATE_EMAIL)) {
                $errors[$key] = 'E-mail введён некорректно';
            }
        }
    }

    $errors = array_filter($errors, function ($elem) { return !empty($elem); });

    if (empty($errors)) {
        $user = get_user_by_email($link, $form_fields_value['email']);

        if ($user && password_verify($form_fields_value['password'], $user['password'])) {
            setAuthUserSessionData($user);
            header("Location: /index.php", true, 301);
            exit();
        } else {
            $errors['email'] = 'Логин и/или пароль введен неверно';
            $errors['password'] = 'Логин и/или пароль введен неверно';
        }
    }
}

$content = include_template(
    'auth.php',
    array(
        'errors' => $errors,
        'form_fields_value' => $form_fields_value,
    )
);

$page = include_template(
    'layout.php',
    array(
        'site_title' => 'Дела в порядке',
        'user' => [],
        'site_content' => $content
    )
);

print($page);
