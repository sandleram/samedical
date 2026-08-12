<?php

App::uses('AppModel', 'Model');

/**
 * Usuario Model
 *
 * @property Status $Status
 * @property Funcionario $Funcionario
 * @property Modulo $Modulo
 */
class EspecieBp extends AppModel {

    public $useTable = 'especie_bp';
    public $virtualFields = array(
        'nome_importacao' => 'CONCAT(EspecieBp.id, " - ", EspecieBp.nome)'
    );

//    public $recursive = 2;
    
    public $belongsTo = array();
    public $hasMany = array();
    
    
}
