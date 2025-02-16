<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AcademicPeriod extends CI_Controller
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



}function academic_period($param1 = '', $param2 = '')
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


    function manage_academic_history()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_academic_history')),
                'url' => base_url('index.php?admin/manage_academic_history/')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
		$page_data['page_name']  = 'manage_academic_history';
		$page_data['page_title'] 	= ucfirst(get_phrase('manage_academic_history'));
		$this->load->view('backend/index', $page_data);
	}

}
