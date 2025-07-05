<?php
require_once( 'helpers.php' );
require_once( 'functions.php' );
require_once( 'init.php' );
require_once( 'models.php' );

// $link получаем из init.php
$projects = [];
$tasks = [];

// temporary
$user_id = 1;

if ( $link == false ) {
  print( "Ошибка подключения: " . mysqli_connect_error() );
}
else {
  $projects = get_user_projects( $link, $user_id );
  $tasks = get_user_tasks( $link, $user_id );
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
