<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Message extends CI_Controller
{
    
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');

        date_default_timezone_set('America/Argentina/Buenos_Aires');
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		
    }
    
    public function index()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
    }
    


function message($param1 = 'message_default', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
    
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
        $this->db->select('COUNT(*) as unread_count');
        $this->db->from('message_thread');

        // Realizar un INNER JOIN con user_message_thread_status usando el message_thread_code
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
        $this->db->join('user_message_status', 'message_thread.message_thread_code = user_message_status.message_thread_code', 'inner');

        // Agrupar las condiciones relacionadas con el usuario
        $this->db->group_start();
        $this->db->where('message_thread.sender_id', $user_id);
        $this->db->where('message_thread.sender_group', $user_group);
        $this->db->or_where('message_thread.receiver_id', $user_id);
        $this->db->where('message_thread.receiver_group', $user_group);

        // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
        $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');

        // Comprobar si el usuario está en bcc
        $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');

        $this->db->group_end();

        // Agregar condiciones para el estado del mensaje
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);

        // Asegúrate de que los mensajes no estén en la papelera o como borradores
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('user_message_thread_status.is_draft', 0);

        $this->db->where('user_message_status.user_id', $user_id);
        $this->db->where('user_message_status.user_group', $user_group);
        // Filtrar por mensajes no leídos
        $this->db->where('user_message_status.new_messages_count >', 0);

        // Ejecutar la consulta
        $unread_count_query = $this->db->get();
        $unread_count_result = $unread_count_query->row_array();
        $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;
 
         $this->db->select('COUNT(*) as received_count');
         $this->db->from('message_thread');
         
         // Realizar un INNER JOIN con user_message_thread_status
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->where('message_thread.is_trash', 0);

         $this->db->group_start();
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->or_where('message_thread.receiver_id', $user_id);
         $this->db->where('message_thread.receiver_group', $user_group);
         
         // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
         $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
         
         // Comprobar si el usuario está en bcc
         $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
         $this->db->group_end();
         
         // Agregar condiciones para is_trash e is_draft
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
         
         $received_count_query = $this->db->get();
         $received_count_result = $received_count_query->row_array();
         $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;

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
         
         $sent_count_query = $this->db->get();
         $sent_count_result = $sent_count_query->row_array();
         $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
 
           // Contar los borradores (draft_count)
         $this->db->select('COUNT(*) as draft_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_draft', 1);
 
         // Ejecutar la consulta
         $draft_count_query = $this->db->get();
         $draft_count_result = $draft_count_query->row_array();
         $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;
 
         $this->db->select('COUNT(*) as trash_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_trash', 1);
 
         // Ejecutar la consulta
         $trash_count_query = $this->db->get();
         $trash_count_result = $trash_count_query->row_array();
         $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;
 
 
         $this->db->select('COUNT(*) as favorite_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_favorite', 1);
 
         // Ejecutar la consulta
         $favorite_count_query = $this->db->get();
         $favorite_count_result = $favorite_count_query->row_array();
         $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;

         $message_counts = [
            'urgent' => 0,
            'homework' => 0,
            'announcement' => 0,
            'meeting' => 0,
            'event' => 0,
            'reminder' => 0,
            'grade_report' => 0,
            'exam' => 0,
            'behavior' => 0,
            'important' => 0
        ];

        $active_tag_count = 0;
        
        // Obtener los tags directamente de la base de datos
        $this->db->select('tags');
        $this->db->from('message_thread mt');
        
        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Ejecutar la consulta para obtener las etiquetas
        $tags_query = $this->db->get();
        $tags_results = $tags_query->result_array();
        
        foreach ($tags_results as $tags_row) {
            $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
            if (!empty($current_tags)) {
                // Contar los tags específicos
                foreach ($current_tags as $tag) {
                    if (isset($message_counts[$tag])) {
                        $message_counts[$tag]++; // Incrementar el contador de ese tag
                    }
                    // Verificar si el tag coincide con param1
                    if ($tag === $param1) {
                        $active_tag_count++; // Incrementar el contador de tags activos
                    }
                }
            }
        }


        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

        // Obtener los mensajes y los hilos de mensajes
        $this->db->select('m.message_id, m.message_thread_code, m.message, m.sender_id, m.sender_group, 
         m.has_text, has_image, has_video, has_audio, has_document, mt.message_thread_id, mt.message_thread_code, mt.receiver_id, mt.receiver_group, mt.last_message_timestamp, 
        mt.last_sender_id,  mt.last_sender_group, umts.is_favorite, umts.is_draft, umts.is_trash, mt.subject');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');

        // Unión adicional con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status umts', 'mt.message_thread_code = umts.message_thread_code');

        // Iniciar agrupamiento de condiciones
        $this->db->group_start();

        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();

        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();

        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.cc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.bcc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Cerrar agrupamiento de condiciones
        $this->db->group_end();

        // Agregar las condiciones comunes (fuera de los OR)
        // Ahora is_draft, is_trash y is_favorite están en la tabla user_message_thread_status (umts)
        $this->db->where('umts.user_id', $user_id);
        $this->db->where('umts.user_group', $user_group);
        $this->db->where('umts.is_draft', 0);
        $this->db->where('umts.is_trash', 0);

        // Ejecutar la consulta
        $query = $this->db->get();
        $messages = $query->result_array();
    
        $grouped_messages = [];
    
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];
    
            // Consulta para obtener los detalles del sender
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['sender_group'] . '_details');
            $this->db->where($message['sender_group'] . '_id', $message['sender_id']);
            $sender_details_query = $this->db->get();
            $sender_details = $sender_details_query->row_array();
    
            // Último remitente
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['last_sender_group'] . '_details');
            $this->db->where($message['last_sender_group'] . '_id', $message['last_sender_id']);
            $last_sender_details_query = $this->db->get();
            $last_sender_details = $last_sender_details_query->row_array();
    
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];

            $message['last_sender_firstname'] = $last_sender_details['firstname'];
            $message['last_sender_lastname'] = $last_sender_details['lastname'];
            $message['last_sender_photo'] = $last_sender_details['photo'];

            // Inicializar si no existe
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = $message;
                $grouped_messages[$thread_code]['tags'] = [];
                $grouped_messages[$thread_code]['has_text'] = !empty($message['has_text']);
                $grouped_messages[$thread_code]['has_image'] = !empty($message['has_image']);
                $grouped_messages[$thread_code]['has_video'] = !empty($message['has_video']);
                $grouped_messages[$thread_code]['has_audio'] = !empty($message['has_audio']);
                $grouped_messages[$thread_code]['has_document'] = !empty($message['has_document']);
                $grouped_messages[$thread_code]['new_message_count'] = 0;
            }
            
            // Verificar si hay attachments
            if (!empty($message['has_text'])) {
                $grouped_messages[$thread_code]['has_text'] = true;
            }
            if (!empty($message['has_image'])) {
                $grouped_messages[$thread_code]['has_image'] = true;
            }
            if (!empty($message['has_video'])) {
                $grouped_messages[$thread_code]['has_video'] = true;
            }
            if (!empty($message['has_audio'])) {
                $grouped_messages[$thread_code]['has_audio'] = true;
            }
            if (!empty($message['has_document'])) {
                $grouped_messages[$thread_code]['has_document'] = true;
            }
    
            // Verificar la cantidad de mensajes no leídos en este thread
            $this->db->select('new_messages_count');
            $this->db->from('user_message_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('message_thread_code', $thread_code);
        
            $new_messages_query = $this->db->get();
            $new_messages_result = $new_messages_query->row_array();
            $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;
    
            // Actualizar si el timestamp es más reciente
            if (strtotime($message['last_message_timestamp']) > strtotime($grouped_messages[$thread_code]['last_message_timestamp'])) {
                $grouped_messages[$thread_code] = $message;
            }
        }

       
    
        // Obtener los tags y subjects
        foreach ($grouped_messages as $thread_code => &$grouped_message) {
            $this->db->select('tags');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $tags_query = $this->db->get();
            $tags_results = $tags_query->result_array();

            foreach ($tags_results as $tags_row) {
                $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
                if (!empty($current_tags)) {
                    // Mezclar los tags actuales con los ya agrupados
                    $grouped_message['tags'] = array_unique(array_merge($grouped_message['tags'], $current_tags));
                    
                  
                }
            }
    
            $this->db->select('subject');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->limit(1);
            $subject_query = $this->db->get();
            if ($subject_row = $subject_query->row_array()) {
                $grouped_message['subject'] = $subject_row['subject'];
            }
        }

    
        usort($grouped_messages, function ($a, $b) {
            if ($a['is_favorite'] == $b['is_favorite']) {
                return strtotime($b['last_message_timestamp']) - strtotime($a['last_message_timestamp']);
            }
            return $b['is_favorite'] - $a['is_favorite'];
        });
    
        $page_data['messages'] = array_values($grouped_messages);
        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 
        $page_data['active_tag'] = $param1; 
        $page_data['active_tag_count'] = $active_tag_count;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('inbox')),
                'url' => base_url('index.php?admin/message')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message';
        $page_data['page_title'] = 'message';
        
    
        $this->load->view('backend/index', $page_data);
    }


    function message_favorite($param1 = 'message_default', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
    
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
    
         // Contar los mensajes no leídos (new_messages_count > 0)
         $this->db->select('COUNT(*) as unread_count');
         $this->db->from('user_message_status');
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('new_messages_count >', 0);
         
         $unread_count_query = $this->db->get();
         $unread_count_result = $unread_count_query->row_array();
         $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;
 
         $this->db->select('COUNT(*) as received_count');
         $this->db->from('message_thread');
         
         // Realizar un INNER JOIN con user_message_thread_status
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->group_start();
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->or_where('message_thread.receiver_id', $user_id);
         $this->db->where('message_thread.receiver_group', $user_group);
         
         // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
         $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
         
         // Comprobar si el usuario está en bcc
         $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
         $this->db->group_end();
         
         // Agregar condiciones para is_trash e is_draft
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $received_count_query = $this->db->get();
         $received_count_result = $received_count_query->row_array();
         $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;

         $this->db->select('COUNT(*) as sent_count');
         $this->db->from('message_thread');
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $sent_count_query = $this->db->get();
         $sent_count_result = $sent_count_query->row_array();
         $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
 
           // Contar los borradores (draft_count)
         $this->db->select('COUNT(*) as draft_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_draft', 1);
 
         // Ejecutar la consulta
         $draft_count_query = $this->db->get();
         $draft_count_result = $draft_count_query->row_array();
         $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;
 
         $this->db->select('COUNT(*) as trash_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_trash', 1);
 
         // Ejecutar la consulta
         $trash_count_query = $this->db->get();
         $trash_count_result = $trash_count_query->row_array();
         $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;
 
 
        $this->db->select('COUNT(*) as favorite_count');
        $this->db->from('message_thread');

        // Unir con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');

        // Filtrar por usuario, grupo y hilos marcados como favoritos
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);
        $this->db->where('user_message_thread_status.is_favorite', 1);

        // Filtrar por mensajes que no están en la papelera o como borradores
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('user_message_thread_status.is_draft', 0);
        $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');

        // Ejecutar la consulta
        $favorite_count_query = $this->db->get();
        $favorite_count_result = $favorite_count_query->row_array();
        $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;


        $message_counts = [
            'urgent' => 0,
            'homework' => 0,
            'announcement' => 0,
            'meeting' => 0,
            'event' => 0,
            'reminder' => 0,
            'grade_report' => 0,
            'exam' => 0,
            'behavior' => 0,
            'important' => 0
        ];

        $active_tag_count = 0;
        
        // Obtener los tags directamente de la base de datos
        $this->db->select('tags');
        $this->db->from('message_thread mt');
        
        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Ejecutar la consulta para obtener las etiquetas
        $tags_query = $this->db->get();
        $tags_results = $tags_query->result_array();
        
        foreach ($tags_results as $tags_row) {
            $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
            if (!empty($current_tags)) {
                // Contar los tags específicos
                foreach ($current_tags as $tag) {
                    if (isset($message_counts[$tag])) {
                        $message_counts[$tag]++; // Incrementar el contador de ese tag
                    }
                    // Verificar si el tag coincide con param1
                    if ($tag === $param1) {
                        $active_tag_count++; // Incrementar el contador de tags activos
                    }
                }
            }
        }


        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

        // Obtener los mensajes y los hilos de mensajes
        $this->db->select('m.message_id, m.message_thread_code, m.message, m.sender_id, m.sender_group, 
         has_image, has_video, has_audio, has_document, has_text, mt.message_thread_id, mt.message_thread_code, mt.receiver_id, mt.receiver_group, mt.last_message_timestamp, 
        mt.last_sender_id, mt.last_sender_group, umts.is_favorite, umts.is_draft, umts.is_trash, mt.subject');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');

        // Unión adicional con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status umts', 'mt.message_thread_code = umts.message_thread_code');

        // Iniciar agrupamiento de condiciones
        $this->db->group_start();

        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();

        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();

        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.cc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.bcc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Cerrar agrupamiento de condiciones
        $this->db->group_end();

        // Agregar las condiciones comunes (fuera de los OR)
        // Ahora is_draft, is_trash y is_favorite están en la tabla user_message_thread_status (umts)
        $this->db->where('umts.user_id', $user_id);
        $this->db->where('umts.user_group', $user_group);
        $this->db->where('umts.is_trash', 0);
        $this->db->where('umts.is_favorite', 1);

        // Ejecutar la consulta
        $query = $this->db->get();
        $messages = $query->result_array();
    
        $grouped_messages = [];
    
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];
    
            // Consulta para obtener los detalles del sender
            $this->db->select('firstname, lastname');
            $this->db->from($message['sender_group'] . '_details');
            $this->db->where($message['sender_group'] . '_id', $message['sender_id']);
            $sender_details_query = $this->db->get();
            $sender_details = $sender_details_query->row_array();
    
            // Último remitente
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['last_sender_group'] . '_details');
            $this->db->where($message['last_sender_group'] . '_id', $message['last_sender_id']);
            $last_sender_details_query = $this->db->get();
            $last_sender_details = $last_sender_details_query->row_array();
    
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];
            $message['last_sender_firstname'] = $last_sender_details['firstname'];
            $message['last_sender_lastname'] = $last_sender_details['lastname'];
            $message['last_sender_photo'] = $last_sender_details['photo'];
    
            // Inicializar si no existe
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = $message;
                $grouped_messages[$thread_code]['tags'] = [];
                $grouped_messages[$thread_code]['has_text'] = !empty($message['has_text']);
                $grouped_messages[$thread_code]['has_image'] = !empty($message['has_image']);
                $grouped_messages[$thread_code]['has_video'] = !empty($message['has_video']);
                $grouped_messages[$thread_code]['has_audio'] = !empty($message['has_audio']);
                $grouped_messages[$thread_code]['has_document'] = !empty($message['has_document']);
                $grouped_messages[$thread_code]['new_message_count'] = 0;
            }

            // Verificar si hay attachments
            if ($message['has_text'] == 1) {
                $grouped_messages[$thread_code]['has_text'] = true;
            }
            if ($message['has_image'] == 1) {
                $grouped_messages[$thread_code]['has_image'] = true;
            }
            if ($message['has_video'] == 1) {
                $grouped_messages[$thread_code]['has_video'] = true;
            }
            if ($message['has_audio'] == 1) {
                $grouped_messages[$thread_code]['has_audio'] = true;
            }
            if ($message['has_document'] == 1) {
                $grouped_messages[$thread_code]['has_document'] = true;
            }
           
    
            // Verificar la cantidad de mensajes no leídos en este thread
            $this->db->select('new_messages_count');
            $this->db->from('user_message_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('message_thread_code', $thread_code);
        
            $new_messages_query = $this->db->get();
            $new_messages_result = $new_messages_query->row_array();
            $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;
    
            // Actualizar si el timestamp es más reciente
            if (strtotime($message['last_message_timestamp']) > strtotime($grouped_messages[$thread_code]['last_message_timestamp'])) {
                $grouped_messages[$thread_code] = $message;
            }
        }
    
        // Obtener los tags y subjects
        foreach ($grouped_messages as $thread_code => &$grouped_message) {
            $this->db->select('tags');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $tags_query = $this->db->get();
            $tags_results = $tags_query->result_array();
            foreach ($tags_results as $tags_row) {
                $current_tags = json_decode($tags_row['tags'], true);
                if (!empty($current_tags)) {
                    $grouped_message['tags'] = array_unique(array_merge($grouped_message['tags'], $current_tags));
                }
            }
    
            $this->db->select('subject');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->limit(1);
            $subject_query = $this->db->get();
            if ($subject_row = $subject_query->row_array()) {
                $grouped_message['subject'] = $subject_row['subject'];
            }
        }
    
        usort($grouped_messages, function ($a, $b) {
            if ($a['is_favorite'] == $b['is_favorite']) {
                return strtotime($b['last_message_timestamp']) - strtotime($a['last_message_timestamp']);
            }
            return $b['is_favorite'] - $a['is_favorite'];
        });
    
        $page_data['messages'] = array_values($grouped_messages);
        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 
        $page_data['active_tag'] = $param1; 
        $page_data['active_tag_count'] = $active_tag_count;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('favorite')),
                'url' => base_url('index.php?admin/message')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_favorite';
        $page_data['page_title'] = 'message_favorite';
        
    
        $this->load->view('backend/index', $page_data);
    }




    function message_tag($param1 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
    
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
        $this->db->select('COUNT(*) as unread_count');
        $this->db->from('message_thread');

        // Realizar un INNER JOIN con user_message_thread_status usando el message_thread_code
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
        $this->db->join('user_message_status', 'message_thread.message_thread_code = user_message_status.message_thread_code', 'inner');

        // Agrupar las condiciones relacionadas con el usuario
        $this->db->group_start();
        $this->db->where('message_thread.sender_id', $user_id);
        $this->db->where('message_thread.sender_group', $user_group);
        $this->db->or_where('message_thread.receiver_id', $user_id);
        $this->db->where('message_thread.receiver_group', $user_group);

        // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
        $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');

        // Comprobar si el usuario está en bcc
        $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');

        $this->db->group_end();

        // Agregar condiciones para el estado del mensaje
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);

        // Asegúrate de que los mensajes no estén en la papelera o como borradores
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('user_message_thread_status.is_draft', 0);

        $this->db->where('user_message_status.user_id', $user_id);
        $this->db->where('user_message_status.user_group', $user_group);
        // Filtrar por mensajes no leídos
        $this->db->where('user_message_status.new_messages_count >', 0);

        // Ejecutar la consulta
        $unread_count_query = $this->db->get();
        $unread_count_result = $unread_count_query->row_array();
        $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;
 
         $this->db->select('COUNT(*) as received_count');
         $this->db->from('message_thread');
         
         // Realizar un INNER JOIN con user_message_thread_status
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->where('message_thread.is_trash', 0);

         $this->db->group_start();
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->or_where('message_thread.receiver_id', $user_id);
         $this->db->where('message_thread.receiver_group', $user_group);
         
         // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
         $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
         
         // Comprobar si el usuario está en bcc
         $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
         $this->db->group_end();
         
         // Agregar condiciones para is_trash e is_draft
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
         
         $received_count_query = $this->db->get();
         $received_count_result = $received_count_query->row_array();
         $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;

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
         
         $sent_count_query = $this->db->get();
         $sent_count_result = $sent_count_query->row_array();
         $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
 
           // Contar los borradores (draft_count)
         $this->db->select('COUNT(*) as draft_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_draft', 1);
 
         // Ejecutar la consulta
         $draft_count_query = $this->db->get();
         $draft_count_result = $draft_count_query->row_array();
         $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;
 
         $this->db->select('COUNT(*) as trash_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_trash', 1);
 
         // Ejecutar la consulta
         $trash_count_query = $this->db->get();
         $trash_count_result = $trash_count_query->row_array();
         $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;
 
 
         $this->db->select('COUNT(*) as favorite_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_favorite', 1);
 
         // Ejecutar la consulta
         $favorite_count_query = $this->db->get();
         $favorite_count_result = $favorite_count_query->row_array();
         $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;


         $message_counts = [
            'urgent' => 0,
            'homework' => 0,
            'announcement' => 0,
            'meeting' => 0,
            'event' => 0,
            'reminder' => 0,
            'grade_report' => 0,
            'exam' => 0,
            'behavior' => 0,
            'important' => 0
        ];

        $active_tag_count = 0;
        
        // Obtener los tags directamente de la base de datos
        $this->db->select('tags');
        $this->db->from('message_thread mt');
        
        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Ejecutar la consulta para obtener las etiquetas
        $tags_query = $this->db->get();
        $tags_results = $tags_query->result_array();
        
        foreach ($tags_results as $tags_row) {
            $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
            if (!empty($current_tags)) {
                // Contar los tags específicos
                foreach ($current_tags as $tag) {
                    if (isset($message_counts[$tag])) {
                        $message_counts[$tag]++; // Incrementar el contador de ese tag
                    }
                    // Verificar si el tag coincide con param1
                    if ($tag === $param1) {
                        $active_tag_count++; // Incrementar el contador de tags activos
                    }
                }
            }
        }





        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

       // Obtener los mensajes y los hilos de mensajes
        $this->db->select('m.message_id, m.message_thread_code, m.message, m.sender_id, m.sender_group, 
        m.has_text, has_image, has_video, has_audio, has_document, mt.message_thread_id, mt.message_thread_code, 
        mt.receiver_id, mt.receiver_group, mt.last_message_timestamp, mt.last_sender_id, mt.last_sender_group, 
        umts.is_favorite, umts.is_draft, umts.is_trash, mt.subject');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');

        // Unión adicional con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status umts', 'mt.message_thread_code = umts.message_thread_code');

        // Iniciar agrupamiento de condiciones
        $this->db->group_start();

        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();

        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();

        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.cc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.bcc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Cerrar agrupamiento de condiciones
        $this->db->group_end();

        // Filtrar mensajes que contengan el tag específico en JSON
        if ($param1 != '') {
        $this->db->where('mt.tags LIKE \'%"' . $param1 . '"%\'');
        }

       

        // Ejecutar la consulta
        $query = $this->db->get();
        $messages = $query->result_array();
    
        $grouped_messages = [];
    
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];
    
            // Consulta para obtener los detalles del sender
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['sender_group'] . '_details');
            $this->db->where($message['sender_group'] . '_id', $message['sender_id']);
            $sender_details_query = $this->db->get();
            $sender_details = $sender_details_query->row_array();
    
            // Último remitente
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['last_sender_group'] . '_details');
            $this->db->where($message['last_sender_group'] . '_id', $message['last_sender_id']);
            $last_sender_details_query = $this->db->get();
            $last_sender_details = $last_sender_details_query->row_array();
    
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];

            $message['last_sender_firstname'] = $last_sender_details['firstname'];
            $message['last_sender_lastname'] = $last_sender_details['lastname'];
            $message['last_sender_photo'] = $last_sender_details['photo'];

            $this->db->select('is_trash, is_draft');
            $this->db->from('user_message_thread_status');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->where('user_id', $user_id); // Coincide con el ID del usuario actual
            $this->db->where('user_group', $user_group); // Coincide con el grupo del usuario actual
            $thread_status_query = $this->db->get();
            $thread_status = $thread_status_query->row_array();
        
            if ($thread_status) {
                if ($thread_status['is_trash'] == 1 && $thread_status['is_draft'] == 0) {
                    $message['status'] = 'papelera';
                } elseif ($thread_status['is_trash'] == 0 && $thread_status['is_draft'] == 1) {
                    $message['status'] = 'archivado';
                } elseif ($thread_status['is_trash'] == 0 && $thread_status['is_draft'] == 0) {
                    $message['status'] = 'buzón';
                }
            }

            // Inicializar si no existe
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = $message;
                $grouped_messages[$thread_code]['tags'] = [];
                $grouped_messages[$thread_code]['has_text'] = !empty($message['has_text']);
                $grouped_messages[$thread_code]['has_image'] = !empty($message['has_image']);
                $grouped_messages[$thread_code]['has_video'] = !empty($message['has_video']);
                $grouped_messages[$thread_code]['has_audio'] = !empty($message['has_audio']);
                $grouped_messages[$thread_code]['has_document'] = !empty($message['has_document']);
                $grouped_messages[$thread_code]['new_message_count'] = 0;
            }
            
            // Verificar si hay attachments
            if (!empty($message['has_text'])) {
                $grouped_messages[$thread_code]['has_text'] = true;
            }
            if (!empty($message['has_image'])) {
                $grouped_messages[$thread_code]['has_image'] = true;
            }
            if (!empty($message['has_video'])) {
                $grouped_messages[$thread_code]['has_video'] = true;
            }
            if (!empty($message['has_audio'])) {
                $grouped_messages[$thread_code]['has_audio'] = true;
            }
            if (!empty($message['has_document'])) {
                $grouped_messages[$thread_code]['has_document'] = true;
            }
    
            // Verificar la cantidad de mensajes no leídos en este thread
            $this->db->select('new_messages_count');
            $this->db->from('user_message_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('message_thread_code', $thread_code);
        
            $new_messages_query = $this->db->get();
            $new_messages_result = $new_messages_query->row_array();
            $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;
    
            // Actualizar si el timestamp es más reciente
            if (strtotime($message['last_message_timestamp']) > strtotime($grouped_messages[$thread_code]['last_message_timestamp'])) {
                $grouped_messages[$thread_code] = $message;
            }

            $grouped_messages[$thread_code]['status'] = $message['status'];
        }

     
    
        // Obtener los tags y subjects
        foreach ($grouped_messages as $thread_code => &$grouped_message) {
            $this->db->select('tags');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $tags_query = $this->db->get();
            $tags_results = $tags_query->result_array();

            foreach ($tags_results as $tags_row) {
                $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
                if (!empty($current_tags)) {
                    // Mezclar los tags actuales con los ya agrupados
                    $grouped_message['tags'] = array_unique(array_merge($grouped_message['tags'], $current_tags));
                    
                }
            }
    
            $this->db->select('subject');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->limit(1);
            $subject_query = $this->db->get();
            if ($subject_row = $subject_query->row_array()) {
                $grouped_message['subject'] = $subject_row['subject'];
            }
        }

    
        usort($grouped_messages, function ($a, $b) {
            if ($a['is_favorite'] == $b['is_favorite']) {
                return strtotime($b['last_message_timestamp']) - strtotime($a['last_message_timestamp']);
            }
            return $b['is_favorite'] - $a['is_favorite'];
        });
    
        $page_data['messages'] = array_values($grouped_messages);
        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 
        $page_data['active_tag'] = $param1; 
        $page_data['active_tag_count'] = $active_tag_count;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('tag')),
                'url' => base_url('index.php?admin/message')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_tag';
        $page_data['page_title'] = 'message_tag';
        
    
        $this->load->view('backend/index', $page_data);
    }





    function message_draft($param1 = 'message_default', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
    
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
    
         // Contar los mensajes no leídos (new_messages_count > 0)
         $this->db->select('COUNT(*) as unread_count');
         $this->db->from('user_message_status');
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('new_messages_count >', 0);
         
         $unread_count_query = $this->db->get();
         $unread_count_result = $unread_count_query->row_array();
         $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;
 
         $this->db->select('COUNT(*) as received_count');
         $this->db->from('message_thread');
         
         // Realizar un INNER JOIN con user_message_thread_status
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->group_start();
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->or_where('message_thread.receiver_id', $user_id);
         $this->db->where('message_thread.receiver_group', $user_group);
         
         // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
         $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
         
         // Comprobar si el usuario está en bcc
         $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
         $this->db->group_end();
         
         // Agregar condiciones para is_trash e is_draft
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $received_count_query = $this->db->get();
         $received_count_result = $received_count_query->row_array();
         $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;

         $this->db->select('COUNT(*) as sent_count');
         $this->db->from('message_thread');
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $sent_count_query = $this->db->get();
         $sent_count_result = $sent_count_query->row_array();
         $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
 
         $this->db->select('COUNT(*) as draft_count');
        $this->db->from('message_thread');

        // Unir con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');

        // Filtrar por usuario, grupo y hilos marcados como borradores
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);
        $this->db->where('user_message_thread_status.is_draft', 1);

        // Filtrar por mensajes que no están en la papelera
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');

        // Ejecutar la consulta
        $draft_count_query = $this->db->get();
        $draft_count_result = $draft_count_query->row_array();
        $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;

 
        $this->db->select('COUNT(*) as trash_count');
        $this->db->from('message_thread');

        // Unir con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');

        // Filtrar por usuario, grupo y hilos que están en la papelera
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);
        $this->db->where('user_message_thread_status.is_trash', 1);
        $this->db->where('user_message_thread_status.is_draft', 0);

        // Ejecutar la consulta
        $trash_count_query = $this->db->get();
        $trash_count_result = $trash_count_query->row_array();
        $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;

 
 
         $this->db->select('COUNT(*) as favorite_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_favorite', 1);
         $this->db->where('is_trash', 0);
         $this->db->where('is_draft', 0);
 
         // Ejecutar la consulta
         $favorite_count_query = $this->db->get();
         $favorite_count_result = $favorite_count_query->row_array();
         $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;


         $message_counts = [
            'urgent' => 0,
            'homework' => 0,
            'announcement' => 0,
            'meeting' => 0,
            'event' => 0,
            'reminder' => 0,
            'grade_report' => 0,
            'exam' => 0,
            'behavior' => 0,
            'important' => 0
        ];

        $active_tag_count = 0;
        
        // Obtener los tags directamente de la base de datos
        $this->db->select('tags');
        $this->db->from('message_thread mt');
        
        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Ejecutar la consulta para obtener las etiquetas
        $tags_query = $this->db->get();
        $tags_results = $tags_query->result_array();
        
        foreach ($tags_results as $tags_row) {
            $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
            if (!empty($current_tags)) {
                // Contar los tags específicos
                foreach ($current_tags as $tag) {
                    if (isset($message_counts[$tag])) {
                        $message_counts[$tag]++; // Incrementar el contador de ese tag
                    }
                    // Verificar si el tag coincide con param1
                    if ($tag === $param1) {
                        $active_tag_count++; // Incrementar el contador de tags activos
                    }
                }
            }
        }


        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

        // Obtener los mensajes y los hilos de mensajes
        $this->db->select('m.message_id, m.message_thread_code, m.message, m.sender_id, m.sender_group, 
         m.has_image, m.has_video, m.has_audio, m.has_document, m.has_text, mt.message_thread_id, mt.message_thread_code, mt.receiver_id, mt.receiver_group, mt.last_message_timestamp, 
        mt.last_sender_id, mt.last_sender_group, umts.is_favorite, umts.is_draft, umts.is_trash, mt.subject');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');

        // Unión adicional con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status umts', 'mt.message_thread_code = umts.message_thread_code');

        // Iniciar agrupamiento de condiciones
        $this->db->group_start();

        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();

        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();

        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.cc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.bcc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Cerrar agrupamiento de condiciones
        $this->db->group_end();

        // Agregar las condiciones comunes (fuera de los OR)
        // Ahora is_draft, is_trash y is_favorite están en la tabla user_message_thread_status (umts)
        $this->db->where('umts.user_id', $user_id);
        $this->db->where('umts.user_group', $user_group);
        $this->db->where('umts.is_draft', 1);
        $this->db->where('umts.is_trash', 0);

        // Ejecutar la consulta
        $query = $this->db->get();
        $messages = $query->result_array();
    
        $grouped_messages = [];
    
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];
    
            // Consulta para obtener los detalles del sender
            $this->db->select('firstname, lastname');
            $this->db->from($message['sender_group'] . '_details');
            $this->db->where($message['sender_group'] . '_id', $message['sender_id']);
            $sender_details_query = $this->db->get();
            $sender_details = $sender_details_query->row_array();
    
            // Último remitente
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['last_sender_group'] . '_details');
            $this->db->where($message['last_sender_group'] . '_id', $message['last_sender_id']);
            $last_sender_details_query = $this->db->get();
            $last_sender_details = $last_sender_details_query->row_array();
    
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];
            $message['last_sender_firstname'] = $last_sender_details['firstname'];
            $message['last_sender_lastname'] = $last_sender_details['lastname'];
            $message['last_sender_photo'] = $last_sender_details['photo'];
    
            // Inicializar si no existe
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = $message;
                $grouped_messages[$thread_code]['tags'] = [];
                $grouped_messages[$thread_code]['has_text'] = !empty($message['has_text']);
                $grouped_messages[$thread_code]['has_image'] = !empty($message['has_image']);
                $grouped_messages[$thread_code]['has_video'] = !empty($message['has_video']);
                $grouped_messages[$thread_code]['has_audio'] = !empty($message['has_audio']);
                $grouped_messages[$thread_code]['has_document'] = !empty($message['has_document']);
                $grouped_messages[$thread_code]['new_message_count'] = 0;
            }
    
            // Verificar si hay attachments
            if ($message['has_text'] == 1) {
                $grouped_messages[$thread_code]['has_text'] = true;
            }
            if ($message['has_image'] == 1) {
                $grouped_messages[$thread_code]['has_image'] = true;
            }
            if ($message['has_video'] == 1) {
                $grouped_messages[$thread_code]['has_video'] = true;
            }
            if ($message['has_audio'] == 1) {
                $grouped_messages[$thread_code]['has_audio'] = true;
            }
            if ($message['has_document'] == 1) {
                $grouped_messages[$thread_code]['has_document'] = true;
            }
           
    
            // Verificar la cantidad de mensajes no leídos en este thread
            $this->db->select('new_messages_count');
            $this->db->from('user_message_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('message_thread_code', $thread_code);
        
            $new_messages_query = $this->db->get();
            $new_messages_result = $new_messages_query->row_array();
            $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;
    
            // Actualizar si el timestamp es más reciente
            if (strtotime($message['last_message_timestamp']) > strtotime($grouped_messages[$thread_code]['last_message_timestamp'])) {
                $grouped_messages[$thread_code] = $message;
            }
        }
    
        // Obtener los tags y subjects
        foreach ($grouped_messages as $thread_code => &$grouped_message) {
            $this->db->select('tags');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $tags_query = $this->db->get();
            $tags_results = $tags_query->result_array();
            foreach ($tags_results as $tags_row) {
                $current_tags = json_decode($tags_row['tags'], true);
                if (!empty($current_tags)) {
                    $grouped_message['tags'] = array_unique(array_merge($grouped_message['tags'], $current_tags));
                }
            }
    
            $this->db->select('subject');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->limit(1);
            $subject_query = $this->db->get();
            if ($subject_row = $subject_query->row_array()) {
                $grouped_message['subject'] = $subject_row['subject'];
            }
        }
    
        usort($grouped_messages, function ($a, $b) {
            if ($a['is_favorite'] == $b['is_favorite']) {
                return strtotime($b['last_message_timestamp']) - strtotime($a['last_message_timestamp']);
            }
            return $b['is_favorite'] - $a['is_favorite'];
        });
    
        $page_data['messages'] = array_values($grouped_messages);
        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 
        $page_data['active_tag'] = $param1; 
        $page_data['active_tag_count'] = $active_tag_count;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('draft')),
                'url' => base_url('index.php?admin/message draft')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_draft';
        $page_data['page_title'] = 'message draft';

    
        $this->load->view('backend/index', $page_data);
    }



    function message_trash($param1 = 'message_default', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
    
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
    
         // Contar los mensajes no leídos (new_messages_count > 0)
         $this->db->select('COUNT(*) as unread_count');
         $this->db->from('user_message_status');
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('new_messages_count >', 0);
         
         $unread_count_query = $this->db->get();
         $unread_count_result = $unread_count_query->row_array();
         $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;
 
         $this->db->select('COUNT(*) as received_count');
         $this->db->from('message_thread');
         
         // Realizar un INNER JOIN con user_message_thread_status
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->group_start();
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->or_where('message_thread.receiver_id', $user_id);
         $this->db->where('message_thread.receiver_group', $user_group);
         
         // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
         $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
         
         // Comprobar si el usuario está en bcc
         $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
         $this->db->group_end();
         
         // Agregar condiciones para is_trash e is_draft
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $received_count_query = $this->db->get();
         $received_count_result = $received_count_query->row_array();
         $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;

         $this->db->select('COUNT(*) as sent_count');
         $this->db->from('message_thread');
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $sent_count_query = $this->db->get();
         $sent_count_result = $sent_count_query->row_array();
         $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
 
           // Contar los borradores (draft_count)
         $this->db->select('COUNT(*) as draft_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_draft', 1);
 
         // Ejecutar la consulta
         $draft_count_query = $this->db->get();
         $draft_count_result = $draft_count_query->row_array();
         $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;
 
         $this->db->select('COUNT(*) as trash_count');
         $this->db->from('user_message_thread_status');
         
         // Filtrar por usuario, grupo y mensajes en papelera
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_trash', 1);
         
         // Excluir mensajes que están como borradores
         $this->db->where('is_draft', 0);
         
         // Ejecutar la consulta
         $trash_count_query = $this->db->get();
         $trash_count_result = $trash_count_query->row_array();
         $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;
         
 
 
         $this->db->select('COUNT(*) as favorite_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_favorite', 1);
 
         // Ejecutar la consulta
         $favorite_count_query = $this->db->get();
         $favorite_count_result = $favorite_count_query->row_array();
         $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;


         $message_counts = [
            'urgent' => 0,
            'homework' => 0,
            'announcement' => 0,
            'meeting' => 0,
            'event' => 0,
            'reminder' => 0,
            'grade_report' => 0,
            'exam' => 0,
            'behavior' => 0,
            'important' => 0
        ];

        $active_tag_count = 0;
        
        // Obtener los tags directamente de la base de datos
        $this->db->select('tags');
        $this->db->from('message_thread mt');
        
        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Ejecutar la consulta para obtener las etiquetas
        $tags_query = $this->db->get();
        $tags_results = $tags_query->result_array();
        
        foreach ($tags_results as $tags_row) {
            $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
            if (!empty($current_tags)) {
                // Contar los tags específicos
                foreach ($current_tags as $tag) {
                    if (isset($message_counts[$tag])) {
                        $message_counts[$tag]++; // Incrementar el contador de ese tag
                    }
                    // Verificar si el tag coincide con param1
                    if ($tag === $param1) {
                        $active_tag_count++; // Incrementar el contador de tags activos
                    }
                }
            }
        }


        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

        // Obtener los mensajes y los hilos de mensajes
        $this->db->select('m.message_id, m.message_thread_code, m.message, m.sender_id, m.sender_group, 
         m.has_image, m.has_video, m.has_audio, m.has_document, m.has_text, mt.message_thread_id, mt.message_thread_code, mt.receiver_id, mt.receiver_group, mt.last_message_timestamp, 
        mt.last_sender_id, mt.last_sender_group, umts.is_favorite, umts.is_draft, umts.is_trash, mt.subject');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');

        // Unión adicional con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status umts', 'mt.message_thread_code = umts.message_thread_code');

        // Iniciar agrupamiento de condiciones
        $this->db->group_start();

        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();

        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();

        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.cc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.bcc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Cerrar agrupamiento de condiciones
        $this->db->group_end();

        // Agregar las condiciones comunes (fuera de los OR)
        // Ahora is_draft, is_trash y is_favorite están en la tabla user_message_thread_status (umts)
        $this->db->where('umts.user_id', $user_id);
        $this->db->where('umts.user_group', $user_group);
        $this->db->where('umts.is_draft', 0);
        $this->db->where('umts.is_trash', 1);

        // Ejecutar la consulta
        $query = $this->db->get();
        $messages = $query->result_array();
    
        $grouped_messages = [];
    
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];

            // Consulta para obtener los detalles del sender
            $this->db->select('firstname, lastname');
            $this->db->from($message['sender_group'] . '_details');
            $this->db->where($message['sender_group'] . '_id', $message['sender_id']);
            $sender_details_query = $this->db->get();
            $sender_details = $sender_details_query->row_array();
    
            // Último remitente
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['last_sender_group'] . '_details');
            $this->db->where($message['last_sender_group'] . '_id', $message['last_sender_id']);
            $last_sender_details_query = $this->db->get();
            $last_sender_details = $last_sender_details_query->row_array();
    
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];
            $message['last_sender_firstname'] = $last_sender_details['firstname'];
            $message['last_sender_lastname'] = $last_sender_details['lastname'];
            $message['last_sender_photo'] = $last_sender_details['photo'];

            

            // Inicializar si no existe
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = $message;
                $grouped_messages[$thread_code]['tags'] = [];
                $grouped_messages[$thread_code]['has_text'] = !empty($message['has_text']);
                $grouped_messages[$thread_code]['has_image'] = !empty($message['has_image']);
                $grouped_messages[$thread_code]['has_video'] = !empty($message['has_video']);
                $grouped_messages[$thread_code]['has_audio'] = !empty($message['has_audio']);
                $grouped_messages[$thread_code]['has_document'] = !empty($message['has_document']);
                $grouped_messages[$thread_code]['new_message_count'] = 0;
            }
    
            // Verificar si hay attachments
            if ($message['has_text'] == 1) {
                $grouped_messages[$thread_code]['has_text'] = true;
            }
            if ($message['has_image'] == 1) {
                $grouped_messages[$thread_code]['has_image'] = true;
            }
            if ($message['has_video'] == 1) {
                $grouped_messages[$thread_code]['has_video'] = true;
            }
            if ($message['has_audio'] == 1) {
                $grouped_messages[$thread_code]['has_audio'] = true;
            }
            if ($message['has_document'] == 1) {
                $grouped_messages[$thread_code]['has_document'] = true;
            }
          
    
            // Verificar la cantidad de mensajes no leídos en este thread
            $this->db->select('new_messages_count');
            $this->db->from('user_message_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('message_thread_code', $thread_code);
        
            $new_messages_query = $this->db->get();
            $new_messages_result = $new_messages_query->row_array();
            $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;
    
            // Actualizar si el timestamp es más reciente
            if (strtotime($message['last_message_timestamp']) > strtotime($grouped_messages[$thread_code]['last_message_timestamp'])) {
                $grouped_messages[$thread_code] = $message;
            }

        }
    
        // Obtener los tags y subjects
        foreach ($grouped_messages as $thread_code => &$grouped_message) {
            $this->db->select('tags');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $tags_query = $this->db->get();
            $tags_results = $tags_query->result_array();
            foreach ($tags_results as $tags_row) {
                $current_tags = json_decode($tags_row['tags'], true);
                if (!empty($current_tags)) {
                    $grouped_message['tags'] = array_unique(array_merge($grouped_message['tags'], $current_tags));
                }
            }
    
            $this->db->select('subject');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->limit(1);
            $subject_query = $this->db->get();
            if ($subject_row = $subject_query->row_array()) {
                $grouped_message['subject'] = $subject_row['subject'];
            }
        }
    
        usort($grouped_messages, function ($a, $b) {
            if ($a['is_favorite'] == $b['is_favorite']) {
                return strtotime($b['last_message_timestamp']) - strtotime($a['last_message_timestamp']);
            }
            return $b['is_favorite'] - $a['is_favorite'];
        });
    
        $page_data['messages'] = array_values($grouped_messages);
        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 
        $page_data['active_tag'] = $param1; 
        $page_data['active_tag_count'] = $active_tag_count;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('trash')),
                'url' => base_url('index.php?admin/message')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_trash';
        $page_data['page_title'] = 'message trash';
    
        $this->load->view('backend/index', $page_data);
    }

    

    function message_read($message_thread_code = '') {
        // Verificar que el usuario esté logueado
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
    
        // Obtener el user_id y user_group de la sesión actual
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');

        // Contar los mensajes no leídos (new_messages_count > 0)
        $this->db->select('COUNT(*) as unread_count');
        $this->db->from('user_message_status');
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->where('new_messages_count >', 0);
        
        $unread_count_query = $this->db->get();
        $unread_count_result = $unread_count_query->row_array();
        $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;

        $this->db->select('COUNT(*) as received_count');
        $this->db->from('message_thread');
        
        // Realizar un INNER JOIN con user_message_thread_status
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
        
        $this->db->group_start();
        $this->db->where('message_thread.sender_id', $user_id);
        $this->db->where('message_thread.sender_group', $user_group);
        $this->db->or_where('message_thread.receiver_id', $user_id);
        $this->db->where('message_thread.receiver_group', $user_group);
        
        // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
        $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
        
        // Comprobar si el usuario está en bcc
        $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();
        
        // Agregar condiciones para is_trash e is_draft
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('user_message_thread_status.is_draft', 0);
        
        $received_count_query = $this->db->get();
        $received_count_result = $received_count_query->row_array();
        $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;


        $this->db->select('COUNT(*) as sent_count');
        $this->db->from('message_thread');
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
        $this->db->where('message_thread.sender_id', $user_id);
        $this->db->where('message_thread.sender_group', $user_group);
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('user_message_thread_status.is_draft', 0);
        
        $sent_count_query = $this->db->get();
        $sent_count_result = $sent_count_query->row_array();
        $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;

          // Contar los borradores (draft_count)
        $this->db->select('COUNT(*) as draft_count');
        $this->db->from('user_message_thread_status');
        
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('is_draft', 1);

        // Ejecutar la consulta
        $draft_count_query = $this->db->get();
        $draft_count_result = $draft_count_query->row_array();
        $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;

        $this->db->select('COUNT(*) as trash_count');
        $this->db->from('user_message_thread_status');
        
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('is_trash', 1);

        // Ejecutar la consulta
        $trash_count_query = $this->db->get();
        $trash_count_result = $trash_count_query->row_array();
        $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;



        $this->db->select('COUNT(*) as favorite_count');
        $this->db->from('user_message_thread_status');
        
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('is_favorite', 1);

        // Ejecutar la consulta
        $favorite_count_query = $this->db->get();
        $favorite_count_result = $favorite_count_query->row_array();
        $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;



        $this->db->select('is_favorite, is_trash, is_draft, trash_timestamp');
        $this->db->from('user_message_thread_status');
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->where('message_thread_code', $message_thread_code);
        
        // Ejecutar la consulta
        $user_message_thread_status_query = $this->db->get();
        $user_message_thread_status_result = $user_message_thread_status_query->row();
        
        $is_favorite = $user_message_thread_status_result->is_favorite; 
        $is_trash = $user_message_thread_status_result->is_trash; 
        $trash_timestamp = $user_message_thread_status_result->trash_timestamp;       
        $is_draft = $user_message_thread_status_result->is_draft; 
        

        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

        $this->db->select('is_trash, trash_timestamp');
        $this->db->from('message_thread');
        $this->db->where('message_thread_code', $message_thread_code);
        
        // Ejecutar la consulta
        $message_thread_status_query = $this->db->get();
        $message_thread_status_result = $message_thread_status_query->row();
        
        $is_trash_thread = $message_thread_status_result->is_trash; 
        $trash_timestamp_thread = $message_thread_status_result->trash_timestamp;       
    
        // Consulta para obtener los mensajes específicos según el message_thread_code
        $this->db->select('m.message_id, m.message_thread_code, m.timestamp, m.message, m.sender_id, m.sender_group, m.receiver_id, m.receiver_group, m.has_image, m.has_video, m.has_audio, m.has_document, m.has_text');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');
     
    
        if (!empty($message_thread_code)) {
            $this->db->where('m.message_thread_code', $message_thread_code);
        }
    
        $query = $this->db->get();
        $messages = $query->result_array();

        $this->db->select('message_thread_id, message_thread_code, last_message_timestamp, receiver_id AS mt_receiver_id, receiver_group AS mt_receiver_group, sender_id AS mt_sender_id, sender_group AS mt_sender_group
        , cc_users_ids AS mt_cc_users_ids, cc_groups AS mt_cc_groups, bcc_users_ids AS mt_bcc_users_ids, bcc_groups AS mt_bcc_groups, tags, subject AS mt_subject, is_trash, trash_timestamp');
        $this->db->from('message_thread');

        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->limit(1);

        $query2 = $this->db->get();
        $messages2 = $query2->row_array(); // Usar row_array() para obtener un único registro

      
        if (!empty($message_thread_code)) {
            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->update('user_message_status', ['new_messages_count' => 0]);

            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->update('user_message_status', ['last_seen_timestamp' => date('Y-m-d H:i:s')]);  
        }

        // Agrupar los mensajes por message_thread_code
        $grouped_messages = [];
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];
    
            // Obtener detalles del remitente (sender)
            $sender_details = $this->get_user_details($message['sender_group'], $message['sender_id']);
            $message['sender_email'] = $sender_details['email'];
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];
            $message['sender_photo'] = $sender_details['photo'];

            // Obtener detalles del destinatario (receiver)
            $receiver_details = $this->get_user_details($message['receiver_group'], $message['receiver_id']);
            $message['receiver_email'] = $receiver_details['email'];
            $message['receiver_firstname'] = $receiver_details['firstname'];
            $message['receiver_lastname'] = $receiver_details['lastname'];

            // Añadir detalles del mensaje al array
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = [
                    'messages' => [],
                    'has_attachments' => false,
                    'attachments' => [],
                ];
            }

            $attachments = [];
            if (!empty($message['has_image'])) {
                $attachments['images'] = json_decode($message['has_image'], true);
            }
            if (!empty($message['has_video'])) {
                $attachments['videos'] = json_decode($message['has_video'], true);
            }
            if (!empty($message['has_audio'])) {
                $attachments['audios'] = json_decode($message['has_audio'], true);
            }
            if (!empty($message['has_document'])) {
                $attachments['documents'] = json_decode($message['has_document'], true);
            }
            if (!empty($message['has_text'])) {
                $attachments['texts'] = json_decode($message['has_text'], true);
            }
        
            // Si hay archivos adjuntos, añadir al array y marcar has_attachments como true
            if (!empty($attachments)) {
                $grouped_messages[$thread_code]['has_attachments'] = true;
                $grouped_messages[$thread_code]['attachments'] = $attachments;
            }


             // Verificar la cantidad de mensajes no leídos en este thread
             $this->db->select('new_messages_count');
             $this->db->from('user_message_status');
             $this->db->where('user_id', $user_id);
             $this->db->where('message_thread_code', $thread_code);
         
             $new_messages_query = $this->db->get();
             $new_messages_result = $new_messages_query->row_array();
             $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;

    
            $grouped_messages[$thread_code]['messages'][] = [
                'sender_firstname' => $message['sender_firstname'],
                'sender_lastname' => $message['sender_lastname'],
                'sender_email' => $message['sender_email'],
                'sender_photo' => $message['sender_photo'],
                'receiver_firstname' => $message['receiver_firstname'],
                'receiver_lastname' => $message['receiver_lastname'],
                'receiver_email' => $message['receiver_email'],
                'timestamp' => $message['timestamp'],
                'message' => $message['message']
            ];
    
            // Verificar si hay attachments
            // if ($message['attachments'] == 1) {
            //     $grouped_messages[$thread_code]['has_attachments'] = true;
            // }
          
        }
    
        $page_data['messages'] = $grouped_messages;
        $page_data['attachments'] = $attachments;
        

        $grouped_messages2 = [];

        // Verifica que $messages2 no esté vacío
        if (!empty($messages2)) {
            $thread_code2 = $messages2['message_thread_code'];
        
            // Obtener detalles del remitente (sender)
            $sender_details2 = $this->get_user_details($messages2['mt_sender_group'], $messages2['mt_sender_id']);
            $messages2['sender_email'] = $sender_details2['email'];
            $messages2['sender_firstname'] = $sender_details2['firstname'];
            $messages2['sender_lastname'] = $sender_details2['lastname'];
            $messages2['sender_photo'] = $sender_details2['photo'];
        
            // Obtener detalles del destinatario (receiver)
            $receiver_details2 = $this->get_user_details($messages2['mt_receiver_group'], $messages2['mt_receiver_id']);
            $messages2['receiver_email'] = $receiver_details2['email'];
            $messages2['receiver_firstname'] = $receiver_details2['firstname'];
            $messages2['receiver_lastname'] = $receiver_details2['lastname'];
            $messages2['receiver_photo'] = $sender_details2['photo'];

            $mt_receiver_details2 = $this->get_user_details($messages2['mt_receiver_group'], $messages2['mt_receiver_id']);
            $messages2['mt_receiver_email'] = $mt_receiver_details2['email'];
            $messages2['mt_receiver_firstname'] = $mt_receiver_details2['firstname'];
            $messages2['mt_receiver_lastname'] = $mt_receiver_details2['lastname'];
            $messages2['mt_receiver_photo'] = $mt_receiver_details2['photo'];

            $mt_sender_details2 = $this->get_user_details($messages2['mt_sender_group'], $messages2['mt_sender_id']);
            $messages2['mt_sender_email'] = $mt_sender_details2['email'];
            $messages2['mt_sender_firstname'] = $mt_sender_details2['firstname'];
            $messages2['mt_sender_lastname'] = $mt_sender_details2['lastname'];
            $messages2['mt_sender_photo'] = $mt_sender_details2['photo'];

            // Obtener detalles de cc y bcc (si existen)
            $cc_details2 = [];
            if (!empty($messages2['mt_cc_users_ids']) && !empty($messages2['mt_cc_groups'])) {
                $cc_users_ids2 = json_decode($messages2['mt_cc_users_ids'], true);
                $cc_groups2 = json_decode($messages2['mt_cc_groups'], true);
        
                foreach ($cc_users_ids2 as $index2 => $cc_user_id2) {
                    $user_details2 = $this->get_user_details($cc_groups2[$index2], $cc_user_id2);
                    if ($user_details2) {
                        $cc_details2[] = [
                            'email' => $user_details2['email'],
                            'firstname' => $user_details2['firstname'],
                            'lastname' => $user_details2['lastname'],
                            'photo' => $user_details2['photo'],
                            'id' => $cc_user_id2,
                            'group' => $cc_groups2[$index2]
                        ];
                    }
                }
            }
        
            $bcc_details2 = [];
            if (!empty($messages2['mt_bcc_users_ids']) && !empty($messages2['mt_bcc_groups'])) {
                $bcc_users_ids2 = json_decode($messages2['mt_bcc_users_ids'], true);
                $bcc_groups2 = json_decode($messages2['mt_bcc_groups'], true);
        
                foreach ($bcc_users_ids2 as $index2 => $bcc_user_id2) {
                    $user_details2 = $this->get_user_details($bcc_groups2[$index2], $bcc_user_id2);
                    if ($user_details2) {
                        $bcc_details2[] = [
                            'email' => $user_details2['email'],
                            'firstname' => $user_details2['firstname'],
                            'lastname' => $user_details2['lastname'],
                            'photo' => $user_details2['photo'],
                            'id' => $bcc_user_id2,
                            'group' => $bcc_groups2[$index2]
                        ];
                    }
                }
            }

            // Añadir detalles del mensaje
            $grouped_messages2[$thread_code2] = [
                'messages' => [
                    [
                        'mt_receiver_id' => $messages2['mt_receiver_id'],
                        'mt_receiver_group' => $messages2['mt_receiver_group'],
                        'mt_receiver_firstname' => $messages2['mt_receiver_firstname'],
                        'mt_receiver_lastname' => $messages2['mt_receiver_lastname'],
                        'mt_receiver_email' => $messages2['mt_receiver_email'],
                        'mt_receiver_photo' => $messages2['mt_receiver_photo'],
                        'mt_sender_id' => $messages2['mt_sender_id'],
                        'mt_sender_group' => $messages2['mt_sender_group'],
                        'mt_sender_firstname' => $messages2['mt_sender_firstname'],
                        'mt_sender_lastname' => $messages2['mt_sender_lastname'],
                        'mt_sender_email' => $messages2['mt_sender_email'],
                        'mt_sender_photo' => $messages2['mt_sender_photo'],
                        'mt_subject' => $messages2['mt_subject'],
                        'mt_cc' => $cc_details2,
                        'mt_bcc' => $bcc_details2,
                    ]
                    ],
                    'tags' => [],
            ];

            $current_tags = json_decode($messages2['tags'], true);
            if (!empty($current_tags)) {
                // Asegurarse de que 'tags' esté inicializado en el array
                if (!isset($grouped_messages2[$thread_code2]['tags'])) {
                    $grouped_messages2[$thread_code2]['tags'] = [];
                }
                // Combinar los tags existentes con los nuevos, y eliminar duplicados
                $grouped_messages2[$thread_code2]['tags'] = array_unique(array_merge($grouped_messages2[$thread_code2]['tags'], $current_tags));
            }
        }
        
        $page_data['messages2'] = $grouped_messages2;

    
        // Definir el breadcrumb
      
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('read')),
                'url' => base_url('index.php?admin/message')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_read';
        $page_data['page_title'] = 'Messaging';
        $page_data['message_thread_code'] = $message_thread_code;
        $page_data['is_trash'] = $is_trash;
        $page_data['trash_timestamp'] = $trash_timestamp;
        $page_data['is_favorite'] = $is_favorite;
        $page_data['is_draft'] = $is_draft;
        $page_data['is_trash_thread'] = $is_trash_thread;

        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count;

        $page_data['mt_sender_id'] = $messages2['mt_sender_id'];
        $page_data['mt_sender_group'] = $messages2['mt_sender_group'];
        $page_data['result_message_tag'] = $result_message_tag; 

        // Cargar la vista
        $this->load->view('backend/index', $page_data);
    }
    
    function get_user_details($group, $id) {
        // Verificar que el grupo no esté vacío
        if (empty($group) || empty($id)) {
            return null; // O manejar el error de otra manera
        }
    
        // Primer consulta: Obtener firstname y lastname de la tabla de detalles
        $this->db->select('firstname, lastname, photo');
        $this->db->from($group . '_details');
        $this->db->where($group . '_id', $id);
        $query = $this->db->get();
        
        // Si no encuentra el usuario, retornar null
        if ($query->num_rows() == 0) {
            return null;
        }
    
        // Almacenar los detalles obtenidos
        $details = $query->row_array();
    
        // Segunda consulta: Obtener el email de la tabla principal
        $this->db->select('email');
        $this->db->from($group);
        $this->db->where($group . '_id', $id);
        $query = $this->db->get();
    
        // Si no encuentra el email, retornar null
        if ($query->num_rows() == 0) {
            return null;
        }
    
        // Combinar los resultados
        $email = $query->row_array();
        
        // Unir los detalles con el email
        return array_merge($details, $email);
    }
    
    
    function message_new($param1 = '', $param2 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

            $user_id = $this->session->userdata('login_user_id');
            $user_group = $this->session->userdata('login_type');

            $this->db->select('COUNT(*) as unread_count');
            $this->db->from('message_thread');
    
            // Realizar un INNER JOIN con user_message_thread_status usando el message_thread_code
            $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
            $this->db->join('user_message_status', 'message_thread.message_thread_code = user_message_status.message_thread_code', 'inner');
    
            // Agrupar las condiciones relacionadas con el usuario
            $this->db->group_start();
            $this->db->where('message_thread.sender_id', $user_id);
            $this->db->where('message_thread.sender_group', $user_group);
            $this->db->or_where('message_thread.receiver_id', $user_id);
            $this->db->where('message_thread.receiver_group', $user_group);
    
            // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
            $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
            $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
    
            // Comprobar si el usuario está en bcc
            $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
            $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
    
            $this->db->group_end();
    
            // Agregar condiciones para el estado del mensaje
            $this->db->where('user_message_thread_status.user_id', $user_id);
            $this->db->where('user_message_thread_status.user_group', $user_group);
    
            // Asegúrate de que los mensajes no estén en la papelera o como borradores
            $this->db->where('user_message_thread_status.is_trash', 0);
            $this->db->where('user_message_thread_status.is_draft', 0);
    
            $this->db->where('user_message_status.user_id', $user_id);
            $this->db->where('user_message_status.user_group', $user_group);
            // Filtrar por mensajes no leídos
            $this->db->where('user_message_status.new_messages_count >', 0);
    
            // Ejecutar la consulta
            $unread_count_query = $this->db->get();
            $unread_count_result = $unread_count_query->row_array();
            $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;
    

            $this->db->select('COUNT(*) as received_count');
            $this->db->from('message_thread');
            
            // Realizar un INNER JOIN con user_message_thread_status
            $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
            
            $this->db->where('message_thread.is_trash', 0);
   
            $this->db->group_start();
            $this->db->where('message_thread.sender_id', $user_id);
            $this->db->where('message_thread.sender_group', $user_group);
            $this->db->or_where('message_thread.receiver_id', $user_id);
            $this->db->where('message_thread.receiver_group', $user_group);
            
            // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
            $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
            $this->db->where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
            
            // Comprobar si el usuario está en bcc
            $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
            $this->db->where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
            $this->db->group_end();
            
            // Agregar condiciones para is_trash e is_draft
            $this->db->where('user_message_thread_status.user_id', $user_id);
            $this->db->where('user_message_thread_status.user_group', $user_group);
            $this->db->where('user_message_thread_status.is_trash', 0);
            $this->db->where('user_message_thread_status.is_draft', 0);
            $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
            
            $received_count_query = $this->db->get();
            $received_count_result = $received_count_query->row_array();
            $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;
   
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
            
            $sent_count_query = $this->db->get();
            $sent_count_result = $sent_count_query->row_array();
            $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
    
              // Contar los borradores (draft_count)
            $this->db->select('COUNT(*) as draft_count');
            $this->db->from('user_message_thread_status');
            
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->where('is_draft', 1);
    
            // Ejecutar la consulta
            $draft_count_query = $this->db->get();
            $draft_count_result = $draft_count_query->row_array();
            $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;
    
            $this->db->select('COUNT(*) as trash_count');
            $this->db->from('user_message_thread_status');
            
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->where('is_trash', 1);
    
            // Ejecutar la consulta
            $trash_count_query = $this->db->get();
            $trash_count_result = $trash_count_query->row_array();
            $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;
    
    
            $this->db->select('COUNT(*) as favorite_count');
            $this->db->from('user_message_thread_status');
            
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->where('is_favorite', 1);
    
            // Ejecutar la consulta
            $favorite_count_query = $this->db->get();
            $favorite_count_result = $favorite_count_query->row_array();
            $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;
   
            $message_counts = [
               'urgent' => 0,
               'homework' => 0,
               'announcement' => 0,
               'meeting' => 0,
               'event' => 0,
               'reminder' => 0,
               'grade_report' => 0,
               'exam' => 0,
               'behavior' => 0,
               'important' => 0
           ];
   
           $active_tag_count = 0;
           
           // Obtener los tags directamente de la base de datos
           $this->db->select('tags');
           $this->db->from('message_thread mt');
           
           // Condiciones de receptor
           $this->db->group_start();
           $this->db->where('mt.receiver_id', $user_id);
           $this->db->where('mt.receiver_group', $user_group);
           $this->db->group_end();
           
           // Condiciones de emisor
           $this->db->or_group_start();
           $this->db->where('mt.sender_id', $user_id);
           $this->db->where('mt.sender_group', $user_group);
           $this->db->group_end();
           
           // Condiciones de CC
           $this->db->or_group_start();
           $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
           $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
           $this->db->group_end();
           
           // Condiciones de BCC
           $this->db->or_group_start();
           $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
           $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
           $this->db->group_end();
           
           // Ejecutar la consulta para obtener las etiquetas
           $tags_query = $this->db->get();
           $tags_results = $tags_query->result_array();
           
           foreach ($tags_results as $tags_row) {
               $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
               if (!empty($current_tags)) {
                   // Contar los tags específicos
                   foreach ($current_tags as $tag) {
                       if (isset($message_counts[$tag])) {
                           $message_counts[$tag]++; // Incrementar el contador de ese tag
                       }
                       // Verificar si el tag coincide con param1
                       if ($tag === $param1) {
                           $active_tag_count++; // Incrementar el contador de tags activos
                       }
                   }
               }
           }
   
   
           $this->db->select('name, badge, label');
           $this->db->from('message_tag');
           $query_message_tag = $this->db->get();
           $result_message_tag = $query_message_tag->result_array();

        if ($param1 == 'send_new') {
            $message_thread_code = substr(md5(rand(100000000, 20000000000)), 0, 15); // Generar código único para el hilo de mensajes
    
            $subject = $this->input->post('subject');
            $message = $this->input->post('message'); // El cuerpo del mensaje
    
            $selectedValueTo = isset($_POST['to']) ? $_POST['to'] : null; // Solo un valor, como 'admin-1'

            $has_image = [];
            $has_video = [];
            $has_audio = [];
            $has_text = [];
            $has_document = [];
    
            $cc_users_ids = [];
            $cc_groups = [];
    
            $bcc_users_ids = [];
            $bcc_groups = [];
    
            $tags = [];
    
            // Procesar CC solo si no está vacío
            if (!empty($_POST['cc'])) {
                $selectedValuesCc = $_POST['cc']; // Valores de CC como 'admin-1', 'admin-2', etc.
    
                // Separar los valores de CC en arrays
                foreach ($selectedValuesCc as $value) {
                    $parts = explode('-', $value); // Separar el valor por el guion "-"
                    if (count($parts) == 2) {
                        $cc_groups[] = $parts[0];  // Parte antes del guion: grupo (e.g., 'admin')
                        $cc_users_ids[] = $parts[1]; // Parte después del guion: ID de usuario (e.g., '1')
                    }
                }
            }
    
            // Procesar BCC solo si no está vacío
            if (!empty($_POST['bcc'])) {
                $selectedValuesBcc = $_POST['bcc'];
    
                foreach ($selectedValuesBcc as $value) {
                    $parts = explode('-', $value); // Separar el valor por el guion "-"
                    if (count($parts) == 2) {
                        $bcc_groups[] = $parts[0];  // Parte antes del guion: grupo (e.g., 'admin')
                        $bcc_users_ids[] = $parts[1]; // Parte después del guion: ID de usuario (e.g., '1')
                    }
                }
            }
    
            // Procesar tags solo si no está vacío
            if (!empty($_POST['tags'])) {
                $selectedValuesTags = $_POST['tags'];
            
                foreach ($selectedValuesTags as $value) {
                    $tags[] = $value;  // Guardar directamente los valores seleccionados
                }
            }
    
            $to_user_id = null;
            $to_group = null;
            if ($selectedValueTo) {
                $to_parts = explode('-', $selectedValueTo); // Separar el valor por el guion "-"
                if (count($to_parts) == 2) {
                    $to_group = $to_parts[0];  // Parte antes del guion: grupo (e.g., 'admin')
                    $to_user_id = $to_parts[1]; // Parte después del guion: ID de usuario (e.g., '1')
                }
            }
    
            // Convertir los arrays de CC y BCC en formato JSON para guardar en la base de datos
            $cc_users_ids_json = !empty($cc_users_ids) ? json_encode($cc_users_ids) : null;
            $cc_groups_json = !empty($cc_groups) ? json_encode($cc_groups) : null;
    
            $bcc_users_ids_json = !empty($bcc_users_ids) ? json_encode($bcc_users_ids) : null;
            $bcc_groups_json = !empty($bcc_groups) ? json_encode($bcc_groups) : null;
    
            $tags_json = !empty($tags) ? json_encode($tags) : json_encode([]); 

            if (!empty($_FILES['attachments']['name'][0])) { // Verificar si hay archivos
                $attachment_directory = 'assets/attachments/' . $message_thread_code . '/';
                if (!is_dir($attachment_directory)) {
                    mkdir($attachment_directory, 0777, true); // Crear la carpeta si no existe
                }
        
                $this->load->library('upload'); // Cargar la librería de carga de archivos
                
                $files = $_FILES;
                $number_of_files = count($_FILES['attachments']['name']);
                $attachments = [];
        
                for ($i = 0; $i < $number_of_files; $i++) {
                    $_FILES['attachment']['name'] = $files['attachments']['name'][$i];
                    $_FILES['attachment']['type'] = $files['attachments']['type'][$i];
                    $_FILES['attachment']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                    $_FILES['attachment']['error'] = $files['attachments']['error'][$i];
                    $_FILES['attachment']['size'] = $files['attachments']['size'][$i];
        
                    $config['upload_path'] = $attachment_directory;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|txt|mp4|mp3|avi';
                    $config['max_size'] = '102400'; // 100MB maximo
                    // $config['file_name'] = time() . '_' . $_FILES['attachment']['name'];
                    $config['file_name'] = $_FILES['attachment']['name'];
        
                    $this->upload->initialize($config);
        
                    if ($this->upload->do_upload('attachment')) {
                        $upload_data = $this->upload->data();
                        $attachments[] = array(
                            'message_thread_code' => $message_thread_code,
                            'file_name' => $upload_data['file_name'],
                            'file_path' => $attachment_directory . $upload_data['file_name'],
                            'uploaded_timestamp' => date('Y-m-d H:i:s')
                        );
        
                        // Verificar la extensión del archivo y almacenar las rutas en los arrays correspondientes
                        $file_extension = strtolower(pathinfo($upload_data['file_name'], PATHINFO_EXTENSION));
                        if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                            $has_image[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['mp4', 'avi'])) {
                            $has_video[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['mp3'])) {
                            $has_audio[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['txt'])) {
                            $has_text[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                            $has_document[] = $attachment_directory . $upload_data['file_name'];
                        }
                    } else {
                        $this->session->set_flashdata('flash_message', array(
                            'title' => 'Error al cargar archivos',
                            'text' => $this->upload->display_errors(),
                            'icon' => 'error',
                            'showCloseButton' => 'true',
                            'confirmButtonText' => 'Aceptar',
                            'confirmButtonColor' => '#d33',
                        ));
                        redirect(base_url() . 'index.php?admin/message_new', 'refresh');
                    }
                }
            }

            $has_text_json = json_encode($has_text);
            $has_document_json = json_encode($has_document);
    
            // Lógica para insertar en la base de datos
            $dataMessage = array(
                'message_thread_code' => $message_thread_code,
                'message' => $message,
                'sender_id' => $this->session->userdata('login_user_id'),
                'sender_group' => $this->session->userdata('login_type'),
                'receiver_id' => $to_user_id, // El ID del usuario para el campo 'To'
                'receiver_group' => $to_group, // El grupo para el campo 'To'
                'timestamp' => date('Y/m/d H:i:s'),
                'has_image' => !empty($has_image) ? str_replace('\/', '/', json_encode($has_image)) : null,
                'has_video' => !empty($has_video) ? str_replace('\/', '/', json_encode($has_video)) : null,
                'has_audio' => !empty($has_audio) ? str_replace('\/', '/', json_encode($has_audio)) : null,
                'has_text' => !empty($has_text) ? str_replace('\/', '/', $has_text_json) : null,
                'has_document' => !empty($has_document) ? str_replace('\/', '/', $has_document_json) : null
            );
    
            $this->db->insert('message', $dataMessage);
    
            // Insertar en la tabla de hilos de mensajes
            $dataMessageThread = array(
                'message_thread_code' => $message_thread_code,
                'subject' => $subject,
                'tags' => $tags_json,
                'sender_id' => $this->session->userdata('login_user_id'),
                'sender_group' => $this->session->userdata('login_type'),
                'receiver_id' => $to_user_id,
                'receiver_group' => $to_group,
                'cc_users_ids' => $cc_users_ids_json, // Guardar los IDs de usuarios de CC en formato JSON
                'cc_groups' => $cc_groups_json, // Guardar los grupos de CC en formato JSON
                'bcc_users_ids' => $bcc_users_ids_json,
                'bcc_groups' => $bcc_groups_json,
                'last_sender_id' => $this->session->userdata('login_user_id'),
                'last_sender_group' => $this->session->userdata('login_type'),
                'last_message_timestamp' => date('Y/m/d H:i:s'), // Timestamp de la última actualización
                'is_trash' => 0,
                'trash_timestamp' => null
            );
    
            $this->db->insert('message_thread', $dataMessageThread);

            // Insertar los participantes en la tabla user_message_status
            $participants = [];

            // Agregar el remitente (sender)
            $participants[] = array(
                'message_thread_code' => $message_thread_code,
                'user_id' => $this->session->userdata('login_user_id'),
                'user_group' => $this->session->userdata('login_type'),
                'new_messages_count' => 0,
                'last_seen_timestamp' => date('Y/m/d H:i:s')
            );

            // Agregar el destinatario (receiver)
            if (!empty($to_user_id)) {
                $participants[] = array(
                    'message_thread_code' => $message_thread_code,
                    'user_id' => $to_user_id,
                    'user_group' => $to_group,
                    'new_messages_count' => 1,
                    'last_seen_timestamp' => NULL
                );
            }

            // Agregar los CC
            foreach ($cc_users_ids as $key => $cc_user_id) {
                $participants[] = array(
                    'message_thread_code' => $message_thread_code,
                    'user_id' => $cc_user_id,
                    'user_group' => $cc_groups[$key],
                    'new_messages_count' => 1,
                    'last_seen_timestamp' => NULL
                );
            }

            // Agregar los BCC
            foreach ($bcc_users_ids as $key => $bcc_user_id) {
                $participants[] = array(
                    'message_thread_code' => $message_thread_code,
                    'user_id' => $bcc_user_id,
                    'user_group' => $bcc_groups[$key],
                    'new_messages_count' => 1,
                    'last_seen_timestamp' => NULL
                );
            }

            // Insertar todos los participantes en la tabla user_message_status
            $this->db->insert_batch('user_message_status', $participants);

            // Insertar los participantes en la tabla user_message_status
            $participantsMessageThreadStatus = [];

            // Agregar el remitente (sender)
            $participantsMessageThreadStatus[] = array(
                'message_thread_code' => $message_thread_code,
                'user_id' => $this->session->userdata('login_user_id'),
                'user_group' => $this->session->userdata('login_type'),
                'is_favorite' => 0,
                'is_trash' => 0,
                'is_draft' => 0,
                'favorite_timestamp' => null,
                'favorite_timestamp' => null,
                'favorite_timestamp' => null,
                'is_trash_by_user_id' => null,
                'is_trash_by_user_group' => null
            );

            // Agregar el destinatario (receiver)
            if (!empty($to_user_id)) {
                $participantsMessageThreadStatus[] = array(
                    'message_thread_code' => $message_thread_code,
                    'user_id' => $to_user_id,
                    'user_group' => $to_group,
                    'is_favorite' => 0,
                    'is_trash' => 0,
                    'is_draft' => 0,
                    'favorite_timestamp' => null,
                    'favorite_timestamp' => null,
                    'favorite_timestamp' => null,
                    'is_trash_by_user_id' => null,
                    'is_trash_by_user_group' => null
                );
            }

            // Agregar los CC
            foreach ($cc_users_ids as $key => $cc_user_id) {
                $participantsMessageThreadStatus[] = array(
                    'message_thread_code' => $message_thread_code,
                    'user_id' => $cc_user_id,
                    'user_group' => $cc_groups[$key],
                    'is_favorite' => 0,
                    'is_trash' => 0,
                    'is_draft' => 0,
                    'favorite_timestamp' => null,
                    'favorite_timestamp' => null,
                    'favorite_timestamp' => null,
                    'is_trash_by_user_id' => null,
                    'is_trash_by_user_group' => null
                );
            }

            // Agregar los BCC
            foreach ($bcc_users_ids as $key => $bcc_user_id) {
                $participantsMessageThreadStatus[] = array(
                    'message_thread_code' => $message_thread_code,
                    'user_id' => $bcc_user_id,
                    'user_group' => $bcc_groups[$key],
                    'is_favorite' => 0,
                    'is_trash' => 0,
                    'is_draft' => 0,
                    'favorite_timestamp' => null,
                    'favorite_timestamp' => null,
                    'favorite_timestamp' => null,
                    'is_trash_by_user_id' => null,
                    'is_trash_by_user_group' => null
                );
            }

            // Insertar todos los participantes en la tabla user_message_status
            $this->db->insert_batch('user_message_thread_status', $participantsMessageThreadStatus);
    
            // Mensaje de confirmación
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Mensaje enviado!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
    
            redirect(base_url() . 'index.php?admin/message_read/' . $message_thread_code, 'refresh');
        }

        if ($param1 == 'send_reply') {
            $message_thread_code = $param2; // Generar código único para el hilo de mensajes
    
            $message = $this->input->post('message'); // El cuerpo del mensaje
    
            $selectedValueTo = $_POST['to']; // Solo un valor, como 'admin-1'
    
            $has_image = [];
            $has_video = [];
            $has_audio = [];
            $has_text = [];
            $has_document = [];

            $attachments = []; 

            // Separar el valor del destinatario (To)
            $to_parts = explode('-', $selectedValueTo); // Separar el valor por el guion "-"
            if (count($to_parts) == 2) {
                $to_group = $to_parts[0];  // Parte antes del guion: grupo (e.g., 'admin')
                $to_user_id = $to_parts[1]; // Parte después del guion: ID de usuario (e.g., '1')
            }

            if (!empty($_FILES['attachments']['name'][0])) { // Verificar si hay archivos
                $attachment_directory = 'assets/attachments/' . $message_thread_code . '/';
                if (!is_dir($attachment_directory)) {
                    mkdir($attachment_directory, 0777, true); // Crear la carpeta si no existe
                }
        
                $this->load->library('upload'); // Cargar la librería de carga de archivos
                
                $files = $_FILES;
                $number_of_files = count($_FILES['attachments']['name']);
                $attachments = [];
        
                for ($i = 0; $i < $number_of_files; $i++) {
                    $_FILES['attachment']['name'] = $files['attachments']['name'][$i];
                    $_FILES['attachment']['type'] = $files['attachments']['type'][$i];
                    $_FILES['attachment']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                    $_FILES['attachment']['error'] = $files['attachments']['error'][$i];
                    $_FILES['attachment']['size'] = $files['attachments']['size'][$i];
        
                    $config['upload_path'] = $attachment_directory;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|txt|mp4|mp3|avi';
                    $config['max_size'] = '102400'; // 100MB maximo
                    // $config['file_name'] = time() . '_' . $_FILES['attachment']['name'];
                    $config['file_name'] = $_FILES['attachment']['name'];
        
                    $this->upload->initialize($config);
        
                    if ($this->upload->do_upload('attachment')) {
                        $upload_data = $this->upload->data();
                        $attachments[] = array(
                            'message_thread_code' => $message_thread_code,
                            'file_name' => $upload_data['file_name'],
                            'file_path' => $attachment_directory . $upload_data['file_name'],
                            'uploaded_timestamp' => date('Y-m-d H:i:s')
                        );
        
                        // Verificar la extensión del archivo y almacenar las rutas en los arrays correspondientes
                        $file_extension = strtolower(pathinfo($upload_data['file_name'], PATHINFO_EXTENSION));
                        if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                            $has_image[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['mp4', 'avi'])) {
                            $has_video[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['mp3'])) {
                            $has_audio[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['txt'])) {
                            $has_text[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                            $has_document[] = $attachment_directory . $upload_data['file_name'];
                        }
                    } else {
                        $this->session->set_flashdata('flash_message', array(
                            'title' => 'Error al cargar archivos',
                            'text' => $this->upload->display_errors(),
                            'icon' => 'error',
                            'showCloseButton' => 'true',
                            'confirmButtonText' => 'Aceptar',
                            'confirmButtonColor' => '#d33',
                        ));
                        redirect(base_url() . 'index.php?admin/message_new', 'refresh');
                    }
                }
            }

            $has_text_json = json_encode($has_text);
            $has_document_json = json_encode($has_document);
    
            // Lógica para insertar en la base de datos
            $dataMessage = array(
                'message_thread_code' => $message_thread_code,
                'message' => $message,
                'sender_id' => $this->session->userdata('login_user_id'),
                'sender_group' => $this->session->userdata('login_type'),
                'receiver_id' => $to_user_id, // El ID del usuario para el campo 'To'
                'receiver_group' => $to_group, // El grupo para el campo 'To'
                'timestamp' => date('Y/m/d H:i:s'),
                'has_image' => !empty($has_image) ? str_replace('\/', '/', json_encode($has_image)) : null,
                'has_video' => !empty($has_video) ? str_replace('\/', '/', json_encode($has_video)) : null,
                'has_audio' => !empty($has_audio) ? str_replace('\/', '/', json_encode($has_audio)) : null,
                'has_text' => !empty($has_text) ? str_replace('\/', '/', $has_text_json) : null,
                'has_document' => !empty($has_document) ? str_replace('\/', '/', $has_document_json) : null
            );
    
            $this->db->insert('message', $dataMessage);
    
            // Insertar en la tabla de hilos de mensajes
            $dataMessageThread = array(
                'last_message_timestamp' => date('Y/m/d H:i:s'),
                'last_sender_id' => $this->session->userdata('login_user_id'),
                'last_sender_group' => $this->session->userdata('login_type')
            );
    
            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->update('message_thread', $dataMessageThread);

              $dataUserMessageThreadStatus = array(
                'is_draft' => 0,
                'draft_timestamp' => null
            );
    
            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->where('user_id', $this->session->userdata('login_user_id'));
            $this->db->where('user_group', $this->session->userdata('login_type'));
            $this->db->update('user_message_thread_status', $dataUserMessageThreadStatus);

            // Obtener todos los participantes de la tabla user_message_status según el message_thread_code
            $this->db->where('message_thread_code', $message_thread_code);
            $participants = $this->db->get('user_message_status')->result_array();

            // Actualizar el campo new_messages_count para cada participante
            foreach ($participants as $participant) {
                $user_id = $participant['user_id'];
                $user_group = $participant['user_group'];

                if ($user_id == $this->session->userdata('login_user_id') && $user_group == $this->session->userdata('login_type')) {
                    // Si el usuario es el sender, poner el new_messages_count en 0
                    $this->db->where('user_id', $user_id);
                    $this->db->where('user_group', $user_group);
                    $this->db->where('message_thread_code', $message_thread_code);
                    $this->db->update('user_message_status', ['new_messages_count' => 0]);
                } else {
                    // Para los demás, incrementar new_messages_count en 1
                    $this->db->set('new_messages_count', 'new_messages_count + 1', FALSE);
                    $this->db->where('user_id', $user_id);
                    $this->db->where('user_group', $user_group);
                    $this->db->where('message_thread_code', $message_thread_code);
                    $this->db->update('user_message_status');
                }
            }

    
            // Mensaje de confirmación
            // $this->session->set_flashdata('flash_message', array(
            //     'title' => 'Mensaje enviado!',
            //     'text' => '',
            //     'icon' => 'success',
            //     'showCloseButton' => 'true',
            //     'confirmButtonText' => 'Aceptar',
            //     'confirmButtonColor' => '#1a92c4',
            //     'timer' => '10000',
            //     'timerProgressBar' => 'true',
            // ));
    
            redirect(base_url() . 'index.php?admin/message_read/' . $message_thread_code, 'refresh');
        }
    
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('new')),
                'url' => base_url('index.php?admin/message')
            )
        );

        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_new';
        $page_data['page_title'] = 'message_new';
    
        // Cargar la vista
        $this->load->view('backend/index', $page_data);
    }
    

    function message_sent($param1 = 'message_default', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
    
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
    
         // Contar los mensajes no leídos (new_messages_count > 0)
        $this->db->select('COUNT(*) as unread_count');
        $this->db->from('user_message_status');

        // Unir la tabla message_thread a la consulta
        $this->db->join('message_thread', 'message_thread.message_thread_code = user_message_status.message_thread_code', 'inner');

        // Unir la tabla user_message_thread_status a la consulta
        $this->db->join('user_message_thread_status', 'user_message_status.message_thread_code = user_message_thread_status.message_thread_code', 'inner');

        // Filtrar por mensajes del usuario como sender o receiver
        $this->db->group_start();
        $this->db->where('message_thread.sender_id', $user_id);
        $this->db->where('message_thread.sender_group', $user_group);
        $this->db->or_where('message_thread.receiver_id', $user_id);
        $this->db->where('message_thread.receiver_group', $user_group);

        // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
        $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');

        // Comprobar si el usuario está en bcc
        $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');

        // Finalizar grupo de condiciones
        $this->db->group_end();

        // Agregar condiciones para is_trash e is_draft
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('user_message_thread_status.is_draft', 0);
        $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');

        // Condiciones adicionales para mensajes no leídos
        $this->db->where('user_message_status.user_id', $user_id);
        $this->db->where('user_message_status.user_group', $user_group);
        $this->db->where('new_messages_count >', 0);

        // Ejecutar la consulta
        $unread_count_query = $this->db->get();
        $unread_count_result = $unread_count_query->row_array();
        $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;

 
         $this->db->select('COUNT(*) as received_count');
         $this->db->from('message_thread');
         
         // Realizar un INNER JOIN con user_message_thread_status
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->group_start();
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->or_where('message_thread.receiver_id', $user_id);
         $this->db->where('message_thread.receiver_group', $user_group);
         
         // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
         $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
         
         // Comprobar si el usuario está en bcc
         $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
         $this->db->group_end();
         
         // Agregar condiciones para is_trash e is_draft
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
         
         $received_count_query = $this->db->get();
         $received_count_result = $received_count_query->row_array();
         $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;

         $this->db->select('COUNT(*) as sent_count');
         $this->db->from('message_thread');
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $sent_count_query = $this->db->get();
         $sent_count_result = $sent_count_query->row_array();
         $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
 
           // Contar los borradores (draft_count)
         $this->db->select('COUNT(*) as draft_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_draft', 1);
 
         // Ejecutar la consulta
         $draft_count_query = $this->db->get();
         $draft_count_result = $draft_count_query->row_array();
         $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;
 
         $this->db->select('COUNT(*) as trash_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_trash', 1);
 
         // Ejecutar la consulta
         $trash_count_query = $this->db->get();
         $trash_count_result = $trash_count_query->row_array();
         $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;
 
 
         $this->db->select('COUNT(*) as favorite_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_favorite', 1);
 
         // Ejecutar la consulta
         $favorite_count_query = $this->db->get();
         $favorite_count_result = $favorite_count_query->row_array();
         $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;

         $message_counts = [
            'urgent' => 0,
            'homework' => 0,
            'announcement' => 0,
            'meeting' => 0,
            'event' => 0,
            'reminder' => 0,
            'grade_report' => 0,
            'exam' => 0,
            'behavior' => 0,
            'important' => 0
        ];

        $active_tag_count = 0;
        
        // Obtener los tags directamente de la base de datos
        $this->db->select('tags');
        $this->db->from('message_thread mt');
        
        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Ejecutar la consulta para obtener las etiquetas
        $tags_query = $this->db->get();
        $tags_results = $tags_query->result_array();
        
        foreach ($tags_results as $tags_row) {
            $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
            if (!empty($current_tags)) {
                // Contar los tags específicos
                foreach ($current_tags as $tag) {
                    if (isset($message_counts[$tag])) {
                        $message_counts[$tag]++; // Incrementar el contador de ese tag
                    }
                    // Verificar si el tag coincide con param1
                    if ($tag === $param1) {
                        $active_tag_count++; // Incrementar el contador de tags activos
                    }
                }
            }
        }

        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

        // Obtener los mensajes y los hilos de mensajes
        $this->db->select('m.message_id, m.message_thread_code, m.message, m.sender_id, m.sender_group, 
         has_image, has_video, has_audio, has_document, has_text, mt.message_thread_id, mt.message_thread_code, mt.receiver_id, mt.receiver_group, mt.last_message_timestamp, 
        mt.last_sender_id, mt.last_sender_group, umts.is_favorite, umts.is_draft, umts.is_trash, mt.subject');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');

        // Unión adicional con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status umts', 'mt.message_thread_code = umts.message_thread_code');


        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        // Ahora is_draft, is_trash y is_favorite están en la tabla user_message_thread_status (umts)
        $this->db->where('umts.user_id', $user_id);
        $this->db->where('umts.user_group', $user_group);
        $this->db->where('umts.is_draft', 0);
        $this->db->where('umts.is_trash', 0);

        // Ejecutar la consulta
        $query = $this->db->get();
        $messages = $query->result_array();
    
        $grouped_messages = [];
    
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];
    
            // Consulta para obtener los detalles del sender
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['sender_group'] . '_details');
            $this->db->where($message['sender_group'] . '_id', $message['sender_id']);
            $sender_details_query = $this->db->get();
            $sender_details = $sender_details_query->row_array();
    
            // Último remitente
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['last_sender_group'] . '_details');
            $this->db->where($message['last_sender_group'] . '_id', $message['last_sender_id']);
            $last_sender_details_query = $this->db->get();
            $last_sender_details = $last_sender_details_query->row_array();
    
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];
            $message['sender_photo'] = $sender_details['photo'];

            $message['last_sender_firstname'] = $last_sender_details['firstname'];
            $message['last_sender_lastname'] = $last_sender_details['lastname'];
            $message['last_sender_photo'] = $last_sender_details['photo'];

            // Inicializar si no existe
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = $message;
                $grouped_messages[$thread_code]['tags'] = [];
                $grouped_messages[$thread_code]['has_text'] = !empty($message['has_text']);
                $grouped_messages[$thread_code]['has_image'] = !empty($message['has_image']);
                $grouped_messages[$thread_code]['has_video'] = !empty($message['has_video']);
                $grouped_messages[$thread_code]['has_audio'] = !empty($message['has_audio']);
                $grouped_messages[$thread_code]['has_document'] = !empty($message['has_document']);
                $grouped_messages[$thread_code]['new_message_count'] = 0;
            }
    
            // Verificar si hay attachments
            if ($message['has_text'] == 1) {
                $grouped_messages[$thread_code]['has_text'] = true;
            }
            if ($message['has_image'] == 1) {
                $grouped_messages[$thread_code]['has_image'] = true;
            }
            if ($message['has_video'] == 1) {
                $grouped_messages[$thread_code]['has_video'] = true;
            }
            if ($message['has_audio'] == 1) {
                $grouped_messages[$thread_code]['has_audio'] = true;
            }
            if ($message['has_document'] == 1) {
                $grouped_messages[$thread_code]['has_document'] = true;
            }
           
    
            // Verificar la cantidad de mensajes no leídos en este thread
            $this->db->select('new_messages_count');
            $this->db->from('user_message_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('message_thread_code', $thread_code);
        
            $new_messages_query = $this->db->get();
            $new_messages_result = $new_messages_query->row_array();
            $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;
    
            // Actualizar si el timestamp es más reciente
            if (strtotime($message['last_message_timestamp']) > strtotime($grouped_messages[$thread_code]['last_message_timestamp'])) {
                $grouped_messages[$thread_code] = $message;
            }
        }
    
        // Obtener los tags y subjects
        foreach ($grouped_messages as $thread_code => &$grouped_message) {
            $this->db->select('tags');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $tags_query = $this->db->get();
            $tags_results = $tags_query->result_array();
            foreach ($tags_results as $tags_row) {
                $current_tags = json_decode($tags_row['tags'], true);
                if (!empty($current_tags)) {
                    $grouped_message['tags'] = array_unique(array_merge($grouped_message['tags'], $current_tags));
                }
            }
    
            $this->db->select('subject');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->limit(1);
            $subject_query = $this->db->get();
            if ($subject_row = $subject_query->row_array()) {
                $grouped_message['subject'] = $subject_row['subject'];
            }
        }
    
        usort($grouped_messages, function ($a, $b) {
            if ($a['is_favorite'] == $b['is_favorite']) {
                return strtotime($b['last_message_timestamp']) - strtotime($a['last_message_timestamp']);
            }
            return $b['is_favorite'] - $a['is_favorite'];
        });
    
        $page_data['messages'] = array_values($grouped_messages);
        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 
        $page_data['active_tag'] = $param1; 
        $page_data['active_tag_count'] = $active_tag_count;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('sent')),
                'url' => base_url('index.php?admin/message')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_sent';
        $page_data['page_title'] = 'message_sent';
    
        $this->load->view('backend/index', $page_data);
    }


    function message_settings($param1 = '', $param2 = '', $param3 = '', $param4 = '') {
        // Verificar que el usuario esté logueado
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
        if ($param1 == 'favorite') {
            if ($param4 == 'add') {
                $this->db->where('message_thread_code', $param3);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_favorite' => 1,
                    'favorite_timestamp' => date('Y-m-d H:i:s') 
                ));
            } elseif ($param4 == 'remove') {
                $this->db->where('message_thread_code', $param3);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_favorite' => 0,
                    'favorite_timestamp' => null 
                ));
            }
            if ($param2 == 'message_read') {
                redirect(base_url() . 'index.php?admin/' . $param2 . '/' . $param3, 'refresh');
            } else if ($param2 == 'message') {
                redirect(base_url() . 'index.php?admin/' . $param2, 'refresh');
            }
        }

        if ($param1 == 'draft') {
            if ($param4 == 'add') {
                $this->db->where('message_thread_code', $param3);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_draft' => 1,
                    'draft_timestamp' => date('Y-m-d H:i:s') 
                ));
                redirect(base_url() . 'index.php?admin/message_draft/', 'refresh');
            } elseif ($param4 == 'remove') {
                $this->db->where('message_thread_code', $param3);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_draft' => 0,
                    'draft_timestamp' => null
                ));
                redirect(base_url() . 'index.php?admin/message/', 'refresh');
            }
          
        }

        if ($param1 == 'trash') {
            if ($param4 == 'add') {
                $this->db->where('message_thread_code', $param3);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 1,
                    'trash_timestamp' => date('Y-m-d H:i:s') 
                ));
                redirect(base_url() . 'index.php?admin/message_trash/', 'refresh');
            } elseif ($param4 == 'remove') {
                $this->db->where('message_thread_code', $param3);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 0,
                    'trash_timestamp' => null
                ));
                redirect(base_url() . 'index.php?admin/message/', 'refresh');
            }
        }
        if ($param3 == 'trash_for_user_message_thread') {
            if ($param2 == 'add') {
                $this->db->where('message_thread_code', $param1);
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
                redirect(base_url() . 'index.php?admin/message_trash/', 'refresh');
            } elseif ($param2 == 'remove') {
                $this->db->where('message_thread_code', $param1);
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
                redirect(base_url() . 'index.php?admin/message/', 'refresh');
            }
        }
        if ($param3 == 'trash_for_all_user_message_thread_owner') {
            if ($param2 == 'add') {
                // Actualizar la tabla message_thread
                $this->db->where('message_thread_code', $param1);
                $this->db->update('message_thread', array(
                    'is_trash' => 1,
                    'trash_timestamp' => date('Y-m-d H:i:s')
                ));
        
                // Actualizar la tabla user_message_thread_status
                $this->db->where('message_thread_code', $param1);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 1,
                    'is_favorite' => 0,
                    'trash_timestamp' => date('Y-m-d H:i:s'),
                    'favorite_timestamp' => null,
                    'is_trash_by_user_id' => $user_id,
                    'is_trash_by_user_group' => $user_group
                ));

                // redirect(base_url() . 'index.php?admin/message_read/' . $param1, 'refresh');
                redirect(base_url() . 'index.php?admin/message_trash/', 'refresh');
            } elseif ($param2 == 'remove') {
                // Actualizar la tabla message_thread
                $this->db->where('message_thread_code', $param1);
                $this->db->update('message_thread', array(
                    'is_trash' => 0,
                    'trash_timestamp' => null
                ));
        
                // Actualizar la tabla user_message_thread_status
                $this->db->where('message_thread_code', $param1);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 0,
                    'is_favorite' => 0,
                    'trash_timestamp' => null,
                    'favorite_timestamp' => null,
                    'is_trash_by_user_id' => null,
                    'is_trash_by_user_group' => null
                ));

                 // redirect(base_url() . 'index.php?admin/message_read/' . $param1, 'refresh');
                 redirect(base_url() . 'index.php?admin/message/', 'refresh');
            }
        }
        
        if($param1 == 'user_message_status') {
            if ($param3 == 'read') {
                $this->db->where('message_thread_code', $param2);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_status', array(
                    'new_messages_count' => 0,
                    'last_seen_timestamp' => date('Y-m-d H:i:s') 
                ));
            } elseif ($param3 == 'unread') {
                $this->db->where('message_thread_code', $param2);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_status', array(
                    'new_messages_count' => 1,
                    'last_seen_timestamp' => null 
                ));
            }
            redirect(base_url() . 'index.php?admin/'.$param4, 'refresh');
        }

        if ($param1 == 'move_to') {
            if ($param3 == 'draft') {
                $this->db->where('message_thread_code', $param2);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_draft' => 1,
                    'draft_timestamp' => date('Y-m-d H:i:s'),
                    'is_trash' => 0,
                    'trash_timestamp' => null
                ));
            } elseif ($param3 == 'trash') {
                $this->db->where('message_thread_code', $param2);
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
            } elseif ($param3 == 'inbox') {
                $this->db->where('message_thread_code', $param2);
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
            if ($param4 == 'message_read') {
                redirect(base_url() . 'index.php?admin/' . $param4 . '/' . $param2, 'refresh');
            } else if ($param4 == 'message') {
                redirect(base_url() . 'index.php?admin/' . $param4, 'refresh');
            } else if ($param4 == 'message_trash') {
                redirect(base_url() . 'index.php?admin/' . $param4, 'refresh');
            } else if ($param4 == 'message_draft') {
                redirect(base_url() . 'index.php?admin/' . $param4, 'refresh');
            } 
        }

        if ($param1 == 'delete_message_thread_bulk') {
            $message_thread_codes = explode('-', $param3); 
        
            foreach ($message_thread_codes as $message_thread_code) {
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
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Conversaciones enviadas a la papelera!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        
            redirect(base_url() . 'index.php?admin/' . $param2 . '/', 'refresh');
        }

        if ($param1 == 'draft_message_thread_bulk') {
            $message_thread_codes = explode('-', $param3); 
        
            foreach ($message_thread_codes as $message_thread_code) {
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

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Conversaciones archivadas correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        
            redirect(base_url() . 'index.php?admin/' . $param2 . '/', 'refresh');
        }

        if ($param1 == 'read_message_thread_bulk') {
            $message_thread_codes = explode('-', $param3); 
        
            foreach ($message_thread_codes as $message_thread_code) {
                $this->db->where('message_thread_code', $message_thread_code);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_status', array(
                    'new_messages_count' => 0,
                    'last_seen_timestamp' => date('Y-m-d H:i:s') 
                ));
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Conversaciones marcadas como vistas correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        
            redirect(base_url() . 'index.php?admin/' . $param2 . '/', 'refresh');
        }

        if ($param1 == 'unread_message_thread_bulk') {
            $message_thread_codes = explode('-', $param3); 
        
            foreach ($message_thread_codes as $message_thread_code) {
                $this->db->where('message_thread_code', $message_thread_code);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_status', array(
                    'new_messages_count' => 1,
                    'last_seen_timestamp' => null
                ));
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Conversaciones marcadas como no vistas correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        
            redirect(base_url() . 'index.php?admin/' . $param2 . '/', 'refresh');
        }

        if ($param1 == 'add_favorite_message_thread_bulk') {
            $message_thread_codes = explode('-', $param3); 
        
            foreach ($message_thread_codes as $message_thread_code) {
                $this->db->where('message_thread_code', $message_thread_code);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_favorite' => 1,
                    'favorite_timestamp' => null
                ));
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Conversaciones marcadas como favorito correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        
            redirect(base_url() . 'index.php?admin/' . $param2 . '/', 'refresh');
        }

        if ($param1 == 'remove_favorite_message_thread_bulk') {
            $message_thread_codes = explode('-', $param3); 
        
            foreach ($message_thread_codes as $message_thread_code) {
                $this->db->where('message_thread_code', $message_thread_code);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_favorite' => 0,
                    'favorite_timestamp' => null
                ));
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Conversaciones marcadas como no favorito correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        
            redirect(base_url() . 'index.php?admin/' . $param2 . '/', 'refresh');
        }
    
    }


}