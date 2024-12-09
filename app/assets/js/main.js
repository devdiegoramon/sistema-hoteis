setTimeout(function () {
    $('.mensagem').fadeOut(300, function () {
        $('.mensagem').remove();
    });
}, 5000);

$('.datepicker').datepicker({
    dateFormat: "dd/mm/yy",
    language: 'pt-BR',

});

$('.input-daterange input').each(function () {
    $(this).datepicker('clearDates');
});

