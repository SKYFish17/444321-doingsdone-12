<?php
require_once( 'helpers.php' );
require_once( 'functions.php' );
require_once( 'data.php' );

$content = include_template( 'main.php', array( 'projects' => $projects, 'tasks' => $tasks, 'show_complete_tasks' => is_show_complete_task() ) );

$page = include_template( 'layout.php', array( 'site_title' => 'Дела в порядке', 'user_name' => 'Константин', 'site_content' => $content ) );

print( $page );
