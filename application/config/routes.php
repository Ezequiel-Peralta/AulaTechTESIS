<?php
if (!defined('BASEPATH')) {
    echo 'Archivo accedido directamente: ' . __FILE__;
    exit('No direct script access allowed');
}


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
|	Examsple.com/class/method/id/
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
| URI contains no data. In the above Examsple, the "welcome" class
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
| Examsples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//Dashboard Routes
$route['admin/dashboard'] = 'AdminSys/Dashboard';

// Students Routes

//Students - students add
$route['admin/students_add'] = 'AdminSys/Students/students_add';

//Students - students bulk add
$route['admin/students_bulk_add/(:any)'] = 'AdminSys/Students/students_bulk_add/$1';

//Students - students information
$route['admin/students_information/(:any)'] = 'AdminSys/Students/students_information/$1';

//Students - students profile
$route['admin/students_profile/(:any)'] = 'AdminSys/Students/students_profile/$1';

//Students - students
$route['admin/students/(:any)/(:any)/(:any)'] = 'AdminSys/Students/students/$1/$2/$3';
$route['admin/students/(:any)/(:any)'] = 'AdminSys/Students/students/$1/$2';
$route['admin/students/(:any)'] = 'AdminSys/Students/students/$1';

//Students - manage students
$route['admin/manage_students'] = 'AdminSys/Students/manage_students';

//Students - students edit
$route['admin/students_edit/(:any)'] = 'AdminSys/Students/students_edit/$1';

//Students - get_students_content
$route['admin/get_students_content'] = 'AdminSys/Students/get_students_content';

//Students - get_students_content_by_sections
$route['admin/get_students_content_by_sections/(:any)'] = 'AdminSys/Students/get_students_content_by_sections/$1';

//Students - get_all_students
$route['admin/get_all_students'] = 'AdminSys/Students/get_all_students';

//Academic Routes

//Academic - academic period
$route['admin/academic_period/(:any)/(:any)'] = 'AdminSys/Academic/academic_period/$1/$2';
$route['admin/academic_period/(:any)'] = 'AdminSys/Academic/academic_period/$1';

//Academic - academic history
$route['admin/academic_history/(:any)'] = 'AdminSys/Academic/academic_history/$1';

//Academic - academic period add
$route['admin/academic_period_add'] = 'AdminSys/Academic/academic_period_add';

//Academic - manage academic history
$route['admin/manage_academic_history'] = 'AdminSys/Academic/manage_academic_history';

//Academic - view_students_academic_history
$route['admin/view_students_academic_history/(:any)'] = 'AdminSys/Academic/view_students_academic_history/$1';

//Library Routes

//Library - add_library
$route['admin/add_library'] = 'AdminSys/Library/add_library';

//Library - library
$route['admin/library/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Library/library/$1/$2/$3/$4';
$route['admin/library/(:any)/(:any)/(:any)'] = 'AdminSys/Library/library/$1/$2/$3';
$route['admin/library/(:any)/(:any)'] = 'AdminSys/Library/library/$1/$2';
$route['admin/library/(:any)'] = 'AdminSys/Library/library/$1';

//Library - edit_library
$route['admin/edit_library/(:any)'] = 'AdminSys/Library/edit_library/$1';

//Library - view_library
$route['admin/view_library/(:any)/(:any)'] = 'AdminSys/Library/view_library/$1/$2';
$route['admin/view_library/(:any)'] = 'AdminSys/Library/view_library/$1';

//Library - manage_library
$route['admin/manage_library'] = 'AdminSys/Library/manage_library';


//Library - library_information
$route['admin/library_information/(:any)'] = 'AdminSys/Library/library_information/$1';

//Behaviors Routes

//Behaviors - add_behaviors
$route['admin/add_behaviors/(:any)/(:any)/(:any)'] = 'AdminSys/Behaviors/add_behaviors/$1/$2/$3';
$route['admin/add_behaviors/(:any)/(:any)'] = 'AdminSys/Behaviors/add_behaviors/$1/$2';
$route['admin/add_behaviors/(:any)'] = 'AdminSys/Behaviors/add_behaviors/$1';

//Behaviors - edit_behaviors
$route['admin/edit_behaviors/(:any)'] = 'AdminSys/Behaviors/edit_behaviors/$1';

//Behaviors - behaviors
$route['admin/behaviors/(:any)'] = 'AdminSys/Behaviors/behaviors/$1';

//Behaviors - behaviors_information
$route['admin/behaviors_information/(:any)/(:any)/(:any)'] = 'AdminSys/Behaviors/behaviors_information/$1/$2/$3';
$route['admin/behaviors_information/(:any)/(:any)'] = 'AdminSys/Behaviors/behaviors_information/$1/$2';
$route['admin/behaviors_information/(:any)'] = 'AdminSys/Behaviors/behaviors_information/$1';

//Behaviors - students_behaviors
$route['admin/students_behaviors/(:any)'] = 'AdminSys/Behaviors/students_behaviors/$1';

//Behaviors - manage_behaviors
$route['admin/manage_behaviors'] = 'AdminSys/Behaviors/manage_behaviors';

//Teachers Routes

//Teachers - teachers_information
$route['admin/teachers_information'] = 'AdminSys/Teachers/teachers_information';

//Teachers - teachers_profile
$route['admin/teachers_profile/(:any)'] = 'AdminSys/Teachers/teachers_profile/$1';

//Teachers - teachers
$route['admin/teachers/(:any)/(:any)/(:any)'] = 'AdminSys/Teachers/teachers/$1/$2/$3';
$route['admin/teachers/(:any)/(:any)'] = 'AdminSys/Teachers/teachers/$1/$2';
$route['admin/teachers/(:any)'] = 'AdminSys/Teachers/teachers/$1';

//Teachers - add_teachers
$route['admin/add_teacher'] = 'AdminSys/Teachers/add_teacher';

//Teachers - get_teachers
$route['admin/get_teachers'] = 'AdminSys/Teachers/get_teachers';

//Teachers - edit_teachers
$route['admin/edit_teachers/(:any)'] = 'AdminSys/Teachers/edit_teachers/$1';

//TeachersAide Routes

//TeachersAide - teachers_aide
$route['admin/teachers_aide/(:any)/(:any)/(:any)'] = 'AdminSys/TeachersAide/teachers_aide/$1/$2/$3';
$route['admin/teachers_aide/(:any)/(:any)'] = 'AdminSys/Teachers/teachers_aide/$1/$2';
$route['admin/teachers_aide/(:any)'] = 'AdminSys/Teachers/teachers_aide/$1';

//TeachersAide - teachers_aide_information
$route['admin/teachers_aide_information'] = 'AdminSys/TeachersAide/teachers_aide_information';

//TeachersAide - teachers_aide_profile
$route['admin/teachers_aide_profile/(:any)'] = 'AdminSys/TeachersAide/teachers_aide_profile/$1';

//TeachersAide - teachers_aide_add
$route['admin/teachers_aide_add'] = 'AdminSys/TeachersAide/teachers_aide_add';

//TeachersAide - add_teachers_aide
$route['admin/add_teachers_aide'] = 'AdminSys/TeachersAide/add_teachers_aide';

//TeachersAide - edit_teachers_aide
$route['admin/edit_teachers_aide/(:any)'] = 'AdminSys/TeachersAide/edit_teachers_aide/$1';

//TeachersAide - teachersAide_edit
$route['admin/teachersAide_edit/(:any)'] = 'AdminSys/TeachersAide/teachersAide_edit/$1';

//Secretaries Routes

//Secretaries - secretaries_information
$route['admin/secretaries_information'] = 'AdminSys/Secretaries/secretaries_information';

//Secretaries - secretaries_profile
$route['admin/secretaries_profile/(:any)'] = 'AdminSys/Secretaries/secretaries_profile/$1';

//Secretaries - secretaries
$route['admin/secretaries/(:any)/(:any)/(:any)'] = 'AdminSys/Secretaries/secretaries/$1/$2/$3';
$route['admin/secretaries/(:any)/(:any)'] = 'AdminSys/Secretaries/secretaries/$1/$2';
$route['admin/secretaries/(:any)'] = 'AdminSys/Secretaries/secretaries/$1';

//Secretaries - add_secretaries
$route['admin/add_secretaries'] = 'AdminSys/Secretaries/add_secretaries';

//Secretaries - edit_secretaries
$route['admin/edit_secretaries/(:any)'] = 'AdminSys/Secretaries/edit_secretaries/$1';

//Principal Routes

//Principal - principal_information
$route['admin/principal_information'] = 'AdminSys/Principal/principal_information';

//Principal - principal_profile
$route['admin/principal_profile/(:any)'] = 'AdminSys/Principal/principal_profile/$1';

//Principal - principal
$route['admin/principal/(:any)/(:any)/(:any)'] = 'AdminSys/Principal/principal/$1/$2/$3';
$route['admin/principal/(:any)/(:any)'] = 'AdminSys/Principal/principal/$1/$2';
$route['admin/principal/(:any)'] = 'AdminSys/Principal/principal/$1';

//Principal - add_principal
$route['admin/add_principal'] = 'AdminSys/Principal/add_principal';

//Principal - edit_principal
$route['admin/edit_principal/(:any)'] = 'AdminSys/Principal/edit_principal/$1';

//Enrollment Routes

//Enrollment - re_enrollments
$route['admin/re_enrollments/(:any)'] = 'AdminSys/Enrollment/re_enrollments/$1';

//Enrollment - pre_enrollments
$route['admin/pre_enrollments'] = 'AdminSys/Enrollment/pre_enrollments';

//Enrollment - preenroll_students
$route['admin/preenroll_students/(:any)/(:any)/(:any)'] = 'AdminSys/Enrollment/preenroll_students/$1/$2/$3';
$route['admin/preenroll_students/(:any)/(:any)'] = 'AdminSys/Enrollment/preenroll_students/$1/$2';
$route['admin/preenroll_students/(:any)'] = 'AdminSys/Enrollment/preenroll_students/$1';

//Enrollment - re_enrollments_students
$route['admin/re_enrollments_students/(:any)/(:any)/(:any)'] = 'AdminSys/Enrollment/re_enrollments_students/$1/$2/$3';
$route['admin/re_enrollments_students/(:any)/(:any)'] = 'AdminSys/Enrollment/re_enrollments_students/$1/$2';
$route['admin/re_enrollments_students/(:any)'] = 'AdminSys/Enrollment/re_enrollments_students/$1';

//Admissions Routes

//Admissions - admissions
$route['admin/admissions'] = 'AdminSys/Admissions/admissions';

//Admissions - admissions_students
$route['admin/admissions_students/(:any)/(:any)/(:any)'] = 'AdminSys/Admissions/admissions_students/$1/$2/$3';
$route['admin/admissions_students/(:any)/(:any)'] = 'AdminSys/Admissions/admissions_students/$1/$2';
$route['admin/admissions_students/(:any)'] = 'AdminSys/Admissions/admissions_students/$1';

//Admin Routes

// Admin Users - get_Admin_users_content
$route['admin/get_Admin_users_content'] = 'AdminSys/admin/get_Admin_users_content';

//Admin - Admin_profile
$route['admin/Admin_profile/(:any)'] = 'AdminSys/admin/Admin_profile/$1';

// Admin - Admin_information
$route['admin/Admin_information'] = 'AdminSys/admin/Admin_information';

//Guardians Routes

//Guardians - guardians_profile
$route['admin/guardians_profile/(:any)'] = 'AdminSys/Guardians/guardians_profile/$1';

//Guardians - get_guardians
$route['admin/get_guardians'] = 'AdminSys/Guardians/get_guardians';

//Guardians - guardians_add
$route['admin/guardians_add'] = 'AdminSys/Guardians/guardians_add';

//Guardians - guardians
$route['admin/guardians/(:any)/(:any)/(:any)'] = 'AdminSys/Guardians/guardians/$1/$2/$3';
$route['admin/guardians/(:any)/(:any)'] = 'AdminSys/Guardians/guardians/$1/$2';
$route['admin/guardians/(:any)'] = 'AdminSys/Guardians/guardians/$1';

//Guardians - guardians_edit
$route['admin/guardians_edit/(:any)'] = 'AdminSys/Guardians/guardians_edit/$1';

//Guardians - get_guardians_content
$route['admin/get_guardians_content'] = 'AdminSys/Guardians/get_guardians_content';

//Sections Routes

// Sections - sections_add
$route['admin/sections_add'] = 'AdminSys/Sections/sections_add';

//Sections - sections_profile
$route['admin/sections_profile/(:any)'] = 'AdminSys/Sections/sections_profile/$1';

//Sections - sections
$route['admin/sections/(:any)/(:any)/(:any)'] = 'AdminSys/Sections/sections/$1/$2/$3';
$route['admin/sections/(:any)/(:any)'] = 'AdminSys/Sections/sections/$1/$2';
$route['admin/sections/(:any)'] = 'AdminSys/Sections/sections/$1';

//Sections - sections
$route['admin/sections/(:any)/(:any)'] = 'AdminSys/Sections/sections/$1/$2';
$route['admin/sections/(:any)'] = 'AdminSys/Sections/sections/$1';

//Sections - get_class_sections
$route['admin/get_class_sections/(:any)'] = 'AdminSys/Sections/get_class_sections/$1';

//Sections - get_class_all_sections
$route['admin/get_class_all_sections'] = 'AdminSys/Sections/get_class_all_sections';

//Sections - get_sections_content_by_class
$route['admin/get_sections_content_by_class/(:any)'] = 'AdminSys/Sections/get_sections_content_by_class/$1';

//Sections - get_sections_content_by_academic_period
$route['admin/get_sections_content_by_academic_period/(:any)/(:any)'] = 'AdminSys/Sections/get_sections_content_by_academic_period/$1/$2';
$route['admin/get_sections_content_by_academic_period/(:any)'] = 'AdminSys/Sections/get_sections_content_by_academic_period/$1';

//Sections - sections_routine
$route['admin/sections_routine/(:any)/(:any)/(:any)'] = 'AdminSys/Sections/sections_routine/$1/$2/$3';
$route['admin/sections_routine/(:any)/(:any)'] = 'AdminSys/Sections/sections_routine/$1/$2';
$route['admin/sections_routine/(:any)'] = 'AdminSys/Sections/sections_routine/$1';

//Sections - get_sections_content
$route['admin/get_sections_content'] = 'AdminSys/Sections/get_sections_content';

//Sections - get_sections_content2
$route['admin/get_sections_content2'] = 'AdminSys/Sections/get_sections_content2';

//Subjects Routes

// Subjects - get_subjects_Exams
$route['admin/get_subjects_Exams/(:any)'] = 'AdminSys/Subjects/get_subjects_Exams/$1';

//Subjects - subjects_profile
$route['admin/subjects_profile/(:any)'] = 'AdminSys/Subjects/subjects_profile/$1';

//Subjects - subjects
$route['admin/subjects/(:any)/(:any)/(:any)'] = 'AdminSys/Subjects/subjects/$1/$2/$3';
$route['admin/subjects/(:any)/(:any)'] = 'AdminSys/Subjects/subjects/$1/$2';
$route['admin/subjects/(:any)'] = 'AdminSys/Subjects/subjects/$1';

//Subjects - get_class_subjects
$route['admin/get_class_subjects/(:any)'] = 'AdminSys/Subjects/get_class_subjects/$1';

//Subjects - get_sections_subjects
$route['admin/get_sections_subjects/(:any)'] = 'AdminSys/Subjects/get_sections_subjects/$1';

//Subjects - manage_subjects
$route['admin/manage_subjects'] = 'AdminSys/Subjects/manage_subjects';

//Subjects - view_subjects
$route['admin/view_subjects/(:any)/(:any)'] = 'AdminSys/Subjects/view_subjects/$1/$2';
$route['admin/view_subjects/(:any)'] = 'AdminSys/Subjects/view_subjects/$1';

//Subjects - subjects_information
$route['admin/subjects_information/(:any)'] = 'AdminSys/Subjects/subjects_information/$1';

//Subjects - add_subjects
$route['admin/add_subjects'] = 'AdminSys/Subjects/add_subjects';

//Subjects - edit_subjects
$route['admin/edit_subjects/(:any)'] = 'AdminSys/Subjects/edit_subjects/$1';

//Classes Routes

//Classes - classes
$route['admin/classes/(:any)/(:any)'] = 'AdminSys/Classes/classes/$1/$2';
$route['admin/classes/(:any)'] = 'AdminSys/Classes/classes/$1';

//Classes - get_class_content2
$route['admin/get_class_content2'] = 'AdminSys/Classes/get_class_content2';

//Attendance Routes

//Attendance - attendance_students
$route['admin/attendance_students/(:any)'] = 'AdminSys/Attendance/attendance_students/$1';

//Attendance - manage_attendance_students
$route['admin/manage_attendance_students/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/manage_attendance_students/$1/$2/$3/$4';
$route['admin/manage_attendance_students/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/manage_attendance_students/$1/$2/$3';
$route['admin/manage_attendance_students/(:any)/(:any)'] = 'AdminSys/Attendance/manage_attendance_students/$1/$2';
$route['admin/manage_attendance_students/(:any)'] = 'AdminSys/Attendance/manage_attendance_students/$1';

//Attendance - manage_attendance_students_selector
$route['admin/manage_attendance_students_selector'] = 'AdminSys/Attendance/manage_attendance_students_selector';

//Attendance - summary_attendance_students
$route['admin/summary_attendance_students/(:any)'] = 'AdminSys/Attendance/summary_attendance_students/$1';

//Attendance - filter_attendance
$route['admin/filter_attendance/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2/$3/$4/$5/$6/$7/$8';
$route['admin/filter_attendance/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2/$3/$4/$5/$6/$7';
$route['admin/filter_attendance/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2/$3/$4/$5/$6';
$route['admin/filter_attendance/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2/$3/$4/$5';
$route['admin/filter_attendance/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2/$3/$4';
$route['admin/filter_attendance/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2/$3';
$route['admin/filter_attendance/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1/$2';
$route['admin/filter_attendance/(:any)'] = 'AdminSys/Attendance/filter_attendance/$1';

// Attendance - filter_attendance_students
$route['admin/filter_attendance_students/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_students/$1/$2/$3/$4/$5/$6/$7/$8';
$route['admin/filter_attendance_students/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_students/$1/$2/$3/$4/$5/$6/$7';
$route['admin/filter_attendance_students/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_students/$1/$2/$3/$4/$5/$6';
$route['admin/filter_attendance_students/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_students/$1/$2/$3/$4/$5';
$route['admin/filter_attendance_students/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_students/$1/$2/$3/$4';
$route['admin/filter_attendance_students/(:any)/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_students/$1/$2/$3';
$route['admin/filter_attendance_students/(:any)/(:any)'] = 'AdminSys/Attendance/filter_attendance_students/$1/$2';
$route['admin/filter_attendance_students/(:any)'] = 'AdminSys/Attendance/filter_attendance_students/$1';

// Attendance - details_attendance_students
$route['admin/details_attendance_students/(:any)'] = 'AdminSys/Attendance/details_attendance_students/$1';

// Attendance - edit_attendance_students
$route['admin/edit_attendance_students/(:any)/(:any)'] = 'AdminSys/Attendance/edit_attendance_students/$1/$2';
$route['admin/edit_attendance_students/(:any)'] = 'AdminSys/Attendance/edit_attendance_students/$1';

//Marks Routes

//Marks - students_mark_history
$route['admin/students_mark_history/(:any)'] = 'AdminSys/Marks/students_mark_history/$1';

// Marks - marks_per_Exams
$route['admin/marks_per_Exams/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks_per_Exams/$1/$2/$3/$4/$5';
$route['admin/marks_per_Exams/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks_per_Exams/$1/$2/$3/$4';
$route['admin/marks_per_Exams/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks_per_Exams/$1/$2/$3';
$route['admin/marks_per_Exams/(:any)/(:any)'] = 'AdminSys/Marks/marks_per_Exams/$1/$2';
$route['admin/marks_per_Exams/(:any)'] = 'AdminSys/Marks/marks_per_Exams/$1';

// Marks - students_mark
$route['admin/students_mark/(:any)'] = 'AdminSys/Marks/students_mark/$1';

// Marks - view_students_mark
$route['admin/view_students_mark/(:any)/(:any)'] = 'AdminSys/Marks/view_students_mark/$1/$2';
$route['admin/view_students_mark/(:any)'] = 'AdminSys/Marks/view_students_mark/$1';

// Marks - marks
$route['admin/marks/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks/$1/$2/$3/$4/$5/$6/$7/$8/$9';
$route['admin/marks/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks/$1/$2/$3/$4/$5/$6/$7/$8';
$route['admin/marks/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks/$1/$2/$3/$4/$5/$6/$7';
$route['admin/marks/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks/$1/$2/$3/$4/$5/$6';
$route['admin/marks/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks/$1/$2/$3/$4/$5';
$route['admin/marks/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks/$1/$2/$3/$4';
$route['admin/marks/(:any)/(:any)/(:any)'] = 'AdminSys/Marks/marks/$1/$2/$3';
$route['admin/marks/(:any)/(:any)'] = 'AdminSys/Marks/marks/$1/$2';
$route['admin/marks/(:any)'] = 'AdminSys/Marks/marks/$1';

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

//Exams Routes

// Exams - manage_Exams
$route['admin/manage_Exams'] = 'AdminSys/Exams/manage_Exams';

//Exams - Exams_add
$route['admin/Exams_add'] = 'AdminSys/Exams/Exams_add';

// Exams - Exams_edit
$route['admin/Exams_edit/(:any)'] = 'AdminSys/Exams/Exams_edit/$1';

// Exams - Exams_information
$route['admin/Exams_information/(:any)/(:any)'] = 'AdminSys/Exams/Exams_information/$1/$2';
$route['admin/Exams_information/(:any)'] = 'AdminSys/Exams/Exams_information/$1';

// Exams - Exams
$route['admin/Exams/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Exams/Exams/$1/$2/$3/$4';
$route['admin/Exams/(:any)/(:any)/(:any)'] = 'AdminSys/Exams/Exams/$1/$2/$3';
$route['admin/Exams/(:any)/(:any)'] = 'AdminSys/Exams/Exams/$1/$2';
$route['admin/Exams/(:any)'] = 'AdminSys/Exams/Exams/$1';

// Exams - view_Exams
$route['admin/view_Exams/(:any)/(:any)/(:any)'] = 'AdminSys/Exams/view_Exams/$1/$2/$3';
$route['admin/view_Exams/(:any)/(:any)'] = 'AdminSys/Exams/view_Exams/$1/$2';
$route['admin/view_Exams/(:any)'] = 'AdminSys/Exams/view_Exams/$1';


//Schedules Routes

// Schedules - schedules
$route['admin/schedules/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Schedules/schedules/$1/$2/$3/$4';
$route['admin/schedules/(:any)/(:any)/(:any)'] = 'AdminSys/Schedules/schedules/$1/$2/$3';
$route['admin/schedules/(:any)/(:any)'] = 'AdminSys/Schedules/schedules/$1/$2';
$route['admin/schedules/(:any)'] = 'AdminSys/Schedules/schedules/$1';

// Schedules - add_schedules
$route['admin/add_schedules'] = 'AdminSys/Schedules/add_schedules';

// Schedules - edit_schedules
$route['admin/edit_schedules/(:any)'] = 'AdminSys/Schedules/edit_schedules/$1';


// Schedules - view_schedules
$route['admin/view_schedules/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Schedules/view_schedules/$1/$2/$3/$4';
$route['admin/view_schedules/(:any)/(:any)/(:any)'] = 'AdminSys/Schedules/view_schedules/$1/$2/$3';
$route['admin/view_schedules/(:any)/(:any)'] = 'AdminSys/Schedules/view_schedules/$1/$2';
$route['admin/view_schedules/(:any)'] = 'AdminSys/Schedules/view_schedules/$1';

// Schedules - manage_schedules
$route['admin/manage_schedules'] = 'AdminSys/Schedules/manage_schedules';

// Schedules - schedules_information
$route['admin/schedules_information/(:any)'] = 'AdminSys/Schedules/schedules_information/$1';

//News Routes

// News - edit_news
$route['admin/edit_news/(:any)'] = 'AdminSys/News/edit_news/$1';

// News - news
$route['admin/news/(:any)/(:any)/(:any)'] = 'AdminSys/News/news/$1/$2/$3';
$route['admin/news/(:any)/(:any)'] = 'AdminSys/News/news/$1/$2';
$route['admin/news/(:any)'] = 'AdminSys/News/news/$1';

// News - manage_news
$route['admin/manage_news'] = 'AdminSys/News/manage_news';

// News - view_news
$route['admin/view_news/(:any)'] = 'AdminSys/News/view_news/$1';

// News - add_news
$route['admin/add_news'] = 'AdminSys/News/add_news';

//Tasks Routes

// Tasks - tasks
$route['admin/tasks(/:any)?(/:any)?(/:any)?(/:any)?'] = 'AdminSys/Tasks/tasks$1$2$3$4';


// Tasks - updateTasksProgress
$route['admin/updateTasksProgress/(:any)'] = 'AdminSys/Tasks/updateTasksProgress/$1';


//Statistics Routes  

// Statistics - statistics
$route['admin/statistics'] = 'AdminSys/Statistics/statistics';

//Messages Routes

// Messages - messages
$route['admin/messages(/:any)?(/:any)?(/:any)?'] = 'AdminSys/Messages/message$1$2$3';

// Messages - messages_favorite
$route['admin/messages_favorite/(:any)/(:any)/(:any)'] = 'AdminSys/Messages/message_favorite/$1/$2/$3';
$route['admin/messages_favorite/(:any)/(:any)'] = 'AdminSys/Messages/message_favorite/$1/$2';
$route['admin/messages_favorite/(:any)'] = 'AdminSys/Messages/message_favorite/$1';

// Messages - messages_tag
$route['admin/messages_tag/(:any)'] = 'AdminSys/Messages/message_tag/$1';

// Messages - messages_draft
$route['admin/messages_draft/(:any)/(:any)/(:any)'] = 'AdminSys/Messages/message_draft/$1/$2/$3';
$route['admin/messages_draft/(:any)/(:any)'] = 'AdminSys/Messages/message_draft/$1/$2';
$route['admin/messages_draft/(:any)'] = 'AdminSys/Messages/message_draft/$1';

// Messages - messages_trash
$route['admin/messages_trash/(:any)/(:any)/(:any)'] = 'AdminSys/Messages/message_trash/$1/$2/$3';
$route['admin/messages_trash/(:any)/(:any)'] = 'AdminSys/Messages/message_trash/$1/$2';
$route['admin/messages_trash/(:any)'] = 'AdminSys/Messages/message_trash/$1';

// Messages - messages_read
$route['admin/messages_read/(:any)'] = 'AdminSys/Messages/message_read/$1';

// Messages - get_user_details
$route['admin/get_user_details/(:any)/(:any)'] = 'AdminSys/Messages/get_user_details/$1/$2';
$route['admin/get_user_details'] = 'AdminSys/Messages/get_user_details';

// Messages - messages_news
$route['admin/messages_news/(:any)/(:any)'] = 'AdminSys/Messages/message_news/$1/$2';
$route['admin/messages_news/(:any)'] = 'AdminSys/Messages/message_news/$1';

// Messages - messages_sent
$route['admin/messages_sent/(:any)/(:any)/(:any)'] = 'AdminSys/Messages/message_sent/$1/$2/$3';
$route['admin/messages_sent/(:any)/(:any)'] = 'AdminSys/Messages/message_sent/$1/$2';
$route['admin/messages_sent/(:any)'] = 'AdminSys/Messages/message_sent/$1';

// Messages - messages_settings
$route['admin/messages_settings/(:any)/(:any)/(:any)/(:any)'] = 'AdminSys/Messages/message_settings/$1/$2/$3/$4';
$route['admin/messages_settings/(:any)/(:any)/(:any)'] = 'AdminSys/Messages/message_settings/$1/$2/$3';
$route['admin/messages_settings/(:any)/(:any)'] = 'AdminSys/Messages/message_settings/$1/$2';
$route['admin/messages_settings/(:any)'] = 'AdminSys/Messages/message_settings/$1';

//Events Routes

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

//Print Routes

// Print - printReportCardES
$route['admin/printReportCardES/(:any)/(:any)'] = 'AdminSys/PrintT/printReportCardES/$1/$2';
$route['admin/printReportCardES'] = 'AdminSys/PrintT/printReportCardES';

// Print - printStudentsTableES
$route['admin/printStudentsTableES/(:any)'] = 'AdminSys/PrintT/printStudentsTableES/$1';

// Print - printStudentsTableEN
$route['admin/printStudentsTableEN/(:any)'] = 'AdminSys/PrintT/printStudentsTableEN/$1';

// Print - printAllStudentsTableES
$route['admin/printAllStudentsTableES'] = 'AdminSys/PrintT/printAllStudentsTableES';

// Print - printAllStudentsTableEN
$route['admin/printAllStudentsTableEN'] = 'AdminSys/PrintT/printAllStudentsTableEN';

// Print - printClassStudentsTableES
$route['admin/printClassStudentsTableES/(:any)'] = 'AdminSys/PrintT/printClassStudentsTableES/$1';

// Print - printClassStudentsTableEN
$route['admin/printClassStudentsTableEN/(:any)'] = 'AdminSys/PrintT/printClassStudentsTableEN/$1';

// Print - exportStudentsTableExcelES
$route['admin/exportStudentsTableExcelES'] = 'AdminSys/PrintT/exportStudentsTableExcelES';

// Print - exportStudentsTableExcelEN
$route['admin/exportStudentsTableExcelEN'] = 'AdminSys/PrintT/exportStudentsTableExcelEN';

// PrintT - exportClassStudentsTableExcelES
$route['admin/exportClassStudentsTableExcelES/(:any)'] = 'AdminSys/PrintT/exportClassStudentsTableExcelES/$1';

// PrintT - printStudentsAdmissionsTableES
$route['admin/printStudentsAdmissionsTableES'] = 'AdminSys/PrintT/printStudentsAdmissionsTableES';

// PrintT - printStudentsAdmissionsTableEN
$route['admin/printStudentsAdmissionsTableEN'] = 'AdminSys/PrintT/printStudentsAdmissionsTableEN';

// PrintT - printStudentsPreEnrollmentsTableES
$route['admin/printStudentsPreEnrollmentsTableES'] = 'AdminSys/PrintT/printStudentsPreEnrollmentsTableES';

// PrintT - printStudentsPreEnrollmentsTableEN
$route['admin/printStudentsPreEnrollmentsTableEN'] = 'AdminSys/PrintT/printStudentsPreEnrollmentsTableEN';

//UserSys Routes

// UsersSys - manage_profile
$route['admin/manage_profile/(:any)'] = 'AdminSys/UsersSys/manage_profile/$1';

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

// UsersSys - change_theme
$route['admin/change_theme/(:any)/(:any)'] = 'AdminSys/UsersSys/change_theme/$1/$2';
$route['admin/change_theme/(:any)'] = 'AdminSys/UsersSys/change_theme/$1';

// UsersSys - change_language
$route['admin/change_language/(:any)/(:any)'] = 'AdminSys/UsersSys/change_language/$1/$2';
$route['admin/change_language/(:any)'] = 'AdminSys/UsersSys/change_language/$1';

// UsersSys - reset_page_tracking
$route['admin/reset_page_tracking/(:any)/(:any)'] = 'AdminSys/UsersSys/reset_page_tracking/$1/$2';
$route['admin/reset_page_tracking/(:any)'] = 'AdminSys/UsersSys/reset_page_tracking/$1';

// UsersSys - language_settings
$route['admin/language_settings/(:any)/(:any)/(:any)'] = 'AdminSys/UsersSys/language_settings/$1/$2/$3';
$route['admin/language_settings/(:any)/(:any)'] = 'AdminSys/UsersSys/language_settings/$1/$2';
$route['admin/language_settings/(:any)'] = 'AdminSys/UsersSys/language_settings/$1';

// UsersSys - get_all_users
$route['admin/get_all_users'] = 'AdminSys/UsersSys/get_all_users';

// UsersSys - get_all_users2
$route['admin/get_all_users2'] = 'AdminSys/UsersSys/get_all_users2';

// UsersSys - get_all_users3
$route['admin/get_all_users3'] = 'AdminSys/UsersSys/get_all_users3';

// UsersSys - get_users
$route['admin/get_users/(:any)/(:any)'] = 'AdminSys/UsersSys/get_users/$1/$2';
$route['admin/get_users/(:any)'] = 'AdminSys/UsersSys/get_users/$1';
