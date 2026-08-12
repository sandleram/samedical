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
                                    'action' => 'gerencial'
                                ),
                                'class' => 'smart-form client-form '
                        )
                );
                    echo $this->Funcoes->menus('geral',$permissao);
                ?>
                
                <header>
                    Relatório Gerencial
                </header>
                <fieldset>
                    <?php 
                        $obrigatorio = '<span class="campo_obrigatorio">*</span>';
                    ?>
                        
                    <!--<div class="row" style="border-bottom: 1px solid rgba(0,0,0,.1);">-->
                    <section>
                        <?php if(count($this->data)>0){ ?>
                        <div style="float:right;">
                            <a href="<?php echo Router::url(array('action'=>'limpar_session'),true); ?>" class="btn btn-default btn-xs">Limpar Dados Preenchidos</a>
                        </div>
                        <?php }?>
                        <label class="label"><strong>Beneficio <span class="note">(breakeven)</span></strong> <?php echo $obrigatorio;?></label>
                            <label class="select select-multiple">
                                <?php 
                                    $return_beneficios = '';
                                    if(isset($this->data['Relatorio']['beneficio_id']) && count($this->data['Relatorio']['beneficio_id']) > 0){
                                        $return_beneficios = implode(',',$this->data['Relatorio']['beneficio_id']);
                                    }
                                    echo $this->Form->hidden('retorno_beneficio_id', array('value'=>$return_beneficios));
                                    echo $this->Form->input('beneficio_id', array('label' => false, 'div' => false, 'placeholder' => 'Beneficio', 'class' => 'input_login custom-scroll', 'options'=>$beneficioArr, 'multiple'=>true));
                                ?>
                            </label>
                    </section>
                    <div class="row" style="margin-top:20px;">
                        <section class="col col-6">
                            <label class="label"><strong>Subfatura</strong> <?php echo $obrigatorio;?>
                            <a href="javascript:void(0);" class="btn btn-default btn-xs select_all_sub">Todos</a>
                            <a href="javascript:void(0);" class="btn btn-default btn-xs deselect_all_sub">Nenhum</a>
                            </label>
                            <label class="select select-multiple">
                                <?php 
                                    $return_subfatura_id = '';
                                    if(isset($this->data['Relatorio']['subfatura_id']) && count($this->data['Relatorio']['subfatura_id']) > 0){
                                        $return_subfatura_id = implode(',',$this->data['Relatorio']['subfatura_id']);
                                    }
                                    echo $this->Form->hidden('retorno_subfatura_id', array('value'=>$return_subfatura_id));
                                    echo $this->Form->input('subfatura_id', array('label' => false, 'div' => false, 'placeholder' => 'Subfatura', 'class' => 'input_login custom-scroll', 'options'=>array(''=>'Selecione um Beneficio!'), 'multiple'=>true, 'default' => ''));
                                ?>
                            </label>
                            <!--<div class="note"><b>Observação:</b> Pode selecionar mais que uma Subfatura.</div>-->
                        </section>
                    
                        <section class="col col-6">
                            <label class="label"><strong>Plano</strong> <?php echo $obrigatorio;?>
                            <a href="javascript:void(0);" class="btn btn-default btn-xs select_all_plano">Todos</a>
                            <a href="javascript:void(0);" class="btn btn-default btn-xs deselect_all_plano">Nenhum</a>
                            </label>
                            <label class="select select-multiple">
                                <?php 
                                    $return_plano_id = '';
                                    if(isset($this->data['Relatorio']['plano_id']) && count($this->data['Relatorio']['plano_id']) > 0){
                                        $return_plano_id = implode(',',$this->data['Relatorio']['plano_id']);
                                    }
                                    echo $this->Form->hidden('retorno_plano_id', array('value'=>$return_plano_id));
                                    echo $this->Form->input('plano_id', array('label' => false, 'div' => false, 'placeholder' => 'Plano', 'class' => 'input_login custom-scroll', 'options'=>array(''=>'Selecione um Beneficio!'), 'multiple'=>true, 'default' => ''));
                                ?>
                                
                            </label>
                            <!--<div class="note"><b>Observação:</b> Pode selecionar mais que um Plano.</div>-->
                        </section>
                    </div>
    

                    
                    <div class="row">
                        <section class="col col-2">
                            <label class="label"><strong>Data de Referência</strong> <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('data_referencia', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>array('data_pagamento'=>'Data Pagamento', 'data_evento'=>'Data Evento'), 'default' => 'data_pagamento')); ?>
                                <i></i>
                            </label>
                        </section>
                         <section class="col col-2">
                            <label class="label"><strong>Data Inicial</strong> <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('mes', array('label' => false, 'div' => false, 'placeholder' => 'Mês', 'class' => 'input_login', 'options'=>$mesArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                          
                        </section>
                        <section class="col col-2">
                            <label class="label"> &nbsp; </label>
                            <label class="select">
                                <?php echo $this->Form->input('ano', array('label' => false, 'div' => false, 'placeholder' => 'Ano', 'class' => 'input_login', 'options'=>$anoArr, 'default' => '')); ?>
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
              
                        <section class="col col-2">
                            <label class="label"> <strong>Coparticipação </strong><?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('copart', array('label' => false, 'div' => false, 'placeholder' => 'Copart', 'class' => 'input_login', 'options'=>$simnaoArr, 'default' => '0')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label"><strong>Elegilidade </strong></label>
                            <label class="select">
                                <?php echo $this->Form->input('elegibilidade', array('label' => false, 'div' => false, 'placeholder' => 'Elegilidade', 'class' => 'input_login', 'options'=>$elegibilidadeArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    
                    

                    
                    <section style="margin-top:20px;">
                        <label class="label"><strong>Páginas</strong> <?php echo $obrigatorio;?>
                            <a href="javascript:void(0);" class="btn btn-default btn-xs select_all_pages">Todos</a>
                            <a href="javascript:void(0);" class="btn btn-default btn-xs deselect_all_pages">Nenhum</a>
                        
                        </label>
                        <div class="row aviso_paginas ck_paginas" style="margin:5px; padding:10px;">

                            <div class="col col-4 ">
                                <?php
                                    $divisao = round(count($paginasArr)/3);
                                    $count = 0;
                                    
                                    #RETORNO ESCOLHIDOS
                                    $paginas_retorno = array();
                                    if(isset($this->data['paginas']) && count($this->data['paginas'])>0){
                                        foreach($this->data['paginas'] as $vpg){
                                            $vpgina = explode('_',$vpg);
                                            $paginas_retorno[] = $vpgina[0];
                                        }
                                    }
                                    
                                    foreach($paginasArr as $vpg){
                                        if($divisao == $count){
                                            echo '</div><div class="col col-4">';
                                            $count = 0;
                                        }
                                        if(count($paginas_retorno)>0){ #EFETUANDO RETORNO ESCOLHIDO
                                            $checked = '';
                                            if(in_array($vpg['valor'], $paginas_retorno)){
                                                $checked = 'checked="checked"';
                                            }
                                        }else{
                                            $checked = 'checked="checked"';
                                            if(in_array($vpg['valor'], array(14,20))){
                                                $checked = '';
                                            }
                                        }
                                        
                                        echo '  <label class="checkbox " >
                                                    <input type="checkbox" name="paginas[]" class="ck_pagina ck_pagina_'.$vpg['valor'].'" rel="'.$vpg['valor'].'" value="'.$vpg['valor'].'_'.$vpg['ordenacao'].'" '.$checked.'>
                                                    <i></i>'.$vpg['nome'].'
                                                </label>';
                                       $count++;
                                    }
                                ?>
                            </div>
                        </div>
                        <div id="aviso_paginas"></div>
                    </section>
                    
                    <div class="row">
                        <section class="col col-2">
                            <label class="label"> <strong>Maiores Utilizadores </strong><?php echo $obrigatorio;?></label>
                            <label class="input">
                                <?php echo $this->Form->input('maiores_utilizadores', array('label' => false, 'div' => false, 'placeholder' => 'Maiores Utilizadores', 'class' => 'input_login', 'default' => '20')); ?>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label"> <strong>Maiores Prestadores </strong><?php echo $obrigatorio;?></label>
                            <label class="input">
                                <?php echo $this->Form->input('maiores_prestadores', array('label' => false, 'div' => false, 'placeholder' => 'Maiores Prestadores', 'class' => 'input_login', 'default' => '20')); ?>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label"> <strong>Quantidade Hiper Consultadores </strong><?php echo $obrigatorio;?></label>
                            <label class="input">
                                <?php echo $this->Form->input('qtd_consultas_hiper', array('label' => false, 'div' => false, 'placeholder' => 'Quantidade Hiper Consultadores', 'class' => 'input_login', 'default' => '12')); ?>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label"> &nbsp; </label>
                            <label class="input">
                                <?php   
                                    $checked = 'checked="checked"';
                                    #RETORNO ESCOLHIDOS
                                    if(!isset($this->data['trinta_hiper']) && count($this->data) > 0){
                                        $checked = '';
                                    }
                                    echo '<label class="checkbox " >
                                            <input type="checkbox" name="trinta_hiper" class="" value="1" '.$checked.'>
                                            <i></i><strong>30 Maiores Hiperconsultadores</strong>
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
        //START RETURN
        //RETURN BENEFICIO
        retorno_beneficio_id = $('#RelatorioRetornoBeneficioId').val();
        if(retorno_beneficio_id != ''){
            beneficio_id  = retorno_beneficio_id.split(','); 
            retorno = verifica_breakeven(beneficio_id);
            if(retorno == true){
                busca_subfatura(beneficio_id);
                busca_plano(beneficio_id);
            }
        }
        
        
        
        
        
        
        $('#RelatorioBeneficioId').change(function(){
            beneficio_id  = $(this).val(); 
            retorno = verifica_breakeven(beneficio_id);
            if(retorno == true){
                busca_subfatura(beneficio_id);
                busca_plano(beneficio_id);
            }
        });
        
        
        $('.select_all_pages').click(function(){
            $('.deselect_all_pages').click();
            $('.ck_paginas input[type=checkbox]').each(function (key, val) {
                if($(this).attr('rel') != '14' && $(this).attr('rel') != '20'){
                    $(this).click();
                }
            });
        });
        
        $('.deselect_all_pages').click(function(){
            $('.ck_paginas input[type=checkbox]').each(function (key, val) {
                $(this).attr('checked',false);
            });
        });
        
        
        $('.ck_pagina').click(function(){
            if($(this).attr('rel') == '13' || $(this).attr('rel') == '14' || $(this).attr('rel') == '19' ||  $(this).attr('rel') == '20'){
                if($(this).is(':checked')){
                    if($(this).attr('rel') == '13'){
                        $('.ck_pagina_14').attr('checked',false);
                    }else if($(this).attr('rel') == '14'){
                        $('.ck_pagina_13').attr('checked',false);
                    }else if($(this).attr('rel') == '19'){
                        $('.ck_pagina_20').attr('checked',false);
                    }else if($(this).attr('rel') == '20'){
                        $('.ck_pagina_19').attr('checked',false);
                    }
                }else{
                    if($(this).attr('rel') == '13'){
                        $('.ck_pagina_14').attr('checked',true);
                    }else if($(this).attr('rel') == '14'){
                        $('.ck_pagina_13').attr('checked',true);
                    }else if($(this).attr('rel') == '19'){
                        $('.ck_pagina_20').attr('checked',true);
                    }else if($(this).attr('rel') == '20'){
                        $('.ck_pagina_19').attr('checked',true);
                    }
                }
            }

        })
        
        
        
        
        $('.select_all_sub').click(function(){
            $('.deselect_all_sub').click();    
            $('#RelatorioSubfaturaId option').prop('selected',true);
        });
        $('.deselect_all_sub').click(function(){
            $('#RelatorioSubfaturaId option').prop('selected',false);
        });
        
        
        $('.select_all_plano').click(function(){
            $('.deselect_all_plano').click();
            $('#RelatorioPlanoId option').prop('selected',true);
        });
        $('.deselect_all_plano').click(function(){
            $('#RelatorioPlanoId option').prop('selected',false);
        });
        



        $('#RelatorioTipo').change(function(){
            if($(this).val() == 'sinistro'){
                $('.exibe_data_referencia').show();
            }else{
                $('.exibe_data_referencia').hide();
            }

            data_competencia = '';
            busca_competencia($(this).val());
        });
        


        /**
         * RELATÓRIO 
         * @param  {[type]} e){                         mes [description]
         * @return {[type]}      [description]
         */
        $('#relatorio-form').submit(function(){
            mes            = $('#RelatorioMes').val();
            ano            = $('#RelatorioAno').val();
            if(mes != '' && ano != ''){
                mes_ano = ano+'-'+mes+'-1';
                retorno = verifica_competencia(mes_ano);
                return retorno;
            }else{
                return false;
            }
            
        });
        
    });
    
    

    function verifica_competencia(mes_ano){
        if(mes_ano != ''){
            $.ajax({
                url:"<?php echo Router::url('/admin/importacao/existe_competencia/',true); ?>",
                data: {busca: mes_ano},
                dataType: "json",
                type: 'POST',
                async: false,
                cache: false,
                success: function(data) {
                    if(data == false){
                        alert('Não existe nenhuma competência nesta data, favor escolha outra para gerar o Relatório.');
                        retorno = false;
                    }else{
                        retorno = true;
                    }
                }
            });
            return retorno;
        }else{
            return false;
        }
    }
    
    function verifica_breakeven(beneficio_id){
         if(beneficio_id != ''){
            $.ajax({
                url:"<?php echo Router::url('/admin/beneficio/verifica_breakeven/',true); ?>",
                data: {busca: beneficio_id},
                dataType: "json",
                type: 'POST',
                async: false,
                cache: false,
                success: function(data) {
                    if(data == false){
                        alert('Breakeven não pode ser diferente.');
                        $('#RelatorioSubfaturaId').empty();
                        $('#RelatorioPlanoId').empty();
                        $('#RelatorioSubfaturaId').append('<option value="">Selecione outro Benefício!</option>');
                        $('#RelatorioPlanoId').append('<option value="">Selecione outro Benefício!</option>');
                        retorno = false;
                    }else{
                        retorno = true;
                    }
                }
            });
            return retorno;
        }else{
            return false;
        }
    }
    
    
    function busca_subfatura(beneficio_id){
        if(beneficio_id != ''){
            $('#RelatorioSubfaturaId').empty();
            $('#RelatorioSubfaturaId').append('<option value="">carregando subfaturas...</option>');
            
            $.ajax({
                url:"<?php echo Router::url('/admin/subfatura/busca_subfaturas/',true); ?>",
                data: {busca: beneficio_id},
                dataType: "json",
                type: 'POST',
                async: false,
                cache: false,
                success: function(data) {
                    $('#RelatorioSubfaturaId').empty();
                    if(data.length != 0){
                        retorno_subfatura_id = $('#RelatorioRetornoSubfaturaId').val();
                        var retorno_subfatura_ids = [];
                        if(retorno_subfatura_id != ''){
                            retorno_subfatura_ids  = retorno_subfatura_id.split(','); 
                        }
                        
                        jQuery.each(data, function(i, val) {
                            if($.inArray(i, retorno_subfatura_ids) != -1) {
                                $('#RelatorioSubfaturaId').append('<option value="'+i+'" selected="selected">'+val+'</option>');
                            }else{
                                $('#RelatorioSubfaturaId').append('<option value="'+i+'">'+val+'</option>');
                            }
                        });
                    }else{
                        $('#RelatorioSubfaturaId').append('<option value="">Somente este benefício não tem Subfatura, favor selecionar outro Beneficio!</option>');
                    }
                    retorno = true;
                },
                error: function(result) {
//                    alert('Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!');
                    $('#RelatorioSubfaturaId').empty();
                    $('#RelatorioSubfaturaId').append('<option value="">Selecione outro Benefício!</option>');
                    retorno = false;
                }
            });
            return retorno;
        }else{
            return false;
        }
    }
    

  
    function busca_plano(beneficio_id){
            if(beneficio_id != ''){
                $('#RelatorioPlanoId').empty();
                $('#RelatorioPlanoId').append('<option value="">carregando planos...</option>');
                
                $.ajax({
                    url:"<?php echo Router::url('/admin/plano/busca_planos/',true); ?>",
                    data: {beneficio_id: beneficio_id},
                    dataType: "json",
                    type: 'POST',
                    async: false,
                    cache: false,
                    success: function(data) {
                        $('#RelatorioPlanoId').empty();
                        if(data.length != 0){
                            retorno_plano_id = $('#RelatorioRetornoPlanoId').val();
                            var retorno_plano_ids = [];
                            if(retorno_plano_id != ''){
                                retorno_plano_ids  = retorno_plano_id.split(','); 
                            }
                            jQuery.each(data, function(i, val) {
                                if($.inArray(i, retorno_plano_ids) != -1) {
                                    $('#RelatorioPlanoId').append('<option value="'+i+'" selected="selected">'+val+'</option>');
                                }else{
                                    $('#RelatorioPlanoId').append('<option value="'+i+'">'+val+'</option>');
                                }
                                
                            });
                        }else{
                            $('#RelatorioPlanoId').append('<option value="">Somente este benefício não tem Subfatura, favor selecionar outro Beneficio!</option>');
                        }
                        retorno = true;
                    },
                    error: function(result) {
//                        alert('Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!');
                        $('#RelatorioPlanoId').empty();
                        $('#RelatorioPlanoId').append('<option value="">Selecione outro Benefício!</option>');
                        retorno = false;
                    }
                });
                
                return retorno;
            }else{
                return false;
            }

    }
    function busca_competencia(tipo,data_competencia){
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
