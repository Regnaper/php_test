<?php

    include('includes/db.php');
    include('includes/admin_cookie.php');

    if ($_GET['question']) $question_id = $_GET['question'];
    if ($_POST['question']) $question_id = $_POST['question'];

    // Добавление ответа
    if ($_POST['new']) {
        $new_answer = $_POST['new_answer'];
        if ($new_answer) mysqli_query($connection,
            "INSERT INTO `answers` (`answer`, `question_id`, `right_answer`) VALUES ('$new_answer', $question_id, 0)");
    }

    // Удаление ответов
    $delete_answers = $_POST['answers'];
    if ($_POST['delete'] && $delete_answers) {
        foreach ($delete_answers as $delete_id => $value)
            if ($value) {
                mysqli_query($connection,"DELETE FROM `answers` WHERE `id` = $delete_id");
            }
    }

    // Редактирование ответов
    if ($_POST['edit']) {
        $edit_id = $_POST['edit']['id'];
        $edit_answer = $_POST['edit']['answer'];
        $sql = "UPDATE `answers` SET `answer` = '$edit_answer' WHERE `id` = $edit_id";
        mysqli_query($connection, $sql);
    }

    // Обновление правильного ответа
    $right_answer = $_POST['right_answer'];
    $last_answer = $_POST['last_answer'];
    if ($right_answer != $last_answer) {
        mysqli_query($connection, "UPDATE `answers` SET `right_answer` = 0 WHERE `id` = $last_answer");
        mysqli_query($connection, "UPDATE `answers` SET `right_answer` = 1 WHERE `id` = $right_answer");
    }

    // Получение из базы вопроса и его ответов
    if ($question_id) {
        $question = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `questions` WHERE `id` = $question_id"));
        $answers = mysqli_query($connection, "SELECT * FROM `answers` WHERE `question_id` = $question_id");
    }


?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <script src="/public/js/checkboxes.js"></script>
    <title>Математика/Ответы</title>
</head>

<body class="bg-light">
    <?php include('includes/header.php'); ?>
    <div class="container">
        <div class="py-5 text-center">
            <h2>Ответы на вопрос:</h2>
            <p class="lead"><?php echo $question['question']; ?></p>
        </div>
    </div>
    <?php include('includes/admin_modals.html'); ?>
    <?php include('includes/edit_answer_modal.php'); ?>
    <div class="container-fluid col-md-11 lead">
        <form method="POST" action="/answers.php">
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th scope="col">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="all" name="all"
                                   onchange="setAll(this.checked, 'answer');">
                            <label class="custom-control-label" for="all"></label>
                        </div>
                    </th>
                    <th scope="col" class="col-md-10">Ответ</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                // Получение списка id ответов для checkbox js
                $answers_ids = [];
                // Вывод таблицы ответов
                while($answer = mysqli_fetch_assoc($answers)) {
                    $answer_id = $answer['id'];
                    array_push($answers_ids, $answer_id); // Вносим id ответа для checkbox js
                    echo '<tr>';
                        echo '<th scope="row">';
                            echo '<div class="custom-control custom-checkbox">';
                                echo '<input type="checkbox" class="custom-control-input" id="answer_' . $answer_id .
                                    '" name="answers[' . $answer_id . ']" onchange="setAllOff(this.checked, \'answer\');">';
                                echo '<label class="custom-control-label" for="answer_' . $answer_id . '"></label>';
                            echo '</div>';
                        echo '</th>';
                        echo '<td id="answerAnswer_' . $answer_id . '" data-toggle="modal" data-target="#editAnswer" 
                                        onclick="answerId = ' . $answer_id . ';">' . $answer['answer'] . '</td>';
                        echo '<td>';
                            // Кнопка-чекбокс для сохранения правильного ответа
                            echo '<button class="custom-control custom-radio btn btn-link" type="submit">
                                      <input class="custom-control-input" type="radio" name="right_answer" 
                                        id="questionAnswer_' . $answer_id . '" value="' . $answer_id . '"';
                                        if ($answer['right_answer'] == 1) { echo 'checked'; $right_answer = $answer_id; } echo '>';
                                      echo '<label class="custom-control-label container" for="questionAnswer_' . $answer_id . '"></label>';
                            echo '</button>';
                        echo '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
            <input hidden id="question" name="question" value="<?php echo $question_id; ?>"/>
            <!-- Передача типа запроса -->
            <input hidden name="delete" id="delete" value=""/>
            <input hidden name="new" id="new" value=""/>
            <input hidden name="last_answer" value="<?php echo $right_answer; ?>"/>
            <hr>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <button class="btn btn-lg btn-outline-secondary" type="submit" onclick="deletePost();">Удалить выбранные</button>
                    <a class="btn btn-lg btn-outline-secondary"
                       href="/questions.php#question_row_<?php echo $question_id; ?>">Назад</a>
                </div>
                <input type="text" class="form-control text-right" placeholder="Ответ" name="new_answer"/>
                <div class="input-group-prepend">
                    <button class="btn btn-lg btn-outline-secondary" type="submit" onclick="newItem();">Добавить ответ</button>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        itemsArray = <?php echo json_encode($answers_ids) ?>; // Передаем все id вопросов в js
    </script>
</body>
</html>

<?php mysqli_close($connection); ?>
