<?php

    include('includes/db.php');
    include('includes/admin_cookie.php');

    // Удаление результатов
    $delete_results = $_POST['results'];
    if ($delete_results && !$_POST['sort']) {
        foreach ($delete_results as $delete_id => $value)
            if ($value) mysqli_query($connection,"DELETE FROM `results` WHERE `id` = $delete_id");
    }

    // Сортировка результатов
    $sort = ["`id`", "DESC"]; // Сортировка по умолчанию
    if ($_POST['last_sort']) $sort = $_POST['last_sort']; // Сохраняем предыдущую сортировку

    if ($_POST['sort']) { // При получении новой сортировки
        if ($_POST['sort'] == $sort[0]) { // Если сортировка по тому же столбцу меняем порядок
            if ($sort[1] == "ASC") $sort[1] = "DESC"; else $sort[1] = "ASC";
        } else $sort[1] = "ASC"; // Иначе сортируем по возрастанию
        $sort[0] = $_POST['sort']; // Принимаем новое значение сортировки
    }

    // Получение или сохранение количества вопросов для теста и порога прохождения из базы данных
    $question_count = $_POST['question_count'];
    $right_count = $_POST['right_count'];
    if ($question_count && $right_count) {
        mysqli_query($connection, "UPDATE `variables` SET `value` = '$question_count' WHERE `name` = 'question_count'");
        mysqli_query($connection, "UPDATE `variables` SET `value` = '$right_count' WHERE `name` = 'right_count'");
    } else {
        $question_count = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `variables` WHERE `name` = 'question_count'"))['value'];
        $right_count = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `variables` WHERE `name` = 'right_count'"))['value'];
    }


    // Получение результатов из базы
    $results = mysqli_query($connection, 'SELECT * FROM `results` ORDER BY ' . $sort[0] . ' ' . $sort[1]);

?>

    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <style>
            tr:target {
                background: #D3D3D3; /* Цвет фона якоря */
            }
        </style>
        <title>Математика/Результаты</title>
    </head>

    <body class="bg-light">
    <?php include('includes/header.php'); ?>
    <div class="container">
        <div class="py-5 text-center">
            <h2>Панель администратора</h2>
        </div>
        <?php include('includes/admin_navs.php'); ?>
    </div>
    <?php include('includes/admin_modals.html'); ?>
    <div class="container-fluid col-md-11 lead">
        <form method="POST" action="/results.php">
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th scope="col">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="all" name="all"
                                   onchange="setAll(this.checked, 'result');">
                            <label class="custom-control-label" for="all"></label>
                        </div>
                    </th>
                    <th scope="col">
                        <button class="btn btn-light" disabled>Военнослужащий</button>
                    </th>
                    <th scope="col">
                        <button class="btn btn-light" type="submit" onclick="changeSort('`created_at`');">Дата и время прохождения</button>
                    </th>
                    <th scope="col">
                        <button class="btn btn-light" type="submit" onclick="changeSort('`result`');">Результат</button>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                // Получение списка id результатов для checkbox js
                $results_ids = [];
                // Вывод таблицы результатов
                while($result = mysqli_fetch_assoc($results)) {
                    $result_id = $result['id'];
                    array_push($results_ids, $result_id); // Вносим id результата для checkbox js
                    echo '<tr id="result_row_' . $result['user_id'] . '">';
                        echo '<th scope="row">';
                            echo '<div class="custom-control custom-checkbox">';
                                echo '<input type="checkbox" class="custom-control-input" id="result_' . $result_id .
                                    '" name="results[' . $result_id . ']" onchange="setAllOff(this.checked, \'result\');">';
                                echo '<label class="custom-control-label" for="result_' . $result_id . '"></label>';
                            echo '</div>';
                        echo '</th>';
                        // Получение пользователя, за которым закреплен результат, из базы данных
                        $result_user_id = $result['user_id'];
                        // Подготовка списка вопросов к передаче строкой в GET
                        $questions_ids = htmlspecialchars($result['questions_ids'], ENT_QUOTES);
                        if ($result_user_id == 0) $result_user = [ 'rank' => 'Анонимное', 'name' => 'тестирование'];
                        else $result_user = mysqli_fetch_assoc(mysqli_query($connection,
                            "SELECT * FROM `users` WHERE `id` = $result_user_id"));
                        echo '<td>' . $result_user['rank'] . ' ' . $result_user['name'] . '</td>';
                        echo '<td>' . $result['created_at'] . '</td>';
                        echo '<td>';
                            // Ссылка на страницу результата пользователя
                            echo '<a class="btn btn-light" 
                                    href="/result.php?user=' . $result_user_id . '&questions=' . $questions_ids . '">';
                            echo $result['result'] . '</a>';
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
            <button class="btn btn-lg btn-outline-secondary" type="submit">Удалить выбранные</button>
            <hr>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Количество вопросов в тесте:</span>
                </div>
                <input type="text" class="form-control" name="question_count" value="<?php echo $question_count; ?>"/>
                <div class="input-group-prepend">
                    <span class="input-group-text">Количество необходимых верных ответов:</span>
                </div>
                <input type="text" class="form-control" name="right_count" value="<?php echo $right_count; ?>"/>
                <div class="input-group-prepend">
                    <button class="btn btn-lg btn-outline-secondary" type="submit">Изменить</button>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        itemsArray = <?php echo json_encode($results_ids) ?>; // Передаем все id вопросов в js
    </script>
    </body>
    </html>

<?php mysqli_close($connection); ?>