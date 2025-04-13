<?php
class Students_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('students/Students_model');
    }

    
}