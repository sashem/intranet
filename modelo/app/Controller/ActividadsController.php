<?php
class ActividadsController extends AppController {
    public $helpers = array ('Html','Form');
	public $components = array('RequestHandler','Security' => array('csrfUseOnce' => false));
	//App::import('Model', 'MyModel');
    //$my_model = ClassRegistry::init('MyModel');
    public function beforeFilter() {
        parent::beforeFilter();
		$this->Security->unlockedActions = array('ajaxAction');
		$this->Security->csrfCheck = false;
		$this->Security->validatePost = false;
		$includeBeforeFilterAdmin = array('view');
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
	public function delete(){
		if(isset($this->request->data["id"])){
			$data=$this->request->data;
			if($this->Actividad->delete($data["id"])){
				parent::sendmsg("501");
			}else{
				parent::sendmsg("104");
			}
		}
	}
	public function save(){
		$data=$this->request->data;
		if(isset($this->request->data["Actividad"])){
    		$data=$this->request->data;
			if ($this->Actividad->saveAll($data,array('deep'=>true))) {
	        	$actividad=$this->Actividad->find("first",array("conditions"=>array("Actividad.id"=>$this->Actividad->id)));
	        	$this->set(array(
		            'actividad' => $actividad,  
		            '_serialize' => 'actividad'
		        ));
	        }else{
	        	parent::sendmsg("104");
	        }
		}
	}
    public function view(){
    	$data=$this->request->query;
		if(isset($data["id"])){
        	$actividad=$this->Actividad->find("first",array("conditions"=>array("Actividad.id"=>$data["id"])));
		}else{
		}
		$this->set(array(
            'actividad' => $actividad,
            '_serialize' => 'actividad'
        ));
    }
	public function getUsers(){
		$data=$this->request->query;
		
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