<?php
class Tasks_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('taskss/Tasks_model');
    }

    
}