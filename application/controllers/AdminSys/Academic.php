<?php

class Academic extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('academic/Academic_model');
        $this->load->library('Academic_service');

        $this->load->model('exams/Exams_model');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function academic_period($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data['name'] = $this->input->post('name');
            $data['start_date'] = $this->input->post('start_date');
            $data['end_date'] = $this->input->post('end_date');
            $data['status_id'] = 1;

            $result = $this->academic_service->create_academic_period($data);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('academic_period_added_successfully')),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('error_adding_academic_period')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/academic_period/', 'refresh');
        }

        if ($param1 == 'update') {
            $data['name'] = $this->input->post('name');
            $data['start_date'] = $this->input->post('start_date');
            $data['end_date'] = $this->input->post('end_date');

            $result = $this->Academic_model->update_academic_period($param2, $data);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('academic_period_updated_successfully')),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('error_updating_academic_period')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/academic_period/', 'refresh');
        }

        if ($param1 == 'disable_academic_period') {
            $result = $this->Academic_model->update_academic_period_status($param2, 0);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('academic_period_disabled_successfully')),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('error_disabling_academic_period')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/academic_period/', 'refresh');
        }

        if ($param1 == 'enable_academic_period') {
            $result = $this->Academic_model->update_academic_period_status($param2, 1);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('academic_period_enabled_successfully')),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('error_enabling_academic_period')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/academic_period/', 'refresh');
        }

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_academic_session')),
                'url' => base_url('index.php?admin/academic_period')
            )
        );

        $academic_period = $this->Academic_model->get_all_academic_periods();
        $period_count = count($academic_period);

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'academic_period' => $academic_period,
            'period_count' => $period_count,
            'page_name' => 'academic_period',
            'page_icon' => 'entypo-clipboard',
            'page_title' => ucfirst(get_phrase('manage_academic_session'))
        );

        $this->load->view('backend/index', $page_data);
    }

    function academic_history($section_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $students = $this->Academic_model->get_students_by_section($section_id);
        $section_name = $this->crud_model->get_section_name($section_id);
        $active_academic_period = $this->Academic_model->get_active_academic_period();
        $sections = $this->Academic_model->get_sections_by_academic_period($active_academic_period->id);
        $all_student_count = count($students);

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('academic_history')) . "&nbsp;&nbsp;/&nbsp;&nbsp;" . $this->crud_model->get_section_name($section_id),
                'url' => base_url('index.php?admin/academic_history/' . $section_id)
            )
        );

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'students' => $students,
            'section_name' => $section_name,
            'active_academic_period' => $active_academic_period,
            'sections' => $sections,
            'all_student_count' => $all_student_count,
            'section_id' => $section_id,
            'page_name' => 'academic_history',
            'page_title' => ucfirst(get_phrase('academic_history')) . ' - ' . $section_name
        );

        $this->load->view('backend/index', $page_data);
    }

    function academic_period_add()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('academic_period_add')),
                'url' => base_url('index.php?admin/academic_period_add')
            )
        );

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'page_name' => 'academic_period_add',
            'page_title' => ucfirst(get_phrase('academic_period_add'))
        );

        $this->load->view('backend/index', $page_data);
    }

    function manage_academic_history()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $active_sections = $this->Academic_model->get_active_sections();
        $classes = $this->Academic_model->get_all_classes();

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_academic_history')),
                'url' => base_url('index.php?admin/manage_academic_history/')
            )
        );

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'active_sections' => $active_sections,
            'classes' => $classes,
            'page_name' => 'manage_academic_history',
            'page_title' => ucfirst(get_phrase('manage_academic_history'))
        );
        $this->load->view('backend/index', $page_data);
    }

    function view_students_academic_history($student_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $student_data = $this->Academic_model->get_student_details2($student_id);

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('view_academic_history')) . ' ' . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $student_data['lastname'] . ', ' . $student_data['firstname'],
                'url' => base_url('index.php?admin/view_students_academic_history/' . $student_id)
            )
        );

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'student_data' => $student_data,
            'student_id' => $student_id,
            'page_name' => 'view_student_academic_history',
            'page_title' => ucfirst(get_phrase('view_academic_history')) . ' - ' . ucfirst($student_data['lastname']) . ', ' . ucfirst($student_data['firstname'])
        );

        $this->load->view('backend/index', $page_data);
    }
}