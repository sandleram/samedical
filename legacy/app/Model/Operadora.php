<?php
App::uses('AppModel', 'Model');
/**
 * Operadora Model
 *
 * @property Operadora $Operadora
 */
class Operadora extends AppModel {
    
    public $useTable = 'operadora';
    
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
    public $belongsTo = array();
}
