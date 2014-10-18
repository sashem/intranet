<?php
class Evento extends AppModel {
        public $name = 'Evento';
		public $belongsTo = array('Clgroup','User');
		public $actsAs = array('Containable');
		/*function find($type, $queryData = array()) {
		 	switch ($type) {
      			case 'all':
					return $this->find('all', array(
			          'fields' => array('User.username')));
				break;
			}
		 }*/
}
?>