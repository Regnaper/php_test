<script src="/vendor/js/jquery-3.1.1.min.js"></script>
<link rel="stylesheet" href="/vendor/css/bootstrap.min.css">
<script src="/vendor/js/bootstrap.min.js"></script>
<nav class="navbar navbar-expand-lg navbar-light bg-light d-print-none container">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="btn btn-light right" href="../index.php">На главную <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="btn btn-light right" href="../add_user.php">Добавить военнослужащего</a>
            </li>
        </ul>
        <div class="form-inline my-2 my-lg-0">
            <?php
                echo '<button type="button" class="btn btn-light right" data-toggle="modal" data-target=';
                // Установка в header кнопки для входа или смены пароля
                if (!in_array($page, $admin_pages)) {
                    echo '"#auth">Администратор</button>';
                } else {
                    echo '"#changePassword">Сменить пароль</button>';
                }
            ?>
        </div>
    </div>
</nav>