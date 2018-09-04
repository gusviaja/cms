<?php

//===== CONSTANTES DO BANCO==========//
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DBSA', 'ni_php');

//===== CONSTANTES DO EMAIL SERVER========//
define('MAILUSER', 'gtedescoiglesias@gmail.com');
define('MAILPASS', 'Rafateamo');
define('MAILPORT', '465');
define('MAILHOST', 'smtp.gmail.com');

//===== CONSTANTES DO SITE========//
define('SITENAME', 'Constante Title, titulo do site');
define('SITEDESC', 'Define descricao do site');

//===== CONSTANTES DE URL E TEMA ATIVO========//
define('HOME', 'http://nimicro.com.br'); // URL DOMAIN
define('THEME', 'padrao');  // TEMA A SER UTILIZADO

define('INCLUDE_PATH', HOME . '/themes/' . THEME); // URL FULL DO INDEX DO TEMA
define('REQUIRE_PATH', 'themes' . DIRECTORY_SEPARATOR . THEME); // URL A PARTIR DA PASTA THEMES

//======AUTO LOAD DE CLASSES DO PROPRIO FRAMEWORK========//
function autoload_framework($Class) {

    $cDir = ['Model', 'Helpers', 'Class'];
    $iDir = null;

    foreach ($cDir as $dirName):
        if (!$iDir && file_exists(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php') && !is_dir(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php')):
            include_once (__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php');
            $iDir = true;
        endif;
    endforeach;

    if (!$iDir):
        trigger_error("Não foi possível incluir {$Class}.class.php", E_USER_ERROR);
        die;
    endif;
}


spl_autoload_register("autoload_framework");


// TRATAMENTO DE ERROS #####################
//CSS constantes :: Mensagens de Erro
define('NI_ACCEPT', 'accept');
define('NI_INFOR', 'infor');
define('NI_ALERT', 'alert');
define('NI_ERROR', 'error');

//NIErro :: Exibe erros lançados :: Front
function NIErro($ErrMsg, $ErrNo, $ErrDie = null) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? NI_INFOR : ($ErrNo == E_USER_WARNING ? NI_ALERT : ($ErrNo == E_USER_ERROR ? NI_ERROR : $ErrNo)));
    echo "<p class=\"trigger {$CssClass}\">{$ErrMsg}<span class=\"ajax_close\"></span></p>";

    if ($ErrDie):
        die;
    endif;
}

//PHPErro :: personaliza o gatilho do PHP
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? NI_INFOR : ($ErrNo == E_USER_WARNING ? NI_ALERT : ($ErrNo == E_USER_ERROR ? NI_ERROR : $ErrNo)));
    echo "<p class=\"trigger {$CssClass}\">";
    echo "<b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>";
    echo "<small>{$ErrFile}</small>";
    echo "<span class=\"ajax_close\"></span></p>";

    if ($ErrNo == E_USER_ERROR):
        die;
    endif;
}

set_error_handler('PHPErro');

