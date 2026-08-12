<?php
App::uses('AppModel', 'Model');
/**
 * UsuarioCliente Model
 *
 * @property UsuarioCliente $UsuarioCliente
 */
class UsuarioCliente extends AppModel {
    
    public $useTable = 'usuario_cliente';
    
    
    
    #EXTERNO / auto relacionamento
    public $hasMany = array();
    
    #INTERNO
    public $belongsTo = array(
        'Cliente' => array(
            'className' => 'Cliente',
            'foreignKey' => 'cliente_id',
            'conditions' => '',
            'fields' => 'id,nome,grupo_empresarial_id,status',
            'order' => ''
        ),
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_id',
            'conditions' => '',
            'fields' => 'id,usuario,nome,perfil_id',
            'order' => ''
        )
        
        
    );
}
