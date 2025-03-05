<?php
class TeacherAide_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_all_teacher_aides() {
        try {
            return $this->db->get('teacher_aide')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_teacher_aides: ' . $e->getMessage());
            return false;
        }
    }

    public function get_teacher_aide_details($teacher_aide_id) {
        try {
            $teacher_aide_id = $this->db->escape_str($teacher_aide_id);
            return $this->db->get_where('teacher_aide_details', array('teacher_aide_id' => $teacher_aide_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_aide_details: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_teacher_aide($data) {
        try {
            $this->db->insert('teacher_aide', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_teacher_aide: ' . $e->getMessage());
            return false;
        }
    }

    public function update_teacher_aide($teacher_aide_id, $data) {
        try {
            $teacher_aide_id = $this->db->escape_str($teacher_aide_id);
            $this->db->where('teacher_aide_id', $teacher_aide_id);
            return $this->db->update('teacher_aide', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_teacher_aide: ' . $e->getMessage());
            return false;
        }
    }

    public function update_teacher_aide_status($teacher_aide_id, $status) {
        try {
            $teacher_aide_id = $this->db->escape_str($teacher_aide_id);
            $this->db->where('teacher_aide_id', $teacher_aide_id);
            return $this->db->update('teacher_aide', array('status' => $status));
        } catch (Exception $e) {
            log_message('error', 'Error in update_teacher_aide_status: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_address($data) {
        try {
            $this->db->insert('address', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_address: ' . $e->getMessage());
            return false;
        }
    }

    public function update_address($address_id, $data) {
        try {
            $address_id = $this->db->escape_str($address_id);
            $this->db->where('address_id', $address_id);
            return $this->db->update('address', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_address: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_teacher_aide_details($data) {
        try {
            $this->db->insert('teacher_aide_details', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_teacher_aide_details: ' . $e->getMessage());
            return false;
        }
    }

    public function update_teacher_aide_details($teacher_aide_id, $data) {
        try {
            $teacher_aide_id = $this->db->escape_str($teacher_aide_id);
            $this->db->where('teacher_aide_id', $teacher_aide_id);
            return $this->db->update('teacher_aide_details', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_teacher_aide_details: ' . $e->getMessage());
            return false;
        }
    }

    public function get_sections_by_teacher_aide($teacher_aide_id) {
        try {
            $teacher_aide_id = $this->db->escape_str($teacher_aide_id);
            return $this->db->get_where('section', array('teacher_aide_id' => $teacher_aide_id))->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_sections_by_teacher_aide: ' . $e->getMessage());
            return false;
        }
    }

    public function update_section_teacher_aide($section_id, $teacher_aide_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            $teacher_aide_id = $this->db->escape_str($teacher_aide_id);
            $this->db->where('section_id', $section_id);
            return $this->db->update('section', array('teacher_aide_id' => $teacher_aide_id));
        } catch (Exception $e) {
            log_message('error', 'Error in update_section_teacher_aide: ' . $e->getMessage());
            return false;
        }
    }

    public function remove_teacher_aide_from_sections($teacher_aide_id, $section_ids) {
        try {
            $teacher_aide_id = $this->db->escape_str($teacher_aide_id);
            $this->db->where('teacher_aide_id', $teacher_aide_id);
            $this->db->where_in('section_id', $section_ids);
            return $this->db->update('section', array('teacher_aide_id' => null));
        } catch (Exception $e) {
            log_message('error', 'Error in remove_teacher_aide_from_sections: ' . $e->getMessage());
            return false;
        }
    }
}