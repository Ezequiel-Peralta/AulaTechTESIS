<?php
class Guardians_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('guardians/Guardians_model');
    }

    

}