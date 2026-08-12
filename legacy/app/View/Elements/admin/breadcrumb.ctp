<?php

$info = array();
$info['models'] = $this->params['models'];
$info['controller'] = $this->params['controller'];
$info['action'] = str_replace($this->params['prefix'] . '_', '', $this->params['action']);
$info['params'] = $this->params['pass'];
$html_add = '';
$idArr = array();



    

if (isset($this->params['pass'][0]) && isset($moduloPaiArr) && isset($moduloPaiArr[$this->params['controller']])) {
    
    $idArr = array($this->params['pass'][0]);
    $moduloPaiModel = $moduloPaiArr[$this->params['controller']];
    $paiNome = $this->Funcoes->titulos($moduloPaiModel, true);

     $html_add .= '<li>' . $this->Html->link($paiNome,  array('controller'=>$moduloPaiModel,'action'=>'index')) . '</li>';
    // if (isset($paiNome) && $paiNome != '' && isset($moduloPaiTipo) && $moduloPaiTipo != '') {
    //     $html_add .= '<li>' . $this->Html->link($moduloPaiTipo . $paiNome,  array('controller'=>$moduloPaiModel, 'action'=>'view', $this->params['pass'][0]) ) . '</li>';
    // }elseif (isset($paiNome) && $paiNome != '') {
        // $html_add .= '<li>' . $this->Html->link($paiNome,  array('controller'=>$moduloPaiModel, 'action'=>'view', $this->params['pass'][0]) ) . '</li>';
    // }
}

$arrayLink = array_merge(array('controller'=>$info['controller'], 'action'=>'index'),$idArr);

if ( $info['action'] == 'index'):
//    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), '/admin/' . $info['controller'] . $id) . '</li>';
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink). '</li>';
    $html_add .= '<li>' . __('All') . '</li>';
    
elseif ( in_array($info['controller'],array('beneficio_previdenciario','atendimento','absenteismo','afastado'))  &&  $info['action'] == 'add'):

    $nomeBeneficiario = $benef['Beneficiario']['nome'];
    
    $html_add .= '<li>' . $this->Html->link('Beneficiarios', Router::url(array('controller'=>'beneficiario'),true)) . '</li>';
    $html_add .= '<li>' . $this->Html->link($nomeBeneficiario, Router::url(array('controller'=>'beneficiario', 'action'=>'view',$this->params['pass'][0]),true)) . '</li>';
    $html_add .= '<li>' . $this->Funcoes->titulos($info['controller'], true) . '</li>';
    if(isset($this->params['pass'][1])){
        $html_add .= '<li>' . __('Edit') . '</li>';
    }else{
        $html_add .= '<li>' . __('Add') . '</li>';
    }


elseif ( in_array($info['controller'],array('agendamento'))  &&  $info['action'] == 'add'):

    $html_add .= '<li>' . $this->Html->link('Beneficiarios', Router::url(array('controller'=>'beneficiario'),true)) . '</li>';
    $html_add .= '<li>' . $this->Html->link($nome, Router::url(array('controller'=>'beneficiario', 'action'=>'view',$this->params['pass'][0]),true)) . '</li>';
    $html_add .= '<li>' . $this->Funcoes->titulos($info['controller'], true) . '</li>';
    if(isset($this->params['pass'][2])){
        $html_add .= '<li>' . __('Edit') . '</li>';
    }else{
        $html_add .= '<li>' . __('Add') . '</li>';
    }

    
    
elseif ($info['action'] == 'add' && isset($info['params']) && count($info['params']) > 0):
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink) . '</li>';
    
    if(count($idArr) > 0){#SUB-PÁGINA
        if(isset($this->params['pass'][1])){
            $html_add .= '<li>' . __('Edit') . '</li>';
        }else{
            $html_add .= '<li>' . __('Add') . '</li>';
        }
    }else{
        $html_add .= '<li>' . __('Edit') . '</li>';
    }
    
elseif ($info['action'] == 'add'):
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink) . '</li>';
    $html_add .= '<li>' . __('Add') . '</li>';
elseif ($info['action'] == 'view'):
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink) . '</li>';
    $html_add .= '<li>' . __('View') . '</li>';
elseif ($info['action'] == 'buscar'):
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink) . '</li>';
    $html_add .= '<li>' . __('Search') . '</li>';
elseif ($info['action'] == 'resultados'):
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink) . '</li>';
    $html_add .= '<li>' . __('Results') . '</li>';
elseif ($info['action'] == 'detalhado'):
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink) . '</li>';
    $html_add .= '<li>' . __('Detailed') . '</li>';
elseif ($info['action'] == 'galeria'):
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true) . ': ' . $titulo, Router::url($this->request->referer())) . '</li>';
    $html_add .= '<li>' . __('Galeria') . '</li>';
elseif ($info['action'] == 'atendimento'):
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink). '</li>';
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->depluralize($this->Funcoes->titulos($info['controller'], true)) . ': ' . $row['Usuario']['nome'], array('controller'=>$info['controller'], 'action'=>'view', $this->params['pass'][0])) . '</li>';
    $html_add .= '<li>' . 'Atendimento' . '</li>';
elseif (in_array($info['action'],array('validacao'))):
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink,array('class'=>'voltar_importacao')). '</li>';
    $html_add .= '<li>' . $this->Html->link('Adicionar', array('controller'=>$info['controller'], 'action'=>'add'),array('class'=>'voltar_importacao')). '</li>';
    $html_add .= '<li>' . $this->Funcoes->titulos($info['action']) . '</li>';        
elseif ($info['controller'] == 'bi' && in_array($info['action'],array('gerencial','rh','medico','lista'))):
    
    if($info['action'] = 'lista'){
        $arrayLink = array('controller'=>$info['controller'], 'action'=>'lista');
        $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink). '</li>';
    }else{
        $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink). '</li>';
    }
    $html_add .= '<li>' . $this->Funcoes->titulos($info['action']) . '</li>';    
elseif (in_array($info['action'],array('gerencial','exportacao'))):
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink). '</li>';
    $html_add .= '<li>' . $this->Funcoes->titulos($info['action']) . '</li>';    
elseif (in_array($info['action'],array('leads_novos','leads_matriculas_fechadas','leads_parados','leads_boletos_gerados'))):
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink). '</li>';
    $html_add .= '<li>' . $this->Funcoes->titulos($info['action']) . '</li>';
elseif (in_array($info['action'],array('leads_novos'))):
    $html_add .= '<li>' . $this->Html->link($this->Funcoes->titulos($info['controller'], true), $arrayLink). '</li>';
    $html_add .= '<li>' . $this->Funcoes->titulos($info['action']) . '</li>';
endif;



if($info['controller'] == 'home' && $info['action'] == 'index'){
    $html_add = '';
}

$html_add .= ' &nbsp;&nbsp;<i class="fa  fa-repeat atualizar_pagina" title="Atualizar Página" style="cursor: pointer;" style="float:right"></i>';


$reset = '<span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class=/"text-warning fa fa-warning/"></i> Aviso! Isso irá redefinir todas as suas configurações do widget." data-html="true"><i class="fa fa-refresh"></i></span> </span>';

$html = '<div id="ribbon">';
$html .= '  <ol class="breadcrumb">';
$html .= '      <li><i class="fa-fw fa fa-home"></i>' . $this->Html->link('Dashboard', '/admin') . '</li>';
$html .= $html_add;
$html .= '  </ol>';
$html .= '
        <style>
            .display_nome{
                display: inline-block;
                margin: 0;
                /*padding: 11px 10px !important;*/
                padding: 5px 10px 0 10px !important;
                background: 0 0;
                vertical-align: top;
                float:right;
                /*border:1px solid #F00;*/
            }
        </style>
    ';

     $localhost = explode( ':', $_SERVER['HTTP_HOST'] );
//    if(!in_array($localhost[0],array('localhost'))){
        $conta_html = '';
        // if($perfil_id == $perfil_root){
        //     $conta_html = '<section style="margin-top:10px;" class="hidden-xs">
        //                         <label class="Bold"><strong>G.E.:</strong></label>
        //                         <label> 
        //                             '.$this->Html->link($selectGrupoEmpresarial[$grupo_empresarial_id],array('controller'=>'grupo_empresarial','action'=>'selecione'),array('style'=>'color:black;')).'
        //                         </label>
        //                     </section>';
        // }
        
        // if(isset($cliente_id) && $cliente_id != ''){
        //     unset($selectCliente['']);
        //     $html .= '  <div class="display_nome hidden-xs">
        //                     <div class="smart-form" style="width:200px; margin:0 !important; padding:0 !important;">
        //                         <fieldset style=" margin:0 !important; padding:0 !important;">
        //                             <section  style="margin-bottom:5px !important;">
        //                                 <label class="select"> <i></i>
        //                                   '.$this->Form->input('select_cliente_id', array('label' => false, 'div' => false, 'class' => 'input_login select_cliente_id', 'options'=>$selectCliente, 'value'=>$cliente_id,'rel'=>$link_geral)).' 
        //                               </label>
        //                             </section>
        //                         </fieldset>
        //                     </div>
        //                 </div>';
        // }
        if(isset($cliente_id) && $cliente_id != ''){
            unset($selectCliente['']);
            #'.$this->Form->input('select_cliente_id', array('label' => false, 'div' => false, 'class' => 'input_login select_cliente_id', 'options'=>$selectCliente, 'value'=>$cliente_id,'rel'=>$link_geral)).' 
            $html .= '  
            
            <style>
                .select2-search:before {
                    margin-top:3px;
                }
                .select2-container  {
                    font-size:12px;
                }
                .select2-result{
                    /*white-space: nowrap;*/
                    font-size:12px;
                }
                .select2-drop{
                    right: 24px !important;
                    margin-left: -150px !important;
                    width:450px !important;
                }
                .select2-container .select2-results__option {
                    white-space: nowrap; 
                }

                .select2-container {
                    width: 100% !important;
                }

                .select2-container .select2-selection {
                    height: auto;
                }

                .select2-dropdown {
                    width: auto !important;
                }
            </style>
            <div class="display_nome hidden-xs">
                            <div class="smart-form" style="width:300px; margin:0 -10px 0 0 !important; padding:0 !important;">
                                <fieldset style=" margin:0 !important; padding:0 !important;">
                                    <section  style="margin-bottom:5px !important;">
                                        <label class="select"> <i></i>

                                        <select style="border-radius:6px;" name="data[select_cliente_id]" id="select_cliente_id" class="select2 select_cliente_id" rel="'.$link_geral.'">';
                                        
                                        if(count($selectClienteNew)>0){

                                            $color_red = 'style="background-color:#f5b8b8;"';
                                            $color_yellow = 'style="color:black; background-color:yellow;"';
                                            foreach($selectClienteNew as $cliente_grupo_id => $cliente_grupo_arr){
                                                $html .= '<optgroup label="'.$cliente_grupo_arr[0]['ge_nome'].'" style="">';
                                                foreach($cliente_grupo_arr as $cliente_grupo){
                                                    $selected = '';
                                                    if($this->Session->read("Auth.Usuario.cliente_id") == $cliente_grupo['cliente_id']){
                                                        $selected = 'selected = "selected"';
                                                    }
                                                    $color = '';
                                                    if($cliente_grupo['cliente_status'] == 0){
                                                        $color = $color_yellow;
                                                    }elseif($cliente_grupo['cliente_status'] == 2){
                                                        $color = $color_red;
                                                    }
                                                    $html .= '<option value="'.$cliente_grupo['cliente_id'].'" '.$selected.' '.$color.' style="margin-left:10px;">'.$cliente_grupo['cliente_nome'].'</option>';
                                                }
                                                $html .= '</optgroup>';
                                            }
                                        }
                                           
            $html .=                ' </select>
                                      </label>
                                    </section>
                                </fieldset>
                            </div>
                        </div>';
        }
        $html .= '  <div class="display_nome hidden-xs" style="margin-right:30px;">'.$conta_html.'</div>';
//    }
    

$html .= '</div>';
echo $html;


?>

