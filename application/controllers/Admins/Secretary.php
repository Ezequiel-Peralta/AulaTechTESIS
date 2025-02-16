<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class secretary extends CI_Controller
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

        $this->db->select('secretary.secretary_id, secretary.email, secretary.username, secretary_details.firstname, secretary_details.lastname, secretary_details.dni, secretary_details.photo, secretary_details.user_status_id');
        $this->db->from('secretary');
        $this->db->join('secretary_details', 'secretary.secretary_id = secretary_details.secretary_id');
        $this->db->order_by('secretary_details.lastname', 'ASC');
        $query = $this->db->get();
        $page_data['secretaries']  = $query->result_array();

        $page_data['page_name']   = 'secretaries_information';
        $page_data['page_title']  = ucfirst(get_phrase('manage_secretaries'));
        
        $this->load->view('backend/index', $page_data);
    }

    function secretary_profile($secretary_id = '')
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
                'url' => base_url('index.php?admin/secretary_profile/' . $secretary_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'secretary_profile';
        $page_data['page_title']  = ucfirst(get_phrase('view_profile'));
        $page_data['param2']  = $secretary_id;
        
        $this->load->view('backend/index', $page_data);
    }


    function secretary($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['email']       			= $this->input->post('email');
            $data['username']    			= $this->input->post('username');
            $data['password']    			= $this->input->post('password');

            $this->db->insert('secretary', $data);
            $insertedSecretaryId = $this->db->insert_id();

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $this->db->insert('address', $dataAddress);
            $insertedAddressId = $this->db->insert_id();
            
            $dataDetails['secretary_id'] = $insertedSecretaryId;
            $dataDetails['address_id'] = $insertedAddressId;
            $dataDetails['user_group_id']        			= '5';
            $dataDetails['firstname']        			= $this->input->post('firstname');
            $dataDetails['lastname']        			= $this->input->post('lastname');
            $dataDetails['dni']        			= $this->input->post('dni');
            $dataDetails['birthday']        			= $this->input->post('birthday');
            $dataDetails['phone_cel']       			= $this->input->post('phone_cel');
            $dataDetails['phone_fij']       			= $this->input->post('phone_fij');
            $dataDetails['gender_id']  			= $this->input->post('gender_id');
            $dataDetails['user_status_id']  	 = 1;

            if (!empty($_FILES['userfile']['name'])) {
                $file_name = 'secretary id - ' . $insertedSecretaryId . '.jpg';
                $file_path = 'uploads/secretary_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
                $dataDetails['photo'] = $file_path;
            } else {
                $dataDetails['photo'] = 'assets/images/default-user-img.jpg';
            }

            $this->db->insert('secretary_details', $dataDetails);

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
            $secretary_details = $this->db->get_where('secretary_details', array('secretary_id' => $secretary_id))->row_array();
            $address_id = $secretary_details['address_id'];
        
            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');
        
            $this->db->where('secretary_id', $secretary_id);
            $this->db->update('secretary', $data);
        
            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');
        
            $this->db->where('address_id', $address_id);
            $this->db->update('address', $dataAddress);
        
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
        
            $this->db->where('secretary_id', $secretary_id);
            $this->db->update('secretary_details', $dataDetails);
        
        
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

        if ($param1 == 'disable_secretary') {
            $secretary_id = $param2;  
    
            if ($secretary_id) {
                $this->db->where('secretary_id', $secretary_id);
                $this->db->update('secretary_details', array(
                    'user_status_id' => 0
                ));
    
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

        if ($param1 == 'enable_secretary') {
            $secretary_id = $param2;  
    
            if ($secretary_id) {
                $this->db->where('secretary_id', $secretary_id);
                $this->db->update('secretary_details', array(
                    'user_status_id' => 1
                ));
    
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




    function add_secretary()
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
                'url' => base_url('index.php?admin/add_secretary')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'add_secretary';
		$page_data['page_title'] = ucfirst(get_phrase('add_secretary'));
		$this->load->view('backend/index', $page_data);
	}
    function edit_secretary($secretary_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

            $page_complete_name = 'edit_secretary'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $secretary_id; // ID del elemento específico (ej. curso o sección)

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
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('edit_secretary')),
                'url' => base_url('')
            )
        );


        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'edit_secretary';
		$page_data['page_title'] 	= 'edit_secretary';
		$page_data['secretary_id'] 	= $secretary_id;
		$this->load->view('backend/index', $page_data);
	}






}