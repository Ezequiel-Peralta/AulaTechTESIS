<?php
class Secretary_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('secretary/Secretary_model');
    }

    

}