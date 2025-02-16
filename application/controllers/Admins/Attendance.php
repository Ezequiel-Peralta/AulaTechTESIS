<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attendance extends CI_Controller
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
    


    function attendance_student($class_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($class_id == '')
            $class_id           =   $this->db->get('class')->first_row()->class_id;

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('manage_student_attendance')),
                'url' => base_url('index.php?admin/attendance_student/' . $class_id)
            )
        );
        
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']  = 'attendance_student';
        $page_data['page_icon'] = 'entypo-clipboard';
        $page_data['page_title'] = ucfirst(get_phrase('manage_student_attendance'));
        $page_data['class_id']   = $class_id;
        $this->load->view('backend/index', $page_data);    
    }

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
    
    function manage_attendance_student($date = '', $month = '', $year = '', $section_id = '')
    {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }

            $page_complete_name = 'manage_attendance_student'; // Nombre de la página
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

        // Verificar si se ha enviado un formulario
        if ($_POST) {
            // Obtener el estado de asistencia de los estudiantes del formulario
            $students = $this->db->get_where('student_details', array('section_id' => $section_id))->result_array();

            // Actualizar el estado de asistencia de cada estudiante
            foreach ($students as $row) {
                $attendance_status = $this->input->post('status_' . $row['student_id']);
                $observation = $this->input->post('observation_' . $row['student_id']);

                $this->db->where('student_id', $row['student_id']);
                $this->db->where('date', $this->input->post('date'));

                // Datos a actualizar, incluyendo el section_id
                $attendance_data = array(
                    'status' => $attendance_status,
                    'observation' => ($attendance_status == 4) ? $observation : '',
                    'section_id' => $section_id
                );

                $this->db->update('attendance_student', $attendance_data);
            }

            // Redirigir de vuelta a la página de administración de asistencia con los parámetros originales
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Asistencia actualizada correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/manage_attendance_student/' . $date . '/' . $month . '/' . $year . '/' . $section_id, 'refresh');
        }

        // Configuración del breadcrumb
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('manage_student_attendance')),
                'url' => base_url('index.php?admin/attendance_student/')
            ),
            array(
                'text' => ucfirst(get_phrase('register_attendance')),
                'url' => null
            )
        );
        $page_data['breadcrumb'] = $breadcrumb;

        // Obtener los datos de la página
        $page_data['date'] = $date;
        $page_data['month'] = $month;
        $page_data['year'] = $year;
        $page_data['section_id'] = $section_id;

        // Configurar el nombre y título de la página
        $page_data['page_name'] = 'manage_attendance_student';
        $page_data['page_title'] = ucfirst(get_phrase('manage_student_attendance'));

        // Cargar la vista con los datos de la página
        $this->load->view('backend/index', $page_data);
    }



    function manage_attendance_student_selector()
    {
        // Construye la URL base
        $redirect_url = base_url() . 'index.php?admin/manage_attendance_student/';

        // Agrega los parámetros solo si no están vacíos
        if ($this->input->post('date')) {
            $redirect_url .= $this->input->post('date') . '/';
        }
        if ($this->input->post('month')) {
            $redirect_url .= $this->input->post('month') . '/';
        }
        if ($this->input->post('year')) {
            $redirect_url .= $this->input->post('year') . '/';
        }
        if ($this->input->post('section_id')) {
            $redirect_url .= $this->input->post('section_id') . '/';
        }

        // Redirige a la URL construida
        redirect($redirect_url, 'refresh');
    }

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
        // $page_data['attendance_student_presente'] = $this->crud_model->get_attendance_student_section_amount($section_id, 1);
        // $page_data['attendance_student_ausente'] = $this->crud_model->get_attendance_student_section_amount($section_id, 2);
        // $page_data['attendance_student_tardanza'] = $this->crud_model->get_attendance_student_section_amount($section_id, 3);
        // $page_data['attendance_student_ausencia_justificada'] = $this->crud_model->get_attendance_student_section_amount($section_id, 4);

        $page_data['day_data'] = $this->crud_model->get_attendance_data_for_chart2($section_id);

        $page_data['section_name'] 	= $this->crud_model->get_section_name2($section_id);
        $page_data['section_id']   = $section_id;
        $this->load->view('backend/index', $page_data);    

        // Consultas a la base de datos para obtener los datos necesarios
        // $complete_class_name = $this->crud_model->get_class_name_numeric($class_id) . "° " . $this->crud_model->get_section_letter_name($section_id);
        // $subject_amount = $this->crud_model->get_section_subject_amount($section_id);
        // $student_amount = $this->crud_model->get_section_student_amount($section_id);
        // $attendance_student_presente = $this->crud_model->get_attendance_student_section_amount($section_id, 1);
        // $attendance_student_ausente = $this->crud_model->get_attendance_student_section_amount($section_id, 2);
        // $attendance_student_tardanza = $this->crud_model->get_attendance_student_section_amount($section_id, 3);
        // $attendance_student_ausencia_justificada = $this->crud_model->get_attendance_student_section_amount($section_id, 4);

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
        $attendance_student_presente = $this->crud_model->get_attendance_student_section_amount2(
            $section_id, 1, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_ausente = $this->crud_model->get_attendance_student_section_amount2(
            $section_id, 2, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_tardanza = $this->crud_model->get_attendance_student_section_amount2(
            $section_id, 3, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_ausencia_justificada = $this->crud_model->get_attendance_student_section_amount2(
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
        $attendance_student_presente = $this->crud_model->get_attendance_student_amount(
            $student_id, 1, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_ausente = $this->crud_model->get_attendance_student_amount(
            $student_id, 2, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_tardanza = $this->crud_model->get_attendance_student_amount(
            $student_id, 3, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_ausencia_justificada = $this->crud_model->get_attendance_student_amount(
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

        $student_info = $this->crud_model->get_student_info($student_id);
       
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






}