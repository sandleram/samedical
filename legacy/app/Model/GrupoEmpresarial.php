<?php
App::uses('AppModel', 'Model');
/**
 * GrupoEmpresarial Model
 *
 * @property GrupoEmpresarial $GrupoEmpresarial
 */
class GrupoEmpresarial extends AppModel {
    
    public $useTable = 'grupo_empresarial';
    
    public $validate = array(
        'nome' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty')
            ),
        )
    );
    
    
    
    
    #EXTERNO / auto relacionamento
    public $hasMany = array();
    
    #INTERNO
    public $belongsTo = array();
}
