$(function(){
	$("#nmTransacao").keyup(function(){
		$(this).val($(this).val().toUpperCase());
	    var varString = $(this).val();
	    var stringAcentos = ('àâêôûãõáéíóúçüÀÂÊÔÛÃÕÁÉÍÓÚÇÜ');
	    var stringSemAcento = ('aaeouaoaeioucuAAEOUAOAEIOUCU');
	    
	    var i = new Number();
	    var j = new Number();
	    var cString = new String();
	    var varRes = '';
	    
	    for (i = 0; i < varString.length; i++) {
	        cString = varString.substring(i, i + 1);
	        for (j = 0; j < stringAcentos.length; j++) {
	            if (stringAcentos.substring(j, j + 1) == cString){
	                cString = stringSemAcento.substring(j, j + 1);
	            }
	        }
	        varRes += cString;        
	    }
	    varRes = varRes.replace( /\s/g, '_');
	    $("#nmlabel").val(varRes.toLowerCase());
	});
});