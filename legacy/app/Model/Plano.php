<?php
App::uses('AppModel', 'Model');
/**
 * Plano Model
 *
 * @property Plano $Plano
 */
class Plano extends AppModel {
    
    public $useTable = 'plano';
    
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
        'Operadora' => array(
            'className' => 'Operadora',
            'foreignKey' => 'operadora_id',
            'conditions' => '',
            'fields' => 'nome',
            'order' => ''
        ),
        'TipoBeneficio' => array(
            'className' => 'TipoBeneficio',
            'foreignKey' => 'tipo_beneficio_id',
            'conditions' => '',
            'fields' => 'descricao',
            'order' => ''
        ),
        'Cliente' => array(
            'className' => 'Cliente',
            'foreignKey' => 'cliente_id',
            'conditions' => '',
            'fields' => 'nome',
            'order' => ''
        ),


    );


}
