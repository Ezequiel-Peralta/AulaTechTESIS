

<div class="sidebar-menu fixed">
<div class="sidebar-menu-inner">
    <header class="logo-env" >

        <!-- logo -->
        <div class="logo sticky-logo" style="text-align: center;">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type;?>/dashboard">
                <!-- <img src="uploads/logoIPDF.png"  style="max-height:100px;"/> -->
                <!-- <img src="uploads/logoIndex.png"  style="max-height:70px;"/> -->
                <img src="uploads/AulaTechLogo.png"  style="max-height:120px;"/>
            </a>
        </div>

        <!-- logo collapse icon -->
        <div class="sidebar-collapse  sticky-logo" style="">
            <a href="#" class="sidebar-collapse-icon with-animation" id="toggle-icon">
                <i class="entypo-menu" id="icon"></i>
            </a>
        </div>

        <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
        <div class="sidebar-mobile-menu visible-xs">
            <a href="#" class="with-animation">
                <i class="entypo-menu"></i>
            </a>
        </div>
    </header>

    <div class="sidebar-user-info sticky-user-info">

		<div class="sui-normal">
			<a href="#" class="user-link" style="color: black;">
				<img src="<?php echo $this->session->userdata('photo');?>" width="55" alt="" class="img-circle" />
				<!-- <span style="color: black;">Bienvenido,</span> -->
                <span style="color: black;"><?php echo ucfirst(get_phrase('welcome')); ?> <?php echo $this->session->userdata('login_type');?>,</span>
				<strong style="color: black;"><?php echo $this->session->userdata('firstname');?> <?php echo $this->session->userdata('lastname');?></strong>
			</a>
		</div>
		<div class="sui-hover inline-links animate-in text-center"><!-- You can remove "inline-links" class to make links appear vertically, class "animate-in" will make A elements animateable when click on user profile -->
			<a href="<?php echo base_url();?>index.php?<?php echo $account_type;?>/manage_profile/<?php echo ucfirst($this->session->userdata('login_user_id'));?>" class="popover-default perfil-btn" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo ucfirst(get_phrase('profile')); ?>">
				<i class="entypo-user" style="font-size: 18px;"></i>
				<!-- Perfil -->
			</a>
			<a href="<?php echo base_url();?>index.php?<?php echo $account_type;?>/profile_settings/<?php echo ucfirst($this->session->userdata('login_user_id'));?>" class="popover-default conf-btn" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo ucfirst(get_phrase('setting')); ?>">
				<i class="entypo-cog" style="font-size: 18px;"></i>
				<!-- Configuración -->
			</a>
			<a href="<?php echo base_url();?>index.php?login_in/logout" class="popover-default logout-btn" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo ucfirst(get_phrase('logout')); ?>">
				<i class="entypo-logout"></i>
				<!-- Cerrar sesión -->
			</a>
            <!-- <button class="btn btn-primary popover-primary" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="It's so simple to create a tooltop for my website!" data-original-title="Twitter Bootstrap Popover">I'm a Popover</button> -->
			<span class="close-sui-popup" style="font-size: 20px;">&times;</span>		
        </div>
	</div>

    <div style=""></div>	
    <ul id="main-menu" class="">

        <li class="<?php if ($page_name == 'dashboard') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/dashboard">
                <i class="fa fa-home" style="background-color: #265044; border-radius: 10px; padding: 10px 6px 10px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('dashboard')); ?></span>
            </a>
        </li>

        <!-- <li class="<?php if ($page_name == 'admin_information') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/admin_information">
                <i class="entypo-rocket" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('admin_section')); ?></span>
            </a>
        </li> -->

        <li class="<?php if ($page_name == 'message') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/messages">
                <i class="entypo-mail mail-icon" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white;"></i>
                <span><?php echo ucfirst(get_phrase('message')); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'section' || $page_name == 'section_profile') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/section">
                <i class="entypo-calendar" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('sections')); ?></span>
            </a>
        </li>

        <li class="<?php
        if ($page_name == 'guardian_add' || $page_name == 'guardian_edit' || $page_name == 'student_edit' || $page_name == 'guardian_profile' || $page_name == 'student_profile' || $page_name == 'behavior' ||
                $page_name == 'student_behavior' ||  $page_name == 'edit_behavior' || $page_name == 'add_behavior' ||
                $page_name == 'student_add' ||
                $page_name == 'student_bulk_add' ||
                $page_name == 'student_information' || $page_name == 'manage_students' || $page_name == 'manage_behavior')
            echo 'opened active has-sub';
        ?> ">
            <a href="#">
                <i class="entypo-graduation-cap" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span> <?php echo ucfirst(get_phrase('student_section')); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'guardian_add') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/guardian_add">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('add_guardian')); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'student_add') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/student_add">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('student_add')); ?></span>
                    </a>
                </li>

                <li class="<?php if ($page_name == 'student_bulk_add') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/student_bulk_add">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('bulk_add_students')); ?></span>
                    </a>
                </li>

                <li class="<?php if ($page_name == 'manage_students' || $page_name == 'student_information') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_students">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('manage_students')); ?></span>
                    </a>
                   
                </li>

                <li class="<?php if ($page_name == 'manage_behavior' || $page_name == 'behavior') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_behavior">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('manage_behavior')); ?></span>
                    </a>
                   
                </li>


                <!-- <li class="<?php if ($page_name == 'student_mark') echo 'opened active'; ?> ">
                    <a href="#">
                        <span><i class="entypo-dot"></i> <?php echo ('Calificaciones'); ?></span>
                    </a>
                    <ul class="text-center">
                        <?php
                        $classes = $this->db->get('class')->result_array();
                        foreach ($classes as $row):
                            ?>
                        <li class="<?php if ($page_name == 'student_mark' && $class_id == $row['class_id']) echo 'active'; ?>">
                            <a href="<?php echo base_url(); ?>index.php?admin/student_mark/<?php echo $row['class_id']; ?>">
                                <span><?php echo $row['name_numeric']; ?><?php echo (' °'); ?> </span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>                 -->
            </ul>
        </li>

        <li class="<?php if ($page_name == 'subjects' || $page_name == 'edit_subject' || $page_name == 'subject_profile' || $page_name == 'subjects_information' || $page_name == 'add_subject' || $page_name == 'manage_subjects' || $page_name == 'view_subjects') echo 'opened active'; ?> ">
            <a href="#">
                <i class="entypo-docs" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('subjects')); ?></span>
            </a>
            <ul class="text-left">
                <li class="<?php if ($page_name == 'add_subject') echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_subject">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('add_subject')); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'view_subjects') echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/view_subjects">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('show_subjects')); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'manage_subjects') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_subjects">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('manage_subjects')); ?></span>
                    </a>
                   
                </li>
            </ul>
        </li>

        <li class="<?php
        if ($page_name == 'attendance_student' || $page_name == 'summary_attendance_student'
            || $page_name == 'manage_attendance_student' || $page_name == 'details_attendance_student')
            echo 'active';
        ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/attendance_student">
                <i class="entypo-pencil" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('attendance')); ?></span>
            </a>
        </li>

        <li class="<?php if ( $page_name == 'student_mark' || $page_name == 'marks_per_exam' || $page_name == 'view_student_mark') echo 'opened active'; ?> ">
            <a href="#">
                <i class="entypo-vcard" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('marks_section')); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'marks_per_exam') echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/marks_per_exam">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('mark_per_exam')); ?></span>
                    </a>
                </li>

                <li class="<?php if ($page_name == 'student_mark') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/student_mark">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('marks_sheet')); ?></span>
                    </a>
                   
                </li>

              
            </ul>
        </li>


         <li class="<?php
        if ($page_name == 'exam_add' || $page_name == 'exam_edit' ||
                $page_name == 'exams_information' || $page_name == 'view_exams' || $page_name == 'manage_exams' ||
                $page_name == 'exam')
            echo 'opened active has-sub';
        ?> ">
            <a href="#">
                <i class=" entypo-doc-text-inv" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('exams_section')); ?></span>
            </a>
            <ul>     
                <li class="<?php if ($page_name == 'exam_add') echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/exam_add">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('exam_add')); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'view_exams') echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/view_exams">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('show_exams')); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'manage_exams' || $page_name == 'exams_information') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_exams">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('manage_exams')); ?></span>
                    </a>
                   
                </li>
               
                
            </ul>   
        </li>


        <li class="<?php
            if ($page_name == 'add_schedule' || $page_name == 'edit_schedule' ||
                    $page_name == 'schedules_information' || $page_name == 'view_schedules' || $page_name == 'manage_schedules' ||
                    $page_name == 'schedules')
                echo 'opened active has-sub';
            ?> ">
            <a href="#">
                <i class="entypo-clock" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('schedules')); ?></span>
            </a>

            <ul>     
                <li class="<?php if ($page_name == 'add_schedule') echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_schedule">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('add_schedule')); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'view_schedules') echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/view_schedules">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('show_schedules')); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'manage_schedules' || $page_name == 'schedules_information') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_schedules">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('manage_schedules')); ?></span>
                    </a>
                   
                </li>
            </ul>   
        </li>

        <!-- <li class="<?php if ($page_name == 'parent') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/parent">
                <i class="entypo-users" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 5px; color:white"></i>
                <span><?php echo ('Sección de padres'); ?></span>
            </a>
        </li> -->

        <!-- <li class="<?php if ($page_name == 'mora' || $page_name == 'scholarship_students'
                            || $page_name == 'receipts'  || $page_name == 'payments' 
                    ) echo 'opened active has-sub'; ?> ">
            <a href="#">
                <i class="fa fa-money" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span>Pagos</span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'mora') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/mora">
                        <span><i class="entypo-dot"></i> Mora</span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'scholarship_students') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/scholarship_students">
                        <span><i class="entypo-dot"></i> Becados</span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'receipts') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/receipts">
                        <span><i class="entypo-dot"></i> Comprobantes</span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'payments') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/payments">
                        <span><i class="entypo-dot"></i> Ingresar pagos</span>
                    </a>
                </li>
            </ul>
        </li> -->

        
        <li class="<?php if ($page_name == 'library' || $page_name == 'library_information' || $page_name == 'manage_library' || $page_name == 'view_library' ||  $page_name == 'add_library' || $page_name == 'edit_library') echo 'opened active has-sub'; ?> ">
            <a href="#">
                <i class="entypo-book" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('library_section')); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'new_add') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_library">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('add_library')); ?></span>
                    </a>
                </li>

                <li class="<?php if ($page_name == 'library' || $page_name == 'view_library') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/view_library">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('view_library')); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'manage_library' || $page_name == 'library_information') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_library">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('manage_library')); ?></span>
                    </a>
                   
                </li>
            </ul>
        </li>

        <li class="<?php if ($page_name == 'view_news' || $page_name == 'add_news' || $page_name == 'manage_news' || $page_name == 'edit_news') echo 'opened active has-sub'; ?> ">
            <a href="#">
                <i class="entypo-doc-text-inv" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('news')); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'add_news') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_news">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('add_news')); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'view_news') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/view_news">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('show_news')); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'manage_news') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_news">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('manage_news')); ?></span>
                    </a>
                </li>
            </ul>
        </li>



        <li class="<?php
        if ($page_name == 'teacher' || $page_name == 'teachers_information'  || $page_name == 'teacher_profile' || $page_name == 'add_teacher' || $page_name == 'edit_teacher' ||
                $page_name == 'teacher_aide' || $page_name == 'teachers_aide_information'  || $page_name == 'teacher_aide_profile' || $page_name == 'add_teacher_aide' || $page_name == 'edit_teacher_aide' ||
                $page_name == 'secretary' || $page_name == 'secretaries_information'  || $page_name == 'secretary_profile' || $page_name == 'add_secretary' || $page_name == 'edit_secretary' ||
                $page_name == 'principal' || $page_name == 'principal_information'  || $page_name == 'principal_profile' || $page_name == 'add_principal' || $page_name == 'edit_principal')
            echo 'opened active has-sub';
        ?> ">
            <a href="#">
                <i class="entypo-graduation-cap" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('staff_section')); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'teacher' || $page_name == 'teacher_profile'  || $page_name == 'edit_teacher' || $page_name == 'teacher_profile' || $page_name == 'teachers_information' || $page_name == 'add_teacher') echo 'active has-sub opened'; ?> ">
                    <a href="#">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('teachers')); ?></span>
                    </a>
                    <ul class="text-left">
                        <li class="<?php if ($page_name == 'add_teacher') echo 'active'; ?>">
                            <a href="<?php echo base_url(); ?>index.php?admin/add_teacher">
                                <span><i class="entypo-dot"></i><?php echo ucfirst(get_phrase('add_teacher')); ?> </span>
                            </a>
                        </li>
                        <li class="<?php if ($page_name == 'teachers_information') echo 'active'; ?>">
                            <a href="<?php echo base_url(); ?>index.php?admin/teachers_information">
                                <span><i class="entypo-dot"></i><?php echo ucfirst(get_phrase('manage_teachers')); ?> </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="<?php if ($page_name == 'teacher_aide' || $page_name == 'teacher_aide_profile'  || $page_name == 'edit_teacher_aide'  || $page_name == 'teachers_aide_information' || $page_name == 'add_teacher_aide') echo 'active'; ?> ">
                    <a href="#">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('teachers_aide')); ?></span>
                    </a>
                    <ul class="text-left">
                        <li class="<?php if ($page_name == 'add_teacher_aide') echo 'active'; ?>">
                            <a href="<?php echo base_url(); ?>index.php?admin/add_teacher_aide">
                                <span><i class="entypo-dot"></i><?php echo ucfirst(get_phrase('add_teacher_aide')); ?> </span>
                            </a>
                        </li>
                        <li class="<?php if ($page_name == 'teachers_aide_information') echo 'active'; ?>">
                            <a href="<?php echo base_url(); ?>index.php?admin/teachers_aide_information">
                                <span><i class="entypo-dot"></i><?php echo ucfirst(get_phrase('manage_teachers_aide')); ?> </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="<?php if ($page_name == 'secretaries_information' || $page_name == 'secretary_profile'  || $page_name == 'edit_secretary'  || $page_name == 'secretary_information' || $page_name == 'add_secretary') echo 'active'; ?> ">
                    <a href="#">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('secretaries')); ?></span>
                    </a>
                    <ul class="text-left">
                        <li class="<?php if ($page_name == 'add_secretary') echo 'active'; ?>">
                            <a href="<?php echo base_url(); ?>index.php?admin/add_secretary">
                                <span><i class="entypo-dot"></i><?php echo ucfirst(get_phrase('add_secretary')); ?> </span>
                            </a>
                        </li>
                        <li class="<?php if ($page_name == 'secretaries_information') echo 'active'; ?>">
                            <a href="<?php echo base_url(); ?>index.php?admin/secretaries_information">
                                <span><i class="entypo-dot"></i><?php echo ucfirst(get_phrase('manage_secretaries')); ?> </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="<?php if ($page_name == 'principal_information' || $page_name == 'principal_profile'  || $page_name == 'edit_principal'  || $page_name == 'principal_information' || $page_name == 'add_principal') echo 'active'; ?> ">
                    <a href="#">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('principals')); ?></span>
                    </a>
                    <ul class="text-left">
                        <li class="<?php if ($page_name == 'add_principal') echo 'active'; ?>">
                            <a href="<?php echo base_url(); ?>index.php?admin/add_principal">
                                <span><i class="entypo-dot"></i><?php echo ucfirst(get_phrase('add_principal')); ?> </span>
                            </a>
                        </li>
                        <li class="<?php if ($page_name == 'principal_information') echo 'active'; ?>">
                            <a href="<?php echo base_url(); ?>index.php?admin/principal_information">
                                <span><i class="entypo-dot"></i><?php echo ucfirst(get_phrase('manage_principals')); ?> </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

        <li class="<?php
        if ($page_name == 'class' ||
            $page_name == 'academic_period' ||
            $page_name == 'student_mark_history' ||
            $page_name == 'manage_academic_history' || $page_name == 'academic_history' || $page_name == 'view_student_academic_history')
            echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-clipboard" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('academic')); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'academic_period') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/academic_period">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('manage_academic_session')); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'class') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/classes">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('manage_classes')); ?></span>
                    </a>
                    <ul class="text-left">
                        <?php
                            $classes = $this->db->get('class')->result_array();
                            foreach ($classes as $row):
                        ?>
                            <li class="<?php if ($page_name == 'student_mark_history' && $class_id == $row['class_id']) echo 'active'; ?>">
                                <a href="<?php echo base_url(); ?>index.php?admin/classes">
                                    <span><i class="entypo-dot"></i><?php echo $row['name']; ?> ° <?php echo ucfirst(get_phrase('year')); ?> </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                
                <li class="<?php if ($page_name == 'manage_academic_history' || $page_name == 'academic_history') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_academic_history">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('manage_academic_history')); ?></span>
                    </a>
                </li>
            </ul>
        </li>


        <li class="<?php
            if ($page_name == 're_enrollments')
                echo 'opened active has-sub';
            ?> ">
            <a href="#">
                <i class="entypo-graduation-cap" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('re_enrollments')); ?></span>
            </a>
            <ul class="text-left">
                <?php
                $this->db->select('id');
                $this->db->from('academic_period');
                $this->db->where('status_id', 0);
                $this->db->order_by('end_date', 'DESC');
                $inactive_academic_period = $this->db->get()->row();

                if ($inactive_academic_period) {
                    $this->db->where('academic_period_id', $inactive_academic_period->id);
                    $sections = $this->db->get('section_history')->result_array();

                    foreach ($sections as $row):
                ?>
                        <li class="<?php if ($page_name == 're_enrollments') echo 'active'; ?>">
                            <a href="<?php echo base_url(); ?>index.php?admin/re_enrollments/<?php echo $row['section_id']; ?>">
                                <span><i class="entypo-dot"></i> 
                                    <?php 
                                        echo $row['name'] . " - " . $this->crud_model->get_academic_period_name_per_section_history($row['section_id']); 
                                    ?>
                                </span>
                            </a>
                        </li>
                <?php 
                    endforeach;
                } else {
                    // echo '<li><span>No hay periodos disponibles</span></li>';
                }
                ?>
            </ul>

        </li>

        <li class="<?php
        if ($page_name == 'pre_enrollments')
            echo 'opened active';
        ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/pre_enrollments/">
                <i class="entypo-graduation-cap" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('enrollment_section')); ?></span>
            </a>
        </li>

        <li class="<?php
        if ($page_name == 'admissions')
            echo 'opened active';
        ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/admissions/">
                <i class="entypo-graduation-cap" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('admissions')); ?></span>
            </a>
        </li>
        
        <li class="<?php if ($page_name == 'statistics') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/statistics">
                <i class="entypo-chart-bar" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('statistics')); ?></span>
            </a>
        </li>


       


      


      

        <!-- <li class="<?php if ($page_name == 'teacher') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/teacher">
                <i class="entypo-users" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 5px; color:white"></i>
                <span><?php echo ('Sección de profesores'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'teacherAide') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/teacherAide">
                <i class="entypo-users" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 5px; color:white"></i>
                <span><?php echo ('Sección de preceptores'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'secretary') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/secretary">
                <i class="entypo-users" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 5px; color:white"></i>
                <span><?php echo ('Sección de secretarios'); ?></span>
            </a>
        </li>
        
        <li class="<?php if ($page_name == 'principal') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/principal">
                <i class="entypo-users" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 5px; color:white"></i>
                <span><?php echo ('Sección de directivos'); ?></span>
            </a>
        </li> -->

        
    

       

      
      



        

        <!-- <li class="<?php if ($page_name == 'attendance') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/attendance/<?php echo date("d/m/Y"); ?>">
                <i class="entypo-pencil" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 5px; color:white"></i>
                <span><?php echo ('Asistencia'); ?></span>
            </a>

        </li> -->
        
     

        

      










        

       

        <li class="<?php
        if ($page_name == 'system_settings' ||
		        $page_name == 'language_settings')
            echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-cog" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('settings')); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'language_settings') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/language_settings">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('language_settings')); ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="<?php if ($page_name == 'manage_profile' || $page_name == 'profile_settings') echo 'opened active has-sub'; ?> ">
            <a href="#">
                <i class="entypo-user" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 6px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('account')); ?></span>
            </a>
            <?php
                $user_id = $this->session->userdata('login_user_id'); 
                $user_group = $this->session->userdata('login_type');
            ?>
            <ul>
                <li class="<?php if ($page_name == 'manage_profile') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_profile/<?php echo $user_id;?>">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('view_profile')); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'profile_settings') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/profile_settings/<?php echo $user_id;?>">
                        <span><i class="entypo-dot"></i> <?php echo ucfirst(get_phrase('profile_settings')); ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="<?php if ($page_name == 'help') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/help">
                <i class="entypo-help-circled" style="background-color: #265044; border-radius: 10px; padding: 5px 5px 5px; color:white"></i>
                <span><?php echo ucfirst(get_phrase('help')); ?></span>
            </a>
        </li> 

    </ul>

</div>
</div>

<style>


.page-container .sidebar-menu .logo-env {
    position: sticky !important;
    top: 0 !important; /* Fija el logo en la parte superior */
    z-index: 1000 !important; /* Asegura que se mantenga sobre otros elementos */
    background-color: #ffffff !important; /* Fija un fondo si lo necesitas */
}



.sticky-user-info {
    position: sticky !important;
    top: 120px !important; /* Ajusta este valor para que se ubique justo debajo del logo */
    z-index: 1500 !important; /* Asegúrate de que esté visible */
    background-color: #ffffff !important;/* Mantén el fondo fijo */
}

.ps-scrollbar-y-rail {
    z-index: 999999999 !important;
}


</style>

<script>
    $(document).ready(function() {
        $('.user-link').hover(
            function() {
                $('.inline-links').addClass('visible');
            },
            function() {
                if ($('.inline-links').hasClass('visible')) {
                    $('.inline-links').removeClass('visible');
                }
            }
        );
        $('.inline-links').hover(
            function() {
                $('.inline-links').addClass('visible');
            },
            function() {
                if ($('.inline-links').hasClass('visible')) {
                    $('.inline-links').removeClass('visible');
                }
            }
        );
    });

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("toggle-icon").addEventListener("click", function(event) {
            event.preventDefault(); // Prevent the default action of the link
            let iconElement = document.getElementById("icon");
            if (iconElement.classList.contains("entypo-menu")) {
                iconElement.classList.remove("entypo-menu");
                iconElement.classList.add("entypo-left");
            } else {
                iconElement.classList.remove("entypo-left");
                iconElement.classList.add("entypo-menu");
            }
        });
    });


</script>