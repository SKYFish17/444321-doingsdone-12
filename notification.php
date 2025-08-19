<?php
require_once 'vendor/autoload.php';
require_once('init.php');
require_once('models.php');

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Location: /index.php", true, 301);
    exit();
}

$tasks = [];

// Конфигурация траспорта
// Тестовая конфигурация debugmail.io
$login = '7ab9d38c-8b91-4d16-b5bb-48125ee89389';
$pass = 'b0e07399-5df5-44bc-8990-eb3d2b060b71';
$host = 'app.debugmail.io';
$port = 25;
$encryption = 'tls';
$auth_mode = 'auth_mode';

$dsn = "smtp://$login:$pass@$host:$port?encryption=$encryption&auth_mode=";
$transport = Transport::fromDsn($dsn);

/** @var mysqli $link */
if ($link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    $date = date_format(date_create(), 'Y-m-d');
    $tasks = getUpcomingTasks($link, $date);
    $tasks_by_user = [];

    if (count($tasks)) {
        foreach ($tasks as $task) {
            if (!isset($tasks_by_user[$task['user_id']])) {
                $tasks_by_user[$task['user_id']] = [
                    'email' => $task['email'],
                    'name' => $task['name'],
                    'tasks' => [$task['title']],
                ];
            } else {
                $tasks_by_user[$task['user_id']]['tasks'][] = $task['title'];
            }
        }
    }

    foreach ($tasks_by_user as $key => $user) {
        // Формирование сообщения
        $message = new Email();
        $message->to($user['email']);
        $message->from("info@mycompany.com");
        $message->subject("Уведомление от сервиса «Дела в порядке»");

        if (count($user['tasks']) < 2) {
            $text = "Уважаемый, {$user['name']}. У вас запланирована задача {$user['tasks'][0]} на $date";
            $message->text($text);
        } else {
            $text = "Уважаемый, {$user['name']}. У вас запланированы задачи на $date: <br>";
            $list = '<ul>';
            foreach ($user['tasks'] as $task) {
                $list .= "<li>$task</li>";
            }
            $list .= '</ul>';
            $message->text($text . $list);
        }

        // Отправка сообщения
        $mailer = new Mailer($transport);
        $mailer->send($message);
    }
}

