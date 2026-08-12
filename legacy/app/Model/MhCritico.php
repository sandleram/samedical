<?php

App::uses('AppModel', 'Model');

/**
 * MhCritico Model
 *
 */
class MhCritico extends AppModel
{
    public $useTable = 'mh_critico';

    public $hasMany = array(
         'MhCriticoHistorico' => array(
            'className' => 'MhCriticoHistorico',
            'foreignKey' => 'mh_critico_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
        
    );
    public $belongsTo = array(
        'MhPrestador' => array(
            'className' => 'MhPrestador',
            'foreignKey' => 'mh_prestador_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'MhPrestadorPrincipal' => array(
            'className' => 'MhPrestador',
            'foreignKey' => 'mh_prestador_principal_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )

    );
}
