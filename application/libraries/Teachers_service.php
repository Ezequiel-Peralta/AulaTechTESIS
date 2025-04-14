<?php
class Teachers_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('teachers/Teachers_model');
    }

}