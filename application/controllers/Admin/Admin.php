<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admins extends CI_Controller
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
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'admin_profile';
        $page_data['page_title']  = ucfirst(get_phrase('view_profile'));
        $page_data['param2']  = $admin_id;
        
        $this->load->view('backend/index', $page_data);
    }


}
    