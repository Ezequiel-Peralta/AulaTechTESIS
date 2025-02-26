<?php
class CrudTeacher extends CI_Model{
    function __constructor() {
        parent::__construct();
    }
    function get_teachers() {
        $query = $this->db->get('teacher');
        return $query->result_array();
    }

    function get_teacher_name($teacher_id) {
        $query = $this->db->get_where('teacher', array('teacher_id' => $teacher_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name'];
    }
    function get_teachers_info($teacher_id) {
        $query = $this->db->get_where('teacher_details', array('teacher_id' => $teacher_id));
        return $query->row_array(); // Retorna una sola fila como array
    }

    function get_teacher_info($teacher_id) {
        $this->db->select('teacher.teacher_id, teacher.email, teacher.username, teacher.password, teacher_details.firstname, teacher_details.lastname, teacher_details.dni, teacher_details.photo,  teacher_details.phone_cel, teacher_details.phone_fij, teacher_details.birthday, teacher_details.gender_id, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
        
        $this->db->from('teacher');
        
        $this->db->join('teacher_details', 'teacher.teacher_id = teacher_details.teacher_id');
        
        $this->db->join('address', 'teacher_details.address_id = address.address_id');
        
        $this->db->where('teacher.teacher_id', $teacher_id);
        
        $query = $this->db->get();
        
        return $query->result_array();
    }

     function get_teachers_aide() {
        $query = $this->db->get('teacher_aide');
        return $query->result_array();
    }

    function get_teacher_aide_name($teacher_aide_id) {
        $query = $this->db->get_where('teacher_aide', array('teacher_aide_id' => $teacher_aide_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name'];
    }

    function get_teacher_aide_info($teacher_aide_id) {
        // Selecciona todas las columnas necesarias de ambas tablas
        $this->db->select('teacher_aide.teacher_aide_id, 
                           teacher_aide.email, 
                           teacher_aide.username, 
                            teacher_aide.password,
                           teacher_aide_details.firstname, 
                           teacher_aide_details.lastname, 
                           teacher_aide_details.dni, 
                           teacher_aide_details.photo, 
                           teacher_aide_details.phone_cel, 
                           teacher_aide_details.phone_fij, 
                           teacher_aide_details.birthday, 
                           teacher_aide_details.gender_id, 
                           address.locality, 
                            address.postalcode, 
                           address.neighborhood, 
                            address.address,
                           address.address_line');
        
        // Indica que la tabla principal es teacher_aide
        $this->db->from('teacher_aide');
        
        // Realiza un JOIN con teacher_aide_details utilizando el campo teacher_aide_id
        $this->db->join('teacher_aide_details', 'teacher_aide.teacher_aide_id = teacher_aide_details.teacher_aide_id');
        
        // Realiza un JOIN con la tabla address utilizando el campo address_id de teacher_aide_details
        $this->db->join('address', 'teacher_aide_details.address_id = address.address_id');
        
        // Agrega la condición para el teacher_aide_id
        $this->db->where('teacher_aide.teacher_aide_id', $teacher_aide_id);
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Devuelve los resultados como un array
        return $query->result_array();
    }
    

    function get_teacher_aide_info_per_section($section_id) {
        $this->db->select('teacher_aide_id');
        $this->db->from('section');
        $this->db->where('section_id', $section_id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $section = $query->row_array();
            $teacher_aide_id = $section['teacher_aide_id'];
            
            $this->db->select('*');
            $this->db->from('teacher_aide_details');
            $this->db->where('teacher_aide_id', $teacher_aide_id);
            $teacher_aide_query = $this->db->get();
            
            if ($teacher_aide_query->num_rows() > 0) {
                return $teacher_aide_query->result_array();
            } else {
                return array(); 
            }
        } else {
            return array(); 
        }
    }

    function get_teacher_aide_info_per_section2($section_id) {
        // Intentamos obtener los registros de la tabla 'section'
        $this->db->select('teacher_aide_id');
        $this->db->from('section');
        $this->db->where('section_id', $section_id);
        $query = $this->db->get();
    
        // Si no se encuentran registros en 'section', buscamos en 'section_history'
        if ($query->num_rows() == 0) {
            $this->db->from('section_history');
            $this->db->where('section_id', $section_id);
            $query = $this->db->get();
        }
    
        // Si se encuentran resultados, obtenemos el teacher_aide_id
        if ($query->num_rows() > 0) {
            $section = $query->row_array();
            $teacher_aide_id = $section['teacher_aide_id'];
            
            // Buscamos la información del teacher aide
            $this->db->select('*');
            $this->db->from('teacher_aide_details');
            $this->db->where('teacher_aide_id', $teacher_aide_id);
            $teacher_aide_query = $this->db->get();
            
            // Si se encuentra el teacher aide, retornamos los resultados
            if ($teacher_aide_query->num_rows() > 0) {
                return $teacher_aide_query->result_array();
            } else {
                return array(); // Si no se encuentra información del teacher aide
            }
        } else {
            return array(); // Si no se encuentran registros ni en 'section' ni en 'section_history'
        }
    }

    function get_teacher_aide_info_per_id($teacher_aide_id) {
        $this->db->select('firstname, lastname, photo, teacher_aide_id');
        $this->db->from('teacher_aide_details');
        $this->db->where('teacher_aide_id', $teacher_aide_id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array(); 
        }
    }
    function get_teacher_info_per_section($section_id) {
        // Obtener los teacher_id de la tabla section_teacher para el section_id dado
        $this->db->select('teacher_id');
        $this->db->from('section_teacher');
        $this->db->where('section_id', $section_id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $teacher_ids = array_column($query->result_array(), 'teacher_id');
            
            // Obtener los detalles de los profesores de la tabla teacher_details
            $this->db->select('*');
            $this->db->from('teacher_details');
            $this->db->where_in('teacher_id', $teacher_ids);
            $teacher_query = $this->db->get();
            
            if ($teacher_query->num_rows() > 0) {
                $teacher_details = $teacher_query->result_array();
                
                // Añadir el nombre de la materia (subject_name) y el subject_id para cada profesor
                foreach ($teacher_details as &$teacher) {
                    $this->db->select('subject_id, name');
                    $this->db->from('subject');
                    $this->db->where('teacher_id', $teacher['teacher_id']);
                    $subject_query = $this->db->get();
                    
                    if ($subject_query->num_rows() > 0) {
                        $subject = $subject_query->row_array();
                        $teacher['subject_id'] = $subject['subject_id'];
                        $teacher['subject_name'] = $subject['name'];
                    } else {
                        $teacher['subject_id'] = null;
                        $teacher['subject_name'] = null;
                    }
                }
                
                return $teacher_details;
            } else {
                return array(); 
            }
        } else {
            return array();
        }
    }

    function get_teacher_info_per_subject($subject_id) {
        // Obtener el teacher_id de la tabla subject para el subject_id dado
        $this->db->select('teacher_id, name');
        $this->db->from('subject');
        $this->db->where('subject_id', $subject_id);
        $subject_query = $this->db->get();
        
        if ($subject_query->num_rows() > 0) {
            $subject_data = $subject_query->row_array();
            $teacher_id = $subject_data['teacher_id'];
            
            // Obtener los detalles del profesor de la tabla teacher
            $this->db->select('*');
            $this->db->from('teacher');
            $this->db->where('teacher_id', $teacher_id);
            $teacher_query = $this->db->get();
            
            if ($teacher_query->num_rows() > 0) {
                $teacher_details = $teacher_query->row_array();
                
                // Obtener los detalles adicionales del profesor de la tabla teacher_details
                $this->db->select('*');
                $this->db->from('teacher_details');
                $this->db->where('teacher_id', $teacher_id);
                $this->db->limit(1); // Limitar la consulta a un solo resultado
                $teacher_details_query = $this->db->get();
                
                if ($teacher_details_query->num_rows() > 0) {
                    $teacher_additional_details = $teacher_details_query->row(); // Utilizar row() en lugar de row_array()
                    
                    // Combinar los detalles del profesor y los detalles adicionales
                    $teacher_info = array_merge($teacher_details, (array) $teacher_additional_details); // Convertir el objeto en array
                    
                    // Añadir el nombre de la materia (subject_name) y el subject_id al array de detalles del profesor
                    $teacher_info['subject_id'] = $subject_id;
                    $teacher_info['subject_name'] = $subject_data['name'];
                    
                    return $teacher_info;
                } else {
                    // No se encontraron detalles adicionales para el profesor
                    return array('error' => 'No additional details found for the teacher.');
                }
            } else {
                // No se encontró el profesor
                return array('error' => 'Teacher not found.');
            }
        } else {
            // No se encontró la materia con el subject_id dado
            return array('error' => 'Subject not found.');
        }
    }

    function get_teacher_info_per_subject2($subject_id) {
        // Intentamos obtener el teacher_id y name de la tabla 'subject' para el subject_id dado
        $this->db->select('teacher_id, name');
        $this->db->from('subject');
        $this->db->where('subject_id', $subject_id);
        $subject_query = $this->db->get();
    
        // Si no se encuentran registros en 'subject', buscamos en 'subject_history'
        if ($subject_query->num_rows() == 0) {
            $this->db->from('subject_history');
            $this->db->where('subject_id', $subject_id);
            $subject_query = $this->db->get();
        }
    
        // Verificamos si encontramos la materia en 'subject' o 'subject_history'
        if ($subject_query->num_rows() > 0) {
            $subject_data = $subject_query->row_array();
            $teacher_id = $subject_data['teacher_id'];
    
            // Obtener los detalles del profesor de la tabla teacher
            $this->db->select('*');
            $this->db->from('teacher');
            $this->db->where('teacher_id', $teacher_id);
            $teacher_query = $this->db->get();
    
            if ($teacher_query->num_rows() > 0) {
                $teacher_details = $teacher_query->row_array();
    
                // Obtener los detalles adicionales del profesor de la tabla teacher_details
                $this->db->select('*');
                $this->db->from('teacher_details');
                $this->db->where('teacher_id', $teacher_id);
                $this->db->limit(1); // Limitar la consulta a un solo resultado
                $teacher_details_query = $this->db->get();
    
                if ($teacher_details_query->num_rows() > 0) {
                    $teacher_additional_details = $teacher_details_query->row(); // Utilizar row() en lugar de row_array()
    
                    // Combinar los detalles del profesor y los detalles adicionales
                    $teacher_info = array_merge($teacher_details, (array) $teacher_additional_details); // Convertir el objeto en array
    
                    // Añadir el nombre de la materia (subject_name) y el subject_id al array de detalles del profesor
                    $teacher_info['subject_id'] = $subject_id;
                    $teacher_info['subject_name'] = $subject_data['name'];
    
                    return $teacher_info;
                } else {
                    // No se encontraron detalles adicionales para el profesor
                    return array('error' => 'No additional details found for the teacher.');
                }
            } else {
                // No se encontró el profesor
                return array('error' => 'Teacher not found.');
            }
        } else {
            // No se encontró la materia ni en 'subject' ni en 'subject_history'
            return array('error' => 'Subject not found.');
        }
    }

}