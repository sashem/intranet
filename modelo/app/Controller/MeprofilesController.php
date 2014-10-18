<?php
class MeprofilesController extends AppController {
    public $helpers = array ('Html','Form');
	public $components = array('RequestHandler','Security' => array('csrfUseOnce' => false));
	//App::import('Model', 'MyModel');
    //$my_model = ClassRegistry::init('MyModel');
    public function save(){
    	$data=$this->request->data;
		if(isset($data["Meprofile"])){
        	if($Meprofiles=$this->Meprofile->save($data)){
        		$id["id"]=$this->Meprofile->id;
				$this->set(array(
		            'id' => $id,
		            '_serialize' => 'id'
        		));
        	}else{
        		parent::sendmsg("105");
        	}
		}else{
			parent::sendmsg("105");
		}
		
    }
    public function beforeFilter() {
        parent::beforeFilter();
		$this->Security->unlockedActions = array('ajaxAction');
		$this->Security->csrfCheck = false;
		$this->Security->validatePost = false;
		$includeBeforeFilterAdmin = array('index');
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
	public function find(){
		$data=$this->request->data;
		if(isset($data["objetivo"])){
        if(!isset($data["objetivo"]["nombres"]))$data["objetivo"]["nombres"]="";
        if(!isset($data["objetivo"]["apellidos"]))$data["objetivo"]["apellidos"]="";			
			$Meprofiles=$this->Meprofile->query("select * 
        	from Meprofiles as Meprofile 
        	where Meprofile.nombres LIKE '%".$data["objetivo"]["nombres"]."%' and Meprofile.apellidos LIKE '%".$data["objetivo"]["apellidos"]."%'"
			);
		}else{
			parent::sendmsg("105");
		}
		
		$this->set(array(
            'Meprofiles' => $Meprofiles,
            '_serialize' => 'Meprofiles'
        ));
	}
	public function getActivity(){
		$data=$this->request->query;
		if(isset($data["id"])){
        	$Meprofiles=$this->Meprofile->query("select Meprofile.id, Meprofile.nombres, Meprofile.apellidos,
        	Meprofile.comuna_santiago, Meprofile.fecha_ingreso_cms,Meprofile.celular, Meprofile.email_cms
        	from Meprofiles as Meprofile
        	where Meprofile.id 
        	not in (select meprofile_id from asistenciaeqs where asistenciaeqs.actividad_id=".$data["id"].")");
		}else{
			$Meprofiles=$this->Meprofile->find("all");
		}
		
		$this->set(array(
            'Meprofiles' => $Meprofiles,
            '_serialize' => 'Meprofiles'
        ));
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