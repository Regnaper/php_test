<?php

include('includes/db.php');
include('includes/admin_cookie.php');

$add = $_POST['add'];
$name = $_POST['name'];
$rank = $_POST['rank'];
$post = $_POST['post'];
$division = $_POST['division'];

?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Математика/Добавить военнослужащего</title>
    </head>

    <body class="bg-light">
        <?php
            include('includes/header.php');
            if ($add) {
                if (mysqli_query($connection, "INSERT INTO `users` (`name`, `rank`, `post`, `division`) VALUES ('$name', '$rank', '$post', '$division')")) {
                    echo '<div class="container lead alert-success">Военнослужащий успешно добавлен</div>';
                } else echo '<div class="container lead alert-danger">Не удалось добавить военнослужащего</div>';
            }
        ?>
        <div class="container">
            <div class="py-5 text-center">
                <h2>Добавление военнослужащего</h2>
                <p class="lead">Заполните форму и нажмите кнопку "Добавить"</p>
            </div>
            <form class="container" method="post" action="add_user.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="Ф.И.О." required>
                    <small id="nameHelp" class="form-text text-muted">Введите полные фамилию, имя и отчество</small>
                </div>
                <div class="form-group">
                    <select class="form-control custom-select" name="rank">
                        <?php
                            include('includes/ranks.php');
                            foreach ($ranks as $rank) {
                                echo '<option>' . $rank . '</option>';
                            }
                        ?>
                    </select>
                    <small id="rankHelp" class="form-text text-muted">Выберите звание</small>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="post" placeholder="Должность">
                    <small id="postHelp" class="form-text text-muted">Введите полное название должности без указания подразделения</small>
                </div>
                <div class="form-group">
                    <select class="form-control custom-select" name="division">
                        <?php
                        include('includes/divisions.php');
                        foreach ($divisions as $division) {
                            echo '<option>' . $division . '</option>';
                        }
                        ?>
                    </select>
                    <small id="divisionHelp" class="form-text text-muted">Выберите подразделение</small>
                </div>
                <input hidden name="add" value="true"/>
                <hr>
                <button class="btn btn-lg btn-outline-secondary" type="submit">Добавить</button>
            </form>
        </div>
        <?php include('includes/admin_modals.html'); ?>
    </body>
</html>