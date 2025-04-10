<?php
class Usersys_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_user_info($user_id) {
        try {
            $user_id = $this->db->escape_str($user_id);
            return $this->db->get_where('admin', array('admin_id' => $user_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_user_info: ' . $e->getMessage());
            return false;
        }
    }

    public function update_user_info($user_id, $data) {
        try {
            $user_id = $this->db->escape_str($user_id);
            $this->db->where('admin_id', $user_id);
            return $this->db->update('admin', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_user_info: ' . $e->getMessage());
            return false;
        }
    }

    public function update_user_details($user_id, $dataDetails) {
        try {
            $user_id = $this->db->escape_str($user_id);
            $this->db->where('admin_id', $user_id);
            return $this->db->update('admin_details', $dataDetails);
        } catch (Exception $e) {
            log_message('error', 'Error in update_user_details: ' . $e->getMessage());
            return false;
        }
    }

    public function get_postalcode_localidad($postal_code) {
        try {
            $postal_code = $this->db->escape_str($postal_code);
            return $this->db->get_where('postal_code_cba', array('postal_code' => $postal_code))->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_postalcode_localidad: ' . $e->getMessage());
            return false;
        }
    }

    public function get_postal_codes() {
        try {
            $this->db->select('postal_code');
            $this->db->distinct();
            return $this->db->get('postal_code_cba')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_postal_codes: ' . $e->getMessage());
            return false;
        }
    }

    public function get_current_password($user_id) {
        try {
            $user_id = $this->db->escape_str($user_id);
            return $this->db->get_where('admin', array('admin_id' => $user_id))->row()->password;
        } catch (Exception $e) {
            log_message('error', 'Error in get_current_password: ' . $e->getMessage());
            return false;
        }
    }

    public function update_password($user_id, $new_password) {
        try {
            $user_id = $this->db->escape_str($user_id);
            $this->db->where('admin_id', $user_id);
            return $this->db->update('admin', array('password' => $new_password));
        } catch (Exception $e) {
            log_message('error', 'Error in update_password: ' . $e->getMessage());
            return false;
        }
    }

    public function update_theme_preference($user_id, $theme_preference) {
        try {
            $user_id = $this->db->escape_str($user_id);
            $this->db->where('admin_id', $user_id);
            return $this->db->update('admin_details', array('theme_preference' => $theme_preference));
        } catch (Exception $e) {
            log_message('error', 'Error in update_theme_preference: ' . $e->getMessage());
            return false;
        }
    }

    public function update_language_preference($user_id, $language_preference) {
        try {
            $user_id = $this->db->escape_str($user_id);
            $this->db->where('admin_id', $user_id);
            return $this->db->update('admin_details', array('language_preference' => $language_preference));
        } catch (Exception $e) {
            log_message('error', 'Error in update_language_preference: ' . $e->getMessage());
            return false;
        }
    }


}