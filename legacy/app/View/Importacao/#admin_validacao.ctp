<!-- RIBBON -->
<?php echo $this->Element('admin/breadcrumb'); ?>
<!-- END RIBBON -->

<div id="content">
    <?php echo $this->Form->msg($this->Session->flash()); ?>
    <p>&nbsp;</p>
    <h4 style="border-bottom: 1px dotted #d3d3d3; padding-bottom: 5px;">
        Validação de Importação
    </h4>
   


    <?php 
        if(isset($rows) && count($rows)>0){
            
    ?>
    <section id="widget-grid" class="" style="margin-top:20px;">
        <div class="row">
            
            <article class="col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget jarviswidget-color-red" id="wid-id-2" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-check-square-o"></i> </span>
                        <h2>Verificar validações</h2>
                    </header>
                    <div>
                        <div class="widget-body no-padding">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5">QTD</th>
                                            <th width="130">Linha Validada</th>
                                            <th>Descrição</th>
                                            <th width="5">Check</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <?php 
                                            foreach($rows as $kRow => $vRow){
                                                $linha = $kRow + 1;
                                                echo "<tr class='class_tr_{$kRow}'>";
                                                echo "<td> #{$linha}</td>"; 
                                                echo "<td> ";
                                                    
                                                    if($vRow['linha'] != ''){
                                                        echo $vRow['linha'];    
                                                    }else{
                                                        echo "Validação Geral ";
                                                    }
                                                echo "</td> ";
                                                echo "<td> ";
                                                if(is_array($vRow['descricao'])){
                                                    foreach($vRow['descricao'] as $descricao){
                                                        if($descricao != ''){
                                                            echo " - ".$descricao;
                                                        }
                                                    }
                                                }else{
                                                    echo " - ".$vRow['descricao'];
                                                }
                                                echo "</td> ";
                                                echo "<td> {$this->Form->input('ck_validacao',array('label'=>false,'type'=>'checkbox','name'=>'ck_validacao','class'=>'ck_select_validacao','style'=>'margin-top:5px','hiddenField'=>false,'value'=> $kRow))} </td>";
                                                echo "</tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </section>
    <?php } ?>
   <div class='row' style="position: fixed;right: 20px; bottom:5px;">
   <a href="<?php echo Router::url('/admin/importacao/add',true); ?>" class="btn btn-labeled btn-success voltar_importacao"> 
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Voltar para Re-importar </a>
   </div>

    
</div>


<script> 
    //ck_select_validacao
    $('.ck_select_validacao').click(function(){
        key = $(this).val();
        if ($(this).is(':checked')) {
            $('.class_tr_'+key).attr('style','background-color: #d0fdd0 !important;');
        }else{
            $('.class_tr_'+key).attr('style','');
        }
        //background-color: #d0fdd0;
    });

    $('.voltar_importacao').click(function(){
        if(!confirm('Tem certeza que deseja sair da lista de valiação?')){
            return false;
        }
        return true;
    });

</script>