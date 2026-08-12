<?php
App::uses('AppModel', 'Model');
/**
 * Banner Model
 *
 * @property Usuario $Usuario
 */
class Empresa extends AppModel {
    
    public $useTable = 'empresa';

    public $virtualFields = array(
        'razao_cnpj' => 'CONCAT(Empresa.razao_social, " (", Empresa.cnpj, ")")'
    );
    
    public $validate = array(
        'nome' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        )
    );
    
    
    
    
    #EXTERNO / auto relacionamento
    public $hasMany = array(
        'EmpresaSubfatura' => array(
            'className' => 'EmpresaSubfatura',
            'foreignKey' => 'empresa_id',
            'conditions' => array(),
            'fields' => '',
            'order' => '',
        )
    );
    
    #INTERNO
    public $belongsTo = array(
        'UsuarioCriador' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_criador_id',
            'conditions' => '',
            'fields' => 'nome',
            'order' => ''
        )
    );
}
