<?php

App::uses('AppModel', 'Model');

/**
 * Importacao Model
 *
 */
class ImportacaoNova extends AppModel
{

    public $useTable = 'importacao_nova';

    public $belongsTo = array(
        'Cliente' => array(
            'className' => 'Cliente',
            'foreignKey' => 'cliente_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    public $hasMany = array();
}
