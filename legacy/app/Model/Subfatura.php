<?php
App::uses('AppModel', 'Model');
/**
 * SubFatura Model
 *
 * @property SubFatura $SubFatura
 */
class Subfatura extends AppModel {
    
    public $useTable = 'subfatura';
    
    public $validate = array(
        'descricao' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        )
    );
    
    
    #EXTERNO / auto relacionamento
    public $hasMany = array(
       'EmpresaSubfatura' => array(
            'className' => 'EmpresaSubfatura',
            'foreignKey' => 'subfatura_id',
            'conditions' => array(),
            'fields' => '',
            'order' => '',
        )
    );
    
    #INTERNO
    public $belongsTo = array(
        'Beneficio' => array(
            'className' => 'Beneficio',
            'foreignKey' => 'beneficio_id',
            'conditions' => array(),
            'fields' => 'cliente_id,descricao',
            'order' => '',
        )
        
        
    );

    
}
