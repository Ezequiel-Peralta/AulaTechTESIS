<?php
class TeachersAide_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_all_teacher_aides() {
        try {
            $this->db->select('teacher_aide.*, teacher_aide_details.*');
            $this->db->from('teacher_aide');
            $this->db->join('teacher_aide_details', 'teacher_aide.teacher_aide_id = teacher_aide_details.teacher_aide_id', 'left');
            return $this->db->get()->result_array();
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

    function get_teacher_aide_info_per_id($teacher_aide_id) {
        try {
            $teacher_aide_id = $this->db->escape_str($teacher_aide_id);

            $this->db->select('ta.*, tad.*'); 
            $this->db->from('teacher_aide ta');
            $this->db->join('teacher_aide_details tad', 'ta.teacher_aide_id = tad.teacher_aide_id', 'left'); 
            $this->db->where('ta.teacher_aide_id', $teacher_aide_id);

            $query = $this->db->get();
            return $query->row_array(); 
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_aide_info_per_id: ' . $e->getMessage());
            return false;
        }
    }

    public function get_teacher_aide_info($teacher_aide_id) {
        try {
            $teacher_aide_id = $this->db->escape_str($teacher_aide_id);
    
            $this->db->select('
                teacher_aide.teacher_aide_id,
                teacher_aide.email,
                teacher_aide.username,
                teacher_aide.password,
                teacher_aide_details.firstname,
                teacher_aide_details.lastname,
                teacher_aide_details.dni,
                teacher_aide_details.photo,
                teacher_aide_details.phone_cel,
                teacher_aide_details.phone_fij,
                teacher_aide_details.birthday,
                teacher_aide_details.gender_id,
                address.locality,
                address.neighborhood,
                address.address,
                address.address_line,
                address.postalcode
            ');
            $this->db->from('teacher_aide');
            $this->db->join('teacher_aide_details', 'teacher_aide.teacher_aide_id = teacher_aide_details.teacher_aide_id');
            $this->db->join('address', 'teacher_aide_details.address_id = address.address_id');
            $this->db->where('teacher_aide.teacher_aide_id', $teacher_aide_id);
    
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_aide_info: ' . $e->getMessage());
            return false;
        }
    }

    public function get_teacher_aide_info_per_section($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
    
            $this->db->select('teacher_aide_id');
            $this->db->from('section');
            $this->db->where('section_id', $section_id);
            $query = $this->db->get();
            $section_data = $query->row_array();
    
            if (!$section_data || empty($section_data['teacher_aide_id'])) {
                return false; 
            }
    
            $teacher_aide_id = $section_data['teacher_aide_id'];
    
            $this->db->select('
                teacher_aide.teacher_aide_id,
                teacher_aide.email,
                teacher_aide.username,
                teacher_aide.password,
                teacher_aide_details.firstname,
                teacher_aide_details.lastname,
                teacher_aide_details.dni,
                teacher_aide_details.photo,
                teacher_aide_details.phone_cel,
                teacher_aide_details.phone_fij,
                teacher_aide_details.birthday,
                teacher_aide_details.gender_id,
                address.locality,
                address.neighborhood,
                address.address,
                address.address_line,
                address.postalcode
            ');
            $this->db->from('teacher_aide');
            $this->db->join('teacher_aide_details', 'teacher_aide.teacher_aide_id = teacher_aide_details.teacher_aide_id');
            $this->db->join('address', 'teacher_aide_details.address_id = address.address_id');
            $this->db->where('teacher_aide.teacher_aide_id', $teacher_aide_id);
    
            return $this->db->get()->result_array();
    
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_aide_info_per_section: ' . $e->getMessage());
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
            return $this->db->update('teacher_aide_details', array('user_status_id' => $status));
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