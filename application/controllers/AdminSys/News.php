<?php

class News extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('newss/News_model');
        $this->load->library('News_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function edit_news($param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('edit_news')),
                'url' => base_url('index.php?admin/edit_news')
            )
        );

        $news_types = $this->News_model->get_news_types();
        $edit_data = $this->News_model->get_news_by_id($param2);

        log_message('error', 'EDIT DATA: ' . print_r($edit_data, true));


        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['param2'] = $param2;
        $page_data['news_types'] = $news_types;
        $page_data['edit_data'] = $edit_data;
        $page_data['page_name'] = 'edit_news';
        $page_data['page_title'] = ucfirst(get_phrase('edit_news'));
        
        $this->load->view('backend/index', $page_data);
    }

    function news($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data = array(
                'title' => $this->input->post('title'),
                'date' => $this->input->post('date'),
                'body' => $this->input->post('body'),
                'news_type_id' => $this->input->post('news_type_id'),
                'status_id' => $this->input->post('status_id'),
                'user_type' => $this->input->post('user_type'),
                'class_id' => ($this->input->post('class_id') == 0) ? null : $this->input->post('class_id'),
                'section_id' => ($this->input->post('section_id') == 0) ? null : $this->input->post('section_id')
            );

            $result = $this->news_service->create_news($data, $_FILES['images']);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('news_added_successfully')) . '!',
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
                    'title' => '¡' . ucfirst(get_phrase('file_upload_error')) . '!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#d33',
                ));
            }
            redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
        }

        if ($param1 == 'update') {
            $data = array(
                'title' => $this->input->post('title'),
                'date' => $this->input->post('date'),
                'body' => $this->input->post('body'),
                'news_type_id' => $this->input->post('news_type_id'),
                'status_id' => $this->input->post('status_id'),
                'user_type' => $this->input->post('user_type'),
                'class_id' => ($this->input->post('class_id') == 0) ? null : $this->input->post('class_id'),
                'section_id' => ($this->input->post('section_id') == 0) ? null : $this->input->post('section_id')
            );
        
            $files_to_delete = $this->input->post('files_to_delete');
            $files_to_delete = is_array($files_to_delete) ? $files_to_delete : [];
        
            $files = isset($_FILES['images']) ? $_FILES['images'] : null;
        
            $result = $this->news_service->update_news($param2, $data, $files, $files_to_delete);
        
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('news_updated_successfully')) . '!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('file_upload_error')) . '!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#d33',
                ));
            }
        
            redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
        }

        if ($param1 == 'disable_news') {
            $result = $this->News_model->update_news_status($param2, 0);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('news_disabled_successfully')) . '!',
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
                    'title' => '¡' . ucfirst(get_phrase('error_disabling_news')) . '!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#d33',
                ));
            }
            redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
        }

        if ($param1 == 'enable_news') {
            $result = $this->News_model->update_news_status($param2, 1);
            if ($result) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('news_enabled_successfully')) . '!',
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
                    'title' => '¡' . ucfirst(get_phrase('error_enabling_news')) . '!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#d33',
                ));
            }
            redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
        }
    }

    function manage_news()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_news')),
                'url' => base_url('index.php?admin/manage_news/')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'manage_news';
        $page_data['page_title'] = ucfirst(get_phrase('manage_news'));
        $this->load->view('backend/index', $page_data);
    }

    function view_news($user_type = '')
    {
        if ($this->session->userdata('admin_login') != 1) {
            redirect('login', 'refresh');
        }
    
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('view_news')),
                'url' => base_url('index.php?admin/view_news/' . $user_type)
            )
        );
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['user_type'] = $user_type;
        $page_data['page_name'] = 'view_news';
        $page_data['page_title'] = ucfirst(get_phrase('view_news'));
        $this->load->view('backend/index', $page_data);
    }

    function add_news()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_news')),
                'url' => base_url('index.php?admin/add_news')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'add_news';
		$page_data['page_title'] = ucfirst(get_phrase('add_news'));
		$this->load->view('backend/index', $page_data);
	}








}