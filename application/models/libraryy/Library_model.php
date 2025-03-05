<?php
class Library_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function create_library($data) {
        try {
            return $this->db->insert('library', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in create_library: ' . $e->getMessage());
            return false;
        }
    }

    public function update_library($library_id, $data) {
        try {
            $this->db->where('library_id', $library_id);
            return $this->db->update('library', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_library: ' . $e->getMessage());
            return false;
        }
    }

    public function update_library_status($library_id, $status_id) {
        try {
            $this->db->where('library_id', $library_id);
            return $this->db->update('library', array('status_id' => $status_id));
        } catch (Exception $e) {
            log_message('error', 'Error in update_library_status: ' . $e->getMessage());
            return false;
        }
    }

    public function get_file_library($library_id) {
        try {
            $this->db->where('library_id', $library_id);
            $query = $this->db->get('library');
            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_file_library: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section_content_by_class($class_id) {
        try {
            $this->db->select('section.section_id, section.name');
            $this->db->from('section');
            $this->db->join('academic_period', 'section.academic_period_id = academic_period.id');
            $this->db->where('section.class_id', $class_id);
            $this->db->where('academic_period.status_id', 1);
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_section_content_by_class: ' . $e->getMessage());
            return false;
        }
    }
    
    public function get_subjects_by_section($section_id) {
        try {
            $this->db->where('section_id', $section_id);
            $query = $this->db->get('subject');
            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_subjects_by_section: ' . $e->getMessage());
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

    public function get_academic_periods() {
        try {
            return $this->db->get('academic_period')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_academic_periods: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section_data($section_id) {
        try {
            $this->db->where('section_id', $section_id);
            $query = $this->db->get('section');
            return $query->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_section_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subjects($section_id, $subject_id) {
        try {
            $this->db->from('subject');
            $this->db->where('section_id', $section_id);

            if (!empty($subject_id)) {
                $this->db->where('subject_id', $subject_id);
            }
            $this->db->where('status_id', 1);
            $subjects = $this->db->get()->result_array();

            if (empty($subjects)) {
                $this->db->from('subject_history');
                $this->db->where('section_id', $section_id);

                if (!empty($subject_id)) {
                    $this->db->where('subject_id', $subject_id);
                }
                $this->db->where('status_id', 1);
                $subjects = $this->db->get()->result_array();
            }

            return $subjects;
        } catch (Exception $e) {
            log_message('error', 'Error in get_subjects: ' . $e->getMessage());
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

    public function get_section_subject_count($section_id) {
        try {
            $this->db->where('section_id', $section_id);
            return $this->db->count_all_results('subject');
        } catch (Exception $e) {
            log_message('error', 'Error en get_section_subject_count: ' . $e->getMessage());
            return false;
        }
    }
    

 
}