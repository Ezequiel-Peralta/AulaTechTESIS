<?php

class Statistics extends CI_Controller
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


    function statistics()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $academic_period_id = $this->crud_model->get_active_academic_period_id();

        $this->db->where('academic_period_id', $academic_period_id);
        $section_data = $this->db->get('section')->result_array();

        $class_ids = [];
        $chart_data_attendance = [];
        $donut_data_attendance = [];
        $students_attendance_data_10_19 = []; 
        $students_attendance_data_20_25 = []; 
        $students_attendance_data_more_25 = []; 

        $chart_data_pass = [];
        $donut_data_pass = [];

        $this->db->select('id as academic_period_id, name');
        $academic_periods = $this->db->get('academic_period')->result_array();

        $chart_data_graduate = [];
        $donut_data_graduate = [];

        if (!empty($section_data)) {
            $class_ids = array_unique(array_column($section_data, 'class_id'));

            foreach ($class_ids as $class_id) {
                $sections_for_class = array_filter($section_data, function($section) use ($class_id) {
                    return $section['class_id'] == $class_id;
                });

                $section_ids = array_column($sections_for_class, 'section_id');

                $range_less_10 = 0;
                $range_10_19 = 0;
                $range_20_25 = 0;
                $range_25_plus = 0;

                foreach ($section_ids as $section_id) {
                    $this->db->select('student_id, COUNT(*) as total_absences');
                    $this->db->where('section_id', $section_id);
                    $this->db->where_in('status', [2, 4]); // Status 2 y 4 representan inasistencias
                    $this->db->group_by('student_id');
                    $attendance_data = $this->db->get('attendance_student')->result_array();

                    foreach ($attendance_data as $attendance) {
                        if ($attendance['total_absences'] < 10) {
                            $range_less_10++;
                        } elseif ($attendance['total_absences'] >= 10 && $attendance['total_absences'] <= 19) {
                            $range_10_19++;
                            
                            $this->db->select('
                            student.student_id, 
                            student_details.photo, 
                            student_details.lastname, 
                            student_details.firstname, 
                            section.name as class, 
                            COUNT(attendance_student.status) as quantity
                        ');
                        $this->db->from('student');
                        $this->db->join('student_details', 'student.student_id = student_details.student_id');
                        $this->db->join('attendance_student', 'student.student_id = attendance_student.student_id');
                        $this->db->join('section', 'student_details.section_id = section.section_id'); // Alias para la primera unión
                        $this->db->where('attendance_student.section_id', $section_id);
                        $this->db->where_in('attendance_student.status', [2, 4]);
                        $this->db->group_by('attendance_student.student_id');
                        $this->db->having('quantity >= 10 AND quantity <= 19');
                        
                        $students_attendance_data_10_19 = $this->db->get()->result_array();

                        } elseif ($attendance['total_absences'] >= 20 && $attendance['total_absences'] <= 25) {
                            $range_20_25++;

                            $this->db->select('
                                student.student_id, 
                                student_details.photo, 
                                student_details.lastname, 
                                student_details.firstname, 
                                section.name as class, 
                                COUNT(attendance_student.status) as quantity
                            ');
                            $this->db->from('student');
                            $this->db->join('student_details', 'student.student_id = student_details.student_id');
                            $this->db->join('attendance_student', 'student.student_id = attendance_student.student_id');
                            $this->db->join('section', 'student_details.section_id = section.section_id');
                            $this->db->where('attendance_student.section_id', $section_id);
                            $this->db->where_in('attendance_student.status', [2, 4]);
                            $this->db->group_by('attendance_student.student_id');
                            $this->db->having('quantity >= 20 AND quantity <= 25');
                            
                            $students_attendance_data_20_25 = $this->db->get()->result_array();
                        } elseif ($attendance['total_absences'] > 25) {
                            $range_25_plus++;
                            
                            $this->db->select('
                                student.student_id, 
                                student_details.photo, 
                                student_details.lastname, 
                                student_details.firstname, 
                                section.name as class, 
                                COUNT(attendance_student.status) as quantity
                            ');
                            $this->db->from('student');
                            $this->db->join('student_details', 'student.student_id = student_details.student_id');
                            $this->db->join('attendance_student', 'student.student_id = attendance_student.student_id');
                            $this->db->join('section', 'student_details.section_id = section.section_id');
                            $this->db->where('attendance_student.section_id', $section_id);
                            $this->db->where_in('attendance_student.status', [2, 4]);
                            $this->db->group_by('attendance_student.student_id');
                            $this->db->having('quantity > 25');
                            
                            $students_attendance_data_more_25 = $this->db->get()->result_array();
                        }
                    }
                }

                $chart_data_attendance[] = [
                    'x' => $class_id . '°',
                    'y' => $range_10_19,
                    'z' => $range_20_25,
                    'a' => $range_25_plus
                ];

                $total_students = $range_less_10 + $range_10_19 + $range_20_25 + $range_25_plus;
                $donut_data_attendance[$class_id] = [
                    ['label' => 'Menos de 10', 'value' => ($total_students > 0) ? round(($range_less_10 / $total_students) * 100) : 0],
                    ['label' => '10-19', 'value' => ($total_students > 0) ? round(($range_10_19 / $total_students) * 100) : 0],
                    ['label' => '20-25', 'value' => ($total_students > 0) ? round(($range_20_25 / $total_students) * 100) : 0],
                    ['label' => 'Más de 25', 'value' => ($total_students > 0) ? round(($range_25_plus / $total_students) * 100) : 0]
                ];
            }
        }

        $students_pass = [];
        $students_no_pass = [];
        $student_pass_no_pass = [];

        if (!empty($section_data)) {
            $class_ids = array_unique(array_column($section_data, 'class_id'));
    
            foreach ($class_ids as $class_id) {
                $this->db->from('student');
                $this->db->join('student_details', 'student.student_id = student_details.student_id');
                $this->db->join('section', 'student_details.section_id = section.section_id');
                $this->db->where('student_details.class_id', $class_id);
                
                // Contar el total de estudiantes
                $total_students = $this->db->count_all_results();

                $this->db->select('
                    student.student_id, 
                    student_details.photo, 
                    student_details.lastname, 
                    student_details.firstname, 
                     student_details.status_reason, 
                    section.name as class
                ');
                $this->db->from('student');
                $this->db->join('student_details', 'student.student_id = student_details.student_id');
                $this->db->join('section', 'student_details.section_id = section.section_id');
                $this->db->where('student_details.class_id', $class_id);
                $this->db->where('student_details.status_reason', 'pass');
        
                $students_pass = array_merge($students_pass, $this->db->get()->result_array());

                    $this->db->select('
                    student.student_id, 
                    student_details.photo, 
                    student_details.lastname, 
                    student_details.firstname, 
                        student_details.status_reason, 
                    section.name as class
                ');
                $this->db->from('student');
                $this->db->join('student_details', 'student.student_id = student_details.student_id');
                $this->db->join('section', 'student_details.section_id = section.section_id');
                $this->db->where('student_details.class_id', $class_id);
                $this->db->where('student_details.status_reason', 'no_pass');
        
                $students_no_pass = array_merge($students_no_pass, $this->db->get()->result_array());

                $student_pass_no_pass = array_merge($student_pass_no_pass, $students_pass, $students_no_pass);

                $this->db->where('class_id', $class_id);
                $this->db->where('status_reason', 'no_pass');
                $students_with_no_pass = $this->db->count_all_results('student_details');
    
                // Students with status_reason = 'pass'
                $this->db->where('class_id', $class_id);
                $this->db->where('status_reason', 'pass');
                $students_with_pass = $this->db->count_all_results('student_details');

                $students_normal = $total_students - ($students_with_pass + $students_with_no_pass);
    
                // Add data for chart
                $chart_data_pass[] = [
                    'x' => $class_id . '°',
                    'pass' => $students_with_pass,
                    'no_pass' => $students_with_no_pass
                ];

    
                $donut_data_pass[$class_id] = [
                    ['label' => 'Pase', 'value' => ($total_students > 0) ? round(($students_with_pass / $total_students) * 100) : 0],
                    ['label' => 'Sin pase', 'value' => ($total_students > 0) ? round(($students_with_no_pass / $total_students) * 100) : 0]
                ];
            }
        }

        $students_graduate = [];

        foreach ($academic_periods as $period) {
            $academic_period_id = $period['academic_period_id'];
            $academic_period_name = $period['name'];
        
            $this->db->select('student_id');
            $this->db->where('old_academic_period_id', $academic_period_id);
            $this->db->where('old_class_id', 6);
            $students = $this->db->get('academic_history')->result_array();
        
            $effective_graduates = 0;
            $non_effective_graduates = 0;
        
            foreach ($students as $student) {
                $student_id = $student['student_id'];
        
                $this->db->select('COUNT(*) as count');
                $this->db->where('student_id', $student_id);
                $this->db->where('exam_id', 22);
                $this->db->where('mark_obtained <', 7);
                $failed_marks = $this->db->get('mark_history')->row()->count;
        
                $status = 'Egreso efectivo';
                if ($failed_marks > 0) {
                    $non_effective_graduates++;
                    $status = 'Egreso no efectivo';
                } else {
                    $effective_graduates++;
                }
        
                // Obtener detalles del estudiante
                $this->db->select('
                    student.student_id, 
                    student_details.photo, 
                    student_details.lastname, 
                    student_details.firstname, 
                    section.name as class
                ');
                $this->db->from('student');
                $this->db->join('student_details', 'student.student_id = student_details.student_id');
                $this->db->join('section', 'student_details.section_id = section.section_id');
                $this->db->where('student.student_id', $student_id);
                $student_details = $this->db->get()->row_array();
        
                // Añadir el status al array de detalles del estudiante
                $student_details['status'] = $status;
        
                $students_graduate[] = $student_details;
            }
        
            $chart_data_graduate[] = [
                'x' => $academic_period_name,
                'efectivo' => $effective_graduates,
                'no_efectivo' => $non_effective_graduates,
            ];
        
            // Calcula el total de graduados
            $total_graduates = $effective_graduates + $non_effective_graduates;
        
            // Donut para graduados
            $percentage_effective_graduates = ($total_graduates > 0) ? round(($effective_graduates / $total_graduates) * 100) : 0;
            $percentage_non_effective_graduates = 100 - $percentage_effective_graduates;
        
            $donut_data_graduate[$academic_period_id] = [
                ['label' => 'Sin finalizar', 'value' => $percentage_effective_graduates],
                ['label' => 'Egreso efectivo', 'value' => $percentage_non_effective_graduates]
            ];
        }

        $this->db->select('id');
        $this->db->from('academic_period');
        $this->db->where('status_id', 0);
        $this->db->order_by('end_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $academic_period_id_repeater = $query->row()->id;
        } else {
            $academic_period_id_repeater = null; 
        }

        $this->db->select('section_id, name');
        $this->db->from('section_history');
        $this->db->where('academic_period_id', $academic_period_id_repeater);
        $sections = $this->db->get()->result_array();
        
        // Inicializar los arrays de datos para los gráficos
        $chart_data_repeater = [];
        $donut_data_repeater = [];

        $students_repeater = [];
        
        // Recorrer cada sección para asegurarse de que todas aparezcan en los gráficos
        foreach ($sections as $section) {
            $section_id = $section['section_id'];
            $section_name = $section['name'];
        
            // Obtener todos los estudiantes que pertenecen a esta sección
            $this->db->select('student_id');
            $this->db->where('new_section_id', $section_id);
            $this->db->where('new_academic_period_id', $academic_period_id_repeater);
            $students = $this->db->get('academic_history')->result_array();
        
            $repeater_count = 0;
            $total_students = count($students);
        
            // Verificar los repetidores
            foreach ($students as $student) {
                $student_id = $student['student_id'];
        
                // Obtener la marca del estudiante
                $this->db->select('mark_obtained');
                $this->db->where('student_id', $student_id);
                $this->db->where('academic_period_id', $academic_period_id_repeater);
                $this->db->where('section_id', $section_id);
                $this->db->where('exam_type_id', 22);
                $mark_data = $this->db->get('mark_history')->row();
        
                // Verificar si el estudiante es un repetidor
                if ($mark_data && $mark_data->mark_obtained < 7 && $mark_data->mark_obtained !== 0 && $mark_data->mark_obtained !== null) {
                    // Si es un repetidor, agregar los detalles del estudiante
                    $this->db->select('
                        student.student_id, 
                        student_details.photo, 
                        student_details.lastname, 
                        student_details.firstname
                    ');
                    $this->db->from('student');
                    $this->db->where('student.student_id', $student_id);
                    $student_details = $this->db->get()->row_array();
                    $student_details['section_name'] = $section_name;
                    
                    // Agregar el detalle del estudiante a la lista de repetidores
                    $students_repeater[] = $student_details;
        
                    // Incrementar el contador de repetidores
                    $repeater_count++;
                }
            }
        
            // Agregar los datos al gráfico de repetidores (chart_data_repeater)
            $chart_data_repeater[] = [
                'x' => $section_name, // Usar el nombre de la sección
                'y' => $repeater_count
            ];
        
            $percentage_repeater = ($total_students > 0) ? round(($repeater_count / $total_students) * 100) : 0;
            $percentage_non_repeater = 100 - $percentage_repeater;
        
            // Datos para el gráfico de dona
            $donut_data_repeater[$section_id] = [
                ['label' => 'Repitentes', 'value' => $percentage_repeater],
                ['label' => 'No repitentes', 'value' => $percentage_non_repeater]
            ];
        }



        $this->db->select('id');
        $this->db->from('academic_period');
        $this->db->where('status_id', 0);
        $this->db->order_by('end_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $academic_period_id_promoted = $query->row()->id;
        } else {
            $academic_period_id_promoted = null; 
        }

        $this->db->select('section_id, name');
        $this->db->from('section_history');
        $this->db->where('academic_period_id', $academic_period_id_promoted);
        $sections_promoted = $this->db->get()->result_array();

        // Inicializar los arrays de datos para los gráficos
        $chart_data_promoted = [];
        $donut_data_promoted = [];

        $students_promoted = [];

        // Recorrer cada sección para asegurarse de que todas aparezcan en los gráficos
        foreach ($sections_promoted as $section) {
            $section_id = $section['section_id'];
            $section_name = $section['name'];

            // Obtener todos los estudiantes que pertenecen a esta sección
            $this->db->select('student_id');
            $this->db->where('new_section_id', $section_id);
            $this->db->where('new_academic_period_id', $academic_period_id_promoted);
            $students = $this->db->get('academic_history')->result_array();

            $promoted_count = 0;
            $total_students = count($students);

            // Verificar los repetidores
            foreach ($students as $student) {
                $student_id = $student['student_id'];

                // Obtener todos los registros de marca del estudiante
                $this->db->select('mark_obtained, exam_type_id');
                $this->db->where('student_id', $student_id);
                $this->db->where('academic_period_id', $academic_period_id_promoted);
                $this->db->where('section_id', $section_id);
                $mark_data = $this->db->get('mark_history')->result_array(); // Obtener todos los registros

                $is_promoted = true; // Suponemos que el estudiante es promovido

                // Revisamos los registros de marca del estudiante
                foreach ($mark_data as $mark) {
                    if ($mark['exam_type_id'] == 22) {
                        // Si el examen de tipo 22 tiene una marca menor a 7, no se considera promovido
                        if ($mark['mark_obtained'] < 7 && $mark['mark_obtained'] !== null && $mark['mark_obtained'] !== 0) {
                            $is_promoted = false;
                            break; // Salir del loop si ya encontramos una marca que descalifica al estudiante
                        }
                    }
                }

                // Si el estudiante es promovido, se agrega a la lista
                if ($is_promoted) {
                    // Obtener los detalles del estudiante
                    $this->db->select('
                        student.student_id, 
                        student_details.photo, 
                        student_details.lastname, 
                        student_details.firstname,
                        section.name as class
                    ');
                    $this->db->from('student');
                    $this->db->join('student_details', 'student.student_id = student_details.student_id');
                    $this->db->join('section', 'student_details.section_id = section.section_id');
                    $this->db->where('student.student_id', $student_id);
                    $student_details = $this->db->get()->row_array();

                    $student_details['section_name'] = $section_name;

                    // Agregar el detalle del estudiante a la lista de promovidos
                    $students_promoted[] = $student_details;

                    // Incrementar el contador de promovidos
                    $promoted_count++;
                }
            }

            // Agregar los datos al gráfico de promovidos (chart_data_promoted)
            $chart_data_promoted[] = [
                'x' => $section_name, // Usar el nombre de la sección
                'y' => $promoted_count
            ];

            $percentage_promoted = ($total_students > 0) ? round(($promoted_count / $total_students) * 100) : 0;
            $percentage_non_promoted = 100 - $percentage_promoted;

            // Datos para el gráfico de dona
            $donut_data_promoted[$section_id] = [
                ['label' => 'Promovidos', 'value' => $percentage_promoted],
                ['label' => 'No promovidos', 'value' => $percentage_non_promoted]
            ];
        }

       

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('statistics')),
                'url' => base_url('index.php?admin/statistics/')
            )
        );

        $page_data['class_ids'] = $class_ids;
        $page_data['chart_data_attendance'] = json_encode($chart_data_attendance);
        $page_data['donut_data_attendance'] = json_encode($donut_data_attendance);
        $page_data['chart_data_pass'] = json_encode($chart_data_pass);
        $page_data['donut_data_pass'] = json_encode($donut_data_pass);
        $page_data['student_pass_no_pass'] = $student_pass_no_pass;
        $page_data['chart_data_graduate'] = json_encode($chart_data_graduate);
        $page_data['donut_data_graduate'] = json_encode($donut_data_graduate);
        $page_data['students_graduate'] = $students_graduate; 
        $page_data['academic_periods'] = $academic_periods;
        $page_data['sections'] = $sections;
        
        $page_data['chart_data_repeater'] = json_encode($chart_data_repeater);
        $page_data['donut_data_repeater'] = json_encode($donut_data_repeater);
        $page_data['students_repeater'] = $students_repeater;
        $page_data['chart_data_promoted'] = json_encode($chart_data_promoted);
        $page_data['donut_data_promoted'] = json_encode($donut_data_promoted);
        $page_data['students_promoted'] = $students_promoted;
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['students_attendance_data_10_19'] = $students_attendance_data_10_19; 
        $page_data['students_attendance_data_20_25'] = $students_attendance_data_20_25; 
        $page_data['students_attendance_data_more_25'] = $students_attendance_data_more_25; 
        $page_data['page_name']  = 'statistics';
        $page_data['page_title'] = ucfirst(get_phrase('statistics'));
        $this->load->view('backend/index', $page_data);
    }

    














}