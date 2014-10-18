<?php
class Observacion extends AppModel {
        public $name = 'Observacion';
		public $belongsTo = Array('User','Actividad');
}
?>