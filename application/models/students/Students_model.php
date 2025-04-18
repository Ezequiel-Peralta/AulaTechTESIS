<?php
class Students_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function get_students($class_id)
    {
        $query = $this->db->get_where('student', array('class_id' => $class_id));
        return $query->result_array();
    }

    function get_behavior_student($behavior_id)
    {
        $query = $this->db->get_where('behavior', array('behavior_id' => $behavior_id));
        return $query->result_array();
    }


    function get_students_per_section($section_id)
    {
        $this->db->select('student_details.student_id, student_details.firstname, student_details.lastname, student_details. enrollment, student_details.dni, student_details.gender_id, student_details.phone_cel, student_details.phone_fij, student_details.class_id, student_details.section_id, student_details.birthday, student.email, student.username, address.state, address.postalcode, address.locality, address.neighborhood, address.address, address.address_line');
        $this->db->from('student_details');
        $this->db->join('student', 'student.student_id = student_details.student_id');
        $this->db->join('address', 'address.address_id = student_details.address_id'); // Unión con la tabla address
        $this->db->where('student_details.section_id', $section_id);
        $query = $this->db->get();

        return $query->result_array();
    }


    function get_students_admissions()
    {
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

    function get_students_pre_enrollments()
    {
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


    function get_student_info2($student_id){
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
    function get_guardian_info_per_student($student_id){
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
    function get_academic_history_by_student($student_id){
        $this->db->where('student_id', $student_id);
        $this->db->order_by('date_change', 'ASC');
        $query = $this->db->get('academic_history');
        return $query->result_array();
    }

    function get_academic_by_student($student_id){
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

    function get_students_by_section($section_id)
    {
        $this->db->select('student_details.student_id, student_details.firstname, student_details.user_status_id, student_details.photo, student_details.lastname, student_details.enrollment, student_details.dni, student_details.gender_id, student_details.phone_cel, student_details.phone_fij, student_details.class_id, student_details.section_id, student_details.birthday, student.email, student.username, address.state, address.postalcode, address.locality, address.neighborhood, address.address, address.address_line');
        $this->db->from('student_details');
        $this->db->join('student', 'student.student_id = student_details.student_id');
        $this->db->join('address', 'address.address_id = student_details.address_id');
        $this->db->where('student_details.section_id', $section_id);
        $query = $this->db->get();

        return $query->result_array();
    }

    function get_student_info($student_id)
    {
        $this->db->select('student.student_id, student.email, student.username, student.password, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.medical_record, student_details.section_id, student_details.about, student_details.class_id, student_details.phone_cel, student_details.phone_fij, student_details.birthday, student_details.gender_id, student_details.enrollment, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
        $this->db->from('student');
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        $this->db->join('address', 'student_details.address_id = address.address_id');
        $this->db->where('student.student_id', $student_id);
        $query = $this->db->get();

        return $query->result_array();
    }

    function create_student($student_data, $address_data, $student_details_data)
    {
        try {
            $this->db->insert('address', $address_data);
            $student_details_data['address_id'] = $this->db->insert_id();

            $this->db->insert('student', $student_data);
            $student_id = $this->db->insert_id();
            $student_details_data['student_id'] = $this->db->insert_id();

            $this->db->insert('student_details', $student_details_data);

            return $student_id;
        } catch (Exception $e) {
            log_message('error', 'Error in create_student: ' . $e->getMessage());
            return false;
        }
    }

    function upload_medical_record_student($student_id, $file_name)
    {
        try {
            $this->db->where('student_id', $student_id);
            $this->db->update('student_details', array('medical_record' => $file_name));

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error in upload_medical_record_student: ' . $e->getMessage());
            return false;
        }
    }

    function update_student($student_id, $student_data, $address_data, $student_details_data)
    {
        try {
            $this->db->where('student_id', $student_id);
            $this->db->update('student', $student_data);

            $this->db->where('address_id', $address_data['address_id']);
            $this->db->update('address', $address_data);

            $this->db->where('student_id', $student_id);
            $this->db->update('student_details', $student_details_data);

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error in update_student: ' . $e->getMessage());
            return false;
        }
    }

    function inactive_student($student_id, $status_reason)
    {
        try {
            $this->db->where('student_id', $student_id);
            $this->db->update('student_details', array('user_status_id' => 0, 'status_reason' => $status_reason, 'class_id' => null, 'section_id' => null));

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error in inactive_student: ' . $e->getMessage());
            return false;
        }
    }

    function inactive_student_pre_enrollments($student_id, $status_reason)
    {
        try {
            $this->db->where('student_id', $student_id);
            $this->db->update('student_details', array('user_status_id' => 0, 'status_reason' => $status_reason, 'class_id' => null, 'section_id' => null));

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error in inactive_student_pre_enrollments: ' . $e->getMessage());
            return false;
        }
    }
}
