<?php
class Behaviors_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('behaviors/Behaviors_model');
    }
    
}