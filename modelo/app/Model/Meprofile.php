<?php
class Meprofile extends AppModel {
        public $name = 'Meprofile';
		public $hasAndBelongsToMany= array(
		'Proyect' => array(
	            'className' => 'Proyect',
	            'joinTable' => 'students',
	            'foreignKey' => 'meprofile_id',
	            'associationForeignKey' => 'proyect_id',
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
}
?>