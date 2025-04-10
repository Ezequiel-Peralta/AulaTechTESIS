<?php
class Message_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('message/Message_model');
    }



}