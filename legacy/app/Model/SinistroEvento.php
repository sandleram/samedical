<?php
App::uses('AppModel', 'Model');
/**
 * SinistroEvento Model
 *
 * @property SinistroEvento $SinistroEvento
 */
class SinistroEvento extends AppModel {
    
    public $useTable = 'sinistro_evento';
    
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
