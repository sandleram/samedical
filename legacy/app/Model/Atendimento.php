<?php

App::uses('AppModel', 'Model');

/**
 * Usuario Model
 *
 * @property Status $Status
 * @property Funcionario $Funcionario
 * @property Modulo $Modulo
 */
class Atendimento extends AppModel {

    public $useTable = 'atendimento';
//    public $recursive = 2;
    
    public $belongsTo = array(
        'UsuarioResponsavel' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_id',
            'conditions' => '',
            'fields' => 'nome',
            'order' => ''
        ),
        'Beneficiario' => array(
            'className' => 'Beneficiario',
            'foreignKey' => 'beneficiario_id',
            'conditions' => '',
            'fields' => 'nome,cpf,cliente_id',
            'order' => ''
        )
    );
    public $hasMany = array(
        'Agendamento' => array(
            'className' => 'Agendamento',
            'foreignKey' => 'atendimento_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    
}
