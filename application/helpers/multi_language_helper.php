<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

if (!function_exists('get_phrase')) {
    function get_phrase($phrase = '') {
        $CI =& get_instance();
        $CI->load->database();
        $current_language = $CI->session->userdata('language_preference'); // Obtener la preferencia de idioma del usuario

        if (!$current_language) {
            // Si no hay una preferencia de idioma definida, establecer en español como predeterminado
            $current_language = 'spanish';
            $CI->session->set_userdata('language_preference', $current_language);
        }

        // Verificar si la frase ya existe en la base de datos de idiomas y agregarla si no
        // $check_phrase = $CI->db->get_where('language', array('phrase' => $phrase))->row()->phrase;
        // if ($check_phrase != $phrase) {
        //     $CI->db->insert('language', array('phrase' => $phrase));
        // }

        // Consultar la traducción de la frase en el idioma actual
        $query = $CI->db->get_where('language', array('phrase' => $phrase));
        $row = $query->row();   

        // Devolver la traducción en el idioma actual si está disponible, de lo contrario, devolver la frase original con mayúsculas y espacios
        if (isset($row->$current_language) && $row->$current_language != "") {
            return $row->$current_language;
        } else {
            return ucfirst(str_replace('_', ' ', $phrase));
        }
    }
}

// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */