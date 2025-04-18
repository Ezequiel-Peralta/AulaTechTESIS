<?php
class News_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('newss/News_model');
    }

    public function create_news($data, $files) {
        try {
            $news_id = $this->News_model->create_news($data);

            if ($news_id && !empty($files['name'][0])) {
                $exam_directory = 'uploads/news/' . $news_id . '/';
                if (!is_dir($exam_directory)) {
                    mkdir($exam_directory, 0777, true);
                }

                $this->load->library('upload');
                $uploaded_files = [];

                for ($i = 0; $i < count($files['name']); $i++) {
                    $_FILES['attachment']['name'] = $files['name'][$i];
                    $_FILES['attachment']['type'] = $files['type'][$i];
                    $_FILES['attachment']['tmp_name'] = $files['tmp_name'][$i];
                    $_FILES['attachment']['error'] = $files['error'][$i];
                    $_FILES['attachment']['size'] = $files['size'][$i];

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
                        throw new Exception($this->upload->display_errors());
                    }
                }

                $files_json = json_encode($uploaded_files);
                $this->News_model->update_news($news_id, ['images' => $files_json]);
            }

            return $news_id;
        } catch (Exception $e) {
            log_message('error', 'Error in create_news: ' . $e->getMessage());
            return false;
        }
    }

    public function update_news($news_id, $data, $files, $files_to_delete) {
        try {
            $existing_files = $this->News_model->get_news_by_id($news_id)['images'];
            $existing_files = !empty($existing_files) ? json_decode($existing_files, true) : [];

            $files_to_keep = array_diff($existing_files, $files_to_delete);

            foreach ($files_to_delete as $file) {
                $file_path = 'uploads/news/' . $news_id . '/' . $file;
                if (file_exists($file_path) && is_file($file_path)) {
                    unlink($file_path); 
                    
                }
            }

            $final_files = $files_to_keep;

            if (!empty($files) && isset($files['name'][0]) && !empty($files['name'][0])) {


                
                $exam_directory = 'uploads/news/' . $news_id . '/';
                if (!is_dir($exam_directory)) {
                    mkdir($exam_directory, 0777, true);
                }

                $this->load->library('upload');
                $uploaded_files = [];

                for ($i = 0; $i < count($files['name']); $i++) {
                    $_FILES['attachment']['name'] = $files['name'][$i];
                    $_FILES['attachment']['type'] = $files['type'][$i];
                    $_FILES['attachment']['tmp_name'] = $files['tmp_name'][$i];
                    $_FILES['attachment']['error'] = $files['error'][$i];
                    $_FILES['attachment']['size'] = $files['size'][$i];

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
                        throw new Exception($this->upload->display_errors());
                    }
                }

                $final_files = array_merge($final_files, $uploaded_files);
            }

            $final_files = array_unique($final_files);
            $files_json = json_encode($final_files);

            $this->News_model->update_news($news_id, $data);
            $this->News_model->update_news($news_id, ['images' => $files_json]);

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error in update_news: ' . $e->getMessage());
            return false;
        }
    }
}