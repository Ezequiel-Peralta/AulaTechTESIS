<?php
class Admissions_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('admissions/Admissions_model');
    }

  
}