<?php
class Principal_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('principal/Principal_model');
    }

    

}