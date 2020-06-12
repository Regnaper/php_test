<?php
    // Определяем на какой администраторской странице находится администратор
    if ($page == '/users.php') $users_nav = 'active';
    if ($page == '/questions.php') $questions_nav = 'active';
    if ($page == '/results.php') $results_nav = 'active';
?>

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?php echo $users_nav; ?>" href="/users.php">Военнослужащие</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $questions_nav; ?>" href="/questions.php">Вопросы</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $results_nav; ?>" href="/results.php">Результаты</a>
    </li>
</ul>
<script src="/public/js/checkboxes.js"></script>
<script src="/public/js/sorting.js"></script>
