<?php

class Statistics extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('statistics/Statistics_model');
        $this->load->library('Statistics_service');

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
        $section_data = $this->Statistics_model->get_sections_by_academic_period($academic_period_id);

        $class_ids = [];
        $chart_data_attendance = [];
        $donut_data_attendance = [];
        $students_attendance_data_10_19 = [];
        $students_attendance_data_20_25 = [];
        $students_attendance_data_more_25 = [];

        $chart_data_pass = [];
        $donut_data_pass = [];

        $academic_periods = $this->Statistics_model->get_academic_periods();

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
                    $attendance_data = $this->Statistics_model->get_attendance_data($section_id);

                    foreach ($attendance_data as $attendance) {
                        if ($attendance['total_absences'] < 10) {
                            $range_less_10++;
                        } elseif ($attendance['total_absences'] >= 10 && $attendance['total_absences'] <= 19) {
                            $range_10_19++;
                            $students_attendance_data_10_19 = $this->Statistics_model->get_students_attendance_data($section_id, 10, 19);
                        } elseif ($attendance['total_absences'] >= 20 && $attendance['total_absences'] <= 25) {
                            $range_20_25++;
                            $students_attendance_data_20_25 = $this->Statistics_model->get_students_attendance_data($section_id, 20, 25);
                        } elseif ($attendance['total_absences'] > 25) {
                            $range_25_plus++;
                            $students_attendance_data_more_25 = $this->Statistics_model->get_students_attendance_data($section_id, 26, PHP_INT_MAX);
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
                $total_students = $this->Statistics_model->count_students_by_class($class_id);

                $students_pass = array_merge($students_pass, $this->Statistics_model->get_students_by_status($class_id, 'pass'));
                $students_no_pass = array_merge($students_no_pass, $this->Statistics_model->get_students_by_status($class_id, 'no_pass'));

                $student_pass_no_pass = array_merge($student_pass_no_pass, $students_pass, $students_no_pass);

                $students_with_no_pass = $this->Statistics_model->count_students_by_status($class_id, 'no_pass');
                $students_with_pass = $this->Statistics_model->count_students_by_status($class_id, 'pass');

                $students_normal = $total_students - ($students_with_pass + $students_with_no_pass);

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

            $students = $this->Statistics_model->get_graduates_by_period($academic_period_id);

            $effective_graduates = 0;
            $non_effective_graduates = 0;

            foreach ($students as $student) {
                $student_id = $student['student_id'];

                $failed_marks = $this->Statistics_model->count_failed_marks($student_id, 22);

                $status = 'Egreso efectivo';
                if ($failed_marks > 0) {
                    $non_effective_graduates++;
                    $status = 'Egreso no efectivo';
                } else {
                    $effective_graduates++;
                }

                $student_details = $this->Statistics_model->get_student_details($student_id);
                $student_details['status'] = $status;

                $students_graduate[] = $student_details;
            }

            $chart_data_graduate[] = [
                'x' => $academic_period_name,
                'efectivo' => $effective_graduates,
                'no_efectivo' => $non_effective_graduates,
            ];

            $total_graduates = $effective_graduates + $non_effective_graduates;
            $percentage_effective_graduates = ($total_graduates > 0) ? round(($effective_graduates / $total_graduates) * 100) : 0;
            $percentage_non_effective_graduates = 100 - $percentage_effective_graduates;

            $donut_data_graduate[$academic_period_id] = [
                ['label' => 'Sin finalizar', 'value' => $percentage_effective_graduates],
                ['label' => 'Egreso efectivo', 'value' => $percentage_non_effective_graduates]
            ];
        }

        $academic_period_id_repeater = $this->Statistics_model->get_last_academic_period_id();

        $sections = $this->Statistics_model->get_sections_by_academic_period($academic_period_id_repeater);

        $chart_data_repeater = [];
        $donut_data_repeater = [];
        $students_repeater = [];

        foreach ($sections as $section) {
            $section_id = $section['section_id'];
            $section_name = $section['name'];

            $students = $this->Statistics_model->get_students_by_section($section_id, $academic_period_id_repeater);

            $repeater_count = 0;
            $total_students = count($students);

            foreach ($students as $student) {
                $student_id = $student['student_id'];

                $mark_data = $this->Statistics_model->get_student_mark($student_id, $academic_period_id_repeater, $section_id, 22);

                if ($mark_data && $mark_data->mark_obtained < 7 && $mark_data->mark_obtained !== 0 && $mark_data->mark_obtained !== null) {
                    $student_details = $this->Statistics_model->get_student_details($student_id);
                    $students_repeater[] = $student_details;
                    $repeater_count++;
                }
            }

            $chart_data_repeater[] = [
                'x' => $section_name,
                'repeater' => $repeater_count,
                'total' => $total_students
            ];

            $percentage_repeater = ($total_students > 0) ? round(($repeater_count / $total_students) * 100) : 0;
            $percentage_non_repeater = 100 - $percentage_repeater;

            $donut_data_repeater[$section_id] = [
                ['label' => 'Repetidores', 'value' => $percentage_repeater],
                ['label' => 'No Repetidores', 'value' => $percentage_non_repeater]
            ];
        }

        $page_data = array(
            'breadcrumb' => array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url()
                ),
                array(
                    'text' => ucfirst(get_phrase('statistics')),
                    'url' => null
                )
            ),
            'page_name' => 'statistics',
            'page_title' => ucfirst(get_phrase('statistics')),
            'chart_data_attendance' => $chart_data_attendance,
            'donut_data_attendance' => $donut_data_attendance,
            'students_attendance_data_10_19' => $students_attendance_data_10_19,
            'students_attendance_data_20_25' => $students_attendance_data_20_25,
            'students_attendance_data_more_25' => $students_attendance_data_more_25,
            'chart_data_pass' => $chart_data_pass,
            'donut_data_pass' => $donut_data_pass,
            'students_pass' => $students_pass,
            'students_no_pass' => $students_no_pass,
            'student_pass_no_pass' => $student_pass_no_pass,
            'chart_data_graduate' => $chart_data_graduate,
            'donut_data_graduate' => $donut_data_graduate,
            'students_graduate' => $students_graduate,
            'chart_data_repeater' => $chart_data_repeater,
            'donut_data_repeater' => $donut_data_repeater,
            'students_repeater' => $students_repeater
        );

        $this->load->view('backend/index', $page_data);
    }
}