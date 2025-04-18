<?php

class Admissions extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('admissions/Admissions_model');
        $this->load->library('Admissions_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function admissions()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('admissions')),
                'url' => base_url('index.php?admin/admissions/')
            )
        );

        $students = $this->Admissions_model->get_pending_admissions();

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['students'] = $students;
        $page_data['page_name'] = 'admissions';
        $page_data['page_icon'] = 'entypo-graduation-cap';
        $page_data['page_tile'] = 'admissions';
        $page_data['page_title'] = ucfirst(get_phrase('admissions'));

        $this->load->view('backend/index', $page_data);
    }

    function admissions_student($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        if ($param1 == 'create') {
            $student_id = $param2;
            $section_id = $param3;

            $section = $this->Admissions_model->get_section_by_id($section_id);

            if ($section) {
                $class_id = $section->class_id;
                $academic_period_id = $section->academic_period_id;

                $this->Admissions_model->update_student_details($student_id, $class_id, $section_id);

                $this->Admissions_model->insert_academic_history($student_id, $class_id, $section_id, $academic_period_id);

                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Estudiante inscripto correctamente!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/admissions/', 'refresh');
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
                redirect(base_url() . 'index.php?admin/admissions/', 'refresh');
            }
        }

        if ($param1 == 're_enrollment_bulk') {
            $segments = $this->uri->segment_array();
            $index = array_search('re_enrollment_bulk', $segments);

            $class_id = isset($segments[$index + 1]) ? $segments[$index + 1] : null;
            $section_id = isset($segments[$index + 2]) ? $segments[$index + 2] : null;
            $students = array_slice($segments, $index + 2);
            $students = array_filter($students, 'is_numeric');

            $section_data = $this->Admissions_model->get_section_by_id($section_id);
            $academic_period_id = $section_data->academic_period_id;

            if (!empty($students)) {
                $this->Admissions_model->bulk_update_student_details($students, $class_id, $section_id);

                foreach ($students as $student_id) {
                    $this->Admissions_model->insert_academic_history($student_id, $class_id, $section_id, $academic_period_id);
                }

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

                redirect(base_url() . 'index.php?admin/students_information/' . $section_id, 'refresh');
            }
        }
    }
}