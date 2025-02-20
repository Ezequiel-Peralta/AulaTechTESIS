<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller
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

    function payments()
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
                'text' => 'payments',
                'url' => base_url('index.php?admin/payments/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $this->db->select('student.student_id, student.email, student.username, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.section_id, student_details.class_id, student_details.user_status_id');
        $this->db->from('student');
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        $this->db->where('student_details.user_status_id', 1);
        $this->db->where('student_details.class_id IS NULL');
        $this->db->or_where('student_details.class_id', '');
        $this->db->where('student_details.user_status_id', 1);
        $this->db->where('student_details.section_id IS NULL');
        $this->db->or_where('student_details.section_id', '');
        $this->db->where('student_details.user_status_id', 1);
        $query = $this->db->get();
        $page_data['students']  = $query->result_array();


        $page_data['page_name']   = 'payments';
        $page_data['page_title']  = 'payments';
        
        $this->load->view('backend/index', $page_data);
    }



    function parent($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']        			= $this->input->post('name');
            $data['dni']        			= $this->input->post('dni');
            $data['email']       			= $this->input->post('email');
            $data['password']    			= $this->input->post('password');
            $data['phone_cel']       			= $this->input->post('phone_cel');
            $data['phone_fij']       			= $this->input->post('phone_fij');
            $data['address']     			= $this->input->post('address');
            $data['gender']  			= $this->input->post('gender');
            $this->db->insert('parent', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('datos agregados exitosamente'));
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        if ($param1 == 'edit') {
            $data['name']        			= $this->input->post('name');
            $data['dni']        			= $this->input->post('dni');
            $data['email']       			= $this->input->post('email');
            $data['password']    			= $this->input->post('password');
            $data['phone_cel']       			= $this->input->post('phone_cel');
            $data['phone_fij']       			= $this->input->post('phone_fij');
            $data['address']     			= $this->input->post('address');
            $data['gender']  			= $this->input->post('gender');
            $this->db->where('parent_id' , $param2);
            $this->db->update('parent' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('datos actualizados exitosamente'));
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        if ($param1 == 'delete') {
            $this->db->where('parent_id' , $param2);
            $this->db->delete('parent');
            $this->session->set_flashdata('flash_message' , get_phrase('datos eliminados exitosamente'));
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        $page_data['page_title'] 	= ucfirst(get_phrase('parent_section'));
        $page_data['page_icon'] 	= 'entypo-users';
        $page_data['page_name']  = 'parent';
        $this->load->view('backend/index', $page_data);
    }
	

    // function get_postal_codes() {
    //     $postal_codes = $this->db->get('postal_code_cba')->result_array();
    //     foreach ($postal_codes as $row) {
    //         echo '<option value="' . $row['postal_code'] . '">' . $row['postal_code'] . '</option>';
    //     }
    // }

	
    // function attendance_teacher($date='',$month='',$year='',$class_id='')
	// {
    //     if ($this->session->userdata('admin_login') != 1)
    //     redirect(base_url(), 'refresh');
		
	// 	if($_POST)
	// 	{
    //         $students   =   $this->db->get_where('student', array('class_id' => $class_id))->result_array();
    //         foreach ($students as $row)
    //         {
    //             $attendance_status  =   $this->input->post('status_' . $row['student_id']);

    //             $this->db->where('student_id' , $row['student_id']);
    //             $this->db->where('date' , $this->input->post('date'));

    //             $this->db->update('attendance' , array('status' => $attendance_status));
    //         }

	// 		$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
	// 		redirect(base_url() . 'index.php?admin/attendance/'.$date.'/'.$month.'/'.$year.'/'.$class_id , 'refresh');
	// 	}
    //     $page_data['date']     =	$date;
    //     $page_data['month']    =	$month;
    //     $page_data['year']     =	$year;
    //     $page_data['class_id'] =	$class_id;
		
    //     $page_data['page_name']  =	'attendance';
    //     $page_data['page_title'] =	'Administrar asistencia';
	// 	$this->load->view('backend/index', $page_data);
	// }

    // function attendance_teacher_selector()
	// {
	// 	redirect(base_url() . 'index.php?admin/attendance/'.$this->input->post('date').'/'.
	// 				$this->input->post('month').'/'.
	// 					$this->input->post('year').'/'.
	// 						$this->input->post('class_id') , 'refresh');
	// }

    
    // function attendance_student($class_id = '') {
    //     if ($this->session->userdata('admin_login') != 1)
    //         redirect(base_url(), 'refresh');
        
    //     $data['all_classes'] = $this->db->get('class')->result_array();
    //     $data['page_name'] = 'attendance_student';
    //     $data['page_icon'] = 'entypo-clipboard';
    //     $data['page_title'] = 'Administrar asistencia de estudiantes';
    //     $data['class_id'] = $class_id;
    //     $this->load->view('backend/index', $data);
    // }
    
    


    

    // function upload_observation_student_image () 
    // {
    //     $day      = $this->input->post('day');
    //     $month  = $this->input->post('month');
    //     $year = $this->input->post('year');
    //     $class_id  = $this->input->post('class_id');
    //     $section_id = $this->input->post('section_id');
    //     $student_id = $this->input->post('student_id');

    //     $student = $this->db->get_where('student', array('student_id' => $student_id))->row_array();
    //     $file_name = $student['name'] . '-' . $student['matricula']  . '-ausenciaJustificada.jpg';
    //     $file_path = 'uploads/asistencias/' . $file_name;

    //     move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
    //     redirect(base_url() . 'index.php?admin/manage_attendance_student/'.$day.'/'.$month.'/'.$year.'/'.$class_id.'/'.$section_id);
    // }

    function notes($param1 = '', $param2 = '', $param3 = '', $param4 = '', $param5 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['note_title'] = urldecode($param3);
            $data['note_body'] = urldecode($param4);
            $data['user_type'] = 'admin';
            $data['user_id'] = $param5;
             
            $this->db->insert('notes', $data);
           $this->session->set_flashdata('flash_message' , get_phrase('nota agregada exitosamente'));
            // redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        }
        if ($param1 == 'update') {
            $data['note_title'] = urldecode($param3);
            $data['note_body'] = urldecode($param4);
        
            $this->db->where('note_id', $param2);
            $this->db->update('notes', $data);
           
            $this->crud_model->clear_cache();
            $this->session->set_flashdata('flash_message' , get_phrase('nota actualizada exitosamente'));
            // redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        } 
		
        if ($param1 == 'delete') {
            $this->db->where('note_id', $param2);
            $this->db->delete('notes');
            $this->session->set_flashdata('flash_message' , get_phrase('datos eliminados exitosamente'));
            // redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
        }
    }
}