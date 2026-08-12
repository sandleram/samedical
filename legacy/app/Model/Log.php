<?php
App::uses('AppModel', 'Model');

/**
 * Log Model
 *
 */
class Log extends AppModel {
    public $useTable = 'log';
    
    
    public $belongsTo = array(
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
}
