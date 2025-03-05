<?php

class Library extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('libraryy/Library_model');
        $this->load->library('Library_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function add_library()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?/Admin/dashboard.php')
            ),
            array(
                'text' => ucfirst(get_phrase('add_library')),
                'url' => base_url('index.php?admin/add_library')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'add_library';
        $page_data['page_icon'] = 'entypo-graduation-cap';
        $page_data['page_title'] = ucfirst(get_phrase('add_library'));
        $this->load->view('backend/index', $page_data);
    }

    function library($param1 = '', $param2 = '', $param3 = '', $param4 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        if ($param1 == 'create') {
            $data = array(
                'file_name' => $this->input->post('filename'),
                'date' => $this->input->post('date'),
                'description' => $this->input->post('description'),
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'subject_id' => $this->input->post('subject_id'),
                'status_id' => 1
            );

            $result = $this->library_service->create_library($data, $_FILES['library_file']);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('library_file_added_successfully'),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('error_adding_library_file'),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/view_library/' . $data['section_id'], 'refresh');
        }

        if ($param1 == 'update') {
            $library_id = $param2;
            $data = array(
                'file_name' => $this->input->post('filename'),
                'date' => $this->input->post('date'),
                'description' => $this->input->post('description'),
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'subject_id' => $this->input->post('subject_id')
            );

            $result = $this->library_service->update_library($library_id, $data, $_FILES['library_file']);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('library_updated_successfully'),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('error_updating_library_file'),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/library_information/' . $data['section_id'], 'refresh');
        }

        if ($param1 == 'disable_file') {
            $file_id = $param2;
            $section_id = $param3;

            $result = $this->Library_model->update_library_status($file_id, 0);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('library_file_disabled_successfully'),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('error_disabling_library_file'),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/library_information/' . $section_id, 'refresh');
        }

        if ($param1 == 'enable_file') {
            $file_id = $param2;
            $section_id = $param3;

            $result = $this->Library_model->update_library_status($file_id, 1);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('library_file_enabled_successfully'),
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => get_phrase('error_enabling_library_file'),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => get_phrase('accept'),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/library_information/' . $section_id, 'refresh');
        }
    }
    function edit_library($library_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $edit_data = $this->Library_model->get_file_library($library_id);
        $classes = $this->Library_model->get_classes();
        $subjects = $this->Library_model->get_subjects_by_section($edit_data[0]['section_id']);

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('edit_library')),
                'url' => base_url('index.php?admin/edit_library')
            )
        );

        $page_data['edit_data'] = $edit_data;
        $page_data['classes'] = $classes;
        $page_data['subjects'] = $subjects;
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['library_id'] = $library_id;
        $page_data['page_name'] = 'edit_library';
        $page_data['page_title'] = ucfirst(get_phrase('edit_library'));
        $this->load->view('backend/index', $page_data);
    }

    function view_library($section_id = '', $subject_id = '')
    {
        if ($this->session->userdata('admin_login') != 1) {
            redirect('login', 'refresh');
        }

        if (empty($section_id)) {
            $active_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();

            if ($active_academic_period) {
                $active_academic_period_id = $active_academic_period->id;

                $this->db->where('academic_period_id', $active_academic_period_id);
                $this->db->order_by('section_id', 'ASC');
                $section = $this->db->get('section')->row();

                if ($section) {
                    $section_id = $section->section_id;
                }
            }
        }

        $used_section_history = false;

        $this->db->where('section_id', $section_id);
        $section_data = $this->db->get('section')->row_array();

        if (empty($section_data)) {
            $this->db->where('section_id', $section_id);
            $section_data = $this->db->get('section_history')->row_array();
            $used_section_history = true;
        }

        $used_subject_history = false;

        if (!empty($subject_id)) {
            $this->db->where('subject_id', $subject_id);
            $subject_data = $this->db->get('subject')->row_array();

            if (empty($subject_data)) {
                $this->db->where('subject_id', $subject_id);
                $subject_data = $this->db->get('subject_history')->row_array();
                $used_subject_history = true;
            }
        } else {
            $subject_data = array();
            $used_subject_history = false;
        }

        $academic_period_name = '';
        if ($used_subject_history == true) {
            $academic_period_name = $this->crud_model->get_academic_period_name_per_section2($section_id);
            $page_data['academic_period_id'] = $section_data['academic_period_id'];
        }

        if ($used_section_history == true) {
            $academic_period_name = $this->crud_model->get_academic_period_name_per_section2($section_id);
            $page_data['academic_period_id'] = $section_data['academic_period_id'];
        }

        if (empty($subject_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_library')) . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'],
                    'url' => base_url('index.php?admin/view_library/' . $section_id)
                )
            );
        } else {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_library')) . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'] . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst($subject_data['name']),
                    'url' => base_url('index.php?admin/view_library/' . $section_id . '/' . $subject_id)
                )
            );
        }

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['subject_id'] = $subject_id;
        $page_data['page_name'] = 'view_library';
        $page_data['used_subject_history'] = $used_subject_history;
        $page_data['used_section_history'] = $used_section_history;
        $page_data['page_title'] 	= ucfirst(get_phrase('view_library'));
        $this->load->view('backend/index', $page_data);
    }

    function manage_library()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_library')),
                'url' => base_url('index.php?admin/manage_library/')
            )
        );

        $active_sections = $this->Library_model->get_active_sections();
        $class_ids = array_column($active_sections, 'class_id');
        $all_classes_count = count($active_sections);

        $classes = $this->Library_model->get_classes_by_ids($class_ids);

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'page_name' => 'manage_library',
            'page_title' => ucfirst(get_phrase('manage_library')),
            'active_sections' => $active_sections,
            'all_classes_count' => $all_classes_count,
            'classes' => $classes
        );
                
		$this->load->view('backend/index', $page_data);
	}

    function library_information($section_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
      
        $section_data = $this->Library_model->get_section_data($section_id);

        $section_subject_count = $this->Library_model->get_section_subject_count($section_id);

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_library')) . ' - ' . $section_data['name'],
                'url' => base_url('index.php?admin/library_information/' . $section_id)
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['section_data'] = $section_data;
        $page_data['section_subject_count'] = $section_subject_count;
		$page_data['page_name']  = 'library_information';
		$page_data['page_title'] 	= ucfirst(get_phrase('manage_library')) . ' - ' . $section_data['name'];
		$this->load->view('backend/index', $page_data);
	}

    

}