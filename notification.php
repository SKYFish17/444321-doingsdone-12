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
$dsn = 'smtp://Buran-devs:qlvghslztlhioght@smtp.yandex.ru:465?encryption=tls&auth_mode=login';
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
        $message->from("mail@doingsdone.dev");
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

