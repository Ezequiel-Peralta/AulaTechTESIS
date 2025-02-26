	<!-- Bottom scripts (common) -->
	<script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/neon-chat.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>
	<script src="assets/js/toastr.js"></script> 

	<?php
    if($page_name === 'student_mark' || $page_name === 'view_student_mark' || $page_name === 'view_student_academic_history' || $page_name === 'view_library' || $page_name === 'library' || $page_name === 'section'):?>
		<script src="assets/js/jquery.validate.min.js"></script>
		<!-- <script src="assets/js/fullcalendar/fullcalendar.min.js"></script> -->
		<script src="assets/js/bootstrap-datepicker.js"></script>
		<script src="assets/js/fileinput.js"></script>
		
		<script src="assets/js/jquery.dataTables.min.js"></script>
		<script src="assets/js/datatables/TableTools.min.js"></script>
		<script src="assets/js/dataTables.bootstrap.js"></script>
		<script src="assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
		<script src="assets/js/datatables/lodash.min.js"></script>
		<script src="assets/js/datatables/responsive/js/datatables.responsive.js"></script>
		
		<script src="assets/js/select2/select2.min.js"></script>

		<script src="assets/js/fullcalendar/fullcalendar.min.js"></script>
		<script src="assets/js/neon-calendar.js"></script>
		
		<link rel="stylesheet" href="assets/js/datatables/responsive/css/datatables.responsive.css">
		<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
		<link rel="stylesheet" href="assets/js/select2/select2.css">

		<!-- <link rel="stylesheet" href="assets/js/toastr/build/toastr.css"> -->
	<?php endif;?>    

	<?php
    if($page_name === 'message_new' || $page_name === 'message_read' || $page_name === 'exam_add'):?>
		<!-- <link rel="stylesheet" href="assets/js/wysihtml5/bootstrap-wysihtml5.css"> -->
		<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
		<!-- <script src="assets/js/wysihtml5/wysihtml5-0.4.0pre.min.js"></script>

		<script src="assets/js/wysihtml5/bootstrap-wysihtml5.js"></script> -->
	<?php endif;?>  


	<?php
    if($page_name === 'dashboard'):?>
		<script src="assets/js/fullcalendar/fullcalendar.min.js"></script>
		<script src="assets/js/neon-calendar.js"></script>

		<script src="assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<?php endif;?> 

	<?php
    if($page_name === 'manage_students'):?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
	<?php endif;?> 



	<?php if ($page_name === 'language_settings' || $page_name === 'details_attendance_student' || $page_name === 'statistics' || $page_name === 'summary_attendance_student' || $page_name === 'library_information' || $page_name === 'academic_history' || $page_name === 'student_information' || $page_name === 'behavior' || $page_name === 'student_behavior' || $page_name === 'admissions' || $page_name === 'pre_enrollments' || $page_name === 're_enrollments' || $page_name === 'academic_period'  || $page_name === 'classes'
			|| $page_name === 'teacherAide' || $page_name === 'classes' || $page_name === 'class' || $page_name === 'section' || $page_name === 'principal_information' || $page_name === 'secretaries_information' || $page_name === 'teachers_information' || $page_name === 'teachers_aide_information'
			|| $page_name === 'subject' || $page_name === 'news' || $page_name === 'exams_information' || $page_name === 'schedules_information' || $page_name === 'view_schedules' || $page_name === 'view_news' || $page_name === 'view_exams' || $page_name === 'view_subjects' || $page_name === 'subjects_information' || $page_name === 'exam' || $page_name === 'marks_per_exam'
			|| $page_name === 'student_mark' || $page_name === 'manage_subjects' || $page_name === 'manage_academic_history' || $page_name === 'manage_news' || $page_name === 'manage_library' || $page_name === 'manage_schedules' || $page_name === 'manage_exams' || $page_name === 'manage_behavior' || $page_name === 'manage_students' || $page_name === 'view_student_mark' || $page_name === 'view_student_academic_history' || $page_name === 'view_library' || $page_name === 'library' || $page_name === 'student_mark_history' || $page_name === 'attendance_student'): ?>
		<?php
		$theme_mode  = $this->session->userdata('theme_preference');
		if ($theme_mode != ''):?>
			<link rel="stylesheet" href="assets/css/<?php echo $theme_mode;?>/datatable.css">
		<?php endif;?>

		<link rel="stylesheet" href="assets/js/datatables/datatables.css">
		<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
		<link rel="stylesheet" href="assets/js/select2/select2.css">

		<link rel="stylesheet" href="assets/js/icheck/skins/minimal/_all.css">
		<link rel="stylesheet" href="assets/js/icheck/skins/square/_all.css">
		<link rel="stylesheet" href="assets/js/icheck/skins/flat/_all.css">
		<link rel="stylesheet" href="assets/js/icheck/skins/futurico/futurico.css">
		<link rel="stylesheet" href="assets/js/icheck/skins/polaris/polaris.css">

		<!-- <script src="assets/js/datatables/datatables.js"></script> -->
		<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
		<script src="https://cdn.datatables.net/colreorder/1.5.4/js/dataTables.colReorder.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

		<script src="assets/js/select2/select2.min.js"></script> 

		<!-- Incluye colResizable JS -->
		 <script src="https://cdnjs.cloudflare.com/ajax/libs/colresizable/1.6.0/colResizable-1.6.js"></script>


		<!-- Incluye ColReorder CSS y JS -->
		<link rel="stylesheet" href="https://cdn.datatables.net/colreorder/1.5.4/css/colReorder.dataTables.min.css">
    	<script src="https://cdn.datatables.net/colreorder/1.5.4/js/dataTables.colReorder.min.js"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jsPDF/2.3.1/jspdf.umd.min.js"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>

		<script src="assets/js/icheck/icheck.min.js"></script>
	<?php endif;?> 

	<?php
		$theme_mode  = $this->session->userdata('theme_preference');
		if ($theme_mode != '' || $page_name === 'message_trash' || $page_name === 'message' || $page_name === 'message_trash' || $page_name === 'message_favorite' || $page_name === 'message_new'
		    || $page_name === 'message_sent' || $page_name === 'message_draft' || $page_name === 'message_tag'):?>
			<link rel="stylesheet" href="assets/css/<?php echo $theme_mode;?>/messages.css">
		<?php endif;?>

	<?php if($page_name === 'student_add' || $page_name === 'add_library' || $page_name === 'add_behavior' || $page_name === 'student_bulk_add' || $page_name === 'edit_behavior'  || $page_name === 'edit_library' || $page_name === 'payments' || $page_name === 'message_new' || $page_name === 'message_read' || $page_name === 'message' || $page_name === 'guardian_add' || $page_name === 'add_teacher' || $page_name === 'add_principal' || $page_name === 'add_secretary' || $page_name === 'add_teacher_aide'  || $page_name === 'teacher_aide_add'
			|| $page_name === 'add_schedule' || $page_name === 'add_subject' || $page_name === 'add_news' || $page_name === 'academic_period_add' || $page_name === 'exam_add' || $page_name === 'edit_schedule' || $page_name === 'edit_subject' || $page_name === 'edit_news' || $page_name === 'exam_edit' || $page_name === 'guardian_edit' || $page_name === 'profile_settings' || $page_name === 'student_edit'
			|| $page_name === 'edit_teacher' || $page_name === 'edit_secretary' || $page_name === 'edit_principal' || $page_name === 'edit_teacher_aide'  || $page_name === 'teacherAide_edit' || $page_name === 'dashboard' ):?>
		<link rel="stylesheet" href="assets/js/selectboxit/jquery.selectBoxIt.css">

		<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
		<link rel="stylesheet" href="assets/js/select2/select2.css">
		<link rel="stylesheet" href="assets/js/selectboxit/jquery.selectBoxIt.css">
		<link rel="stylesheet" href="assets/js/daterangepicker/daterangepicker-bs3.css">
		<link rel="stylesheet" href="assets/js/icheck/skins/minimal/_all.css">
		<link rel="stylesheet" href="assets/js/icheck/skins/square/_all.css">
		<link rel="stylesheet" href="assets/js/icheck/skins/flat/_all.css">
		<link rel="stylesheet" href="assets/js/icheck/skins/futurico/futurico.css">
		<link rel="stylesheet" href="assets/js/icheck/skins/polaris/polaris.css">

		<script src="assets/js/jquery.bootstrap.wizard.min.js"></script>
		<script src="assets/js/jquery.validate.min.js"></script>
		<script src="assets/js/jquery.inputmask.bundle.js"></script>

		<script src="assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
		<script src="assets/js/bootstrap-datepicker.js"></script>
		<script src="assets/js/jquery.multi-select.js"></script>

		<script src="assets/js/bootstrap-switch.min.js"></script>

		<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
		<link rel="stylesheet" href="assets/js/select2/select2.css">

		<script src="assets/js/jquery.validate.min.js"></script>
		<script src="assets/js/resizeable.js"></script>
		<script src="assets/js/bootstrap-datepicker.js"></script>
		<script src="assets/js/fileinput.js"></script>

		<script src="assets/js/select2/select2.min.js"></script>

		<script src="assets/js/bootstrap-tagsinput.min.js"></script>
		<script src="assets/js/typeahead.min.js"></script>
		<script src="assets/js/bootstrap-timepicker.min.js"></script>
		<script src="assets/js/moment.min.js"></script>
		<script src="assets/js/daterangepicker/daterangepicker.js"></script>
		<script src="assets/js/icheck/icheck.min.js"></script>

		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/lang/es.js"></script> -->

	<?php endif;?> 

	
	<?php
    if($page_name === 'add_teacher'):?>


		<script src="assets/js/bootstrap-tagsinput.min.js"></script>
		
		<script src="assets/js/jquery.multi-select.js"></script>
		
	<?php endif;?> 

	<?php if($page_name === 'news' || $page_name === 'manage_profile' || $page_name === 'message_new' || $page_name === 'exam_add'):?>

		<link rel="stylesheet" href="assets/js/dropzone/dropzone.css">

		<script src="assets/js/fileinput.js"></script>
		<script src="assets/js/dropzone/dropzone.js"></script>

	<?php endif;?> 

	<!--$page_name === 'summary_attendance_student' || -->
	<!-- <?php if($page_name === 'details_attendance_student'):?> 
		<link rel="stylesheet" href="assets/js/datatables/responsive/css/datatables.responsive.css">
		<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
		<link rel="stylesheet" href="assets/js/select2/select2.css">

	    <script src="assets/js/jquery.dataTables.min.js"></script>
		<script src="assets/js/datatables/TableTools.min.js"></script>
		<script src="assets/js/dataTables.bootstrap.js"></script>
		<script src="assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
		<script src="assets/js/datatables/lodash.min.js"></script>
		<script src="assets/js/datatables/responsive/js/datatables.responsive.js"></script>
		<script src="assets/js/select2/select2.min.js"></script>

	<?php endif;?>  -->

	<script>
        let isLoadingPopupActive = false;

        function showLoading() {
            isLoadingPopupActive = true;
            Swal.fire({
                imageUrl: "assets/images/loading-ezgif.com-gif-maker.gif",
                imageWidth: 100,
                imageHeight: 100,
                imageAlt: "Custom image",
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                background: 'transparent', // Set the background to transparent
                customClass: {
                    popup: 'transparent-popup'
                }
            });

            // Close the loading popup after a delay
            setTimeout(() => {
                Swal.close();
                isLoadingPopupActive = false;
                // Check if there is a flash message to show
                showFlashMessage();
            }, 600); // Adjust the time as necessary
        }

        function showFlashMessage() {
            <?php if ($this->session->flashdata('flash_message')): ?>
                <?php $flash_message = $this->session->flashdata('flash_message'); ?>
                Swal.fire({
                    title: "<?php echo $flash_message['title']; ?>",
                    text: "<?php echo $flash_message['text']; ?>",
                    icon: "<?php echo $flash_message['icon']; ?>",
                    showCloseButton: <?php echo $flash_message['showCloseButton'] ? 'true' : 'false'; ?>,
                    confirmButtonText: "<?php echo $flash_message['confirmButtonText']; ?>",
                    confirmButtonColor: "<?php echo $flash_message['confirmButtonColor']; ?>",
                    timer: <?php echo $flash_message['timer']; ?>,
                    timerProgressBar: <?php echo $flash_message['timerProgressBar'] ? 'true' : 'false'; ?>
                });

                // Cambiar el color del timerProgressBar usando CSS si el icono es 'success'
                if ("<?php echo $flash_message['icon']; ?>" === 'success') {
                    var progressBarStyle = document.createElement('style');
                    progressBarStyle.innerHTML = `
                        .swal2-timer-progress-bar {
                            background-color: #a5dc86 !important; 
                        }
                    `;
                    document.head.appendChild(progressBarStyle);
                }
            <?php endif; ?>
        }

        // Show the loading popup when the page loads
         window.addEventListener('load', showLoading);
    </script>


<script type="text/javascript">

	// jQuery(document).ready(function($)
	// {
		

	// 	var datatable = $("#table_export").dataTable();
		
	// 	$(".dataTables_wrapper select").select2({
	// 		minimumResultsForSearch: -1
	// 	});

	
	// });
		
</script>

<style>
	div:where(.swal2-container).swal2-backdrop-show, div:where(.swal2-container).swal2-noanimation {
    	background: rgba(0, 0, 0, .6) !important;
	}

	.swal2-timer-progress-bar {
        /* background-color: #265044 !important;  */
		/* background-color: #a5dc86 !important; */
    }

	div:where(.swal2-container) div:where(.swal2-timer-progress-bar) {
		height: .45em !important;
	}
</style>