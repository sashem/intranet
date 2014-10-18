<?php
class ProyectsController extends AppController {
    public $helpers = array ('Html','Form');
	public $components = array('RequestHandler','Security' => array('csrfUseOnce' => false));
	//App::import('Model', 'MyModel');
    //$my_model = ClassRegistry::init('MyModel');
    public function beforeFilter() {
        parent::beforeFilter();
		$this->Security->unlockedActions = array('ajaxAction');
		$this->Security->csrfCheck = false;
		$this->Security->validatePost = false;
		$includeBeforeFilterAdmin = array('save','index','delete');
		if (in_array($this->action,$includeBeforeFilterAdmin)){
			if(isset($this->request->data['cookie'])){
				parent::checkadmin($this->request->data['cookie']);
			}else if(isset($this->request->query['cookie'])){
				parent::checkadmin($this->request->query['cookie']);
			}else{
				parent::sendmsg("101");	
			}
		}
		$checkPermissionsFilter= array('save','view','delete');
		if (in_array($this->action,$checkPermissionsFilter)){
			
		}
    }
    public function index(){
        $proyects=$this->Proyect->find('all');
		$this->set(array(
            'proyects' => $proyects,
            '_serialize' => 'proyects'
        ));
    }
	public function saveActivity(){
			if(isset($this->request->data["Actividad"])){
				$data=$this->request->data;
				if(!isset($data["Actividad"]["Meprofile"])){
					$data["Actividad"]["Meprofile"]=array();
					$proyecto=$this->Proyect->find('first',array('conditions'=>array('Proyect.id'=>$data["Actividad"]["proyect_id"])));
		        	foreach($proyecto["Meprofile"] as $empresario){
		        		$empresario["Asistenciame"]=array(
							"proyect_id"=>$proyecto["Proyect"]["id"],
							"meprofile_id"=>$empresario["id"]
						);
		        		array_push($data["Actividad"]["Meprofile"],$empresario);
		        	}
				}
				//print_r($data);
		        
				if($this->Proyect->Actividad->saveAll($data,array("deep"=>true))){
					$actividad=$this->Proyect->Actividad->find("first",array('conditions'=>(array("Actividad.id"=>$this->Proyect->Actividad->id))));
					$this->set(array(
			            'actividad' => $actividad,
			            '_serialize' => 'actividad'
			        ));
				}else{
					parent::sendmsg("104");
				}
			}
	}
    public function view() {
		if(isset($this->request->query["id"])){
		$id=$this->request->query["id"];
		//$members=$this->Proyect->Member->find('all',array("conditions"=>array("Proyect.id"=>$id)));
		//$this->Proyect->Actividad->unbindModel(array("hasAndBelongsToMany"=>"Profile"));
		$this->Proyect->Actividad->bindModel(array("hasMany"=>array("Asistenciaeq","Asistenciame")));
		//$this->Proyect->recursive=1;
		$proyect=$this->Proyect->find('first',array(
			'contain'=>array("Actividad"=>array("Asistenciaeq","Asistenciame"),"Profile"=>array("Asistenciaeq"),"Clgroup","User","Programa","Permiso","Centro","Clprofile","Meprofile"),
			'conditions'=>array("Proyect.id"=>$id),
		));
		//$this->Proyect->virtualFields["creador"]='(select users.username from users join proyects on users.id=proyects.user_id)';
		//$paging=array('fields'=>array('Proyect.user_id','Proyect.creador'));
		$this->set(array(
            'proyect' => $proyect,
            '_serialize' => 'proyect'
        ));
		}
    }
    public function save() {
        if(isset($this->request->data["proyecto"])){
    		$data=$this->request->data['proyecto'];
			$user=$this->viewVars['user'];
			unset($data["User"]);
			$data["Proyect"]["user_id"]=$user["User"]["id"];
	        if ($this->Proyect->saveAll($data,array("deep"=>true))) {
	        	$newproyect["id"]=$this->Proyect->id;
	        	$this->set(array(
		            'newproyect' => $newproyect,  
		            '_serialize' => 'newproyect'
		        ));
	        }else{
	        	parent::sendmsg("104");
	        }
		}
    }
	public function delete(){
		if(isset($this->request->data["id"])){
    		$id=$this->request->data["id"];
    		if($this->Proyect->delete($id)){
    			parent::sendmsg("502");
    		}else{
    			parent::sendmsg("105");
    		}
    	}
	}
	public function update(){
		if(isset($this->request->data["proyecto"])){
			$data=$this->request->data["proyecto"];
			unset($data["User"]);
			if ($this->Proyect->saveAll($data,array("deep"=>true))){
				$this->Proyect->Actividad->bindModel(array("hasMany"=>array("Asistenciaeq","Asistenciame")));
				$newproyect=$this->Proyect->find("first",array(
					'conditions'=>array('Proyect.id'=>$this->Proyect->id),
					'contain'=>array("Actividad"=>array("Asistenciaeq","Asistenciame"),"Profile"=>array("Asistenciaeq"),"Clgroup","User","Programa","Permiso","Centro","Clprofile","Meprofile"),
					));
	        	$this->set(array(
		            'newproyect' => $newproyect,  
		            '_serialize' => 'newproyect'
		        ));
	        }else{
	        	parent::sendmsg("104");
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