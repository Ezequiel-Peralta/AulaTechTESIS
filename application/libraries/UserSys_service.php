<?php
class UserSys_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('usersys/Usersys_model');
    }


}