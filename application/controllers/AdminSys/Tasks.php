<?php

class Tasks extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('taskss/Tasks_model');
        $this->load->library('Tasks_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function tasks($param1 = '', $param2 = '', $param3 = '', $param4 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data['user_type'] = 'admin';
            $data['user_id'] = $this->input->post('user_id');
            $data['title'] = $this->input->post('task_title');
            $data['task_style'] = $this->input->post('task_style');
            $data['status_id'] = 1;

            $this->Tasks_model->insert_task($data);

            $task_id = $this->db->insert_id();
            $task_items_string = $this->input->post('task_items');
            $task_items_array = explode(',', $task_items_string);

            foreach ($task_items_array as $item) {
                $item = trim($item);
                if (!empty($item)) {
                    $item_data = array(
                        'task_id' => $task_id,
                        'description' => $item,
                        'status_id' => 0,
                        'status' => 'enabled'
                    );
                    $this->Tasks_model->insert_task_item($item_data);
                }
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('task_added_successfully')) . '!',
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
            $data['user_type'] = 'admin';
            $data['user_id'] = $this->input->post('user_id');
            $data['title'] = $this->input->post('task_title');
            $data['task_style'] = $this->input->post('task_style');

            $this->Tasks_model->update_task($param2, $data);

            $task_items_post = $this->input->post('task_items');
            $task_item_ids = $this->input->post('task_item_ids');

            foreach ($task_items_post as $index => $new_item) {
                $task_item_id = $task_item_ids[$index];
                if (!empty($task_item_id) && !empty($new_item)) {
                    $existing_item = $this->Tasks_model->get_task_item($task_item_id);
                    if (!empty($existing_item) && $existing_item['description'] != $new_item) {
                        $item_data = array(
                            'description' => trim($new_item)
                        );
                        $this->Tasks_model->update_task_item($task_item_id, $item_data);
                    }
                }
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('task_updated_successfully')) . '!',
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
            $this->Tasks_model->update_task_status($param2, 0);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('task_disabled_successfully')) . '!',
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
            $this->Tasks_model->update_task_status($param2, 1);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('task_enabled_successfully')) . '!',
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
            $data['task_id'] = $param2;
            $data['description'] = urldecode($param3);
            $data['status_id'] = 0;
            $data['status'] = 'enabled';

            $this->Tasks_model->insert_task_item($data);
        }

        if ($param1 == 'checkUncheckIndividualTaskItem') {
            $data['status_id'] = $param4;

            $this->Tasks_model->update_task_item_status($param3, $data);

            $task_id = $this->Tasks_model->get_task_id_by_item($param3);
            $this->updateTaskProgress($task_id);
        }

        if ($param1 == 'disabledIndividualTaskItem') {
            $this->Tasks_model->update_task_item_status($param2, array('status' => 'disabled'));

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
            $this->Tasks_model->update_task_item_status($param2, array('status' => 'enabled'));

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
        $total_items = $this->Tasks_model->count_task_items($task_id);
        $completed_items = $this->Tasks_model->count_completed_task_items($task_id);

        $progress = ($total_items > 0) ? round(($completed_items / $total_items) * 100, 2) : 0;

        $this->Tasks_model->update_task_progress($task_id, $progress);
    }
}