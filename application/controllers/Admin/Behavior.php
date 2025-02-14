<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Behavior extends CI_Controller
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



    function add_behavior($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_behavior')),
                'url' => base_url('index.php?admin/add_behavior')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'add_behavior';
        $page_data['student_id']  = $param1;
        $page_data['class_id']  = $param2;
        $page_data['section_id']  = $param3;
		$page_data['page_title'] = ucfirst(get_phrase('add_behavior'));
		$this->load->view('backend/index', $page_data);
	}

    function edit_behavior($behavior_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

            $page_complete_name = 'edit_behavior'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $behavior_id; // ID del elemento específico (ej. curso o sección)

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
                'text' => ucfirst(get_phrase('edit_behavior')),
                'url' => base_url('index.php?admin/edit_behavior')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['behavior_id'] = $behavior_id;
		$page_data['page_title'] = ucfirst(get_phrase('edit_behavior'));
        $page_data['page_name'] = 'edit_behavior';
		$this->load->view('backend/index', $page_data);
	}


    function behavior($section_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        // Breadcrumb actualizado para usar section_id
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_behavior')). "&nbsp;&nbsp;/&nbsp;&nbsp;". $this->crud_model->get_section_name($section_id),
                'url' => base_url('index.php?admin/behavior/'.$section_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;
    
        // Filtra los registros de student_details según el section_id
        $this->db->select('student.student_id, student.email, student.username, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.section_id, student_details.class_id, student_details.user_status_id');
        $this->db->from('student');
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        $this->db->where('student_details.user_status_id', 1);
        $this->db->where('student_details.section_id', $section_id);
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Almacena los resultados en el array de datos
        $page_data['students']  = $query->result_array();
    
        // Crear un array para almacenar los comportamientos de cada estudiante
        $behaviors = [];
        
        // Obtener los registros de comportamiento para cada estudiante
        foreach ($page_data['students'] as $student) {
            $student_id = $student['student_id'];
    
            // Consultar la tabla de behavior
            $this->db->where('student_id', $student_id);
            $behavior_query = $this->db->get('behavior');
            
            // Almacenar el comportamiento del estudiante en el array
            $behaviors[$student_id] = $behavior_query->result_array();
        }
    
        // Añadir los comportamientos al array de datos
        $page_data['behaviors'] = $behaviors;
    
        // Actualiza los títulos y datos de la página usando section_id
        $page_data['page_name']   = 'behavior';
        $page_data['page_title']  = ucfirst(get_phrase('manage_behavior')). ' - ' . $this->crud_model->get_section_name($section_id) ;
        $page_data['section_id']  = $section_id;
        
        $this->load->view('backend/index', $page_data);
    }





    function behavior_information($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['behavior_type_id']       			= $this->input->post('type');
            $data['date']    			= $this->input->post('date');
            $data['comment']        			= $this->input->post('comment');
            $data['student_id']        			=  $this->input->post('student_id');
            $data['class_id']        			=  $this->input->post('class_id');
            $data['section_id']        			=  $this->input->post('section_id');

            $data['status_id']        			=  1;

            $this->db->insert('behavior', $data);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('student_behavior_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/student_behavior/' .  $data['student_id'], 'refresh');
        }
        if ($param1 == 'update') {
            $data['behavior_type_id']       			= $this->input->post('type');
            $data['date']    			= $this->input->post('date');
            $data['comment']        			= $this->input->post('comment');
            $data['student_id']        			=  $this->input->post('student_id');
            $data['class_id']        			=  $this->input->post('class_id');
            $data['section_id']        			=  $this->input->post('section_id');
        
            $this->db->where('behavior_id', $param2);
            $this->db->update('behavior', $data);
        
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('student_behavior_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/student_behavior/' . $data['student_id'], 'refresh');
        } 
        if ($param1 == 'disable_behavior') {
            $behavior_id = $param2;  
    
            if ($behavior_id) {
                $this->db->where('behavior_id', $behavior_id);
                $this->db->update('behavior', array(
                    'status_id' => 0 
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('behavior_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_behavior')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/student_behavior/'. $param3, 'refresh');
        }
        if ($param1 == 'enable_behavior') {
            $behavior_id = $param2;  
    
            if ($behavior_id) {
                $this->db->where('behavior_id', $behavior_id);
                $this->db->update('behavior', array(
                    'status_id' => 1 
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('behavior_enabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_enabling_behavior')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/student_behavior/' . $param3, 'refresh');
        }
        
    }

    



    function student_behavior($student_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $this->db->select('student.email, student.username, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.section_id, student_details.class_id, student_details.user_status_id');
        $this->db->from('student');
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        $this->db->where('student_details.user_status_id', 1);
        $this->db->where('student.student_id', $student_id);
        
        $query = $this->db->get();
        $page_data['student'] = $query->row_array();  

        $this->db->where('student_id', $student_id);
        $behavior_query = $this->db->get('behavior');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('student_behavior')) . ' - ' . $page_data['student']['lastname'] . ", " . $page_data['student']['firstname'] . ".",
                'url' => base_url('index.php?admin/student_behavior/'.$student_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;
        
        $page_data['behaviors'] = $behavior_query->result_array();
        $page_data['student_id'] = $student_id;
        $page_data['page_name']   = 'student_behavior';
        $page_data['page_icon']   = 'entypo-graduation-cap';
        $page_data['page_title']  = ucfirst(get_phrase('student_behavior')) . ' - ' . $page_data['student']['lastname'] . ", " . $page_data['student']['firstname'];
        
        $this->load->view('backend/index', $page_data);
    }



    function manage_behavior()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_behavior')),
                'url' => base_url('index.php?admin/manage_students/')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
		$page_data['page_name']  = 'manage_behavior';
		$page_data['page_title'] 	= ucfirst(get_phrase('manage_behavior'));
		$this->load->view('backend/index', $page_data);
	}




}