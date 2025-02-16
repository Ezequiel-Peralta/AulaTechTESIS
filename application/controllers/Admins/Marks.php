<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Marks extends CI_Controller
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



    function marks_per_exam($class_id = '', $section_id = '', $subject_id = '', $exam_id = '', $exam_type_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($this->input->post('operation') == 'selection') {
          
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['section_id']   = $this->input->post('section_id');
            $page_data['subject_id'] = $this->input->post('subject_id');
            $page_data['exam_id']    = $this->input->post('exam_id');
            
            if ($page_data['class_id'] > 0 && $page_data['section_id'] > 0 && $page_data['subject_id'] > 0 && $page_data['exam_id'] > 0 ) {
                redirect(base_url() . 'index.php?admin/marks_per_exam/' . $page_data['class_id'] . '/' . $page_data['section_id'] . '/' . $page_data['subject_id'] . '/' . $page_data['exam_id'], 'refresh');
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Tenes que seleccionar correctamente las opciones!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/marks_per_exam/', 'refresh');
            }
        }
    
        if ($this->input->post('operation') == 'update_all') {
            $marks = $this->input->post('marks');
            $exam_type_id = $this->input->post('exam_type_id'); // Captura el exam_type_id
            
            // Itera sobre todas las calificaciones recibidas
            foreach ($marks as $mark_id => $mark_obtained) {
                // Actualiza cada calificación en la base de datos
                $data = array(
                    'mark_obtained' => $mark_obtained,
                    'exam_type_id' => $exam_type_id // Incluye el exam_type_id en los datos
                );
                $this->db->where('mark_id', $mark_id);
                $this->db->update('mark', $data);
            }
        
            // Redirige según sea necesario
            if ($this->input->post('class_id') > 0 && $this->input->post('section_id') > 0 && $this->input->post('subject_id') > 0 && $this->input->post('exam_id') > 0 ) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Calificaciones agregadas correctamente!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/marks_per_exam/' . $this->input->post('class_id') . '/' . $this->input->post('section_id') . '/' . $this->input->post('subject_id') . '/' . $this->input->post('exam_id'), 'refresh');
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Tenes que seleccionar correctamente las opciones!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/marks_per_exam/', 'refresh');
            }
        }
    
        if ($this->input->post('operation') == 'update') {
            $data['mark_obtained'] = $this->input->post('mark_obtained');
            $data['exam_type_id'] = $this->input->post('exam_type_id'); // Incluye el exam_type_id
            
            $this->db->where('mark_id', $this->input->post('mark_id'));
            $this->db->update('mark', $data);
            $this->session->set_flashdata('flash_message', get_phrase('datos actualizados exitosamente'));
            redirect(base_url() . 'index.php?admin/marks_per_exam/' . $this->input->post('exam_id') . '/' . $this->input->post('class_id') . '/' . $this->input->post('subject_id'), 'refresh');
        }
    
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_marks_per_exam')),
                'url' => base_url('index.php?admin/marks_per_exam')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
    
        $page_data['class_id']   = $class_id;
        $page_data['section_id']   = $section_id;
        $page_data['subject_id'] = $subject_id;
        $page_data['exam_id']    = $exam_id;
        
        $page_data['page_info'] = 'Exam marks';
        
        $page_data['page_name']  = 'marks_per_exam';
        $page_data['page_title'] = ucfirst(get_phrase('manage_marks_per_exam'));
        $this->load->view('backend/index', $page_data);
    }


    function student_mark($class_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('marks_sheet')),
                'url' => base_url('index.php?admin/student_mark/' . $class_id)
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'student_mark';
		$page_data['page_title'] 	= ucfirst(get_phrase('marks_sheet')) . ' - ' . $this->crud_model->get_class_name($class_id);
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}


    function view_student_mark($section_id = '', $subject_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

           
            $page_complete_name = 'view_student_mark'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $section_id; // ID del elemento específico (ej. curso o sección)

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
        
        $used_section_history = false;

        // Buscar datos de la sección
        $this->db->where('section_id', $section_id);
        $section_data = $this->db->get('section')->row_array();

        if (empty($section_data)) {
            // Si no hay registros en 'section', buscar en 'section_history'
            $this->db->where('section_id', $section_id);
            $section_data = $this->db->get('section_history')->row_array();
            $used_section_history = true;
        }

        // Contar la cantidad de materias asociadas a la sección
        $this->db->where('section_id', $section_id);
        $section_subject_count = $this->db->count_all_results('subject');

        if ($section_subject_count == 0) {
            // Si no hay registros en 'subject', buscar en 'subject_history'
            $this->db->where('section_id', $section_id);
            $section_subject_count = $this->db->count_all_results('subject_history');
        }

        $used_subject_history = false;

        if (!empty($subject_id)) {
            // Buscar datos de la materia
            $this->db->where('subject_id', $subject_id);
            $subject_data = $this->db->get('subject')->row_array();
        
            if (empty($subject_data)) {
                // Si no hay registros en 'subject', buscar en 'subject_history'
                $this->db->where('subject_id', $subject_id);
                $subject_data = $this->db->get('subject_history')->row_array();
                $used_subject_history = true;
            }
        } else {
            $subject_data = array(); // Manejo de caso cuando no hay subject_id
            $used_subject_history = false; // Inicializar por defecto
        }
        

        $academic_period_name = '';
        if ($used_subject_history == true) {
            $academic_period_name = $this->crud_model->get_academic_period_name_per_section2($section_id);
            $page_data['academic_period_id'] = $section_data['academic_period_id']; 

        }

        if ($used_section_history == true) {
            $academic_period_name = $this->crud_model->get_academic_period_name_per_section2($section_id);
            $page_data['academic_period_id'] = $section_data['academic_period_id']; 
        }

        if (empty($subject_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view')) . ' ' . ucfirst(get_phrase('marks_sheet')) . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'],
                    'url' => base_url('index.php?admin/view_student_mark/' . '/' . $section_id)
                )
            );
        } else {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view')) . ' ' . ucfirst(get_phrase('marks_sheet')) . 
                             ($used_subject_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'] . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst($subject_data['name']),
                    'url' => base_url('index.php?admin/view_student_mark/' . $section_id . '/' . $subject_id)
                )
            );
        }
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['section_data'] = $section_data; 
        $page_data['individual_section_subject_count'] = 1;
        $page_data['section_subject_count'] = $section_subject_count;
        $page_data['used_subject_history'] = $used_subject_history;
        $page_data['used_section_history'] = $used_section_history;
        $page_data['subject_id'] = $subject_id;  
		$page_data['page_name']  = 'view_student_mark';
        if (empty($subject_id)) {
            $page_data['page_title'] 	= ucfirst(get_phrase('marks_sheet'));
        } else {
            $page_data['page_title'] 	= ucfirst(get_phrase('marks_sheet')) . ' - ' . ucfirst($subject_data['name']);
        }
		$this->load->view('backend/index', $page_data);
	}


    function mark($operation = '', $class_id = '', $section_id = '', $subject_id = '',  $student_id = '', $exam_type = '', $mark_obtained = '',  $date = '', $mark_id = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
    
        // Si $mark_obtained es "NULL", interpretamos que es un valor vacío
        $mark_obtained = ($mark_obtained === 'NULL') ? NULL : $mark_obtained;
    
        $data = [
            'class_id' => $class_id,
            'section_id' => $section_id,
            'subject_id' => $subject_id,
            'student_id' => $student_id,
            'exam_type_id' => $exam_type,
            'mark_obtained' => $mark_obtained // Se guarda NULL si está vacío
        ];
    
        if ($exam_type === '22') {
            $data['date'] = $date;
        }
    
    
        if ($operation === 'create') {
            $this->db->insert('mark', $data);
        } elseif ($operation === 'update' && !empty($mark_id)) {
            $this->db->where('mark_id', $mark_id);
            $this->db->update('mark', $data);
        } elseif ($operation === 'delete' && !empty($mark_id)) {
            $this->db->where('mark_id', $mark_id);
            $this->db->delete('mark');
        }
    }

    function mark_history($operation = '', $class_id = '', $section_id = '', $subject_id = '',  $student_id = '', $exam_type = '', $mark_obtained = '',  $date = '', $mark_id = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
    
        $mark_obtained = ($mark_obtained === 'NULL') ? NULL : $mark_obtained;
    
        $data = [
            'mark_obtained' => $mark_obtained 
        ];
    
        if ($exam_type === '22') {
            $data['date'] = $date;
        }
    
        if ($operation === 'create') {
            $this->db->insert('mark', $data);
        } elseif ($operation === 'update' && !empty($mark_id)) {
            $this->db->where('mark_id', $mark_id);
            $query = $this->db->get('mark');
    
            if ($query->num_rows() > 0) {
                $this->db->where('mark_id', $mark_id);
                $this->db->update('mark', $data);
            } else {
                $this->db->where('mark_id', $mark_id);
                $this->db->update('mark_history', $data);
            }
        } elseif ($operation === 'delete' && !empty($mark_id)) {
            $this->db->where('mark_id', $mark_id);
            $query = $this->db->get('mark');
    
            if ($query->num_rows() > 0) {
                $this->db->where('mark_id', $mark_id);
                $this->db->delete('mark');
            } else {
                $this->db->where('mark_id', $mark_id);
                $this->db->delete('mark_history');
            }
        }
    }
    

  




}