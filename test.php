<?php

    include('includes/db.php');
    include('includes/admin_cookie.php');

    $user_id = $_POST['user'];

    // Удаление результата анонимного пользователя из базы
    if (!$user_id) mysqli_query($connection, "DELETE FROM `results` WHERE `user_id` = 0");

    // Выбор категории вопросов (продвинутые или стандартные)
    $first = 0;
    if ($_POST['first'] == 'on') $first = 1;
    if ($_GET['first'] == 'on') $first = 1;

    // Получение количества вопросов для теста из базы данных
    $question_count = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `variables` WHERE `name` = 'question_count'"))['value'];

    // Получение вопросов из базы данных
    $questions = mysqli_query($connection, "SELECT * FROM `questions` WHERE `first` = $first ORDER BY RAND() LIMIT $question_count");

    include('includes/passed.php');

?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Математика/Тест</title>
    </head>

    <body class="bg-light">
        <?php include('includes/header.php'); ?>
        <div class="container">
            <div class="py-5 text-left">
                <p class="lead">Для сдачи зачета ответьте как минимум на 7 из 10-ти вопросов</p>
            </div>
            <form method="POST" action="/result.php">
                <?php while($question = mysqli_fetch_assoc($questions)) { // Вывод вопросов и вариантов ответов к ним
                    $question_id = $question['id'];
                    $answers = mysqli_query($connection, "SELECT * FROM `answers` WHERE `question_id` = '$question_id' ORDER BY RAND()");
                    echo '<hr><h4>' . $question['question'] . '</h4>';
                    echo '<div class="list-group">';
                    while($answer = mysqli_fetch_assoc($answers)) {
                        $answer_id = $answer['id'];
                        $answer_text = $answer['answer'];
                        echo '<div class="list-group-item list-group-item-action">';
                            echo '<div class="custom-control custom-radio">
                                      <input class="custom-control-input" type="radio" name="questions[' . $question_id . ']" 
                                        id="' .  $question_id . '_' . $answer_id . '" value="' . $answer_id . '" checked>
                                      <label class="custom-control-label container" for="' .  $question_id . '_' . $answer_id . '">
                                        ' . $answer_text . '
                                      </label>
                                  </div>';
                        echo '</div>';
                    }
                    echo '</div>';
                }?>
                <hr>
                <input name="user" hidden value="<?php echo $user_id; ?>"/>
                <button class="btn btn-lg btn-outline-secondary" type="submit">Готово</button>
            </form>
        </div>
        <br>
        <?php include('includes/admin_modals.html'); ?>
    </body>
</html>

<?php mysqli_close($connection); ?>