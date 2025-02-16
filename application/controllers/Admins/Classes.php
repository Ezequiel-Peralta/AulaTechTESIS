<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Classes extends CI_Controller
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
    


    function classes($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']         = $this->input->post('name');
            $this->db->insert('class', $data);
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
            $data['name']         = $this->input->post('name');
            
            $this->db->where('class_id', $param2);
            $this->db->update('class', $data);
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
        if ($param1 == 'delete') {
            $this->db->where('class_id', $param2);
            $this->db->delete('class');
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Curso eliminado correctamente!',
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

        $page_data['classes']    = $this->db->get('class')->result_array();
        $page_data['page_name']  = 'class';
        $page_data['page_icon'] = 'entypo-clipboard';
        $page_data['page_title'] = ucfirst(get_phrase('manage_classes'));
        $this->load->view('backend/index', $page_data);
    }

    function get_class_section($class_id)
    {
        $this->db->select('section.section_id, section.name');
        $this->db->where('section.class_id', $class_id); 
        $this->db->from('section');
        $this->db->join('academic_period', 'section.academic_period_id = academic_period.id');
        $this->db->where('academic_period.status_id', 1); 
        $sections = $this->db->get()->result_array();
        
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_class_all_section()
    {
        $this->db->select('section.section_id, section.name');
        $this->db->from('section');
        $this->db->join('academic_period', 'section.academic_period_id = academic_period.id');
        $this->db->where('academic_period.status_id', 1); 
        $sections = $this->db->get()->result_array();
        
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }


}