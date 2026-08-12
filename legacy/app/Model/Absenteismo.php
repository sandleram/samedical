<?php

App::uses('AppModel', 'Model');

/**
 * Absenteismo Model
 *
 */
class Absenteismo extends AppModel {

    public $useTable = 'absenteismo';
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
