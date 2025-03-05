<?php
class Classes_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function insert_class($data) {
        try {
            $this->db->insert('class', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_class: ' . $e->getMessage());
            return false;
        }
    }

    public function update_class($class_id, $data) {
        try {
            $class_id = $this->db->escape_str($class_id);
            $this->db->where('class_id', $class_id);
            return $this->db->update('class', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_class: ' . $e->getMessage());
            return false;
        }
    }

    public function get_all_classes() {
        try {
            return $this->db->get('class')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_classes: ' . $e->getMessage());
            return false;
        }
    }
}