<?php
class Sections_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('sections/Sections_model');
    }

    
}