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

    function get_students($class_id) {
        $query = $this->db->get_where('student', array('class_id' => $class_id));
        return $query->result_array();
    }

    function get_behavior_student($behavior_id) {
        $query = $this->db->get_where('behavior', array('behavior_id' => $behavior_id));
        return $query->result_array();
    }


    function get_students_per_section($section_id) {
        $this->db->select('student_details.firstname, student_details.lastname, student_details. enrollment, student_details.dni, student_details.gender_id, student_details.phone_cel, student_details.phone_fij, student_details.class_id, student_details.section_id, student_details.birthday, student.email, student.username, address.state, address.postalcode, address.locality, address.neighborhood, address.address, address.address_line');
        $this->db->from('student_details');
        $this->db->join('student', 'student.student_id = student_details.student_id');
        $this->db->join('address', 'address.address_id = student_details.address_id'); // Unión con la tabla address
        $this->db->where('student_details.section_id', $section_id);
        $query = $this->db->get();
        
        return $query->result_array();
    }
    

    function get_students_admissions() {
        $this->db->select('student_details.firstname, student_details.lastname, student_details. enrollment, student_details.dni, student_details.gender_id, student_details.phone_cel, student_details.phone_fij, student_details.class_id, student_details.section_id, student_details.birthday, student_details.status_reason, student.email, student.username, address.state, address.postalcode, address.locality, address.neighborhood, address.address, address.address_line');
        $this->db->from('student_details');
        $this->db->join('student', 'student.student_id = student_details.student_id');
        $this->db->join('address', 'address.address_id = student_details.address_id'); 
        $this->db->where('student_details.user_status_id', 0);
        $this->db->where('student_details.class_id', NULL);
        $this->db->where('student_details.section_id', NULL);
        $query = $this->db->get();
        
        return $query->result_array();
    }

    function get_students_pre_enrollments() {
        $this->db->select('student_details.firstname, student_details.lastname, student_details. enrollment, student_details.dni, student_details.gender_id, student_details.phone_cel, student_details.phone_fij, student_details.class_id, student_details.section_id, student_details.birthday, student.email, student.username, address.state, address.postalcode, address.locality, address.neighborhood, address.address, address.address_line');
        $this->db->from('student_details');
        $this->db->join('student', 'student.student_id = student_details.student_id');
        $this->db->join('address', 'address.address_id = student_details.address_id'); 
        $this->db->where('student_details.user_status_id', 1);
        $this->db->where('student_details.class_id', NULL);
        $this->db->where('student_details.section_id', NULL);
        $query = $this->db->get();
        
        return $query->result_array();
    }
    


    function get_student_info($student_id) {
        // Selecciona todas las columnas necesarias de ambas tablas
        $this->db->select('student.student_id, student.email, student.username, student.password, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.medical_record, student_details.section_id, student_details.about, student_details.class_id, student_details.phone_cel, student_details.phone_fij, student_details.birthday, student_details.gender_id, student_details.enrollment, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
        
        // Indica que la tabla principal es student
        $this->db->from('student');
        
        // Realiza un JOIN con student_details utilizando el campo student_id
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        
        // Realiza un JOIN con la tabla address utilizando el campo address_id de student_details
        $this->db->join('address', 'student_details.address_id = address.address_id');
        
        // Agrega la condición para el student_id
        $this->db->where('student.student_id', $student_id);
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Devuelve los resultados como un array
        return $query->result_array();
    }
    
    function get_student_info2($student_id) {
        // Selecciona todas las columnas necesarias de ambas tablas
        $this->db->select('student.student_id, student.email, student.username, student.password, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.medical_record, student_details.section_id, student_details.about, student_details.class_id, student_details.phone_cel, student_details.phone_fij, student_details.birthday, student_details.gender_id, student_details.enrollment, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
        
        // Indica que la tabla principal es student
        $this->db->from('student');
        
        // Realiza un JOIN con student_details utilizando el campo student_id
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        
        // Realiza un JOIN con la tabla address utilizando el campo address_id de student_details
        $this->db->join('address', 'student_details.address_id = address.address_id');
        
        // Agrega la condición para el student_id
        $this->db->where('student.student_id', $student_id);
        
        // Ejecuta la consulta y almacena los resultados en una variable
        $query = $this->db->get();
        
        // Devuelve los resultados como un array
        return $query->row_array();
    }

    function get_parent_info($parent_id) {
        $query = $this->db->get_where('parent', array('parent_id' => $parent_id));
        return $query->result_array();
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
    
    

    function get_guardian_info_per_student($student_id) {
        // Obtener guardian_id y guardian_type_id de la tabla student_guardian
        $this->db->select('guardian_id, guardian_type_id');
        $this->db->from('student_guardian');
        $this->db->where('student_id', $student_id);
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            $guardians = $query->result_array();
    
            // Extraer todos los guardian_id en un array
            $guardian_ids = array_column($guardians, 'guardian_id');
    
            // Obtener detalles de los guardianes de las tablas guardian y guardian_details
            $this->db->select('guardian.guardian_id, guardian.email, guardian.username, guardian_details.firstname, guardian_details.lastname, guardian_details.dni, guardian_details.photo, guardian_details.user_status_id, guardian_details.phone_cel, guardian_details.phone_fij, guardian_details.birthday, guardian_details.gender_id');
            $this->db->from('guardian');
            $this->db->join('guardian_details', 'guardian.guardian_id = guardian_details.guardian_id');
            $this->db->where_in('guardian.guardian_id', $guardian_ids);
            $guardian_query = $this->db->get();
    
            if ($guardian_query->num_rows() > 0) {
                $guardian_details = $guardian_query->result_array();
    
                // Asociar guardian_type_id con cada tutor
                foreach ($guardian_details as &$detail) {
                    foreach ($guardians as $guardian) {
                        if ($detail['guardian_id'] == $guardian['guardian_id']) {
                            $detail['guardian_type_id'] = $guardian['guardian_type_id'];
                            break;
                        }
                    }
                }
    
                return $guardian_details;
            } else {
                echo ucfirst(get_phrase('no_guardian_data_found'));
                return array();
            }
        } else {
            // Retornar un array vacío si no hay guardianes asociados
            return array();
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

    function get_subjects() {
        $query = $this->db->get('subject');
        return $query->result_array();
    }

    function get_subject_info($subject_id) {
        $this->db->select('subject.*, teacher_details.firstname, teacher_details.lastname, teacher_details.teacher_id');
        $this->db->from('subject');
        $this->db->join('teacher_details', 'teacher_details.teacher_id = subject.teacher_id', 'left');
        $this->db->where('subject.subject_id', $subject_id);
    
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_subject_info2($subject_id) {
        $this->db->select('subject.*, teacher_details.firstname, teacher_details.lastname, teacher_details.teacher_id');
        $this->db->from('subject');
        $this->db->join('teacher_details', 'teacher_details.teacher_id = subject.teacher_id', 'left');
        $this->db->where('subject.subject_id', $subject_id);
        
        $query = $this->db->get();
        
        if ($query->num_rows() == 0) {
            $this->db->select('subject_history.*, teacher_details.firstname, teacher_details.lastname, teacher_details.teacher_id');
            $this->db->from('subject_history');
            $this->db->join('teacher_details', 'teacher_details.teacher_id = subject_history.teacher_id', 'left');
            $this->db->where('subject_history.subject_id', $subject_id);
            
            $query = $this->db->get();
        }
    
        return $query->result_array();
    }

    


    function get_subjects_by_class($class_id) {
        $query = $this->db->get_where('subject', array('class_id' => $class_id));
        return $query->result_array();
    }

    function get_subjects_by_section($section_id) {
        $query = $this->db->get_where('subject', array('section_id' => $section_id));
        return $query->result_array();
    }

    function get_subjects_by_section2($section_id) {
        $query = $this->db->get_where('subject', array('section_id' => $section_id));
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        
        $query_history = $this->db->get_where('subject_history', array('section_id' => $section_id));
        
        return $query_history->result_array();
    }

    function get_academic_history_by_student($student_id) {
        $this->db->where('student_id', $student_id);
        $this->db->order_by('date_change', 'ASC'); 
        $query = $this->db->get('academic_history');
        return $query->result_array();
    }

    function get_academic_by_student($student_id) {
        // Buscar los detalles del estudiante
        $this->db->where('student_id', $student_id);
        $student_query = $this->db->get('student_details');
    
        // Validar si se encontró información del estudiante
        if ($student_query->num_rows() > 0) {
            $student_data = $student_query->row_array(); // Obtener datos del estudiante como array
    
            // Buscar información académica relacionada con la sección
            $this->db->select('section_id AS new_section_id, class_id AS new_class_id, academic_period_id AS new_academic_period_id');
            $this->db->where('section_id', $student_data['section_id']);
            $query = $this->db->get('section');
    
            // Obtener los resultados como array
            $result = $query->result_array();
    
            // Agregar el student_id manualmente a cada registro
            foreach ($result as &$row) {
                $row['student_id'] = $student_id; // Agregar el student_id
            }
    
            return $result;
        }
    
        // Retornar array vacío si no hay información
        return [];
    }
    
    

    function get_subjects_and_library_by_section($section_id) {
        $this->db->select('
            subject.subject_id AS subject_id,
            subject.name AS subject_name,
            subject.section_id AS section_id,
            subject.class_id AS class_id,
            library.library_id AS library_id,
            library.url_file AS url_file,
            library.file_name AS file_name,
            library.date AS file_date,
            library.description AS file_description,
            library.status_id AS file_status_id
        ');
        $this->db->from('subject');
        $this->db->join('library', 'library.subject_id = subject.subject_id', 'left');
        $this->db->where('subject.section_id', $section_id);
        $query = $this->db->get();
        
        $results = $query->result_array();
        
        $grouped_subjects = [];
        foreach ($results as $row) {
            $subject_id = $row['subject_id'];
            
            if (!isset($grouped_subjects[$subject_id])) {
                $grouped_subjects[$subject_id] = [
                    'subject_id' => $row['subject_id'],
                    'subject_name' => $row['subject_name'],
                    'files' => []
                ];
            }
            
            if (!empty($row['library_id'])) {
                $grouped_subjects[$subject_id]['files'][] = [
                    'file_name' => $row['file_name'],
                    'url_file' => $row['url_file'],
                    'file_date' => $row['file_date'],
                    'file_description' => $row['file_description'],
                    'file_status_id' => $row['file_status_id'],
                    'library_id' => $row['library_id']
                ];
            }
        }
        return $grouped_subjects;
    }
    
    
    function get_marks_by_student_subject($student_id, $subject_id) {
        $query = $this->db->get_where('mark', array('student_id' => $student_id, 'subject_id' => $subject_id));
        return $query->result_array();
    }

    function get_marks_by_student_subject2($student_id, $subject_id, $academic_period_id) {
        // Primero buscar en la tabla 'mark_history' por student_id, subject_id y academic_period_id
        $query_history = $this->db->get_where('mark_history', array(
            'student_id' => $student_id,
            'subject_id' => $subject_id,
            'academic_period_id' => $academic_period_id
        ));
        
        if ($query_history->num_rows() > 0) {
            // Si encuentra registros en 'mark_history', devolver los resultados
            return $query_history->result_array();
        } else {
            // Si no encuentra registros en 'mark_history', buscar en 'mark' con los mismos parámetros
            $query = $this->db->get_where('mark', array(
                'student_id' => $student_id,
                'subject_id' => $subject_id
            ));
            return $query->result_array();
        }
    }

    function get_marks_by_student_subject3($student_id, $subject_id, $academic_period_id) {
        // Buscar en la tabla 'mark_history' por student_id, subject_id y un academic_period_id distinto al proporcionado
        $this->db->where('student_id', $student_id);
        $this->db->where('subject_id', $subject_id);
        $this->db->where('academic_period_id !=', $academic_period_id); // Condición de desigualdad para academic_period_id
        
        // Ordenar por 'date' de manera descendente (más reciente primero)
        $this->db->order_by('date', 'DESC');
        
        // Limitar la cantidad de resultados a 3
        $this->db->limit(3);
        
        // Ejecutar la consulta
        $query_history = $this->db->get('mark_history');
        
        if ($query_history->num_rows() > 0) {
            // Si encuentra registros, devolver los resultados
            return $query_history->result_array();
        }
        return []; // Retornar un array vacío si no se encuentran registros
    }

    function get_subject_name_by_id($subject_id) {
        $query = $this->db->get_where('subject', array('subject_id' => $subject_id))->row();
        return $query->name;
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
    

    function get_attendance_student_section_amount($section_id = '', $attendance_type = '', $filter_type = '', $date = '', $start_date = '', $end_date = '', $dateMoth = '', $start_date_yearly = '', $end_date_yearly = '') {
        $this->db->where('section_id', $section_id);
        $this->db->where('status', $attendance_type);
    
        if ($filter_type === 'daily' && !empty($date)) {
            $this->db->where('date', $date);
        } elseif ($filter_type === 'weekly' && !empty($start_date) && !empty($end_date)) {
            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $end_date);
        } elseif ($filter_type === 'monthly' && !empty($dateMoth)) {
            $months = [
                'january' => '01',
                'february' => '02',
                'march' => '03',
                'april' => '04',
                'may' => '05',
                'june' => '06',
                'july' => '07',
                'august' => '08',
                'september' => '09',
                'october' => '10',
                'november' => '11',
                'december' => '12'
            ];

            $month_number = isset($months[strtolower($dateMoth)]) ? $months[strtolower($dateMoth)] : null;

            if ($month_number) {
                $this->db->where('MONTH(date)', $month_number);
            }
        } elseif ($filter_type === 'yearly' && !empty($start_date_yearly) && !empty($end_date_yearly)) {
            $months = [
                'january' => '01',
                'february' => '02',
                'march' => '03',
                'april' => '04',
                'may' => '05',
                'june' => '06',
                'july' => '07',
                'august' => '08',
                'september' => '09',
                'october' => '10',
                'november' => '11',
                'december' => '12'
            ];
    
            $start_month_number = isset($months[strtolower($start_date_yearly)]) ? $months[strtolower($start_date_yearly)] : null;
            $end_month_number = isset($months[strtolower($end_date_yearly)]) ? $months[strtolower($end_date_yearly)] : null;
    
            if ($start_month_number && $end_month_number) {
                $current_year = date('Y');
                $start_date = $current_year . '-' . $start_month_number . '-01';
                $end_date = $current_year . '-' . $end_month_number . '-31';
    
                $this->db->where('date >=', $start_date);
                $this->db->where('date <=', $end_date);
            }
        }
    
        $amount = $this->db->from('attendance_student')->count_all_results();
    
        return $amount; 
    }


    function get_attendance_student_section_amount2($section_id = '', $attendance_type = '', $filter_type = '', $date = '', $start_date = '', $end_date = '', $dateMoth = '', $start_date_yearly = '', $end_date_yearly = '') {
        $this->db->where('section_id', $section_id);
        $this->db->where('status', $attendance_type);
    
        if ($filter_type === 'daily' && !empty($date)) {
            $this->db->where('date', $date);
        } elseif ($filter_type === 'weekly' && !empty($start_date) && !empty($end_date)) {
            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $end_date);
        } elseif ($filter_type === 'monthly' && !empty($dateMoth)) {
            $months = [
                'january' => '01',
                'february' => '02',
                'march' => '03',
                'april' => '04',
                'may' => '05',
                'june' => '06',
                'july' => '07',
                'august' => '08',
                'september' => '09',
                'october' => '10',
                'november' => '11',
                'december' => '12'
            ];

            $month_number = isset($months[strtolower($dateMoth)]) ? $months[strtolower($dateMoth)] : null;

            if ($month_number) {
                $this->db->where('MONTH(date)', $month_number);
            }
        } elseif ($filter_type === 'yearly' && !empty($start_date_yearly) && !empty($end_date_yearly)) {
            $months = [
                'january' => '01',
                'february' => '02',
                'march' => '03',
                'april' => '04',
                'may' => '05',
                'june' => '06',
                'july' => '07',
                'august' => '08',
                'september' => '09',
                'october' => '10',
                'november' => '11',
                'december' => '12'
            ];
    
            $start_month_number = isset($months[strtolower($start_date_yearly)]) ? $months[strtolower($start_date_yearly)] : null;
            $end_month_number = isset($months[strtolower($end_date_yearly)]) ? $months[strtolower($end_date_yearly)] : null;
    
            if ($start_month_number && $end_month_number) {
                $current_year = date('Y');
                $start_date = $current_year . '-' . $start_month_number . '-01';
                $end_date = $current_year . '-' . $end_month_number . '-31';
    
                $this->db->where('date >=', $start_date);
                $this->db->where('date <=', $end_date);
            }
        }
    
        $amount = $this->db->from('attendance_student')->count_all_results();

        if ($amount === 0) {
            $this->db->where('section_id', $section_id);
            $this->db->where('status', $attendance_type);
        
            if ($filter_type === 'daily' && !empty($date)) {
                $this->db->where('date', $date);
            } elseif ($filter_type === 'weekly' && !empty($start_date) && !empty($end_date)) {
                $this->db->where('date >=', $start_date);
                $this->db->where('date <=', $end_date);
            } elseif ($filter_type === 'monthly' && !empty($dateMoth)) {
                $months = [
                    'january' => '01',
                    'february' => '02',
                    'march' => '03',
                    'april' => '04',
                    'may' => '05',
                    'june' => '06',
                    'july' => '07',
                    'august' => '08',
                    'september' => '09',
                    'october' => '10',
                    'november' => '11',
                    'december' => '12'
                ];
    
                $month_number = isset($months[strtolower($dateMoth)]) ? $months[strtolower($dateMoth)] : null;
    
                if ($month_number) {
                    $this->db->where('MONTH(date)', $month_number);
                }
            } elseif ($filter_type === 'yearly' && !empty($start_date_yearly) && !empty($end_date_yearly)) {
                $months = [
                    'january' => '01',
                    'february' => '02',
                    'march' => '03',
                    'april' => '04',
                    'may' => '05',
                    'june' => '06',
                    'july' => '07',
                    'august' => '08',
                    'september' => '09',
                    'october' => '10',
                    'november' => '11',
                    'december' => '12'
                ];
        
                $start_month_number = isset($months[strtolower($start_date_yearly)]) ? $months[strtolower($start_date_yearly)] : null;
                $end_month_number = isset($months[strtolower($end_date_yearly)]) ? $months[strtolower($end_date_yearly)] : null;
        
                if ($start_month_number && $end_month_number) {
                    $current_year = date('Y');
                    $start_date = $current_year . '-' . $start_month_number . '-01';
                    $end_date = $current_year . '-' . $end_month_number . '-31';
        
                    $this->db->where('date >=', $start_date);
                    $this->db->where('date <=', $end_date);
                }
            }
        
            $amount = $this->db->from('attendance_student_history')->count_all_results();

        }

        return $amount; 
    }


    function get_attendance_student_amount($student_id = '', $attendance_type = '', $filter_type = '', $date = '', $start_date = '', $end_date = '', $dateMoth = '', $start_date_yearly = '', $end_date_yearly = '') {
        $this->db->where('student_id', $student_id);
        $this->db->where('status', $attendance_type);
    
        if ($filter_type === 'daily' && !empty($date)) {
            $this->db->where('date', $date);
        } elseif ($filter_type === 'weekly' && !empty($start_date) && !empty($end_date)) {
            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $end_date);
        } elseif ($filter_type === 'monthly' && !empty($dateMoth)) {
            $months = [
                'january' => '01',
                'february' => '02',
                'march' => '03',
                'april' => '04',
                'may' => '05',
                'june' => '06',
                'july' => '07',
                'august' => '08',
                'september' => '09',
                'october' => '10',
                'november' => '11',
                'december' => '12'
            ];

            $month_number = isset($months[strtolower($dateMoth)]) ? $months[strtolower($dateMoth)] : null;

            if ($month_number) {
                $this->db->where('MONTH(date)', $month_number);
            }
        } elseif ($filter_type === 'yearly' && !empty($start_date_yearly) && !empty($end_date_yearly)) {
            $months = [
                'january' => '01',
                'february' => '02',
                'march' => '03',
                'april' => '04',
                'may' => '05',
                'june' => '06',
                'july' => '07',
                'august' => '08',
                'september' => '09',
                'october' => '10',
                'november' => '11',
                'december' => '12'
            ];
    
            $start_month_number = isset($months[strtolower($start_date_yearly)]) ? $months[strtolower($start_date_yearly)] : null;
            $end_month_number = isset($months[strtolower($end_date_yearly)]) ? $months[strtolower($end_date_yearly)] : null;
    
            if ($start_month_number && $end_month_number) {
                $current_year = date('Y');
                $start_date = $current_year . '-' . $start_month_number . '-01';
                $end_date = $current_year . '-' . $end_month_number . '-31';
    
                $this->db->where('date >=', $start_date);
                $this->db->where('date <=', $end_date);
            }
        }
    
        $amount = $this->db->from('attendance_student')->count_all_results();
    
        return $amount; 
    }


    public function get_attendance_data_for_chart($section_id) {
        // Obtener todos los student_id que pertenecen a la sección dada
        $student_ids = $this->db->select('student_id')->get_where('student_details', array('section_id' => $section_id))->result_array();
        
        // Verificar si hay student_ids
        if (empty($student_ids)) {
            return []; // Si no hay estudiantes, retornar un array vacío
        }
    
        // Inicializar la fecha final con la fecha más grande posible
        $max_date = '9999-12-31';
        
        // Iterar sobre los student_id obtenidos
        foreach ($student_ids as $student) {
            if (!isset($student['student_id'])) {
                continue; // Si el student_id no está definido, omitir
            }
    
            // Obtener la fecha máxima de los registros de asistencia para el estudiante actual
            $max_date_query = $this->db
                ->select_max('date')
                ->where('student_id', $student['student_id'])
                ->get('attendance_student')
                ->row_array();
        
            // Actualizar la fecha final si es menor que la fecha máxima encontrada
            if ($max_date_query && !empty($max_date_query['date']) && $max_date_query['date'] < $max_date) {
                $max_date = $max_date_query['date'];
            }
        }
        
        // Calcular la fecha inicial 7 días antes de la fecha final
        $start_date = date('Y-m-d', strtotime('-6 days', strtotime($max_date)));
        
        // Crear un array para almacenar los datos en el formato esperado para el gráfico
        $chart_data = [];
        
        // Definir las claves de status con sus correspondientes nombres
        $status_labels = [
            1 => 'presente',
            2 => 'ausente',
            3 => 'tardanza',
            4 => 'justificado'
        ];
        
        // Iterar sobre los student_id obtenidos
        foreach ($student_ids as $student) {
            if (!isset($student['student_id'])) {
                continue; // Omitir si el student_id no está definido
            }
    
            // Obtener los registros de asistencia para el estudiante actual
            $attendance_data = $this->db
                ->select('date, status, COUNT(*) as count')
                ->where('student_id', $student['student_id'])
                ->where('date >=', $start_date)
                ->where('date <=', $max_date)
                ->group_by(['date', 'status'])
                ->get('attendance_student')
                ->result_array();
        
            // Iterar sobre los datos de asistencia para el estudiante actual
            foreach ($attendance_data as $data) {
                if (!isset($data['date'], $data['status'], $data['count'])) {
                    continue; // Si falta alguno de los índices, omitir
                }
    
                // Verificar que el status esté en las claves definidas
                if (!array_key_exists($data['status'], $status_labels)) {
                    continue; // Si el status no está definido, omitir
                }
    
                // Verificar si ya existe una entrada en el array para esta fecha
                if (!isset($chart_data[$data['date']])) {
                    $chart_data[$data['date']] = ['elapsed' => $data['date']];
        
                    // Inicializar todas las claves de status con valor cero
                    foreach ($status_labels as $status => $label) {
                        $chart_data[$data['date']][$label] = 0;
                    }
                }
    
                // Establecer el valor de conteo para la clave de status correspondiente
                $label = $status_labels[$data['status']];
                $chart_data[$data['date']][$label] += $data['count'];
            }
        }
        
        // Devolver los datos formateados para el gráfico como un array PHP
        return array_values($chart_data);
    }




    public function get_attendance_data_for_chart2($section_id) {
        // Obtener todos los student_id que pertenecen a la sección dada
        $student_ids = $this->db->select('student_id')->get_where('student_details', array('section_id' => $section_id))->result_array();
    
        // Verificar si no hay student_ids, buscar en academic_history
        if (empty($student_ids)) {
            $student_ids = $this->db
                ->select('student_id')
                ->get_where('academic_history', array('new_section_id' => $section_id))
                ->result_array();
    
            // Si sigue vacío, retornar un array vacío
            if (empty($student_ids)) {
                return [];
            }
        }
    
        // Inicializar la fecha final con la fecha más grande posible
        $max_date = '9999-12-31';
    
        // Iterar sobre los student_id obtenidos
        foreach ($student_ids as $student) {
            if (!isset($student['student_id'])) {
                continue; // Si el student_id no está definido, omitir
            }
    
            // Obtener la fecha máxima de los registros de asistencia para el estudiante actual
            $max_date_query = $this->db
                ->select_max('date')
                ->where('student_id', $student['student_id'])
                ->get('attendance_student')
                ->row_array();
    
            // Si no se encontraron registros en attendance_student, buscar en attendance_student_history
            if (empty($max_date_query)) {
                $max_date_query = $this->db
                    ->select_max('date')
                    ->where('student_id', $student['student_id'])
                    ->get('attendance_student_history')
                    ->row_array();
            }
    
            // Actualizar la fecha final si es menor que la fecha máxima encontrada
            if ($max_date_query && !empty($max_date_query['date']) && $max_date_query['date'] < $max_date) {
                $max_date = $max_date_query['date'];
            }
        }
    
        // Calcular la fecha inicial 7 días antes de la fecha final
        $start_date = date('Y-m-d', strtotime('-6 days', strtotime($max_date)));
    
        // Crear un array para almacenar los datos en el formato esperado para el gráfico
        $chart_data = [];
    
        // Definir las claves de status con sus correspondientes nombres
        $status_labels = [
            1 => 'presente',
            2 => 'ausente',
            3 => 'tardanza',
            4 => 'justificado'
        ];
    
        // Iterar sobre los student_id obtenidos
        foreach ($student_ids as $student) {
            if (!isset($student['student_id'])) {
                continue; // Omitir si el student_id no está definido
            }
    
            // Obtener los registros de asistencia para el estudiante actual
            $attendance_data = $this->db
                ->select('date, status, COUNT(*) as count')
                ->where('student_id', $student['student_id'])
                ->where('date >=', $start_date)
                ->where('date <=', $max_date)
                ->group_by(['date', 'status'])
                ->get('attendance_student')
                ->result_array();
    
            // Si no hay registros en attendance_student, buscar en attendance_student_history
            if (empty($attendance_data)) {
                $attendance_data = $this->db
                    ->select('date, status, COUNT(*) as count')
                    ->where('student_id', $student['student_id'])
                    ->where('date >=', $start_date)
                    ->where('date <=', $max_date)
                    ->group_by(['date', 'status'])
                    ->get('attendance_student_history')
                    ->result_array();
            }
    
            // Iterar sobre los datos de asistencia para el estudiante actual
            foreach ($attendance_data as $data) {
                if (!isset($data['date'], $data['status'], $data['count'])) {
                    continue; // Si falta alguno de los índices, omitir
                }
    
                // Verificar que el status esté en las claves definidas
                if (!array_key_exists($data['status'], $status_labels)) {
                    continue; // Si el status no está definido, omitir
                }
    
                // Verificar si ya existe una entrada en el array para esta fecha
                if (!isset($chart_data[$data['date']])) {
                    $chart_data[$data['date']] = ['elapsed' => $data['date']];
    
                    // Inicializar todas las claves de status con valor cero
                    foreach ($status_labels as $status => $label) {
                        $chart_data[$data['date']][$label] = 0;
                    }
                }
    
                // Establecer el valor de conteo para la clave de status correspondiente
                $label = $status_labels[$data['status']];
                $chart_data[$data['date']][$label] += $data['count'];
            }
        }
    
        // Devolver los datos formateados para el gráfico como un array PHP
        return array_values($chart_data);
    }
    



    
    


    public function get_attendance_per_student_amount($student_id, $attendance_type) {
        // Obtener la cantidad de registros de attendance_student que coincidan con el student_id y el attendance_type
        $amount = $this->db->where(array('student_id' => $student_id, 'status' => $attendance_type))->from('attendance_student')->count_all_results();
    
        // Devolver la cantidad obtenida
        return $amount;
    }
    
    
    public function get_attendance_data_for_chart_student($student_id) {
        // Inicializar la fecha final con la fecha más grande posible
        $max_date_query = $this->db
            ->select_max('date')
            ->where('student_id', $student_id)
            ->get('attendance_student')
            ->row_array();
    
        // Si no se encontraron registros de asistencia para el estudiante, devolver un array vacío
        if (!$max_date_query || empty($max_date_query['date'])) {
            return [];
        }
    
        $max_date = $max_date_query['date'];
    
        // Calcular la fecha inicial 6 días antes de la fecha final
        $start_date = date('Y-m-d', strtotime('-6 days', strtotime($max_date)));
    
        // Crear un array para almacenar los datos en el formato esperado para el gráfico
        $chart_data = [];
    
        // Definir las claves de status con sus correspondientes nombres
        $status_labels = [
            1 => 'presente',
            2 => 'ausente',
            3 => 'tardanza',
            4 => 'justificado'
        ];
    
        // Obtener los registros de asistencia para el estudiante
        $attendance_data = $this->db
            ->select('date, status, COUNT(*) as count')
            ->where('student_id', $student_id)
            ->where('date >=', $start_date) // Considerar solo las fechas después de la fecha inicial
            ->where('date <=', $max_date)   // Considerar solo las fechas antes de la fecha final
            ->group_by(['date', 'status'])
            ->get('attendance_student')
            ->result_array();
    
        // Iterar sobre los datos de asistencia para el estudiante
        foreach ($attendance_data as $data) {
            // Verificar si ya existe una entrada en el array para esta fecha
            if (!isset($chart_data[$data['date']])) {
                // Si no existe, crear una nueva entrada con el formato esperado
                $chart_data[$data['date']] = ['elapsed' => $data['date']];
    
                // Inicializar todas las claves de status con valor cero
                foreach ($status_labels as $status => $label) {
                    $chart_data[$data['date']][$label] = 0;
                }
            }
    
            // Establecer el valor de conteo para la clave de status correspondiente
            if (isset($status_labels[$data['status']])) {
                $label = $status_labels[$data['status']];
                $chart_data[$data['date']][$label] += $data['count'];
            }
        }
    
        // Devolver los datos formateados para el gráfico como un array PHP
        return array_values($chart_data);
    }
    
    public function get_attendance_data_for_student($student_id) {
        // Obtener los registros de asistencia para el estudiante
        $attendance_data = $this->db
            ->select('date, status, observation')
            ->where('student_id', $student_id)
            ->order_by('date', 'DESC')
            ->get('attendance_student')
            ->result_array();
    
        return $attendance_data;
    }
    

   
    function get_stages() {
        $query = $this->db->get('stage');
        return $query->result_array();
    }

    function get_exams() {
        $query = $this->db->get('exam');
        return $query->result_array();
    }

    function get_exam_info($exam_id) {
        $query = $this->db->get_where('exam', array('exam_id' => $exam_id));
        return $query->result_array();
    }

    function get_exam_type_info($id) {
        $query = $this->db->get_where('exam_type', array('id' => $id));
        return $query->result_array();
    }

    function get_task_info($task_id) {
        $query = $this->db->get_where('task', array('task_id' => $task_id));
        return $query->result_array();
    }

    function get_guardians() {
        $query = $this->db->get('guardian');
        return $query->result_array();
    }

    // function get_guardian_info($guardian_id) {
    //     $query = $this->db->get_where('guardian_details', array('guardian_id' => $guardian_id));
    //     return $query->result_array();
    // }

    function get_guardian_info($guardian_id) {
          // Selecciona todas las columnas necesarias de ambas tablas
          $this->db->select('guardian.guardian_id, guardian.email, guardian.username, guardian.password, guardian_details.firstname, guardian_details.lastname, guardian_details.dni, guardian_details.photo, guardian_details.phone_cel, guardian_details.phone_fij, guardian_details.birthday, guardian_details.gender_id, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
        
          // Indica que la tabla principal es student
          $this->db->from('guardian');
          
          // Realiza un JOIN con student_details utilizando el campo student_id
          $this->db->join('guardian_details', 'guardian.guardian_id = guardian_details.guardian_id');
          
          // Realiza un JOIN con la tabla address utilizando el campo address_id de student_details
          $this->db->join('address', 'guardian_details.address_id = address.address_id');
          
          // Agrega la condición para el student_id
          $this->db->where('guardian.guardian_id', $guardian_id);
          
          // Ejecuta la consulta y almacena los resultados en una variable
          $query = $this->db->get();
          
          // Devuelve los resultados como un array
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
    
    
    function get_tearchers() {
        $query = $this->db->get('teacher');
        return $query->result_array();
    }

    function get_teachers_info($teacher_id) {
        $query = $this->db->get_where('teacher_details', array('teacher_id' => $teacher_id));
        return $query->row_array(); // Retorna una sola fila como array
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
    

    function get_student_info_per_guardian($guardian_id) {
        // Obtener student_id de la tabla student_guardian
        $this->db->select('student_id');
        $this->db->from('student_guardian');
        $this->db->where('guardian_id', $guardian_id);
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            $students = $query->result_array();
    
            // Extraer todos los student_id en un array
            $student_ids = array_column($students, 'student_id');
    
            // Obtener detalles de los estudiantes de las tablas student, student_details y address
            $this->db->select('student.student_id, student.email, student.username, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.phone_cel, student_details.phone_fij, student_details.birthday, student_details.gender_id, student_details.section_id, student_details.class_id, address.locality, address.neighborhood, address.address_line');
            $this->db->from('student');
            $this->db->join('student_details', 'student.student_id = student_details.student_id');
            $this->db->join('address', 'student_details.address_id = address.address_id', 'left');
            $this->db->where_in('student.student_id', $student_ids);
            $student_query = $this->db->get();
    
            if ($student_query->num_rows() > 0) {
                return $student_query->result_array();
            } else {
                echo "No se encontraron detalles de estudiantes.";
                return array(); 
            }
        } else {
            echo "No se encontraron estudiantes para el tutor con ID: $guardian_id.";
            return array(); 
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
