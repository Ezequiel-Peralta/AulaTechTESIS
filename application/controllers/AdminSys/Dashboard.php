<?php

class Dashboard extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('dashboard/Dashboard_model');
        $this->load->library('dashboard_service');

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

        $page_data['events'] = json_encode($this->dashboard_service->get_visible_events($user_id, $user_group));
        $page_data['disabledEvents'] = json_encode($this->dashboard_service->get_disabled_events($user_id, $user_group));

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