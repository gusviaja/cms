<?php
ob_start();
require('./_app/BootInit.php');
//require('./vendor/autoload.php');

$Session = new Session;

?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    
    <link rel="stylesheet" href="<?= HOME; ?>/_cdn/bootstrap/css/bootstrap-reboot.min.css"> 
    <link rel="stylesheet" href="<?= HOME; ?>/_cdn/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="<?= INCLUDE_PATH; ?>/src/css/custom.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Pacifico" rel="stylesheet"> 

        <?php

        // $senha = "alfa8888";
        // $senha = password_hash($senha, PASSWORD_DEFAULT);
        // echo $senha;
        $Link = new Link;
        $Link->getTags(); // TAGS PARA SEO, UTIL APENAS PARA PROJETOS COM AMBICAO DE POSICIONAMENTO ADEQUADO EM GOOGLEBOOT

        ?>
        

    </head>
    <body>

        <?php
        require(REQUIRE_PATH . '/inc/header.inc.php');
        // echo $Link->getPatch();
        if (!require($Link->getPatch())):
            NIErro('Erro ao incluir arquivo de navegação!', NI_ERROR, true);
        endif;

        require(REQUIRE_PATH . '/inc/footer.inc.php');
        ?> 

    

    
  


    <script src="<?= HOME ?>/_cdn/jquery/jquery.min.js"></script>
    <script src="<?= HOME ?>/_cdn/bootstrap/js/popper.min.js"></script>
    <script src="<?= HOME ?>/_cdn/bootstrap/js/bootstrap.min.js"></script>
    
    </body>  
</html>
<?php
ob_end_flush();
