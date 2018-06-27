$(document).ready(function(){
    $('.chosen-select').chosen({width: "100%"});

    var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem, { color: '#1AB394', size: 'small' });

    $("#chpresencial,#chdistancia").mask('9999', {clearIfNotMatch: false});
});