<?php

class Secretaries extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('secretaries/Secretaries_model');
        $this->load->library('Secretaries_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function secretaries_information()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_secretaries')),
                'url' => base_url('index.php?admin/secretaries_information/')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['secretaries'] = $this->Secretaries_model->get_all_secretaries();
        $page_data['page_name'] = 'secretaries_information';
        $page_data['page_title'] = ucfirst(get_phrase('manage_secretaries'));

        $this->load->view('backend/index', $page_data);
    }

    function secretaries_profile($secretary_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_secretaries')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/secretaries_profile/' . $secretary_id)
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['param2'] = $secretary_id;
        $page_data['page_name'] = 'secretary_profile';
        $page_data['page_title'] = ucfirst(get_phrase('view_profile'));
        $page_data['secretary_info'] = $this->Secretaries_model->get_secretary_info($secretary_id);

        $this->load->view('backend/index', $page_data);
    }

    function secretaries($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');

            $insertedSecretaryId = $this->Secretaries_model->insert_secretary($data);

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $insertedAddressId = $this->Secretaries_model->insert_address($dataAddress);

            $dataDetails['secretary_id'] = $insertedSecretaryId;
            $dataDetails['address_id'] = $insertedAddressId;
            $dataDetails['user_group_id'] = '5';
            $dataDetails['firstname'] = $this->input->post('firstname');
            $dataDetails['lastname'] = $this->input->post('lastname');
            $dataDetails['dni'] = $this->input->post('dni');
            $dataDetails['birthday'] = $this->input->post('birthday');
            $dataDetails['phone_cel'] = $this->input->post('phone_cel');
            $dataDetails['phone_fij'] = $this->input->post('phone_fij');
            $dataDetails['gender_id'] = $this->input->post('gender_id');
            $dataDetails['user_status_id'] = 1;

            if (!empty($_FILES['userfile']['name'])) {
                $file_name = 'secretary id - ' . $insertedSecretaryId . '.jpg';
                $file_path = 'uploads/secretary_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
                $dataDetails['photo'] = $file_path;
            } else {
                $dataDetails['photo'] = 'assets/images/default-user-img.jpg';
            }

            $this->Secretaries_model->insert_secretary_details($dataDetails);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('secretary_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/secretaries_information/', 'refresh');
        }

        if ($param1 == 'update') {
            $secretary_id = $param2;
            $secretary_details = $this->Secretaries_model->get_secretary_details($secretary_id);
            $address_id = $secretary_details['address_id'];

            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');

            $this->Secretaries_model->update_secretary($secretary_id, $data);

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $this->Secretaries_model->update_address($address_id, $dataAddress);

            $dataDetails['firstname'] = $this->input->post('firstname');
            $dataDetails['lastname'] = $this->input->post('lastname');
            $dataDetails['dni'] = $this->input->post('dni');
            $dataDetails['birthday'] = $this->input->post('birthday');
            $dataDetails['phone_cel'] = $this->input->post('phone_cel');
            $dataDetails['phone_fij'] = $this->input->post('phone_fij');
            $dataDetails['gender_id'] = $this->input->post('gender_id');

            if (!empty($_FILES['userfile']['name'])) {
                if (!empty($secretary_details['photo']) && file_exists($secretary_details['photo'])) {
                    unlink($secretary_details['photo']);
                }
                $file_name = 'secretary id - ' . $secretary_id . '.jpg';
                $file_path = 'uploads/secretary_image/' . $file_name;
                $dataDetails['photo'] = $file_path;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
            } else {
                $dataDetails['photo'] = $secretary_details['photo'];
            }

            $this->Secretaries_model->update_secretary_details($secretary_id, $dataDetails);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('secretary_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            redirect(base_url() . 'index.php?admin/secretaries_information/', 'refresh');
        }

        if ($param1 == 'disable_secretaries') {
            $secretary_id = $param2;

            if ($secretary_id) {
                $this->Secretaries_model->update_secretary_status($secretary_id, 0);

                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('secretary_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_secretary')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }

            redirect(base_url() . 'index.php?admin/secretaries_information/', 'refresh');
        }

        if ($param1 == 'enable_secretaries') {
            $secretary_id = $param2;

            if ($secretary_id) {
                $this->Secretaries_model->update_secretary_status($secretary_id, 1);

                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('secretary_enabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_enabling_secretary')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }

            redirect(base_url() . 'index.php?admin/secretaries_information/', 'refresh');
        }
    }

    function add_secretaries()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_secretary')),
                'url' => base_url('index.php?admin/add_secretaries')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'add_secretary';
        $page_data['page_title'] = ucfirst(get_phrase('add_secretary'));

        $this->load->view('backend/index', $page_data);
    }

    function edit_secretaries($secretary_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('edit_secretary')),
                'url' => base_url('index.php?admin/edit_secretaries/' . $secretary_id)
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'edit_secretary';
        $page_data['page_title'] = 'edit_secretary';
        $page_data['secretary_id'] = $secretary_id;

        $this->load->view('backend/index', $page_data);
    }
}