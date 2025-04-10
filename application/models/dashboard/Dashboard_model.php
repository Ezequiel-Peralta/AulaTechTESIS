<?php
class Dashboard_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_visible_event_ids($user_id, $user_group) {
        try {
            $user_id = $this->db->escape_str($user_id);
            $user_group = $this->db->escape_str($user_group);

            $this->db->select('event_id');
            $this->db->from('event_visibility');

            $this->db->group_start();
            $this->db->where('visible_to', 'my_account');
            $this->db->where('created_by_user_id', $user_id);
            $this->db->where('created_by_group', $user_group);
            $this->db->group_end();

            $this->db->or_group_start();
            $this->db->where('visibility_for_creator', $user_id);
            $this->db->where('created_by_group', $user_group);
            $this->db->where('visibility_for_creator', 1);
            $this->db->group_end();

            $this->db->or_group_start();
            $this->db->where('visible_to', $user_group);
            $this->db->where('visible_to_id', $user_id);
            $this->db->where('visible_to_category', 'PerUser');
            $this->db->group_end();

            $this->db->or_group_start();
            $this->db->where('visible_to', $user_group);
            $this->db->where('visible_to_id', null);
            $this->db->where('visible_to_category', 'All');
            $this->db->group_end();

            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_visible_event_ids: ' . $e->getMessage());
            return false;
        }
    }

    public function get_events_by_ids($event_ids, $status_id) {
        try {
            $this->db->select('event_id, title, body, date, start, end, color, type, status_id');
            $this->db->where_in('event_id', $event_ids);
            $this->db->where('status_id', $status_id);
            return $this->db->get('events')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_events_by_ids: ' . $e->getMessage());
            return false;
        }
    }
}