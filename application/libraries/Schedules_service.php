<?php
class Schedules_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('schedules/Schedules_model');
    }
  
}