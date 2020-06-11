<?php

    include('includes/db.php');
    include('includes/admin_cookie.php');

    // Массив категорий вопросов
    $question_categories = ['Стандартный', 'Повышенной сложности'];

    // Добавление вопроса
    if ($_POST['new']) {
        $new_question = $_POST['new_question'];
        if ($new_question) mysqli_query($connection,
            "INSERT INTO `questions` (`question`, `first`) VALUES ('$new_question', 0)");
    }

    // Редактирование вопросов
    if ($_POST['edit']) {
        $edit_id = $_POST['edit']['id'];
        $edit_question = $_POST['edit']['question'];
        $edit_first = array_search($_POST['edit']['first'], $question_categories);
        $sql = "UPDATE `questions` SET `question` = '$edit_question', `first` = '$edit_first' WHERE `id` = $edit_id";
        mysqli_query($connection, $sql);
    }

    // Удаление вопросов и их ответов
    $delete_questions = $_POST['questions'];
    if ($_POST['delete'] && $delete_questions) {
        foreach ($delete_questions as $delete_id => $value)
            if ($value) {
                mysqli_query($connection,"DELETE FROM `questions` WHERE `id` = $delete_id");
                mysqli_query($connection,"DELETE FROM `answers` WHERE `question_id` = $delete_id");
            }
    }

    // Сортировка вопросов
    $sort = ["`id`", "DESC"]; // Сортировка по умолчанию
    if ($_POST['last_sort']) $sort = $_POST['last_sort']; // Сохраняем предыдущую сортировку

    if ($_POST['sort']) { // При получении новой сортировки
        if ($_POST['sort'] == $sort[0]) { // Если сортировка по тому же столбцу меняем порядок
            if ($sort[1] == "ASC") $sort[1] = "DESC"; else $sort[1] = "ASC";
        } else $sort[1] = "ASC"; // Иначе сортируем по возрастанию
        $sort[0] = $_POST['sort']; // Принимаем новое значение сортировки
    }

    // Получение вопросов из базы
    $questions = mysqli_query($connection, 'SELECT * FROM `questions` ORDER BY ' . $sort[0] . ' ' . $sort[1]);

?>

    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <style>
            tr:target {
                background: #D3D3D3; /* Цвет фона якоря */
            }
        </style>
        <title>Математика/Вопросы</title>
    </head>

    <body class="bg-light">
    <?php include('includes/header.php'); ?>
    <div class="container">
        <div class="py-5 text-center">
            <h2>Панель администратора</h2>
        </div>
        <?php include('includes/admin_navs.php'); ?>
        <?php include('includes/edit_question_modal.php'); ?>
    </div>
    <?php include('includes/admin_modals.html'); ?>
    <div class="container-fluid col-md-11 lead">
        <form method="POST" action="/questions.php">
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th scope="col">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="all" name="all"
                                   onchange="setAll(this.checked, 'question');">
                            <label class="custom-control-label" for="all"></label>
                        </div>
                    </th>
                    <th scope="col">
                        <button class="btn btn-light" type="submit" onclick="changeSort('`question`');">Вопрос</button>
                    </th>
                    <th scope="col">
                        <button class="btn btn-light" type="submit" onclick="changeSort('`first`');">Категория</button>
                    </th>
                    <th scope="col">
                        <button class="btn btn-light" disabled>Количество ответов</button>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                // Получение списка id вопросов для checkbox js
                $questions_ids = [];
                // Вывод таблицы вопросов
                while($question = mysqli_fetch_assoc($questions)) {
                    $question_id = $question['id'];
                    array_push($questions_ids, $question_id); // Вносим id вопроса для checkbox js
                    $question_answers_count = mysqli_fetch_assoc(mysqli_query($connection,
                        "SELECT COUNT(*) as 'count' FROM `answers` WHERE `question_id` = $question_id"))['count'];
                    echo '<tr id="question_row_' . $question_id . '">';
                        echo '<th scope="row">';
                            echo '<div class="custom-control custom-checkbox">';
                                echo '<input type="checkbox" class="custom-control-input" id="question_' . $question_id .
                                    '" name="questions[' . $question_id . ']" onchange="setAllOff(this.checked, \'question\');">';
                                echo '<label class="custom-control-label" for="question_' . $question_id . '"></label>';
                            echo '</div>';
                        echo '</th>';
                        echo '<td id="questionQuestion_' . $question_id . '" data-toggle="modal" data-target="#editQuestion" 
                                onclick="questionId = ' . $question_id . ';">' . $question['question'] . '</td>';
                        echo '<td id="questionFirst_' . $question_id . '" >' . $question_categories[$question['first']] . '</td>';
                        echo '<td>';
                            echo '<a class="btn btn-light" href="/answers.php?question=' . $question_id . '">' . $question_answers_count . '</a>';
                        echo '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
            <hr>
            <!-- Передача параметров сортировки -->
            <input type="hidden" name="last_sort[0]" id="last_sort[0]" value="<?php echo $sort[0]; ?>"/>
            <input type="hidden" name="last_sort[1]" id="last_sort[1]" value="<?php echo $sort[1]; ?>"/>
            <input type="hidden" name="sort" id="sort" value=""/>
            <!-- Передача типа запроса -->
            <input hidden name="delete" id="delete" value=""/>
            <input hidden name="new" id="new" value=""/>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <button class="btn btn-lg btn-outline-secondary" type="submit" onclick="deletePost();">Удалить выбранные</button>
                </div>
                <input type="text" class="form-control text-right" placeholder="Вопрос" name="new_question"/>
                <div class="input-group-prepend">
                    <button class="btn btn-lg btn-outline-secondary" type="submit" onclick="newItem();">Добавить вопрос</button>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        itemsArray = <?php echo json_encode($questions_ids) ?>; // Передаем все id вопросов в js
    </script>
    </body>
    </html>

<?php mysqli_close($connection); ?>