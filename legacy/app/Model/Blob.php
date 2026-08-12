<?php

App::uses('AppModel', 'Model');

/**
 * Blob Model
 *
 */
class Blob extends AppModel {

    public $useTable = 'blob';
//    public $recursive = 2;
    
    public $belongsTo = array();
    public $hasMany = array();
    
    
}
