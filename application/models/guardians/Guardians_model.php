<?php
class Guardians_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_guardians() {
        try {
            return $this->db->get('guardian')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_guardians: ' . $e->getMessage());
            return false;
        }
    }

    public function get_guardian_info($guardian_id) {
        try {
            $guardian_id = $this->db->escape_str($guardian_id);
            $this->db->select('guardian.guardian_id, guardian.email, guardian.username, guardian.password, guardian_details.firstname, guardian_details.lastname, guardian_details.dni, guardian_details.photo, guardian_details.phone_cel, guardian_details.phone_fij, guardian_details.birthday, guardian_details.gender_id, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
            $this->db->from('guardian');
            $this->db->join('guardian_details', 'guardian.guardian_id = guardian_details.guardian_id');
            $this->db->join('address', 'guardian_details.address_id = address.address_id');
            $this->db->where('guardian.guardian_id', $guardian_id);
            return $this->db->get()->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_guardian_info: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_guardian($data) {
        try {
            $this->db->insert('guardian', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_guardian: ' . $e->getMessage());
            return false;
        }
    }

    public function update_guardian($guardian_id, $data) {
        try {
            $guardian_id = $this->db->escape_str($guardian_id);
            $this->db->where('guardian_id', $guardian_id);
            return $this->db->update('guardian', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_guardian: ' . $e->getMessage());
            return false;
        }
    }

    public function update_guardian_status($guardian_id, $status) {
        try {
            $guardian_id = $this->db->escape_str($guardian_id);
            $this->db->where('guardian_id', $guardian_id);
            return $this->db->update('guardian', array('status' => $status));
        } catch (Exception $e) {
            log_message('error', 'Error in update_guardian_status: ' . $e->getMessage());
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

    public function insert_guardian_details($data) {
        try {
            $this->db->insert('guardian_details', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_guardian_details: ' . $e->getMessage());
            return false;
        }
    }

    public function update_guardian_details($guardian_id, $data) {
        try {
            $guardian_id = $this->db->escape_str($guardian_id);
            $this->db->where('guardian_id', $guardian_id);
            return $this->db->update('guardian_details', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_guardian_details: ' . $e->getMessage());
            return false;
        }
    }

    public function get_guardian_details($guardian_id) {
        try {
            $guardian_id = $this->db->escape_str($guardian_id);
            return $this->db->get_where('guardian_details', array('guardian_id' => $guardian_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_guardian_details: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_student_guardian($data) {
        try {
            $this->db->insert('student_guardian', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_student_guardian: ' . $e->getMessage());
            return false;
        }
    }

    public function update_student_guardian($guardian_id, $student_id, $data) {
        try {
            $guardian_id = $this->db->escape_str($guardian_id);
            $student_id = $this->db->escape_str($student_id);
            $this->db->where('guardian_id', $guardian_id);
            $this->db->where('student_id', $student_id);
            return $this->db->update('student_guardian', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_student_guardian: ' . $e->getMessage());
            return false;
        }
    }

    public function delete_student_guardians($guardian_id, $student_ids) {
        try {
            $guardian_id = $this->db->escape_str($guardian_id);
            $this->db->where('guardian_id', $guardian_id);
            $this->db->where_in('student_id', $student_ids);
            return $this->db->delete('student_guardian');
        } catch (Exception $e) {
            log_message('error', 'Error in delete_student_guardians: ' . $e->getMessage());
            return false;
        }
    }

    public function get_student_guardians($guardian_id) {
        try {
            $guardian_id = $this->db->escape_str($guardian_id);
            return $this->db->get_where('student_guardian', array('guardian_id' => $guardian_id))->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_student_guardians: ' . $e->getMessage());
            return false;
        }
    }

    public function get_all_guardians() {
        try {
            return $this->db->get('guardian')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_guardians: ' . $e->getMessage());
            return false;
        }
    }
}