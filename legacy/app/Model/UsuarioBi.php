<?php

App::uses('AppModel', 'Model');

/**
 * UsuarioBi Model
 *
 */
class UsuarioBi extends AppModel {

    public $useTable = 'usuario_bi';
//    public $recursive = 2;
    
    public $belongsTo = array(
        'Bi' => array(
            'className' => 'Bi',
            'foreignKey' => 'bi_id',
            'conditions' => '',
            'fields' => 'titulo,subtitulo,link',
            'order' => ['ordem'=>'Desc']
        ),
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_id',
            'conditions' => '',
            'fields' => 'id,usuario,nome,perfil_id',
            'order' => ''
        )
    );

    public $hasMany = array();
    
    
}
