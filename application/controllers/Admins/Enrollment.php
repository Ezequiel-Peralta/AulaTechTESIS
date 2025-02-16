<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Enrollment extends CI_Controller
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



    public function printStudentPreEnrollmentsTableES()
    {
        // Obtener datos de los estudiantes y la sección
        $student_data = $this->crud_model->get_students_pre_enrollments();
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
        $student_data = $this->crud_model->get_students_pre_enrollments();
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