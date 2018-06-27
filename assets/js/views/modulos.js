/**
 * Created by paulo.roberto on 20/11/2017.
 */
$(document).ready(function(){
    load();
});

function displayResult(item) {
    //console.log(item);
    $("#id_disciplina").val(item.value);
}

function load() {

    $(".dial").knob();
    carregarKnob("#chpresencial_graf", $(".chpresencial").text());
    carregarKnob("#chdistancia_graf", $(".chdistancia").text());

    $(".btn_remove_disc").click(function() {
        var id = $(this).data('id');
        swal({
            title: "Você tem certeza?",
            text: "O registro será excluído definitivamente!",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Cancelar!",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, excluir!",
            closeOnConfirm: false
        }, function () {
            transation('remove',{id: id});
        });
    });

    $(".btn_disciplina").click(function() {
        carregarTabel($(this));
        $('.typeahead_1').typeahead({
            ajax: {
                url: $("#url").val() + '/get_disciplina',
                method: 'get',
                triggerLength: 1,
                timeout: 500,
                displayField: "name",
                loadingClass: "loading-circle",
                preDispatch: function (query) {
                    preloading(true);
                    return {
                        term: query
                    }
                },
                preProcess: function (data) {
                    var res = [];
                    $.each(data,function (i, item) {
                        var element = {};
                        element.id = item.ci_disciplina;
                        element.name = item.ds_disciplina;
                        res.push(element);
                    });
                    preloading(false);
                    return res;
                }
            },
            onSelect: displayResult
        }).bind('typeahead:closed', function () {
            $(this).val("");
        });
    });

}

function carregarTabel(obj) {
    var data = {id: obj.data('id')};
    var res = transation('load_disciplina',data);
    var table = $("#table_disciplina tbody");
    var tr = "";

    table.find("tr").remove();

    if(res.status == 200){
        var obj = res.responseJSON;
        if(obj.num_registro === 0){
            tr += "<tr class=\"table-danger tr_messeger\"><td colspan='5' class='text-center'><label><h4>Nenhum registro encontrado</h4></label></td></tr>";
        }else{
            obj = obj.result;
            $.each(obj, function (i, item) {
                var buttonEdit = "<button class='btn btn-primary'><i class='fa fa-edit'></i></button>";
                var buttonRemove = "<button class='btn btn-danger'><i class='fa fa-trash'></i></button>";

                var tr = "<tr>" +
                    "<td>"+item.cd_disciplina+"</td>" +
                    "<td>"+item.ds_disciplina+"</td>" +
                    "<td class='text-center'>"+item.nr_chpresencial+"</td>" +
                    "<td class='text-center'>"+item.nr_chadistancia+"</td>" +
                    "<td class='text-center'>"+buttonEdit+"</td>"+
                    "<td class='text-center'>"+buttonRemove+"</td>"+
                    "</tr>";

                var obj_table = $("#table_disciplina");
                obj_table.find('tbody tr.tr_messeger').remove();
                obj_table.find('tbody').append(tr);
            });

        }
    }else{
        alert_send("Erro!","Ocorreu erro inesperado. Contate o administrador do sistema.","error");
        tr += "<tr class=\"table-danger tr_messeger\"><td colspan='5' class='text-center'><label><h4><i class='fa fa-exclamation-circle'></i> Erro Inesperado!</h4></label></td></tr>";
    }

    table.append(tr);
    limparform($('#form-disciplina'));
    $("#id_modulo").val(data.id);
    $("#modal-form-disc").modal('show');
}

function preloading(bool) {
    $("#loading_button i").remove();
    if(bool){
        $("#loading_button").append("<i class=\"fa fa-spinner fa-spin fa-fw\"></i>");
    }else{
        $("#loading_button").append("<i class=\"fa fa-search\"></i>");
    }
}

function carregarKnob(obj, valor) {
    var total = parseInt(valor);
    var div = $(obj).val();
    var perc = div/total*100;

    $(obj).val(perc).trigger('change');
}

function transation(action,obj){
    if(action == 'save'){
        var url = $("#url").val()+'/addmodulo';
        var ajax = $.ajax({
            type: "post",
            url: url,
            data: obj,
            async: false,
            dataType: "json",
            success: function (json) {
                //console.log(json);
                if(json.valid){
                    $(".tr_messeger").remove();
                    alert_send("Sucesso!","Adicionado modulo "+obj.modulo+" com sucesso","success");
                }else{
                    alert_send("Erro!",json.messeger,"error");
                }
            }
        });
        return ajax.responseJSON;
    }else if(action == 'remove'){
        var url = $("#url").val()+'/remove_modulo';
        $.ajax({
            type: "post",
            url: url,
            data: obj,
            async: false,
            dataType: "json",
            success: function (json) {
                //console.log(json);
                if(json.valid){
                    alert_send("Sucesso!",json.messeger,"success");
                    var tr = $('#tr_'+json.id);
                    tr.remove();

                    var count_tr = $("#table_modulo tbody tr").length;
                    if(count_tr == 0){
                        tr = "<tr class=\"table-danger tr_messeger\"><td colspan='5' class='text-center'><label><h4>Nenhum registro encontrado</h4></label></td></tr>";
                        $("#table_modulo tbody").append(tr);
                    }
                }else{
                    alert_send("Erro!",json.messeger,"error");
                }
            }
        });
    }else if(action == 'load_disciplina'){
        var url = $("#url").val()+'/load_disciplina';
        var result = $.ajax({
            type: "post",
            url: url,
            data: obj,
            async: false,
            dataType: "json"
        });
        return result;
    }else if(action == 'salvar_modulo_disciplina'){
        var url = $("#url").val()+'/salvar_modulo_disciplina';
        var result = $.ajax({
            type: "post",
            url: url,
            data: obj,
            async: false,
            dataType: "json"
        });
        return result;
    }
}

function alert_send(title, text, type){
    swal({ title: title, text: text, type: type });
}

function limparform(form){
    form.find('input,textarea').val("");
}

function validar_disciplina() {

    $("#form-disciplina").submit(function(e) {
        e.preventDefault();
    }).validate({
        rules: {
            id_disciplina:{
                required: true,
                number: true
            },
            disciplina: {
                required: true,
                minlength: 3
            },
            chpresencial: {
                required: true,
                number: true
            },
            chadistancia: {
                required: true,
                number: true
            }
        },
        messages: {
            id_disciplina: {
                required: "Selecione uma disciplina",
                minlength: jQuery.validator.format("Valor inválido! No minimo 3 caracteres.")
            },
            disciplina: {
                required: "Selecione uma disciplina",
                minlength: jQuery.validator.format("Valor inválido! No minimo 3 caracteres.")
            },
            chpresencial: {
                required: "Carga horário presencial é obrigatória. <br />(Caso não tem carga horaria definida esse campo com 0)",
                minlength: jQuery.validator.format("At least {0} characters required!")
            },
            chadistancia: {
                required: "Carga horário distancia é obrigatória. <br />(Caso não tem carga horaria definida esse campo com 0)",
                minlength: jQuery.validator.format("At least {0} characters required!")
            }
        },
        validClass: "success",
        showErrors: function(errorMap, errorList) {
            this.defaultShowErrors();
        },
        submitHandler: function(form) {
            var obj = {
                id: $("#id_disciplina").val(),
                disciplina: $(".typeahead_1").val(),
                chpresencial: $("#ch_presencial").val(),
                chadistancia: $("#ch_adistancia").val(),
                id_modulo: $("#id_modulo").val()
            };

            var buttonEdit = "<button class='btn btn-primary'><i class='fa fa-edit'></i></button>";
            var buttonRemove = "<button class='btn btn-danger'><i class='fa fa-trash'></i></button>";

            var template = "<tr>" +
                "<td>"+obj.id+"</td>" +
                "<td>"+obj.disciplina+"</td>" +
                "<td class='text-center'>"+obj.chpresencial+"</td>" +
                "<td class='text-center'>"+obj.chadistancia+"</td>" +
                "<td class='text-center'>"+buttonEdit+"</td>"+
                "<td class='text-center'>"+buttonRemove+"</td>"+
                "</tr>";

            var obj_table = $("#table_disciplina");
            obj_table.find('tbody tr.tr_messeger').remove();
            obj_table.find('tbody').append(template);

            var retorno = transation('salvar_modulo_disciplina',obj);
            limparform($('#form-disciplina'));
            return false;
        }

    });
}

$("#btn_modal_modulo").click(function(){
    $("#modal-form").modal('toggle');
    $('#modal-form input,textarea').val("");
});

$("#btn_modal_add_mod").click(function() {
    var modulo = $("#modulo_tx").val().toUpperCase();
    var desc = $("#modulo_ds").val();
    var id_curso = $("#curso").val();
    var tbody = $(".table_modulo tbody");
    var addBtn = "<button type=\"button\" class=\"btn btn-outline btn-primary btn_disciplina\" title=\"\" data-toggle=\"tooltip\" id=\"btn_disciplina\" " +
                    "data-original-title=\"Adicionar Disciplinas\" data-id=''><span class=\"fa fa-plus\"></span> Adicionar Disciplinas</button>";
    var addRemover = "<button type=\"button\" class=\"btn btn-outline btn-danger btn_remove_disc\" title=\"\" data-toggle=\"tooltip\" id=\"btn_remove_disc\" " +
        "data-original-title=\"Remover Disciplinas\" data-id=''><span class=\"fa fa-trash\"></span></button>";

    var data = {modulo: modulo, descricao: desc, id_curso: id_curso};
    var resp = transation('save',data);

    var template = {
        html: "<tr id='tr_"+resp.id+"'>" +
                "<td>"+modulo+"</td>" +
                "<td class='text-center'>0</td>" +
                "<td class='text-center'>0</td>" +
                "<td class='text-center'><button type=\"button\" class=\"btn btn-outline btn-primary\" data-toggle=\"tooltip\" data-toggle=\"tooltip\">0</button></td>" +
                "<td width='30' align='center'>"+addBtn+"</td>" +
                "<td width='30' align='center'>"+addRemover+"</td>" +
              "</tr>"
    };

    tbody.append(template.html);

    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });

    $('#btCheckAll').on('ifChecked', function (event) {
        $(".btCheck").each(function () {
            $(this).iCheck('check')
        })
    }).on('ifUnchecked', function (event) {
        $(".btCheck").each(function () {
            $(this).iCheck('uncheck')
        })
    });

    $("#btn_remove_disc").data('id',resp.id).attr('id','btn_remove_disc_'+resp.id);
    $("#btn_disciplina").data('id',resp.id).attr('id','btn_disciplina_'+resp.id);
    carregarKnob("#chpresencial_graf", '1');
    carregarKnob("#chdistancia_graf", '1');

    $("#modal-form").modal('hide');
    load();
});

$("#btInsertEdit").click(function(){
    validar_disciplina();
});

