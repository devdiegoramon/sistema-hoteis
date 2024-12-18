<?php

// VERIFICAÇÃO PARA ACESSAR
if (isset($_POST['cadastrar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";

    // Valida e sanitiza os dados recebidos
    $nome = trim(mysqli_real_escape_string($con, $_POST['nome'] ?? ''));
    $numero = trim(mysqli_real_escape_string($con, $_POST['numero'] ?? ''));
    $tipo = intval($_POST['tipo'] ?? 0);
    $valor = !empty($_POST['valor']) ? str_replace([",", "."], ["", "."], $_POST['valor']) : 'null';
    $capacidade = intval($_POST['capacidade'] ?? 0);
    $descricao = trim(mysqli_real_escape_string($con, $_POST['descricao'] ?? 'Sem descrição'));
    $cor = $_POST['cor'] ?? 'default'; // Valor padrão se não definido
    $vaga = $_POST['vaga'] ?? [];

    // Validação de campos obrigatórios
    if (empty($nome) || empty($numero) || $capacidade === 0) {
        $text = "Por favor, preencha todos os campos obrigatórios.";
        $type = 2;
        header("Location: ../index.php?text=$text&type=$type");
        exit;
    }

    // Verifica duplicidade de nome e número
    $sqlConfereNome = "SELECT 1 FROM acomodacao WHERE nome = '$nome'";
    $resultConfereNome = mysqli_query($con, $sqlConfereNome);
    $sqlConfereNumero = "SELECT 1 FROM acomodacao WHERE numero = '$numero'";
    $resultConfereNumero = mysqli_query($con, $sqlConfereNumero);

    if (mysqli_num_rows($resultConfereNome) > 0 && mysqli_num_rows($resultConfereNumero) > 0) {
        $text = "Cadastro não realizado: Nome e número já cadastrados anteriormente.";
        $type = 2;
        header("Location: ../index.php?text=$text&type=$type");
        exit;
    }

    // Cadastro da acomodação
    $date = date("Y-m-d");
    $hora = date("H:i:s");
    $sqlCadastro = "INSERT INTO acomodacao
    (idtipoacomodacao, nome, numero, valor, capacidade, descricao, ativo, datag, horag, cor, tipo)
    VALUES
    ({$tipo},
    '{$nome}',
    {$numero},
    {$valor},
    {$capacidade},
    '{$descricao}',
    's', -- valor padrão para ativo
    '{$date}',
    '{$hora}',
    '{$cor}',
    '{$tipo}')";

    if (mysqli_query($con, $sqlCadastro)) {
        $idacomodacao = mysqli_insert_id($con);

        // Vincula vagas ao cadastro da acomodação
        foreach ($vaga as $dadosvaga) {
            $dadosvaga = explode('-', $dadosvaga);
            $idvaga = intval($dadosvaga[0]);
            $numvaga = mysqli_real_escape_string($con, $dadosvaga[1]);

            $sqlVaga = "UPDATE estacionamento SET idacomodacao = $idacomodacao WHERE idvaga = $idvaga";
            if (mysqli_query($con, $sqlVaga)) {
                // Log de vagas
                $idFuncionario = intval($_SESSION['idlogin'] ?? 0);
                $funcionario = mysqli_real_escape_string($con, $_SESSION['login'] ?? 'Desconhecido');
                $descricaoLog = "Funcionário: <b>$funcionario</b> vinculou a vaga de número <b>$numvaga</b> à acomodação <b>$nome</b> em $date às $hora.";

                $sqlLog = "INSERT INTO log (iduser, acao, obs, tabela, idtabela, datag, horag)
                           VALUES ('$idFuncionario', 'cadastro', '$descricaoLog', 'acomodacao', '$idacomodacao', '$date', '$hora')";

                mysqli_query($con, $sqlLog);
            }
        }

        // Log de cadastro da acomodação
        $idFuncionario = intval($_SESSION['idlogin'] ?? 0);
        $funcionario = mysqli_real_escape_string($con, $_SESSION['login'] ?? 'Desconhecido');
        $descricaoLog = "Funcionário: <b>$funcionario</b> cadastrou a acomodação <b>$nome</b> em $date às $hora.";
        $sqlLog = "INSERT INTO log (iduser, acao, obs, tabela, idtabela, datag, horag)
                   VALUES ('$idFuncionario', 'cadastro', '$descricaoLog', 'acomodacao', '$idacomodacao', '$date', '$hora')";
        mysqli_query($con, $sqlLog);

        $text = "Cadastro realizado com sucesso.";
        $type = 0;
    } else {
        $text = "Erro ao cadastrar a acomodação. Contate o administrador.";
        $type = 1;
    }

    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Acesso inválido.";
    header("Location: ../../../index.php?text=$text&type=1");
}