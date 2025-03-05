<?php
class Subject_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('subject/Subject_model');
    }

    

}