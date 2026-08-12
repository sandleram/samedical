<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('Model', 'Model');
App::uses('CakeSession', 'Model/Datasource');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
//    public $actsAs = array('Timestamp');
//    public $actsAs = array('DataPtBr');
//    public $permissionsArr = array(1);//DEFINE array admins
    
    
    function __construct($id=false, $table=null, $ds=null)
    {
//        $id_company = CakeSession::read('Auth.User.Companies.id');
//        $arrMaster = array('Companies', 'Company', 'Users', 'User', 'Profiles', 'Profile', 'Logs', 'Log', 'Solicitations', 'Solicitation', 'Statuses', 'Status', 'Statuse');
//        if(!in_array($id['class'], $arrMaster)):
//            if(!isset($id_company)):
//                header('Location: '.WEBROOT_ADMIN);
//                exit;
//            else:
//                $this->useDbConfig = 'company'.$id_company;
//            endif;
//        endif;
        parent::__construct($id, $table, $ds);
    }    
    
    
    /**
     * Binds the model to its associated models. This method optionally takes an array of model names
     * and contains the binding to any models given in the an array parameter.
     *
     * @param array $models An array of model names that the bind will be restricted to.
     */
//    public function bind($associations = null, $models = null) { //USAGE CANDIDATO(CONTROLLER) AND EXPERIENCIA(MODEL)
//        krumo($associations, $models);exit;
//        $result = $this->bindModel($associations);
//        if (!empty($models)) {
//            $this->contain($models);
//        }
//            return $result;
//    }
   

    
}
