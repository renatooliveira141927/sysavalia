/**
 * Created by paulo.roberto on 14/05/2015.
 */
$(function(){
    $(".selectpicker").selectpicker('refresh');
    $.dragONDrop();
    $("#nm_grupo").upper();
});

function test() {
    var valid = true;
    var messages = [];
    $("#formInsertEdit").find("input,select,textarea").each(function(index){
        $(this).parent().removeClass("has-error");
    });

    var nm_grupo = $("#nm_grupo");

    if(nm_grupo.val().trim() == ''){
    	addError('O Campo grupo está vázio', messages);
        valid = false;
    }else if(nm_grupo.val().trim().length < 2){
    	addError('O campo Grupo tem que ter no mínimo 2 caracteres', messages);
    	valid = false;
    }else if(checkCaracteresEspecial(nm_grupo, /[!@#$%¨&*`_+={}]/) != -1){
    	addError('Caracteres Inválido', messages);
        valid = false;
    }

    if($('#fl_nivel_acesso option:selected').val() == 0){
    	addError('Selecione corretamente um Nível de Acesso', messages);
        valid = false;
    }

    if(!valid) {
		errorMessage = '';
		messages.forEach(function(entry) {
			errorMessage += entry+';<br/>';
		});
		growAlert("Alerta", errorMessage, "warning", "ban", 5000);
	}

    return valid;
}