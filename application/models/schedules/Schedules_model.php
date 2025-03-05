<?php
class Schedules_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function create_schedule($data) {
        try {
            $this->db->insert('schedule', $data);
            $schedule_id = $this->db->insert_id();

            $this->db->where('subject_id', $data['subject_id']);
            return $this->db->update('subject', array('schedule_id' => $schedule_id));
        } catch (Exception $e) {
            log_message('error', 'Error in create_schedule: ' . $e->getMessage());
            return false;
        }
    }

    public function update_schedule($schedule_id, $data) {
        try {
            $this->db->where('schedule_id', $schedule_id);
            $this->db->update('schedule', $data);

            $this->db->where('subject_id', $data['subject_id']);
            return $this->db->update('subject', array('schedule_id' => $schedule_id));
        } catch (Exception $e) {
            log_message('error', 'Error in update_schedule: ' . $e->getMessage());
            return false;
        }
    }

    public function update_schedule_status($schedule_id, $status_id) {
        try {
            $this->db->where('schedule_id', $schedule_id);
            return $this->db->update('schedule', array('status_id' => $status_id));
        } catch (Exception $e) {
            log_message('error', 'Error in update_schedule_status: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subject($subject_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            return $this->db->get_where('subject', array('subject_id' => $subject_id))->row();
        } catch (Exception $e) {
            log_message('error', 'Error in get_subject: ' . $e->getMessage());
            return false;
        }
    }

    public function get_active_academic_period() {
        try {
            return $this->db->get_where('academic_period', array('status_id' => 1))->row();
        } catch (Exception $e) {
            log_message('error', 'Error in get_active_academic_period: ' . $e->getMessage());
            return false;
        }
    }

    public function get_first_section($academic_period_id) {
        try {
            $academic_period_id = $this->db->escape_str($academic_period_id);
            $this->db->where('academic_period_id', $academic_period_id);
            $this->db->order_by('section_id', 'ASC');
            return $this->db->get('section')->row();
        } catch (Exception $e) {
            log_message('error', 'Error in get_first_section: ' . $e->getMessage());
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

    public function get_teacher_data($teacher_id) {
        try {
            $teacher_id = $this->db->escape_str($teacher_id);
            return $this->db->get_where('teacher_details', array('teacher_id' => $teacher_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_schedule($schedule_id) {
        try {
            $this->db->where('schedule_id', $schedule_id);
            $query = $this->db->get('schedule');
            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_schedule: ' . $e->getMessage());
            return false;
        }
    }

    public function get_active_sections() {
        try {
            $this->db->select('section.class_id, section.section_id, section.letter_name, section.shift_id');
            $this->db->from('section');
            $this->db->join('academic_period', 'section.academic_period_id = academic_period.id');
            $this->db->where('academic_period.status_id', 1);
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_active_sections: ' . $e->getMessage());
            return false;
        }
    }

    public function get_classes_by_ids($class_ids) {
        try {
            $this->db->where_in('class_id', $class_ids);
            return $this->db->get('class')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_classes_by_ids: ' . $e->getMessage());
            return false;
        }
    }

}