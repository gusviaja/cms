<?php
if (empty($login)) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="container">
    <section>

        <h2>Posts:</h2>

        <?php
        $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
        if ($empty):
            NIErro("Oppsss: Você tentou editar um post que não existe no sistema!", NI_INFOR);
        endif;


        $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
        if ($action):
            require ('_models/AdminPost.class.php');

            $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
            $postUpdate = new AdminPost;

            switch ($action):
                case 'active':
                    $postUpdate->ExeStatus($postAction, '1');
                    NIErro("O status do post foi atualizado para <b>ativo</b>. Post publicado!", NI_ACCEPT);
                    break;

                case 'inative':
                    $postUpdate->ExeStatus($postAction, '0');
                    NIErro("O status do post foi atualizado para <b>inativo</b>. Post agora é um rascunho!", NI_ALERT);
                    break;

                case 'delete':
                    $postUpdate->ExeDelete($postAction);
                    NIErro($postUpdate->getError()[0], $postUpdate->getError()[1]);
                    break;

                default :
                    NIErro("Ação não foi identifica pelo sistema, favor utilize os botões!", NI_ALERT);
            endswitch;
        endif;


        $posti = 0;
        $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        $Pager = new Pager('painel.php?exe=posts/index&page=');
        $Pager->ExePager($getPage, 10);

        $readPosts = new Read;
        $readPosts->ExeRead("ni_posts", "ORDER BY post_status ASC, post_date DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
        if ($readPosts->getResult()):
            foreach ($readPosts->getResult() as $post):
                $posti++;
                extract($post);
                $status = (!$post_status ? 'style="background: #fffed8"' : '');
                ?>
                <article<?php if ($posti % 2 == 0) echo ' class="right"'; ?> <?= $status; ?>>

                    <div class="img thumb_small">
                        <?= Check::Image('../uploads/' . $post_cover, $post_title, 120, 70); ?>
                    </div>

                    <h1><a target="_blank" href="../artigo/<?= $post_name; ?>" title="Ver Post"><?= Check::Words($post_title, 10) ?></a></h1>
                    <ul class="info post_actions">
                        <li><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($post_date)); ?>Hs</li>
                        <li><a class="act_view" target="_blank" href="../artigo/<?= $post_name; ?>" title="Ver no site">Ver no site</a></li>
                        <li><a class="act_edit" href="painel.php?exe=posts/update&postid=<?= $post_id; ?>" title="Editar">Editar</a></li>

                        <?php if (!$post_status): ?>
                            <li><a class="act_inative" href="painel.php?exe=posts/index&post=<?= $post_id; ?>&action=active" title="Ativar">Ativar</a></li>
                        <?php else: ?>
                            <li><a class="act_ative" href="painel.php?exe=posts/index&post=<?= $post_id; ?>&action=inative" title="Inativar">Inativar</a></li>
                        <?php endif; ?>

                        <li><a class="act_delete" href="painel.php?exe=posts/index&post=<?= $post_id; ?>&action=delete" title="Excluir">Deletar</a></li>
                    </ul>

                </article>
                <?php
            endforeach;

        else:
            $Pager->ReturnPage();
            NIErro("Desculpe, ainda não existem posts cadastrados!", NI_INFOR);
        endif;
        ?>

        <div class="clear"></div>
    </section>

    <?php
    $Pager->ExePaginator("ni_posts");
    echo $Pager->getPaginator();
    ?>

    
</div> <!-- content home -->