<?php
class Events_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('eventss/Events_model');
    }

    public function create_event($event_data) {
        try {
            $this->Events_model->create_event($event_data);
            $event_id = $this->db->insert_id();
            $visibility_data = array(
                'event_id' => $event_id,
                'visible_to' => $event_data['visible_to'],
                'visible_to_category' => $event_data['visible_to_category'],
                'visible_to_id' => $event_data['visible_to_id'],
                'created_by_user_id' => $event_data['created_by_user_id'],
                'created_by_group' => $event_data['created_by_group'],
                'created_at' => $event_data['created_at'],
                'visibility_for_creator' => $event_data['visibility_for_creator'],
                'visible_edit' => $event_data['visible_edit'],
                'visible_delete' => $event_data['visible_delete']
            );
            return $this->Events_model->create_event_visibility($visibility_data);
        } catch (Exception $e) {
            log_message('error', 'Error in create_event: ' . $e->getMessage());
            return false;
        }
    }

    public function update_event($event_id, $event_data) {
        try {
            $this->Events_model->update_event($event_id, $event_data);
            $visibility_data = array(
                'visible_to' => $event_data['visible_to'],
                'visible_to_category' => $event_data['visible_to_category'],
                'visible_to_id' => $event_data['visible_to_id'],
                'visibility_for_creator' => $event_data['visibility_for_creator'],
                'visible_edit' => $event_data['visible_edit'],
                'visible_delete' => $event_data['visible_delete']
            );
            return $this->Events_model->update_event_visibility($event_id, $visibility_data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_event: ' . $e->getMessage());
            return false;
        }
    }


}