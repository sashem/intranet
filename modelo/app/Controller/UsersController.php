<?php
class UsersController extends AppController {
    public $helpers = array ('Html','Form');
	public $components = array('RequestHandler','Security' => array('csrfUseOnce' => false));
	//App::import('Model', 'MyModel');
    //$my_model = ClassRegistry::init('MyModel');
    public function beforeFilter() {
        parent::beforeFilter();
		$this->Security->unlockedActions = array('ajaxAction');
		$this->Security->csrfCheck = false;
		$this->Security->validatePost = false;
		$includeBeforeFilterAdmin = array('add','index');
		if (in_array($this->action,$includeBeforeFilterAdmin)){
			if(isset($this->request->data['cookie'])){
				parent::checkadmin($this->request->data['cookie']);
			}else if(isset($this->request->query['cookie'])){
				parent::checkadmin($this->request->query['cookie']);
			}else{
				parent::sendmsg("101");	
			}
		}        
    }
    public function index(){
        $users=$this->User->find('all',array('contain'=>array("User"),'fields' => array('id', 'username', 'rol','Profile.nombres','Profile.apellidos')));
		$this->set(array(
            'users' => $users,
            '_serialize' => 'users'
        ));
    }
    public function view($id) {

    }
	public function login(){
		$data = $this->request->data;
		if(count($data)==0){
			parent::sendmsg("100");
		}
		if(!isset($data["User"]["username"])||!isset($data["User"]["password"])){
			parent::sendmsg("100");
		}
		if($data["User"]["username"]=="" || $data["User"]["password"]==""){
			parent::sendmsg("100");
		}
		
		$password=Security::cipher($data["User"]["password"],$data["User"]["username"]);
		$data["User"]["password"]=$password;
		$nusers=$this->User->find('count');
		
		if($nusers==0){
			if($this->User->save($data)){	}
			else{
				$this->_stop();	
			}
		}

		if($user=$this->User->find('first',array('conditions' => array('User.username' => $data["User"]["username"], 'User.password' => $password)))){
			$user["User"]["key"] = Security::generateAuthKey();
			$this->User->save($user);
		}else{ parent::sendmsg("102");}
		
		$this->set(array(
            'user' => $user,  
            '_serialize' => 'user'
        ));
	}
	public function fetchProfile(){
		$user=$this->viewVars['user'];
		//$this->User->Profile->Member->bindModel(array('hasOne'=>array("Proyect"=>array("conditions"=>array("Proyect.id = Member.proyect_id")))));
		$profile=$this->User->Profile->find('first',array('conditions'=>array('User.id'=>$user["User"]["id"])));
		$this->set('profile',$profile);
		//$this->set('proyectos',$proyectos);
		$this->set(array("_serialize"=>array("profile")));
	}
	public function saveProfile(){
		$user=$this->viewVars['user'];
		$profile=array();
		$profile["Profile"]=$this->request->data["Profile"];
		$profile["Profile"]["user_id"]=$user["User"]["id"];
		$this->User->Profile->save($profile);
		echo json_encode($profile);
	}
    public function save() {
    	if(isset($this->request->data["User"])){
    		$data=$this->request->data;
	        $password=Security::cipher($data["User"]["password"],$data["User"]["username"]);
	        $data["User"]["password"]=$password;
	        if ($newuser=$this->User->save($data)) {
	        	$this->set(array(
		            'newuser' => $newuser,  
		            '_serialize' => 'newuser'
		        ));
	        }else{
	        	parent::sendmsg("104");
	        }
		}
    }
	public function delete(){
		//print_r ($this->request->data);
    	if(isset($this->request->data["id"])){
    		$id=$this->request->data["id"];
    		if($this->User->delete($id)){
    			parent::sendmsg("502");
    		}else{
    			parent::sendmsg("105");
    		}
    	}
	}
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>