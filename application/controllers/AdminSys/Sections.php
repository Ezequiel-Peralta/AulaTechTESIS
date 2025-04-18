<?php

class Sections extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('sections/Sections_model');
        $this->load->model('teacherAide/TeacherAide_model');
        $this->load->library('Sections_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function section_add()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_section')),
                'url' => base_url('index.php?admin/section_add')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name'] = 'section_add';
        $page_data['page_icon'] = 'entypo-graduation-cap';
        $page_data['page_title'] = ucfirst(get_phrase('add_section'));
        $this->load->view('backend/index', $page_data);
    }

    function sections_profile($section_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_sections')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/sections_profile/')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name'] = 'section_profile';
        $page_data['page_title'] = ucfirst(get_phrase('manage_sections')) . ' / ' . ucfirst(get_phrase('view_profile'));
        $page_data['section_id'] = $section_id;

        $this->load->view('backend/index', $page_data);
    }

    function section($class_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        // detect the first class
        if ($class_id == '')
            $class_id = $this->Sections_model->get_first_class_id();

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_sections')),
                'url' => base_url('index.php?admin/section')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name'] = 'section';
        $page_data['page_title'] = ucfirst(get_phrase('manage_sections'));
        $page_data['class_id'] = $class_id;
        $this->load->view('backend/index', $page_data);
    }

    function sections($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name'] = $this->input->post('name');
            $data['letter_name'] = $this->input->post('letter_name');
            $data['shift_id'] = $this->input->post('shift_id');
            $data['class_id'] = $this->input->post('class_id');
            $data['teacher_aide_id'] = null;
            $data['status_id'] = 1;

            $academic_period = $this->Sections_model->get_active_academic_period();
            $data['academic_period_id'] = $academic_period ? $academic_period->id : '';

            $this->Sections_model->insert_section($data);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('section_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/section/', 'refresh');
        }

        if ($param1 == 'update') {
            $data['name'] = $this->input->post('name');
            $data['letter_name'] = $this->input->post('letter_name');
            $data['class_id'] = $this->input->post('class_id');
            $data['shift_id'] = $this->input->post('shift_id');
            $this->Sections_model->update_section($param2, $data);
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('section_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/section/', 'refresh');
        }

        if ($param1 == 'disable_section') {
            $section_id = $param2;

            if ($section_id) {
                $this->Sections_model->update_section_status($section_id, 0);

                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('section_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_section')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }

            redirect(base_url() . 'index.php?admin/section/', 'refresh');
        }

        if ($param1 == 'enable_section') {
            $section_id = $param2;

            if ($section_id) {
                $this->Sections_model->update_section_status($section_id, 1);

                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('section_enabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_enabling_section')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }

            redirect(base_url() . 'index.php?admin/section/', 'refresh');
        }
    }

    function get_class_sections($class_id)
    {
        $sections = $this->Sections_model->get_sections_by_class($class_id);
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_class_all_section()
    {
        $sections = $this->Sections_model->get_all_sections();
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_sections_content_by_class($class_id)
    {
        $sections = $this->Sections_model->get_sections_by_class($class_id);
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_sections_content_by_academic_period($academic_period_id, $url)
    {
        $sections = $this->Sections_model->get_sections_by_academic_period($academic_period_id);
        foreach ($sections as $row) {
            echo '<option value="' . base_url() . 'index.php?admin/' . $url . '/' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function section_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $data['subject_id'] = $this->input->post('subject_id');
            $data['time_start'] = $this->input->post('time_start');
            $data['time_end'] = $this->input->post('time_end');
            $data['day_id'] = $this->input->post('day_id');
            $this->Sections_model->insert_section_routine($data);
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Horario agregado correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/section_routine/', 'refresh');
        }
        if ($param1 == 'update') {
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $data['subject_id'] = $this->input->post('subject_id');
            $data['time_start'] = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
            $data['time_end'] = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
            $data['day_id'] = $this->input->post('day_id');

            $this->Sections_model->update_section_routine($param2, $data);
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Horario modificado correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/section_routine/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->Sections_model->get_section_routine_by_id($param2);
        }
        if ($param1 == 'delete') {
            $this->Sections_model->delete_section_routine($param2);
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Horario eliminado correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/section_routine/', 'refresh');
        }

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_schedules')),
                'url' => base_url('index.php?admin/class_routine')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name'] = 'section_routine';
        $page_data['page_icon'] = 'entypo-clock';
        $page_data['page_title'] = ucfirst(get_phrase('manage_schedules'));
        $this->load->view('backend/index', $page_data);
    }

    function get_section_content()
    {
        $sections = $this->Sections_model->get_all_sections();
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_section_content2()
    {
        $sections = $this->Sections_model->get_active_sections();
        echo '<option value="">' . 'seleccionar' . '</option>';
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }
}