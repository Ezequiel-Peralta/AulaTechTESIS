<?php
class Messages_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_unread_count($user_id, $user_group)
    {
        try {
            $user_id = $this->db->escape_str($user_id);
            $user_group = $this->db->escape_str($user_group);

            $this->db->select('COUNT(*) as unread_count');
            $this->db->from('message_thread');
            $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
            $this->db->join('user_message_status', 'message_thread.message_thread_code = user_message_status.message_thread_code', 'inner');
            $this->db->group_start();
            $this->db->where('message_thread.sender_id', $user_id);
            $this->db->where('message_thread.sender_group', $user_group);
            $this->db->or_where('message_thread.receiver_id', $user_id);
            $this->db->where('message_thread.receiver_group', $user_group);
            $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
            $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
            $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
            $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
            $this->db->group_end();
            $this->db->where('user_message_thread_status.user_id', $user_id);
            $this->db->where('user_message_thread_status.user_group', $user_group);
            $this->db->where('user_message_thread_status.is_trash', 0);
            $this->db->where('user_message_thread_status.is_draft', 0);
            $this->db->where('user_message_status.user_id', $user_id);
            $this->db->where('user_message_status.user_group', $user_group);
            $this->db->where('user_message_status.new_messages_count >', 0);

            $query = $this->db->get();
            $result = $query->row_array();
            return isset($result['unread_count']) ? $result['unread_count'] : 0;
        } catch (Exception $e) {
            log_message('error', 'Error in get_unread_count: ' . $e->getMessage());
            return false;
        }
    }

    public function get_received_count($user_id, $user_group)
    {
        try {
            $user_id = $this->db->escape_str($user_id);
            $user_group = $this->db->escape_str($user_group);

            $this->db->select('COUNT(*) as received_count');
            $this->db->from('message_thread');
            $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
            $this->db->where('message_thread.is_trash', 0);
            $this->db->group_start();
            $this->db->where('message_thread.sender_id', $user_id);
            $this->db->where('message_thread.sender_group', $user_group);
            $this->db->or_where('message_thread.receiver_id', $user_id);
            $this->db->where('message_thread.receiver_group', $user_group);
            $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
            $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
            $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
            $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
            $this->db->group_end();
            $this->db->where('user_message_thread_status.user_id', $user_id);
            $this->db->where('user_message_thread_status.user_group', $user_group);
            $this->db->where('user_message_thread_status.is_trash', 0);
            $this->db->where('user_message_thread_status.is_draft', 0);
            $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');

            $query = $this->db->get();
            $result = $query->row_array();
            return isset($result['received_count']) ? $result['received_count'] : 0;
        } catch (Exception $e) {
            log_message('error', 'Error in get_received_count: ' . $e->getMessage());
            return false;
        }
    }

    public function get_sent_count($user_id, $user_group)
    {
        try {
            $user_id = $this->db->escape_str($user_id);
            $user_group = $this->db->escape_str($user_group);

            $this->db->select('COUNT(*) as sent_count');
            $this->db->from('message_thread');
            $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
            $this->db->where('message_thread.sender_id', $user_id);
            $this->db->where('message_thread.sender_group', $user_group);
            $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
            $this->db->where('user_message_thread_status.user_id', $user_id);
            $this->db->where('user_message_thread_status.user_group', $user_group);
            $this->db->where('user_message_thread_status.is_trash', 0);
            $this->db->where('user_message_thread_status.is_draft', 0);

            $query = $this->db->get();
            $result = $query->row_array();
            return isset($result['sent_count']) ? $result['sent_count'] : 0;
        } catch (Exception $e) {
            log_message('error', 'Error in get_sent_count: ' . $e->getMessage());
            return false;
        }
    }

    public function get_draft_count($user_id, $user_group)
    {
        try {
            $user_id = $this->db->escape_str($user_id);
            $user_group = $this->db->escape_str($user_group);

            $this->db->select('COUNT(*) as draft_count');
            $this->db->from('user_message_thread_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->where('is_draft', 1);

            $query = $this->db->get();
            $result = $query->row_array();
            return isset($result['draft_count']) ? $result['draft_count'] : 0;
        } catch (Exception $e) {
            log_message('error', 'Error in get_draft_count: ' . $e->getMessage());
            return false;
        }
    }

    public function get_trash_count($user_id, $user_group)
    {
        try {
            $user_id = $this->db->escape_str($user_id);
            $user_group = $this->db->escape_str($user_group);

            $this->db->select('COUNT(*) as trash_count');
            $this->db->from('user_message_thread_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->where('is_trash', 1);

            $query = $this->db->get();
            $result = $query->row_array();
            return isset($result['trash_count']) ? $result['trash_count'] : 0;
        } catch (Exception $e) {
            log_message('error', 'Error in get_trash_count: ' . $e->getMessage());
            return false;
        }
    }

    public function get_favorite_count($user_id, $user_group)
    {
        try {
            $user_id = $this->db->escape_str($user_id);
            $user_group = $this->db->escape_str($user_group);

            $this->db->select('COUNT(*) as favorite_count');
            $this->db->from('user_message_thread_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->where('is_favorite', 1);

            $query = $this->db->get();
            $result = $query->row_array();
            return isset($result['favorite_count']) ? $result['favorite_count'] : 0;
        } catch (Exception $e) {
            log_message('error', 'Error in get_favorite_count: ' . $e->getMessage());
            return false;
        }
    }

    public function add_favorite($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_thread_status', array(
            'is_favorite' => 1,
            'favorite_timestamp' => date('Y-m-d H:i:s')
        ));
    }

    public function remove_favorite($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_thread_status', array(
            'is_favorite' => 0,
            'favorite_timestamp' => null
        ));
    }

    public function add_draft($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_thread_status', array(
            'is_draft' => 1,
            'draft_timestamp' => date('Y-m-d H:i:s')
        ));
    }

    public function remove_draft($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_thread_status', array(
            'is_draft' => 0,
            'draft_timestamp' => null
        ));
    }

    public function add_trash($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_thread_status', array(
            'is_trash' => 1,
            'trash_timestamp' => date('Y-m-d H:i:s')
        ));
    }

    public function remove_trash($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_thread_status', array(
            'is_trash' => 0,
            'trash_timestamp' => null
        ));
    }

    public function add_trash_for_user($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_thread_status', array(
            'is_trash' => 1,
            'is_draft' => 0,
            'is_favorite' => 0,
            'trash_timestamp' => date('Y-m-d H:i:s'),
            'draft_timestamp' => null,
            'favorite_timestamp' => null,
        ));
    }

    public function remove_trash_for_user($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_thread_status', array(
            'is_trash' => 0,
            'is_draft' => 0,
            'is_favorite' => 0,
            'trash_timestamp' => null,
            'draft_timestamp' => null,
            'favorite_timestamp' => null,
        ));
    }

    public function add_trash_for_all($message_thread_code, $user_id, $user_group)
    {
        // Actualizar la tabla message_thread
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->update('message_thread', array(
            'is_trash' => 1,
            'trash_timestamp' => date('Y-m-d H:i:s')
        ));

        // Actualizar la tabla user_message_thread_status
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->update('user_message_thread_status', array(
            'is_trash' => 1,
            'is_favorite' => 0,
            'trash_timestamp' => date('Y-m-d H:i:s'),
            'favorite_timestamp' => null,
            'is_trash_by_user_id' => $user_id,
            'is_trash_by_user_group' => $user_group
        ));
    }

    public function remove_trash_for_all($message_thread_code, $user_id, $user_group)
    {
        // Actualizar la tabla message_thread
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->update('message_thread', array(
            'is_trash' => 0,
            'trash_timestamp' => null
        ));

        // Actualizar la tabla user_message_thread_status
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->update('user_message_thread_status', array(
            'is_trash' => 0,
            'is_favorite' => 0,
            'trash_timestamp' => null,
            'favorite_timestamp' => null,
            'is_trash_by_user_id' => null,
            'is_trash_by_user_group' => null
        ));
    }

    public function mark_as_read($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_status', array(
            'new_messages_count' => 0,
            'last_seen_timestamp' => date('Y-m-d H:i:s')
        ));
    }

    public function mark_as_unread($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_status', array(
            'new_messages_count' => 1,
            'last_seen_timestamp' => null
        ));
    }

    public function move_to_draft($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_thread_status', array(
            'is_draft' => 1,
            'draft_timestamp' => date('Y-m-d H:i:s'),
            'is_trash' => 0,
            'trash_timestamp' => null
        ));
    }

    public function move_to_trash($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_thread_status', array(
            'is_trash' => 1,
            'trash_timestamp' => date('Y-m-d H:i:s'),
            'is_favorite' => 0,
            'favorite_timestamp' => null,
            'is_draft' => 0,
            'draft_timestamp' => null
        ));
    }

    public function move_to_inbox($message_thread_code, $user_id, $user_group)
    {
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->update('user_message_thread_status', array(
            'is_trash' => 0,
            'is_draft' => 0,
            'trash_timestamp' => null,
            'favorite_timestamp' => null,
            'draft_timestamp' => null,
            'is_favorite' => 0,
        ));
    }

    public function delete_message_thread($message_thread_code, $user_id, $user_group)
    {
        try {
            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->update('user_message_thread_status', array(
                'is_trash' => 1,
                'trash_timestamp' => date('Y-m-d H:i:s'),
                'is_draft' => 0,
                'draft_timestamp' => null,
                'is_favorite' => 0,
                'favorite_timestamp' => null
            ));
        } catch (Exception $e) {
            log_message('error', 'Error in delete_message_thread: ' . $e->getMessage());
            return false;
        }
    }

    public function draft_message_thread($message_thread_code, $user_id, $user_group)
    {
        try {
            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->update('user_message_thread_status', array(
                'is_draft' => 1,
                'draft_timestamp' => date('Y-m-d H:i:s'),
                'is_trash' => 0,
                'trash_timestamp' => null
            ));
        } catch (Exception $e) {
            log_message('error', 'Error in draft_message_thread: ' . $e->getMessage());
            return false;
        }
    }

    public function read_message_thread($message_thread_code, $user_id, $user_group)
    {
        try {
            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->update('user_message_status', array(
                'new_messages_count' => 0,
                'last_seen_timestamp' => date('Y-m-d H:i:s') 
            ));
        } catch (Exception $e) {
            log_message('error', 'Error in read_message_thread: ' . $e->getMessage());
            return false;
        }
    }

    public function unread_message_thread($message_thread_code, $user_id, $user_group)
    {
        try {
            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->update('user_message_status', array(
                'new_messages_count' => 1,
                'last_seen_timestamp' => null
            ));
        } catch (Exception $e) {
            log_message('error', 'Error in unread_message_thread: ' . $e->getMessage());
            return false;
        }
    }

    public function add_favorite_message_thread($message_thread_code, $user_id, $user_group)
    {
        try {
            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->update('user_message_thread_status', array(
                'is_favorite' => 1,
                'favorite_timestamp' => date('Y-m-d H:i:s')
            ));
        } catch (Exception $e) {
            log_message('error', 'Error in add_favorite_message_thread: ' . $e->getMessage());
            return false;
        }
    }

    public function remove_favorite_message_thread($message_thread_code, $user_id, $user_group)
    {
        try {
            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->update('user_message_thread_status', array(
                'is_favorite' => 0,
                'favorite_timestamp' => null
            ));
        } catch (Exception $e) {
            log_message('error', 'Error in remove_favorite_message_thread: ' . $e->getMessage());
            return false;
        }
    }

}