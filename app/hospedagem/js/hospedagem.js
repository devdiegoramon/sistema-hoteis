/* global fetch */

$('#cep_invalido').hide();
$('.cep').mask('00000-000');
$('.phone').mask('(00) 0000-0000')
$('.celular').mask('(00) 00000-0000');
$('.phone_with_ddd').mask('(00) 0000-0000');
$('.cnpj').mask('00.000.000/0000-00', {reverse: true});
$('.dropify').dropify({
    messages: {
        'default': 'Arraste e solte ou clique para adicionar',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove': 'Remover',
        'error': 'Ooops, algo errado aconteceu.'
    }
});

const cepValido = (cep) => cep.length == 8;

const pesquisarCep = async() => {
    let cep = $("#cep").val();
    cep = cep.replace('-', '');
    const url = "https://viacep.com.br/ws/" + cep + "/json/";

    let logradouro = $("#logradouro");
    let cidade = $("#cidade");
    let bairro = $("#bairro");


    if (cepValido(cep)) {
        const dados = await fetch(url);
        const endereco = await dados.json();
        if (endereco.hasOwnProperty('erro')) {
            $('#cep_invalido').show();
            logradouro.val('');
            cidade.val('');
            bairro.val('');
            $('#btn_endereco').prop('disabled', true);
        } else {
            $('#cep_invalido').hide();
            $('#btn_endereco').prop('disabled', false);
            preencherFormulario(endereco);
        }
    } else {
        $('#cep_invalido').show();
        $('#btn_endereco').prop('disabled', true);
        logradouro.val('');
        cidade.val('');
        bairro.val('');
    }
};

const preencherFormulario = (endereco) => {
    let logradouro = $("#logradouro");
    let cidade = $("#cidade");
    let bairro = $("#bairro");

    logradouro.val(endereco.logradouro);
    cidade.val(endereco.localidade);
    bairro.val(endereco.bairro);
};

$("#cep").focusout(function () {
    pesquisarCep();
});
