<?php
class Admin_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('admin/Admin_model');
    }

}