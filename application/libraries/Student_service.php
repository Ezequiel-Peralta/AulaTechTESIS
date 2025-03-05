<?php
class Student_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('student/Student_model');
    }

    
}