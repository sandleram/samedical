<?php
App::uses('AppModel', 'Model');
/**
 * Sinistro Model
 *
 * @property Sinistro $Sinistro
 */
class Sinistro extends AppModel {
    public $useDbConfig = 'default';
    public $useTable = 'sinistro';
    
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
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Cliente' => array(
            'className' => 'Cliente',
            'foreignKey' => 'cliente_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Plano' => array(
            'className' => 'Plano',
            'foreignKey' => 'plano_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Procedimento' => array(
            'className' => 'Procedimento',
            'foreignKey' => 'procedimento_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'SubFatura' => array(
            'className' => 'SubFatura',
            'foreignKey' => 'subfatura_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Importacao' => array(
            'className' => 'Importacao',
            'foreignKey' => 'importacao_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Beneficio' => array(
            'className' => 'Beneficio',
            'foreignKey' => 'beneficio_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );


}
