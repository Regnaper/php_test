<?php
    include('includes/db.php');
    include('includes/admin_cookie.php');
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Математика/Тест уже был пройден!</title>
    </head>

    <body class="bg-light">
        <?php include('includes/header.php'); ?>
        <div class="container">
            <div class="py-5 text-center">
                <h2>КОНТРОЛЬНЫЕ ВОПРОСЫ</h2>
                <p class="lead">для сдачи зачета на знание курса математики 1-4 классов</p>
            </div>
            <p class="lead">Вы уже сдавали зачет, обратитесь к администратору для назначения пересдачи.</p>
            <hr>
            <a class="btn btn-lg btn-outline-secondary" href="index.php"">Назад</a>
        </div>
        <?php include('includes/admin_modals.html'); ?>
    </body>
</html>

<?php mysqli_close($connection); ?>