<?php
class Teacher_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('teacher/Teacher_model');
    }

}