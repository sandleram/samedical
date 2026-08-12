<?php

App::uses('AppModel', 'Model');

/**
 * Beneficio Model
 *
 */
class Beneficio extends AppModel {

    public $useTable = 'beneficio';
    public $virtualFields = array('descricao_breakeven' => 'CONCAT(Beneficio.descricao, " (", Beneficio.breakeven,"%)")');

    /**
     * Validation rules
     *
     * @var array
     */
   
    
    public $validate = array(
        'descricao' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            )
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
            'fields' => 'nome,grupo_empresarial_id',
            'order' => ''
        ),


    );
    
}
