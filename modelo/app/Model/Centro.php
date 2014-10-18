<?php
class Centro extends AppModel {
        public $name = 'Centro';
		public $belongsTo = Array('Clgroup');
		public $hasMany = Array('Proyect');
}
?>