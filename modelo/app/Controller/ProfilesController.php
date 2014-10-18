<?php
class ProfilesController extends AppController {
    public $helpers = array ('Html','Form');
	public $components = array('RequestHandler','Security' => array('csrfUseOnce' => false));
	//App::import('Model', 'MyModel');
    //$my_model = ClassRegistry::init('MyModel');
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
	public function getActivity(){
		$data=$this->request->query;
		if(isset($data["id"])){
        	$profiles=$this->Profile->query("select Profile.id, Profile.nombres, Profile.apellidos,
        	Profile.comuna_santiago, Profile.fecha_ingreso_cms,Profile.celular, Profile.email_cms
        	from profiles as Profile
        	where Profile.id 
        	not in (select profile_id from asistenciaeqs where asistenciaeqs.actividad_id=".$data["id"].")");
		}else{
			$profiles=$this->Profile->find("all");
		}
		
		$this->set(array(
            'profiles' => $profiles,
            '_serialize' => 'profiles'
        ));
	}
    public function index(){
    	$data=$this->request->query;
		//print_r($data);
		//$this->Profile->bindModel(array('hasMany' => array('Member')));
		if(isset($data["id"])){
        	$profiles=$this->Profile->query("select Profile.id, Profile.nombres, Profile.apellidos,
        	Profile.comuna_santiago, Profile.fecha_ingreso_cms,Profile.celular, Profile.email_cms
        	from profiles as Profile
        	where Profile.id 
        	not in (select profile_id from members where members.proyect_id=".$data["id"].")");
		}else{
			$profiles=$this->Profile->find("all");
		}
		
		$this->set(array(
            'profiles' => $profiles,
            '_serialize' => 'profiles'
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