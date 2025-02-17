<?php

class Admissions extends CI_Controller
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


    function admissions()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('admissions')),
                'url' => base_url('index.php?admin/admissions/')
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
            student_details.status_reason,
            address.state, 
            address.postalcode, 
            address.locality, 
            address.neighborhood,
            address.address,
            address.address_line
        ');
        $this->db->from('student');
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        
        $this->db->join('address', 'student_details.address_id = address.address_id', 'left'); 
        
        $this->db->where('student_details.user_status_id', 0);
        $this->db->where('student_details.class_id IS NULL');
        $this->db->or_where('student_details.class_id', '');
        $this->db->where('student_details.section_id IS NULL');
        $this->db->or_where('student_details.section_id', '');
        $query = $this->db->get();
        $page_data['students']  = $query->result_array();

        $page_data['page_name']   = 'admissions';
        $page_data['page_icon']   = 'entypo-graduation-cap';
        $page_data['page_tile']   = 'admissions';
        $page_data['page_title']  = ucfirst(get_phrase('admissions'));
        
        $this->load->view('backend/index', $page_data);
    }


    function admissions_student($param1 = '', $param2 = '', $param3 = '')
    { 
        if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
        if ($param1 == 'create') {
             // Obtener los datos enviados por POST
            $student_id = $param2;
            $section_id = $param3;

            // Obtener el class_id a partir del section_id
            $this->db->select('class_id');
            $this->db->from('section');
            $this->db->where('section_id', $section_id);
            $section = $this->db->get()->row();

            if ($section) {
                $class_id = $section->class_id;
                $academic_period_id = $section->academic_period_id;

                // Actualizar la información del estudiante
                $this->db->where('student_id', $student_id);
                $this->db->update('student_details', array(
                    'class_id' => $class_id,
                    'section_id' => $section_id,
                    'user_status_id' => 1, 
                    'status_reason' => ''
                ));

                $this->db->insert('academic_history', array(
                    'student_id' => $student_id,
                    'old_class_id' => null,
                    'old_section_id' => null,
                    'new_class_id' => $class_id,
                    'new_section_id' => $section_id,
                    'old_academic_period_id' => null,
                    'new_academic_period_id' => $academic_period_id,
                    'date_change' => date('Y-m-d')
                ));

                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Estudiante inscripto correctamente!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/admissions/', 'refresh');
            } else {
                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Estudiante no inscripto!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/admissions/', 'refresh');
            }
        }

        if ($param1 == 're_enrollment_bulk') {
            $segments = $this->uri->segment_array();
            $index = array_search('re_enrollment_bulk', $segments);
        
            // Obtiene el class_id desde el primer parámetro después de 'pre_enrollment_bulk'
            $class_id = isset($segments[$index + 1]) ? $segments[$index + 1] : null;
        
            // Obtiene el section_id desde el segundo parámetro después de 'class_id'
            $section_id = isset($segments[$index + 2]) ? $segments[$index + 2] : null;
        
            // Obtiene los student_id a partir del tercer parámetro en adelante
            $students = array_slice($segments, $index + 2);
            $students = array_filter($students, 'is_numeric');

             $this->db->select('academic_period_id');
             $this->db->from('section');
             $this->db->where('section_id', $section_id);
             $section_data = $this->db->get()->row();
 
                 $academic_period_id = $section_data->academic_period_id;
            
            if (!empty($students)) {
                // Actualiza la clase y la sección de cada estudiante en la base de datos
                $this->db->where_in('student_id', $students);
                $this->db->update('student_details', [
                    'class_id' => $class_id, 
                    'section_id' => $section_id, 
                    'user_status_id' => 1, 
                    'status_reason' => ''
                ]);

                $this->db->insert('academic_history', array(
                    'student_id' => $students,
                    'old_class_id' => null,
                    'old_section_id' => null,
                    'new_class_id' => $class_id,
                    'new_section_id' => $section_id,
                    'old_academic_period_id' => null,
                    'new_academic_period_id' => $academic_period_id,
                    'date_change' => date('Y-m-d')
                ));
        
                // Mensaje de éxito
                $this->session->set_flashdata('flash_message', array(
                    'title' => '¡' . ucfirst(get_phrase('students_re_enrolled_successfully')) . '!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => true,
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => 10000,
                    'timerProgressBar' => true,
                ));
        
                // Redirige a la lista de preinscripciones
                redirect(base_url() . 'index.php?admin/student_information/' . $section_id, 'refresh');
            } 
        }

    }











}