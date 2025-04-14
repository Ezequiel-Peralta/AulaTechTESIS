<?php

class Behaviors extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('behaviors/behaviors_model');
        $this->load->library('Behaviors_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function add_behavior($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_behavior')),
                'url' => base_url('index.php?admin/add_behavior')
            )
        );

        $classes = $this->behaviors_model->get_classes();
        $sections = $this->behaviors_model->get_sections_by_class($param2);
        $students = $this->behaviors_model->get_students_by_section($param3);
        $behavior_types = $this->behaviors_model->get_behavior_types();

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'page_name' => 'add_behavior',
            'student_id' => $param1,
            'class_id' => $param2,
            'section_id' => $param3,
            'page_title' => ucfirst(get_phrase('add_behavior')),
            'classes' => $classes,
            'sections' => $sections,
            'students' => $students,
            'behavior_types' => $behavior_types
        );

        $this->load->view('backend/index', $page_data);
    }

    function edit_behavior($behavior_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('edit_behavior')),
                'url' => base_url('index.php?admin/edit_behavior')
            )
        );

        $behavior_data = $this->behaviors_model->get_behavior_student($behavior_id);
        $classes = $this->behaviors_model->get_classes();
        $sections = $this->behaviors_model->get_sections_by_class($behavior_data['class_id']);
        $students = $this->behaviors_model->get_students_by_section($behavior_data['section_id']);
        $behavior_types = $this->behaviors_model->get_behavior_types();
        
        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'behavior_id' => $behavior_id,
            'page_title' => ucfirst(get_phrase('edit_behavior')),
            'page_name' => 'edit_behavior',
            'behavior_data' => $behavior_data,
            'classes' => $classes,
            'sections' => $sections,
            'students' => $students,
            'behavior_types' => $behavior_types
        );

        $this->load->view('backend/index', $page_data);
    }

    function behavior($section_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_behavior')) . "&nbsp;&nbsp;/&nbsp;&nbsp;" . $this->crud_model->get_section_name($section_id),
                'url' => base_url('index.php?admin/behavior/' . $section_id)
            )
        );

        $students = $this->Behaviors_model->get_students_by_section($section_id);
        $all_student_count = count($students);

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'students' => $students,
            'all_student_count' => $all_student_count,
            'page_name' => 'behavior',
            'page_title' => ucfirst(get_phrase('manage_behavior')) . ' - ' . $this->crud_model->get_section_name($section_id),
            'section_id' => $section_id
        );

        $this->load->view('backend/index', $page_data);
    }

    function behavior_information($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        if ($param1 == 'create') {
            $data = array(
                'behavior_type_id' => $this->input->post('type'),
                'date' => $this->input->post('date'),
                'comment' => $this->input->post('comment'),
                'student_id' => $this->input->post('student_id'),
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'status_id' => 1
            );

            $success = $this->Behaviors_model->create_behavior($data);

            if ($success) {
                // Mensaje de exito
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('student_behavior_added_successfully')),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                // Mensaje de error
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('error_adding_behavior')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#d9534f',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/student_behavior/' . $data['student_id'], 'refresh');
        }

        if ($param1 == 'update') {
            $data = array(
                'behavior_type_id' => $this->input->post('type'),
                'date' => $this->input->post('date'),
                'comment' => $this->input->post('comment'),
                'student_id' => $this->input->post('student_id'),
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id')
            );

            $this->Behaviors_model->update_behavior($param2, $data);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('student_behavior_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/student_behavior/' . $data['student_id'], 'refresh');
        }

        if ($param1 == 'disable_behavior') {
            $this->Behaviors_model->update_behavior_status($param2, 0);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('behavior_disabled_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/student_behavior/' . $param3, 'refresh');
        }

        if ($param1 == 'enable_behavior') {
            $this->Behaviors_model->update_behavior_status($param2, 1);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('behavior_enabled_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/student_behavior/' . $param3, 'refresh');
        }
    }

    function student_behavior($student_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $student_behavior = $this->Behaviors_model->get_student_behavior($student_id);
        $student = $student_behavior['student_data'];
        $behaviors = $student_behavior['behavior_data'];

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('student_behavior')) . ' - ' . $student['lastname'] . ", " . $student['firstname'] . ".",
                'url' => base_url('index.php?admin/student_behavior/' . $student_id)
            )
        );

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'student' => $student,
            'behaviors' => $behaviors,
            'student_id' => $student_id,
            'page_name' => 'student_behavior',
            'page_icon' => 'entypo-graduation-cap',
            'page_title' => ucfirst(get_phrase('student_behavior')) . ' - ' . $student['lastname'] . ", " . $student['firstname']
        );

        $this->load->view('backend/index', $page_data);
    }

    function manage_behavior()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_behavior')),
                'url' => base_url('index.php?admin/manage_behavior/')
            )
        );

        $active_sections = $this->Behaviors_model->get_active_sections();
        $class_ids = array_column($active_sections, 'class_id');
        $all_classes_count = count($active_sections);

        $classes = $this->Behaviors_model->get_classes_by_ids($class_ids);

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'page_name' => 'manage_behavior',
            'page_title' => ucfirst(get_phrase('manage_behavior')),
            'active_sections' => $active_sections,
            'all_classes_count' => $all_classes_count,
            'classes' => $classes
        );

        $this->load->view('backend/index', $page_data);
    }

}