<?php

App::uses('AppModel', 'Model');

/**
 * MhCriticoHistorico Model
 *
 */
class MhCriticoHistorico extends AppModel {
    public $useTable = 'mh_critico_historico';


    public $hasMany = array(
       
       
   );
 
    public $belongsTo = array(
        'MhCritico' => array(
            'className' => 'MhCritico',
            'foreignKey' => 'mh_critico_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    
    
    
}
