<?php

App::uses('AppModel', 'Model');

/**
 * Cliente Model
 *
 */
class Cliente extends AppModel {

    public $useTable = 'cliente';

    /**
     * Validation rules
     *
     * @var array
     */
   
     #EXTERNO / auto relacionamento
    public $hasMany = array();
    
    #INTERNO
    public $belongsTo = array(
        'GrupoEmpresarial' => array(
            'className' => 'GrupoEmpresarial',
            'foreignKey' => 'grupo_empresarial_id',
            'conditions' => '',
            'fields' => 'id,nome,status',
            'order' => ''
        // ),['GrupoEmpresarial.nome'=>'ASC']
        // 'Usuario' => array(
        //     'className' => 'Usuario',
        //     'foreignKey' => 'usuario_id',
        //     'conditions' => '',
        //     'fields' => '',
        //     'order' => ''
        )
        
        
    );
    
    public $validate = array(
        'nome' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            )
        )
    );


    
    
}
