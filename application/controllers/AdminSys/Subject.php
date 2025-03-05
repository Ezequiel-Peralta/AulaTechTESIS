<?php

class Subject extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('subject/Subject_model');
        $this->load->library('Subject_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function subject_profile($subject_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('subject_profile')),
                'url' => base_url('index.php?admin/subject_profile/')
            )
        );

        $subject_info = $this->Subject_model->get_subject_info($subject_id);

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'subject_profile';
        $page_data['page_title'] = ucfirst(get_phrase('subject_profile'));
        $page_data['subject_info'] = $subject_info;
        $page_data['subject_id'] = $subject_id;

        $this->load->view('backend/index', $page_data);
    }

    function subjects($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data = array(
                'name' => $this->input->post('name'),
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'teacher_id' => $this->input->post('teacher_id'),
                'status_id' => 1
            );

            $section = $this->Subject_model->get_section($data['section_id']);
            if ($section) {
                $data['teacher_aide_id'] = $section->teacher_aide_id;
            }

            $createdSubjectId = $this->Subject_model->create_subject($data);

            // Manejar la imagen
            if (!empty($_FILES['userfile']['name'])) {
                $file_name = 'subject id - ' . $createdSubjectId . '.jpg';
                $file_path = 'uploads/subject_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);

                $this->Subject_model->update_subject_image($createdSubjectId, $file_name);
            } else {
                $default_image = 'assets/images/default-subject-img.jpg';
                $this->Subject_model->update_subject_image($createdSubjectId, $default_image);
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('subject_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            redirect(base_url() . 'index.php?admin/subjects_information/' . $data['section_id'], 'refresh');
        }

        if ($param1 == 'update') {
            $subject_id = $param2;

            $current_subject_data = $this->Subject_model->get_subject($subject_id);

            $data = array(
                'name' => $this->input->post('name'),
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'teacher_id' => $this->input->post('teacher_id')
            );

            $section = $this->Subject_model->get_section($data['section_id']);
            if ($section) {
                $data['teacher_aide_id'] = $section->teacher_aide_id;
            }

            if (!empty($_FILES['userfile']['name'])) {
                if (!empty($current_subject_data['image']) && file_exists($current_subject_data['image'])) {
                    unlink($current_subject_data['image']);
                }

                $file_name = 'subject id - ' . $subject_id . '.jpg';
                $file_path = 'uploads/subject_image/' . $file_name;
                $data['image'] = $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
            } else {
                $data['image'] = $current_subject_data['image'];
            }

            $this->Subject_model->update_subject($subject_id, $data);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('subject_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/subjects_information/' . $data['section_id'], 'refresh');
        }

        if ($param1 == 'disable_subject') {
            $subject_id = $param2;

            if ($subject_id) {
                $this->Subject_model->update_subject_status($subject_id, 0);

                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('subject_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_subject')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }

            redirect(base_url() . 'index.php?admin/subjects_information/' . $param3, 'refresh');
        }

        if ($param1 == 'enable_subject') {
            $subject_id = $param2;

            if ($subject_id) {
                $this->Subject_model->update_subject_status($subject_id, 1);

                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('subject_enabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_enabling_subject')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }

            redirect(base_url() . 'index.php?admin/subjects_information/' . $param3, 'refresh');
        }
    }

    function get_class_subject($class_id)
    {
        $subjects = $this->Subject_model->get_subjects_by_class($class_id);
        foreach ($subjects as $row) {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_section_subjects($section_id)
    {
        $subjects = $this->Subject_model->get_subjects_by_section($section_id);
        foreach ($subjects as $row) {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function manage_subjects()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_subjects')),
                'url' => base_url('index.php?admin/manage_subjects/')
            )
        );

        $active_sections = $this->Subject_model->get_active_sections();
        $class_ids = array_column($active_sections, 'class_id');
        $all_classes_count = count($active_sections);

        $classes = $this->Subject_model->get_classes_by_ids($class_ids);

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'page_name' => 'manage_subjects',
            'page_title' => ucfirst(get_phrase('manage_subjects')),
            'active_sections' => $active_sections,
            'all_classes_count' => $all_classes_count,
            'classes' => $classes
        );

        $this->load->view('backend/index', $page_data);
    }

    function view_subjects($section_id = '', $teacher_id = '')
    {
        if ($this->session->userdata('admin_login') != 1) {
            redirect('login', 'refresh');
        }

        if (empty($section_id)) {
            $active_academic_period = $this->Subject_model->get_active_academic_period();

            if ($active_academic_period) {
                $active_academic_period_id = $active_academic_period->id;

                $section = $this->Subject_model->get_first_section($active_academic_period_id);

                if ($section) {
                    $section_id = $section->section_id;
                }
            }
        }

        $used_section_history = false;

        $section_data = $this->Subject_model->get_section_data($section_id);

        if (empty($section_data)) {
            $section_data = $this->Subject_model->get_section_history_data($section_id);
            $used_section_history = true;
        }

        $academic_period_name = '';
        if ($used_section_history == true) {
            $academic_period_name = $this->crud_model->get_academic_period_name_per_section2($section_id);
            $page_data['academic_period_id'] = $section_data['academic_period_id'];
        }

        $teacher_data = $this->Subject_model->get_teacher_data($teacher_id);

        if (!empty($teacher_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_subjects')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst($teacher_data['lastname']) . ', ' . ucfirst($teacher_data['firstname']),
                    'url' => base_url('index.php?admin/view_subjects/' . $section_id . '/' . $teacher_id)
                )
            );
        } else if (!empty($section_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_subjects')) . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') .  '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'],
                    'url' => base_url('index.php?admin/view_subjects/' . $section_id)
                )
            );
        } else {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_subjects')) .  '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'],
                    'url' => base_url('index.php?admin/view_subjects/' . $section_id)
                )
            );
        }

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['teacher_id'] = $teacher_id;
        $page_data['used_section_history'] = $used_section_history;
        $page_data['page_name'] = 'view_subjects';
        $page_data['page_title'] = ucfirst(get_phrase('view_subjects'));
        $this->load->view('backend/index', $page_data);
    }

    function subjects_information($section_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('subjects_information')),
                'url' => base_url('index.php?admin/subjects_information/' . $section_id)
            )
        );

        $section_data = $this->Subject_model->get_section_data($section_id);

        $all_subjects_count = $this->Subject_model->get_section_subject_count($section_id);

        $page_data['section_data'] = $section_data;
        $page_data['all_subjects_count'] = $all_subjects_count;
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['page_name'] = 'subjects_information';
        $page_data['page_title'] = ucfirst(get_phrase('subjects_information'));
        $this->load->view('backend/index', $page_data);
    }

    function add_subject()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_subject')),
                'url' => base_url('index.php?admin/subject_add')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'add_subject';
        $page_data['page_title'] = ucfirst(get_phrase('add_subject'));
        $this->load->view('backend/index', $page_data);
    }

    function edit_subject($subject_id)
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('edit_subject')),
                'url' => base_url('index.php?admin/edit_subject/' . $subject_id)
            )
        );

        $subject_info = $this->Subject_model->get_subject_info($subject_id);

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'edit_subject';
        $page_data['page_title'] = ucfirst(get_phrase('edit_subject'));
        $page_data['subject_info'] = $subject_info;
        $page_data['subject_id'] = $subject_id;

        $this->load->view('backend/index', $page_data);
    }
}