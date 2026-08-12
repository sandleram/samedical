<?php echo $this->Element('admin/breadcrumb');?>

<div id="content">
    <div class="row hidden-mobile">
        <div class="aviso_ok_js" style="display: none;">
            <div class="alert adjusted alert-success fade in">
                <button class="close" data-dismiss="alert">
                        ×
                </button>
                <i class="fa-fw fa fa-check" style="float:left; margin-top: 3px;"></i> <span class="message"></span>
            </div>
        </div>
         <div class="aviso_error_js" style="display: none;">
            <div class="alert adjusted alert-danger fade in">
                <button class="close" data-dismiss="alert">
                        ×
                </button>
                <i class="fa-fw fa fa-warning "style="float:left; margin-top: 3px;"></i> <span class="message"></span>
            </div>
        </div>
        
        
        
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-align-left">
            <?php 
                if($this->params['controller'] == 'destino'){
                    $tamanho = '710 pixels de Largura por <br>
                                490 pixels de Altura<br><br>';
                }else{
                    $tamanho = '600 pixels de Largura por <br>
                                400 pixels de Altura<br><br>';
                }
                
                $dataHelpContent = '<h5>Extensões Permitidas</h5> 
                                    Somente imagems com extensão(.jpg / .gif / .png).
                                    <br>
                                    <h5>Tamanho Padrão</h5> 
                                        O tamanho padrão para esta imagem é de: <br> 
                                       '.$tamanho.'
                                        <b>Observação:</b> <i style="font-size:11px">Caso coloquem outra imagem de um tamanho diferente deste estipulados acima, 
                                        lembre-se que o sistema adequará a imagem para que siga o padrão informado para não 
                                        termos problemas com o layout, deixar a imagem achatada ou com qualidade inferior.</i>
                                    <br>
                                    ';
                $dataHelpContent .= '<br><h5>Conteúdo Geral</h5>
                                    <b>Titulo e Descrição</b> - Estes são Obrigatórios.<br><br>' ;
                if($this->params['controller'] == 'acomodacao_hotel'){
                    $dataHelpContent .= '<b>Categoria</b>- Categoria só aparecerá para "Hotéis | Resorts | Hotéis Boutique" não é item obrigatório. ';
                }
                
                echo '<label class="input"> '.$this->Html->image('sys/help.png', array(  'width'=>'22px', 'style'=>'cursor:help; margin-left:10px;', 'rel'=>"popover-hover", 'data-placement'=>"bottom", 'data-html'=>'true','data-content'  =>$dataHelpContent)).'</label>';
            ?>
            <div class="page-title">
                <?php 
                    if(in_array($permissao, array(2,3))){
                        echo '<div id="fileuploader">Upload</div>';
                    }
                ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-align-left">
            <?php 
                echo $this->Funcoes->menus('geral',$permissao);
            ?>
        </div>
    </div>
    
    
    <div id="galeria_js" class="row">
        <?php
            #CARREGA A GALERIA 
            echo $this->Element('admin/galeria_js');
            
            $urlJs = Router::url(array('action' => 'admin_galeria_js',$this->params['pass'][0]), true);
            $formData = $this->params['controller']."_id:".$this->params['pass'][0];
            if($this->params['controller'] == 'acomodacao'):
                $urlJs = Router::url(array('action' => 'admin_galeria_js',$this->params['pass'][0],$this->params['pass'][1]), true);
                $formData = $this->params['controller']."_id:".$this->params['pass'][1];
            endif;
            
            
            
        ?>
    </div>

    
    <script type="text/javascript">
        $(document).ready(function(){
            
            
            //http://hayageek.com/docs/jquery-upload-file.php
             $("#fileuploader").uploadFile({
                url:"<?php echo Router::url(array('action' => 'upload_image'), true); ?>",
                allowedTypes:"png,gif,jpg,jpeg",
                autoSubmit:true,
                multiple:true,
                showStatusAfterSuccess:false,
                formData: {<?php echo $formData;?>},
                fileName:"filesGaleria",
                afterUploadAll:function(){
                    $.ajax({
                        url:"<?php echo $urlJs; ?>",
                        statusCode: {
                            403: function() {alert(' Desculpe, você perdeu sua sessão, logue-se novamente!');location.reload(true); },
                            404: function() {alert(' Desculpe, a página solicitada não foi encontrada!'); },
                            500: function() {alert(' Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!'); location.reload(true);}
                        },
                        success: function(data) {
                           $('#galeria_js').html(data);
//                           $('.superbox').SuperBox(); #carrega via galeria_js
                        }
                    });
                }
            });
            
            jQuery.data( document.body , "ac", { p: <?php echo ($permissao)? $permissao : 0 ;?> });
            
            //SALVA O TITULO E DESCRIÇÃO
            $(document).on('click', '.superbox-img-atualizar', function() {
                rel = $(this).attr('rel');
                titulo = $('input[name="title_'+rel+'"]').val();
                descricao = $('textarea[name="description_'+rel+'"]').val();
                controller = '<?php echo $this->params['controller'];?>';
                categoria = '';
                if(controller == 'acomodacao_hotel'){
                    categoria = $('select[name="categoria_'+rel+'"]').val();
                }
                
                if(titulo != ""  && descricao != "" ){
                    $.ajax({
                        url: "<?php echo Router::url(array('action' => 'admin_save_image'), true); ?>",
                        data: {'id': rel, 'titulo': titulo, 'descricao': descricao, 'parametro_id':categoria},
                        type: 'POST',
                        statusCode: {
                            403: function() {alert(' Desculpe, você perdeu sua sessão, logue-se novamente!'); location.reload(true);},
                            404: function() {alert(' Desculpe, a página solicitada não foi encontrada!'); },
                            500: function() {alert(' Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!'); location.reload(true);}
                        },
                        success: function(data) {
                            if(data == 1){
                                $('img[rel="'+rel+'"]').attr('title',titulo);
                                $('img[rel="'+rel+'"]').attr('alt',descricao);
                                $('img[rel="'+rel+'"]').attr('parametro',categoria);
//                                alert('Atualizado com sucesso!');
                                $('.aviso_ok_js .message').html('Informações atualizadas com sucesso!');
                                $('.superbox-close').click();
                            }else{
                                alert('Erro ao Salvar, favor verificar as informações e/ou tentar mais tarde!!');
                            }
                        }
                    });
                }else{
                    alert('Os campos "Título" e "Descrição" são obrigatórios, favor verificar!');
                }
            });


             //DELETA INFORMAÇÕES O TITULO E DESCRIÇÃO
            $(document).on('click', '.superbox-img-excluir', function() {
                rel = $(this).attr('rel');
                pach = $(this).attr('pach');
                pachArr = pach.split('/');
                pach_image = pachArr[pachArr.length-1];
                if(confirm('Tem certeza que deseja deletar esta imagem?')){
                    if(rel != "" && pach_image != "" && pach_image != undefined  ){
                        $.ajax({
                            url: "<?php echo Router::url(array('action' => 'admin_delete_image'), true); ?>",
                            data: {'id': rel,'pach': pach_image},
                            type: 'POST',
                            statusCode: {
                                403: function() {alert(' Desculpe, você perdeu sua sessão, logue-se novamente!'); location.reload(true);},
                                404: function() {alert(' Desculpe, a página solicitada não foi encontrada! ');},
                                500: function() {alert(' Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!'); location.reload(true);}
                            },
                            success: function(data) {
                                if(data == 1){
//                                    alert('');
                                    $('.aviso_ok_js .message').html('Imagem EXCLUÍDA com sucesso!');
                                    $.ajax({
                                        url:"<?php echo $urlJs; ?>",
                                        success: function(data) {
                                           $('#galeria_js').html(data);
//                                           $('.superbox').SuperBox(); #carrega via galeria_js
                                        }
                                    });
                                }else{
                                    alert('Erro ao Deletar o Registro, favor verificar as informações e/ou tentar mais tarde!!');
                                }

                            }
                        });
                    }
                }
            });
        });
        
    </script>
    
    
    
</div>