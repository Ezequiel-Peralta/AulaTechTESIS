<?php
class Principal_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_all_principals() {
        try {
            $this->db->select('principal.principal_id, principal.email, principal.username, principal_details.firstname, principal_details.lastname, principal_details.dni, principal_details.photo, principal_details.user_status_id');
            $this->db->from('principal');
            $this->db->join('principal_details', 'principal.principal_id = principal_details.principal_id');
            $this->db->order_by('principal_details.lastname', 'ASC');
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_principals: ' . $e->getMessage());
            return false;
        }
    }

    public function get_principal_info($principal_id) {
        try {
            $principal_id = $this->db->escape_str($principal_id);
            $this->db->select('principal.principal_id, principal.email, principal.username, principal.password, principal_details.firstname, principal_details.lastname, principal_details.dni, principal_details.photo, principal_details.phone_cel, principal_details.phone_fij, principal_details.birthday, principal_details.gender_id, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
            $this->db->from('principal');
            $this->db->join('principal_details', 'principal.principal_id = principal_details.principal_id');
            $this->db->join('address', 'principal_details.address_id = address.address_id');
            $this->db->where('principal.principal_id', $principal_id);
            return $this->db->get()->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_principal_info: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_principal($data) {
        try {
            $this->db->insert('principal', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_principal: ' . $e->getMessage());
            return false;
        }
    }

    public function update_principal($principal_id, $data) {
        try {
            $principal_id = $this->db->escape_str($principal_id);
            $this->db->where('principal_id', $principal_id);
            return $this->db->update('principal', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_principal: ' . $e->getMessage());
            return false;
        }
    }

    public function update_principal_status($principal_id, $status) {
        try {
            $principal_id = $this->db->escape_str($principal_id);
            $this->db->where('principal_id', $principal_id);
            return $this->db->update('principal_details', array('user_status_id' => $status));
        } catch (Exception $e) {
            log_message('error', 'Error in update_principal_status: ' . $e->getMessage());
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

    public function insert_principal_details($data) {
        try {
            $this->db->insert('principal_details', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_principal_details: ' . $e->getMessage());
            return false;
        }
    }

    public function update_principal_details($principal_id, $data) {
        try {
            $principal_id = $this->db->escape_str($principal_id);
            $this->db->where('principal_id', $principal_id);
            return $this->db->update('principal_details', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_principal_details: ' . $e->getMessage());
            return false;
        }
    }

    public function get_principal_details($principal_id) {
        try {
            $principal_id = $this->db->escape_str($principal_id);
            return $this->db->get_where('principal_details', array('principal_id' => $principal_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_principal_details: ' . $e->getMessage());
            return false;
        }
    }
}