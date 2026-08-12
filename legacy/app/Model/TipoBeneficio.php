<?php
App::uses('AppModel', 'Model');
/**
 * TipoBeneficio Model
 *
 * @property TipoBeneficio $TipoBeneficio
 */
class TipoBeneficio extends AppModel {
    
    public $useTable = 'tipo_beneficio';
    
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
