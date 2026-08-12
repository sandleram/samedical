<?php
App::uses('AppController', 'Controller');
//App::uses('CakeEmail', 'Network/Email');
//App::uses('HttpSocket', 'Network/Http');
//App::import('Controller', 'QvResposta');
//$QvResposta = new QvRespostaController;
//$erro = $QvResposta->busca_resposta('test2e');

/**
 *
 * @author Sândler A. Matos <sandleram@gmail.com>
 * @link /ws/* Acesso Webservice
 */
class WsController extends AppController {
    
    public $components = array('Paginator', 'Funcoes', 'Json', 'Mapeamento');
    public $cod_error = array(  '-10' => 'Exception (erro de sistema)',
                                '0' => 'Autenticado / Salvo / processo realizado com sucesso',
                                '10' => 'CPF encontrado',
                                '11' => 'CPF invalido',
                                '20' => 'CPF não encontrado',
                                '30' => 'Cadastrar senha',
                                '40' => 'Senha Cadastrada',
                                '50' => 'Login ou senha invalidos',
                                '60' => 'Chave de acesso valida',
                                '70' => 'Chave de acesso invalida',
                                '80' => 'Nao ha mapeamento / prochaska',
                                '90' => 'Falta campo obrigatorio',
                                '100' => 'Usuario inativo ou inexistente',
                                '110' => 'Senha alterada',
                                '120' => 'Opcao invalida ou inexistente',
                                '130' => 'Responde mapeamento',
                                '140' => 'E-mail invalido / nao possui e-mail cadastrado',
                                '150' => 'Nao ha missoes disponiveis / aceitas',
                                '160' => 'Missao ja foi pontuada hoje',
                                '180' => 'Usuario inativo ou bloqueada (inativo permanetntemente, nao podera acessar com chave de acesso)', // EMPRESA 
                                '181' => 'Acesso permitido para usuarios com idade a partir de 18 anos',
                                '190' => 'Vacina ja esta programada, verifique seus registros'
                        );
    private $depArr = array();
    private $cod_dado_pessoal = '';
    private $cod_empresa_beneficiario = '';
    private $cod_preenchido = '';
    private $cod_empresa = '';
    private $sexo = '';
    private $cod_formulario = '';
    private $nome_completo = '';
    private $nome = '';
    private $email = '';
    private $percentual_geral = 0;

    public function beforeFilter() {
        parent::beforeFilter();

    }

    public function index() {
        $this->autoRender = false;
        exit();
    }

    public function pr($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public function pre($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit;
    }
    

    public function call_integration_robo_proativa(){
        #DESENVOLVER EM REST A API
    }
    
    
    
    /**
     * BUSCA DO JAVA PARA MONTAR OS GRUPOS DOS CARDS
     */
    public function call_dw(){
        $this->autoRender = false;
        #$data = $this->params->data;
        #array_values
        $return = $this->Mapeamento->categoria_cronicos();
        
        header('Content-type: application/json');
        echo json_encode($return);
        exit;
    }
    
    
    
}
