<?php

class Events extends CI_Controller
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

    function events($param1 = '', $param2 = '', $param3 = '', $param4 = '', $param5 = '', $param6 = '', $param7 = '', $param8 = '', $param9 = '', $param10 = '', $param11 = '', $param12 = '', $param13 = '',  $param14 = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
    
        if ($param1 == 'create') {
            // Decodificamos los parámetros de la URL
            $event_title = urldecode($param2); 
            $event_body = urldecode($param3);
            $event_start = urldecode($param4); // Fecha de inicio
            $event_end = urldecode($param5); // Fecha de fin (puede ser null)
            $visible_to = urldecode($param6); // Corresponde a user_type
            $visible_to_category = urldecode($param7); // Corresponde a user_option

            // $visible_to_id = urldecode($param7); // Corresponde a visibility_id
            if(urldecode($param8) == 'null') {
                $visible_to_id = null;
            } else {
                $visible_to_id = urldecode($param8);
            }

            $AllDay = urldecode($param9); // Obtener el valor de allDay
            $event_color = urldecode($param10); // Color del evento
            $visibility_for_creator = urldecode($param11);
            $visible_edit = urldecode($param12);
            $visible_delete = urldecode($param13);
            $event_type = urldecode($param14);

            $created_by_user_id = $this->session->userdata('login_user_id'); 
            $created_by_group = $this->session->userdata('login_type');
            $created_at = date('Y-m-d H:i:s'); 
    
            // Validamos los parámetros
            if (empty($event_title) || empty($event_start)) {
                echo "Nombre del evento o fecha están vacíos.";
                return;
            }
    
            // Preparamos los datos para la inserción
            $event_data = array(
                'title' => $event_title,
                'body' => $event_body,
                'created_by_user_id' => $created_by_user_id,
                'created_by_group' => $created_by_group,
                'created_at' => $created_at, // Establece la fecha de creación
                'color' => $event_color,
                'type' => $event_type,
                'status_id' => 1
            );

            // Verificamos si es un evento de todo el día
            if ($AllDay === 'true') {
                // Si es allDay, insertamos solo en 'day' y ponemos null en 'start' y 'end'
                $event_data['date'] = $event_start; // Se inserta la fecha sin hora
                $event_data['start'] = null; // Null en start
                $event_data['end'] = null; // Null en end
                $event_data['allDay'] = true;
            } else if ($event_end === 'null') {
                // Si no es allDay, insertamos en 'start' y 'end' según corresponda
                $event_data['start'] = $event_start; // Usa la fecha de inicio
                $event_data['end'] = null; // Si hay fecha de fin, la insertamos, si no, null
                $event_data['date'] = null; // Null en 
                $event_data['allDay'] = false;
            } else {
                  // Si no es allDay, insertamos en 'start' y 'end' según corresponda
                  $event_data['start'] = $event_start; // Usa la fecha de inicio
                  $event_data['end'] = $event_end; // Si hay fecha de fin, la insertamos, si no, null
                  $event_data['date'] = null; // Null en 
                  $event_data['allDay'] = false;
            }
    
            if (!$this->db->insert('events', $event_data)) {
                echo "Error al insertar el evento: " . $this->db->last_query();
                return;
            }

         
    
            $event_id = $this->db->insert_id(); // ID del evento recién creado
    
            // Insertar en la tabla 'event_visibility'
            $visibility_data = array(
                'event_id' => $event_id,
                'visible_to' => $visible_to, 
                'visible_to_category' => $visible_to_category,
                'visible_to_id' => $visible_to_id,
                'created_by_user_id' => $created_by_user_id,
                'created_by_group' => $created_by_group,
                'created_at' => $created_at
            );

            if ($visibility_for_creator === 'true') {
                $visibility_data['visibility_for_creator'] = true;
            } else if ($visibility_for_creator === 'false') {
                $visibility_data['visibility_for_creator'] = false;
            }

            if ($visible_edit === 'true') {
                $visibility_data['visible_edit'] = true;
            } else if ($visible_edit === 'false') {
                $visibility_data['visible_edit'] = false;
            }

            if ($visible_delete === 'true') {
                $visibility_data['visible_delete'] = true;
            } else if ($visible_delete === 'false') {
                $visibility_data['visible_delete'] = false;
            }
    
            if (!$this->db->insert('event_visibility', $visibility_data)) {
                echo "Error al insertar visibilidad: " . $this->db->last_query();
                return;
            }
    
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('event_added_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        }

        if ($param1 == 'update') {
            $event_id = $param2;
        
            // Obtener los valores enviados desde el formulario
            $title = $this->input->post('title');
            $body = $this->input->post('body');
            $date = $this->input->post('date');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $color = $this->input->post('color');
            $type = $this->input->post('type');

            $visibility_for_creator = $this->input->post('content-visibility-for-creator-modal') ? 1 : 0;
            $visible_edit = $this->input->post('content-visible-edit-modal') ? 1 : 0;
            $visible_delete = $this->input->post('content-visible-delete-modal') ? 1 : 0;

            $data = array('title' => $title, 'body' => $body, 'color' => $color, 'type' => $type);

            $visible_to = $this->input->post('users-list-modal');

            if (empty($this->input->post('user-admin-option-modal'))) {
                $visible_to_category = $this->input->post('user-student-option-modal');

                if($visible_to_category == 'All') {
                    $visible_to_id = null;
                } else if ($visible_to_category == 'PerClass') {
                    $visible_to_id = $this->input->post('content-class-list-modal');
                } else if ($visible_to_category == 'PerSection') {
                    $visible_to_id = $this->input->post('content-sections-list-modal');
                }
            } else {
                $visible_to_category = $this->input->post('user-admin-option-modal');

                if($visible_to_category == 'All') {
                    $visible_to_id = null;
                } else if ($visible_to_category == 'PerUser') {
                    $visible_to_id = $this->input->post('content-admin-list-modal');
                } 
            }

            $dataEventVisibility = array(
                'visible_to' => $visible_to,
                'visible_to_category' => $visible_to_category,
                'visible_to_id' => $visible_to_id,
                'visibility_for_creator' => $visibility_for_creator,
                'visible_edit' => $visible_edit,
                'visible_delete' => $visible_delete
            );


                                     
        
            // Verificar si date no es nulo ni vacío
            if (!empty($date)) {
                // Actualiza solo con date (evento de un solo día)
                $data['date'] = $date;
                // Limpiar los valores de start y end si existen en la tabla
                $data['start'] = null;
                $data['end'] = null;
        
            } elseif (!empty($start) && empty($end)) {
                // Si start tiene valor y end es vacío (evento de una sola hora)
                $data['start'] = $start;
                $data['end'] = null; // Aseguramos que 'end' quede vacío en la base de datos
                // Limpiar el valor de date si existe en la tabla
                $data['date'] = null;
        
            } elseif (!empty($start) && !empty($end)) {
                // Si start y end tienen valor (evento con hora de inicio y fin)
                $data['start'] = $start;
                $data['end'] = $end;
                // Limpiar el valor de date si existe en la tabla
                $data['date'] = null;
            }
        
            // Actualizar la tabla 'events' donde event_id coincide
            $this->db->where('event_id', $event_id);
            $this->db->update('events', $data);

            $this->db->where('event_id', $event_id);
            $this->db->update('event_visibility', $dataEventVisibility);
        
            // Redirigir después de actualizar
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('event_updated_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        }

        if ($param1 == 'disable') {
            $event_id = $param2;

            $this->db->where('event_id', $event_id);
            $this->db->update('events', array('status_id' => 0));
        
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('event_disabled_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));       
          
        
            redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        }

        if ($param1 == 'enable') {
            $event_id = $param2;

            $this->db->where('event_id', $event_id);
            $this->db->update('events', array('status_id' => 1));
        
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('event_enabled_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));       
          
        
            redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        }
        
        
    }





}