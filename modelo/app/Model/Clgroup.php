<?php
class Clgroup extends AppModel {
        public $name = 'Clgroup';
		public $hasMany = array(
	        'Clprofile' => array(
	            'className' => 'Clprofile',
	            'dependent' => true
	        ),
	        'Centro' => array(
	            'className' => 'Centro',
	            'dependent' => true
	        ),
	        'Evento' => array(
	            'className' => 'Evento',
	            'dependent' => true
	        ),'Proyect' => array(
	            'className' => 'Proyect',
	            'dependent' => true
	        )
	    );
}
?>