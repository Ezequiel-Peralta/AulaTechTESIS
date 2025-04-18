<?php
class News_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function create_news($data) {
        try {
            $this->db->insert('news', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in create_news: ' . $e->getMessage());
            return false;
        }
    }

    public function update_news($news_id, $data) {
        try {
            $news_id = $this->db->escape_str($news_id);
            $this->db->where('news_id', $news_id);
            return $this->db->update('news', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_news: ' . $e->getMessage());
            return false;
        }
    }

    public function update_news_status($news_id, $status_id) {
        try {
            $news_id = $this->db->escape_str($news_id);
            $this->db->where('news_id', $news_id);
            return $this->db->update('news', array('status_id' => $status_id));
        } catch (Exception $e) {
            log_message('error', 'Error in update_news_status: ' . $e->getMessage());
            return false;
        }
    }

    public function get_news_by_id($news_id) {
        try {
            $news_id = $this->db->escape_str($news_id);
    
            log_message('error', 'Buscando noticia con ID: ' . $news_id);
    
            $result = $this->db->get_where('news', array('news_id' => $news_id))->row_array();
    
            if (empty($result)) {
                log_message('error', 'No se encontró la noticia con ID: ' . $news_id);
            } else {
                log_message('error', 'Noticia encontrada: ' . print_r($result, true));
            }
    
            return $result;
        } catch (Exception $e) {
            log_message('error', 'Error en get_news_by_id: ' . $e->getMessage());
            return false;
        }
    }
    

    public function get_news_types() {
        try {
            return $this->db->get('news_types')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_news_types: ' . $e->getMessage());
            return false;
        }
    }
}