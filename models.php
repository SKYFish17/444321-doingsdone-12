<?php

// запрос для получения списка проектов у текущего пользователя.
function get_user_projects( $con, $user_id ) {
  $sql = "SELECT title FROM projects WHERE user_id = '$user_id'";
  $result = mysqli_query( $con, $sql );
  $projects = [];

  if ( !$result ) {
    $error = mysqli_error( $con );
    print( "Ошибка MySQL: " . $error );
  } else {
    $projects = mysqli_fetch_all( $result, MYSQLI_ASSOC );
    $projects = array_column( $projects, 'title' );
  }

  return $projects;
}

//  запрос для получения списка из всех задач у текущего пользователя
function get_user_tasks( $con, $user_id ) {
  $sql = "SELECT t.title, t.file_path, t.dt_deadline, t.status, p.title as project  FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.user_id = '$user_id'";
  $result = mysqli_query( $con, $sql );
  $tasks = [];

  if ( !$result ) {
    $error = mysqli_error( $con );
    print( "Ошибка MySQL: " . $error );
  } else {
    $tasks = mysqli_fetch_all( $result, MYSQLI_ASSOC );
  }

  return $tasks;
}