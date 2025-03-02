<?php

class Attendance extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');

        $this->load->model('attendance/attendance_model'); // Cargar el modelo de asistencia
        $this->load->library('Attendance_service'); // Cargar la librería de servicio de asistencia 

        date_default_timezone_set('America/Argentina/Buenos_Aires');
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		
    }

    function attendance_student($class_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $class_id = empty($class_id) ? $this->attendance_model->get_first_class_id() : $class_id;
        
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
        
        $page_data = [
            'breadcrumb' => $breadcrumb,
            'page_name' => 'attendance_student',
            'page_title' => ucfirst(get_phrase('manage_student_attendance')),
            'class_id' => $class_id
        ];
        
        $this->load->view('backend/index', $page_data);    
    }

    function manage_attendance_student($date = '', $month = '', $year = '', $section_id = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }

        if ($_POST) {
            $students = $this->attendance_model->get_students_by_section($section_id);
            $attendance_data = [];

            foreach ($students as $row) {
                $attendance_data[$row['student_id']] = [
                    'status' => $this->input->post('status_' . $row['student_id']),
                    'observation' => $this->input->post('observation_' . $row['student_id'])
                ];
            }

            $this->Attendance_service->update_attendance($section_id, $this->input->post('date'), $attendance_data);

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

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'date' => $date,
            'month' => $month,
            'year' => $year,
            'section_id' => $section_id,
            'page_name' => 'manage_attendance_student',
            'page_title' => ucfirst(get_phrase('manage_student_attendance'))
        );

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

    function summary_attendance_student($section_id='') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }

        $used_section_history = false;

        $section_data = $this->attendance_model->get_section_data($section_id);

        if (empty($section_data)) {
            $section_data = $this->attendance_model->get_section_history_data($section_id);
            $used_section_history = true;
        }

        $academic_period_name = '';
            
        if ($used_section_history == true) {
            $academic_period_name = $this->crud_model->get_academic_period_name_per_section2($section_id);
            $page_data['academic_period_id'] = $section_data['academic_period_id']; 
        }

         // Obtener el conteo de todos los estudiantes
         $all_student_count = $this->attendance_model->get_all_student_count($section_id);

         // Obtener los estudiantes
         $students = $this->attendance_model->get_students($section_id);

        $current_date = date('Y-m-d');
        $attendance_student_presente = $this->attendance_model->get_attendance_student_section_amount($section_id, 1, 'daily', $current_date);
        $attendance_student_ausente = $this->attendance_model->get_attendance_student_section_amount($section_id, 2, 'daily', $current_date);
        $attendance_student_tardanza = $this->attendance_model->get_attendance_student_section_amount($section_id, 3, 'daily', $current_date);
        $attendance_student_ausencia_justificada = $this->attendance_model->get_attendance_student_section_amount($section_id, 4, 'daily', $current_date);

        $total_attendance = $attendance_student_presente + $attendance_student_ausente + $attendance_student_tardanza + $attendance_student_ausencia_justificada;
        $percentage_presente = $total_attendance > 0 ? number_format(($attendance_student_presente / $total_attendance) * 100, 2) : 0;
        $percentage_ausente = $total_attendance > 0 ? number_format(($attendance_student_ausente / $total_attendance) * 100, 2) : 0;
        $percentage_tardanza = $total_attendance > 0 ? number_format(($attendance_student_tardanza / $total_attendance) * 100, 2) : 0;
        $percentage_justificados = $total_attendance > 0 ? number_format(($attendance_student_ausencia_justificada / $total_attendance) * 100, 2) : 0;

        $academic_periods = $this->attendance_model->get_academic_periods();

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

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'page_name' => 'summary_attendance_student',
            'page_title' => ucfirst(get_phrase('attendance_summary')),
            'subject_amount' => $this->crud_model->get_section_subject_amount2($section_id),
            'student_amount' => $this->crud_model->get_section_student_amount2($section_id),
            'used_section_history' => $used_section_history,
            'day_data' => $this->attendance_service->get_attendance_data_for_chart2($section_id),
            'section_name' => $this->crud_model->get_section_name2($section_id),
            'section_id' => $section_id,
            'attendance_student_presente' => $attendance_student_presente,
            'attendance_student_ausente' => $attendance_student_ausente,
            'attendance_student_tardanza' => $attendance_student_tardanza,
            'attendance_student_ausencia_justificada' => $attendance_student_ausencia_justificada,
            'percentage_presente' => $percentage_presente,
            'percentage_ausente' => $percentage_ausente,
            'percentage_tardanza' => $percentage_tardanza,
            'percentage_justificados' => $percentage_justificados,
            'all_student_count' => $all_student_count,
            'students' => $students,
            'academic_periods' => $academic_periods
        );

        $this->load->view('backend/index', $page_data);
    }


    function filter_attendance($section_id = '', $filter_type = '', $date = '', $start_date = '', $end_date = '', $dateMoth = '', $start_date_yearly = '', $end_date_yearly = '') {
        $attendance_student_presente = $this->attendance_model->get_attendance_student_section_amount(
            $section_id, 1, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_ausente = $this->attendance_model->get_attendance_student_section_amount(
            $section_id, 2, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_tardanza = $this->attendance_model->get_attendance_student_section_amount(
            $section_id, 3, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_ausencia_justificada = $this->attendance_model->get_attendance_student_section_amount(
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
        $attendance_student_presente = $this->attendance_model->get_attendance_student_amount(
            $student_id, 1, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_ausente = $this->attendance_model->get_attendance_student_amount(
            $student_id, 2, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_tardanza = $this->attendance_model->get_attendance_student_amount(
            $student_id, 3, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly
        );
    
        $attendance_student_ausencia_justificada = $this->attendance_model->get_attendance_student_amount(
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

        $current_date = date('Y-m-d');
        $attendance_data = $this->attendance_model->get_attendance_data_for_student($student_id);

        $attendance_student_presente = $this->attendance_model->get_attendance_student_amount($student_id, 1, 'daily', $current_date);
        $attendance_student_ausente = $this->attendance_model->get_attendance_student_amount($student_id, 2, 'daily', $current_date);
        $attendance_student_tardanza = $this->attendance_model->get_attendance_student_amount($student_id, 3, 'daily', $current_date);
        $attendance_student_ausencia_justificada = $this->attendance_model->get_attendance_student_amount($student_id, 4, 'daily', $current_date);

        $total_attendance = $attendance_student_presente + $attendance_student_ausente + $attendance_student_tardanza + $attendance_student_ausencia_justificada;

        $percentage_presente = $total_attendance > 0 ? number_format(($attendance_student_presente / $total_attendance) * 100, 2) : 0;
        $percentage_ausente = $total_attendance > 0 ? number_format(($attendance_student_ausente / $total_attendance) * 100, 2) : 0;
        $percentage_tardanza = $total_attendance > 0 ? number_format(($attendance_student_tardanza / $total_attendance) * 100, 2) : 0;
        $percentage_justificados = $total_attendance > 0 ? number_format(($attendance_student_ausencia_justificada / $total_attendance) * 100, 2) : 0;

        $count_attendance_data = count($attendance_data);

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
            
        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'page_name' => 'details_attendance_student',
            'page_title' => ucfirst(get_phrase('attendance_summary')) . " - " . ucfirst(get_phrase('student_details')),
            'student_id' => $student_id,
            'student_lastname_firstname' => ucfirst($student_info[0]['lastname']) . ', ' . ucfirst($student_info[0]['firstname']),
            'section_name' => $this->crud_model->get_section_name($student_info[0]['section_id']),
            'attendance_data' => $attendance_data,
            'attendance_student_presente' => $attendance_student_presente,
            'attendance_student_ausente' => $attendance_student_ausente,
            'attendance_student_tardanza' => $attendance_student_tardanza,
            'attendance_student_ausencia_justificada' => $attendance_student_ausencia_justificada,
            'percentage_presente' => $percentage_presente,
            'percentage_ausente' => $percentage_ausente,
            'percentage_tardanza' => $percentage_tardanza,
            'percentage_justificados' => $percentage_justificados,
            'current_date' => $current_date,
            'current_date_formatted' => date('d/m/Y'),
            'current_week_start' => date('Y-m-d', strtotime($current_date . ' -6 days')),
            'current_week_end' => $current_date,
            'current_year' => date('Y'),
            'current_month_number' => date('m'),
            'current_month_name' => date('F'),
            'current_month_start' => date('F', strtotime('first day of last month')),
            'current_month_end' => date('F', strtotime('last day of this month')),
            'count_attendance_data' => $count_attendance_data
        );

        $this->load->view('backend/index', $page_data);    
    }

    function edit_attendance_student($student_id = '', $date = '')
    {
            $status     = $this->input->post('status');
            $date      = $this->input->post('date');
            $observation       = $this->input->post('observation');

            $this->attendance_model->edit_attendance_student($student_id, $date, $status, $observation);

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
            redirect(base_url() . 'index.php?admin/details_attendance_student/' . $student_id, 'refresh');
    }







}