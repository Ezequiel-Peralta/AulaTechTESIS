<?php
class Behavior_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('behavior/Behavior_model');
    }
    
}