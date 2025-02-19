<?php
class CrudMark extends CI_Model {
    function __construct() {
        parent::__construct();
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

}