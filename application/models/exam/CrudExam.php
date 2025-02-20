<?php
class CrudExam extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    function get_exams() {
        $query = $this->db->get('exam');
        return $query->result_array();
    }

    function get_exam_info($exam_id) {
        $query = $this->db->get_where('exam', array('exam_id' => $exam_id));
        return $query->result_array();
    }

    function get_exam_type_info($id) {
        $query = $this->db->get_where('exam_type', array('id' => $id));
        return $query->result_array();
    }
}