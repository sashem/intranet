<?php
class Profile extends AppModel {
        public $name = 'Profile';
		public $belongsTo = 'User';
		public $recursive = 1;
		public $hasAndBelongsToMany = array(
	        'Proyect' => array(
	            'className' => 'Proyect',
	            'joinTable' => 'members',
	            'foreignKey' => 'profile_id',
	            'associationForeignKey' => 'proyect_id',
	            'unique' => 'keepExisting',
	            'conditions' => '',
	            'fields' => '',
	            'order' => '',
	            'limit' => '',
	            'offset' => '',
	            'finderQuery' => '',
	            'deleteQuery' => '',
	            'insertQuery' => ''
	        ),
	        'Actividad' => array(
	            'className' => 'Actividad',
	            'joinTable' => 'asistenciaeqs',
	            'foreignKey' => 'profile_id',
	            'associationForeignKey' => 'actividad_id',
	            'unique' => 'keepExisting',
	            'conditions' => '',
	            'fields' => '',
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