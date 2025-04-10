<?php

class Student extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('student/Student_model');
        $this->load->library('student_service');

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
        $page_data['page_name'] = 'student_add';
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
                    switch ($i) {
                        case 0:
                            $dataDetails['lastname'] = $cell;
                            break;
                        
                        case 1:
                            $dataDetails['firstname'] = $cell;
                            break;
                    
                        case 2:
                            switch ($cell) {
                                case "0":
                                    $dataDetails['gender_id'] = 0;
                                    break;
                                case "Masculino":
                                    $dataDetails['gender_id'] = 0;
                                    break;
                                case "Male":
                                    $dataDetails['gender_id'] = 0;
                                    break;
                                case "1":
                                    $dataDetails['gender_id'] = 1;
                                    break;
                                case "Femenino":
                                    $dataDetails['gender_id'] = 1;
                                    break;
                                case "Female":
                                    $dataDetails['gender_id'] = 1;
                                    break;
                                case "2":
                                    $dataDetails['gender_id'] = 2;
                                    break;
                                case "Otro":
                                    $dataDetails['gender_id'] = 2;
                                    break;
                                case "Other":
                                    $dataDetails['gender_id'] = 2;
                                    break;
                                default:
                                    $dataDetails['gender_id'] = null;
                                    break;
                            }
                            break;
                    
                        case 3:
                            $dataDetails['dni'] = $cell;
                            break;
                    
                        case 4:
                            $dataDetails['enrollment'] = $cell;
                            break;
                    
                        case 5:
                            $data['username'] = $cell;
                            break;
                    
                        case 6:
                            $data['email'] = $cell;
                            break;
                    
                        case 7:
                            $data['password'] = $cell;
                            break;
                    
                        case 8:
                            if (is_numeric($cell)) {
                                // Convertir el número a una fecha
                                $timestamp = ($cell - 25569) * 86400; // 25569 es el número de días desde 1900-01-01 a 1970-01-01
                                $dataDetails['birthday'] = date('Y-m-d', $timestamp);
                            } else {
                                // Si no es un número, convertir usando strtotime
                                $dataDetails['birthday'] = date('Y-m-d', strtotime($cell));
                            }
                            break;
                    
                        case 9:
                            $dataDetails['phone_cel'] = $cell;
                            break;
                    
                        case 10:
                            $dataDetails['phone_fij'] = $cell;
                            break;
                    
                        case 11:
                            $dataAddress['state'] = 'Córdoba';
                            break;
                    
                        case 12:
                            $dataAddress['postalcode'] = $cell;
                            break;
                    
                        case 13:
                            $dataAddress['locality'] = $cell;
                            break;
                    
                        case 14:
                            $dataAddress['neighborhood'] = $cell;
                            break;
                    
                        case 15:
                            $dataAddress['address'] = $cell;
                            break;
                    
                        case 16:
                            $dataAddress['address_line'] = $cell;
                            break;
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
                    'text' => ucfirst(get_phrase('manage_students')) . "&nbsp;&nbsp;/&nbsp;&nbsp;" . $this->crud_model->get_section_name($section_id),
                    'url' => base_url('index.php?admin/student_information/' . $section_id)
                )
            );
    
            $page_data['breadcrumb'] = $breadcrumb;
            $page_data['students'] = $this->Student_model->get_students_by_section($section_id);
            $page_data['page_name'] = 'student_information';
            $page_data['page_title'] = ucfirst(get_phrase('manage_students')) . ' - ' . $this->crud_model->get_section_name($section_id);
            $page_data['section_id'] = $section_id;
    
            $this->load->view('backend/index', $page_data);
        }
    
        function student_profile($student_id = '')
        {
            if ($this->session->userdata('admin_login') != 1)
                redirect('login', 'refresh');
    
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('manage_students')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                    'url' => base_url('index.php?admin/student_profile/' . $student_id)
                )
            );
    
            $page_data['breadcrumb'] = $breadcrumb;
            $page_data['page_name'] = 'student_profile';
            $page_data['page_title'] = ucfirst(get_phrase('view_profile'));
            $page_data['student_info'] = $this->Student_model->get_student_info($student_id);
    
            $this->load->view('backend/index', $page_data);
        }
    
        function student($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            if ($param1 == 'create') {
                $this->create_student();
            }
            if ($param1 == 'update') {
                $this->update_student($param2);
            }
            if ($param1 == 'inactive_student') {
                $student_id = $param2;
                $section_id = $param3;

                $reason = $this->input->post('reason');
                $other_reason = $this->input->post('other_reason');
                
                $status_reason = !empty($other_reason) ? $other_reason : $reason;

                $this->inactive_student($student_id, $section_id, $status_reason);
            }
            if ($param1 == 'inactive_student_pre_enrollements') {
                $student_id = $param2;

                $reason = $this->input->post('reason');
                $other_reason = $this->input->post('other_reason');
                
                $status_reason = !empty($other_reason) ? $other_reason : $reason;

                $this->inactive_student_pre_enrollments($student_id, $status_reason);
            }
    }

    private function create_student()
    {
        $data = $this->input->post();

        $student_data = array(
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password']
        );

        $address_data = array(
            'state' => $data['state'],
            'postalcode' => $data['postalcode'],
            'locality' => $data['locality'],
            'neighborhood' => $data['neighborhood'],
            'address' => $data['address'],
            'address_line' => $data['address_line']
        );

        $student_details_data = array(
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'dni' => $data['dni'],
            'gender_id' => $data['gender_id'],
            'phone_cel' => $data['phone_cel'],
            'phone_fij' => $data['phone_fij'],
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'birthday' => $data['birthday'],
            'photo' => $this->upload_photo('userfile', 'student_image', 'assets/images/default-user-img.jpg'),
            'medical_record' => $this->upload_photo('medical_record_file', 'fichas_medicas')
        );

        $result = $this->Student_model->create_student($student_data, $address_data, $student_details_data);

        if ($result) {
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
        } else {
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('error_adding_student')),
                'text' => '',
                'icon' => 'error',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/student_add', 'refresh');
        }
    }

    private function update_student($student_id)
    {
        $data = $this->input->post();

        $student_data = array(
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password']
        );

        $address_data = array(
            'state' => $data['state'],
            'postalcode' => $data['postalcode'],
            'locality' => $data['locality'],
            'neighborhood' => $data['neighborhood'],
            'address' => $data['address'],
            'address_line' => $data['address_line']
        );

        $student_details_data = array(
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'dni' => $data['dni'],
            'gender_id' => $data['gender_id'],
            'phone_cel' => $data['phone_cel'],
            'phone_fij' => $data['phone_fij'],
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'birthday' => $data['birthday'],
            'photo' => $this->upload_photo('userfile', 'student_image', $data['photo']),
            'medical_record' => $this->upload_photo('medical_record_file', 'fichas_medicas', $data['medical_record'])
        );

        $result = $this->Student_model->update_student($student_id, $student_data, $address_data, $student_details_data);

        if ($result) {
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
            redirect(base_url() . 'index.php?admin/student_information/' . $data['section_id'], 'refresh');
        } else {
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('error_updating_student')),
                'text' => '',
                'icon' => 'error',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/student_edit/' . $student_id, 'refresh');
        }
    }

    private function inactive_student($student_id, $section_id, $status_reason)
    {
        $result = $this->Student_model->inactive_student($student_id, $status_reason);

        if ($result) {
            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('student_inactivated_successfully')),
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
                'title' => ucfirst(get_phrase('error_inactivating_student')),
                'text' => '',
                'icon' => 'error',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
        }
        redirect(base_url() . 'index.php?admin/re_enrollments/'. $section_id, 'refresh');
    }

    private function inactive_student_pre_enrollments($student_id, $status_reason)
    {
        $result = $this->Student_model->inactive_student_pre_enrollments($student_id, $status_reason);

        if ($result) {
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

    private function upload_photo($input_name, $upload_path, $default = '')
    {
        if (!empty($_FILES[$input_name]['name'])) {
            $file_name = $input_name . ' - ' . uniqid() . '.' . pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION);
            $file_path = 'uploads/' . $upload_path . '/' . $file_name;
            move_uploaded_file($_FILES[$input_name]['tmp_name'], $file_path);
            return $file_path;
        }
        return $default;
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

   