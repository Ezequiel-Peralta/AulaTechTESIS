<?php

class Teachers extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('teachers/Teachers_model');
        $this->load->library('Teachers_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function teachers_information()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_teachers')),
                'url' => base_url('index.php?admin/teachers_information/')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['teachers'] = $this->Teachers_model->get_all_teachers();
        $page_data['page_name'] = 'teachers_information';
        $page_data['page_title'] = ucfirst(get_phrase('manage_teachers'));

        $this->load->view('backend/index', $page_data);
    }

    function teachers_profile($teacher_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_teachers')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/teacher_profile/' . $teacher_id)
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['teacher'] = $this->Teachers_model->get_teacher_info($teacher_id);
        $page_data['page_name'] = 'teacher_profile';
        $page_data['param2'] = $teacher_id;
        $page_data['page_title'] = ucfirst(get_phrase('view_profile'));

        $this->load->view('backend/index', $page_data);
    }

    function teacher($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');

            $insertedTeacherId = $this->Teachers_model->insert_teacher($data);

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $insertedAddressId = $this->Teachers_model->insert_address($dataAddress);

            $dataDetails['teacher_id'] = $insertedTeacherId;
            $dataDetails['address_id'] = $insertedAddressId;
            $dataDetails['user_group_id'] = '3';
            $dataDetails['firstname'] = $this->input->post('firstname');
            $dataDetails['lastname'] = $this->input->post('lastname');
            $dataDetails['dni'] = $this->input->post('dni');
            $dataDetails['birthday'] = $this->input->post('birthday');
            $dataDetails['phone_cel'] = $this->input->post('phone_cel');
            $dataDetails['phone_fij'] = $this->input->post('phone_fij');
            $dataDetails['gender_id'] = $this->input->post('gender_id');
            $dataDetails['user_status_id'] = 1;

            if (!empty($_FILES['userfile']['name'])) {
                $file_name = 'teacher id - ' . $insertedTeacherId . '.jpg';
                $file_path = 'uploads/teacher_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
                $dataDetails['photo'] = $file_path;
            } else {
                $dataDetails['photo'] = 'assets/images/default-user-img.jpg';
            }

            $this->Teachers_model->insert_teacher_details($dataDetails);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('teacher_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/teachers_information/', 'refresh');
        }

        if ($param1 == 'update') {
            $teacher_id = $param2;
            $teacher_details = $this->Teachers_model->get_teacher_details($teacher_id);
            $address_id = $teacher_details['address_id'];

            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');

            $this->Teachers_model->update_teacher($teacher_id, $data);

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $this->Teachers_model->update_address($address_id, $dataAddress);

            $dataDetails['firstname'] = $this->input->post('firstname');
            $dataDetails['lastname'] = $this->input->post('lastname');
            $dataDetails['dni'] = $this->input->post('dni');
            $dataDetails['birthday'] = $this->input->post('birthday');
            $dataDetails['phone_cel'] = $this->input->post('phone_cel');
            $dataDetails['phone_fij'] = $this->input->post('phone_fij');
            $dataDetails['gender_id'] = $this->input->post('gender_id');

            if (!empty($_FILES['userfile']['name'])) {
                if (!empty($teacher_details['photo']) && file_exists($teacher_details['photo'])) {
                    unlink($teacher_details['photo']);
                }
                $file_name = 'teacher id - ' . $teacher_id . '.jpg';
                $file_path = 'uploads/teacher_image/' . $file_name;
                $dataDetails['photo'] = $file_path;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
            } else {
                $dataDetails['photo'] = $teacher_details['photo'];
            }

            $this->Teachers_model->update_teacher_details($teacher_id, $dataDetails);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('teacher_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            redirect(base_url() . 'index.php?admin/teachers_information/', 'refresh');
        }

        if ($param1 == 'disable_teacher') {
            $teacher_id = $param2;

            if ($teacher_id) {
                $this->Teachers_model->update_teacher_status($teacher_id, 0);

                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('teacher_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_teacher')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }

            redirect(base_url() . 'index.php?admin/teachers_information/', 'refresh');
        }

        if ($param1 == 'enable_teacher') {
            $teacher_id = $param2;

            if ($teacher_id) {
                $this->Teachers_model->update_teacher_status($teacher_id, 1);

                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('teacher_enabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_enabling_teacher')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }

            redirect(base_url() . 'index.php?admin/teachers_information/', 'refresh');
        }
    }

    function add_teacher()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_teacher')),
                'url' => base_url('index.php?admin/add_teacher')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'add_teacher';
        $page_data['page_title'] = ucfirst(get_phrase('add_teacher'));

        $this->load->view('backend/index', $page_data);
    }

    function get_teachers()
    {
        $teachers = $this->Teachers_model->get_teachers();

        foreach ($teachers as $row) {
            $teacher_details = $this->Teachers_model->get_teachers_info($row['teacher_id']);

            if (!empty($teacher_details)) {
                $firstname = isset($teacher_details['firstname']) ? $teacher_details['firstname'] : '';
                $lastname = isset($teacher_details['lastname']) ? $teacher_details['lastname'] : '';

                echo '<option value="' . $row['teacher_id'] . '" data-firstname="' . $firstname . '" data-lastname="' . $lastname . '">' . $lastname . ', ' . $firstname . '.' . '</option>';
            }
        }
    }

    function edit_teacher($teacher_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_teachers')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('edit_teacher')),
                'url' => base_url('index.php?admin/edit_teacher/' . $teacher_id)
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['teacher'] = $this->Teachers_model->get_teacher_info($teacher_id);
        $page_data['page_name'] = 'edit_teacher';
        $page_data['page_title'] = ucfirst(get_phrase('edit_teacher'));

        $this->load->view('backend/index', $page_data);
    }
}