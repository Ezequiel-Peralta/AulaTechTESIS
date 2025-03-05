<?php

class Schedules extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('schedules/Schedules_model');
        $this->load->library('Schedules_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function schedules($param1 = '', $param2 = '', $param3 = '', $param4 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data = array(
                'day_id' => $this->input->post('day_id'),
                'time_start' => $this->input->post('time_start'),
                'time_end' => $this->input->post('time_end'),
                'status_id' => 1,
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'subject_id' => $this->input->post('subject_id')
            );

            $subject = $this->Schedules_model->get_subject($data['subject_id']);
            if ($subject) {
                $data['teacher_id'] = $subject->teacher_id;
            }

            $result = $this->Schedules_model->create_schedule($data);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('schedule_added_successfully')),
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
                    'title' => ucfirst(get_phrase('error_adding_schedule')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/schedules_information/' . $data['section_id'], 'refresh');
        }

        if ($param1 == 'update') {
            $data = array(
                'day_id' => $this->input->post('day_id'),
                'time_start' => $this->input->post('time_start'),
                'time_end' => $this->input->post('time_end'),
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'subject_id' => $this->input->post('subject_id')
            );

            $subject = $this->Schedules_model->get_subject($data['subject_id']);
            if ($subject) {
                $data['teacher_id'] = $subject->teacher_id;
            }

            $result = $this->Schedules_model->update_schedule($param2, $data);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('schedule_modified_successfully')),
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
                    'title' => ucfirst(get_phrase('error_updating_schedule')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/schedules_information/' . $data['section_id'], 'refresh');
        }

        if ($param1 == 'disable_schedule') {
            $result = $this->Schedules_model->update_schedule_status($param2, 0);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('schedule_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_schedule')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/schedules_information/' . $param3, 'refresh');
        }

        if ($param1 == 'enable_schedule') {
            $result = $this->Schedules_model->update_schedule_status($param2, 1);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('schedule_enabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_enabling_schedule')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/schedules_information/' . $param3, 'refresh');
        }
    }

    function add_schedule()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_schedule')),
                'url' => base_url('index.php?admin/add_schedule')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'add_schedule';
        $page_data['page_title'] = ucfirst(get_phrase('add_schedule'));
        $this->load->view('backend/index', $page_data);
    }

    function edit_schedule($schedule_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

            
        $edit_data = $this->Schedules_model->get_schedule($schedule_id);

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('edit_schedule')),
                'url' => base_url('index.php?admin/edit_schedule')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['schedule_id'] = $schedule_id;
        $page_data['edit_data'] = $edit_data;
        $page_data['page_name'] = 'edit_schedule';
        $page_data['page_title'] = ucfirst(get_phrase('edit_schedule'));
        $this->load->view('backend/index', $page_data);
    }

    function view_schedules($section_id = '', $teacher_id = '')
    {
        if ($this->session->userdata('admin_login') != 1) {
            redirect('login', 'refresh');
        }

        if (empty($section_id)) {
            $active_academic_period = $this->Schedules_model->get_active_academic_period();

            if ($active_academic_period) {
                $active_academic_period_id = $active_academic_period->id;

                $section = $this->Schedules_model->get_first_section($active_academic_period_id);

                if ($section) {
                    $section_id = $section->section_id;
                }
            }
        }

        $used_section_history = false;

        $section_data = $this->Schedules_model->get_section_data($section_id);

        if (empty($section_data)) {
            $section_data = $this->Schedules_model->get_section_history_data($section_id);
            $used_section_history = true;
        }

        $academic_period_name = '';

        if ($used_section_history == true) {
            $academic_period_name = $this->crud_model->get_academic_period_name_per_section2($section_id);
            $page_data['academic_period_id'] = $section_data['academic_period_id'];
        }

        $teacher_data = $this->Schedules_model->get_teacher_data($teacher_id);

        if (!empty($teacher_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_schedules')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst($teacher_data['lastname']) . ', ' . ucfirst($teacher_data['firstname']),
                    'url' => base_url('index.php?admin/view_schedules/' . $section_id . '/' . $teacher_id)
                )
            );
        } else if (!empty($section_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_schedules')) . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'],
                    'url' => base_url('index.php?admin/view_schedules/' . $section_id)
                )
            );
        } else {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_schedules')) . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'],
                    'url' => base_url('index.php?admin/view_schedules/' . $section_id)
                )
            );
        }

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['teacher_id'] = $teacher_id;
        $page_data['used_section_history'] = $used_section_history;
        $page_data['page_name'] = 'view_schedules';
        $page_data['page_title'] = ucfirst(get_phrase('view_schedules'));
        $this->load->view('backend/index', $page_data);
    }

    function manage_schedules()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_schedules')),
                'url' => base_url('index.php?admin/manage_schedules/')
            )
        );

        $active_sections = $this->Schedules_model->get_active_sections();
        $class_ids = array_column($active_sections, 'class_id');
        $all_classes_count = count($active_sections);

        $classes = $this->Schedules_model->get_classes_by_ids($class_ids);

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'page_name' => 'manage_schedules',
            'page_title' => ucfirst(get_phrase('manage_schedules')),
            'active_sections' => $active_sections,
            'all_classes_count' => $all_classes_count,
            'classes' => $classes
        );
        
        $this->load->view('backend/index', $page_data);
    }

    function schedules_information($section_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $section_data = $this->Schedules_model->get_section_data($section_id);

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_schedules')) . ' - ' . $section_data['name'],
                'url' => base_url('index.php?admin/schedules_information/' . $section_id)
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['section_data'] = $section_data;
        $page_data['page_name'] = 'schedules_information';
        $page_data['page_title'] = ucfirst(get_phrase('manage_schedules')) . ' - ' . $section_data['name'];
        $this->load->view('backend/index', $page_data);
    }
}