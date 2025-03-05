<?php
class Mark_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function create_mark($data) {
        try {
            $this->db->insert('mark', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in create_mark: ' . $e->getMessage());
            return false;
        }
    }

    public function update_mark($mark_id, $data) {
        try {
            $mark_id = $this->db->escape_str($mark_id);
            $this->db->where('mark_id', $mark_id);
            return $this->db->update('mark', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_mark: ' . $e->getMessage());
            return false;
        }
    }

    public function delete_mark($mark_id) {
        try {
            $mark_id = $this->db->escape_str($mark_id);
            $this->db->where('mark_id', $mark_id);
            return $this->db->delete('mark');
        } catch (Exception $e) {
            log_message('error', 'Error in delete_mark: ' . $e->getMessage());
            return false;
        }
    }

    public function update_mark_history($mark_id, $data) {
        try {
            $mark_id = $this->db->escape_str($mark_id);
            $this->db->where('mark_id', $mark_id);
            return $this->db->update('mark_history', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_mark_history: ' . $e->getMessage());
            return false;
        }
    }

    public function delete_mark_history($mark_id) {
        try {
            $mark_id = $this->db->escape_str($mark_id);
            $this->db->where('mark_id', $mark_id);
            return $this->db->delete('mark_history');
        } catch (Exception $e) {
            log_message('error', 'Error in delete_mark_history: ' . $e->getMessage());
            return false;
        }
    }

    public function get_marks_by_student_subject($student_id, $subject_id) {
        try {
            $student_id = $this->db->escape_str($student_id);
            $subject_id = $this->db->escape_str($subject_id);
            $query = $this->db->get_where('mark', array('student_id' => $student_id, 'subject_id' => $subject_id));
            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_marks_by_student_subject: ' . $e->getMessage());
            return false;
        }
    }

    public function get_marks_by_student_subject2($student_id, $subject_id, $academic_period_id) {
        try {
            $student_id = $this->db->escape_str($student_id);
            $subject_id = $this->db->escape_str($subject_id);
            $academic_period_id = $this->db->escape_str($academic_period_id);

            $query_history = $this->db->get_where('mark_history', array(
                'student_id' => $student_id,
                'subject_id' => $subject_id,
                'academic_period_id' => $academic_period_id
            ));
            
            if ($query_history->num_rows() > 0) {
                return $query_history->result_array();
            } else {
                $query = $this->db->get_where('mark', array(
                    'student_id' => $student_id,
                    'subject_id' => $subject_id
                ));
                return $query->result_array();
            }
        } catch (Exception $e) {
            log_message('error', 'Error in get_marks_by_student_subject2: ' . $e->getMessage());
            return false;
        }
    }

    public function get_marks_by_student_subject3($student_id, $subject_id, $academic_period_id) {
        try {
            $student_id = $this->db->escape_str($student_id);
            $subject_id = $this->db->escape_str($subject_id);
            $academic_period_id = $this->db->escape_str($academic_period_id);

            $this->db->where('student_id', $student_id);
            $this->db->where('subject_id', $subject_id);
            $this->db->where('academic_period_id !=', $academic_period_id);
            $this->db->order_by('date', 'DESC');
            $this->db->limit(3);
            $query_history = $this->db->get('mark_history');
            
            if ($query_history->num_rows() > 0) {
                return $query_history->result_array();
            }
            return [];
        } catch (Exception $e) {
            log_message('error', 'Error in get_marks_by_student_subject3: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section_data($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            return $this->db->get_where('section', array('section_id' => $section_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_section_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section_history_data($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            return $this->db->get_where('section_history', array('section_id' => $section_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_section_history_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subject_count_by_section($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            return $this->db->where('section_id', $section_id)->count_all_results('subject');
        } catch (Exception $e) {
            log_message('error', 'Error in get_subject_count_by_section: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subject_data($subject_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            return $this->db->get_where('subject', array('subject_id' => $subject_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_subject_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subject_history_data($subject_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            return $this->db->get_where('subject_history', array('subject_id' => $subject_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_subject_history_data: ' . $e->getMessage());
            return false;
        }
    }
}