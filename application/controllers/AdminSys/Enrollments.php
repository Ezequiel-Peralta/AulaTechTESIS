<?php

class Enrollments extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('Enrollments_model');
        $this->load->library('Enrollments_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function re_enrollments($section_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('re_enrollments')) . "&nbsp;&nbsp;/&nbsp;&nbsp;" . $this->crud_model->get_section_history_name($section_id) . " - " . $this->crud_model->get_academic_period_name_per_section_history($section_id),
                'url' => base_url('index.php?admin/re_enrollments/' . $section_id)
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;

        $students = $this->Enrollments_model->get_students_for_re_enrollment($section_id);
        $page_data['students'] = $students;

        $old_sections = $this->Enrollments_model->get_old_sections($section_id);
        $page_data['sections'] = $old_sections;

        $page_data['page_name'] = 're_enrollments';
        $page_data['page_icon'] = 'entypo-graduation-cap';
        $page_data['page_title'] = ucfirst(get_phrase('re_enrollments')) . ' - ' . $this->crud_model->get_section_history_name($section_id) . " - " . $this->crud_model->get_academic_period_name_per_section_history($section_id);
        $page_data['section_id'] = $section_id;

        $this->load->view('backend/index', $page_data);
    }

    function pre_enrollments()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

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

        $students = $this->Enrollments_model->get_students_for_pre_enrollment();
        $page_data['students'] = $students;

        $page_data['page_name'] = 'pre_enrollments';
        $page_data['page_icon'] = 'entypo-graduation-cap';
        $page_data['page_title'] = ucfirst(get_phrase('enrollment_section'));

        $this->load->view('backend/index', $page_data);
    }

    function preenroll_students($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        if ($param1 == 'create') {
            $student_id = $param2;
            $section_id = $param3;

            $section = $this->Enrollments_model->get_section_by_id($section_id);

            if ($section) {
                $class_id = $section->class_id;
                $academic_period_id = $section->academic_period_id;

                $this->Enrollments_model->update_student_details($student_id, $class_id, $section_id);

                $this->Enrollments_model->insert_academic_history($student_id, $class_id, $section_id, $academic_period_id);

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

            $class_id = isset($segments[$index + 1]) ? $segments[$index + 1] : null;
            $section_id = isset($segments[$index + 2]) ? $segments[$index + 2] : null;
            $students = array_slice($segments, $index + 2);
            $students = array_filter($students, 'is_numeric');

            if (!empty($students)) {
                $this->Enrollments_model->bulk_update_student_details($students, $class_id, $section_id);

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

                redirect(base_url() . 'index.php?admin/pre_enrollments/', 'refresh');
            }
        }
    }

    function re_enrollments_students($param1 = '', $param2 = '', $param3 = '', $param4 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        if ($param1 == 'create') {
            $student_id = $param2;
            $section_id = $param3;

            $section = $this->Enrollments_model->get_section_by_id($section_id);

            if ($section) {
                $class_id = $section->class_id;
                $academic_period_id = $section->academic_period_id;

                $this->Enrollments_model->update_student_details($student_id, $class_id, $section_id);

                $this->Enrollments_model->update_academic_history($student_id, $class_id, $section_id);

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
                redirect(base_url() . 'index.php?admin/re_enrollments/' . $param4, 'refresh');
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
                redirect(base_url() . 'index.php?admin/re_enrollments/' . $param4, 'refresh');
            }
        }

        if ($param1 == 're_enrollment_bulk') {
            $student_ids_json = $this->input->post('selected_student_ids');
            $section_id = $this->input->post('target_section_id');
            $class_id = $this->input->post('target_class_id');
        
            $student_ids = json_decode($student_ids_json, true);

            if (!empty($student_ids)) {
                $this->Enrollments_model->bulk_update_student_details($student_ids, $class_id, $section_id);

                $this->Enrollments_model->bulk_update_academic_history($student_ids, $class_id, $section_id);

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