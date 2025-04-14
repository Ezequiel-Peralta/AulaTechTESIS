<?php
class Messages_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('Messages/messages_model');
    }



}