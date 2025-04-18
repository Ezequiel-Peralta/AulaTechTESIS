<?php

class Principal extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('principal/Principal_model');
        $this->load->library('Principal_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function principal_information()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_principals')),
                'url' => base_url('index.php?admin/principal_information/')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['principals'] = $this->Principal_model->get_all_principals();
        $page_data['page_name'] = 'principal_information';
        $page_data['page_title'] = ucfirst(get_phrase('manage_principals'));

        $this->load->view('backend/index', $page_data);
    }

    function principal_profile($principal_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_principals')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/principal_profile/' . $principal_id)
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['param2'] = $principal_id;
        $page_data['page_name'] = 'principal_profile';
        $page_data['page_title'] = ucfirst(get_phrase('view_profile'));
        $page_data['principal_info'] = $this->Principal_model->get_principal_info($principal_id);

        $this->load->view('backend/index', $page_data);
    }

    function principal($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');

            $insertedPrincipalId = $this->Principal_model->insert_principal($data);

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $insertedAddressId = $this->Principal_model->insert_address($dataAddress);

            $dataDetails['principal_id'] = $insertedPrincipalId;
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
                $file_name = 'principal id - ' . $insertedPrincipalId . '.jpg';
                $file_path = 'uploads/principal_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
                $dataDetails['photo'] = $file_path;
            } else {
                $dataDetails['photo'] = 'assets/images/default-user-img.jpg';
            }

            $this->Principal_model->insert_principal_details($dataDetails);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('principal_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/principal_information/', 'refresh');
        }

        if ($param1 == 'update') {
            $principal_id = $param2;
            $principal_details = $this->Principal_model->get_principal_details($principal_id);
            $address_id = $principal_details['address_id'];

            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');

            $this->Principal_model->update_principal($principal_id, $data);

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $this->Principal_model->update_address($address_id, $dataAddress);

            $dataDetails['firstname'] = $this->input->post('firstname');
            $dataDetails['lastname'] = $this->input->post('lastname');
            $dataDetails['dni'] = $this->input->post('dni');
            $dataDetails['birthday'] = $this->input->post('birthday');
            $dataDetails['phone_cel'] = $this->input->post('phone_cel');
            $dataDetails['phone_fij'] = $this->input->post('phone_fij');
            $dataDetails['gender_id'] = $this->input->post('gender_id');

            if (!empty($_FILES['userfile']['name'])) {
                if (!empty($principal_details['photo']) && file_exists($principal_details['photo'])) {
                    unlink($principal_details['photo']);
                }
                $file_name = 'principal id - ' . $principal_id . '.jpg';
                $file_path = 'uploads/principal_image/' . $file_name;
                $dataDetails['photo'] = $file_path;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
            } else {
                $dataDetails['photo'] = $principal_details['photo'];
            }

            $this->Principal_model->update_principal_details($principal_id, $dataDetails);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('principal_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            redirect(base_url() . 'index.php?admin/principal_information/', 'refresh');
        }

        if ($param1 == 'disable_principal') {
            $principal_id = $param2;

            if ($principal_id) {
                $this->Principal_model->update_principal_status($principal_id, 0);

                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('principal_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_principal')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }

            redirect(base_url() . 'index.php?admin/principal_information/', 'refresh');
        }

        if ($param1 == 'enable_principal') {
            $principal_id = $param2;

            if ($principal_id) {
                $this->Principal_model->update_principal_status($principal_id, 1);

                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('principal_enabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_enabling_principal')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }

            redirect(base_url() . 'index.php?admin/principal_information/', 'refresh');
        }
    }

    function add_principal()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_principal')),
                'url' => base_url('index.php?admin/add_principal')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'add_principal';
        $page_data['page_title'] = ucfirst(get_phrase('add_principal'));

        $this->load->view('backend/index', $page_data);
    }

    function edit_principal($principal_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('edit_principal')),
                'url' => base_url('')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'edit_principal';
        $page_data['page_title'] = 'edit_principal';
        $page_data['principal_id'] = $principal_id;

        $this->load->view('backend/index', $page_data);
    }
}