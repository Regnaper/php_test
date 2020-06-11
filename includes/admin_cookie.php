<?php
    // Получаем cookies из базы для сравнения
    $cookie = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `variables` WHERE `name` = 'cookie'"))['value'];

    // Проверка текущей страницы на возможность допуска к ней
    if (!in_array($page, $admin_pages)) {
        // При переходе на пользовательские страницы удаляем cookies
        unset($_COOKIE['zgt_admin']);
        setcookie('zgt_admin', null, -1, '/');
    } else if ($_COOKIE['zgt_admin'] != $cookie && $page != '/users.php') {
        // При переходе на страницы администратора при несоответствии cookie перенаправляем пользователя
        session_start();
        $_SESSION['message'] = 'У Вас не доступа к этой странице! Вы перенаправлены на главную страницу.';
        header('Location: index.php');
        exit();
    }