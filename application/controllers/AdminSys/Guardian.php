<?php

class Guardian extends CI_Controller
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

    function guardian_profile($guardian_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_guardians')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/guardian_profile/' . $guardian_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'guardian_profile';
        $page_data['page_title']  = ucfirst(get_phrase('view_profile'));
        $page_data['param2']  = $guardian_id;
        
        $this->load->view('backend/index', $page_data);
    }


    function get_guardians() {
        $guardians = $this->crud_model->get_guardians();
        
        foreach ($guardians as $row) {
            $guardian_details = $this->crudGuardian->get_guardian_info($row['guardian_id']);
            
            // Verificar si se encontraron detalles del guardián
            $firstname = isset($guardian_details['firstname']) ? $guardian_details['firstname'] : '';
            $lastname = isset($guardian_details['lastname']) ? $guardian_details['lastname'] : '';
    
            echo '<option value="' . $row['guardian_id'] . '" data-firstname="' . $firstname . '" data-lastname="' . $lastname . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
        }
    }

    function guardian_add()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_guardian')),
                'url' => base_url('index.php?admin/guardian_add')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'guardian_add';
        $page_data['page_icon'] = 'entypo-graduation-cap';
		$page_data['page_title'] = ucfirst(get_phrase('add_guardian'));
		$this->load->view('backend/index', $page_data);
	}


    function guardian($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['email']       			= $this->input->post('email');
            $data['username']    			= $this->input->post('username');
            $data['password']    			= $this->input->post('password');

            $this->db->insert('guardian', $data);
            $insertedGuardianId = $this->db->insert_id();

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $this->db->insert('address', $dataAddress);
            $insertedAddressId = $this->db->insert_id();
            
            $dataDetails['guardian_id'] = $insertedGuardianId;
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
                $file_name = 'guardian id - ' . $insertedGuardianId . '.jpg';
                $file_path = 'uploads/guardian_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
                $dataDetails['photo'] = $file_path;
            } else {
                $dataDetails['photo'] = 'assets/images/default-user-img.jpg';
            }

            $this->db->insert('guardian_details', $dataDetails);

            $student_ids = $this->input->post('student_id');
            $relationships = $this->input->post('relationship');

            if (!empty($student_ids) && !empty($relationships)) {
                foreach ($student_ids as $index => $student_id) {
                    $dataStudent = array(
                        'student_id' => $student_id,
                        'guardian_id' => $insertedGuardianId,
                        'guardian_type_id' => $relationships[$index]
                    );
                    $this->db->insert('student_guardian', $dataStudent);
                }
            }

            $this->session->set_flashdata('flash_message', array(
               'title' => ucfirst(get_phrase('guardian_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/guardian_add/', 'refresh');
        }
        if ($param1 == 'update') {
            $guardian_id = $param2; 
            $guardian_details = $this->db->get_where('guardian_details', array('guardian_id' => $guardian_id))->row_array();
            $address_id = $guardian_details['address_id'];

            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');

            $this->db->where('guardian_id', $guardian_id);
            $this->db->update('guardian', $data);

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
                if (!empty($guardian_details['photo']) && file_exists($guardian_details['photo'])) {
                    unlink($guardian_details['photo']);
                }
                $file_name = 'guaridan id - ' . $guardian_id . '.jpg';
                $file_path = 'uploads/guardian_image/' . $file_name;
                $data['photo'] = $file_path;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
            } else {
                $dataDetails['photo'] = $guardian_details['photo'];
            }
           
            $this->db->where('guardian_id' , $guardian_id);
            $this->db->update('guardian_details' , $dataDetails);

            $existing_student_ids = $this->input->post('existing_student_ids') ?: []; 
            $student_ids = $this->input->post('student_id');
            $relationships = $this->input->post('relationship');
            
            $current_students = $this->db->select('student_id')->where('guardian_id', $guardian_id)->get('student_guardian')->result_array();
            $current_student_ids = array_column($current_students, 'student_id');
            
            $student_ids_to_delete = array_diff($current_student_ids, $existing_student_ids);
        
            
            if (!empty($student_ids_to_delete)) {
                $this->db->where('guardian_id', $guardian_id);
                $this->db->where_in('student_id', $student_ids_to_delete);
                $this->db->delete('student_guardian');
            }
            
            foreach ($student_ids as $index => $student_id) {
                if (!in_array($student_id, $existing_student_ids)) {
                    // Insertar nuevos tutores
                    $dataStudent = array(
                        'student_id' => $student_id,
                        'guardian_id' => $guardian_id,
                        'guardian_type_id' => $relationships[$index]
                    );
                    $this->db->insert('student_guardian', $dataStudent);
                } else {
                    // Actualizar guardianes existentes
                    $dataStudent = array(
                        'guardian_type_id' => $relationships[$index]
                    );
                    $this->db->where('guardian_id', $guardian_id);
                    $this->db->where('student_id', $student_id);
                    $this->db->update('student_guardian', $dataStudent);
                }
            }
        
            $this->session->set_flashdata('flash_message', array(
               'title' => ucfirst(get_phrase('guardian_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            redirect(base_url() . 'index.php?admin/guardian_edit/' . $guardian_id, 'refresh');
        }
        if ($param1 == 'disable_guardian') {
            $guardian_id = $param2;  
    
            if ($guardian_id) {
                $this->db->where('guardian_id', $guardian_id);
                $this->db->update('guardian_details', array(
                    'user_status_id' => 0 
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('guardian_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_guardian')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/student_profile/'. $param3, 'refresh');
        }
        if ($param1 == 'enable_guardian') {
            $guardian_id = $param2;  
    
            if ($guardian_id) {
                $this->db->where('guardian_id', $guardian_id);
                $this->db->update('guardian_details', array(
                    'user_status_id' => 1 
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('guardian_enabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_enabling_guardian')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/student_profile/' . $param3, 'refresh');
        }
        $page_data['page_title'] 	= ucfirst(get_phrase('guardian_section'));
        $page_data['page_icon'] 	= 'entypo-users';
        $page_data['page_name']  = 'guardian';
        $this->load->view('backend/index', $page_data);
    }


    function guardian_edit($param2 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('guardian_edit')),
                'url' => base_url('')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'guardian_edit';
		$page_data['page_title'] 	= ucfirst(get_phrase('guardian_edit'));
		$page_data['param2'] 	= $param2;
		$this->load->view('backend/index', $page_data);
	}

    function get_guardians_content()
    {
        $sections = $this->db->get_where('guardian_details')->result_array();
        foreach ($sections as $row) {
            echo '<option value="' . $row['guardian_id'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
        }
    }
   


}