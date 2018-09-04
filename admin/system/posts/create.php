<?php
if (empty($login)) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="container">
<div class="row">

<div class="col-md-11">
    <article>

        <header>
            <h1>Criar Post:</h1>
        </header>

        <?php
        $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($post) && $post['SendPostForm']):
            $post['post_status'] = ($post['SendPostForm'] == 'Cadastrar' ? '0' : '1' );
            $post['post_cover'] = ( $_FILES['post_cover']['tmp_name'] ? $_FILES['post_cover'] : null );
            unset($post['SendPostForm']);

            require('_models/AdminPost.class.php');
            $cadastra = new AdminPost;
            $cadastra->ExeCreate($post);

            if ($cadastra->getResult()):

                if (!empty($_FILES['gallery_covers']['tmp_name'])):
                    $sendGallery = new AdminPost;
                    $sendGallery->gbSend($_FILES['gallery_covers'], $cadastra->getResult());
                endif;

                header('Location: painel.php?exe=posts/update&create=true&postid=' . $cadastra->getResult());
            else:
                NIErro($cadastra->getError()[0], $cadastra->getError()[1]);
            endif;
        endif;
        ?>


        <form name="PostForm" action="" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label for="post_cover" >Enviar Capa:</label>
                <input type="file" name="post_cover" id="post_cover"/>
            </div>

            <div class="form-group">
                <label for="post_title" >Titulo:</label>
                <input class="form-control" type="text" id="post_title" name="post_title" value="<?php if (isset($post['post_title'])) echo $post['post_title']; ?>" />
           </div>
			<div class="box box-info">
			<div class="box-body pad">
					 
							<textarea id="editor1" name="post_content" rows="10" cols="80">
									<?php if (isset($post['post_content'])) echo htmlspecialchars($post['post_content']); ?>
							</textarea>
					  
					</div>
			</div>
          

            <div class="label_line">

                <label class="label_small">
                    <span class="field">Data:</span>
                    <input type="text" class="formDate center" name="post_date" value="<?php
                    if (isset($post['post_date'])): echo $post['post_date'];
                    else: echo date('d/m/Y H:i:s');
                    endif;
                    ?>" />
                </label>

                <label class="label_small">
                    <span class="field">Categoria:</span>
                    <select name="post_category">
                        <option value=""> Selecione a categoria: </option>                        
                        <?php
                        $readSes = new Read;
                        $readSes->ExeRead("ni_categories", "WHERE category_parent IS NULL ORDER BY category_title ASC");
                        if ($readSes->getRowCount() >= 1):
                            foreach ($readSes->getResult() as $ses):
                                echo "<option disabled=\"disabled\" value=\"\"> {$ses['category_title']} </option>";
                                $readCat = new Read;
                                $readCat->ExeRead("ni_categories", "WHERE category_parent = :parent ORDER BY category_title ASC", "parent={$ses['category_id']}");

                                if ($readCat->getRowCount() >= 1):
                                    foreach ($readCat->getResult() as $cat):
                                        echo "<option ";

                                        if ($post['post_category'] == $cat['category_id']):
                                            echo "selected=\"selected\" ";
                                        endif;

                                        echo "value=\"{$cat['category_id']}\"> &raquo;&raquo; {$cat['category_title']} </option>";
                                    endforeach;
                                endif;

                            endforeach;
                        endif;
                        ?>
                    </select>
                </label>

                <label class="label_small">
                    <span class="field">Author:</span>
                    <select name="post_author">
                        <option value="<?= $_SESSION['userlogin']['user_id']; ?>"> <?= "{$_SESSION['userlogin']['user_name']} {$_SESSION['userlogin']['user_lastname']}"; ?> </option>
                        <?php
                        $readAut = new Read;
                        $readAut->ExeRead("ws_users", "WHERE user_id != :id AND user_level >= :level ORDER BY user_name ASC", "id={$_SESSION['userlogin']['user_id']}&level=2");

                        if ($readAut->getRowCount() >= 1):
                            foreach ($readAut->getResult() as $aut):
                                echo "<option ";

                                if ($post['post_author'] == $aut['user_id']):
                                    echo "selected=\"selected\" ";
                                endif;

                                echo "value=\"{$aut['user_id']}\"> {$aut['user_name']} {$aut['user_lastname']} </option>";
                            endforeach;
                        endif;
                        ?>
                    </select>
                </label>

            </div><!--/line-->

			</br>
			<div class="form-group">
                <label for="gallery_covers" >Enviar Galeria:</label>
                <input type="file" multiple name="gallery_covers[]" id="gallery_covers"/>
            </div>

            <input type="submit" class="btn btn-primary" value="Cadastrar" name="SendPostForm" />
            <input type="submit" class="btn btn-success" value="Cadastrar & Publicar" name="SendPostForm" />

        </form>

    </article>
	</div>
	<div class="col-md-1"></div>


</div> <!-- container -->