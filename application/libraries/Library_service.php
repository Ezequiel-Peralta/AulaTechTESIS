<?php
class Library_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('libraryy/Library_model');
    }

    public function create_library($data, $file) {
        try {
            $this->Library_model->create_library($data);
            $inserted_id = $this->db->insert_id();

            if (!empty($file['name'])) {
                $base_path = './uploads/library/';
                if (!is_dir($base_path)) {
                    mkdir($base_path, 0777, true);
                }

                $config['upload_path'] = $base_path;
                $config['allowed_types'] = 'jpg|png|pdf|docx|txt|xls|xlsx';
                $config['max_size'] = '102400';
                $config['file_name'] = 'archivo_id_' . $inserted_id;

                $this->load->library('upload', $config);

                $section = $this->db->get_where('section', array('section_id' => $data['section_id']))->row();
                if ($section) {
                    $section_name = $section->class_id . '-' . $section->letter_name;
                    $section_folder = $config['upload_path'] . $section_name;
                    $subject_folder = $section_folder . '/subject_' . $data['subject_id'];

                    if (!is_dir($section_folder)) mkdir($section_folder, 0777, true);
                    if (!is_dir($subject_folder)) mkdir($subject_folder, 0777, true);

                    $config['upload_path'] = $subject_folder;
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload('library_file')) {
                        $error = $this->upload->display_errors();
                        throw new Exception('Error al subir el archivo: ' . $error);
                    } else {
                        $upload_data = $this->upload->data();
                        $file_name = $upload_data['file_name'];

                        $this->db->where('library_id', $inserted_id);
                        $this->db->update('library', ['url_file' => $file_name]);
                    }
                }
            }

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error in create_library: ' . $e->getMessage());
            return false;
        }
    }

    public function update_library($library_id, $data, $file) {
        try {
            $currentFile = $this->db->get_where('library', array('library_id' => $library_id))->row();

            if (!empty($file['name'])) {
                if (!empty($currentFile->url_file) && file_exists('./uploads/library/' . $currentFile->url_file)) {
                    unlink('./uploads/library/' . $currentFile->url_file);
                }

                $base_path = './uploads/library/';
                if (!is_dir($base_path)) {
                    mkdir($base_path, 0777, true);
                }

                $config['upload_path'] = $base_path;
                $config['allowed_types'] = 'jpg|png|pdf|docx|txt|xls|xlsx';
                $config['max_size'] = '102400';
                $config['file_name'] = 'archivo_id_' . $library_id;

                $this->load->library('upload', $config);

                $section = $this->db->get_where('section', array('section_id' => $data['section_id']))->row();
                if ($section) {
                    $section_name = $section->class_id . '-' . $section->letter_name;
                    $section_folder = $config['upload_path'] . $section_name;
                    $subject_folder = $section_folder . '/subject_' . $data['subject_id'];

                    if (!is_dir($section_folder)) mkdir($section_folder, 0777, true);
                    if (!is_dir($subject_folder)) mkdir($subject_folder, 0777, true);

                    $config['upload_path'] = $subject_folder;
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload('library_file')) {
                        $error = $this->upload->display_errors();
                        throw new Exception('Error al subir el archivo: ' . $error);
                    } else {
                        $upload_data = $this->upload->data();
                        $file_name = $upload_data['file_name'];

                        $this->db->where('library_id', $library_id);
                        $this->db->update('library', ['url_file' => $file_name]);
                    }
                }
            }

            $this->Library_model->update_library($library_id, $data);

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error in update_library: ' . $e->getMessage());
            return false;
        }
    }

}