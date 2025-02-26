<?php

class Tasks extends CI_Controller
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

    function task($param1 = '', $param2 = '' , $param3 = '', $param4 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['user_type']   = 'admin';
            $data['user_id'] = $this->input->post('user_id');
            $data['title']       = $this->input->post('task_title');
            $data['task_style']   = $this->input->post('task_style');
            $data['status_id']   = 1;

            $this->db->insert('task', $data);

            // Obtener el ID de la tarea recién creada
            $task_id = $this->db->insert_id();

            // Obtener los elementos de la tarea enviados desde el formulario
            $task_items_string = $this->input->post('task_items');
            // Convertir la cadena de elementos en un array separado por comas
            $task_items_array = explode(',', $task_items_string);

            // Insertar cada elemento de la tarea en la tabla task_items
            foreach ($task_items_array as $item) {
                $item = trim($item); // Elimina espacios innecesarios
                if (!empty($item)) { // Verificar si la descripción no está vacía
                    $item_data = array(
                        'task_id' => $task_id,
                        'description' => $item,
                        'status_id' => 0, // El valor de status siempre será 0
                        'status' => 'enabled' 
                    );
                    $this->db->insert('task_items', $item_data);
                }
            }

            $this->session->set_flashdata('flash_message', array(
                'title' =>  ucfirst(get_phrase('task_added_successfully')) . '!',
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
            $data['user_type']   = 'admin';
            $data['user_id'] = $this->input->post('user_id');
            $data['title']       = $this->input->post('task_title');
            $data['task_style']   = $this->input->post('task_style');

            $this->db->where('task_id', $param2);
            $this->db->update('task', $data);

            // Obtener los valores de los elementos de la tarea enviados desde el formulario
            $task_items_post = $this->input->post('task_items');

            // Obtener los IDs de los elementos de tarea enviados desde el formulario
            $task_item_ids = $this->input->post('task_item_ids');

            // Iterar sobre los valores de los elementos enviados desde el formulario
            foreach ($task_items_post as $index => $new_item) {
                // Obtener el ID del elemento de tarea correspondiente
                $task_item_id = $task_item_ids[$index];
                
                // Verificar si el ID está vacío o si el valor del elemento está vacío
                if (!empty($task_item_id) && !empty($new_item)) {
                    // Obtener el elemento de tarea correspondiente de la base de datos
                    $existing_item = $this->db->get_where('task_items', array('task_item_id' => $task_item_id))->row_array();
                    
                    // Verificar si el elemento de tarea existe en la base de datos
                    if (!empty($existing_item)) {
                        // Verificar si el valor del elemento es diferente al valor existente en la base de datos
                        if ($existing_item['description'] != $new_item) {
                            // Actualizar el elemento correspondiente en la base de datos
                            $item_data = array(
                                'description' => trim($new_item),
                                // Otras columnas y valores que deseas actualizar
                            );
                            $this->db->where('task_item_id', $task_item_id);
                            $this->db->update('task_items', $item_data);
                        }
                    }
                }
            }
         
            $this->session->set_flashdata('flash_message', array(
                'title' =>  ucfirst(get_phrase('task_updated_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('subject', array(
                'subject_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'disable') {
            $this->db->where('task_id', $param2);
            $this->db->update('task', array('status_id' => 0));
            
            $this->session->set_flashdata('flash_message', array(
                'title' =>  ucfirst(get_phrase('task_disabled_successfully')) . '!',
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
            $this->db->where('task_id', $param2);
            $this->db->update('task', array('status_id' => 1));
            
            $this->session->set_flashdata('flash_message', array(
                'title' =>  ucfirst(get_phrase('task_enabled_successfully')) . '!',
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
        if ($param1 == 'createIndividualTaskItem') {
            $data['task_id']   = $param2;
            $data['description']   = urldecode($param3);
            $data['status_id']   =  0;
            $data['status']   =  'enabled';

            $this->db->insert('task_items', $data);

            // $this->session->set_flashdata('flash_message' , get_phrase('tarea añadida exitosamente'));
            // redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        } if ($param1 == 'checkUncheckIndividualTaskItem') {
             // Definir el nuevo valor para la columna 'status'
            $data['status_id'] = $param4;

            // Agregar las condiciones WHERE para filtrar los registros a actualizar
            $this->db->where('task_id', $param2);
            $this->db->where('task_item_id', $param3);

            // Realizar la actualización en la tabla 'task_items'
            $this->db->update('task_items', $data);

            // Obtener el ID de la tarea asociada al ítem
            $task_id = $this->db->get_where('task_items', array('task_item_id' => $param3))->row('task_id');

            // Calcular el progreso de la tarea y actualizarlo en la base de datos
            $this->updateTaskProgress($task_id);
        }
        if ($param1 == 'disabledIndividualTaskItem') {
            $this->db->where('task_item_id', $param2);
            $this->db->update('task_items', array('status' => 'disabled'));
            
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Elemento de la tarea deshabilitada correctamente!',
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

        if ($param1 == 'enabledIndividualTaskItem') {
            $this->db->where('task_item_id', $param2);
            $this->db->update('task_items', array('status' => 'enabled'));
            
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Elemento de la tarea habilitada correctamente!',
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


    public function updateTaskProgress($task_id) {
        // Obtener el total de ítems de la tarea
        $total_items = $this->db->where('task_id', $task_id)->count_all_results('task_items');
    
        // Obtener el total de ítems completados de la tarea
        $completed_items = $this->db->where('task_id', $task_id)
                                    ->where('status_id', 1)
                                    ->count_all_results('task_items');
    
        // Calcular el porcentaje de progreso y redondear a dos decimales
        $progress = ($total_items > 0) ? round(($completed_items / $total_items) * 100, 2) : 0;
    
        // Actualizar el progreso de la tarea
        $this->db->where('task_id', $task_id)->update('task', array('progress' => $progress));
    }
    





}