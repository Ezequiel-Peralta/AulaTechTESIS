<?php
class Marks_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('marks/Marks_model');
    }

    


}