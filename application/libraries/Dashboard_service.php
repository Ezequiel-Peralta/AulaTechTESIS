<?php
class Dashboard_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('dashboard/Dashboard_model');
    }

    public function get_visible_events($user_id, $user_group) {
        try {
            $visible_event_ids = $this->Dashboard_model->get_visible_event_ids($user_id, $user_group);
            $event_ids = array_column($visible_event_ids, 'event_id');

            if (!empty($event_ids)) {
                $events = $this->Dashboard_model->get_events_by_ids($event_ids, 1);
                return $this->process_events($events);
            } else {
                return [];
            }
        } catch (Exception $e) {
            log_message('error', 'Error in get_visible_events: ' . $e->getMessage());
            return false;
        }
    }

    public function get_disabled_events($user_id, $user_group) {
        try {
            $visible_event_ids = $this->Dashboard_model->get_visible_event_ids($user_id, $user_group);
            $event_ids = array_column($visible_event_ids, 'event_id');

            if (!empty($event_ids)) {
                $events = $this->Dashboard_model->get_events_by_ids($event_ids, 0);
                return $this->process_events($events);
            } else {
                return [];
            }
        } catch (Exception $e) {
            log_message('error', 'Error in get_disabled_events: ' . $e->getMessage());
            return false;
        }
    }

    private function process_events($events) {
        foreach ($events as &$event) {
            if (!is_null($event['date'])) {
                $event['start'] = $event['date'];
                $event['end'] = null;
                $event['allDay'] = true;
                $event['className'] = $event['color'];
            } else {
                $event['allDay'] = false;
            }
            unset($event['date']);
        }
        return $events;
    }
}