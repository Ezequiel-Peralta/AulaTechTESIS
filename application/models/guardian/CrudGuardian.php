<?php
class CrudGuardian extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_guardians(){
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

   
}
