<?php

/**
 * Login.class [ MODEL ]
 *Autentica, valida, e verifica usuários com request ao sistema de login!
 * 
 * @copyright (c) 2015, Nestor G. Tedesco Iglesias - NI SISTEMAS WEB
 */
class Login {

    private $Level;
    private $Email;
    private $Senha;
    private $Error;
    private $Result;

    /**
     * <strong>Informar Level:</strong> Informe o nível de acesso mínimo para a área a ser protegida conforme setou noo banco. Exemplo 3 = admin
     * @param INT $Level = Nível mínimo para acesso
     */
    function __construct($Level) {
        $this->Level = (int) $Level;
    }

    /**
     * <b>Efetuar Login:</b> Envelope um array atribuitivo com índices STRING user [email], STRING pass.
     * Ao passar este array na ExeLogin() os dados são verificados e o login é feito!
     * @param ARRAY $UserData = user [email], pass
     */
    public function ExeLogin(array $UserData) {
        $this->Email = (string) htmlentities(trim($UserData['user']));
        $this->Senha = (string) htmlentities(trim($UserData['pass']));
        // $this->Senha = password_hash($this->Senha, PASSWORD_DEFAULT);
        // var_dump( $this->Senha);
        $this->setLogin();
    }

    /**
     * <b>Verificar Login:</b> Executando um getResult é possível verificar se foi ou não efetuado
     * o acesso com os dados.
     * @return BOOL $Var = true para login e false para erro
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com uma mensagem e um tipo de erro NI_.
     * @return ARRAY $Error = Array associatico com o erro
     */
    public function getError() {
        return $this->Error;
    }

    /**
     * <b>Checar Login:</b> Execute esse método para verificar a sessão USERLOGIN e revalidar o acesso
     * para proteger telas restritas.
     * @return BOLEAM $login = Retorna true ou mata a sessão e retorna false!
     */
    public function CheckLogin() {
        if (empty($_SESSION['userlogin']) || $_SESSION['userlogin']['user_level'] < $this->Level):
            unset($_SESSION['userlogin']);
            return false;
        else:
            return true;
        endif;
    }

    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    //Valida os dados e armazena os erros caso existam. Executa o login!
    private function setLogin() {
        if (!$this->Email || !$this->Senha || !Check::Email($this->Email)):
            $this->Error = ['Informe seu E-mail e senha para efetuar o login!', NI_INFOR];
            $this->Result = false;
        elseif (!$this->getUser()):
            $this->Error = ['Náo encontramos usuarios com estes dados!', NI_ALERT];
            $this->Result = false;
        elseif ($this->Result['user_level'] < $this->Level):
            $this->Error = ["Desculpe {$this->Result['user_name']}, você não tem credenciais validas para acessar esta área!", NI_ERROR];
            $this->Result = false;
        else:
            $this->Execute();
        endif;
    }

    //Vetifica usuário e senha no banco de dados!
    private function getUser() {
       
        $read = new Read;
        $read->ExeRead("ni_users", "WHERE user_email = :e", "e={$this->Email}");
        $userStored = $read->getResult()[0];
        $hashStored = $userStored["user_password"];
        if(password_verify($this->Senha,$hashStored)):
            $this->Result = $userStored;
            return true;
        else:
            return false;
        endif;    
        // echo $hashStored;die();
        
    }

    //Executa o login armazenando a sessão!
    private function Execute() {
        if (!session_id()):
            session_start();
        endif;

        $_SESSION['userlogin'] = $this->Result;

        $this->Error = ["Olá {$this->Result['user_name']}. Por gentileza aguarde redirecionamento!", NI_ACCEPT];
        $this->Result = true;
    }

}
