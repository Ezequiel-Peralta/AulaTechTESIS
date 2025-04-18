<?php
class Statistics_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_sections_by_academic_period($academic_period_id) {
        try {
            $academic_period_id = $this->db->escape_str($academic_period_id);
            return $this->db->get_where('section', array('academic_period_id' => $academic_period_id))->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_sections_by_academic_period: ' . $e->getMessage());
            return false;
        }
    }

    public function get_attendance_data($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            $this->db->select('student_id, COUNT(*) as total_absences');
            $this->db->where('section_id', $section_id);
            $this->db->where('status', 2); 
            $this->db->group_by('student_id');
            return $this->db->get('attendance_student')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_attendance_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_students_attendance_data($section_id, $min_absences, $max_absences) {
        try {
            $section_id = $this->db->escape_str($section_id);
            $min_absences = $this->db->escape_str($min_absences);
            $max_absences = $this->db->escape_str($max_absences);

            $this->db->select('student_id, COUNT(*) as total_absences');
            $this->db->where('section_id', $section_id);
            $this->db->where('status', 2); 
            $this->db->group_by('student_id');
            $this->db->having('total_absences >=', $min_absences);
            $this->db->having('total_absences <=', $max_absences);
            return $this->db->get('attendance_student')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_students_attendance_data: ' . $e->getMessage());
            return false;
        }
    }

    public function count_students_by_class($class_id) {
        try {
            $class_id = $this->db->escape_str($class_id);
            return $this->db->where('class_id', $class_id)->count_all_results('student_details');
        } catch (Exception $e) {
            log_message('error', 'Error in count_students_by_class: ' . $e->getMessage());
            return false;
        }
    }

    public function get_students_by_status($class_id, $status) {
        try {
            $class_id = $this->db->escape_str($class_id);
            $status = $this->db->escape_str($status);
            return $this->db->get_where('student_details', array('class_id' => $class_id, 'user_status_id' => $status))->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_students_by_status: ' . $e->getMessage());
            return false;
        }
    }

    public function count_students_by_status($class_id, $status) {
        try {
            $class_id = $this->db->escape_str($class_id);
            $status = $this->db->escape_str($status);
            return $this->db->where(array('class_id' => $class_id, 'user_status_id' => $status))->count_all_results('student_details');
        } catch (Exception $e) {
            log_message('error', 'Error in count_students_by_status: ' . $e->getMessage());
            return false;
        }
    }

    // public function get_graduates_by_period($academic_period_id) {
    //     try {
    //         $academic_period_id = $this->db->escape_str($academic_period_id);
    //         return $this->db->get_where('graduates', array('academic_period_id' => $academic_period_id))->result_array();
    //     } catch (Exception $e) {
    //         log_message('error', 'Error in get_graduates_by_period: ' . $e->getMessage());
    //         return false;
    //     }
    // }

    public function count_failed_marks($student_id, $subject_id) {
        try {
            $student_id = $this->db->escape_str($student_id);
            $subject_id = $this->db->escape_str($subject_id);
            return $this->db->where(array('student_id' => $student_id, 'subject_id' => $subject_id, 'mark_obtained <' => 7))->count_all_results('marks');
        } catch (Exception $e) {
            log_message('error', 'Error in count_failed_marks: ' . $e->getMessage());
            return false;
        }
    }

    public function get_student_details($student_id) {
        try {
            $student_id = $this->db->escape_str($student_id);
            return $this->db->get_where('student_details', array('student_id' => $student_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_student_details: ' . $e->getMessage());
            return false;
        }
    }

    public function get_last_academic_period_id() {
        try {
            $this->db->select_max('academic_period_id');
            $result = $this->db->get('academic_period')->row();
            return $result ? $result->academic_period_id : null;
        } catch (Exception $e) {
            log_message('error', 'Error in get_last_academic_period_id: ' . $e->getMessage());
            return false;
        }
    }

    public function get_students_by_section($section_id, $academic_period_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            $academic_period_id = $this->db->escape_str($academic_period_id);
            return $this->db->get_where('student_details', array('section_id' => $section_id, 'academic_period_id' => $academic_period_id))->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_students_by_section: ' . $e->getMessage());
            return false;
        }
    }

    public function get_student_mark($student_id, $academic_period_id, $section_id, $subject_id) {
        try {
            $student_id = $this->db->escape_str($student_id);
            $academic_period_id = $this->db->escape_str($academic_period_id);
            $section_id = $this->db->escape_str($section_id);
            $subject_id = $this->db->escape_str($subject_id);

            return $this->db->get_where('marks', array(
                'student_id' => $student_id,
                'academic_period_id' => $academic_period_id,
                'section_id' => $section_id,
                'subject_id' => $subject_id
            ))->row();
        } catch (Exception $e) {
            log_message('error', 'Error in get_student_mark: ' . $e->getMessage());
            return false;
        }
    }

    public function get_academic_periods() {
        try {
            return $this->db->get('academic_period')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_academic_periods: ' . $e->getMessage());
            return false;
        }
    }
}