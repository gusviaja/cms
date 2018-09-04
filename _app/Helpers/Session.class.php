<?php

/**
 * Session.class [ HELPER ]
 * Responsável pelas estatísticas, sessões e atualizações de tráfego do sistema!
 * 
 * @copyright (c) 2015, Nestor G. Tedesco Iglesias - NI SISTEMAS WEB
 */
class Session {

    private $Date;
    private $Cache;
    private $Traffic;
    private $Browser;

    function __construct($Cache = null) {
        session_start();
        $this->CheckSession($Cache);
    }

    //Verifica e executa todos os métodos da classe!
    private function CheckSession($Cache = null) {
        $this->Date = date('Y-m-d');
        $this->Cache = ( (int) $Cache ? $Cache : 20 );

        if (empty($_SESSION['useronline'])):
            
            $this->setSession();
            $this->CheckBrowser();
        else:
            $this->sessionUpdate();
            $this->CheckBrowser();
        endif;

        $this->Date = null;
    }

    /*
     * ***************************************
     * ********   SESSÃO DO USUÁRIO   ********
     * ***************************************
     */

    //Inicia a sessão do usuário mantem viva acrescentando o valor
    //de minutos que passamos em Cache ou o default que é de 20min
    private function setSession() {
        $_SESSION['useronline'] = [
            "online_session" => session_id(),
            "online_startview" => date('Y-m-d H:i:s'),
            "online_endview" => date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes")),
            "online_ip" => $_SERVER['REMOTE_ADDR'],
            "online_url" => strip_tags(trim($_SERVER['REQUEST_URI'])),
            "online_agent" => $_SERVER['HTTP_USER_AGENT']
        ];
    }

    //Atualiza sessão do usuário!
    private function sessionUpdate() {
        $_SESSION['useronline']['online_endview'] = date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes"));
        $_SESSION['useronline']['online_url'] = strip_tags(trim($_SERVER['REQUEST_URI']));
    }

   
    //Verifica, cria e atualiza o cookie do usuário [ HELPER TRAFFIC ]
    private function getCookie(): bool {
        $Cookie = filter_input(INPUT_COOKIE, 'useronline', FILTER_DEFAULT);
        setcookie("useronline", base64_encode("nimicro"), time() + 86400);
        if (!$Cookie):
            return false;
        else:
            return true;
        endif;
    }

    /*
     * ***************************************
     * *******  NAVEGADORES DE ACESSO   ******
     * ***************************************
     */

    //Identifica navegador do usuário!
    private function CheckBrowser() {
        $this->Browser = $_SESSION['useronline']['online_agent'];
        if (strpos($this->Browser, 'Chrome')):
            $this->Browser = 'Chrome';
        elseif (strpos($this->Browser, 'Firefox')):
            $this->Browser = 'Firefox';
        elseif (strpos($this->Browser, 'MSIE') || strpos($this->Browser, 'Trident/')):
            $this->Browser = 'IE';
        else:
            $this->Browser = 'Outros';
        endif;
    }

}
