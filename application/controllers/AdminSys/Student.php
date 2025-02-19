<?php

class Student extends CI_Controller
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

    function student_add()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/controllers/views/backed/index.php')
            ),
            array(
                'text' => ucfirst(get_phrase('student_add')),
                'url' => base_url('index.php?admin/student_add')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'student_add';
        $page_data['page_icon'] = 'entypo-graduation-cap';
		$page_data['page_title'] = ucfirst(get_phrase('student_add'));
		$this->load->view('backend/index', $page_data);
	}

    function student_bulk_add($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
        
        if ($param1 == 'import_excel') {
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_import.xlsx');
    
            include 'simplexlsx.class.php';
    
            $xlsx = new SimpleXLSX('uploads/student_import.xlsx');
    
            list($num_cols, $num_rows) = $xlsx->dimension();
            $f = 0;
    
            foreach ($xlsx->rows() as $r) {
                if ($f == 0) {
                    $f++;
                    continue;
                }
    
                $row_empty = true;
                foreach ($r as $cell) {
                    if (!empty($cell)) {
                        $row_empty = false;
                        break;
                    }
                }
    
                if ($row_empty) {
                    break;
                }
    
                $data = array();
                foreach ($r as $i => $cell) {
                    if ($i == '0') {
                        $dataDetails['lastname'] = $cell;
                    } else if ($i == 1) {
                        $dataDetails['firstname'] = $cell;
                    } else if ($i == 2) {
                        if ($cell === "0" || $cell === "Masculino" || $cell === "Male") {
                            $dataDetails['gender_id'] = 0;
                        } else if ($cell === "1" || $cell === "Femenino" || $cell === "Female") {
                            $dataDetails['gender_id'] = 1;
                        } else if ($cell === "2" || $cell === "Otro" || $cell === "Other") {
                            $dataDetails['gender_id'] = 2;
                        } else {
                            $dataDetails['gender_id'] = null; 
                        }
                    } else if ($i == 3) {
                        $dataDetails['dni'] = $cell;
                    } else if ($i == 4) {
                        $dataDetails['enrollment'] = $cell;
                    } else if ($i == 5) {
                        $data['username'] = $cell;
                    } else if ($i == 6) {
                        $data['email'] = $cell;
                    } else if ($i == 7) {
                        $data['password'] = $cell;
                    } else if ($i == 8) {
                        if (is_numeric($cell)) {
                            // Convertir el número a una fecha
                            $timestamp = ($cell - 25569) * 86400; // 25569 es el número de días desde 1900-01-01 a 1970-01-01
                            $dataDetails['birthday'] = date('Y-m-d', $timestamp);
                        } else {
                            // Si no es un número, convertir usando strtotime
                            $dataDetails['birthday'] = date('Y-m-d', strtotime($cell));
                        }
                    } else if ($i == 9) {
                        $dataDetails['phone_cel'] = $cell;
                    }  else if ($i == 10) {
                        $dataDetails['phone_fij'] = $cell;
                    } else if ($i == 11) {
                        $dataAddress['state'] = 'Córdoba';
                    } else if ($i == 12) {
                        $dataAddress['postalcode'] = $cell;
                    } else if ($i == 13) {
                        $dataAddress['locality'] = $cell;
                    } else if ($i == 14) {
                        $dataAddress['neighborhood'] = $cell;
                    }  else if ($i == 15) {
                        $dataAddress['address'] = $cell;
                    } else if ($i == 16) {
                        $dataAddress['address_line'] = $cell;
                    } 
                }
                $dataDetails['photo'] = 'assets/images/default-user-img.jpg';
    
                $dataDetails['user_status_id'] = 1;
    
                $dataDetails['user_group_id'] = 2;
    
                $dataDetails['class_id'] = $this->input->post('class_id');
                if (empty($dataDetails['class_id'])) {
                    $dataDetails['class_id'] = null;
                }
                
                $dataDetails['section_id'] = $this->input->post('section_id');
                if (empty($dataDetails['section_id'])) {
                    $dataDetails['section_id'] = null;
                }
    
                $this->db->insert('address', $dataAddress);
                $insertedAddressId = $this->db->insert_id();
                $dataDetails['address_id'] = $insertedAddressId;
    
                $this->db->insert('student', $data);
                $insertedStudentId = $this->db->insert_id();
                $dataDetails['student_id'] = $insertedStudentId;
    
                $this->db->insert('student_details', $dataDetails);
            }
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('student_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            // Si class_id o section_id son null, redirigir a pre_enrollments
            if (empty($dataDetails['class_id']) || empty($dataDetails['section_id'])) {
                redirect(base_url() . 'index.php?admin/pre_enrollments', 'refresh');
            } else {
                // De lo contrario, redirigir a student_information con el section_id
                redirect(base_url() . 'index.php?admin/student_information/' . $dataDetails['section_id'], 'refresh');
            }
        }
    
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('bulk_add_students')),
                    'url' => base_url('index.php?admin/student_bulk_add')
                )
            );
                    
            $page_data['breadcrumb'] = $breadcrumb;
    
            $page_data['page_name']  = 'student_bulk_add';
            $page_data['page_title'] = ucfirst(get_phrase('bulk_add_students'));
            $this->load->view('backend/index', $page_data);
        }



        function student_information($section_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_students')). "&nbsp;&nbsp;/&nbsp;&nbsp;". $this->crud_model->get_section_name($section_id),
                'url' => base_url('index.php?admin/student_information/'.$section_id)
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $this->db->select('
            student.student_id, 
            student.email, 
            student.username, 
            student_details.enrollment, 
            student_details.firstname, 
            student_details.lastname, 
            student_details.dni, 
            student_details.photo, 
            student_details.birthday, 
            student_details.phone_cel, 
            student_details.phone_fij, 
            student_details.section_id, 
            student_details.class_id, 
            student_details.user_status_id, 
            student_details.gender_id, 
            student_details.address_id, 
            address.state, 
            address.postalcode, 
            address.locality, 
            address.neighborhood,
            address.address,
            address.address_line
        ');
        
        // Indica que la tabla principal es student
        $this->db->from('student');
        
        // Realiza un JOIN con student_details utilizando el campo student_id
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        
        // Realiza un JOIN adicional con la tabla adress utilizando el campo adress_id
        $this->db->join('address', 'student_details.address_id = address.address_id', 'left'); // 'left' se usa para incluir estudiantes sin dirección
        
        // Añade la condición para filtrar por section_id y user_status_id
        $this->db->where('student_details.user_status_id', 1);
        $this->db->where('student_details.section_id', $section_id);
        
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Almacena los resultados en el array de datos
        $page_data['students'] = $query->result_array();

        // Actualiza los títulos y datos de la página usando section_id
        $page_data['page_name']   = 'student_information';
        $page_data['page_title']  = ucfirst(get_phrase('manage_students')). ' - ' . $this->crud_model->get_section_name($section_id);
        $page_data['section_id']  = $section_id;
        
        $this->load->view('backend/index', $page_data);
    }


    function student_profile($student_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        // Breadcrumb actualizado para usar section_id
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_students')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/student_profile/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'student_profile';
        $page_data['page_title']  = ucfirst(get_phrase('view_profile'));
        $page_data['param2']  = $student_id;
        
        $this->load->view('backend/index', $page_data);
    }


    function student($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['email']       			= $this->input->post('email');
            $data['username']    			= $this->input->post('username');
            $data['password']    			= $this->input->post('password');

            $this->db->insert('student', $data);
            $insertedStudentId = $this->db->insert_id();

            $dataAddress['state'] = $this->input->post('state');
            $dataAddress['postalcode'] = $this->input->post('postalcode');
            $dataAddress['locality'] = $this->input->post('locality');
            $dataAddress['neighborhood'] = $this->input->post('neighborhood');
            $dataAddress['address'] = $this->input->post('address');
            $dataAddress['address_line'] = $this->input->post('address_line');

            $this->db->insert('address', $dataAddress);
            $insertedAddressId = $this->db->insert_id();
            
            $dataDetails['student_id'] = $insertedStudentId;
            $dataDetails['address_id'] = $insertedAddressId;
            $dataDetails['user_group_id']        			= '2';
            $dataDetails['firstname']        			= $this->input->post('firstname');
            $dataDetails['lastname']        			= $this->input->post('lastname');
            $dataDetails['enrollment']        			= $this->input->post('enrollment');
            $dataDetails['dni']        			= $this->input->post('dni');
            $dataDetails['birthday']        			= $this->input->post('birthday');
            $dataDetails['about']        			= $this->input->post('about');
            $dataDetails['phone_cel']       			= $this->input->post('phone_cel');
            $dataDetails['phone_fij']       			= $this->input->post('phone_fij');
            $dataDetails['gender_id']  			= $this->input->post('gender_id');
            $dataDetails['class_id']  	 = NULL;
            $dataDetails['section_id']  	 = NULL;
            $dataDetails['user_status_id']  	 = 1;

            if (!empty($_FILES['userfile']['name'])) {
                $file_name = 'student id - ' . $insertedStudentId . '.jpg';
                $file_path = 'uploads/student_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
                $dataDetails['photo'] = $file_path;
            } else {
                $dataDetails['photo'] = 'assets/images/default-user-img.jpg';
            }

            if (!empty($_FILES['medical_record_file']['name'])) {
                $medical_file_extension = pathinfo($_FILES['medical_record_file']['name'], PATHINFO_EXTENSION);
                $medical_file_name = 'ficha medica id - ' . $insertedStudentId . '.' . $medical_file_extension;
                $medical_file_path = 'uploads/fichas_medicas/' . $medical_file_name;
                move_uploaded_file($_FILES['medical_record_file']['tmp_name'], $medical_file_path);
        
                $dataDetails['medical_record'] = $medical_file_path;
            }

            $this->db->insert('student_details', $dataDetails);

            // Insertar tutores
            $guardian_ids = $this->input->post('guardian_id');
            $relationships = $this->input->post('relationship');

            if (!empty($guardian_ids) && !empty($relationships)) {
                foreach ($guardian_ids as $index => $guardian_id) {
                    $dataGuardian = array(
                        'student_id' => $insertedStudentId,
                        'guardian_id' => $guardian_id,
                        'guardian_type_id' => $relationships[$index]
                    );
                    $this->db->insert('student_guardian', $dataGuardian);
                }
            }



            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('student_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/pre_enrollments/', 'refresh');
        }
        if ($param1 == 'update') {
            $student_id = $param2; 
            $student_details = $this->db->get_where('student_details', array('student_id' => $student_id))->row_array();
            $address_id = $student_details['address_id'];

            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');
        
            $this->db->where('student_id', $student_id);
            $this->db->update('student', $data);
        
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
            $dataDetails['enrollment'] = $this->input->post('enrollment');
            $dataDetails['dni'] = $this->input->post('dni');
            $dataDetails['birthday'] = $this->input->post('birthday');
            $dataDetails['about'] = $this->input->post('about');
            $dataDetails['phone_cel'] = $this->input->post('phone_cel');
            $dataDetails['phone_fij'] = $this->input->post('phone_fij');
            $dataDetails['gender_id'] = $this->input->post('gender_id');
            $dataDetails['class_id'] = $this->input->post('class_id');
            $dataDetails['section_id'] = $this->input->post('section_id');

            if ($student_details['class_id'] != $dataDetails['class_id'] || $student_details['section_id'] != $dataDetails['section_id']) {
        
                $this->db->select('academic_period_id');
                $this->db->from('section');
                $this->db->where('section_id', $student_details['section_id']);
                $old_section = $this->db->get()->row();
        
                $this->db->select('academic_period_id');
                $this->db->from('section');
                $this->db->where('section_id', $dataDetails['section_id']);
                $new_section = $this->db->get()->row();
        
                $dataAcademic['old_class_id'] = $student_details['class_id'];
                $dataAcademic['new_class_id'] = $dataDetails['class_id'];
                $dataAcademic['old_section_id'] = $student_details['section_id'];
                $dataAcademic['new_section_id'] = $dataDetails['section_id'];
                $dataAcademic['old_academic_period_id'] = $old_section ? $old_section->academic_period_id : null;
                $dataAcademic['new_academic_period_id'] = $new_section ? $new_section->academic_period_id : null;
                $dataAcademic['date_change'] = date('Y-m-d');
        
                $this->db->where('student_id', $student_id);
                $this->db->update('academic_history', $dataAcademic);
            }

        
            if (!empty($_FILES['userfile']['name'])) {
                if (!empty($student_details['photo']) && file_exists($student_details['photo'])) {
                    unlink($student_details['photo']);
                }
                $file_name = 'student id - ' . $student_id . '.jpg';
                $file_path = 'uploads/student_image/' . $file_name;
                $dataDetails['photo'] = $file_path;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
            } else {
                $dataDetails['photo'] = $student_details['photo'];
            }

            if (!empty($_FILES['medical_record_file']['name'])) {
                if (!empty($student_details['medical_record']) && file_exists($student_details['medical_record'])) {
                    unlink($student_details['medical_record']);
                }
                $medical_file_extension = pathinfo($_FILES['medical_record_file']['name'], PATHINFO_EXTENSION);
                $medical_file_name = 'ficha medica id - ' . $student_id . '.' . $medical_file_extension;
                $medical_file_path = 'uploads/fichas_medicas/' . $medical_file_name;
                $dataDetails['medical_record'] = $medical_file_path;
                move_uploaded_file($_FILES['medical_record_file']['tmp_name'], $medical_file_path);
            }
        
            $this->db->where('student_id', $student_id);
            $this->db->update('student_details', $dataDetails);


            $existing_guardian_ids = $this->input->post('existing_guardian_ids') ?: []; // IDs de guardianes existentes
            $guardian_ids = $this->input->post('guardian_id') ?: [];
            $relationships = $this->input->post('relationship') ?: [];
            
            // Obtener los guardian_id actuales para el estudiante
            $current_guardians = $this->db->select('guardian_id')->where('student_id', $student_id)->get('student_guardian')->result_array();
            $current_guardian_ids = array_column($current_guardians, 'guardian_id');
            
            // Identificar guardianes para eliminar
            $guardian_ids_to_delete = array_diff($current_guardian_ids, $existing_guardian_ids);
            
            // print_r($existing_guardian_ids);
            // print_r($guardian_ids);
            // print_r($guardian_ids_to_delete);
            // exit();
            
            if (!empty($guardian_ids_to_delete)) {
                $this->db->where('student_id', $student_id);
                $this->db->where_in('guardian_id', $guardian_ids_to_delete);
                $this->db->delete('student_guardian');
            }
            
            // Insertar o actualizar guardianes nuevos y existentes
            foreach ($guardian_ids as $index => $guardian_id) {
                if (!in_array($guardian_id, $existing_guardian_ids)) {
                    // Insertar nuevos tutores
                    $dataGuardian = array(
                        'student_id' => $student_id,
                        'guardian_id' => $guardian_id,
                        'guardian_type_id' => $relationships[$index]
                    );
                    $this->db->insert('student_guardian', $dataGuardian);
                } else {
                    // Actualizar guardianes existentes
                    $dataGuardian = array(
                        'guardian_type_id' => $relationships[$index]
                    );
                    $this->db->where('student_id', $student_id);
                    $this->db->where('guardian_id', $guardian_id);
                    $this->db->update('student_guardian', $dataGuardian);
                }
            }
        
            $this->session->set_flashdata('flash_message', array(
               'title' => ucfirst(get_phrase('student_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/student_information/' . $dataDetails['section_id'], 'refresh');
        } 
		
        if ($param1 == 'inactive_student') {
            $student_id = $param2;  
        
            $reason = $this->input->post('reason');
            $other_reason = $this->input->post('other_reason');
            
            $status_reason = !empty($other_reason) ? $other_reason : $reason;
    
            if ($student_id) {
                $this->db->where('student_id', $student_id);
                $this->db->update('student_details', array(
                    'user_status_id' => 0, 
                    'class_id' => null,
                    'section_id' => null,
                    'status_reason' => $status_reason  
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('student_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_student')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            // Redirigir de vuelta a la página de inactivación de estudiantes
            redirect(base_url() . 'index.php?admin/re_enrollments/'. $param3, 'refresh');
        }

        if ($param1 == 'inactive_student_pre_enrollements') {
            $student_id = $param2;  
        
            $reason = $this->input->post('reason');
            $other_reason = $this->input->post('other_reason');
            
            $status_reason = !empty($other_reason) ? $other_reason : $reason;
    
            if ($student_id) {
                $this->db->where('student_id', $student_id);
                $this->db->update('student_details', array(
                    'user_status_id' => 0, 
                    'class_id' => null,
                    'section_id' => null,
                    'status_reason' => $status_reason  
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('student_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_student')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/pre_enrollments/', 'refresh');
        }
        
    }

    function manage_students()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_students')),
                'url' => base_url('index.php?admin/manage_students/')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
		$page_data['page_name']  = 'manage_students';
		$page_data['page_title'] 	= ucfirst(get_phrase('manage_students'));
		$this->load->view('backend/index', $page_data);
	}


    function student_edit($param2 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

            $page_complete_name = 'student_edit'; // Nombre de la página
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
                'url' => base_url()
            ),
            array(
                'text' => ucfirst(get_phrase('student_edit')),
                'url' => base_url('')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'student_edit';
		$page_data['page_title'] 	= ucfirst(get_phrase('student_edit'));
		$page_data['param2'] 	= $param2;
		$this->load->view('backend/index', $page_data);
	}


    function get_students_content()
    {
        $students = $this->db->get_where('student_details')->result_array();
        foreach ($students as $row) {
            echo '<option value="' . $row['student_id'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
        }
    }


    function get_students_content_by_section($section_id)
    {
        $students = $this->db->get_where('student_details', array('section_id' => $section_id))->result_array();
        foreach ($students as $row) {
            echo '<option value="' . $row['student_id'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
        }
    }


    function get_all_students()
    {
        // Filtramos solo estudiantes
        $user_types = ['student'];

        // Opción inicial
        // $output = '<option value="">' . 'Seleccionar' . '</option>';

        foreach ($user_types as $type) {
            // Obtenemos los detalles de los estudiantes, incluyendo la sección y validando que el período académico esté activo
            $this->db->select("$type.{$type}_id as user_id, $type.email, {$type}_details.section_id, {$type}_details.firstname, {$type}_details.lastname, section.name as section_name, section.letter_name");
            $this->db->from($type);
            $this->db->join("{$type}_details", "{$type}.{$type}_id = {$type}_details.{$type}_id", 'left');
            $this->db->join('section', 'section.section_id = '.$type.'_details.section_id', 'left'); // Relacionar con la tabla de secciones
            $this->db->join('academic_period', 'academic_period.id = section.academic_period_id', 'left'); // Relacionar con la tabla de periodos académicos
            $this->db->where('academic_period.status_id', 1); // Filtrar por períodos académicos activos
            $users = $this->db->get()->result_array();

            // Agrupar estudiantes por sección
            $sections = [];
            foreach ($users as $user) {
                // Concatenar el nombre de la sección con la letra
                $section_name = $user['section_name'];
                $sections[$section_name][] = $user;
            }

            // Generar las opciones agrupadas por sección
            foreach ($sections as $section => $students) {
                $output .= '<optgroup label="' . $section . '">'; // Agrupar por sección

                foreach ($students as $student) {
                    $fullname = $student['lastname'] . ', ' . $student['firstname'] . '.';
                    $output .= '<option value="' . $type . '-' . $student['user_id'] . '" title="' . $fullname . '" data-cc-group="' . $type . '" data-firstname="' . $student['firstname'] . '" data-lastname="' . $student['lastname'] . '">'
                        . '<span>' . $fullname . '</span></option>';
                }

                $output .= '</optgroup>';
            }
        }

        echo $output;
    }



}

   