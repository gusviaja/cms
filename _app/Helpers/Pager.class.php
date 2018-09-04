<?php

/**
 * Pager.class [ HELPER ]
 * Realização a gestão e a paginação de resultados do sistema!
 * 
 * @copyright (c) 2015, Nestor G. Tedesco Iglesias - NI SISTEMAS WEB
 */
class Pager {

    /** DEFINE O PAGER */
    private $Page;
    private $Pages;
    private $Limit;
    private $Offset;

    /** REALIZA A LEITURA */
    private $Tabela;
    private $Termos;
    private $Places;

    /** DEFINE O PAGINATOR */
    private $Rows;
    private $Link;
    private $MaxLinks;
    private $First;
    private $Last;

    /** RENDERIZA O PAGINATOR */
    private $Paginator;

    /**
     * <strong>Iniciar Paginação:</strong> Defina o link onde a paginação será recuperada. Você ainda pode mudar os textos
     * do primeiro e último link de navegação e a quantidade de links exibidos (opcional)
     * @param STRING $Link = Ex: index.php?pagina&page=
     * @param STRING $First = Texto do link (Primeira Página)
     * @param STRING $Last = Texto do link (Última Página)
     * @param STRING $MaxLinks = Quantidade de links (5)
     */
    function __construct($Link, $First = null, $Last = null, $MaxLinks = null) {
        $this->Link = (string) $Link;
        $this->First = ( (string) $First ? $First : 'First Page' );
        $this->Last = ( (string) $Last ? $Last : 'Final Page' );
        $this->MaxLinks = ( (int) $MaxLinks ? $MaxLinks : 5);
    }

    /**
     * <strong>Executar Pager:</strong> Informe o índice da URL que vai recuperar a navegação e o limite de resultados por página.
     * Você devere usar LIMIT getLimit() e OFFSET getOffset() na query que deseja paginar. A página atual está em getPage()
     * @param INT $Page = Recupere a página na URL
     * @param INT $Limit = Defina o LIMIT da consulta
     */
    public function ExePager($Page, $Limit) {
        $this->Page = ( (int) $Page ? $Page : 1 );
        $this->Limit = (int) $Limit;
        $this->Offset = ($this->Page * $this->Limit) - $this->Limit;
    }

    /**
     * <strong>Retornar:</strong> Caso informado uma page com número maior que os resultados, este método navega a paginação
     * em retorno até a página com resultados!
     * @return LOCATION = Retorna a página
     */
    public function ReturnPage() {
        if ($this->Page > 1):
            $nPage = (intval($this->Last) ? $this->Last : $this->Page - 1);
            header("Location: {$this->Link}{$nPage}");
        endif;
    }

    /**
     * <strong>Obter Página:</strong> Retorna o número da página atualmente em foco pela URL. Pode ser usada para validar
     * a navegação da paginação!
     * @return INT = Retorna a página atual
     */
    public function getPage() {
        return $this->Page;
    }

    /**
     * <strong>Limite por Página:</strong> Retorna o limite de resultados por página da paginação. Deve ser usada na SQL que obtém
     * os resultados. Ex: LIMIT = getLimit();
     * @return INT = Limite de resultados
     */
    public function getLimit() {
        return $this->Limit;
    }

    /**
     * <strong>Offset por Página:</strong> Retorna o offset de resultados por página da paginação. Deve ser usada na SQL que obtém
     * os resultado. Ex: OFFSET = getLimit();
     * @return INT = Offset de resultados
     */
    public function getOffset() {
        return $this->Offset;
    }

    /**
     * <strong>Executar Paginação:</strong> Cria o menu de navegação de paginação dentro de uma lista não ordenada com a class paginator.
     * Informe o nome da tabela e condições caso exista. O resto é feito pelo método. Execute um <b>echo getPaginator();</b>
     * para exibir a paginação na view.
     * @param STRING $Tabela = Nome da tabela
     * @param STRING $Termos = Condição da seleção caso tenha
     * @param STRING $ParseString = Prepared Statements
     */
    public function ExePaginator($Tabela, $Termos = null, $ParseString = null) {
        $this->Tabela = (string) $Tabela;
        $this->Termos = (string) $Termos;
        $this->Places = (string) $ParseString;
        $this->getSyntax();
    }

    /**
     * <strong>Exibir Paginação:</strong> Retorna os links para a paginação de resultados. Deve ser usada com um echo para exibição.
     * Para formatar as classes são: ul.paginator, li a e li .active.
     * @return HTML = Paginação de resultados
     */
    public function getPaginator() {
        return $this->Paginator;
    }

    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    //Cria a paginação de resultados
    private function getSyntax() {
        $read = new Read;
        $read->ExeRead($this->Tabela, $this->Termos, $this->Places);
        $this->Rows = $read->getRowCount();

        if ($this->Rows > $this->Limit):
            $Paginas = ceil($this->Rows / $this->Limit);
            $MaxLinks = $this->MaxLinks;

            $this->Paginator = "<ul class=\"paginator\">";
            $this->Paginator .= "<li><a title=\"{$this->First}\" href=\"{$this->Link}1\">{$this->First}</a></li>";

            for ($iPag = $this->Page - $MaxLinks; $iPag <= $this->Page - 1; $iPag ++):
                if ($iPag >= 1):
                    $this->Paginator .= "<li><a title=\"Página {$iPag}\" href=\"{$this->Link}{$iPag}\">{$iPag}</a></li>";
                endif;
            endfor;

            $this->Paginator .= "<li><span class=\"active\">{$this->Page}</span></li>";

            for ($dPag = $this->Page + 1; $dPag <= $this->Page + $MaxLinks; $dPag ++):
                if ($dPag <= $Paginas):
                    $this->Paginator .= "<li><a title=\"Página {$dPag}\" href=\"{$this->Link}{$dPag}\">{$dPag}</a></li>";
                endif;
            endfor;

            $this->Paginator .= "<li><a title=\"{$this->Last}\" href=\"{$this->Link}{$Paginas}\">{$this->Last}</a></li>";
            $this->Paginator .= "</ul>";
        endif;
    }

}
