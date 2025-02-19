<?php

//require 'AdminCarpeta/dashboard.php';
//$dashboard = new Dashboard(); // Crea una instancia de la clase
//$resultado = $dashboard->index();
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller
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
    

    

    
	function student_add()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/controllers/views/backed/index.php')
            ),
            array(
                'text' => ucfirst(get_phrase('student_add')),
                'url' => base_url('index.php?admin/student_add')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'student_add';
        $page_data['page_icon'] = 'entypo-graduation-cap';
		$page_data['page_title'] = ucfirst(get_phrase('student_add'));
		$this->load->view('backend/index', $page_data);
	}





    function add_library()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?/Admin/dashboard.php')

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
	
	function academic_period($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            // Crear nuevo periodo académico
            $data['name'] = $this->input->post('name');
            $data['start_date'] = $this->input->post('start_date');
            $data['end_date'] = $this->input->post('end_date');
            $data['status_id'] = 1;

            // Insertar el nuevo periodo académico
            $this->db->insert('academic_period', $data);
            $new_academic_period_id = $this->db->insert_id(); // Obtener el ID del nuevo periodo académico

            // Obtener datos del periodo académico actual antes de desactivarlo
            $current_academic_period = $this->db->get_where('academic_period', ['status_id' => 1])->row_array();
            $current_academic_period_id = $current_academic_period['id'];

            // Desactivar el periodo académico actual
            $this->db->where('id', $current_academic_period_id);
            $this->db->update('academic_period', ['status_id' => 0]);

            // Obtener los detalles de los estudiantes activos (user_status_id = 1)
            $students = $this->db->get_where('student_details', ['user_status_id' => 1])->result_array();

            // Insertar datos en la tabla academic_history
            foreach ($students as $student) {
                $student_id = $student['student_id'];
                $section_id = $student['section_id'];
                $class_id = $student['class_id'];

                if ($student['class_id'] == 6) {
                    // Cambiar user_status_id a 0 y status_reason a "egreso"
                    $this->db->where('student_id', $student['student_id']);
                    $this->db->update('student_details', [
                        'user_status_id' => 0,
                        'status_reason' => 'graduation'
                    ]);
                }

                $academic_history_data = [
                    'student_id' => $student['student_id'],
                    'old_class_id' => $student['class_id'],
                    'old_section_id' => $student['section_id'],
                    'old_academic_period_id' => $current_academic_period_id,
                    'new_class_id' => null,
                    'new_section_id' => null,
                    'new_academic_period_id' => $new_academic_period_id, 
                    'date_change' => date('Y-m-d') // Solo año, mes y día
                ];
                $this->db->insert('academic_history', $academic_history_data);

                // Mover los datos de `mark` a `mark_history`
                $marks = $this->db->get_where('mark', ['student_id' => $student['student_id']])->result_array();
                foreach ($marks as $mark) {
                    $mark_history_data = [
                        'mark_id' => $mark['mark_id'],
                        'academic_period_id' => $current_academic_period_id, // Periodo académico antiguo
                        'subject_id' => $mark['subject_id'], // Asumimos que subject_id no cambia
                        'mark_obtained' => $mark['mark_obtained'],
                        'student_id' => $mark['student_id'],
                        'class_id' => $mark['class_id'],
                        'section_id' => $mark['section_id'],
                        'exam_id' => $mark['exam_id'],
                        'exam_type_id' => $mark['exam_type_id'], // Asumimos que exam_id representa un exam_history_id
                        'date' => $mark['date']
                    ];
                    $this->db->insert('mark_history', $mark_history_data);
                }

                // Eliminar los registros de `mark` después de moverlos
                $this->db->delete('mark', ['student_id' => $student['student_id']]);

                 // Mover los datos de `attendance_student` a `attendance_student_history`
                $attendances = $this->db->get_where('attendance_student', ['student_id' => $student['student_id']])->result_array();
                foreach ($attendances as $attendance) {
                    $attendance_history_data = [
                        'attendance_id' => $attendance['attendance_id'],
                        'section_id' => $attendance['section_id'],
                        'student_id' => $attendance['student_id'],
                        'date' => $attendance['date'],
                        'observation' => $attendance['observation'],
                        'status' => $attendance['status'], // Renombrado a attendance_status_id
                        'academic_period_id' => $current_academic_period_id,
                    ];
                    $this->db->insert('attendance_student_history', $attendance_history_data);
                }

                // Eliminar los registros de `attendance_student` después de moverlos
                $this->db->delete('attendance_student', ['student_id' => $student['student_id']]);

                 // Mover `behavior` a `behavior_history`
                $behaviors = $this->db->get_where('behavior', ['student_id' => $student_id])->result_array();
                foreach ($behaviors as $behavior) {
                    $behavior_history_data = [
                        'behavior_id' => $behavior['behavior_id'],
                        'student_id' => $behavior['student_id'],
                        'class_id' => $behavior['class_id'],
                        'section_id' => $behavior['section_id'],
                        'date' => $behavior['date'],
                        'comment' => $behavior['comment'],
                        'behavior_type_id' => $behavior['behavior_type_id'],
                        'status_id' => $behavior['status_id'],
                        'academic_period_id' => $current_academic_period_id
                    ];
                    $this->db->insert('behavior_history', $behavior_history_data);
                }
                $this->db->delete('behavior', ['student_id' => $student_id]);

                // Mover `exam` a `exam_history`
                $exams = $this->db->get_where('exam', ['section_id' => $section_id])->result_array();
                foreach ($exams as $exam) {
                    $exam_history_data = [
                        'exam_id' => $exam['exam_id'],
                        'name' => $exam['name'],
                        'date' => $exam['date'],
                        'files' => $exam['files'],
                        'exam_type_id' => $exam['exam_type_id'],
                        'class_id' => $exam['class_id'],
                        'section_id' => $exam['section_id'],
                        'subject_id' => $exam['subject_id'],
                        'teacher_id' => $exam['teacher_id'],
                        'status_id' => $exam['status_id'],
                        'academic_period_id' => $current_academic_period_id
                    ];
                    $this->db->insert('exam_history', $exam_history_data);
                }
                $this->db->delete('exam', ['section_id' => $section_id]);

                // Mover `library` a `library_history`
                $libraries = $this->db->get_where('library', ['section_id' => $section_id])->result_array();
                foreach ($libraries as $library) {
                    $library_history_data = [
                        'library_id' => $library['library_id'],
                        'file_name' => $library['file_name'],
                        'description' => $library['description'],
                        'class_id' => $library['class_id'],
                        'section_id' => $library['section_id'],
                        'subject_id' => $library['subject_id'],
                        'url_file' => $library['url_file'],
                        'date' => $library['date'],
                        'status_id' => $library['status_id'],
                        'academic_period_id' => $current_academic_period_id
                    ];
                    $this->db->insert('library_history', $library_history_data);
                }
                $this->db->delete('library', ['section_id' => $section_id]);

                // Mover `schedule` a `schedule_history`
                $schedules = $this->db->get_where('schedule', ['section_id' => $section_id])->result_array();
                foreach ($schedules as $schedule) {
                    $schedule_history_data = [
                        'schedule_id' => $schedule['schedule_id'],
                        'time_start' => $schedule['time_start'],
                        'time_end' => $schedule['time_end'],
                        'day_id' => $schedule['day_id'],
                        'class_id' => $schedule['class_id'],
                        'section_id' => $schedule['section_id'],
                        'subject_id' => $schedule['subject_id'],
                        'teacher_id' => $schedule['teacher_id'],
                        'status_id' => $schedule['status_id'],
                        'academic_period_id' => $current_academic_period_id
                    ];
                    $this->db->insert('schedule_history', $schedule_history_data);
                }
                $this->db->delete('schedule', ['section_id' => $section_id]);

                // Mover `subject` a `subject_history`
                $subjects = $this->db->get_where('subject', ['section_id' => $section_id])->result_array();
                foreach ($subjects as $subject) {
                    $subject_history_data = [
                        'subject_id' => $subject['subject_id'],
                        'name' => $subject['name'],
                        'image' => $subject['image'],
                        'class_id' => $subject['class_id'],
                        'section_id' => $subject['section_id'],
                        'teacher_aide_id' => $subject['teacher_aide_id'],
                        'teacher_id' => $subject['teacher_id'],
                        'schedule_id' => $subject['schedule_id'],
                        'status_id' => $subject['status_id'],
                        'academic_period_id' => $current_academic_period_id
                    ];
                    $this->db->insert('subject_history', $subject_history_data);
                }
                $this->db->delete('subject', ['section_id' => $section_id]);
            }

            // Mover carpetas de archivos
            function move_directory($source, $destination) {
                if (!is_dir($destination)) {
                    mkdir($destination, 0755, true);
                }
                $files = glob($source . '*');
                foreach ($files as $file) {
                    $dest_path = $destination . basename($file);
                    rename($file, $dest_path);
                }
            }

            // Mover archivos y carpetas
            move_directory('uploads/exams/', 'uploads/exams_history/');
            move_directory('uploads/library/', 'uploads/library_history/');
            move_directory('uploads/subject_image/', 'uploads/subject_image_history/');
            

            // Actualizar `student_details` poniendo `class_id` y `section_id` como NULL para estudiantes activos
            $this->db->where('user_status_id', 1);
            $this->db->update('student_details', [
                'class_id' => null,
                'section_id' => null
            ]);

            // Obtener todos los registros de la tabla `section`
            $sections = $this->db->get('section')->result_array();

            // Mover cada registro a `section_history`
            foreach ($sections as $section) {
                $section_history_data = [
                    'section_id' => $section['section_id'],
                    'name' => $section['name'],
                    'letter_name' => $section['letter_name'],
                    'class_id' => $section['class_id'],
                    'teacher_aide_id' => $section['teacher_aide_id'],
                    'shift_id' => $section['shift_id'],
                    'status_id' => $section['status_id'],
                    'academic_period_id' => $current_academic_period_id
                ];
                $this->db->insert('section_history', $section_history_data);
            }

            // Eliminar todos los registros de la tabla `section`
            $this->db->empty_table('section'); 

            // Mensaje de confirmación
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('academic_period_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            // Redireccionar
            redirect(base_url() . 'index.php?admin/academic_period/', 'refresh');

        }
        if ($param1 == 'update') {
            $data['name'] = $this->input->post('name');
            $data['start_date'] = $this->input->post('start_date');
            $data['end_date'] = $this->input->post('end_date');
            $this->db->where('id', $param2);
            $this->db->update('academic_period', $data);

            // Mensaje de confirmación
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('academic_period_updated_successfully')), 
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            // Redireccionar
            redirect(base_url() . 'index.php?admin/academic_period/', 'refresh');
        } 
        if ($param1 == 'disable_academic_period') {
            $academic_period_id = $param2;  
        
            if ($academic_period_id) {
                $this->db->where('id', $academic_period_id);
                $this->db->update('academic_period', array(
                    'status_id' => 0 
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('academic_period_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_academic_period')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/academic_period/', 'refresh');
        }
            if ($param1 == 'enable_academic_period') {
                $academic_period_id = $param2;  
        
                if ($academic_period_id) {
                    $this->db->where('id', $academic_period_id);
                    $this->db->update('academic_period', array(
                        'status_id' => 1 
                    ));
        
                    $this->session->set_flashdata('flash_message', array(
                        'title' => ucfirst(get_phrase('academic_period_enabled_successfully')),
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
                        'title' => ucfirst(get_phrase('error_enabling_academic_period')),
                        'text' => '',
                        'icon' => 'error',
                        'showCloseButton' => 'true',
                        'confirmButtonText' => ucfirst(get_phrase('accept')),
                        'confirmButtonColor' => '#1a92c4',
                        'timer' => '10000',
                        'timerProgressBar' => 'true',
                    ));
                }
        
                redirect(base_url() . 'index.php?admin/academic_period/', 'refresh');
            }
            

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_academic_session')),
                'url' => base_url('index.php?admin/academic_period')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['academic_period']    = $this->db->get('academic_period')->result_array();
        $page_data['page_name']  = 'academic_period';
        $page_data['page_icon'] = 'entypo-clipboard';
		$page_data['page_title'] = ucfirst(get_phrase('manage_academic_session'));
        $this->load->view('backend/index', $page_data);
    }
	
    function student_bulk_add($param1 = '')
{
    if ($this->session->userdata('admin_login') != 1) {
        redirect(base_url(), 'refresh');
    }
    
    if ($param1 == 'import_excel') {
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_import.xlsx');

        include 'simplexlsx.class.php';

        $xlsx = new SimpleXLSX('uploads/student_import.xlsx');

        list($num_cols, $num_rows) = $xlsx->dimension();
        $f = 0;

        foreach ($xlsx->rows() as $r) {
            if ($f == 0) {
                $f++;
                continue;
            }

            $row_empty = true;
            foreach ($r as $cell) {
                if (!empty($cell)) {
                    $row_empty = false;
                    break;
                }
            }

            if ($row_empty) {
                break;
            }

            $data = array();
            foreach ($r as $i => $cell) {
                if ($i == '0') {
                    $dataDetails['lastname'] = $cell;
                } else if ($i == 1) {
                    $dataDetails['firstname'] = $cell;
                } else if ($i == 2) {
                    if ($cell === "0" || $cell === "Masculino" || $cell === "Male") {
                        $dataDetails['gender_id'] = 0;
                    } else if ($cell === "1" || $cell === "Femenino" || $cell === "Female") {
                        $dataDetails['gender_id'] = 1;
                    } else if ($cell === "2" || $cell === "Otro" || $cell === "Other") {
                        $dataDetails['gender_id'] = 2;
                    } else {
                        $dataDetails['gender_id'] = null; 
                    }
                } else if ($i == 3) {
                    $dataDetails['dni'] = $cell;
                } else if ($i == 4) {
                    $dataDetails['enrollment'] = $cell;
                } else if ($i == 5) {
                    $data['username'] = $cell;
                } else if ($i == 6) {
                    $data['email'] = $cell;
                } else if ($i == 7) {
                    $data['password'] = $cell;
                } else if ($i == 8) {
                    if (is_numeric($cell)) {
                        // Convertir el número a una fecha
                        $timestamp = ($cell - 25569) * 86400; // 25569 es el número de días desde 1900-01-01 a 1970-01-01
                        $dataDetails['birthday'] = date('Y-m-d', $timestamp);
                    } else {
                        // Si no es un número, convertir usando strtotime
                        $dataDetails['birthday'] = date('Y-m-d', strtotime($cell));
                    }
                } else if ($i == 9) {
                    $dataDetails['phone_cel'] = $cell;
                }  else if ($i == 10) {
                    $dataDetails['phone_fij'] = $cell;
                } else if ($i == 11) {
                    $dataAddress['state'] = 'Córdoba';
                } else if ($i == 12) {
                    $dataAddress['postalcode'] = $cell;
                } else if ($i == 13) {
                    $dataAddress['locality'] = $cell;
                } else if ($i == 14) {
                    $dataAddress['neighborhood'] = $cell;
                }  else if ($i == 15) {
                    $dataAddress['address'] = $cell;
                } else if ($i == 16) {
                    $dataAddress['address_line'] = $cell;
                } 
            }
            $dataDetails['photo'] = 'assets/images/default-user-img.jpg';

            $dataDetails['user_status_id'] = 1;

            $dataDetails['user_group_id'] = 2;

            $dataDetails['class_id'] = $this->input->post('class_id');
            if (empty($dataDetails['class_id'])) {
                $dataDetails['class_id'] = null;
            }
            
            $dataDetails['section_id'] = $this->input->post('section_id');
            if (empty($dataDetails['section_id'])) {
                $dataDetails['section_id'] = null;
            }

            $this->db->insert('address', $dataAddress);
            $insertedAddressId = $this->db->insert_id();
            $dataDetails['address_id'] = $insertedAddressId;

            $this->db->insert('student', $data);
            $insertedStudentId = $this->db->insert_id();
            $dataDetails['student_id'] = $insertedStudentId;

            $this->db->insert('student_details', $dataDetails);
        }
        $this->session->set_flashdata('flash_message', array(
            'title' => ucfirst(get_phrase('student_added_successfully')),
            'text' => '',
            'icon' => 'success',
            'showCloseButton' => 'true',
            'confirmButtonText' => ucfirst(get_phrase('accept')),
            'confirmButtonColor' => '#1a92c4',
            'timer' => '10000',
            'timerProgressBar' => 'true',
        ));
        // Si class_id o section_id son null, redirigir a pre_enrollments
        if (empty($dataDetails['class_id']) || empty($dataDetails['section_id'])) {
            redirect(base_url() . 'index.php?admin/pre_enrollments', 'refresh');
        } else {
            // De lo contrario, redirigir a student_information con el section_id
            redirect(base_url() . 'index.php?admin/student_information/' . $dataDetails['section_id'], 'refresh');
        }
    }

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('bulk_add_students')),
                'url' => base_url('index.php?admin/student_bulk_add')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']  = 'student_bulk_add';
        $page_data['page_title'] = ucfirst(get_phrase('bulk_add_students'));
        $this->load->view('backend/index', $page_data);
    }

	
	function student_information($section_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_students')). "&nbsp;&nbsp;/&nbsp;&nbsp;". $this->crud_model->get_section_name($section_id),
                'url' => base_url('index.php?admin/student_information/'.$section_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $this->db->select('
            student.student_id, 
            student.email, 
            student.username, 
            student_details.enrollment, 
            student_details.firstname, 
            student_details.lastname, 
            student_details.dni, 
            student_details.photo, 
            student_details.birthday, 
            student_details.phone_cel, 
            student_details.phone_fij, 
            student_details.section_id, 
            student_details.class_id, 
            student_details.user_status_id, 
            student_details.gender_id, 
            student_details.address_id, 
            address.state, 
            address.postalcode, 
            address.locality, 
            address.neighborhood,
            address.address,
            address.address_line
        ');
        
        // Indica que la tabla principal es student
        $this->db->from('student');
        
        // Realiza un JOIN con student_details utilizando el campo student_id
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        
        // Realiza un JOIN adicional con la tabla adress utilizando el campo adress_id
        $this->db->join('address', 'student_details.address_id = address.address_id', 'left'); // 'left' se usa para incluir estudiantes sin dirección
        
        // Añade la condición para filtrar por section_id y user_status_id
        $this->db->where('student_details.user_status_id', 1);
        $this->db->where('student_details.section_id', $section_id);
        
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Almacena los resultados en el array de datos
        $page_data['students'] = $query->result_array();

        // Actualiza los títulos y datos de la página usando section_id
        $page_data['page_name']   = 'student_information';
        $page_data['page_title']  = ucfirst(get_phrase('manage_students')). ' - ' . $this->crud_model->get_section_name($section_id);
        $page_data['section_id']  = $section_id;
        
        $this->load->view('backend/index', $page_data);
    }


    function academic_history($section_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('academic_history')). "&nbsp;&nbsp;/&nbsp;&nbsp;". $this->crud_model->get_section_name($section_id),
                'url' => base_url('index.php?admin/academic_history/'.$section_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $this->db->select('
            student.student_id, 
            student.email, 
            student.username, 
            student_details.enrollment, 
            student_details.firstname, 
            student_details.lastname, 
            student_details.dni, 
            student_details.photo, 
            student_details.birthday, 
            student_details.phone_cel, 
            student_details.phone_fij, 
            student_details.section_id, 
            student_details.class_id, 
            student_details.user_status_id, 
            student_details.gender_id, 
            student_details.address_id, 
            address.state, 
            address.postalcode, 
            address.locality, 
            address.neighborhood,
            address.address,
            address.address_line
        ');
        
        // Indica que la tabla principal es student
        $this->db->from('student');
        
        // Realiza un JOIN con student_details utilizando el campo student_id
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        
        // Realiza un JOIN adicional con la tabla adress utilizando el campo adress_id
        $this->db->join('address', 'student_details.address_id = address.address_id', 'left'); // 'left' se usa para incluir estudiantes sin dirección
        
        // Añade la condición para filtrar por section_id y user_status_id
        $this->db->where('student_details.user_status_id', 1);
        $this->db->where('student_details.section_id', $section_id);
        
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Almacena los resultados en el array de datos
        $page_data['students'] = $query->result_array();

        // Actualiza los títulos y datos de la página usando section_id
        $page_data['page_name']   = 'academic_history';
        $page_data['page_title']  = ucfirst(get_phrase('academic_history')). ' - ' . $this->crud_model->get_section_name($section_id);
        $page_data['section_id']  = $section_id;
        
        $this->load->view('backend/index', $page_data);
    }



    function teachers_information()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_teachers')),
                'url' => base_url('index.php?admin/teachers_information/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $this->db->select('teacher.teacher_id, teacher.email, teacher.username, teacher_details.firstname, teacher_details.lastname, teacher_details.dni, teacher_details.photo, teacher_details.user_status_id');
        $this->db->from('teacher');
        $this->db->join('teacher_details', 'teacher.teacher_id = teacher_details.teacher_id');
        $this->db->order_by('teacher_details.lastname', 'ASC');
        $query = $this->db->get();
        $page_data['teachers']  = $query->result_array();

        $page_data['page_name']   = 'teachers_information';
        $page_data['page_title']  = ucfirst(get_phrase('manage_teachers'));
        
        $this->load->view('backend/index', $page_data);
    }

    


    function teachers_aide_information()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_teachers_aide')),
                'url' => base_url('index.php?admin/teachers_aide_information/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $this->db->select('teacher_aide.teacher_aide_id, teacher_aide.email, teacher_aide.username, teacher_aide_details.firstname, teacher_aide_details.lastname, teacher_aide_details.dni, teacher_aide_details.photo, teacher_aide_details.user_status_id');
        $this->db->from('teacher_aide');
        $this->db->join('teacher_aide_details', 'teacher_aide.teacher_aide_id = teacher_aide_details.teacher_aide_id');
        $this->db->order_by('teacher_aide_details.lastname', 'ASC');
        $query = $this->db->get();
        $page_data['teachers_aide']  = $query->result_array();
        $page_data['page_name']   = 'teachers_aide_information';
        $page_data['page_title']  = ucfirst(get_phrase('manage_teachers_aide'));
        
        $this->load->view('backend/index', $page_data);
    }


    function secretaries_information()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_secretaries')),
                'url' => base_url('index.php?admin/secretaries_information/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $this->db->select('secretary.secretary_id, secretary.email, secretary.username, secretary_details.firstname, secretary_details.lastname, secretary_details.dni, secretary_details.photo, secretary_details.user_status_id');
        $this->db->from('secretary');
        $this->db->join('secretary_details', 'secretary.secretary_id = secretary_details.secretary_id');
        $this->db->order_by('secretary_details.lastname', 'ASC');
        $query = $this->db->get();
        $page_data['secretaries']  = $query->result_array();

        $page_data['page_name']   = 'secretaries_information';
        $page_data['page_title']  = ucfirst(get_phrase('manage_secretaries'));
        
        $this->load->view('backend/index', $page_data);
    }

    function principals_information()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_principals')),
                'url' => base_url('index.php?admin/principals_information/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $this->db->select('principal.principal_id, principal.email, principal.username, principal_details.firstname, principal_details.lastname, principal_details.dni, principal_details.photo, principal_details.user_status_id');
        $this->db->from('principal');
        $this->db->join('principal_details', 'principal.principal_id = principal_details.principal_id');
        $this->db->order_by('principal_details.lastname', 'ASC');
        $query = $this->db->get();
        $page_data['principals']  = $query->result_array();

        $page_data['page_name']   = 'principals_information';
        $page_data['page_title']  = ucfirst(get_phrase('manage_principals'));
        
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







    function re_enrollments($section_id = '')
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
                'text' => ucfirst(get_phrase('re_enrollments')). "&nbsp;&nbsp;/&nbsp;&nbsp;". $this->crud_model->get_section_history_name($section_id) . " - " . $this->crud_model->get_academic_period_name_per_section_history($section_id),
                'url' => base_url('index.php?admin/re_enrollments/'.$section_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;
    
        $this->db->select('student.student_id, student.email, student.username, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo');
        $this->db->from('student');
        // JOIN con student_details
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        // JOIN con academic_history para filtrar por old_section_id
        $this->db->join('academic_history', 'student.student_id = academic_history.student_id');
        // Filtrar según el section_id comparado con old_section_id
        $this->db->where('academic_history.old_section_id', $section_id);
        // Ejecutar la consulta
        $this->db->where('student_details.class_id', NULL);
        $this->db->where('student_details.section_id', NULL);
        $query = $this->db->get();
        
        // Almacena los resultados en el array de datos
        $page_data['students']  = $query->result_array();



         // Obtener old_section
        $old_section = $this->db->get_where('section_history', array('section_id' => $section_id))->row();

        // Obtener old_academic_period (más reciente con status_id = 0)
        $old_academic_period = $this->db
            ->where('status_id', 0)
            ->order_by('end_date', 'DESC')
            ->limit(1)
            ->get('academic_period')
            ->row();
        $old_academic_period_id = $old_academic_period ? $old_academic_period->id : null;

        // Filtrar old_sections según old_academic_period
        $this->db->where('academic_period_id', $old_academic_period_id);
        $sections = $this->db->get('section_history')->result_array();

        // Enviar old_sections a la vista
        $page_data['sections'] = $sections;

    
        // Actualiza los títulos y datos de la página usando section_id
        $page_data['page_name']   = 're_enrollments';
        $page_data['page_icon']   = 'entypo-graduation-cap';
        $page_data['page_title']  = ucfirst(get_phrase('re_enrollments')). ' - ' . $this->crud_model->get_section_history_name($section_id) . " - " . $this->crud_model->get_academic_period_name_per_section_history($section_id);
        $page_data['section_id']  = $section_id;
        
        $this->load->view('backend/index', $page_data);
    }
    


    function pre_enrollments()
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
                'text' => ucfirst(get_phrase('enrollment_section')),
                'url' => base_url('index.php?admin/pre_enrollments/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

            $this->db->select('
            student.student_id, 
            student.email, 
            student.username, 
            student_details.enrollment, 
            student_details.firstname, 
            student_details.lastname, 
            student_details.dni, 
            student_details.photo, 
            student_details.birthday, 
            student_details.phone_cel, 
            student_details.phone_fij, 
            student_details.section_id, 
            student_details.class_id, 
            student_details.user_status_id, 
            student_details.gender_id, 
            student_details.address_id, 
            address.state, 
            address.postalcode, 
            address.locality, 
            address.neighborhood,
            address.address,
            address.address_line
        ');
        $this->db->from('student');
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        
        $this->db->join('address', 'student_details.address_id = address.address_id', 'left'); 
        $this->db->where('student_details.user_status_id', 1);
        $this->db->where('student_details.class_id', NULL);
        $this->db->where('student_details.section_id', NULL);
        $query = $this->db->get();
        $page_data['students']  = $query->result_array();

        $page_data['page_name']   = 'pre_enrollments';
        $page_data['page_icon']   = 'entypo-graduation-cap';
        $page_data['page_title']  = ucfirst(get_phrase('enrollment_section'));
        
        $this->load->view('backend/index', $page_data);
    }


    function payments()
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
                'text' => 'payments',
                'url' => base_url('index.php?admin/payments/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $this->db->select('student.student_id, student.email, student.username, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.section_id, student_details.class_id, student_details.user_status_id');
        $this->db->from('student');
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        $this->db->where('student_details.user_status_id', 1);
        $this->db->where('student_details.class_id IS NULL');
        $this->db->or_where('student_details.class_id', '');
        $this->db->where('student_details.user_status_id', 1);
        $this->db->where('student_details.section_id IS NULL');
        $this->db->or_where('student_details.section_id', '');
        $this->db->where('student_details.user_status_id', 1);
        $query = $this->db->get();
        $page_data['students']  = $query->result_array();


        $page_data['page_name']   = 'payments';
        $page_data['page_title']  = 'payments';
        
        $this->load->view('backend/index', $page_data);
    }


    function admissions()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('admissions')),
                'url' => base_url('index.php?admin/admissions/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb; 

        $this->db->select('
            student.student_id, 
            student.email, 
            student.username, 
            student_details.enrollment, 
            student_details.firstname, 
            student_details.lastname, 
            student_details.dni, 
            student_details.photo, 
            student_details.birthday, 
            student_details.phone_cel, 
            student_details.phone_fij, 
            student_details.section_id, 
            student_details.class_id, 
            student_details.user_status_id, 
            student_details.gender_id, 
            student_details.address_id, 
            student_details.status_reason,
            address.state, 
            address.postalcode, 
            address.locality, 
            address.neighborhood,
            address.address,
            address.address_line
        ');
        $this->db->from('student');
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        
        $this->db->join('address', 'student_details.address_id = address.address_id', 'left'); 
        
        $this->db->where('student_details.user_status_id', 0);
        $this->db->where('student_details.class_id IS NULL');
        $this->db->or_where('student_details.class_id', '');
        $this->db->where('student_details.section_id IS NULL');
        $this->db->or_where('student_details.section_id', '');
        $query = $this->db->get();
        $page_data['students']  = $query->result_array();

        $page_data['page_name']   = 'admissions';
        $page_data['page_icon']   = 'entypo-graduation-cap';
        $page_data['page_tile']   = 'admissions';
        $page_data['page_title']  = ucfirst(get_phrase('admissions'));
        
        $this->load->view('backend/index', $page_data);
    }


    function student_profile($student_id = '')
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
                'text' => ucfirst(get_phrase('manage_students')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/student_profile/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'student_profile';
        $page_data['page_title']  = ucfirst(get_phrase('view_profile'));
        $page_data['param2']  = $student_id;
        
        $this->load->view('backend/index', $page_data);
    }



    function teacher_profile($teacher_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_teachers')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/teacher_profile/' . $teacher_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'teacher_profile';
        $page_data['page_title']  = ucfirst(get_phrase('view_profile'));
        $page_data['param2']  = $teacher_id;
        
        $this->load->view('backend/index', $page_data);
    }

    function teacher_aide_profile($teacher_aide_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_teachers_aide')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/teacher_aide_profile/' . $teacher_aide_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'teacher_aide_profile';
        $page_data['page_title']  = ucfirst(get_phrase('view_profile'));
        $page_data['param2']  = $teacher_aide_id;
        
        $this->load->view('backend/index', $page_data);
    }


    function secretary_profile($secretary_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_secretaries')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/secretary_profile/' . $secretary_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'secretary_profile';
        $page_data['page_title']  = ucfirst(get_phrase('view_profile'));
        $page_data['param2']  = $secretary_id;
        
        $this->load->view('backend/index', $page_data);
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

    function manage_profile($user_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_profile')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/manage_profile/' . $user_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'manage_profile';
        $page_data['page_title']  = ucfirst(get_phrase('manage_profile')) . ' - ' . ucfirst(get_phrase('view_profile'));
        $page_data['user_id']  = $user_id;
        
        $this->load->view('backend/index', $page_data);
    }

  

    function help()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('help')),
                'url' => base_url('index.php?admin/help/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'help';
        $page_data['page_title']  = ucfirst(get_phrase('help'));
        
        $this->load->view('backend/index', $page_data);
    }



    function guardian_profile($guardian_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_guardians')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/guardian_profile/' . $guardian_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'guardian_profile';
        $page_data['page_title']  = ucfirst(get_phrase('view_profile'));
        $page_data['param2']  = $guardian_id;
        
        $this->load->view('backend/index', $page_data);
    }


    function principal_profile($principal_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_principals')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/principal_profile/' . $principal_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'principal_profile';
        $page_data['page_title']  = ucfirst(get_phrase('view_profile'));
        $page_data['param2']  = $principal_id;
        
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

    function subject_profile($subject_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('subject_profile')),
                'url' => base_url('index.php?admin/subject_profile/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'subject_profile';
        $page_data['page_title']  = ucfirst(get_phrase('subject_profile'));
        $page_data['subject_id']  = $subject_id;
        
        $this->load->view('backend/index', $page_data);
    }

	
    function student($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['email']       			= $this->input->post('email');
            $data['username']    			= $this->input->post('username');
            $data['password']    			= $this->input->post('password');

            $this->db->insert('student', $data);
            $insertedStudentId = $this->db->insert_id();

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $this->db->insert('address', $dataAddress);
            $insertedAddressId = $this->db->insert_id();
            
            $dataDetails['student_id'] = $insertedStudentId;
            $dataDetails['address_id'] = $insertedAddressId;
            $dataDetails['user_group_id']        			= '2';
            $dataDetails['firstname']        			= $this->input->post('firstname');
            $dataDetails['lastname']        			= $this->input->post('lastname');
            $dataDetails['enrollment']        			= $this->input->post('enrollment');
            $dataDetails['dni']        			= $this->input->post('dni');
            $dataDetails['birthday']        			= $this->input->post('birthday');
            $dataDetails['about']        			= $this->input->post('about');
            $dataDetails['phone_cel']       			= $this->input->post('phone_cel');
            $dataDetails['phone_fij']       			= $this->input->post('phone_fij');
            $dataDetails['gender_id']  			= $this->input->post('gender_id');
            $dataDetails['class_id']  	 = NULL;
            $dataDetails['section_id']  	 = NULL;
            $dataDetails['user_status_id']  	 = 1;

            if (!empty($_FILES['userfile']['name'])) {
                $file_name = 'student id - ' . $insertedStudentId . '.jpg';
                $file_path = 'uploads/student_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
                $dataDetails['photo'] = $file_path;
            } else {
                $dataDetails['photo'] = 'assets/images/default-user-img.jpg';
            }

            if (!empty($_FILES['medical_record_file']['name'])) {
                $medical_file_extension = pathinfo($_FILES['medical_record_file']['name'], PATHINFO_EXTENSION);
                $medical_file_name = 'ficha medica id - ' . $insertedStudentId . '.' . $medical_file_extension;
                $medical_file_path = 'uploads/fichas_medicas/' . $medical_file_name;
                move_uploaded_file($_FILES['medical_record_file']['tmp_name'], $medical_file_path);
        
                $dataDetails['medical_record'] = $medical_file_path;
            }

            $this->db->insert('student_details', $dataDetails);

            // Insertar tutores
            $guardian_ids = $this->input->post('guardian_id');
            $relationships = $this->input->post('relationship');

            if (!empty($guardian_ids) && !empty($relationships)) {
                foreach ($guardian_ids as $index => $guardian_id) {
                    $dataGuardian = array(
                        'student_id' => $insertedStudentId,
                        'guardian_id' => $guardian_id,
                        'guardian_type_id' => $relationships[$index]
                    );
                    $this->db->insert('student_guardian', $dataGuardian);
                }
            }



            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('student_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/pre_enrollments/', 'refresh');
        }
        if ($param1 == 'update') {
            $student_id = $param2; 
            $student_details = $this->db->get_where('student_details', array('student_id' => $student_id))->row_array();
            $address_id = $student_details['address_id'];

            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');
        
            $this->db->where('student_id', $student_id);
            $this->db->update('student', $data);
        
            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');
        
            $this->db->where('address_id', $address_id);
            $this->db->update('address', $dataAddress);
        
            $dataDetails['firstname'] = $this->input->post('firstname');
            $dataDetails['lastname'] = $this->input->post('lastname');
            $dataDetails['enrollment'] = $this->input->post('enrollment');
            $dataDetails['dni'] = $this->input->post('dni');
            $dataDetails['birthday'] = $this->input->post('birthday');
            $dataDetails['about'] = $this->input->post('about');
            $dataDetails['phone_cel'] = $this->input->post('phone_cel');
            $dataDetails['phone_fij'] = $this->input->post('phone_fij');
            $dataDetails['gender_id'] = $this->input->post('gender_id');
            $dataDetails['class_id'] = $this->input->post('class_id');
            $dataDetails['section_id'] = $this->input->post('section_id');

            if ($student_details['class_id'] != $dataDetails['class_id'] || $student_details['section_id'] != $dataDetails['section_id']) {
        
                $this->db->select('academic_period_id');
                $this->db->from('section');
                $this->db->where('section_id', $student_details['section_id']);
                $old_section = $this->db->get()->row();
        
                $this->db->select('academic_period_id');
                $this->db->from('section');
                $this->db->where('section_id', $dataDetails['section_id']);
                $new_section = $this->db->get()->row();
        
                $dataAcademic['old_class_id'] = $student_details['class_id'];
                $dataAcademic['new_class_id'] = $dataDetails['class_id'];
                $dataAcademic['old_section_id'] = $student_details['section_id'];
                $dataAcademic['new_section_id'] = $dataDetails['section_id'];
                $dataAcademic['old_academic_period_id'] = $old_section ? $old_section->academic_period_id : null;
                $dataAcademic['new_academic_period_id'] = $new_section ? $new_section->academic_period_id : null;
                $dataAcademic['date_change'] = date('Y-m-d');
        
                $this->db->where('student_id', $student_id);
                $this->db->update('academic_history', $dataAcademic);
            }

        
            if (!empty($_FILES['userfile']['name'])) {
                if (!empty($student_details['photo']) && file_exists($student_details['photo'])) {
                    unlink($student_details['photo']);
                }
                $file_name = 'student id - ' . $student_id . '.jpg';
                $file_path = 'uploads/student_image/' . $file_name;
                $dataDetails['photo'] = $file_path;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
            } else {
                $dataDetails['photo'] = $student_details['photo'];
            }

            if (!empty($_FILES['medical_record_file']['name'])) {
                if (!empty($student_details['medical_record']) && file_exists($student_details['medical_record'])) {
                    unlink($student_details['medical_record']);
                }
                $medical_file_extension = pathinfo($_FILES['medical_record_file']['name'], PATHINFO_EXTENSION);
                $medical_file_name = 'ficha medica id - ' . $student_id . '.' . $medical_file_extension;
                $medical_file_path = 'uploads/fichas_medicas/' . $medical_file_name;
                $dataDetails['medical_record'] = $medical_file_path;
                move_uploaded_file($_FILES['medical_record_file']['tmp_name'], $medical_file_path);
            }
        
            $this->db->where('student_id', $student_id);
            $this->db->update('student_details', $dataDetails);


            $existing_guardian_ids = $this->input->post('existing_guardian_ids') ?: []; // IDs de guardianes existentes
            $guardian_ids = $this->input->post('guardian_id');
            $relationships = $this->input->post('relationship');
            
            // Obtener los guardian_id actuales para el estudiante
            $current_guardians = $this->db->select('guardian_id')->where('student_id', $student_id)->get('student_guardian')->result_array();
            $current_guardian_ids = array_column($current_guardians, 'guardian_id');
            
            // Identificar guardianes para eliminar
            $guardian_ids_to_delete = array_diff($current_guardian_ids, $existing_guardian_ids);
            
            // print_r($existing_guardian_ids);
            // print_r($guardian_ids);
            // print_r($guardian_ids_to_delete);
            // exit();
            
            if (!empty($guardian_ids_to_delete)) {
                $this->db->where('student_id', $student_id);
                $this->db->where_in('guardian_id', $guardian_ids_to_delete);
                $this->db->delete('student_guardian');
            }
            
            // Insertar o actualizar guardianes nuevos y existentes
            foreach ($guardian_ids as $index => $guardian_id) {
                if (!in_array($guardian_id, $existing_guardian_ids)) {
                    // Insertar nuevos tutores
                    $dataGuardian = array(
                        'student_id' => $student_id,
                        'guardian_id' => $guardian_id,
                        'guardian_type_id' => $relationships[$index]
                    );
                    $this->db->insert('student_guardian', $dataGuardian);
                } else {
                    // Actualizar guardianes existentes
                    $dataGuardian = array(
                        'guardian_type_id' => $relationships[$index]
                    );
                    $this->db->where('student_id', $student_id);
                    $this->db->where('guardian_id', $guardian_id);
                    $this->db->update('student_guardian', $dataGuardian);
                }
            }
        
            $this->session->set_flashdata('flash_message', array(
               'title' => ucfirst(get_phrase('student_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/student_information/' . $dataDetails['section_id'], 'refresh');
        } 
		
        if ($param1 == 'inactive_student') {
            $student_id = $param2;  
        
            $reason = $this->input->post('reason');
            $other_reason = $this->input->post('other_reason');
            
            $status_reason = !empty($other_reason) ? $other_reason : $reason;
    
            if ($student_id) {
                $this->db->where('student_id', $student_id);
                $this->db->update('student_details', array(
                    'user_status_id' => 0, 
                    'class_id' => null,
                    'section_id' => null,
                    'status_reason' => $status_reason  
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('student_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_student')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            // Redirigir de vuelta a la página de inactivación de estudiantes
            redirect(base_url() . 'index.php?admin/re_enrollments/'. $param3, 'refresh');
        }

        if ($param1 == 'inactive_student_pre_enrollements') {
            $student_id = $param2;  
        
            $reason = $this->input->post('reason');
            $other_reason = $this->input->post('other_reason');
            
            $status_reason = !empty($other_reason) ? $other_reason : $reason;
    
            if ($student_id) {
                $this->db->where('student_id', $student_id);
                $this->db->update('student_details', array(
                    'user_status_id' => 0, 
                    'class_id' => null,
                    'section_id' => null,
                    'status_reason' => $status_reason  
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('student_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_student')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/pre_enrollments/', 'refresh');
        }
        
    }



    function parent($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']        			= $this->input->post('name');
            $data['dni']        			= $this->input->post('dni');
            $data['email']       			= $this->input->post('email');
            $data['password']    			= $this->input->post('password');
            $data['phone_cel']       			= $this->input->post('phone_cel');
            $data['phone_fij']       			= $this->input->post('phone_fij');
            $data['address']     			= $this->input->post('address');
            $data['gender']  			= $this->input->post('gender');
            $this->db->insert('parent', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('datos agregados exitosamente'));
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        if ($param1 == 'edit') {
            $data['name']        			= $this->input->post('name');
            $data['dni']        			= $this->input->post('dni');
            $data['email']       			= $this->input->post('email');
            $data['password']    			= $this->input->post('password');
            $data['phone_cel']       			= $this->input->post('phone_cel');
            $data['phone_fij']       			= $this->input->post('phone_fij');
            $data['address']     			= $this->input->post('address');
            $data['gender']  			= $this->input->post('gender');
            $this->db->where('parent_id' , $param2);
            $this->db->update('parent' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('datos actualizados exitosamente'));
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        if ($param1 == 'delete') {
            $this->db->where('parent_id' , $param2);
            $this->db->delete('parent');
            $this->session->set_flashdata('flash_message' , get_phrase('datos eliminados exitosamente'));
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        $page_data['page_title'] 	= ucfirst(get_phrase('parent_section'));
        $page_data['page_icon'] 	= 'entypo-users';
        $page_data['page_name']  = 'parent';
        $this->load->view('backend/index', $page_data);
    }
	
   
   
    
    

    


   
    
   


    

   

 

  

  


  

   

    // function get_postal_codes() {
    //     $postal_codes = $this->db->get('postal_code_cba')->result_array();
    //     foreach ($postal_codes as $row) {
    //         echo '<option value="' . $row['postal_code'] . '">' . $row['postal_code'] . '</option>';
    //     }
    // }

    function get_postal_codes() {
        $this->db->select('postal_code');
        $this->db->distinct();
        $postal_codes = $this->db->get('postal_code_cba')->result_array();
        foreach ($postal_codes as $row) {
            echo '<option value="' . $row['postal_code'] . '">' . $row['postal_code'] . '</option>';
        }
    }

    function get_guardians() {
        $guardians = $this->crud_model->get_guardians();
        
        foreach ($guardians as $row) {
            $guardian_details = $this->crudGuardian->get_guardian_info($row['guardian_id']);
            
            // Verificar si se encontraron detalles del guardián
            $firstname = isset($guardian_details['firstname']) ? $guardian_details['firstname'] : '';
            $lastname = isset($guardian_details['lastname']) ? $guardian_details['lastname'] : '';
    
            echo '<option value="' . $row['guardian_id'] . '" data-firstname="' . $firstname . '" data-lastname="' . $lastname . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
        }
    }

    function get_class_subject($class_id)
    {
        $subjects = $this->db->get_where('subject' , array(
            'class_id' => $class_id
        ))->result_array();
        foreach ($subjects as $row) {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
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
	
    // function attendance_teacher($date='',$month='',$year='',$class_id='')
	// {
    //     if ($this->session->userdata('admin_login') != 1)
    //     redirect(base_url(), 'refresh');
		
	// 	if($_POST)
	// 	{
    //         $students   =   $this->db->get_where('student', array('class_id' => $class_id))->result_array();
    //         foreach ($students as $row)
    //         {
    //             $attendance_status  =   $this->input->post('status_' . $row['student_id']);

    //             $this->db->where('student_id' , $row['student_id']);
    //             $this->db->where('date' , $this->input->post('date'));

    //             $this->db->update('attendance' , array('status' => $attendance_status));
    //         }

	// 		$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
	// 		redirect(base_url() . 'index.php?admin/attendance/'.$date.'/'.$month.'/'.$year.'/'.$class_id , 'refresh');
	// 	}
    //     $page_data['date']     =	$date;
    //     $page_data['month']    =	$month;
    //     $page_data['year']     =	$year;
    //     $page_data['class_id'] =	$class_id;
		
    //     $page_data['page_name']  =	'attendance';
    //     $page_data['page_title'] =	'Administrar asistencia';
	// 	$this->load->view('backend/index', $page_data);
	// }

    // function attendance_teacher_selector()
	// {
	// 	redirect(base_url() . 'index.php?admin/attendance/'.$this->input->post('date').'/'.
	// 				$this->input->post('month').'/'.
	// 					$this->input->post('year').'/'.
	// 						$this->input->post('class_id') , 'refresh');
	// }

    
    // function attendance_student($class_id = '') {
    //     if ($this->session->userdata('admin_login') != 1)
    //         redirect(base_url(), 'refresh');
        
    //     $data['all_classes'] = $this->db->get('class')->result_array();
    //     $data['page_name'] = 'attendance_student';
    //     $data['page_icon'] = 'entypo-clipboard';
    //     $data['page_title'] = 'Administrar asistencia de estudiantes';
    //     $data['class_id'] = $class_id;
    //     $this->load->view('backend/index', $data);
    // }
    
    


    

    // function upload_observation_student_image () 
    // {
    //     $day      = $this->input->post('day');
    //     $month  = $this->input->post('month');
    //     $year = $this->input->post('year');
    //     $class_id  = $this->input->post('class_id');
    //     $section_id = $this->input->post('section_id');
    //     $student_id = $this->input->post('student_id');

    //     $student = $this->db->get_where('student', array('student_id' => $student_id))->row_array();
    //     $file_name = $student['name'] . '-' . $student['matricula']  . '-ausenciaJustificada.jpg';
    //     $file_path = 'uploads/asistencias/' . $file_name;

    //     move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
    //     redirect(base_url() . 'index.php?admin/manage_attendance_student/'.$day.'/'.$month.'/'.$year.'/'.$class_id.'/'.$section_id);
    // }

    function summary_attendance_student($section_id='')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        
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

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('manage_student_attendance')),
                'url' => base_url('index.php?admin/attendance_student/' . $section_id)
            ),
            array(
                'text' => ucfirst(get_phrase('attendance_summary')).  ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') .  '&nbsp;&nbsp;/&nbsp;&nbsp;' . $this->crud_model->get_section_name2($section_id),
                'url' => null
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']  = 'summary_attendance_student';
        $page_data['page_icon'] = 'entypo-clipboard';
        $page_data['page_title'] 	= ucfirst(get_phrase('attendance_summary')). " - " . $this->crud_model->get_section_name($section_id);
        $page_data['subject_amount'] = $this->crud_model->get_section_subject_amount2($section_id);
        $page_data['student_amount'] = $this->crud_model->get_section_student_amount2($section_id);
        $page_data['used_section_history'] = $used_section_history;
        // $page_data['attendance_student_presente'] = $this->crudAttendance->get_attendance_student_section_amount($section_id, 1);
        // $page_data['attendance_student_ausente'] = $this->crudAttendance->get_attendance_student_section_amount($section_id, 2);
        // $page_data['attendance_student_tardanza'] = $this->crudAttendance->get_attendance_student_section_amount($section_id, 3);
        // $page_data['attendance_student_ausencia_justificada'] = $this->crudAttendance->get_attendance_student_section_amount($section_id, 4);

        $page_data['day_data'] = $this->crudAttendance->get_attendance_data_for_chart2($section_id);

        $page_data['section_name'] 	= $this->crud_model->get_section_name2($section_id);
        $page_data['section_id']   = $section_id;
        $this->load->view('backend/index', $page_data);    

        // Consultas a la base de datos para obtener los datos necesarios
        // $complete_class_name = $this->crud_model->get_class_name_numeric($class_id) . "° " . $this->crud_model->get_section_letter_name($section_id);
        // $subject_amount = $this->crud_model->get_section_subject_amount($section_id);
        // $student_amount = $this->crud_model->get_section_student_amount($section_id);
        // $attendance_student_presente = $this->crudAttendance->get_attendance_student_section_amount($section_id, 1);
        // $attendance_student_ausente = $this->crudAttendance->get_attendance_student_section_amount($section_id, 2);
        // $attendance_student_tardanza = $this->crudAttendance->get_attendance_student_section_amount($section_id, 3);
        // $attendance_student_ausencia_justificada = $this->crudAttendance->get_attendance_student_section_amount($section_id, 4);

        // // Datos a enviar a la vista
        // $data = array(
        //     'page_name' => 'summary_attendance_student',
        //     'page_icon' => 'entypo-clipboard',
        //     'page_title' => 'Resumen de asistencia - ' . $complete_class_name,
        //     'class_id' => $class_id,
        //     'complete_class_name' => $complete_class_name,
        //     'subject_amount' => $subject_amount,
        //     'student_amount' => $student_amount,
        //     'attendance_student_presente' => $attendance_student_presente,
        //     'attendance_student_ausente' => $attendance_student_ausente,
        //     'attendance_student_tardanza' => $attendance_student_tardanza,
        //     'attendance_student_ausencia_justificada' => $attendance_student_ausencia_justificada,
        //     'section_id' => $section_id
        // );

        // // Cargar la vista con los datos
        // $this->load->view('backend/index', $data);
    }


    function filter_attendance($section_id = '', $filter_type = '', $date = '', $start_date = '', $end_date = '', $dateMoth = '', $start_date_yearly = '', $end_date_yearly = '') {
        $attendance_student_presente = $this->crudAttendance->get_attendance_student_section_amount2(
            $section_id, 1, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_ausente = $this->crudAttendance->get_attendance_student_section_amount2(
            $section_id, 2, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_tardanza = $this->crudAttendance->get_attendance_student_section_amount2(
            $section_id, 3, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_ausencia_justificada = $this->crudAttendance->get_attendance_student_section_amount2(
            $section_id, 4, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        // Calcular porcentajes
        $total_attendance = $attendance_student_presente + $attendance_student_ausente + $attendance_student_tardanza + $attendance_student_ausencia_justificada;
        $percentage_presente = $total_attendance > 0 ? number_format(($attendance_student_presente / $total_attendance) * 100, 2) : 0;
        $percentage_ausente = $total_attendance > 0 ? number_format(($attendance_student_ausente / $total_attendance) * 100, 2) : 0;
        $percentage_tardanza = $total_attendance > 0 ? number_format(($attendance_student_tardanza / $total_attendance) * 100, 2) : 0;
        $percentage_justificados = $total_attendance > 0 ? number_format(($attendance_student_ausencia_justificada / $total_attendance) * 100, 2) : 0;
    
        // Enviar datos como JSON
        echo json_encode([
            'attendance_student_presente' => $attendance_student_presente,
            'attendance_student_ausente' => $attendance_student_ausente,
            'attendance_student_tardanza' => $attendance_student_tardanza,
            'attendance_student_ausencia_justificada' => $attendance_student_ausencia_justificada,
            'percentage_presente' => $percentage_presente,
            'percentage_ausente' => $percentage_ausente,
            'percentage_tardanza' => $percentage_tardanza,
            'percentage_justificados' => $percentage_justificados
        ]);
    }
    
    

    function filter_attendance_student($student_id = '', $filter_type = '', $date = '', $start_date = '', $end_date = '', $dateMoth = '' , $start_date_yearly = '', $end_date_yearly = '') {
        $attendance_student_presente = $this->crudAttendance->get_attendance_student_amount(
            $student_id, 1, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_ausente = $this->crudAttendance->get_attendance_student_amount(
            $student_id, 2, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_tardanza = $this->crudAttendance->get_attendance_student_amount(
            $student_id, 3, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_ausencia_justificada = $this->crudAttendance->get_attendance_student_amount(
            $student_id, 4, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        // Calcular porcentajes
        $total_attendance = $attendance_student_presente + $attendance_student_ausente + $attendance_student_tardanza + $attendance_student_ausencia_justificada;
        $percentage_presente = $total_attendance > 0 ? number_format(($attendance_student_presente / $total_attendance) * 100, 2) : 0;
        $percentage_ausente = $total_attendance > 0 ? number_format(($attendance_student_ausente / $total_attendance) * 100, 2) : 0;
        $percentage_tardanza = $total_attendance > 0 ? number_format(($attendance_student_tardanza / $total_attendance) * 100, 2) : 0;
        $percentage_justificados = $total_attendance > 0 ? number_format(($attendance_student_ausencia_justificada / $total_attendance) * 100, 2) : 0;
    
        // Enviar datos como JSON
        echo json_encode([
            'attendance_student_presente' => $attendance_student_presente,
            'attendance_student_ausente' => $attendance_student_ausente,
            'attendance_student_tardanza' => $attendance_student_tardanza,
            'attendance_student_ausencia_justificada' => $attendance_student_ausencia_justificada,
            'percentage_presente' => $percentage_presente,
            'percentage_ausente' => $percentage_ausente,
            'percentage_tardanza' => $percentage_tardanza,
            'percentage_justificados' => $percentage_justificados
        ]);
    }

    

    function details_attendance_student($student_id='')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $student_info = $this->crudStudent->get_student_info($student_id);
       
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_student_attendance')),
                'url' => base_url('index.php?admin/attendance_student/')
            ),
            array(
                'text' => $this->crud_model->get_class_name_numeric($student_info[0]['class_id']) . '° ' . $this->crud_model->get_section_letter_name($student_info[0]['section_id']),
                'url' => base_url('index.php?admin/summary_attendance_student/' . $student_info[0]['section_id'])
            ),
            array(
                // 'text' => $student_info[0]['name'],
                'text' => ucfirst(get_phrase('student_details')),
                'url' => null
            )
        );
            
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']  = 'details_attendance_student';
        $page_data['page_title'] 	= ucfirst(get_phrase('attendance_summary')). " - ". ucfirst(get_phrase('student_details'));
        $page_data['student_id'] = $student_id;

        $page_data['student_lastname_firstname'] 	= ucfirst($student_info[0]['lastname']) . ', ' . ucfirst($student_info[0]['firstname']);
        $page_data['section_name'] 	= $this->crud_model->get_section_name($student_info[0]['section_id']);

        $this->load->view('backend/index', $page_data);    
    }

    function edit_attendance_student($param1 = '', $param2 = '')
    {
            $data['status']     = $this->input->post('status');
            $data['date']       = $this->input->post('date');
            $data['observation']       = $this->input->post('observation');

            $this->db->where(array(
                'student_id' => $param1,
                'date' => $param2
            ));
            $this->db->update('attendance_student', $data);

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Datos actualizados correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/details_attendance_student/' . $param1, 'refresh');
    }

    function student_mark_history($class_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('manage_marks')),
                'url' => base_url('index.php?admin/attendance_student/' . $class_id)
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'student_mark_history';
		$page_data['page_title'] 	= ucfirst(get_phrase('academic_history')) . ' - ' . $this->crud_model->get_class_name($class_id);
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}

    function exams_information($section_id = '', $subject_id = '')
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


    
    function academic_period_add()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('academic_period_add')),
                'url' => base_url('index.php?admin/academic_period_add')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'academic_period_add';
		$page_data['page_title'] = ucfirst(get_phrase('academic_period_add'));
		$this->load->view('backend/index', $page_data);
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


    function edit_news($param2 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

            $page_complete_name = 'edit_news'; // Nombre de la página
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
                'text' => ucfirst(get_phrase('edit_news')),
                'url' => base_url('index.php?admin/edit_news')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['param2'] = $param2;
		$page_data['page_name']  = 'edit_news';
		$page_data['page_title'] = ucfirst(get_phrase('edit_news'));
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


    function get_section_subjects($section_id)
    {
        $subjects = $this->db->get_where('subject' , array(
            'section_id' => $section_id
        ))->result_array();
        foreach ($subjects as $row) {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
        }
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

    function profile_settings($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($param1 == 'update_profile_info') {
            $user_id = $this->session->userdata('login_user_id');

            $dataDetails['firstname']  = $this->input->post('firstname');
            $dataDetails['lastname']  = $this->input->post('lastname');
            $data['email'] = $this->input->post('email');
            
            $this->db->where('admin_id', $user_id);
            $this->db->update('admin', $data);

            if (!empty($_FILES['userfile']['name'])) {
                $file_name = 'admin id - ' . $user_id . '.jpg';
                $file_path = 'uploads/admin_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
                $dataDetails['photo'] = $file_path;
            } else {
                $dataDetails['photo'] = 'assets/images/default-user-img.jpg';
            }

            $this->db->where('admin_id', $user_id);
            $this->db->update('admin_details', $dataDetails);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('profile_updated_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/profile_settings/' . $user_id, 'refresh');
        }
        if ($param1 == 'change_password') {
            $user_id = $this->session->userdata('login_user_id');

            $data['password']             = $this->input->post('password');
            $data['new_password']         = $this->input->post('new_password');
            $data['confirm_new_password'] = $this->input->post('confirm_new_password');
            
            $current_password = $this->db->get_where('admin', array(
                'admin_id' => $user_id
            ))->row()->password;
            if ($current_password !== $data['password']) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('the_old_password_does_not_match')) . '!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                $this->db->where('admin_id', $this->session->userdata('admin_id'));
                $this->db->update('admin', array(
                    'password' => $data['new_password']
                ));
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('password_updated_successfully')) . '!',
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
                    'title' => ucfirst(get_phrase('new_password_and_confirmation_do_not_match')) . '!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/profile_settings/' . $user_id, 'refresh');
        }

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('profile_settings')),
                'url' => base_url('index.php?admin/manage_profile')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']  = 'profile_settings';
        $page_data['page_title'] = ucfirst(get_phrase('profile_settings'));
        $page_data['edit_data'] = $this->crud_model->get_admin_info($this->session->userdata('admin_id'));
    
        $this->load->view('backend/index', $page_data);
    }

    function news($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
            if ($param1 == 'create') {
                $data['title'] = $this->input->post('title');
                $data['date'] = $this->input->post('date');
                $data['body'] = $this->input->post('body');
                $data['news_type_id'] = $this->input->post('news_type_id');
                $data['status_id'] = $this->input->post('status_id');
                $data['user_type'] = $this->input->post('user_type');
            
                $data['class_id'] = ($this->input->post('class_id') == 0) ? null : $this->input->post('class_id');
                $data['section_id'] = ($this->input->post('section_id') == 0) ? null : $this->input->post('section_id');
            
                $this->db->insert('news', $data);
                $news_id = $this->db->insert_id();

                if (!empty($_FILES['images']['name'][0])) { 
                    $exam_directory = 'uploads/news/' . $news_id . '/';
                    if (!is_dir($exam_directory)) {
                        mkdir($exam_directory, 0777, true); 
                    }

                    $this->load->library('upload'); 
                    
                    $files = $_FILES;
                    $number_of_files = count($_FILES['images']['name']);
                    
                    $uploaded_files = []; 

                    for ($i = 0; $i < $number_of_files; $i++) {
                        $_FILES['attachment']['name'] = $files['images']['name'][$i];
                        $_FILES['attachment']['type'] = $files['images']['type'][$i];
                        $_FILES['attachment']['tmp_name'] = $files['images']['tmp_name'][$i];
                        $_FILES['attachment']['error'] = $files['images']['error'][$i];
                        $_FILES['attachment']['size'] = $files['images']['size'][$i];

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

                            redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
                        }
                    }

                    $files_json = json_encode($uploaded_files);
                    $this->db->where('news_id', $news_id);
                    $this->db->update('news', ['images' => $files_json]);
                }
            
                // Muestra un mensaje de éxito
                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('news_added_successfully')) . '!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));

            
                // Redirige a la página de gestión de noticias
                redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
            }
            
            if ($param1 == 'update') {
                $data['title'] = $this->input->post('title');
                $data['date'] = $this->input->post('date');
                $data['body'] = $this->input->post('body');
                $data['news_type_id'] = $this->input->post('news_type_id');
                $data['status_id'] = $this->input->post('status_id');
                $data['user_type'] = $this->input->post('user_type');
                $data['class_id'] = ($this->input->post('class_id') == 0) ? null : $this->input->post('class_id');
                $data['section_id'] = ($this->input->post('section_id') == 0) ? null : $this->input->post('section_id');
            
                
                $existing_files = $this->db->get_where('news', array('news_id' => $param2))->row()->images;
                $existing_files = !empty($existing_files) ? json_decode($existing_files, true) : [];
                
            
                $new_files = !empty($_FILES['images']['name'][0]) ? $_FILES['images']['name'] : [];
            
                $files_to_delete = $this->input->post('files_to_delete');
                $files_to_delete = is_array($files_to_delete) ? $files_to_delete : [];
                
            
                $files_to_keep = array_diff($existing_files, $files_to_delete); 
                
            
                foreach ($files_to_delete as $file) {
                    $file_path = 'uploads/news/' . $param2 . '/' . $file;
                    if (file_exists($file_path) && is_file($file_path)) {
                        unlink($file_path); 
                    }
                }
            
                $final_files = $files_to_keep;
            
                $news_directory = 'uploads/news/' . $param2 . '/';
                if (!is_dir($news_directory)) {
                    mkdir($news_directory, 0777, true); 
                }
            
                if (!empty($_FILES['images']['name'][0])) {
                    $exam_directory = 'uploads/news/' . $param2 . '/';
                    if (!is_dir($exam_directory)) {
                        mkdir($exam_directory, 0777, true); 
                    }
            
                    $this->load->library('upload'); 
                    
                    $files = $_FILES;
                    $number_of_files = count($_FILES['images']['name']);
                    
                    $uploaded_files = []; 
            
                    for ($i = 0; $i < $number_of_files; $i++) {
                        $_FILES['attachment']['name'] = $files['images']['name'][$i];
                        $_FILES['attachment']['type'] = $files['images']['type'][$i];
                        $_FILES['attachment']['tmp_name'] = $files['images']['tmp_name'][$i];
                        $_FILES['attachment']['error'] = $files['images']['error'][$i];
                        $_FILES['attachment']['size'] = $files['images']['size'][$i];
            
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
            
                            redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
                        }
                    }
            
                    $final_files = array_merge($final_files, $uploaded_files);
                }
            
                $final_files = array_unique($final_files);
            
                $files_json = json_encode($final_files);

                $this->db->where('news_id', $param2);
                $this->db->update('news', $data);
            
                $this->db->where('news_id', $param2);
                $this->db->update('news', ['images' => $files_json]);
            
                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('news_updated_successfully')) . '!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            
                redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
            }
            

        if ($param1 == 'disable_news') {
            
            $this->db->where('news_id', $param2);
            $this->db->update('news', array(
                'status_id' => 0
            ));
            $this->session->set_flashdata('flash_message', array(
                'title' => '¡' . ucfirst(get_phrase('news_disabled_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
        }
   
        if ($param1 == 'enable_news') {
            
            $this->db->where('news_id', $param2);
            $this->db->update('news', array(
                'status_id' => 1
            ));
            $this->session->set_flashdata('flash_message', array(
                'title' => '¡' . ucfirst(get_phrase('news_enabled_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
        }
            
    }
   

    function admin_information()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_admins')),
                'url' => base_url('index.php?admin/admin_information/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  	= 'admin_information';
        $page_data['page_icon']  	= 'entypo-graduation-cap';
		$page_data['page_title'] 	= ucfirst(get_phrase('manage_admins'));
		$this->load->view('backend/index', $page_data);
	}

    function notes($param1 = '', $param2 = '', $param3 = '', $param4 = '', $param5 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['note_title'] = urldecode($param3);
            $data['note_body'] = urldecode($param4);
            $data['user_type'] = 'admin';
            $data['user_id'] = $param5;
             
            $this->db->insert('notes', $data);
           $this->session->set_flashdata('flash_message' , get_phrase('nota agregada exitosamente'));
            // redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        }
        if ($param1 == 'update') {
            $data['note_title'] = urldecode($param3);
            $data['note_body'] = urldecode($param4);
        
            $this->db->where('note_id', $param2);
            $this->db->update('notes', $data);
           
            $this->crud_model->clear_cache();
            $this->session->set_flashdata('flash_message' , get_phrase('nota actualizada exitosamente'));
            // redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        } 
		
        if ($param1 == 'delete') {
            $this->db->where('note_id', $param2);
            $this->db->delete('notes');
            $this->session->set_flashdata('flash_message' , get_phrase('datos eliminados exitosamente'));
            // redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        }
    }

    
   
   




   


 




    


    


   
   

    

   



   


    

   


   

    

     


    




 



  


   

   

    

    
  
   


    

    
   



    

   



    

    // function get_teachers() {
    //     $teachers = $this->crud_model->get_tearchers();
        
    //     foreach ($teachers as $row) {
    //         $teacher_details = $this->crudTeacher->get_teachers_info($row['teacher_id']);
            
    //         if (!empty($teacher_details)) {
    //             $firstname = isset($teacher_details['firstname']) ? $teacher_details['firstname'] : '';
    //             $lastname = isset($teacher_details['lastname']) ? $teacher_details['lastname'] : '';
        
    //             echo '<option value="' . $row['teacher_id'] . '" data-firstname="' . $firstname . '" data-lastname="' . $lastname . '">' . $firstname . ' ' . $lastname . '</option>';
    //         }
    //     }
    // }

      function get_teachers() {
        $teachers = $this->crud_model->get_tearchers();
        
        foreach ($teachers as $row) {
            $teacher_details = $this->crudTeacher->get_teachers_info($row['teacher_id']);
            
            if (!empty($teacher_details)) {
                $firstname = isset($teacher_details['firstname']) ? $teacher_details['firstname'] : '';
                $lastname = isset($teacher_details['lastname']) ? $teacher_details['lastname'] : '';
        
                echo '<option value="' . $row['teacher_id'] . '" data-firstname="' . $firstname . '" data-lastname="' . $lastname . '">' . $lastname . ', ' . $firstname . '.' . '</option>';
            }
        }
    }
    

    function add_subject()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_subject')),
                'url' => base_url('index.php?admin/subject_add')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'add_subject';
		$page_data['page_title'] = ucfirst(get_phrase('add_subject'));
		$this->load->view('backend/index', $page_data);
	}


    function edit_subject($subject_id)
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

            $page_complete_name = 'edit_subject'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $subject_id; // ID del elemento específico (ej. curso o sección)

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
                'text' => ucfirst(get_phrase('edit_subject')),
                'url' => base_url('index.php?admin/edit_subject')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['subject_id']  = $subject_id;
		$page_data['page_name']  = 'edit_subject';
		$page_data['page_title'] = ucfirst(get_phrase('edit_subject'));
		$this->load->view('backend/index', $page_data);
	}



    function student_edit($param2 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

            $page_complete_name = 'student_edit'; // Nombre de la página
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
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('student_edit')),
                'url' => base_url('')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'student_edit';
		$page_data['page_title'] 	= ucfirst(get_phrase('student_edit'));
		$page_data['param2'] 	= $param2;
		$this->load->view('backend/index', $page_data);
	}



    function guardian_edit($param2 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('guardian_edit')),
                'url' => base_url('')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'guardian_edit';
		$page_data['page_title'] 	= ucfirst(get_phrase('guardian_edit'));
		$page_data['param2'] 	= $param2;
		$this->load->view('backend/index', $page_data);
	}



    function edit_teacher($teacher_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

            $page_complete_name = 'edit_teacher'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $teacher_id; // ID del elemento específico (ej. curso o sección)

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
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('edit_teacher')),
                'url' => base_url('')
            )
        );

        $selected_section_ids = array_map('intval', array_column(
            $this->db->select('section_id')
                     ->where('teacher_id', $teacher_id)
                     ->get('section_teacher')
                     ->result_array(),
            'section_id'
        ));
  

        $page_data['selected_section_ids'] = $selected_section_ids;

        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'edit_teacher';
		$page_data['page_title'] 	= 'edit_teacher';
		$page_data['teacher_id'] 	= $teacher_id;
		$this->load->view('backend/index', $page_data);
	}


    function edit_secretary($secretary_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

            $page_complete_name = 'edit_secretary'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $secretary_id; // ID del elemento específico (ej. curso o sección)

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
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('edit_secretary')),
                'url' => base_url('')
            )
        );


        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'edit_secretary';
		$page_data['page_title'] 	= 'edit_secretary';
		$page_data['secretary_id'] 	= $secretary_id;
		$this->load->view('backend/index', $page_data);
	}


    function edit_principal($principal_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

            $page_complete_name = 'edit_principal'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $principal_id; // ID del elemento específico (ej. curso o sección)

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
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('edit_principal')),
                'url' => base_url('')
            )
        );


        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'edit_principal';
		$page_data['page_title'] 	= 'edit_principal';
		$page_data['principal_id'] 	= $principal_id;
		$this->load->view('backend/index', $page_data);
	}



    
    function edit_teacher_aide($teacher_aide_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

            
            $page_complete_name = 'edit_teacher_aide'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $teacher_aide_id; // ID del elemento específico (ej. curso o sección)

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
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('edit_teacher_aide')),
                'url' => base_url('')
            )
        );

        $selected_section_ids = array_map('intval', array_column(
            $this->db->select('section_id')
                     ->where('teacher_aide_id', $teacher_aide_id)
                     ->get('section')
                     ->result_array(),
            'section_id'
        ));
  
        $page_data['selected_section_ids'] = $selected_section_ids;

        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'edit_teacher_aide';
		$page_data['page_title'] 	= 'edit_teacher_aide';
		$page_data['teacher_aide_id'] 	= $teacher_aide_id;
		$this->load->view('backend/index', $page_data);
	}



    function teacherAide_edit($param2 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('edit_teacherAide')),
                'url' => base_url('')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'teacherAide_edit';
		$page_data['page_title'] 	= 'teacherAide_edit';
		$page_data['param2'] 	= $param2;
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

 

    function get_class_content2()
    {
        $this->db->select('class.class_id, class.name');
        $this->db->from('class');
        $classes = $this->db->get()->result_array();
        echo '<option value="">' . 'seleccionar' . '</option>';
        foreach ($classes as $row) {
            echo '<option value="' . $row['class_id'] . '">' . $row['name'] . '°' . '</option>';
        }
    }

    function get_all_users()
{
    $user_id = $this->session->userdata('login_user_id'); 
    $user_group = $this->session->userdata('login_type');

    $user_types = ['admin', 'student', 'guardian', 'teacher', 'teacher_aide'];

    $output = '<option value="">' . 'Seleccionar un destinatario' . '</option>';

    foreach ($user_types as $type) {
        $this->db->select("$type.{$type}_id as user_id, $type.email, {$type}_details.firstname, {$type}_details.lastname");
        $this->db->from($type);
        $this->db->join("{$type}_details", "{$type}.{$type}_id = {$type}_details.{$type}_id", 'left');
        $users = $this->db->get()->result_array();

        $output .= '<optgroup label="' . ucfirst($type) . '">';

        foreach ($users as $user) {
            if ($user['user_id'] == $user_id && $type == $user_group) {
                continue; 
            }

            $fullname = $user['lastname'] . ', ' . $user['firstname'] . '.';
            $output .= '<option value="' . $type . '-' . $user['user_id'] . '" title="' . $fullname . '" data-cc-group="' . $type . '" data-firstname="' . $user['firstname'] . '" data-lastname="' . $user['lastname'] . '">'
            . $user['email'] . ' (<span>' . $fullname . '</span>)</option>';
        }

        $output .= '</optgroup>';
    }

    echo $output;
}

    function get_all_students()
    {
        // Filtramos solo estudiantes
        $user_types = ['student'];

        // Opción inicial
        // $output = '<option value="">' . 'Seleccionar' . '</option>';

        foreach ($user_types as $type) {
            // Obtenemos los detalles de los estudiantes, incluyendo la sección y validando que el período académico esté activo
            $this->db->select("$type.{$type}_id as user_id, $type.email, {$type}_details.section_id, {$type}_details.firstname, {$type}_details.lastname, section.name as section_name, section.letter_name");
            $this->db->from($type);
            $this->db->join("{$type}_details", "{$type}.{$type}_id = {$type}_details.{$type}_id", 'left');
            $this->db->join('section', 'section.section_id = '.$type.'_details.section_id', 'left'); // Relacionar con la tabla de secciones
            $this->db->join('academic_period', 'academic_period.id = section.academic_period_id', 'left'); // Relacionar con la tabla de periodos académicos
            $this->db->where('academic_period.status_id', 1); // Filtrar por períodos académicos activos
            $users = $this->db->get()->result_array();

            // Agrupar estudiantes por sección
            $sections = [];
            foreach ($users as $user) {
                // Concatenar el nombre de la sección con la letra
                $section_name = $user['section_name'];
                $sections[$section_name][] = $user;
            }

            // Generar las opciones agrupadas por sección
            foreach ($sections as $section => $students) {
                $output .= '<optgroup label="' . $section . '">'; // Agrupar por sección

                foreach ($students as $student) {
                    $fullname = $student['lastname'] . ', ' . $student['firstname'] . '.';
                    $output .= '<option value="' . $type . '-' . $student['user_id'] . '" title="' . $fullname . '" data-cc-group="' . $type . '" data-firstname="' . $student['firstname'] . '" data-lastname="' . $student['lastname'] . '">'
                        . '<span>' . $fullname . '</span></option>';
                }

                $output .= '</optgroup>';
            }
        }

        echo $output;
    }





    function get_all_users2()
    {
        $user_id = $this->session->userdata('login_user_id'); 
    $user_group = $this->session->userdata('login_type');

    $user_types = ['admin', 'student', 'guardian', 'teacher', 'teacher_aide'];

    $output = '<option value="">' . 'Seleccionar un destinatario' . '</option>';

    foreach ($user_types as $type) {
        $this->db->select("$type.{$type}_id as user_id, $type.email, {$type}_details.firstname, {$type}_details.lastname");
        $this->db->from($type);
        $this->db->join("{$type}_details", "{$type}.{$type}_id = {$type}_details.{$type}_id", 'left');
        $users = $this->db->get()->result_array();

        $output .= '<optgroup label="' . ucfirst($type) . '">';

        foreach ($users as $user) {
            if ($user['user_id'] == $user_id && $type == $user_group) {
                continue; 
            }

            $fullname = $user['lastname'] . ', ' . $user['firstname'] . '.';
            $output .= '<option value="' . $type . '-' . $user['user_id'] . '" title="' . $fullname . '" data-cc-group="' . $type . '" data-firstname="' . $user['firstname'] . '" data-lastname="' . $user['lastname'] . '">'
            . $user['email'] . ' (<span>' . $fullname . '</span>)</option>';
        }

        $output .= '</optgroup>';
    }

    echo $output;
    }

    function get_all_users3()
    {
        $user_id = $this->session->userdata('login_user_id'); 
        $user_group = $this->session->userdata('login_type');
    
        $user_types = ['admin', 'student', 'guardian', 'teacher', 'teacher_aide'];
    
        $output = '<option value="">' . 'Seleccionar un destinatario' . '</option>';
    
        foreach ($user_types as $type) {
            $this->db->select("$type.{$type}_id as user_id, $type.email, {$type}_details.firstname, {$type}_details.lastname");
            $this->db->from($type);
            $this->db->join("{$type}_details", "{$type}.{$type}_id = {$type}_details.{$type}_id", 'left');
            $users = $this->db->get()->result_array();
    
            $output .= '<optgroup label="' . ucfirst($type) . '">';
    
            foreach ($users as $user) {
                if ($user['user_id'] == $user_id && $type == $user_group) {
                    continue; 
                }
    
                $fullname = $user['lastname'] . ', ' . $user['firstname'] . '.';
                $output .= '<option value="' . $type . '-' . $user['user_id'] . '" title="' . $fullname . '" data-cc-group="' . $type . '" data-firstname="' . $user['firstname'] . '" data-lastname="' . $user['lastname'] . '">'
                . $user['email'] . ' (<span>' . $fullname . '</span>)</option>';
            }
    
            $output .= '</optgroup>';
        }
    
        echo $output;
    }


    function get_users($param1 = '', $param2 = '')
    {
        // Crear un array para almacenar los tipos de usuarios y sus tablas
        $user_types = ['admin', 'student', 'guardian', 'teacher', 'teacher_aide'];
    
        // Inicializar la salida HTML
        $output = '<option value="">' . 'Seleccionar un destinatario' . '</option>';
    
        // Iterar sobre cada tipo de usuario
        foreach ($user_types as $type) {
            // Seleccionar el id, email, firstname y lastname para cada tipo de usuario
            $this->db->select("$type.{$type}_id as user_id, $type.email, {$type}_details.firstname, {$type}_details.lastname");
            $this->db->from($type);
            $this->db->join("{$type}_details", "{$type}.{$type}_id = {$type}_details.{$type}_id", 'left');
            $users = $this->db->get()->result_array();
    
            // Comenzar el optgroup para el tipo de usuario
            $output .= '<optgroup label="' . ucfirst($type) . '">';
    
            // Agregar cada usuario dentro del optgroup
            foreach ($users as $user) {
                // Formatear apellido, nombre
                $fullname = $user['lastname'] . ', ' . $user['firstname'] . '.';
                // Generar la opción con el formato deseado
                $output .= '<option value="' . $type. '-' . $user['user_id'] . '" title="' . $fullname . '" data-cc-group="' . $type . '" data-firstname="' . $user['firstname'] . '" data-lastname="' . $user['lastname'] . '">'
                . $user['email'] . ' (<span>' . $fullname . '</span>)</option>';
            }
    
            // Cerrar el optgroup
            $output .= '</optgroup>';
        }
    
        // Imprimir el HTML generado
        echo $output;
    }

    

    function get_admin_users_content()
    {
        $this->db->select('admin_id, firstname, lastname');
        $this->db->from('admin_details');
        $admins = $this->db->get()->result_array();
        
        echo '<option value="">' . 'Seleccionar' . '</option>';
        
        foreach ($admins as $row) {
            echo '<option value="' . $row['admin_id'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
        }
    }

    function get_guardians_content()
    {
        $sections = $this->db->get_where('guardian_details')->result_array();
        foreach ($sections as $row) {
            echo '<option value="' . $row['guardian_id'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
        }
    }

    function get_students_content()
    {
        $students = $this->db->get_where('student_details')->result_array();
        foreach ($students as $row) {
            echo '<option value="' . $row['student_id'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
        }
    }

    function get_students_content_by_section($section_id)
    {
        $students = $this->db->get_where('student_details', array('section_id' => $section_id))->result_array();
        foreach ($students as $row) {
            echo '<option value="' . $row['student_id'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
        }
    }

    function preenroll_student($param1 = '', $param2 = '', $param3 = '')
    { 
        if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
        if ($param1 == 'create') {
             // Obtener los datos enviados por POST
            $student_id = $param2;
            $section_id = $param3;

            // Obtener el class_id a partir del section_id
            $this->db->select('class_id, academic_period_id');
            $this->db->from('section');
            $this->db->where('section_id', $section_id);
            $section = $this->db->get()->row();

            if ($section) {
                $class_id = $section->class_id;
                $academic_period_id = $section->academic_period_id;

                // Actualizar la información del estudiante
                $this->db->where('student_id', $student_id);
                $this->db->update('student_details', array(
                    'class_id' => $class_id,
                    'section_id' => $section_id,
                    'user_status_id' => 1
                ));

                $this->db->where('student_id', $student_id);
                $this->db->where('new_class_id', $class_id);
                $this->db->where('new_section_id', $section_id);
                $existing_record = $this->db->get('academic_history')->row();

                if ($existing_record) {
                    $this->db->where('student_id', $student_id);
                    $this->db->update('academic_history', array(
                        'new_class_id' => $class_id,
                        'new_section_id' => $section_id,
                        'new_academic_period_id' => $academic_period_id
                    ));
                } else {
                    $this->db->insert('academic_history', array(
                        'student_id' => $student_id,
                        'old_class_id' => null,
                        'old_section_id' => null,
                        'new_class_id' => $class_id,
                        'new_section_id' => $section_id,
                        'old_academic_period_id' => null,
                        'new_academic_period_id' => $academic_period_id,
                        'date_change' => date('Y-m-d')
                    ));
                }

                $dataAcademic['student_id'] = $student_id;
                $dataAcademic['old_class_id'] = null;
                $dataAcademic['new_class_id'] = $class_id;
                $dataAcademic['old_section_id'] = null;
                $dataAcademic['new_section_id'] = $section_id; 
                $dataAcademic['old_academic_period_id'] = null;
                $dataAcademic['new_academic_period_id'] = $academic_period_id;
                $dataAcademic['date_change'] = date('Y-m-d');

                $this->db->insert('academic_history', $dataAcademic);

                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('students_pre_enrolled_successfully')) . '!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/pre_enrollments/', 'refresh');
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Estudiante no inscripto!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/pre_enrollments/', 'refresh');
            }
        }

        if ($param1 == 'pre_enrollment_bulk') {
            $segments = $this->uri->segment_array();
            $index = array_search('pre_enrollment_bulk', $segments);
        
            // Obtiene el class_id desde el primer parámetro después de 'pre_enrollment_bulk'
            $class_id = isset($segments[$index + 1]) ? $segments[$index + 1] : null;
        
            // Obtiene el section_id desde el segundo parámetro después de 'class_id'
            $section_id = isset($segments[$index + 2]) ? $segments[$index + 2] : null;
        
            // Obtiene los student_id a partir del tercer parámetro en adelante
            $students = array_slice($segments, $index + 2);
            $students = array_filter($students, 'is_numeric');
            
            if (!empty($students)) {
                // Actualiza la clase y la sección de cada estudiante en la base de datos
                $this->db->where_in('student_id', $students);
                $this->db->update('student_details', ['class_id' => $class_id, 'section_id' => $section_id, 'user_status_id' => 1]);
        
                // Mensaje de éxito
                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('students_pre_enrolled_successfully')) . '!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => true,
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => 10000,
                    'timerProgressBar' => true,
                ));
        
                // Redirige a la lista de preinscripciones
                redirect(base_url() . 'index.php?admin/pre_enrollments/', 'refresh');
            } 
        }
        


    }



    function admissions_student($param1 = '', $param2 = '', $param3 = '')
    { 
        if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
        if ($param1 == 'create') {
             // Obtener los datos enviados por POST
            $student_id = $param2;
            $section_id = $param3;

            // Obtener el class_id a partir del section_id
            $this->db->select('class_id');
            $this->db->from('section');
            $this->db->where('section_id', $section_id);
            $section = $this->db->get()->row();

            if ($section) {
                $class_id = $section->class_id;
                $academic_period_id = $section->academic_period_id;

                // Actualizar la información del estudiante
                $this->db->where('student_id', $student_id);
                $this->db->update('student_details', array(
                    'class_id' => $class_id,
                    'section_id' => $section_id,
                    'user_status_id' => 1, 
                    'status_reason' => ''
                ));

                $this->db->insert('academic_history', array(
                    'student_id' => $student_id,
                    'old_class_id' => null,
                    'old_section_id' => null,
                    'new_class_id' => $class_id,
                    'new_section_id' => $section_id,
                    'old_academic_period_id' => null,
                    'new_academic_period_id' => $academic_period_id,
                    'date_change' => date('Y-m-d')
                ));

                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Estudiante inscripto correctamente!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/admissions/', 'refresh');
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Estudiante no inscripto!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/admissions/', 'refresh');
            }
        }

        if ($param1 == 're_enrollment_bulk') {
            $segments = $this->uri->segment_array();
            $index = array_search('re_enrollment_bulk', $segments);
        
            // Obtiene el class_id desde el primer parámetro después de 'pre_enrollment_bulk'
            $class_id = isset($segments[$index + 1]) ? $segments[$index + 1] : null;
        
            // Obtiene el section_id desde el segundo parámetro después de 'class_id'
            $section_id = isset($segments[$index + 2]) ? $segments[$index + 2] : null;
        
            // Obtiene los student_id a partir del tercer parámetro en adelante
            $students = array_slice($segments, $index + 2);
            $students = array_filter($students, 'is_numeric');

             $this->db->select('academic_period_id');
             $this->db->from('section');
             $this->db->where('section_id', $section_id);
             $section_data = $this->db->get()->row();
 
                 $academic_period_id = $section_data->academic_period_id;
            
            if (!empty($students)) {
                // Actualiza la clase y la sección de cada estudiante en la base de datos
                $this->db->where_in('student_id', $students);
                $this->db->update('student_details', [
                    'class_id' => $class_id, 
                    'section_id' => $section_id, 
                    'user_status_id' => 1, 
                    'status_reason' => ''
                ]);

                $this->db->insert('academic_history', array(
                    'student_id' => $students,
                    'old_class_id' => null,
                    'old_section_id' => null,
                    'new_class_id' => $class_id,
                    'new_section_id' => $section_id,
                    'old_academic_period_id' => null,
                    'new_academic_period_id' => $academic_period_id,
                    'date_change' => date('Y-m-d')
                ));
        
                // Mensaje de éxito
                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('students_re_enrolled_successfully')) . '!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => true,
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => 10000,
                    'timerProgressBar' => true,
                ));
        
                // Redirige a la lista de preinscripciones
                redirect(base_url() . 'index.php?admin/student_information/' . $section_id, 'refresh');
            } 
        }

    }


    function re_enrollments_student($param1 = '', $param2 = '', $param3 = '', $param4 = '')
    { 
        if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
        if ($param1 == 'create') {
             // Obtener los datos enviados por POST
            $student_id = $param2;
            $section_id = $param3;

            // Obtener el class_id a partir del section_id
            $this->db->select('class_id, academic_period_id');
            $this->db->from('section');
            $this->db->where('section_id', $section_id);
            $section = $this->db->get()->row();

            if ($section) {
                $class_id = $section->class_id;
                $academic_period_id = $section->academic_period_id;

                // Actualizar la información del estudiante
                $this->db->where('student_id', $student_id);
                $this->db->update('student_details', array(
                    'class_id' => $class_id,
                    'section_id' => $section_id
                ));

                $this->db->where('student_id', $student_id);
                $this->db->order_by('date_change', 'DESC'); 
                $this->db->limit(1);
                $recent_record = $this->db->get('academic_history')->row();
                
                if ($recent_record) {
                    // Actualizar solo el registro más reciente
                    $this->db->where('academic_history_id', $recent_record->academic_history_id); 
                    $this->db->where('student_id', $student_id);
                    $this->db->where('date_change', $recent_record->date_change);
                    $this->db->update('academic_history', array(
                        'new_class_id' => $class_id,
                        'new_section_id' => $section_id,
                        'date_change' => date('Y-m-d'), 
                    ));
                } 

                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Estudiante rematriculado correctamente!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                // redirect(base_url() . 'index.php?admin/re_enrollments/' + $param4, 'refresh');
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Estudiante no rematriculado!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                // redirect(base_url() . 'index.php?admin/re_enrollments/' + $param4, 'refresh');
            }
        }

       if ($param1 == 're_enrollment_bulk') {
            $segments = $this->uri->segment_array();
            $index = array_search('re_enrollment_bulk', $segments);
        
            // Obtiene el class_id desde el primer parámetro después de 'pre_enrollment_bulk'
            $class_id = isset($segments[$index + 1]) ? $segments[$index + 1] : null;
        
            // Obtiene el section_id desde el segundo parámetro después de 'class_id'
            $section_id = isset($segments[$index + 2]) ? $segments[$index + 2] : null;
        
            // Obtiene los student_id a partir del tercer parámetro en adelante
            $students = array_slice($segments, $index + 2);
            $students = array_filter($students, 'is_numeric');
            
            if (!empty($students)) {
                // Actualiza la clase y la sección de cada estudiante en la base de datos
                $this->db->where_in('student_id', $students);
                $this->db->update('student_details', ['class_id' => $class_id, 'section_id' => $section_id, 'user_status_id' => 1]);

                $this->db->where_in('student_id', $students);
                $this->db->update('academic_history', ['new_class_id' => $class_id, 'new_section_id' => $section_id]);
        
                // Mensaje de éxito
                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('students_re_enrolled_successfully')) . '!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => true,
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => 10000,
                    'timerProgressBar' => true,
                ));
        
                // Redirige a la lista de preinscripciones
                redirect(base_url() . 'index.php?admin/student_information/' . $section_id, 'refresh');
            } 
        }
    }

    function message($param1 = 'message_default', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
    
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
        $this->db->select('COUNT(*) as unread_count');
        $this->db->from('message_thread');

        // Realizar un INNER JOIN con user_message_thread_status usando el message_thread_code
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
        $this->db->join('user_message_status', 'message_thread.message_thread_code = user_message_status.message_thread_code', 'inner');

        // Agrupar las condiciones relacionadas con el usuario
        $this->db->group_start();
        $this->db->where('message_thread.sender_id', $user_id);
        $this->db->where('message_thread.sender_group', $user_group);
        $this->db->or_where('message_thread.receiver_id', $user_id);
        $this->db->where('message_thread.receiver_group', $user_group);

        // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
        $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');

        // Comprobar si el usuario está en bcc
        $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');

        $this->db->group_end();

        // Agregar condiciones para el estado del mensaje
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);

        // Asegúrate de que los mensajes no estén en la papelera o como borradores
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('user_message_thread_status.is_draft', 0);

        $this->db->where('user_message_status.user_id', $user_id);
        $this->db->where('user_message_status.user_group', $user_group);
        // Filtrar por mensajes no leídos
        $this->db->where('user_message_status.new_messages_count >', 0);

        // Ejecutar la consulta
        $unread_count_query = $this->db->get();
        $unread_count_result = $unread_count_query->row_array();
        $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;
 
         $this->db->select('COUNT(*) as received_count');
         $this->db->from('message_thread');
         
         // Realizar un INNER JOIN con user_message_thread_status
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->where('message_thread.is_trash', 0);

         $this->db->group_start();
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->or_where('message_thread.receiver_id', $user_id);
         $this->db->where('message_thread.receiver_group', $user_group);
         
         // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
         $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
         
         // Comprobar si el usuario está en bcc
         $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
         $this->db->group_end();
         
         // Agregar condiciones para is_trash e is_draft
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
         
         $received_count_query = $this->db->get();
         $received_count_result = $received_count_query->row_array();
         $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;

         $this->db->select('COUNT(*) as sent_count');
         $this->db->from('message_thread');
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);

         $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $sent_count_query = $this->db->get();
         $sent_count_result = $sent_count_query->row_array();
         $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
 
           // Contar los borradores (draft_count)
         $this->db->select('COUNT(*) as draft_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_draft', 1);
 
         // Ejecutar la consulta
         $draft_count_query = $this->db->get();
         $draft_count_result = $draft_count_query->row_array();
         $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;
 
         $this->db->select('COUNT(*) as trash_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_trash', 1);
 
         // Ejecutar la consulta
         $trash_count_query = $this->db->get();
         $trash_count_result = $trash_count_query->row_array();
         $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;
 
 
         $this->db->select('COUNT(*) as favorite_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_favorite', 1);
 
         // Ejecutar la consulta
         $favorite_count_query = $this->db->get();
         $favorite_count_result = $favorite_count_query->row_array();
         $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;

         $message_counts = [
            'urgent' => 0,
            'homework' => 0,
            'announcement' => 0,
            'meeting' => 0,
            'event' => 0,
            'reminder' => 0,
            'grade_report' => 0,
            'exam' => 0,
            'behavior' => 0,
            'important' => 0
        ];

        $active_tag_count = 0;
        
        // Obtener los tags directamente de la base de datos
        $this->db->select('tags');
        $this->db->from('message_thread mt');
        
        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Ejecutar la consulta para obtener las etiquetas
        $tags_query = $this->db->get();
        $tags_results = $tags_query->result_array();
        
        foreach ($tags_results as $tags_row) {
            $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
            if (!empty($current_tags)) {
                // Contar los tags específicos
                foreach ($current_tags as $tag) {
                    if (isset($message_counts[$tag])) {
                        $message_counts[$tag]++; // Incrementar el contador de ese tag
                    }
                    // Verificar si el tag coincide con param1
                    if ($tag === $param1) {
                        $active_tag_count++; // Incrementar el contador de tags activos
                    }
                }
            }
        }


        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

        // Obtener los mensajes y los hilos de mensajes
        $this->db->select('m.message_id, m.message_thread_code, m.message, m.sender_id, m.sender_group, 
         m.has_text, has_image, has_video, has_audio, has_document, mt.message_thread_id, mt.message_thread_code, mt.receiver_id, mt.receiver_group, mt.last_message_timestamp, 
        mt.last_sender_id,  mt.last_sender_group, umts.is_favorite, umts.is_draft, umts.is_trash, mt.subject');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');

        // Unión adicional con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status umts', 'mt.message_thread_code = umts.message_thread_code');

        // Iniciar agrupamiento de condiciones
        $this->db->group_start();

        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();

        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();

        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.cc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.bcc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Cerrar agrupamiento de condiciones
        $this->db->group_end();

        // Agregar las condiciones comunes (fuera de los OR)
        // Ahora is_draft, is_trash y is_favorite están en la tabla user_message_thread_status (umts)
        $this->db->where('umts.user_id', $user_id);
        $this->db->where('umts.user_group', $user_group);
        $this->db->where('umts.is_draft', 0);
        $this->db->where('umts.is_trash', 0);

        // Ejecutar la consulta
        $query = $this->db->get();
        $messages = $query->result_array();
    
        $grouped_messages = [];
    
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];
    
            // Consulta para obtener los detalles del sender
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['sender_group'] . '_details');
            $this->db->where($message['sender_group'] . '_id', $message['sender_id']);
            $sender_details_query = $this->db->get();
            $sender_details = $sender_details_query->row_array();
    
            // Último remitente
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['last_sender_group'] . '_details');
            $this->db->where($message['last_sender_group'] . '_id', $message['last_sender_id']);
            $last_sender_details_query = $this->db->get();
            $last_sender_details = $last_sender_details_query->row_array();
    
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];

            $message['last_sender_firstname'] = $last_sender_details['firstname'];
            $message['last_sender_lastname'] = $last_sender_details['lastname'];
            $message['last_sender_photo'] = $last_sender_details['photo'];

            // Inicializar si no existe
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = $message;
                $grouped_messages[$thread_code]['tags'] = [];
                $grouped_messages[$thread_code]['has_text'] = !empty($message['has_text']);
                $grouped_messages[$thread_code]['has_image'] = !empty($message['has_image']);
                $grouped_messages[$thread_code]['has_video'] = !empty($message['has_video']);
                $grouped_messages[$thread_code]['has_audio'] = !empty($message['has_audio']);
                $grouped_messages[$thread_code]['has_document'] = !empty($message['has_document']);
                $grouped_messages[$thread_code]['new_message_count'] = 0;
            }
            
            // Verificar si hay attachments
            if (!empty($message['has_text'])) {
                $grouped_messages[$thread_code]['has_text'] = true;
            }
            if (!empty($message['has_image'])) {
                $grouped_messages[$thread_code]['has_image'] = true;
            }
            if (!empty($message['has_video'])) {
                $grouped_messages[$thread_code]['has_video'] = true;
            }
            if (!empty($message['has_audio'])) {
                $grouped_messages[$thread_code]['has_audio'] = true;
            }
            if (!empty($message['has_document'])) {
                $grouped_messages[$thread_code]['has_document'] = true;
            }
    
            // Verificar la cantidad de mensajes no leídos en este thread
            $this->db->select('new_messages_count');
            $this->db->from('user_message_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('message_thread_code', $thread_code);
        
            $new_messages_query = $this->db->get();
            $new_messages_result = $new_messages_query->row_array();
            $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;
    
            // Actualizar si el timestamp es más reciente
            if (strtotime($message['last_message_timestamp']) > strtotime($grouped_messages[$thread_code]['last_message_timestamp'])) {
                $grouped_messages[$thread_code] = $message;
            }
        }

       
    
        // Obtener los tags y subjects
        foreach ($grouped_messages as $thread_code => &$grouped_message) {
            $this->db->select('tags');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $tags_query = $this->db->get();
            $tags_results = $tags_query->result_array();

            foreach ($tags_results as $tags_row) {
                $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
                if (!empty($current_tags)) {
                    // Mezclar los tags actuales con los ya agrupados
                    $grouped_message['tags'] = array_unique(array_merge($grouped_message['tags'], $current_tags));
                    
                  
                }
            }
    
            $this->db->select('subject');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->limit(1);
            $subject_query = $this->db->get();
            if ($subject_row = $subject_query->row_array()) {
                $grouped_message['subject'] = $subject_row['subject'];
            }
        }

    
        usort($grouped_messages, function ($a, $b) {
            if ($a['is_favorite'] == $b['is_favorite']) {
                return strtotime($b['last_message_timestamp']) - strtotime($a['last_message_timestamp']);
            }
            return $b['is_favorite'] - $a['is_favorite'];
        });
    
        $page_data['messages'] = array_values($grouped_messages);
        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 
        $page_data['active_tag'] = $param1; 
        $page_data['active_tag_count'] = $active_tag_count;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('inbox')),
                'url' => base_url('index.php?admin/message')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message';
        $page_data['page_title'] = 'message';
        
    
        $this->load->view('backend/index', $page_data);
    }


    function message_favorite($param1 = 'message_default', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
    
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
    
         // Contar los mensajes no leídos (new_messages_count > 0)
         $this->db->select('COUNT(*) as unread_count');
         $this->db->from('user_message_status');
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('new_messages_count >', 0);
         
         $unread_count_query = $this->db->get();
         $unread_count_result = $unread_count_query->row_array();
         $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;
 
         $this->db->select('COUNT(*) as received_count');
         $this->db->from('message_thread');
         
         // Realizar un INNER JOIN con user_message_thread_status
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->group_start();
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->or_where('message_thread.receiver_id', $user_id);
         $this->db->where('message_thread.receiver_group', $user_group);
         
         // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
         $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
         
         // Comprobar si el usuario está en bcc
         $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
         $this->db->group_end();
         
         // Agregar condiciones para is_trash e is_draft
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $received_count_query = $this->db->get();
         $received_count_result = $received_count_query->row_array();
         $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;

         $this->db->select('COUNT(*) as sent_count');
         $this->db->from('message_thread');
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $sent_count_query = $this->db->get();
         $sent_count_result = $sent_count_query->row_array();
         $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
 
           // Contar los borradores (draft_count)
         $this->db->select('COUNT(*) as draft_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_draft', 1);
 
         // Ejecutar la consulta
         $draft_count_query = $this->db->get();
         $draft_count_result = $draft_count_query->row_array();
         $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;
 
         $this->db->select('COUNT(*) as trash_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_trash', 1);
 
         // Ejecutar la consulta
         $trash_count_query = $this->db->get();
         $trash_count_result = $trash_count_query->row_array();
         $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;
 
 
        $this->db->select('COUNT(*) as favorite_count');
        $this->db->from('message_thread');

        // Unir con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');

        // Filtrar por usuario, grupo y hilos marcados como favoritos
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);
        $this->db->where('user_message_thread_status.is_favorite', 1);

        // Filtrar por mensajes que no están en la papelera o como borradores
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('user_message_thread_status.is_draft', 0);
        $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');

        // Ejecutar la consulta
        $favorite_count_query = $this->db->get();
        $favorite_count_result = $favorite_count_query->row_array();
        $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;


        $message_counts = [
            'urgent' => 0,
            'homework' => 0,
            'announcement' => 0,
            'meeting' => 0,
            'event' => 0,
            'reminder' => 0,
            'grade_report' => 0,
            'exam' => 0,
            'behavior' => 0,
            'important' => 0
        ];

        $active_tag_count = 0;
        
        // Obtener los tags directamente de la base de datos
        $this->db->select('tags');
        $this->db->from('message_thread mt');
        
        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Ejecutar la consulta para obtener las etiquetas
        $tags_query = $this->db->get();
        $tags_results = $tags_query->result_array();
        
        foreach ($tags_results as $tags_row) {
            $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
            if (!empty($current_tags)) {
                // Contar los tags específicos
                foreach ($current_tags as $tag) {
                    if (isset($message_counts[$tag])) {
                        $message_counts[$tag]++; // Incrementar el contador de ese tag
                    }
                    // Verificar si el tag coincide con param1
                    if ($tag === $param1) {
                        $active_tag_count++; // Incrementar el contador de tags activos
                    }
                }
            }
        }


        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

        // Obtener los mensajes y los hilos de mensajes
        $this->db->select('m.message_id, m.message_thread_code, m.message, m.sender_id, m.sender_group, 
         has_image, has_video, has_audio, has_document, has_text, mt.message_thread_id, mt.message_thread_code, mt.receiver_id, mt.receiver_group, mt.last_message_timestamp, 
        mt.last_sender_id, mt.last_sender_group, umts.is_favorite, umts.is_draft, umts.is_trash, mt.subject');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');

        // Unión adicional con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status umts', 'mt.message_thread_code = umts.message_thread_code');

        // Iniciar agrupamiento de condiciones
        $this->db->group_start();

        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();

        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();

        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.cc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.bcc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Cerrar agrupamiento de condiciones
        $this->db->group_end();

        // Agregar las condiciones comunes (fuera de los OR)
        // Ahora is_draft, is_trash y is_favorite están en la tabla user_message_thread_status (umts)
        $this->db->where('umts.user_id', $user_id);
        $this->db->where('umts.user_group', $user_group);
        $this->db->where('umts.is_trash', 0);
        $this->db->where('umts.is_favorite', 1);

        // Ejecutar la consulta
        $query = $this->db->get();
        $messages = $query->result_array();
    
        $grouped_messages = [];
    
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];
    
            // Consulta para obtener los detalles del sender
            $this->db->select('firstname, lastname');
            $this->db->from($message['sender_group'] . '_details');
            $this->db->where($message['sender_group'] . '_id', $message['sender_id']);
            $sender_details_query = $this->db->get();
            $sender_details = $sender_details_query->row_array();
    
            // Último remitente
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['last_sender_group'] . '_details');
            $this->db->where($message['last_sender_group'] . '_id', $message['last_sender_id']);
            $last_sender_details_query = $this->db->get();
            $last_sender_details = $last_sender_details_query->row_array();
    
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];
            $message['last_sender_firstname'] = $last_sender_details['firstname'];
            $message['last_sender_lastname'] = $last_sender_details['lastname'];
            $message['last_sender_photo'] = $last_sender_details['photo'];
    
            // Inicializar si no existe
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = $message;
                $grouped_messages[$thread_code]['tags'] = [];
                $grouped_messages[$thread_code]['has_text'] = !empty($message['has_text']);
                $grouped_messages[$thread_code]['has_image'] = !empty($message['has_image']);
                $grouped_messages[$thread_code]['has_video'] = !empty($message['has_video']);
                $grouped_messages[$thread_code]['has_audio'] = !empty($message['has_audio']);
                $grouped_messages[$thread_code]['has_document'] = !empty($message['has_document']);
                $grouped_messages[$thread_code]['new_message_count'] = 0;
            }

            // Verificar si hay attachments
            if ($message['has_text'] == 1) {
                $grouped_messages[$thread_code]['has_text'] = true;
            }
            if ($message['has_image'] == 1) {
                $grouped_messages[$thread_code]['has_image'] = true;
            }
            if ($message['has_video'] == 1) {
                $grouped_messages[$thread_code]['has_video'] = true;
            }
            if ($message['has_audio'] == 1) {
                $grouped_messages[$thread_code]['has_audio'] = true;
            }
            if ($message['has_document'] == 1) {
                $grouped_messages[$thread_code]['has_document'] = true;
            }
           
    
            // Verificar la cantidad de mensajes no leídos en este thread
            $this->db->select('new_messages_count');
            $this->db->from('user_message_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('message_thread_code', $thread_code);
        
            $new_messages_query = $this->db->get();
            $new_messages_result = $new_messages_query->row_array();
            $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;
    
            // Actualizar si el timestamp es más reciente
            if (strtotime($message['last_message_timestamp']) > strtotime($grouped_messages[$thread_code]['last_message_timestamp'])) {
                $grouped_messages[$thread_code] = $message;
            }
        }
    
        // Obtener los tags y subjects
        foreach ($grouped_messages as $thread_code => &$grouped_message) {
            $this->db->select('tags');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $tags_query = $this->db->get();
            $tags_results = $tags_query->result_array();
            foreach ($tags_results as $tags_row) {
                $current_tags = json_decode($tags_row['tags'], true);
                if (!empty($current_tags)) {
                    $grouped_message['tags'] = array_unique(array_merge($grouped_message['tags'], $current_tags));
                }
            }
    
            $this->db->select('subject');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->limit(1);
            $subject_query = $this->db->get();
            if ($subject_row = $subject_query->row_array()) {
                $grouped_message['subject'] = $subject_row['subject'];
            }
        }
    
        usort($grouped_messages, function ($a, $b) {
            if ($a['is_favorite'] == $b['is_favorite']) {
                return strtotime($b['last_message_timestamp']) - strtotime($a['last_message_timestamp']);
            }
            return $b['is_favorite'] - $a['is_favorite'];
        });
    
        $page_data['messages'] = array_values($grouped_messages);
        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 
        $page_data['active_tag'] = $param1; 
        $page_data['active_tag_count'] = $active_tag_count;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('favorite')),
                'url' => base_url('index.php?admin/message')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_favorite';
        $page_data['page_title'] = 'message_favorite';
        
    
        $this->load->view('backend/index', $page_data);
    }




    function message_tag($param1 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
    
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
        $this->db->select('COUNT(*) as unread_count');
        $this->db->from('message_thread');

        // Realizar un INNER JOIN con user_message_thread_status usando el message_thread_code
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
        $this->db->join('user_message_status', 'message_thread.message_thread_code = user_message_status.message_thread_code', 'inner');

        // Agrupar las condiciones relacionadas con el usuario
        $this->db->group_start();
        $this->db->where('message_thread.sender_id', $user_id);
        $this->db->where('message_thread.sender_group', $user_group);
        $this->db->or_where('message_thread.receiver_id', $user_id);
        $this->db->where('message_thread.receiver_group', $user_group);

        // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
        $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');

        // Comprobar si el usuario está en bcc
        $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');

        $this->db->group_end();

        // Agregar condiciones para el estado del mensaje
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);

        // Asegúrate de que los mensajes no estén en la papelera o como borradores
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('user_message_thread_status.is_draft', 0);

        $this->db->where('user_message_status.user_id', $user_id);
        $this->db->where('user_message_status.user_group', $user_group);
        // Filtrar por mensajes no leídos
        $this->db->where('user_message_status.new_messages_count >', 0);

        // Ejecutar la consulta
        $unread_count_query = $this->db->get();
        $unread_count_result = $unread_count_query->row_array();
        $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;
 
         $this->db->select('COUNT(*) as received_count');
         $this->db->from('message_thread');
         
         // Realizar un INNER JOIN con user_message_thread_status
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->where('message_thread.is_trash', 0);

         $this->db->group_start();
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->or_where('message_thread.receiver_id', $user_id);
         $this->db->where('message_thread.receiver_group', $user_group);
         
         // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
         $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
         
         // Comprobar si el usuario está en bcc
         $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
         $this->db->group_end();
         
         // Agregar condiciones para is_trash e is_draft
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
         
         $received_count_query = $this->db->get();
         $received_count_result = $received_count_query->row_array();
         $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;

         $this->db->select('COUNT(*) as sent_count');
         $this->db->from('message_thread');
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);

         $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $sent_count_query = $this->db->get();
         $sent_count_result = $sent_count_query->row_array();
         $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
 
           // Contar los borradores (draft_count)
         $this->db->select('COUNT(*) as draft_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_draft', 1);
 
         // Ejecutar la consulta
         $draft_count_query = $this->db->get();
         $draft_count_result = $draft_count_query->row_array();
         $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;
 
         $this->db->select('COUNT(*) as trash_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_trash', 1);
 
         // Ejecutar la consulta
         $trash_count_query = $this->db->get();
         $trash_count_result = $trash_count_query->row_array();
         $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;
 
 
         $this->db->select('COUNT(*) as favorite_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_favorite', 1);
 
         // Ejecutar la consulta
         $favorite_count_query = $this->db->get();
         $favorite_count_result = $favorite_count_query->row_array();
         $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;


         $message_counts = [
            'urgent' => 0,
            'homework' => 0,
            'announcement' => 0,
            'meeting' => 0,
            'event' => 0,
            'reminder' => 0,
            'grade_report' => 0,
            'exam' => 0,
            'behavior' => 0,
            'important' => 0
        ];

        $active_tag_count = 0;
        
        // Obtener los tags directamente de la base de datos
        $this->db->select('tags');
        $this->db->from('message_thread mt');
        
        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Ejecutar la consulta para obtener las etiquetas
        $tags_query = $this->db->get();
        $tags_results = $tags_query->result_array();
        
        foreach ($tags_results as $tags_row) {
            $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
            if (!empty($current_tags)) {
                // Contar los tags específicos
                foreach ($current_tags as $tag) {
                    if (isset($message_counts[$tag])) {
                        $message_counts[$tag]++; // Incrementar el contador de ese tag
                    }
                    // Verificar si el tag coincide con param1
                    if ($tag === $param1) {
                        $active_tag_count++; // Incrementar el contador de tags activos
                    }
                }
            }
        }





        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

       // Obtener los mensajes y los hilos de mensajes
        $this->db->select('m.message_id, m.message_thread_code, m.message, m.sender_id, m.sender_group, 
        m.has_text, has_image, has_video, has_audio, has_document, mt.message_thread_id, mt.message_thread_code, 
        mt.receiver_id, mt.receiver_group, mt.last_message_timestamp, mt.last_sender_id, mt.last_sender_group, 
        umts.is_favorite, umts.is_draft, umts.is_trash, mt.subject');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');

        // Unión adicional con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status umts', 'mt.message_thread_code = umts.message_thread_code');

        // Iniciar agrupamiento de condiciones
        $this->db->group_start();

        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();

        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();

        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.cc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.bcc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Cerrar agrupamiento de condiciones
        $this->db->group_end();

        // Filtrar mensajes que contengan el tag específico en JSON
        if ($param1 != '') {
        $this->db->where('mt.tags LIKE \'%"' . $param1 . '"%\'');
        }

       

        // Ejecutar la consulta
        $query = $this->db->get();
        $messages = $query->result_array();
    
        $grouped_messages = [];
    
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];
    
            // Consulta para obtener los detalles del sender
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['sender_group'] . '_details');
            $this->db->where($message['sender_group'] . '_id', $message['sender_id']);
            $sender_details_query = $this->db->get();
            $sender_details = $sender_details_query->row_array();
    
            // Último remitente
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['last_sender_group'] . '_details');
            $this->db->where($message['last_sender_group'] . '_id', $message['last_sender_id']);
            $last_sender_details_query = $this->db->get();
            $last_sender_details = $last_sender_details_query->row_array();
    
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];

            $message['last_sender_firstname'] = $last_sender_details['firstname'];
            $message['last_sender_lastname'] = $last_sender_details['lastname'];
            $message['last_sender_photo'] = $last_sender_details['photo'];

            $this->db->select('is_trash, is_draft');
            $this->db->from('user_message_thread_status');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->where('user_id', $user_id); // Coincide con el ID del usuario actual
            $this->db->where('user_group', $user_group); // Coincide con el grupo del usuario actual
            $thread_status_query = $this->db->get();
            $thread_status = $thread_status_query->row_array();
        
            if ($thread_status) {
                if ($thread_status['is_trash'] == 1 && $thread_status['is_draft'] == 0) {
                    $message['status'] = 'papelera';
                } elseif ($thread_status['is_trash'] == 0 && $thread_status['is_draft'] == 1) {
                    $message['status'] = 'archivado';
                } elseif ($thread_status['is_trash'] == 0 && $thread_status['is_draft'] == 0) {
                    $message['status'] = 'buzón';
                }
            }

            // Inicializar si no existe
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = $message;
                $grouped_messages[$thread_code]['tags'] = [];
                $grouped_messages[$thread_code]['has_text'] = !empty($message['has_text']);
                $grouped_messages[$thread_code]['has_image'] = !empty($message['has_image']);
                $grouped_messages[$thread_code]['has_video'] = !empty($message['has_video']);
                $grouped_messages[$thread_code]['has_audio'] = !empty($message['has_audio']);
                $grouped_messages[$thread_code]['has_document'] = !empty($message['has_document']);
                $grouped_messages[$thread_code]['new_message_count'] = 0;
            }
            
            // Verificar si hay attachments
            if (!empty($message['has_text'])) {
                $grouped_messages[$thread_code]['has_text'] = true;
            }
            if (!empty($message['has_image'])) {
                $grouped_messages[$thread_code]['has_image'] = true;
            }
            if (!empty($message['has_video'])) {
                $grouped_messages[$thread_code]['has_video'] = true;
            }
            if (!empty($message['has_audio'])) {
                $grouped_messages[$thread_code]['has_audio'] = true;
            }
            if (!empty($message['has_document'])) {
                $grouped_messages[$thread_code]['has_document'] = true;
            }
    
            // Verificar la cantidad de mensajes no leídos en este thread
            $this->db->select('new_messages_count');
            $this->db->from('user_message_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('message_thread_code', $thread_code);
        
            $new_messages_query = $this->db->get();
            $new_messages_result = $new_messages_query->row_array();
            $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;
    
            // Actualizar si el timestamp es más reciente
            if (strtotime($message['last_message_timestamp']) > strtotime($grouped_messages[$thread_code]['last_message_timestamp'])) {
                $grouped_messages[$thread_code] = $message;
            }

            $grouped_messages[$thread_code]['status'] = $message['status'];
        }

     
    
        // Obtener los tags y subjects
        foreach ($grouped_messages as $thread_code => &$grouped_message) {
            $this->db->select('tags');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $tags_query = $this->db->get();
            $tags_results = $tags_query->result_array();

            foreach ($tags_results as $tags_row) {
                $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
                if (!empty($current_tags)) {
                    // Mezclar los tags actuales con los ya agrupados
                    $grouped_message['tags'] = array_unique(array_merge($grouped_message['tags'], $current_tags));
                    
                }
            }
    
            $this->db->select('subject');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->limit(1);
            $subject_query = $this->db->get();
            if ($subject_row = $subject_query->row_array()) {
                $grouped_message['subject'] = $subject_row['subject'];
            }
        }

    
        usort($grouped_messages, function ($a, $b) {
            if ($a['is_favorite'] == $b['is_favorite']) {
                return strtotime($b['last_message_timestamp']) - strtotime($a['last_message_timestamp']);
            }
            return $b['is_favorite'] - $a['is_favorite'];
        });
    
        $page_data['messages'] = array_values($grouped_messages);
        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 
        $page_data['active_tag'] = $param1; 
        $page_data['active_tag_count'] = $active_tag_count;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('tag')),
                'url' => base_url('index.php?admin/message')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_tag';
        $page_data['page_title'] = 'message_tag';
        
    
        $this->load->view('backend/index', $page_data);
    }





    function message_draft($param1 = 'message_default', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
    
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
    
         // Contar los mensajes no leídos (new_messages_count > 0)
         $this->db->select('COUNT(*) as unread_count');
         $this->db->from('user_message_status');
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('new_messages_count >', 0);
         
         $unread_count_query = $this->db->get();
         $unread_count_result = $unread_count_query->row_array();
         $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;
 
         $this->db->select('COUNT(*) as received_count');
         $this->db->from('message_thread');
         
         // Realizar un INNER JOIN con user_message_thread_status
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->group_start();
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->or_where('message_thread.receiver_id', $user_id);
         $this->db->where('message_thread.receiver_group', $user_group);
         
         // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
         $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
         
         // Comprobar si el usuario está en bcc
         $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
         $this->db->group_end();
         
         // Agregar condiciones para is_trash e is_draft
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $received_count_query = $this->db->get();
         $received_count_result = $received_count_query->row_array();
         $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;

         $this->db->select('COUNT(*) as sent_count');
         $this->db->from('message_thread');
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $sent_count_query = $this->db->get();
         $sent_count_result = $sent_count_query->row_array();
         $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
 
         $this->db->select('COUNT(*) as draft_count');
        $this->db->from('message_thread');

        // Unir con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');

        // Filtrar por usuario, grupo y hilos marcados como borradores
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);
        $this->db->where('user_message_thread_status.is_draft', 1);

        // Filtrar por mensajes que no están en la papelera
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');

        // Ejecutar la consulta
        $draft_count_query = $this->db->get();
        $draft_count_result = $draft_count_query->row_array();
        $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;

 
        $this->db->select('COUNT(*) as trash_count');
        $this->db->from('message_thread');

        // Unir con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');

        // Filtrar por usuario, grupo y hilos que están en la papelera
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);
        $this->db->where('user_message_thread_status.is_trash', 1);
        $this->db->where('user_message_thread_status.is_draft', 0);

        // Ejecutar la consulta
        $trash_count_query = $this->db->get();
        $trash_count_result = $trash_count_query->row_array();
        $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;

 
 
         $this->db->select('COUNT(*) as favorite_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_favorite', 1);
         $this->db->where('is_trash', 0);
         $this->db->where('is_draft', 0);
 
         // Ejecutar la consulta
         $favorite_count_query = $this->db->get();
         $favorite_count_result = $favorite_count_query->row_array();
         $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;


         $message_counts = [
            'urgent' => 0,
            'homework' => 0,
            'announcement' => 0,
            'meeting' => 0,
            'event' => 0,
            'reminder' => 0,
            'grade_report' => 0,
            'exam' => 0,
            'behavior' => 0,
            'important' => 0
        ];

        $active_tag_count = 0;
        
        // Obtener los tags directamente de la base de datos
        $this->db->select('tags');
        $this->db->from('message_thread mt');
        
        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Ejecutar la consulta para obtener las etiquetas
        $tags_query = $this->db->get();
        $tags_results = $tags_query->result_array();
        
        foreach ($tags_results as $tags_row) {
            $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
            if (!empty($current_tags)) {
                // Contar los tags específicos
                foreach ($current_tags as $tag) {
                    if (isset($message_counts[$tag])) {
                        $message_counts[$tag]++; // Incrementar el contador de ese tag
                    }
                    // Verificar si el tag coincide con param1
                    if ($tag === $param1) {
                        $active_tag_count++; // Incrementar el contador de tags activos
                    }
                }
            }
        }


        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

        // Obtener los mensajes y los hilos de mensajes
        $this->db->select('m.message_id, m.message_thread_code, m.message, m.sender_id, m.sender_group, 
         m.has_image, m.has_video, m.has_audio, m.has_document, m.has_text, mt.message_thread_id, mt.message_thread_code, mt.receiver_id, mt.receiver_group, mt.last_message_timestamp, 
        mt.last_sender_id, mt.last_sender_group, umts.is_favorite, umts.is_draft, umts.is_trash, mt.subject');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');

        // Unión adicional con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status umts', 'mt.message_thread_code = umts.message_thread_code');

        // Iniciar agrupamiento de condiciones
        $this->db->group_start();

        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();

        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();

        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.cc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.bcc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Cerrar agrupamiento de condiciones
        $this->db->group_end();

        // Agregar las condiciones comunes (fuera de los OR)
        // Ahora is_draft, is_trash y is_favorite están en la tabla user_message_thread_status (umts)
        $this->db->where('umts.user_id', $user_id);
        $this->db->where('umts.user_group', $user_group);
        $this->db->where('umts.is_draft', 1);
        $this->db->where('umts.is_trash', 0);

        // Ejecutar la consulta
        $query = $this->db->get();
        $messages = $query->result_array();
    
        $grouped_messages = [];
    
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];
    
            // Consulta para obtener los detalles del sender
            $this->db->select('firstname, lastname');
            $this->db->from($message['sender_group'] . '_details');
            $this->db->where($message['sender_group'] . '_id', $message['sender_id']);
            $sender_details_query = $this->db->get();
            $sender_details = $sender_details_query->row_array();
    
            // Último remitente
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['last_sender_group'] . '_details');
            $this->db->where($message['last_sender_group'] . '_id', $message['last_sender_id']);
            $last_sender_details_query = $this->db->get();
            $last_sender_details = $last_sender_details_query->row_array();
    
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];
            $message['last_sender_firstname'] = $last_sender_details['firstname'];
            $message['last_sender_lastname'] = $last_sender_details['lastname'];
            $message['last_sender_photo'] = $last_sender_details['photo'];
    
            // Inicializar si no existe
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = $message;
                $grouped_messages[$thread_code]['tags'] = [];
                $grouped_messages[$thread_code]['has_text'] = !empty($message['has_text']);
                $grouped_messages[$thread_code]['has_image'] = !empty($message['has_image']);
                $grouped_messages[$thread_code]['has_video'] = !empty($message['has_video']);
                $grouped_messages[$thread_code]['has_audio'] = !empty($message['has_audio']);
                $grouped_messages[$thread_code]['has_document'] = !empty($message['has_document']);
                $grouped_messages[$thread_code]['new_message_count'] = 0;
            }
    
            // Verificar si hay attachments
            if ($message['has_text'] == 1) {
                $grouped_messages[$thread_code]['has_text'] = true;
            }
            if ($message['has_image'] == 1) {
                $grouped_messages[$thread_code]['has_image'] = true;
            }
            if ($message['has_video'] == 1) {
                $grouped_messages[$thread_code]['has_video'] = true;
            }
            if ($message['has_audio'] == 1) {
                $grouped_messages[$thread_code]['has_audio'] = true;
            }
            if ($message['has_document'] == 1) {
                $grouped_messages[$thread_code]['has_document'] = true;
            }
           
    
            // Verificar la cantidad de mensajes no leídos en este thread
            $this->db->select('new_messages_count');
            $this->db->from('user_message_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('message_thread_code', $thread_code);
        
            $new_messages_query = $this->db->get();
            $new_messages_result = $new_messages_query->row_array();
            $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;
    
            // Actualizar si el timestamp es más reciente
            if (strtotime($message['last_message_timestamp']) > strtotime($grouped_messages[$thread_code]['last_message_timestamp'])) {
                $grouped_messages[$thread_code] = $message;
            }
        }
    
        // Obtener los tags y subjects
        foreach ($grouped_messages as $thread_code => &$grouped_message) {
            $this->db->select('tags');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $tags_query = $this->db->get();
            $tags_results = $tags_query->result_array();
            foreach ($tags_results as $tags_row) {
                $current_tags = json_decode($tags_row['tags'], true);
                if (!empty($current_tags)) {
                    $grouped_message['tags'] = array_unique(array_merge($grouped_message['tags'], $current_tags));
                }
            }
    
            $this->db->select('subject');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->limit(1);
            $subject_query = $this->db->get();
            if ($subject_row = $subject_query->row_array()) {
                $grouped_message['subject'] = $subject_row['subject'];
            }
        }
    
        usort($grouped_messages, function ($a, $b) {
            if ($a['is_favorite'] == $b['is_favorite']) {
                return strtotime($b['last_message_timestamp']) - strtotime($a['last_message_timestamp']);
            }
            return $b['is_favorite'] - $a['is_favorite'];
        });
    
        $page_data['messages'] = array_values($grouped_messages);
        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 
        $page_data['active_tag'] = $param1; 
        $page_data['active_tag_count'] = $active_tag_count;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('draft')),
                'url' => base_url('index.php?admin/message draft')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_draft';
        $page_data['page_title'] = 'message draft';

    
        $this->load->view('backend/index', $page_data);
    }



    function message_trash($param1 = 'message_default', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
    
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
    
         // Contar los mensajes no leídos (new_messages_count > 0)
         $this->db->select('COUNT(*) as unread_count');
         $this->db->from('user_message_status');
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('new_messages_count >', 0);
         
         $unread_count_query = $this->db->get();
         $unread_count_result = $unread_count_query->row_array();
         $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;
 
         $this->db->select('COUNT(*) as received_count');
         $this->db->from('message_thread');
         
         // Realizar un INNER JOIN con user_message_thread_status
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->group_start();
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->or_where('message_thread.receiver_id', $user_id);
         $this->db->where('message_thread.receiver_group', $user_group);
         
         // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
         $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
         
         // Comprobar si el usuario está en bcc
         $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
         $this->db->group_end();
         
         // Agregar condiciones para is_trash e is_draft
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $received_count_query = $this->db->get();
         $received_count_result = $received_count_query->row_array();
         $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;

         $this->db->select('COUNT(*) as sent_count');
         $this->db->from('message_thread');
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $sent_count_query = $this->db->get();
         $sent_count_result = $sent_count_query->row_array();
         $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
 
           // Contar los borradores (draft_count)
         $this->db->select('COUNT(*) as draft_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_draft', 1);
 
         // Ejecutar la consulta
         $draft_count_query = $this->db->get();
         $draft_count_result = $draft_count_query->row_array();
         $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;
 
         $this->db->select('COUNT(*) as trash_count');
         $this->db->from('user_message_thread_status');
         
         // Filtrar por usuario, grupo y mensajes en papelera
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_trash', 1);
         
         // Excluir mensajes que están como borradores
         $this->db->where('is_draft', 0);
         
         // Ejecutar la consulta
         $trash_count_query = $this->db->get();
         $trash_count_result = $trash_count_query->row_array();
         $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;
         
 
 
         $this->db->select('COUNT(*) as favorite_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_favorite', 1);
 
         // Ejecutar la consulta
         $favorite_count_query = $this->db->get();
         $favorite_count_result = $favorite_count_query->row_array();
         $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;


         $message_counts = [
            'urgent' => 0,
            'homework' => 0,
            'announcement' => 0,
            'meeting' => 0,
            'event' => 0,
            'reminder' => 0,
            'grade_report' => 0,
            'exam' => 0,
            'behavior' => 0,
            'important' => 0
        ];

        $active_tag_count = 0;
        
        // Obtener los tags directamente de la base de datos
        $this->db->select('tags');
        $this->db->from('message_thread mt');
        
        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Ejecutar la consulta para obtener las etiquetas
        $tags_query = $this->db->get();
        $tags_results = $tags_query->result_array();
        
        foreach ($tags_results as $tags_row) {
            $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
            if (!empty($current_tags)) {
                // Contar los tags específicos
                foreach ($current_tags as $tag) {
                    if (isset($message_counts[$tag])) {
                        $message_counts[$tag]++; // Incrementar el contador de ese tag
                    }
                    // Verificar si el tag coincide con param1
                    if ($tag === $param1) {
                        $active_tag_count++; // Incrementar el contador de tags activos
                    }
                }
            }
        }


        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

        // Obtener los mensajes y los hilos de mensajes
        $this->db->select('m.message_id, m.message_thread_code, m.message, m.sender_id, m.sender_group, 
         m.has_image, m.has_video, m.has_audio, m.has_document, m.has_text, mt.message_thread_id, mt.message_thread_code, mt.receiver_id, mt.receiver_group, mt.last_message_timestamp, 
        mt.last_sender_id, mt.last_sender_group, umts.is_favorite, umts.is_draft, umts.is_trash, mt.subject');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');

        // Unión adicional con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status umts', 'mt.message_thread_code = umts.message_thread_code');

        // Iniciar agrupamiento de condiciones
        $this->db->group_start();

        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();

        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();

        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.cc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->where('mt.bcc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();

        // Cerrar agrupamiento de condiciones
        $this->db->group_end();

        // Agregar las condiciones comunes (fuera de los OR)
        // Ahora is_draft, is_trash y is_favorite están en la tabla user_message_thread_status (umts)
        $this->db->where('umts.user_id', $user_id);
        $this->db->where('umts.user_group', $user_group);
        $this->db->where('umts.is_draft', 0);
        $this->db->where('umts.is_trash', 1);

        // Ejecutar la consulta
        $query = $this->db->get();
        $messages = $query->result_array();
    
        $grouped_messages = [];
    
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];

            // Consulta para obtener los detalles del sender
            $this->db->select('firstname, lastname');
            $this->db->from($message['sender_group'] . '_details');
            $this->db->where($message['sender_group'] . '_id', $message['sender_id']);
            $sender_details_query = $this->db->get();
            $sender_details = $sender_details_query->row_array();
    
            // Último remitente
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['last_sender_group'] . '_details');
            $this->db->where($message['last_sender_group'] . '_id', $message['last_sender_id']);
            $last_sender_details_query = $this->db->get();
            $last_sender_details = $last_sender_details_query->row_array();
    
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];
            $message['last_sender_firstname'] = $last_sender_details['firstname'];
            $message['last_sender_lastname'] = $last_sender_details['lastname'];
            $message['last_sender_photo'] = $last_sender_details['photo'];

            

            // Inicializar si no existe
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = $message;
                $grouped_messages[$thread_code]['tags'] = [];
                $grouped_messages[$thread_code]['has_text'] = !empty($message['has_text']);
                $grouped_messages[$thread_code]['has_image'] = !empty($message['has_image']);
                $grouped_messages[$thread_code]['has_video'] = !empty($message['has_video']);
                $grouped_messages[$thread_code]['has_audio'] = !empty($message['has_audio']);
                $grouped_messages[$thread_code]['has_document'] = !empty($message['has_document']);
                $grouped_messages[$thread_code]['new_message_count'] = 0;
            }
    
            // Verificar si hay attachments
            if ($message['has_text'] == 1) {
                $grouped_messages[$thread_code]['has_text'] = true;
            }
            if ($message['has_image'] == 1) {
                $grouped_messages[$thread_code]['has_image'] = true;
            }
            if ($message['has_video'] == 1) {
                $grouped_messages[$thread_code]['has_video'] = true;
            }
            if ($message['has_audio'] == 1) {
                $grouped_messages[$thread_code]['has_audio'] = true;
            }
            if ($message['has_document'] == 1) {
                $grouped_messages[$thread_code]['has_document'] = true;
            }
          
    
            // Verificar la cantidad de mensajes no leídos en este thread
            $this->db->select('new_messages_count');
            $this->db->from('user_message_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('message_thread_code', $thread_code);
        
            $new_messages_query = $this->db->get();
            $new_messages_result = $new_messages_query->row_array();
            $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;
    
            // Actualizar si el timestamp es más reciente
            if (strtotime($message['last_message_timestamp']) > strtotime($grouped_messages[$thread_code]['last_message_timestamp'])) {
                $grouped_messages[$thread_code] = $message;
            }

        }
    
        // Obtener los tags y subjects
        foreach ($grouped_messages as $thread_code => &$grouped_message) {
            $this->db->select('tags');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $tags_query = $this->db->get();
            $tags_results = $tags_query->result_array();
            foreach ($tags_results as $tags_row) {
                $current_tags = json_decode($tags_row['tags'], true);
                if (!empty($current_tags)) {
                    $grouped_message['tags'] = array_unique(array_merge($grouped_message['tags'], $current_tags));
                }
            }
    
            $this->db->select('subject');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->limit(1);
            $subject_query = $this->db->get();
            if ($subject_row = $subject_query->row_array()) {
                $grouped_message['subject'] = $subject_row['subject'];
            }
        }
    
        usort($grouped_messages, function ($a, $b) {
            if ($a['is_favorite'] == $b['is_favorite']) {
                return strtotime($b['last_message_timestamp']) - strtotime($a['last_message_timestamp']);
            }
            return $b['is_favorite'] - $a['is_favorite'];
        });
    
        $page_data['messages'] = array_values($grouped_messages);
        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 
        $page_data['active_tag'] = $param1; 
        $page_data['active_tag_count'] = $active_tag_count;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('trash')),
                'url' => base_url('index.php?admin/message')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_trash';
        $page_data['page_title'] = 'message trash';
    
        $this->load->view('backend/index', $page_data);
    }

    

    function message_read($message_thread_code = '') {
        // Verificar que el usuario esté logueado
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
    
        // Obtener el user_id y user_group de la sesión actual
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');

        // Contar los mensajes no leídos (new_messages_count > 0)
        $this->db->select('COUNT(*) as unread_count');
        $this->db->from('user_message_status');
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->where('new_messages_count >', 0);
        
        $unread_count_query = $this->db->get();
        $unread_count_result = $unread_count_query->row_array();
        $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;

        $this->db->select('COUNT(*) as received_count');
        $this->db->from('message_thread');
        
        // Realizar un INNER JOIN con user_message_thread_status
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
        
        $this->db->group_start();
        $this->db->where('message_thread.sender_id', $user_id);
        $this->db->where('message_thread.sender_group', $user_group);
        $this->db->or_where('message_thread.receiver_id', $user_id);
        $this->db->where('message_thread.receiver_group', $user_group);
        
        // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
        $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
        
        // Comprobar si el usuario está en bcc
        $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
        $this->db->group_end();
        
        // Agregar condiciones para is_trash e is_draft
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('user_message_thread_status.is_draft', 0);
        
        $received_count_query = $this->db->get();
        $received_count_result = $received_count_query->row_array();
        $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;


        $this->db->select('COUNT(*) as sent_count');
        $this->db->from('message_thread');
        $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
        $this->db->where('message_thread.sender_id', $user_id);
        $this->db->where('message_thread.sender_group', $user_group);
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('user_message_thread_status.is_draft', 0);
        
        $sent_count_query = $this->db->get();
        $sent_count_result = $sent_count_query->row_array();
        $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;

          // Contar los borradores (draft_count)
        $this->db->select('COUNT(*) as draft_count');
        $this->db->from('user_message_thread_status');
        
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('is_draft', 1);

        // Ejecutar la consulta
        $draft_count_query = $this->db->get();
        $draft_count_result = $draft_count_query->row_array();
        $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;

        $this->db->select('COUNT(*) as trash_count');
        $this->db->from('user_message_thread_status');
        
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('is_trash', 1);

        // Ejecutar la consulta
        $trash_count_query = $this->db->get();
        $trash_count_result = $trash_count_query->row_array();
        $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;



        $this->db->select('COUNT(*) as favorite_count');
        $this->db->from('user_message_thread_status');
        
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('is_favorite', 1);

        // Ejecutar la consulta
        $favorite_count_query = $this->db->get();
        $favorite_count_result = $favorite_count_query->row_array();
        $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;



        $this->db->select('is_favorite, is_trash, is_draft, trash_timestamp');
        $this->db->from('user_message_thread_status');
        $this->db->where('user_id', $user_id);
        $this->db->where('user_group', $user_group);
        $this->db->where('message_thread_code', $message_thread_code);
        
        // Ejecutar la consulta
        $user_message_thread_status_query = $this->db->get();
        $user_message_thread_status_result = $user_message_thread_status_query->row();
        
        $is_favorite = $user_message_thread_status_result->is_favorite; 
        $is_trash = $user_message_thread_status_result->is_trash; 
        $trash_timestamp = $user_message_thread_status_result->trash_timestamp;       
        $is_draft = $user_message_thread_status_result->is_draft; 
        

        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

        $this->db->select('is_trash, trash_timestamp');
        $this->db->from('message_thread');
        $this->db->where('message_thread_code', $message_thread_code);
        
        // Ejecutar la consulta
        $message_thread_status_query = $this->db->get();
        $message_thread_status_result = $message_thread_status_query->row();
        
        $is_trash_thread = $message_thread_status_result->is_trash; 
        $trash_timestamp_thread = $message_thread_status_result->trash_timestamp;       
    
        // Consulta para obtener los mensajes específicos según el message_thread_code
        $this->db->select('m.message_id, m.message_thread_code, m.timestamp, m.message, m.sender_id, m.sender_group, m.receiver_id, m.receiver_group, m.has_image, m.has_video, m.has_audio, m.has_document, m.has_text');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');
     
    
        if (!empty($message_thread_code)) {
            $this->db->where('m.message_thread_code', $message_thread_code);
        }
    
        $query = $this->db->get();
        $messages = $query->result_array();

        $this->db->select('message_thread_id, message_thread_code, last_message_timestamp, receiver_id AS mt_receiver_id, receiver_group AS mt_receiver_group, sender_id AS mt_sender_id, sender_group AS mt_sender_group
        , cc_users_ids AS mt_cc_users_ids, cc_groups AS mt_cc_groups, bcc_users_ids AS mt_bcc_users_ids, bcc_groups AS mt_bcc_groups, tags, subject AS mt_subject, is_trash, trash_timestamp');
        $this->db->from('message_thread');

        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->limit(1);

        $query2 = $this->db->get();
        $messages2 = $query2->row_array(); // Usar row_array() para obtener un único registro

      
        if (!empty($message_thread_code)) {
            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->update('user_message_status', ['new_messages_count' => 0]);

            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->update('user_message_status', ['last_seen_timestamp' => date('Y-m-d H:i:s')]);  
        }

        // Agrupar los mensajes por message_thread_code
        $grouped_messages = [];
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];
    
            // Obtener detalles del remitente (sender)
            $sender_details = $this->get_user_details($message['sender_group'], $message['sender_id']);
            $message['sender_email'] = $sender_details['email'];
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];
            $message['sender_photo'] = $sender_details['photo'];

            // Obtener detalles del destinatario (receiver)
            $receiver_details = $this->get_user_details($message['receiver_group'], $message['receiver_id']);
            $message['receiver_email'] = $receiver_details['email'];
            $message['receiver_firstname'] = $receiver_details['firstname'];
            $message['receiver_lastname'] = $receiver_details['lastname'];

            // Añadir detalles del mensaje al array
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = [
                    'messages' => [],
                    'has_attachments' => false,
                    'attachments' => [],
                ];
            }

            $attachments = [];
            if (!empty($message['has_image'])) {
                $attachments['images'] = json_decode($message['has_image'], true);
            }
            if (!empty($message['has_video'])) {
                $attachments['videos'] = json_decode($message['has_video'], true);
            }
            if (!empty($message['has_audio'])) {
                $attachments['audios'] = json_decode($message['has_audio'], true);
            }
            if (!empty($message['has_document'])) {
                $attachments['documents'] = json_decode($message['has_document'], true);
            }
            if (!empty($message['has_text'])) {
                $attachments['texts'] = json_decode($message['has_text'], true);
            }
        
            // Si hay archivos adjuntos, añadir al array y marcar has_attachments como true
            if (!empty($attachments)) {
                $grouped_messages[$thread_code]['has_attachments'] = true;
                $grouped_messages[$thread_code]['attachments'] = $attachments;
            }


             // Verificar la cantidad de mensajes no leídos en este thread
             $this->db->select('new_messages_count');
             $this->db->from('user_message_status');
             $this->db->where('user_id', $user_id);
             $this->db->where('message_thread_code', $thread_code);
         
             $new_messages_query = $this->db->get();
             $new_messages_result = $new_messages_query->row_array();
             $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;

    
            $grouped_messages[$thread_code]['messages'][] = [
                'sender_firstname' => $message['sender_firstname'],
                'sender_lastname' => $message['sender_lastname'],
                'sender_email' => $message['sender_email'],
                'sender_photo' => $message['sender_photo'],
                'receiver_firstname' => $message['receiver_firstname'],
                'receiver_lastname' => $message['receiver_lastname'],
                'receiver_email' => $message['receiver_email'],
                'timestamp' => $message['timestamp'],
                'message' => $message['message']
            ];
    
            // Verificar si hay attachments
            // if ($message['attachments'] == 1) {
            //     $grouped_messages[$thread_code]['has_attachments'] = true;
            // }
          
        }
    
        $page_data['messages'] = $grouped_messages;
        $page_data['attachments'] = $attachments;
        

        $grouped_messages2 = [];

        // Verifica que $messages2 no esté vacío
        if (!empty($messages2)) {
            $thread_code2 = $messages2['message_thread_code'];
        
            // Obtener detalles del remitente (sender)
            $sender_details2 = $this->get_user_details($messages2['mt_sender_group'], $messages2['mt_sender_id']);
            $messages2['sender_email'] = $sender_details2['email'];
            $messages2['sender_firstname'] = $sender_details2['firstname'];
            $messages2['sender_lastname'] = $sender_details2['lastname'];
            $messages2['sender_photo'] = $sender_details2['photo'];
        
            // Obtener detalles del destinatario (receiver)
            $receiver_details2 = $this->get_user_details($messages2['mt_receiver_group'], $messages2['mt_receiver_id']);
            $messages2['receiver_email'] = $receiver_details2['email'];
            $messages2['receiver_firstname'] = $receiver_details2['firstname'];
            $messages2['receiver_lastname'] = $receiver_details2['lastname'];
            $messages2['receiver_photo'] = $sender_details2['photo'];

            $mt_receiver_details2 = $this->get_user_details($messages2['mt_receiver_group'], $messages2['mt_receiver_id']);
            $messages2['mt_receiver_email'] = $mt_receiver_details2['email'];
            $messages2['mt_receiver_firstname'] = $mt_receiver_details2['firstname'];
            $messages2['mt_receiver_lastname'] = $mt_receiver_details2['lastname'];
            $messages2['mt_receiver_photo'] = $mt_receiver_details2['photo'];

            $mt_sender_details2 = $this->get_user_details($messages2['mt_sender_group'], $messages2['mt_sender_id']);
            $messages2['mt_sender_email'] = $mt_sender_details2['email'];
            $messages2['mt_sender_firstname'] = $mt_sender_details2['firstname'];
            $messages2['mt_sender_lastname'] = $mt_sender_details2['lastname'];
            $messages2['mt_sender_photo'] = $mt_sender_details2['photo'];

            // Obtener detalles de cc y bcc (si existen)
            $cc_details2 = [];
            if (!empty($messages2['mt_cc_users_ids']) && !empty($messages2['mt_cc_groups'])) {
                $cc_users_ids2 = json_decode($messages2['mt_cc_users_ids'], true);
                $cc_groups2 = json_decode($messages2['mt_cc_groups'], true);
        
                foreach ($cc_users_ids2 as $index2 => $cc_user_id2) {
                    $user_details2 = $this->get_user_details($cc_groups2[$index2], $cc_user_id2);
                    if ($user_details2) {
                        $cc_details2[] = [
                            'email' => $user_details2['email'],
                            'firstname' => $user_details2['firstname'],
                            'lastname' => $user_details2['lastname'],
                            'photo' => $user_details2['photo'],
                            'id' => $cc_user_id2,
                            'group' => $cc_groups2[$index2]
                        ];
                    }
                }
            }
        
            $bcc_details2 = [];
            if (!empty($messages2['mt_bcc_users_ids']) && !empty($messages2['mt_bcc_groups'])) {
                $bcc_users_ids2 = json_decode($messages2['mt_bcc_users_ids'], true);
                $bcc_groups2 = json_decode($messages2['mt_bcc_groups'], true);
        
                foreach ($bcc_users_ids2 as $index2 => $bcc_user_id2) {
                    $user_details2 = $this->get_user_details($bcc_groups2[$index2], $bcc_user_id2);
                    if ($user_details2) {
                        $bcc_details2[] = [
                            'email' => $user_details2['email'],
                            'firstname' => $user_details2['firstname'],
                            'lastname' => $user_details2['lastname'],
                            'photo' => $user_details2['photo'],
                            'id' => $bcc_user_id2,
                            'group' => $bcc_groups2[$index2]
                        ];
                    }
                }
            }

            // Añadir detalles del mensaje
            $grouped_messages2[$thread_code2] = [
                'messages' => [
                    [
                        'mt_receiver_id' => $messages2['mt_receiver_id'],
                        'mt_receiver_group' => $messages2['mt_receiver_group'],
                        'mt_receiver_firstname' => $messages2['mt_receiver_firstname'],
                        'mt_receiver_lastname' => $messages2['mt_receiver_lastname'],
                        'mt_receiver_email' => $messages2['mt_receiver_email'],
                        'mt_receiver_photo' => $messages2['mt_receiver_photo'],
                        'mt_sender_id' => $messages2['mt_sender_id'],
                        'mt_sender_group' => $messages2['mt_sender_group'],
                        'mt_sender_firstname' => $messages2['mt_sender_firstname'],
                        'mt_sender_lastname' => $messages2['mt_sender_lastname'],
                        'mt_sender_email' => $messages2['mt_sender_email'],
                        'mt_sender_photo' => $messages2['mt_sender_photo'],
                        'mt_subject' => $messages2['mt_subject'],
                        'mt_cc' => $cc_details2,
                        'mt_bcc' => $bcc_details2,
                    ]
                    ],
                    'tags' => [],
            ];

            $current_tags = json_decode($messages2['tags'], true);
            if (!empty($current_tags)) {
                // Asegurarse de que 'tags' esté inicializado en el array
                if (!isset($grouped_messages2[$thread_code2]['tags'])) {
                    $grouped_messages2[$thread_code2]['tags'] = [];
                }
                // Combinar los tags existentes con los nuevos, y eliminar duplicados
                $grouped_messages2[$thread_code2]['tags'] = array_unique(array_merge($grouped_messages2[$thread_code2]['tags'], $current_tags));
            }
        }
        
        $page_data['messages2'] = $grouped_messages2;

    
        // Definir el breadcrumb
      
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('read')),
                'url' => base_url('index.php?admin/message')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_read';
        $page_data['page_title'] = 'Messaging';
        $page_data['message_thread_code'] = $message_thread_code;
        $page_data['is_trash'] = $is_trash;
        $page_data['trash_timestamp'] = $trash_timestamp;
        $page_data['is_favorite'] = $is_favorite;
        $page_data['is_draft'] = $is_draft;
        $page_data['is_trash_thread'] = $is_trash_thread;

        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count;

        $page_data['mt_sender_id'] = $messages2['mt_sender_id'];
        $page_data['mt_sender_group'] = $messages2['mt_sender_group'];
        $page_data['result_message_tag'] = $result_message_tag; 

        // Cargar la vista
        $this->load->view('backend/index', $page_data);
    }
    
    function get_user_details($group, $id) {
        // Verificar que el grupo no esté vacío
        if (empty($group) || empty($id)) {
            return null; // O manejar el error de otra manera
        }
    
        // Primer consulta: Obtener firstname y lastname de la tabla de detalles
        $this->db->select('firstname, lastname, photo');
        $this->db->from($group . '_details');
        $this->db->where($group . '_id', $id);
        $query = $this->db->get();
        
        // Si no encuentra el usuario, retornar null
        if ($query->num_rows() == 0) {
            return null;
        }
    
        // Almacenar los detalles obtenidos
        $details = $query->row_array();
    
        // Segunda consulta: Obtener el email de la tabla principal
        $this->db->select('email');
        $this->db->from($group);
        $this->db->where($group . '_id', $id);
        $query = $this->db->get();
    
        // Si no encuentra el email, retornar null
        if ($query->num_rows() == 0) {
            return null;
        }
    
        // Combinar los resultados
        $email = $query->row_array();
        
        // Unir los detalles con el email
        return array_merge($details, $email);
    }
    
    
    function message_new($param1 = '', $param2 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

            $user_id = $this->session->userdata('login_user_id');
            $user_group = $this->session->userdata('login_type');

            $this->db->select('COUNT(*) as unread_count');
            $this->db->from('message_thread');
    
            // Realizar un INNER JOIN con user_message_thread_status usando el message_thread_code
            $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
            $this->db->join('user_message_status', 'message_thread.message_thread_code = user_message_status.message_thread_code', 'inner');
    
            // Agrupar las condiciones relacionadas con el usuario
            $this->db->group_start();
            $this->db->where('message_thread.sender_id', $user_id);
            $this->db->where('message_thread.sender_group', $user_group);
            $this->db->or_where('message_thread.receiver_id', $user_id);
            $this->db->where('message_thread.receiver_group', $user_group);
    
            // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
            $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
            $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
    
            // Comprobar si el usuario está en bcc
            $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
            $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
    
            $this->db->group_end();
    
            // Agregar condiciones para el estado del mensaje
            $this->db->where('user_message_thread_status.user_id', $user_id);
            $this->db->where('user_message_thread_status.user_group', $user_group);
    
            // Asegúrate de que los mensajes no estén en la papelera o como borradores
            $this->db->where('user_message_thread_status.is_trash', 0);
            $this->db->where('user_message_thread_status.is_draft', 0);
    
            $this->db->where('user_message_status.user_id', $user_id);
            $this->db->where('user_message_status.user_group', $user_group);
            // Filtrar por mensajes no leídos
            $this->db->where('user_message_status.new_messages_count >', 0);
    
            // Ejecutar la consulta
            $unread_count_query = $this->db->get();
            $unread_count_result = $unread_count_query->row_array();
            $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;
    

            $this->db->select('COUNT(*) as received_count');
            $this->db->from('message_thread');
            
            // Realizar un INNER JOIN con user_message_thread_status
            $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
            
            $this->db->where('message_thread.is_trash', 0);
   
            $this->db->group_start();
            $this->db->where('message_thread.sender_id', $user_id);
            $this->db->where('message_thread.sender_group', $user_group);
            $this->db->or_where('message_thread.receiver_id', $user_id);
            $this->db->where('message_thread.receiver_group', $user_group);
            
            // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
            $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
            $this->db->where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
            
            // Comprobar si el usuario está en bcc
            $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
            $this->db->where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
            $this->db->group_end();
            
            // Agregar condiciones para is_trash e is_draft
            $this->db->where('user_message_thread_status.user_id', $user_id);
            $this->db->where('user_message_thread_status.user_group', $user_group);
            $this->db->where('user_message_thread_status.is_trash', 0);
            $this->db->where('user_message_thread_status.is_draft', 0);
            $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
            
            $received_count_query = $this->db->get();
            $received_count_result = $received_count_query->row_array();
            $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;
   
            $this->db->select('COUNT(*) as sent_count');
            $this->db->from('message_thread');
            $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
            
            $this->db->where('message_thread.sender_id', $user_id);
            $this->db->where('message_thread.sender_group', $user_group);
   
            $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
            $this->db->where('user_message_thread_status.user_id', $user_id);
            $this->db->where('user_message_thread_status.user_group', $user_group);
            $this->db->where('user_message_thread_status.is_trash', 0);
            $this->db->where('user_message_thread_status.is_draft', 0);
            
            $sent_count_query = $this->db->get();
            $sent_count_result = $sent_count_query->row_array();
            $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
    
              // Contar los borradores (draft_count)
            $this->db->select('COUNT(*) as draft_count');
            $this->db->from('user_message_thread_status');
            
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->where('is_draft', 1);
    
            // Ejecutar la consulta
            $draft_count_query = $this->db->get();
            $draft_count_result = $draft_count_query->row_array();
            $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;
    
            $this->db->select('COUNT(*) as trash_count');
            $this->db->from('user_message_thread_status');
            
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->where('is_trash', 1);
    
            // Ejecutar la consulta
            $trash_count_query = $this->db->get();
            $trash_count_result = $trash_count_query->row_array();
            $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;
    
    
            $this->db->select('COUNT(*) as favorite_count');
            $this->db->from('user_message_thread_status');
            
            $this->db->where('user_id', $user_id);
            $this->db->where('user_group', $user_group);
            $this->db->where('is_favorite', 1);
    
            // Ejecutar la consulta
            $favorite_count_query = $this->db->get();
            $favorite_count_result = $favorite_count_query->row_array();
            $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;
   
            $message_counts = [
               'urgent' => 0,
               'homework' => 0,
               'announcement' => 0,
               'meeting' => 0,
               'event' => 0,
               'reminder' => 0,
               'grade_report' => 0,
               'exam' => 0,
               'behavior' => 0,
               'important' => 0
           ];
   
           $active_tag_count = 0;
           
           // Obtener los tags directamente de la base de datos
           $this->db->select('tags');
           $this->db->from('message_thread mt');
           
           // Condiciones de receptor
           $this->db->group_start();
           $this->db->where('mt.receiver_id', $user_id);
           $this->db->where('mt.receiver_group', $user_group);
           $this->db->group_end();
           
           // Condiciones de emisor
           $this->db->or_group_start();
           $this->db->where('mt.sender_id', $user_id);
           $this->db->where('mt.sender_group', $user_group);
           $this->db->group_end();
           
           // Condiciones de CC
           $this->db->or_group_start();
           $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
           $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
           $this->db->group_end();
           
           // Condiciones de BCC
           $this->db->or_group_start();
           $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
           $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
           $this->db->group_end();
           
           // Ejecutar la consulta para obtener las etiquetas
           $tags_query = $this->db->get();
           $tags_results = $tags_query->result_array();
           
           foreach ($tags_results as $tags_row) {
               $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
               if (!empty($current_tags)) {
                   // Contar los tags específicos
                   foreach ($current_tags as $tag) {
                       if (isset($message_counts[$tag])) {
                           $message_counts[$tag]++; // Incrementar el contador de ese tag
                       }
                       // Verificar si el tag coincide con param1
                       if ($tag === $param1) {
                           $active_tag_count++; // Incrementar el contador de tags activos
                       }
                   }
               }
           }
   
   
           $this->db->select('name, badge, label');
           $this->db->from('message_tag');
           $query_message_tag = $this->db->get();
           $result_message_tag = $query_message_tag->result_array();

        if ($param1 == 'send_new') {
            $message_thread_code = substr(md5(rand(100000000, 20000000000)), 0, 15); // Generar código único para el hilo de mensajes
    
            $subject = $this->input->post('subject');
            $message = $this->input->post('message'); // El cuerpo del mensaje
    
            $selectedValueTo = isset($_POST['to']) ? $_POST['to'] : null; // Solo un valor, como 'admin-1'

            $has_image = [];
            $has_video = [];
            $has_audio = [];
            $has_text = [];
            $has_document = [];
    
            $cc_users_ids = [];
            $cc_groups = [];
    
            $bcc_users_ids = [];
            $bcc_groups = [];
    
            $tags = [];
    
            // Procesar CC solo si no está vacío
            if (!empty($_POST['cc'])) {
                $selectedValuesCc = $_POST['cc']; // Valores de CC como 'admin-1', 'admin-2', etc.
    
                // Separar los valores de CC en arrays
                foreach ($selectedValuesCc as $value) {
                    $parts = explode('-', $value); // Separar el valor por el guion "-"
                    if (count($parts) == 2) {
                        $cc_groups[] = $parts[0];  // Parte antes del guion: grupo (e.g., 'admin')
                        $cc_users_ids[] = $parts[1]; // Parte después del guion: ID de usuario (e.g., '1')
                    }
                }
            }
    
            // Procesar BCC solo si no está vacío
            if (!empty($_POST['bcc'])) {
                $selectedValuesBcc = $_POST['bcc'];
    
                foreach ($selectedValuesBcc as $value) {
                    $parts = explode('-', $value); // Separar el valor por el guion "-"
                    if (count($parts) == 2) {
                        $bcc_groups[] = $parts[0];  // Parte antes del guion: grupo (e.g., 'admin')
                        $bcc_users_ids[] = $parts[1]; // Parte después del guion: ID de usuario (e.g., '1')
                    }
                }
            }
    
            // Procesar tags solo si no está vacío
            if (!empty($_POST['tags'])) {
                $selectedValuesTags = $_POST['tags'];
            
                foreach ($selectedValuesTags as $value) {
                    $tags[] = $value;  // Guardar directamente los valores seleccionados
                }
            }
    
            $to_user_id = null;
            $to_group = null;
            if ($selectedValueTo) {
                $to_parts = explode('-', $selectedValueTo); // Separar el valor por el guion "-"
                if (count($to_parts) == 2) {
                    $to_group = $to_parts[0];  // Parte antes del guion: grupo (e.g., 'admin')
                    $to_user_id = $to_parts[1]; // Parte después del guion: ID de usuario (e.g., '1')
                }
            }
    
            // Convertir los arrays de CC y BCC en formato JSON para guardar en la base de datos
            $cc_users_ids_json = !empty($cc_users_ids) ? json_encode($cc_users_ids) : null;
            $cc_groups_json = !empty($cc_groups) ? json_encode($cc_groups) : null;
    
            $bcc_users_ids_json = !empty($bcc_users_ids) ? json_encode($bcc_users_ids) : null;
            $bcc_groups_json = !empty($bcc_groups) ? json_encode($bcc_groups) : null;
    
            $tags_json = !empty($tags) ? json_encode($tags) : json_encode([]); 

            if (!empty($_FILES['attachments']['name'][0])) { // Verificar si hay archivos
                $attachment_directory = 'assets/attachments/' . $message_thread_code . '/';
                if (!is_dir($attachment_directory)) {
                    mkdir($attachment_directory, 0777, true); // Crear la carpeta si no existe
                }
        
                $this->load->library('upload'); // Cargar la librería de carga de archivos
                
                $files = $_FILES;
                $number_of_files = count($_FILES['attachments']['name']);
                $attachments = [];
        
                for ($i = 0; $i < $number_of_files; $i++) {
                    $_FILES['attachment']['name'] = $files['attachments']['name'][$i];
                    $_FILES['attachment']['type'] = $files['attachments']['type'][$i];
                    $_FILES['attachment']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                    $_FILES['attachment']['error'] = $files['attachments']['error'][$i];
                    $_FILES['attachment']['size'] = $files['attachments']['size'][$i];
        
                    $config['upload_path'] = $attachment_directory;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|txt|mp4|mp3|avi';
                    $config['max_size'] = '102400'; // 100MB maximo
                    // $config['file_name'] = time() . '_' . $_FILES['attachment']['name'];
                    $config['file_name'] = $_FILES['attachment']['name'];
        
                    $this->upload->initialize($config);
        
                    if ($this->upload->do_upload('attachment')) {
                        $upload_data = $this->upload->data();
                        $attachments[] = array(
                            'message_thread_code' => $message_thread_code,
                            'file_name' => $upload_data['file_name'],
                            'file_path' => $attachment_directory . $upload_data['file_name'],
                            'uploaded_timestamp' => date('Y-m-d H:i:s')
                        );
        
                        // Verificar la extensión del archivo y almacenar las rutas en los arrays correspondientes
                        $file_extension = strtolower(pathinfo($upload_data['file_name'], PATHINFO_EXTENSION));
                        if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                            $has_image[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['mp4', 'avi'])) {
                            $has_video[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['mp3'])) {
                            $has_audio[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['txt'])) {
                            $has_text[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                            $has_document[] = $attachment_directory . $upload_data['file_name'];
                        }
                    } else {
                        $this->session->set_flashdata('flash_message', array(
                            'title' => 'Error al cargar archivos',
                            'text' => $this->upload->display_errors(),
                            'icon' => 'error',
                            'showCloseButton' => 'true',
                            'confirmButtonText' => 'Aceptar',
                            'confirmButtonColor' => '#d33',
                        ));
                        redirect(base_url() . 'index.php?admin/message_new', 'refresh');
                    }
                }
            }

            $has_text_json = json_encode($has_text);
            $has_document_json = json_encode($has_document);
    
            // Lógica para insertar en la base de datos
            $dataMessage = array(
                'message_thread_code' => $message_thread_code,
                'message' => $message,
                'sender_id' => $this->session->userdata('login_user_id'),
                'sender_group' => $this->session->userdata('login_type'),
                'receiver_id' => $to_user_id, // El ID del usuario para el campo 'To'
                'receiver_group' => $to_group, // El grupo para el campo 'To'
                'timestamp' => date('Y/m/d H:i:s'),
                'has_image' => !empty($has_image) ? str_replace('\/', '/', json_encode($has_image)) : null,
                'has_video' => !empty($has_video) ? str_replace('\/', '/', json_encode($has_video)) : null,
                'has_audio' => !empty($has_audio) ? str_replace('\/', '/', json_encode($has_audio)) : null,
                'has_text' => !empty($has_text) ? str_replace('\/', '/', $has_text_json) : null,
                'has_document' => !empty($has_document) ? str_replace('\/', '/', $has_document_json) : null
            );
    
            $this->db->insert('message', $dataMessage);
    
            // Insertar en la tabla de hilos de mensajes
            $dataMessageThread = array(
                'message_thread_code' => $message_thread_code,
                'subject' => $subject,
                'tags' => $tags_json,
                'sender_id' => $this->session->userdata('login_user_id'),
                'sender_group' => $this->session->userdata('login_type'),
                'receiver_id' => $to_user_id,
                'receiver_group' => $to_group,
                'cc_users_ids' => $cc_users_ids_json, // Guardar los IDs de usuarios de CC en formato JSON
                'cc_groups' => $cc_groups_json, // Guardar los grupos de CC en formato JSON
                'bcc_users_ids' => $bcc_users_ids_json,
                'bcc_groups' => $bcc_groups_json,
                'last_sender_id' => $this->session->userdata('login_user_id'),
                'last_sender_group' => $this->session->userdata('login_type'),
                'last_message_timestamp' => date('Y/m/d H:i:s'), // Timestamp de la última actualización
                'is_trash' => 0,
                'trash_timestamp' => null
            );
    
            $this->db->insert('message_thread', $dataMessageThread);

            // Insertar los participantes en la tabla user_message_status
            $participants = [];

            // Agregar el remitente (sender)
            $participants[] = array(
                'message_thread_code' => $message_thread_code,
                'user_id' => $this->session->userdata('login_user_id'),
                'user_group' => $this->session->userdata('login_type'),
                'new_messages_count' => 0,
                'last_seen_timestamp' => date('Y/m/d H:i:s')
            );

            // Agregar el destinatario (receiver)
            if (!empty($to_user_id)) {
                $participants[] = array(
                    'message_thread_code' => $message_thread_code,
                    'user_id' => $to_user_id,
                    'user_group' => $to_group,
                    'new_messages_count' => 1,
                    'last_seen_timestamp' => NULL
                );
            }

            // Agregar los CC
            foreach ($cc_users_ids as $key => $cc_user_id) {
                $participants[] = array(
                    'message_thread_code' => $message_thread_code,
                    'user_id' => $cc_user_id,
                    'user_group' => $cc_groups[$key],
                    'new_messages_count' => 1,
                    'last_seen_timestamp' => NULL
                );
            }

            // Agregar los BCC
            foreach ($bcc_users_ids as $key => $bcc_user_id) {
                $participants[] = array(
                    'message_thread_code' => $message_thread_code,
                    'user_id' => $bcc_user_id,
                    'user_group' => $bcc_groups[$key],
                    'new_messages_count' => 1,
                    'last_seen_timestamp' => NULL
                );
            }

            // Insertar todos los participantes en la tabla user_message_status
            $this->db->insert_batch('user_message_status', $participants);

            // Insertar los participantes en la tabla user_message_status
            $participantsMessageThreadStatus = [];

            // Agregar el remitente (sender)
            $participantsMessageThreadStatus[] = array(
                'message_thread_code' => $message_thread_code,
                'user_id' => $this->session->userdata('login_user_id'),
                'user_group' => $this->session->userdata('login_type'),
                'is_favorite' => 0,
                'is_trash' => 0,
                'is_draft' => 0,
                'favorite_timestamp' => null,
                'favorite_timestamp' => null,
                'favorite_timestamp' => null,
                'is_trash_by_user_id' => null,
                'is_trash_by_user_group' => null
            );

            // Agregar el destinatario (receiver)
            if (!empty($to_user_id)) {
                $participantsMessageThreadStatus[] = array(
                    'message_thread_code' => $message_thread_code,
                    'user_id' => $to_user_id,
                    'user_group' => $to_group,
                    'is_favorite' => 0,
                    'is_trash' => 0,
                    'is_draft' => 0,
                    'favorite_timestamp' => null,
                    'favorite_timestamp' => null,
                    'favorite_timestamp' => null,
                    'is_trash_by_user_id' => null,
                    'is_trash_by_user_group' => null
                );
            }

            // Agregar los CC
            foreach ($cc_users_ids as $key => $cc_user_id) {
                $participantsMessageThreadStatus[] = array(
                    'message_thread_code' => $message_thread_code,
                    'user_id' => $cc_user_id,
                    'user_group' => $cc_groups[$key],
                    'is_favorite' => 0,
                    'is_trash' => 0,
                    'is_draft' => 0,
                    'favorite_timestamp' => null,
                    'favorite_timestamp' => null,
                    'favorite_timestamp' => null,
                    'is_trash_by_user_id' => null,
                    'is_trash_by_user_group' => null
                );
            }

            // Agregar los BCC
            foreach ($bcc_users_ids as $key => $bcc_user_id) {
                $participantsMessageThreadStatus[] = array(
                    'message_thread_code' => $message_thread_code,
                    'user_id' => $bcc_user_id,
                    'user_group' => $bcc_groups[$key],
                    'is_favorite' => 0,
                    'is_trash' => 0,
                    'is_draft' => 0,
                    'favorite_timestamp' => null,
                    'favorite_timestamp' => null,
                    'favorite_timestamp' => null,
                    'is_trash_by_user_id' => null,
                    'is_trash_by_user_group' => null
                );
            }

            // Insertar todos los participantes en la tabla user_message_status
            $this->db->insert_batch('user_message_thread_status', $participantsMessageThreadStatus);
    
            // Mensaje de confirmación
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Mensaje enviado!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
    
            redirect(base_url() . 'index.php?admin/message_read/' . $message_thread_code, 'refresh');
        }

        if ($param1 == 'send_reply') {
            $message_thread_code = $param2; // Generar código único para el hilo de mensajes
    
            $message = $this->input->post('message'); // El cuerpo del mensaje
    
            $selectedValueTo = $_POST['to']; // Solo un valor, como 'admin-1'
    
            $has_image = [];
            $has_video = [];
            $has_audio = [];
            $has_text = [];
            $has_document = [];

            $attachments = []; 

            // Separar el valor del destinatario (To)
            $to_parts = explode('-', $selectedValueTo); // Separar el valor por el guion "-"
            if (count($to_parts) == 2) {
                $to_group = $to_parts[0];  // Parte antes del guion: grupo (e.g., 'admin')
                $to_user_id = $to_parts[1]; // Parte después del guion: ID de usuario (e.g., '1')
            }

            if (!empty($_FILES['attachments']['name'][0])) { // Verificar si hay archivos
                $attachment_directory = 'assets/attachments/' . $message_thread_code . '/';
                if (!is_dir($attachment_directory)) {
                    mkdir($attachment_directory, 0777, true); // Crear la carpeta si no existe
                }
        
                $this->load->library('upload'); // Cargar la librería de carga de archivos
                
                $files = $_FILES;
                $number_of_files = count($_FILES['attachments']['name']);
                $attachments = [];
        
                for ($i = 0; $i < $number_of_files; $i++) {
                    $_FILES['attachment']['name'] = $files['attachments']['name'][$i];
                    $_FILES['attachment']['type'] = $files['attachments']['type'][$i];
                    $_FILES['attachment']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                    $_FILES['attachment']['error'] = $files['attachments']['error'][$i];
                    $_FILES['attachment']['size'] = $files['attachments']['size'][$i];
        
                    $config['upload_path'] = $attachment_directory;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|txt|mp4|mp3|avi';
                    $config['max_size'] = '102400'; // 100MB maximo
                    // $config['file_name'] = time() . '_' . $_FILES['attachment']['name'];
                    $config['file_name'] = $_FILES['attachment']['name'];
        
                    $this->upload->initialize($config);
        
                    if ($this->upload->do_upload('attachment')) {
                        $upload_data = $this->upload->data();
                        $attachments[] = array(
                            'message_thread_code' => $message_thread_code,
                            'file_name' => $upload_data['file_name'],
                            'file_path' => $attachment_directory . $upload_data['file_name'],
                            'uploaded_timestamp' => date('Y-m-d H:i:s')
                        );
        
                        // Verificar la extensión del archivo y almacenar las rutas en los arrays correspondientes
                        $file_extension = strtolower(pathinfo($upload_data['file_name'], PATHINFO_EXTENSION));
                        if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                            $has_image[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['mp4', 'avi'])) {
                            $has_video[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['mp3'])) {
                            $has_audio[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['txt'])) {
                            $has_text[] = $attachment_directory . $upload_data['file_name'];
                        } elseif (in_array($file_extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                            $has_document[] = $attachment_directory . $upload_data['file_name'];
                        }
                    } else {
                        $this->session->set_flashdata('flash_message', array(
                            'title' => 'Error al cargar archivos',
                            'text' => $this->upload->display_errors(),
                            'icon' => 'error',
                            'showCloseButton' => 'true',
                            'confirmButtonText' => 'Aceptar',
                            'confirmButtonColor' => '#d33',
                        ));
                        redirect(base_url() . 'index.php?admin/message_new', 'refresh');
                    }
                }
            }

            $has_text_json = json_encode($has_text);
            $has_document_json = json_encode($has_document);
    
            // Lógica para insertar en la base de datos
            $dataMessage = array(
                'message_thread_code' => $message_thread_code,
                'message' => $message,
                'sender_id' => $this->session->userdata('login_user_id'),
                'sender_group' => $this->session->userdata('login_type'),
                'receiver_id' => $to_user_id, // El ID del usuario para el campo 'To'
                'receiver_group' => $to_group, // El grupo para el campo 'To'
                'timestamp' => date('Y/m/d H:i:s'),
                'has_image' => !empty($has_image) ? str_replace('\/', '/', json_encode($has_image)) : null,
                'has_video' => !empty($has_video) ? str_replace('\/', '/', json_encode($has_video)) : null,
                'has_audio' => !empty($has_audio) ? str_replace('\/', '/', json_encode($has_audio)) : null,
                'has_text' => !empty($has_text) ? str_replace('\/', '/', $has_text_json) : null,
                'has_document' => !empty($has_document) ? str_replace('\/', '/', $has_document_json) : null
            );
    
            $this->db->insert('message', $dataMessage);
    
            // Insertar en la tabla de hilos de mensajes
            $dataMessageThread = array(
                'last_message_timestamp' => date('Y/m/d H:i:s'),
                'last_sender_id' => $this->session->userdata('login_user_id'),
                'last_sender_group' => $this->session->userdata('login_type')
            );
    
            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->update('message_thread', $dataMessageThread);

              $dataUserMessageThreadStatus = array(
                'is_draft' => 0,
                'draft_timestamp' => null
            );
    
            $this->db->where('message_thread_code', $message_thread_code);
            $this->db->where('user_id', $this->session->userdata('login_user_id'));
            $this->db->where('user_group', $this->session->userdata('login_type'));
            $this->db->update('user_message_thread_status', $dataUserMessageThreadStatus);

            // Obtener todos los participantes de la tabla user_message_status según el message_thread_code
            $this->db->where('message_thread_code', $message_thread_code);
            $participants = $this->db->get('user_message_status')->result_array();

            // Actualizar el campo new_messages_count para cada participante
            foreach ($participants as $participant) {
                $user_id = $participant['user_id'];
                $user_group = $participant['user_group'];

                if ($user_id == $this->session->userdata('login_user_id') && $user_group == $this->session->userdata('login_type')) {
                    // Si el usuario es el sender, poner el new_messages_count en 0
                    $this->db->where('user_id', $user_id);
                    $this->db->where('user_group', $user_group);
                    $this->db->where('message_thread_code', $message_thread_code);
                    $this->db->update('user_message_status', ['new_messages_count' => 0]);
                } else {
                    // Para los demás, incrementar new_messages_count en 1
                    $this->db->set('new_messages_count', 'new_messages_count + 1', FALSE);
                    $this->db->where('user_id', $user_id);
                    $this->db->where('user_group', $user_group);
                    $this->db->where('message_thread_code', $message_thread_code);
                    $this->db->update('user_message_status');
                }
            }

    
            // Mensaje de confirmación
            // $this->session->set_flashdata('flash_message', array(
            //     'title' => 'Mensaje enviado!',
            //     'text' => '',
            //     'icon' => 'success',
            //     'showCloseButton' => 'true',
            //     'confirmButtonText' => 'Aceptar',
            //     'confirmButtonColor' => '#1a92c4',
            //     'timer' => '10000',
            //     'timerProgressBar' => 'true',
            // ));
    
            redirect(base_url() . 'index.php?admin/message_read/' . $message_thread_code, 'refresh');
        }
    
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('new')),
                'url' => base_url('index.php?admin/message')
            )
        );

        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_new';
        $page_data['page_title'] = 'message_new';
    
        // Cargar la vista
        $this->load->view('backend/index', $page_data);
    }
    

    function message_sent($param1 = 'message_default', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
    
        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
    
         // Contar los mensajes no leídos (new_messages_count > 0)
        $this->db->select('COUNT(*) as unread_count');
        $this->db->from('user_message_status');

        // Unir la tabla message_thread a la consulta
        $this->db->join('message_thread', 'message_thread.message_thread_code = user_message_status.message_thread_code', 'inner');

        // Unir la tabla user_message_thread_status a la consulta
        $this->db->join('user_message_thread_status', 'user_message_status.message_thread_code = user_message_thread_status.message_thread_code', 'inner');

        // Filtrar por mensajes del usuario como sender o receiver
        $this->db->group_start();
        $this->db->where('message_thread.sender_id', $user_id);
        $this->db->where('message_thread.sender_group', $user_group);
        $this->db->or_where('message_thread.receiver_id', $user_id);
        $this->db->where('message_thread.receiver_group', $user_group);

        // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
        $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');

        // Comprobar si el usuario está en bcc
        $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
        $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');

        // Finalizar grupo de condiciones
        $this->db->group_end();

        // Agregar condiciones para is_trash e is_draft
        $this->db->where('user_message_thread_status.user_id', $user_id);
        $this->db->where('user_message_thread_status.user_group', $user_group);
        $this->db->where('user_message_thread_status.is_trash', 0);
        $this->db->where('user_message_thread_status.is_draft', 0);
        $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');

        // Condiciones adicionales para mensajes no leídos
        $this->db->where('user_message_status.user_id', $user_id);
        $this->db->where('user_message_status.user_group', $user_group);
        $this->db->where('new_messages_count >', 0);

        // Ejecutar la consulta
        $unread_count_query = $this->db->get();
        $unread_count_result = $unread_count_query->row_array();
        $unread_count = isset($unread_count_result['unread_count']) ? $unread_count_result['unread_count'] : 0;

 
         $this->db->select('COUNT(*) as received_count');
         $this->db->from('message_thread');
         
         // Realizar un INNER JOIN con user_message_thread_status
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         
         $this->db->group_start();
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->or_where('message_thread.receiver_id', $user_id);
         $this->db->where('message_thread.receiver_group', $user_group);
         
         // Comprobar si el usuario está en cc (LIKE para buscar en el JSON almacenado como string)
         $this->db->or_where('message_thread.cc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.cc_groups LIKE \'%"' . $user_group . '"%\'');
         
         // Comprobar si el usuario está en bcc
         $this->db->or_where('message_thread.bcc_users_ids LIKE \'%"' . $user_id . '"%\'');
         $this->db->or_where('message_thread.bcc_groups LIKE \'%"' . $user_group . '"%\'');
         $this->db->group_end();
         
         // Agregar condiciones para is_trash e is_draft
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
         
         $received_count_query = $this->db->get();
         $received_count_result = $received_count_query->row_array();
         $received_count = isset($received_count_result['received_count']) ? $received_count_result['received_count'] : 0;

         $this->db->select('COUNT(*) as sent_count');
         $this->db->from('message_thread');
         $this->db->join('user_message_thread_status', 'message_thread.message_thread_code = user_message_thread_status.message_thread_code', 'inner');
         $this->db->where('(user_message_thread_status.is_trash_by_user_id = 0 OR user_message_thread_status.is_trash_by_user_id IS NULL)');
         $this->db->where('message_thread.sender_id', $user_id);
         $this->db->where('message_thread.sender_group', $user_group);
         $this->db->where('user_message_thread_status.user_id', $user_id);
         $this->db->where('user_message_thread_status.user_group', $user_group);
         $this->db->where('user_message_thread_status.is_trash', 0);
         $this->db->where('user_message_thread_status.is_draft', 0);
         
         $sent_count_query = $this->db->get();
         $sent_count_result = $sent_count_query->row_array();
         $sent_count = isset($sent_count_result['sent_count']) ? $sent_count_result['sent_count'] : 0;
 
           // Contar los borradores (draft_count)
         $this->db->select('COUNT(*) as draft_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_draft', 1);
 
         // Ejecutar la consulta
         $draft_count_query = $this->db->get();
         $draft_count_result = $draft_count_query->row_array();
         $draft_count = isset($draft_count_result['draft_count']) ? $draft_count_result['draft_count'] : 0;
 
         $this->db->select('COUNT(*) as trash_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_trash', 1);
 
         // Ejecutar la consulta
         $trash_count_query = $this->db->get();
         $trash_count_result = $trash_count_query->row_array();
         $trash_count = isset($trash_count_result['trash_count']) ? $trash_count_result['trash_count'] : 0;
 
 
         $this->db->select('COUNT(*) as favorite_count');
         $this->db->from('user_message_thread_status');
         
         $this->db->where('user_id', $user_id);
         $this->db->where('user_group', $user_group);
         $this->db->where('is_favorite', 1);
 
         // Ejecutar la consulta
         $favorite_count_query = $this->db->get();
         $favorite_count_result = $favorite_count_query->row_array();
         $favorite_count = isset($favorite_count_result['favorite_count']) ? $favorite_count_result['favorite_count'] : 0;

         $message_counts = [
            'urgent' => 0,
            'homework' => 0,
            'announcement' => 0,
            'meeting' => 0,
            'event' => 0,
            'reminder' => 0,
            'grade_report' => 0,
            'exam' => 0,
            'behavior' => 0,
            'important' => 0
        ];

        $active_tag_count = 0;
        
        // Obtener los tags directamente de la base de datos
        $this->db->select('tags');
        $this->db->from('message_thread mt');
        
        // Condiciones de receptor
        $this->db->group_start();
        $this->db->where('mt.receiver_id', $user_id);
        $this->db->where('mt.receiver_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de emisor
        $this->db->or_group_start();
        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        $this->db->group_end();
        
        // Condiciones de CC
        $this->db->or_group_start();
        $this->db->where('mt.cc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.cc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Condiciones de BCC
        $this->db->or_group_start();
        $this->db->where('mt.bcc_users_ids LIKE', '%"' . $user_id . '"%');
        $this->db->where('mt.bcc_groups LIKE', '%"' . $user_group . '"%');
        $this->db->group_end();
        
        // Ejecutar la consulta para obtener las etiquetas
        $tags_query = $this->db->get();
        $tags_results = $tags_query->result_array();
        
        foreach ($tags_results as $tags_row) {
            $current_tags = json_decode($tags_row['tags'], true); // Decodificar los tags JSON
            if (!empty($current_tags)) {
                // Contar los tags específicos
                foreach ($current_tags as $tag) {
                    if (isset($message_counts[$tag])) {
                        $message_counts[$tag]++; // Incrementar el contador de ese tag
                    }
                    // Verificar si el tag coincide con param1
                    if ($tag === $param1) {
                        $active_tag_count++; // Incrementar el contador de tags activos
                    }
                }
            }
        }

        $this->db->select('name, badge, label');
        $this->db->from('message_tag');
        $query_message_tag = $this->db->get();
        $result_message_tag = $query_message_tag->result_array();

        // Obtener los mensajes y los hilos de mensajes
        $this->db->select('m.message_id, m.message_thread_code, m.message, m.sender_id, m.sender_group, 
         has_image, has_video, has_audio, has_document, has_text, mt.message_thread_id, mt.message_thread_code, mt.receiver_id, mt.receiver_group, mt.last_message_timestamp, 
        mt.last_sender_id, mt.last_sender_group, umts.is_favorite, umts.is_draft, umts.is_trash, mt.subject');
        $this->db->from('message m');
        $this->db->join('message_thread mt', 'm.message_thread_code = mt.message_thread_code');

        // Unión adicional con la tabla user_message_thread_status
        $this->db->join('user_message_thread_status umts', 'mt.message_thread_code = umts.message_thread_code');


        $this->db->where('mt.sender_id', $user_id);
        $this->db->where('mt.sender_group', $user_group);
        // Ahora is_draft, is_trash y is_favorite están en la tabla user_message_thread_status (umts)
        $this->db->where('umts.user_id', $user_id);
        $this->db->where('umts.user_group', $user_group);
        $this->db->where('umts.is_draft', 0);
        $this->db->where('umts.is_trash', 0);

        // Ejecutar la consulta
        $query = $this->db->get();
        $messages = $query->result_array();
    
        $grouped_messages = [];
    
        foreach ($messages as $message) {
            $thread_code = $message['message_thread_code'];
    
            // Consulta para obtener los detalles del sender
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['sender_group'] . '_details');
            $this->db->where($message['sender_group'] . '_id', $message['sender_id']);
            $sender_details_query = $this->db->get();
            $sender_details = $sender_details_query->row_array();
    
            // Último remitente
            $this->db->select('firstname, lastname, photo');
            $this->db->from($message['last_sender_group'] . '_details');
            $this->db->where($message['last_sender_group'] . '_id', $message['last_sender_id']);
            $last_sender_details_query = $this->db->get();
            $last_sender_details = $last_sender_details_query->row_array();
    
            $message['sender_firstname'] = $sender_details['firstname'];
            $message['sender_lastname'] = $sender_details['lastname'];
            $message['sender_photo'] = $sender_details['photo'];

            $message['last_sender_firstname'] = $last_sender_details['firstname'];
            $message['last_sender_lastname'] = $last_sender_details['lastname'];
            $message['last_sender_photo'] = $last_sender_details['photo'];

            // Inicializar si no existe
            if (!isset($grouped_messages[$thread_code])) {
                $grouped_messages[$thread_code] = $message;
                $grouped_messages[$thread_code]['tags'] = [];
                $grouped_messages[$thread_code]['has_text'] = !empty($message['has_text']);
                $grouped_messages[$thread_code]['has_image'] = !empty($message['has_image']);
                $grouped_messages[$thread_code]['has_video'] = !empty($message['has_video']);
                $grouped_messages[$thread_code]['has_audio'] = !empty($message['has_audio']);
                $grouped_messages[$thread_code]['has_document'] = !empty($message['has_document']);
                $grouped_messages[$thread_code]['new_message_count'] = 0;
            }
    
            // Verificar si hay attachments
            if ($message['has_text'] == 1) {
                $grouped_messages[$thread_code]['has_text'] = true;
            }
            if ($message['has_image'] == 1) {
                $grouped_messages[$thread_code]['has_image'] = true;
            }
            if ($message['has_video'] == 1) {
                $grouped_messages[$thread_code]['has_video'] = true;
            }
            if ($message['has_audio'] == 1) {
                $grouped_messages[$thread_code]['has_audio'] = true;
            }
            if ($message['has_document'] == 1) {
                $grouped_messages[$thread_code]['has_document'] = true;
            }
           
    
            // Verificar la cantidad de mensajes no leídos en este thread
            $this->db->select('new_messages_count');
            $this->db->from('user_message_status');
            $this->db->where('user_id', $user_id);
            $this->db->where('message_thread_code', $thread_code);
        
            $new_messages_query = $this->db->get();
            $new_messages_result = $new_messages_query->row_array();
            $grouped_messages[$thread_code]['new_message_count'] = $new_messages_result ? $new_messages_result['new_messages_count'] : 0;
    
            // Actualizar si el timestamp es más reciente
            if (strtotime($message['last_message_timestamp']) > strtotime($grouped_messages[$thread_code]['last_message_timestamp'])) {
                $grouped_messages[$thread_code] = $message;
            }
        }
    
        // Obtener los tags y subjects
        foreach ($grouped_messages as $thread_code => &$grouped_message) {
            $this->db->select('tags');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $tags_query = $this->db->get();
            $tags_results = $tags_query->result_array();
            foreach ($tags_results as $tags_row) {
                $current_tags = json_decode($tags_row['tags'], true);
                if (!empty($current_tags)) {
                    $grouped_message['tags'] = array_unique(array_merge($grouped_message['tags'], $current_tags));
                }
            }
    
            $this->db->select('subject');
            $this->db->from('message_thread');
            $this->db->where('message_thread_code', $thread_code);
            $this->db->limit(1);
            $subject_query = $this->db->get();
            if ($subject_row = $subject_query->row_array()) {
                $grouped_message['subject'] = $subject_row['subject'];
            }
        }
    
        usort($grouped_messages, function ($a, $b) {
            if ($a['is_favorite'] == $b['is_favorite']) {
                return strtotime($b['last_message_timestamp']) - strtotime($a['last_message_timestamp']);
            }
            return $b['is_favorite'] - $a['is_favorite'];
        });
    
        $page_data['messages'] = array_values($grouped_messages);
        $page_data['unread_count'] = $unread_count; 
        $page_data['sent_count'] = $sent_count;  
        $page_data['received_count'] = $received_count;  
        $page_data['draft_count'] = $draft_count;  
        $page_data['trash_count'] = $trash_count;
        $page_data['favorite_count'] = $favorite_count; 
        $page_data['result_message_tag'] = $result_message_tag; 
        $page_data['message_counts'] = $message_counts; 
        $page_data['active_tag'] = $param1; 
        $page_data['active_tag_count'] = $active_tag_count;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('message')) . ' - ' . ucfirst(get_phrase('sent')),
                'url' => base_url('index.php?admin/message')
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'message_sent';
        $page_data['page_title'] = 'message_sent';
    
        $this->load->view('backend/index', $page_data);
    }


    function message_settings($param1 = '', $param2 = '', $param3 = '', $param4 = '') {
        // Verificar que el usuario esté logueado
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $user_id = $this->session->userdata('login_user_id');
        $user_group = $this->session->userdata('login_type');
        
        if ($param1 == 'favorite') {
            if ($param4 == 'add') {
                $this->db->where('message_thread_code', $param3);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_favorite' => 1,
                    'favorite_timestamp' => date('Y-m-d H:i:s') 
                ));
            } elseif ($param4 == 'remove') {
                $this->db->where('message_thread_code', $param3);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_favorite' => 0,
                    'favorite_timestamp' => null 
                ));
            }
            if ($param2 == 'message_read') {
                redirect(base_url() . 'index.php?admin/' . $param2 . '/' . $param3, 'refresh');
            } else if ($param2 == 'message') {
                redirect(base_url() . 'index.php?admin/' . $param2, 'refresh');
            }
        }

        if ($param1 == 'draft') {
            if ($param4 == 'add') {
                $this->db->where('message_thread_code', $param3);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_draft' => 1,
                    'draft_timestamp' => date('Y-m-d H:i:s') 
                ));
                redirect(base_url() . 'index.php?admin/message_draft/', 'refresh');
            } elseif ($param4 == 'remove') {
                $this->db->where('message_thread_code', $param3);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_draft' => 0,
                    'draft_timestamp' => null
                ));
                redirect(base_url() . 'index.php?admin/message/', 'refresh');
            }
          
        }

        if ($param1 == 'trash') {
            if ($param4 == 'add') {
                $this->db->where('message_thread_code', $param3);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 1,
                    'trash_timestamp' => date('Y-m-d H:i:s') 
                ));
                redirect(base_url() . 'index.php?admin/message_trash/', 'refresh');
            } elseif ($param4 == 'remove') {
                $this->db->where('message_thread_code', $param3);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 0,
                    'trash_timestamp' => null
                ));
                redirect(base_url() . 'index.php?admin/message/', 'refresh');
            }
        }
        if ($param3 == 'trash_for_user_message_thread') {
            if ($param2 == 'add') {
                $this->db->where('message_thread_code', $param1);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 1,
                    'is_draft' => 0,
                    'is_favorite' => 0,
                    'trash_timestamp' => date('Y-m-d H:i:s'),
                    'draft_timestamp' => null,
                    'favorite_timestamp' => null,
                ));
                redirect(base_url() . 'index.php?admin/message_trash/', 'refresh');
            } elseif ($param2 == 'remove') {
                $this->db->where('message_thread_code', $param1);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 0,
                    'is_draft' => 0,
                    'is_favorite' => 0,
                    'trash_timestamp' => null,
                    'draft_timestamp' => null,
                    'favorite_timestamp' => null,
                ));
                redirect(base_url() . 'index.php?admin/message/', 'refresh');
            }
        }
        if ($param3 == 'trash_for_all_user_message_thread_owner') {
            if ($param2 == 'add') {
                // Actualizar la tabla message_thread
                $this->db->where('message_thread_code', $param1);
                $this->db->update('message_thread', array(
                    'is_trash' => 1,
                    'trash_timestamp' => date('Y-m-d H:i:s')
                ));
        
                // Actualizar la tabla user_message_thread_status
                $this->db->where('message_thread_code', $param1);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 1,
                    'is_favorite' => 0,
                    'trash_timestamp' => date('Y-m-d H:i:s'),
                    'favorite_timestamp' => null,
                    'is_trash_by_user_id' => $user_id,
                    'is_trash_by_user_group' => $user_group
                ));

                // redirect(base_url() . 'index.php?admin/message_read/' . $param1, 'refresh');
                redirect(base_url() . 'index.php?admin/message_trash/', 'refresh');
            } elseif ($param2 == 'remove') {
                // Actualizar la tabla message_thread
                $this->db->where('message_thread_code', $param1);
                $this->db->update('message_thread', array(
                    'is_trash' => 0,
                    'trash_timestamp' => null
                ));
        
                // Actualizar la tabla user_message_thread_status
                $this->db->where('message_thread_code', $param1);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 0,
                    'is_favorite' => 0,
                    'trash_timestamp' => null,
                    'favorite_timestamp' => null,
                    'is_trash_by_user_id' => null,
                    'is_trash_by_user_group' => null
                ));

                 // redirect(base_url() . 'index.php?admin/message_read/' . $param1, 'refresh');
                 redirect(base_url() . 'index.php?admin/message/', 'refresh');
            }
        }
        
        if($param1 == 'user_message_status') {
            if ($param3 == 'read') {
                $this->db->where('message_thread_code', $param2);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_status', array(
                    'new_messages_count' => 0,
                    'last_seen_timestamp' => date('Y-m-d H:i:s') 
                ));
            } elseif ($param3 == 'unread') {
                $this->db->where('message_thread_code', $param2);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_status', array(
                    'new_messages_count' => 1,
                    'last_seen_timestamp' => null 
                ));
            }
            redirect(base_url() . 'index.php?admin/'.$param4, 'refresh');
        }

        if ($param1 == 'move_to') {
            if ($param3 == 'draft') {
                $this->db->where('message_thread_code', $param2);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_draft' => 1,
                    'draft_timestamp' => date('Y-m-d H:i:s'),
                    'is_trash' => 0,
                    'trash_timestamp' => null
                ));
            } elseif ($param3 == 'trash') {
                $this->db->where('message_thread_code', $param2);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 1,
                    'trash_timestamp' => date('Y-m-d H:i:s'),
                    'is_favorite' => 0,
                    'favorite_timestamp' => null,
                    'is_draft' => 0,
                    'draft_timestamp' => null
                ));
            } elseif ($param3 == 'inbox') {
                $this->db->where('message_thread_code', $param2);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 0,
                    'is_draft' => 0,
                    'trash_timestamp' => null,
                    'favorite_timestamp' => null,
                    'draft_timestamp' => null,
                    'is_favorite' => 0,
                ));
            }
            if ($param4 == 'message_read') {
                redirect(base_url() . 'index.php?admin/' . $param4 . '/' . $param2, 'refresh');
            } else if ($param4 == 'message') {
                redirect(base_url() . 'index.php?admin/' . $param4, 'refresh');
            } else if ($param4 == 'message_trash') {
                redirect(base_url() . 'index.php?admin/' . $param4, 'refresh');
            } else if ($param4 == 'message_draft') {
                redirect(base_url() . 'index.php?admin/' . $param4, 'refresh');
            } 
        }

        if ($param1 == 'delete_message_thread_bulk') {
            $message_thread_codes = explode('-', $param3); 
        
            foreach ($message_thread_codes as $message_thread_code) {
                $this->db->where('message_thread_code', $message_thread_code);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_trash' => 1,
                    'trash_timestamp' => date('Y-m-d H:i:s'),
                    'is_draft' => 0,
                    'draft_timestamp' => null,
                    'is_favorite' => 0,
                    'favorite_timestamp' => null
                ));
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Conversaciones enviadas a la papelera!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        
            redirect(base_url() . 'index.php?admin/' . $param2 . '/', 'refresh');
        }

        if ($param1 == 'draft_message_thread_bulk') {
            $message_thread_codes = explode('-', $param3); 
        
            foreach ($message_thread_codes as $message_thread_code) {
                $this->db->where('message_thread_code', $message_thread_code);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_draft' => 1,
                    'draft_timestamp' => date('Y-m-d H:i:s'),
                    'is_trash' => 0,
                    'trash_timestamp' => null
                ));
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Conversaciones archivadas correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        
            redirect(base_url() . 'index.php?admin/' . $param2 . '/', 'refresh');
        }

        if ($param1 == 'read_message_thread_bulk') {
            $message_thread_codes = explode('-', $param3); 
        
            foreach ($message_thread_codes as $message_thread_code) {
                $this->db->where('message_thread_code', $message_thread_code);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_status', array(
                    'new_messages_count' => 0,
                    'last_seen_timestamp' => date('Y-m-d H:i:s') 
                ));
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Conversaciones marcadas como vistas correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        
            redirect(base_url() . 'index.php?admin/' . $param2 . '/', 'refresh');
        }

        if ($param1 == 'unread_message_thread_bulk') {
            $message_thread_codes = explode('-', $param3); 
        
            foreach ($message_thread_codes as $message_thread_code) {
                $this->db->where('message_thread_code', $message_thread_code);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_status', array(
                    'new_messages_count' => 1,
                    'last_seen_timestamp' => null
                ));
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Conversaciones marcadas como no vistas correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        
            redirect(base_url() . 'index.php?admin/' . $param2 . '/', 'refresh');
        }

        if ($param1 == 'add_favorite_message_thread_bulk') {
            $message_thread_codes = explode('-', $param3); 
        
            foreach ($message_thread_codes as $message_thread_code) {
                $this->db->where('message_thread_code', $message_thread_code);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_favorite' => 1,
                    'favorite_timestamp' => null
                ));
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Conversaciones marcadas como favorito correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        
            redirect(base_url() . 'index.php?admin/' . $param2 . '/', 'refresh');
        }

        if ($param1 == 'remove_favorite_message_thread_bulk') {
            $message_thread_codes = explode('-', $param3); 
        
            foreach ($message_thread_codes as $message_thread_code) {
                $this->db->where('message_thread_code', $message_thread_code);
                $this->db->where('user_id', $user_id);
                $this->db->where('user_group', $user_group);
                $this->db->update('user_message_thread_status', array(
                    'is_favorite' => 0,
                    'favorite_timestamp' => null
                ));
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => 'Conversaciones marcadas como no favorito correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        
            redirect(base_url() . 'index.php?admin/' . $param2 . '/', 'refresh');
        }
    
    }
    
    
    
    
    
    

    function events($param1 = '', $param2 = '', $param3 = '', $param4 = '', $param5 = '', $param6 = '', $param7 = '', $param8 = '', $param9 = '', $param10 = '', $param11 = '', $param12 = '', $param13 = '',  $param14 = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
    
        if ($param1 == 'create') {
            // Decodificamos los parámetros de la URL
            $event_title = urldecode($param2); 
            $event_body = urldecode($param3);
            $event_start = urldecode($param4); // Fecha de inicio
            $event_end = urldecode($param5); // Fecha de fin (puede ser null)
            $visible_to = urldecode($param6); // Corresponde a user_type
            $visible_to_category = urldecode($param7); // Corresponde a user_option

            // $visible_to_id = urldecode($param7); // Corresponde a visibility_id
            if(urldecode($param8) == 'null') {
                $visible_to_id = null;
            } else {
                $visible_to_id = urldecode($param8);
            }

            $AllDay = urldecode($param9); // Obtener el valor de allDay
            $event_color = urldecode($param10); // Color del evento
            $visibility_for_creator = urldecode($param11);
            $visible_edit = urldecode($param12);
            $visible_delete = urldecode($param13);
            $event_type = urldecode($param14);

            $created_by_user_id = $this->session->userdata('login_user_id'); 
            $created_by_group = $this->session->userdata('login_type');
            $created_at = date('Y-m-d H:i:s'); 
    
            // Validamos los parámetros
            if (empty($event_title) || empty($event_start)) {
                echo "Nombre del evento o fecha están vacíos.";
                return;
            }
    
            // Preparamos los datos para la inserción
            $event_data = array(
                'title' => $event_title,
                'body' => $event_body,
                'created_by_user_id' => $created_by_user_id,
                'created_by_group' => $created_by_group,
                'created_at' => $created_at, // Establece la fecha de creación
                'color' => $event_color,
                'type' => $event_type,
                'status_id' => 1
            );

            // Verificamos si es un evento de todo el día
            if ($AllDay === 'true') {
                // Si es allDay, insertamos solo en 'day' y ponemos null en 'start' y 'end'
                $event_data['date'] = $event_start; // Se inserta la fecha sin hora
                $event_data['start'] = null; // Null en start
                $event_data['end'] = null; // Null en end
                $event_data['allDay'] = true;
            } else if ($event_end === 'null') {
                // Si no es allDay, insertamos en 'start' y 'end' según corresponda
                $event_data['start'] = $event_start; // Usa la fecha de inicio
                $event_data['end'] = null; // Si hay fecha de fin, la insertamos, si no, null
                $event_data['date'] = null; // Null en 
                $event_data['allDay'] = false;
            } else {
                  // Si no es allDay, insertamos en 'start' y 'end' según corresponda
                  $event_data['start'] = $event_start; // Usa la fecha de inicio
                  $event_data['end'] = $event_end; // Si hay fecha de fin, la insertamos, si no, null
                  $event_data['date'] = null; // Null en 
                  $event_data['allDay'] = false;
            }
    
            if (!$this->db->insert('events', $event_data)) {
                echo "Error al insertar el evento: " . $this->db->last_query();
                return;
            }

         
    
            $event_id = $this->db->insert_id(); // ID del evento recién creado
    
            // Insertar en la tabla 'event_visibility'
            $visibility_data = array(
                'event_id' => $event_id,
                'visible_to' => $visible_to, 
                'visible_to_category' => $visible_to_category,
                'visible_to_id' => $visible_to_id,
                'created_by_user_id' => $created_by_user_id,
                'created_by_group' => $created_by_group,
                'created_at' => $created_at
            );

            if ($visibility_for_creator === 'true') {
                $visibility_data['visibility_for_creator'] = true;
            } else if ($visibility_for_creator === 'false') {
                $visibility_data['visibility_for_creator'] = false;
            }

            if ($visible_edit === 'true') {
                $visibility_data['visible_edit'] = true;
            } else if ($visible_edit === 'false') {
                $visibility_data['visible_edit'] = false;
            }

            if ($visible_delete === 'true') {
                $visibility_data['visible_delete'] = true;
            } else if ($visible_delete === 'false') {
                $visibility_data['visible_delete'] = false;
            }
    
            if (!$this->db->insert('event_visibility', $visibility_data)) {
                echo "Error al insertar visibilidad: " . $this->db->last_query();
                return;
            }
    
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('event_added_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        }

        if ($param1 == 'update') {
            $event_id = $param2;
        
            // Obtener los valores enviados desde el formulario
            $title = $this->input->post('title');
            $body = $this->input->post('body');
            $date = $this->input->post('date');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $color = $this->input->post('color');
            $type = $this->input->post('type');

            $visibility_for_creator = $this->input->post('content-visibility-for-creator-modal') ? 1 : 0;
            $visible_edit = $this->input->post('content-visible-edit-modal') ? 1 : 0;
            $visible_delete = $this->input->post('content-visible-delete-modal') ? 1 : 0;

            $data = array('title' => $title, 'body' => $body, 'color' => $color, 'type' => $type);

            $visible_to = $this->input->post('users-list-modal');

            if (empty($this->input->post('user-admin-option-modal'))) {
                $visible_to_category = $this->input->post('user-student-option-modal');

                if($visible_to_category == 'All') {
                    $visible_to_id = null;
                } else if ($visible_to_category == 'PerClass') {
                    $visible_to_id = $this->input->post('content-class-list-modal');
                } else if ($visible_to_category == 'PerSection') {
                    $visible_to_id = $this->input->post('content-sections-list-modal');
                }
            } else {
                $visible_to_category = $this->input->post('user-admin-option-modal');

                if($visible_to_category == 'All') {
                    $visible_to_id = null;
                } else if ($visible_to_category == 'PerUser') {
                    $visible_to_id = $this->input->post('content-admin-list-modal');
                } 
            }

            $dataEventVisibility = array(
                'visible_to' => $visible_to,
                'visible_to_category' => $visible_to_category,
                'visible_to_id' => $visible_to_id,
                'visibility_for_creator' => $visibility_for_creator,
                'visible_edit' => $visible_edit,
                'visible_delete' => $visible_delete
            );


                                     
        
            // Verificar si date no es nulo ni vacío
            if (!empty($date)) {
                // Actualiza solo con date (evento de un solo día)
                $data['date'] = $date;
                // Limpiar los valores de start y end si existen en la tabla
                $data['start'] = null;
                $data['end'] = null;
        
            } elseif (!empty($start) && empty($end)) {
                // Si start tiene valor y end es vacío (evento de una sola hora)
                $data['start'] = $start;
                $data['end'] = null; // Aseguramos que 'end' quede vacío en la base de datos
                // Limpiar el valor de date si existe en la tabla
                $data['date'] = null;
        
            } elseif (!empty($start) && !empty($end)) {
                // Si start y end tienen valor (evento con hora de inicio y fin)
                $data['start'] = $start;
                $data['end'] = $end;
                // Limpiar el valor de date si existe en la tabla
                $data['date'] = null;
            }
        
            // Actualizar la tabla 'events' donde event_id coincide
            $this->db->where('event_id', $event_id);
            $this->db->update('events', $data);

            $this->db->where('event_id', $event_id);
            $this->db->update('event_visibility', $dataEventVisibility);
        
            // Redirigir después de actualizar
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('event_updated_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        }

        if ($param1 == 'disable') {
            $event_id = $param2;

            $this->db->where('event_id', $event_id);
            $this->db->update('events', array('status_id' => 0));
        
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('event_disabled_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));       
          
        
            redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        }

        if ($param1 == 'enable') {
            $event_id = $param2;

            $this->db->where('event_id', $event_id);
            $this->db->update('events', array('status_id' => 1));
        
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('event_enabled_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));       
          
        
            redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        }
        
        
    }


    public function printReportCardES($student_id = '', $section_id = '')
    {
        $student_data = $this->crudStudent->get_student_info2($student_id);
        $section = $this->crud_model->get_section_info4($section_id);
        $class_name = $this->crud_model->get_class_name($section['class_id']);
        $academic_period = $this->crud_model->get_academic_period_name_per_section2($section['section_id']);
        $section_letter_name = $section['letter_name'];
        $subjects = $this->crudSubject->get_subjects_by_section2($section_id);
        $shift = ($section['shift_id'] == 1) ? ucfirst(get_phrase('morning')) : ucfirst(get_phrase('afternoon'));
        $academic_period_id = $section['academic_period_id'];

        // Inicializamos las variables
        $absent_count = 0;
        $justified_absent_count = 0;

        // Consultar la tabla attendance_student
        $this->db->select('status');
        $this->db->where('student_id', $student_id);
        $this->db->where('section_id', $section_id);
        $query = $this->db->get('attendance_student');
        $attendance_records = $query->result_array();

        // Sumar ausentes e injustificados
        foreach ($attendance_records as $record) {
            if ($record['status'] == 2) {
                $absent_count++;
            } elseif ($record['status'] == 4) {
                $justified_absent_count++;
            }
        }

        // Si no se encontraron registros, buscar en attendance_student_history
        if ($absent_count == 0 && $justified_absent_count == 0) {
            $this->db->select('status');
            $this->db->where('student_id', $student_id);
            $this->db->where('section_id', $section_id);
            $this->db->where('academic_period_id', $section['academic_period_id']);
            $query_history = $this->db->get('attendance_student_history');
            $attendance_records_history = $query_history->result_array();

            foreach ($attendance_records_history as $record) {
                if ($record['status'] == 2) {
                    $absent_count++;
                } elseif ($record['status'] == 4) {
                    $justified_absent_count++;
                }
            }
        }

        // Determinar la condición de asistencia
        $total_absences = $absent_count + $justified_absent_count;
        $attendance_condition = ($total_absences > 25) ? 'T.E.A' : 'REGULAR';

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libreta de Calificaciones del Estudiante</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            margin: 0;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 10px;
            border-bottom: 0.5px solid #000;
        }

        .school-name {
            font-weight: bold;
            font-size: 12px;
        }

        .student-info {
            justify-content: space-between;
            font-size: 12px;
             text-align: center;
                 line-height: 1.8;
            margin-top: 250px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
            font-size: 10px;
        }

        .main-table th {
            background-color: #fff;
            font-weight: bold;
        }

        .subject-column {
            text-align: left !important;
            width: 25%;
        }

        .eval-column {
            width: 30px;
        }

        .bottom-section {
        position: relative;
        bottom: 0;
        width: 100%;
        }
        
        .bottom-section table {
            background-color: white;
        }
        
        /* Ensure second page starts on new page */
        .cover-page {
            page-break-before: always;
        }

        .attendance-box {
            border: 1px solid #000;
            padding: 5px;
            width: 150px;
        }

        .observations-box {
            border: 1px solid #000;
            flex-grow: 1;
            padding: 5px;
        }

      
        .school-logo {
            width: 60px !important;
            height: 80px !important;
            margin-top: 30px;
            margin-bottom: 0px;
        }

        .institution-header {
            margin-bottom: 40px;
            line-height: 1.5;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0;
        }

        .coloquio-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .coloquio-table th, .coloquio-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .coloquio-title {
            font-weight: bold;
            margin: 30px 0 10px;
        }

        .hr {
            width: 100%;
            height: 2px;
            color: black;
        }

        .left-side {
            width: 41%;
            padding: 0px 14px !important;
            border: 2px solid #000;
            margin-left: 32px;
            margin-top: 40px;
        }

        .right-side {
            width: 51%;
            text-align: center;
              border: 2px solid #000 !important;
                      margin-right: 32px;
                             margin-left: 30px;
               margin-top: 40px;
        }

        .evaluation-table {
            width: 100%;
            border-collapse: collapse;
        }

        .evaluation-table td {
            border: 1px solid black;
            padding: 1px;
            text-align: left;
        } .evaluation-table th {
            border: 1px solid black;
            text-align: center;
        }

        .evaluation-table th {
            font-weight: bolder !important;
            font-size: 9px !important;
        }

        .period-title {
            font-weight: bold;
            font-size: 12px;
            margin: 20px 0 5px;
            text-align: center;
        }

        .school-logo {
            width: 120px;
            margin-bottom: 5px;
        }

        .header-text {
            text-align: center;
            margin-bottom: 40px;
        }

        .header-text .bold {
            font-weight: bold;
        }

        .report-title {
            font-size: 16px;
            font-weight: bolder;
            text-align: center;
            margin: 55px 0;
            line-height: 1.5;
        }
      
    </style>
</head>
<body>
    <!-- First Page -->
    <div class="header">
        <div>
            <div class="school-name" style="margin-bottom: 3px; font-size: 12px;">NOMBRE DE LA INSTITUCIÓN</div>
            <div style="margin-bottom: 5px; font-size: 12px;">Dirección</div>
        </div>
        <div style="text-align: right;">
            <div style="font-weight: bolder; font-size: 14px;">INFORME DE PROGRESO ESCOLAR</div>
           
        </div>
    </div>

    <div style="margin-bottom: 12px;  border-bottom: 0.5px solid #000;">
            <div style="text-align: left; margin-top: -12px; font-size: 12px;  margin-bottom: 5px;">
                <div>
                    Plan: <span style="font-weight: bold;"> Sin Modalidad </span><br>
                </div>
                <div style="margin-top: 3px;">
                    Estudiante: <span style="font-weight: bold;">' . $student_data['lastname'] . ', ' . $student_data['firstname'] . ' </span>
                    <span style="margin-left: 20px;"> Tipo y N° doc: <span style="font-weight: bold;">DNI - 47475089 </span></span>
                </div>
            </div>
           
        <div style="text-align: right; margin-top: -37px; margin-bottom: 5px;">
            <div style="font-size: 12px;">
                Curso: <span style="font-weight: bold; margin-right: 5px;">' . $section['class_id'] . '</span>  División: <span style="font-weight: bold; margin-right: 5px;">' . ucfirst($section['letter_name']) . '</span> Turno: <span style="font-weight: bold; margin-right: 5px;">' . $shift . '</span> Ciclo Lectivo: <span style="font-weight: bold; margin-right: 5px;">' . $academic_period . '</span><br>
                <div style="margin-top: 3px; margin-right: 5px;">
                    Versión IPE: <span style="font-weight: bold;">Preliminar</span>
                </div>
            </div>
        </div>
    </div>

    

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2" class="subject-column text-center" style="font-size: 12px !important; font-weight: bolder !important; vertical-align: middle !important; text-align: center !important;">Espacios Curriculares (E.C.)</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 1</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 2</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 3</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 4</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 5</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 6</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 7</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">JIIS 1</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">JIIS 2</th>
                <th style="font-size: 11px !important; font-weight: bolder !important;">Coloquio Dic.</th>
                <th style="font-size: 11px !important; font-weight: bolder !important;">Coloquio Feb.</th>
                <th style="font-size: 11px !important; font-weight: bolder !important;">Prom. Final</th>
            </tr>
            <tr>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>';
    
        foreach ($subjects as $subject) {
            // Obtener las marcas del estudiante para la asignatura
            $marks = $this->crudMark->get_marks_by_student_subject2($student_id, $subject['subject_id'], $section['academic_period_id']);
    
            // Inicializar un array para almacenar los marks organizados por exam_type_id
            $marks_by_exam_type = array_fill(1, 21, ''); // Rellenar todas las posiciones con valores vacíos
    
            // Rellenar los valores existentes en $marks_by_exam_type
            foreach ($marks as $mark) {
                if ($mark['exam_type_id'] >= 1 && $mark['exam_type_id'] <= 21) {
                    // Validar si mark_obtained no está vacío
                    if (!empty($mark['mark_obtained'])) {
                        $mark_value = floatval($mark['mark_obtained']);
                        // Si el valor es 0.00, establecer como vacío; si no, usar el valor correspondiente
                        $marks_by_exam_type[$mark['exam_type_id']] = ($mark_value === 0.00) ? '' : $mark_value;
                    }
                }
            }
        
            // Generar la fila HTML
            $html .= '<tr>';
            $html .= '<td class="subject-column">' . $subject['name'] . '</td>';
        
            for ($exam_type_id = 1; $exam_type_id <= 21; $exam_type_id++) {
                // Usar directamente el valor almacenado en $marks_by_exam_type
                $value = $marks_by_exam_type[$exam_type_id];
                $html .= '<td>' .  ($value !== '' ? $value : '') . '</td>';
            }
        
            $html .= '</tr>';
        }

    



        $html .= '
        </tbody>
    </table>

    <div class="bottom-section" style="display: flex; gap: 10px; margin-top: 30px;">
        <div style="width: 100px;">
           <table style="width: 80%; border-collapse: collapse;">
                <tr>
                    <th rowspan="2" style="border: 1px solid #000; padding: 7px 4px; font-size: 7px; font-weight: bolder; text-align: center;">
                        INASISTENCIAS<br>DIARIAS
                    </th>
                    <td style="border: 1px solid #000; font-size: 7px; padding: 1px 4px; font-weight: bolder; text-align: center; width: 25%;">
                        Just.
                    </td>
                    <td style="border: 1px solid #000; font-size: 7px; padding: 1px 4px; font-weight: bolder; text-align: center; width: 25%;">
                        Inj.
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000; font-size: 7px; text-align: center;">
                        ' . $justified_absent_count .'
                    </td>
                    <td style="border: 1px solid #000; font-size: 7px; text-align: center;">
                       ' . $absent_count .'
                    </td>
                </tr>
                <tr>
                    <td colspan="1" style="border: 1px solid #000; padding: 3px 0px; font-size: 7px; text-align: center; font-weight: bold;">
                        ESTADO
                    </td>
                    <td colspan="2" style="border: 1px solid #000; padding: 3px 0px; font-size: 7px; text-align: center;">
                        ' . $attendance_condition . '
                    </td>
                </tr>
                
            </table>
        </div>
        
        <div style="flex: 1; padding: 8px;  border: 2px solid #000; margin-left: 3px; margin-top: -10px;">
            <div style="font-weight: bolder; margin-top: -4px; padding-bottom: 4px; text-align: center !important; margin-left: -8px; margin-right: -8px; border-bottom: 1px solid #000; font-size: 7px;">OBSERVACIONES</div>
            <div style="min-height: 40px;"></div>
        </div>
        


        <div style="flex: 1; border: 2px solid #000; margin-left: -7px; margin-top: -10px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 7px; height: 100%;">
                <thead>
                    <tr>
                        <th style="text-align: center; padding: 4px 0px; font-weight: bolder; border-bottom: 0px solid #000; colspan="3">
                            E.C. EN CONTRATURNO EN ESTADO T.E.A.
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border-top: 1px solid #000;"></td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #000;"></td>

                    <tr>
                    </tr>
                        <td style="border-top: 1px solid #000;"></td>
                    </tr>
                
                </tbody>
            </table>
        </div>

        
     

         <div style="flex: 1; border: 2px solid #000; margin-left: -7px; margin-top: -10px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 7px; height: 100%;">
                <thead>
                    <tr>
                        <th style="text-align: center; padding: 4px 0px; font-weight: bolder; border-bottom: 1px solid #000; colspan="3">
                            E.C. PREVIOS.
                        </th>
                    </tr>
                </thead>
                <tbody>';

                $rowCount = 0;

                // Recorre las asignaturas y sus marcas
                foreach ($subjects as $subject) {
                    // Obtener las marcas para el subject con exam_type_id = 21
                    $marks = $this->crudMark->get_marks_by_student_subject3($student_id, $subject['subject_id'], $section['academic_period_id']);
                
                    foreach ($marks as $mark) {
                        if ($mark['exam_type_id'] == 21 && ($mark['date'] !== null && $mark['date'] !== '' && $mark['mark_obtained'] < 7)) {
                            // Convertir `mark_obtained` a entero y verificar que sea mayor a 0
                            $mark_obtained = intval($mark['mark_obtained']);
                            if ($mark_obtained > 0) {
                                $rowCount++; // Incrementa el contador de filas generadas
                
                                // Generar la fila HTML
                                $html .= '<tr style="text-align: center;">';
                                $html .= '<td style="border-top: 1px solid #000;">' . htmlspecialchars($subject['name'])
                                . '&nbsp; - &nbsp;' . $section['name'];
                                $html .= '</td> </tr>';
                            }
                        }
                    }
                }
                
                // Completar las filas faltantes hasta alcanzar el mínimo de 3
                while ($rowCount < 3) {
                    $html .= '<tr>';
                    $html .= '<td style="border-top: 1px solid #000;">&nbsp;</td>'; // Celda vacía para el nombre
                    $html .= '<td style="border-top: 1px solid #000;">&nbsp;</td>'; // Celda vacía para la nota
                    $html .= '<td style="border-top: 1px solid #000;">&nbsp;</td>'; // Celda vacía para la fecha
                    $html .= '</tr>';
                    $rowCount++;
                }
                
                $html .= '
                </tbody>
            </table>
        </div>

       
    </div>

    <div style="margin-top: 220px; text-align: center; width: 100%;">
            <div style="display: flex; justify-content: center; gap: 200px;">
                <div style="text-align: center;">
                    <div style="border-bottom: 1px dotted #000; width: 200px; margin-bottom: 5px;">
                        &nbsp;
                    </div>
                    <div style="font-size: 11px;">
                        Firma del Padre, Madre o Tutor
                    </div>
                </div>
                
                <div style="text-align: center;">
                    <div style="border-bottom: 1px dotted #000; width: 200px; margin-bottom: 5px;">
                        &nbsp;
                    </div>
                    <div style="font-size: 11px;">
                        Firma del/la Director/a
                    </div>
                </div>
            </div>
        </div>

    <!-- Second Page -->
    <div style="page-break-before: always;"></div>

    <div style="font-family: Arial, sans-serif;
            display: flex;
            justify-content: space-between;">
    
        <div class="left-side" >
            <div class="period-title">PERIODO DE EVALUACIÓN: COLOQUIO DICIEMBRE</div>
            <table class="evaluation-table" style="margin-bottom: 30px !important;">
                <tr>
                    <th rowspan="2" style="width: 11%; padding: 15px 0px !important;">DISCIPLINA</th>
                    <th rowspan="2" style="width: 8%; padding: 15px 0px !important;">FECHA</th>
                    <th colspan="2" style="width: 11%; padding: 6px 0px !important;">CALIFICACIÓN</th>
                    <th rowspan="2" style="width: 44%; padding: 15px 0px !important;">FIRMA DEL PROFESOR</th>
                </tr>
                <tr>
                    <th style="padding: 7px 5px;">N°</th>
                    <th style="padding: 7px 0px;">LETRA</th>
                </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
            </table>

            <div class="period-title">PERIODO DE EVALUACIÓN: COLOQUIO FEBRERO</div>
            <table class="evaluation-table" style="margin-bottom: 10px !important;">
                <tr>
                    <th rowspan="2" style="width: 11%; padding: 15px 0px !important;">DISCIPLINA</th>
                    <th rowspan="2" style="width: 8%; padding: 15px 0px !important;">FECHA</th>
                    <th colspan="2" style="width: 11%; padding: 6px 0px !important;">CALIFICACIÓN</th>
                    <th rowspan="2" style="width: 44%; padding: 15px 0px !important;">FIRMA DEL PROFESOR</th>
                </tr>
                <tr>
                    <th style="padding: 7px 5px;">N°</th>
                    <th style="padding: 7px 0px;">LETRA</th>
                </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
            </table>
        </div>

        <div class="right-side">
            <img src="' . base_url('assets/images/favicon2.png') . '" alt="NOMBRE DE LA INSTITUCIÓN" class="school-logo">
            
            <div class="header-text">
                <div class="" style="margin-bottom: 5px; font-weight: bolder !important;">GOBIERNO DE CÓRDOBA</div>
                <div>MINISTERIO DE EDUCACIÓN</div>
                <div>SECRETARÍA DE ESTADO DE EDUCACIÓN</div>
                <div>DIRECCIÓN GENERAL DE INSTITUTOS PRIVADOS DE ENSEÑANZAS</div>
                <br>
                <div>NOMBRE DE LA INSTITUCIÓN</div>
                <div style="font-size: 12px;">Nombre del centro educativo</div>
                <br>
                <div>Localidad: CORDOBA</div>
                <div>Departamento: CAPITAL</div>
            </div>

            <div class="report-title">
                LIBRETA DE CALIFICACIONES DEL ESTUDIANTE<br>
                PRIMER CICLO
            </div>

            <div class="student-info">
                Curso: <span style="font-weight: bolder;">';
                
                switch ($section['class_id']) {
                    case '1':
                        $html .= 'PRIMER AÑO';
                        break;
                    case '2':
                        $html .= 'SEGUNDO AÑO';
                        break;
                    case '3':
                        $html .= 'TERCER AÑO';
                        break;
                    case '4':
                        $html .= 'CUARTO AÑO';
                        break;
                    case '5':
                        $html .= 'QUINTO AÑO';
                        break;
                    case '6':
                        $html .= 'SEXTO AÑO';
                        break;
                    default:
                        echo ' '; 
                        break;
                }
                
                $html .= '</span>&nbsp;&nbsp;
                División: <span style="font-weight: bolder;">' . ucfirst($section['letter_name']) . '</span>&nbsp;&nbsp;
                Turno: <span style="font-weight: bolder;">' . $shift . '</span>
                <br>
                Estudiante: <span style="font-weight: bolder;">' . $student_data['lastname'] . ', ' . $student_data['firstname'] . '</span>
                <br>
                Tipo y N° doc: <span style="font-weight: bolder;">DNI - ' . $student_data['dni'] . '</span>
                <br>
                <span style="font-weight: bolder; font-size: 14px;">AÑO LECTIVO ' . $academic_period . '</span>
            </div>
        </div>

    </div>

    <script>window.print();</script>
</body>
</html>';

        // Mostrar el HTML
        echo $html;
    }

   
    public function printStudentTableES($section_id = '')
    {
        // Obtener datos de los estudiantes y la sección
        $student_data = $this->crudStudent->get_students_per_section($section_id);
        $section = $this->crud_model->get_section_info($section_id);
        $class_name = $this->crud_model->get_class_name($section['class_id']);
        $academic_period = $this->crud_model->get_academic_period_name_per_section($section_id);
        $section_letter_name = $this->crud_model->get_section_letter_name($section_id);
        $shift = ($section['shift_id'] == 1) ? ucfirst(get_phrase('morning')) : ucfirst(get_phrase('afternoon'));

        // Configuración de estudiantes por página
        $studentsPerPage = 13;
        $totalPages = ceil(count($student_data) / $studentsPerPage);

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Reporte de Estudiantes</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0px 0 10px 0px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }
                .info-item {
                    display: inline-block;
                    width: 23%;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .table td:nth-child(5), .table td:nth-child(6) {
                    word-wrap: break-word;
                    word-break: break-all;
                    max-width: 150px;
                }
                .text-left {
                    text-align: left;
                }
                .page-footer {
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                .page-break {
                    page-break-after: always;
                }
            </style>
        </head>
        <body>';

        // Generar encabezado común
        $header = '
        <div class="page-header">
            <div class="header-top">
                <div class="logo-container">
                    <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                </div>
                <div class="report-title">
                    <strong>Reporte de Estudiantes</strong> <br>
                    <span style="font-size: 16px;">' . date('d/m/Y') . '</span>
                </div>
            </div>
            <div class="course-info">
                <div class="info-item"><strong>Curso:</strong> ' . $class_name . '</div>
                <div class="info-item"><strong>División:</strong> ' . $section_letter_name . '</div>
                <div class="info-item"><strong>Turno:</strong> ' . $shift . '</div>
                <div class="info-item"><strong>Ciclo lectivo:</strong> ' . $academic_period . '</div>
            </div>
        </div>';

        // Paginación de estudiantes
        for ($page = 1; $page <= $totalPages; $page++) {
            if ($page > 1) {
                $html .= '<div class="page-break"></div>';
            }
            $html .= $header;
            
            $html .= '<div class="container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Apellido</th>
                                    <th>Nombre</th>
                                    <th>Género</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th>Usuario</th>
                                    <th>Fecha Nac.</th>
                                </tr>
                            </thead>
                            <tbody>';

            // Calcular el rango de estudiantes para esta página
            $startIndex = ($page - 1) * $studentsPerPage;
            $studentsOnPage = array_slice($student_data, $startIndex, $studentsPerPage);

            foreach ($studentsOnPage as $student) {
                $birthday = date('d/m/Y', strtotime($student['birthday']));
                
                $html .= '<tr>
                            <td class="text-left">' . $student['lastname'] . '</td>
                            <td class="text-left">' . $student['firstname'] . '</td>
                            <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                            <td>' . $student['dni'] . '</td>
                            <td>' . htmlspecialchars($student['email']) . '</td>
                            <td>' . $student['username'] . '</td>
                            <td>' . $birthday . '</td>
                        </tr>';
            }

            $html .= '</tbody></table></div>';

            // Pie de página con el número de página
            $html .= '<div class="page-footer">
                        <strong>Página ' . $page . ' de ' . $totalPages . '</strong>
                    </div>';
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        // Mostrar el HTML
        echo $html;
    }
        

    public function printStudentTableEN($section_id = '')
    {
        // Obtener datos de los estudiantes y secciones
        $student_data = $this->crudStudent->get_students_per_section($section_id);
        $section = $this->crud_model->get_section_info($section_id);
        $section_letter_name = $this->crud_model->get_section_letter_name($section_id);
        $class_name = $this->crud_model->get_class_name($section['class_id']);
        $academic_period = $this->crud_model->get_academic_period_name_per_section($section_id);
    
        // Turno
        $shift = ($section['shift_id'] == 1) ? ucfirst(get_phrase('morning')) : ucfirst(get_phrase('afternoon'));
    
        // Número máximo de estudiantes por página
        $studentsPerPage = 16;
        
        // Calcular el número de páginas necesarias
        $totalPages = ceil(count($student_data) / $studentsPerPage);
    
        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Student report - ' . $class_name . ' ' . $section_letter_name . ' - ' . date('d-m-Y') . '</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                .page-header {
                    padding: 5px 20px;
                    width: 100%;
                    border-bottom: 1px solid #ddd;
                }
    
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-top: -10px;
                    margin-bottom: 0px;
                }
    
                .logo {
                    width: 50px;
                    height: auto;
                }
    
                .report-title {
                    font-size: 18px;
                    text-align: right;
                }
    
                .course-info {
                    display: flex;
                    justify-content: center;
                    gap: 20px;
                    font-size: 14px;
                    margin-top: 0px;
                    margin-bottom: 5px;
                    text-align: center;
                }
    
                .table thead th {
                    vertical-align: middle;
                    text-align: center;
                    font-size: 14px;
                }
    
                .table tbody td {
                    vertical-align: middle;
                    text-align: center;
                }
    
                .page-footer {
                    font-size: 12px;
                    text-align: center;
                    padding-top: 0px;
                }
    
                @media print {
                    @page { margin: 0; size: auto; }
                    .page-header {
                        position: fixed;
                        top: 0;
                        width: 100%;
                        z-index: 100;
                    }
                    .page-footer {
                   font-size: 12px;
        text-align: center;
        padding-top: 0px;
        margin-top: 20px; 

                    }
                    .container {
                        margin-top: 135px;
                    }
                    .table {
                        margin-bottom: 0px;
                    }
                }
            </style>
        </head>
        <body>';
    
        // Encabezado común en todas las páginas
        $header = '
        <div class="page-header">
                    <div class="header-top">
                        <div class="logo-container">
                            <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                        </div>
                        <div class="report-title">
                            <strong> Student report </strong> <br>
                            <span style="font-size: 14px;">' . date('d/m/Y') . '</span>
                        </div>
                    </div>
                    <div class="course-info">
                        <div><strong>Class:</strong> ' . $class_name . '</div>
                        <div><strong>Section:</strong> ' . $section_letter_name . '</div>
                        <div><strong>Shift:</strong> ' . $shift . '</div>
                        <div><strong>Academic period:</strong> ' . $academic_period . '</div>
                    </div>
                </div>
                ';
    
        // Paginación de estudiantes
        for ($page = 1; $page <= $totalPages; $page++) {
            $html .= $header; // Agregar el encabezado en cada página
            $html .= '<div class="container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Lastname</th>
                                    <th>Firstname</th>
                                    <th>Gender</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>B. Date</th>
                                </tr>
                            </thead>
                            <tbody>';
    
            // Agregar los estudiantes correspondientes a la página actual
            $startIndex = ($page - 1) * $studentsPerPage;
            $endIndex = min($startIndex + $studentsPerPage, count($student_data));
            for ($i = $startIndex; $i < $endIndex; $i++) {
                $student = $student_data[$i];
                $birthday = date('d/m/Y', strtotime($student['birthday']));
    
                $html .= '<tr>
                            <td class="text-left">' . $student['lastname'] . '</td>
                            <td class="text-left">' . $student['firstname'] . '</td>
                            <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                            <td>' . $student['dni'] . '</td>
                            <td>' . htmlspecialchars($student['email']) . '</td>
                            <td>' . $student['username'] . '</td>
                            <td>' . $birthday . '</td>
                        </tr>';
            }
    
            $html .= '</tbody></table></div>';
    
            // Pie de página con número de página
            $html .= '<div class="page-footer">
                        <strong> Page ' . $page . ' of ' . $totalPages . ' </strong>
                    </div>';
    
            // Divisor de página (agrega un salto de página en la impresión)
            if ($page < $totalPages) {
                $html .= '<div style="page-break-after: always;"></div>';
            }
        }
    
        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';
    
        // Mostrar el HTML
        echo $html;
    }
    




    public function printAllStudentTableES()
    {
        // Obtener los datos de las secciones activas y de los estudiantes
        $sections = $this->crud_model->get_all_sections(); 
        $student_data = $this->crud_model->get_all_students_info();

        // Configuración de estudiantes por página
        $studentsPerPage = 13; 

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Reporte de Estudiantes</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                    .page-header {
                     margin-top: 5px;

                     }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                   
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0px 0 10px 0px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }
                .info-item {
                    display: inline-block;
                    width: 23%; /* Ajustar según el espacio necesario */
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                    .table td:nth-child(5), /* Email */
                    .table td:nth-child(6) { /* Usuario */
                        word-wrap: break-word;
                        word-break: break-all;
                        max-width: 150px; /* Ajusta el ancho máximo según sea necesario */
                    }


                .text-left {
                    text-align: left;
                }
                .page-footer {
                    text-align: center;
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                /* Saltos de página */
                .page-break {
                    page-break-after: always;
                }
            </style>
        </head>
        <body>';

        // Para cada sección, generamos su reporte
        foreach ($sections as $section) {
            // Obtener datos específicos de la sección
            $class_name = $this->crud_model->get_class_name($section['class_id']);
            $section_letter_name = $section['letter_name'];
            $academic_period = $this->crud_model->get_academic_period_name_per_section($section['section_id']);

            // Determinar el turno según shift_id
            $shift = '';
            if ($section['shift_id'] == 1) {
                $shift = ucfirst(get_phrase('morning'));
            } elseif ($section['shift_id'] == 2) {
                $shift = ucfirst(get_phrase('afternoon'));
            }

            // Filtrar estudiantes por sección actual
            $students_in_section = array_filter($student_data, function($student) use ($section) {
                return $student['section_id'] == $section['section_id'];
            });

            // Número total de páginas para esta sección
            $totalPages = ceil(count($students_in_section) / $studentsPerPage);

            // Encabezado para la sección (incluido en cada página)
            $header = '
            <div class="page-header">
                <div class="header-top">
                    <div class="logo-container">
                        <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                    </div>
                    <div class="report-title">
                        <strong>Reporte de Estudiantes</strong> <br>
                        <span class="text-right" style="font-size: 16px;">' . date('d/m/Y') . '</span>
                    </div>
                </div>
                <div class="course-info">
                    <div class="info-item"><strong>Curso:</strong> ' . $class_name . '</div>
                    <div class="info-item"><strong>División:</strong> ' . $section_letter_name . '</div>
                    <div class="info-item"><strong>Turno:</strong> ' . $shift . '</div>
                    <div class="info-item"><strong>Ciclo lectivo:</strong> ' . $academic_period . '</div>
                </div>
            </div>';

            // Paginación de estudiantes para esta sección
            for ($page = 1; $page <= $totalPages; $page++) {
                // Evita duplicar saltos de página innecesarios
                if ($page > 1 || $section !== reset($sections)) {
                    $html .= '<div class="page-break"></div>';
                }
                $html .= $header; // Encabezado por página
                
                // Tabla de estudiantes para la página actual
                $html .= '<div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Apellido</th>
                                        <th>Nombre</th>
                                        <th>Género</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                        <th>Usuario</th>
                                        <th>Fecha Nac.</th>
                                    </tr>
                                </thead>
                                <tbody>';

                // Calcular el rango de estudiantes para esta página
                $startIndex = ($page - 1) * $studentsPerPage;
                $studentsOnPage = array_slice($students_in_section, $startIndex, $studentsPerPage);

                foreach ($studentsOnPage as $student) {
                    $birthday = date('d/m/Y', strtotime($student['birthday']));
                    
                    $html .= '<tr>
                                <td class="text-left">' . $student['lastname'] . '</td>
                                <td class="text-left">' . $student['firstname'] . '</td>
                                <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                                <td>' . $student['dni'] . '</td>
                                <td>' . htmlspecialchars($student['email']) . '</td>
                                <td>' . $student['username'] . '</td>
                                <td>' . $birthday . '</td>
                            </tr>';
                }

                $html .= '</tbody></table></div>';

                // Pie de página con el número de página
                $html .= '<div class="page-footer">
                            <strong>Página ' . $page . ' de ' . $totalPages . '</strong>
                        </div>';
            }
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        // Mostrar el HTML
        echo $html;
    }




    public function printAllStudentTableEN()
    {
        // Obtener los datos de las secciones activas y de los estudiantes
        $sections = $this->crud_model->get_all_sections(); 
        $student_data = $this->crud_model->get_all_students_info();

        // Configuración de estudiantes por página
        $studentsPerPage = 13; 

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Reporte de Estudiantes</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                    .page-header {
                     margin-top: 5px;

                     }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                   
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0px 0 10px 0px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }
                .info-item {
                    display: inline-block;
                    width: 23%; /* Ajustar según el espacio necesario */
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                    .table td:nth-child(5), /* Email */
                    .table td:nth-child(6) { /* Usuario */
                        word-wrap: break-word;
                        word-break: break-all;
                        max-width: 150px; /* Ajusta el ancho máximo según sea necesario */
                    }


                .text-left {
                    text-align: left;
                }
                .page-footer {
                    text-align: center;
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                /* Saltos de página */
                .page-break {
                    page-break-after: always;
                }
            </style>
        </head>
        <body>';

        // Para cada sección, generamos su reporte
        foreach ($sections as $section) {
            // Obtener datos específicos de la sección
            $class_name = $this->crud_model->get_class_name($section['class_id']);
            $section_letter_name = $section['letter_name'];
            $academic_period = $this->crud_model->get_academic_period_name_per_section($section['section_id']);

            // Determinar el turno según shift_id
            $shift = '';
            if ($section['shift_id'] == 1) {
                $shift = ucfirst(get_phrase('morning'));
            } elseif ($section['shift_id'] == 2) {
                $shift = ucfirst(get_phrase('afternoon'));
            }

            // Filtrar estudiantes por sección actual
            $students_in_section = array_filter($student_data, function($student) use ($section) {
                return $student['section_id'] == $section['section_id'];
            });

            // Número total de páginas para esta sección
            $totalPages = ceil(count($students_in_section) / $studentsPerPage);

            // Encabezado para la sección (incluido en cada página)
            $header = '
            <div class="page-header">
                <div class="header-top">
                    <div class="logo-container">
                        <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                    </div>
                    <div class="report-title">
                        <strong>Student Report</strong> <br>
                        <span class="text-right" style="font-size: 16px;">' . date('d/m/Y') . '</span>
                    </div>
                </div>
                <div class="course-info">
                    <div class="info-item"><strong>Class:</strong> ' . $class_name . '</div>
                    <div class="info-item"><strong>Section:</strong> ' . $section_letter_name . '</div>
                    <div class="info-item"><strong>Shift:</strong> ' . $shift . '</div>
                    <div class="info-item"><strong>Academic period:</strong> ' . $academic_period . '</div>
                </div>
            </div>';

            // Paginación de estudiantes para esta sección
            for ($page = 1; $page <= $totalPages; $page++) {
                // Evita duplicar saltos de página innecesarios
                if ($page > 1 || $section !== reset($sections)) {
                    $html .= '<div class="page-break"></div>';
                }
                $html .= $header; // Encabezado por página
                
                // Tabla de estudiantes para la página actual
                $html .= '<div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Lastname</th>
                                        <th>Firstname</th>
                                        <th>Gender</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>B. Date</th>
                                    </tr>
                                </thead>
                                <tbody>';

                // Calcular el rango de estudiantes para esta página
                $startIndex = ($page - 1) * $studentsPerPage;
                $studentsOnPage = array_slice($students_in_section, $startIndex, $studentsPerPage);

                foreach ($studentsOnPage as $student) {
                    $birthday = date('d/m/Y', strtotime($student['birthday']));
                    
                    $html .= '<tr>
                                <td class="text-left">' . $student['lastname'] . '</td>
                                <td class="text-left">' . $student['firstname'] . '</td>
                                <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                                <td>' . $student['dni'] . '</td>
                                <td>' . htmlspecialchars($student['email']) . '</td>
                                <td>' . $student['username'] . '</td>
                                <td>' . $birthday . '</td>
                            </tr>';
                }

                $html .= '</tbody></table></div>';

                // Pie de página con el número de página
                $html .= '<div class="page-footer">
                            <strong>Page ' . $page . ' of ' . $totalPages . '</strong>
                        </div>';
            }
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        // Mostrar el HTML
        echo $html;
    }




    public function printClassStudentTableES($class_id = '')
    {
        // Obtener los datos de las secciones activas y de los estudiantes según la clase
        $sections = $this->crud_model->get_all_sections_per_class($class_id);
        $student_data = $this->crud_model->get_all_students_info();

        // Configuración de estudiantes por página
        $studentsPerPage = 13;

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Reporte de Estudiantes</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                     .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0px 0 10px 0px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .page-break {
                    page-break-after: always;
                }
            </style>
        </head>
        <body>';

        // Para cada sección de la clase especificada, generamos su reporte
        foreach ($sections as $section) {
            $class_name = $this->crud_model->get_class_name($section['class_id']);
            $section_letter_name = $section['letter_name'];
            $academic_period = $this->crud_model->get_academic_period_name_per_section($section['section_id']);

            $shift = '';
            if ($section['shift_id'] == 1) {
                $shift = ucfirst(get_phrase('morning'));
            } elseif ($section['shift_id'] == 2) {
                $shift = ucfirst(get_phrase('afternoon'));
            }

            $students_in_section = array_filter($student_data, function($student) use ($section) {
                return $student['section_id'] == $section['section_id'];
            });

            $totalPages = ceil(count($students_in_section) / $studentsPerPage);

            $header = '
            <div class="page-header">
                <div class="header-top">
                    <div class="logo-container">
                        <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                    </div>
                    <div class="report-title">
                        <strong>Reporte de Estudiantes</strong> <br>
                        <span class="text-right" style="font-size: 16px;">' . date('d/m/Y') . '</span>
                    </div>
                </div>
                <div class="course-info">
                    <div class="info-item"><strong>Curso:</strong> ' . $class_name . '</div>
                    <div class="info-item"><strong>División:</strong> ' . $section_letter_name . '</div>
                    <div class="info-item"><strong>Turno:</strong> ' . $shift . '</div>
                    <div class="info-item"><strong>Ciclo lectivo:</strong> ' . $academic_period . '</div>
                </div>
            </div>';

            for ($page = 1; $page <= $totalPages; $page++) {
                if ($page > 1 || $section !== reset($sections)) {
                    $html .= '<div class="page-break"></div>';
                }
                $html .= $header;

                $html .= '<div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Apellido</th>
                                        <th>Nombre</th>
                                        <th>Género</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                        <th>Usuario</th>
                                        <th>Fecha Nac.</th>
                                    </tr>
                                </thead>
                                <tbody>';

                $startIndex = ($page - 1) * $studentsPerPage;
                $studentsOnPage = array_slice($students_in_section, $startIndex, $studentsPerPage);

                foreach ($studentsOnPage as $student) {
                    $birthday = date('d/m/Y', strtotime($student['birthday']));

                    $html .= '<tr>
                                <td class="text-left">' . $student['lastname'] . '</td>
                                <td class="text-left">' . $student['firstname'] . '</td>
                                <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                                <td>' . $student['dni'] . '</td>
                                <td>' . htmlspecialchars($student['email']) . '</td>
                                <td>' . $student['username'] . '</td>
                                <td>' . $birthday . '</td>
                            </tr>';
                }

                $html .= '</tbody></table></div>';
                $html .= '<div class="page-footer">
                            <strong>Página ' . $page . ' de ' . $totalPages . '</strong>
                        </div>';
            }
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        echo $html;
    }



    public function printClassStudentTableEN($class_id = '')
    {
        // Obtener los datos de las secciones activas y de los estudiantes según la clase
        $sections = $this->crud_model->get_all_sections_per_class($class_id);
        $student_data = $this->crud_model->get_all_students_info();

        // Configuración de estudiantes por página
        $studentsPerPage = 13;

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Student Report</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                     .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0px 0 10px 0px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .page-break {
                    page-break-after: always;
                }
            </style>
        </head>
        <body>';

        // Para cada sección de la clase especificada, generamos su reporte
        foreach ($sections as $section) {
            $class_name = $this->crud_model->get_class_name($section['class_id']);
            $section_letter_name = $section['letter_name'];
            $academic_period = $this->crud_model->get_academic_period_name_per_section($section['section_id']);

            $shift = '';
            if ($section['shift_id'] == 1) {
                $shift = ucfirst(get_phrase('morning'));
            } elseif ($section['shift_id'] == 2) {
                $shift = ucfirst(get_phrase('afternoon'));
            }

            $students_in_section = array_filter($student_data, function($student) use ($section) {
                return $student['section_id'] == $section['section_id'];
            });

            $totalPages = ceil(count($students_in_section) / $studentsPerPage);

            $header = '
            <div class="page-header">
                <div class="header-top">
                    <div class="logo-container">
                        <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                    </div>
                    <div class="report-title">
                        <strong>Reporte de Estudiantes</strong> <br>
                        <span class="text-right" style="font-size: 16px;">' . date('d/m/Y') . '</span>
                    </div>
                </div>
                <div class="course-info">
                    <div class="info-item"><strong>Class:</strong> ' . $class_name . '</div>
                    <div class="info-item"><strong>Section:</strong> ' . $section_letter_name . '</div>
                    <div class="info-item"><strong>Shift:</strong> ' . $shift . '</div>
                    <div class="info-item"><strong>Academic period:</strong> ' . $academic_period . '</div>
                </div>
            </div>';

            for ($page = 1; $page <= $totalPages; $page++) {
                if ($page > 1 || $section !== reset($sections)) {
                    $html .= '<div class="page-break"></div>';
                }
                $html .= $header;

                $html .= '<div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Lastname</th>
                                        <th>Firstnmae</th>
                                        <th>Gender</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>B. Date</th>
                                    </tr>
                                </thead>
                                <tbody>';

                $startIndex = ($page - 1) * $studentsPerPage;
                $studentsOnPage = array_slice($students_in_section, $startIndex, $studentsPerPage);

                foreach ($studentsOnPage as $student) {
                    $birthday = date('d/m/Y', strtotime($student['birthday']));

                    $html .= '<tr>
                                <td class="text-left">' . $student['lastname'] . '</td>
                                <td class="text-left">' . $student['firstname'] . '</td>
                                <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                                <td>' . $student['dni'] . '</td>
                                <td>' . htmlspecialchars($student['email']) . '</td>
                                <td>' . $student['username'] . '</td>
                                <td>' . $birthday . '</td>
                            </tr>';
                }

                $html .= '</tbody></table></div>';
                $html .= '<div class="page-footer">
                            <strong>Page ' . $page . ' of ' . $totalPages . '</strong>
                        </div>';
            }
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        echo $html;
    }


    public function exportStudentTableExcelES()
    {
        $student_data = $this->crud_model->exportStudentTableExcelES();
        
        echo json_encode($student_data);
    }

    
    public function exportStudentTableExcelEN()
    {
        $student_data = $this->crud_model->exportStudentTableExcelEN();
        
        echo json_encode($student_data);
    }



    public function exportClassStudentTableExcelES($class_id = '')
    {
        $student_data = $this->crud_model->exportClassStudentTableExcelES($class_id);
        
        echo json_encode($student_data);
    }

 
    public function printStudentAdmissionsTableES()
    {
        // Obtener datos de los estudiantes y la sección
        $student_data = $this->crudStudent->get_students_admissions();
        $academic_period = $this->crud_model->get_active_academic_period_name();

        $studentsPerPage = 13;
        $totalPages = ceil(count($student_data) / $studentsPerPage);

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>&nbsp;</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 10px 10px 10px 10px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: center; 
                    align-items: center; 
                    text-align: center;   
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                .info-item {
                    display: inline-block;
                    width: 23%;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .table td:nth-child(5), .table td:nth-child(6) {
                    word-wrap: break-word;
                    word-break: break-all;
                    max-width: 150px;
                }
                .text-left {
                    text-align: left;
                }
                .page-footer {
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                .page-break {
                    page-break-after: always;
                }

                @media print {
                    @page { margin: 0;
                    size: auto; }
                }
            </style>
        </head>
        <body>';

        // Generar encabezado común
        $header = '
        <div class="page-header">
            <div class="header-top">
                <div class="logo-container">
                    <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                </div>
                <div class="report-title">
                    <strong>Reporte de Estudiantes en admisiones</strong> <br>
                    <span style="font-size: 16px;">' . date('d/m/Y') . '</span>
                </div>
            </div>
            <div class="course-info">
                <div class="info-item"><strong>Ciclo lectivo:</strong> ' . $academic_period . '</div>
            </div>
        </div>';

        // Paginación de estudiantes
        for ($page = 1; $page <= $totalPages; $page++) {
            if ($page > 1) {
                $html .= '<div class="page-break"></div>';
            }
            $html .= $header;
            
            $html .= '<div class="container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Apellido</th>
                                    <th>Nombre</th>
                                    <th>Género</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th>Usuario</th>
                                    <th>Fecha Nac.</th>
                                    <th>Motivo</th>
                                </tr>
                            </thead>
                            <tbody>';

            // Calcular el rango de estudiantes para esta página
            $startIndex = ($page - 1) * $studentsPerPage;
            $studentsOnPage = array_slice($student_data, $startIndex, $studentsPerPage);

            foreach ($studentsOnPage as $student) {
                $birthday = date('d/m/Y', strtotime($student['birthday']));
                
                $html .= '<tr>
                            <td class="text-left">' . $student['lastname'] . '</td>
                            <td class="text-left">' . $student['firstname'] . '</td>
                            <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                            <td>' . $student['dni'] . '</td>
                            <td>' . htmlspecialchars($student['email']) . '</td>
                            <td>' . $student['username'] . '</td>
                            <td>' . $birthday . '</td>
                            <td>' . ucfirst(get_phrase($student['status_reason'])) . '</td>
                        </tr>';
            }

            $html .= '</tbody></table></div>';

            // Pie de página con el número de página
            $html .= '<div class="page-footer">
                        <strong>Página ' . $page . ' de ' . $totalPages . '</strong>
                    </div>';
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        // Mostrar el HTML
        echo $html;
    }
        

    public function printStudentAdmissionsTableEN()
    {
        $student_data = $this->crudStudent->get_students_admissions();
        $academic_period = $this->crud_model->get_active_academic_period_name();
    
        // Número máximo de estudiantes por página
        $studentsPerPage = 16;
        
        // Calcular el número de páginas necesarias
        $totalPages = ceil(count($student_data) / $studentsPerPage);
    
        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>&nbsp;</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                 body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 10px 10px 10px 10px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: center; 
                    align-items: center; 
                    text-align: center;   
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                .info-item {
                    display: inline-block;
                    width: 23%;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .table td:nth-child(5), .table td:nth-child(6) {
                    word-wrap: break-word;
                    word-break: break-all;
                    max-width: 150px;
                }
                .text-left {
                    text-align: left;
                }
                .page-footer {
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                .page-break {
                    page-break-after: always;
                }

                @media print {
                    @page { margin: 0;
                    size: auto; }
                }
            </style>
        </head>
        <body>';
    
        // Encabezado común en todas las páginas
        $header = '
        <div class="page-header">
                    <div class="header-top">
                        <div class="logo-container">
                            <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                        </div>
                        <div class="report-title">
                            <strong> Student report in admissions </strong> <br>
                            <span style="font-size: 14px;">' . date('d/m/Y') . '</span>
                        </div>
                    </div>
                    <div class="course-info">
                        <div><strong>Academic period:</strong> ' . $academic_period . '</div>
                    </div>
                </div>
                ';
    
        // Paginación de estudiantes
        for ($page = 1; $page <= $totalPages; $page++) {
            $html .= $header; // Agregar el encabezado en cada página
            $html .= '<div class="container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Lastname</th>
                                    <th>Firstname</th>
                                    <th>Gender</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>B. Date</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>';
    
            // Agregar los estudiantes correspondientes a la página actual
            $startIndex = ($page - 1) * $studentsPerPage;
            $endIndex = min($startIndex + $studentsPerPage, count($student_data));
            for ($i = $startIndex; $i < $endIndex; $i++) {
                $student = $student_data[$i];
                $birthday = date('d/m/Y', strtotime($student['birthday']));
    
                $html .= '<tr>
                            <td class="text-left">' . $student['lastname'] . '</td>
                            <td class="text-left">' . $student['firstname'] . '</td>
                            <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                            <td>' . $student['dni'] . '</td>
                            <td>' . htmlspecialchars($student['email']) . '</td>
                            <td>' . $student['username'] . '</td>
                            <td>' . $birthday . '</td>
                            <td>' . ucfirst(get_phrase($student['status_reason'])) . '</td>
                        </tr>';
            }
    
            $html .= '</tbody></table></div>';
    
            // Pie de página con número de página
            $html .= '<div class="page-footer">
                        <strong> Page ' . $page . ' of ' . $totalPages . ' </strong>
                    </div>';
    
            // Divisor de página (agrega un salto de página en la impresión)
            if ($page < $totalPages) {
                $html .= '<div style="page-break-after: always;"></div>';
            }
        }
    
        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';
    
        // Mostrar el HTML
        echo $html;
    }


    public function printStudentPreEnrollmentsTableES()
    {
        // Obtener datos de los estudiantes y la sección
        $student_data = $this->crudStudent->get_students_pre_enrollments();
        $academic_period = $this->crud_model->get_active_academic_period_name();

        $studentsPerPage = 13;
        $totalPages = ceil(count($student_data) / $studentsPerPage);

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>&nbsp;</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 10px 10px 10px 10px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: center; 
                    align-items: center; 
                    text-align: center;   
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                .info-item {
                    display: inline-block;
                    width: 23%;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .table td:nth-child(5), .table td:nth-child(6) {
                    word-wrap: break-word;
                    word-break: break-all;
                    max-width: 150px;
                }
                .text-left {
                    text-align: left;
                }
                .page-footer {
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                .page-break {
                    page-break-after: always;
                }

                @media print {
                    @page { margin: 0;
                    size: auto; }
                }
            </style>
        </head>
        <body>';

        // Generar encabezado común
        $header = '
        <div class="page-header">
            <div class="header-top">
                <div class="logo-container">
                    <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                </div>
                <div class="report-title">
                    <strong>Reporte de Estudiantes en Matriculación</strong> <br>
                    <span style="font-size: 16px;">' . date('d/m/Y') . '</span>
                </div>
            </div>
            <div class="course-info">
                <div class="info-item"><strong>Ciclo lectivo:</strong> ' . $academic_period . '</div>
            </div>
        </div>';

        // Paginación de estudiantes
        for ($page = 1; $page <= $totalPages; $page++) {
            if ($page > 1) {
                $html .= '<div class="page-break"></div>';
            }
            $html .= $header;
            
            $html .= '<div class="container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Apellido</th>
                                    <th>Nombre</th>
                                    <th>Género</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th>Usuario</th>
                                    <th>Fecha Nac.</th>
                                </tr>
                            </thead>
                            <tbody>';

            // Calcular el rango de estudiantes para esta página
            $startIndex = ($page - 1) * $studentsPerPage;
            $studentsOnPage = array_slice($student_data, $startIndex, $studentsPerPage);

            foreach ($studentsOnPage as $student) {
                $birthday = date('d/m/Y', strtotime($student['birthday']));
                
                $html .= '<tr>
                            <td class="text-left">' . $student['lastname'] . '</td>
                            <td class="text-left">' . $student['firstname'] . '</td>
                            <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                            <td>' . $student['dni'] . '</td>
                            <td>' . htmlspecialchars($student['email']) . '</td>
                            <td>' . $student['username'] . '</td>
                            <td>' . $birthday . '</td>
                        </tr>';
            }

            $html .= '</tbody></table></div>';

            // Pie de página con el número de página
            $html .= '<div class="page-footer">
                        <strong>Página ' . $page . ' de ' . $totalPages . '</strong>
                    </div>';
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        // Mostrar el HTML
        echo $html;
    }
        

    public function printStudentPreEnrollmentsTableEN()
    {
        $student_data = $this->crudStudent->get_students_pre_enrollments();
        $academic_period = $this->crud_model->get_active_academic_period_name();
    
        // Número máximo de estudiantes por página
        $studentsPerPage = 16;
        
        // Calcular el número de páginas necesarias
        $totalPages = ceil(count($student_data) / $studentsPerPage);
    
        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>&nbsp;</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                 body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 10px 10px 10px 10px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: center; 
                    align-items: center; 
                    text-align: center;   
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                .info-item {
                    display: inline-block;
                    width: 23%;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .table td:nth-child(5), .table td:nth-child(6) {
                    word-wrap: break-word;
                    word-break: break-all;
                    max-width: 150px;
                }
                .text-left {
                    text-align: left;
                }
                .page-footer {
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                .page-break {
                    page-break-after: always;
                }

                @media print {
                    @page { margin: 0;
                    size: auto; }
                }
            </style>
        </head>
        <body>';
    
        // Encabezado común en todas las páginas
        $header = '
        <div class="page-header">
                    <div class="header-top">
                        <div class="logo-container">
                            <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                        </div>
                        <div class="report-title">
                            <strong> Student report in Enrollments </strong> <br>
                            <span style="font-size: 14px;">' . date('d/m/Y') . '</span>
                        </div>
                    </div>
                    <div class="course-info">
                        <div><strong>Academic period:</strong> ' . $academic_period . '</div>
                    </div>
                </div>
                ';
    
        // Paginación de estudiantes
        for ($page = 1; $page <= $totalPages; $page++) {
            $html .= $header; // Agregar el encabezado en cada página
            $html .= '<div class="container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Lastname</th>
                                    <th>Firstname</th>
                                    <th>Gender</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>B. Date</th>
                                </tr>
                            </thead>
                            <tbody>';
    
            // Agregar los estudiantes correspondientes a la página actual
            $startIndex = ($page - 1) * $studentsPerPage;
            $endIndex = min($startIndex + $studentsPerPage, count($student_data));
            for ($i = $startIndex; $i < $endIndex; $i++) {
                $student = $student_data[$i];
                $birthday = date('d/m/Y', strtotime($student['birthday']));
    
                $html .= '<tr>
                            <td class="text-left">' . $student['lastname'] . '</td>
                            <td class="text-left">' . $student['firstname'] . '</td>
                            <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                            <td>' . $student['dni'] . '</td>
                            <td>' . htmlspecialchars($student['email']) . '</td>
                            <td>' . $student['username'] . '</td>
                            <td>' . $birthday . '</td>
                        </tr>';
            }
    
            $html .= '</tbody></table></div>';
    
            // Pie de página con número de página
            $html .= '<div class="page-footer">
                        <strong> Page ' . $page . ' of ' . $totalPages . ' </strong>
                    </div>';
    
            // Divisor de página (agrega un salto de página en la impresión)
            if ($page < $totalPages) {
                $html .= '<div style="page-break-after: always;"></div>';
            }
        }
    
        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';
    
        // Mostrar el HTML
        echo $html;
    }
    


}