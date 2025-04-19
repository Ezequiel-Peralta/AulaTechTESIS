<?php

class Classes extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('classess/Classes_model');
        $this->load->library('Classes_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function classes($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data['name'] = $this->input->post('name');
            $this->Classes_model->insert_class($data);
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Curso añadido correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        }

        if ($param1 == 'update') {
            $data['name'] = $this->input->post('name');
            $this->Classes_model->update_class($param2, $data);
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Curso actualizado correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        }

        if($param2 == 'inactive') {
            $this->Classes_model->update_status_class($param1, array('status_id' => 0));
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Curso inactivado correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        }

        if($param2 == 'active') {
            $this->Classes_model->update_status_class($param1, array('status_id' => 1));
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Curso activado correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        }


        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_classes')),
                'url' => base_url('index.php?admin/classes')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['classes'] = $this->Classes_model->get_all_classes();
        $page_data['page_name'] = 'class';
        $page_data['page_icon'] = 'entypo-clipboard';
        $page_data['page_title'] = ucfirst(get_phrase('manage_classes'));
        $this->load->view('backend/index', $page_data);
    }

    function get_class_content2()
    {
        $classes = $this->Classes_model->get_all_classes();
        echo '<option value="">' . 'seleccionar' . '</option>';
        foreach ($classes as $row) {
            echo '<option value="' . $row['class_id'] . '">' . $row['name'] . '°' . '</option>';
        }
    }
}