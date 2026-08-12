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
    
    public $components = array('Paginator', 'Funcoes');#'Json',,  'Mapeamento' , 'RequestHandler'
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
    private $list_operadora = ['AMIL','GNDI','ONE HEALTH','SEGUROS UNIMED','SULAMERICA'];

    
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
    

    static function regra_sinistro(){

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
    
    public function call_rotina(){
        $this->autoRender = false;

            $toEmail = ['sandleram@gmail.com','sandler.matos@gmail.com'];
            $subject = 'SAMed - Relatório Teste';
            $msg = "Olá <b> teste<br><br>Enviado:".date('d/m/Y')." às ".date('H:i');

            
            if(!parent::envio_email($toEmail,$subject,$msg)){
                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                'log'=>'Erro - Envio Emial teste',
                                'mensagem'            =>  $subject,
                                'description'         =>  '',
                                'server_description'  =>  '',
                                'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                'usuario_id'          =>  '1'
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
            }else{
               #BEGIN - CRIANDO LOG
               $this->loadModel('Log');
               $this->Log->create();
               $data_log = array('id' =>'',
                               'log'=>'Envio Emial teste',
                               'mensagem'            =>  $subject,
                               'description'         =>  '',
                               'server_description'  =>  '',
                               'data_cadastro'       =>  date('Y/m/d H:i:s'),
                               'usuario_id'          =>  '1'
                            );
               $this->Log->save($data_log);
               #END - CRIANDO LOG
            }

            echo 1;
            exit;
    }

    public function call_rotina_agendamento_pendente(){
        $this->autoRender = false;
        $this->loadModel('Agendamento');
        #$rows = $this->Agendamento->find('All',['conditions'=>'']);

        $condition[] = 'Agendamento.status = 0 ';
        

        $this->loadModel('Atendimento');
        $this->Atendimento->unBindModel(array('hasMany' => array('Agendamento'),'belongsTo'=>array('UsuarioResponsavel')));
        
        $this->loadModel('Beneficiario');
        $this->Beneficiario->unBindModel(array('hasMany' => array('Afastado','BeneficioPrevidenciario','Absenteismo','Atendimento'),'belongsTo'=>array('UsuarioCriador','UsuarioAtualizacao','Empresa')));
        
        $this->loadModel('Usuario');
        $this->Usuario->unBindModel(array('hasMany' => array('UsuarioCliente','UsuarioBi'),'belongsTo'=>array('UsuarioCriador','Perfil','GrupoEmpresarial')));

       
       $rows = $this->Agendamento->find('all',['conditions'=>$condition,
                                        'order' => array('Agendamento.status' => 'asc','Agendamento.data_hora' => 'asc'),
                                        'recursive'=> 3,
                                        // 'limit'=>100
                                        ]);
        
        
        #CRIAÇÃO DE ENVIO DE E-MAIL CÓPIA PARA EQUIPE
        #buscar usuários acsc
        #buscar usuários gerdau
            
        #users equipe acsc (cópia para: simone.bastos1@redesc.org.br)
        $list_acsc_users = [61,62,63,64,65,66,90,91,78,79,80,101,72,73,74,67,68,69,70,71,81,82,87,88,84,85,102,83,75,76,77,100,86,98,94,93,97,95,96,92];
        $list_acsc_users = [3,61,62,63,64,65,66,67,68,69,70,71,72,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,100,101,102,104,105,151,163,165,175,181,269,279,283,294,296,330,333,338,339,340,346,349];#5,51,
        $list_gerdau_users = [];


        #ATENDIMENTO GERDAU IR PARA atendimento6@prevhug.com.br

        // krumo(1);
        // exit;
        
        #TRATA DOWNLOAD
        $list = [];
        foreach($rows as $row){
    
            $cpf = $row['Atendimento']['Beneficiario']['cpf'];
            $cpf = str_replace('.','',$cpf);
            $cpf = str_replace('-','',$cpf);
            $cpf = $this->Funcoes->formata_cpf($cpf);
           

            $cliente_id = $row['Atendimento']['Beneficiario']['cliente_id'];
            $us_resp_id = $row['UsuarioAgendamento']['id'];
            $us_email = $row['UsuarioAgendamento']['email'];
            $cliente = $row['Atendimento']['Beneficiario']['Cliente']['nome'];
            $responsavel = $this->Funcoes->utf8ToIso($row['UsuarioAgendamento']['nome']);
            
            
            if(!isset($list_responsavel[$us_resp_id])){
                $list_responsavel[$us_resp_id] = $responsavel;
            }
            if(!isset($list_email[$us_resp_id])){
                $list_email[$us_resp_id] = $us_email;
            }
            if(!isset($list_cliente[$cliente_id])){
                $list_cliente[$cliente_id] = $cliente;
            }

            
            
            $list[$us_resp_id][$cliente_id][] = [
                                                'cliente' => $this->Funcoes->utf8ToIso($row['Atendimento']['Beneficiario']['Cliente']['nome']),
                                                'id' => $row['Atendimento']['beneficiario_id'],
                                                'beneficiario' => $this->Funcoes->utf8ToIso($row['Atendimento']['Beneficiario']['nome']),
                                                'cpf' => $cpf,
                                                'data_agendamento' => $this->Funcoes->dateToView(@$row['Agendamento']['data_hora']),
                                                'responsavel' => $this->Funcoes->utf8ToIso($row['UsuarioAgendamento']['nome']),
                                                'quem_agendou' => $this->Funcoes->utf8ToIso($row['Usuario']['nome']),
                                                'data_criacao' => $this->Funcoes->dateToView($row['Agendamento']['data_cadastro'],true),
                                                'status' => 'Aguardando',
                                                'url' => Router::url('/admin/beneficiario/view/'.$row['Atendimento']['beneficiario_id'],true)
                                            ];
        }
    
        foreach($list as $user_resp_id => $list_client){

            $responsavel = $list_responsavel[$user_resp_id];
            $us_email = ['sandleram@gmail.com','marcia.morila@prevhug.com.br'];
            if(isset($list_email[$user_resp_id]) && $list_email[$user_resp_id]!= ''){
                $us_email = $list_email[$user_resp_id];
                if(in_array($user_resp_id,$list_acsc_users)){
                    $us_email = [$list_email[$user_resp_id],'marcia.morila@prevhug.com.br','atendimento5@prevhug.com.br'];   #simone.bastos1@redesc.org.br
                }
                #$us_email = [$list_email[$user_resp_id],'sandleram@gmail.com'];
            }
           
           
                                
            $html_email = '
                       <table border="1" border-color="white">
                            <tr valign="middle">
                                <th width="5">ID Beneficiario</th>
                                <th>Cliente</th>
                                <th>Beneficiário</th>
                                <th>Data do Agendamento</th>
                                <th>Responsável</th>
                                <th>Quem Agendou</th>
                                <th>Data da Criação</th>
                                <th>Status</th>
                            </tr>';
                #var_dump($us_email);
            foreach($list_client as $cliente_id => $agendamentos){
                $cliente = $list_cliente[$cliente_id];
                #var_dump($cliente);
                foreach($agendamentos as $agendamento){ 
                    $html_email .= '<tr>
                                        <td>'.$agendamento['id'].'</td>
                                        <td>'.$cliente.'</td>
                                        <td>'.trim($agendamento['beneficiario']).'</td>
                                        <td>'.$agendamento['data_agendamento'].'</td>
                                        <td>'.$agendamento['responsavel'].'</td>
                                        <td>'.$agendamento['quem_agendou'].'</td>
                                        <td>'.$agendamento['data_criacao'].'</td>
                                        <td>Pendente</td>
                                    </tr>';
                }
                
            }
            $html_email .= '</table>';

           
           
            #var_dump('***************************');
            

            $toEmail = $us_email;
            #$toEmail = ['sandleram@gmail.com','marcia.morila@prevhug.com.br'];
            $subject = 'SAMed - Relatório de agendamentos pendentes';
            #$msg = "Olá <b> {$responsavel}</b><br /><br />Segue o relatório de agendamentos pendentes<br><br><br>$html_email<br><br>Enviado:".date('d/m/Y')." às ".date('H:i').' para:'.$us_email;
            $msg = "Olá <b> {$responsavel}</b><br /><br />
                    Segue o relatório de agendamentos pendentes<br><br><br>
                    {$html_email}<br><br>
                    Enviado:".date('d/m/Y')." às ".date('H:i')."<br><br><br><br>
                    <b>Por favor, não responder este e-mail pois é um relatório automático do sistema!!! </b><br>
                    Qualquer alteração que precise efetuar nos registros, acesse o <a href='https://samed.app.br/'>Sistema SAMed</a>.
                    <br><br><br><br>";
        
       
           
            if(!parent::envio_email($toEmail,$subject,$msg)){
                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                'log'=>'Erro - Envio Relatório pendentes',
                                'mensagem'            =>  $subject,
                                'description'         =>  'Enviado para: '.$us_email,
                                'server_description'  =>  '',
                                'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                'usuario_id'          =>  $user_resp_id
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
            }else{
               #BEGIN - CRIANDO LOG
               $this->loadModel('Log');
               $this->Log->create();
               $data_log = array('id' =>'',
                               'log'=>'Envio Relatório pendentes',
                               'mensagem'            =>  $subject,
                               'description'         =>  'Enviado para: '.$us_email,
                               'server_description'  =>  '',
                               'data_cadastro'       =>  date('Y/m/d H:i:s'),
                               'usuario_id'          =>  $user_resp_id
                       );
               $this->Log->save($data_log);
               #END - CRIANDO LOG
            }

        }

        #var_dump($list_responsavel);
        #var_dump($list_email);


        echo 1;
        exit;
    }


    
    public function call_(){
        $this->autoRender = false;
        #$data = $this->params->data;
        #array_values
        $return = $this->Mapeamento->categoria_cronicos();
        
        header('Content-type: application/json');
        echo json_encode($return);
        exit;
    }



    


    public function busca_grau_parentesco($grau_parentesco, $operadora,$elegibilidade){

        if($operadora == 'GNDI'){
            switch ($elegibilidade) {
                case "0":
                    $ds_grau_parentesco = "TITULAR";
                    break;
                case "1":
                    $ds_grau_parentesco = "ESPOSA";
                    break;
                case "2":
                    $ds_grau_parentesco = "FILHO";
                    break;
                case "3":
                    $ds_grau_parentesco = "FILHA";
                    break;
                case "4":
                    $ds_grau_parentesco = "FILHO ESTUDANTE";
                    break;
                case "5":
                    $ds_grau_parentesco = "FILHO INVALIDO";
                    break;
                case "6":
                    $ds_grau_parentesco = "COMPANHEIRA(O)";
                    break;
                case "7":
                    $ds_grau_parentesco = "PAI";
                    break;
                case "8":
                    $ds_grau_parentesco = "MAE";
                    break;
                case "9":
                    $ds_grau_parentesco = "TUTELADO(A)";
                    break;
                case "10":
                    $ds_grau_parentesco = "PESSOA DESIGNADA";
                    break;
                case "11":
                    $ds_grau_parentesco = "ENTEADO(A)";
                    break;
                case "12":
                    $ds_grau_parentesco = "MARIDO DESEMPREGADO";
                    break;
                case "13":
                    $ds_grau_parentesco = "EX-ESPOSA";
                    break;
                case "14":
                    $ds_grau_parentesco = "IRMAO(A)";
                    break;
                case "15":
                    $ds_grau_parentesco = "IRMAO(A) INVALIDO(A)";
                    break;
                case "16":
                    $ds_grau_parentesco = "INVALIDO";
                    break;
                case "17":
                    $ds_grau_parentesco = "MARIDO";
                    break;
                case "18":
                    $ds_grau_parentesco = "AVO(A)";
                    break;
                case "19":
                    $ds_grau_parentesco = "MAE DE CRIACAO";
                    break;
                case "20":
                    $ds_grau_parentesco = "PAI DE CRIACAO";
                    break;
                case "21":
                    $ds_grau_parentesco = "PADRASTO";
                    break;
                case "22":
                    $ds_grau_parentesco = "MADASTRA";
                    break;
                case "23":
                    $ds_grau_parentesco = "NETO";
                    break;
                case "24":
                    $ds_grau_parentesco = "SOBRINHO(A)";
                    break;
                case "25":
                    $ds_grau_parentesco = "NOIVO(A)";
                    break;
                case "26":
                    $ds_grau_parentesco = "SOGRO(A)";
                    break;
                case "27":
                    $ds_grau_parentesco = "TIO(A)";
                    break;
                case "28":
                    $ds_grau_parentesco = "GENRO";
                    break;
                case "29":
                    $ds_grau_parentesco = "NORA";
                    break;
                case "30":
                    $ds_grau_parentesco = "CUNHADO(A)";
                    break;
                case "31":
                    $ds_grau_parentesco = "PRIMO(A)";
                    break;
                case "32":
                    $ds_grau_parentesco = "EMPREG(O) DOMEST(O)";
                    break;
                case "33":
                    $ds_grau_parentesco = "MARIDO INVALIDO";
                    break;
                case "34":
                    $ds_grau_parentesco = "AMIGO(A)";
                    break;
                case "35":
                    $ds_grau_parentesco = "AFILHADO";
                    break;
                case "36":
                    $ds_grau_parentesco = "CURATELA";
                    break;
                case "37":
                    $ds_grau_parentesco = "PAI INVALIDO";
                    break;
                case "38":
                    $ds_grau_parentesco = "MOTORISTA";
                    break;
                case "39":
                    $ds_grau_parentesco = "DEPENDENTES";
                    break;
                case "99":
                    $ds_grau_parentesco = "OUTROS";
                    break;
                default:
                    $ds_grau_parentesco = "NAO INFORMADO";
                    break;
            }
        }else if($operadora == 'BRADESCO'){
            switch ($grau_parentesco) {
                case "0":
                    $ds_grau_parentesco = "TITULAR";
                    break;
                case "1":
                    $ds_grau_parentesco = "CONJUGE";
                    break;
                case "2":
                    $ds_grau_parentesco = "FILHO(A)";
                    break;
                default:
                    $ds_grau_parentesco = "NAO INFORMADO";
                    break;
            }
        }else{
            $ds_grau_parentesco = str_replace('ô','o',$grau_parentesco);
            $ds_grau_parentesco = strtoupper($ds_grau_parentesco);
        }
        


        return $ds_grau_parentesco;
    }

    public function busca_grau_estado_civil($estado_civil, $operadora,$estado_civil_id){

        if($operadora == 'GNDI'){
            
            switch ($estado_civil_id) {
                case "1":
                    $ds_estado_civil = "SOLTEIRO(A)";
                    break;
                case "2":
                    $ds_estado_civil = "CASADO(A)";
                    break;
                case "3":
                    $ds_estado_civil = "VIUVO(A)";
                    break;
                case "4":
                    $ds_estado_civil = "SEPAR.(A)/DIVORC.(A)";
                    break;
                case "5":
                    $ds_estado_civil = "OUTROS";
                    break;
                case "0":
                    $ds_estado_civil = "NAO INFORMADO";
                    break;
                default:
                    $ds_estado_civil = "NAO INFORMADO";
                    break;
            }
            
        }else if($operadora == 'AMIL'){
            switch ($estado_civil) {
                case "SOLTEIRO":
                    $ds_estado_civil = "SOLTEIRO(A)";
                    break;
                case "CASADO":
                    $ds_estado_civil = "CASADO(A)";
                    break;
                case "OUTROS":
                    $ds_estado_civil = "OUTROS";
                    break;
                default:
                    $ds_estado_civil = "NAO INFORMADO";
                    break;
            }
        }else{
            $ds_estado_civil = $estado_civil;
        }
        
        return $ds_estado_civil;
    }
    public function busca_faixa_etaria_id($idade){
        $cod_faixa_etaria_ans = '';
        if(is_numeric($idade)){
            if ($idade >= 0 && $idade <= 18) {
                $cod_faixa_etaria_ans = 1;
            } elseif ($idade >= 19 && $idade <= 23) {
            $cod_faixa_etaria_ans = 2;
            } elseif ($idade >= 24 && $idade <= 28) {
                $cod_faixa_etaria_ans = 3;
            } elseif ($idade >= 29 && $idade <= 33) {
                $cod_faixa_etaria_ans = 4;
            } elseif ($idade >= 34 && $idade <= 38) {
                $cod_faixa_etaria_ans = 5;
            } elseif ($idade >= 39 && $idade <= 43) {
                $cod_faixa_etaria_ans = 6;
            } elseif ($idade >= 44 && $idade <= 48) {
                $cod_faixa_etaria_ans = 7;
            } elseif ($idade >= 49 && $idade <= 53) {
                $cod_faixa_etaria_ans = 8;
            } elseif ($idade >= 54 && $idade <= 58) {
                $cod_faixa_etaria_ans = 9;
            } elseif ($idade >= 59) {
                $cod_faixa_etaria_ans = 10;
            } else {
                $cod_faixa_etaria_ans = 0;
            }
        }
        
        return $cod_faixa_etaria_ans;
    }

    public function busca_faixa_etaria_ds($idade){
        $ds_faixa_etaria_ans = '';
        if(is_numeric($idade)){
            if ($idade >= 0 && $idade <= 18) {
                $ds_faixa_etaria_ans = "0 a 18";
            } elseif ($idade >= 19 && $idade <= 23) {
                $ds_faixa_etaria_ans = "19 a 23";
            } elseif ($idade >= 24 && $idade <= 28) {
                $ds_faixa_etaria_ans = "24 a 28";
            } elseif ($idade >= 29 && $idade <= 33) {
                $ds_faixa_etaria_ans = "29 a 33";
            } elseif ($idade >= 34 && $idade <= 38) {
                $ds_faixa_etaria_ans = "34 a 38";
            } elseif ($idade >= 39 && $idade <= 43) {
                $ds_faixa_etaria_ans = "39 a 43";
            } elseif ($idade >= 44 && $idade <= 48) {
                $ds_faixa_etaria_ans = "44 a 48";
            } elseif ($idade >= 49 && $idade <= 53) {
                $ds_faixa_etaria_ans = "49 a 53";
            } elseif ($idade >= 54 && $idade <= 58) {
                $ds_faixa_etaria_ans = "54 a 58";
            } elseif ($idade >= 59) {
                $ds_faixa_etaria_ans = "59 ou mais";
            } else {
                $ds_faixa_etaria_ans = "Não informado";
            }
        }
        
        return $ds_faixa_etaria_ans;
    }

    public function busca_elegibilidade ($elegibilidade,$operadora,$attr=[]){


        // if($operadora == 'BRADESCO'){
        //     $elegibilidade = 'DEPENDENTE';
        //     if($attr['numero_carteira_complemento'] == '00'){
        //         $elegibilidade = 'TITULAR';
        //     }
        // }else if($operadora == 'CNU'){
        //     $elegibilidade = 'DEPENDENTE';
        //     if($attr['ds_parentesco'] == 'TITULAR'){
        //         $elegibilidade = 'TITULAR';
        //     }
        // }else if($operadora == 'PROMEDICA'){
        //     #fazer depois
        // }else if($operadora == 'SEGUROS UNIMED'){
        //     $elegibilidade = 'DEPENDENTE';
        //     if($attr['nome_titular'] == $attr['nome_beneficiario']){
        //         $elegibilidade = 'TITULAR';
        //     }
        // }else if($operadora == 'GNDI'){
        //     // $elegibilidade = 'DEPENDENTE';
        //     // if($attr['ds_parentesco'] == 'TITULAR'){
        //     //     $elegibilidade = 'TITULAR';
        //     // }
        // }

        return $elegibilidade;
    }

    
    public function busca_sexo ($sexo,$operadora){

        if(!in_array($sexo,['M','F'])){
            if($operadora == 'GNDI'){
                if($sexo == 1){
                    $sexo = 'M';
                }else if($sexo == 2){
                    $sexo = 'F';
                }else{
                    $sexo = 'NI';
                }
            }else if($operadora == 'AMIL'){
                if($sexo == 'MASCULINO'){
                    $sexo = 'M';
                }else if($sexo == 'FEMININO'){
                    $sexo = 'F';
                }else{
                    $sexo = 'NI';
                }
            }else if($operadora == 'BRADESCO'){
                if($sexo == '01'){
                    $sexo = 'M';
                }else if($sexo == '02'){
                    $sexo = 'F';
                }else{
                    $sexo = 'NI';
                }
            }
        }

        return $sexo;
    }

    public function busca_tipo_reembolso($tipo_reembolso,$operadora,$attr){
        
        if($operadora == 'GNDI'){
            $tipo_reembolso = '0';
            if($attr['tipo_servico_operadora'] == 'Reembolso a Clientes'){
                $tipo_reembolso = '1';
            }
        }else if($operadora == 'AMIL'){
            if($tipo_reembolso == 'REDE'){
                $tipo_reembolso = '0';
            }else{
                $tipo_reembolso = '1';
            }
        }else if($operadora == 'CNU'){
            if($tipo_reembolso == 'N'){
                $tipo_reembolso = '1';
            }else{
                $tipo_reembolso = '0';
            }
       
        }else if($operadora == 'PROMEDICA'){
            if($tipo_reembolso == 'Reembolso' and $attr['nome_prestador'] == 'Reembolso'){
                $tipo_reembolso = '1';
            }else{
                $tipo_reembolso = '0';
            }
        }else if($operadora == 'SEGUROS UNIMED'){
            if($attr['tipo_sinistro'] == 'Y' ){
                $tipo_reembolso = '0';
            }else{
                $tipo_reembolso = '1';
            }
            // if($tipo_reembolso == 'Y' ){
            //     $tipo_reembolso = '0';
            // }else{
            //     $tipo_reembolso = '1';
            // }
        }else if($operadora == 'SULAMERICA'){
            if($tipo_reembolso == 'REDE' ){
                $tipo_reembolso = '0';
            }else{
                $tipo_reembolso = '1';
            }
        }

        return $tipo_reembolso;
    }


    public function busca_tipo_internacao($ds_especialidade, $operadora, $attr){
        $tipo_internacao = '';
        
        if($operadora == 'AMIL'){
            switch ($ds_especialidade) {
                case "Ginecologia E Obstetrícia":
                    $tipo_internacao = "Obstétrica";
                    break;
                case "Cirurgia Geral":
                case "Cirurgia Vascular":
                case "Cirurgia Plástica":
                    $tipo_internacao = "Cirúrgica";
                    break;
                case "Clínica Médica":
                case "Clínica Médica (Medicina interna)":
                    $tipo_internacao = "Clínica";
                    break;
                case "Medicina Nuclear":
                    $tipo_internacao = "Nuclear";
                    break;
                case "NAO INFORMADO":
                    $tipo_internacao = "Não Informado";
                    break;
                case "Patologia":
                case "Patologia Clínica/Medicina Laboratorial":
                    $tipo_internacao = "Patológico";
                    break;
                case "Urologia":
                    $tipo_internacao = "Urológico";
                    break;
                case "Reumatologia":
                    $tipo_internacao = "Reumatológico";
                    break;
                default:
                    $tipo_internacao = "Não cadastrado";
                    break;
            }
        }else if($operadora == 'CNU'){
            switch ($ds_especialidade) {
                case "Ginecologia E Obstetricia":
                    $tipo_internacao = "Obstétrica";
                    break;
                case "Cirurgia Da Cabeca E Pescoco":
                case "Cirurgia Da Mao":
                case "Cirurgia Do Aparelho Digestivo":
                case "Cirurgia Geral":
                case "Cirurgia Plastica":
                case "Cirurgia Vascular":
                case "Neurocirurgia":
                    $tipo_internacao = "Cirúrgica";
                    break;
                case "Clinica Medica":
                    $tipo_internacao = "Clínica";
                    break;
                case "MEDICINA NUCLEAR":
                    $tipo_internacao = "Nuclear";
                    break;
                case "ANATOMIA PATOLOGICA E CITOPATOLOGIA":
                case "ANATOMIA PATOLOGICA E CITOPATOLOGICA":
                case "Patologia":
                case "PATOLOGIA CLINICA":
                case "Patologia Clinica  Medicina Laboratorial":
                    $tipo_internacao = "Patológico";
                    break;
                case "Urologia":
                    $tipo_internacao = "Urológico";
                    break;
                case "Reumatologia":
                    $tipo_internacao = "Reumatológico";
                    break;
                default:
                    $tipo_internacao = "Não cadastrado";
                    break;
            }
        }else if($operadora == 'GNDI'){
            switch ($ds_especialidade) {
                case "Ginecologia e obstetrícia":
                    $tipo_internacao = "Obstétrica";
                    break;
                case "Cirurgia geral":
                case "Cirurgia vascular":
                case "Cirurgia plástica":
                    $tipo_internacao = "Cirúrgica";
                    break;
                case "Clínica médica (Medicina interna)":
                    $tipo_internacao = "Clínica";
                    break;
                case "Medicina nuclear":
                    $tipo_internacao = "Nuclear";
                    break;
                case "NAO INFORMADO":
                    $tipo_internacao = "Não Informado";
                    break;
                case "Patologia":
                    $tipo_internacao = "Patológico";
                    break;
                case "Urologia":
                    $tipo_internacao = "Urológico";
                    break;
                case "Reumatologia":
                    $tipo_internacao = "Reumatológico";
                    break;
                default:
                    $tipo_internacao = "Não cadastrado";
                    break;
            }
        }else if($operadora == 'HAPVIDA'){

            switch ($ds_especialidade) {
                case "GINECOLOGIA E OBSTETRICIA":
                    $tipo_internacao = "Obstétrica";
                    break;
                case "CIRURGIA GERAL":
                case "CIRURGIA CARDIOVASCULAR":
                case "CIRURGIA DE CABECA E PESCOCO":
                case "CIRURGIA PLASTICA":
                case "CIRURGIA PEDIATRICA":
                case "CIRURGIA TORACICA":
                    $tipo_internacao = "Cirúrgica";
                    break;
                case "CLINICA MEDICA":
                    $tipo_internacao = "Clínica";
                    break;
                case "MEDICINA NUCLEAR":
                    $tipo_internacao = "Nuclear";
                    break;
                case "NAO INFORMADO":
                    $tipo_internacao = "Não Informado";
                    break;
                case "PATOLOGIA":
                    $tipo_internacao = "Patológico";
                    break;
                case "UROLOGIA":
                    $tipo_internacao = "Urológico";
                    break;
                case "REUMATOLOGIA":
                    $tipo_internacao = "Reumatológico";
                    break;
                default:
                    $tipo_internacao = "Não cadastrado";
                    break;
            }
        }else if($operadora == 'PROMEDICA'){
            switch ($ds_especialidade) {
                case "Médico Ginecologista E Obstetra":
                    $tipo_internacao = "Obstétrica";
                    break;
                case "Médico Cirurgião Geral":
                case "Médico Cirurgião Cardiovascular":
                case "Médico Cirurgião Da Mão":
                case "Médico Cirurgião Do Aparelho Digestivo":
                case "Médico Cirurgião Pediátrico":
                case "Médico Cirurgião Torácico":
                case "Médico Em Cirurgia Vascular":
                    $tipo_internacao = "Cirúrgica";
                    break;
                case "Médico Clínico":
                    $tipo_internacao = "Clínica";
                    break;
                case "Médico Em Medicina Nuclear":
                    $tipo_internacao = "Nuclear";
                    break;
                case "Não Informada":
                case "Sem Informação":
                    $tipo_internacao = "Não Informado";
                    break;
                case "Médico Patologista Clínico / Medicina Laboratorial":
                    $tipo_internacao = "Patológico";
                    break;
                case "Médico Urologista":
                    $tipo_internacao = "Urológico";
                    break;
                case "Médico Reumatologista":
                    $tipo_internacao = "Reumatológico";
                    break;
                default:
                    $tipo_internacao = "Não cadastrado";
                    break;
            }
        }else if($operadora == 'SULAMERICA' && $attr['cod_atendimento'] != '' ){
            
            switch ($attr['cod_atendimento']) {
                case "6":
                    $tipo_internacao = "Clínica";
                    break;
                case "7":
                    $tipo_internacao = "Cirúrgica";
                    break;
                default:
                    $tipo_internacao = "Não informado";
                    break;
            }
        }

        return $tipo_internacao;
    }
    
    public function busca_tipo_servico($tipo_servico, $operadora, $tipo_servico_operadora){
        if($operadora == 'CNU'){
            if($tipo_servico == 'S'){
                $tipo_servico = '1';
            }else{
                $tipo_servico = '2';
            }
        }else if($operadora == 'GNDI'){
            if($tipo_servico_operadora == 'Internação'){
                $tipo_servico = '1';
            }else{
                $tipo_servico = '2';
            }
        }else if($operadora == 'HAPVIDA'){
            if(in_array($tipo_servico,['INTERNACAO CLINICA','INTERNACAO CIRURGICA'])){
                $tipo_servico = '1';
            }else{
                $tipo_servico = '2';
            }
        }else if($operadora == 'PROMEDICA'){
            if(in_array($tipo_servico,['INTERNACAO DE EMERGENCIA','INTERNACAO ELETIVA'])){
                $tipo_servico = '1';
            }else{
                $tipo_servico = '2';
            }
        }else if($operadora == 'SEGUROS UNIMED'){
            if(in_array($tipo_servico,['A'])){
                $tipo_servico = '2';
            }else{
                $tipo_servico = '1';
            }
        }else if($operadora == 'SULAMERICA'){
            if(in_array($tipo_servico,['INTERNADO','INTERNADO-APTO','HOSPITAL DIA','INTERNADO-ENFERMARIA'])){
                $tipo_servico = '1';
            }elseif(in_array($tipo_servico,['AMBULATORIAL'])){
                $tipo_servico = '2';
            }else{
                $tipo_servico = '2';
            }
        }


        return $tipo_servico;
    }

    public function busca_carteira_titular($numero_carteira_titular, $operadora, $attr){
        $retorno = $numero_carteira_titular;
        if($operadora == 'BRADESCO'){
            $retorno = $attr['numero_carteira'].'00';
        }else if($operadora == 'CNU'){
            $retorno = '0'.$numero_carteira_titular;
        }else if($operadora == 'PROMEDICA'){
            $retorno = $attr['matricula'].'00';
        }else if($operadora == 'SEGUROS UNIMED'){
            $retorno = str_replace('.','',str_replace('-','',$numero_carteira_titular));
        }
        return $retorno;
    }

    public function busca_carteira($numero_carteira, $operadora, $attr){
        $retorno = $numero_carteira;
        if($operadora == 'BRADESCO'){
            $retorno = $numero_carteira.$attr['numero_carteira_complemento'];
        }else if($operadora == 'CNU'){
            $retorno = $attr['matricula'].'0'.$attr['sequencia'];
        }else if($operadora == 'SEGUROS UNIMED'){
            $retorno = str_replace('.','',str_replace('-','',$numero_carteira));
        }
        return $retorno;
    }
    public function busca_cpf($operadora,$attr){
        $retorno = $attr['cpf_beneficiario'];
        if($operadora == 'BRADESCO'){
        }else if($operadora == 'CNU'){
        }else if($operadora == 'SEGUROS UNIMED'){
            #if($dadosrobo['elegibilidade'] == 'T')
            if(isset($attr['cpf_titular']) && $attr['cpf_titular'] != '' && $attr['elegibilidade'] == 'T'){
                $retorno = $attr['cpf_titular'];
            }
        }
        return $retorno;
    }
    

    /**
     * CHAMADA PRINCIPAL
     */
    public function call_integration_robo_proativa(){
        $this->autoRender = false;
        $cliente_id = '';
        #$cliente_id = 21; #TECNISA (AMIL)
        #$cliente_id = 18; #VICUNHA (AMIL)
        #$cliente_id = 67; #VIVER (AMIL)
        #$cliente_id = 30; #CROMUS (GNDI) 

        #$cliente_id = 65; #BLUEBAY (AMIL)
        #$cliente_id = 38; #CALDEX (AMIL) 
        #$cliente_id = 63; #DECISAO (AMIL)
        #$cliente_id = 27; #EIGIER LABORATORIO (AMIL)
        #$cliente_id = 37; #ETILUX (AMIL)
        #$cliente_id = 66; #FLORAL (AMIL) Não tem beneficiario
        #$cliente_id = 28; #GRUPO MOAS (AMIL)
        #$cliente_id = 35; #NORTENE/SANTENO (AMIL)
        #$cliente_id = 25; #ROJEMAC (AMIL)
        echo 'Cliente: '.$cliente_id.'<br>';
        


        if(isset($cliente_id) && $cliente_id != ''){
            #exit('joa rodou Beneficiario 4');
            $date = date('Y-m-d H:i:s');
            echo 'begin: '.$date.'<br>';
            
            // echo 'Beneficiario <br>';
            // $this->robo_proativa_beneficiario($cliente_id);
            // $date = date('Y-m-d H:i:s');
            // echo $date.'<br><br>';
            
            // echo 'Faturamento <br>';
            // $this->robo_proativa_faturamento($cliente_id);
            // $date = date('Y-m-d H:i:s');
            // echo $date.'<br><br>';
            
            // echo 'Sinistro <br>';
            // $this->robo_proativa_sinistro($cliente_id,'all');
            
            echo '<br>end: '.date('Y-m-d H:i:s');
            
        }else{
            echo 2;
            exit;
        }

        /**
         * SELECT competencia,operadora FROM dw_beneficiario WHERE cliente_id = 30 AND operadora = 'AMIL' GROUP BY competencia;	
         * SELECT cliente_id, operadora, DATE_FORMAT(data_pagamento,'%Y-%m') FROM sinistro WHERE cliente_id = 30 AND operadora = 'AMIL' GROUP BY cliente_id, operadora, DATE_FORMAT(data_pagamento,'%Y-%m');
         * SELECT competencia,operadora FROM faturamento WHERE cliente_id = 30 AND operadora = 'AMIL' GROUP BY competencia;	
         * 
         */

        echo 1;
    }

    #PROATIVA - BENEFICIARIO
    public function robo_proativa_beneficiario($cliente_id){
        ini_set('memory_limit', '12048M');
        ini_set('max_execution_time', 90000000);
        $this->loadModel('DwBeneficiario');
        $this->loadModel('BeneficiarioProRobo');
        $this->loadModel('DwRoboAtualizacao');
       
        #BUSCA QUANDO FOI A ULTIMA BUSCA
        $conditions = ['conditions'=>['tipo'=>'Beneficiario'],'fields'=>'ultima_atualizacao']; #
        $ultima_atualizacao = $this->DwRoboAtualizacao->find('first',$conditions);

        if(count($ultima_atualizacao)>0){
            $data_ultima_atualizacao = $ultima_atualizacao['DwRoboAtualizacao']['ultima_atualizacao'];
        }
        $data_ultima_atualizacao = '2019-01-01 00:00:01';
        
        
    
        
        $error = [];
        $data_cadastro = date('Y-m-d H:i:s');
        
        #BUSCA POR OPERADORA
        foreach($this->list_operadora as $operadora){

            #BUSCAR A MAIOR COMPETÊNCIA
            $conditions = ['conditions'=>['operadora'=>$operadora,"data_cadastro >= '{$data_ultima_atualizacao}'", 'cliente_id'=>$cliente_id],
                           'fields'=>'competencia',
                           'group'=>['competencia'],
                           'order'=>['competencia'=>'DESC'],
                           'recursive'=>-1,
                           
                            ];
            $competencia = $this->BeneficiarioProRobo->find('first',$conditions);
          
            
            if(!isset($competencia['BeneficiarioProRobo']['competencia'])){
                continue;
            }


            $conditions = ['conditions'=>['operadora'=>$operadora,"data_cadastro >= '{$data_ultima_atualizacao}'", 'competencia'=>$competencia['BeneficiarioProRobo']['competencia'], 'cliente_id'=>$cliente_id],
                          'order'=>['dt_inclusao'=>'ASC']];#,'limit'=>'1000'
            $rows = $this->BeneficiarioProRobo->find('all',$conditions);
            
            // krumo($conditions);
            // krumo('total: '.count($rows));
           
           
            $alterado_exclusao = '';
            if(count($rows) > 0){

                $this->DwBeneficiario->query('DELETE FROM dw_beneficiario where  cliente_id = "'.$cliente_id.'" and operadora = "'.$operadora.'"');

                
                foreach($rows as $row){                
                    $situacao = 'Ativo'; 
                    if($row['BeneficiarioProRobo']['dt_exclusao'] != ''){
                        $situacao = 'Excluido'; 
                    }


                    $chave_beneficiario =  strtoupper(trim(str_replace(' ','',$row['BeneficiarioProRobo']['nome_beneficiario']))).$this->Funcoes->dateToView($row['BeneficiarioProRobo']['dt_nascimento']);
                    #VERIFICA SE FOI SALVO
                    $conditions = ['conditions'=>['cliente_id'=>$row['BeneficiarioProRobo']['cliente_id'],
                                                'operadora'=>$operadora,
                                                'chave_beneficiario'=>$chave_beneficiario ],
                                    'fields'=>'id,dt_exclusao,status',
                                    'recursive'=> -1];
                    $rowBenef = $this->DwBeneficiario->find('first',$conditions);
                    #krumo($rowBenef);
                  

                    $id = '';
                    if(count($rowBenef)>0 && isset($rowBenef['DwBeneficiario']['id']) && $rowBenef['DwBeneficiario']['id'] != ''){
                        $id = $rowBenef['DwBeneficiario']['id'];
                        #ATUALIZAÇÃO CASO A DATA DE EXCLUSÃO FOI PREENCHIDA
                        // if($rowBenef['Beneficiario']['dt_exclusao'] != $row['BeneficiarioProRobo']['dt_exclusao']  && $rowBenef['Beneficiario']['status'] == 2){
                        //     $data['id']=$rowBenef['Beneficiario']['id'];
                        //     $data['dt_exclusao'] = $row['BeneficiarioProRobo']['dt_exclusao'];#OK
                        //     $data['situacao'] = $situacao; 
                        //     $data['status'] = 1; 
                        //     if(!$this->Beneficiario->save($data)){
                        //         $error['Beneficiario'][$operadora][] = $row['BeneficiarioProRobo']['cliente_id'];
                        //         continue;
                        //     }
                        // }else if($rowBenef['Beneficiario']['dt_exclusao']  != $row['BeneficiarioProRobo']['dt_exclusao'] ){
                        //     $data['id']=$rowBenef['Beneficiario']['id'];
                        //     $data['dt_exclusao'] = $row['BeneficiarioProRobo']['dt_exclusao'];#OK
                        //     $data['situacao'] = $situacao; 
                        //     if(!$this->Beneficiario->save($data)){
                        //         $error['Beneficiario'][$operadora][] = $row['BeneficiarioProRobo']['cliente_id'];
                        //         continue;
                        //     }
                        // }else if($rowBenef['Beneficiario']['status'] == 2 ){
                        //     $data['id']=$rowBenef['Beneficiario']['id'];
                        //     $data['status'] = 1; 
                        //     if(!$this->Beneficiario->save($data)){
                        //         $error['Beneficiario'][$operadora][] = $row['BeneficiarioProRobo']['cliente_id'];
                        //         continue;
                        //     }
                        // }
                        // continue;
                    }


                                
                    #CREATE Beneficiario
                    $data = [];
                    $data['id']=$id;
                    $data['cliente_id'] = $row['BeneficiarioProRobo']['cliente_id'];
                    $data['competencia'] = $row['BeneficiarioProRobo']['competencia'];#OK
                    $data['nome'] = $row['BeneficiarioProRobo']['nome_beneficiario']; #OK


                    $attr_cpf = ['cpf_beneficiario'=>$row['BeneficiarioProRobo']['cpf_beneficiario'], 
                                 'cpf_titular'=>$row['BeneficiarioProRobo']['cpf_titular'],
                                 'elegibilidade'=>$row['BeneficiarioProRobo']['elegibilidade']];
                    $data['cpf'] = $this->busca_cpf($operadora,$attr_cpf);#OK
                    $data['rg'] = $row['BeneficiarioProRobo']['rg']; #OK
                    $data['pis'] = $row['BeneficiarioProRobo']['pis']; #OK
                    $data['nome_mae'] = $row['BeneficiarioProRobo']['nome_mae']; #OK
                    $data['email'] = $row['BeneficiarioProRobo']['email'];#ok 
                    $data['data_nascimento'] = $row['BeneficiarioProRobo']['dt_nascimento'];#OK
                    $data['endereco'] = $row['BeneficiarioProRobo']['end_logradouro']; #OK
                    $data['numero'] = $row['BeneficiarioProRobo']['end_numero']; #OK
                    $data['complemento'] = $row['BeneficiarioProRobo']['end_complemento']; #OK
                    $data['bairro'] = $row['BeneficiarioProRobo']['end_bairro']; #OK
                    $data['cep'] = $row['BeneficiarioProRobo']['end_cep']; #OK
                    $data['cidade'] = $row['BeneficiarioProRobo']['end_cidade']; #OK
                    $data['estado'] = $row['BeneficiarioProRobo']['end_uf']; #OK

                    
                    if($row['BeneficiarioProRobo']['ddd_celular'] != '' && $row['BeneficiarioProRobo']['celular'] != ''){
                        $data['telefone_tipo'] = 'Celular'; 
                        $data['telefone'] = "({$row['BeneficiarioProRobo']['ddd_celular']}) {$row['BeneficiarioProRobo']['celular']}"; #OK 
                    }

                    $data['sexo'] = $this->busca_sexo($row['BeneficiarioProRobo']['sexo'],$operadora);#OK
                    $data['ocupacao'] = $row['BeneficiarioProRobo']['cargo'];#OK 
                    $data['situacao'] = $situacao; 
                    $data['chave_beneficiario'] = $chave_beneficiario;
                    $data['grupo_familiar_id'] = $row['BeneficiarioProRobo']['grupo_familiar_id'];#OK 
                    $data['cod_matricula'] = $row['BeneficiarioProRobo']['cod_matricula'];#OK
                    $data['dt_inclusao'] = $row['BeneficiarioProRobo']['dt_inclusao'];#OK
                    $data['dt_exclusao'] = $row['BeneficiarioProRobo']['dt_exclusao'];#OK
                    $data['dt_admissao'] = $row['BeneficiarioProRobo']['dt_admissao'];#OK
                    
                    $idade = $row['BeneficiarioProRobo']['idade']; #OK
                    if(trim($row['BeneficiarioProRobo']['idade']) == '' && $row['BeneficiarioProRobo']['dt_nascimento'] != ''){
                        $idade = $this->Funcoes->calcula_idade($row['BeneficiarioProRobo']['dt_nascimento']);
                    }
                    $data['idade']= $idade;

                    // $data['faixa_etaria_ans_id'] = $row['BeneficiarioProRobo']['faixa_etaria_ans_id']; #OK
                    // if($row['BeneficiarioProRobo']['faixa_etaria_ans_id'] == 0 || $row['BeneficiarioProRobo']['faixa_etaria_ans_id'] == ''){
                    //     $data['faixa_etaria_ans_id'] = ''; #OK
                    // }

                    $grau_parentesco = $row['BeneficiarioProRobo']['grau_parentesco_id'];
                    if($row['BeneficiarioProRobo']['grau_parentesco_id'] == 0 || $row['BeneficiarioProRobo']['grau_parentesco_id'] == ''){
                        $data['grau_parentesco_id'] = ''; #OK
                    }
                    $data['grau_parentesco_id'] = $grau_parentesco; 
                    $data['ds_grau_parentesco'] = $this->busca_grau_parentesco($row['BeneficiarioProRobo']['ds_grau_parentesco'],$operadora,$row['BeneficiarioProRobo']['elegibilidade']); #OK
                    $data['nome_titular'] = $row['BeneficiarioProRobo']['nome_titular']; #OK
                    $data['cpf_titular'] = $row['BeneficiarioProRobo']['cpf_titular']; #OK
                    $data['estado_civil_id'] = $row['BeneficiarioProRobo']['estado_civil_id'];#OK
                    $data['ds_estado_civil'] = $this->busca_grau_estado_civil($row['BeneficiarioProRobo']['ds_estado_civil'],$operadora,$row['BeneficiarioProRobo']['estado_civil_id']); #OK
                    $data['plano_id'] = $row['BeneficiarioProRobo']['plano_id']; #OK
                    $data['ds_plano'] = $row['BeneficiarioProRobo']['ds_plano']; #OK

                    $elegibilidade = '';

                    if($operadora == 'SEGUROS UNIMED'){
                        if($row['BeneficiarioProRobo']['grau_parentesco_id'] != ''){
                            if($row['BeneficiarioProRobo']['grau_parentesco_id'] == '00'){
                                $elegibilidade = 'TITULAR';
                            }else{
                                $elegibilidade = 'DEPENDENTE';
                            }
                        }
                    }else{
                        if($row['BeneficiarioProRobo']['elegibilidade'] != ''){
                            $elegibilidade = 'DEPENDENTE';
                            if(in_array($row['BeneficiarioProRobo']['elegibilidade'], ['T','01','1','00','0','TITULAR','titular'])){
                                $elegibilidade = 'TITULAR';
                            }
                        }
                    }
                    $data['elegibilidade'] = $elegibilidade; 


                    $data['cod_cns'] = $row['BeneficiarioProRobo']['cod_cns']; #OK 
                    $data['numero_nascido_vivo'] = $row['BeneficiarioProRobo']['numero_nascido_vivo']; #OK 
                    $data['cod_operadora'] = $row['BeneficiarioProRobo']['cod_operadora']; #OK 
                    $data['operadora'] = $row['BeneficiarioProRobo']['operadora']; #OK 
                    $data['faixa_etaria_ans_id'] = $this->busca_faixa_etaria_id($idade);
                    $data['ds_faixa_etaria_ans'] = $this->busca_faixa_etaria_ds($idade);

                    
                    $data['ds_tipo_acomodacao'] = $row['BeneficiarioProRobo']['ds_tipo_acomodacao']; #OK 
                    if($operadora == 'GNDI' && $data['ds_tipo_acomodacao'] != '' && !in_array($data['ds_tipo_acomodacao'],["ENFERMARIA" , "APARTAMENTO"])){
                        $data['ds_tipo_acomodacao'] = $row['BeneficiarioProRobo']['ds_tipo_acomodacao'] == 1 ? "ENFERMARIA" : "APARTAMENTO"; #OK 
                    }



                    $data['tipo_movimentacao'] = $row['BeneficiarioProRobo']['tipo_movimentacao']; #OK 
                    $data['cod_u_seg'] = $row['BeneficiarioProRobo']['cod_u_seg']; #OK 
                    $data['codigo_empresa'] = $row['BeneficiarioProRobo']['codigo_empresa']; #OK 
                    $data['relacao_dep'] = $row['BeneficiarioProRobo']['relacao_dep']; #OK 
                    $data['relacao_dep_digito'] = $row['BeneficiarioProRobo']['relacao_dep_digito']; #OK 
                    $data['lotacao_do_funcionario'] = $row['BeneficiarioProRobo']['lotacao_do_funcionario']; #OK 
                    $data['motivo_exclusao'] = $row['BeneficiarioProRobo']['motivo_exclusao']; #OK 
                    $data['cod_empresa'] = $row['BeneficiarioProRobo']['cod_empresa']; #OK 
                    $data['num_contrato'] = $row['BeneficiarioProRobo']['num_contrato']; #OK 
                    
                    $data['carteirinha'] = $row['BeneficiarioProRobo']['carteirinha']; #OK 

                    if($operadora == 'SEGUROS UNIMED'){
                        $data['carteirinha'] =  $row['BeneficiarioProRobo']['carteirinha'].
                                                $row['BeneficiarioProRobo']['cod_u_seg'].
                                                $row['BeneficiarioProRobo']['grupo_familiar_id'].
                                                str_pad($row['BeneficiarioProRobo']['codigo_empresa'], 4, "0", STR_PAD_LEFT).
                                                str_pad($row['BeneficiarioProRobo']['relacao_dep'], 2, "0", STR_PAD_LEFT).
                                                $row['BeneficiarioProRobo']['relacao_dep_digito'];

                    }else if($operadora == 'BRADESCO'){
                        #DESENVOLVER
                        #carteirinha + carteirinha_complemento
                    }

                    $data['carteirinha_titular'] = $row['BeneficiarioProRobo']['carteirinha_titular']; #OK 
                    $data['cod_permanencia'] = $row['BeneficiarioProRobo']['cod_permanencia']; #OK 
                    $data['desc_permanencia'] = $row['BeneficiarioProRobo']['desc_permanencia']; #OK 
                    $data['remido'] = $row['BeneficiarioProRobo']['remido']; #OK 
                    $data['usuario_criador_id'] = 1; 
                    $data['data_cadastro_robo'] = $row['BeneficiarioProRobo']['data_cadastro'];#ok
                    $data['data_atualizacao'] = $data_cadastro;
                    
                    
                    #krumo('ok');
                    # exit;
                    
                    if($id == ''){
                        $this->DwBeneficiario->create();
                        $data['data_cadastro'] = $data_cadastro;
                        $data['status'] = 1;
                    }
                    if(!$this->DwBeneficiario->save($data)){
                        $error['DwBeneficiario'][$operadora][] = $row['BeneficiarioProRobo']['cliente_id'];
                    }
                     //krumo('ok');
                     
                
                }
            }

            if(count($error)>0){
                #LOG ERROR
                var_dump($error);
                
            }
        }


        // echo 'Início - '.$data_cadastro.'<br>';
        // echo 'FIM - '.date('Y-m-d H:i:s');
        // exit;

        $this->DwRoboAtualizacao->create();
        $data_dw_att = [];
        $data_dw_att['id'] = '';
        $data_dw_att['tipo'] = 'Beneficiario';
        $data_dw_att['ultima_atualizacao'] = $data_cadastro;
        $data_dw_att['status'] = 1;
        $data_dw_att['data_cadastro'] = date('Y-m-d H:i:s');
        $this->DwRoboAtualizacao->save($data_dw_att);

        return true;
    }

    #PROATIVA - FATURAMENTO
    public function robo_proativa_faturamento($cliente_id = 'all'){
        ini_set('memory_limit', '12048M');
        ini_set('max_execution_time', 90000000);
        $this->loadModel('Faturamento');
        $this->loadModel('FaturamentoProRobo');
        $this->loadModel('DwRoboAtualizacao');
       
        #$rows = $this->FaturamentoProRobo->find('count');
        #krumo($rows);

        #$rows = $this->Faturamento->find('count');
        #krumo($rows);
        #$exec = $this->Faturamento->query('TRUNCATE TABLE faturamento;'); #execução 
        
        #BUSCA QUANDO FOI A ULTIMA BUSCA
        $conditions = ['conditions'=>['tipo'=>'Faturamento'],'fields'=>'ultima_atualizacao','order'=>['data_cadastro'=>'DESC']];
        $ultima_atualizacao = $this->DwRoboAtualizacao->find('first',$conditions);

        $data_ultima_atualizacao = '2019-01-01 00:00:01';
        if(count($ultima_atualizacao)>0){
            $data_ultima_atualizacao = $ultima_atualizacao['DwRoboAtualizacao']['ultima_atualizacao'];
        }
        


        $error = [];
        $data_cadastro = date('Y-m-d H:i:s');
        
        #BUSCA POR OPERADORA
        foreach($this->list_operadora as $operadora){
            $conditions = ['conditions'=>['operadora'=>$operadora,"data_cadastro >= '{$data_ultima_atualizacao}'"]];#,'limit'=>3
            $rows = $this->FaturamentoProRobo->find('all',$conditions);
            #krumo($operadora);
            #krumo($rows);

            foreach($rows as $row){

                #VERIFICA SE FOI SALVO
                $conditions = ['conditions'=>['cliente_id'=>$row['FaturamentoProRobo']['cliente_id'],
                                              'operadora'=>$operadora,
                                              'codigo_operadora'=>$row['FaturamentoProRobo']['codigo_operadora'],
                                              'competencia_referencia'=>$row['FaturamentoProRobo']['competencia_referencia'],
                                              'competencia'=>$row['FaturamentoProRobo']['competencia']]];
                $saved_count = $this->Faturamento->find('count',$conditions);
                
                if($saved_count == 0){
                    #CREATE FATURAMENTO   
                    $data = [];
                    $data['id']='';
                    $data['cliente_id'] = $row['FaturamentoProRobo']['cliente_id'];
                    $data['competencia_referencia'] = $row['FaturamentoProRobo']['competencia_referencia'];
                    $data['competencia'] = $row['FaturamentoProRobo']['competencia'];
                    $data['codigo_operadora'] = $row['FaturamentoProRobo']['codigo_operadora'];
                    $data['operadora'] = $row['FaturamentoProRobo']['operadora'];
                    $data['valor_fatura'] = $row['FaturamentoProRobo']['valor_fatura'];
                    $data['qtd_vidas'] = $row['FaturamentoProRobo']['qtd_vidas'];
                    $data['reembolso'] = $row['FaturamentoProRobo']['reembolso'];
                    $data['rede'] = $row['FaturamentoProRobo']['rede'];
                    $data['coparticipacao'] = $row['FaturamentoProRobo']['coparticipacao'];
                    $data['revisao'] = $row['FaturamentoProRobo']['revisao'];
                    $data['recuperacao'] = $row['FaturamentoProRobo']['recuperacao'];
                    $data['valor_sinistro'] = $row['FaturamentoProRobo']['valor_sinistro'];
                    $data['percentual'] = $row['FaturamentoProRobo']['percentual'];
                    $data['qtd_beneficiarios_atendidos'] = $row['FaturamentoProRobo']['qtd_beneficiarios_atendidos'];
                    $data['total_sinistro'] = $row['FaturamentoProRobo']['total_sinistro'];

                    if($data['valor_sinistro'] == ''){
                        $data['valor_sinistro'] = $row['FaturamentoProRobo']['total_sinistro']; 
                    }
                    if($data['total_sinistro'] == ''){
                        $data['total_sinistro'] = $row['FaturamentoProRobo']['valor_sinistro']; 
                    }


                    $data['data_cadastro_robo'] = $row['FaturamentoProRobo']['data_cadastro'];
                    $data['data_cadastro'] = $data_cadastro;
                    
                    $this->Faturamento->create();
                    if(!$this->Faturamento->save($data)){
                        $error['Faturamento'][$operadora][] = $row['FaturamentoProRobo']['cliente_id'];
                    }
                }
            }
            if(count($error)>0){
                #LOG ERROR
                var_dump($error);
                
            }
        }

        $this->DwRoboAtualizacao->create();
        $data_dw_att = [];
        $data_dw_att['id'] = '';
        $data_dw_att['tipo'] = 'Faturamento';
        $data_dw_att['ultima_atualizacao'] = $data_cadastro;
        $data_dw_att['status'] = 1;
        $data_dw_att['data_cadastro'] = date('Y-m-d H:i:s');
        $this->DwRoboAtualizacao->save($data_dw_att);

        return true;
    }

    #PROATIVA - SINISTRO
    public function robo_proativa_sinistro($cliente_id, $competencia_all = false){
        ini_set('memory_limit', '12048M');
        ini_set('max_execution_time', 90000000);
        $this->loadModel('Sinistro');
        $this->loadModel('SinistroProRobo');
        $this->loadModel('DwRoboAtualizacao');
       
        #BUSCA QUANDO FOI A ULTIMA BUSCA
        $conditions = ['conditions'=>['tipo'=>'Sinistro'],'fields'=>'ultima_atualizacao']; #
        $ultima_atualizacao = $this->DwRoboAtualizacao->find('first',$conditions);


        if(count($ultima_atualizacao)>0){
            $data_ultima_atualizacao = $ultima_atualizacao['DwRoboAtualizacao']['ultima_atualizacao'];
        }
        $data_ultima_atualizacao = '2019-01-01 00:00:01';
        
        
        
        $error = [];
        $data_cadastro = date('Y-m-d H:i:s');
        
        #BUSCA POR OPERADORA
        foreach($this->list_operadora as $operadora){

            #DELETAR
            // if($operadora == 'SEGUROS UNIMED'){
            //     $sql = "DELETE FROM sinistro WHERE operadora = 'SEGUROS UNIMED' and cliente_id = 17";
            //     if(!$this->Sinistro->query($sql)){
            //         $error['Sinistro'][$operadora][] = 'Erro ao tentar excluir a operadora '.$operadora;
            //         continue;
            //     }
            // }


            #BUSCAR A MAIOR COMPETÊNCIA
            $conditions = ['conditions'=>['operadora'=>$operadora,"data_cadastro >= '{$data_ultima_atualizacao}'", 'cliente_id'=>$cliente_id],
                           'fields'=>'competencia',
                           'group'=>['competencia'],
                           'order'=>['competencia'=>'DESC'],
                           'recursive'=>-1,
                           
                            ];
            $competencia = $this->SinistroProRobo->find('first',$conditions);
          
                       
            if(!isset($competencia['SinistroProRobo']['competencia'])){
                continue;
            }

            #VERIFICAR COMPETENCIA FALTANTE
            
            $conditions = ['conditions'=>['operadora'=>$operadora,"data_cadastro >= '{$data_ultima_atualizacao}'", 'competencia'=>$competencia['SinistroProRobo']['competencia'], 'cliente_id'=>$cliente_id]];#,'limit'=>1000
            if($competencia_all == true){
                $conditions = ['conditions'=>['operadora'=>$operadora,"data_cadastro >= '{$data_ultima_atualizacao}'", 'cliente_id'=>$cliente_id]];#,'limit'=>1000
            }
            #$conditions = ['conditions'=>['operadora'=>$operadora,"data_cadastro >= '{$data_ultima_atualizacao}'", 'competencia'=>'2024-01-01', 'cliente_id'=>$cliente_id]];#,'limit'=>1000
            $rows = $this->SinistroProRobo->find('all',$conditions);
         
            
            foreach($rows as $row){

                $chave_beneficiario =  strtoupper(trim(str_replace(' ','',$row['SinistroProRobo']['nome_beneficiario']))).$this->Funcoes->dateToView($row['SinistroProRobo']['data_nascimento']);
                
                #VERIFICA SE FOI SALVO
                // $conditions = ['conditions'=>['cliente_id'=>$row['SinistroProRobo']['cliente_id'],
                //                               'operadora'=>$operadora,
                //                               'numero_carteira'=>$row['SinistroProRobo']['numero_carteira'],
                //                               'data_evento'=>$row['SinistroProRobo']['data_evento'],
                //                               'ds_procedimento'=>$row['SinistroProRobo']['ds_procedimento'],
                //                               'valor'=>$row['SinistroProRobo']['valor'],
                //                               'chave_beneficiario'=>$chave_beneficiario],
                //                 'recursive'=> -1];
                // $saved_count = $this->Sinistro->find('count',$conditions);
                
                $saved_count = 0;
                if($saved_count == 0){
                    #CREATE Sinistro
                    $data = [];
                    $data['id']='';
                    $data['cliente_id'] =  $row['SinistroProRobo']['cliente_id'];
                    #$data['subfatura_id'] =  $row['SinistroProRobo']['subfatura_id'];

                    

                    $data['cod_subfatura'] =  $row['SinistroProRobo']['num_contrato'];
                    if($operadora == 'PROMEDICA'){
                        #MAPEAR
                        #$data['cod_subfatura'] =  $row['SinistroProRobo']['cod_empresa'].'0'.$row['SinistroProRobo']['subs'].$row['SinistroProRobo']['dv'];
                    }

                    $data['chave_beneficiario'] =  $chave_beneficiario;
                    $data['matricula'] =  $row['SinistroProRobo']['matricula'];
                   # $data['beneficio_id'] =  $row['SinistroProRobo']['beneficio_id'];
                    $data['cod_grupo_familiar'] =  $row['SinistroProRobo']['cod_grupo_familiar'];


                    $attr = ['numero_carteira'=>$row['SinistroProRobo']['numero_carteira'],'matricula'=>$row['SinistroProRobo']['matricula']];
                    $data['numero_carteira_titular'] =  $this->busca_carteira_titular($row['SinistroProRobo']['numero_carteira_titular'],$operadora,$attr);
                    


                    $data['numero_carteira_titular_complemento'] =  $row['SinistroProRobo']['numero_carteira_titular_complemento'];
                    $data['cpf_titular'] =  $row['SinistroProRobo']['cpf_titular'];
                    $data['nome_titular'] =  $row['SinistroProRobo']['nome_titular'];


                    #$data['beneficiario_id'] =  $row['SinistroProRobo']['beneficiario_id'];
                    

                    $attr = ['numero_carteira_complemento'=>$row['SinistroProRobo']['numero_carteira_complemento'],'matricula'=>$row['SinistroProRobo']['matricula'],'sequencia'=>''];
                    $data['numero_carteira'] =  $this->busca_carteira($row['SinistroProRobo']['numero_carteira'],$operadora,$attr);


                    $data['numero_carteira_complemento'] =  $row['SinistroProRobo']['numero_carteira_complemento'];
                    $data['cpf_beneficiario'] =  $row['SinistroProRobo']['cpf_beneficiario'];
                    $data['nome_beneficiario'] =  $row['SinistroProRobo']['nome_beneficiario'];

                    $data['sexo'] =  $this->busca_sexo($row['SinistroProRobo']['sexo'],$operadora);


                    $attr = ['numero_carteira_complemento'=>$row['SinistroProRobo']['numero_carteira_complemento'],
                            'ds_parentesco'=>$row['SinistroProRobo']['ds_parentesco'],
                            'nome_titular'=>$row['SinistroProRobo']['nome_titular'],
                            'nome_beneficiario'=>$row['SinistroProRobo']['nome_beneficiario']];
                    $data['elegibilidade'] = $this->busca_elegibilidade($row['SinistroProRobo']['elegibilidade'],$operadora,$attr);
                   
                   
                    $data['data_nascimento'] =  $row['SinistroProRobo']['data_nascimento'];
                    if($operadora == 'AMIL' && $row['SinistroProRobo']['ds_procedimento'] == 'PEONA'){
                        $data['data_nascimento'] =  $row['SinistroProRobo']['data_pagamento'];
                    }

                    
                    $idade = $row['SinistroProRobo']['idade']; #OK
                    if(trim($row['SinistroProRobo']['idade']) == '' && $row['SinistroProRobo']['data_nascimento'] != ''){
                        $idade = $this->Funcoes->calcula_idade($row['SinistroProRobo']['data_nascimento']);
                    }
                    $data['idade']= $idade;

                   
                    #$data['parentesco_id'] =  $row['SinistroProRobo']['parentesco_id'];



                    $attr = ['nome_prestador'=>$row['SinistroProRobo']['nome_prestador'],
                            'tipo_sinistro'=>$row['SinistroProRobo']['tipo_sinistro'],
                            'tipo_servico_operadora'=>$row['SinistroProRobo']['tipo_servico_operadora']];
                    $data['tipo_reembolso'] =   $this->busca_tipo_reembolso($row['SinistroProRobo']['tipo_reembolso'],$operadora,$attr);



                    $data['nome_prestador'] =  $row['SinistroProRobo']['nome_prestador'];#ok
                    $data['cod_prestador'] =  $row['SinistroProRobo']['cod_prestador'];#ok
                    $data['cidade_prestador'] =  $row['SinistroProRobo']['cidade_prestador'];#ok
                    $data['uf_prestador'] =  $row['SinistroProRobo']['uf_prestador'];#ok
                    $data['cod_faixa_etaria_ans'] =  $this->busca_faixa_etaria_id($idade);#ok
                    #$data['plano_id'] =  $row['SinistroProRobo']['plano_id'];#ok
                    $data['cod_plano'] =  $row['SinistroProRobo']['plano_id'];#ok


                    $data['ds_plano'] =  $row['SinistroProRobo']['ds_plano'];#ok
                    if($operadora == 'AMIL' && $row['SinistroProRobo']['ds_procedimento'] == 'PEONA'){
                        $data['ds_plano'] =  'PEONA';#ok
                    }

                    $data['nro_conta_medica'] =  $row['SinistroProRobo']['nro_conta_medica'];#ok
                    #$data['procedimento_id'] =  $row['SinistroProRobo']['procedimento_id'];
                    $data['cod_procedimento'] =  $row['SinistroProRobo']['cod_procedimento'];#ok
                    $data['ds_procedimento'] =  $row['SinistroProRobo']['ds_procedimento'];#ok
                    $data['qtde_procedimento'] = $row['SinistroProRobo']['qtde_procedimento']; #ok


                    $data['tipo_servico'] = $this->busca_tipo_servico($row['SinistroProRobo']['tipo_servico'], $operadora, $row['SinistroProRobo']['tipo_servico_operadora']);#ok

                    $data['conta_medica'] =  $row['SinistroProRobo']['conta_medica'];
                    $data['valor'] =  $row['SinistroProRobo']['valor'];
                    $data['valor_coparticipacao'] =  $row['SinistroProRobo']['valor_coparticipacao'];
                    $data['senha'] =  $row['SinistroProRobo']['senha'];
                    $data['nr_autorizacao'] =  $row['SinistroProRobo']['nr_autorizacao'];
                    $data['prestador_tipo'] =  $row['SinistroProRobo']['prestador_tipo'];
                    $data['local_atendimento'] =  $row['SinistroProRobo']['local_atendimento'];
                    $data['cod_especialidade'] =  $row['SinistroProRobo']['cod_especialidade'];

                    $ds_especialidade = trim($row['SinistroProRobo']['ds_especialidade']);
                    $ds_especialidade = str_replace(' ','',$ds_especialidade);


                    $data['ds_especialidade'] =  $ds_especialidade;
                    $data['data_evento'] =  $row['SinistroProRobo']['data_evento'];
                    $data['data_pagamento'] =  $row['SinistroProRobo']['data_pagamento'];
                    $data['cid'] =  $row['SinistroProRobo']['cid'];
                    $data['ds_cid'] =  $row['SinistroProRobo']['ds_cid'];
                    $data['operadora'] =  $row['SinistroProRobo']['operadora'];
                    $data['tipo_servico_operadora'] =  $row['SinistroProRobo']['tipo_servico_operadora'];


                    $attr = ['cod_atendimento'=>''];
                    $data['tipo_internacao'] = $this->busca_tipo_internacao($ds_especialidade,$operadora,$attr); 


                    $data['tipo_entrada'] =  $row['SinistroProRobo']['tipo_entrada'];

                    // $data['campo_1_coluna'] =  $row['SinistroProRobo']['campo_1_coluna'];
                    // $data['campo_1_dado'] =  $row['SinistroProRobo']['campo_1_dado'];
                    // $data['campo_2_coluna'] =  $row['SinistroProRobo']['campo_2_coluna'];
                    // $data['campo_2_dado'] =  $row['SinistroProRobo']['campo_2_dado'];
                    // $data['campo_3_coluna'] =  $row['SinistroProRobo']['campo_3_coluna'];
                    // $data['campo_3_dado'] =  $row['SinistroProRobo']['campo_3_dado'];
                    // $data['campo_4_coluna'] =  $row['SinistroProRobo']['campo_4_coluna'];
                    // $data['campo_4_dado'] =  $row['SinistroProRobo']['campo_4_dado'];


                    $data['competencia_robo'] =  $row['SinistroProRobo']['competencia'];#ok 
                    $data['ds_parentesco'] =  $row['SinistroProRobo']['ds_parentesco'];#OK


                    $data['cod_origem_prestador'] =  $row['SinistroProRobo']['cod_origem_prestador'];#ok
                    $data['num_contrato'] =  $row['SinistroProRobo']['num_contrato'];#ok
                    $data['nome_contrato'] =  $row['SinistroProRobo']['nome_contrato'];#ok
                    $data['apolice'] =  $row['SinistroProRobo']['apolice'];
                    $data['lotacao_titular'] =  $row['SinistroProRobo']['lotacao_titular'];
                    $data['endereco_titular'] =  $row['SinistroProRobo']['endereco_titular'];
                    $data['titular_cidade'] =  $row['SinistroProRobo']['titular_cidade'];
                    $data['titular_uf'] =  $row['SinistroProRobo']['titular_uf'];
                    $data['titular_cep'] =  $row['SinistroProRobo']['titular_cep'];
                    $data['procedimento_tipo_tabela'] =  $row['SinistroProRobo']['procedimento_tipo_tabela'];
                    $data['codigo_beneficio'] =  $row['SinistroProRobo']['codigo_beneficio'];
                    $data['data_final_servico'] =  $row['SinistroProRobo']['data_final_servico'];
                    $data['co_particiacao_perc'] =  $row['SinistroProRobo']['co_particiacao_perc'];
                    $data['tipo_sinistro'] =  $row['SinistroProRobo']['tipo_sinistro'];
                    $data['atendimento_emergencia'] =  $row['SinistroProRobo']['atendimento_emergencia'];
                    $data['tipo_paciente'] =  $row['SinistroProRobo']['tipo_paciente'];
                    $data['provedor_codigo'] =  $row['SinistroProRobo']['provedor_codigo'];
                    $data['estipulante_cnpj'] =  $row['SinistroProRobo']['estipulante_cnpj'];
                    $data['estipulante_endereco'] =  $row['SinistroProRobo']['estipulante_endereco'];
                    $data['estipulante_cidade'] =  $row['SinistroProRobo']['estipulante_cidade'];
                    $data['estipulante_uf'] =  $row['SinistroProRobo']['estipulante_uf'];
                    $data['estipulante_cep'] =  $row['SinistroProRobo']['estipulante_cep'];
                    $data['origem_pagamento'] =  $row['SinistroProRobo']['origem_pagamento'];
                    $data['tabela_grupo'] =  $row['SinistroProRobo']['tabela_grupo'];

                    $data['codigo_grupo'] =  $row['SinistroProRobo']['codigo_grupo'];
                    if($operadora == 'SEGUROS UNIMED' && $row['SinistroProRobo']['codigo_beneficio'] != '' && $row['SinistroProRobo']['codigo_grupo'] == ''){
                        $data['codigo_grupo'] =  $row['SinistroProRobo']['codigo_beneficio'];
                    }

                    $data['descricao_grupo'] =  $row['SinistroProRobo']['descricao_grupo'];
                    $data['codigo_subgrupo'] =  $row['SinistroProRobo']['codigo_subgrupo'];
                    $data['descricao_subgrupo'] =  $row['SinistroProRobo']['descricao_subgrupo'];
                    $data['data_alta'] =  $row['SinistroProRobo']['data_alta'];

                    $data['tipo_doc_registro'] =  $row['SinistroProRobo']['tipo_doc_registro']; #INEXISTENTE
                    $data['uf_registro'] =  $row['SinistroProRobo']['uf_registro'];
                    $data['numero_registro'] =  $row['SinistroProRobo']['numero_registro'];
                    #$data['cnpj_prestador'] =  $row['SinistroProRobo']['cnpj_prestador']; #INEXISTENTE

                    $data['nome_hash'] =  md5($row['SinistroProRobo']['nome_beneficiario']);
                    $data['nome_prestador_hash'] =  md5($row['SinistroProRobo']['nome_prestador']);

                    $data['usuario_id'] =  1;
                    $data['data_cadastro'] =  $row['SinistroProRobo']['data_cadastro'];
                    $data['status'] =  1;
                    $data['data_cadastro_robo'] = $row['SinistroProRobo']['data_cadastro'];#ok
                    $data['data_cadastro'] = $data_cadastro;
                    
                    $this->Sinistro->create();
                    if(!$this->Sinistro->save($data)){
                        $error['Sinistro'][$operadora][] = $row['SinistroProRobo']['cliente_id'];
                    }

                    // krumo($data);
                    // krumo($error);
                    // exit;
                }
               
            }
            if(count($error)>0){
                #LOG ERROR
                var_dump($error);
                
            }
        }

        $this->DwRoboAtualizacao->create();
        $data_dw_att = [];
        $data_dw_att['id'] = '';
        $data_dw_att['tipo'] = 'Sinistro';
        $data_dw_att['ultima_atualizacao'] = $data_cadastro;
        $data_dw_att['status'] = 1;
        $data_dw_att['data_cadastro'] = date('Y-m-d H:i:s');
        $this->DwRoboAtualizacao->save($data_dw_att);

        #krumo($error);

        return true;
        
    }
    
    
}