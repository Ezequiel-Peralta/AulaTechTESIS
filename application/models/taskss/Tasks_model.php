<?php
class Tasks_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function insert_task($data) {
        try {
            $this->db->insert('task', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_task: ' . $e->getMessage());
            return false;
        }
    }

    public function update_task($task_id, $data) {
        try {
            $task_id = $this->db->escape_str($task_id);
            $this->db->where('task_id', $task_id);
            return $this->db->update('task', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_task: ' . $e->getMessage());
            return false;
        }
    }

    public function update_task_status($task_id, $status) {
        try {
            $task_id = $this->db->escape_str($task_id);
            $this->db->where('task_id', $task_id);
            return $this->db->update('task', array('status_id' => $status));
        } catch (Exception $e) {
            log_message('error', 'Error in update_task_status: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_task_item($data) {
        try {
            $this->db->insert('task_items', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in insert_task_item: ' . $e->getMessage());
            return false;
        }
    }

    public function update_task_item($task_item_id, $data) {
        try {
            $task_item_id = $this->db->escape_str($task_item_id);
            $this->db->where('task_item_id', $task_item_id);
            return $this->db->update('task_items', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_task_item: ' . $e->getMessage());
            return false;
        }
    }

    public function update_task_item_status($task_item_id, $data) {
        try {
            $task_item_id = $this->db->escape_str($task_item_id);
            $this->db->where('task_item_id', $task_item_id);
            return $this->db->update('task_items', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_task_item_status: ' . $e->getMessage());
            return false;
        }
    }

    public function get_task_item($task_item_id) {
        try {
            $task_item_id = $this->db->escape_str($task_item_id);
            return $this->db->get_where('task_items', array('task_item_id' => $task_item_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_task_item: ' . $e->getMessage());
            return false;
        }
    }

    public function get_task_id_by_item($task_item_id) {
        try {
            $task_item_id = $this->db->escape_str($task_item_id);
            return $this->db->get_where('task_items', array('task_item_id' => $task_item_id))->row('task_id');
        } catch (Exception $e) {
            log_message('error', 'Error in get_task_id_by_item: ' . $e->getMessage());
            return false;
        }
    }

    public function count_task_items($task_id) {
        try {
            $task_id = $this->db->escape_str($task_id);
            return $this->db->where('task_id', $task_id)->count_all_results('task_items');
        } catch (Exception $e) {
            log_message('error', 'Error in count_task_items: ' . $e->getMessage());
            return false;
        }
    }

    public function count_completed_task_items($task_id) {
        try {
            $task_id = $this->db->escape_str($task_id);
            return $this->db->where('task_id', $task_id)->where('status_id', 1)->count_all_results('task_items');
        } catch (Exception $e) {
            log_message('error', 'Error in count_completed_task_items: ' . $e->getMessage());
            return false;
        }
    }

    public function update_task_progress($task_id, $progress) {
        try {
            $task_id = $this->db->escape_str($task_id);
            $this->db->where('task_id', $task_id);
            return $this->db->update('task', array('progress' => $progress));
        } catch (Exception $e) {
            log_message('error', 'Error in update_task_progress: ' . $e->getMessage());
            return false;
        }
    }
}