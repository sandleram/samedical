<?php

App::uses('AppModel', 'Model');

/**
 * Usuario Model
 *
 * @property Status $Status
 * @property Funcionario $Funcionario
 * @property Modulo $Modulo
 */
class Usuario extends AppModel {

    public $useTable = 'usuario';

    /**
     * Validation rules
     *
     * @var array
     */
   
    
    public $validate = array(
        'nome' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        )
    );

   
    #CRIANDO AUTO RELACIONAMENTO
    public $hasMany = array(
        'UsuarioCliente' => array(
            'className' => 'UsuarioCliente',
            'foreignKey' => 'usuario_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'UsuarioBi' => array(
            'className' => 'UsuarioBi',
            'foreignKey' => 'usuario_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    public $belongsTo = array(
        'UsuarioCriador' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_criador_id',
            'conditions' => '',
            'fields' => 'nome',
            'order' => ''
        ),
        'Perfil' => array(
            'className' => 'Perfil',
            'foreignKey' => 'perfil_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => 'nome,descricao,tipo',
            'order' => ''
        ),
        'GrupoEmpresarial' => array(
            'className' => 'GrupoEmpresarial',
            'foreignKey' => 'grupo_empresarial_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    #liga uma tabela de ligação
//    public $hasAndBelongsToMany = array(
//        'Endereco' => array(
//            'className' => 'Cidade',
//        )
//    );
  
}
