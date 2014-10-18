<?php
class Student extends AppModel {
        public $name = 'Student';
		public $belongsTo = Array('Project','Meprofile');
		var $validate = array(
        'project_id' => array('rule' => 'uniqueCombi'),
        'meprofile_id'  => array('rule' => 'uniqueCombi')
    	);

	    function uniqueCombi() {
	        $combi = array(
	            "{$this->alias}.proyect_id" => $this->data[$this->alias]['proyect_id'],
	            "{$this->alias}.meprofile_id"  => $this->data[$this->alias]['meprofile_id']
	        );
	        return $this->isUnique($combi, false);
	    }
}
?>