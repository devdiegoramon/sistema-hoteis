<?php

if (!isset($_POST['validar'])) {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
    exit();
}

include "../../config/connMysql.php";
include "../../config/config.php";

$sqlDeletaLogo = "DELETE FROM logo";

if (empty($_FILES['logo']['name'])) {
    if (mysqli_query($con, $sqlDeletaLogo)) {
        $text = "Logo alterada com sucesso.";
        $type = 0;
        unlink("../arquivos/logo/logo.png");
        header("Location: ../index.php?text=$text&type=$type");
        exit();
    }
}

// Configurações de upload
$_UP['pasta'] = '../arquivos/logo/';
$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
$_UP['extensoes'] = array('png');
$_UP['renomeia'] = true;
$_UP['erros'] = [
    'Não houve erro',
    'O arquivo no upload é maior do que o limite do PHP',
    'O arquivo ultrapassa o limite de tamanho especificado no HTML',
    'O upload do arquivo foi feito parcialmente',
    'Não foi feito o upload do arquivo'
];

// Verifica se houve erro no upload
if ($_FILES['logo']['error'] != 0) {
    $text = "Erro no upload: " . $_UP['erros'][$_FILES['logo']['error']];
    $type = 1;
    header("Location: ../index.php?text=$text&type=$type");
    exit();
}

// Verifica a extensão do arquivo
$temp = explode('.', $_FILES['logo']['name']);
$extensao = strtolower(end($temp));
if (!in_array($extensao, $_UP['extensoes'])) {
    $text = "Por favor, envie arquivos com a extensão PNG.";
    $type = 1;
} elseif ($_UP['tamanho'] < $_FILES['logo']['size']) {
    $text = "O arquivo enviado é muito grande. Envie arquivos de até 2MB.";
    $type = 1;
} else {
    // Define o nome do arquivo
    $nome_final = $_UP['renomeia'] ? 'logo.png' : $_FILES['logo']['name'];

    // Deleta a logo antiga no banco de dados
    if (mysqli_query($con, $sqlDeletaLogo)) {
        $sqlInsertLogo = "INSERT INTO logo VALUES (null, '$nome_final', '$nome_final')";
        if (mysqli_query($con, $sqlInsertLogo)) {
            // Move o arquivo para a pasta
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
                // Gera log
                session_start();
                $idFuncionario = $_SESSION['idlogin'] ?? 0;
                $funcionario = $_SESSION['login'] ?? 'Desconhecido';

                // Tratamento para categoria indefinida
                if (!isset($_SESSION['idcategoria']) || empty($_SESSION['idcategoria'])) {
                    $idcategoria = 1; // Substituir por um valor padrão válido, se necessário
                } else {
                    $idcategoria = $_SESSION['idcategoria'];
                }

                $date = date("Y-m-d");
                $data = date("d/m/Y");
                $hora = date("H:i:s");
                $descricaoLog = "Funcionário: <b>$funcionario</b>, cadastrou uma nova logo no dia <b>$data</b> às <b>$hora</b>";

                $sqlLog = "INSERT INTO log VALUES (null, $idFuncionario, 'cadastro', '$descricaoLog', 'logo', $idcategoria, null, '$date', '$hora')";

                if (mysqli_query($con, $sqlLog)) {
                    $text = "Logo alterada com sucesso.";
                    $type = 0;
                } else {
                    $text = "Erro ao registrar o log.";
                    $type = 1;
                }
            } else {
                $text = "Erro ao mover o arquivo.";
                $type = 1;
            }
        } else {
            $text = "Erro ao salvar a logo no banco de dados.";
            $type = 1;
        }
    } else {
        $text = "Erro ao deletar a logo anterior.";
        $type = 1;
    }
}

// Fecha a conexão e redireciona
mysqli_close($con);
header("Location: ../index.php?text=$text&type=$type");
