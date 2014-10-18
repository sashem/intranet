<?php
class Member extends AppModel {
        public $name = 'Member';
		public $belongsTo = Array('Proyect','Profile');
}
?>