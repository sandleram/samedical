<?php echo $this->Element('admin/breadcrumb');?>

<div id="content">
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding">
                <?php
                echo $this->Form->msg($this->Session->flash());

                echo $this->Form->create(
                        $TABLE, array(
                                'type' => 'file',
                                'id' => $this->params['controller'].'-form',
                                'url' => array(
                                    'controller' => $this->params['controller'],
                                    'action' => 'exportacao'
                                ),
                                'target'=>'_blank',
                                'class' => 'smart-form client-form '
                        )
                );
                    echo $this->Form->msg($this->Session->flash());
                    echo $this->Funcoes->menus('geral',$permissao);
                ?>
                
                <header>
                    Exportação Sinistro ou Fatura
                </header>
                <fieldset>
                    <?php 
                        $obrigatorio = '<span class="campo_obrigatorio">*</span>';
                    ?>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label"> <strong>Tipo de Exportação </strong><?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('tipo', array('label' => false, 'div' => false, 'placeholder' => 'tipo', 'class' => 'input_login', 'options'=>$tipoExportacaoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>

                    <!--<div class="row" style="border-bottom: 1px solid rgba(0,0,0,.1);">-->
                    <div class="row">
                    <section class="col col-6">
                            <label class="label"><strong>Beneficio</strong> <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('beneficio_id', array('label' => false, 'div' => false, 'placeholder' => 'Beneficio', 'class' => 'input_login custom-scroll', 'options'=>$beneficioArr, 'multiple'=>false, 'default' => '')); ?>
                            </label>
                            <!--<div class="note"><b>Observação:</b> Pode selecionar mais que um Beneficio.</div>-->
                    </section>
                    
                        <section class="col col-2 exibe_data_referencia" style="display: none;">
                            <label class="label"><strong>Data de Referência</strong> <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('data_referencia', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>array('data_pagamento'=>'Data Pagamento', 'data_evento'=>'Data Evento'), 'default' => 'data_pagamento')); ?>
                                <i></i>
                            </label>
                        </section>
                        
                    </div>
                    
                    


                    
                    <div class="row">
                       <section class="col col-4">
                            <label class="label"><strong>Data - Mês/Ano</strong> <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('mes_ano', array('label' => false, 'div' => false, 'placeholder' => 'Mês e Ano', 'class' => 'input_login', 'options'=>array(''=> 'Selecione o Tipo de Exportação...'), 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label"> <strong>Periodo </strong><?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('periodo', array('label' => false, 'div' => false, 'placeholder' => 'Ano', 'class' => 'input_login', 'options'=>$periodoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label"> &nbsp; </label>
                            <label class="input">
                                <?php echo '<label class="checkbox " >
                                                <input type="checkbox" name="meses_anteriores" class="" value="1">
                                                <i></i><strong>Meses Anteriores</strong>
                                            </label>'; 
                                ?>
                            </label>
                        </section>
                    </div>
                    
                    
                </fieldset>
                
                <footer>
                    <button type="submit" class="btn btn-primary ">
                        Buscar
                    </button>
                    <span class="campo_obrigatorio">* Campos Obrigatórios</span>
                </footer>

                <?php echo $this->Form->end();?>
            </div>

        </div>
    </div>
    
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#RelatorioTipo').change(function(){
            if($(this).val() == 'sinistro'){
                $('.exibe_data_referencia').show();
            }else{
                $('.exibe_data_referencia').hide();
            }
            
            busca_competencia($(this).val());
        })
        
        
        
//        
//        $('#RelatorioBeneficioId').change(function(){
//            beneficio_id  = $(this).val() ; 
//            retorno1 = busca_subfatura(beneficio_id);
//            retorno2 = busca_plano(beneficio_id);
////            retorno2 = busca_plano(beneficio_id);
//            if(retorno1 == false || retorno2 == false){
////                // false, erro0, erro1, erro2, erro3
////                $('#RelatorioSubfaturaId').empty();
////                $('#RelatorioSubfaturaId').append('<option value="">Selecione o Benefício</option>');
////                $('#RelatorioPlanoId').empty();
////                $('#RelatorioPlanoId').append('<option value="">Selecione o Benefício</option>');
//            }
//        });
//        
       
       
    });
    

    
    function busca_competencia(tipo){
            if(tipo != ''){
                $('#RelatorioMesAno').empty();
                $('#RelatorioMesAno').append('<option value="">carregando competencias...</option>');
                
                $.ajax({
                    url:"<?php echo Router::url('/admin/importacao/busca_competencia/',true); ?>",
                    data: {tipo: tipo},
                    dataType: "json",
                    type: 'POST',
                    async: false,
                    cache: false,
                    success: function(data) {
                        $('#RelatorioMesAno').empty();
                        if(data.length != 0){
                            $('#RelatorioMesAno').append('<option value="">Selecione a Data</option>');
                            jQuery.each(data, function(i, val) {
                                $('#RelatorioMesAno').append('<option value="'+i+'">'+val+'</option>');
                            });
                        }else{
                            $('#RelatorioMesAno').append('<option value="">Nenhuma data encontrada!</option>');
                        }
                        retorno = true;
                    },
                    error: function(result) {
//                        alert('Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!');
                        $('#RelatorioMesAno').empty();
                        $('#RelatorioMesAno').append('<option value="">Selecione o tipo de exportação!</option>');
                        retorno = false;
                    }
                });
                
                return retorno;
            }else{
                return false;
            }

    }




   
</script>