<?php

class Section extends CI_Controller
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

    function section_add()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_section')),
                'url' => base_url('index.php?admin/section_add')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'section_add';
        $page_data['page_icon'] = 'entypo-graduation-cap';
		$page_data['page_title'] = ucfirst(get_phrase('add_section'));
		$this->load->view('backend/index', $page_data);
	}

    


    function section_profile($section_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_sections')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/section_profile/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'section_profile';
        $page_data['page_title']  = ucfirst(get_phrase('manage_sections')) . ' / ' . ucfirst(get_phrase('view_profile'));
        $page_data['section_id']  = $section_id;
        
        $this->load->view('backend/index', $page_data);
    }



    function section($class_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        // detect the first class
        if ($class_id == '')
            $class_id           =   $this->db->get('class')->first_row()->class_id;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_sections')),
                'url' => base_url('index.php?admin/section')
            )
        );
                        
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']  = 'section';
        $page_data['page_title'] = ucfirst(get_phrase('manage_sections'));
        $page_data['class_id']   = $class_id;
        $this->load->view('backend/index', $page_data);    
    }



    function sections($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']       =   $this->input->post('name');
            $data['letter_name']  =   $this->input->post('letter_name');
            $data['shift_id']   =   $this->input->post('shift_id');
            $data['class_id']   =   $this->input->post('class_id');
            $data['teacher_aide_id'] = null;
            $data['status_id']   =   1;

            $this->db->select('id');
            $this->db->from('academic_period');
            $this->db->where('status_id', 1);
            $query = $this->db->get();
            $row = $query->row();
    
            $data['academic_period_id'] = $row ? $row->id : '';

            $this->db->insert('section' , $data);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('section_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/section/' , 'refresh');
        }

        if ($param1 == 'update') {
            $data['name']       =   $this->input->post('name');
            $data['letter_name']  =   $this->input->post('letter_name');
            $data['class_id']   =   $this->input->post('class_id');
            $data['shift_id']   =   $this->input->post('shift_id');
            $this->db->where('section_id' , $param2);
            $this->db->update('section' , $data);
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('section_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/section/' , 'refresh');
        }

        if ($param1 == 'disable_section') {
            $section_id = $param2;  
    
            if ($section_id) {
                $this->db->where('section_id', $section_id);
                $this->db->update('section', array(
                    'status_id' => 0 
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('section_disabled_successfully')),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('error_disabling_section')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/section/', 'refresh');
        }
        if ($param1 == 'enable_section') {
            $section_id = $param2;  
    
            if ($section_id) {
                $this->db->where('section_id', $section_id);
                $this->db->update('section', array(
                    'status_id' => 1 
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('section_enabled_successfully')),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('error_enabling_section')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/section/', 'refresh');
        }
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


    function get_section_content_by_class($class_id)
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

    function get_section_content_by_academic_period($academic_period_id, $url)
    {
        $this->db->select('section.section_id, section.name');
        $this->db->from('section');
        $this->db->where('section.academic_period_id', $academic_period_id);
        $sections = $this->db->get()->result_array();

        if (empty($sections)) {
            $this->db->select('section_history.section_id, section_history.name');
            $this->db->from('section_history');
            $this->db->where('section_history.academic_period_id', $academic_period_id);
            $sections = $this->db->get()->result_array();
        }

        foreach ($sections as $row) {
            echo '<option value="' . base_url() . 'index.php?admin/' . $url . '/' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function section_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['class_id']   = $this->input->post('class_id');
            $data['section_id']   = $this->input->post('section_id');
            $data['subject_id'] = $this->input->post('subject_id');
            $data['time_start'] = $this->input->post('time_start');
            $data['time_end']   = $this->input->post('time_end');
            $data['day_id']        = $this->input->post('day_id');
            $this->db->insert('section_routine', $data);
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Horario agregado correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/section_routine/', 'refresh');
        }
        if ($param1 == 'update') {
            $data['class_id']   = $this->input->post('class_id');
            $data['section_id']   = $this->input->post('section_id');
            $data['subject_id'] = $this->input->post('subject_id');
            $data['time_start'] = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
            $data['time_end']   = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
            $data['day_id']        = $this->input->post('day_id');
            
            $this->db->where('section_routine_id', $param2);
            $this->db->update('section_routine', $data);
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Horario modificado correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/section_routine/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('class_routine', array(
                'class_routine_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('section_routine_id', $param2);
            $this->db->delete('section_routine');
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Horario eliminado correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/section_routine/', 'refresh');
        }

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_schedules')),
                'url' => base_url('index.php?admin/class_routine')
            )
        );
                        
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']  = 'section_routine';
        $page_data['page_icon']  = 'entypo-clock';
        $page_data['page_title'] = ucfirst(get_phrase('manage_schedules'));
        $this->load->view('backend/index', $page_data);
    }

    function get_section_content()
    {
        $sections = $this->db->get_where('section')->result_array();
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_section_content2()
    {
        $this->db->select('section.section_id, section.name');
        $this->db->from('section');
        $this->db->join('academic_period', 'section.academic_period_id = academic_period.id');
        $this->db->where('academic_period.status_id', 1); 
        $sections = $this->db->get()->result_array();
        echo '<option value="">' . 'seleccionar' . '</option>';
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }



}