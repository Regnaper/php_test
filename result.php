<?php

    include('includes/db.php');

    if ($_GET['user'] != null) { // При переходе через GET получаем данные, подготавливаем их и пропускаем проверку cookies
        $user_id = $_GET['user'];
        $questions = unserialize(htmlspecialchars_decode($_GET['questions'], ENT_QUOTES));
    } else {
        $user_id = $_POST['user'];
        include('includes/passed.php');
        $questions = $_POST['questions'];
    }

    // Получение пользователя прошедшего тест для дальнейшей записи в базу для невозможности поменять результат прохождения
    if (!$user_id) $user_id = 0; // Анонимное прохождение
    if ($user_id == 0) $user = ['', '', '', '', ''];
    // Получение пользователя из базы данных
    else $user = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$user_id'"));

    // Получение порога прохождения зачета
    $right_count_need = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `variables` WHERE `name` = 'right_count'"))['value'];
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Математика/Результат</title>
    </head>

    <body class="bg-light">
        <?php include('includes/header.php'); ?>
        <div class="container">
            <div class="text-center">
                <h4>Результаты зачета на знание курса математики 1-4 классов</h4><br>
                <br><p class="lead"><?php echo $user['post']; ?></p>
                <p class="border-top border-dark">(должность сдающего)</p>
                <p class="lead" style="white-space: pre-wrap;">
                    <?php echo $user['rank'] . "                                          " . $user['name']; ?>
                </p>
                <p class="border-top border-dark">(звание, роспись, Ф.И.О. сдающего)</p>
            </div>
            <h5>Ответы сдающего:</h5>
            <table class="table table-bordered table-hover container">
                <tbody>
                    <?php
                    // Вывод результатов теста
                    $right_count = 0;
                    foreach ($questions as $question_id => $answer_id) {
                        $question =  mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `questions` WHERE `id` = '$question_id'"));
                        $answer =  mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `answers` WHERE `id` = '$answer_id'"));
                        echo '<tr><td>' . $question['question'] . '</td><td>';
                        if ($answer['right_answer']) {
                            $right_count++;
                            echo '<b>верно</b>' . '</td></tr>';
                        } else {
                            echo '<b>неверно</b>' . '</td></tr>';
                        }
                    }
                    // Сохранение результата в базу данных
                    if ($_GET['user'] == null) {
                        $questions = serialize($questions);
                        mysqli_query($connection,
                            "INSERT INTO `results` (`result`, `user_id`, `questions_ids`) VALUES ($right_count, $user_id, '$questions')");
                    } $right_count = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `results` WHERE `user_id` = '$user_id'"))['result'];
                    ?>
                </tbody>
            </table>
            <hr>
            <div class="h5">
                Итого правильных ответов: <?php echo $right_count; ?><br>
                Результат: <?php echo ($right_count < $right_count_need) ? 'незачёт' : 'зачёт'; ?>
                <p class="lead">Дата: <?php echo date('d.m.Y'); ?></p>
            </div>
            <div class="text-center">
                <br><br><p class="border-top border-dark">(должность лица, принимавшего зачет)</p>
                <br><br><p class="border-top border-dark">(звание, роспись, фамилия, инициалы лица, принимавшего зачет)</p>
            </div>
            <div class="input-group mb-3 d-print-none">
                <div class="input-group-prepend">
                    <input class="btn btn-lg btn-outline-secondary" type="button" value="Печать" onclick="print()"/>
                    <?php
                        echo '<a class="btn btn-lg btn-outline-secondary" ';
                        if ($_GET['user'] != null) echo 'href="/results.php#result_row_' . $user_id . '">Назад</a>';
                        else echo 'href="/index.php">На главную</a>';
                    ?>
                </div>
            </div>
            <br>
        </div>
        <?php include('includes/admin_modals.html'); ?>
    </body>
</html>

<?php mysqli_close($connection); ?>