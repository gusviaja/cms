<?php
$view = new View;
$jumbotron = $view->Load('jumbotron');


$dados =  array(
    "titulo_post"=>"Obrigado por utilizar NI_MicroFramework",
    "texto_post"=>"Embora trata-se de um framework simples, contem as clases necessarias para 
    a correta construção de qualquer sistema PHP MVC",
    "subtexto_post"=>"o que acha de aprender a utiliza-lo com um exemplo pratico?",
    "texto_botao"=>"Ir ao manual"

);

?>

    <div class="container">
    <?php
         $view->Show($dados,$jumbotron);
    ?></div>







