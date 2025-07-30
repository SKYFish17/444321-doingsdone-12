<?php
/**
 * Считает кол-во задач в проекте
 * @param $tasks
 * @param $project
 * @return int
 */
function get_project_number_of_tasks($tasks, $project ) {
    $number = 0;

    foreach( $tasks as $task ) {
        if ( $task['project'] === $project['title'] ) {
            $number++;
        }
    }

    return $number;
}

// Определяет, срочная ли задача
/**
 * @param $deadline
 * @return bool|void
 */
function is_urgency_task($deadline ) {
    if ( $deadline === 'null' ) {
        return;
    }

    $cur_date = date_create( "now" );
    $deadline_date = date_create( $deadline );

    $diff = date_diff( $cur_date, $deadline_date );
    $days_count = date_interval_format( $diff, "%r%d" );
    $hours_count = date_interval_format( $diff, "%r%h" );

    if ( $days_count <= 0 && $hours_count <= 24 ) {
      return true;
    } else {
        return false;
    }
}

// показывать или нет выполненные задачи
/**
 * @return int
 */
function is_show_complete_task() {
    return rand(0, 1);
}

/**
 * @param $value
 * @return string|null
 */
function validateEmpty($value) {
    return strlen($value) === 0 ? 'Поле должно быть заполнено' : null;
}

/**
 * @param $link
 * @param $user_id
 * @param $project_id
 * @return string|void
 */
function validateProject($link, $user_id, $project_id) {
    if (!get_user_project($link, $user_id, $project_id)) {
        return 'Проект не найден';
    }
}

/**
 * @param $date
 * @param string $format
 * @return string|void
 */
function validateDate($date, $format = 'Y-m-d') {
    if (!empty($date)) {
        $d = date_create_from_format($format, $date);

        if (!$d || date_format($d, $format) !== $date) {
            return 'Дата должна быть в формате ГГГГ-ММ-ДД';
        }

        if ($date < date('Y-m-d')) {
            return 'Дата должна быть больше или равна текущей';
        }
    }
}

/**
 * @param $file
 * @return false|string
 */
function saveFile($file){
    $tmp_name = $file['tmp_name'];
    $upload_path = __DIR__  . '/uploads';
    $name = basename($file['name']);

    if (move_uploaded_file($tmp_name, "$upload_path/$name")) {
        return "/uploads/$name";
    } else {
        return false;
    }
}

/**
 * @param $file
 * @return string|void
 */
function validateFile($file) {
    $file_type = $file['type'];
    $file_size = $file['size'];

    if ($file_type !== 'image/jpeg') {
        return 'Допустимый формат файла - jpeg';
    }

    if ($file_size >= 2*1024*1024) {
        return 'Максимальный размер файла - 2Мб';
    }
}

function validateEmailExist($link, $email) {
    return !empty(get_user_by_email($link, $email)) ? 'Данный e-mail уже используется' : null;
}
