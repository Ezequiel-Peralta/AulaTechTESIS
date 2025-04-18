<?php
class TeachersAide_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('teachersAide/TeachersAide_model');
    }

    

}