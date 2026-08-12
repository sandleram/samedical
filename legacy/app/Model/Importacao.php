<?php

App::uses('AppModel', 'Model');

/**
 * Importacao Model
 *
 */
class Importacao extends AppModel {

    public $useTable = 'importacao';

    public $belongsTo = array(
        'Cliente' => array(
            'className' => 'Cliente',
            'foreignKey' => 'cliente_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
 
    public $hasMany = array(

    );
    
}
