<?php
class Events_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function create_event($event_data, $visibility_data) {
        try {
            $this->db->insert('events', $event_data);
            $inserted_id = $this->db->insert_id();

            $visibility_data['event_id'] = $inserted_id;

            return $this->create_event_visibility($visibility_data);
        } catch (Exception $e) {
            log_message('error', 'Error in create_event: ' . $e->getMessage());
            return false;
        }
    }

    public function update_event($event_id, $event_data, $visibility_data) {
        try {
            $this->db->where('event_id', $event_id);
            $this->db->update('events', $event_data);

            $this->db->where('event_id', $event_id);
            return $this->db->update('event_visibility', $visibility_data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_event: ' . $e->getMessage());
            return false;
        }
    }

    public function update_event_status($event_id, $status_id) {
        try {
            $this->db->where('event_id', $event_id);
            return $this->db->update('events', array('status_id' => $status_id));
        } catch (Exception $e) {
            log_message('error', 'Error in update_event_status: ' . $e->getMessage());
            return false;
        }
    }

    public function create_event_visibility($visibility_data) {
        try {
            return $this->db->insert('event_visibility', $visibility_data);
        } catch (Exception $e) {
            log_message('error', 'Error in create_event_visibility: ' . $e->getMessage());
            return false;
        }
    }
}