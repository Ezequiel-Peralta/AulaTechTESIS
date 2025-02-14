<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

    class Model_login extends CI_Model {

        public function __construct() {
            parent::__construct();
            $this->load->database();
        }
    
        // Función para validar el inicio de sesión
        public function validate_login($email, $password) {
            // Consulta para buscar el usuario en la tabla 'admin'
            $admin_query = $this->db->get_where('admin', array('email' => $email, 'password' => $password));
            if ($admin_query->num_rows() > 0) {
                $admin_row = $admin_query->row();
                return array(
                    'login_status' => 'success',
                    'user_data' => array(
                        'id' => $admin_row->id,
                        'email' => $admin_row->email,
                        'password' => $admin_row->password,
                        'firstname' => $admin_row->firstname,
                        'lastname' => $admin_row->lastname,
                        'gender' => $admin_row->gender,
                        'phone' => $admin_row->phone,
                        'id_group' => $admin_row->id_group,
                        'login_type' => 'admin'
                    )
                );
            }
    
            // Consulta para buscar el usuario en la tabla 'teacher'
            $teacher_query = $this->db->get_where('teacher', array('email' => $email, 'password' => $password));
            if ($teacher_query->num_rows() > 0) {
                $teacher_row = $teacher_query->row();
                return array(
                    'login_status' => 'success',
                    'user_data' => array(
                        'id' => $teacher_row->teacher_id,
                        'name' => $teacher_row->name,
                        'login_type' => 'teacher'
                    )
                );
            }
    
            // Consulta para buscar el usuario en la tabla 'student'
            $student_query = $this->db->get_where('student', array('email' => $email, 'password' => $password));
            if ($student_query->num_rows() > 0) {
                $student_row = $student_query->row();
                return array(
                    'login_status' => 'success',
                    'user_data' => array(
                        'id' => $student_row->student_id,
                        'name' => $student_row->name,
                        'login_type' => 'student'
                    )
                );
            }
    
            // Si ningún usuario coincide, devuelve 'invalid'
            return array('login_status' => 'invalid');
        }
    
    
    }