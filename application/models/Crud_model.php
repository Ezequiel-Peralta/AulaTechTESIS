<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crud_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function clear_cache() {
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function get_type_name_by_id($type, $type_id = '', $field = 'name') {
        return $this->db->get_where($type, array($type . '_id' => $type_id))->row()->$field;
    }

    

    

    function get_section_info_per_teacher_aide($teacher_aide_id) {
        // Selecciona las columnas necesarias de la tabla section
        $this->db->select('section_id, name, letter_name, class_id, shift_id');
        $this->db->from('section');
        
        // Agrega la condición para el teacher_aide_id
        $this->db->where('teacher_aide_id', $teacher_aide_id);
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Devuelve los resultados como un array
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array(); // Devuelve un array vacío si no hay resultados
        }
    }

    
    
    

    
    

    function get_secretarys() {
        $query = $this->db->get('secretary');
        return $query->result_array();
    }

    function get_secretary_name($secretary_id) {
        $query = $this->db->get_where('secretary', array('secretary_id' => $secretary_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name'];
    }

    function get_secretary_info($secretary_id) {
        $this->db->select('secretary.secretary_id, secretary.email, secretary.username, secretary.password, secretary_details.firstname, secretary_details.lastname, secretary_details.dni, secretary_details.photo,  secretary_details.phone_cel, secretary_details.phone_fij, secretary_details.birthday, secretary_details.gender_id, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
        
        $this->db->from('secretary');
        
        $this->db->join('secretary_details', 'secretary.secretary_id = secretary_details.secretary_id');
        
        $this->db->join('address', 'secretary_details.address_id = address.address_id');
        
        $this->db->where('secretary.secretary_id', $secretary_id);
        
        $query = $this->db->get();
        
        return $query->result_array();
    }

    function get_principals() {
        $query = $this->db->get('principal');
        return $query->result_array();
    }

    function get_principal_name($principal_id) {
        $query = $this->db->get_where('principal', array('principal_id' => $principal_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name'];
    }

    function get_principal_info($principal_id) {
        $this->db->select('principal.principal_id, principal.email, principal.username, principal.password, principal_details.firstname, principal_details.lastname, principal_details.dni, principal_details.photo,  principal_details.phone_cel, principal_details.phone_fij, principal_details.birthday, principal_details.gender_id, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
        
        $this->db->from('principal');
        
        $this->db->join('principal_details', 'principal.principal_id = principal_details.principal_id');
        
        $this->db->join('address', 'principal_details.address_id = address.address_id');
        
        $this->db->where('principal.principal_id', $principal_id);
        
        $query = $this->db->get();
        
        return $query->result_array();
    }

    

    function get_class_name($class_id) {
        $query = $this->db->get_where('class', array('class_id' => $class_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name'];
    }

    function get_class_name_numeric($class_id) {
        $query = $this->db->get_where('class', array('class_id' => $class_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name'];
    }

    function get_section_letter_name($section_id) {
        $query = $this->db->get_where('section', array('section_id' => $section_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['letter_name'];
    }


    function get_section_letter_name2($section_id) {
        // Intentar obtener el registro de la tabla 'section'
        $query = $this->db->get_where('section', array('section_id' => $section_id));
        $res = $query->result_array();
        
        // Si no se encuentra registro en 'section', intentar con 'section_history'
        if (empty($res)) {
            $query = $this->db->get_where('section_history', array('section_id' => $section_id));
            $res = $query->result_array();
        }
        
        // Si se encuentra algún registro, retornar el 'letter_name'
        if (!empty($res)) {
            return $res[0]['letter_name'];
        }
        
        // Si no se encuentra ningún registro, retornar NULL o valor por defecto
        return null;
    }


    function get_section_history_letter_name($section_id) {
        $query = $this->db->get_where('section_history', array('section_id' => $section_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['letter_name'];
    }

    function get_section_content_by_class($class_id)
    {
        $this->db->select('section.section_id, section.name');
        $this->db->where('section.class_id', $class_id); 
        $this->db->from('section');
        $this->db->join('academic_period', 'section.academic_period_id = academic_period.id');
        $this->db->where('academic_period.status_id', 1); 
        $sections = $this->db->get()->result_array();
        return $sections;
    }

    function get_section_content_by_academic_period($academic_period_id)
    {
        $this->db->select('section.section_id, section.name');
        $this->db->from('section');
        $this->db->where('section.academic_period_id', $academic_period_id);
        $sections = $this->db->get()->result_array();

        if (empty($sections)) {
            $this->db->select('section_history.section_id, section_history.name');
            $this->db->from('section_history');
            $this->db->where('section_history.academic_period_id', $academic_period_id);
            $sections = $this->db->get()->result_array();
        }

        return $sections;
    }


    
    function get_section_name($section_id) {
        $query = $this->db->get_where('section', array('section_id' => $section_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name'];
    }

    function get_section_name2($section_id) {
        $query = $this->db->get_where('section', array('section_id' => $section_id));
        $res = $query->result_array();
        
        if (!empty($res)) {
            return $res[0]['name'];
        }
        
        $query_history = $this->db->get_where('section_history', array('section_id' => $section_id));
        $res_history = $query_history->result_array();
        
        if (!empty($res_history)) {
            return $res_history[0]['name'];
        }
        
        return null;
    }

    function get_section_history_name($section_id) {
        $query = $this->db->get_where('section_history', array('section_id' => $section_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name'];
    }

    function get_classes() {
        $query = $this->db->get('class');
        return $query->result_array();
    }

    function get_class_info($class_id) {
        $query = $this->db->get_where('class', array('class_id' => $class_id));
        return $query->result_array();
    }

    function get_section_info($section_id) {
        $query = $this->db->get_where('section', array('section_id' => $section_id));
        return $query->row_array(); 
    }

    function get_section_info2($section_id) {
        $query = $this->db->get_where('section', array('section_id' => $section_id));
        return $query->result_array();
    }

    function get_section_info3($section_id) {
        // Buscar en la tabla 'section'
        $query = $this->db->get_where('section', array('section_id' => $section_id));
        $result = $query->result_array(); // Devuelve una sola fila como array asociativo.
        
        // Si no encuentra registros, buscar en 'section_history'
        if (empty($result)) {
            $query = $this->db->get_where('section_history', array('section_id' => $section_id));
            $result = $query->result_array();
        }
        
        return $result;
    }
    

    function get_section_info4($section_id) {
        // Buscar en la tabla 'section'
        $query = $this->db->get_where('section', array('section_id' => $section_id));
        $result = $query->row_array(); // Devuelve una sola fila como array asociativo.
        
        // Si no encuentra registros, buscar en 'section_history'
        if (empty($result)) {
            $query = $this->db->get_where('section_history', array('section_id' => $section_id));
            $result = $query->row_array();
        }
        
        return $result;
    }
    
    
    function get_academic_period_name_per_section ($section_id) {
        $section = $this->db->get_where('section', array('section_id' => $section_id));
        
        if ($section->num_rows() > 0) {
            $academic_period_id = $section->row()->academic_period_id;
            
            $query = $this->db->get_where('academic_period', array('id' => $academic_period_id));
            
            if ($query->num_rows() > 0) {
                return $query->row()->name; // Devuelve el nombre del período académico
            }
        }
        
        return '';
    }

    
    function get_academic_period_name_per_section2($section_id) {
        // Buscar en la tabla 'section'
        $section = $this->db->get_where('section', array('section_id' => $section_id));
        
        // Si no encuentra en 'section', buscar en 'section_history'
        if ($section->num_rows() == 0) {
            $section = $this->db->get_where('section_history', array('section_id' => $section_id));
        }
        
        // Verificar si encontró datos en alguna de las tablas
        if ($section->num_rows() > 0) {
            $academic_period_id = $section->row()->academic_period_id;
    
            // Buscar el nombre del período académico en la tabla 'academic_period'
            $query = $this->db->get_where('academic_period', array('id' => $academic_period_id));
            
            if ($query->num_rows() > 0) {
                return $query->row()->name; // Devuelve el nombre del período académico
            }
        }
        
        // Si no se encuentra nada, devuelve una cadena vacía
        return '';
    }

   
    

    function get_active_academic_period_name() {
        $query = $this->db->get_where('academic_period', array('status_id' => 1));
        
        if ($query->num_rows() > 0) {
            return $query->row()->name; 
        }
        
        return ''; 
    }

    function get_active_academic_period_id() {
        $query = $this->db->get_where('academic_period', array('status_id' => 1));
        
        if ($query->num_rows() > 0) {
            return $query->row()->id; 
        }
        
        return ''; 
    }
    

    function get_academic_period_name_per_section_history ($section_id) {
        $section = $this->db->get_where('section_history', array('section_id' => $section_id));
        
        if ($section->num_rows() > 0) {
            $academic_period_id = $section->row()->academic_period_id;
            
            $query = $this->db->get_where('academic_period', array('id' => $academic_period_id));
            
            if ($query->num_rows() > 0) {
                return $query->row()->name; // Devuelve el nombre del período académico
            }
        }
        
        return '';
    }

    function truncate($type) {
        if ($type == 'all') {
            $this->db->truncate('student');
            $this->db->truncate('mark');
            $this->db->truncate('teacher');
            $this->db->truncate('secretary');
            $this->db->truncate('principal');
            $this->db->truncate('subject');
            $this->db->truncate('class');
            $this->db->truncate('exam');
            $this->db->truncate('grade');
        } else {
            $this->db->truncate($type);
        }
    }

    ////////IMAGE URL//////////
    // function get_image_url($type = '', $id = '') {
    //     if (file_exists('uploads/' . $type . '_image/' . $id . '.jpg'))
    //         $image_url = base_url() . 'uploads/' . $type . '_image/' . $id . '.jpg';
    //     else
    //         $image_url = base_url() . 'uploads/user.jpg';

    //     return $image_url;
    // }

    function get_image_url($type = '', $name = '', $matricula = '') {
        $image_url = base_url() . 'uploads/' . $type . '_image/';
    
        // Generar el nombre de archivo basado en el nombre y la matrícula del estudiante
        $file_name = $name . '-' . $matricula . '.jpg';
        $image_path = FCPATH . 'uploads/' . $type . '_image/' . $file_name;
    
        // Verificar si existe el archivo de imagen
        if (file_exists($image_path)) {
            $image_url .= $file_name;
        } else {
            // Si no existe, establecer una imagen predeterminada
            $image_url = base_url() . 'uploads/user.jpg';
        }
    
        return $image_url;
    }

    function get_image_url_2($type = '', $user_id = '') {
        $image_url = base_url() . 'uploads/' . $type . '_image/';
    
        // Generar el nombre de archivo basado en el nombre y la matrícula del estudiante
        $file_name = $type . ' id - ' . $user_id . '.jpg';
        $image_path = FCPATH . 'uploads/' . $type . '_image/' . $file_name;
    
        // Verificar si existe el archivo de imagen
        if (file_exists($image_path)) {
            $image_url .= $file_name;
        } else {
            // Si no existe, establecer una imagen predeterminada
            $image_url = base_url() . 'uploads/user.jpg';
        }
    
        return $image_url;
    }
    
    function get_section_subject_amount($section_id) {
        $query = $this->db->get_where('subject', array('section_id' => $section_id));
        return $query->num_rows();
    }

    function get_section_subject_amount2($section_id) {
        // Buscar en la tabla 'subject'
        $query = $this->db->get_where('subject', array('section_id' => $section_id));
        $subject_count = $query->num_rows();
    
        // Si no se encontraron registros en 'subject', buscar en 'subject_history'
        if ($subject_count == 0) {
            $query = $this->db->get_where('subject_history', array('section_id' => $section_id));
            $subject_count = $query->num_rows();
        }
    
        return $subject_count;
    }
    

    function get_section_student_amount($section_id) {
        $query = $this->db->get_where('student_details', array('section_id' => $section_id));
        return $query->num_rows();
    }

    function get_section_student_amount2($section_id) {
        // Buscar en la tabla 'student_details'
        $query = $this->db->get_where('student_details', array('section_id' => $section_id));
        $student_count = $query->num_rows();
    
        // Si no se encontraron registros en 'student_details'
        if ($student_count == 0) {
            // Buscar en la tabla 'academic_history' usando el section_id en la columna 'new_section_id'
            $this->db->select('student_id');
            $this->db->from('academic_history');
            $this->db->where('new_section_id', $section_id);
            $academic_history_query = $this->db->get();
            $student_ids = $academic_history_query->result_array();
    
            // Si se encuentran student_id en 'academic_history'
            if (!empty($student_ids)) {
                // Extraer los student_id en un array
                $student_ids_array = array_column($student_ids, 'student_id');
    
                // Buscar los datos correspondientes en 'student_details'
                $this->db->from('student_details');
                $this->db->where_in('student_id', $student_ids_array);
                $student_count = $this->db->count_all_results();
            }
        }
    
        return $student_count;
    }
    

    
    

   
    function get_stages() {
        $query = $this->db->get('stage');
        return $query->result_array();
    }

    

    function get_task_info($task_id) {
        $query = $this->db->get_where('task', array('task_id' => $task_id));
        return $query->result_array();
    }
    
   
    // function get_teacher_aide_info_per_section($section_id) {
    //     $query = $this->db->get_where('teacher_aide_details', array('guardian_id' => $guardian_id));
    //     return $query->result_array();
    // }

    function get_section_teacher($section_id) {
        $query = $this->db->get_where('section_teacher', array('section_id' => $section_id));
        return $query->result_array();
    }

    function get_file_library($library_id) {
        $query = $this->db->get_where('library', array('library_id' => $library_id));
        return $query->result_array();
    }
    

    function get_student_info_per_section($section_id) {
        // Selecciona las columnas necesarias de las tablas student, student_details y address
        $this->db->select('student.student_id, student.email, student.username, 
                           student_details.firstname, student_details.lastname, 
                           student_details.enrollment, student_details.dni, 
                           student_details.gender_id, student_details.phone_cel, 
                           student_details.phone_fij, student_details.photo, 
                           student_details.about, student_details.address_id, 
                           student_details.birthday, student_details.class_id, 
                           address.state, address.postalcode, address.locality, 
                           address.neighborhood, address.address, address.address_line, address.details');
        
        // Indica que la tabla principal es student
        $this->db->from('student');
        
        // Realiza un JOIN con student_details utilizando el campo student_id
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        
        // Realiza un JOIN con la tabla address utilizando el campo address_id de student_details
        $this->db->join('address', 'student_details.address_id = address.address_id');
        
        // Agrega la condición para el section_id en la tabla student
        $this->db->where('student_details.section_id', $section_id);
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Devuelve los resultados como un array
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array(); // Devuelve un array vacío si no hay resultados
        }
    }


    function get_student_info_per_section2($section_id) {
        // Selecciona las columnas necesarias de las tablas student, student_details y address
        $this->db->select('student.student_id, student.email, student.username, 
                           student_details.firstname, student_details.lastname, 
                           student_details.enrollment, student_details.dni, 
                           student_details.gender_id, student_details.phone_cel, 
                           student_details.phone_fij, student_details.photo, 
                           student_details.about, student_details.address_id, 
                           student_details.birthday, student_details.class_id, 
                           address.state, address.postalcode, address.locality, 
                           address.neighborhood, address.address, address.address_line, address.details');
        
        // Realizamos la consulta principal a la tabla 'student'
        $this->db->from('student');
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        $this->db->join('address', 'student_details.address_id = address.address_id');
        $this->db->where('student_details.section_id', $section_id);
        
        $query = $this->db->get();
        
        // Si la consulta no devuelve resultados, buscamos en 'academic_history'
        if ($query->num_rows() == 0) {
            // Buscamos los student_id en academic_history con el $section_id
            $this->db->select('student_id');
            $this->db->from('academic_history');
            $this->db->where('new_section_id', $section_id); // Asumo que el $section_id se busca en new_section_id
            $history_query = $this->db->get();
            
            // Si encontramos resultados en academic_history
            if ($history_query->num_rows() > 0) {
                // Obtenemos los student_id encontrados
                $student_ids = array_column($history_query->result_array(), 'student_id');
                
                // Ahora buscamos los datos de esos estudiantes
                $this->db->select('student.student_id, student.email, student.username, 
                                   student_details.firstname, student_details.lastname, 
                                   student_details.enrollment, student_details.dni, 
                                   student_details.gender_id, student_details.phone_cel, 
                                   student_details.phone_fij, student_details.photo, 
                                   student_details.about, student_details.address_id, 
                                   student_details.birthday, student_details.class_id, 
                                   address.state, address.postalcode, address.locality, 
                                   address.neighborhood, address.address, address.address_line, address.details');
                $this->db->from('student');
                $this->db->join('student_details', 'student.student_id = student_details.student_id');
                $this->db->join('address', 'student_details.address_id = address.address_id');
                $this->db->where_in('student.student_id', $student_ids); // Filtramos por los student_id encontrados en academic_history
                
                $query = $this->db->get();
            }
        }
    
        // Devuelve los resultados como un array
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array(); // Devuelve un array vacío si no hay resultados
        }
    }
    
    
    function get_section_info_per_teacher($teacher_id) {
        // Selecciona las columnas necesarias de la tabla section
        $this->db->select('section.section_id, section.name, section.letter_name, section.class_id, section.shift_id, section.teacher_aide_id');
        
        // Indica que la tabla principal es section_teacher
        $this->db->from('section_teacher');
        
        // Realiza un JOIN con la tabla section utilizando el campo section_id
        $this->db->join('section', 'section_teacher.section_id = section.section_id');
        
        // Agrega la condición para el teacher_id
        $this->db->where('section_teacher.teacher_id', $teacher_id);
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Devuelve los resultados como un array
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array(); // Devuelve un array vacío si no hay resultados
        }
    }
    


    
    
    function get_total_students_by_section_id($section_id) {
        $query = $this->db->get_where('student_details', array('section_id' => $section_id));
        return $query->num_rows();
    }

    function get_total_students_by_section_id2($section_id) {
        $query = $this->db->get_where('student_details', array('section_id' => $section_id));
    
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            $this->db->from('academic_history');
            $this->db->where('new_section_id', $section_id);
            $query_history = $this->db->get();
    
            return $query_history->num_rows();
        }
    }
    

    
    
    function get_section_info_per_subject($subject_id) {
        // Selecciona la columna section_id de la tabla subject
        $this->db->select('section_id');
        $this->db->from('subject');
        $this->db->where('subject_id', $subject_id);
        $subject_query = $this->db->get();
        
        // Verifica si se encontró el subject_id
        if ($subject_query->num_rows() > 0) {
            // Obtiene el section_id
            $section_id = $subject_query->row()->section_id;
            
            // Selecciona las columnas necesarias de la tabla section
            $this->db->select('section_id, name, letter_name, class_id, shift_id');
            $this->db->from('section');
            $this->db->where('section_id', $section_id);
            $section_query = $this->db->get();
            
            // Devuelve los resultados como un array
            if ($section_query->num_rows() > 0) {
                return $section_query->row_array(); // Devuelve una fila de resultados como un array
            } else {
                return array(); // Devuelve un array vacío si no hay resultados
            }
        } else {
            return array(); // Devuelve un array vacío si no se encontró el subject_id
        }
    }
    
    
    
    function get_admin_info($admin_id) {
        $this->db->select('admin.admin_id, 
                           admin.email, 
                           admin.username, 
                            admin.password, 
                           admin_details.firstname, 
                           admin_details.lastname, 
                           admin_details.photo');
        
        $this->db->from('admin');
        
        $this->db->join('admin_details', 'admin.admin_id = admin_details.admin_id');
        
        $this->db->where('admin.admin_id', $admin_id);
        
        $query = $this->db->get();
        
        return $query->result_array();
    }
    
    function count_unread_message_of_thread($message_thread_code) {
        $unread_message_counter = 0;
        $current_user_id = $this->session->userdata('login_user_id');
        $current_user_group = $this->session->userdata('login_type');
        $messages = $this->db->get_where('message', array('message_thread_code' => $message_thread_code))->result_array();
        foreach ($messages as $row) {
            if ($row['sender_id'] != $current_user_id && $row['sender_group'] != $current_user_group && $row['read_status_id'] == '0')
                $unread_message_counter++;
        }
        return $unread_message_counter;
    }


    function send_new_private_message() {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));

        $receiver_id   = $this->input->post('receiver_id');
        $receiver_group  = $this->input->post('receiver_group');

        $sender_id     = $this->session->userdata('login_user_id');
        $sender_group     = $this->session->userdata('login_type');

        //check if the thread between those 2 users exists, if not create new thread
        $num1 = $this->db->get_where('message_thread', array('sender_id' => $sender_id, 'sender_group' => $sender_group, 'receiver_id' => $receiver_id, 'receiver_group' => $receiver_group))->num_rows();
        $num2 = $this->db->get_where('message_thread', array('sender_id' => $receiver_id, 'sender_group' => $receiver_group, 'receiver_id' => $sender_id, 'receiver_group' => $sender_group))->num_rows();

        if ($num1 == 0 && $num2 == 0) {
            $message_thread_code                        = substr(md5(rand(100000000, 20000000000)), 0, 15);
            $data_message_thread['message_thread_code'] = $message_thread_code;
            $data_message_thread['sender_id']              = $sender_id;
            $data_message_thread['sender_group']              = $sender_group;
            $data_message_thread['receiver_id']            = $receiver_id;
            $data_message_thread['receiver_group']            = $receiver_group;
            $this->db->insert('message_thread', $data_message_thread);
        }
        if ($num1 > 0)
            $message_thread_code = $this->db->get_where('message_thread', array('sender_id' => $sender_id, 'sender_group' => $sender_group, 'receiver_id' => $receiver_id, 'receiver_group' => $receiver_group))->row()->message_thread_code;
        if ($num2 > 0)
            $message_thread_code = $this->db->get_where('message_thread', array('sender_id' => $receiver_id, 'sender_group' => $receiver_group, 'receiver_id' => $sender_id, 'sender_id' => $receiver_id ))->row()->message_thread_code;

        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender_id']                 = $sender_id;
        $data_message['sender_group']                 = $sender_group;
        $data_message['timestamp']              = $timestamp;
        $this->db->insert('message', $data_message);

        // notify email to email reciever
        //$this->email_model->notify_email('new_message_notification', $this->db->insert_id());

        return $message_thread_code;
    }

    function mark_thread_messages_read($message_thread_code) {
        // mark read only the oponnent messages of this thread, not currently logged in user's sent messages
        $current_user_id = $this->session->userdata('login_user_id');
        $current_user_group = $this->session->userdata('login_type');
        $this->db->where('sender_id !=', $current_user_id);
        $this->db->where('sender_group !=', $current_user_group);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->update('message', array('read_status_id' => 1));
    }

   
    function send_reply_message($message_thread_code) {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));
        $sender_id     = $this->session->userdata('login_user_id');
        $sender_group     = $this->session->userdata('login_type');

        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender_id']                 = $sender_id;
        $data_message['sender_group']                 = $sender_group;
        $data_message['timestamp']              = $timestamp;
        $this->db->insert('message', $data_message);

    
        // notify email to email reciever
        //$this->email_model->notify_email('new_message_notification', $this->db->insert_id());
    }

    function get_all_students_info() {
        // Selecciona todas las columnas necesarias de las tablas
        $this->db->select('student.student_id, student.email, student.username, student.password, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.medical_record, student_details.section_id, student_details.about, student_details.class_id, student_details.phone_cel, student_details.phone_fij, student_details.birthday, student_details.gender_id, student_details.enrollment, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
        
        // Indica que la tabla principal es student
        $this->db->from('student');
        
        // Realiza un JOIN con student_details utilizando el campo student_id
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        
        // Realiza un JOIN con la tabla address utilizando el campo address_id de student_details
        $this->db->join('address', 'student_details.address_id = address.address_id');
        
        // Realiza un JOIN con la tabla section para obtener el academic_period_id
        $this->db->join('section', 'student_details.section_id = section.section_id');
        
        // Realiza un JOIN con la tabla academic_period para filtrar por status_id = 1
        $this->db->join('academic_period', 'section.academic_period_id = academic_period.id');
        
        // Filtra por status_id = 1 en la tabla academic_period
        $this->db->where('academic_period.status_id', 1);
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Devuelve los resultados como un array
        return $query->result_array();
    }




    public function exportStudentTableExcelES()
    {
        // Obtiene todas las secciones activas
        $sections = $this->db->select('section.section_id, section.name')
        ->from('section')
        ->join('academic_period', 'section.academic_period_id = academic_period.id')
        ->where('academic_period.status_id', 1)
        ->get()
        ->result_array();

        $dataPorSeccion = [];

        foreach ($sections as $section) {
            // Para cada sección, obtiene los estudiantes asociados
            $estudiantes = $this->db->select('student_details.lastname AS Apellido, 
                                  student_details.firstname AS Nombre, 
                                  (CASE 
                                      WHEN student_details.gender_id = 0 THEN "Masculino" 
                                      WHEN student_details.gender_id = 1 THEN "Femenino" 
                                      ELSE "Otro" 
                                  END) AS Género, 
                                  student_details.dni AS DNI, 
                                  student.email AS Email,
                                  student.username AS Usuario, 
                                  student_details.enrollment AS Matricula,
                                  student_details.phone_cel AS Teléfono Celular, 
                                  student_details.phone_fij AS Teléfono Fijo, 
                                   DATE_FORMAT(student_details.birthday, "%d-%m-%Y") AS Fecha de Nacimiento,
                                address.state AS Provincia, 
                                  address.postalcode AS Código Postal,
                                  address.locality AS Localidad, 
                                  address.neighborhood AS Barrio, 
                                  address.address AS Calle, 
                                  address.address_line AS Altura')
                        ->from('student')
                        ->join('student_details', 'student.student_id = student_details.student_id')
                        ->join('address', 'student_details.address_id = address.address_id')
                        ->join('class', 'student_details.class_id = class.class_id')
                        ->join('section', 'student_details.section_id = section.section_id')
                        ->where('student_details.section_id', $section['section_id'])
                        ->get()
                        ->result_array();

            
            // Guarda los datos de los estudiantes bajo el nombre de la sección en el array
            $dataPorSeccion[$section['name']] = $estudiantes;
        }

    // Establecer las cabeceras adecuadas para la respuesta JSON
    header('Content-Type: application/json');
    return json_encode($dataPorSeccion, JSON_UNESCAPED_UNICODE);
    }





    public function exportStudentTableExcelEN()
    {
        // Obtiene todas las secciones activas
        $sections = $this->db->select('section.section_id, section.name')
        ->from('section')
        ->join('academic_period', 'section.academic_period_id = academic_period.id')
        ->where('academic_period.status_id', 1)
        ->get()
        ->result_array();

        $dataPorSeccion = [];

        foreach ($sections as $section) {
            // Para cada sección, obtiene los estudiantes asociados
            $estudiantes = $this->db->select('student_details.lastname AS Lastname, 
                                  student_details.firstname AS Firstname, 
                                  (CASE 
                                      WHEN student_details.gender_id = 0 THEN "Male" 
                                      WHEN student_details.gender_id = 1 THEN "Female" 
                                      ELSE "Other" 
                                  END) AS Gender, 
                                  student_details.dni AS DNI, 
                                  student.email AS Email,
                                  student.username AS Username, 
                                  student_details.enrollment AS Enrollment,
                                  student_details.phone_cel AS Cell Phone, 
                                  student_details.phone_fij AS Landline, 
                                   DATE_FORMAT(student_details.birthday, "%d-%m-%Y") AS Birthday,
                                address.state AS State, 
                                  address.postalcode AS Postal Code,
                                  address.locality AS Locality, 
                                  address.neighborhood AS Neighborhood, 
                                  address.address AS Address, 
                                  address.address_line AS Address line')
                        ->from('student')
                        ->join('student_details', 'student.student_id = student_details.student_id')
                        ->join('address', 'student_details.address_id = address.address_id')
                        ->join('class', 'student_details.class_id = class.class_id')
                        ->join('section', 'student_details.section_id = section.section_id')
                        ->where('student_details.section_id', $section['section_id'])
                        ->get()
                        ->result_array();

            
            // Guarda los datos de los estudiantes bajo el nombre de la sección en el array
            $dataPorSeccion[$section['name']] = $estudiantes;
        }

    // Establecer las cabeceras adecuadas para la respuesta JSON
    header('Content-Type: application/json');
    return json_encode($dataPorSeccion, JSON_UNESCAPED_UNICODE);
    }

    



    function get_all_sections() {
        // Selecciona todas las columnas necesarias de las tablas
        $this->db->select('section.section_id, section.name, section.letter_name, section.class_id, section.shift_id, section.academic_period_id, section.status_id');
        
        // Indica que la tabla principal es student
        $this->db->from('section');
        
        // Realiza un JOIN con la tabla academic_period para filtrar por status_id = 1
        $this->db->join('academic_period', 'section.academic_period_id = academic_period.id');
        
        // Filtra por status_id = 1 en la tabla academic_period
        $this->db->where('academic_period.status_id', 1);
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Devuelve los resultados como un array
        return $query->result_array();
    }

    function get_all_sections_per_class($class_id = '') {
        // Selecciona todas las columnas necesarias de las tablas
        $this->db->select('section.section_id, section.name, section.letter_name, section.class_id, section.shift_id, section.academic_period_id, section.status_id');
        
        // Indica que la tabla principal es student
        $this->db->from('section');
        $this->db->where('class_id', $class_id);

        // Realiza un JOIN con la tabla academic_period para filtrar por status_id = 1
        $this->db->join('academic_period', 'section.academic_period_id = academic_period.id');
        
        // Filtra por status_id = 1 en la tabla academic_period
        $this->db->where('academic_period.status_id', 1);
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Devuelve los resultados como un array
        return $query->result_array();
    }




    public function exportClassStudentTableExcelES($class_id = '')
    {
        
        $sections = $this->db->select('section.section_id, section.name')
        ->from('section')
        ->where('section.class_id', $class_id)
        ->join('academic_period', 'section.academic_period_id = academic_period.id')
        ->where('academic_period.status_id', 1)
        ->get()
        ->result_array();

        $dataPorSeccion = [];

        foreach ($sections as $section) {
            // Para cada sección, obtiene los estudiantes asociados
            $estudiantes = $this->db->select('student_details.lastname AS Apellido, 
                                student_details.firstname AS Nombre, 
                                (CASE 
                                    WHEN student_details.gender_id = 0 THEN "Masculino" 
                                    WHEN student_details.gender_id = 1 THEN "Femenino" 
                                    ELSE "Otro" 
                                END) AS Género, 
                                student_details.dni AS DNI, 
                                student.email AS Email,
                                student.username AS Usuario, 
                                student_details.enrollment AS Matricula,
                                student_details.phone_cel AS Teléfono_Celular, 
                                student_details.phone_fij AS Teléfono_Fijo, 
                                DATE_FORMAT(student_details.birthday, "%d-%m-%Y") AS Fecha_de_Nacimiento,
                                address.state AS Provincia, 
                                address.postalcode AS Código_Postal,
                                address.locality AS Localidad, 
                                address.neighborhood AS Barrio, 
                                address.address AS Calle, 
                                address.address_line AS Altura')
                        ->from('student')
                        ->join('student_details', 'student.student_id = student_details.student_id')
                        ->join('address', 'student_details.address_id = address.address_id')
                        ->join('class', 'student_details.class_id = class.class_id')
                        ->join('section', 'student_details.section_id = section.section_id')
                        ->where('student_details.section_id', $section['section_id'])
                        ->get()
                        ->result_array();

            // Guarda los datos de los estudiantes bajo el nombre de la sección en el array
            $dataPorSeccion[$section['name']] = $estudiantes;
        }

        header('Content-Type: application/json');
        return json_encode($dataPorSeccion, JSON_UNESCAPED_UNICODE);
    }
    




}
