<?php
class Client extends AppModel {
        public $name = 'Client';
		public $belongsTo = Array('Proyect','Clprofile');
}
?>