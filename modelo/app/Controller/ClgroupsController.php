<?php
class ClgroupsController extends AppController {
    public $helpers = array ('Html','Form');
	public $components = array('RequestHandler','Security' => array('csrfUseOnce' => false));
	//App::import('Model', 'MyModel');
    //$my_model = ClassRegistry::init('MyModel');
    public function beforeFilter() {
        parent::beforeFilter();
		$this->Security->unlockedActions = array('ajaxAction');
		$this->Security->csrfCheck = false;
		$this->Security->validatePost = false;
		$includeBeforeFilterAdmin = array('add','index','delete');
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
        $clients=$this->Clgroup->find('all',array('contain'=>array("Clgroup")));
		//$clients = Hash::combine($clients, '{n}.Clgroup.id', '{n}');
		//$clients=json_encode($clients);
		//print_r($clients);
		//$response["clients"]=$clients;
		$this->set(array(
            'clients' => $clients,
            '_serialize' => 'clients'
        ));
    }
    public function view($id) {
		$this->Clgroup->Evento->virtualFields["creador"]='(select users.username from users join eventos on users.id=eventos.user_id)';
		$paging=array('fields'=>array('Evento.user_id','Evento.creador'));
		$client=$this->Clgroup->find('first',array(
			'contain'=>array(),
			'conditions'=>array('id'=>$id))
			);
		$this->set(array(
            'client' => $client,
            '_serialize' => 'client'
        ));
    }
	public function save() {
    	if(isset($this->request->data["Clgroup"])){
    		$data=$this->request->data;
	        if ($newclient=$this->Clgroup->save($data)) {
	        	$this->set(array(
		            'newclient' => $newclient,  
		            '_serialize' => 'newclient'
		        ));
	        }else{
	        	parent::sendmsg("104");
	        }
		}
    }
	public function saveContact(){
		if(isset($this->request->data["Clprofile"])){
    		$data=$this->request->data;
	        if ($newcontact=$this->Clgroup->Clprofile->save($data)) {
	        	//$this->Clgroup->touch();
				//$this->Clgroup->Clprofile->touch();
	        	$this->set(array(
		            'newcontact' => $newcontact,  
		            '_serialize' => 'newcontact'
		        ));
	        }else{
	        	parent::sendmsg("104");
	        }
		}
	}
	public function saveCenter(){
		if(isset($this->request->data["Centro"])){
    		$data=$this->request->data;
	        if ($newcenter=$this->Clgroup->Centro->save($data)) {
	        	$this->set(array(
		            'newcenter' => $newcenter,  
		            '_serialize' => 'newcenter'
		        ));
	        }else{
	        	parent::sendmsg("104");
	        }
		}
	}
	
	public function saveEvent(){
		if(isset($this->request->data["Evento"])){
    		$data=$this->request->data;
			$user=$this->viewVars['user'];
	        $data["Evento"]["user_id"]=$user["User"]["id"];
	        if ($newevent=$this->Clgroup->Evento->save($data)) {
	        	$this->set(array(
		            'newevent' => $newevent,  
		            '_serialize' => 'newevent'
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
    		if($this->Clgroup->delete($id)){
    			parent::sendmsg("502");
    		}else{
    			parent::sendmsg("105");
    		}
    	}
	}
	public function deleteContact(){
		//print_r ($this->request->data);
    	if(isset($this->request->data["id"])){
    		$id=$this->request->data["id"];
    		if($this->Clgroup->Clprofile->delete($id)){
    			parent::sendmsg("502");
    		}else{
    			parent::sendmsg("105");
    		}
    	}
	}
	public function deleteCenter(){
		//print_r ($this->request->data);
    	if(isset($this->request->data["id"])){
    		$id=$this->request->data["id"];
    		if($this->Clgroup->Centro->delete($id)){
    			parent::sendmsg("502");
    		}else{
    			parent::sendmsg("105");
    		}
    	}
	}
	public function deleteEvent(){
		//print_r ($this->request->data);
    	if(isset($this->request->data["id"])){
    		$id=$this->request->data["id"];
    		if($this->Clgroup->Evento->delete($id)){
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
function obj_serializer($obj){
	$aux=array();
	foreach ($obj as $key=>$value){
		$aux[$key]=json_encode($value);
	}
	return $aux;
}
?>