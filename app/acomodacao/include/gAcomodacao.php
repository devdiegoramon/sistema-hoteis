<?php

// VERIFICAÇÃO PARA ACESSAR
if (isset($_POST['cadastrar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";

    // Valida e sanitiza os dados recebidos
    $nome = trim(mysqli_real_escape_string($con, $_POST['nome'] ?? ''));
    $numero = trim(mysqli_real_escape_string($con, $_POST['numero'] ?? ''));
    $tipo = intval($_POST['tipo'] ?? 0);
    $valor = !empty($_POST['valor']) ? str_replace([",", "."], ["", "."], $_POST['valor']) : 0;  // Garantir que o valor seja um número
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
    $sqlConfereNome = "SELECT 1 FROM acomodacao WHERE nome = ?";
    $stmtConfereNome = mysqli_prepare($con, $sqlConfereNome);
    mysqli_stmt_bind_param($stmtConfereNome, "s", $nome);
    mysqli_stmt_execute($stmtConfereNome);
    $resultConfereNome = mysqli_stmt_get_result($stmtConfereNome);

    $sqlConfereNumero = "SELECT 1 FROM acomodacao WHERE numero = ?";
    $stmtConfereNumero = mysqli_prepare($con, $sqlConfereNumero);
    mysqli_stmt_bind_param($stmtConfereNumero, "s", $numero);
    mysqli_stmt_execute($stmtConfereNumero);
    $resultConfereNumero = mysqli_stmt_get_result($stmtConfereNumero);

    if (mysqli_num_rows($resultConfereNome) > 0 && mysqli_num_rows($resultConfereNumero) > 0) {
        $text = "Cadastro não realizado: Nome e número já cadastrados anteriormente.";
        $type = 2;
        header("Location: ../index.php?text=$text&type=$type");
        exit;
    }

    // Cadastro da acomodação utilizando prepared statement
    $date = date("Y-m-d");
    $hora = date("H:i:s");

    // Defina o valor padrão para 'ativo'
    $ativo = 's';

    // Aqui criamos a consulta de inserção
    $sqlCadastro = "INSERT INTO acomodacao
    (idtipoacomodacao, nome, numero, valor, capacidade, descricao, ativo, datag, horag, cor)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar a consulta
    $stmtCadastro = mysqli_prepare($con, $sqlCadastro);

    // Verificar se a preparação da consulta foi bem-sucedida
    if ($stmtCadastro === false) {
        die('Erro na preparação da consulta: ' . mysqli_error($con));
    }

    // Definindo as variáveis para a execução do prepared statement
    $tipo_param = $tipo;
    $nome_param = $nome;
    $numero_param = $numero;
    $valor_param = $valor;
    $capacidade_param = $capacidade;
    $descricao_param = $descricao;
    $ativo_param = $ativo;
    $date_param = $date;
    $hora_param = $hora;
    $cor_param = $cor;

    // Vincular os parâmetros à consulta preparada
    mysqli_stmt_bind_param($stmtCadastro, "issdssssss", $tipo_param, $nome_param, $numero_param, $valor_param, $capacidade_param, $descricao_param, $ativo_param, $date_param, $hora_param, $cor_param);

    // Executar a consulta
    if (mysqli_stmt_execute($stmtCadastro)) {
        $idacomodacao = mysqli_insert_id($con);

        // Vincula vagas ao cadastro da acomodação
        foreach ($vaga as $dadosvaga) {
            $dadosvaga = explode('-', $dadosvaga);
            $idvaga = intval($dadosvaga[0]);
            $numvaga = mysqli_real_escape_string($con, $dadosvaga[1]);

            $sqlVaga = "UPDATE estacionamento SET idacomodacao = ? WHERE idvaga = ?";
            $stmtVaga = mysqli_prepare($con, $sqlVaga);
            mysqli_stmt_bind_param($stmtVaga, "ii", $idacomodacao, $idvaga);

            if (mysqli_stmt_execute($stmtVaga)) {
                // Log de vagas
                $idFuncionario = intval($_SESSION['idlogin'] ?? 0);
                $funcionario = mysqli_real_escape_string($con, $_SESSION['login'] ?? 'Desconhecido');
                $descricaoLog = "Funcionário: <b>$funcionario</b> vinculou a vaga de número <b>$numvaga</b> à acomodação <b>$nome</b> em $date às $hora.";

                $sqlLog = "INSERT INTO log (iduser, acao, obs, tabela, idtabela, datag, horag)
                           VALUES (?, 'cadastro', ?, 'acomodacao', ?, ?, ?)";
                $stmtLog = mysqli_prepare($con, $sqlLog);
                mysqli_stmt_bind_param($stmtLog, "issssss", $idFuncionario, $descricaoLog, $idacomodacao, $date, $hora, $descricaoLog);
                mysqli_stmt_execute($stmtLog);
            }
        }

        // Log de cadastro da acomodação
        $idFuncionario = intval($_SESSION['idlogin'] ?? 0);
        $funcionario = mysqli_real_escape_string($con, $_SESSION['login'] ?? 'Desconhecido');
        $descricaoLog = "Funcionário: <b>$funcionario</b> cadastrou a acomodação <b>$nome</b> em $date às $hora.";

        $sqlLog = "INSERT INTO log (iduser, acao, obs, tabela, idtabela, datag, horag)
                   VALUES (?, 'cadastro', ?, 'acomodacao', ?, ?, ?)";
        $stmtLog = mysqli_prepare($con, $sqlLog);
        mysqli_stmt_bind_param($stmtLog, "issssss", $idFuncionario, $descricaoLog, $idacomodacao, $date, $hora, $descricaoLog);
        mysqli_stmt_execute($stmtLog);

        $text = "Cadastro realizado com sucesso.";
        $type = 0;
    } else {
        $text = "Erro ao cadastrar a acomodação. Contate o administrador.";
        $type = 1;
    }

    // Fechar as declarações
    mysqli_stmt_close($stmtCadastro);
    mysqli_stmt_close($stmtConfereNome);
    mysqli_stmt_close($stmtConfereNumero);
    mysqli_stmt_close($stmtVaga);
    mysqli_stmt_close($stmtLog);
    mysqli_close($con);

    // Redirecionar
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Acesso inválido.";
    header("Location: ../../../index.php?text=$text&type=1");
}
?>
