<?php

include('includes/db.php');
include('includes/admin_cookie.php');

session_start(); // Оповещение о неудачном входе в панель администратора
if(!empty($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Получение списка пользователей из базы
$users = mysqli_query($connection, "SELECT * FROM users ORDER BY `name`");

?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <script src="vendor/js/checkboxes.js"></script>
        <title>Математика</title>
    </head>

    <body class="bg-light">
        <?php include('includes/header.php'); ?>
        <?php echo '<div class="container lead alert-danger">' . $message . '</div>'; ?>
        <div class="container">
            <div class="py-5 text-center">
                <h2>КОНТРОЛЬНЫЕ ВОПРОСЫ</h2>
                <p class="lead">для сдачи военнослужащими зачета на знание курса математики 1-4 классов</p>
            </div>
            <p class="lead mb-3">Выберите сдающего и нажмите кнопку "Начать тестирование":</p>
            <form method="POST" action="/test.php">
                <select class="custom-select d-block w-100" name="user" required="">
                    <?php
                    while($user = mysqli_fetch_assoc($users)) {
                        echo '<hr><option value="' . $user['id'] . '">'
                            . $user['rank'] . ' ' . $user['name'] . ', ' . $user['division'] . '</option>';
                    }
                    ?>
                </select>
                <br>
                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" id="first" name="first" onchange="setFirstTest(this.checked);">
                    <label class="custom-control-label" for="first">Вопросы повышенной сложности</label>
                </div>
                <hr class="mb-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="btn btn-lg btn-outline-secondary" type="submit">Начать тестирование</button>
                    </div>
                    <input type="text" class="form-control" disabled/>
                    <div class="input-group-prepend">
                        <a class="btn btn-lg btn-outline-secondary" id="testTest" href="/test.php">Анонимное тестирование</a>
                    </div>
                </div>
            </form>
        </div>
        <?php include('includes/admin_modals.html'); ?>
    </body>
</html>

<?php mysqli_close($connection); ?>