<?php // krumo(1);krumo($this->Session->read('Auth.permissoes'));exit;?>
<aside id="left-panel">
    <div class="login-info">
        <span>
           
                <?php
                $usuario = $this->Session->read("Auth.Usuario"); 
                
//                krumo($usuario);
                echo '<a href="javascript:void(0);" style="cursor:default;">';#id="show-shortcut"
                if (isset($usuario['imagem']) && $usuario['imagem'] != ''):
                    $img = 'img/uploads/usuario/mini/'.$usuario['imagem'];
                    if (!file_exists($img)) {
                            if ($usuario['sexo'] == 'Masculino'): $img = '/img/avatars/user_male.png';
                        elseif ($usuario['sexo'] == 'Feminino'): $img = '/img/avatars/user_female.png';
                        else: $img = '/img/avatars/male.png';
                        endif;
                    }else{
                        $img = '/'.$img;
                    }
                else:
                    if ($usuario['sexo'] == 'Masculino'): $img = '/img/avatars/user_male.png';
                    elseif ($usuario['sexo'] == 'Feminino'): $img = '/img/avatars/user_female.png';
                    else: $img = '/img/avatars/male.png';
                    endif;
                endif;
               
                echo $this->Html->image($img, array("alt" => "",
                    "title" => $usuario['nome'],
                    "class" => "offline",
                    "style" => "width:25px;height:25px"));
                
                echo '<span style="font-size: 14px">';
                echo ' '.$usuario['apelido'];
                echo '</span>';
//                echo '<i class="fa fa-angle-down"></i>';
                echo '</a>';
                
                ?>
        </span>
    </div>

    <nav>
        <?php
        $permissoes = $this->Session->read('Auth.permissoes');
        #tipos de permissoes
        $arrayAllow = array(1,2,3); 
        #permissoes somente para administrador master root (usuario 1)
        $arrayAllowRoot = array('pagina:admin_add');
        
        

        #ESTRUTURA DE MENU DEFAULT PARA AJAX E ACTIVE
        $active = ' class="active"';
        $open = ' class="open"';
        $ul = ' style="display:block"';
        $prefix     = '/' . $this->params['prefix'];
        
        
        $edt = '<span style="font-style: italic; font-weight: normal !important; font-size:11px;">[DATA]... </span>';   
        $controller_at = $this->params['controller'];
        $action_at = $this->params['action'];
        
        $editando[$controller_at] = '';
        if(isset($this->params['pass'][0]) && $this->params['action'] == 'admin_add'):
            $editando[$controller_at] = str_replace('[DATA]', 'Editando', $edt);
        elseif(isset($this->params['pass'][0]) && $this->params['action'] == 'admin_view'):
            $editando[$controller_at] = str_replace('[DATA]', 'Visualizando', $edt);
        elseif(isset($this->params['pass'][0]) && $this->params['action'] == 'admin_galeria'):
            $editando[$controller_at] = str_replace('[DATA]', 'Editando Galeria', $edt);
        elseif(isset($this->params['pass'][0]) && $this->params['action'] == 'admin_video'):
            $editando[$controller_at] = str_replace('[DATA]', 'Visualizando Vídeos', $edt);
        elseif(isset($this->params['pass'][0]) && $this->params['action'] == 'admin_video_add'):
            $editando[$controller_at] = str_replace('[DATA]', 'Editando Vídeo', $edt);
        elseif(isset($this->params['pass'][0]) && $this->params['action'] == 'admin_video_view'):
            $editando[$controller_at] = str_replace('[DATA]', 'Visualizano Vídeo', $edt);
        endif;
        
        @$actionOpen[$controller_at] = $open;
        @$actionUl[$controller_at] = $ul;
        @$actionLi[$controller_at][$action_at] = $li;
        
        
//        if(in_array($this->params['controller'], array('acomodacao_servico','acomodacao'))):
//            $editando['acomodacao_hotel'] = '';
//            if(isset($this->params['pass'][1]) && $this->params['action'] == 'admin_add'):
//                $editando['acomodacao_hotel'] = str_replace('[DATA]', 'Editando Serviço', $edt);
//            elseif(isset($this->params['pass'][1]) && $this->params['action'] == 'admin_view'):
//                $editando['acomodacao_hotel'] = str_replace('[DATA]', 'Visualizando Serviço', $edt);
//            elseif($this->params['action'] == 'admin_index'):
//                $editando['acomodacao_hotel'] = str_replace('[DATA]', 'Serviços', $edt);
//            endif;
//        endif;
        
        
        
        ?>
        
        <ul id='my_menu'>
        <?php
            
            if(is_array($permissoes)):
                foreach($permissoes as $permissao):
                    $html   = '<i class="fa fa-lg fa-fw ' . $permissao['icon'] . '"></i> <span class="menu-item-parent ">' . $permissao['nome'] . '</span></a>';
                    if(isset($permissao) && in_array($permissao['permissao'], $arrayAllow)):
                        if($permissao['modulo_id'] == 0):
                            if($permissao['menu'] == 0): #ESTE CASO É UM MENU DIRETO (DASHBOARD)
                                echo '<li>';
                                $caminhoLink = ($permissao['controller'] == '')? $prefix : $prefix . '/'.strtolower($permissao['controller']) ;
                                echo $this->Html->link_('', $caminhoLink , array(), false, $html );
                                echo '</li>';
                                
                            else:
                                $existeSubMenu = false;
                                $htmlModuloSub =  '';
                                        
                                if($permissao['menu'] != 2): #NÃO ACESSA SE FOR MENU DIRETO 
                                    foreach($permissoes as $subPermissao): #VERIFICAÇÃO SUBMENUS
                                        if($subPermissao['modulo_id'] == $permissao['id'] && in_array($permissoes[$subPermissao['controller']]['permissao'], array(1,2,3))):
                                            if($subPermissao['menu'] == 0): #SUBMENU NIVEL 0
                                                $html2   = '<i class="fa fa-lg fa-fw ' . $subPermissao['icon'] . '" style="color: #058dc7;font-size: 20px;"></i> <span class=" ">' . $subPermissao['nome'] . '</span></a>';
                                                $htmlModuloSub .=  '<li '.$this->Funcoes->openMenu(strtolower($subPermissao['controller']),'li').'>';
                                                $caminhoLink = ($subPermissao['controller'] == '')? $prefix : $prefix . '/'.strtolower($subPermissao['controller']) ;
                                                $optionsLink = array('style'=>'padding-left: 20px;');
                                                
                                                if(preg_match('/cron/',$subPermissao['controller'])): #CRON
                                                    $optionsLink = array('style'=>'padding-left: 20px;','target'=>'_blank');
                                                endif;
                                                $htmlModuloSub .=  $this->Html->link_('', $caminhoLink , $optionsLink , false, $html2 );
                                                $htmlModuloSub .=  '</li>';
                                            else:
                                                $htmlModuloSub .= '<li '.$this->Funcoes->openMenu(strtolower($subPermissao['controller']),'li').'>';
                                                $htmlModuloSub .= '<a href="#" title="" style="padding-left: 20px;"><i class="fa fa-lg fa-fw ' . $subPermissao['icon'] . '" style="color: #058dc7;font-size: 20px;"></i><span class="menu-item-parent">'.$subPermissao['nome'].'</span></a>';
                                                $htmlModuloSub .=  '  <ul '.$this->Funcoes->openMenu(strtolower($subPermissao['controller']),'ul').'>';
                                                if (in_array($permissoes[$subPermissao['controller']]['permissao'], array(2,3))):
                                                    
                                                    $edt = isset($editando[strtolower($subPermissao['controller'])])? $editando[strtolower($subPermissao['controller'])] : '' ;
                                                    $htmlModuloSub .=  '<li '.$this->Funcoes->openMenu(strtolower($subPermissao['controller']),'admin_add').'>'.$this->Html->link_('Novo ', $prefix . '/'.strtolower($subPermissao['controller']).'/add', array(), false, $edt ).'</li>' ;
                                                endif;
                                                
                                                $htmlModuloSub .=  '<li '.$this->Funcoes->openMenu(strtolower($subPermissao['controller']),'admin_index').'> '.$this->Html->link('Todos ', $prefix . '/'.strtolower($subPermissao['controller'])).'</li>' ;
                                                $htmlModuloSub .=  '  </ul>';
                                                $htmlModuloSub .=  '</li>';
                                                $existeSubMenu = true;
                                            endif;
                                        endif;
                                    endforeach;
                                endif;

                                #NÃO TIVER SUBMENU ADICIONA-SE O ADD E O INDEX
                                   // if(!$existeSubMenu && $permissao['menu'] == 2): 
                                if($permissao['menu'] == 2): #ACESSA SE FOR MENU DIRETO 
                                    if (in_array($permissao['permissao'], array(2,3))):
                                        $edt = isset($editando[strtolower($permissao['controller'])])? $editando[$this->params['controller']] : '' ;
                                        $htmlModuloSub .=  '<li '.$this->Funcoes->openMenu(strtolower($permissao['controller']),'admin_add').'>'.$this->Html->link_('Novo ', $prefix . '/'.strtolower($permissao['controller']).'/add', array(), false, $edt ).'</li>' ;
                                    endif;
                                    $htmlModuloSub .=  '<li '.$this->Funcoes->openMenu(strtolower($permissao['controller']),'admin_index').'>'.$this->Html->link('Todos', $prefix . '/'.strtolower($permissao['controller'])).'</li>' ;
                                    $existeSubMenu = true;
                                endif;
                                
                                
                                if($existeSubMenu):
                                    $htmlModulo =  '<li '.$this->Funcoes->openMenu(strtolower($permissao['controller']),'li').'>';
                                    $htmlModulo .= '  <a href="#" title="' . $permissao['nome'] . '">' . $html;
                                    $htmlModulo .=  '  <ul '.$this->Funcoes->openMenu(strtolower($permissao['controller']),'ul').'>';
                                    $htmlModulo .= $htmlModuloSub;
                                    $htmlModulo .=  '  </ul>';
                                    $htmlModulo .=  '</li>';
                                    echo $htmlModulo;
                                endif;
                            endif;
                        endif;
                    endif;
                endforeach;
            endif;
        ?>
            
        </ul>
    </nav>
    <span class="minifyme"> <i class="fa fa-arrow-circle-left hit"></i> </span>

</aside>
<!-- END NAVIGATION -->