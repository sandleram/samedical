<?php

App::uses('AppModel', 'Model');

/**
 * BeneficioPrevidenciario Model
 *
 */
class BeneficioPrevidenciario extends AppModel {

    public $useTable = 'beneficio_previdenciario';
//    public $recursive = 2;
    
    public $belongsTo = array(
        'Importacao' => array(
            'className' => 'Importacao',
            'foreignKey' => 'importacao_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $hasMany = array();
    
    
}
