<?php
class TeacherAide_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('teacherAide/TeacherAide_model');
    }

    

}