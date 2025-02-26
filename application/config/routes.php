<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//Dashboard
$route['admin/dashboard'] = 'AdminSys/Dashboard';

// Student

//Student - student add
$route['admin/student_add'] = 'AdminSys/Student/student_add';

//Student - student bulk add
$route['admin/student_bulk_add/(:any)'] = 'AdminSys/Student/student_bulk_add/$1';
$route['admin/student_bulk_add'] = 'AdminSys/Student/student_bulk_add';

//Student - student information
$route['admin/student_information/(:any)'] = 'AdminSys/Student/student_information/$1';
$route['admin/student_information'] = 'AdminSys/Student/student_information';

//Student - student profile
$route['admin/student_profile/(:any)'] = 'AdminSys/Student/student_profile/$1';
$route['admin/student_profile'] = 'AdminSys/Student/student_profile';

//Student - student
$route['admin/student/(:any)/(:any)/(:any)'] = 'AdminSys/Student/student/$1/$2/$3';
$route['admin/student/(:any)/(:any)'] = 'AdminSys/Student/student/$1/$2';
$route['admin/student/(:any)'] = 'AdminSys/Student/student/$1';
$route['admin/student'] = 'AdminSys/Student/student';

//Student - manage students
$route['admin/manage_students'] = 'AdminSys/Student/manage_students';

//Student - student edit
$route['admin/student_edit/(:any)'] = 'AdminSys/Student/student_edit/$1';
$route['admin/student_edit'] = 'AdminSys/Student/student_edit';

//Student - get_students_content
$route['admin/get_students_content'] = 'AdminSys/Student/get_students_content';

//Student - get_students_content_by_section
$route['admin/get_students_content_by_section/(:any)'] = 'AdminSys/Student/get_students_content_by_section/$1';
$route['admin/get_students_content_by_section'] = 'AdminSys/Student/get_students_content_by_section';

//Student - get_all_students
$route['admin/get_all_students'] = 'AdminSys/Student/get_all_students';

//Academic

//Academic - academic period
$route['admin/academic_period/(:any)/(:any)'] = 'AdminSys/Academic/academic_period/$1/$2';
$route['admin/academic_period/(:any)'] = 'AdminSys/Academic/academic_period/$1';
$route['admin/academic_period'] = 'AdminSys/Academic/academic_period';

//Academic - academic history
$route['admin/academic_history/(:any)'] = 'AdminSys/Academic/academic_history/$1';
$route['admin/academic_history'] = 'AdminSys/Academic/academic_history';

//Academic - academic period add
$route['admin/academic_period_add'] = 'AdminSys/Academic/academic_period_add';

//Academic - manage academic history
$route['admin/manage_academic_history'] = 'AdminSys/Academic/manage_academic_history';

//Academic - view_student_academic_history
$route['admin/view_student_academic_history/(:any)'] = 'AdminSys/Academic/view_student_academic_history/$1';
$route['admin/view_student_academic_history'] = 'AdminSys/Academic/view_student_academic_history';

//Library

//Library - add_library
$route['admin/add_library'] = 'AdminSys/Library/add_library';

//Library - library
$route['admin/library/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Library/library/$1/$2/$3/$4';
$route['admin/library/(:any)/(:any)/(:any)'] = 'AdminSys/Library/library/$1/$2/$3';
$route['admin/library/(:any)/(:any)'] = 'AdminSys/Library/library/$1/$2';
$route['admin/library/(:any)'] = 'AdminSys/Library/library/$1';
$route['admin/library'] = 'AdminSys/Library/library';

//Library - edit_library
$route['admin/edit_library/(:any)'] = 'AdminSys/Library/edit_library/$1';
$route['admin/edit_library'] = 'AdminSys/Library/edit_library';

//Library - view_library
$route['admin/view_library/(:any)/(:any)'] = 'AdminSys/Library/view_library/$1/$2';
$route['admin/view_library/(:any)'] = 'AdminSys/Library/view_library/$1';
$route['admin/view_library'] = 'AdminSys/Library/view_library';

//Library - manage_library
$route['admin/manage_library'] = 'AdminSys/Library/manage_library';


//Library - library_information
$route['admin/library_information/(:any)'] = 'AdminSys/Library/library_information/$1';
$route['admin/library_information'] = 'AdminSys/Library/library_information';

//Behavior

//Behavior - add_behavior
$route['admin/add_behavior/(:any)/(:any)/(:any)'] = 'AdminSys/Behavior/add_behavior/$1/$2/$3';
$route['admin/add_behavior/(:any)/(:any)'] = 'AdminSys/Behavior/add_behavior/$1/$2';
$route['admin/add_behavior/(:any)'] = 'AdminSys/Behavior/add_behavior/$1';
$route['admin/add_behavior'] = 'AdminSys/Behavior/add_behavior';

//Behavior - edit_behavior
$route['admin/edit_behavior/(:any)'] = 'AdminSys/Behavior/edit_behavior/$1';
$route['admin/edit_behavior'] = 'AdminSys/Behavior/edit_behavior';

//Behavior - behavior
$route['admin/behavior/(:any)'] = 'AdminSys/Behavior/behavior/$1';
$route['admin/behavior'] = 'AdminSys/Behavior/behavior';

//Behavior - behavior_information
$route['admin/behavior_information/(:any)/(:any)/(:any)'] = 'AdminSys/Behavior/behavior_information/$1/$2/$3';
$route['admin/behavior_information/(:any)/(:any)'] = 'AdminSys/Behavior/behavior_information/$1/$2';
$route['admin/behavior_information/(:any)'] = 'AdminSys/Behavior/behavior_information/$1';
$route['admin/behavior_information'] = 'AdminSys/Behavior/behavior_information';

//Behavior - student_behavior
$route['admin/student_behavior/(:any)'] = 'AdminSys/Behavior/student_behavior/$1';
$route['admin/student_behavior'] = 'AdminSys/Behavior/student_behavior';

//Behavior - manage_behavior
$route['admin/manage_behavior'] = 'AdminSys/Behavior/manage_behavior';

//Teacher

//Teacher - teachers_information
$route['admin/teachers_information'] = 'AdminSys/Teacher/teachers_information';

//Teacher - teacher_profile
$route['admin/teacher_profile/(:any)'] = 'AdminSys/Teacher/teacher_profile/$1';
$route['admin/teacher_profile'] = 'AdminSys/Teacher/teacher_profile';

//Teacher - teacher
$route['admin/teacher/(:any)/(:any)/(:any)'] = 'AdminSys/Teacher/teacher/$1/$2/$3';
$route['admin/teacher/(:any)/(:any)'] = 'AdminSys/Teacher/teacher/$1/$2';
$route['admin/teacher/(:any)'] = 'AdminSys/Teacher/teacher/$1';
$route['admin/teacher'] = 'AdminSys/Teacher/teacher';

//Teacher - add_teacher
$route['admin/add_teacher'] = 'AdminSys/Teacher/add_teacher';

//Teacher - get_teachers
$route['admin/get_teachers'] = 'AdminSys/Teacher/get_teachers';

//Teacher - edit_teacher
$route['admin/edit_teacher/(:any)'] = 'AdminSys/Teacher/edit_teacher/$1';
$route['admin/edit_teacher'] = 'AdminSys/Teacher/edit_teacher';

//TeacherAide

//TeacherAide - teacher_aide
$route['admin/teacher_aide/(:any)/(:any)/(:any)'] = 'AdminSys/TeacherAide/teacher_aide/$1/$2/$3';
$route['admin/teacher_aide/(:any)/(:any)'] = 'AdminSys/Teacher/teacher_aide/$1/$2';
$route['admin/teacher_aide/(:any)'] = 'AdminSys/Teacher/teacher_aide/$1';
$route['admin/teacher_aide'] = 'AdminSys/Teacher/teacher_aide';

//TeacherAide - teachers_aide_information
$route['admin/teachers_aide_information'] = 'AdminSys/TeacherAide/teachers_aide_information';

//TeacherAide - teacher_aide_profile
$route['admin/teacher_aide_profile/(:any)'] = 'AdminSys/TeacherAide/teacher_aide_profile/$1';
$route['admin/teacher_aide_profile'] = 'AdminSys/TeacherAide/teacher_aide_profile';

//TeacherAide - teacher_aide_add
$route['admin/teacher_aide_add'] = 'AdminSys/TeacherAide/teacher_aide_add';

//TeacherAide - add_teacher_aide
$route['admin/add_teacher_aide'] = 'AdminSys/TeacherAide/add_teacher_aide';

//TeacherAide - edit_teacher_aide
$route['admin/edit_teacher_aide/(:any)'] = 'AdminSys/TeacherAide/edit_teacher_aide/$1';
$route['admin/edit_teacher_aide'] = 'AdminSys/TeacherAide/edit_teacher_aide';

//TeacherAide - teacherAide_edit
$route['admin/teacherAide_edit/(:any)'] = 'AdminSys/TeacherAide/teacherAide_edit/$1';
$route['admin/teacherAide_edit'] = 'AdminSys/TeacherAide/teacherAide_edit';

//Secretary

//Secretary - secretaries_information
$route['admin/secretaries_information'] = 'AdminSys/Secretary/secretaries_information';

//Secretary - secretary_profile
$route['admin/secretary_profile/(:any)'] = 'AdminSys/Secretary/secretary_profile/$1';
$route['admin/secretary_profile'] = 'AdminSys/Secretary/secretary_profile';

//Secretary - secretary
$route['admin/secretary/(:any)/(:any)/(:any)'] = 'AdminSys/Secretary/secretary/$1/$2/$3';
$route['admin/secretary/(:any)/(:any)'] = 'AdminSys/Secretary/secretary/$1/$2';
$route['admin/secretary/(:any)'] = 'AdminSys/Secretary/secretary/$1';
$route['admin/secretary'] = 'AdminSys/Secretary/secretary';

//Secretary - add_secretary
$route['admin/add_secretary'] = 'AdminSys/Secretary/add_secretary';

//Secretary - edit_secretary
$route['admin/edit_secretary/(:any)'] = 'AdminSys/Secretary/edit_secretary/$1';
$route['admin/edit_secretary'] = 'AdminSys/Secretary/edit_secretary';

//Principal

//Principal - principal_information
$route['admin/principal_information'] = 'AdminSys/Principal/principal_information';

//Principal - principal_profile
$route['admin/principal_profile/(:any)'] = 'AdminSys/Principal/principal_profile/$1';
$route['admin/principal_profile'] = 'AdminSys/Principal/principal_profile';

//Principal - principal
$route['admin/principal/(:any)/(:any)/(:any)'] = 'AdminSys/Principal/principal/$1/$2/$3';
$route['admin/principal/(:any)/(:any)'] = 'AdminSys/Principal/principal/$1/$2';
$route['admin/principal/(:any)'] = 'AdminSys/Principal/principal/$1';
$route['admin/principal'] = 'AdminSys/Principal/principal';

//Principal - add_principal
$route['admin/add_principal'] = 'AdminSys/Principal/add_principal';

//Principal - edit_principal
$route['admin/edit_principal/(:any)'] = 'AdminSys/Principal/edit_principal/$1';
$route['admin/edit_principal'] = 'AdminSys/Principal/edit_principal';

//Enrollment

//Enrollment - re_enrollments
$route['admin/re_enrollments/(:any)'] = 'AdminSys/Enrollment/re_enrollments/$1';
$route['admin/re_enrollments'] = 'AdminSys/Enrollment/re_enrollments';

//Enrollment - pre_enrollments
$route['admin/pre_enrollments'] = 'AdminSys/Enrollment/pre_enrollments';

//Enrollment - preenroll_student
$route['admin/preenroll_student/(:any)/(:any)/(:any)'] = 'AdminSys/Enrollment/preenroll_student/$1/$2/$3';
$route['admin/preenroll_student/(:any)/(:any)'] = 'AdminSys/Enrollment/preenroll_student/$1/$2';
$route['admin/preenroll_student/(:any)'] = 'AdminSys/Enrollment/preenroll_student/$1';
$route['admin/preenroll_student'] = 'AdminSys/Enrollment/preenroll_student';

//Enrollment - re_enrollments_student
$route['admin/re_enrollments_student/(:any)/(:any)/(:any)'] = 'AdminSys/Enrollment/re_enrollments_student/$1/$2/$3';
$route['admin/re_enrollments_student/(:any)/(:any)'] = 'AdminSys/Enrollment/re_enrollments_student/$1/$2';
$route['admin/re_enrollments_student/(:any)'] = 'AdminSys/Enrollment/re_enrollments_student/$1';
$route['admin/re_enrollments_student'] = 'AdminSys/Enrollment/re_enrollments_student';

//Admissions

//Admissions - admissions
$route['admin/admissions'] = 'AdminSys/Admissions/admissions';

//Admissions - admissions_student
$route['admin/admissions_student/(:any)/(:any)/(:any)'] = 'AdminSys/Admissions/admissions_student/$1/$2/$3';
$route['admin/admissions_student/(:any)/(:any)'] = 'AdminSys/Admissions/admissions_student/$1/$2';
$route['admin/admissions_student/(:any)'] = 'AdminSys/Admissions/admissions_student/$1';
$route['admin/admissions_student'] = 'AdminSys/Admissions/admissions_student';

//Admin

// Admin Users - get_admin_users_content
$route['admin/get_admin_users_content'] = 'AdminSys/Admin/get_admin_users_content';

//Admin - admin_profile
$route['admin/admin_profile/(:any)'] = 'AdminSys/Admin/admin_profile/$1';
$route['admin/admin_profile'] = 'AdminSys/Admin/admin_profile';

// Admin - admin_information
$route['admin/admin_information'] = 'AdminSys/Admin/admin_information';

//Guardian

//Guardian - guardian_profile
$route['admin/guardian_profile/(:any)'] = 'AdminSys/Guardian/guardian_profile/$1';
$route['admin/guardian_profile'] = 'AdminSys/Guardian/guardian_profile';

//Guardian - get_guardians
$route['admin/get_guardians'] = 'AdminSys/Guardian/get_guardians';

//Guardian - guardian_add
$route['admin/guardian_add'] = 'AdminSys/Guardian/guardian_add';

//Guardian - guardian
$route['admin/guardian/(:any)/(:any)/(:any)'] = 'AdminSys/Guardian/guardian/$1/$2/$3';
$route['admin/guardian/(:any)/(:any)'] = 'AdminSys/Guardian/guardian/$1/$2';
$route['admin/guardian/(:any)'] = 'AdminSys/Guardian/guardian/$1';
$route['admin/guardian'] = 'AdminSys/Guardian/guardian';

//Guardian - guardian_edit
$route['admin/guardian_edit/(:any)'] = 'AdminSys/Guardian/guardian_edit/$1';
$route['admin/guardian_edit'] = 'AdminSys/Guardian/guardian_edit';

//Guardian - get_guardians_content
$route['admin/get_guardians_content'] = 'AdminSys/Guardian/get_guardians_content';

//Section

// Section - section_add
$route['admin/section_add'] = 'AdminSys/Section/section_add';

//Section - section_profile
$route['admin/section_profile/(:any)'] = 'AdminSys/Section/section_profile/$1';
$route['admin/section_profile'] = 'AdminSys/Section/section_profile';

//Section - section
$route['admin/section/(:any)/(:any)/(:any)'] = 'AdminSys/Section/section/$1/$2/$3';
$route['admin/section/(:any)/(:any)'] = 'AdminSys/Section/section/$1/$2';
$route['admin/section/(:any)'] = 'AdminSys/Section/section/$1';
$route['admin/section'] = 'AdminSys/Section/section';

//Section - sections
$route['admin/sections/(:any)/(:any)'] = 'AdminSys/Section/sections/$1/$2';
$route['admin/sections/(:any)'] = 'AdminSys/Section/sections/$1';
$route['admin/sections'] = 'AdminSys/Section/sections';

//Section - get_class_section
$route['admin/get_class_section/(:any)'] = 'AdminSys/Section/get_class_section/$1';
$route['admin/get_class_section'] = 'AdminSys/Section/get_class_section';

//Section - get_class_all_section
$route['admin/get_class_all_section'] = 'AdminSys/Section/get_class_all_section';

//Section - get_section_content_by_class
$route['admin/get_section_content_by_class/(:any)'] = 'AdminSys/Section/get_section_content_by_class/$1';
$route['admin/get_section_content_by_class'] = 'AdminSys/Section/get_section_content_by_class';

//Section - get_section_content_by_academic_period
$route['admin/get_section_content_by_academic_period/(:any)/(:any)'] = 'AdminSys/Section/get_section_content_by_academic_period/$1/$2';
$route['admin/get_section_content_by_academic_period/(:any)'] = 'AdminSys/Section/get_section_content_by_academic_period/$1';
$route['admin/get_section_content_by_academic_period'] = 'AdminSys/Section/get_section_content_by_academic_period';

//Section - section_routine
$route['admin/section_routine/(:any)/(:any)/(:any)'] = 'AdminSys/Section/section_routine/$1/$2/$3';
$route['admin/section_routine/(:any)/(:any)'] = 'AdminSys/Section/section_routine/$1/$2';
$route['admin/section_routine/(:any)'] = 'AdminSys/Section/section_routine/$1';
$route['admin/section_routine'] = 'AdminSys/Section/section_routine';

//Section - get_section_content
$route['admin/get_section_content'] = 'AdminSys/Section/get_section_content';

//Section - get_section_content2
$route['admin/get_section_content2'] = 'AdminSys/Section/get_section_content2';

//Subject

// Subjects - get_subject_exams
$route['admin/get_subject_exams/(:any)'] = 'AdminSys/Subject/get_subject_exams/$1';
$route['admin/get_subject_exams'] = 'AdminSys/Subject/get_subject_exams';

//Subject - subject_profile
$route['admin/subject_profile/(:any)'] = 'AdminSys/Subject/subject_profile/$1';
$route['admin/subject_profile'] = 'AdminSys/Subject/subject_profile';

//Subject - subjects
$route['admin/subjects/(:any)/(:any)/(:any)'] = 'AdminSys/Subject/subjects/$1/$2/$3';
$route['admin/subjects/(:any)/(:any)'] = 'AdminSys/Subject/subjects/$1/$2';
$route['admin/subjects/(:any)'] = 'AdminSys/Subject/subjects/$1';
$route['admin/subjects'] = 'AdminSys/Subject/subjects';

//Subject - get_class_subject
$route['admin/get_class_subject/(:any)'] = 'AdminSys/Subject/get_class_subject/$1';
$route['admin/get_class_subject'] = 'AdminSys/Subject/get_class_subject';

//Subject - get_section_subjects
$route['admin/get_section_subjects/(:any)'] = 'AdminSys/Subject/get_section_subjects/$1';
$route['admin/get_section_subjects'] = 'AdminSys/Subject/get_section_subjects';

//Subject - manage_subjects
$route['admin/manage_subjects'] = 'AdminSys/Subject/manage_subjects';

//Subject - view_subjects
$route['admin/view_subjects/(:any)/(:any)'] = 'AdminSys/Subject/view_subjects/$1/$2';
$route['admin/view_subjects/(:any)'] = 'AdminSys/Subject/view_subjects/$1';
$route['admin/view_subjects'] = 'AdminSys/Subject/view_subjects';

//Subject - subjects_information
$route['admin/subjects_information/(:any)'] = 'AdminSys/Subject/subjects_information/$1';
$route['admin/subjects_information'] = 'AdminSys/Subject/subjects_information';

//Subject - add_subject
$route['admin/add_subject'] = 'AdminSys/Subject/add_subject';

//Subject - edit_subject
$route['admin/edit_subject/(:any)'] = 'AdminSys/Subject/edit_subject/$1';
$route['admin/edit_subject'] = 'AdminSys/Subject/edit_subject';

//Classes

//Classes - classes
$route['admin/classes/(:any)/(:any)'] = 'AdminSys/Classes/classes/$1/$2';
$route['admin/classes/(:any)'] = 'AdminSys/Classes/classes/$1';
$route['admin/classes'] = 'AdminSys/Classes/classes';

//Classes - get_class_content2
$route['admin/get_class_content2'] = 'AdminSys/Classes/get_class_content2';

//Attendance

//Attendance - attendance_student
$route['admin/attendance_student/(:any)'] = 'AdminSys/Attendance/attendance_student/$1';
$route['admin/attendance_student'] = 'AdminSys/Attendance/attendance_student';

//Attendance - manage_attendance_student
$route['admin/manage_attendance_student/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/manage_attendance_student/$1/$2/$3/$4';
$route['admin/manage_attendance_student/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/manage_attendance_student/$1/$2/$3';
$route['admin/manage_attendance_student/(:any)/(:any)'] = 'AdminSys/Attendance/manage_attendance_student/$1/$2';
$route['admin/manage_attendance_student/(:any)'] = 'AdminSys/Attendance/manage_attendance_student/$1';
$route['admin/manage_attendance_student'] = 'AdminSys/Attendance/manage_attendance_student';

//Attendance - manage_attendance_student_selector
$route['admin/manage_attendance_student_selector'] = 'AdminSys/Attendance/manage_attendance_student_selector';

//Attendance - summary_attendance_student
$route['admin/summary_attendance_student/(:any)'] = 'AdminSys/Attendance/summary_attendance_student/$1';
$route['admin/summary_attendance_student'] = 'AdminSys/Attendance/summary_attendance_student';

//Attendance - filter_attendance
$route['admin/filter_attendance/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2/$3/$4/$5/$6/$7/$8';
$route['admin/filter_attendance/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2/$3/$4/$5/$6/$7';
$route['admin/filter_attendance/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2/$3/$4/$5/$6';
$route['admin/filter_attendance/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2/$3/$4/$5';
$route['admin/filter_attendance/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2/$3/$4';
$route['admin/filter_attendance/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2/$3';
$route['admin/filter_attendance/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2';
$route['admin/filter_attendance/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1';
$route['admin/filter_attendance'] = 'AdminSys/Attendance/filter_attendance';

// Attendance - filter_attendance_student
$route['admin/filter_attendance_student/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_student/$1/$2/$3/$4/$5/$6/$7/$8';
$route['admin/filter_attendance_student/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_student/$1/$2/$3/$4/$5/$6/$7';
$route['admin/filter_attendance_student/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_student/$1/$2/$3/$4/$5/$6';
$route['admin/filter_attendance_student/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_student/$1/$2/$3/$4/$5';
$route['admin/filter_attendance_student/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_student/$1/$2/$3/$4';
$route['admin/filter_attendance_student/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_student/$1/$2/$3';
$route['admin/filter_attendance_student/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_student/$1/$2';
$route['admin/filter_attendance_student/(:any)'] = 'AdminSys/Attendance/filter_attendance_student/$1';
$route['admin/filter_attendance_student'] = 'AdminSys/Attendance/filter_attendance_student';

// Attendance - details_attendance_student
$route['admin/details_attendance_student/(:any)'] = 'AdminSys/Attendance/details_attendance_student/$1';
$route['admin/details_attendance_student'] = 'AdminSys/Attendance/details_attendance_student';

// Attendance - edit_attendance_student
$route['admin/edit_attendance_student/(:any)/(:any)'] = 'AdminSys/Attendance/edit_attendance_student/$1/$2';
$route['admin/edit_attendance_student/(:any)'] = 'AdminSys/Attendance/edit_attendance_student/$1';
$route['admin/edit_attendance_student'] = 'AdminSys/Attendance/edit_attendance_student';

//Mark

//Mark - student_mark_history
$route['admin/student_mark_history/(:any)'] = 'AdminSys/Marks/student_mark_history/$1';
$route['admin/student_mark_history'] = 'AdminSys/Marks/student_mark_history';

// Marks - marks_per_exam
$route['admin/marks_per_exam/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks_per_exam/$1/$2/$3/$4/$5';
$route['admin/marks_per_exam/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks_per_exam/$1/$2/$3/$4';
$route['admin/marks_per_exam/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks_per_exam/$1/$2/$3';
$route['admin/marks_per_exam/(:any)/(:any)'] = 'AdminSys/Marks/marks_per_exam/$1/$2';
$route['admin/marks_per_exam/(:any)'] = 'AdminSys/Marks/marks_per_exam/$1';
$route['admin/marks_per_exam'] = 'AdminSys/Marks/marks_per_exam';

// Marks - student_mark
$route['admin/student_mark/(:any)'] = 'AdminSys/Marks/student_mark/$1';
$route['admin/student_mark'] = 'AdminSys/Marks/student_mark';

// Marks - view_student_mark
$route['admin/view_student_mark/(:any)/(:any)'] = 'AdminSys/Marks/view_student_mark/$1/$2';
$route['admin/view_student_mark/(:any)'] = 'AdminSys/Marks/view_student_mark/$1';
$route['admin/view_student_mark'] = 'AdminSys/Marks/view_student_mark';

// Marks - mark
$route['admin/mark/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark/$1/$2/$3/$4/$5/$6/$7/$8/$9';
$route['admin/mark/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark/$1/$2/$3/$4/$5/$6/$7/$8';
$route['admin/mark/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark/$1/$2/$3/$4/$5/$6/$7';
$route['admin/mark/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark/$1/$2/$3/$4/$5/$6';
$route['admin/mark/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark/$1/$2/$3/$4/$5';
$route['admin/mark/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark/$1/$2/$3/$4';
$route['admin/mark/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark/$1/$2/$3';
$route['admin/mark/(:any)/(:any)'] = 'AdminSys/Marks/mark/$1/$2';
$route['admin/mark/(:any)'] = 'AdminSys/Marks/mark/$1';
$route['admin/mark'] = 'AdminSys/Marks/mark';

// Marks - mark_history
$route['admin/mark_history/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark_history/$1/$2/$3/$4/$5/$6/$7/$8/$9';
$route['admin/mark_history/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark_history/$1/$2/$3/$4/$5/$6/$7/$8';
$route['admin/mark_history/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark_history/$1/$2/$3/$4/$5/$6/$7';
$route['admin/mark_history/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark_history/$1/$2/$3/$4/$5/$6';
$route['admin/mark_history/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark_history/$1/$2/$3/$4/$5';
$route['admin/mark_history/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark_history/$1/$2/$3/$4';
$route['admin/mark_history/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/mark_history/$1/$2/$3';
$route['admin/mark_history/(:any)/(:any)'] = 'AdminSys/Marks/mark_history/$1/$2';
$route['admin/mark_history/(:any)'] = 'AdminSys/Marks/mark_history/$1';
$route['admin/mark_history'] = 'AdminSys/Marks/mark_history';

//Exams

// Exams - manage_exams
$route['admin/manage_exams'] = 'AdminSys/Exams/manage_exams';

//Exams - exam_add
$route['admin/exam_add'] = 'AdminSys/Exams/exam_add';

// Exams - exam_edit
$route['admin/exam_edit/(:any)'] = 'AdminSys/Exams/exam_edit/$1';

// Exams - exams_information
$route['admin/exams_information/(:any)/(:any)'] = 'AdminSys/Exams/exams_information/$1/$2';
$route['admin/exams_information/(:any)'] = 'AdminSys/Exams/exams_information/$1';
$route['admin/exams_information'] = 'AdminSys/Exams/exams_information';

// Exams - exam
$route['admin/exam/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Exams/exam/$1/$2/$3/$4';
$route['admin/exam/(:any)/(:any)/(:any)'] = 'AdminSys/Exams/exam/$1/$2/$3';
$route['admin/exam/(:any)/(:any)'] = 'AdminSys/Exams/exam/$1/$2';
$route['admin/exam/(:any)'] = 'AdminSys/Exams/exam/$1';
$route['admin/exam'] = 'AdminSys/Exams/exam';

// Exams - view_exams
$route['admin/view_exams/(:any)/(:any)/(:any)'] = 'AdminSys/Exams/view_exams/$1/$2/$3';
$route['admin/view_exams/(:any)/(:any)'] = 'AdminSys/Exams/view_exams/$1/$2';
$route['admin/view_exams/(:any)'] = 'AdminSys/Exams/view_exams/$1';
$route['admin/view_exams'] = 'AdminSys/Exams/view_exams';


//Schedules

// Schedules - schedules
$route['admin/schedules/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Schedules/schedules/$1/$2/$3/$4';
$route['admin/schedules/(:any)/(:any)/(:any)'] = 'AdminSys/Schedules/schedules/$1/$2/$3';
$route['admin/schedules/(:any)/(:any)'] = 'AdminSys/Schedules/schedules/$1/$2';
$route['admin/schedules/(:any)'] = 'AdminSys/Schedules/schedules/$1';
$route['admin/schedules'] = 'AdminSys/Schedules/schedules';

// Schedules - add_schedule
$route['admin/add_schedule'] = 'AdminSys/Schedules/add_schedule';

// Schedules - edit_schedule
$route['admin/edit_schedule/(:any)'] = 'AdminSys/Schedules/edit_schedule/$1';
$route['admin/edit_schedule'] = 'AdminSys/Schedules/edit_schedule';


// Schedules - view_schedules
$route['admin/view_schedules/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Schedules/view_schedules/$1/$2/$3/$4';
$route['admin/view_schedules/(:any)/(:any)/(:any)'] = 'AdminSys/Schedules/view_schedules/$1/$2/$3';
$route['admin/view_schedules/(:any)/(:any)'] = 'AdminSys/Schedules/view_schedules/$1/$2';
$route['admin/view_schedules/(:any)'] = 'AdminSys/Schedules/view_schedules/$1';
$route['admin/view_schedules'] = 'AdminSys/Schedules/view_schedules';

// Schedules - manage_schedules
$route['admin/manage_schedules'] = 'AdminSys/Schedules/manage_schedules';

// Schedules - schedules_information
$route['admin/schedules_information/(:any)'] = 'AdminSys/Schedules/schedules_information/$1';
$route['admin/schedules_information'] = 'AdminSys/Schedules/schedules_information';

//News

// News - edit_news
$route['admin/edit_news/(:any)'] = 'AdminSys/News/edit_news/$1';
$route['admin/edit_news'] = 'AdminSys/News/edit_news';

// News - news
$route['admin/news/(:any)/(:any)/(:any)'] = 'AdminSys/News/news/$1/$2/$3';
$route['admin/news/(:any)/(:any)'] = 'AdminSys/News/news/$1/$2';
$route['admin/news/(:any)'] = 'AdminSys/News/news/$1';
$route['admin/news'] = 'AdminSys/News/news';

// News - manage_news
$route['admin/manage_news'] = 'AdminSys/News/manage_news';

// News - view_news
$route['admin/view_news/(:any)'] = 'AdminSys/News/view_news/$1';
$route['admin/view_news'] = 'AdminSys/News/view_news';

// News - add_news
$route['admin/add_news'] = 'AdminSys/News/add_news';

//Tasks

// Tasks - task
$route['admin/task/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Tasks/task/$1/$2/$3/$4';
$route['admin/task/(:any)/(:any)/(:any)'] = 'AdminSys/Tasks/task/$1/$2/$3';
$route['admin/task/(:any)/(:any)'] = 'AdminSys/Tasks/task/$1/$2';
$route['admin/task/(:any)'] = 'AdminSys/Tasks/task/$1';
$route['admin/task'] = 'AdminSys/Tasks/task';

// Tasks - updateTaskProgress
$route['admin/updateTaskProgress/(:any)'] = 'AdminSys/Tasks/updateTaskProgress/$1';
$route['admin/updateTaskProgress'] = 'AdminSys/Tasks/updateTaskProgress';


//Statistics 

// Statistics - statistics
$route['admin/statistics'] = 'AdminSys/Statistics/statistics';

//Message

// Messages - message
$route['admin/message/(:any)/(:any)/(:any)'] = 'AdminSys/Message/message/$1/$2/$3';
$route['admin/message/(:any)/(:any)'] = 'AdminSys/Message/message/$1/$2';
$route['admin/message/(:any)'] = 'AdminSys/Message/message/$1';
$route['admin/message'] = 'AdminSys/Message/message';

// Messages - message_favorite
$route['admin/message_favorite/(:any)/(:any)/(:any)'] = 'AdminSys/Message/message_favorite/$1/$2/$3';
$route['admin/message_favorite/(:any)/(:any)'] = 'AdminSys/Message/message_favorite/$1/$2';
$route['admin/message_favorite/(:any)'] = 'AdminSys/Message/message_favorite/$1';
$route['admin/message_favorite'] = 'AdminSys/Message/message_favorite';

// Messages - message_tag
$route['admin/message_tag/(:any)'] = 'AdminSys/Message/message_tag/$1';
$route['admin/message_tag'] = 'AdminSys/Message/message_tag';

// Messages - message_draft
$route['admin/message_draft/(:any)/(:any)/(:any)'] = 'AdminSys/Message/message_draft/$1/$2/$3';
$route['admin/message_draft/(:any)/(:any)'] = 'AdminSys/Message/message_draft/$1/$2';
$route['admin/message_draft/(:any)'] = 'AdminSys/Message/message_draft/$1';
$route['admin/message_draft'] = 'AdminSys/Message/message_draft';

// Messages - message_trash
$route['admin/message_trash/(:any)/(:any)/(:any)'] = 'AdminSys/Message/message_trash/$1/$2/$3';
$route['admin/message_trash/(:any)/(:any)'] = 'AdminSys/Message/message_trash/$1/$2';
$route['admin/message_trash/(:any)'] = 'AdminSys/Message/message_trash/$1';
$route['admin/message_trash'] = 'AdminSys/Message/message_trash';

// Messages - message_read
$route['admin/message_read/(:any)'] = 'AdminSys/Message/message_read/$1';
$route['admin/message_read'] = 'AdminSys/Message/message_read';

// Message - get_user_details
$route['admin/get_user_details/(:any)/(:any)'] = 'AdminSys/Message/get_user_details/$1/$2';
$route['admin/get_user_details'] = 'AdminSys/Message/get_user_details';

// Message - message_new
$route['admin/message_new/(:any)/(:any)'] = 'AdminSys/Message/message_new/$1/$2';
$route['admin/message_new/(:any)'] = 'AdminSys/Message/message_new/$1';
$route['admin/message_new'] = 'AdminSys/Message/message_new';

// Message - message_sent
$route['admin/message_sent/(:any)/(:any)/(:any)'] = 'AdminSys/Message/message_sent/$1/$2/$3';
$route['admin/message_sent/(:any)/(:any)'] = 'AdminSys/Message/message_sent/$1/$2';
$route['admin/message_sent/(:any)'] = 'AdminSys/Message/message_sent/$1';
$route['admin/message_sent'] = 'AdminSys/Message/message_sent';

// Message - message_settings
$route['admin/message_settings/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Message/message_settings/$1/$2/$3/$4';
$route['admin/message_settings/(:any)/(:any)/(:any)'] = 'AdminSys/Message/message_settings/$1/$2/$3';
$route['admin/message_settings/(:any)/(:any)'] = 'AdminSys/Message/message_settings/$1/$2';
$route['admin/message_settings/(:any)'] = 'AdminSys/Message/message_settings/$1';
$route['admin/message_settings'] = 'AdminSys/Message/message_settings';

//Events

// Events - events
$route['admin/events/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11/$12/$13/$14';
$route['admin/events/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11/$12/$13';
$route['admin/events/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11/$12';
$route['admin/events/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11';
$route['admin/events/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10';
$route['admin/events/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2/$3/$4/$5/$6/$7/$8/$9';
$route['admin/events/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2/$3/$4/$5/$6/$7/$8';
$route['admin/events/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2/$3/$4/$5/$6/$7';
$route['admin/events/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2/$3/$4/$5/$6';
$route['admin/events/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2/$3/$4/$5';
$route['admin/events/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2/$3/$4';
$route['admin/events/(:any)/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2/$3';
$route['admin/events/(:any)/(:any)'] = 'AdminSys/Events/events/$1/$2';
$route['admin/events/(:any)'] = 'AdminSys/Events/events/$1';
$route['admin/events'] = 'AdminSys/Events/events';

//Print

// Print - printReportCardES
$route['admin/printReportCardES/(:any)/(:any)'] = 'AdminSys/PrintT/printReportCardES/$1/$2';
$route['admin/printReportCardES'] = 'AdminSys/PrintT/printReportCardES';

// Print - printStudentTableES
$route['admin/printStudentTableES/(:any)'] = 'AdminSys/PrintT/printStudentTableES/$1';
$route['admin/printStudentTableES'] = 'AdminSys/PrintT/printStudentTableES';

// Print - printStudentTableEN
$route['admin/printStudentTableEN/(:any)'] = 'AdminSys/PrintT/printStudentTableEN/$1';
$route['admin/printStudentTableEN'] = 'AdminSys/PrintT/printStudentTableEN';

// Print - printAllStudentTableES
$route['admin/printAllStudentTableES'] = 'AdminSys/PrintT/printAllStudentTableES';

// Print - printAllStudentTableEN
$route['admin/printAllStudentTableEN'] = 'AdminSys/PrintT/printAllStudentTableEN';

// Print - printClassStudentTableES
$route['admin/printClassStudentTableES/(:any)'] = 'AdminSys/PrintT/printClassStudentTableES/$1';

// Print - printClassStudentTableEN
$route['admin/printClassStudentTableEN/(:any)'] = 'AdminSys/PrintT/printClassStudentTableEN/$1';

// Print - exportStudentTableExcelES
$route['admin/exportStudentTableExcelES'] = 'AdminSys/PrintT/exportStudentTableExcelES';

// Print - exportStudentTableExcelEN
$route['admin/exportStudentTableExcelEN'] = 'AdminSys/PrintT/exportStudentTableExcelEN';

// PrintT - exportClassStudentTableExcelES
$route['admin/exportClassStudentTableExcelES/(:any)'] = 'AdminSys/PrintT/exportClassStudentTableExcelES/$1';

// PrintT - printStudentAdmissionsTableES
$route['admin/printStudentAdmissionsTableES'] = 'AdminSys/PrintT/printStudentAdmissionsTableES';

// PrintT - printStudentAdmissionsTableEN
$route['admin/printStudentAdmissionsTableEN'] = 'AdminSys/PrintT/printStudentAdmissionsTableEN';

// PrintT - printStudentPreEnrollmentsTableES
$route['admin/printStudentPreEnrollmentsTableES'] = 'AdminSys/PrintT/printStudentPreEnrollmentsTableES';

// PrintT - printStudentPreEnrollmentsTableEN
$route['admin/printStudentPreEnrollmentsTableEN'] = 'AdminSys/PrintT/printStudentPreEnrollmentsTableEN';

//UserSys

// UsersSys - manage_profile
$route['admin/manage_profile/(:any)'] = 'AdminSys/UsersSys/manage_profile/$1';
$route['admin/manage_profile'] = 'AdminSys/UsersSys/manage_profile';

// UsersSys - help
$route['admin/help'] = 'AdminSys/UsersSys/help';

// UsersSys - get_postalcode_localidad
$route['admin/get_postalcode_localidad/(:any)'] = 'AdminSys/UsersSys/get_postalcode_localidad/$1';

// UsersSys - get_postal_codes
$route['admin/get_postal_codes'] = 'AdminSys/UsersSys/get_postal_codes';

// UsersSys - profile_settings
$route['admin/profile_settings/(:any)/(:any)/(:any)'] = 'AdminSys/UsersSys/profile_settings/$1/$2/$3';
$route['admin/profile_settings/(:any)/(:any)'] = 'AdminSys/UsersSys/profile_settings/$1/$2';
$route['admin/profile_settings/(:any)'] = 'AdminSys/UsersSys/profile_settings/$1';
$route['admin/profile_settings'] = 'AdminSys/UsersSys/profile_settings';

// UsersSys - change_theme
$route['admin/change_theme/(:any)/(:any)'] = 'AdminSys/UsersSys/change_theme/$1/$2';
$route['admin/change_theme/(:any)'] = 'AdminSys/UsersSys/change_theme/$1';
$route['admin/change_theme'] = 'AdminSys/UsersSys/change_theme';

// UsersSys - change_language
$route['admin/change_language/(:any)/(:any)'] = 'AdminSys/UsersSys/change_language/$1/$2';
$route['admin/change_language/(:any)'] = 'AdminSys/UsersSys/change_language/$1';
$route['admin/change_language'] = 'AdminSys/UsersSys/change_language';

// UsersSys - reset_page_tracking
$route['admin/reset_page_tracking/(:any)/(:any)'] = 'AdminSys/UsersSys/reset_page_tracking/$1/$2';
$route['admin/reset_page_tracking/(:any)'] = 'AdminSys/UsersSys/reset_page_tracking/$1';
$route['admin/reset_page_tracking'] = 'AdminSys/UsersSys/reset_page_tracking';

// UsersSys - language_settings
$route['admin/language_settings/(:any)/(:any)/(:any)'] = 'AdminSys/UsersSys/language_settings/$1/$2/$3';
$route['admin/language_settings/(:any)/(:any)'] = 'AdminSys/UsersSys/language_settings/$1/$2';
$route['admin/language_settings/(:any)'] = 'AdminSys/UsersSys/language_settings/$1';
$route['admin/language_settings'] = 'AdminSys/UsersSys/language_settings';

// UsersSys - get_all_users
$route['admin/get_all_users'] = 'AdminSys/UsersSys/get_all_users';

// UsersSys - get_all_users2
$route['admin/get_all_users2'] = 'AdminSys/UsersSys/get_all_users2';

// UsersSys - get_all_users3
$route['admin/get_all_users3'] = 'AdminSys/UsersSys/get_all_users3';

// UsersSys - get_users
$route['admin/get_users/(:any)/(:any)'] = 'AdminSys/UsersSys/get_users/$1/$2';
$route['admin/get_users/(:any)'] = 'AdminSys/UsersSys/get_users/$1';
$route['admin/get_users'] = 'AdminSys/UsersSys/get_users';
