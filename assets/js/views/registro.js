$(function () {
    mask_form();
});

function mask_form() {
    $("input[name=telefone]").mask("(99)9999-9999");
}