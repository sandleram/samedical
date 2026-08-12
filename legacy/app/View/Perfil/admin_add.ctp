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
                                    'action' => 'add'
                                ),
                                'class' => 'smart-form client-form '
                        )
                );
                    echo $this->Form->msg($this->Session->flash());
                    echo $this->Funcoes->menus('geral',$permissao);
                ?>
                
                <header>
                    <?php echo (isset($this->params['pass'][0]))? 'Edição' : 'Cadastro' ;?> de <?php echo $this->Funcoes->titulos($this->params['controller']);?>
                </header>
                <fieldset>
                    <?php 
                        $obrigatorio = '<span class="campo_obrigatorio">*</span>';
                        echo $this->Form->hidden('id');
                        if(isset($this->data[$TABLE]['id'])):
                    ?>
                    <div class="row">
                        <section class="col col-6" >
                            <label class="label"><strong>ID:</strong> <?php echo $this->data[$TABLE]['id']; ?></label>
                        </section>
                    </div>
                    <?php endif; ?>
                    
                    <section>
                        <label class="label">Nome <?php echo $obrigatorio;?></label>
                        <label class="input"> 
                            <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Nome', 'class' => 'input_login', 'maxlength' => '100')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Nome </b>
                        </label>
                    </section>
                   
                        
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Tipo <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('tipo', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>$tipo, 'default' => '')); ?>
                                <i></i>
                            </label>
                            <span class="note">
                                0 = Operacional <i>(tem acessos médicos)</i><br>
                                1 = Administrativo <i>(Não tem acessos médicos)</i><br>
                                2 = Master (Acessa Tudo)
                            </span>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Status <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('status', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>$this->Funcoes->status(), 'default' => '1')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                </fieldset>
                <!--BEGIN -  GERENCIAMENTO DE ACESSOS -->
                <fieldset>
                    <header>
                        <h3>Gerenciamento de Acessos</h3>
                    </header>

                    <table class="table table-bordered "> <!-- table-striped -->
                        <thead>
                            <tr>
                                <th>Módulos</th>
                                <th width="100" style="text-align: center;"><i class="fa fa-ban txt-color-teal"></i> Sem Acesso</th>
                                <th width="80" style="text-align: center;"><i class="fa fa-eye txt-color-teal"></i> Visualizar</th>
                                <th width="130" style="text-align: center;"> <i class="fa fa-edit txt-color-teal"></i> Adicionar / Editar</th>
                                <th width="80" style="text-align: center;"> <i class="fa fa-gears txt-color-teal"></i> Gerenciar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            #PADRAO GERENCIMANTO DE ACESSOS
                            $options = array(
                                0 => '',
                                1 => '',
                                2 => '',
                                3 => ''
                            );
                            /**
                             * PerfilModulo (Array, 3 elements)
                                    2 (Array, 3 elements)
                                    id (String, 2 characters ) 31
                                    modulos_id (String, 1 characters ) 2
                                    permissao (String, 1 characters ) 1
                             */
                            #MONTA RETORNO DOS MÓDULOX X USUÁRIOS SALVOS
                            $modulosArr = array();
                            
                            if(isset($this->data['PerfilModulo'])):
                                foreach ($this->data['PerfilModulo'] as $perfilmodulo):
                                    $modulosArr[$perfilmodulo['modulo_id']]['id']           = $perfilmodulo['id'];
                                    $modulosArr[$perfilmodulo['modulo_id']]['permissao']    = $perfilmodulo['permissao'];
                                    $modulosArr[$perfilmodulo['modulo_id']]['modulos_id']   = $perfilmodulo['modulo_id'];
                                endforeach;
                            endif;
                          
                            #BEGIN - MONTA LISTA DE MÓDULOS
                            foreach ($modulos as $modulo):
                                $modulo = $modulo['Modulo'];
                                
                                if($modulo['modulo_id'] == 0):
                                    
                                    /**
                                     * CRIAÇÃO SUB-MÓDULO
                                     * SUB VEM ANTES DO MÓDULO PELA VERIFICAÇÃO E ADEQUAÇÃO DOS REQUISITOS
                                     */
                                    $subModuloHtml = '';
                                    $existeSubModulo = false;
                                    foreach ($modulos as $subModulo):
                                        $subModulo = $subModulo['Modulo'];
                                        $attributes = array(
                                            'label' => false,
                                            'legend' => false,
                                            'separator' => '</td><td align="center">',
                                            'value' => (isset($modulosArr[$subModulo['id']]['permissao']) ? $modulosArr[$subModulo['id']]['permissao'] : 0),
                                            'name' => "PerfilModulo[".$subModulo['id']."][permissao]"
                                        );
                                        $sub_id = '';
                                        if($subModulo['modulo_id'] == $modulo['id']):
                                            $value_modulo_id =  (isset($modulosArr[$subModulo['id']]['id']) ? $modulosArr[$subModulo['id']]['id'] : '');
                                            $subModuloHtml .= '<tr>';
                                            $subModuloHtml .= '  <td> &nbsp;&nbsp;<span style="font-size:16px;">&bull;</span> '.$subModulo['nome'].' </td>';
                                            $subModuloHtml .=  '  <td align="center">';
                                            $subModuloHtml .=         $this->Form->hidden('moduloid_' . $subModulo['id'], array('name'=>"PerfilModulo[".$subModulo['id']."][id]",'value'=> $value_modulo_id));
                                            $subModuloHtml .=         $this->Form->hidden('modulo_' . $subModulo['id'], array('name'=>"PerfilModulo[".$subModulo['id']."][modulo_id]",'value'=> $subModulo['id']));
                                            $subModuloHtml .=         $this->Form->radio('moduloperm_' . $subModulo['id'], $options, $attributes);
                                            $subModuloHtml .=  '  </td>';
                                            $subModuloHtml .=  '</tr>';
                                            $sub_id = $subModulo['id'];
                                            $existeSubModulo = true;
                                        endif;
                                        
                                        
                                        /**
                                        * CRIAÇÃO SUB2-MÓDULO
                                        * SUB VEM ANTES DO MÓDULO PELA VERIFICAÇÃO E ADEQUAÇÃO DOS REQUISITOS
                                        */
                                        if($existeSubModulo && $sub_id != ''):
                                            $sub2ModuloHtml = '';
                                            foreach ($modulos as $sub2Modulo):
                                                $sub2Modulo = $sub2Modulo['Modulo'];
                                                
                                                $attributes = array(
                                                    'label' => false,
                                                    'legend' => false,
                                                    'separator' => '</td><td align="center">',
                                                    'value' => (isset($modulosArr[$sub2Modulo['id']]['permissao']) ? $modulosArr[$sub2Modulo['id']]['permissao'] : 0),
                                                    'name' => "PerfilModulo[".$sub2Modulo['id']."][permissao]"
                                                );
                                                if($sub2Modulo['modulo_id'] == $subModulo['id'] ):
                                                    $value_modulo2_id =  (isset($modulosArr[$sub2Modulo['id']]['id']) ? $modulosArr[$sub2Modulo['id']]['id'] : '');
                                                    $sub2ModuloHtml .= '<tr>';
                                                    $sub2ModuloHtml .= '  <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-size:14px">&ordm;</span> &nbsp;'.$sub2Modulo['nome'].' </td>';
                                                    $sub2ModuloHtml .=  '  <td align="center">';
                                                    $sub2ModuloHtml .=         $this->Form->hidden('moduloid_' . $sub2Modulo['id'], array('name'=>"PerfilModulo[".$sub2Modulo['id']."][id]",'value'=> $value_modulo2_id));
                                                    $sub2ModuloHtml .=         $this->Form->hidden('modulo_' . $sub2Modulo['id'], array('name'=>"PerfilModulo[".$sub2Modulo['id']."][modulo_id]",'value'=> $sub2Modulo['id']));
                                                    $sub2ModuloHtml .=         $this->Form->radio('moduloperm_' . $sub2Modulo['id'], $options, $attributes);
                                                    $sub2ModuloHtml .=  '  </td>';
                                                    $sub2ModuloHtml .=  '</tr>';
                                                    $subModuloHtml .= $sub2ModuloHtml;
                                                    $sub2ModuloHtml = '';
                                                endif;
                                            endforeach;
                                        endif;
                                    endforeach;
                                    
                                   
                                    
                                    /**
                                     * CRIAÇÃO MODÚLO PAI
                                     */
                                    $disabled = array();
                                    $value = (isset($modulosArr[$modulo['id']]['permissao']) ? $modulosArr[$modulo['id']]['permissao'] : 0);
                                    #SERVE PARA SETAR O MODULO ID_1 COMO PADRÃO
//                                    if($modulo['id'] == 1):
//                                        $disabled = array('disabled'=>'disabled');
//                                        $value = 3;
//                                    endif;
                                    
                                    $attributes = array_merge(array('label' => false,
                                                                    'legend' => false,
                                                                    'separator' => '</td><td align="center">',
                                                                    'value' => $value,
                                                                    'name' => "PerfilModulo[".$modulo['id']."][permissao]",
                                                                ),$disabled );
                                    
                                     
                                    #CASO EXISTA UM SUB-MODULO
                                    $value_modulo_id =  (isset($modulosArr[$modulo['id']]['id']) ? $modulosArr[$modulo['id']]['id'] : '');
//                                     if($existeSubModulo  && $modulo['menu'] != 2):
//                                         $propriedades   = 'align="left" colspan = "4" ';
//                                         $camposModulo   = '<b>Permitir:</b> '.$this->Form->input('moduloperm_' . $modulo['id'] , array('name' => $attributes['name'],  'label' => false, 'div' => false, 'class' => 'input_login', 'options'=>array('0'=>'Não', '3'=> 'Sim'), 'default' => $attributes['value']));
// //                                        $camposModulo = '';
//                                     else:
                                        $propriedades = 'align="center"';
                                        $camposModulo = $this->Form->hidden('moduloid_' . $modulo['id'], array('name'=>"PerfilModulo[".$modulo['id']."][id]",'value'=> $value_modulo_id));
                                        $camposModulo .= $this->Form->hidden('modulo_' . $modulo['id'], array('name'=>"PerfilModulo[".$modulo['id']."][modulo_id]",'value'=> $modulo['id']));
                                        $camposModulo .= $this->Form->radio('moduloperm_' . $modulo['id'], $options, $attributes);
                                    // endif;
                                    
                                    $moduloHtml  = '<tr style="background-color:#aaa !important; color:#fff;">';
                                    $moduloHtml .= '  <td> '.$modulo['nome'].' </td>';
                                    $moduloHtml .= '  <td  '.$propriedades.'>';
                                    $moduloHtml .=       $camposModulo;
                                    $moduloHtml .= '  </td>';
                                    $moduloHtml .= '</tr>';
                                    
                                    #EXIBINDO A TABLEA
                                    echo $moduloHtml.$subModuloHtml;
                                endif;
                            endforeach;
                            #END - MONTA LISTA DE MÓDULOS
                            ?>
                        </tbody>
                    </table>
                </fieldset>
                <!--END -  GERENCIAMENTO DE ACESSOS -->
                <footer>
                    <button type="submit" class="btn btn-primary ">
                        Salvar
                    </button>
                    <span class="campo_obrigatorio">* Campos Obrigatórios</span>
                </footer>

                <?php echo $this->Form->end();?>
            </div>

        </div>
    </div>
    
</div>

<?php
    #ALIMENTANDO TAGS
    $tipo_text = '';
    if(isset($tipoArr) && count($tipoArr)>0){
        $tipo_text = "'".implode("','",$tipoArr)."'";
    }
?>
<script type="text/javascript">
    $(document).ready(function() {
      var tipoAll = [<?php echo $tipo_text;?>];
      $( "#ParametroTipoNovo" ).autocomplete({
        source: tipoAll
      });
      
      
      $('.link_tipo_novo').click(function(){
          $('.link_tipo_novo').fadeOut('slow');
          $('.tipo_old').fadeOut('slow');
          $('.tipo_new').fadeIn('slow');
          
      });
    });
</script>
