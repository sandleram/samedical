<?php
App::uses('AppModel', 'Model');
/**
 * Banner Model
 *
 * @property EmpresaSubfatura $EmpresaSubfatura
 */
class EmpresaSubfatura extends AppModel {
    
    public $useTable = 'empresa_subfatura';
    
    public $validate = array(
        'empresa_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
        'subfatura_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        )
    );
    

    #INTERNO
    public $belongsTo = array(
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => 'nome',
            'order' => ''
        ),
        'Subfatura' => array(
            'className' => 'Subfatura',
            'foreignKey' => 'subfatura_id',
            'conditions' => '',
            'fields' => 'descricao',
            'order' => ''
        )
    );
}
