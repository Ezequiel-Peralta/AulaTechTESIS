<?php
class Teachers_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_all_teachers() {
        try {
            return $this->db->get('teacher')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_teachers: ' . $e->getMessage());
            return false;
        }
    }

    public function get_teacher_info($teacher_id) {
        try {
            $teacher_id = $this->db->escape_str($teacher_id);
            $this->db->select('teacher.teacher_id, teacher.email, teacher.username, teacher.password, teacher_details.firstname, teacher_details.lastname, teacher_details.dni, teacher_details.photo, teacher_details.phone_cel, teacher_details.phone_fij, teacher_details.birthday, teacher_details.gender_id, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
            $this->db->from('teacher');
            $this->db->join('teacher_details', 'teacher.teacher_id = teacher_details.teacher_id');
            $this->db->join('address', 'teacher_details.address_id = address.address_id');
            $this->db->where('teacher.teacher_id', $teacher_id);
            return $this->db->get()->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_info: ' . $e->getMessage());
            return false;
        }
    }

    public function get_teacher_info_per_subject($subject_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
    
            $this->db->select('teacher_id');
            $this->db->from('subject');
            $this->db->where('subject_id', $subject_id);
            $query = $this->db->get();
            $subject_data = $query->row_array();
    
            if (!$subject_data || empty($subject_data['teacher_id'])) {
                return false; 
            }
    
            $teacher_id = $subject_data['teacher_id'];
    
            $this->db->select('
                teacher.teacher_id,
                teacher.email,
                teacher.username,
                teacher.password,
                teacher_details.firstname,
                teacher_details.lastname,
                teacher_details.dni,
                teacher_details.photo,
                teacher_details.phone_cel,
                teacher_details.phone_fij,
                teacher_details.birthday,
                teacher_details.gender_id,
                address.locality,
                address.neighborhood,
                address.address,
                address.address_line,
                address.postalcode
            ');
            $this->db->from('teacher');
            $this->db->join('teacher_details', 'teacher.teacher_id = teacher_details.teacher_id');
            $this->db->join('address', 'teacher_details.address_id = address.address_id');
            $this->db->where('teacher.teacher_id', $teacher_id);
    
            return $this->db->get()->row_array();
    
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_info_per_subject: ' . $e->getMessage());
            return false;
        }
    }

    public function get_teacher_info2($teacher_id) {
        try {
            $teacher_id = $this->db->escape_str($teacher_id);
            $this->db->select('teacher.teacher_id, teacher.email, teacher.username, teacher.password, teacher_details.firstname, teacher_details.lastname, teacher_details.dni, teacher_details.photo, teacher_details.phone_cel, teacher_details.phone_fij, teacher_details.birthday, teacher_details.gender_id, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
            $this->db->from('teacher');
            $this->db->join('teacher_details', 'teacher.teacher_id = teacher_details.teacher_id');
            $this->db->join('address', 'teacher_details.address_id = address.address_id');
            $this->db->where('teacher.teacher_id', $teacher_id);
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_info: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_teacher($data) {
        try {
            $this->db->insert('teacher', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_teacher: ' . $e->getMessage());
            return false;
        }
    }

    public function update_teacher($teacher_id, $data) {
        try {
            $teacher_id = $this->db->escape_str($teacher_id);
            $this->db->where('teacher_id', $teacher_id);
            return $this->db->update('teacher', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_teacher: ' . $e->getMessage());
            return false;
        }
    }

    public function update_teacher_status($teacher_id, $status) {
        try {
            $teacher_id = $this->db->escape_str($teacher_id);
            $this->db->where('teacher_id', $teacher_id);
            return $this->db->update('teacher', array('status' => $status));
        } catch (Exception $e) {
            log_message('error', 'Error in update_teacher_status: ' . $e->getMessage());
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

    public function insert_teacher_details($data) {
        try {
            $this->db->insert('teacher_details', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_teacher_details: ' . $e->getMessage());
            return false;
        }
    }

    public function update_teacher_details($teacher_id, $data) {
        try {
            $teacher_id = $this->db->escape_str($teacher_id);
            $this->db->where('teacher_id', $teacher_id);
            return $this->db->update('teacher_details', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_teacher_details: ' . $e->getMessage());
            return false;
        }
    }

    public function get_teacher_details($teacher_id) {
        try {
            $teacher_id = $this->db->escape_str($teacher_id);
            return $this->db->get_where('teacher_details', array('teacher_id' => $teacher_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_details: ' . $e->getMessage());
            return false;
        }
    }

    public function get_teachers() {
        try {
            $this->db->select('td.*, t.email, t.username'); 
            $this->db->from('teacher_details td');
            $this->db->join('teacher t', 't.teacher_id = td.teacher_id');
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_teachers: ' . $e->getMessage());
            return false;
        }
    }
    
    public function get_teachers_info($teacher_id) {
        try {
            $teacher_id = $this->db->escape_str($teacher_id);
            $this->db->select('td.*, t.email, t.username'); 
            $this->db->from('teacher_details td');
            $this->db->join('teacher t', 't.teacher_id = td.teacher_id');
            $this->db->where('td.teacher_id', $teacher_id);
            return $this->db->get()->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_teachers_info: ' . $e->getMessage());
            return false;
        }
    }
    
    
}