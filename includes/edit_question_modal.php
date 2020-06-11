<!-- editQuestionModal -->
<div class="modal fade" id="editQuestion" tabindex="-1" role="dialog" aria-labelledby="editQuestionTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editQuestionTitle">Редактировать вопрос</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/questions.php">
                    <div class="form-group">
                        <textarea class="form-control text-left" id="editQuestionInput" name="edit[question]" required></textarea>
                        <small class="form-text text-muted" id="editQuestionHelp">Вопрос</small>
                        <br>
                        <select class="form-control custom-select" id="editFirstInput" name="edit[first]">
                            <option>Стандартный</option>
                            <option>Повышенной сложности</option>
                        </select>
                        <small class="form-text text-muted" id="editFirstHelp">Категория вопроса</small>
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
    // Заполнение полей окна редактирования вопроса
    $('#editQuestion').on('shown.bs.modal', function () {
        values = ['Question', 'First'];
        setValues(values, 'question', questionId);
        $('#editQuestionInput').trigger('focus')
    })
</script>