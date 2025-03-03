<?php
class Exams_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    function get_exams() {
        try {
            $query = $this->db->get('exam');
            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_exams: ' . $e->getMessage());
            return false;
        }
    }

    function get_exam_info($exam_id) {
        try {
            $exam_id = $this->db->escape_str($exam_id);
            $query = $this->db->get_where('exam', array('exam_id' => $exam_id));
            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_exam_info: ' . $e->getMessage());
            return false;
        }
    }

    function get_exam_type_info($id) {
        try {
            $id = $this->db->escape_str($id);
            $query = $this->db->get_where('exam_type', array('id' => $id));
            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_exam_type_info: ' . $e->getMessage());
            return false;
        }
    }

    function create_exam($data) {
        try {
            $this->db->insert('exam', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in create_exam: ' . $e->getMessage());
            return false;
        }
    }

    function update_exam($exam_id, $data) {
        try {
            $exam_id = $this->db->escape_str($exam_id);
            $this->db->where('exam_id', $exam_id);
            $this->db->update('exam', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_exam: ' . $e->getMessage());
            return false;
        }
    }

    function update_exam_status($exam_id, $status) {
        try {
            $exam_id = $this->db->escape_str($exam_id);
            $status = $this->db->escape_str($status);
            $this->db->where('exam_id', $exam_id);
            $this->db->update('exam', array('status_id' => $status));
        } catch (Exception $e) {
            log_message('error', 'Error in update_exam_status: ' . $e->getMessage());
            return false;
        }
    }

    function bulk_update_exam_status($exam_ids, $status) {
        try {
            $status = $this->db->escape_str($status);
            $this->db->where_in('exam_id', $exam_ids);
            $this->db->update('exam', array('status_id' => $status));
        } catch (Exception $e) {
            log_message('error', 'Error in bulk_update_exam_status: ' . $e->getMessage());
            return false;
        }
    }

    function get_teacher_id_by_subject($subject_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            $subject = $this->db->get_where('subject', array('subject_id' => $subject_id))->row();
            return $subject ? $subject->teacher_id : null;
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_id_by_subject: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            return $this->db->get_where('section', array('section_id' => $section_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_section: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section_history($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            return $this->db->get_where('section_history', array('section_id' => $section_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_section_history: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subject($subject_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            return $this->db->get_where('subject', array('subject_id' => $subject_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_subject: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subject_history($subject_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            return $this->db->get_where('subject_history', array('subject_id' => $subject_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_subject_history: ' . $e->getMessage());
            return false;
        }
    }

    public function get_teacher($teacher_id) {
        try {
            $teacher_id = $this->db->escape_str($teacher_id);
            return $this->db->get_where('teacher_details', array('teacher_id' => $teacher_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher: ' . $e->getMessage());
            return false;
        }
    }

    public function get_exam_type_name($exam_type_id) {
        try {
            $exam_type_id = $this->db->escape_str($exam_type_id);
            $this->db->select('name');
            $this->db->where('id', $exam_type_id);
            $result = $this->db->get('exam_type')->row();
            return $result ? $result->name : '';
        } catch (Exception $e) {
            log_message('error', 'Error in get_exam_type_name: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subject_name($subject_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            $this->db->select('name');
            $this->db->where('subject_id', $subject_id);
            $result = $this->db->get('subject')->row();
            return $result ? $result->name : '';
        } catch (Exception $e) {
            log_message('error', 'Error in get_subject_name: ' . $e->getMessage());
            return false;
        }
    }

    public function get_exam_types() {
        try {
            return $this->db->get('exam_type')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_exam_types: ' . $e->getMessage());
            return false;
        }
    }

    public function get_exams_by_section_and_subject($section_id, $subject_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            $subject_id = $this->db->escape_str($subject_id);
            $this->db->where('section_id', $section_id);
            if (!empty($subject_id)) {
                $this->db->where('subject_id', $subject_id);
            }
            return $this->db->get('exam')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_exams_by_section_and_subject: ' . $e->getMessage());
            return false;
        }
    }
}