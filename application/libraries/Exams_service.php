<?php
class Exams_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('exams/Exams_model');
    }

    public function get_teacher_id_by_subject($subject_id) {
        try {
            return $this->Exams_model->get_teacher_id_by_subject($subject_id);
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_id_by_subject: ' . $e->getMessage());
            return false;
        }
    }

    public function create_exam($data) {
        try {
            return $this->Exams_model->create_exam($data);
        } catch (Exception $e) {
            log_message('error', 'Error in create_exam: ' . $e->getMessage());
            return false;
        }
    }

    public function update_exam($exam_id, $data) {
        try {
            $this->Exams_model->update_exam($exam_id, $data);
        } catch (Exception $e) {
            log_message('error', 'Error in update_exam: ' . $e->getMessage());
            return false;
        }
    }

    public function update_exam_status($exam_id, $status) {
        try {
            $this->Exams_model->update_exam_status($exam_id, $status);
        } catch (Exception $e) {
            log_message('error', 'Error in update_exam_status: ' . $e->getMessage());
            return false;
        }
    }

    public function bulk_update_exam_status($segments, $action) {
        try {
            $index = array_search($action . '_exam_bulk', $segments);
            $exam_ids = array_slice($segments, $index + 1);
            $exam_ids = array_filter($exam_ids, 'is_numeric');

            $status = ($action === 'enable') ? 1 : 0;
            $this->Exams_model->bulk_update_exam_status($exam_ids, $status);
        } catch (Exception $e) {
            log_message('error', 'Error in bulk_update_exam_status: ' . $e->getMessage());
            return false;
        }
    }

    public function upload_exam_files($exam_id, $files) {
        try {
            $exam_directory = 'uploads/exams/' . $exam_id . '/';
            if (!is_dir($exam_directory)) {
                mkdir($exam_directory, 0777, true);
            }

            $this->load->library('upload');

            $number_of_files = count($files['attachments']['name']);
            $uploaded_files = [];

            for ($i = 0; $i < $number_of_files; $i++) {
                $_FILES['attachment']['name'] = $files['attachments']['name'][$i];
                $_FILES['attachment']['type'] = $files['attachments']['type'][$i];
                $_FILES['attachment']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                $_FILES['attachment']['error'] = $files['attachments']['error'][$i];
                $_FILES['attachment']['size'] = $files['attachments']['size'][$i];

                $config['upload_path'] = $exam_directory;
                $config['allowed_types'] = '*';
                $config['max_size'] = '100480';
                $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $_FILES['attachment']['name']);
                $config['file_name'] = $filename;
                $config['detect_mime'] = FALSE;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('attachment')) {
                    $upload_data = $this->upload->data();
                    $uploaded_files[] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('flash_message', array(
                        'title' => '¡' . ucfirst(get_phrase('file_upload_error')) . '!',
                        'text' => $this->upload->display_errors(),
                        'icon' => 'error',
                        'showCloseButton' => 'true',
                        'confirmButtonText' => ucfirst(get_phrase('accept')),
                        'confirmButtonColor' => '#d33',
                    ));
                    redirect(base_url() . 'index.php?admin/exams_information/' . $exam_id, 'refresh');
                }
            }

            $files_json = json_encode($uploaded_files);
            $this->Exams_model->update_exam($exam_id, ['files' => $files_json]);
        } catch (Exception $e) {
            log_message('error', 'Error in upload_exam_files: ' . $e->getMessage());
            return false;
        }
    }

    public function get_section_data($section_id) {
        try {
            $section_data = $this->Exams_model->get_section($section_id);
            $used_section_history = false;
            $academic_period_name = '';

            if (empty($section_data)) {
                $section_data = $this->Exams_model->get_section_history($section_id);
                $used_section_history = true;
            }

            if ($used_section_history) {
                $academic_period_name = $this->crud_model->get_academic_period_name_per_section2($section_id);
            }

            return array(
                'section_data' => $section_data,
                'used_section_history' => $used_section_history,
                'academic_period_name' => $academic_period_name
            );
        } catch (Exception $e) {
            log_message('error', 'Error in get_section_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_subject_data($subject_id) {
        try {
            $subject_data = $this->Exams_model->get_subject($subject_id);
            $used_subject_history = false;

            if (empty($subject_data)) {
                $subject_data = $this->Exams_model->get_subject_history($subject_id);
                $used_subject_history = true;
            }

            return array(
                'subject_data' => $subject_data,
                'used_subject_history' => $used_subject_history
            );
        } catch (Exception $e) {
            log_message('error', 'Error in get_subject_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_teacher_data($teacher_id) {
        try {
            return $this->Exams_model->get_teacher($teacher_id);
        } catch (Exception $e) {
            log_message('error', 'Error in get_teacher_data: ' . $e->getMessage());
            return false;
        }
    }

    public function get_all_exams_count($section_id, $subject_id) {
        try {
            $this->db->from('exam');
            $this->db->where('section_id', $section_id);
            if (!empty($subject_id)) {
                $this->db->where('subject_id', $subject_id);
            }
            return $this->db->count_all_results();
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_exams_count: ' . $e->getMessage());
            return false;
        }
    }

    public function get_exams($section_id, $subject_id) {
        try {
            if (!empty($subject_id)) {
                return $this->db->get_where('exam', array('subject_id' => $subject_id))->result_array();
            } else {
                return $this->db->get_where('exam', array('section_id' => $section_id))->result_array();
            }
        } catch (Exception $e) {
            log_message('error', 'Error in get_exams: ' . $e->getMessage());
            return false;
        }
    }
}