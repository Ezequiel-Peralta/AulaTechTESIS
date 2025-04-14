<?php
class Sections_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function insert_section($data) {
        try {
            $this->db->insert('section', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_section: ' . $e->getMessage());
            return false;
        }
    }

    public function update_section($section_id, $data) {
        try {
            $section_id = $this->db->escape_str($section_id);
            $this->db->where('section_id', $section_id);
            return $this->db->update('section', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_section: ' . $e->getMessage());
            return false;
        }
    }

    public function update_section_status($section_id, $status) {
        try {
            $section_id = $this->db->escape_str($section_id);
            $this->db->where('section_id', $section_id);
            return $this->db->update('section', array('status_id' => $status));
        } catch (Exception $e) {
            log_message('error', 'Error in update_section_status: ' . $e->getMessage());
            return false;
        }
    }

    public function get_first_class_id() {
        try {
            $class = $this->db->get('class')->first_row();
            return isset($class->class_id) ? $class->class_id : null;
        } catch (Exception $e) {
            log_message('error', 'Error in get_first_class_id: ' . $e->getMessage());
            return false;
        }
    }

    public function get_active_academic_period() {
        try {
            return $this->db->get_where('academic_period', array('status' => 1))->row();
        } catch (Exception $e) {
            log_message('error', 'Error in get_active_academic_period: ' . $e->getMessage());
            return false;
        }
    }

    public function get_sections_by_class($class_id) {
        try {
            $class_id = $this->db->escape_str($class_id);
            return $this->db->get_where('section', array('class_id' => $class_id))->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_sections_by_class: ' . $e->getMessage());
            return false;
        }
    }

    public function get_all_sections() {
        try {
            return $this->db->get('section')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_sections: ' . $e->getMessage());
            return false;
        }
    }

    public function get_active_sections() {
        try {
            return $this->db->get_where('section', array('status_id' => 1))->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_active_sections: ' . $e->getMessage());
            return false;
        }
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

    public function insert_section_routine($data) {
        try {
            $this->db->insert('class_routine', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_section_routine: ' . $e->getMessage());
            return false;
        }
    }

    public function update_section_routine($routine_id, $data) {
        try {
            $routine_id = $this->db->escape_str($routine_id);
            $this->db->where('class_routine_id', $routine_id);
            return $this->db->update('class_routine', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_section_routine: ' . $e->getMessage());
            return false;
        }
    }

    public function delete_section_routine($routine_id) {
        try {
            $routine_id = $this->db->escape_str($routine_id);
            $this->db->where('class_routine_id', $routine_id);
            return $this->db->delete('class_routine');
        } catch (Exception $e) {
            log_message('error', 'Error in delete_section_routine: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section_routine_by_id($routine_id) {
        try {
            $routine_id = $this->db->escape_str($routine_id);
            return $this->db->get_where('class_routine', array('class_routine_id' => $routine_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_section_routine_by_id: ' . $e->getMessage());
            return false;
        }
    }
}