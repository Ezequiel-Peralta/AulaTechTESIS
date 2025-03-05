<?php
class Guardian_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('guardian/Guardian_model');
    }

    

}