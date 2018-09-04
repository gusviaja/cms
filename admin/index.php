<?php
ob_start();
session_start();
require('../_app/BootInit.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="mit" content="2017-09-15T22:35:39-03:00+52728">
        <title>CMS Admin</title>

        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,800' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?= HOME;?>/_cdn/bootstrap/css/bootstrap-reboot.min.css" />
        <link rel="stylesheet" href="<?= HOME;?>/_cdn/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/admin.css" />

    </head>
    <body class="">
        <div class="container" >
            <div class="row">
                        <div class="col-md-3"></div>
                         <div class="login col-md-6">
                            <h1 class="text-center titulo-admin">::Administrar Site::</h1>

                            <?php
                            $login = new Login(3);

                            if ($login->CheckLogin()):
                                header('Location: painel.php');
                            endif;

                            $dataLogin = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                            if (!empty($dataLogin['AdminLogin'])):

                                $login->ExeLogin($dataLogin);
                                if (!$login->getResult()):
                                    NIErro($login->getError()[0], $login->getError()[1]);
                                else:
                                    header('Location: painel.php');
                                endif;

                            endif;

                            $get = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
                            if (!empty($get)):
                                if ($get == 'restrito'):
                                    NIErro('<b>Oppsss:</b> Acesso negado. Favor efetue login para acessar o painel!', NI_ALERT);
                                elseif ($get == 'logoff'):
                                    NIErro('<b>Sucesso ao deslogar:</b> Sua sessão foi finalizada. Volte sempre!', NI_ACCEPT);
                                endif;
                            endif;
                            ?>

                            <form name="AdminLoginForm" action="" method="post">
                                <div class="form-group">
                                    <label for="user">Email: </label>
                                        <input id="user" type="email" name="user" class="form-control" />
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="pass"> Senha: </label>
                                        <input id="pass" type="password" name="pass" class="form-control" />
                                    </label>
                                </div>

                                <input type="submit" name="AdminLogin" value="Logar" class="btn btn-primary" />

                            </form>
                       </div><!-- END COL -->
        
                         <div class="col-md-3"></div>
          
            </div>  <!-- END ROW -->
        </div> <!-- END CONTAINER -->

    </body>
</html>
<?php
ob_end_flush();
