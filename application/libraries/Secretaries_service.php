<?php
class Secretaries_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('secretaries/Secretaries_model');
    }

    

}