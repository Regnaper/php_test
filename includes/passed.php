<?php

    // Если пользователь имеет результат теста перенаправляем его на информационную страницу
    $result = mysqli_query($connection, "SELECT * FROM `results` WHERE `user_id` = '$user_id'");
    if (mysqli_num_rows($result) > 0) {
        header('Location: passed.php');
        exit();
    }