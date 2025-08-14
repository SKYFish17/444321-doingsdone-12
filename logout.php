<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $_SESSION = [];
    header("Location: /index.php", true, 301);
    exit();
}