<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('crud_model');
        $this->load->database();
        $this->load->library('session');
        /* cache control */
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
    }

    public function index() {

        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'index.php?admin/dashboard', 'refresh');

        if ($this->session->userdata('teacher_login') == 1)
            redirect(base_url() . 'index.php?teacher/dashboard', 'refresh');

        if ($this->session->userdata('student_login') == 1)
            redirect(base_url() . 'index.php?student/dashboard', 'refresh');

        if ($this->session->userdata('parent_login') == 1)
            redirect(base_url() . 'index.php?parents/dashboard', 'refresh');

        $this->load->view('backend/login');
    }

    function ajax_login() {
        $response = array();

        $email_or_username = $this->input->post("email");
        $password = $this->input->post("password");
        $response['submitted_data'] = $_POST;

        $login_status = $this->validate_login($email_or_username, $password);
        $response['login_status'] = $login_status;
        if ($login_status == 'success') {
            $response['redirect_url'] = '';
        }

        echo json_encode($response);
    }

    function validate_login($email_or_username = '', $password = '') {
        $this->db->where('password', $password);
        $this->db->group_start();
        $this->db->where('email', $email_or_username);
        $this->db->or_where('username', $email_or_username);
        $this->db->group_end();
        $query = $this->db->get('admin');

        if ($query->num_rows() > 0) {
            $row = $query->row();

            // Obtener datos adicionales de admin_details
            $details_query = $this->db->get_where('admin_details', array('admin_id' => $row->admin_id));
            if ($details_query->num_rows() > 0) {
                $details = $details_query->row();

                // Cambiar el valor de login_status_id a 1 cuando se inicia sesión
                $this->db->set('login_status_id', '1');
                $this->db->set('last_login', 'now');

                $this->db->where('admin_id', $row->admin_id);
                $this->db->update('admin_details');

                $this->session->set_userdata('admin_login', '1');
                $this->session->set_userdata('admin_id', $row->admin_id);
                $this->session->set_userdata('login_user_id', $row->admin_id);
                $this->session->set_userdata('username', $row->username);
                $this->session->set_userdata('email', $row->email);
                $this->session->set_userdata('firstname', $details->firstname);
                $this->session->set_userdata('lastname', $details->lastname);
                $this->session->set_userdata('photo', $details->photo);
                $this->session->set_userdata('login_type', 'admin');
                $this->session->set_userdata('login_status_id', '1');
                $this->session->set_userdata('theme_preference', $details->theme_preference);
                $this->session->set_userdata('language_preference', $details->language_preference);
                
                return 'success';
            }
        }

        return 'invalid';
    }

    function four_zero_four() {
        $this->load->view('four_zero_four');
    }

    function logout() {
        $admin_id = $this->session->userdata('admin_id');
        if ($admin_id) {
            // Cambiar el valor de login_status_id a 2 cuando se cierra sesión
            $this->db->set('login_status_id', '2');
            $this->db->set('last_login', 'NOW()', FALSE);
            $this->db->where('admin_id', $admin_id);
            $this->db->update('admin_details');
        }

        $this->session->sess_destroy();
        // $this->session->set_flashdata('logout_notification', 'logged_out');
        redirect(base_url(), 'refresh');
    }

}
