<?php
class ProgramasController extends AppController {
    public $helpers = array ('Html','Form');
	public $components = array('RequestHandler','Security' => array('csrfUseOnce' => false));
	//App::import('Model', 'MyModel');
    //$my_model = ClassRegistry::init('MyModel');
    public function beforeFilter() {
        parent::beforeFilter();
		$this->Security->unlockedActions = array('ajaxAction');
		$this->Security->csrfCheck = false;
		$this->Security->validatePost = false;
		$includeBeforeFilterAdmin = array('add','index','delete','uploadProgram');
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
        $programas=$this->Programa->find('all');
		$this->set(array(
            'programas' => $programas,
            '_serialize' => 'programas'
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
	public function uploadProgram(){
		$archivos=$_FILES;
		//print_r($archivos);
		$data=array();
		foreach($archivos as $archivo){
			if($archivo["type"]=="application/pdf"){
				move_uploaded_file($archivo["tmp_name"], WWW_ROOT . 'files' . DS .$archivo["name"]);
				$data=$archivo["name"];
			}else{
				parent::sendmsg("106");
			}
		}
		$this->set(array(
		          'data' => $data,  
		         '_serialize' => 'data'
		));
	}
	public function save() {
    	if(isset($this->request->data["Programa"])){
    		$data=$this->request->data;
	  		$programa=array("Programa"=>$data["Programa"]);
			$archivos=$data["archivos"];
			$ruta=WWW_ROOT . 'files' . DS;
			foreach ($archivos as $tipo => $archivo){
				$nombre_archivo=$programa["Programa"]["nombre"]."_".$tipo.".pdf";
				rename($ruta . $archivo,$ruta . $nombre_archivo);	
				$programa["Programa"]["archivo_".$tipo]=$nombre_archivo;
			}
			if($data=$this->Programa->save($programa)){
				$this->set(array(
		          'data' => $data,  
		         '_serialize' => 'data'
				));
			}else{
				parent::sendmsg("107");
			}
		}
    }
	public function delete(){
		if(isset($this->request->data["id"])){
    		$id=$this->request->data["id"];
    		$programa=$this->Programa->find('first',array('conditions' => array("Programa.id" => $id)));
    		if (unlink(WWW_ROOT . 'files' . DS . $programa["Programa"]["archivo_programa"])
    		&& unlink(WWW_ROOT . 'files' . DS . $programa["Programa"]["archivo_clase_clase"])
			&& unlink(WWW_ROOT . 'files' . DS . $programa["Programa"]["archivo_cuadernillo"])){
				if($this->Programa->delete($id)){
    			parent::sendmsg("502");
    			}else{
    			parent::sendmsg("105");
    			}	
			}else{
				parent::sendmsg("108");
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