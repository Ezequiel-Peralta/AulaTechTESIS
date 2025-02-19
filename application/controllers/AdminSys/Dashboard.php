<?php

class Dashboard extends CI_Controller
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
            redirect(base_url(), 'refresh');

        $user_id = $this->session->userdata('login_user_id'); 
        $user_group = $this->session->userdata('login_type');

        // Consultar en la tabla event_visibility para obtener los event_id visibles para el usuario actual
        $this->db->select('event_id');
        $this->db->from('event_visibility');

        // Filtro para visible_to == "my_account"
        $this->db->group_start(); // Abrir un grupo de condiciones
        $this->db->where('visible_to', 'my_account');
        $this->db->where('created_by_user_id', $user_id);
        $this->db->where('created_by_group', $user_group);
        $this->db->group_end(); // Cerrar el grupo de condiciones

        // Agregar el filtro por el creador del evento (created_by_user_id y created_by_group)
        // $this->db->or_group_start(); // Crear una condición "OR"
        // $this->db->where('created_by_user_id', $user_id);
        // $this->db->where('created_by_group', $user_group);
        // $this->db->group_end(); // Cerrar el grupo de condiciones "OR"

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

        // Obtener los event_id visibles
        $visible_event_ids = $this->db->get()->result_array();

        // Extraer solo los event_id en un array
        $event_ids = array_column($visible_event_ids, 'event_id');

        // Si hay eventos visibles, hacer la consulta en la tabla events
        if (!empty($event_ids)) {
            // Traer los eventos de la base de datos basados en los event_id filtrados
            $this->db->select('event_id, title, body, date, start, end, color, type'); // Agregar event_id aquí
            $this->db->where_in('event_id', $event_ids);
            $this->db->where('status_id', 1); 
            $events = $this->db->get('events')->result_array();

            // Procesar los eventos
            foreach ($events as &$event) {
                if (!is_null($event['date'])) {
                    // El evento es de un solo día sin hora
                    $event['start'] = $event['date']; // El campo 'start' será el día
                    $event['end'] = null; // No hay hora de fin
                    $event['allDay'] = true; // Evento de día completo
                    $event['className'] = $event['color']; 
                } else {
                    // El evento tiene hora específica
                    $event['allDay'] = false; // No es un evento de día completo
                    // Los campos 'start' y 'end' ya contienen los valores adecuados
                }

                // Eliminar la columna 'date' ya que no es necesaria en el formato de fullCalendar
                unset($event['date']);
            }

            // Pasar los eventos a la vista como JSON
            $page_data['events'] = json_encode($events);
        } else {
            // Si no hay eventos visibles, pasar un array vacío
            $page_data['events'] = json_encode([]);
        }



        // Consultar en la tabla event_visibility para obtener los event_id visibles para el usuario actual
        $this->db->select('event_id');
        $this->db->from('event_visibility');

        // Filtro para visible_to == "my_account"
        $this->db->group_start(); // Abrir un grupo de condiciones
        $this->db->where('visible_to', 'my_account');
        $this->db->where('created_by_user_id', $user_id);
        $this->db->where('created_by_group', $user_group);
        $this->db->group_end(); // Cerrar el grupo de condiciones

        // Agregar el filtro por el creador del evento (created_by_user_id y created_by_group)
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

        // Obtener los event_id visibles
        $visible_event_ids = $this->db->get()->result_array();

        // Extraer solo los event_id en un array
        $event_ids = array_column($visible_event_ids, 'event_id');

        // Si hay eventos visibles, hacer la consulta en la tabla events con status_id 1
        if (!empty($event_ids)) {
            // Traer los eventos activos (status_id 1)
            $this->db->select('event_id, title, body, date, start, end, color, type, status_id');
            $this->db->where_in('event_id', $event_ids);
            $this->db->where('status_id', 1); // Filtro por status_id 1
            $events = $this->db->get('events')->result_array();

            // Procesar los eventos
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

            $page_data['events'] = json_encode($events);
        } else {
            $page_data['events'] = json_encode([]);
        }

        // Ahora, repetir el código para traer los eventos deshabilitados (status_id 0)
        if (!empty($event_ids)) {
            // Traer los eventos deshabilitados (status_id 0)
            $this->db->select('event_id, title, body, date, start, end, color, type, status_id');
            $this->db->where_in('event_id', $event_ids);
            $this->db->where('status_id', 0); // Filtro por status_id 0
            $disabled_events = $this->db->get('events')->result_array();

            // Procesar los eventos
            foreach ($disabled_events as &$event) {
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

            $page_data['disabledEvents'] = json_encode($disabled_events);
        } else {
            $page_data['disabledEvents'] = json_encode([]);
        }





        // Configuración de la página
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('dashboard')),
                'url' => base_url('index.php?admin/dashboard')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name']  = 'dashboard';
        $page_data['page_icon']  = 'entypo-gauge';
        $page_data['page_title'] = ucfirst(get_phrase('dashboard'));
        $this->load->view('backend/index', $page_data);
    }


}

   