<?php
class Behavior_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_student_behavior($student_id) {
        try {
            $student_id = $this->db->escape_str($student_id);

            $this->db->where('student_id', $student_id);
            $behavior_data = $this->db->get('behavior')->result_array();

            $this->db->select('student.student_id, student.email, student.username, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.section_id, student_details.class_id, student_details.user_status_id');
            $this->db->from('student');
            $this->db->join('student_details', 'student.student_id = student_details.student_id');
            $this->db->where('student.student_id', $student_id);
            $student_data = $this->db->get()->row_array();

            return array(
                'behavior_data' => $behavior_data,
                'student_data' => $student_data
            );
        } catch (Exception $e) {
            log_message('error', 'Error in get_student_behavior: ' . $e->getMessage());
            return false;
        }
    }

    public function get_behavior_info($behavior_id) {
        try {
            $behavior_id = $this->db->escape_str($behavior_id);
            return $this->db->get_where('behavior', array('behavior_id' => $behavior_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_behavior_info: ' . $e->getMessage());
            return false;
        }
    }

    public function get_behavior_student($behavior_id) {
        try {
            $behavior_id = $this->db->escape_str($behavior_id);
            $this->db->select('behavior.*');
            $this->db->from('behavior');
            $this->db->where('behavior.behavior_id', $behavior_id);
            return $this->db->get()->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_behavior_student: ' . $e->getMessage());
            return false;
        }
    }

    public function get_students_by_section($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            $this->db->select('student.student_id, student.email, student.username, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.section_id, student_details.class_id, student_details.user_status_id');
            $this->db->from('student');
            $this->db->join('student_details', 'student.student_id = student_details.student_id');
            $this->db->where('student_details.user_status_id', 1);
            $this->db->where('student_details.section_id', $section_id);
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_students_by_section: ' . $e->getMessage());
            return false;
        }
    }

    public function get_behavior_by_student($student_id) {
        try {
            $student_id = $this->db->escape_str($student_id);
            $this->db->where('student_id', $student_id);
            return $this->db->get('behavior')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_behavior_by_student: ' . $e->getMessage());
            return false;
        }
    }

    public function create_behavior($data) {
        $this->db->trans_start(); // Iniciar transaccion

        try {
            // Insertar el behavior
            $this->db->insert('behavior', $data);

            // Verificar si fue exitoso
            if ($this->db->affected_rows() <= 0) {
                throw new Exception('Error al insertar comportamiento.');
            }

            // Confirmar la transaccion
            $this->db->trans_commit();
            return true;

        } catch (Exception $e) {
            $this->db->trans_rollback(); // Revertir cambios en caso de error
            log_message('error', 'Error in create_behavior: ' . $e->getMessage());
            return false;
        }
    }

    public function update_behavior($behavior_id, $data) {
        try {
            $behavior_id = $this->db->escape_str($behavior_id);
            $this->db->where('behavior_id', $behavior_id);
            $this->db->update('behavior', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_behavior: ' . $e->getMessage());
            return false;
        }
    }

    public function update_behavior_status($behavior_id, $status) {
        try {
            $behavior_id = $this->db->escape_str($behavior_id);
            $status = $this->db->escape_str($status);
            $this->db->where('behavior_id', $behavior_id);
            $this->db->update('behavior', array('status_id' => $status));
        } catch (Exception $e) {
            log_message('error', 'Error in update_behavior_status: ' . $e->getMessage());
            return false;
        }
    }

    public function get_active_sections() {
        try {
            $this->db->select('section.class_id, section.section_id, section.letter_name, section.shift_id');
            $this->db->from('section');
            $this->db->join('academic_period', 'section.academic_period_id = academic_period.id');
            $this->db->where('academic_period.status_id', 1);
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_active_sections: ' . $e->getMessage());
            return false;
        }
    }

    public function get_classes_by_ids($class_ids) {
        try {
            $this->db->where_in('class_id', $class_ids);
            return $this->db->get('class')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_classes_by_ids: ' . $e->getMessage());
            return false;
        }
    }

    public function get_behavior_types() {
        try {
            return $this->db->get('behavior_type')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_behavior_types: ' . $e->getMessage());
            return false;
        }
    }

    public function get_classes() {
        try {
            return $this->db->get('class')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_classes: ' . $e->getMessage());
            return false;
        }
    }

    public function get_sections_by_class($class_id) {
        try {
            $class_id = $this->db->escape_str($class_id);
            $this->db->where('class_id', $class_id);
            return $this->db->get('section')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_sections_by_class: ' . $e->getMessage());
            return false;
        }
    }


}