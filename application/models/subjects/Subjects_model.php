<?php
class Subjects_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function create_subject($data) {
        try {
            $this->db->insert('subject', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in create_subject: ' . $e->getMessage());
            return false;
        }
    }

    public function update_subject($subject_id, $data) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            $this->db->where('subject_id', $subject_id);
            return $this->db->update('subject', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_subject: ' . $e->getMessage());
            return false;
        }
    }

    public function update_subject_status($subject_id, $status_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            $this->db->where('subject_id', $subject_id);
            return $this->db->update('subject', array('status_id' => $status_id));
        } catch (Exception $e) {
            log_message('error', 'Error in update_subject_status: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subject($subject_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            return $this->db->get_where('subject', array('subject_id' => $subject_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_subject: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subjects() {
        try {
            return $this->db->get('subject')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_subjects: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subject_info($subject_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            $this->db->select('subject.*, teacher_details.firstname, teacher_details.lastname, teacher_details.teacher_id');
            $this->db->from('subject');
            $this->db->join('teacher_details', 'teacher_details.teacher_id = subject.teacher_id', 'left');
            $this->db->where('subject.subject_id', $subject_id);
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_subject_info: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subject_info2($subject_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
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
        } catch (Exception $e) {
            log_message('error', 'Error in get_subject_info2: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subjects_by_class($class_id) {
        try {
            $class_id = $this->db->escape_str($class_id);
            return $this->db->get_where('subject', array('class_id' => $class_id))->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_subjects_by_class: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subjects_by_section($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            return $this->db->get_where('subject', array('section_id' => $section_id))->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_subjects_by_section: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subjects_by_section2($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            $query = $this->db->get_where('subject', array('section_id' => $section_id));
            if ($query->num_rows() > 0) {
                return $query->result_array();
            }
            return $this->db->get_where('subject_history', array('section_id' => $section_id))->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_subjects_by_section2: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subjects_and_library_by_section($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
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
            $results = $this->db->get()->result_array();
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
        } catch (Exception $e) {
            log_message('error', 'Error in get_subjects_and_library_by_section: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subject_name_by_id($subject_id) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            $query = $this->db->get_where('subject', array('subject_id' => $subject_id))->row();
            return $query->name;
        } catch (Exception $e) {
            log_message('error', 'Error in get_subject_name_by_id: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            return $this->db->get_where('section', array('section_id' => $section_id))->row();
        } catch (Exception $e) {
            log_message('error', 'Error in get_section: ' . $e->getMessage());
            return false;
        }
    }

    public function update_subject_image($subject_id, $file_name) {
        try {
            $subject_id = $this->db->escape_str($subject_id);
            $this->db->where('subject_id', $subject_id);
            return $this->db->update('subject', array('image' => $file_name));
        } catch (Exception $e) {
            log_message('error', 'Error in update_subject_image: ' . $e->getMessage());
            return false;
        }
    }

    public function get_active_academic_period() {
        try {
            return $this->db->get_where('academic_period', array('status_id' => 1))->row();
        } catch (Exception $e) {
            log_message('error', 'Error in get_active_academic_period: ' . $e->getMessage());
            return false;
        }
    }

    public function get_first_section($academic_period_id) {
        try {
            $academic_period_id = $this->db->escape_str($academic_period_id);
            $this->db->where('academic_period_id', $academic_period_id);
            $this->db->order_by('section_id', 'ASC');
            return $this->db->get('section')->row();
        } catch (Exception $e) {
            log_message('error', 'Error in get_first_section: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section_data($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            return $this->db->get_where('section', array('section_id' => $section_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_section_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section_history_data($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            return $this->db->get_where('section_history', array('section_id' => $section_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_section_history_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_teacher_data($teacher_id) {
        try {
            $teacher_id = $this->db->escape_str($teacher_id);
            return $this->db->get_where('teacher_details', array('teacher_id' => $teacher_id))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_data: ' . $e->getMessage());
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