<?php

App::uses('AppModel', 'Model');

/**
 * Perfil Model
 *
 */
class Perfil extends AppModel {
    public $useTable = 'perfil';
    
    public $hasMany = array(
        'PerfilModulo' => array(
            'className' => 'PerfilModulo',
            'foreignKey' => 'perfil_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
}
