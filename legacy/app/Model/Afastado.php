<?php

App::uses('AppModel', 'Model');

/**
 * Usuario Model
 *
 * @property Status $Status
 * @property Funcionario $Funcionario
 * @property Modulo $Modulo
 */
class Afastado extends AppModel {

    public $useTable = 'afastado';
//    public $recursive = 2;
//    var $hasMany = array('AfastadoCurso');
    
    public $belongsTo = array(
        'Importacao' => array(
            'className' => 'Importacao',
            'foreignKey' => 'importacao_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Beneficiario' => array(
            'className' => 'Beneficiario',
            'foreignKey' => 'beneficiario_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    public $hasMany = array();
    
}
