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
}

// Валидация формы
$errors = [];
$args = [
    'name' => FILTER_UNSAFE_RAW,
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
            } else {
                $errors[$key] = validateEmailExist($link, $field_value);
            }
        }
    }

    $errors = array_filter($errors, function ($elem) { return !empty($elem); });

    if (empty($errors) && !empty($form_fields_value)) {
        if (createUser(
            $link,
            $form_fields_value['name'],
            $form_fields_value['email'],
            $form_fields_value['login']
        )
        ) {
            header("Location: /index.php", true, 301);
            exit();
        }
    }
}

$page = include_template(
    'register.php',
    array(
        'site_title' => 'Дела в порядке',
        'user_name' => 'Константин',
        'errors' => $errors,
        'form_fields_value' => $form_fields_value,
    )
);

print($page);
