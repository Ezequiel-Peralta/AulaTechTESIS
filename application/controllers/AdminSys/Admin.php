<?php

class Admin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('admin/Admins_model');
        $this->load->library('Admins_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function admin_profile($admin_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/admin_profile/' . $admin_id)
            )
        );

        $admin_data = $this->Admin_model->get_admin_by_id($admin_id);

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'admin_profile';
        $page_data['page_title'] = ucfirst(get_phrase('view_profile'));
        $page_data['admin_data'] = $admin_data;
        $page_data['param2'] = $admin_id;

        $this->load->view('backend/index', $page_data);
    }

    function admin_information()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_admins')),
                'url' => base_url('index.php?admin/admin_information/')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'admin_information';
        $page_data['page_icon'] = 'entypo-graduation-cap';
        $page_data['page_title'] = ucfirst(get_phrase('manage_admins'));
        $this->load->view('backend/index', $page_data);
    }

    function get_admin_users_content()
    {
        $admins = $this->Admin_model->get_all_admins();

        echo '<option value="">' . 'Seleccionar' . '</option>';

        foreach ($admins as $row) {
            echo '<option value="' . $row['admin_id'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
        }
    }
}