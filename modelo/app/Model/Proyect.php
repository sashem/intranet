<?php
class Proyect extends AppModel {
        public $name = 'Proyect';
		public $actsAs = array('Containable');
		public $belongsTo = Array('User'=>array("fields"=>array('username')),'Programa','Clgroup','Clprofile','Centro');
		public $hasAndBelongsToMany = array(
	        'Profile' => array(
	            'className' => 'Profile',
	            'joinTable' => 'members',
	            'foreignKey' => 'proyect_id',
	            'associationForeignKey' => 'profile_id',
	            'unique' => 'keepExisting',
	            'conditions' => '',
	            'fields' => Array('id','nombres','apellidos','comuna_santiago','fecha_ingreso_cms','celular','email_cms'),
	            'order' => '',
	            'limit' => '',
	            'offset' => '',
	            'finderQuery' => '',
	            'deleteQuery' => '',
	            'insertQuery' => ''
	        ),
	        'Meprofile' => array(
	            'className' => 'Meprofile',
	            'joinTable' => 'students',
	            'foreignKey' => 'proyect_id',
	            'associationForeignKey' => 'meprofile_id',
	            'unique' => true,
	            'conditions' => '',
	            'order' => '',
	            'limit' => '',
	            'offset' => '',
	            'finderQuery' => '',
	            'deleteQuery' => '',
	            'insertQuery' => ''
	        )
    	);
		public $hasMany = array(
	        'Actividad' => array(
	            'className' => 'Actividad',
	            'dependent' => true
	        )/*,
	        'Member' => array(
	            'className' => 'Member',
	            'dependent' => true
	        )*/,
	        'Permiso' => array(
	            'className' => 'Permiso',
	            'dependent' => true
	        )
	    );
}
?>