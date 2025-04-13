<?php
class Subjects_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('subjects/Subjects_model');
    }

    

}