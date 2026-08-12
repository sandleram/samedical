<?php
App::uses('AppModel', 'Model');
/**
 * Faturamento Model
 *
 * @property Faturamento $Faturamento
 */
class Faturamento extends AppModel {
    public $useDbConfig = 'default';
    public $useTable = 'faturamento';
    
    public $validate = array(
      
    );
    
    
    #EXTERNO / auto relacionamento
    public $hasMany = array();
    
    #INTERNO
    public $belongsTo = array(
     
    );


}
