$(document).ready(function() {
    $("#txtEditor").Editor();
});

function adicionaItensAvaliacao(){
    alert('aaa');
    var table =$("#dadosAlternativa");
    var alt = $("#alternativa");
    var resp = $("#resposta");
    var analise = $("#analise");
    var ciTr = $("#dadosAlternativa tbody tr").length + 1;

    var addLinha = true;
    if(addLinha) {
        var linha = "<tr id='_" + ciTr + "'>" +
            "<td style='vertical-align: middle;'>" + alt.val() + "</td>" +
            "<input type='hidden' name='altenativa[]' value='" + alt.val() + "'>" +
            "<td style='vertical-align: middle;'>" + resp.val() + "</td>" +
            "<input type='hidden' name='resposta[]' value='" + resp.val() + "'>" +
            "<td style='vertical-align: middle;text-align: center; max-width: 500px;'>" + analise.val() + "</td>" +
            "<input type='hidden' name='analise[]' value='" + analise.val() + "'>" +
            "</tr>";

        table.append(linha);
        alt.val("");
        $("#alternativa").val("");
        resp.val("");
        $("#resposta").val("");
        analise.val("");
        $("#analise").val("");
    }
}
