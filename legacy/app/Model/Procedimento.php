<?php
App::uses('AppModel', 'Model');
/**
 * Procedimento Model
 *
 * @property Procedimento $Procedimento
 */
class Procedimento extends AppModel {
    
    public $useTable = 'procedimento';
    
    
    #EXTERNO / auto relacionamento
    public $hasMany = array();
    
    #INTERNO
    public $belongsTo = array(
        'SinistroEvento' => array(
            'className' => 'SinistroEvento',
            'foreignKey' => 'sinistro_evento_id',
            'conditions' => '',
            'fields' => 'id, descricao',
            'order' => ''
        ),
        'Operadora' => array(
            'className' => 'Operadora',
            'foreignKey' => 'operadora_id',
            'conditions' => '',
            'fields' => 'nome',
            'order' => ''
        )
    );


}
