<?php

    $connection = mysqli_connect('zgt', 'root', '', 'zgt_db');
    if (!$connection) {
        echo mysqli_connect_error();
        exit();
    }

    // Переменные для проверки привилегий
    $admin_pages = ['/users.php', '/questions.php', '/results.php', '/answers.php'];
    $pos = strpos($_SERVER['REQUEST_URI'], "?");
    if ($pos) $page = substr($_SERVER['REQUEST_URI'], 0, $pos);
    else $page = $_SERVER['REQUEST_URI'];