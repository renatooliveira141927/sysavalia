/**
 * Created by paulo.roberto on 11/05/2015.
 */

function test() {
    $('select[name="grupo_select[]"] > option').prop('selected', 'selected');
    return true;
}

$.fn.selectPicker = function(params){
    var component = $(this);
    var firstSelect = component.find('select:eq(0)');
    var fromOptions = firstSelect.find('option');
    var secondSelect = component.find('select:eq(1)');
    var toOptions = secondSelect.find('option');

    var addOne = component.find('button:eq(0)');
    var addAll = component.find('button:eq(2)');
    var removeOne = component.find('button:eq(1)');
    var removeAll = component.find('button:eq(3)');

    $('.buttons').find('button').css('width', '40px');

    addOne.click(function(){
        var selecionados = firstSelect.find('option:selected');
        selecionados.each(function() {
            secondSelect.append($(this));
            $(this).attr("selected", false);
        });
        //ordena(secondSelect);
    });

    addAll.click(function(){
        fromOptions = firstSelect.find('option');
        fromOptions.each(function() {
            secondSelect.append($(this));
            $(this).attr("selected", false);
        });
        firstSelect.html('');
        //ordena(secondSelect);
    });

    removeOne.click(function(){
        var selecionados = secondSelect.find('option:selected');
        selecionados.each(function() {
            firstSelect.append($(this));
            $(this).attr("selected", false);
        });
        //ordena(firstSelect);
    });

    removeAll.click(function(){
        toOptions = secondSelect.find('option');
        toOptions.each(function() {
            firstSelect.append($(this));
            $(this).attr("selected", false);
        });
        //ordena(firstSelect);
    });

    //Verifica se o elemento ja existe na lista passada
    function jaExiste(elemento, listaPara){
        var retorno = false;
        $(listaPara).each(function(){
            if(elemento.val() == $(this).val()){
                retorno = true;
            }
        });
        return retorno;
    }

    //Ordena os options de um select
    function ordena(elemento){
        var options = elemento.find('option');
        var arr = options.map(function(_, o) { return { t: $(o).text(), v: o.value }; }).get();
        arr.sort(function(o1, o2) { return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0; });
        options.each(function(i, o) {
            o.value = arr[i].v;
            $(o).text(arr[i].t);
        });
    }
};

var dados, map, unidades, selectedId, SelectedInep;
$(function(){
    $('#unidade_trabalho').typeahead({
        source: function (query, process) {
            unidades = [];
            map = {};
            $.get(
                $("#url").val(),
                {'term':$('#unidade_trabalho').val()},
                function(data){
                    dados = data[0];
                    $.each(data, function (i, unidade) {
                        map[unidade.label] = unidade;
                        unidades.push(unidade.label);
                    });
                    process(unidades);
                },'json'
            );
        },
        updater: function (item) {
            console.log(map[item]);
            selectedId = map[item].id;
            SelectedInep = map[item].inep;
            $('#cod_unid').text(SelectedInep);
            $('#unidade_trabalho_hidden').val(map[item].label);
            $('#cd_unidade').val(selectedId);
            $('#unidade-trab').find('input:eq(0)').prop('disabled', true);
            $('#edit-unidade').prop('disabled', false);
            return item;
        }
    });

    $('#edit-unidade').click(function(){
        var unid = $('#unidade_trabalho[disabled]');
        if(unid.length > 0){
            $('#cod_unid').text("");
            $('#cd_unidade').val("");
            $('#unidade_trabalho').val("");
            $('#unidade_trabalho_hidden').val("");
            $('#unidade-trab').find('input:eq(0)').prop('disabled', false);
            $('#edit-unidade').prop('disabled', true);
        }
    });

    var utDisabled = $('#cd_unidade').val() && $('#unidade_trabalho_hidden').val();
    $('#unidade-trab').find('input:eq(0)').prop('disabled', utDisabled);
    $('#edit-unidade').prop('disabled', !utDisabled);

    $(".selectPicker").selectPicker();

    //$('.datetimepicker').datetimepicker({pickTime:false,language:'pt-br'});
    $('#data_nascimento .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: "dd/mm/yyyy",
        startView: 1,
        language: 'pt-BR'
    });

    $("#unidade_trabalho, #nome").upper();
    $('#cpf').mask("999.999.999-99");
    $('#nascimento').mask("99/99/9999");
});