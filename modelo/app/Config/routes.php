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
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
 	Router::resourceMap( array(
        array( 'action' => 'index', 'method' => 'GET', 'id' => false ),
        array( 'action' => 'view', 'method' => 'GET', 'id' => true ),
        array( 'action' => 'add', 'method' => 'POST', 'id' => false),
        array( 'action' => 'save', 'method' => 'POST', 'id' => false),
        array( 'action' => 'edit', 'method' => 'PUT', 'id' => true ),
        array( 'action' => 'delete', 'method' => 'DELETE', 'id' => true ),
        array( 'action' => 'upload', 'method' => 'POST', 'id' => false ),
        array( 'action' => 'update', 'method' => 'POST', 'id' => false ),
        array( 'action' => 'login', 'method' => 'POST', 'id' => false ),
		array( 'action' => 'fetchProfile', 'method' => 'GET', 'id' =>false),
		array( 'action' => 'saveProfile', 'method' => 'PUT', 'id' =>false),
		array( 'action' => 'saveContact', 'method' => 'POST', 'id' =>false),
		array( 'action' => 'deleteContact', 'method' => 'POST', 'id' =>false),
		array( 'action' => 'saveCenter', 'method' => 'POST', 'id' =>false),
		array( 'action' => 'deleteCenter', 'method' => 'POST', 'id' =>false),
		array( 'action' => 'saveEvent', 'method' => 'POST', 'id' =>false),
		array( 'action' => 'saveActivity', 'method' => 'POST', 'id' =>false),
		array( 'action' => 'deleteEvent', 'method' => 'POST', 'id' =>false),
		array( 'action' => 'getActivity', 'method' => 'GET', 'id' =>false),
        array( 'action' => 'uploadProgram', 'method' => 'POST', 'id' => false )
    ) );
	
	Router::parseExtensions('json');
	
	require CAKE . 'Config' . DS . 'routes.php';
