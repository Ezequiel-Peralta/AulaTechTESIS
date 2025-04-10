<?php

class UserSys extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $this->load->model('usersys/Usersys_model');
        $this->load->library('UserSys_service');

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function manage_profile($user_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('manage_profile')) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . ucfirst(get_phrase('view_profile')),
                'url' => base_url('index.php?admin/manage_profile/' . $user_id)
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'manage_profile';
        $page_data['page_title'] = ucfirst(get_phrase('manage_profile')) . ' - ' . ucfirst(get_phrase('view_profile'));
        $page_data['user_id'] = $user_id;

        $this->load->view('backend/index', $page_data);
    }

    function help()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('help')),
                'url' => base_url('index.php?admin/help/')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'help';
        $page_data['page_title'] = ucfirst(get_phrase('help'));

        $this->load->view('backend/index', $page_data);
    }

    function get_postalcode_localidad($postal_code)
    {
        $localidades = $this->Usersys_model->get_postalcode_localidad($postal_code);
        foreach ($localidades as $row) {
            echo '<option value="' . $row['localidad'] . '">' . $row['localidad'] . '</option>';
        }
    }

    function get_postal_codes() {
        $postal_codes = $this->Usersys_model->get_postal_codes();
        foreach ($postal_codes as $row) {
            echo '<option value="' . $row['postal_code'] . '">' . $row['postal_code'] . '</option>';
        }
    }

    function profile_settings($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($param1 == 'update_profile_info') {
            $user_id = $this->session->userdata('login_user_id');

            $dataDetails['firstname'] = $this->input->post('firstname');
            $dataDetails['lastname'] = $this->input->post('lastname');
            $data['email'] = $this->input->post('email');

            $this->Usersys_model->update_user_info($user_id, $data);

            if (!empty($_FILES['userfile']['name'])) {
                $file_name = 'admin id - ' . $user_id . '.jpg';
                $file_path = 'uploads/admin_image/' . $file_name;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path);
                $dataDetails['photo'] = $file_path;
            } else {
                $dataDetails['photo'] = 'assets/images/default-user-img.jpg';
            }

            $this->Usersys_model->update_user_details($user_id, $dataDetails);

            $this->session->set_flashdata('flash_message', array(
                'title' => ucfirst(get_phrase('profile_updated_successfully')) . '!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => ucfirst(get_phrase('accept')),
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/profile_settings/' . $user_id, 'refresh');
        }
        if ($param1 == 'change_password') {
            $user_id = $this->session->userdata('login_user_id');

            $data['password'] = $this->input->post('password');
            $data['new_password'] = $this->input->post('new_password');
            $data['confirm_new_password'] = $this->input->post('confirm_new_password');

            $current_password = $this->Usersys_model->get_current_password($user_id);
            if ($current_password !== $data['password']) {
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('the_old_password_does_not_match')) . '!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            } else if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                $this->Usersys_model->update_password($user_id, $data['new_password']);
                $this->session->set_flashdata('flash_message', array(
                    'title' => ucfirst(get_phrase('password_updated_successfully')) . '!',
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
                    'title' => ucfirst(get_phrase('new_password_and_confirmation_do_not_match')) . '!',
                    'text' => '',
                    'icon' => 'error',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => ucfirst(get_phrase('accept')),
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
            }
            redirect(base_url() . 'index.php?admin/profile_settings/' . $user_id, 'refresh');
        }

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('profile_settings')),
                'url' => base_url('index.php?admin/manage_profile')
            )
        );

        $page_data['breadcrumb'] = $breadcrumb;
        $page_data['page_name'] = 'profile_settings';
        $page_data['page_title'] = ucfirst(get_phrase('profile_settings'));
        $page_data['edit_data'] = $this->crud_model->get_admin_info($this->session->userdata('admin_id'));

        $this->load->view('backend/index', $page_data);
    }

    function change_theme($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $this->session->set_userdata('theme_preference', $param2);

        $this->Usersys_model->update_theme_preference($param1, $param2);

        redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
    }

    function change_language($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $this->session->set_userdata('language_preference', $param2);

        $this->Usersys_model->update_language_preference($param1, $param2);

        redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
    }

    function language_settings($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		
            if ($param1 == 'open_edit_phrase_language') {
                $page_data['current_editing_language'] 	= $param2;	
            }
            if ($param1 == 'edit_phrase_language') {
                $language = $param2;
                $total_phrase = $this->input->post('total_phrase');
                // Comienza el bucle desde 1 ya que el primer phrase_id puede ser 1
                for ($i = 1; $i < $total_phrase; $i++) {
                    // Obtén el valor del input correspondiente a esta iteración
                    $phrase_language = $this->input->post('phrase' . $i);
                    // Actualiza la columna de lenguaje específica para el phrase_id actual
                    $this->db->where('phrase_id', $i);
                    $this->db->update('language', array($language => $phrase_language));
                }
                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Definiciones del idioma actualizadas correctamente!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                redirect(base_url() . 'index.php?admin/language_settings/', 'refresh');
            }
            
            if ($param1 == 'edit_phrase') {
                // Obtenemos el nuevo valor de la frase desde el formulario
                $new_phrase_value = $this->input->post('phrase');
                
                // Creamos un array con los datos a actualizar
                $data = array('phrase' => $new_phrase_value);
                
                // Filtramos la actualización para que solo afecte a la fila con el phrase_id proporcionado
                $this->db->where('phrase_id', $param2);
                
                // Ejecutamos la actualización en la base de datos
                $this->db->update('language', $data);
                
                // Configuramos un mensaje de sesión para indicar que la frase se actualizó correctamente
                $this->session->set_flashdata('flash_message', array(
                    'title' => 'Frase actualizada correctamente!',
                    'text' => '',
                    'icon' => 'success',
                    'showCloseButton' => 'true',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#1a92c4',
                    'timer' => '10000',
                    'timerProgressBar' => 'true',
                ));
                
                // Redireccionamos de vuelta a la página de configuración de idioma
                redirect(base_url() . 'index.php?admin/language_settings/', 'refresh');
            }
        if ($param1 == 'edit_language') {
            // Obtener el nuevo nombre del idioma del formulario POST.
            $new_language_name = $this->input->post('language_name');
        
            // Obtener el nombre actual del idioma desde el parámetro.
            $current_language_name = $param2;
        
            // Cambiar el nombre de la columna en la base de datos.
            $this->db->query("ALTER TABLE `language` CHANGE `$current_language_name` `$new_language_name` VARCHAR(255)");
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Idioma actualizado correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
			redirect(base_url() . 'index.php?admin/language_settings/', 'refresh');
		}
		if ($param1 == 'add_phrase') {
			$data['phrase'] = $this->input->post('phrase');
			$this->db->insert('language', $data);
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Frase añadida correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
			redirect(base_url() . 'index.php?admin/language_settings/', 'refresh');
		}
		if ($param1 == 'add_language') {
			$language = $this->input->post('language');
			$this->load->dbforge();
			$fields = array(
				$language => array(
					'type' => 'VARCHAR',
                    'constraint' => '255'
				)
			);
			$this->dbforge->add_column('language', $fields);
			
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Idioma añadido correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
			redirect(base_url() . 'index.php?admin/language_settings/', 'refresh');
		}
		if ($param1 == 'delete_language') {
			$language = $param2;
			$this->load->dbforge();
			$this->dbforge->drop_column('language', $language);
			// $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Idioma eliminado correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
			redirect(base_url() . 'index.php?admin/language_settings/', 'refresh');
		}

        if ($param1 == 'delete_languages_bulk') {
            $segments = $this->uri->segment_array();
            $index = array_search('delete_languages_bulk', $segments);
        
            $languages = array_slice($segments, $index);
        
            $this->load->dbforge();
            foreach ($languages as $language) {
                $this->dbforge->drop_column('language', $language);
            }
        
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Idiomas eliminados correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
            redirect(base_url() . 'index.php?admin/language_settings/', 'refresh');
        }

        if ($param1 == 'delete_phrase') {
            $this->db->where('phrase_id', $param2);
            $this->db->delete('language');
            $this->session->set_flashdata('flash_message', array(
                'title' => 'Frase eliminada correctamente!',
                'text' => '',
                'icon' => 'success',
                'showCloseButton' => 'true',
                'confirmButtonText' => 'Aceptar',
                'confirmButtonColor' => '#1a92c4',
                'timer' => '10000',
                'timerProgressBar' => 'true',
            ));
			redirect(base_url() . 'index.php?admin/language_settings/', 'refresh');
		}

        $breadcrumb = array(
            array(
                'text' => ucfirst(get_phrase('home')),
                'url' => base_url('index.php?admin/dashboard')
            ),
            array(
                'text' => ucfirst(get_phrase('language_settings')),
                'url' => base_url('index.php?admin/language_settings')
            )
        );
                
        $page_data['breadcrumb'] = $breadcrumb;
		$page_data['page_name']        = 'language_settings';
		$page_data['page_title']       = ucfirst(get_phrase('language_settings'));
		$this->load->view('backend/index', $page_data);	
    }

    function get_all_users()
    {
        $user_id = $this->session->userdata('login_user_id'); 
        $user_group = $this->session->userdata('login_type');

        $user_types = ['admin', 'student', 'guardian', 'teacher', 'teacher_aide'];

        $output = '<option value="">' . 'Seleccionar un destinatario' . '</option>';

        foreach ($user_types as $type) {
            $this->db->select("$type.{$type}_id as user_id, $type.email, {$type}_details.firstname, {$type}_details.lastname");
            $this->db->from($type);
            $this->db->join("{$type}_details", "{$type}.{$type}_id = {$type}_details.{$type}_id", 'left');
            $users = $this->db->get()->result_array();

            $output .= '<optgroup label="' . ucfirst($type) . '">';

            foreach ($users as $user) {
                if ($user['user_id'] == $user_id && $type == $user_group) {
                    continue; 
                }

                $fullname = $user['lastname'] . ', ' . $user['firstname'] . '.';
                $output .= '<option value="' . $type . '-' . $user['user_id'] . '" title="' . $fullname . '" data-cc-group="' . $type . '" data-firstname="' . $user['firstname'] . '" data-lastname="' . $user['lastname'] . '">'
                . $user['email'] . ' (<span>' . $fullname . '</span>)</option>';
            }

            $output .= '</optgroup>';
        }

        echo $output;
    }

    function get_all_users2()
    {
        $user_id = $this->session->userdata('login_user_id'); 
    $user_group = $this->session->userdata('login_type');

    $user_types = ['admin', 'student', 'guardian', 'teacher', 'teacher_aide'];

    $output = '<option value="">' . 'Seleccionar un destinatario' . '</option>';

    foreach ($user_types as $type) {
        $this->db->select("$type.{$type}_id as user_id, $type.email, {$type}_details.firstname, {$type}_details.lastname");
        $this->db->from($type);
        $this->db->join("{$type}_details", "{$type}.{$type}_id = {$type}_details.{$type}_id", 'left');
        $users = $this->db->get()->result_array();

        $output .= '<optgroup label="' . ucfirst($type) . '">';

        foreach ($users as $user) {
            if ($user['user_id'] == $user_id && $type == $user_group) {
                continue; 
            }

            $fullname = $user['lastname'] . ', ' . $user['firstname'] . '.';
            $output .= '<option value="' . $type . '-' . $user['user_id'] . '" title="' . $fullname . '" data-cc-group="' . $type . '" data-firstname="' . $user['firstname'] . '" data-lastname="' . $user['lastname'] . '">'
            . $user['email'] . ' (<span>' . $fullname . '</span>)</option>';
        }

        $output .= '</optgroup>';
    }

    echo $output;
    }

    function get_all_users3()
    {
        $user_id = $this->session->userdata('login_user_id'); 
        $user_group = $this->session->userdata('login_type');
    
        $user_types = ['admin', 'student', 'guardian', 'teacher', 'teacher_aide'];
    
        $output = '<option value="">' . 'Seleccionar un destinatario' . '</option>';
    
        foreach ($user_types as $type) {
            $this->db->select("$type.{$type}_id as user_id, $type.email, {$type}_details.firstname, {$type}_details.lastname");
            $this->db->from($type);
            $this->db->join("{$type}_details", "{$type}.{$type}_id = {$type}_details.{$type}_id", 'left');
            $users = $this->db->get()->result_array();
    
            $output .= '<optgroup label="' . ucfirst($type) . '">';
    
            foreach ($users as $user) {
                if ($user['user_id'] == $user_id && $type == $user_group) {
                    continue; 
                }
    
                $fullname = $user['lastname'] . ', ' . $user['firstname'] . '.';
                $output .= '<option value="' . $type . '-' . $user['user_id'] . '" title="' . $fullname . '" data-cc-group="' . $type . '" data-firstname="' . $user['firstname'] . '" data-lastname="' . $user['lastname'] . '">'
                . $user['email'] . ' (<span>' . $fullname . '</span>)</option>';
            }
    
            $output .= '</optgroup>';
        }
    
        echo $output;
    }


    function get_users($param1 = '', $param2 = '')
    {
        // Crear un array para almacenar los tipos de usuarios y sus tablas
        $user_types = ['admin', 'student', 'guardian', 'teacher', 'teacher_aide'];
    
        // Inicializar la salida HTML
        $output = '<option value="">' . 'Seleccionar un destinatario' . '</option>';
    
        // Iterar sobre cada tipo de usuario
        foreach ($user_types as $type) {
            // Seleccionar el id, email, firstname y lastname para cada tipo de usuario
            $this->db->select("$type.{$type}_id as user_id, $type.email, {$type}_details.firstname, {$type}_details.lastname");
            $this->db->from($type);
            $this->db->join("{$type}_details", "{$type}.{$type}_id = {$type}_details.{$type}_id", 'left');
            $users = $this->db->get()->result_array();
    
            // Comenzar el optgroup para el tipo de usuario
            $output .= '<optgroup label="' . ucfirst($type) . '">';
    
            // Agregar cada usuario dentro del optgroup
            foreach ($users as $user) {
                // Formatear apellido, nombre
                $fullname = $user['lastname'] . ', ' . $user['firstname'] . '.';
                // Generar la opción con el formato deseado
                $output .= '<option value="' . $type. '-' . $user['user_id'] . '" title="' . $fullname . '" data-cc-group="' . $type . '" data-firstname="' . $user['firstname'] . '" data-lastname="' . $user['lastname'] . '">'
                . $user['email'] . ' (<span>' . $fullname . '</span>)</option>';
            }
    
            // Cerrar el optgroup
            $output .= '</optgroup>';
        }
    
        // Imprimir el HTML generado
        echo $output;
    }

    
    

}