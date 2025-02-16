<?php
class CrudSubject extends CI_Model {
    function __construct() {
        parent::__construct();
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
    
    
    

    function get_subject_name_by_id($subject_id) {
        $query = $this->db->get_where('subject', array('subject_id' => $subject_id))->row();
        return $query->name;
    }
}