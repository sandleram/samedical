<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
//	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
//	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));


        #APROVED
//        Router::connect('/', array('controller' => 'home', 'action' => 'index', 'admin' => false));
//	Router::connect('/admin', array('controller' => 'home', 'action'=> 'index', 'admin'=> true));#CONTROLE DE LOGIN
	Router::connect('/admin',                   array('controller' => 'usuario',   'action' => 'login',             'admin' => false));#CONTROLE DE LOGIN
	Router::connect('/',                        array('controller' => 'usuario',   'action' => 'login',             'admin' => false));#CONTROLE DE LOGIN
 //       Router::connect('/cadastrese/*',            array('controller' => 'usuario',   'action' => 'cadastrese',        'admin' => false));
 //       Router::connect('/dashboard/*',             array('controller' => 'home',      'action' => 'dashboard',         'admin' => false));
//	Router::connect('/admin/import',            array('controller' => 'import',    'action' => 'index',             'admin' => true));
        
        #PROVA ENEM
//        Router::connect('/enem/*',           array('controller' => 'prova',     'action' => 'aluno_cadastro',   'admin' => false));
//        Router::connect('/enem/prova/',      array('controller' => 'prova',     'action' => 'aluno_cadastro',    'admin' => false)); #FAZER
//        Router::connect('/vestibular',      array('controller' => 'prova',     'action' => 'vestibular',       'admin' => false));
//        Router::connect('/vestibular/prova',array('controller' => 'prova',     'action' => 'vestibular_prova', 'admin' => false));
//	Router::connect('/enem/prova/*',      array('controller' => 'prova',     'action' => 'enem',             'admin' => false));
        
        
        

//        #FE - MENU
//        Router::connect('/resorts',                 array('controller' => 'pagina', 'action' => 'resorts',          'admin' => false));
//        Router::connect('/resort/*',                array('controller' => 'pagina', 'action' => 'resort',           'admin' => false));
//        #LINKS RODAPÉ
//        Router::connect('/sobre',                   array('controller' => 'pagina', 'action' => 'sobre',            'admin' => false));
//        Router::connect('/mais_contatos',           array('controller' => 'pagina', 'action' => 'mais_contatos',    'admin' => false));
//        
// Router::mapResources(array('Ws'));
// Router::parseExtensions('json');
        
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */

	#Router::mapResources('rest');
	Router::mapResources('rest', array(
		'connectOptions' => array(
			'routeClass' => 'ApiRoute',
		)
	));
	Router::resourceMap(array(
		array('action' => 'bi_proativa_beneficiarios', 'method' => 'GET', 'id' => false),
		array('action' => 'bi_proativa_faturamentos', 'method' => 'GET', 'id' => false),
		array('action' => 'bi_proativa_sinistros', 'method' => 'GET', 'id' => false),
		array('action' => 'index', 'method' => 'GET', 'id' => false),
		array('action' => 'view', 'method' => 'GET', 'id' => true),
		array('action' => 'add', 'method' => 'POST', 'id' => false),
		array('action' => 'edit', 'method' => 'PUT', 'id' => true),
		array('action' => 'delete', 'method' => 'DELETE', 'id' => true),
		array('action' => 'update', 'method' => 'POST', 'id' => true)
	));
	Router::parseExtensions();
	
	// Router::mapResources('rest', array(
	// 	'connectOptions' => array(
	// 		'routeClass' => 'ApiRoute',
	// 	)
	// ));
	// Router::resourceMap(array(
	// 	array('action' => 'index', 'method' => 'GET', 'id' => false),
	// 	array('action' => 'view', 'method' => 'GET', 'id' => true),
	// 	array('action' => 'add', 'method' => 'POST', 'id' => false),
	// 	array('action' => 'edit', 'method' => 'PUT', 'id' => true),
	// 	array('action' => 'delete', 'method' => 'DELETE', 'id' => true),
	// 	array('action' => 'update', 'method' => 'POST', 'id' => true)
	// ));
	// Router::parseExtensions();
	
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
