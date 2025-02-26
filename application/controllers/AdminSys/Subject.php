<?php

class Subject extends CI_Controller
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

    function subject_profile($subject_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('subject_profile')),
                'url' => base_url('index.php?admin/subject_profile/')
            )
        );
                    
        $page_data['breadcrumb'] = $breadcrumb;

        $page_data['page_name']   = 'subject_profile';
        $page_data['page_title']  = ucfirst(get_phrase('subject_profile'));
        $page_data['subject_id']  = $subject_id;
        
        $this->load->view('backend/index', $page_data);
    }

    function subjects($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name'] = $this->input->post('name');
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $data['teacher_id'] = $this->input->post('teacher_id');
            $data['status_id'] = 1;

            $section = $this->db->get_where('section', array('section_id' => $data['section_id']))->row();
            if ($section) {
                $data['teacher_aide_id'] = $section->teacher_aide_id;
            }

            $this->db->insert('subject', $data);
            $createdSubjectId = $this->db->insert_id(); 

            // Manejar la imagen
            if (!empty($_FILES['userfile']['name'])) {
                $file_name = 'subject id - ' . $createdSubjectId . '.jpg';
                $file_path = 'uploads/subject_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);

                $this->db->where('subject_id', $createdSubjectId);
                $this->db->update('subject', ['image' => $file_name]);
            } else {
                $default_image = 'assets/images/default-subject-img.jpg';
                $this->db->where('subject_id', $createdSubjectId);
                $this->db->update('subject', ['image' => $default_image]);
            }

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('subject_added_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));

            redirect(base_url() . 'index.php?admin/subjects_information/' . $data['section_id'], 'refresh');
        }
        if ($param1 == 'update') {
            $subject_id = $param2; 

            $current_subject_data = $this->db->get_where('subject', array('subject_id' => $subject_id))->row_array();

            $data['name']       = $this->input->post('name');
            $data['class_id']   = $this->input->post('class_id');
            $data['section_id']   = $this->input->post('section_id');
            $data['teacher_id'] = $this->input->post('teacher_id');

            $section = $this->db->get_where('section', array('section_id' => $data['section_id']))->row();
            if ($section) {
                $data['teacher_aide_id'] = $section->teacher_aide_id;
            }
            

            if (!empty($_FILES['userfile']['name'])) {
                if (!empty($current_subject_data['image']) && file_exists($current_subject_data['image'])) {
                    unlink($current_subject_data['image']);
                }
        
                $file_name = 'subject id - ' . $subject_id . '.jpg';
                $file_path = 'uploads/subject_image/' . $file_name;
                $data['image'] = $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
            } else {
                $data['image'] = $current_subject_data['image'];
            }
        

            $this->db->where('subject_id', $param2);
            $this->db->update('subject', $data);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('subject_updated_successfully')),
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/subjects_information/'.$data['section_id'], 'refresh');
        } 

        if ($param1 == 'disable_subject') {
            $subject_id = $param2;  
    
            if ($subject_id) {
                $this->db->where('subject_id', $subject_id);
                $this->db->update('subject', array(
                    'status_id' => 0 
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('subject_disabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_disabling_subject')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/subjects_information/' . $param3, 'refresh');
        }
        if ($param1 == 'enable_subject') {
            $subject_id = $param2;  
    
            if ($subject_id) {
                $this->db->where('subject_id', $subject_id);
                $this->db->update('subject', array(
                    'status_id' => 1 
                ));
    
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('subject_enabled_successfully')),
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
                    'title' => ucfirst(get_phrase('error_enabling_subject')),
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
    
            redirect(base_url() . 'index.php?admin/subjects_information/' . $param3, 'refresh');
        }
       
    }
    

    
    function get_class_subject($class_id)
    {
        $subjects = $this->db->get_where('subject' , array(
            'class_id' => $class_id
        ))->result_array();
        foreach ($subjects as $row) {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_section_subjects($section_id)
    {
        $subjects = $this->db->get_where('subject' , array(
            'section_id' => $section_id
        ))->result_array();
        foreach ($subjects as $row) {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function manage_subjects()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_subjects')),
                'url' => base_url('index.php?admin/manage_subjects/')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
			
		$page_data['page_name']  = 'manage_subjects';
		$page_data['page_title'] 	= ucfirst(get_phrase('manage_subjects'));
		$this->load->view('backend/index', $page_data);
	}


    function view_subjects($section_id = '', $teacher_id = '')
    {
        if ($this->session->userdata('admin_login') != 1) {
            redirect('login', 'refresh');
        }
    
        if (empty($section_id)) {
            $active_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();
    
            if ($active_academic_period) {
                $active_academic_period_id = $active_academic_period->id;
    
                $this->db->where('academic_period_id', $active_academic_period_id);
                $this->db->order_by('section_id', 'ASC');
                $section = $this->db->get('section')->row();
    
                if ($section) {
                    $section_id = $section->section_id; 
                }
            }
        }

        $used_section_history = false;

        $this->db->where('section_id', $section_id);
        $section_data = $this->db->get('section')->row_array(); 

        if (empty($section_data)) {
            // Si está vacío, buscar en la tabla section_history
            $this->db->where('section_id', $section_id);
            $section_data = $this->db->get('section_history')->row_array();
            $used_section_history = true;
        }

        $academic_period_name = '';
        if ($used_section_history == true) {
            $academic_period_name = $this->crud_model->get_academic_period_name_per_section2($section_id);
            $page_data['academic_period_id'] = $section_data['academic_period_id']; 

        }

        $this->db->where('teacher_id', $teacher_id);
        $teacher_data = $this->db->get('teacher_details')->row_array(); 

        if (!empty($teacher_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_subjects')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst($teacher_data['lastname']) . ', ' . ucfirst($teacher_data['firstname']),
                    'url' => base_url('index.php?admin/view_subjects/' . $section_id . '/' . $teacher_id)
                )
            );
        } else if (!empty($section_id)) {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_subjects')) . ($used_section_history ? '&nbsp;&nbsp;/&nbsp;&nbsp;' . $academic_period_name : '') .  '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'],
                    'url' => base_url('index.php?admin/view_subjects/' . $section_id)
                )
            );
        } else {
            $breadcrumb = array(
                array(
                    'text' => ucfirst(get_phrase('home')),
                    'url' => base_url('index.php?admin/dashboard')
                ),
                array(
                    'text' => ucfirst(get_phrase('view_subjects')) .  '&nbsp;&nbsp;/&nbsp;&nbsp;' . $section_data['name'],
                    'url' => base_url('index.php?admin/view_subjects/' . $section_id)
                ) 
            );
        }
    
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
        $page_data['teacher_id'] = $teacher_id;
        $page_data['used_section_history'] = $used_section_history;
        $page_data['page_name'] = 'view_subjects';
        $page_data['page_title'] = ucfirst(get_phrase('view_subjects'));
        $this->load->view('backend/index', $page_data);
    }

    function subjects_information($section_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('subjects_information')),
                'url' => base_url('index.php?admin/subjects_information/' . $section_id)
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['section_id'] = $section_id;
		$page_data['page_name']  = 'subjects_information';
		$page_data['page_title'] 	= ucfirst(get_phrase('subjects_information'));
		$this->load->view('backend/index', $page_data);
	}

    function add_subject()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('add_subject')),
                'url' => base_url('index.php?admin/subject_add')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;

		$page_data['page_name']  = 'add_subject';
		$page_data['page_title'] = ucfirst(get_phrase('add_subject'));
		$this->load->view('backend/index', $page_data);
	}

    function edit_subject($subject_id)
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

            $page_complete_name = 'edit_subject'; // Nombre de la página
            $user_id = $this->session->userdata('login_user_id'); // ID del usuario actual
            $user_group = $this->session->userdata('login_type'); // Grupo del usuario actual
            $element_id = $subject_id; // ID del elemento específico (ej. curso o sección)

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
                'text' => ucfirst(get_phrase('edit_subject')),
                'url' => base_url('index.php?admin/edit_subject')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['subject_id']  = $subject_id;
		$page_data['page_name']  = 'edit_subject';
		$page_data['page_title'] = ucfirst(get_phrase('edit_subject'));
		$this->load->view('backend/index', $page_data);
	}

    function get_subject_exams($subject_id)
    {
        $exams = $this->db->get_where('exam' , array(
            'subject_id' => $subject_id
        ))->result_array();
        foreach ($exams as $row) {
            echo '<option value="' . $row['exam_id'] . '">' . $row['name'] . '</option>';
        }
    }










}