<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class News extends CI_Controller
{
    
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');

        date_default_timezone_set('America/Argentina/Buenos_Aires');
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		
    }
    
    public function index()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
    }



    function edit_news($param2 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

            $page_complete_name = 'edit_news'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $param2; // ID del elemento específico (ej. curso o sección)

            // Buscar registros para este page_name y element_id
            $this->db->where('page_name', $page_complete_name);
            $this->db->where('element_id', $element_id);
            $tracking = $this->db->get('page_tracking')->row_array();

            if (!empty($tracking)) {
                // Verificar si el registro está siendo utilizado por otro usuario
                if ($tracking['user_id'] !== NULL && $tracking['user_group'] !== NULL && ($tracking['user_id'] !== $user_id || $tracking['user_group'] !== $user_group)) {
                    // Si otro usuario está accediendo a este elemento, redirige con un mensaje
                    $this->session->set_flashdata('flash_message', array(
                        'title' => '¡' . ucfirst(get_phrase('this_page_is_being_used_by_another_user')) . '!',
                        'text' => '',
                        'icon' => 'error',
                        'showCloseButton' => 'true',
                        'confirmButtonText' => ucfirst(get_phrase('accept')),
                        'confirmButtonColor' => '#1a92c4',
                        'timer' => '10000',
                        'timerProgressBar' => 'true',
                    ));
                    redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
                } else {
                    // Si el usuario actual ya tiene acceso al elemento, actualiza el registro
                    $dataTracking = array(
                        'user_id' => $user_id,
                        'user_group' => $user_group
                    );
                    $this->db->where('page_tracking_id', $tracking['page_tracking_id']);
                    $this->db->update('page_tracking', $dataTracking);
                }
            } else {
                // Si no existe un registro con este element_id, se inserta uno nuevo
                $dataTracking = array(
                    'page_name' => $page_complete_name,
                    'element_id' => $element_id,
                    'user_id' => $user_id,
                    'user_group' => $user_group
                );
                $this->db->insert('page_tracking', $dataTracking);
            }

			
			
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
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['param2'] = $param2;
		$page_data['page_name']  = 'edit_news';
		$page_data['page_title'] = ucfirst(get_phrase('edit_news'));
		$this->load->view('backend/index', $page_data);
	}


    function news($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
            if ($param1 == 'create') {
                $data['title'] = $this->input->post('title');
                $data['date'] = $this->input->post('date');
                $data['body'] = $this->input->post('body');
                $data['news_type_id'] = $this->input->post('news_type_id');
                $data['status_id'] = $this->input->post('status_id');
                $data['user_type'] = $this->input->post('user_type');
            
                $data['class_id'] = ($this->input->post('class_id') == 0) ? null : $this->input->post('class_id');
                $data['section_id'] = ($this->input->post('section_id') == 0) ? null : $this->input->post('section_id');
            
                $this->db->insert('news', $data);
                $news_id = $this->db->insert_id();

                if (!empty($_FILES['images']['name'][0])) { 
                    $exam_directory = 'uploads/news/' . $news_id . '/';
                    if (!is_dir($exam_directory)) {
                        mkdir($exam_directory, 0777, true); 
                    }

                    $this->load->library('upload'); 
                    
                    $files = $_FILES;
                    $number_of_files = count($_FILES['images']['name']);
                    
                    $uploaded_files = []; 

                    for ($i = 0; $i < $number_of_files; $i++) {
                        $_FILES['attachment']['name'] = $files['images']['name'][$i];
                        $_FILES['attachment']['type'] = $files['images']['type'][$i];
                        $_FILES['attachment']['tmp_name'] = $files['images']['tmp_name'][$i];
                        $_FILES['attachment']['error'] = $files['images']['error'][$i];
                        $_FILES['attachment']['size'] = $files['images']['size'][$i];

                        $config['upload_path'] = $exam_directory;
                        $config['allowed_types'] = '*'; 
                        $config['max_size'] = '100480'; 
                        $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $_FILES['attachment']['name']);
                        $config['file_name'] = $filename;
                        $config['detect_mime'] = FALSE;

                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('attachment')) {
                            $upload_data = $this->upload->data();
                            $uploaded_files[] = $upload_data['file_name']; 
                        } else {
                            $this->session->set_flashdata('flash_message', array(
                                'title' => '¡' . ucfirst(get_phrase('file_upload_error')) . '!',
                                'text' => $this->upload->display_errors(),
                                'icon' => 'error',
                                'showCloseButton' => 'true',
                                'confirmButtonText' =>  ucfirst(get_phrase('accept')),
                                'confirmButtonColor' => '#d33',
                            ));

                            redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
                        }
                    }

                    $files_json = json_encode($uploaded_files);
                    $this->db->where('news_id', $news_id);
                    $this->db->update('news', ['images' => $files_json]);
                }
            
                // Muestra un mensaje de éxito
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

            
                // Redirige a la página de gestión de noticias
                redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
            }
            
            if ($param1 == 'update') {
                $data['title'] = $this->input->post('title');
                $data['date'] = $this->input->post('date');
                $data['body'] = $this->input->post('body');
                $data['news_type_id'] = $this->input->post('news_type_id');
                $data['status_id'] = $this->input->post('status_id');
                $data['user_type'] = $this->input->post('user_type');
                $data['class_id'] = ($this->input->post('class_id') == 0) ? null : $this->input->post('class_id');
                $data['section_id'] = ($this->input->post('section_id') == 0) ? null : $this->input->post('section_id');
            
                
                $existing_files = $this->db->get_where('news', array('news_id' => $param2))->row()->images;
                $existing_files = !empty($existing_files) ? json_decode($existing_files, true) : [];
                
            
                $new_files = !empty($_FILES['images']['name'][0]) ? $_FILES['images']['name'] : [];
            
                $files_to_delete = $this->input->post('files_to_delete');
                $files_to_delete = is_array($files_to_delete) ? $files_to_delete : [];
                
            
                $files_to_keep = array_diff($existing_files, $files_to_delete); 
                
            
                foreach ($files_to_delete as $file) {
                    $file_path = 'uploads/news/' . $param2 . '/' . $file;
                    if (file_exists($file_path) && is_file($file_path)) {
                        unlink($file_path); 
                    }
                }
            
                $final_files = $files_to_keep;
            
                $news_directory = 'uploads/news/' . $param2 . '/';
                if (!is_dir($news_directory)) {
                    mkdir($news_directory, 0777, true); 
                }
            
                if (!empty($_FILES['images']['name'][0])) {
                    $exam_directory = 'uploads/news/' . $param2 . '/';
                    if (!is_dir($exam_directory)) {
                        mkdir($exam_directory, 0777, true); 
                    }
            
                    $this->load->library('upload'); 
                    
                    $files = $_FILES;
                    $number_of_files = count($_FILES['images']['name']);
                    
                    $uploaded_files = []; 
            
                    for ($i = 0; $i < $number_of_files; $i++) {
                        $_FILES['attachment']['name'] = $files['images']['name'][$i];
                        $_FILES['attachment']['type'] = $files['images']['type'][$i];
                        $_FILES['attachment']['tmp_name'] = $files['images']['tmp_name'][$i];
                        $_FILES['attachment']['error'] = $files['images']['error'][$i];
                        $_FILES['attachment']['size'] = $files['images']['size'][$i];
            
                        $config['upload_path'] = $exam_directory;
                        $config['allowed_types'] = '*'; 
                        $config['max_size'] = '100480'; 
                        $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $_FILES['attachment']['name']);
                        $config['file_name'] = $filename;
                        $config['detect_mime'] = FALSE;
            
                        $this->upload->initialize($config);
            
                        if ($this->upload->do_upload('attachment')) {
                            $upload_data = $this->upload->data();
                            $uploaded_files[] = $upload_data['file_name']; 
                        } else {
                            $this->session->set_flashdata('flash_message', array(
                                'title' => '¡' . ucfirst(get_phrase('file_upload_error')) . '!',
                                'text' => $this->upload->display_errors(),
                                'icon' => 'error',
                                'showCloseButton' => 'true',
                                'confirmButtonText' =>  ucfirst(get_phrase('accept')),
                                'confirmButtonColor' => '#d33',
                            ));
            
                            redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
                        }
                    }
            
                    $final_files = array_merge($final_files, $uploaded_files);
                }
            
                $final_files = array_unique($final_files);
            
                $files_json = json_encode($final_files);

                $this->db->where('news_id', $param2);
                $this->db->update('news', $data);
            
                $this->db->where('news_id', $param2);
                $this->db->update('news', ['images' => $files_json]);
            
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
            
                redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
            }
            

        if ($param1 == 'disable_news') {
            
            $this->db->where('news_id', $param2);
            $this->db->update('news', array(
                'status_id' => 0
            ));
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
            redirect(base_url() . 'index.php?admin/manage_news/', 'refresh');
        }
   
        if ($param1 == 'enable_news') {
            
            $this->db->where('news_id', $param2);
            $this->db->update('news', array(
                'status_id' => 1
            ));
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
		$page_data['page_name']  = 'manage_news';
		$page_data['page_title'] 	= ucfirst(get_phrase('manage_news'));
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