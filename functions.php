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
