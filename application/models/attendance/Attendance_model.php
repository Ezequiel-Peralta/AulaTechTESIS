<?php
class Attendance_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_attendance_count($table, $section_id, $attendance_type, $filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly) {
        try {
            // Validar y sanitizar los parámetros de entrada
            $section_id = $this->db->escape_str($section_id);
            $attendance_type = $this->db->escape_str($attendance_type);

            $this->db->where('section_id', $section_id);
            $this->db->where('status', $attendance_type);
            
            // Aplicar filtros
            $this->apply_filters($filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly);
            
            return $this->db->from($table)->count_all_results();
        } catch (Exception $e) {
            log_message('error', 'Error in get_attendance_count: ' . $e->getMessage());
            return false;
        }
    }

    public function get_student_ids($section_id) {
        try {
            // Validar y sanitizar el parámetro de entrada
            $section_id = $this->db->escape_str($section_id);

            $student_ids = $this->db->select('student_id')
                                    ->get_where('student_details', array('section_id' => $section_id))
                                    ->result_array();
            
            if (empty($student_ids)) {
                $student_ids = $this->db->select('student_id')
                                        ->get_where('academic_history', array('new_section_id' => $section_id))
                                        ->result_array();
            }
            
            return $student_ids;
        } catch (Exception $e) {
            log_message('error', 'Error in get_student_ids: ' . $e->getMessage());
            return false;
        }
    }

    public function get_max_date_for_student($student_id) {
        try {
            // Validar y sanitizar el parámetro de entrada
            $student_id = $this->db->escape_str($student_id);

            $max_date_query = $this->db->select_max('date')
                                       ->where('student_id', $student_id)
                                       ->get('attendance_student')
                                       ->row_array();
            
            if (empty($max_date_query)) {
                $max_date_query = $this->db->select_max('date')
                                           ->where('student_id', $student_id)
                                           ->get('attendance_student_history')
                                           ->row_array();
            }

            return isset($max_date_query['date']) ? $max_date_query['date'] : null;
        } catch (Exception $e) {
            log_message('error', 'Error in get_max_date_for_student: ' . $e->getMessage());
            return false;
        }
    }

    public function get_attendance_data($student_id, $start_date, $max_date) {
        try {
            // Validar y sanitizar los parámetros de entrada
            $student_id = $this->db->escape_str($student_id);
            $start_date = $this->db->escape_str($start_date);
            $max_date = $this->db->escape_str($max_date);

            $attendance_data = $this->db->select('date, status, COUNT(*) as count')
                                        ->where('student_id', $student_id)
                                        ->where('date >=', $start_date)
                                        ->where('date <=', $max_date)
                                        ->group_by(['date', 'status'])
                                        ->get('attendance_student')
                                        ->result_array();

            if (empty($attendance_data)) {
                $attendance_data = $this->db->select('date, status, COUNT(*) as count')
                                            ->where('student_id', $student_id)
                                            ->where('date >=', $start_date)
                                            ->where('date <=', $max_date)
                                            ->group_by(['date', 'status'])
                                            ->get('attendance_student_history')
                                            ->result_array();
            }

            return $attendance_data;
        } catch (Exception $e) {
            log_message('error', 'Error in get_attendance_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_attendance_per_student_amount($student_id, $attendance_type) {
        try {
            // Validar y sanitizar los parámetros de entrada
            $student_id = $this->db->escape_str($student_id);
            $attendance_type = $this->db->escape_str($attendance_type);

            $amount = $this->db->where(array('student_id' => $student_id, 'status' => $attendance_type))
                               ->from('attendance_student')
                               ->count_all_results();
            return $amount;
        } catch (Exception $e) {
            log_message('error', 'Error in get_attendance_per_student_amount: ' . $e->getMessage());
            return false;
        }
    }

    public function get_attendance_data_for_student($student_id) {
        try {
            // Validar y sanitizar el parámetro de entrada
            $student_id = $this->db->escape_str($student_id);

            $attendance_data = $this->db
                ->select('date, status, observation')
                ->where('student_id', $student_id)
                ->order_by('date', 'DESC')
                ->get('attendance_student')
                ->result_array();
        
            return $attendance_data;
        } catch (Exception $e) {
            log_message('error', 'Error in get_attendance_data_for_student: ' . $e->getMessage());
            return false;
        }
    }

    public function get_first_class_id() {
        try {
            $class = $this->db->get('class')->first_row();
            return isset($class->class_id) ? $class->class_id : null;
        } catch (Exception $e) {
            log_message('error', 'Error in get_first_class_id: ' . $e->getMessage());
            return false;
        }
    }

    public function update_attendance($student_id, $date, $section_id, $status, $observation) {
        try {
            // Validar y sanitizar los parámetros de entrada
            $student_id = $this->db->escape_str($student_id);
            $date = $this->db->escape_str($date);
            $section_id = $this->db->escape_str($section_id);
            $status = $this->db->escape_str($status);
            $observation = $this->db->escape_str($observation);

            $this->db->where('student_id', $student_id);
            $this->db->where('date', $date);

            $attendance_data = array(
                'status' => $status,
                'observation' => ($status == 4) ? $observation : '',
                'section_id' => $section_id
            );

            $this->db->update('attendance_student', $attendance_data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_attendance: ' . $e->getMessage());
            return false;
        }
    }

    public function get_students_by_section($section_id) {
        try {
            // Validar y sanitizar el parámetro de entrada
            $section_id = $this->db->escape_str($section_id);

            return $this->db->get_where('student_details', array('section_id' => $section_id))->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_students_by_section: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section_data($section_id) {
        try {
            // Validar y sanitizar el parámetro de entrada
            $section_id = $this->db->escape_str($section_id);

            $section_data = $this->db->where('section_id', $section_id)->get('section')->row_array();
            return $section_data;
        } catch (Exception $e) {
            log_message('error', 'Error in get_section_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section_history_data($section_id) {
        try {
            // Validar y sanitizar el parámetro de entrada
            $section_id = $this->db->escape_str($section_id);

            $section_data = $this->db->where('section_id', $section_id)->get('section_history')->row_array();
            return $section_data;
        } catch (Exception $e) {
            log_message('error', 'Error in get_section_history_data: ' . $e->getMessage());
            return false;
        }
    }

    public function edit_attendance_student($student_id, $date, $status, $observation) {
        try {
            // Validar y sanitizar los parámetros de entrada
            $student_id = $this->db->escape_str($student_id);
            $date = $this->db->escape_str($date);
            $status = $this->db->escape_str($status);
            $observation = $this->db->escape_str($observation);

            $data = array(
                'status' => $status,
                'observation' => $observation
            );
        
            $this->db->where(array('student_id' => $student_id, 'date' => $date));
            $this->db->update('attendance_student', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in edit_attendance_student: ' . $e->getMessage());
            return false;
        }
    }

    public function get_all_student_count($section_id) {
        try {
            // Validar y sanitizar el parámetro de entrada
            $section_id = $this->db->escape_str($section_id);

            // Consulta parametrizada
            $this->db->from('student_details');
            $this->db->where('user_status_id', 1);
            $this->db->where('section_id', $section_id);
            $query = $this->db->get();
            $all_student_count = $query->num_rows();

            if ($all_student_count === 0) {
                $this->db->from('academic_history');
                $this->db->where('new_section_id', $section_id);
                $query = $this->db->get();
                $all_student_count = $query->num_rows();
            }

            return $all_student_count;
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_student_count: ' . $e->getMessage());
            return false;
        }
    }

    public function get_students($section_id) {
        try {
            // Validar y sanitizar el parámetro de entrada
            $section_id = $this->db->escape_str($section_id);

            // Consulta parametrizada
            $this->db->where('section_id', $section_id);
            $students = $this->db->get('student_details')->result_array();

            if (empty($students)) {
                $this->db->where('new_section_id', $section_id);
                $students = $this->db->get('academic_history')->result_array();
            }

            return $students;
        } catch (Exception $e) {
            log_message('error', 'Error in get_students: ' . $e->getMessage());
            return false;
        }
    }

    public function apply_filters($filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly) {
        switch ($filter_type) {
            case 'daily':
                if (!empty($date) && $date !== 'null') {
                    $this->db->where('date', $date);
                }
                break;
            case 'weekly':
                if (!empty($start_date) && $start_date !== 'null' && !empty($end_date) && $end_date !== 'null') {
                    $this->db->where('date >=', $start_date);
                    $this->db->where('date <=', $end_date);
                }
                break;
            case 'monthly':
                if (!empty($dateMoth) && $dateMoth !== 'null') {
                    $this->db->where('MONTH(date)', date('m', strtotime($dateMoth)));
                    $this->db->where('YEAR(date)', date('Y', strtotime($dateMoth)));
                }
                break;
            case 'yearly':
                if (!empty($start_date_yearly) && $start_date_yearly !== 'null' && !empty($end_date_yearly) && $end_date_yearly !== 'null') {
                    $this->db->where('date >=', $start_date_yearly);
                    $this->db->where('date <=', $end_date_yearly);
                }
                break;
        }
    }

    public function get_attendance_student_section_amount($section_id = '', $attendance_type = '', $filter_type = '', $date = '', $start_date = '', $end_date = '', $dateMoth = '', $start_date_yearly = '', $end_date_yearly = '') {
        try {
            $this->db->where('section_id', $section_id);
            $this->db->where('status', $attendance_type);
            
            $this->apply_filters($filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly);
            
            $amount = $this->db->from('attendance_student')->count_all_results();
            
            if ($amount === 0) {
                $this->db->where('section_id', $section_id);
                $this->db->where('status', $attendance_type);
                $this->apply_filters($filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly);
                $amount = $this->db->from('attendance_student_history')->count_all_results();
            }
        
            return $amount;
        } catch (Exception $e) {
            log_message('error', 'Error in get_attendance_student_section_amount: ' . $e->getMessage());
            return false;
        }
    }

    public function get_attendance_student_amount($student_id, $attendance_type, $filter_type, $date = '', $start_date = '', $end_date = '', $dateMoth = '', $start_date_yearly = '', $end_date_yearly = '') {
        try {
            $this->db->where('student_id', $student_id);
            $this->db->where('status', $attendance_type);

            $this->apply_filters($filter_type, $date, $start_date, $end_date, $dateMoth, $start_date_yearly, $end_date_yearly);

            return $this->db->from('attendance_student')->count_all_results();
        } catch (Exception $e) {
            log_message('error', 'Error in get_attendance_student_amount: ' . $e->getMessage());
            return false;
        }
    }

    public function get_academic_periods() {
        try {
            $query = $this->db->get('academic_period');
            
            if (!$query) {
                throw new Exception('Error al obtener los periodos académicos: ' . $this->db->error());
            }

            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error en get_academic_periods: ' . $e->getMessage());
            return false;
        }
    }

    public function get_academic_period_by_id($academic_period_id) {
        try {
            $academic_period_id = $this->db->escape_str($academic_period_id);

            $query = $this->db->get_where('academic_period', array('id' => $academic_period_id));
            
            if (!$query) {
                throw new Exception('Error al obtener el periodo académico: ' . $this->db->error());
            }

            return $query->row();
        } catch (Exception $e) {
            log_message('error', 'Error en get_academic_period_by_id: ' . $e->getMessage());
            return false;
        }
    }

}