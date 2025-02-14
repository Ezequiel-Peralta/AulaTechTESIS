<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Library extends CI_Controller
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
    

    function add_library()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_library')),
                'url' => base_url('index.php?admin/add_library')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'add_library';
        $page_data['page_icon'] = 'entypo-graduation-cap';
		$page_data['page_title'] = ucfirst(get_phrase('add_library'));
		$this->load->view('backend/index', $page_data);
	}



    function library($param1 = '', $param2 = '', $param3 = '', $param4 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            if ($param1 == 'create') {
                $data['file_name'] = $this->input->post('filename');
                $data['date'] = $this->input->post('date');
                $data['description'] = $this->input->post('description');
                $data['class_id'] = $this->input->post('class_id');
                $data['section_id'] = $this->input->post('section_id');
                $data['subject_id'] = $this->input->post('subject_id');
                $data['status_id'] = 1;
            
                $class_id =  $data['class_id'];
                $section_id =  $data['section_id'];
            
                // Insertar el registro inicial en la base de datos
                $this->db->insert('library', $data);
                $insertedFileId = $this->db->insert_id(); 
                
                // Verifica si se subió un archivo
                if (!empty($_FILES['library_file']['name'])) {
                    $base_path = './uploads/library/';
                    if (!is_dir($base_path)) {
                        mkdir($base_path, 0777, true); 
                    }
            
                    // Configuración de la subida
                    $config['upload_path'] = $base_path; // Directorio base
                    $config['allowed_types'] = 'jpg|png|pdf|docx|txt|xls|xlsx';
                    $config['max_size'] = '102400'; 
                    $config['file_name'] = 'archivo_id_' . $insertedFileId;
            
                    $this->load->library('upload', $config);
            
                    // Crear subcarpetas específicas para sección y materia
                    $section = $this->db->get_where('section', array('section_id' => $data['section_id']))->row();
                    if ($section) {
                        $section_name = $section->class_id . '-' . $section->letter_name;
                        $section_folder = $config['upload_path'] . $section_name;
                        $subject_folder = $section_folder . '/subject_' . $data['subject_id'];
            
                        if (!is_dir($section_folder)) mkdir($section_folder, 0777, true);
                        if (!is_dir($subject_folder)) mkdir($subject_folder, 0777, true);
            
                        $config['upload_path'] = $subject_folder;
            
                        $this->upload->initialize($config);
            
                        if (!$this->upload->do_upload('library_file')) {
                            $error = $this->upload->display_errors();
                            echo 'Error al subir el archivo: ' . $error;
                            exit();
                        } else {
                            $upload_data = $this->upload->data();
                            $file_name = $upload_data['file_name']; // Solo el nombre y extensión del archivo
            
                            // Actualizar la base de datos con solo el nombre del archivo
                            $this->db->where('library_id', $insertedFileId);
                            $this->db->update('library', ['url_file' => $file_name]);
                        }
                    } 
                } 
            
                // Mensaje de éxito
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('library_file_added_successfully'),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/view_library/' . $section_id, 'refresh');
            }
            if ($param1 == 'update') {
                $library_id = $param2; 

                // Recoger los datos del formulario
                $dataDetails['file_name'] = $this->input->post('filename');
                $dataDetails['date'] = $this->input->post('date');
                $data['class_id'] = $this->input->post('class_id');
                $data['section_id'] = $this->input->post('section_id');
                $data['subject_id'] = $this->input->post('subject_id');
                $data['description'] = $this->input->post('description');
            
                // Obtener detalles de la biblioteca antes de actualizar
                $currentFile = $this->db->get_where('library', array('library_id' => $library_id))->row();
            
                // Comprobar si hay un nuevo archivo para cargar
                if (!empty($_FILES['library_file']['name'])) {
                    // Eliminar el archivo anterior si existe
                    if (!empty($currentFile->url_file) && file_exists('./uploads/library/' . $currentFile->url_file)) {
                        unlink('./uploads/library/' . $currentFile->url_file);
                    }
            
                    // Configuración de carga
                    $base_path = './uploads/library/';
                    if (!is_dir($base_path)) {
                        mkdir($base_path, 0777, true); 
                    }
            
                    $config['upload_path'] = $base_path; // Directorio base
                    $config['allowed_types'] = 'jpg|png|pdf|docx|txt|xls|xlsx';
                    $config['max_size'] = '102400'; 
                    $config['file_name'] = 'archivo_id_' . $library_id; // Cambiar nombre del archivo
            
                    $this->load->library('upload', $config);
            
                    // Crear carpetas de sección y materia
                    $section = $this->db->get_where('section', array('section_id' => $data['section_id']))->row();
                    if ($section) {
                        $section_name = $section->class_id . '-' . $section->letter_name;
                        $section_folder = $config['upload_path'] . $section_name;
                        $subject_folder = $section_folder . '/subject_' . $data['subject_id'];
            
                        if (!is_dir($section_folder)) mkdir($section_folder, 0777, true);
                        if (!is_dir($subject_folder)) mkdir($subject_folder, 0777, true);
            
                        $config['upload_path'] = $subject_folder;
                        $this->upload->initialize($config);
            
                        // Realizar la carga del archivo
                        if (!$this->upload->do_upload('library_file')) {
                            $error = $this->upload->display_errors();
                            echo 'Error al subir el archivo: ' . $error;
                            exit();
                        } else {
                            $upload_data = $this->upload->data();
                            $file_name = $upload_data['file_name']; // Obtener solo el nombre del archivo
            
                            // Actualizar la base de datos con el nombre del archivo
                            $dataDetails['url_file'] = $file_name; // Guardar solo el nombre del archivo
                        }
                    } else {
                        echo "Error: Sección no encontrada.";
                        exit();
                    }
                } else {
                    // Si no se sube un nuevo archivo, conserva el archivo existente
                    $dataDetails['url_file'] = $currentFile->url_file; // Mantener el archivo existente
                }
            
                // Actualizar la biblioteca en la base de datos
                $this->db->where('library_id', $library_id);
                $this->db->update('library', array_merge($data, $dataDetails));
            
                // Mensaje de éxito
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('library_updated_successfully'),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            
                redirect(base_url() . 'index.php?admin/library_information/' . $data['section_id'], 'refresh');
            }
		
        if ($param1 == 'disable_file') {
            $file_id = $param2;  
            $section_id = $param3;  
    
            if ($file_id) {
                $this->db->where('library_id', $file_id);
                $this->db->update('library', array(
                    'status_id' => 0
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('library_file_disabled_successfully'),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('error_disabling_library_file'),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/library_information/' . $section_id, 'refresh');
        }

        if ($param1 == 'enable_file') {
            $file_id = $param2;  
            $section_id = $param3;  
    
            if ($file_id) {
                $this->db->where('library_id', $file_id);
                $this->db->update('library', array(
                    'status_id' => 1
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('library_file_enabled_successfully'),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('error_enabling_library_file'),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/library_information/'. $section_id, 'refresh');
        }
        
    }


    function edit_library($library_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

            $page_complete_name = 'edit_library'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $library_id; // ID del elemento específico (ej. curso o sección)

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
                'text' => ucfirst(get_phrase('edit_library')),
                'url' => base_url('index.php?admin/edit_library')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['library_id'] = $library_id;
		$page_data['page_name']  = 'edit_library';
		$page_data['page_title'] = ucfirst(get_phrase('edit_library'));
		$this->load->view('backend/index', $page_data);
	}



    function view_library($section_id = '', $subject_id = '')
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


        if (empty($subject_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_library')) . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'],
                    'url' => base_url('index.php?admin/view_library/' . $section_id)
                )
            );
        } else {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_library')) . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'] . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst($subject_data['name']),
                    'url' => base_url('index.php?admin/view_library/' . $section_id . '/' . $subject_id)
                )
            );
        }
    
      
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['subject_id'] = $subject_id;
        $page_data['page_name'] = 'view_library';
        $page_data['used_subject_history'] = $used_subject_history;
        $page_data['used_section_history'] = $used_section_history;
        $page_data['page_title'] = ucfirst(get_phrase('view_library'));
        $this->load->view('backend/index', $page_data);
    }

    function manage_library()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_library')),
                'url' => base_url('index.php?admin/manage_library/')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
		$page_data['page_name']  = 'manage_library';
		$page_data['page_title'] 	= ucfirst(get_phrase('manage_library'));
		$this->load->view('backend/index', $page_data);
	}


    function library_information($section_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

      

        $this->db->where('section_id', $section_id);
        $section_data = $this->db->get('section')->row_array(); 

        $this->db->where('section_id', $section_id);
        $section_subject_count = $this->db->count_all_results('subject'); 


        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_library')) . ' - ' . $section_data['name'],
                'url' => base_url('index.php?admin/library_information/' . $section_id)
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['section_data'] = $section_data; 
        $page_data['section_subject_count'] = $section_subject_count; 
		$page_data['page_name']  = 'library_information';
		$page_data['page_title'] 	= ucfirst(get_phrase('manage_library')) . ' - ' . $section_data['name'];
		$this->load->view('backend/index', $page_data);
	}

    
}  