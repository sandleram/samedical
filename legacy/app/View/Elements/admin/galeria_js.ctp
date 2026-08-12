<!-- SuperBox -->
<div  class="superbox col-sm-12">
    <?php
        foreach($row['Galeria'] as $img):
            #ITENS COM HTTP OU HTTPS VINDO DO CONECTOR (retirado campo imagem_thumb porque a imagem tem baixa qualidade)
            if(preg_match('/http:\/\//', $img['imagem']) || preg_match('/https:\/\//', $img['imagem'])):
                if($img['imagem']):
                    echo '<div class="superbox-list">';
                        echo $this->Html->image($img['imagem'], array('data-img'=>$img['imagem'],'alt'=>$img['descricao'], 'title'=>$img['titulo'], 'rel'=>$img['id'], 'parametro'=>$img['parametro_id'], 'class'=>'superbox-img'));
                    echo '</div>';
                endif;
            #ITENS COM IMAGENS CARREGADAS
            else:
                if(file_exists('img/uploads/'.$this->params['controller'].'/'.$img['imagem'])):
                    echo '<div class="superbox-list">';
                    echo $this->Html->image('uploads/'.$this->params['controller'].'/thumb/'.$img['imagem'], array('data-img'=>$this->webroot.'img/uploads/'.$this->params['controller'].'/'.$img['imagem'],'alt'=>$img['descricao'], 'title'=>$img['titulo'], 'rel'=>$img['id'], 'parametro'=>$img['parametro_id'], 'class'=>'superbox-img'));
                    echo '</div>';
                endif;
            endif;       
        endforeach;
    ?>
    <div class="superbox-float"></div>
</div>
<!-- /SuperBox -->

<div class="superbox-show" style="height:300px; display: none"></div>
<script type="text/javascript">
    $(document).ready(function(){
        var options = [];
        
        
        var controller = '<?php echo $this->params['controller'];?>';
        
        options["exibir_categorias"] = false;
        if(controller == 'acomodacao_hotel'){
            options["exibir_categorias"] = true;
            options["categorias"] = '<?php echo isset($paramsOpt)? $paramsOpt : '';?>';
        }
        $('.superbox').SuperBox(options);
    });
</script>

