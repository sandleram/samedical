<?php
App::uses('AppModel', 'Model');
/**
 * Mensagem Model
 *
 * @property Mensagem $Mensagem
 */
class Mensagem extends AppModel {
    
    public $useTable = 'mensagem';
    
    public $validate = array(
//        'nome' => array(
//            'notEmpty' => array(
//                'rule' => array('notEmpty'),
//            ),
//        )
    );
    
    
    
    #EXTERNO / auto relacionamento
    public $hasMany = array();
    
    #INTERNO
    public $belongsTo = array(
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => 'nome,telefone',
            'order' => ''
        )
    );
}
