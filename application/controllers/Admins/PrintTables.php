<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PrintTables extends CI_Controller
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

public function printStudentTableES($section_id = '')
    {
        // Obtener datos de los estudiantes y la sección
        $student_data = $this->crud_model->get_students_per_section($section_id);
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
        $student_data = $this->crud_model->get_students_per_section($section_id);
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
        $student_data = $this->crud_model->get_students_admissions();
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
        $student_data = $this->crud_model->get_students_admissions();
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