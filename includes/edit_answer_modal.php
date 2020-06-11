<!-- editAnswerModal -->
<div class="modal fade" id="editAnswer" tabindex="-1" role="dialog" aria-labelledby="editAnswerTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAnswerTitle">Редактировать ответ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/answers.php">
                    <div class="form-group">
                        <textarea class="form-control text-left" id="editAnswerInput" name="edit[answer]" required></textarea>
                        <small class="form-text text-muted" id="editAnswerHelp">Ответ</small>
                    </div>
                    <hr>
                    <input type="hidden" id="editIdInput" name="edit[id]" value=""/>
                    <input type="hidden" id="modal_question" name="question" value=""/>
                    <button type="submit" class="btn btn-outline-secondary">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Заполнение полей окна редактирования ответа
    $('#editAnswer').on('shown.bs.modal', function () {
        document.getElementById('modal_question').value = document.getElementById('question').value;
        values = ['Answer'];
        setValues(values, 'answer', answerId);
        $('#editAnswerInput').trigger('focus')
    })
</script>