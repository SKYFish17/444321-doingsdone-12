<?php
require_once( 'helpers.php' );
require_once( 'functions.php' );
require_once( 'init.php' );
require_once( 'models.php' );

// $con получаем из init.php
$projects = [];
$tasks = [];

// temporary
$user_id = 1;

if ( $con == false ) {
  print( "Ошибка подключения: " . mysqli_connect_error() );
}
else {
  $projects = get_user_projects( $con, $user_id );
  $tasks = get_user_tasks( $con, $user_id );
}

$content = include_template(
  'main.php',
  array(
    'projects' => $projects,
    'tasks' => $tasks,
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

print( $page );
