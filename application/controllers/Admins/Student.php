
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
    
    public function index()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
    }
    

function student_add()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
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
            $guardian_ids = $this->input->post('guardian_id');
            $relationships = $this->input->post('relationship');
            
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
    
 
        
        function view_student_academic_history($student_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');


        $this->db->where('student_id', $student_id);
        $student_data = $this->db->get('student_details')->row_array(); 


      
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_academic_history')) . ' ' . '&nbsp;&nbsp;/&nbsp;&nbsp;' . $student_data['lastname'] . ', ' . $student_data['firstname'],
                    'url' => base_url('index.php?admin/view_student_academic_history/' . $student_id)
                )
            );
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['student_data'] = $student_data; 
        $page_data['student_id'] = $student_id; 
		$page_data['page_name']  = 'view_student_academic_history';
		$page_data['page_title'] 	= ucfirst(get_phrase('view_academic_history')) . ' - ' . ucfirst($student_data['lastname']) . ', ' . ucfirst($student_data['firstname']);
		$this->load->view('backend/index', $page_data);
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




    public function printReportCardES($student_id = '', $section_id = '')
    {
        $student_data = $this->crud_model->get_student_info2($student_id);
        $section = $this->crud_model->get_section_info4($section_id);
        $class_name = $this->crud_model->get_class_name($section['class_id']);
        $academic_period = $this->crud_model->get_academic_period_name_per_section2($section['section_id']);
        $section_letter_name = $section['letter_name'];
        $subjects = $this->crud_model->get_subjects_by_section2($section_id);
        $shift = ($section['shift_id'] == 1) ? ucfirst(get_phrase('morning')) : ucfirst(get_phrase('afternoon'));
        $academic_period_id = $section['academic_period_id'];

        // Inicializamos las variables
        $absent_count = 0;
        $justified_absent_count = 0;

        // Consultar la tabla attendance_student
        $this->db->select('status');
        $this->db->where('student_id', $student_id);
        $this->db->where('section_id', $section_id);
        $query = $this->db->get('attendance_student');
        $attendance_records = $query->result_array();

        // Sumar ausentes e injustificados
        foreach ($attendance_records as $record) {
            if ($record['status'] == 2) {
                $absent_count++;
            } elseif ($record['status'] == 4) {
                $justified_absent_count++;
            }
        }

        // Si no se encontraron registros, buscar en attendance_student_history
        if ($absent_count == 0 && $justified_absent_count == 0) {
            $this->db->select('status');
            $this->db->where('student_id', $student_id);
            $this->db->where('section_id', $section_id);
            $this->db->where('academic_period_id', $section['academic_period_id']);
            $query_history = $this->db->get('attendance_student_history');
            $attendance_records_history = $query_history->result_array();

            foreach ($attendance_records_history as $record) {
                if ($record['status'] == 2) {
                    $absent_count++;
                } elseif ($record['status'] == 4) {
                    $justified_absent_count++;
                }
            }
        }

        // Determinar la condición de asistencia
        $total_absences = $absent_count + $justified_absent_count;
        $attendance_condition = ($total_absences > 25) ? 'T.E.A' : 'REGULAR';

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libreta de Calificaciones del Estudiante</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            margin: 0;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 10px;
            border-bottom: 0.5px solid #000;
        }

        .school-name {
            font-weight: bold;
            font-size: 12px;
        }

        .student-info {
            justify-content: space-between;
            font-size: 12px;
             text-align: center;
                 line-height: 1.8;
            margin-top: 250px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
            font-size: 10px;
        }

        .main-table th {
            background-color: #fff;
            font-weight: bold;
        }

        .subject-column {
            text-align: left !important;
            width: 25%;
        }

        .eval-column {
            width: 30px;
        }

        .bottom-section {
        position: relative;
        bottom: 0;
        width: 100%;
        }
        
        .bottom-section table {
            background-color: white;
        }
        
        /* Ensure second page starts on new page */
        .cover-page {
            page-break-before: always;
        }

        .attendance-box {
            border: 1px solid #000;
            padding: 5px;
            width: 150px;
        }

        .observations-box {
            border: 1px solid #000;
            flex-grow: 1;
            padding: 5px;
        }

      
        .school-logo {
            width: 60px !important;
            height: 80px !important;
            margin-top: 30px;
            margin-bottom: 0px;
        }

        .institution-header {
            margin-bottom: 40px;
            line-height: 1.5;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0;
        }

        .coloquio-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .coloquio-table th, .coloquio-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .coloquio-title {
            font-weight: bold;
            margin: 30px 0 10px;
        }

        .hr {
            width: 100%;
            height: 2px;
            color: black;
        }

        .left-side {
            width: 41%;
            padding: 0px 14px !important;
            border: 2px solid #000;
            margin-left: 32px;
            margin-top: 40px;
        }

        .right-side {
            width: 51%;
            text-align: center;
              border: 2px solid #000 !important;
                      margin-right: 32px;
                             margin-left: 30px;
               margin-top: 40px;
        }

        .evaluation-table {
            width: 100%;
            border-collapse: collapse;
        }

        .evaluation-table td {
            border: 1px solid black;
            padding: 1px;
            text-align: left;
        } .evaluation-table th {
            border: 1px solid black;
            text-align: center;
        }

        .evaluation-table th {
            font-weight: bolder !important;
            font-size: 9px !important;
        }

        .period-title {
            font-weight: bold;
            font-size: 12px;
            margin: 20px 0 5px;
            text-align: center;
        }

        .school-logo {
            width: 120px;
            margin-bottom: 5px;
        }

        .header-text {
            text-align: center;
            margin-bottom: 40px;
        }

        .header-text .bold {
            font-weight: bold;
        }

        .report-title {
            font-size: 16px;
            font-weight: bolder;
            text-align: center;
            margin: 55px 0;
            line-height: 1.5;
        }
      
    </style>
</head>
<body>
    <!-- First Page -->
    <div class="header">
        <div>
            <div class="school-name" style="margin-bottom: 3px; font-size: 12px;">NOMBRE DE LA INSTITUCIÓN</div>
            <div style="margin-bottom: 5px; font-size: 12px;">Dirección</div>
        </div>
        <div style="text-align: right;">
            <div style="font-weight: bolder; font-size: 14px;">INFORME DE PROGRESO ESCOLAR</div>
           
        </div>
    </div>

    <div style="margin-bottom: 12px;  border-bottom: 0.5px solid #000;">
            <div style="text-align: left; margin-top: -12px; font-size: 12px;  margin-bottom: 5px;">
                <div>
                    Plan: <span style="font-weight: bold;"> Sin Modalidad </span><br>
                </div>
                <div style="margin-top: 3px;">
                    Estudiante: <span style="font-weight: bold;">' . $student_data['lastname'] . ', ' . $student_data['firstname'] . ' </span>
                    <span style="margin-left: 20px;"> Tipo y N° doc: <span style="font-weight: bold;">DNI - 47475089 </span></span>
                </div>
            </div>
           
        <div style="text-align: right; margin-top: -37px; margin-bottom: 5px;">
            <div style="font-size: 12px;">
                Curso: <span style="font-weight: bold; margin-right: 5px;">' . $section['class_id'] . '</span>  División: <span style="font-weight: bold; margin-right: 5px;">' . ucfirst($section['letter_name']) . '</span> Turno: <span style="font-weight: bold; margin-right: 5px;">' . $shift . '</span> Ciclo Lectivo: <span style="font-weight: bold; margin-right: 5px;">' . $academic_period . '</span><br>
                <div style="margin-top: 3px; margin-right: 5px;">
                    Versión IPE: <span style="font-weight: bold;">Preliminar</span>
                </div>
            </div>
        </div>
    </div>

    

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2" class="subject-column text-center" style="font-size: 12px !important; font-weight: bolder !important; vertical-align: middle !important; text-align: center !important;">Espacios Curriculares (E.C.)</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 1</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 2</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 3</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 4</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 5</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 6</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 7</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">JIIS 1</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">JIIS 2</th>
                <th style="font-size: 11px !important; font-weight: bolder !important;">Coloquio Dic.</th>
                <th style="font-size: 11px !important; font-weight: bolder !important;">Coloquio Feb.</th>
                <th style="font-size: 11px !important; font-weight: bolder !important;">Prom. Final</th>
            </tr>
            <tr>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>';
    
        foreach ($subjects as $subject) {
            // Obtener las marcas del estudiante para la asignatura
            $marks = $this->crud_model->get_marks_by_student_subject2($student_id, $subject['subject_id'], $section['academic_period_id']);
    
            // Inicializar un array para almacenar los marks organizados por exam_type_id
            $marks_by_exam_type = array_fill(1, 21, ''); // Rellenar todas las posiciones con valores vacíos
    
            // Rellenar los valores existentes en $marks_by_exam_type
            foreach ($marks as $mark) {
                if ($mark['exam_type_id'] >= 1 && $mark['exam_type_id'] <= 21) {
                    // Validar si mark_obtained no está vacío
                    if (!empty($mark['mark_obtained'])) {
                        $mark_value = floatval($mark['mark_obtained']);
                        // Si el valor es 0.00, establecer como vacío; si no, usar el valor correspondiente
                        $marks_by_exam_type[$mark['exam_type_id']] = ($mark_value === 0.00) ? '' : $mark_value;
                    }
                }
            }
        
            // Generar la fila HTML
            $html .= '<tr>';
            $html .= '<td class="subject-column">' . $subject['name'] . '</td>';
        
            for ($exam_type_id = 1; $exam_type_id <= 21; $exam_type_id++) {
                // Usar directamente el valor almacenado en $marks_by_exam_type
                $value = $marks_by_exam_type[$exam_type_id];
                $html .= '<td>' .  ($value !== '' ? $value : '') . '</td>';
            }
        
            $html .= '</tr>';
        }

    



        $html .= '
        </tbody>
    </table>

    <div class="bottom-section" style="display: flex; gap: 10px; margin-top: 30px;">
        <div style="width: 100px;">
           <table style="width: 80%; border-collapse: collapse;">
                <tr>
                    <th rowspan="2" style="border: 1px solid #000; padding: 7px 4px; font-size: 7px; font-weight: bolder; text-align: center;">
                        INASISTENCIAS<br>DIARIAS
                    </th>
                    <td style="border: 1px solid #000; font-size: 7px; padding: 1px 4px; font-weight: bolder; text-align: center; width: 25%;">
                        Just.
                    </td>
                    <td style="border: 1px solid #000; font-size: 7px; padding: 1px 4px; font-weight: bolder; text-align: center; width: 25%;">
                        Inj.
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000; font-size: 7px; text-align: center;">
                        ' . $justified_absent_count .'
                    </td>
                    <td style="border: 1px solid #000; font-size: 7px; text-align: center;">
                       ' . $absent_count .'
                    </td>
                </tr>
                <tr>
                    <td colspan="1" style="border: 1px solid #000; padding: 3px 0px; font-size: 7px; text-align: center; font-weight: bold;">
                        ESTADO
                    </td>
                    <td colspan="2" style="border: 1px solid #000; padding: 3px 0px; font-size: 7px; text-align: center;">
                        ' . $attendance_condition . '
                    </td>
                </tr>
                
            </table>
        </div>
        
        <div style="flex: 1; padding: 8px;  border: 2px solid #000; margin-left: 3px; margin-top: -10px;">
            <div style="font-weight: bolder; margin-top: -4px; padding-bottom: 4px; text-align: center !important; margin-left: -8px; margin-right: -8px; border-bottom: 1px solid #000; font-size: 7px;">OBSERVACIONES</div>
            <div style="min-height: 40px;"></div>
        </div>
        


        <div style="flex: 1; border: 2px solid #000; margin-left: -7px; margin-top: -10px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 7px; height: 100%;">
                <thead>
                    <tr>
                        <th style="text-align: center; padding: 4px 0px; font-weight: bolder; border-bottom: 0px solid #000; colspan="3">
                            E.C. EN CONTRATURNO EN ESTADO T.E.A.
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border-top: 1px solid #000;"></td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #000;"></td>

                    <tr>
                    </tr>
                        <td style="border-top: 1px solid #000;"></td>
                    </tr>
                
                </tbody>
            </table>
        </div>

        
     

         <div style="flex: 1; border: 2px solid #000; margin-left: -7px; margin-top: -10px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 7px; height: 100%;">
                <thead>
                    <tr>
                        <th style="text-align: center; padding: 4px 0px; font-weight: bolder; border-bottom: 1px solid #000; colspan="3">
                            E.C. PREVIOS.
                        </th>
                    </tr>
                </thead>
                <tbody>';

                $rowCount = 0;

                // Recorre las asignaturas y sus marcas
                foreach ($subjects as $subject) {
                    // Obtener las marcas para el subject con exam_type_id = 21
                    $marks = $this->crud_model->get_marks_by_student_subject3($student_id, $subject['subject_id'], $section['academic_period_id']);
                
                    foreach ($marks as $mark) {
                        if ($mark['exam_type_id'] == 21 && ($mark['date'] !== null && $mark['date'] !== '' && $mark['mark_obtained'] < 7)) {
                            // Convertir `mark_obtained` a entero y verificar que sea mayor a 0
                            $mark_obtained = intval($mark['mark_obtained']);
                            if ($mark_obtained > 0) {
                                $rowCount++; // Incrementa el contador de filas generadas
                
                                // Generar la fila HTML
                                $html .= '<tr style="text-align: center;">';
                                $html .= '<td style="border-top: 1px solid #000;">' . htmlspecialchars($subject['name'])
                                . '&nbsp; - &nbsp;' . $section['name'];
                                $html .= '</td> </tr>';
                            }
                        }
                    }
                }
                
                // Completar las filas faltantes hasta alcanzar el mínimo de 3
                while ($rowCount < 3) {
                    $html .= '<tr>';
                    $html .= '<td style="border-top: 1px solid #000;">&nbsp;</td>'; // Celda vacía para el nombre
                    $html .= '<td style="border-top: 1px solid #000;">&nbsp;</td>'; // Celda vacía para la nota
                    $html .= '<td style="border-top: 1px solid #000;">&nbsp;</td>'; // Celda vacía para la fecha
                    $html .= '</tr>';
                    $rowCount++;
                }
                
                $html .= '
                </tbody>
            </table>
        </div>

       
    </div>

    <div style="margin-top: 220px; text-align: center; width: 100%;">
            <div style="display: flex; justify-content: center; gap: 200px;">
                <div style="text-align: center;">
                    <div style="border-bottom: 1px dotted #000; width: 200px; margin-bottom: 5px;">
                        &nbsp;
                    </div>
                    <div style="font-size: 11px;">
                        Firma del Padre, Madre o Tutor
                    </div>
                </div>
                
                <div style="text-align: center;">
                    <div style="border-bottom: 1px dotted #000; width: 200px; margin-bottom: 5px;">
                        &nbsp;
                    </div>
                    <div style="font-size: 11px;">
                        Firma del/la Director/a
                    </div>
                </div>
            </div>
        </div>

    <!-- Second Page -->
    <div style="page-break-before: always;"></div>

    <div style="font-family: Arial, sans-serif;
            display: flex;
            justify-content: space-between;">
    
        <div class="left-side" >
            <div class="period-title">PERIODO DE EVALUACIÓN: COLOQUIO DICIEMBRE</div>
            <table class="evaluation-table" style="margin-bottom: 30px !important;">
                <tr>
                    <th rowspan="2" style="width: 11%; padding: 15px 0px !important;">DISCIPLINA</th>
                    <th rowspan="2" style="width: 8%; padding: 15px 0px !important;">FECHA</th>
                    <th colspan="2" style="width: 11%; padding: 6px 0px !important;">CALIFICACIÓN</th>
                    <th rowspan="2" style="width: 44%; padding: 15px 0px !important;">FIRMA DEL PROFESOR</th>
                </tr>
                <tr>
                    <th style="padding: 7px 5px;">N°</th>
                    <th style="padding: 7px 0px;">LETRA</th>
                </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
            </table>

            <div class="period-title">PERIODO DE EVALUACIÓN: COLOQUIO FEBRERO</div>
            <table class="evaluation-table" style="margin-bottom: 10px !important;">
                <tr>
                    <th rowspan="2" style="width: 11%; padding: 15px 0px !important;">DISCIPLINA</th>
                    <th rowspan="2" style="width: 8%; padding: 15px 0px !important;">FECHA</th>
                    <th colspan="2" style="width: 11%; padding: 6px 0px !important;">CALIFICACIÓN</th>
                    <th rowspan="2" style="width: 44%; padding: 15px 0px !important;">FIRMA DEL PROFESOR</th>
                </tr>
                <tr>
                    <th style="padding: 7px 5px;">N°</th>
                    <th style="padding: 7px 0px;">LETRA</th>
                </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
            </table>
        </div>

        <div class="right-side">
            <img src="' . base_url('assets/images/favicon2.png') . '" alt="NOMBRE DE LA INSTITUCIÓN" class="school-logo">
            
            <div class="header-text">
                <div class="" style="margin-bottom: 5px; font-weight: bolder !important;">GOBIERNO DE CÓRDOBA</div>
                <div>MINISTERIO DE EDUCACIÓN</div>
                <div>SECRETARÍA DE ESTADO DE EDUCACIÓN</div>
                <div>DIRECCIÓN GENERAL DE INSTITUTOS PRIVADOS DE ENSEÑANZAS</div>
                <br>
                <div>NOMBRE DE LA INSTITUCIÓN</div>
                <div style="font-size: 12px;">Nombre del centro educativo</div>
                <br>
                <div>Localidad: CORDOBA</div>
                <div>Departamento: CAPITAL</div>
            </div>

            <div class="report-title">
                LIBRETA DE CALIFICACIONES DEL ESTUDIANTE<br>
                PRIMER CICLO
            </div>

            <div class="student-info">
                Curso: <span style="font-weight: bolder;">';
                
                switch ($section['class_id']) {
                    case '1':
                        $html .= 'PRIMER AÑO';
                        break;
                    case '2':
                        $html .= 'SEGUNDO AÑO';
                        break;
                    case '3':
                        $html .= 'TERCER AÑO';
                        break;
                    case '4':
                        $html .= 'CUARTO AÑO';
                        break;
                    case '5':
                        $html .= 'QUINTO AÑO';
                        break;
                    case '6':
                        $html .= 'SEXTO AÑO';
                        break;
                    default:
                        echo ' '; 
                        break;
                }
                
                $html .= '</span>&nbsp;&nbsp;
                División: <span style="font-weight: bolder;">' . ucfirst($section['letter_name']) . '</span>&nbsp;&nbsp;
                Turno: <span style="font-weight: bolder;">' . $shift . '</span>
                <br>
                Estudiante: <span style="font-weight: bolder;">' . $student_data['lastname'] . ', ' . $student_data['firstname'] . '</span>
                <br>
                Tipo y N° doc: <span style="font-weight: bolder;">DNI - ' . $student_data['dni'] . '</span>
                <br>
                <span style="font-weight: bolder; font-size: 14px;">AÑO LECTIVO ' . $academic_period . '</span>
            </div>
        </div>

    </div>

    <script>window.print();</script>
</body>
</html>';

        // Mostrar el HTML
        echo $html;
    }


}

   