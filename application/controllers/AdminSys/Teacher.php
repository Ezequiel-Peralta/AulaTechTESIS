<?php

class Teacher extends CI_Controller
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

        $this->db->select('teacher.teacher_id, teacher.email, teacher.username, teacher_details.firstname, teacher_details.lastname, teacher_details.dni, teacher_details.photo, teacher_details.user_status_id');
        $this->db->from('teacher');
        $this->db->join('teacher_details', 'teacher.teacher_id = teacher_details.teacher_id');
        $this->db->order_by('teacher_details.lastname', 'ASC');
        $query = $this->db->get();
        $page_data['teachers']  = $query->result_array();

        $page_data['page_name']   = 'teachers_information';
        $page_data['page_title']  = ucfirst(get_phrase('manage_teachers'));
        
        $this->load->view('backend/index', $page_data);
    }

    
    function teacher_profile($teacher_id = '')
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

        $page_data['page_name']   = 'teacher_profile';
        $page_data['page_title']  = ucfirst(get_phrase('view_profile'));
        $page_data['param2']  = $teacher_id;
        
        $this->load->view('backend/index', $page_data);
    }



    function teacher($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['email']       			= $this->input->post('email');
            $data['username']    			= $this->input->post('username');
            $data['password']    			= $this->input->post('password');

            $this->db->insert('teacher', $data);
            $insertedTeacherId = $this->db->insert_id();

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $this->db->insert('address', $dataAddress);
            $insertedAddressId = $this->db->insert_id();
            
            $dataDetails['teacher_id'] = $insertedTeacherId;
            $dataDetails['address_id'] = $insertedAddressId;
            $dataDetails['user_group_id']        			= '3';
            $dataDetails['firstname']        			= $this->input->post('firstname');
            $dataDetails['lastname']        			= $this->input->post('lastname');
            $dataDetails['dni']        			= $this->input->post('dni');
            $dataDetails['birthday']        			= $this->input->post('birthday');
            $dataDetails['phone_cel']       			= $this->input->post('phone_cel');
            $dataDetails['phone_fij']       			= $this->input->post('phone_fij');
            $dataDetails['gender_id']  			= $this->input->post('gender_id');
            $dataDetails['user_status_id']  	 = 1;

            if (!empty($_FILES['userfile']['name'])) {
                $file_name = 'teacher id - ' . $insertedTeacherId . '.jpg';
                $file_path = 'uploads/teacher_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
                $dataDetails['photo'] = $file_path;
            } else {
                $dataDetails['photo'] = 'assets/images/default-user-img.jpg';
            }

            $this->db->insert('teacher_details', $dataDetails);

            $section_ids = $this->input->post('section_id');

            // if (!empty($section_ids)) {
            //     foreach ($section_ids as $section_id) {
            //              $data = array(
            //                  'section_id' => $section_id,
            //                  'teacher_id' => $insertedTeacherId
            //              );
            //              $this->db->insert('section_teacher', $data);
            //          }
            // }

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
            $teacher_details = $this->db->get_where('teacher_details', array('teacher_id' => $teacher_id))->row_array();
            $address_id = $teacher_details['address_id'];
        
            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');
        
            $this->db->where('teacher_id', $teacher_id);
            $this->db->update('teacher', $data);
        
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
        
            $this->db->where('teacher_id', $teacher_id);
            $this->db->update('teacher_details', $dataDetails);
        
            // $section_ids = $this->input->post('section_id');
            // if (!is_array($section_ids)) {
            //     $section_ids = []; // Asegurar que sea un array vacío si no hay secciones seleccionadas
            // }
        
            // $existing_section_ids = $this->db->select('section_id')
            //     ->from('section_teacher')
            //     ->where('teacher_id', $teacher_id)
            //     ->get()
            //     ->result_array();
        
            // $existing_section_ids = array_column($existing_section_ids, 'section_id');
        
            // $sections_to_delete = array_diff($existing_section_ids, $section_ids); 
            // $sections_to_add = array_diff($section_ids, $existing_section_ids);
            // $sections_to_keep = array_intersect($existing_section_ids, $section_ids);
        
            // // Si no se han seleccionado nuevas secciones, elimina todas las secciones existentes
            // if (empty($section_ids)) {
            //     $this->db->where('teacher_id', $teacher_id)->delete('section_teacher');
            // } else {
            //     // Eliminar secciones que ya no se necesitan
            //     if (!empty($sections_to_delete)) {
            //         $this->db->where('teacher_id', $teacher_id)
            //             ->where_in('section_id', $sections_to_delete)
            //             ->delete('section_teacher');
            //     }
        
            //     // Agregar secciones nuevas
            //     if (!empty($sections_to_add)) {
            //         foreach ($sections_to_add as $section_id) {
            //             $dataSectionTeacher = array(
            //                 'section_id' => $section_id,
            //                 'teacher_id' => $teacher_id
            //             );
            //             $this->db->insert('section_teacher', $dataSectionTeacher);
            //         }
            //     }
            // }

        
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
                $this->db->where('teacher_id', $teacher_id);
                $this->db->update('teacher_details', array(
                    'user_status_id' => 0
                ));
    
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
                $this->db->where('teacher_id', $teacher_id);
                $this->db->update('teacher_details', array(
                    'user_status_id' => 1
                ));
    
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

		$page_data['page_name']  = 'add_teacher';
		$page_data['page_title'] = ucfirst(get_phrase('add_teacher'));
		$this->load->view('backend/index', $page_data);
	}


    function get_teachers() {
        $teachers = $this->crud_model->get_tearchers();
        
        foreach ($teachers as $row) {
            $teacher_details = $this->crud_model->get_teachers_info($row['teacher_id']);
            
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

            $page_complete_name = 'edit_teacher'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $teacher_id; // ID del elemento específico (ej. curso o sección)

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
                'text' => ucfirst(get_phrase('edit_teacher')),
                'url' => base_url('')
            )
        );

        $selected_section_ids = array_map('intval', array_column(
            $this->db->select('section_id')
                     ->where('teacher_id', $teacher_id)
                     ->get('section_teacher')
                     ->result_array(),
            'section_id'
        ));
  

        $page_data['selected_section_ids'] = $selected_section_ids;

        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'edit_teacher';
		$page_data['page_title'] 	= 'edit_teacher';
		$page_data['teacher_id'] 	= $teacher_id;
		$this->load->view('backend/index', $page_data);
	}





}