<?php

App::uses('Helper', 'View', 'Html');

class FuncoesHelper extends Helper
{
    var $helpers = array('Html', 'Session');
    public static $perfil_id;
    public static $permissao;

    /**
     * Adiciona um texto com default em um array, geralmente utilizado para os selects
     * @param array $array
     * @param type $default
     * @return type
     */
    public function select_merge(array $array, $default = 'Selecione...')
    {
        $array_selecione = array('' => $default);
        return $array_selecione + $array;
    }

    public function retiraSubArray(array $array, $model)
    {
        $arrayNew = array();
        if (count($array) > 0):
            foreach ($array as $kArray => $vArray):
                $arrayNew[$kArray] = $vArray[$model];
            endforeach;
        endif;
        return $arrayNew;
    }

    public function monetary($value, $text = true)
    {
        $value = (is_numeric($value) && $value != '') ? $value : 0;

        $yesText = '<span style="white-space: nowrap;"> R$ ' . number_format($value, 2, ',', '.') . '</span>';
        $noText = number_format($value, 2, ',', '.');

        return ($text === true) ? $yesText : $noText;
    }

    public function titulos($controller, $plural = false)
    {
        $control = array(
            'usuario'                   => array('single' => 'Usuário',                 'plural' => 'Usuários'),
            'aluno'                     => array('single' => 'Aluno',                   'plural' => 'Alunos'),
            'empresa'                   => array('single' => 'Empresa',                 'plural' => 'Empresas'),
            'parametro'                 => array('single' => 'Parâmetro',               'plural' => 'Parâmetros'),
            'log'                       => array('single' => 'Log',                     'plural' => 'Logs'),
            'importacao'                => array('single' => 'Importação',              'plural' => 'Importações'),
            'grupo_empresarial'         => array('single' => 'Grupo Empresarial',       'plural' => 'Grupos Empresarial'),
            'cliente'                   => array('single' => 'Cliente',                 'plural' => 'Clientes'),
            'subfatura'                 => array('single' => 'Subfatura',               'plural' => 'Subfaturas'),
            'operadora'                 => array('single' => 'Operadora',               'plural' => 'Operadoras'),
            'beneficio'                 => array('single' => 'Benefício',               'plural' => 'Benefícios'),
            'tipo_beneficio'            => array('single' => 'Tipo de Benefício',       'plural' => 'Tipos de Benefício'),
            'plano'                     => array('single' => 'Plano',                   'plural' => 'Planos'),
            'procedimento'              => array('single' => 'Procedimento',            'plural' => 'Procedimentos'),
            'perfil'                    => array('single' => 'Perfil',                  'plural' => 'Perfis'),
            'log'                       => array('single' => 'Log',                     'plural' => 'Logs'),
            'relatorio'                 => array('single' => 'Relatório',               'plural' => 'Relatórios'),
            'gerencial'                 => array('single' => 'Gerencial',               'plural' => 'Gerenciais'),
            'beneficio_previdenciario'  => array('single' => 'Benefício Previdenciário', 'plural' => 'Benefícios Previdenciário'),
            'bi'                        => array('single' => 'BI',                      'plural'  => 'BI'),
            'gerencial'                 => array('single' => 'Gerencial',               'plural' => 'Gerenciais'),
            'medico'                    => array('single' => 'Médico',                  'plural' => 'Médicos'),
            'rh'                        => array('single' => 'RH',                      'plural' => 'RHs'),
            'exportacao'                => array('single' => 'Exportação',              'plural' => 'Exportações')
        );

        if (isset($control[$controller])):
            return ($plural) ? $control[$controller]['plural'] : $control[$controller]['single'];
        else:
            $controller = str_replace('_x_', '_', $controller);
            $controller = str_replace('_', ' ', $controller);
            $controller = ucwords($controller);
            return ($plural) ? $controller : $this->depluralize($controller);
        endif;
    }

    public function depluralize($word)
    {

        $rules = array(
            'ss' => false,
            'os' => 'o',
            'ies' => 'y',
            'xes' => 'x',
            'oes' => 'o',
            'ies' => 'y',
            'ves' => 'f',
            's' => ''
        );

        foreach (array_keys($rules) as $key) {
            if (substr($word, (strlen($key) * -1)) != $key)
                continue;
            if ($key === false)
                return $word;
            return substr($word, 0, strlen($word) - strlen($key)) . $rules[$key];
        }
        return $word;
    }

    public function pluralize($quantity, $singular, $plural = null)
    {
        if ($quantity == 1 || empty($singular))
            return $singular;
        if ($plural !== null)
            return $plural;

        $last_letter = strtolower($singular[strlen($singular) - 1]);
        switch ($last_letter) {
            case 'y':
                return substr($singular, 0, -1) . 'ies';
            case 's':
                return $singular . 'es';
            default:
                return $singular . 's';
        }
    }


    /**
     * @tutorial Função gera um array com índice primário referente ao id do array transmitido
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function arrayIndiceId(array $data, $id = 'id')
    {
        try {
            $dataNew = array();
            if (count($data) > 0):
                foreach ($data as $vData):
                    if (!isset($vData[$id])):
                        throw new Exception();
                    endif;
                    $dataNew[$vData[$id]] = $vData;
                endforeach;
            else:
                throw new Exception();
            endif;

            return $dataNew;
        } catch (Exception $e) {
            return $data;
        }
    }


    /**
     * 
     * @param type $data
     * @return type
     */
    public function idade($data)
    {
        if (isset($data) && !empty($data)) {
            // Separa em dia, mês e ano
            list($ano, $mes, $dia) = explode('-', $data);

            // Descobre que dia é hoje e retorna a unix timestamp
            $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            // Descobre a unix timestamp da data de nascimento do fulano
            $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);
            // Depois apenas fazemos o cálculo já citado :)
            $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

            return $idade;
        } else {
            return '';
        }
    }


    /**
     * @tutorial Função para colocar efeito de abertura no menu!
     * @staticvar array $defaultArr
     * @param type $controller
     * @param type $type
     * @return string
     */
    public function openMenu($controller, $type)
    {
        static $defaultArr = array(
            'li'            =>  'class="open"',
            'ul'            =>  'style="display:block"',
            'admin_add'     =>  'class="active"',
            'admin_index'   =>  'class="active"',
            'admin_view'    =>  'class="active"',
            'admin_galeria' =>  'class="active"',
            'icon'          =>  '<b class="collapse-sign"><em class="fa fa-collapse-o"></em></b>'
        );

        static $modulosPaiArr = array('pergunta'     => 'resposta');

        $controller_at  = $this->params['controller'];
        $action_at      = $this->params['action'];


        if ($controller_at == $controller || (isset($modulosPaiArr[$controller_at]) && $modulosPaiArr[$controller_at] == $controller)) {
            if (preg_match('/admin_/', $type)) {
                if ($type == $action_at) { #EDITANDO
                    return $defaultArr[$type];
                } elseif ($action_at == 'admin_view' && $type == 'admin_add') { #VISUALIZANDO
                    return $defaultArr[$action_at];
                } elseif ($action_at == 'admin_galeria' && $type == 'admin_add') { #Editando Galeria
                    return $defaultArr[$action_at];
                } else {
                    return '';
                }
            } else {
                return $defaultArr[$type];
            }
            return  array($controller => $defaultArr);
        }

        return '';
    }


    /**
     * Funcionalidade de exibição de menus, (geral e lista)
     * @param type $tipo
     * @param type $perfil_id
     * @param type $id
     * @return string
     */
    public function menus($tipo, $permissao, $id = '', $row = array())
    {


        $action = $this->params['action'];
        $model = ucfirst($this->params['controller']);
        $controller = $this->params['controller'];
        $perfil_id = $this->Session->read("Auth.Usuario.perfil_id");
        $AppController = ClassRegistry::init('AppController'); #ESPECIAL
        $moduloPaiArr = $AppController->moduloPaiArr;




        $list_perfil_adm = $AppController->perfil_adm;
        // if($controller != 'beneficiario' and !in_array($perfil_id, $list_perfil_adm)){
        //     $list_perfil_adm = [];
        // }


        // if($tipo == 'lista' && $id != '' && in_array($perfil_id, $list_perfil_adm)):
        if ($tipo == 'lista' && $id != ''):

            $html  = '<div class="btn-group">
                        <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            Ações <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right"  >';
            $idPai = array();
            if (isset($this->params['pass'][0]) && isset($moduloPaiArr[$this->params['controller']])):
                $idPai = array($this->params['pass'][0]); #ID REFERENCIA MÓDULO PAI
            endif;

            if ($this->params['controller'] == 'importacao' && ($permissao == 2 || $permissao == 3)) {
                if ($permissao == 2 || $permissao == 3) {
                    $url_file = 'files/uploads/importacao/' . $row['Importacao']['arquivo_importado'];
                    $file_disabled = '';
                    if ($row['Importacao']['arquivo_importado'] != '') {
                        if (file_exists($url_file)) {
                            $html .= '<li><a href="' . Router::url("/importacao/send_file/" . $id) . '" target="_blank" ">Arquivo</a></li>';
                        } else {
                            $html .= '<li><a href="javascript:void(0);">Sem Arquivo</a></li>';
                        }
                    }
                }
            } else if ($this->params['controller'] == 'importacao_nova' && ($permissao == 2 || $permissao == 3)) {
                if ($permissao == 2 || $permissao == 3) {
                    $caminho = [
                        0 => 'aguardando',
                        1 => 'processando',
                        2 => 'concluido',
                        3 => 'concluido_erro',
                        4 => 'erro'
                    ];
                    $url_file = 'files/uploads/importacao_nova/' . $caminho[$row['ImportacaoNova']['status_processo']] . '/' . $row['ImportacaoNova']['arquivo_importado'];


                    $file_disabled = '';
                    if ($row['ImportacaoNova']['arquivo_importado'] != '') {
                        if (file_exists($url_file)) {
                            $html .= '<li><a href="' . Router::url("/importacao_nova/send_file/" . $id) . '" target="_blank" ">Arquivo</a></li>';
                        } else {
                            $html .= '<li><a href="javascript:void(0);">Sem Arquivo</a></li>';
                        }
                    }
                }
            } elseif ($this->params['controller'] == 'afastado') {
                if ($permissao == 2 || $permissao == 3) {
                    $html .= '<li>' . $this->Html->link(__('Edit'), array_merge(array('action' => 'add'), array($row), array($id))) . '</li>';
                }
            } else {
                #krumo($permissao);
                #if (in_array($perfil_id, $AppController->perfil_adm)) {
                if ($permissao == 1 || $permissao == 2 || $permissao == 3) {
                    $html .= '<li>' . $this->Html->link(__('View'),  array_merge(array('action' => 'view'), $idPai, array($id))) . '</li>';
                }
                if ($permissao == 2 || $permissao == 3) {
                    #if (in_array($perfil_id, $AppController->perfil_adm)) {
                    $html .= '<li>' . $this->Html->link(__('Edit'), array_merge(array('action' => 'add'), $idPai, array($id))) . '</li>';
                }
            }

            if ($permissao == 3) {
                #if(in_array($perfil_id, $AppController->perfil_adm)){
                $html .= '<li class="divider"></li>';
                $html .= '<li class="bg-color-red">' . $this->Html->link(__('Delete'), array_merge(array('action' => 'delete'), $idPai, array($id)), array('class' => 'ajaxMsg', 'ajaxMsg' => 'Tem certeza que deseja excluir o registro ID: ' . $id, 'style' => 'color:white;')) . '</li>';
            }

            $html .= '</ul>';
            $html .= '</div>';

            return $html;

        // elseif($tipo == 'geral' && in_array($perfil_id, $list_perfil_adm)):
        elseif ($tipo == 'geral'):
            $idPai = array();
            $id = '';


            if (isset($this->params['pass'][0]) && isset($moduloPaiArr) && isset($moduloPaiArr[$this->params['controller']])):
                $idPai  = array($this->params['pass'][0]); #ID REFERENCIA MÓDULO PAI
                if (isset($this->params['pass'][1])):
                    $id     = $this->params['pass'][1]; #ID REFERENCIA
                endif;
            elseif (isset($this->params['pass'][0])):
                //                if(isset($this->params['pass'][0])):
                $id     = $this->params['pass'][0]; #ID REFERENCIA
            //                endif;
            endif;
            if (in_array($action, array('admin_index', 'admin_view', 'admin_add', 'admin_galeria')) || $controller == 'relatorio'):
                $search = '';
                #FOI ADICIONADO DIRETO JUNTO AO FORMULÁRIO DE BUSCA
                #if ($action == 'admin_index'):
                #    $search = $this->_View->Element('admin/search_filter');
                #endif;
                $html = $search . '<div class="btn-group" style="float:right; margin-bottom: 10px;">
                                    <button class="btn btn-primary btn-sm dropdown-toggle " data-toggle="dropdown">
                                        Ações <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">';
                $option = '';
                $divider = '<li class="divider"></li>';
                $titulo = $this->titulos($controller);
                #verifica se o término do título acaba com a para mudar o nome do novo ou nova

                $convert = substr($titulo, -1);
                $convert2 = substr($this->tirar_acentos($titulo), -2);
                $novo = ($convert == 'a' || $convert2 == 'ao') ? 'Nova ' : 'Novo ';


                #if ($action == 'admin_index' && in_array($perfil_id, $AppController->perfil_adm)):
                if ($action == 'admin_index' && in_array($permissao, array(2, 3))):
                    if ($controller == 'agendamento'):
                        $option .= '<li>' . $this->Html->link('Lista de Beneficiários ', array('controller' => 'beneficiario')) . '</li>';

                    elseif ($controller == 'afastado'):
                        $option .= '<li>' . $this->Html->link('Exportar ', array('controller' => 'afastado', 'action' => 'index', 1)) . '</li>';


                    else:


                        $option .= '<li>' . $this->Html->link($novo . $titulo, array_merge(array('action' => 'add'), $idPai)) . '</li>';

                        #DOWNLOAD
                        //                    if($controller == 'newsletter'): 
                        //                        $option .= '<li>' . $this->Html->link('Download Lista', array_merge(array('action' => 'download'), $idPai),array('target'=>'_blank')) . '</li>';
                        //                    endif;
                        //                    
                        //                    #Alteração classificação
                        //                    if(in_array($controller,array('pacote','acomodacao_hotel'))):
                        //                        $option .= $divider;
                        //                        $option .= '<li class="dropdown-submenu">';
                        //                        $option .= '<a tabindex="-1" href="javascript:void(0);">Alterar Classificação</a>
                        //                                    <ul class="dropdown-menu" style="left:20px; top:30px;">';
                        //                                    for($i=1;$i<=5;$i++):
                        //                                        $option .= '<li>';
                        //                                        $option .= '<a href="'.Router::url(array('action'=>'classificacao','estrelas'=>$i)).'" ajaxmsg="Tem certeza que deseja alterar a classificação dos registros selecionados para '.$i.' estrela'.(($i > 1)? 's':'').' ?" rel="'.Router::url(array('action'=>'classificacao','estrelas'=>$i)).'" class="ck_classifica_all'.$i.' ajaxMsg">';
                        //                                            for($j=1;$j<=$i;$j++):
                        //                                                $option .= $this->Html->image('icons/estrela.png');
                        //                                            endfor;   
                        //                                        $option .= '</a>';
                        //                                        $option .= '</li>';
                        //                                    endfor; 
                        //                        $option .= '</ul>';
                        //                        $option .= '</li>';
                        //                
                        //                    endif;

                        #if($perfil_id == $AppController->perfil_root):
                        if ($permissao == 3):
                            $option .= $divider;
                            $option .= '<li class="bg-color-red"> ' . $this->Html->link('Excluir Selecionados', array_merge(array('action' => 'delete'), $idPai), array('ajaxMsg' => 'Tem certeza que deseja excluir os registros selecionados?', 'rel' => Router::url(array_merge(array('action' => 'delete'), $idPai)), 'style' => 'color:white', 'class' => 'ck_delete_all ajaxMsg')) . '</li>';
                        endif;
                    endif;


                elseif ($action == 'admin_add'):
                    #f ($id && in_array($perfil_id, $AppController->perfil_adm)):
                    if ($id && in_array($permissao, array(2, 3))):
                        $option .= '<li>' . $this->Html->link($novo . $titulo, array_merge(array('action' => 'add'), $idPai)) . '</li>';
                    endif;
                    if ($id):
                        $option .= '<li>' . $this->Html->link('Visualizar ' . $titulo, array_merge(array('action' => 'view'), $idPai, array($id))) . '</li>';
                    endif;
                    $option .= '<li>' . $this->Html->link('Lista de ' . $this->titulos($controller, true), array_merge(array('action' => 'index'), $idPai)) . '</li>';
                    #if ($id && in_array($perfil_id, $AppController->perfil_adm)):
                    if ($id && $permissao == 3):
                        $option .= $divider;
                        $option .= '<li class="bg-color-red">' . $this->Html->link('Excluir ' . $titulo, array_merge(array('action' => 'delete'), $idPai, array($id)), array('class' => 'ajaxMsg', 'ajaxMsg' => 'Tem certeza que deseja excluir o parâmetro ID: ' . $id, 'style' => 'color:white')) . '</li>';
                    endif;
                elseif ($controller == 'relatorio' && in_array($action, array('admin_afastados', 'admin_atendimentos_pendentes', 'admin_beneficiarios'))):
                    if ($action == 'admin_afastados') {
                        $option .= '<li>' . $this->Html->link('Exportar ', array('controller' => 'relatorio', 'action' => 'admin_afastados', 1)) . '</li>';
                        $option .= $divider;
                    } elseif ($action == 'admin_atendimentos_pendentes') {
                        $option .= '<li>' . $this->Html->link('Exportar ', array('controller' => 'relatorio', 'action' => 'admin_atendimentos_pendentes', 1)) . '</li>';
                        $option .= $divider;
                    } elseif ($action == 'admin_beneficiarios') {
                        $option .= '<li>' . $this->Html->link('Exportar ', array('controller' => 'relatorio', 'action' => 'admin_beneficiarios', 1)) . '</li>';
                        $option .= $divider;
                    } else {
                        #$option .= '<li>Nenhum item </li>';
                    }
                    $option .= '<li>' . $this->Html->link('Lista de Relatórios ', array('controller' => 'relatorio', 'action' => 'index')) . '</li>';



                elseif (in_array($action, array('admin_view', 'admin_galeria'))):
                    #if (in_array($perfil_id, $AppController->perfil_adm)):
                    if (in_array($permissao, array(2, 3))):
                        $option .= '<li>' . $this->Html->link($novo . $titulo, array_merge(array('action' => 'add'), $idPai)) . '</li>';
                    endif;

                    #if ($id && in_array($perfil_id, $AppController->perfil_adm)):
                    if (in_array($permissao, array(2, 3))):
                        $option .= '<li>' . $this->Html->link('Editar ' . $titulo, array_merge(array('action' => 'add'), $idPai, array($id))) . '</li>';
                    endif;

                    $option .= '<li>' . $this->Html->link('Lista de ' . $this->titulos($controller, true), array_merge(array('action' => 'index'), $idPai)) . '</li>';

                    #if ($id && in_array($perfil_id, $AppController->perfil_adm)):
                    if ($id && $permissao == 3):
                        $option .= $divider;
                        $option .= '<li class="bg-color-red">' . $this->Html->link('Excluir ' . $titulo, array_merge(array('action' => 'delete'), $idPai, array($id)), array('class' => 'ajaxMsg', 'ajaxMsg' => 'Tem certeza que deseja excluir o parâmetro ID: ' . $id, 'style' => 'color:white')) . '</li>';

                    endif;
                endif;

                $html2 = '</ul></div>';

                // if (in_array($perfil_id, $AppController->perfil_adm) && !in_array($action, array('admin_index'))):
                //     return $html . $option . $html2;
                // elseif (in_array($perfil_id, $AppController->perfil_adm)):
                //     return $html . $option . $html2;
                // endif;
                if ($permissao == 1 && !in_array($action, array('admin_index'))):
                    return $html . $option . $html2;
                elseif (in_array($permissao, array(2, 3))):
                    return $html . $option . $html2;
                endif;
            endif;
        else:
            return '';
        endif;
    }


    /**
     * Método de exibição de status
     * @param type $id
     * @param type $color
     * @param type $text Este pode ser colocado: Exemplo: '' -> não aparecerá nada, array(''=>'TESTE') -> irá parecer teste.
     * @return string
     */
    public function status($id = null, $color = false, $text = false)
    {

        $inicial = array('' => 'Selecione...');
        if ($text !== false) {
            if ($text === '') {
                $inicial = array();
            } else {
                $inicial = $text;
            }
        }

        $status = array_merge($inicial, array(
            0 => 'Inativo',
            1 => 'Ativo',

        ));
        if ($this->Session->read('Auth.Usuario.id') == 1) {
            $status[2] = 'Excluído';
            //            $status[3] = 'Administrativo';
        }

        if (!is_null($id)) {
            if (isset($status[$id])) {
                if ($color == true) {
                    if ($id == 0) {
                        return '<span style="background-color:yellow; padding: 2px 4px; border-radius: 6px;">' . $status[$id] . '</span>';
                    } elseif ($id == 2) {
                        return '<span style="background-color:red; color:white; padding: 2px 4px; border-radius: 6px;">' . $status[$id] . '</span>';
                    } elseif ($id == 3) {
                        return '<span style="background-color:blue; color:white; padding: 2px 4px; border-radius: 6px;">' . $status[$id] . '</span>';
                    } else {
                        return '<span style="background-color:green; color:white; padding: 2px 4px; border-radius: 6px;">' . $status[$id] . '</span>';
                    }
                } else {
                    return $status[$id];
                }
            }
            return '';
        } else {
            return $status;
        }
    }

    public function status_processo($id = null, $color = false, $text = false)
    {

        $inicial = array('' => 'Selecione...');
        if ($text !== false) {
            if ($text === '') {
                $inicial = array();
            } else {
                $inicial = $text;
            }
        }

        $status = array_merge($inicial, array(
            0 => 'Aguardando',
            1 => 'Processando',
            2 => 'Concluído',
            3 => 'Concluído com erro',
            4 => 'Erro'
        ));


        if (!is_null($id)) {
            if (isset($status[$id])) {
                if ($color == true) {
                    if ($id == 0) {
                        return '<span style="background-color:yellow; padding: 2px 4px; border-radius: 6px;">' . $status[$id] . '</span>';
                    } elseif ($id == 1) {
                        return '<span style="background-color:blue;color:white; padding: 2px 4px; border-radius: 6px;">' . $status[$id] . '</span>';
                    } elseif ($id == 2) {
                        return '<span style="background-color:green; color:white; padding: 2px 4px; border-radius: 6px;">' . $status[$id] . '</span>';
                    } elseif ($id == 3) {
                        return '<span style="background-color:orange; color:white; padding: 2px 4px; border-radius: 6px;">' . $status[$id] . '</span>';
                    } elseif ($id == 4) {
                        return '<span style="background-color:red; color:white; padding: 2px 4px; border-radius: 6px;">' . $status[$id] . '</span>';
                    } else {
                        return $status[$id];
                    }
                } else {
                    return $status[$id];
                }
            }
            return '';
        } else {
            return $status;
        }
    }


    /**
     * Método de exibição de status
     * @param type $id
     * @param type $color
     * @param type $text Este pode ser colocado: Exemplo: '' -> não aparecerá nada, array(''=>'TESTE') -> irá parecer teste.
     * @return string
     */
    public function status_processamento($id = null, $color = false, $text = false)
    {

        $status = array(
            0 => 'Aguardando',
            1 => 'Processado',
            2 => 'Erro',
            3 => 'Em Processamento',
            4 => 'Reprocessando'
        );

        if (!is_null($id)) {
            if (isset($status[$id])) {
                if ($color == true) {
                    if ($id == 0) {
                        return '<span style="background-color:blue; color:white; padding: 2px 4px">' . $status[$id] . '</span>';
                    } elseif ($id == 1) {
                        return '<span style="background-color:green; color:white; padding: 2px 4px">' . $status[$id] . '</span>';
                    } elseif ($id == 2) {
                        return '<span style="background-color:red; color:white; padding: 2px 4px">' . $status[$id] . '</span>';
                    } elseif ($id == 3) {
                        return '<span style="background-color:yellow; padding: 2px 4px">' . $status[$id] . '</span>';
                    } elseif ($id == 4) {
                        return '<span style="background-color:orange; padding: 2px 4px">' . $status[$id] . '</span>';
                    } else {
                        return '<span style=" color:black; padding: 2px 4px">' . $status[$id] . '</span>';
                    }
                } else {
                    return $status[$id];
                }
            }
            return $id;
        } else {
            return $id;
        }
    }


    /**
     * Altera as URLs para uma forma amigável
     * @param type $str
     * @return type
     */
    public function NormalizaURL($str)
    {
        $str = strtolower(utf8_decode($str));
        $i = 1;
        $str = strtr($str, utf8_decode('àáâãäåæçèéêëìíîïñòóôõöøùúûýýÿ'), 'aaaaaaaceeeeiiiinoooooouuuyyy');
        $str = preg_replace("/([^a-z0-9])/", '-', utf8_encode($str));
        while ($i > 0) $str = str_replace('--', '-', $str, $i);
        if (substr($str, -1) == '-') $str = substr($str, 0, -1);
        return $str;
    }


    /**
     * Faz uma demarcação no texto (geralmente utilizado filtro da busca
     * Usado em (Destino / Noticia)
     * @param type $str -> Texto do Banco
     * @param type $filtro -> Filtro Busca
     * @example <?php echo $this->Funcoes->marcatexto($row['Noticia']['texto_chamada'],$s_texto_chamada);?>
     */
    public function marcatexto($str = '', $filtro = '', $qtd_limite = 3)
    {
        $error = 0;
        $content_default = $str;
        if ($str != '' && $filtro != '') {
            $buscaArr = explode(' ', $filtro);
            if (count($buscaArr) > 0) {
                foreach ($buscaArr as $vBusca) {
                    if (strlen($vBusca) <= $qtd_limite) {
                        $error = 1;
                        break;
                    }
                    $str = str_replace($vBusca, '<span class="destaque_busca">' . $vBusca . '</span>', $str);
                    $vBusca = strtolower($vBusca);
                    $str = str_replace($vBusca, '<span class="destaque_busca">' . $vBusca . '</span>', $str);
                    $vBusca = ucfirst($vBusca);
                    $str = str_replace($vBusca, '<span class="destaque_busca">' . $vBusca . '</span>', $str);
                    $vBusca = strtoupper($vBusca);
                    $str = str_replace($vBusca, '<span class="destaque_busca">' . $vBusca . '</span>', $str);
                }
            }
        }
        echo ($error > 0) ? $content_default : $str;
    }



    /**
     * FAZ TRADUÇAO DE TEXTO DE INGLÊS PAR PORTUGUÊS
     * @param type $str
     */
    public function translate($str)
    {
        #DADOS
        #user: litoralverdeapi@gmail.com
        #pws: litoralverde12@
        //        $apiKey = '<paste your API key here>';
        //        $url = 'https://www.googleapis.com/language/translate/v2/languages?key=' . $apiKey;
        //
        //        $handle = curl_init($url);
        //        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);     //We want the result to be saved into variable, not printed out
        //        $response = curl_exec($handle);                         
        //        curl_close($handle);
        //
        //        print_r(json_decode($response, true));
    }


    /**
     * FAZER QUEBRA DE LINHA DE TEXTO DE TEXTAREA
     * @param type $str
     * @return type
     */
    public function quebraLinhaTextarea($str)
    {
        return nl2br($str);
    }


    /**TIRA ACENTUAÇÃO
     */
    public function tirar_acentos($dado)
    {
        // $dado = trim( str_replace( "\'", "", $dado) );
        // $dado = str_replace( "'", "", $dado );
        $dado = str_replace("–", "-", $dado);
        $dado = str_replace("ç", "c", $dado);
        $dado = str_replace("Ç", "C", $dado);
        $dado = ereg_replace("[áàâã]", "a", $dado);
        $dado = ereg_replace("[ÁÀÂÃ]", "A", $dado);
        $dado = ereg_replace("[éèê]", "e", $dado);
        $dado = ereg_replace("[ÉÈÊ]", "E", $dado);
        $dado = ereg_replace("[íìîï]", "i", $dado);
        $dado = ereg_replace("[ÍÌÎ]", "I", $dado);
        $dado = ereg_replace("[óòôõ]", "o", $dado);
        $dado = ereg_replace("[ÓÒÔÕ]", "O", $dado);
        $dado = ereg_replace("[úùû]", "u", $dado);
        $dado = ereg_replace("[ÚÙÛ]", "U", $dado);
        return $dado;
    }


    /**
     * NORMALIZA TEXTO COM PRIMEIRA LETRA DE CADA PALAVRA MAIÚSCULA
     * @param type $str
     * @return type
     */
    public function normalizaTexto($str)
    {
        return ucwords(strtolower($str));
    }




    /**
     * VERIFICA TIPO SE FEMININO OU NÃO DO TEXTO
     * @param type $str
     * @return type
     */
    public function convertReferenciaFeminino($str)
    {
        $convert = substr($str, -1);
        if ($convert == 's') {
            $convert = substr($str, -2);
            $convert2 = substr($this->tirar_acentos($str), -3);
            $result = ($convert == 'as' || $convert2 == 'aos') ? 'as' : 'os';
        } else {
            $convert2 = substr($this->tirar_acentos($str), -2);
            $result = ($convert == 'a' || $convert2 == 'ao') ? 'a' : 'o';
        }


        return $result;
    }


    /**
     * VERIFICA EXISTENCIA DO ARQUIVO EXTERNO 
     * EXEMPLO: uma imagem externa
     * @param type $url
     * @return type
     */
    public function checkExternalFile($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $retCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $retCode;
    }


    /**
     * LIMITA QUANTIDADE DE CARACTERES
     * @param string $str
     * @param type $limit
     * @return string
     */
    public function limita_caracteres($str, $limit = 10)
    {
        if (strlen($str) > $limit) {
            $str = substr($str, 0, $limit) . '...';
        }
        return $str;
    }



    /**
     * AVISO POPUP DINÂMICO PARA O FRONT 
     * EXEMPLO: newsletter está usando no sidebar
     * @param type $str
     * @param type $title
     * @param type $width
     * @return string
     */
    public function aviso_dinamico($str, $title = 'Aviso', $width = '50%')
    {
        $html = '';
        if ($str) {
            $html = '<a href="#" data-reveal-id="modalNews" class="click_aviso"></a>
                    <div id="modalNews" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog" style="width: ' . $width . '; position:fixed; top:200px;">
                      <h5 id="modalTitle">' . $title . '</h5>
                      ' . $str . '
                      <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                    </div>';
        }
        return $html;
    }




    /**
     * BUSCA TAGS
     * 
     * @param type $tipo_id Este pode ser usado tanto para buscar um ID ou TIPO
     * @param type $defaultSelect - "Selecione..."
     * @return type
     * @example1 $this->Funcoes->busca_tags(1); Busca uma tag
     * @example2 $this->Funcoes->busca_tags('Notícia'); Busca tags por notícia
     * @example3 $this->Funcoes->busca_tags('Outros', 'Tags...');  Busca tags por outros mas adicionando o default como Tags... (list)
     */
    public function busca_tags($tipo_id = 'Outros', $defaultSelect = '')
    {
        $tipoArr = array('Notícia' => 1, 'Galeria' => 2, 'Outros' => 3);

        if ($tipo_id == '') {
            return array();
        }

        $Tag = ClassRegistry::init('Tag');
        if (isset($tipoArr[$tipo_id])) {
            $conditions = array(
                'conditions' => array('Tag.tipo' => $tipoArr[$tipo_id], 'Tag.status' => '1'),
                'fields' => array('Tag.id', 'Tag.nome'),
                'order' => array('Tag.nome' => 'ASC'),
                'recursive' => -1
            );
            $tagsArr = $Tag->find('list', $conditions);

            if ($defaultSelect != ''):
                $tagsArr = $this->select_merge($tagsArr, $defaultSelect);
            endif;

            return $tagsArr;
        } else {
            $conditions = array(
                'conditions' => array('Tag.id' => $tipo_id),
                'fields' => array('Tag.nome'),
                'recursive' => -1
            );
            $tagsArr = $Tag->find('first', $conditions);

            return isset($tagsArr['Tag']['nome']) ? $tagsArr['Tag']['nome'] : '';
        }
    }



    /**
     * Método de exibição de vários status
     * @param type $id
     * @param type $color
     * @param type $text Este pode ser colocado: Exemplo: '' -> não aparecerá nada, array(''=>'TESTE') -> irá parecer teste.
     * @return string
     */
    public function status_aluno($str, $download = false)
    {
        if ($str != '') {
            if ($download == true) {
                if (preg_match('/reprovado/', strtolower($str)) || preg_match('/pendente/', strtolower($str))) {
                    return '<span style="color:red; padding: 2px 4px">' . $str . '</span>';
                } elseif (preg_match('/não conseguiu/', strtolower($str)) || preg_match('/não compareceu/', strtolower($str))) {
                    return '<span style="color:#FDD302; padding: 2px 4px">' . $str . '</span>';
                } elseif (preg_match('/ às /', $str) || preg_match('/compareceu/', strtolower($str)) || preg_match('/aprovado/', strtolower($str)) || preg_match('/entregue/', strtolower($str)) || preg_match('/assinado/', strtolower($str)) || preg_match('/efetuado/', strtolower($str))) {
                    return '<span style="color:green; padding: 2px 4px">' . $str . '</span>';
                } elseif (preg_match('/potencial/', strtolower($str)) || preg_match('/semm interesse/', strtolower($str))) {
                    return '<span style="color:blue; padding: 2px 4px">' . $str . '</span>';
                }
            } else {
                if (preg_match('/reprovado/', strtolower($str)) || preg_match('/pendente/', strtolower($str))) {
                    return '<span style="background-color:red; color:white; padding: 2px 4px">' . $str . '</span>';
                } elseif (preg_match('/não conseguiu/', strtolower($str)) || preg_match('/não compareceu/', strtolower($str))) {
                    return '<span style="background-color:yellow; padding: 2px 4px">' . $str . '</span>';
                } elseif (preg_match('/ às /', $str) || preg_match('/compareceu/', strtolower($str)) || preg_match('/aprovado/', strtolower($str)) || preg_match('/entregue/', strtolower($str)) || preg_match('/assinado/', strtolower($str)) || preg_match('/efetuado/', strtolower($str))) {
                    return '<span style="background-color:green; color:white; padding: 2px 4px">' . $str . '</span>';
                } elseif (preg_match('/potencial/', strtolower($str)) || preg_match('/semm interesse/', strtolower($str))) {
                    return '<span style="background-color:blue; color:white; padding: 2px 4px">' . $str . '</span>';
                }
            }
        }
        //        return '<span style="background-color:yellow; padding: 2px 4px">'.$str.'</span>';
        //        return '<span style="background-color:red; color:white; padding: 2px 4px">'.$str.'</span>';
        //        return '<span style="background-color:blue; color:white; padding: 2px 4px">'.$str.'</span>';
        //        return '<span style="background-color:green; color:white; padding: 2px 4px">'.$str.'</span>';

        return $str;
    }




    public function valor_negativo($value = 0)
    {
        if ($value < 0):
            $value = '<span style="color:#F00;">' . $value . '</span>';
        endif;


        return $value;
    }


    function word_limiter($str, $limit = 5, $end_char = '&#8230;')
    {
        if (trim($str) == '') {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);

        if (strlen($str) == strlen($matches[0])) {
            $end_char = '';
        }

        return rtrim($matches[0]) . $end_char;
    }



    function character_limiter($str, $n = 15, $end_char = '&#8230;')
    {
        if (strlen($str) < $n) {
            return $str;
        }

        $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

        if (strlen($str) <= $n) {
            return $str;
        }

        $out = "";
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val . ' ';

            if (strlen($out) >= $n) {
                $out = trim($out);
                return (strlen($out) == strlen($str)) ? $out : $out . $end_char;
            }
        }
    }


    public function busca_tags_old($tipo_id = 'Outros', $defaultSelect = '')
    {
        $tipoArr = array('Notícia' => 1, 'Galeria' => 2, 'Outros' => 3);

        if ($tipo_id == '') {
            return array();
        }

        $Tag = ClassRegistry::init('Tag');
        if (isset($tipoArr[$tipo_id])) {
            $conditions = array(
                'conditions' => array('Tag.tipo' => $tipoArr[$tipo_id], 'Tag.status' => '1'),
                'fields' => array('Tag.id', 'Tag.nome'),
                'order' => array('Tag.nome' => 'ASC'),
                'recursive' => -1
            );
            $tagsArr = $Tag->find('list', $conditions);

            if ($defaultSelect != ''):
                $tagsArr = $this->select_merge($tagsArr, $defaultSelect);
            endif;

            return $tagsArr;
        } else {
            $conditions = array(
                'conditions' => array('Tag.id' => $tipo_id),
                'fields' => array('Tag.nome'),
                'recursive' => -1
            );
            $tagsArr = $Tag->find('first', $conditions);

            return isset($tagsArr['Tag']['nome']) ? $tagsArr['Tag']['nome'] : '';
        }
    }

    /**
     * FILTRO DINÂMICO
     * @param type $row
     */
    public function filtro_gera_class_view($row)
    {
        $class = '';

        if (count($row) > 0) {
            $tiposArr = array('tipo', 'estrelas', 'nacional', 'pais', 'bairro', 'reevoo_classificacao');
            foreach ($tiposArr as $tipo):
                if (isset($row[$tipo])):
                    $class = $row[$tipo];
                endif;
            endforeach;
        }

        return $class;
    }

    public function vs_monta_menu($cod_perfil)
    {
        //        require_once ROOTT.'/class/trata.class.php';
        //        require_once ROOTT.'/class/estrutura.class.php';
        //        $estrutura = new estrutura();

        $MenuModel = ClassRegistry::init('Menu'); #ESPECIAL

        $itens = 0;
        $filtro = "";
        $inner = "";

        $inner .= $cod_perfil != "" ? " JOIN menu_perfil b ON a.cod_menu=b.cod_menu " : "";
        $filtro .= $cod_perfil != "" ? " and b.cod_perfil=$cod_perfil " : "";
        $filtro .= (($_SESSION['cod_perfil'] == 1 || $_SESSION['cod_perfil'] == 2) && isset($_SESSION["cod_conta"])) != "" ? " AND a.cod_menu NOT IN ( SELECT mpg.cod_menu FROM grupo_sem_acesso gsp JOIN menu_perfil_grupo mpg ON ( gsp.cod_perfil_acesso = mpg.cod_perfil_grupo ) JOIN menu men ON (men.cod_menu = mpg.cod_menu) WHERE gsp.cod_conta = " . $_SESSION['cod_conta'] . " AND men.menu!='Usuario')" : "";

        $sql = ''
            . ' SELECT '
            . ' a.*, a.primeiro as primeiros '
            . ' FROM menu a USE INDEX(idx_menu__menu) '
            . $inner
            . ' WHERE a.menu=\'Sistema\'';

        /*
         * busca se a tabela configuracao tem algum tratamento especial para a conta que está acessando o VS
         * ex.: na TOTVS os funcionarios (nivel 4.00) não podem visualizar os menus "selecionar conta" e "manuais e procedimentos"
         */
        //        if ($this->buscar_configuracao('estrutura','1') != false) {
        //          $sql .= ' AND a.cod_menu <> 243 AND a.cod_menu <> 126';
        //      }

        $sql .= ' AND status=\'1\''
            . $filtro
            . ' ORDER BY a.primeiro, a.segundo, a.terceiro, a.quarto';

        $menuArr = $MenuModel->query($sql);


        if (count($menuArr) > 0) {
            $this->html = "<ul id=\"nav\">\n";
            $primeiro = "";
            $segundo = "";
            $terceiro = "";
            $quarto = "";
            $abre1 = false;
            $abre2 = false;
            $abre3 = false;

            foreach ($menuArr as $kMenu => $vMenu) {
                $vMenu = $vMenu['a'];
                if ($primeiro == $vMenu['primeiro'] && $vMenu['segundo'] == 1 && $vMenu['terceiro'] == 0 || ($abre1 == false && $vMenu['segundo'] > 1)) {
                    $this->html .= "<ul class='fundo_sub'>\n";
                    $abre1 = true;
                    $itens = 0;
                }

                if ($primeiro == $vMenu['primeiro'] && $vMenu['terceiro'] == 1 && $vMenu['quarto'] == 0 || ($abre2 == false && $vMenu['terceiro'] > 1)) {
                    $this->html .= "<ul class='subsub_estilo'>\n";
                    $abre2 = true;
                    $itens = 0;
                }

                if ($primeiro == $vMenu['primeiro'] && $vMenu['quarto'] == 1) {
                    $this->html .= "<ul class='subsub_estilo'>\n";
                    $abre3 = true;
                    $itens = 0;
                }


                #VALIDA LINK para direcionamento correto
                if (preg_match('/v4/', $this->params->here)) {
                    $vMenu['link'] = str_replace('../../', ENDERECO, $vMenu['link']);
                }
                $link = ($vMenu['link'] == '#') ? 'javascript:void(0)' : $vMenu['link'];

                if ($vMenu['segundo'] == 0) {
                    $this->html .= "<li><a href='$link' class='menu'>" . $this->trata_maiuscula($vMenu['nome']);
                    $this->html .= "</a>\n";
                } else {
                    if ($primeiro == $vMenu['primeiro'] && $vMenu['segundo'] == 1 && $vMenu['terceiro'] == 0 || $itens == 0) {
                        $this->html .= "<li class='sub_estilo'>";
                    } else {
                        $this->html .= "<li>";
                    }

                    if ($vMenu['quarto'] > 0) {
                        $this->html .= "<a href='$link' class='submenu'>" . $vMenu['nome'] . "</a></li>\n";
                    } else {
                        if ($vMenu['segundo'] > 0 && $vMenu['terceiro'] == 0) {
                            if ($menuArr[$kMenu + 1]['a']['terceiro'] > 0) {
                                $this->html .= "<a href='$link' class='submenu_seta'>" . $vMenu['nome'] . "</a>\n";
                            } else {
                                $this->html .= "<a href='$link' class='submenu'>" . $vMenu['nome'] . "</a>\n";
                            }
                        } else {
                            if (isset($menuArr[$kMenu + 1]['a']['quarto']) && $menuArr[$kMenu + 1]['a']['quarto'] > 0) {
                                $this->html .= "<a href='$link' class='submenu_seta'>" . $vMenu['nome'] . "</a>\n";
                            } else {
                                $this->html .= "<a href='$link' class='submenu'>" . $vMenu['nome'] . "</a>\n";
                            }
                        }
                    }
                    $itens = $itens + 1;
                }
                $primeiro = $vMenu['primeiro'];
                $segundo = $vMenu['segundo'];
                $terceiro = $vMenu['terceiro'];
                $quarto = $vMenu['quarto'];

                if ($abre3 == true) {
                    if ($menuArr[$kMenu + 1]['a']['terceiro'] != $terceiro) {
                        $this->html .= "</ul>\n";
                        $abre3 = false;
                    }
                }

                if ($abre2 == true && $abre3 == false) {
                    if (isset($menuArr[$kMenu + 1]['a']['segundo']) && $menuArr[$kMenu + 1]['a']['segundo'] != $segundo) {
                        $this->html .= "</li></ul>\n";
                        $abre2 = false;
                    } else {
                        if (isset($menuArr[$kMenu + 1]['a']['primeiro']) && $menuArr[$kMenu + 1]['a']['primeiro'] == $primeiro && $menuArr[$kMenu + 1]['a']['segundo'] == $segundo && $menuArr[$kMenu + 1]['a']['terceiro'] != $terceiro) {
                            $this->html .= "</li>\n";
                        }
                    }
                }

                if ($abre1 == false && $abre2 == false && $abre3 == false) {
                    if ($menuArr[$kMenu + 1]['a']['primeiro'] != $primeiro) {
                        $this->html .= "</li>\n";
                    }
                }

                if ($abre1 == true && $abre2 == false && $abre3 == false) {
                    if (isset($menuArr[$kMenu + 1]['a']['primeiro']) && $menuArr[$kMenu + 1]['a']['primeiro'] != $primeiro) {
                        $this->html .= "</li></ul></li>\n";
                        $abre1 = false;
                    }
                    if (isset($menuArr[$kMenu + 1]['a']['primeiro']) && $menuArr[$kMenu + 1]['a']['primeiro'] == $primeiro && $menuArr[$kMenu + 1]['a']['segundo'] != $segundo && $menuArr[$kMenu + 1]['a']['terceiro'] == 0) {
                        //                        if($menuArr[$kMenu + 1]['a']['primeiro'] == $primeiro && $menuArr[$kMenu + 1]['a']['segundo'] == 0){
                        //                            $this->html .= "</li><li>TESTE</li></ul></li>\n";
                        //                        }else{
                        $this->html .= "</li>\n";
                        //                        }
                    }
                }
            }
            $this->html .= "</ul>\n";


            //            $filtro2 = ($_SESSION["cod_conta"] != "") ? "AND codigo_referencia = {$_SESSION['cod_conta']}" : "";
            //  $chave  = 'sistemas_perfil';
            //        $sql = ''
            //                . ' SELECT '
            //                . ' valor'
            //                . ' FROM configuracao'
            //                . ' WHERE '
            //                . ' 1=1'      
            //                . ' '.$filtro2
            //                . ' AND chave = \''.$chave.'\''
            //                . ' AND status = \'1\'';
            //        krumo($sql);
            //        $saida_tbconf = $MenuModel->query($sql);
            //INICIO - CRIAR DROPBOX DO PERFIL NO MENU
            //            if (isset($saida_tbconf) && $saida_tbconf == $_SESSION['cod_usuario']) {
            //                $obj_usuario = new Usuario($this->db, $this->erro);
            //                $rs_perfil = $obj_usuario->buscarPerfil();
            //                $this->html .= "<div>";
            //                $html = $this->html;
            //                $funcao_string = "(function(e, obj){ jQuery.ajax({ type: 'POST', dataType: 'JSON', url: '../../ajax/sistema/alterar_perfil.php', data: { cod_perfil: obj.value, acao: true }, success: function(res){ alert('Perfil alterado.'); location.reload(); } });})(event, this)";
            //                $this->select_banco($rs_perfil, "cod_perfil_adm", "cod_perfil_adm", 1, $_SESSION['cod_perfil'], "", "onchange=\"" . $funcao_string . "\"", "Nenhum resultado foi encontrado", "nome_grupo");
            //                $html2 = $this->html;
            //                $this->html = '';
            ////                //ATENÇÃO: Se alterar o VALUE desse botão, precisa alterar a verificação na página ajax: ../../ajax/sistema/alterar_perfil.php
            //                $this->html .= $html . $html2 . '<input type="button" name="bkp_session" value="Perfil original" style="background-color:#0268C0; color: #ffffff" onclick="' . $funcao_string . '">';
            //                $this->html .= "</div>\n";
            //                $this->html .= "<script>jQuery('#cod_perfil_adm').addClass('chosen-select').chosen({width: '200px'}); </script>";
            //            }
            //FIM - CRIAR DROPBOX DO PERFIL NO MENU
        }

        return $this->html;
    }

    function trata_maiuscula($campo)
    {
        $campo = strtoupper($campo);
        $estranha = "áéíóúàèìòùâêîôûäëïöüãõç";
        $correta = "ÁÉÍÓÚÀÈÌÒÙÂÊÎÔÛÄËÏÖÜÃÕÇ";
        $retorno = "";

        $contagem = strlen($estranha);

        for ($i = 0; $i < $contagem; $i++) {

            $retorno = str_replace($estranha[$i], $correta[$i], $campo);
            $campo = $retorno;
        }
        return $campo;
    }

    /**
     * 
     * @param array $opcoes 
     * @param type $comhora
     * @return type
     * @example $opções pode usar (dia)(mes)(ano)(hora)(minuto)(segundo) ou +1 ou -1 podendo adicionar ou remover
     *          Exemplo  : $this->Funcoes->dataAtual(); data atual
     *          Exemplo 1: $this->Funcoes->dataAtual(array(), false); data atual sem hora
     *          Exemplo 2: $this->Funcoes->dataAtual(array(), true); data atual com  hora
     *          Exemplo 3: $this->Funcoes->dataAtual(array('dias'=>+20), true);
     *          Exemplo 4: $this->Funcoes->dataAtual(array('mes'=>-1), true);
     *          Exemplo 5: $this->Funcoes->dataAtual(array('dia'=>+15,'mes'=>+3), true);
     */
    public function dataAtual(array $opcoes = array(), $formato = 'Y-m-d', $com_hora = false)
    {
        $dia = date('d');
        if (isset($opcoes['dia']) && $opcoes['dia'] !== NULL && is_numeric($opcoes['dia'])) {
            $dia = date('d') + $opcoes['dia'];
        }

        $mes = date('m');
        if (isset($opcoes['mes']) && $opcoes['mes'] !== NULL && is_numeric($opcoes['mes'])) {
            $mes = date('m') + $opcoes['mes'];
        }

        $ano = date('Y');
        if (isset($opcoes['ano']) && $opcoes['ano'] !== NULL && is_numeric($opcoes['ano'])) {
            $ano = date('Y') + $opcoes['ano'];
        }

        $hora = date('H');
        if (isset($opcoes['hora']) && $opcoes['hora'] !== NULL && is_numeric($opcoes['hora'])) {
            $hora = date('H') + $opcoes['hora'];
        }

        $minuto = date('i');
        if (isset($opcoes['minuto']) && $opcoes['minuto'] !== NULL && is_numeric($opcoes['minuto'])) {
            $minuto = date('i') + $opcoes['minuto'];
        }

        $segundo = date('s');
        if (isset($opcoes['segundo']) && $opcoes['segundo'] !== NULL && is_numeric($opcoes['segundo'])) {
            $segundo = date('s') + $opcoes['segundo'];
        }

        $mkDate = mktime($hora, $minuto, $segundo, $mes, $dia, $ano);
        return ($com_hora == true) ? date($formato . ' H:i:s', $mkDate) : date($formato, $mkDate);
    }

    public function semRegistro1($rows, $colspan = 15, $search = '', $action = '')
    {
        $html = '';
        if (count($rows) == 0) {
            $html =  '<tr style=""><td colspan="' . $colspan . '" style="text-align:center; padding: 30px 0;">';
            $html .= 'Nenhum Registro Encontrado!!!';
            if (isset($search) && is_array($search) && count($search) > 0):
                $html .= '<br><br>' . $this->Html->image("sys/filter-clear.png", array("alt" => "Limpar Filtros", "title" => "Limpar Filtros", "url" => array('action' => 'admin_busca_unset', 'ALL', $action))) . ' ';
                $html .= $this->Html->link('Limpar Filtros', array('controller' => $this->params['controller'], 'action' => 'busca_unset', 'ALL', $action));
            endif;

            $html .= '</td></tr>';
        }
        return $html;
    }

    public function semRegistro($rows, $colspan = 15, $search = '', $action = '')
    {
        $html = '';
        if (count($rows) == 0) {
            $html =  '<tr style=""><td colspan="' . $colspan . '" style="text-align:center; padding: 30px 0;">';
            $html .= 'Nenhum Registro Encontrado!!!';

            if (isset($search) && is_array($search) && count($search) > 0):
                $class_msg = '';
                $ajaxmsg = '';

                if ($search['search'] == 'atribuidos'):
                    $class_msg = 'ajaxMsg';
                    $ajaxmsg = 'Se limpar o filtro, removerá a visualização dos agendamentos atribuídos.';
                endif;


                $html .= '<br><br>' . $this->Html->image("sys/filter-clear.png", array("alt" => "Limpar Filtros", "title" => "Limpar Filtros", "class" => $class_msg, "ajaxmsg" => $ajaxmsg, "url" => array('action' => 'admin_busca_unset', 'ALL', $action))) . ' ';
                $html .= $this->Html->link('Limpar Filtros', array('controller' => $this->params['controller'], 'action' => 'busca_unset', 'ALL', $action), array('class' => $class_msg, 'ajaxmsg' => $ajaxmsg));
            endif;



            $html .= '</td></tr>';
        }
        return $html;
    }


    public function dateToView($data, $comhora = false)
    {
        $dataNew = '';
        if ($data != '') {
            $dataHoraArr = explode(' ', $data);
            $dataArr = explode('-', $dataHoraArr[0]);

            if ($dataArr[2] != '00') {
                $dataNew = $dataArr[2] . '/' . $dataArr[1] . '/' . $dataArr[0];

                if ($comhora != false) {
                    $dataNew .= ' às ' . $dataHoraArr[1];
                }
            }
        }
        return $dataNew;
    }

    public function msg($msg, $type = null, $icon = false)
    {
        if (!$msg): return '';
        endif;

        if ($type == null) {
            if (strpos($msg, 'sucesso')) {
                $type = 1;
                $icon = true;
            } elseif (strpos($msg, 'Erro: ')) {
                $type = 3;
                $icon = true;
            } else {
                $type = 2;
                $icon = true;
            }
        }

        $float = 'style="float:left; margin-top: 3px;"';
        $typeMsg = '';
        if ($type == 1): $typeMsg = 'alert-success';
            $iconType = ' <i class="fa-fw fa fa-check" ' . $float . '></i>';
        elseif ($type == 2): $typeMsg = 'alert-warning';
            $iconType = ' <i class="fa-fw fa fa-warning" ' . $float . '></i>';
        elseif ($type == 3): $typeMsg = 'alert-danger';
            $iconType = ' <i class="fa-fw fa fa-times" ' . $float . '></i>';
        elseif ($type == 4): $typeMsg = 'alert-dismissable';
            $iconType = ' ';
        elseif ($type == 5): $typeMsg = 'alert-info';
            $iconType = ' <i class="fa-fw fa fa-info" ' . $float . '></i>';
        elseif ($type == 6): $typeMsg = 'alert-link';
            $iconType = ' <i class="fa fa-align-justify" ' . $float . '></i>';
        endif;

        $icon = ($icon === false) ? '' : $iconType;

        $html = '<div class="alert adjusted ' . $typeMsg . ' fade in">
             <button class="close" data-dismiss="alert">
                     ×
             </button>
            ' . $icon . ' ' . $msg . '</div>';
        return $html;
    }

    /**
     * DETERMINA O PERCENTUAL FEITO DE UMA QUANTIDADE TOTAL
     * @param type $total
     * @param type $feitos
     * @return type
     * @reference https://www.scriptbrasil.com.br/forum/topic/174898-c%C3%A1lculo-de-porcentagem/
     */
    public function calcula_percentual($total, $feitos)
    {
        $calculo = (($feitos / $total) * 100);

        return $calculo;
    }


    public function formata_cnpj($cnpj = '')
    {
        $str = '';
        if ($cnpj != '') {
            $str = preg_replace("/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})/", "$1.$2.$3/$4-$5", $cnpj);
        }

        return $str;
    }

    public function formata_cpf($cpf = '')
    {
        $str = '';
        if ($cpf != '') {
            $str = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})/", "$1.$2.$3-$4", $cpf);
        }

        return $str;
    }

    public function color($id = 0, $somente_cor = false)
    {
        if ($id == 0) {
            $texto = ($somente_cor) ?  '&nbsp;' : 'Não';
            $cor = 'red';
        } elseif ($id == 1) {
            $texto = ($somente_cor) ? '&nbsp;' : 'Sim';
            $cor = 'green';
        } else {
            $texto = '&nbsp;';
            $cor = 'blue';
        }
        return '<div style="background-color:' . $cor . '; color:white; padding: 1px 2px; text-align:center;" >' . $texto . '</div>';
    }


    public function preenche($string, $lado, $quantidade, $carac = "0")
    {
        if ($lado == "E") {
            return str_pad($string, $quantidade, $carac, STR_PAD_LEFT);
        } elseif ($lado == "D") {
            return str_pad($string, $quantidade, $carac, STR_PAD_RIGHT);
        }
    }

    function ultimo_dia_mes($mes, $ano)
    {
        $date = date("d", mktime(0, 0, 0, ($mes + 1), 0, $ano));
        return $date;
    }
    function converter_mes($datahora)
    {
        $arrMonthsOfYear = array('Vazio', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $mes = intval(date('m', strtotime($datahora)));
        $mes_extenso = $arrMonthsOfYear[$mes];
        return $mes_extenso;
    }
    function converter_mes_direto($mes)
    {
        $arrMonthsOfYear = array('Vazio', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $mes = intval($mes);
        $mes_extenso = $arrMonthsOfYear[$mes];
        return $mes_extenso;
    }

    /*FUNÇÃO DATA RECADOS */
    function data_hora_recado($datahora)
    {
        list($data, $horas) = explode(' ', $datahora);
        $data = explode('-', $data);
        $horas = explode(':', $horas);

        $datamkt = mktime($horas[0], $horas[1], $horas[2], $data[1], $data[2], $data[0]);
        $atualmkt = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));

        $dif = $atualmkt - $datamkt;
        $dif = round(($dif / 60));
        if ($dif == 0):
            $t = ' segundos atrás';
        elseif ($dif < 59):
            $t = $dif . ' minutos atrás';
        else:
            $qtde_dias = round(($dif / (60 * 24)));


            if (($data[2] != date('d'))):
                #$qtde_dias = date('d') - $data[2];
                if ($qtde_dias == 1):
                    $t = 'Ontem ás ' . $horas[0] . ':' . $horas[1];
                elseif ($qtde_dias > 1 && $qtde_dias <= 30):
                    $t = $qtde_dias . ' dias atrás';
                else:
                    $ano = ($data[0] != date('Y')) ? ' de ' . $data[0] : '';
                    $escrito = $data[2] . ' de ' . $this->converter_mes_direto($data[1]) . $ano;
                    $t = $escrito . ' ás ' . $horas[0] . ':' . $horas[1];
                endif;

            elseif ($data[1] != date('m')):
                $ano = ($data[0] != date('Y')) ? ' de ' . $data[0] : '';
                $escrito = $data[2] . ' de ' . $this->converter_mes_direto($data[1]) . $ano;
                $t = $escrito . ' ás ' . $horas[0] . ':' . $horas[1];
            else:
                $t = 'Hoje ás ' . $horas[0] . ':' . $horas[1];
            endif;
        endif;
        return $t;
    }


    public function masc_tel($TEL)
    {
        $tam = strlen(preg_replace("/[^0-9]/", "", $TEL));
        if ($tam == 13) { // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS e 9 dígitos
            return "+" . substr($TEL, 0, $tam - 11) . " (" . substr($TEL, $tam - 11, 2) . ") " . substr($TEL, $tam - 9, 5) . "-" . substr($TEL, -4);
        }
        if ($tam == 12) { // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS
            return "+" . substr($TEL, 0, $tam - 10) . " (" . substr($TEL, $tam - 10, 2) . ") " . substr($TEL, $tam - 8, 4) . "-" . substr($TEL, -4);
        }
        if ($tam == 11) { // COM CÓDIGO DE ÁREA NACIONAL e 9 dígitos
            return "(" . substr($TEL, 0, 2) . ") " . substr($TEL, 2, 5) . "-" . substr($TEL, 7, 11);
        }
        if ($tam == 10) { // COM CÓDIGO DE ÁREA NACIONAL
            return "(" . substr($TEL, 0, 2) . ") &nbsp;" . substr($TEL, 2, 4) . "-" . substr($TEL, 6, 10);
        }
        if ($tam <= 9) { // SEM CÓDIGO DE ÁREA
            return substr($TEL, 0, $tam - 4) . "-" . substr($TEL, -4);
        }
    }


    public function dias_entre_datas($data1, $data2)
    {
        $dt1  = explode('-', $data1);
        $dt2  = explode('-', $data2);

        $datamkt = mktime(0, 0, 1, $dt1[1], $dt1[2], $dt1[0]);
        $atualmkt = mktime(0, 0, 1, $dt2[1], $dt2[2], $dt2[0]);
        #$atualmkt = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

        $dif = $atualmkt - $datamkt;
        $dif = round(($dif / 60));

        $qtde_dias = round(($dif / (60 * 24)));


        return $qtde_dias;
    }

    public function isBetween($x, $lower, $upper)
    {
        return ($lower <= $x && $x <= $upper);
    }




    /**
     * Data provável do parto
     */
    public function data_provavel_parto($data_menstruacao)
    {
        $data = $this->calculaData('Y-m-d H:i:s', $data_menstruacao, ' + 280 DAY');
        $data = explode(' ', $data);
        return $data[0];
    }

    /**
     * Dias prováveis para o parto
     */
    public function dias_para_parto($data_menstruacao)
    {
        $data_atual = date('Y-m-d');
        $data_fim = $this->data_provavel_parto($data_menstruacao);


        #= + 280 dias para o parto
        $qtd_dias = $this->dias_entre_datas($data_atual, $data_fim);
        if ($qtd_dias < 0) {
            $return = 'Data da Última Menstruação: ' . $this->dateToView($data_menstruacao) . '<br>';
            $return .= 'Parto já realizado!';
        } else {
            $return = 'Data da Última Menstruação: ' . $this->dateToView($data_menstruacao) . '<br>';
            $return .= 'Data Provável: ' . $this->dateToView($data_fim) . '<br>';
            $return .= 'Semanas restantes: ' . round($qtd_dias / 7) . '<br>';
            $return .= 'Dias restantes: ' . $qtd_dias;
        }

        return $return;
    }

    /**
     * Periodo em semnas da gestação
     * $data_inicio = menstrucao
     * $data_fim
     */
    public function semanas_entre_datas($data_inicio, $data_fim)
    {
        $qtd_dias = $this->dias_entre_datas($data_inicio, $data_fim);
        $semanas = round($qtd_dias / 7);
        return $semanas;
    }

    /**
     * Grupo trimestral VH gestante
     */
    public function grupo_trimestral($data_menstruacao, $data_fim = '')
    {
        if ($data_fim == '') {
            $data_fim = date('Y-m-d');
        }
        $semanas = $this->semanas_entre_datas($data_menstruacao, $data_fim);

        if ($this->isBetween($semanas, 0, 12))
            return 'Primeiro Trimestre';
        elseif ($this->isBetween($semanas, 13, 30))
            return 'Segundo Trimestre';
        elseif ($this->isBetween($semanas, 31, 42))
            return 'Terceiro Trimestre';
        else
            return 'Pós Parto';
    }


    /**
     *
     * Calculando datas no futuro ou passado a partir de datas definidas
     * exemplo:
     * Calcular a data daqui 3 dias
     *
     * $format = "d/m/Y H:i:s";
     * $date = "2009-05-20 06:34:00";
     * $calculo = "+ 3 days";
     * calculaData( $format, $date, $calculo );
     *
     * @param String $format
     * @param String $date
     * @param String $calculo
     * @return string
     */
    public function calculaData($format = 'Y-m-d H:i:s', $date, $calculo)
    {
        $timestamp = strtotime($date . $calculo);
        return date($format, $timestamp);
    }

    /**
     * Método de exibição de status
     * @param type $id
     * @param type $color
     * @param type $text Este pode ser colocado: Exemplo: '' -> não aparecerá nada, array(''=>'TESTE') -> irá parecer teste.
     * @return string
     */
    public function status_agenda($id = null, $color = false, $text = false)
    {

        $inicial = array('' => 'Selecione...');
        if ($text !== false) {
            if ($text === '') {
                $inicial = array();
            } else {
                $inicial = $text;
            }
        }

        if ($id === TRUE) {
            $id = 1;
        }
        if ($id === FALSE) {
            $id = 0;
        }

        $status = array_merge($inicial, array(
            0 => 'Aguardando',
            1 => 'Concluído',
        ));
        if ($this->Session->read('Auth.Usuario.id') == 1) {
            $status[2] = 'Excluído';
            $status[3] = 'Administrativo';
        }

        if (!is_null($id)) {
            if (isset($status[$id])) {
                if ($color == true) {
                    if ($id == 0) {
                        return '<span style="background-color:yellow; padding: 2px 4px">' . $status[$id] . '</span>';
                    } elseif ($id == 2) {
                        return '<span style="background-color:red; color:white; padding: 2px 4px">' . $status[$id] . '</span>';
                    } elseif ($id == 3) {
                        return '<span style="background-color:blue; color:white; padding: 2px 4px">' . $status[$id] . '</span>';
                    } else {
                        return '<span style="background-color:green; color:white; padding: 2px 4px">' . $status[$id] . '</span>';
                    }
                } else {
                    return $status[$id];
                }
            }
            return '';
        } else {
            return $status;
        }
    }
}
