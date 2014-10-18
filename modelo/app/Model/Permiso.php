<?php
class Permiso extends AppModel {
        public $name = 'Permiso';
		public $belongsTo = Array('Proyect','User');
}
?>