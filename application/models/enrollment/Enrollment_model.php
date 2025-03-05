<?php
class Enrollment_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_students_for_re_enrollment($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            $this->db->select('student_id, firstname, lastname, enrollment');
            $this->db->where('section_id', $section_id);
            return $this->db->get('student_details')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_students_for_re_enrollment: ' . $e->getMessage());
            return false;
        }
    }

    public function get_students_for_pre_enrollment() {
        try {
            $this->db->select('student_id, firstname, lastname, enrollment');
            $this->db->where('class_id IS NULL');
            $this->db->or_where('section_id IS NULL');
            return $this->db->get('student_details')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_students_for_pre_enrollment: ' . $e->getMessage());
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

    public function update_academic_history($student_id, $class_id, $section_id) {
        try {
            $student_id = $this->db->escape_str($student_id);
            $class_id = $this->db->escape_str($class_id);
            $section_id = $this->db->escape_str($section_id);

            $this->db->where('student_id', $student_id);
            return $this->db->update('academic_history', array(
                'new_class_id' => $class_id,
                'new_section_id' => $section_id,
                'date_change' => date('Y-m-d')
            ));
        } catch (Exception $e) {
            log_message('error', 'Error in update_academic_history: ' . $e->getMessage());
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

    public function bulk_update_academic_history($students, $class_id, $section_id) {
        try {
            $class_id = $this->db->escape_str($class_id);
            $section_id = $this->db->escape_str($section_id);

            foreach ($students as $student_id) {
                $student_id = $this->db->escape_str($student_id);
                $this->db->where('student_id', $student_id);
                $this->db->update('academic_history', array(
                    'new_class_id' => $class_id,
                    'new_section_id' => $section_id,
                    'date_change' => date('Y-m-d')
                ));
            }
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error in bulk_update_academic_history: ' . $e->getMessage());
            return false;
        }
    }

    public function get_old_sections($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            $this->db->select('section_id, name');
            $this->db->where('section_id !=', $section_id);
            return $this->db->get('section')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_old_sections: ' . $e->getMessage());
            return false;
        }
    }
}