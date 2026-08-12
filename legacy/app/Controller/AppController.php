<?php
ini_set('max_execution_time', 10000); //5 minutos
ini_set('memory_limit', '-1');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
//    set_time_limit(0);
//    ini_set('memory_limit','2048M');


/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Define os componentes disponíveis por padrão
     *
     * @var array
     * @access public
     */
    var $components = array('Session', 'Cookie', 'Auth', 'Funcoes');
    //  var $components = array('Session', 'Cookie', 'Auth','RequestHandler');

    /**
     * Define os helpers disponíveis por padrão
     *
     * @var array
     * @access public
     */
    var $helpers = array(
        'Html',
        'Form',
        'Session',
        //    'Ajax',
        //    'Javascript',
        'Paginator',
        'Maps',
        'Link'
    );


    #Status de verificação <> de adm 1
    var $status_default = '.status < 2';
    #usuario root
    var $uRoot = 1;
    #condition de empresas
    var $conditionsDefault = '';
    var $grupo_empresarial_id = '';
    var $cliente_id = '';
    var $empresa_id = '';
    var $perfil_id = '';
    var $usuario_id = '';

    var $selectGrupoEmpresarial = '';
    var $selectCliente = '';




    #PERFIS 
    var $perfil_root            = 1;  #TUDO (TODAS CONTAS)
    var $perfil_administrador   = 2;  #REALIZA CADASTRO E ATUALIZAÇÕES (No próprio GE)
    var $perfil_ti              = 3;  #REALIZA CADASTRO E ATUALIZAÇÕES (No próprio GE)
    var $perfil_operador        = 4;  #APENAS VIZUALIZÇÃO DE CADASTRO E DASHBOARD (somente cliente selecionado) (NO GE) ??
    var $perfil_auditoria       = 5;  #APENAS VIZUALIZÇÃO DE RELATÓRIOS (todos clientes) (NO GE) ??
    var $perfil_backoffice      = 6;  #APENAS VIZUALIZÇÃO DE RELATÓRIOS (todos clientes) (NO GE) ??
    var $perfil_cliente         = 7;  #GERENCIAL DA EMPRESA DELE (USUÁRIOS ENTRE OUTROS) (NO GE) ??

    #PERFIS GRUPO
    var $perfil_adm             = array(1, 2);     #SOMENTE ADMINISTRATIVO
    var $perfil_adm_ti          = array(1, 2, 3);   #SOMENTE ADMINISTRATIVO
    var $perfil_geral           = array(1, 2, 3, 4, 5, 6, 7, 8, 9); #TODOS
    var $perfil_geral_cliente   = array(1, 2, 3, 7); #TODOS
    var $hash_token = '@Samed2018$';


    var $link_dev = 'http://localhost/samed/';
    var $link_pro = 'https://samed.app.br/';


    //    var $moduloPaiArr = array( 'pergunta'=>'resposta','mh_critico_historico'=>'mh_critico') ;
    var $moduloPaiArr = array('pergunta' => 'resposta', 'mh_critico_historico' => 'mh_critico');







    /**
     * Before Filter
     *
     * Função de callback executada antes que qualquer outra
     *
     * @access public
     * @link http://book.cakephp.org/pt/view/984/Callbacks
     */
    function _setErrorLayout()
    {
        if ($this->name == 'CakeError') {
            $this->layout = 'error';
        }
    }

    function isHttps()
    {
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return true;
        }
        if (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
            return true;
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            return true;
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            return true;
        }
        return false;
    }


    public function user_hash_token()
    {
        return md5($this->Session->read('Auth.Usuario.id') . $this->hash_token);
    }


    #CONTROLE DE LOGIN

    function beforeFilter()
    {
        $this->_setErrorLayout();


        $this->Auth->authenticate = array('Form');

        Security::setHash('md5'); // Método de Hash da senha

        $this->Auth->userModel = "usuario"; // Nome do modelo para os usuários
        $this->Auth->fields = array(
            'username' => 'usuario', // Troque o segundo parametro se desejar
            'password' => 'senha', // Troque o segundo parametro se desejar
        );
        //        $this->Auth->userScope = array(
        //            'Usuario.status' => '1' // Permite apenas usuários ativos
        //        );
        $this->Auth->authorize = 'Controller'; // Utiliza a função isAuthorize para autorizar os usuários
        $this->Auth->autoRedirect = true; // Redireciona o usuário para a requisição anterior que foi negada após o login
        $this->Auth->loginAction = array(
            'controller' => 'usuario',
            'action' => 'login',
            'admin' => false
        );
        $this->Auth->loginRedirect = array(
            'controller' => 'home',
            'action' => 'index',
            'admin' => true
        );

        $this->Auth->logoutRedirect = array(
            'controller' => 'usuario',
            'action' => 'logout',
            'admin' => false
        );


        $msgP1 = '<div class="alert adjusted alert-danger fade in">
                <button class="close" data-dismiss="alert">
                        ×
                </button>
                <i class="fa-fw fa-lg fa fa-exclamation"></i> ';
        $msgP2 = '</div>';
        $loginError = $msgP1 . '<strong>Aviso:</strong> Usuário ou senha inválidos.' . $msgP2;
        $authError = $msgP1 . '<strong>Aviso:</strong> Você não tem permissão para acessar.' . $msgP2;

        //        $this->Auth->loginError = __('<strong>Aviso:</strong> Usuário ou senha inválidos.', true);
        //        $this->Auth->authError = __('<strong>Aviso:</strong> Você não tem permissão para acessar.', true);
        $this->Auth->loginError = __($loginError, true);
        $this->Auth->authError = __($authError, true);

        if (!isset($this->params['admin'])) {
            $this->Auth->allow();
        }

        //        krumo($this->Auth);

        // #VERIFICA HTTPS
        // $localhost = explode( ':', $_SERVER['HTTP_HOST'] );
        // if($localhost[0] != 'localhost'){
        //     if(!isset($_SERVER['HTTPS'])){
        //         $this->link_pro = 'http://samed.app.br/';
        //     }
        // }

        $action     = $this->params['action'];
        $controller = $this->params['controller'];

        #BEGIN - ADMIN
        if (!is_null($this->params['admin'])) {
            $this->layout = 'admin';
            #$perfil_id = $this->Session->read("Auth.Usuario.perfil_id");
        } else {
            $this->layout = 'front';
        }


        $AVISO_DINAMICO = 'class="invalid alert alert-danger alert-block " style="display: none; width:100%; float:left; font-style:normal; font-size:12px; margin-bottom: 0 !important; padding: 4px 10px !important;"';
        $this->set('AVISO_DINAMICO', $AVISO_DINAMICO);

        $this->set('cel_hidden', 'class="hidden-xs hidden-sm"');
        $this->set('uRoot', $this->uRoot);



        $grupo_empresarial_id = NULL;
        $cliente_id = NULL;
        $empresa_id = NULL;
        $perfil_id = NULL;
        $usuario_id = NULL;
        if ($this->Session->check('Auth')) {
            $perfil_id = $this->Session->read('Auth.Usuario.perfil_id'); #BUSCAR PERFIL DO USUÁRIO
            $usuario_id = $this->Session->read('Auth.Usuario.id'); #BUSCAR ID DO USUÁRIO
        }
        #FICA FORA PARA ATUALIZAR SEMPRE
        $grupo_empresarial_id = $this->Session->read('Auth.Usuario.grupo_empresarial_id'); #BUSCAR GRUPO DO USUÁRIO
        $cliente_id = $this->Session->read('Auth.Usuario.cliente_id'); #BUSCAR CLIENTE DO USUÁRIO



        $this->grupo_empresarial_id = $grupo_empresarial_id;
        $this->cliente_id = $cliente_id;
        $this->perfil_id = $perfil_id;
        $this->usuario_id = $usuario_id;




        $conditionsDefault = '.grupo_empresarial_id = ' . $grupo_empresarial_id;
        $this->conditionsDefault = $conditionsDefault;

        #CORREÇÃO 
        if (isset($this->params['admin']) && ($this->Session->read('Auth') == NULL || $grupo_empresarial_id == '' || $perfil_id == '')):
            $this->Session->setFlash(__('Sua sessão foi encerrada.', true));
            $this->Session->destroy();
            $this->redirect($this->Auth->logoutRedirect);
        endif;
        //        if($this->Session->read('Auth.Usuario.empresa_id') == NULL && $action != 'logout'){
        //            $this->redirect(array('controller'=>'usuario', 'action'=>'logout','admin'=>false));
        //            $this->Auth->allow();
        //          }
        //        krumo($this->params);
        //        exit;


        #BEGIN - ADMIN
        if (!is_null($this->params['admin'])) {
            $this->layout = 'admin';


            //            if (!is_array($this->Session->read('Auth.permissoes')) || count($this->Session->read('Auth.permissoes')) == 0) {#DESCOMENTAR
            //            krumo(1);
            //            exit;

            if ($this->Session->read('Auth.Usuario.id') == $this->uRoot) {
                $moduloNome = 'Modulo';
                $this->loadModel('Modulo');
                $moduloArr = $this->Modulo->find('all', array('conditions' => array('Modulo.status' => array(1, 3), 'Modulo.id >' => 0), 'recursive' => -1, 'order' => 'order')); #STATUS PARA ADM user 1
            } else {
                $moduloNome = 'PerfilModulo';
                $this->loadModel('PerfilModulo');
                $moduloArr = $this->PerfilModulo->find('all', array('conditions' => array('PerfilModulo.perfil_id' => $this->Session->read('Auth.Usuario.perfil_id'), 'Modulo.id >' => 0, 'Modulo.status' => 1), 'recursive' => 0, 'order' => 'order'));
            }


            $permissoes = array();
            // debug($moduloArr);exit;
            if (is_array($moduloArr) && count($moduloArr) > 0) {
                foreach ($moduloArr as $vUM):
                    $kUM = $vUM['Modulo']['controller'];
                    $permissoes[$kUM]['permissao']  = ($moduloNome == 'Modulo') ? 3 : $vUM[$moduloNome]['permissao'];
                    $permissoes[$kUM]['id']         = $vUM['Modulo']['id'];
                    $permissoes[$kUM]['modulo_id']  = $vUM['Modulo']['modulo_id'];
                    $permissoes[$kUM]['nome']       = $vUM['Modulo']['nome'];
                    $permissoes[$kUM]['controller'] = $vUM['Modulo']['controller'];
                    $permissoes[$kUM]['icon']        = $vUM['Modulo']['icon'];
                    $permissoes[$kUM]['menu']       = $vUM['Modulo']['menu'];
                endforeach;
            }
            $this->Session->write('Auth.permissoes', $permissoes);
            //            }#DESCOMENTAR



            #VERIFICA PERMISSAO
            $frase = 'Seu Usuário Não tem Permissão de Acesso';
            $permissoes = $this->Session->read('Auth.permissoes');
            $controller = $this->params['controller'];
            $control_allowed = array('Home', 'Kcfinder');
            $control_action_allowed = array('Grupo_empresarial/selecione', 'Usuario/atualiza_session_cliente', 'Blob/download');
            $control_verify = ucfirst($controller);
            $control_action_verify = ucfirst($this->params['controller'] . '/' . str_replace('admin_', '', $this->params['action']));

            #ALLOWED ACTION
            $permission_allow = false;
            if (in_array($control_verify, $control_allowed) || in_array($control_action_verify, $control_action_allowed)) {
                $permission_allow = true;
            }

            //    krumo($permissoes);
            //    krumo($permissoes[$control_verify]);
            //    krumo($permissoes[$control_action_verify]);
            //    krumo($permission_allow);
            //    exit;


            // krumo($permissoes);
            // exit;

            if ($permission_allow == false) {
                if (!isset($permissoes[$control_verify]) && !isset($permissoes[$control_action_verify])) {
                    $this->Session->setFlash($frase . ' !!!');
                    return $this->redirect($this->Auth->redirectUrl());
                } elseif (isset($permissoes[$control_action_verify]['permissao'])) {
                    if ($permissoes[$control_action_verify]['permissao'] == 0) {
                        $this->Session->setFlash($frase . " para a área ({$permissoes[$control_action_verify]['nome']})");
                        return $this->redirect($this->Auth->redirectUrl());
                    } elseif (in_array($permissoes[$control_action_verify]['permissao'], array(1, 2, 3))) {
                        $permissao = $permissoes[$control_action_verify]['permissao'];
                        $this->set(compact('permissao'));

                        #BLOQUEIO POR ACTION (MÉTODO)
                        if ($permissao == 1 && in_array($this->params['action'], array('admin_add', 'admin_delete'))) {
                            $this->Session->setFlash($frase . " para Gerenciar da área ({$permissoes[$control_action_verify]['nome']}) ");
                            return $this->redirect($this->Auth->redirectUrl());
                        } elseif ($permissao == 2 && in_array($this->params['action'], array('admin_delete'))) {
                            $this->Session->setFlash($frase . " para Exclusões da área ({$permissoes[$control_action_verify]['nome']}) ");
                            return $this->redirect($this->Auth->redirectUrl());
                        }
                    }
                } elseif (isset($permissoes[$control_verify]['permissao'])) {
                    if ($permissoes[$control_verify]['permissao'] == 0) {
                        $this->Session->setFlash($frase . " para a área ({$permissoes[$control_verify]['nome']})");
                        return $this->redirect($this->Auth->redirectUrl());
                    } elseif (in_array($permissoes[$control_verify]['permissao'], array(1, 2, 3))) {
                        $permissao = $permissoes[$control_verify]['permissao'];
                        $this->set(compact('permissao'));

                        #BLOQUEIO POR ACTION (MÉTODO)
                        if ($permissao == 1 && in_array($this->params['action'], array('admin_add', 'admin_delete'))) {
                            $this->Session->setFlash($frase . " para Gerenciar da área ({$permissoes[$control_verify]['nome']}) ");
                            return $this->redirect($this->Auth->redirectUrl());
                        } elseif ($permissao == 2 && in_array($this->params['action'], array('admin_delete'))) {
                            $this->Session->setFlash($frase . " para Exclusões da área ({$permissoes[$control_verify]['nome']}) ");
                            return $this->redirect($this->Auth->redirectUrl());
                        }
                    }
                }
            }




            //            
            //            if($action_allow == false){
            //                if (!in_array($control_verify, $control_allowed)) {
            //                    if (!isset($permissoes[$control_verify]) && !isset($permissoes[$control_action_verify]) ) {
            //                        $this->Session->setFlash($frase . ' !!!');
            //                        return $this->redirect($this->Auth->redirectUrl());
            //                    } elseif (isset($permissoes[$control_verify]['permissao'])) {
            //                        $this->Session->setFlash($frase . " para a área ({$permissoes[$control_verify]['nome']})");
            //                        return $this->redirect($this->Auth->redirectUrl());
            //                    } elseif ($permissoes[$control_action_verify]['permissao'] == 0) {
            //                        $this->Session->setFlash($frase . " para a área ({$permissoes[$control_action_verify]['nome']})");
            //                        return $this->redirect($this->Auth->redirectUrl());
            //                    } elseif (in_array($permissoes[$control_verify]['permissao'], array(1, 2, 3))) {
            //                        $permissao = $permissoes[$control_verify]['permissao'];
            //                        $this->set(compact('permissao'));
            //
            //                        
            //                        #BLOQUEIO POR ACTION (MÉTODO)
            //                        if ($permissao == 1 && in_array($this->params['action'], array('admin_add', 'admin_delete'))) {
            //                            $this->Session->setFlash($frase . " para Gerenciar da área ({$permissoes[$control_verify]['nome']}) ");
            //                            return $this->redirect($this->Auth->redirectUrl());
            //                        } elseif ($permissao == 2 && in_array($this->params['action'], array('admin_delete'))) {
            //                            $this->Session->setFlash($frase . " para Exclusões da área ({$permissoes[$control_verify]['nome']}) ");
            //                            return $this->redirect($this->Auth->redirectUrl());
            //                        }
            //                    } elseif (in_array($permissoes[$control_action_verify]['permissao'], array(1, 2, 3))) {
            //                        $permissao = $permissoes[$control_action_verify]['permissao'];
            //                        $this->set(compact('permissao'));
            //
            //                        
            //                        #BLOQUEIO POR ACTION (MÉTODO)
            //                        if ($permissao == 1 && in_array($this->params['action'], array('admin_add', 'admin_delete'))) {
            //                            $this->Session->setFlash($frase . " para Gerenciar da área ({$permissoes[$control_action_verify]['nome']}) ");
            //                            return $this->redirect($this->Auth->redirectUrl());
            //                        } elseif ($permissao == 2 && in_array($this->params['action'], array('admin_delete'))) {
            //                            $this->Session->setFlash($frase . " para Exclusões da área ({$permissoes[$control_action_verify]['nome']}) ");
            //                            return $this->redirect($this->Auth->redirectUrl());
            //                        }
            //                    }
            //                }
            //            }
            //

            //            krumo('usuariomodulo mudará o nome para usuário perfil');
            //            krumo('todo o controle de acesso será via perfil');
            //



            #BEGIN - FRONT
        } else {


            //             echo 'FRONT`';
            // exit;
            //            $this->layout = 'front';
            //            $action     = $this->params['action'];
            //            $controller = $this->params['controller'];
            ////            $conditionsDefault = 'status = 1';
            //
            //            $menuSite = '';
            //            if(!in_array($action, array('login','logout'))):
            //                $this->loadModel('Pagina');
            //                $options = array('conditions'=> array('menu'=>'1'), 'fields'=>array('titulo','controller','menu'), 'order'=> 'id' , 'recursive'=> -1);
            //                $menuArr  = $this->Pagina->find('all', $options);
            //                $menuSite = $this->Funcoes->retiraSubArray($menuArr,'Pagina');
            //            endif;
            //            $this->set('menuSite',$menuSite);
            //
            //
            //            /*BEGIN - VERIFICÇÃO PARA PARCEIRO NOVO */
            ////            $control_allowed_front = array('home','hotel','busca_hoteis','login','logout');
            ////            $block = true;
            ////                if(in_array($this->params['action'], $control_allowed_front)):
            ////                    $block = false;
            ////                else:
            ////                    foreach($menuSite as $menu_test):
            ////                        if($menu_test['controller'] == $this->params['action']):
            ////                            $block = false;
            ////                        endif;
            ////                    endforeach;
            ////                endif;
            ////
            ////            if($block == true):
            ////                $this->redirect('/');
            ////            endif;
            //            /*END - VERIFICÇÃO PARA PARCEIRO NOVO */
            //
            //
            //
            //
            //
            // echo $controller;
            //       krumo($this->params);
            //        exit;
            //
            //
            //
            //            /*BEGIN - BUSCA SEO*/
            //                $this->loadModel('Seo');
            //
            //                $this->seo_title = '';
            //                $this->seo_description = '';
            //                $this->seo_keywords = '';
            //                $this->seo_url = '';
            //
            //
            //                #ADD COMPARATIVO AQUI
            //
            //                if(in_array($this->params['action'], array('destino','hotel','resort','hotel_boutique','pacote'))):
            //                    $tableArr = array(
            //                          'hotel' => 'acomodacao_hotel',
            //                          'resort' => 'acomodacao_hotel',
            //                          'hotel_boutique' => 'acomodacao_hotel',
            //                          'pacote'           => 'pacote',
            //                          'destino'          => 'destino'
            //                        );
            //                    $controller_seo = $tableArr[$this->params['action']];
            //                    $model_seo = $this->Funcoes->controller_to_model($controller_seo);
            //                    $this->loadModel($model_seo);
            //                    $this->$model_seo->recursive = -1;
            //                    if(isset($this->params['pass'][0]) && $this->params['pass'][0] != ''):
            //                        $rs_pagina = $this->$model_seo->find('first', array('conditions'=>array('slug'=>$this->params['pass'][0]), 'fields'=>'id'));
            //                        if(count($rs_pagina) > 0):
            //                            $id_ref_seo = $rs_pagina[$model_seo]['id'];
            //                            if($id_ref_seo != ''):
            //                                $this->loadModel('Seo');
            //                                $this->Seo->recursive = -1;
            //                                $seoArr = $this->Seo->find('first', array('conditions'=>array('table_referencia'=>$controller_seo,'id_referencia'=>$id_ref_seo)));
            //                                if(count($seoArr) > 0):
            //                                    $seoArr = $seoArr['Seo'];
            //                                    $this->seo_title = $seoArr['title'];
            //                                    $this->seo_description = $seoArr['description'];
            //                                    $this->seo_keywords = $seoArr['keywords'];
            //                                    $this->seo_url = $seoArr['url'];
            //
            //                                    $this->Session->write('SEO',$seoArr);
            //                                endif;
            //                            endif;
            //                        endif;
            //                    endif;
            //                endif;







        }




        /**
         * USADO EM (NOTICIAS / DESTINOS)
         */
        $controller = $this->params['controller'];
        $action = $this->params['action'];

        #VALIDAÇÃO DOS CAMPOS DO USUÁRIO
        if (in_array($controller, array('usuario', 'empresa', 'beneficiario', 'beneficio_previdenciario', 'atendimento')) && $action == 'admin_add'):
            $config_ckeditor = "
            filebrowserImageBrowseUrl   : '" . Router::url('/js/admin/plugin/ckeditor/plugins/kcfinder/browse.php?type=images', true) . "',
            height: '280px', 
            startupFocus : false,
            toolbar: [{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                      { name: 'paragraph', items : [ 'NumberedList','BulletedList'] }]            ";
            $this->set(compact('config_ckeditor'));

            /**
             * [
              { name: 'document', items : [ 'Preview'] },
              { name: 'tools', items : [ 'Maximize', 'ShowBlocks', ] },  
              { name: 'clipboard', items : ['Undo','Redo','-' ,'Cut','Copy','Paste','PasteText','PasteFromWord' ] },
              { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-' ] },
              '/',
              { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
              { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
              '/',
              { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote', '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
              { name: 'colors', items : [ 'TextColor','BGColor' ] },
              { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
              { name: 'insert', items : [ 'Image','Table','HorizontalRule','SpecialChar','PageBreak' ] }
            ]
             */
        endif;



        /*PROFILES - VALIDATION */
        $allow_jquery = array('admin_atualiza_session_cliente', 'admin_atualiza_session_menu', 'admin_busca_subfaturas', 'atualiza_chave_fatura_sinistro', 'call_dwFaturaMes', 'call_dwPopulacao', 'call_dwSinistroEvento', 'call_dwSinistroPaciente', 'call_dwSinistroPrestadorEvento', 'call_gerarChave', 'call_procedimentos', 'call_procedimentosSemClassific', 'call_rotina_agendamento_pendente');

        if (isset($this->params['admin']) && $this->params['admin'] == TRUE):
            //            if($controller == 'aluno' && in_array($action, array('admin_index','admin_atendimento','admin_financeiro'))  && !in_array($perfil_id, array_merge($this->perfil_acesso_sacademico,array($this->perfil_fac_diretor, $this->perfil_fac_assistente)))):
            //                $this->Session->setFlash('Seu perfil Não tem permissão de acesso na área de tentativa de acesso!');
            //                $this->redirect(array('controller'=>'home','action'=>'index'));
            //            elseif(in_array($controller, array('usuario', 'parametro', 'empresa_curso', 'vestibular', 'curso', 'empresa' )) && !in_array($action,$allow_jquery) && !in_array($perfil_id, array($this->perfil_root,$this->perfil_diretor,$this->perfil_gerente))):
            //                $this->Session->setFlash('Seu perfil Não tem permissão de acesso na área de tentativa de acesso!');
            //                $this->redirect(array('controller'=>'home','action'=>'index'));
            //            endif;

            $this->busca_grupo_empresarial();
            $this->busca_cliente();
        //            krumo(1);
        //            exit;
        endif;



        // $moduloPaiArr = array();
        // $moduloPaiModel = '';
        // if(isset($this->moduloPaiArr[$this->params['controller']])){
        //     $moduloPaiArr = $this->moduloPaiArr;
        //     $moduloPaiModel = $this->moduloPaiArr[$this->params['controller']];
        // }

        // $this->set('moduloPaiArr', $moduloPaiArr);
        // $this->set('moduloPaiModel', $moduloPaiModel);

        $this->set('controller', $controller);
        $this->set('action', $action);
        $this->set('grupo_empresarial_id', $this->grupo_empresarial_id);
        $this->set('cliente_id', $this->cliente_id);
        $this->set('empresa_id', $this->empresa_id);
        $this->set('perfil_id', $this->perfil_id);
        $this->set('usuario_id', $this->usuario_id); #NÃO PASSO PARA A VIEW
        #$this->set('conditionsDefault', $this->conditionsDefault);#NÃO PASSO PARA A VIEW

        $this->set('perfil_root', $this->perfil_root);
        $this->set('perfil_administrador', $this->perfil_administrador);
        $this->set('perfil_cliente', $this->perfil_cliente);
        $this->set('perfil_consultor', $this->perfil_consultor);
        $this->set('perfil_geral', $this->perfil_geral);
        $this->set('perfil_adm', $this->perfil_adm);

        $this->set('link_dev', $this->link_dev);
        $this->set('link_pro', $this->link_pro);
        $this->set('moduloPaiArr', $this->moduloPaiArr);
        $this->set('obrigatorio', '<span class="campo_obrigatorio">*</span>');







        $localhost = explode(':', $_SERVER['HTTP_HOST']);
        $link_geral = $this->link_pro;
        if (in_array($localhost[0], array('localhost'))) {
            $link_geral = $this->link_dev;
        }
        $this->set('link_geral', $link_geral);

        #DESENVOLVERRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR
        #VALIDA ACESSO
        #verificar se tem permissão de acesso para o cliente ou grupo_empresarial selecionada de acordo com o perfil
        #VALIDAR PARA ADMIN E CLIENTE SOMENTE A CONTA ATUAL CASO SEJA DIFERENTE DE OLD NÃO PERMITIR.

        if (preg_match('/_add|_view/', $action)) {
            #krumo($controller);
            #krumo($action);
            #krumo($this->params['pass'][0]);
            #$controller #CONVERT CONTROLLER TO CLASS
            #VERIFY CONTENT 
            #CONVERSÃO PARA ARQUIVOS COM UNDERLINE
            //            $control_verify = $this->params['controller'];
            //            $control_verify = str_replace('_', ' ', $control_verify);
            //            $control_verify = ucwords($control_verify);
            //            $control_verify = str_replace(' ', '', $control_verify);

        }

        //        krumo($controller);
        //        krumo($action);
        //        exit;


        #LOGO DO GRUPO EMPRESARIAL
        $logoGE = '';
        $corGE = '';
        if (!empty($grupo_empresarial_id) && empty($cliente_id) &&  !in_array($action, array('admin_selecione', 'logout')) && !in_array($action, $allow_jquery)) {
            if ($perfil_id == $this->perfil_root && in_array($controller, array('grupo_empresarial', 'cliente', 'usuario'))) {
                #PASSA PORQUE PRECISA CRIAR NOVO
            } else {
                $this->Session->setFlash('Selecione um Cliente para dar continuidade!');
                $this->redirect(array('controller' => 'grupo_empresarial', 'action' => 'selecione', 'admin' => true));
            }
        } else {
            $this->loadModel('GrupoEmpresarial');

            $rowImgLogo = $this->GrupoEmpresarial->find('first', ['conditions' => ['id' => $grupo_empresarial_id], 'fields' => 'img_logo,cor', 'recursive' => -1]);

            if (isset($rowImgLogo['GrupoEmpresarial']['img_logo']) && $rowImgLogo['GrupoEmpresarial']['img_logo'] != '') {
                if (file_exists('img/uploads/grupo_empresarial/' . $rowImgLogo['GrupoEmpresarial']['img_logo'])) {
                    $logoGE = Router::url('/img/uploads/grupo_empresarial/' . $rowImgLogo['GrupoEmpresarial']['img_logo'], true);
                }
            }
            if (isset($rowImgLogo['GrupoEmpresarial']['cor']) && $rowImgLogo['GrupoEmpresarial']['cor'] != '') {
                $corGE = $rowImgLogo['GrupoEmpresarial']['cor'];
            }
        }

        $this->set('logoGE', $logoGE);
        $this->set('corGE', $corGE);



        /*
        if($controller == 'grupo_empresarial' && $action == 'admin_selecione'){
            #PASSA
        }else{
            if(empty($grupo_empresarial_id) && $perfil_id == $this->perfil_root){
                $this->Session->setFlash('Aviso: Selecione um gurpo empresarial!');
                $this->redirect(array('controller'=>'grupo_empresarial','action'=>'selecione','admin'=>true));
            }else if(empty($grupo_empresarial_id) && $perfil_id != $this->perfil_root){
                $this->Session->setFlash('Aviso: Você não tem permissão de acesso, por favor, entrar em contato com o administrador!');
                $this->redirect(array('controller'=>'grupo_empresarial','action'=>'selecione','admin'=>true));
            }else if(!empty($grupo_empresarial_id) && empty($cliente_id) &&  !in_array($action, array('admin_selecione','logout')) && !in_array($action,$allow_jquery)){
                if($perfil_id == $this->perfil_root && in_array($controller,array('grupo_empresarial','cliente'))){
                    #PASSA PORQUE PRECISA CRIAR NOVO
                }else{
                    if(!in_array($action, array('admin_selecione','logout')) && !in_array($action,$allow_jquery)){
                        $this->Session->setFlash('Selecione um Cliente para dar continuidade!');
                        $this->redirect(array('controller'=>'grupo_empresarial','action'=>'selecione','admin'=>true));    
                    }
                    
                }
            }
        }
       


        if(!empty($grupo_empresarial_id) && empty($cliente_id) &&  !in_array($action, array('admin_selecione','logout')) && !in_array($action,$allow_jquery)){
            $this->redirect(array('controller'=>'grupo_empresarial','action'=>'selecione','admin'=>true));
        }
        */


        $this->_setErrorLayout();
    }

    // beforefilter

    /**
     * Is Authorized
     *
     * Faz a autorização do usuário
     *
     * @return boolean
     * @access public
     */
    function isAuthorized()
    {
        // Pode ser mais complexo antes de liberar o acesso
        $this->loadModel('Usuario');
        $user_default = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => 1), 'recursive' => '-1'));
        if (count($user_default) != 1) { #erro referência
            $msg = base64_decode('U2lzdGVtYSBjb20gZXJybyBkZSBSZWZlcsOqbmNpYS4gPGJyIC8+IEZBVk9SIENPTlRBVEFSIE8gQURNSU5JU1RSQURPUiBETyBTSVNURU1B');
            $this->Session->destroy();
            $this->Session->setFlash($msg);
            $this->redirect($this->Auth->logout());
        }

        if (in_array($this->Auth->user('status'), array('0', '2'))) {
            if ($this->Auth->user('id') == 1) {
                return true;
            } #autorize default
            $this->Session->destroy();
            $this->Session->setFlash(__('Você Não Tem Autorização de Acesso, contate o Administrador do Sistema! ', true));
            $this->redirect($this->Auth->logout());
        }

        return true;
    }



    /**
     * CONTROLLERS DEFAULTS
     */

    /**
     * CLICK PARA LIMPAR A BUSCA DENTRO DAS LISTAGENS
     * @param type $search
     * @param type $name_search
     * @return boolean
     * @example http://localhost/litoralverde/be/admin/pacote/busca_unset/ALL
                public function admin_busca_unset($search) {
                    $this->autoRender = false;
                    parent::all_busca_unset($search,$this->name_search);
                    $this->redirect(array('action' => 'index'));
                }
                public function admin_busca_unset($id_classPai = null, $search = null) {
                    $this->autoRender = false;
                    parent::all_busca_unset($search,$this->name_search);
                    $this->redirect(array('action' => 'index',$id_classPai));
                }
     */
    public function all_busca_unset($search, $name_search)
    {
        if ($search !== null && !empty($name_search)) {
            if ($search != "ALL"):
                $searchArr = $this->Session->read($name_search);
                unset($searchArr[$search]);
                $this->Session->delete($name_search);
                $this->Session->write($name_search, $searchArr);
            else:
                $this->Session->delete($name_search);
            endif;
        }
        return true;
    }






    public function busca_unidades($empresa_id)
    {
        $this->loadModel('Empresa');
        $ids = $this->Empresa->find('list', array('conditions' => array('empresa_id' => $empresa_id), 'fields' => 'id'));
        return $ids;
    }


    public function envio_email($toEmail, $subject, $msg)
    {
        $Email = new CakeEmail();
        $Email->config('default');
        $Email->emailFormat('html');
        $Email->template('cadastro_novo')->viewVars(array('msg' => $msg));
        $Email->to($toEmail);
        $Email->subject($subject);
        $return = $Email->send('default');
        return true;
    }


    public function alteracao_DB()
    {
        #ALTER TABLE `importacao` CHANGE COLUMN `data_cadastro` `data_cadastro` DATETIME NOT NULL AFTER `data_competencia`;
    }


    public function busca_grupo_empresarial()
    {

        if (!$this->Session->check('selectGrupoEmpresarial')) {
            $selectGrupoEmpresarial = array();

            if ($this->perfil_id == 1 || ($this->perfil_id == 2 && $this->grupo_empresarial_id == 1)) {
                $this->loadModel('GrupoEmpresarial');
                $selectGrupoEmpresarial = $this->GrupoEmpresarial->find('list', array('conditions' => array('status' => '1'), 'fields' => 'id,nome'));
            }
            #$selectGrupoEmpresarial = $this->Funcoes->select_merge($selectGrupoEmpresarial,'GrupoEmpresarial...');
            $this->Session->write('selectGrupoEmpresarial', $selectGrupoEmpresarial);
        }


        $this->selectGrupoEmpresarial = $this->Session->read('selectGrupoEmpresarial');
        $this->set('selectGrupoEmpresarial', $this->selectGrupoEmpresarial);
    }

    public function busca_cliente()
    {
        #if(!$this->Session->check('selectCliente')){
        $this->loadModel('Cliente');
        $selectCliente = array();

        #NOVA CHAMADA
        $this->loadModel('UsuarioCliente');
        $this->loadModel('Cliente');


        if (in_array($this->perfil_id, $this->perfil_adm)) {
            $cond = ['Cliente.status < 2'];
            if ($this->perfil_id == 1) {
                $cond = [];
            }

            $UCArr = $this->Cliente->find('all', array('conditions' => $cond, 'fields' => 'id,nome,status, grupo_empresarial_id', 'recursive' => 2, 'order' => ['GrupoEmpresarial.nome' => 'ASC', 'Cliente.nome' => 'ASC'])); #
            $selectClienteNew = [];
            $selectClienteGENew = [];
            if (count($UCArr) > 0) {
                foreach ($UCArr as $uc) {
                    $selectClienteGENew[$uc['Cliente']['id']] = $uc['GrupoEmpresarial']['id'];

                    $selectClienteNew[$uc['GrupoEmpresarial']['id']][] = [
                        'ge_nome' => $uc['GrupoEmpresarial']['nome'],
                        'cliente_id' => $uc['Cliente']['id'],
                        'cliente_status' => $uc['Cliente']['status'],
                        'cliente_nome' => $uc['Cliente']['nome']
                    ];
                }
            }
        } else {
            $UCArr = $this->UsuarioCliente->find('all', array('conditions' => ['UsuarioCliente.usuario_id' => $this->usuario_id, 'Cliente.status < 2'], 'recursive' => 2));
            $selectClienteNew = [];
            $selectClienteGENew = [];
            if (count($UCArr) > 0) {
                foreach ($UCArr as $uc) {
                    $selectClienteGENew[$uc['Cliente']['id']] = $uc['Cliente']['GrupoEmpresarial']['id'];
                    $selectClienteNew[$uc['Cliente']['GrupoEmpresarial']['id']][] = [
                        'ge_nome' => $uc['Cliente']['GrupoEmpresarial']['nome'],
                        'cliente_id' => $uc['Cliente']['id'],
                        'cliente_status' => $uc['Cliente']['status'],
                        'cliente_nome' => $uc['Cliente']['nome']
                    ];
                }
            }
        }





        // krumo($UCArr);
        // exit;

        #SOMENTE PARA O GRUPO EMPRESARIAL
        if (in_array($this->params['controller'], ['grupo_empresarial', 'home', 'importacao', 'importacao_nova'])) {

            if (!in_array($this->perfil_id, $this->perfil_adm)) {
                $this->loadModel('UsuarioCliente');
                $UCArr = $this->UsuarioCliente->find('list', array('conditions' => array('usuario_id' => $this->usuario_id), 'fields' => 'id,cliente_id'));
                if (count($UCArr) > 0) {
                    $UCConditions = implode(',', $UCArr);
                    $selectCliente = $this->Cliente->find('list', array('conditions' => array("status" => 1, "grupo_empresarial_id" => $this->grupo_empresarial_id, " id IN ({$UCConditions})"), 'fields' => 'id,nome'));
                }
                $selectCliente = $this->Funcoes->select_merge($selectCliente, 'Selecione');
            } else {
                $selectCliente = $this->Cliente->find('list', array('conditions' => array("status" => 1, "grupo_empresarial_id" => $this->grupo_empresarial_id), 'fields' => 'id,nome'));
                $textSelect = (count($selectCliente) >  0) ? 'Selecione...' : 'Este grupo empresarial não possui clientes ativos!';
                if ($this->params['action'] != 'admin_add') {
                    $selectCliente = $this->Funcoes->select_merge($selectCliente, $textSelect);
                }
            }
        }

        /*if(in_array($this->perfil_id, $this->perfil_adm)){
                $selectCliente = $this->Cliente->find('list',array('conditions'=>array("status"=>1,"grupo_empresarial_id"=>$this->grupo_empresarial_id),'fields'=>'id,nome'));
                $textSelect = (count($selectCliente) >  0) ? 'Selecione...' : 'Este grupo mpresarial não possui clientes ativos!' ;
                if(!$this->params['action'] == 'admin_add'){
                    $selectCliente = $this->Funcoes->select_merge($selectCliente,$textSelect);
                }
            }else{
                $this->loadModel('UsuarioCliente');
                $UCArr = $this->UsuarioCliente->find('list',array('conditions'=>array('usuario_id'=>$this->usuario_id),'fields'=>'id,cliente_id'));
                if(count($UCArr)> 0){
                    $UCConditions = implode(',',$UCArr);
                    $selectCliente = $this->Cliente->find('list',array('conditions'=>array("status"=>1,"grupo_empresarial_id"=>$this->grupo_empresarial_id,"cliente_id IN {$UCConditions}"),'fields'=>'id,nome'));
                }
                $selectCliente = $this->Funcoes->select_merge($selectCliente,'Selecione');
            }*/


        // krumo($selectCliente);
        // exit;

        $this->Session->write('selectCliente', $selectCliente);
        $this->Session->write('selectClienteNew', $selectClienteNew);
        $this->Session->write('selectClienteGENew', $selectClienteGENew);
        #}


        $this->selectCliente = $this->Session->read('selectCliente');
        $this->selectClienteNew = $this->Session->read('selectClienteNew');
        $this->selectClienteGENew = $this->Session->read('selectClienteGENew');

        $this->set('selectCliente', $this->selectCliente);
        $this->set('selectClienteNew', $this->selectClienteNew);
        $this->set('selectClienteGENew', $this->selectClienteGENew);
    }



    public function curlCall($url, $data)
    {
        ini_set('MAX_EXECUTION_TIME', 300);
        $ch = curl_init($url);
        $payload = json_encode($data);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }




    /**
     * CREATE BLOB
     *  - 0 -> create
     *  - 1 -> update
     *  - 2 -> delete
     * 
     * 
     * Attr = ['action'=>0 , table'=>'nome_da_tabela']
     * Attr = ['action'=>1 , 'id'=>1 ]
     * Attr = ['action'=>2 , 'id'=>1 ]
     * 
     * blob
     */
    public function blob_action($FILE = null, $attr = [])
    {
        $this->loadModel('Blob');
        $dbo_blob = $this->Blob->getDatasource();

        if (!in_array($attr['action'], array(0, 1, 2))) {
            return false;
        }
        $file_exist = false;
        if (isset($FILE['tmp_name']) && $FILE['tmp_name'] != '' && file_exists($FILE['tmp_name']) && in_array($attr['action'], array(0, 1))) {

            $fileContent = file_get_contents($FILE['tmp_name']);
            $fileNameArr = explode('.', $FILE['name']);
            $extensao = $fileNameArr[count($fileNameArr) - 1];
            unset($fileNameArr[count($fileNameArr) - 1]);
            $file_name = implode('.', $fileNameArr);
            $file_name = $this->Funcoes->normalizaeUrl($file_name) . '_' . time() . '.' . $extensao;
            $file_exist = true;
        }

        // krumo($fileContent);
        // krumo($FILE);
        // krumo($attr);
        // krumo($file_exist);
        // exit;

        if ($attr['action'] == 0) {
            if ($file_exist == false) {
                return false;
            }

            #AJUSTA MYSQL PARA ACEITAR BLOB MAIORES
            $this->mysql_blob_allowed($dbo_blob);


            $dataSaveBlob = [];
            $dataSaveBlob['Blob']['id'] = '';
            $dataSaveBlob['Blob']['table'] = $attr['table'];
            $dataSaveBlob['Blob']['nome'] = $file_name;
            $dataSaveBlob['Blob']['tipo'] = $FILE['type'];
            $dataSaveBlob['Blob']['tamanho'] = $FILE['size'];
            $dataSaveBlob['Blob']['extensao'] = $extensao;
            $dataSaveBlob['Blob']['blob'] =  $fileContent;
            $dataSaveBlob['Blob']['data_cadastro'] =  date('Y-m-d H:i:s');
            $dataSaveBlob['Blob']['usuario_id'] =  $this->Session->read('Auth.Usuario.id');

            $this->Blob->create();
            if (!$this->Blob->save($dataSaveBlob)) {
                return false;
            }

            return $this->Blob->id;
        } else if ($attr['action'] == 1) {
            if ($file_exist == false) {
                return false;
            }
            $dataSaveBlob['Blob']['id'] = $attr['id'];
            $dataSaveBlob['Blob']['table'] = $attr['table'];
            $dataSaveBlob['Blob']['nome'] = $file_name;
            $dataSaveBlob['Blob']['tipo'] = $FILE['type'];
            $dataSaveBlob['Blob']['tamanho'] = $FILE['size'];
            $dataSaveBlob['Blob']['extensao'] = $extensao;
            $dataSaveBlob['Blob']['blob'] =  $fileContent;
            $dataSaveBlob['Blob']['data_atualizacao'] =  date('Y-m-d H:i:s');
            $dataSaveBlob['Blob']['usuario_id_atualizacao'] =  $this->Session->read('Auth.Usuario.id');

            if (!$this->Blob->save($dataSaveBlob)) {
                return false;
            }

            return $this->Blob->id;
        } else if ($attr['action'] == 2) {
            if (!isset($attr['id']) || $attr['id'] == '') {
                return false;
            }
            $dataSaveBlob['Blob']['id'] = $attr['id'];
            $dataSaveBlob['Blob']['status'] = 2;
            $dataSaveBlob['Blob']['data_atualizacao'] =  date('Y-m-d H:i:s');
            $dataSaveBlob['Blob']['usuario_id_atualizacao'] =  $this->Session->read('Auth.Usuario.id');

            if (!$this->Blob->save($dataSaveBlob)) {
                return false;
            }
        }

        return false;
    }


    public function mysql_blob_allowed($dbo)
    {
        #AJUSTA MYSQL PARA ACEITAR BLOB MAIORES
        $result = $dbo->fetchAll("SHOW VARIABLES LIKE 'max_allowed_packet'");
        debug($result);
        $dbo->execute("SET GLOBAL max_allowed_packet = 67108864");
        return true;
    }

    /**
     * DOWNLOAD BLOB
     */
    public function blob_download($id_md5)
    {
        $this->redirect(array('controller' => 'blob', 'action' => 'download', $id_md5, 'admin' => true));
    }
}
    
    

    /*
     * INSERT into parametro (`nome`, `valor`, `ordenacao`,  `tipo`, `usuario_id`,  `status`) values 
        ('Perfil populacional', 1 , 1, 'relatorio_paginas', 1, 1),
        ('Distribuição plano por faixa etária', 2 , 2, 'relatorio_paginas', 1, 1),
        ('Sinistralidade do periodo', 3, 3,  'relatorio_paginas', 1, 1),
        ('Distribuição por subfatura', 4, 4,  'relatorio_paginas', 1, 1),
        ('Distribuição por plano', 5, 5,  'relatorio_paginas', 1, 1),
        ('Sinistro por gênero', 6, 6,  'relatorio_paginas', 1, 1),
        ('Sinistro por elebigilidade', 7, 7,  'relatorio_paginas', 1, 1),
        ('Sinistro por faixa etária', 8, 8,  'relatorio_paginas', 1, 1),
        ('Sinistro por tipo de atendimento', 9, 9,  'relatorio_paginas', 1, 1),
        ('Sinistro por tipo de evento', 10,10, 'relatorio_paginas', 1, 1),
        ('Sinistro de PS semanal', 11,11, 'relatorio_paginas', 1, 1),
        ('Tabela de índices', 12,12, 'relatorio_paginas', 1, 1),
        ('Sinistro maiores utilizadores (sem nome)', 13,13, 'relatorio_paginas', 1, 1),
        ('Sinistro maiores utilizadores', 14,14, 'relatorio_paginas', 1, 1),
        ('Sinistro maiores prestadores - consultas e ps', 15,15, 'relatorio_paginas', 1, 1),
        ('Sinistro maiores prestadores - exames', 16,16, 'relatorio_paginas', 1, 1),
        ('Sinistro maiores prestadores - internação', 17,17, 'relatorio_paginas', 1, 1),
        ('Sinistro maiores prestadores - terapias', 18,18, 'relatorio_paginas', 1, 1),
        ('Hiperconsultadores (sem nome)', 19,19, 'relatorio_paginas', 1, 1),
        ('Hiperconsultadores', 20,20, 'relatorio_paginas', 1, 1),
        ('Perfil de custo', 21,21, 'relatorio_paginas', 1, 1);


        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Acidente de trabalho', '8', '1', 'Absentismo_Tipo', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Acompanhamento familiar', '11', '2', 'Absentismo_Tipo', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Acompanhamento multi-profissional', '6', '3', 'Absentismo_Tipo', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Consulta Médica', '4', '4', 'Absentismo_Tipo', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Consulta Odontológica', '7', '5', 'Absentismo_Tipo', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Doação de sangue', '5', '6', 'Absentismo_Tipo', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Licença maternidade', '17', '7', 'Absentismo_Tipo', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Licença paternidade', '9', '8', 'Absentismo_Tipo', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Motivo não informado', '12', '9', 'Absentismo_Tipo', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`) VALUES ('Óbito parente 1° grau', '10', '10', 'Absentismo_Tipo', '1');

        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Administrativo', '5439', '1', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Atendimento', '59', '2', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Cadastro', '5440', '3', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Comercial', '57', '4', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Desenvolvimento TI', '52', '5', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Diretoria', '8491', '6', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Estudos', '5438', '7', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Faturamento', '8492', '8', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Financeiro', '58', '9', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Gestão de Risco', '5441', '10', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Gestão de Saúde', '8493', '11', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Implantação', '5435', '12', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Infra', '5442', '13', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Logística', '3441', '14', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Marketing', '55', '15', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Planejamento e Gestão', '8494', '16', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Pós-Venda', '56', '17', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Pós-Venda / Relacionamento', '8495', '18', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Processamento', '8496', '19', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Qualidade de Vida', '60', '20', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Recursos Humanos', '51', '21', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Relacioamento', '5436', '22', 'Dpto_Colaborador', '1', '1');
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('Vida e Preferência', '5437', '23', 'Dpto_Colaborador', '1', '1');


        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`, `status`) VALUES ('SUS', 'SUS', '1', 'Emissor', '1', '1');
        UPDATE `samed`.`parametro` SET `ordenacao`='2' WHERE `id`='206';
        INSERT INTO `samed`.`parametro` (`nome`, `valor`, `ordenacao`, `tipo`, `usuario_id`) VALUES ('Operadora', 'Operadora', '2', 'Emissor', '1');


        
     */
