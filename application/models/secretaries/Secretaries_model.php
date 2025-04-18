<?php
class Secretaries_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_all_secretaries() {
        try {
            $this->db->select('secretary.*, secretary_details.*');
            $this->db->from('secretary');
            $this->db->join('secretary_details', 'secretary.secretary_id = secretary_details.secretary_id', 'left');
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_secretaries: ' . $e->getMessage());
            return false;
        }
    }
    

    public function get_secretary_info($secretary_id) {
        try {
            $secretary_id = $this->db->escape_str($secretary_id);
            $this->db->select('secretary.secretary_id, secretary.email, secretary.username, secretary.password, secretary_details.firstname, secretary_details.lastname, secretary_details.dni, secretary_details.photo, secretary_details.phone_cel, secretary_details.phone_fij, secretary_details.birthday, secretary_details.gender_id, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
            $this->db->from('secretary');
            $this->db->join('secretary_details', 'secretary.secretary_id = secretary_details.secretary_id');
            $this->db->join('address', 'secretary_details.address_id = address.address_id');
            $this->db->where('secretary.secretary_id', $secretary_id);
            return $this->db->get()->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_secretary_info: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_secretary($data) {
        try {
            $this->db->insert('secretary', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_secretary: ' . $e->getMessage());
            return false;
        }
    }

    public function update_secretary($secretary_id, $data) {
        try {
            $secretary_id = $this->db->escape_str($secretary_id);
            $this->db->where('secretary_id', $secretary_id);
            return $this->db->update('secretary', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_secretary: ' . $e->getMessage());
            return false;
        }
    }

    public function update_secretary_status($secretary_id, $status) {
        try {
            $secretary_id = $this->db->escape_str($secretary_id);
            $this->db->where('secretary_id', $secretary_id);
            return $this->db->update('secretary_details', array('user_status_id' => $status));
        } catch (Exception $e) {
            log_message('error', 'Error in update_secretary_status: ' . $e->getMessage());
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

    public function insert_secretary_details($data) {
        try {
            $this->db->insert('secretary_details', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_secretary_details: ' . $e->getMessage());
            return false;
        }
    }

    public function update_secretary_details($secretary_id, $data) {
        try {
            $secretary_id = $this->db->escape_str($secretary_id);
            $this->db->where('secretary_id', $secretary_id);
            return $this->db->update('secretary_details', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_secretary_details: ' . $e->getMessage());
            return false;
        }
    }

    public function get_secretary_details($secretary_id) {
        try {
            $secretary_id = $this->db->escape_str($secretary_id);
            return $this->db->get_where('secretary_details', array('secretary_id' => $secretary_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_secretary_details: ' . $e->getMessage());
            return false;
        }
    }
}