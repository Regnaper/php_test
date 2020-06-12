<?php

    if ($_SERVER['SERVER_NAME'] == "php-math-test.herokuapp.com") {
        $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
        $host = $url["host"];
        $username = $url["user"];
        $password = $url["pass"];
        $dbname = substr($url["path"], 1);
    } else {
        $host = 'zgt';
        $dbname = 'zgt_db';
        $username = 'root';
        $password = '';
    }

    $connection = mysqli_connect($host, $username, $password, $dbname);
    if (!$connection) {
        echo mysqli_connect_error();
        exit();
    }

    // Переменные для проверки привилегий
    $admin_pages = ['/users.php', '/questions.php', '/results.php', '/answers.php'];
    $pos = strpos($_SERVER['REQUEST_URI'], "?");
    if ($pos) $page = substr($_SERVER['REQUEST_URI'], 0, $pos);
    else $page = $_SERVER['REQUEST_URI'];