<?php
class Section_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('section/Section_model');
    }

    
}