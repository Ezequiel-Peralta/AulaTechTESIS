<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Language extends CI_Controller
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
    




}
    

