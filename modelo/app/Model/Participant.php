<?php
class Participant extends AppModel {
        public $name = 'Participant';
		public $belongsTo = Array('Proyect','Meprofile');
}
?>