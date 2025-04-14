<?php
class Admins_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_admin_by_id($admin_id) {
        try {
            $admin_id = $this->db->escape_str($admin_id);
            return $this->db->get_where('admin_details', array('admin_id' => $admin_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_admin_by_id: ' . $e->getMessage());
            return false;
        }
    }

    public function get_all_admins() {
        try {
            return $this->db->select('admin_id, firstname, lastname')->get('admin_details')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_admins: ' . $e->getMessage());
            return false;
        }
    }
}