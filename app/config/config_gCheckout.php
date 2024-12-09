<?php

/* ARQUIVO DE CONFIGURAÇÂO GERAL DO SISTEMA */
session_start();

// Padrão brasileiro
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

//DOMAIN = dominio Ex:.. localhost
define('DOMAIN', isset($_SERVER['HTTP_Host']) ? $_SERVER['HTTP_Host'] : $_SERVER['SERVER_NAME']);

function pathUrl($dir = __DIR__) {
    $root = "";
    $dir = str_replace('\\', '/', realpath($dir));

    //HTTPS OU HTTP
    $root .= !empty($_SERVER['HTTPS']) ? 'https' : 'http';
    //Host
    $root .= '://' . $_SERVER['HTTP_HOST'];
    //ALIAS
    if (!empty($_SERVER['CONTEXT_PREFIX'])) {
        $root .= $_SERVER['CONTEXT_PREFIX'];
        $root .= substr($dir, strlen($_SERVER['CONTEXT_DOCUMENT_ROOT']));
    } else {
        $root .= substr($dir, strlen($_SERVER['DOCUMENT_ROOT']));
    }
    $root .= '/';
    return $root;
}

$root = !empty($_SERVER['HTTPS']) ? 'https' : 'http';

//localhost/pousada/app
$based = pathUrl();
$based = array_reverse(explode('/', $based));
unset($based[0], $based[1]);
$based = array_reverse($based);
define('BASED', $based = implode('/', $based));

//LocalHost/pousada
$base = pathUrl();
$base = array_reverse(explode('/', $base));
unset($base[0], $base[1], $base[2]);
$base = array_reverse($base);
define('BASE', $base = implode('/', $base));

//Valida a Sessão
if (!isset($_SESSION['login'])) {
    $text = "Necessário realizar login para continuar";
    header("Location: " . BASE . "/index.php?text=$text&type=1");
} elseif ($_SESSION["tempo"] + 60 * 60 < time()) {
    session_destroy();
    $text = "Sessão expirada";
    header("Location: " . BASE . "/index.php?text=$text&type=1");
} else {
    $_SESSION["tempo"] = time();
}
