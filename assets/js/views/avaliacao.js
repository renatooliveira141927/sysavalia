$(function(){
    $(".date").mask("99/99/9999");
    $('.datetimepicker').datetimepicker({pickTime:false,language:'pt-br',useCurrent:false});
});

function adicionaItensAvaliacao(){
	var table =$("#dadosgabaritoAdd");
	var nr_linhas= $("#nr_linhas").val();
	var i=1;
	var linhas;
	var ciTr = $("#dadosgabaritoAdd tbody tr").length;

    while(i<=nr_linhas){
        linhas+="<tr id='_"+(ciTr+i)+"'>" +
            "<td style='vertical-align: middle;'>"+i+"</td>" +
            "<input type='hidden' name='item[]' value='"+i+"'>" +
            "<td style='vertical-align: middle;'>"+
            "<input type='text' size='1' class='form-control' id='resposta[]' name='resposta_"+i+"'" +
            "	onblur='changeDescritor(this)'/></td>" +
            "<td style='vertical-align: middle;text-align: center; max-width: 500px;'>"+
            "<select class='chosen-select form-control descritor_"+i+"' name='descritor_"+i+"' id='descritor[]'>"+
            "<option value='0'>Selecione um Descritor</option>" +
            "<option value='1'>A</option>";
        "</select>"+
        "<input type='text' id='descritor_"+i+" name='descritor_"+i+"/></td>"
        +"</tr>";
        i++;
    }
    table.append(linhas);
}

function changeDescritor(obj){
    var nome= obj.name;
    var objeto=nome.substr(nome.indexOf('_')+1);
    var descritor = $('.descritor_'+objeto).attr('name');
    var disciplina =$('#disciplina').val();
    if(descritor!=""){
        var url_request = $('#base_url').val();
        console.log(url_request);

        $.ajax({
            type: 'POST',
            url: url_request,
            data: {disciplina: disciplina}
        }).done(function(html){
            $('.descritor_'+objeto).html(html);
        });
    }
}

$('#municipio').change(function () {
    var municipio = $('#municipio option:selected').val();
    console.log(municipio);
    if(municipio != 0){
        var url_request = $('#base_url');
        console.log(url_request);

        $.ajax({
            type: 'POST',
            url: url_request,
            data: {municipio: municipio}
        }).done(function(html){
            $('#curso').html(html);
        });
    }
});

