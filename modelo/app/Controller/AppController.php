<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	 public $components = array('RequestHandler');
	 public function beforeFilter() {
		$excludeBeforeFilter = array('login');
		if (!in_array($this->action,$excludeBeforeFilter)){
			if(isset($this->request->data['cookie'])){
				$this->checksession($this->request->data['cookie']);
			}else if(isset($this->request->query['cookie'])){
				$this->checksession($this->request->query['cookie']);
			}else{
				$this->sendmsg("101");	
			}
		}
	}
	 private function checksession($session){
	 	$this->loadModel("User");
	 	if($user=$this->User->find('first',array('conditions' => array("User.key" => $session)))){
	 		$this->set('user' , $user);
	 	}
		else{
			$this->sendmsg("101");
		}
	 }
	 public function checkadmin($session){
	 	$this->loadModel("User");
	 	if($user=$this->User->find('first',array('conditions' => array("User.key" => $session,"User.rol"=>0)))){
	 		$this->set('user' , $user);
	 	}
		else{
			$this->sendmsg("103");
		}
	 }
	 public function sendmsg($code){
	 	$mensaje = array("mensaje"=>array("cuerpo" => $code));
	 	$this->set(array(
	            'mensaje' => $mensaje,  
	            '_serialize' => 'mensaje'
	    ));
		echo json_encode($mensaje);
		//$this->render('/Elements/jsonreturn');
		$this->_stop();
	 }
}
