<?php
App::uses('AppController', 'Controller');
#CALL localhost/samed/rest/bi_beneficiarios.json?token=__W8ufr49swg.@3UEefMZD3tKnxeoR
# authorization key: token value: __W8ufr49swg.@3UEefMZD3tKnxeoR

/**
 * https://stackoverflow.com/questions/21745320/cakephp-authentication-on-rest-api
 * https://copyprogramming.com/howto/restful-api-for-cakephp-2
 * https://book.cakephp.org/2/pt/development/rest.html
 * https://medium.com/@abeltolu/build-a-rest-api-using-cakephp-2-x-in-3-minutes-1abe842ad7a1
 * 
 * 
 */
/**
 *
 * @author Sândler A. Matos <sandleram@gmail.com>
 * @link /api/* Acesso API REST
 */
class RestController extends AppController
{
    public $components = array('Paginator', 'Funcoes', 'RequestHandler'); #'Json',,  'Mapeamento' , 'RequestHandler'
    protected $token_proativa = '__W8ufr49swg.@3UEefMZD3tKnxeoR';
        
    

    public function index(){
        $response = array('status' => 'failed', 'message' => 'Failed to process request');
        $this->set(array(
            'response' => $response,
            '_serialize' => array('response')
        ));
    }


    public function bi_proativa_beneficiario() {
        $fail = true;
        
        if(isset($this->params->query['token']) && $this->params->query['token'] == $this->token_proativa && isset($this->params->query['cliente_id']) && $this->params->query['cliente_id'] != ''){
            $this->loadModel('DwBeneficiario');
            $cliente_id = $this->params->query['cliente_id'];
            $fields = 'cliente_id,competencia, cod_subfatura, empresa_id,nome,cpf,estado_civil,data_nascimento,cidade,estado,sexo,chave_beneficiario,cod_matricula,dt_inclusao,dt_exclusao,dt_admissao,idade,faixa_etaria_ans_id,ds_grau_parentesco,nome_titular,cpf_titular,plano_id,ds_plano,elegibilidade,cod_operadora,operadora,ds_faixa_etaria_ans,ds_tipo_acomodacao,cod_u_seg,codigo_empresa,grupo_familiar_id,relacao_dep,relacao_dep_digito,lotacao_do_funcionario,cod_empresa,num_contrato,carteirinha,carteirinha_titular';
            $data = $this->DwBeneficiario->find('all', [  'conditions' => ['cliente_id' =>$cliente_id],
                                                                'fields' => $fields, 
                                                                #'limit' => 10, 
                                                                'order' => ['id'=>'DESC'], 
                                                                'recursive' => -1]);
            if(count($data)>0){
                $data = $this->Funcoes->retiraSubArray($data,'DwBeneficiario');

                $fail = false;
                $this->set(array(
                    'beneficiario' => $data,
                    '_serialize' => array('beneficiario')
                ));

                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                'log'=>'Rest API - Proativa Beneficiarios',
                                'mensagem'            =>  '',
                                'description'         =>  'bi_proativa_beneficiarios',
                                'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                                'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                'usuario_id'          =>  1
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
            }
        }

        if($fail){
            $response = array('status' => 'failed', 'message' => 'Failed to process request');
            $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            ));
            
            #BEGIN - CRIANDO LOG
            $this->loadModel('Log');
            $this->Log->create();
            $data_log = array('id' =>'',
                            'log'=>'Eroo - Rest API - Proativa Beneficiarios',
                            'mensagem'            =>  '',
                            'description'         =>  'bi_proativa_beneficiarios',
                            'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                            'data_cadastro'       =>  date('Y/m/d H:i:s'),
                            'usuario_id'          =>  1
                    );
            $this->Log->save($data_log);
            #END - CRIANDO LOG
        }

        // $this->set('Beneficiario', $Beneficiario);
        // $this->viewBuilder()->setOption('serialize', ['Beneficiario']);
    }

    #SOMENTE TECNISA
    public function bi_proativa_faturamento() {
        $fail = true;

        if(isset($this->params->query['token']) && $this->params->query['token'] == $this->token_proativa && isset($this->params->query['cliente_id']) && $this->params->query['cliente_id'] != ''){
            $this->loadModel('Faturamento');
            $cliente_id = $this->params->query['cliente_id'];
            $fields="cliente_id,competencia_referencia,competencia,codigo_operadora,operadora,valor_fatura,qtd_vidas,reembolso,rede,coparticipacao,revisao,recuperacao,valor_sinistro,percentual,total_sinistro,qtd_beneficiarios_atendidos";
            $data = $this->Faturamento->find('all', [  'conditions' => ['cliente_id'=>$cliente_id], 
                                                        'fields' => $fields, 
                                                        #'limit' => 10, 
                                                        'order' => ['id'=>'DESC'], 
                                                        'recursive' => -1]);
            
            if(count($data)>0){
                $data = $this->Funcoes->retiraSubArray($data,'Faturamento');
                $fail = false;
                $this->set(array(
                    'faturamento' => $data, 
                    '_serialize' => array('faturamento')
                ));

                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                'log'=>'Rest API - Proativa Faturamentos',
                                'mensagem'            =>  '',
                                'description'         =>  'bi_proativa_faturamentos',
                                'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                                'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                'usuario_id'          =>  1
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
            }
        }

        if($fail){
            $response = array('status' => 'failed', 'message' => 'Failed to process request');
            $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            ));

            #BEGIN - CRIANDO LOG
            $this->loadModel('Log');
            $this->Log->create();
            $data_log = array('id' =>'',
                            'log'=>'Erro - Rest API - Proativa Faturamentos',
                            'mensagem'            =>  '',
                            'description'         =>  'bi_proativa_faturamentos',
                            'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                            'data_cadastro'       =>  date('Y/m/d H:i:s'),
                            'usuario_id'          =>  1
                    );
            $this->Log->save($data_log);
            #END - CRIANDO LOG
        }

        // $this->set('Beneficiario', $Beneficiario);
        // $this->viewBuilder()->setOption('serialize', ['Beneficiario']);
    }

    public function bi_proativa_sinistro() {
        $fail = true;
        
        if(isset($this->params->query['token']) && $this->params->query['token'] == $this->token_proativa && isset($this->params->query['cliente_id']) && $this->params->query['cliente_id'] != ''){
            $this->loadModel('Sinistro');
            $cliente_id = $this->params->query['cliente_id'];
            $fields = 'cliente_id,empresa_id,subfatura_id,cod_subfatura,chave_beneficiario,matricula,cod_grupo_familiar,numero_carteira_titular,numero_carteira_titular_complemento,cpf_titular,nome_titular,beneficiario_id,numero_carteira,numero_carteira_complemento,cpf_beneficiario,nome_beneficiario,sexo,elegibilidade,data_nascimento,idade,tipo_reembolso,cod_prestador,nome_prestador,cidade_prestador,uf_prestador,cod_faixa_etaria_ans,plano_id,cod_plano,ds_plano,nro_conta_medica,procedimento_id,cod_procedimento,ds_procedimento,qtde_procedimento,tipo_servico,conta_medica,valor,valor_coparticipacao,senha,nr_autorizacao,ds_especialidade,data_evento,data_pagamento,cid,ds_cid,operadora,tipo_servico_operadora,tipo_internacao,tipo_entrada,campo_1_dado,campo_2_coluna,competencia_robo,ds_parentesco,num_contrato,nome_contrato,apolice,codigo_beneficio,data_final_servico,co_particiacao_perc,tipo_sinistro,atendimento_emergencia,tipo_paciente,origem_pagamento,tabela_grupo,codigo_grupo,descricao_grupo,codigo_subgrupo,descricao_subgrupo,data_alta,cnpj_prestador,nome_hash,nome_prestador_hash';
            $data = $this->Sinistro->find('all', [  'conditions' => ['cliente_id'=>$cliente_id], #,"data_pagamento > '2022-01-01'"
                                                                'fields' => $fields, 
                                                                #'limit' => 10, 
                                                                'order' => ['id'=>'DESC'], 
                                                                'recursive' => -1]);
            if(count($data)>0){
                $data = $this->Funcoes->retiraSubArray($data,'Sinistro');

                $fail = false;
                $this->set(array(
                    'sinistro' => $data,
                    '_serialize' => array('sinistro')
                ));

                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                'log'=>'Rest API - Proativa Sinistros',
                                'mensagem'            =>  '',
                                'description'         =>  'bi_proativa_sinistros',
                                'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                                'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                'usuario_id'          =>  1
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
            }
        }

        if($fail){
            $response = array('status' => 'failed', 'message' => 'Failed to process request');
            $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            ));

            #BEGIN - CRIANDO LOG
            $this->loadModel('Log');
            $this->Log->create();
            $data_log = array('id' =>'',
                            'log'=>'Erro - Rest API - Proativa Sinistros',
                            'mensagem'            =>  '',
                            'description'         =>  'bi_proativa_sinistros',
                            'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                            'data_cadastro'       =>  date('Y/m/d H:i:s'),
                            'usuario_id'          =>  1
                    );
            $this->Log->save($data_log);
            #END - CRIANDO LOG
        }

        // $this->set('Beneficiario', $Beneficiario);
        // $this->viewBuilder()->setOption('serialize', ['Beneficiario']);
    }

    public function bi_proativa_beneficio() {
        $fail = true;
        $name_G = 'Beneficio';
        $name_P = 'beneficio';
        
        if(isset($this->params->query['token']) && $this->params->query['token'] == $this->token_proativa){
            $this->loadModel($name_G);
            $fields = 'id,cliente_id,breakeven';
            $data = $this->$name_G->find('all', [  'conditions' => ['cliente_id in (select id from cliente where grupo_empresarial_id = 6)'],
                                                                'fields' => $fields, 
                                                                #'limit' => 10, 
                                                                #'order' => ['id'=>'DESC'], 
                                                                'recursive' => -1]);
            if(count($data)>0){
                $data = $this->Funcoes->retiraSubArray($data,$name_G);

                $fail = false;
                $this->set(array(
                    $name_P => $data,
                    '_serialize' => array($name_P)
                ));

                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                'log'=>'Rest API - Proativa '.$name_G,
                                'mensagem'            =>  '',
                                'description'         =>  'bi_proativa_'.$name_P,
                                'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                                'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                'usuario_id'          =>  1
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
            }
        }

        if($fail){
            $response = array('status' => 'failed', 'message' => 'Failed to process request');
            $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            ));

            #BEGIN - CRIANDO LOG
            $this->loadModel('Log');
            $this->Log->create();
            $data_log = array('id' =>'',
                            'log'=>'Erro - Rest API - Proativa '.$name_G,
                            'mensagem'            =>  '',
                            'description'         =>  'bi_proativa_'.$name_P,
                            'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                            'data_cadastro'       =>  date('Y/m/d H:i:s'),
                            'usuario_id'          =>  1
                    );
            $this->Log->save($data_log);
            #END - CRIANDO LOG
        }

        // $this->set('Beneficiario', $Beneficiario);
        // $this->viewBuilder()->setOption('serialize', ['Beneficiario']);
    }

    public function bi_proativa_cliente() {
        $fail = true;
        $name_G = 'Cliente';
        $name_P = 'cliente';
        
        if(isset($this->params->query['token']) && $this->params->query['token'] == $this->token_proativa){
            $this->loadModel($name_G);
            $fields = 'id,nome';#cod_cliente
            $data = $this->$name_G->find('all', [  'conditions' => ['grupo_empresarial_id'=>'6'],
                                                                'fields' => $fields, 
                                                                #'limit' => 10, 
                                                                #'order' => ['id'=>'DESC'], 
                                                                'recursive' => -1]);
            if(count($data)>0){
                $data = $this->Funcoes->retiraSubArray($data,$name_G);

                $fail = false;
                $this->set(array(
                    $name_P => $data,
                    '_serialize' => array($name_P)
                ));

                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                'log'=>'Rest API - Proativa '.$name_G,
                                'mensagem'            =>  '',
                                'description'         =>  'bi_proativa_'.$name_P,
                                'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                                'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                'usuario_id'          =>  1
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
            }
        }

        if($fail){
            $response = array('status' => 'failed', 'message' => 'Failed to process request');
            $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            ));

            #BEGIN - CRIANDO LOG
            $this->loadModel('Log');
            $this->Log->create();
            $data_log = array('id' =>'',
                            'log'=>'Erro - Rest API - Proativa '.$name_G,
                            'mensagem'            =>  '',
                            'description'         =>  'bi_proativa_'.$name_P,
                            'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                            'data_cadastro'       =>  date('Y/m/d H:i:s'),
                            'usuario_id'          =>  1
                    );
            $this->Log->save($data_log);
            #END - CRIANDO LOG
        }

        // $this->set('Beneficiario', $Beneficiario);
        // $this->viewBuilder()->setOption('serialize', ['Beneficiario']);
    }

    public function bi_proativa_grupo_estatistico() {
        $fail = true;
        $name_G = 'GrupoEstatistico';
        $name_P = 'grupo_estatistico';
        
        if(isset($this->params->query['token']) && $this->params->query['token'] == $this->token_proativa){
            $this->loadModel($name_G);
            $fields = '';
            $data = $this->$name_G->find('all', [  'conditions' => [],
                                                                'fields' => $fields, 
                                                                #'limit' => 10, 
                                                                #'order' => ['id'=>'DESC'], 
                                                                'recursive' => -1]);
            if(count($data)>0){
                $data = $this->Funcoes->retiraSubArray($data,$name_G);

                $fail = false;
                $this->set(array(
                    $name_P => $data,
                    '_serialize' => array($name_P)
                ));

                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                'log'=>'Rest API - Proativa '.$name_G,
                                'mensagem'            =>  '',
                                'description'         =>  'bi_proativa_'.$name_P,
                                'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                                'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                'usuario_id'          =>  1
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
            }
        }

        if($fail){
            $response = array('status' => 'failed', 'message' => 'Failed to process request');
            $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            ));

            #BEGIN - CRIANDO LOG
            $this->loadModel('Log');
            $this->Log->create();
            $data_log = array('id' =>'',
                            'log'=>'Erro - Rest API - Proativa '.$name_G,
                            'mensagem'            =>  '',
                            'description'         =>  'bi_proativa_'.$name_P,
                            'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                            'data_cadastro'       =>  date('Y/m/d H:i:s'),
                            'usuario_id'          =>  1
                    );
            $this->Log->save($data_log);
            #END - CRIANDO LOG
        }

        // $this->set('Beneficiario', $Beneficiario);
        // $this->viewBuilder()->setOption('serialize', ['Beneficiario']);
    }

    public function bi_proativa_cronicos() {
        $fail = true;
        $name_G = 'Cronicos';
        $name_P = 'cronicos';
        
        if(isset($this->params->query['token']) && $this->params->query['token'] == $this->token_proativa){
            $this->loadModel($name_G);
            $fields = '';
            $data = $this->$name_G->find('all', [  'conditions' => [],
                                                                'fields' => $fields, 
                                                                #'limit' => 10, 
                                                                #'order' => ['id'=>'DESC'], 
                                                                'recursive' => -1]);
            if(count($data)>0){
                $data = $this->Funcoes->retiraSubArray($data,$name_G);

                $fail = false;
                $this->set(array(
                    $name_P => $data,
                    '_serialize' => array($name_P)
                ));

                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                'log'=>'Rest API - Proativa '.$name_G,
                                'mensagem'            =>  '',
                                'description'         =>  'bi_proativa_'.$name_P,
                                'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                                'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                'usuario_id'          =>  1
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
            }
        }

        if($fail){
            $response = array('status' => 'failed', 'message' => 'Failed to process request');
            $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            ));

            #BEGIN - CRIANDO LOG
            $this->loadModel('Log');
            $this->Log->create();
            $data_log = array('id' =>'',
                            'log'=>'Erro - Rest API - Proativa '.$name_G,
                            'mensagem'            =>  '',
                            'description'         =>  'bi_proativa_'.$name_P,
                            'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                            'data_cadastro'       =>  date('Y/m/d H:i:s'),
                            'usuario_id'          =>  1
                    );
            $this->Log->save($data_log);
            #END - CRIANDO LOG
        }

        // $this->set('Beneficiario', $Beneficiario);
        // $this->viewBuilder()->setOption('serialize', ['Beneficiario']);
    }

    public function bi_proativa_subfaturas() {
        $fail = true;
        $name_G = 'Subfaturas';
        $name_P = 'subfaturas';
        
        if(isset($this->params->query['token']) && $this->params->query['token'] == $this->token_proativa){
            $this->loadModel($name_G);
            $fields = '';
            $data = $this->$name_G->find('all', [  'conditions' => [],
                                                                'fields' => $fields, 
                                                                #'limit' => 10, 
                                                                #'order' => ['id'=>'DESC'], 
                                                                'recursive' => -1]);
            if(count($data)>0){
                $data = $this->Funcoes->retiraSubArray($data,$name_G);

                $fail = false;
                $this->set(array(
                    $name_P => $data,
                    '_serialize' => array($name_P)
                ));

                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                'log'=>'Rest API - Proativa '.$name_G,
                                'mensagem'            =>  '',
                                'description'         =>  'bi_proativa_'.$name_P,
                                'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                                'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                'usuario_id'          =>  1
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
            }
        }

        if($fail){
            $response = array('status' => 'failed', 'message' => 'Failed to process request');
            $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            ));

            #BEGIN - CRIANDO LOG
            $this->loadModel('Log');
            $this->Log->create();
            $data_log = array('id' =>'',
                            'log'=>'Erro - Rest API - Proativa '.$name_G,
                            'mensagem'            =>  '',
                            'description'         =>  'bi_proativa_'.$name_P,
                            'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                            'data_cadastro'       =>  date('Y/m/d H:i:s'),
                            'usuario_id'          =>  1
                    );
            $this->Log->save($data_log);
            #END - CRIANDO LOG
        }

        // $this->set('Beneficiario', $Beneficiario);
        // $this->viewBuilder()->setOption('serialize', ['Beneficiario']);
    }

    public function bi_proativa_procedimento() {
        $fail = true;
        $name_G = 'Procedimento';
        $name_P = 'procedimento';
        
        if(isset($this->params->query['token']) && $this->params->query['token'] == $this->token_proativa){
            $this->loadModel($name_G);
            $fields = '';
            $data = $this->$name_G->find('all', [  'conditions' => [],
                                                                'fields' => $fields, 
                                                                #'limit' => 10, 
                                                                #'order' => ['id'=>'DESC'], 
                                                                'recursive' => -1]);
            if(count($data)>0){
                $data = $this->Funcoes->retiraSubArray($data,$name_G);

                $fail = false;
                $this->set(array(
                    $name_P => $data,
                    '_serialize' => array($name_P)
                ));

                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                'log'=>'Rest API - Proativa '.$name_G,
                                'mensagem'            =>  '',
                                'description'         =>  'bi_proativa_'.$name_P,
                                'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                                'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                'usuario_id'          =>  1
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
            }
        }

        if($fail){
            $response = array('status' => 'failed', 'message' => 'Failed to process request');
            $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            ));

            #BEGIN - CRIANDO LOG
            $this->loadModel('Log');
            $this->Log->create();
            $data_log = array('id' =>'',
                            'log'=>'Erro - Rest API - Proativa '.$name_G,
                            'mensagem'            =>  '',
                            'description'         =>  'bi_proativa_'.$name_P,
                            'server_description'  =>  'IP: '.$_SERVER['REMOTE_ADDR'],
                            'data_cadastro'       =>  date('Y/m/d H:i:s'),
                            'usuario_id'          =>  1
                    );
            $this->Log->save($data_log);
            #END - CRIANDO LOG
        }

        // $this->set('Beneficiario', $Beneficiario);
        // $this->viewBuilder()->setOption('serialize', ['Beneficiario']);
    }





    // public function call_bi_beneficiarios()
    // {
    //     $this->layout = false;
    //     $this->loadModel('Beneficiario');

    //     $response = array('status' => 'failed', 'message' => 'Failed to process request');

    //     $data = $this->Beneficiario->find('all', ['limit' => 10, 'recursive' => -1]);

    //     if (!empty($data)) {
    //         $response = array('status' => 'success', 'data' => $data);
    //     } else {
    //         $response['message'] = 'Found no matching data';
    //     }


    //     // if(!empty($id)){
    //     // } else {
    //     //     $response['message'] = "Please provide ID";
    //     // }

    //     $this->response->type('application/json');
    //     $this->response->body(json_encode($response));
    //     return $this->response->send();
    // }
}