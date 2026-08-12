<?php
App::uses('AppModel', 'Model');
/**
 * SinistroProRobo Model
 *
 * @property SinistroProRobo $SinistroProRobo
 */
class SinistroProRobo extends AppModel {
    public $useDbConfig = 'default_pro_robo';
    public $useTable = 'sinistro';
    
    public $validate = array(
        
    );
    
    
    
    
    #EXTERNO / auto relacionamento
    public $hasMany = array();
    
    #INTERNO
    public $belongsTo = array(
       
    );


}
