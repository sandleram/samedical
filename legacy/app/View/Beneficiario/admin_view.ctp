<?php echo $this->Element('admin/breadcrumb'); ?>

<div id="content">
    

    <div class="row">
        
        <div class="col-sm-12">
            <?php echo $this->Form->msg($this->Session->flash()); ?>        
              <?php #krumo($row); ?>         

              <div class="well well-sm">

                 <div class="row">

                    <div class="col-sm-12 col-md-12 col-lg-3">
                    

                        <?php echo $this->element('admin/beneficiario/beneficiario_resumido');?>
                    </div>
                     <style type="text/css">
                        .smart-timeline-list li {
                             padding: 10px 0 !important;
                         }
                         .smart-timeline-icon>img {
                             border: 1px solid #ffbdcf;
                         }
                     </style>
                     
                     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                     <div class="widget-body">
                        <ul id="myTab1" class="nav nav-tabs bordered" >
                            <?php 

                                    // krumo($permissao);
                                    // krumo($permissoes);

                                    $inicial = 's5';
                                    $active = 'active';

                                    #<!-- TIMELINE -->                                
                                    if(isset($permissoes['Atendimento']['permissao']) && $permissoes['Atendimento']['permissao'] > 0){ 
                                        echo '  <li class="'.$active.'">
                                                    <a href="#s1" data-toggle="tab">Timeline 
                                                        <span class="badge bg-color-blue txt-color-white">'.count($row['listTimeline']).'</span>
                                                    </a>
                                                </li>';
                                        $inicial = ($inicial == 's5')? 's1':'';
                                        $active = '';
                                    }
                                    
                                    #<!-- AFASTADO -->
                                    if(isset($permissoes['Afastado']['permissao']) &&  $permissoes['Afastado']['permissao'] > 0){ 
                                        echo '  <li>
                                                    <a href="#s2" data-toggle="tab">Afastado 
                                                        <span class="badge bg-color-blue txt-color-white">'.count($row['listAfastado']).'</span>
                                                    </a>
                                                </li>';
                                        $inicial = ($inicial == 's5')? 's2':'';
                                        $active = '';
                                    }
                                    
                                    #<!-- BENEFICIO PREVIDENCIARIO -->
                                    if(isset($permissoes['Beneficio_previdenciario']['permissao']) &&  $permissoes['Beneficio_previdenciario']['permissao'] > 0){ 
                                        echo '  <li>
                                                    <a href="#s3" data-toggle="tab">Beneficio Previdenciário 
                                                    <span class="badge bg-color-blue txt-color-white">'.count($row['listBeneficioPrevidenciario']).'</span>
                                                    </a>
                                                </li>';
                                        $inicial = ($inicial == 's5')? 's3':'';
                                        $active = '';
                                    }

                                    #<!-- ABESENTEISMO  -->
                                    if(isset($permissoes['Absenteismo']['permissao']) &&  $permissoes['Absenteismo']['permissao'] > 0){ 
                                        echo '  <li>
                                                    <a href="#s4" data-toggle="tab">Absenteísmo
                                                    <span class="badge bg-color-blue txt-color-white">'.count($row['listAbsenteismo']).'</span>
                                                    </a>
                                                </li>';
                                        $inicial = ($inicial == 's5')? 's4':'';
                                        $active = '';
                                    }

                                    echo '  <li>
                                                <a href="#s5" data-toggle="tab">Cadastro</a>
                                            </li>';
                                    

                                    echo '  <li class="dropdown">';
                                    echo '     <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Ações <b class="caret"></b></a>';
                                    echo '     <ul class="dropdown-menu dropdown-menu-right">';

                                    $inicial = 's5';
                                    $active = 'active';

                                    #<!-- TIMELINE -->                                
                                    if(isset($permissoes['Atendimento']['permissao']) && $permissoes['Atendimento']['permissao'] > 1){ 
                                        echo ' <li><a href="'.Router::url(array('controller'=>'atendimento','action'=>'add',$row['Beneficiario']['id']),true).'">Novo Atendimento</a></li>';
                                        $inicial = ($inicial == 's5')? 's1':'';
                                        $active = '';
                                    }
                                    
                                    #<!-- AGENDAMENTO -->                                
                                    if(isset($permissoes['Agendamento']['permissao']) && $permissoes['Agendamento']['permissao'] > 1){ 
                                        echo ' <li><a href="'.Router::url(array('controller'=>'agendamento','action'=>'add',$row['Beneficiario']['id']),true).'">Novo Agendamento</a></li>';
                                        $inicial = ($inicial == 's5')? 's1':'';
                                        $active = '';
                                    }

                                    #<!-- AFASTADO -->
                                    if(isset($permissoes['Afastado']['permissao']) &&  $permissoes['Afastado']['permissao'] > 1){ 
                                        echo ' <li><a href="'.Router::url(array('controller'=>'afastado','action'=>'add',$row['Beneficiario']['id']),true).'">Novo Afastado</a></li>';
                                        $inicial = ($inicial == 's5')? 's2':'';
                                        $active = '';
                                    }
                                    
                                    #<!-- BENEFICIO PREVIDENCIARIO -->
                                    if(isset($permissoes['Beneficio_previdenciario']['permissao']) &&  $permissoes['Beneficio_previdenciario']['permissao'] > 1){ 
                                        echo ' <li><a href="'.Router::url(array('controller'=>'beneficio_previdenciario','action'=>'add',$row['Beneficiario']['id']),true).'">Novo Benefício Previdenciário</a></li>';
                                        $inicial = ($inicial == 's5')? 's3':'';
                                        $active = '';
                                    }

                                    #<!-- ABESENTEISMO  -->
                                    if(isset($permissoes['Absenteismo']['permissao']) &&  $permissoes['Absenteismo']['permissao'] > 1){ 
                                        echo ' <li><a href="'.Router::url(array('controller'=>'absenteismo','action'=>'add',$row['Beneficiario']['id']),true).'">Novo Absenteísmo</a></li>';
                                        $inicial = ($inicial == 's5')? 's4':'';
                                        $active = '';
                                    }


                                    if($permissao > 0){
                                        echo '         <li class="divider"></li>';
                                        if($permissao > 1){
                                            echo '         <li><a href="'.Router::url(array('action'=>'add'),true).'">Novo Beneficiario</a></li>';
                                            echo '         <li><a href="'.Router::url(array('action'=>'add',$row['Beneficiario']['id']),true).'">Editar Beneficiario</a></li>';
                                        }
                                        echo '         <li><a href="'.Router::url(array('action'=>'index'),true).'">Lista de Beneficiario</a></li>';
                                        
                                        // if($permissao == 3 && in_array($perfil_id, $perfil_adm)){
                                        if($permissao == 3 ){
                                            echo '         <li class="divider"></li>';
                                            echo '         <li class="bg-color-red">';
                                            echo '              <a href="'.Router::url(array('action'=>'delete',$row['Beneficiario']['id']),true).'" class="ajaxMsg" ajaxmsg="Tem certeza que deseja excluir o parâmetro ID: '.$row['Beneficiario']['id'].'" style="color:white">Excluir Beneficiario</a>';
                                            echo '         </li>';
                                        }
                                    }

                                    echo '      </ul>';
                                    echo '  </li>';

                            /*
                            ?>

                            <li class="active">
                                <a href="#s1" data-toggle="tab">Timeline 
                                    <span class="badge bg-color-blue txt-color-white"><?php echo count($row['listTimeline']) ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="#s2" data-toggle="tab">Afastado 
                                    <span class="badge bg-color-blue txt-color-white"><?php echo count($row['listAfastado']);?></span>
                                </a>
                            </li>
                            <li>
                                <a href="#s3" data-toggle="tab">Beneficio Previdenciário 
                                <span class="badge bg-color-blue txt-color-white"><?php echo count($row['listBeneficioPrevidenciario']);?></span>
                                </a>
                            </li>
                            <li>
                                <a href="#s4" data-toggle="tab">Absenteísmo
                                <span class="badge bg-color-blue txt-color-white"><?php echo count($row['listAbsenteismo']);?></span>
                                </a>
                            </li>
                            <li>
                                <a href="#s5" data-toggle="tab">Cadastro</a>
                            </li>

                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Ações <b class="caret"></b></a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="<?php echo Router::url(array('controller'=>'atendimento','action'=>'add',$row['Beneficiario']['id']),true);?>">Novo Atendimento</a></li>
                                    <li><a href="<?php echo Router::url(array('controller'=>'agendamento','action'=>'add',$row['Beneficiario']['id']),true);?>">Novo Agendamento</a></li>
                                    <li><a href="<?php echo Router::url(array('controller'=>'afastado','action'=>'add',$row['Beneficiario']['id']),true);?>">Novo Afastado</a></li>
                                    <li><a href="<?php echo Router::url(array('controller'=>'beneficio_previdenciario','action'=>'add',$row['Beneficiario']['id']),true);?>">Novo Benefício Previdenciário</a></li>
                                    <li><a href="<?php echo Router::url(array('controller'=>'absenteismo','action'=>'add',$row['Beneficiario']['id']),true);?>">Novo Absenteísmo</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo Router::url(array('action'=>'add'),true);?>">Novo Beneficiario</a></li>
                                    <li><a href="<?php echo Router::url(array('action'=>'add',$row['Beneficiario']['id']),true);?>">Editar Beneficiario</a></li>
                                    <li><a href="<?php echo Router::url(array('action'=>'index'),true);?>">Lista de Beneficiario</a></li>
                                    <li class="divider"></li>
                                    <li class="bg-color-red"><a href="<?php echo Router::url(array('action'=>'delete',$row['Beneficiario']['id']),true);?>" class="ajaxMsg" ajaxmsg="Tem certeza que deseja excluir o parâmetro ID: <?php echo $row['Beneficiario']['id'];?>" style="color:white">Excluir Beneficiario</a></li>
                                </ul>
                            </li>
                            <?php  */
                            ?>
                            
                        </ul>

                        <div id="myTabContent1" class="tab-content padding-10">
                            <?php 
                                $inicial = 's5';
                                $active = 'in active';

                                #<!-- TIMELINE -->                                
                                if(isset($permissoes['Atendimento']['permissao']) && $permissoes['Atendimento']['permissao'] > 0){ 
                                    echo '<div class="tab-pane fade '.$active.'" id="s1">';
                                            echo $this->element('admin/beneficiario/timeline');
                                    echo '</div>';
                                    $inicial = ($inicial == 's5')? 's1':'';
                                    $active = '';
                                }
                                
                                #<!-- AFASTADO -->
                                if(isset($permissoes['Afastado']['permissao']) &&  $permissoes['Afastado']['permissao'] > 0){ 
                                    echo '<div class="tab-pane fade '.$active.'" id="s2">';
                                            echo $this->element('admin/beneficiario/afastado');
                                    echo '</div>';
                                    $inicial = ($inicial == 's5')? 's2':'';
                                    $active = '';
                                }
                                
                                #<!-- BENEFICIO PREVIDENCIARIO -->
                                if(isset($permissoes['Beneficio_previdenciario']['permissao']) &&  $permissoes['Beneficio_previdenciario']['permissao'] > 0){ 
                                    echo '<div class="tab-pane fade '.$active.'" id="s3">';
                                            echo $this->element('admin/beneficiario/beneficio_previdenciario');
                                    echo '</div>';
                                    $inicial = ($inicial == 's5')? 's3':'';
                                    $active = '';
                                }

                                #<!-- ABESENTEISMO  -->
                                if(isset($permissoes['Absenteismo']['permissao']) &&  $permissoes['Absenteismo']['permissao'] > 0){ 
                                    echo '<div class="tab-pane fade '.$active.'" id="s4">';
                                            echo $this->element('admin/beneficiario/absenteismo');
                                    echo '</div>';
                                    $inicial = ($inicial == 's5')? 's4':'';
                                    $active = '';
                                }

                                #<!-- CADASTRO  -->
                                echo '<div class="tab-pane fade '.$active.'" id="s5">';
                                        echo $this->element('admin/beneficiario/beneficiario_completo');
                                echo '</div>';
                               
                                
                                
                                
                            ?>
                            

                        
                           
                        </div>

                    </div>

                     </div>
                    
                 </div>
              </div>
        </div>
     </div>
</div>
 
<?php #echo $this->Html->image('/img/avatars/female.png');?>
<?php #echo $row[$TABLE]['nome']; ?> 


<script type="text/javascript">

    // ENTRAR FINALIZADO
    $(document).ready(function(){
        setTimeout(function(){
            $("body").removeClass("minified");
            $('.minifyme').click();
        },1000);
        
    })
    
</script>