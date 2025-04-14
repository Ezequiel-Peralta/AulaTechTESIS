<?php
class Admins_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('admin/Admins_model');
    }

}