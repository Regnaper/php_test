// Смена пароля (проверка на совпадение введенных паролей)
$('#changePassword').on('shown.bs.modal', function () { // Фокус на поле ввода пароля
    $('#changePasswordInput').trigger('focus')
})

password = document.getElementById("changePasswordInput");
confirm_password = document.getElementById("changePasswordConfirmInput");

function validatePassword() {
    if (password.value !== confirm_password.value) {
        confirm_password.setCustomValidity("Введенные пароли не совпадают");
    } else {
        confirm_password.setCustomValidity('');
    }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;


// Заполнение окна редактирования значениями
function setValues(values, item, itemId) {
    values.forEach(function(value){
        document.getElementById(`edit${value}Input`).value =
            document.getElementById(`${item}${value}_${itemId}`).innerText;
    });
    document.getElementById(`editIdInput`).value = itemId;
}

// Фокус на поле ввода пароля в окне аутентификации администратора
$('#auth').on('shown.bs.modal', function () {
    $('#password').trigger('focus')
})