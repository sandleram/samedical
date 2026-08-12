<?php

App::uses('AppModel', 'Model');

/**
 * Absenteismo Model
 *
 */
class Bi extends AppModel {

    public $useTable = 'bi';
//    public $recursive = 2;
    
    public $belongsTo = array(
        'GrupoEmpresarial' => array(
			'className' => 'GrupoEmpresarial',
			'foreignKey' => 'grupo_empresarial_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
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
