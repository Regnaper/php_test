<?php

    //Установка cookies для работы в панели администратора
    if (!$_COOKIE['zgt_admin']) setcookie("zgt_admin", $cookie_hash = hash('ripemd160', time()));

    include('includes/db.php');

    //Запись cookies в базу данных
    if ($cookie_hash) mysqli_query($connection, "UPDATE `variables` SET `value` = '$cookie_hash' WHERE `name` = 'cookie'");

    include('includes/admin_cookie.php');

    // Получение пароля для верификации
    $password = $_POST['password'];
    $hash = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `variables` WHERE `name` = 'encoded_password'"))['value'];

    // Перенаправление при попытке входа без cookies или пароля
    if (!$_COOKIE['zgt_admin'] && !password_verify($password, $hash)) {
        session_start();
        $_SESSION['message'] = 'Неверный пароль! Вы перенаправлены на главную страницу.';
        header('Location: index.php');
        exit();
    }

    // Смена пароля
    $changePassword = $_POST['changePassword'];
    if ($changePassword) {
        $changePassword = password_hash($changePassword, PASSWORD_DEFAULT);
        mysqli_query($connection, "UPDATE `variables` SET `value` = '$changePassword' WHERE `name` = 'encoded_password'");
    }

    // Добавление пользователя
    if ($_POST['new']) {
        $new_user = $_POST['new_user'];
        if ($new_user) mysqli_query($connection,
            "INSERT INTO `users` (`name`, `rank`, `division`) VALUES ('$new_user', ' ', ' ')");
    }

    // Редактирование пользователей
    if ($_POST['edit']) {
        $edit_id = $_POST['edit']['id'];
        $edit_name = $_POST['edit']['name'];
        $edit_rank = $_POST['edit']['rank'];
        $edit_post = $_POST['edit']['post'];
        $edit_division = $_POST['edit']['division'];
        $sql = "UPDATE `users` SET `name` = '$edit_name', `rank` = '$edit_rank', `post` = '$edit_post', `division` = '$edit_division' WHERE `id` = $edit_id";
        mysqli_query($connection, $sql);
    }

    // Удаление пользователей и их результатов
    $delete_users = $_POST['users'];
    if ($_POST['delete'] && $delete_users) {
        foreach ($delete_users as $delete_id => $value)
            if ($value) {
                mysqli_query($connection,"DELETE FROM `users` WHERE `id` = $delete_id");
                mysqli_query($connection,"DELETE FROM `results` WHERE `user_id` = $delete_id");
            }
    }

    // Сортировка пользователей
    $sort = ["`name`", "ASC"]; // Сортировка по умолчанию
    if ($_POST['last_sort']) $sort = $_POST['last_sort']; // Сохраняем предыдущую сортировку

    if ($_POST['sort']) { // При получении новой сортировки
        if ($_POST['sort'] == $sort[0]) { // Если сортировка по тому же столбцу меняем порядок
            if ($sort[1] == "ASC") $sort[1] = "DESC"; else $sort[1] = "ASC";
        } else $sort[1] = "ASC"; // Иначе сортируем по возрастанию
        $sort[0] = $_POST['sort']; // Принимаем новое значение сортировки
    }

    // Получение пользователей из базы
    $users = mysqli_query($connection, 'SELECT * FROM `users` WHERE `id` > 0 ORDER BY ' . $sort[0] . ' ' . $sort[1]);

?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Математика/Военнослужащие</title>
    </head>

    <body class="bg-light">
        <?php include('includes/header.php'); ?>
        <div class="container">
            <div class="py-5 text-center">
                <h2>Панель администратора</h2>
            </div>
            <?php include('includes/admin_navs.php'); ?>
            <?php include('includes/edit_user_modal.php'); ?>
        </div>
        <?php include('includes/admin_modals.html'); ?>
        <div class="container-fluid col-md-11 lead">
            <form method="POST" action="/users.php">
                <table class="table table-bordered table-hover text-center">
                    <thead>
                    <tr>
                        <th scope="col">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="all" name="all"
                                       onchange="setAll(this.checked, 'user');">
                                <label class="custom-control-label" for="all"></label>
                            </div>
                        </th>
                        <th scope="col">
                            <button class="btn btn-light" type="submit" onclick="changeSort('`name`');">Ф.И.О.</button>
                        </th>
                        <th scope="col">
                            <button class="btn btn-light" type="submit" onclick="changeSort('`rank`');">Звание</button>
                        </th>
                        <th scope="col">
                            <button class="btn btn-light" type="submit" onclick="changeSort('`post`');">Должность</button>
                        </th>
                        <th scope="col">
                            <button class="btn btn-light" type="submit" onclick="changeSort('`division`');">Подразделение</button>
                        </th>
                        <th scope="col">
                            <button class="btn btn-light" disabled>Результат</button>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        // Получение списка id пользователей для checkbox js
                        $users_ids = [];
                        // Вывод таблицы пользователей
                        while($user = mysqli_fetch_assoc($users)) {
                            $user_id = $user['id'];
                            array_push($users_ids, $user_id); // Вносим id пользователя для checkbox js
                            $user_result = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `results` WHERE `user_id` = $user_id"));
                            echo '<tr>';
                                echo '<th scope="row">';
                                    echo '<div class="custom-control custom-checkbox">';
                                        echo '<input type="checkbox" class="custom-control-input" id="user_' . $user_id .
                                            '" name="users[' . $user_id . ']" onchange="setAllOff(this.checked, \'user\');">';
                                        echo '<label class="custom-control-label" for="user_' . $user_id . '"></label>';
                                    echo '</div>';
                                echo '</th>';
                                echo '<td id="userName_' . $user_id . '" data-toggle="modal" data-target="#editUser" onclick=
                                    "userId = ' . $user_id . ';">' . $user['name'];
                                echo '</td>';
                                echo '<td id="userRank_' . $user_id . '" >' . $user['rank'] . '</td>';
                                echo '<td id="userPost_' . $user_id . '" >' . $user['post'] . '</td>';
                                echo '<td id="userDivision_' . $user_id . '" >' . $user['division'] . '</td>';
                                echo '<td>';
                                    // Ссылка на страницу результатов на пользователя
                                    if ($user_result) {
                                        echo '<a class="btn btn-light" href="/results.php#result_row_' . $user_id . '">';
                                        echo $user_result['result'] . '</a>';
                                    }
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
                    <input type="text" class="form-control text-right" placeholder="Ф.И.О. военнослужащего" name="new_user"/>
                    <div class="input-group-prepend">
                        <button class="btn btn-lg btn-outline-secondary" type="submit" onclick="newItem();">Добавить военнослужащего</button>
                    </div>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            itemsArray = <?php echo json_encode($users_ids) ?>; // Передаем все id пользователей в js
        </script>
    </body>
</html>

<?php mysqli_close($connection); ?>