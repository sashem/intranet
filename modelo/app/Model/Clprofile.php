<?php
class Clprofile extends AppModel {
        public $name = 'Clprofile';
		public $belongsTo = 'Clgroup';
		public $hasMany = array(
	        'Client' => array(
	            'className' => 'Client',
	            'dependent' => true
	        )
	    );
}
?>