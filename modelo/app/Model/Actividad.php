<?php
class Actividad extends AppModel {
        public $name = 'Actividad';
		public $actsAs = array('Containable');
		public $belongsTo = 'Proyect';
		public $hasMany = array('Observacion');
		public $hasAndBelongsToMany = array(
	        'Profile' => array(
	            'className' => 'Profile',
	            'joinTable' => 'asistenciaeqs',
	            'foreignKey' => 'actividad_id',
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
	            'joinTable' => 'asistenciames',
	            'foreignKey' => 'actividad_id',
	            'associationForeignKey' => 'meprofile_id',
	            'unique' => 'keepExisting',
	            'conditions' => '',
	            'order' => '',
	            'limit' => '',
	            'offset' => '',
	            'finderQuery' => '',
	            'deleteQuery' => '',
	            'insertQuery' => ''
	        )
    	);
}
?>