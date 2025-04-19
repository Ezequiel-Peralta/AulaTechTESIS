<?php
class Academic_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function create_academic_period($data) {
        try {
            $this->db->insert('academic_period', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error in create_academic_period: ' . $e->getMessage());
            return false;
        }
    }

    public function get_active_academic_period() {
        try {
            $query = $this->db->get_where('academic_period', array('status_id' => 1));
    
            return $query->row();
        } catch (Exception $e) {
            log_message('error', 'Error in get_active_academic_period: ' . $e->getMessage());
            return false;
        }
    }

    public function get_students_by_section($section_id) {
        try {
            $section_id = $this->db->escape_str($section_id);
            $this->db->select('student.*, student_details.*');
            $this->db->from('student');
            $this->db->join('student_details', 'student.student_id = student_details.student_id');
            $this->db->where('student_details.section_id', $section_id);
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_students_by_section: ' . $e->getMessage());
            return false;
        }
    }

    public function get_current_academic_period() {
        try {
            return $this->db->get_where('academic_period', ['status_id' => 1])->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_current_academic_period: ' . $e->getMessage());
            return false;
        }
    }
    
    public function get_active_sections() {
        try {
            $this->db->select('section.class_id, section.section_id, section.letter_name, section.shift_id, class.name AS class_name');
            $this->db->from('section');
            $this->db->join('academic_period', 'section.academic_period_id = academic_period.id');
            $this->db->join('class', 'section.class_id = class.class_id'); 
            $this->db->where('academic_period.status_id', 1);
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_active_sections: ' . $e->getMessage());
            return false;
        }
    }

    public function get_all_classes() {
        try {
            $this->db->select('*');
            $this->db->from('class'); 
            return $this->db->get()->result_array(); 
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_classes: ' . $e->getMessage());
            return false;
        }
    }

    public function update_academic_period($academic_period_id, $data) {
        try {
            $this->db->where('id', $academic_period_id);
            $this->db->update('academic_period', $data);
            return true; 
        } catch (Exception $e) {
            log_message('error', 'Error in update_academic_period: ' . $e->getMessage());
            return false;
        }
    }

    public function update_academic_period_status($academic_period_id, $status_id) {
        try {
            $this->db->where('id', $academic_period_id);
            $this->db->update('academic_period', ['status_id' => $status_id]);
        } catch (Exception $e) {
            log_message('error', 'Error in update_academic_period_status: ' . $e->getMessage());
            return false;
        }
    }

    public function update_student_details_for_new_period() {
        try {
            $this->db->where('user_status_id', 1);
            $this->db->update('student_details', [
                'class_id' => null,
                'section_id' => null
            ]);
        } catch (Exception $e) {
            log_message('error', 'Error in update_student_details_for_new_period: ' . $e->getMessage());
            return false;
        }
    }

    public function get_active_students() {
        try {
            return $this->db->get_where('student_details', ['user_status_id' => 1])->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_active_students: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_academic_history($data) {
        try {
            $this->db->insert('academic_history', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in insert_academic_history: ' . $e->getMessage());
            return false;
        }
    }

    public function move_marks_to_history($student_id, $current_academic_period_id) {
        try {
            $marks = $this->db->get_where('mark', ['student_id' => $student_id])->result_array();
            foreach ($marks as $mark) {
                $mark_history_data = [
                    'mark_id' => $mark['mark_id'],
                    'academic_period_id' => $current_academic_period_id,
                    'subject_id' => $mark['subject_id'],
                    'mark_obtained' => $mark['mark_obtained'],
                    'student_id' => $mark['student_id'],
                    'class_id' => $mark['class_id'],
                    'section_id' => $mark['section_id'],
                    'exam_id' => $mark['exam_id'],
                    'exam_type_id' => $mark['exam_type_id'],
                    'date' => $mark['date']
                ];
                $this->db->insert('mark_history', $mark_history_data);
            }
            $this->db->delete('mark', ['student_id' => $student_id]);
        } catch (Exception $e) {
            log_message('error', 'Error in move_marks_to_history: ' . $e->getMessage());
            return false;
        }
    }

    public function move_attendance_to_history($student_id, $current_academic_period_id) {
        try {
            $attendances = $this->db->get_where('attendance_student', ['student_id' => $student_id])->result_array();
            foreach ($attendances as $attendance) {
                $attendance_history_data = [
                    'attendance_id' => $attendance['attendance_id'],
                    'section_id' => $attendance['section_id'],
                    'student_id' => $attendance['student_id'],
                    'date' => $attendance['date'],
                    'observation' => $attendance['observation'],
                    'status' => $attendance['status'],
                    'academic_period_id' => $current_academic_period_id,
                ];
                $this->db->insert('attendance_student_history', $attendance_history_data);
            }
            $this->db->delete('attendance_student', ['student_id' => $student_id]);
        } catch (Exception $e) {
            log_message('error', 'Error in move_attendance_to_history: ' . $e->getMessage());
            return false;
        }
    }

    public function move_behavior_to_history($student_id, $current_academic_period_id) {
        try {
            $behaviors = $this->db->get_where('behavior', ['student_id' => $student_id])->result_array();
            foreach ($behaviors as $behavior) {
                $behavior_history_data = [
                    'behavior_id' => $behavior['behavior_id'],
                    'student_id' => $behavior['student_id'],
                    'class_id' => $behavior['class_id'],
                    'section_id' => $behavior['section_id'],
                    'date' => $behavior['date'],
                    'comment' => $behavior['comment'],
                    'behavior_type_id' => $behavior['behavior_type_id'],
                    'status_id' => $behavior['status_id'],
                    'academic_period_id' => $current_academic_period_id
                ];
                $this->db->insert('behavior_history', $behavior_history_data);
            }
            $this->db->delete('behavior', ['student_id' => $student_id]);
        } catch (Exception $e) {
            log_message('error', 'Error in move_behavior_to_history: ' . $e->getMessage());
            return false;
        }
    }

    public function move_exams_to_history($section_id, $current_academic_period_id) {
        try {
            $exams = $this->db->get_where('exam', ['section_id' => $section_id])->result_array();
            foreach ($exams as $exam) {
                $exam_history_data = [
                    'exam_id' => $exam['exam_id'],
                    'name' => $exam['name'],
                    'date' => $exam['date'],
                    'files' => $exam['files'],
                    'exam_type_id' => $exam['exam_type_id'],
                    'class_id' => $exam['class_id'],
                    'section_id' => $exam['section_id'],
                    'subject_id' => $exam['subject_id'],
                    'teacher_id' => $exam['teacher_id'],
                    'status_id' => $exam['status_id'],
                    'academic_period_id' => $current_academic_period_id
                ];
                $this->db->insert('exam_history', $exam_history_data);
            }
            $this->db->delete('exam', ['section_id' => $section_id]);
        } catch (Exception $e) {
            log_message('error', 'Error in move_exams_to_history: ' . $e->getMessage());
            return false;
        }
    }

    public function move_libraries_to_history($section_id, $current_academic_period_id) {
        try {
            $libraries = $this->db->get_where('library', ['section_id' => $section_id])->result_array();
            foreach ($libraries as $library) {
                $library_history_data = [
                    'library_id' => $library['library_id'],
                    'file_name' => $library['file_name'],
                    'description' => $library['description'],
                    'class_id' => $library['class_id'],
                    'section_id' => $library['section_id'],
                    'subject_id' => $library['subject_id'],
                    'url_file' => $library['url_file'],
                    'date' => $library['date'],
                    'status_id' => $library['status_id'],
                    'academic_period_id' => $current_academic_period_id
                ];
                $this->db->insert('library_history', $library_history_data);
            }
            $this->db->delete('library', ['section_id' => $section_id]);
        } catch (Exception $e) {
            log_message('error', 'Error in move_libraries_to_history: ' . $e->getMessage());
            return false;
        }
    }

    public function move_schedules_to_history($section_id, $current_academic_period_id) {
        try {
            $schedules = $this->db->get_where('schedule', ['section_id' => $section_id])->result_array();
            foreach ($schedules as $schedule) {
                $schedule_history_data = [
                    'schedule_id' => $schedule['schedule_id'],
                    'time_start' => $schedule['time_start'],
                    'time_end' => $schedule['time_end'],
                    'day_id' => $schedule['day_id'],
                    'class_id' => $schedule['class_id'],
                    'section_id' => $schedule['section_id'],
                    'subject_id' => $schedule['subject_id'],
                    'teacher_id' => $schedule['teacher_id'],
                    'status_id' => $schedule['status_id'],
                    'academic_period_id' => $current_academic_period_id
                ];
                $this->db->insert('schedule_history', $schedule_history_data);
            }
            $this->db->delete('schedule', ['section_id' => $section_id]);
        } catch (Exception $e) {
            log_message('error', 'Error in move_schedules_to_history: ' . $e->getMessage());
            return false;
        }
    }

    public function move_subjects_to_history($section_id, $current_academic_period_id) {
        try {
            $subjects = $this->db->get_where('subject', ['section_id' => $section_id])->result_array();
            foreach ($subjects as $subject) {
                $subject_history_data = [
                    'subject_id' => $subject['subject_id'],
                    'name' => $subject['name'],
                    'image' => $subject['image'],
                    'class_id' => $subject['class_id'],
                    'section_id' => $subject['section_id'],
                    'teacher_aide_id' => $subject['teacher_aide_id'],
                    'teacher_id' => $subject['teacher_id'],
                    'schedule_id' => $subject['schedule_id'],
                    'status_id' => $subject['status_id'],
                    'academic_period_id' => $current_academic_period_id
                ];
                $this->db->insert('subject_history', $subject_history_data);
            }
            $this->db->delete('subject', ['section_id' => $section_id]);
        } catch (Exception $e) {
            log_message('error', 'Error in move_subjects_to_history: ' . $e->getMessage());
            return false;
        }
    }

    public function move_sections_to_history($current_academic_period_id) {
        try {
            $sections = $this->db->get('section')->result_array();
            foreach ($sections as $section) {
                $section_history_data = [
                    'section_id' => $section['section_id'],
                    'name' => $section['name'],
                    'letter_name' => $section['letter_name'],
                    'class_id' => $section['class_id'],
                    'teacher_aide_id' => $section['teacher_aide_id'],
                    'shift_id' => $section['shift_id'],
                    'status_id' => $section['status_id'],
                    'academic_period_id' => $current_academic_period_id
                ];
                $this->db->insert('section_history', $section_history_data);
            }
            $this->db->empty_table('section');
        } catch (Exception $e) {
            log_message('error', 'Error in move_sections_to_history: ' . $e->getMessage());
            return false;
        }
    }

    public function update_student_details($student_id, $data) {
        try {
            $this->db->where('student_id', $student_id);
            $this->db->update('student_details', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_student_details: ' . $e->getMessage());
            return false;
        }
    }

    public function get_all_academic_periods() {
        try {
            return $this->db->get('academic_period')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_academic_periods: ' . $e->getMessage());
            return false;
        }
    }

    public function get_sections_by_academic_period($academic_period_id) {
        try {
            $this->db->where('academic_period_id', $academic_period_id);
            return $this->db->get('section')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_sections_by_academic_period: ' . $e->getMessage());
            return false;
        }
    }

    public function get_student_details($student_id) {
        try {
        $this->db->select('student.student_id, student.email, student.username, student.password, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.medical_record, student_details.section_id, student_details.about, student_details.class_id, student_details.phone_cel, student_details.phone_fij, student_details.birthday, student_details.gender_id, student_details.enrollment, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
        $this->db->from('student');
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        $this->db->join('address', 'student_details.address_id = address.address_id');
        $this->db->where('student.student_id', $student_id);
        $query = $this->db->get();

        return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_student_details: ' . $e->getMessage());
            return false;
        }
    }

    public function get_student_details2($student_id) {
        try {
        $this->db->select('student.student_id, student.email, student.username, student.password, student_details.enrollment, student_details.firstname, student_details.lastname, student_details.dni, student_details.photo, student_details.medical_record, student_details.section_id, student_details.about, student_details.class_id, student_details.phone_cel, student_details.phone_fij, student_details.birthday, student_details.gender_id, student_details.enrollment, address.locality, address.neighborhood, address.address, address.address_line, address.postalcode');
        $this->db->from('student');
        $this->db->join('student_details', 'student.student_id = student_details.student_id');
        $this->db->join('address', 'student_details.address_id = address.address_id');
        $this->db->where('student.student_id', $student_id);
        $query = $this->db->get();

        return $query->row_array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_student_details: ' . $e->getMessage());
            return false;
        }
    }

}