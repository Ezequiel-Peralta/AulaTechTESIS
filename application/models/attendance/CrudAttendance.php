<?php
class CrudAttendance extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    function get_attendance_student_section_amount($section_id = '', $attendance_type = '', $filter_type = '', $date = '', $start_date = '', $end_date = '', $dateMoth = '', $start_date_yearly = '', $end_date_yearly = '') {
        $this->db->where('section_id', $section_id);
        $this->db->where('status', $attendance_type);
    
        if ($filter_type === 'daily' && !empty($date)) {
            $this->db->where('date', $date);
        } elseif ($filter_type === 'weekly' && !empty($start_date) && !empty($end_date)) {
            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $end_date);
        } elseif ($filter_type === 'monthly' && !empty($dateMoth)) {
        

            $month_number = MONTHS[strtolower($dateMoth)] ?  MONTHS[strtolower($dateMoth)] : null;

            if ($month_number) {
                $this->db->where('MONTH(date)', $month_number);
            }
        } elseif ($filter_type === 'yearly' && !empty($start_date_yearly) && !empty($end_date_yearly)) {
   
    
            $start_month_number = MONTHS[strtolower($start_date_yearly)] ?  MONTHS[strtolower($start_date_yearly)] : null;
            $end_month_number = MONTHS[strtolower($end_date_yearly)] ?  MONTHS[strtolower($end_date_yearly)] : null;
    
            if ($start_month_number && $end_month_number) {
                $current_year = date('Y');
                $start_date = $current_year . '-' . $start_month_number . '-01';
                $end_date = $current_year . '-' . $end_month_number . '-31';
    
                $this->db->where('date >=', $start_date);
                $this->db->where('date <=', $end_date);
            }
        }
    
        $amount = $this->db->from('attendance_student')->count_all_results();
    
        return $amount; 
    }


    function get_attendance_student_section_amount2($section_id = '', $attendance_type = '', $filter_type = '', $date = '', $start_date = '', $end_date = '', $dateMoth = '', $start_date_yearly = '', $end_date_yearly = '') {
        $this->db->where('section_id', $section_id);
        $this->db->where('status', $attendance_type);
    
        if ($filter_type === 'daily' && !empty($date)) {
            $this->db->where('date', $date);
        } elseif ($filter_type === 'weekly' && !empty($start_date) && !empty($end_date)) {
            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $end_date);
        } elseif ($filter_type === 'monthly' && !empty($dateMoth)) {
           

            $month_number = MONTHS[strtolower($dateMoth)] ?  MONTHS[strtolower($dateMoth)] : null;

            if ($month_number) {
                $this->db->where('MONTH(date)', $month_number);
            }
        } elseif ($filter_type === 'yearly' && !empty($start_date_yearly) && !empty($end_date_yearly)) {

    
            $start_month_number = MONTHS[strtolower($start_date_yearly)] ?  MONTHS[strtolower($start_date_yearly)] : null;
            $end_month_number = MONTHS[strtolower($end_date_yearly)] ?  MONTHS[strtolower($end_date_yearly)] : null;
    
            if ($start_month_number && $end_month_number) {
                $current_year = date('Y');
                $start_date = $current_year . '-' . $start_month_number . '-01';
                $end_date = $current_year . '-' . $end_month_number . '-31';
    
                $this->db->where('date >=', $start_date);
                $this->db->where('date <=', $end_date);
            }
        }
    
        $amount = $this->db->from('attendance_student')->count_all_results();

        
        if ($amount === 0) {
            $this->db->where('section_id', $section_id);
            $this->db->where('status', $attendance_type);
        
            if ($filter_type === 'daily' && !empty($date)) {
                $this->db->where('date', $date);
            } elseif ($filter_type === 'weekly' && !empty($start_date) && !empty($end_date)) {
                $this->db->where('date >=', $start_date);
                $this->db->where('date <=', $end_date);
            } elseif ($filter_type === 'monthly' && !empty($dateMoth)) {
               
    
                $month_number = MONTHS[strtolower($dateMoth)] ?  MONTHS[strtolower($dateMoth)] : null;
    
                if ($month_number) {
                    $this->db->where('MONTH(date)', $month_number);
                }
            } elseif ($filter_type === 'yearly' && !empty($start_date_yearly) && !empty($end_date_yearly)) {
              
        
                $start_month_number = MONTHS[strtolower($start_date_yearly)] ?  MONTHS[strtolower($start_date_yearly)] : null;
                $end_month_number = MONTHS[strtolower($end_date_yearly)] ?  MONTHS[strtolower($end_date_yearly)] : null;
        
                if ($start_month_number && $end_month_number) {
                    $current_year = date('Y');
                    $start_date = $current_year . '-' . $start_month_number . '-01';
                    $end_date = $current_year . '-' . $end_month_number . '-31';
        
                    $this->db->where('date >=', $start_date);
                    $this->db->where('date <=', $end_date);
                }
            }
        
            $amount = $this->db->from('attendance_student_history')->count_all_results();

        }

        return $amount; 
    }


    function get_attendance_student_amount($student_id = '', $attendance_type = '', $filter_type = '', $date = '', $start_date = '', $end_date = '', $dateMoth = '', $start_date_yearly = '', $end_date_yearly = '') {
        $this->db->where('student_id', $student_id);
        $this->db->where('status', $attendance_type);
    
        if ($filter_type === 'daily' && !empty($date)) {
            $this->db->where('date', $date);
        } elseif ($filter_type === 'weekly' && !empty($start_date) && !empty($end_date)) {
            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $end_date);
        } elseif ($filter_type === 'monthly' && !empty($dateMoth)) {
           

            $month_number = MONTHS[strtolower($dateMoth)] ?  MONTHS[strtolower($dateMoth)] : null;

            if ($month_number) {
                $this->db->where('MONTH(date)', $month_number);
            }
        } elseif ($filter_type === 'yearly' && !empty($start_date_yearly) && !empty($end_date_yearly)) {
   
    
            $start_month_number = MONTHS[strtolower($start_date_yearly)] ?  MONTHS[strtolower($start_date_yearly)] : null;
            $end_month_number = MONTHS[strtolower($end_date_yearly)] ?  MONTHS[strtolower($end_date_yearly)] : null;
    
            if ($start_month_number && $end_month_number) {
                $current_year = date('Y');
                $start_date = $current_year . '-' . $start_month_number . '-01';
                $end_date = $current_year . '-' . $end_month_number . '-31';
    
                $this->db->where('date >=', $start_date);
                $this->db->where('date <=', $end_date);
            }
        }
    
        $amount = $this->db->from('attendance_student')->count_all_results();
    
        return $amount; 
    }


    public function get_attendance_data_for_chart($section_id) {
        // Obtener todos los student_id que pertenecen a la sección dada
        $student_ids = $this->db->select('student_id')->get_where('student_details', array('section_id' => $section_id))->result_array();
        
        // Verificar si hay student_ids
        if (empty($student_ids)) {
            return []; // Si no hay estudiantes, retornar un array vacío
        }
    
        // Inicializar la fecha final con la fecha más grande posible
        $max_date = '9999-12-31';
        
        // Iterar sobre los student_id obtenidos
        foreach ($student_ids as $student) {
            if (!isset($student['student_id'])) {
                continue; // Si el student_id no está definido, omitir
            }
    
            // Obtener la fecha máxima de los registros de asistencia para el estudiante actual
            $max_date_query = $this->db
                ->select_max('date')
                ->where('student_id', $student['student_id'])
                ->get('attendance_student')
                ->row_array();
        
            // Actualizar la fecha final si es menor que la fecha máxima encontrada
            if ($max_date_query && !empty($max_date_query['date']) && $max_date_query['date'] < $max_date) {
                $max_date = $max_date_query['date'];
            }
        }
        
        // Calcular la fecha inicial 7 días antes de la fecha final
        $start_date = date('Y-m-d', strtotime('-6 days', strtotime($max_date)));
        
        // Crear un array para almacenar los datos en el formato esperado para el gráfico
        $chart_data = [];
        
        // Definir las claves de status con sus correspondientes nombres
        $status_labels = [
            1 => 'presente',
            2 => 'ausente',
            3 => 'tardanza',
            4 => 'justificado'
        ];
        
        // Iterar sobre los student_id obtenidos
        foreach ($student_ids as $student) {
            if (!isset($student['student_id'])) {
                continue; // Omitir si el student_id no está definido
            }
    
            // Obtener los registros de asistencia para el estudiante actual
            $attendance_data = $this->db
                ->select('date, status, COUNT(*) as count')
                ->where('student_id', $student['student_id'])
                ->where('date >=', $start_date)
                ->where('date <=', $max_date)
                ->group_by(['date', 'status'])
                ->get('attendance_student')
                ->result_array();
        
            // Iterar sobre los datos de asistencia para el estudiante actual
            foreach ($attendance_data as $data) {
                if (!isset($data['date'], $data['status'], $data['count'])) {
                    continue; // Si falta alguno de los índices, omitir
                }
    
                // Verificar que el status esté en las claves definidas
                if (!array_key_exists($data['status'], $status_labels)) {
                    continue; // Si el status no está definido, omitir
                }
    
                // Verificar si ya existe una entrada en el array para esta fecha
                if (!isset($chart_data[$data['date']])) {
                    $chart_data[$data['date']] = ['elapsed' => $data['date']];
        
                    // Inicializar todas las claves de status con valor cero
                    foreach ($status_labels as $status => $label) {
                        $chart_data[$data['date']][$label] = 0;
                    }
                }
    
                // Establecer el valor de conteo para la clave de status correspondiente
                $label = $status_labels[$data['status']];
                $chart_data[$data['date']][$label] += $data['count'];
            }
        }
        
        // Devolver los datos formateados para el gráfico como un array PHP
        return array_values($chart_data);
    }




    public function get_attendance_data_for_chart2($section_id) {
        // Obtener todos los student_id que pertenecen a la sección dada
        $student_ids = $this->db->select('student_id')->get_where('student_details', array('section_id' => $section_id))->result_array();
    
        // Verificar si no hay student_ids, buscar en academic_history
        if (empty($student_ids)) {
            $student_ids = $this->db
                ->select('student_id')
                ->get_where('academic_history', array('new_section_id' => $section_id))
                ->result_array();
    
            // Si sigue vacío, retornar un array vacío
            if (empty($student_ids)) {
                return [];
            }
        }
    
        // Inicializar la fecha final con la fecha más grande posible
        $max_date = '9999-12-31';
    
        // Iterar sobre los student_id obtenidos
        foreach ($student_ids as $student) {
            if (!isset($student['student_id'])) {
                continue; // Si el student_id no está definido, omitir
            }
    
            // Obtener la fecha máxima de los registros de asistencia para el estudiante actual
            $max_date_query = $this->db
                ->select_max('date')
                ->where('student_id', $student['student_id'])
                ->get('attendance_student')
                ->row_array();
    
            // Si no se encontraron registros en attendance_student, buscar en attendance_student_history
            if (empty($max_date_query)) {
                $max_date_query = $this->db
                    ->select_max('date')
                    ->where('student_id', $student['student_id'])
                    ->get('attendance_student_history')
                    ->row_array();
            }
    
            // Actualizar la fecha final si es menor que la fecha máxima encontrada
            if ($max_date_query && !empty($max_date_query['date']) && $max_date_query['date'] < $max_date) {
                $max_date = $max_date_query['date'];
            }
        }
    
        // Calcular la fecha inicial 7 días antes de la fecha final
        $start_date = date('Y-m-d', strtotime('-6 days', strtotime($max_date)));
    
        // Crear un array para almacenar los datos en el formato esperado para el gráfico
        $chart_data = [];
    
        // Definir las claves de status con sus correspondientes nombres
        $status_labels = [
            1 => 'presente',
            2 => 'ausente',
            3 => 'tardanza',
            4 => 'justificado'
        ];
    
        // Iterar sobre los student_id obtenidos
        foreach ($student_ids as $student) {
            if (!isset($student['student_id'])) {
                continue; // Omitir si el student_id no está definido
            }
    
            // Obtener los registros de asistencia para el estudiante actual
            $attendance_data = $this->db
                ->select('date, status, COUNT(*) as count')
                ->where('student_id', $student['student_id'])
                ->where('date >=', $start_date)
                ->where('date <=', $max_date)
                ->group_by(['date', 'status'])
                ->get('attendance_student')
                ->result_array();
    
            // Si no hay registros en attendance_student, buscar en attendance_student_history
            if (empty($attendance_data)) {
                $attendance_data = $this->db
                    ->select('date, status, COUNT(*) as count')
                    ->where('student_id', $student['student_id'])
                    ->where('date >=', $start_date)
                    ->where('date <=', $max_date)
                    ->group_by(['date', 'status'])
                    ->get('attendance_student_history')
                    ->result_array();
            }
    
            // Iterar sobre los datos de asistencia para el estudiante actual
            foreach ($attendance_data as $data) {
                if (!isset($data['date'], $data['status'], $data['count'])) {
                    continue; // Si falta alguno de los índices, omitir
                }
    
                // Verificar que el status esté en las claves definidas
                if (!array_key_exists($data['status'], $status_labels)) {
                    continue; // Si el status no está definido, omitir
                }
    
                // Verificar si ya existe una entrada en el array para esta fecha
                if (!isset($chart_data[$data['date']])) {
                    $chart_data[$data['date']] = ['elapsed' => $data['date']];
    
                    // Inicializar todas las claves de status con valor cero
                    foreach ($status_labels as $status => $label) {
                        $chart_data[$data['date']][$label] = 0;
                    }
                }
    
                // Establecer el valor de conteo para la clave de status correspondiente
                $label = $status_labels[$data['status']];
                $chart_data[$data['date']][$label] += $data['count'];
            }
        }
    
        // Devolver los datos formateados para el gráfico como un array PHP
        return array_values($chart_data);
    }
    



    
    


    public function get_attendance_per_student_amount($student_id, $attendance_type) {
        // Obtener la cantidad de registros de attendance_student que coincidan con el student_id y el attendance_type
        $amount = $this->db->where(array('student_id' => $student_id, 'status' => $attendance_type))->from('attendance_student')->count_all_results();
    
        // Devolver la cantidad obtenida
        return $amount;
    }
    
    
    public function get_attendance_data_for_chart_student($student_id) {
        // Inicializar la fecha final con la fecha más grande posible
        $max_date_query = $this->db
            ->select_max('date')
            ->where('student_id', $student_id)
            ->get('attendance_student')
            ->row_array();
    
        // Si no se encontraron registros de asistencia para el estudiante, devolver un array vacío
        if (!$max_date_query || empty($max_date_query['date'])) {
            return [];
        }
    
        $max_date = $max_date_query['date'];
    
        // Calcular la fecha inicial 6 días antes de la fecha final
        $start_date = date('Y-m-d', strtotime('-6 days', strtotime($max_date)));
    
        // Crear un array para almacenar los datos en el formato esperado para el gráfico
        $chart_data = [];
    
        // Definir las claves de status con sus correspondientes nombres
        $status_labels = [
            1 => 'presente',
            2 => 'ausente',
            3 => 'tardanza',
            4 => 'justificado'
        ];
    
        // Obtener los registros de asistencia para el estudiante
        $attendance_data = $this->db
            ->select('date, status, COUNT(*) as count')
            ->where('student_id', $student_id)
            ->where('date >=', $start_date) // Considerar solo las fechas después de la fecha inicial
            ->where('date <=', $max_date)   // Considerar solo las fechas antes de la fecha final
            ->group_by(['date', 'status'])
            ->get('attendance_student')
            ->result_array();
    
        // Iterar sobre los datos de asistencia para el estudiante
        foreach ($attendance_data as $data) {
            // Verificar si ya existe una entrada en el array para esta fecha
            if (!isset($chart_data[$data['date']])) {
                // Si no existe, crear una nueva entrada con el formato esperado
                $chart_data[$data['date']] = ['elapsed' => $data['date']];
    
                // Inicializar todas las claves de status con valor cero
                foreach ($status_labels as $status => $label) {
                    $chart_data[$data['date']][$label] = 0;
                }
            }
    
            // Establecer el valor de conteo para la clave de status correspondiente
            if (isset($status_labels[$data['status']])) {
                $label = $status_labels[$data['status']];
                $chart_data[$data['date']][$label] += $data['count'];
            }
        }
    
        // Devolver los datos formateados para el gráfico como un array PHP
        return array_values($chart_data);
    }
    
    public function get_attendance_data_for_student($student_id) {
        // Obtener los registros de asistencia para el estudiante
        $attendance_data = $this->db
            ->select('date, status, observation')
            ->where('student_id', $student_id)
            ->order_by('date', 'DESC')
            ->get('attendance_student')
            ->result_array();
    
        return $attendance_data;
    }

}