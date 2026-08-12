<?php
/**
 * AppShell file
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Shell', 'Console');
App::uses('CakeEmail', 'Network/Email');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class AppShell extends Shell {
	var $components = array('Session', 'Cookie', 'Auth', 'Funcoes');
	
	public function envio_email($toEmail, $subject, $msg) {
        $Email = new CakeEmail();
        $Email->config('default');
        $Email->emailFormat('html');
        $Email->template('cadastro_novo')->viewVars(array('msg'=>$msg));
        $Email->to($toEmail);
        $Email->subject($subject);
        $return = $Email->send('default');
        return true;
    }
}
