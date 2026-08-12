<?php
App::uses('AppModel', 'Model');
/**
 * FaturamentoProRobo Model
 *
 * @property FaturamentoProRobo $FaturamentoProRobo
 */
class FaturamentoProRobo extends AppModel {
    public $useDbConfig = 'default_pro_robo';
    public $useTable = 'faturamento';
    
    public $validate = array(
      
    );
    
    
    #EXTERNO / auto relacionamento
    public $hasMany = array();
    
    #INTERNO
    public $belongsTo = array(
     
    );


}
