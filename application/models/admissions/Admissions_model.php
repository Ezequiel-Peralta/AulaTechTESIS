<?php
class Admissions_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_pending_admissions() {
        try {
            $this->db->select('
                student.student_id, 
                student.email, 
                student.username, 
                student_details.enrollment, 
                student_details.firstname, 
                student_details.lastname, 
                student_details.dni, 
                student_details.photo, 
                student_details.birthday, 
                student_details.phone_cel, 
                student_details.phone_fij, 
                student_details.section_id, 
                student_details.class_id, 
                student_details.user_status_id, 
                student_details.gender_id, 
                student_details.address_id, 
                student_details.status_reason,
                address.state, 
                address.postalcode, 
                address.locality, 
                address.neighborhood,
                address.address,
                address.address_line
            ');
            $this->db->from('student');
            $this->db->join('student_details', 'student.student_id = student_details.student_id');
            $this->db->join('address', 'student_details.address_id = address.address_id', 'left');
            $this->db->where('student_details.user_status_id', 0);
            $this->db->where('student_details.class_id IS NULL');
            $this->db->or_where('student_details.class_id', '');
            $this->db->where('student_details.section_id IS NULL');
            $this->db->or_where('student_details.section_id', '');
            $query = $this->db->get();
            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_pending_admissions: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section_by_id($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            return $this->db->get_where('section', array('section_id' => $section_id))->row();
        } catch (Exception $e) {
            log_message('error', 'Error in get_section_by_id: ' . $e->getMessage());
            return false;
        }
    }

    public function update_student_details($student_id, $class_id, $section_id) {
        try {
            $student_id = $this->db->escape_str($student_id);
            $class_id = $this->db->escape_str($class_id);
            $section_id = $this->db->escape_str($section_id);

            $this->db->where('student_id', $student_id);
            return $this->db->update('student_details', array(
                'class_id' => $class_id,
                'section_id' => $section_id,
                'user_status_id' => 1,
                'status_reason' => ''
            ));
        } catch (Exception $e) {
            log_message('error', 'Error in update_student_details: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_academic_history($student_id, $class_id, $section_id, $academic_period_id) {
        try {
            $student_id = $this->db->escape_str($student_id);
            $class_id = $this->db->escape_str($class_id);
            $section_id = $this->db->escape_str($section_id);
            $academic_period_id = $this->db->escape_str($academic_period_id);

            return $this->db->insert('academic_history', array(
                'student_id' => $student_id,
                'old_class_id' => null,
                'old_section_id' => null,
                'new_class_id' => $class_id,
                'new_section_id' => $section_id,
                'old_academic_period_id' => null,
                'new_academic_period_id' => $academic_period_id,
                'date_change' => date('Y-m-d')
            ));
        } catch (Exception $e) {
            log_message('error', 'Error in insert_academic_history: ' . $e->getMessage());
            return false;
        }
    }

    public function bulk_update_student_details($students, $class_id, $section_id) {
        try {
            $class_id = $this->db->escape_str($class_id);
            $section_id = $this->db->escape_str($section_id);

            $this->db->where_in('student_id', $students);
            return $this->db->update('student_details', array(
                'class_id' => $class_id,
                'section_id' => $section_id,
                'user_status_id' => 1,
                'status_reason' => ''
            ));
        } catch (Exception $e) {
            log_message('error', 'Error in bulk_update_student_details: ' . $e->getMessage());
            return false;
        }
    }
}