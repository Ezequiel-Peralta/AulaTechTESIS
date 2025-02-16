<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class schedules extends CI_Controller
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
    






    function schedules($param1 = '', $param2 = '' , $param3 = '', $param4 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
            if ($param1 == 'create') {
                $data['day_id'] = $this->input->post('day_id');
                $data['time_start'] = $this->input->post('time_start');
                $data['time_end'] = $this->input->post('time_end');
                $data['status_id'] = 1;
                $data['class_id'] = $this->input->post('class_id');
                $data['section_id'] = $this->input->post('section_id');
                $data['subject_id'] = $this->input->post('subject_id');

                $subject = $this->db->get_where('subject', array('subject_id' => $data['subject_id']))->row();
                if ($subject) {
                    $data['teacher_id'] = $subject->teacher_id;
                }
        
                $this->db->insert('schedule', $data);
                $schedule_id = $this->db->insert_id();

                $subject_id = $data['subject_id']; 

                $this->db->where('subject_id', $subject_id);
                $this->db->update('subject', array('schedule_id' => $schedule_id)); 
        
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('schedule_added_successfully')),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' =>  ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
        
                redirect(base_url() . 'index.php?admin/schedules_information/' . $data['section_id'], 'refresh');
            }
            if ($param1 == 'update') {
                $data['day_id'] = $this->input->post('day_id');
                $data['time_start'] = $this->input->post('time_start');
                $data['time_end'] = $this->input->post('time_end');
                $data['class_id'] = $this->input->post('class_id');
                $data['section_id'] = $this->input->post('section_id');
                $data['subject_id'] = $this->input->post('subject_id');

                $subject = $this->db->get_where('subject', array('subject_id' => $data['subject_id']))->row();
                if ($subject) {
                    $data['teacher_id'] = $subject->teacher_id;
                }
            
                $this->db->where('schedule_id', $param2);
                $this->db->update('schedule',$data);

                $subject_id = $data['subject_id']; 

                $this->db->where('subject_id', $subject_id);
                $this->db->update('subject', array('schedule_id' => $param2)); 
            
                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('schedule_modified_successfully')) . '!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/schedules_information/' . $data['section_id'], 'refresh');
            }
            

        if ($param1 == 'disable_schedule') {
            
            $this->db->where('schedule_id', $param2);
            $this->db->update('schedule', array(
                'status_id' => 0
            ));
            $this->session->set_flashdata('flash_message', array(
                'title' => '¡' . ucfirst(get_phrase('schedule_disabled_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/schedules_information/' . $param3, 'refresh');
        }
   
        if ($param1 == 'enable_schedule') {
            
            $this->db->where('schedule_id', $param2);
            $this->db->update('schedule', array(
                'status_id' => 1
            ));
            $this->session->set_flashdata('flash_message', array(
                'title' => '¡' . ucfirst(get_phrase('schedule_enabled_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/schedules_information/' . $param3, 'refresh');
        }
        
    }



    function add_schedule()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_schedule')),
                'url' => base_url('index.php?admin/add_schedule')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'add_schedule';
		$page_data['page_title'] = ucfirst(get_phrase('add_schedule'));
		$this->load->view('backend/index', $page_data);
	}


    function edit_schedule($schedule_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

            $page_complete_name = 'edit_schedule'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $schedule_id; // ID del elemento específico (ej. curso o sección)

            // Buscar registros para este page_name y element_id
            $this->db->where('page_name', $page_complete_name);
            $this->db->where('element_id', $element_id);
            $tracking = $this->db->get('page_tracking')->row_array();

            if (!empty($tracking)) {
                // Verificar si el registro está siendo utilizado por otro usuario
                if ($tracking['user_id'] !== NULL && $tracking['user_group'] !== NULL && ($tracking['user_id'] !== $user_id || $tracking['user_group'] !== $user_group)) {
                    // Si otro usuario está accediendo a este elemento, redirige con un mensaje
                    $this->session->set_flashdata('flash_message', array(
                        'title' => '¡' . ucfirst(get_phrase('this_page_is_being_used_by_another_user')) . '!',
                        'text' => '',
                        'icon' => 'error',
                        'showCloseButton' => 'true',
                        'confirmButtonText' => ucfirst(get_phrase('accept')),
                        'confirmButtonColor' => '#1a92c4',
                        'timer' => '10000',
                        'timerProgressBar' => 'true',
                    ));
                    redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
                } else {
                    // Si el usuario actual ya tiene acceso al elemento, actualiza el registro
                    $dataTracking = array(
                        'user_id' => $user_id,
                        'user_group' => $user_group
                    );
                    $this->db->where('page_tracking_id', $tracking['page_tracking_id']);
                    $this->db->update('page_tracking', $dataTracking);
                }
            } else {
                // Si no existe un registro con este element_id, se inserta uno nuevo
                $dataTracking = array(
                    'page_name' => $page_complete_name,
                    'element_id' => $element_id,
                    'user_id' => $user_id,
                    'user_group' => $user_group
                );
                $this->db->insert('page_tracking', $dataTracking);
            }


			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('edit_schedule')),
                'url' => base_url('index.php?admin/edit_schedule')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['schedule_id'] = $schedule_id;
		$page_data['page_name']  = 'edit_schedule';
		$page_data['page_title'] = ucfirst(get_phrase('edit_schedule'));
		$this->load->view('backend/index', $page_data);
	}



    function view_schedules($section_id = '', $teacher_id = '')
    {
        if ($this->session->userdata('admin_login') != 1) {
            redirect('login', 'refresh');
        }
    
        if (empty($section_id)) {
            $active_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();
    
            if ($active_academic_period) {
                $active_academic_period_id = $active_academic_period->id;
    
                $this->db->where('academic_period_id', $active_academic_period_id);
                $this->db->order_by('section_id', 'ASC');
                $section = $this->db->get('section')->row();
    
                if ($section) {
                    $section_id = $section->section_id; 
                }
            }
        }

        $used_section_history = false;

        $this->db->where('section_id', $section_id);
        $section_data = $this->db->get('section')->row_array();

        if (empty($section_data)) {
            $this->db->where('section_id', $section_id);
            $section_data = $this->db->get('section_history')->row_array();
            $used_section_history = true;
        }

        $academic_period_name = '';
        
        if ($used_section_history == true) {
            $academic_period_name = $this->crud_model->get_academic_period_name_per_section2($section_id);
            $page_data['academic_period_id'] = $section_data['academic_period_id']; 
        }

        $this->db->where('teacher_id', $teacher_id);
        $teacher_data = $this->db->get('teacher_details')->row_array();
    
        if (!empty($teacher_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_schedules')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst($teacher_data['lastname']) . ', ' . ucfirst($teacher_data['firstname']),
                    'url' => base_url('index.php?admin/view_schedules/' . $section_id . '/' . $teacher_id)
                )
            );
        } else if (!empty($section_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_schedules')) . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'],
                    'url' => base_url('index.php?admin/view_schedules/' . $section_id)
                )
            );
        } else {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_schedules')) .  ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'],
                    'url' => base_url('index.php?admin/view_schedules/' . $section_id)
                ) 
            );
        }

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['teacher_id'] = $teacher_id;
        $page_data['used_section_history'] = $used_section_history;
        $page_data['page_name'] = 'view_schedules';
        $page_data['page_title'] = ucfirst(get_phrase('view_schedules'));
        $this->load->view('backend/index', $page_data);
    }


    function manage_schedules()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_schedules')),
                'url' => base_url('index.php?admin/manage_schedules/')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
		$page_data['page_name']  = 'manage_schedules';
		$page_data['page_title'] 	= ucfirst(get_phrase('manage_schedules'));
		$this->load->view('backend/index', $page_data);
	}

    function schedules_information($section_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $this->db->where('section_id', $section_id);
        $section_data = $this->db->get('section')->row_array(); 

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_schedules')) . ' - ' . $section_data['name'],
                'url' => base_url('index.php?admin/schedules_information/' . $section_id)
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['section_data'] = $section_data; 
		$page_data['page_name']  = 'schedules_information';
		$page_data['page_title'] 	= ucfirst(get_phrase('manage_schedules')) . ' - ' . $section_data['name'];
		$this->load->view('backend/index', $page_data);
	}


}