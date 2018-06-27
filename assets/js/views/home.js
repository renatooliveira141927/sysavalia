$(function() {
    $("#btn_note").tooltip();
})

function modalPass() {
    var $form = $('#formRecupSenha');
    var options = {
        'title': 'Recuperação de senha',
        'size': 'md',
        'execute': submeter,
        'params': $form
    };
    $form.ShowAsModal(options).show();
}

function submeter(form) {
    form.submit();
}