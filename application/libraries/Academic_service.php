<?php
class Academic_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('academic/Academic_model');
    }

    public function create_academic_period($data) {
        try {
            $new_academic_period_id = $this->Academic_model->create_academic_period($data);

            $current_academic_period = $this->Academic_model->get_current_academic_period();

            $current_academic_period_id = $current_academic_period['id'];
            $this->Academic_model->update_academic_period_status($current_academic_period_id, 0);

            $students = $this->Academic_model->get_active_students();
            foreach ($students as $student) {
                $student_id = $student['student_id'];
                $section_id = $student['section_id'];
                $class_id = $student['class_id'];

                if ($student['class_id'] == 6) {
                    $this->Academic_model->update_student_details($student_id, [
                        'user_status_id' => 0,
                        'status_reason' => 'graduation'
                    ]);
                }

                $academic_history_data = [
                    'student_id' => $student_id,
                    'old_class_id' => $class_id,
                    'old_section_id' => $section_id,
                    'old_academic_period_id' => $current_academic_period_id,
                    'new_class_id' => null,
                    'new_section_id' => null,
                    'new_academic_period_id' => $new_academic_period_id,
                    'date_change' => date('Y-m-d')
                ];
                $this->Academic_model->insert_academic_history($academic_history_data);

                $this->Academic_model->move_marks_to_history($student_id, $current_academic_period_id);
                $this->Academic_model->move_attendance_to_history($student_id, $current_academic_period_id);
                $this->Academic_model->move_behavior_to_history($student_id, $current_academic_period_id);
            }

            $this->Academic_model->move_exams_to_history($section_id, $current_academic_period_id);
            $this->Academic_model->move_libraries_to_history($section_id, $current_academic_period_id);
            $this->Academic_model->move_schedules_to_history($section_id, $current_academic_period_id);
            $this->Academic_model->move_subjects_to_history($section_id, $current_academic_period_id);
            $this->Academic_model->move_sections_to_history($current_academic_period_id);

            $this->Academic_model->update_student_details_for_new_period();

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error in create_academic_period: ' . $e->getMessage());
            return false;
        }
    }

  

  

}