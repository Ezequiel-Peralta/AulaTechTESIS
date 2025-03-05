<?php
class Mark_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('mark/Mark_model');
    }

    


}