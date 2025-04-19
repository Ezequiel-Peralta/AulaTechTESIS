<?php

class TeachersAide extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('teachersAide/TeachersAide_model');
        $this->load->library('TeachersAide_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function teachers_aide($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');

            $insertedTeacherAideId = $this->TeachersAide_model->insert_teacher_aide($data);

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $insertedAddressId = $this->TeachersAide_model->insert_address($dataAddress);

            $dataDetails['teacher_aide_id'] = $insertedTeacherAideId;
            $dataDetails['address_id'] = $insertedAddressId;
            $dataDetails['user_group_id'] = '4';
            $dataDetails['firstname'] = $this->input->post('firstname');
            $dataDetails['lastname'] = $this->input->post('lastname');
            $dataDetails['dni'] = $this->input->post('dni');
            $dataDetails['birthday'] = $this->input->post('birthday');
            $dataDetails['phone_cel'] = $this->input->post('phone_cel');
            $dataDetails['phone_fij'] = $this->input->post('phone_fij');
            $dataDetails['gender_id'] = $this->input->post('gender_id');
            $dataDetails['user_status_id'] = 1;

            if (!empty($_FILES['userfile']['name'])) {
                $file_name = 'teacher aide id - ' . $insertedTeacherAideId . '.jpg';
                $file_path = 'uploads/teacher_aide_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
                $dataDetails['photo'] = $file_path;
            } else {
                $dataDetails['photo'] = 'assets/images/default-user-img.jpg';
            }

            $this->TeachersAide_model->insert_teacher_aide_details($dataDetails);

            $section_ids = $this->input->post('section_id');

            if (!empty($section_ids)) {
                foreach ($section_ids as $section_id) {
                    $this->TeachersAide_model->update_section_teacher_aide($section_id, $insertedTeacherAideId);
                }
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('teacher_aide_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/teachers_aide_information/', 'refresh');
        }

        if ($param1 == 'update') {
            $teacher_aide_id = $param2;
            $teacher_aide_details = $this->TeachersAide_model->get_teacher_aide_details($teacher_aide_id);
            $address_id = $teacher_aide_details['address_id'];

            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');

            $this->TeachersAide_model->update_teacher_aide($teacher_aide_id, $data);

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $this->TeachersAide_model->update_address($address_id, $dataAddress);

            $dataDetails['firstname'] = $this->input->post('firstname');
            $dataDetails['lastname'] = $this->input->post('lastname');
            $dataDetails['dni'] = $this->input->post('dni');
            $dataDetails['birthday'] = $this->input->post('birthday');
            $dataDetails['phone_cel'] = $this->input->post('phone_cel');
            $dataDetails['phone_fij'] = $this->input->post('phone_fij');
            $dataDetails['gender_id'] = $this->input->post('gender_id');

            if (!empty($_FILES['userfile']['name'])) {
                if (!empty($teacher_aide_details['photo']) && file_exists($teacher_aide_details['photo'])) {
                    unlink($teacher_aide_details['photo']);
                }
                $file_name = 'teacher aide id - ' . $teacher_aide_id . '.jpg';
                $file_path = 'uploads/teacher_aide_image/' . $file_name;
                $dataDetails['photo'] = $file_path;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
            } else {
                $dataDetails['photo'] = $teacher_aide_details['photo'];
            }

            $this->TeachersAide_model->update_teacher_aide_details($teacher_aide_id, $dataDetails);

            // $existing_section_ids = $this->TeachersAide_model->get_sections_by_teacher_aide($teacher_aide_id);

            $existing_section_ids = $this->input->post('existing_section_ids') ?: [];
            $section_ids = $this->input->post('section_id') ?: [];

            // Obtener las secciones actuales del auxiliar
            $current_sections = $this->TeachersAide_model->get_teacher_aide_sections($teacher_aide_id);
            $current_section_ids = array_column($current_sections, 'section_id');

            // Secciones a eliminar (ya no están seleccionadas)
            $section_ids_to_delete = array_diff($current_section_ids, $section_ids);

            // Secciones a agregar (nuevas seleccionadas)
            $section_ids_to_add = array_diff($section_ids, $current_section_ids);

            // Eliminar las asociaciones de secciones que ya no están seleccionadas
            if (!empty($section_ids_to_delete)) {
                $this->TeachersAide_model->remove_teacher_aide_from_sections($teacher_aide_id, $section_ids_to_delete);
            }

            // Agregar nuevas secciones
            foreach ($section_ids_to_add as $section_id) {
                $this->TeachersAide_model->update_section_teacher_aide($section_id, $teacher_aide_id);
            }



            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('teacher_aide_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            redirect(base_url() . 'index.php?admin/teachers_aide_information/', 'refresh');
        }

        if ($param1 == 'disable_teachers_aide') {
            $teacher_aide_id = $param2;

            if ($teacher_aide_id) {
                $this->TeachersAide_model->update_teacher_aide_status($teacher_aide_id, 0);

                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('teacher_aide_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_teacher_aide')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }

            redirect(base_url() . 'index.php?admin/teachers_aide_information/', 'refresh');
        }

        if ($param1 == 'enable_teachers_aide') {
            $teacher_aide_id = $param2;

            if ($teacher_aide_id) {
                $this->TeachersAide_model->update_teacher_aide_status($teacher_aide_id, 1);

                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('teacher_aide_enabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_enabling_teacher_aide')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }

            redirect(base_url() . 'index.php?admin/teachers_aide_information/', 'refresh');
        }
    }

    function teachers_aide_information()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_teachers_aide')),
                'url' => base_url('index.php?admin/teachers_aide_information/')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['teachers_aide'] = $this->TeachersAide_model->get_all_teacher_aides();
        $page_data['page_name'] = 'teachers_aide_information';
        $page_data['page_title'] = ucfirst(get_phrase('manage_teachers_aide'));

        $this->load->view('backend/index', $page_data);
    }

    function teachers_aide_profile($teacher_aide_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_teachers_aide')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/teachers_aide_profile/' . $teacher_aide_id)
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'teacher_aide_profile';
        $page_data['page_title'] = ucfirst(get_phrase('view_profile'));
        $page_data['param2'] = $teacher_aide_id;

        $this->load->view('backend/index', $page_data);
    }

    function teachers_aide_add()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_teacher_aide')),
                'url' => base_url('index.php?admin/teachers_aide_add')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'teacher_aide_add';
        $page_data['page_icon'] = 'entypo-graduation-cap';
        $page_data['page_title'] = ucfirst(get_phrase('add_teacher_aide'));

        $this->load->view('backend/index', $page_data);
    }

    function add_teachers_aide()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_teacher_aide')),
                'url' => base_url('index.php?admin/adds_teacher_aide')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'add_teacher_aide';
        $page_data['page_title'] = ucfirst(get_phrase('add_teacher_aide'));

        $this->load->view('backend/index', $page_data);
    }

    function edit_teachers_aide($teacher_aide_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('edit_teacher_aide')),
                'url' => base_url('index.php?admin/edit_teachers_aide')
            )
        );

        $selected_section_ids = array_map('intval', array_column(
            $this->TeachersAide_model->get_sections_by_teacher_aide($teacher_aide_id),
            'section_id'
        ));

        $page_data['selected_section_ids'] = $selected_section_ids;
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'edit_teacher_aide';
        $page_data['page_title'] = 'edit_teacher_aide';
        $page_data['teacher_aide_id'] = $teacher_aide_id;

        $this->load->view('backend/index', $page_data);
    }

    function teachersAide_edit($param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('edit_teacherAide')),
                'url' => base_url('')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'teacherAide_edit';
        $page_data['page_title'] = 'teacherAide_edit';
        $page_data['param2'] = $param2;

        $this->load->view('backend/index', $page_data);
    }
}