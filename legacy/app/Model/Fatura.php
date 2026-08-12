<?php
App::uses('AppModel', 'Model');
/**
 * Fatura Model
 *
 * @property Fatura $Fatura
 */
class Fatura extends AppModel {
    
    public $useTable = 'fatura';
    
    public $validate = array(
        'descricao' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        )
    );
    
    
    #EXTERNO / auto relacionamento
    public $hasMany = array();
    
    #INTERNO
    public $belongsTo = array(
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
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
        ),
        'Importacao' => array(
            'className' => 'Importacao',
            'foreignKey' => 'importacao_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'SubFatura' => array(
            'className' => 'SubFatura',
            'foreignKey' => 'subfatura_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Beneficio' => array(
            'className' => 'Beneficio',
            'foreignKey' => 'beneficio_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );


}
