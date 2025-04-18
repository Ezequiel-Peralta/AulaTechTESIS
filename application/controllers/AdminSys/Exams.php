<?php

class Exams extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('students/Students_model');
        $this->load->model('subjects/Subjects_model');
        $this->load->model('exams/Exams_model');
        $this->load->library('Exams_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function exams_edit($param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('exam_edit')),
                'url' => base_url('index.php?admin/exams_edit')
            )
        );

        $exam_types = $this->Exams_model->get_exam_types();
        $edit_data = $this->Exams_model->get_exam_info($param2);

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'param2' => $param2,
            'page_name' => 'exam_edit',
            'page_title' => ucfirst(get_phrase('exam_edit')),
            'exam_types' => $exam_types,
            'edit_data' => $edit_data
        );

        $this->load->view('backend/index', $page_data);
    }

    function exams_add()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('exam_add')),
                'url' => base_url('index.php?admin/exams_add')
            )
        );

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'page_name' => 'exam_add',
            'page_title' => ucfirst(get_phrase('exam_add'))
        );
        
        $this->load->view('backend/index', $page_data);
    }

    function exams_information($section_id = '', $subject_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $subject_data = $this->Subjects_model->get_subject($subject_id);
        $active_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();
        $sections = array();

        if ($active_academic_period) {
            $this->db->where('academic_period_id', $active_academic_period->id);
            $sections = $this->db->get('section')->result_array();
        }

        $all_exams_count = $this->exams_service->get_all_exams_count($section_id, $subject_id);
        $exams = $this->exams_service->get_exams($section_id, $subject_id);

        foreach ($exams as &$exam) {
            $exam['exam_type_name'] = $this->Exams_model->get_exam_type_name($exam['exam_type_id']);
            $exam['subject_name'] = $this->Exams_model->get_subject_name($exam['subject_id']);
        }

        if (!empty($subject_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('manage_exams')) . "&nbsp;&nbsp;/&nbsp;&nbsp;" . $this->crud_model->get_section_name($section_id) . "&nbsp;&nbsp;/&nbsp;&nbsp;" . ucfirst($subject_data['name']),
                    'url' => base_url('index.php?admin/exams_information/' . $section_id . '/' . $subject_id)
                )
            );

            $page_title = ucfirst(get_phrase('manage_exams')) . " - " . $this->crud_model->get_section_name($section_id) . " - " . ucfirst($subject_data['name']);
        } else {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('manage_exams')) . " - " . $this->crud_model->get_section_name($section_id),
                    'url' => base_url('index.php?admin/exams_information/' . $section_id)
                )
            );

            $page_title = ucfirst(get_phrase('manage_exams')) . " - " . $this->crud_model->get_section_name($section_id);
        }

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'subject_id' => $subject_id,
            'page_name' => 'exams_information',
            'page_title' => $page_title,
            'section_id' => $section_id,
            'sections' => $sections,
            'all_exams_count' => $all_exams_count,
            'exams' => $exams
        );
        $this->load->view('backend/index', $page_data);
    }

    function manage_exams()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_exams')),
                'url' => base_url('index.php?admin/manage_exams/')
            )
        );

        $page_data = array(
            'breadcrumb' => $breadcrumb,
            'page_name' => 'manage_exams',
            'page_title' => ucfirst(get_phrase('manage_exams'))
        );
        
        $this->load->view('backend/index', $page_data);
    }

    function exams($param1 = '', $param2 = '', $param3 = '', $param4 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data['name'] = $this->input->post('name');
            $data['date'] = $this->input->post('date');
            $data['status_id'] = $this->input->post('status_id');
            $data['exam_type_id'] = $this->input->post('exam_type_id');
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $data['subject_id'] = $this->input->post('subject_id');

            $data['teacher_id'] = $this->exams_service->get_teacher_id_by_subject($data['subject_id']);

            $exam_id = $this->exams_service->create_exam($data);

            if ($exam_id && !empty($_FILES['attachments']['name'])) {
                $this->exams_service->upload_exam_files($exam_id, $_FILES['attachments']);
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => '¡' . ucfirst(get_phrase('evaluation_added_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            redirect(base_url() . 'index.php?admin/exams_information/' . $data['section_id'], 'refresh');
        }

        if ($param1 == 'update') {
            $data['name'] = $this->input->post('name');
            $data['date'] = $this->input->post('date');
            $data['status_id'] = $this->input->post('status_id');
            $data['exam_type_id'] = $this->input->post('exam_type');
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $data['subject_id'] = $this->input->post('subject_id');

            $data['teacher_id'] = $this->exams_service->get_teacher_id_by_subject($data['subject_id']);

            $this->exams_service->update_exam($param2, $data);

            $existing_files = isset($_POST['existing_files']) && is_array($_POST['existing_files']) ? $_POST['existing_files'] : [];

$files_to_delete_raw = isset($_POST['files_to_delete']) ? $_POST['files_to_delete'] : '';
$files_to_delete = [];

if (is_string($files_to_delete_raw)) {
    $files_to_delete = array_filter(explode(',', $files_to_delete_raw));
}

if (!empty($files_to_delete)) {
    $exam_directory = 'uploads/exams/' . $param2 . '/';
    foreach ($files_to_delete as $file) {
        $file_path = $exam_directory . $file;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    $updated_files = array_values(array_diff($existing_files, $files_to_delete));
    $this->Exams_model->update_exam_files($param2, ['files' => json_encode($updated_files)]);
}


            if (!empty($_FILES['attachments']['name'][0])) {
                $this->exams_service->upload_exam_files($param2, $_FILES['attachments']);
            }


            $this->session->set_flashdata('flash_message', array(
                'title' => '¡' . ucfirst(get_phrase('evaluation_modified_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            redirect(base_url() . 'index.php?admin/exams_information/' . $data['section_id'], 'refresh');
        }

        if ($param1 == 'disable_exams_bulk') {
            $this->exams_service->bulk_update_exam_status($this->uri->segment_array(), 'disable');

            $this->session->set_flashdata('flash_message', array(
                'title' => '¡' . ucfirst(get_phrase('evaluations_disabled_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => true,
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => 10000,
                'timerProgressBar' => true,
            ));

            redirect(base_url() . 'index.php?admin/exams_information/' . $param2, 'refresh');
        }

        

        if ($param1 == 'disable_exams') {
            $this->exams_service->update_exam_status($param2, 0);

            $this->session->set_flashdata('flash_message', array(
                'title' => '¡' . ucfirst(get_phrase('evaluation_disabled_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            redirect(base_url() . 'index.php?admin/exams_information/' . $param3, 'refresh');
        }

        if ($param1 == 'enable_exams') {
            $this->exams_service->update_exam_status($param2, 1);

            $this->session->set_flashdata('flash_message', array(
                'title' => '¡' . ucfirst(get_phrase('evaluation_enabled_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            redirect(base_url() . 'index.php?admin/exams_information/' . $param3, 'refresh');
        }

        if ($param1 == 'enable_exams_bulk') {
            $this->exams_service->bulk_update_exam_status($this->uri->segment_array(), 'enable');

            $this->session->set_flashdata('flash_message', array(
                'title' => '¡' . ucfirst(get_phrase('evaluations_enabled_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => true,
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => 10000,
                'timerProgressBar' => true,
            ));

            redirect(base_url() . 'index.php?admin/exams_information/' . $param2, 'refresh');
        }
    
    }

    function view_exams($section_id = '', $subject_id = '', $teacher_id = '')
{
    if ($this->session->userdata('admin_login') != 1) {
        redirect('login', 'refresh');
    }

    if (empty($section_id)) {
        $section_id = $this->db->select('section_id')
                               ->order_by('section_id', 'ASC')
                               ->limit(1)
                               ->get('section')
                               ->row_array()['section_id'];
    }

    $section_data = $this->exams_service->get_section_data($section_id);
    $teacher_data = $this->exams_service->get_teacher_data($teacher_id);

    $used_section_history = $section_data['used_section_history'];
    $academic_period_name = $section_data['academic_period_name'];

    if (!empty($subject_id)) {
        $subject_data = $this->exams_service->get_subject_data($subject_id);
        $used_subject_history = $subject_data['used_subject_history'];
    } else {
        $subject_data = [];
        $used_subject_history = false;
    }
 
    $section_name = isset($section_data['section_data']['name']) ? $section_data['section_data']['name'] : '';
    $subject_name = isset($subject_data['subject_data']['name']) ? $subject_data['subject_data']['name'] : '';
    $teacher_name = isset($teacher_data['lastname']) && isset($teacher_data['firstname']) ? ucfirst($teacher_data['lastname']) . ', ' . ucfirst($teacher_data['firstname']) : '';

    $breadcrumb = array(
        array(
            'text' => ucfirst(get_phrase('home')),
            'url' => base_url('index.php?admin/dashboard')
        )
    );

    switch (true) {
        case !empty($teacher_id):
            $breadcrumb[] = array(
                'text' => ucfirst(get_phrase('view_exams')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $teacher_name,
                'url' => base_url('index.php?admin/view_exams/' . $section_id . '/' . $subject_id . '/' . $teacher_id)
            );
            break;
        case !empty($subject_id):
            $breadcrumb[] = array(
                'text' => ucfirst(get_phrase('view_exams'))  . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_name . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst($subject_name),
                'url' => base_url('index.php?admin/view_exams/' . $section_id . '/' . $subject_id)
            );
            break;
        default:
            $breadcrumb[] = array(
                'text' => ucfirst(get_phrase('view_exams'))  . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_name,
                'url' => base_url('index.php?admin/view_exams/' . $section_id)
            );
            break;
    }

    $exams = $this->exams_service->get_exams($section_id, $subject_id, $teacher_id, $used_section_history, $used_subject_history);

    $page_data = array(
        'teacher_id' => $teacher_id,
        'breadcrumb' => $breadcrumb,
        'section_id' => $section_id,
        'subject_id' => $subject_id,
        'used_subject_history' => $used_subject_history,
        'used_section_history' => $used_section_history,
        'page_name' => 'view_exams',
        'page_title' => ucfirst(get_phrase('view_exams')),
        'exams' => $exams
    );

    $this->load->view('backend/index', $page_data);
}

    function get_subject_exams($subject_id)
    {
        $exams = $this->Exams_model->get_exams_by_subject($subject_id);
        foreach ($exams as $row) {
            echo '<option value="' . $row['exam_id'] . '">' . $row['name'] . '</option>';
        }
    }















}