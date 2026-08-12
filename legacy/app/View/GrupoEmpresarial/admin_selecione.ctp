<?php echo $this->Element('admin/breadcrumb');?>

<style>
    .table_graph tr td {
        line-height: 60px !important;

    }
    .table_graph2 tr td {
        line-height: 35px !important;
    }
</style>


<div id="content">
    <?php echo $this->Form->msg($this->Session->flash());?> 
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="well no-padding smart-form client-form " >

                    <header style="background-color: #d6d6d6; font-weight: bold;">
                        Selecione um cliente;
                        <?php
                            // $titulo = ' uma Grupo Empresarial | Cliente';
                            // if ($perfil_id != $perfil_root || ($perfil_id == $perfil_administrador && $grupo_empresarial_id == 1)){
                                // $titulo = ' um Cliente';
                            // }
                            // echo $titulo;
                            
//                            krumo($perfil_id);
//                            krumo($perfil_root);
//                            krumo($perfil_administrador);
//                            krumo($grupo_empresarial_id);
                        ?>

                    </header>

                    <fieldset>
                        <?php if ($perfil_id == $perfil_root || ($perfil_id == $perfil_administrador && $grupo_empresarial_id == 1)){ ?>
                        <!-- <div class="row" style="margin-bottom:30px;">
                           <section class="col col-4">
                               <label class="Bold" style="margin-bottom:5px;"><strong>Grupo Empresarial: </strong></label>
                                <label class="select"><i></i> 
                                    <?php #echo $this->Form->input('select_grupo_empresarial_id', array('label' => false, 'div' => false, 'class' => 'input_login select_grupo_empresarial_id', 'options'=>$selectGrupoEmpresarial,  'value'=>$grupo_empresarial_id,'rel'=>$link_geral)); ?> 
                                </label>
                            </section>
                        </div> -->
                        <?php }?>
                        <div class="row" style="margin-bottom:200px">

                           <section class="col col-6">
                                <label class="Bold" style="margin-bottom:5px;"><strong>Cliente: </strong></label>
                                <label class="select"> <i></i>
                                    <?php #echo $this->Form->input('select_cliente_id', array('label' => false, 'div' => false, 'class' => 'input_login select_cliente_id', 'options'=>$selectCliente, 'value'=>$cliente_id,'rel'=>$link_geral)); ?> 
                                    
                                    <?php 
                                    
                                    // echo '<select style="border-radius:6px;"  name="data[select_cliente_id]" id="select_cliente_id" class="input_login select_cliente_id" rel="'.$link_geral.'">';
                                    echo '<select style="border-radius:6px;"  name="data[select_cliente_id]" id="select_cliente_id" class="select2 select_cliente_id" rel="'.$link_geral.'">';
                                        
                                        if(count($selectClienteNew)>0){
                                            $selected = '';
                                            if($this->Session->read("Auth.Usuario.cliente_id") == ''){
                                                $selected = 'selected = "selected"';
                                            }
                                            echo '<option value="" '.$selected.' >Selecione...</option>';

                                            $color_red = 'style="background-color:#f5b8b8;"';
                                            $color_yellow = 'style="color:black; background-color:yellow;"';
                                            foreach($selectClienteNew as $cliente_grupo_id => $cliente_grupo_arr){
                                                echo '<optgroup label="'.$cliente_grupo_arr[0]['ge_nome'].'" style="">';
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
                                                    echo '<option value="'.$cliente_grupo['cliente_id'].'" '.$selected.' '.$color.' style="margin-left:6px;">'.$cliente_grupo['cliente_nome'].'</option>';
                                                }
                                                echo '</optgroup>';
                                            }
                                        }
                                           
                                    echo '</select>';
                                    ?> 
                                </label>
                            </section>
                        </div>
                    </fieldset>
                </div>
        </div>
    </div>
</div>
