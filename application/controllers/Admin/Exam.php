<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Exam extends CI_Controller
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
    





} function exams_information($section_id = '', $subject_id = '')
{
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');

    $this->db->where('subject_id', $subject_id);
    $subject_data = $this->db->get('subject')->row_array(); 

    if (!empty($subject_id)) {
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_exams')) . "&nbsp;&nbsp;/&nbsp;&nbsp;" . $this->crud_model->get_section_name($section_id) . "&nbsp;&nbsp;/&nbsp;&nbsp;" . ucfirst($subject_data['name']),
                'url' => base_url('index.php?admin/exams_information/' . $section_id . '/' . $subject_id)
            )
        );

        $page_data['page_title'] 	= ucfirst(get_phrase('manage_exams')) . " - " . $this->crud_model->get_section_name($section_id) . " - " . ucfirst($subject_data['name']);
    } else {
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_exams')) . " - " . $this->crud_model->get_section_name($section_id),
                'url' => base_url('index.php?admin/exams_information/' . $section_id)
            )
        );

        $page_data['page_title'] 	= ucfirst(get_phrase('manage_exams')) . " - " . $this->crud_model->get_section_name($section_id);
    }
                
    $page_data['breadcrumb'] = $breadcrumb;
    $page_data['subject_id'] = $subject_id;
    $page_data['page_name']  = 'exams_information';
    $page_data['section_id']  = $section_id;
    $this->load->view('backend/index', $page_data);
}

function exam($param1 = '', $param2 = '' , $param3 = '', $param4 = '')
{
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name'] = $this->input->post('name');
            $data['date'] = $this->input->post('date');
            $data['status_id'] = $this->input->post('status_id');
            $data['exam_type_id'] = $this->input->post('exam_type_id');
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $data['subject_id'] = $this->input->post('subject_id');
            
            $subject = $this->db->get_where('subject', array('subject_id' => $data['subject_id']))->row();
            if ($subject) {
                $data['teacher_id'] = $subject->teacher_id;
            }
    
            $this->db->insert('exam', $data);
            $exam_id = $this->db->insert_id();
    
            if (!empty($_FILES['attachments']['name'][0])) { 
                $exam_directory = 'uploads/exams/' . $exam_id . '/';
                if (!is_dir($exam_directory)) {
                    mkdir($exam_directory, 0777, true); 
                }
    
                $this->load->library('upload'); 
                
                $files = $_FILES;
                $number_of_files = count($_FILES['attachments']['name']);
                
                $uploaded_files = []; 
    
                for ($i = 0; $i < $number_of_files; $i++) {
                    $_FILES['attachment']['name'] = $files['attachments']['name'][$i];
                    $_FILES['attachment']['type'] = $files['attachments']['type'][$i];
                    $_FILES['attachment']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                    $_FILES['attachment']['error'] = $files['attachments']['error'][$i];
                    $_FILES['attachment']['size'] = $files['attachments']['size'][$i];
    
    
                    $config['upload_path'] = $exam_directory;
                    $config['allowed_types'] = '*'; 
                    $config['max_size'] = '100480'; 
                    $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $_FILES['attachment']['name']);
                    $config['file_name'] = $filename;
                    $config['detect_mime'] = FALSE;
    
                    $this->upload->initialize($config);
    
                    if ($this->upload->do_upload('attachment')) {
                        $upload_data = $this->upload->data();
                        $uploaded_files[] = $upload_data['file_name']; 
                    } else {
                        $this->session->set_flashdata('flash_message', array(
                            'title' => '¡' . ucfirst(get_phrase('file_upload_error')) . '!',
                            'text' => $this->upload->display_errors(),
                            'icon' => 'error',
                            'showCloseButton' => 'true',
                            'confirmButtonText' =>  ucfirst(get_phrase('accept')),
                            'confirmButtonColor' => '#d33',
                        ));

                        redirect(base_url() . 'index.php?admin/exams_information/' . $data['section_id'], 'refresh');
                    }
                }
    
                $files_json = json_encode($uploaded_files);
                $this->db->where('exam_id', $exam_id);
                $this->db->update('exam', ['files' => $files_json]);
            }
    
            $this->session->set_flashdata('flash_message', array(
                'title' => '¡' .  ucfirst(get_phrase('evaluation_added_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' =>  ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
    
            redirect(base_url() . 'index.php?admin/exams_information/' . $data['section_id'], 'refresh');
        }
        if ($param1 == 'update') {
            $data['name'] = $this->input->post('name');
            $data['date'] = $this->input->post('date');
            $data['status_id'] = $this->input->post('status_id');
            $data['exam_type_id'] = $this->input->post('exam_type');
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $data['subject_id'] = $this->input->post('subject_id');

            $subject = $this->db->get_where('subject', array('subject_id' => $data['subject_id']))->row();
            if ($subject) {
                $data['teacher_id'] = $subject->teacher_id;
            }
        
            $existing_files = $this->db->get_where('exam', array('exam_id' => $param2))->row()->files;
            $existing_files = !empty($existing_files) ? json_decode($existing_files, true) : [];
        
            $files_to_delete = $this->input->post('files_to_delete');
            $files_to_delete = is_array($files_to_delete) ? $files_to_delete : [];
            
            $files_to_keep = array_diff($existing_files, $files_to_delete); 
            
            foreach ($files_to_delete as $file) {
                $file_path = 'uploads/exams/' . $param2 . '/' . $file;
                if (file_exists($file_path) && is_file($file_path)) {
                    unlink($file_path); 
                }
            }
        
            $final_files = $files_to_keep;
        
            // Directorio de almacenamiento de archivos
            $exam_directory = 'uploads/exams/' . $param2 . '/';
            if (!is_dir($exam_directory)) {
                mkdir($exam_directory, 0777, true); // Crear la carpeta si no existe
            }
        
            // Procesamos nuevos archivos adjuntos si existen
            if (!empty($_FILES['attachments']['name'][0])) {
                $exam_directory = 'uploads/exams/' . $param2 . '/';
                if (!is_dir($exam_directory)) {
                    mkdir($exam_directory, 0777, true); 
                }
        
                $this->load->library('upload'); 
                
                $files = $_FILES;
                $number_of_files = count($_FILES['attachments']['name']);
                
                $uploaded_files = []; 
        
                for ($i = 0; $i < $number_of_files; $i++) {
                    $_FILES['attachment']['name'] = $files['attachments']['name'][$i];
                    $_FILES['attachment']['type'] = $files['attachments']['type'][$i];
                    $_FILES['attachment']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                    $_FILES['attachment']['error'] = $files['attachments']['error'][$i];
                    $_FILES['attachment']['size'] = $files['attachments']['size'][$i];
        
                    $config['upload_path'] = $exam_directory;
                    $config['allowed_types'] = '*';
                    $config['max_size'] = '100480';
                    $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $_FILES['attachment']['name']);
                    $config['file_name'] = $filename;
                    $config['detect_mime'] = FALSE;
        
                    $this->upload->initialize($config);
        
                    if ($this->upload->do_upload('attachment')) {
                        $upload_data = $this->upload->data();
                        $final_files[] = $upload_data['file_name']; 
                    } else {
                        // Mostrar error de carga y salir
                        $this->session->set_flashdata('flash_message', array(
                            'title' => '¡' .  ucfirst(get_phrase('file_upload_error')) . '!',
                            'text' => $this->upload->display_errors(),
                            'icon' => 'error',
                            'showCloseButton' => 'true',
                            'confirmButtonText' =>  ucfirst(get_phrase('accept')),
                            'confirmButtonColor' => '#d33',
                        ));
                        redirect(base_url() . 'index.php?admin/exams_information/' . $data['section_id'], 'refresh');
                    }
                }

                $final_files = array_merge($final_files, $uploaded_files);
            }
        
            $final_files = array_unique($final_files);
            $files_json = json_encode($final_files);
            
  
            $this->db->where('exam_id', $param2);
            $this->db->update('exam', $data);
        
            $this->db->where('exam_id', $param2);
            $this->db->update('exam', ['files' => $files_json]);

            $this->session->set_flashdata('flash_message', array(
                'title' => '¡' . ucfirst(get_phrase('evaluation_modified_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/exams_information/' . $data['section_id'], 'refresh');
        }
    if ($param1 == 'disable_exam_bulk') {
        $segments = $this->uri->segment_array();

        $index = array_search('disable_exam_bulk', $segments);
        
        $exams = array_slice($segments, $index + 1); 
    
        $exams = array_filter($exams, 'is_numeric'); 
    
        $this->db->where_in('exam_id', $exams);
        $this->db->update('exam', ['status_id' => 0]);
    
        $this->session->set_flashdata('flash_message', array(
           'title' => '¡' . ucfirst(get_phrase('evaluations_disabled_successfully')) . '!',
            'text' => '',
            'icon' => 'success',
            'showCloseButton' => true,
            'confirmButtonText' => ucfirst(get_phrase('accept')),
            'confirmButtonColor' => '#1a92c4',
            'timer' => 10000,
            'timerProgressBar' => true,
        ));
    
        redirect(base_url() . 'index.php?admin/exams_information/' . $param2, 'refresh');
    }

    if ($param1 == 'disable_exam') {
        
        $this->db->where('exam_id', $param2);
        $this->db->update('exam', array(
            'status_id' => 0
        ));
        $this->session->set_flashdata('flash_message', array(
            'title' => '¡' . ucfirst(get_phrase('evaluation_disabled_successfully')) . '!',
            'text' => '',
            'icon' => 'success',
            'showCloseButton' => 'true',
            'confirmButtonText' => ucfirst(get_phrase('accept')),
            'confirmButtonColor' => '#1a92c4',
            'timer' => '10000',
            'timerProgressBar' => 'true',
        ));
        redirect(base_url() . 'index.php?admin/exams_information/' . $param3, 'refresh');
    }

    if ($param1 == 'enable_exam_bulk') {
        $segments = $this->uri->segment_array();

        $index = array_search('enable_exam_bulk', $segments);
        
        $exams = array_slice($segments, $index + 1); 
    
        $exams = array_filter($exams, 'is_numeric'); 
    
        $this->db->where_in('exam_id', $exams);
        $this->db->update('exam', ['status_id' => 1]);
    
    
        $this->session->set_flashdata('flash_message', array(
            'title' => '¡' . ucfirst(get_phrase('evaluations_enabled_successfully')) . '!',
            'text' => '',
            'icon' => 'success',
            'showCloseButton' => 'true',
            'confirmButtonText' => ucfirst(get_phrase('accept')),
            'confirmButtonColor' => '#1a92c4',
            'timer' => '10000',
            'timerProgressBar' => 'true',
        ));
    
        redirect(base_url() . 'index.php?admin/exams_information/' . $param2, 'refresh');
    }
    if ($param1 == 'enable_exam') {
        
        $this->db->where('exam_id', $param2);
        $this->db->update('exam', array(
            'status_id' => 1
        ));
        $this->session->set_flashdata('flash_message', array(
            'title' => '¡' . ucfirst(get_phrase('evaluation_enabled_successfully')) . '!',
            'text' => '',
            'icon' => 'success',
            'showCloseButton' => 'true',
            'confirmButtonText' => ucfirst(get_phrase('accept')),
            'confirmButtonColor' => '#1a92c4',
            'timer' => '10000',
            'timerProgressBar' => 'true',
        ));
        redirect(base_url() . 'index.php?admin/exams_information/' . $param3, 'refresh');
    }





    function exam_add()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('exam_add')),
                'url' => base_url('index.php?admin/exam_add')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'exam_add';
		$page_data['page_title'] = ucfirst(get_phrase('exam_add'));
		$this->load->view('backend/index', $page_data);
	}



    function exam_edit($param2 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

            $page_complete_name = 'exam_edit'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $param2; // ID del elemento específico (ej. curso o sección)

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
                'text' => ucfirst(get_phrase('exam_edit')),
                'url' => base_url('index.php?admin/exam_edit')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['param2'] = $param2;
		$page_data['page_name']  = 'exam_edit';
		$page_data['page_title'] = ucfirst(get_phrase('exam_edit'));
		$this->load->view('backend/index', $page_data);
	}

    function get_subject_exams($subject_id)
    {
        $exams = $this->db->get_where('exam' , array(
            'subject_id' => $subject_id
        ))->result_array();
        foreach ($exams as $row) {
            echo '<option value="' . $row['exam_id'] . '">' . $row['name'] . '</option>';
        }
    }


    function manage_exams()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_exams')),
                'url' => base_url('index.php?admin/manage_exams/')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'manage_exams';
		$page_data['page_title'] 	= ucfirst(get_phrase('manage_exams'));
		$this->load->view('backend/index', $page_data);
	}

    function view_exams($section_id = '', $subject_id = '', $teacher_id = '')
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

        $this->db->where('teacher_id', $teacher_id);
        $teacher_data = $this->db->get('teacher_details')->row_array();
    
        if (!empty($teacher_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_exams')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst($teacher_data['lastname']) . ', ' .  ucfirst($teacher_data['firstname']),
                    'url' => base_url('index.php?admin/view_exams/' . $section_id . '/' . $subject_id . '/' . $teacher_id)
                )
            );
        } else if (!empty($subject_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_exams'))  . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'] . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst($subject_data['name']),
                    'url' => base_url('index.php?admin/view_exams/' . $section_id . '/' . $subject_id)
                )
            );
            
        } else {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_exams'))  . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'],
                    'url' => base_url('index.php?admin/view_exams/' . $section_id)
                )
            );
        }
    
        $page_data['teacher_id'] = $teacher_id;
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['subject_id'] = $subject_id;
        $page_data['used_subject_history'] = $used_subject_history;
        $page_data['used_section_history'] = $used_section_history;
        $page_data['page_name'] = 'view_exams';
        $page_data['page_title'] = ucfirst(get_phrase('view_exams'));
        $this->load->view('backend/index', $page_data);
    }

    
}







