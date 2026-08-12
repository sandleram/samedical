<?php

App::uses('AppModel', 'Model');

/**
 * Beneficiario Model
 *
 */
class Beneficiario extends AppModel {
    public $useDbConfig = 'default';
    public $useTable = 'beneficiario';


    public $hasMany = array(
        'Afastado' => array(
            'className' => 'Afastado',
            'foreignKey' => 'beneficiario_id',
            'conditions' => ['status < 2'],
            'fields' => '',
            'order' => array('data_cadastro'=>'desc','id'=>'desc')
        ),
        'BeneficioPrevidenciario' => array(
            'className' => 'BeneficioPrevidenciario',
            'foreignKey' => 'beneficiario_id',
            'conditions' => '',
            'fields' => '',
            'order' => array('data_cadastro'=>'desc','id'=>'desc')
        ),
        'Absenteismo' => array(
            'className' => 'Absenteismo',
            'foreignKey' => 'beneficiario_id',
            'conditions' => '',
            'fields' => '',
            'order' => array('data_cadastro'=>'desc','id'=>'desc')
        ),
        'Atendimento' => array(
            'className' => 'Atendimento',
            'foreignKey' => 'beneficiario_id',
            'conditions' => array('status'=>1),
            'fields' => '',
            'order' => array('data_cadastro'=>'desc')
        )   
    );
 
    public $belongsTo = array(
        'Cliente' => array(
            'className' => 'Cliente',
            'foreignKey' => 'cliente_id',
            'conditions' => '',
            'fields' => 'nome',
            'order' => ''
        ),
        'UsuarioCriador' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_criador_id',
            'conditions' => '',
            'fields' => 'nome',
            'order' => ''
        ),
        'UsuarioAtualizacao' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_atualizacao_id',
            'conditions' => '',
            'fields' => 'nome',
            'order' => ''
        ),
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => array(),
            'fields' => 'Empresa.nome,Empresa.cnpj',
            'order' => '',
        )
        

    );


    
    
}
