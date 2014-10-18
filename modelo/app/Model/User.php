<?php
class User extends AppModel {
        public $name = 'User';
		public $validate = array(
        'username' => array(
            'rule' => 'notEmpty'
        ),
        'password' => array(
            'rule' => 'notEmpty'
        )
	    );
		public $hasOne = array(
	        'Profile' => array(
	            'className' => 'Profile',
	            'dependent' => true
	        )
	    );
		public $hasMany = array(
	        'Proyect' => array(
	            'className' => 'Proyect',
	            'dependent' => false
	        ),
	        'Permiso' => array(
	            'className' => 'Permiso',
	            'dependent' => true
	        ),
	        'Evento' => array(
	            'className' => 'Evento',
	            'dependent' => FALSE
	        ),
	        'Observacion' => array(
	            'className' => 'Observacion',
	            'dependent' => TRUE
	        )
	    );
		
}
?>