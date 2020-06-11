<!-- editUserModal -->
<div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="editUserTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserTitle">Редактировать данные военнослужащего</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/users.php">
                    <div class="form-group">
                        <input class="form-control" id="editNameInput" name="edit[name]" required>
                        <small class="form-text text-muted" id="editNameHelp">Ф.И.О. военнослужащего</small>
                        <br>
                        <select class="form-control custom-select" id="editRankInput" name="edit[rank]">
                            <?php
                            include('includes/ranks.php');
                            foreach ($ranks as $rank) {
                                echo '<option>' . $rank . '</option>';
                            }
                            ?>
                        </select>
                        <small class="form-text text-muted" id="editRankHelp">Звание военнослужащего</small>
                        <br>
                        <textarea class="form-control text-left" id="editPostInput" name="edit[post]"></textarea>
                        <small class="form-text text-muted" id="editPostHelp">Должность военнослужащего</small>
                        <br>
                        <select class="form-control custom-select" id="editDivisionInput" name="edit[division]">
                            <?php
                            include('includes/divisions.php');
                            foreach ($divisions as $division) {
                                echo '<option>' . $division . '</option>';
                            }
                            ?>
                        </select>
                        <small class="form-text text-muted" id="editDivisionHelp">Подразделение</small>
                    </div>
                    <hr>
                    <input type="hidden" id="editIdInput" name="edit[id]" value=""/>
                    <!-- Передача параметров сортировки -->
                    <input type="hidden" name="last_sort[0]" value="<?php echo $sort[0]; ?>"/>
                    <input type="hidden" name="last_sort[1]" value="<?php echo $sort[1]; ?>"/>
                    <button type="submit" class="btn btn-outline-secondary">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Заполнение полей окна редактирования пользователя
    $('#editUser').on('shown.bs.modal', function () {
        values = ['Name', 'Rank', 'Post', 'Division'];
        setValues(values, 'user', userId);
        $('#editNameInput').trigger('focus')
    })
</script>