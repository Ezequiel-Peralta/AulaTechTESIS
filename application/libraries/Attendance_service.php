<?php
class Attendance_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('attendance/Attendance_model');
    }

    public function get_attendance_data_for_chart($section_id) {
        try {
            $student_ids = $this->Attendance_model->get_student_ids($section_id);
            if (empty($student_ids)) {
                return []; 
            }

            $max_date = '9999-12-31';
            foreach ($student_ids as $student) {
                if (!isset($student['student_id'])) {
                    continue; 
                }
                $max_date_query = $this->Attendance_model->get_max_date_for_student($student['student_id']);
                if ($max_date_query && !empty($max_date_query['date']) && $max_date_query['date'] < $max_date) {
                    $max_date = $max_date_query['date'];
                }
            }
            $start_date = date('Y-m-d', strtotime('-6 days', strtotime($max_date)));
        
            return $this->prepare_chart_data($student_ids, $start_date, $max_date);
        } catch (Exception $e) {
            log_message('error', 'Error in get_attendance_data_for_chart: ' . $e->getMessage());
            return false;
        }
    }

    public function get_attendance_data_for_chart2($section_id) {
        try {
            $student_ids = $this->Attendance_model->get_student_ids($section_id);

            if (empty($student_ids)) {
                return [];
            }

            $max_date = '9999-12-31';
            
            foreach ($student_ids as $student) {
                if (!isset($student['student_id'])) {
                    continue;
                }

                $max_date_for_student = $this->Attendance_model->get_max_date_for_student($student['student_id']);
                
                if ($max_date_for_student && $max_date_for_student < $max_date) {
                    $max_date = $max_date_for_student;
                }
            }

            $start_date = date('Y-m-d', strtotime('-6 days', strtotime($max_date)));

            return $this->prepare_chart_data($student_ids, $start_date, $max_date);
        } catch (Exception $e) {
            log_message('error', 'Error in get_attendance_data_for_chart2: ' . $e->getMessage());
            return false;
        }
    }

    public function get_attendance_data_for_chart_student($student_id) {
        try {
            $max_date_query = $this->Attendance_model->get_max_date_for_student($student_id);
        
            if (!$max_date_query || empty($max_date_query['date'])) {
                return [];
            }
        
            $max_date = $max_date_query['date'];
            $start_date = date('Y-m-d', strtotime('-6 days', strtotime($max_date)));
        
            $chart_data = [];
            $status_labels = [
                1 => 'presente',
                2 => 'ausente',
                3 => 'tardanza',
                4 => 'justificado'
            ];
        
            $attendance_data = $this->Attendance_model->get_attendance_data($student_id, $start_date, $max_date);
        
            foreach ($attendance_data as $data) {
                if (!isset($chart_data[$data['date']])) {
                    $chart_data[$data['date']] = ['elapsed' => $data['date']];
        
                    foreach ($status_labels as $status => $label) {
                        $chart_data[$data['date']][$label] = 0;
                    }
                }
        
                if (isset($status_labels[$data['status']])) {
                    $label = $status_labels[$data['status']];
                    $chart_data[$data['date']][$label] += $data['count'];
                }
            }
        
            return array_values($chart_data);
        } catch (Exception $e) {
            log_message('error', 'Error in get_attendance_data_for_chart_student: ' . $e->getMessage());
            return false;
        }
    }

    private function prepare_chart_data($student_ids, $start_date, $max_date) {
        try {
            $status_labels = [1 => 'presente', 2 => 'ausente', 3 => 'tardanza', 4 => 'justificado'];
            $chart_data = [];

            foreach ($student_ids as $student) {
                if (!isset($student['student_id'])) {
                    continue;
                }

                $attendance_data = $this->Attendance_model->get_attendance_data($student['student_id'], $start_date, $max_date);

                foreach ($attendance_data as $data) {
                    if (!isset($data['date'], $data['status'], $data['count'])) {
                        continue; 
                    }

                    if (!array_key_exists($data['status'], $status_labels)) {
                        continue; 
                    }

                    if (!isset($chart_data[$data['date']])) {
                        $chart_data[$data['date']] = ['elapsed' => $data['date']];
                        foreach ($status_labels as $status => $label) {
                            $chart_data[$data['date']][$label] = 0;
                        }
                    }

                    $label = $status_labels[$data['status']];
                    $chart_data[$data['date']][$label] += $data['count'];
                }
            }

            return array_values($chart_data);
        } catch (Exception $e) {
            log_message('error', 'Error in prepare_chart_data: ' . $e->getMessage());
            return false;
        }
    }

    public function update_attendance($section_id, $date, $attendance_data) {
        try {
            foreach ($attendance_data as $student_id => $data) {
                $this->Attendance_model->update_attendance($student_id, $date, $section_id, $data['status'], $data['observation']);
            }
        } catch (Exception $e) {
            log_message('error', 'Error in update_attendance: ' . $e->getMessage());
            return false;
        }
    }
}