<?php
class CrudParent extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    function get_children_by_parent($parent_id) {
        $query = $this->db->get_where('student', array('parent_id' => $parent_id));
        return $query->result_array();
    }
    function get_parent_info($parent_id) {
        $query = $this->db->get_where('parent', array('parent_id' => $parent_id));
        return $query->result_array();
    }
}