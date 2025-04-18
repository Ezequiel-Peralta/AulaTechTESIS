

<?php
$subject_info = $this->crudSubject->get_student_info_per_section2($param2);
foreach($subject_info as $row):?>

<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">
    <i class="entypo-user"></i>
    <?php
        if ($param4 == 'student') {
            $info = $this->crudStudent->get_student_info($param3);
            foreach($info as $row9):
    ?>
                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_student_profile/<?php echo $row9['student_id'];?>');" class="breadcrumb-link">
                <?php
                    if (!empty($row9['lastname'])) {
                        echo $row9['lastname'] . ', ';
                    }
                    if (!empty($row9['firstname'])) {
                        $first_letter = mb_substr($row9['firstname'], 0, 1);
                        echo $first_letter . '.';
                    }
                ?>
                </a>
                &nbsp;
                /
                &nbsp;
    <?php
            endforeach;
        } elseif ($param4 == 'teacher') {
            $info = $this->crudTeacher->get_teacher_info($param3);
            foreach($info as $row9):
    ?>
                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_teacher_profile/<?php echo $row9['teacher_id'];?>');" class="breadcrumb-link">
                <?php
                    if (!empty($row9['lastname'])) {
                        echo $row9['lastname'] . ', ';
                    }
                    if (!empty($row9['firstname'])) {
                        $first_letter = mb_substr($row9['firstname'], 0, 1);
                        echo $first_letter . '.';
                    }
                ?>
                </a>
                &nbsp;
                /
                &nbsp;
    <?php
            endforeach;
        }
    ?>
    <?php echo 'Perfil'; ?>
    </h4>
</div>

<div class="modal-body" style="height: 600px; overflow:auto; background-color: #ebebeb;">
    <div class="profile-header" style="border-radius: 15px;">
        <img src="assets/images/profile-header.jpg" class="cover-photo" alt="Cover Photo">
        <img src="<?php echo $row['image'];?>" class="img-fluid" alt="Profile Picture" width="120" height="120">
        <div class="profile-info">
            <h2 style="font-weight: 600;">
                <?php echo ucfirst($row['name']);?>
            </h2>
        </div>
        <div class="profile-buttons">
            <button class="btn btn-secondary"><i class="entypo-pencil"></i> Modificar</button>
            <div class="btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <i class="entypo-dot-3"></i> Más <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                    <li class="text-left">
                        <a href="#" class="btn btn-default btn-dropdown btn-icon icon-left" style="border-radius: 0px !important;">Tareas<i class="fa fa-pencil-square-o"></i></a></li>
                    <li>
                        <a href="#" class="btn btn-default btn-dropdown btn-icon icon-left" style="border-radius: 0px !important;"><?php echo ('Calificaciones');?><i class="entypo-vcard"></i></a>
                    </li>
                    <li>
                        <a href="#" class="btn btn-default btn-dropdown btn-icon icon-left" style="border-radius: 0px !important;"><?php echo ('Asistencia');?><i class="entypo-book-open"></i></a>
                    </li>
                    <li>
                        <a href="#" class="btn btn-default btn-dropdown btn-icon icon-left" style="border-radius: 0px !important;"><?php echo ('Horario de clases');?><i class="entypo-clock"></i></a> <!--clock-o-->
                    </li>
                    <li>
                        <a href="#" class="btn btn-default btn-dropdown btn-icon icon-left" style="border-radius: 0px !important;"><?php echo ('Documentos');?><i class="entypo-doc-text"></i></a>
                    </li>
                    <li>
                        <a href="#" class="btn btn-default btn-dropdown btn-icon icon-left" style="border-radius: 0px !important;"><?php echo ('Pagos y facturación');?><i class="fa fa-money"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <br>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;">Información básica</h4>
            <br>
            <ul class="info-list">
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> Preceptor</strong>
                    <span class="info-cell">
                        <?php
                            $teacher_aide_info = $this->crudTeacher->get_teacher_aide_info_per_section($row['section_id']);
                            foreach($teacher_aide_info as $teacher_aide):
                            echo $teacher_aide['firstname'] . ' ' . $teacher_aide['lastname'];          
                        ?> 
                        <?php endforeach; ?>
                    </span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> Profesor</strong>
                    <span class="info-cell">
                        <?php
                            $teacher_info = $this->crudTeacher->get_teacher_info_per_subject($row['subject_id']);
                            echo $teacher_info['firstname'] . ' ' . $teacher_info['lastname'];
                        ?>    
                    </span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-graduation-cap"></i> Cantidad de estudiantes</strong>
                    <span class="info-cell">
                        <?php
                            echo $this->crud_model->get_section_student_amount($row['section_id']);
                        ?>    
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;">Horarios académicos</h4>
            <br>
            <ul class="info-list">

            </ul>
        </div>
    </div>    

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;">Información académica</h4>
            <br>
            <ul class="info-list">
                <?php
                $section_info = $this->crud_model->get_section_info_per_subject($row['subject_id']);
                if (!empty($section_info)): ?>
                    <div>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="card-body text-center">
                                    <h3 style="font-weight: 600; background-color: #B0DFCC; border-radius: 15px; padding: 5px 0px;">
                                        <?php echo $section_info['name']; ?> 
                                        <?php 
                                            if ($section_info['shift_id'] == 1) {
                                                echo '<i class="fa fa-sun-o" style="color: #265044" aria-hidden="true"></i>';
                                            } else {
                                                echo '<i class="fa fa-moon-o" style="color: #265044" aria-hidden="true"></i>';
                                            }
                                        ?>
                                    </h3>
                                    <br>
                                    <div class="text-center">
                                        <span class="min-title">
                                            Preceptor
                                        </span>
                                    </div>
                                    <br>
                                    <div class="teacher-aide-avatars">
                                        <?php
                                        $teacher_aide_info = $this->crudTeacher->get_teacher_aide_info_per_section($row['section_id']);
                                        foreach($teacher_aide_info as $teacher_aide):
                                            // Determina el color del borde basado en
                                            $border_color = $teacher_aide['gender_id'] == 0 ? 'border-blue' : 'border-pink';
                                            $teacher_aide_avatar_img = $teacher_aide['gender_id'] == 0 ? 'assets/images/teacher-aide-m-3.png' : 'assets/images/teacher-aide-w-3.png';
                                        ?>    
                                        <div class="teacher-aide-avatar">
                                            <img src="<?php echo base_url($teacher_aide_avatar_img); ?>" width="80" height="80" class="teacher-aide-image" alt="Desk">
                                            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_teacher_aide_profile/<?php echo $teacher_aide['teacher_aide_id'];?>/<?php echo $row['subject_id'];?>/<?php echo 'subject';?>');">
                                                <div class="teacher-aide-photo <?php echo $border_color; ?> popover-primary" 
                                                    style="background-image: url('<?php echo base_url($teacher_aide['photo']); ?>');"
                                                    data-toggle="popover" 
                                                    data-trigger="hover" 
                                                    data-placement="top" 
                                                    data-html="true"
                                                    data-content="<div class='popover-content'>
                                                                    <div class='popover-row'>
                                                                        <span class='popover-label'>Nombre</span>
                                                                        <span class='popover-value'><?php echo $teacher_aide['firstname'] . ' ' . $teacher_aide['lastname']; ?></span>
                                                                    </div>
                                                                    <div class='popover-row'>
                                                                        <span class='popover-label'>Dni</span>
                                                                        <span class='popover-value'><?php echo $teacher_aide['dni']?></span>
                                                                    </div>
                                                                </div>"
                                                    data-original-title="<div class='popover-header'><img src='<?php echo base_url($teacher_aide['photo']); ?>' class='popover-img <?php echo $border_color; ?>' alt='Photo'></div>">
                                                </div>
                                            </a>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <br>
                                    <div class="text-center">
                                        <span class="min-title">
                                            Profesor
                                        </span>
                                    </div>
                                    <br>
                                    <div class="teacher-avatars">
                                        <?php
                                        $teacher_info = $this->crudTeacher->get_teacher_info_per_subject($row['subject_id']);
                                        // Determina el color del borde basado en el género del profesor
                                        $border_color = $teacher_info['gender_id'] == 0 ? 'border-blue' : 'border-pink';
                                        $teacher_avatar_img = 'assets/images/teacher-avatar-3.png';
                                        ?>    
                                        <div class="teacher-avatar">
                                            <img src="<?php echo base_url($teacher_avatar_img); ?>" width="80" height="80" class="teacher-image" alt="Desk">
                                            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_teacher_profile/<?php echo $teacher_info['teacher_id'];?>/<?php echo $row['subject_id'];?>/<?php echo 'subject';?>');">
                                                <div class="teacher-photo <?php echo $border_color; ?> popover-primary" 
                                                    style="background-image: url('<?php echo base_url($teacher_info['photo']); ?>');"
                                                    data-toggle="popover" 
                                                    data-trigger="hover" 
                                                    data-placement="top" 
                                                    data-html="true"
                                                    data-content="<div class='popover-content'>
                                                                    <div class='popover-row'>
                                                                        <span class='popover-label'>Nombre</span>
                                                                        <span class='popover-value'><?php echo $teacher_info['firstname'] . ' ' . $teacher_info['lastname']; ?></span>
                                                                    </div>
                                                                    <div class='popover-row'>
                                                                        <span class='popover-label'>Dni</span>
                                                                        <span class='popover-value'><?php echo $teacher_info['dni']?></span>
                                                                    </div>
                                                                </div>"
                                                    data-original-title="<div class='popover-header'><img src='<?php echo base_url($teacher_info['photo']); ?>' class='popover-img <?php echo $border_color; ?>' alt='Photo'></div>">
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="text-center">
                                        <span class="min-title">
                                            Estudiantes
                                        </span>
                                    </div>
                                    <br>
                                    <div class="student-avatars">
                                        <?php 
                                        $student_info = $this->crudStudent->get_student_info_per_section($row['section_id']);
                                        foreach($student_info as $student): 
                                            // Determina el color del borde basado en el gender_id
                                            $border_color = $student['gender_id'] == 0 ? 'border-blue' : 'border-pink';
                                        ?>
                                        <div class="student-avatar">
                                            <img src="<?php echo base_url('assets/images/school-desk4.png'); ?>" width="80" height="100" class="desk-image" alt="Desk">
                                            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_student_profile/<?php echo $student['student_id'];?>/<?php echo $row['subject_id'];?>/<?php echo 'subject';?>');">
                                                <div class="student-photo <?php echo $border_color; ?> popover-primary" 
                                                    style="background-image: url('<?php echo base_url($student['photo']); ?>');"
                                                    data-toggle="popover" 
                                                    data-trigger="hover" 
                                                    data-placement="top" 
                                                    data-html="true"
                                                    data-content="<div class='popover-content'>
                                                                    <div class='popover-row'>
                                                                        <span class='popover-label'>Nombre</span>
                                                                        <span class='popover-value'><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></span>
                                                                    </div>
                                                                    <div class='popover-row'>
                                                                        <span class='popover-label'>Dni</span>
                                                                        <span class='popover-value'><?php echo $student['dni']?></span>
                                                                    </div>
                                                                    <div class='popover-row'>
                                                                        <span class='popover-label'>Matrícula</span>
                                                                        <span class='popover-value'><?php echo $student['enrollment']; ?></span>
                                                                    </div>
                                                                </div>"
                                                    data-original-title="<div class='popover-header'><img src='<?php echo base_url($student['photo']); ?>' class='popover-img <?php echo $border_color; ?>' alt='Photo'></div>">
                                                </div>
                                            </a>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </ul>
        </div>
    </div>


</div>

<div class="modal-footer text-center" style="text-align: center;">
    <!-- <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Cerrar</button> -->
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="entypo-cancel"></i>&nbsp;&nbsp;Cerrar</button>
</div>

<?php endforeach;?>

<style>
      .teacher-aide-avatars {
        display: flex !important;
        flex-wrap: wrap !important;
        justify-content: center !important;
        margin-bottom: -20px !important;
    }
    .teacher-aide-avatar {
        position: relative !important;
        width: 100px !important;
        height: 120px !important;
        margin-right: -10px !important;
    }
    .teacher-aide-image {
        /* width: 100% !important;
        height: auto !important; */
        /* padding-right: 30px !important; */
        padding-right: 0px !important;
        /* filter: drop-shadow(4px 4px 4px #B0DFCC) !important; */
    }
    .teacher-aide-photo {
        position: absolute !important;
        top: 0px !important;
        left: 35px !important;
        width: 38px !important;
        height: 38px !important;
        background-size: cover !important;
        background-position: center !important;
        border-radius: 50% !important;
    }

    .min-title {
        font-style: italic;
        font-weight: bold;
        color: #265044;
    }

    .popover.top {
        width: 200px !important;
        border-radius: 15px !important;
    }

    .popover-header {
    text-align: center;
    background-color: #f7f7f7; /* Fondo claro para el header */
    border-bottom: 1px solid #ebebeb;
}

.popover-img {
    display: inline-block;
    width: 60px;
    height: 60px;
    border-radius: 50%;
}

.popover-content {
    width: 100%;
    background: white !important;
    padding: 5px 5px !important;
}

.popover-row {
    display: flex;
    justify-content: space-between;
    color: #265044 !important;
}

.popover-label {
    font-weight: bold;
}

.popover-value {
    text-align: right;
}

    .border-blue {
        /* border-color: #00f !important;  */
        border: 3px solid #00BFD0 !important;
    }
    .border-pink {
        /* border-color: #f0c !important;  */
        border: 3px solid #f0c !important;
    }

    .student-avatars {
        display: flex !important;
        flex-wrap: wrap !important;
        justify-content: center !important;
    }
    .student-avatar {
        position: relative !important;
        width: 100px !important;
        height: 120px !important;
        margin-right: -10px !important;
    }
    .desk-image {
        /* width: 100% !important;
        height: auto !important; */
        /* padding-right: 30px !important; */
        padding-right: 0px !important;
        /* filter: drop-shadow(4px 4px 4px #B0DFCC) !important; */
    }
    .student-photo {
        position: absolute !important;
        top: -5px !important;
        left: 32px !important;
        width: 35px !important;
        height: 35px !important;
        background-size: cover !important;
        background-position: center !important;
        border-radius: 50% !important;
    }




    .teacher-avatars {
        display: flex !important;
        flex-wrap: wrap !important;
        justify-content: center !important;
        margin-bottom: -20px !important;
    }
    .teacher-avatar {
        position: relative !important;
        width: 100px !important;
        height: 120px !important;
        margin-right: -10px !important;
    }
    .teacher-image {
        /* width: 100% !important;
        height: auto !important; */
        /* padding-right: 30px !important; */
        padding-right: 0px !important;
        /* filter: drop-shadow(4px 4px 4px #B0DFCC) !important; */
    }
    .teacher-photo {
        position: absolute !important;
        top: 0px !important;
        left: 20px !important;
        width: 38px !important;
        height: 38px !important;
        background-size: cover !important;
        background-position: center !important;
        border-radius: 50% !important;
    }






    /* Estilo base para la pelotita de estado */
    .status-dot {
        display: inline-block;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        margin-top: 2px;
        margin-left: 5px;
    }

    /* Colores para diferentes estados */
    .status-dot.online {
        background-color: #4CAF50; /* Verde */
    }

    .status-dot.offline {
        background-color: #F44336; /* Rojo */
    }

    .status-dot.away {
        background-color: #FFC107; /* Amarillo */
    }

    .status-dot.busy {
        background-color: #FF5722; /* Naranja */
    }

    .card-body-guardian {
        border: 3px solid #ebebeb !important;
        padding: 10px 10px !important;
        border-radius: 15px !important;
    }

    .profile-header {
        background-color: #fff;
        text-align: center;
        position: relative;
        border-radius: 15px;
    }
    .profile-header img.img-fluid {
        border-radius: 50%;
        margin-top: -60px;
        border: 7px solid white;
    }
    
    .profile-header .cover-photo {
        width: 100%;
        height: 110px;
        object-fit: cover;
        border-radius: 15px;
    }
    .profile-info {
        margin-top: 10px;
    }
    .profile-buttons {
        margin-top: 20px;
    }
    .profile-buttons button {
        margin-right: 10px;
    }
    .profile-description {
        margin-top: 20px;
        padding: 0 20px;
        text-align: center;
    }
    .profile-details {
        margin-top: 20px;
    }
    .profile-details .info-list {
        display: block;
        padding: 0;
        list-style: none;
    }
    .profile-details .info-list li {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .profile-details .info-list li strong {
        width: 50%;
        text-align: left;
    }
    .profile-details .info-list li span {
        width: 50%;
        text-align: right;
    }

    .dropdown-menu {
        border-radius: 15px !important;
        background-color: #B0DFCC !important;
    }

    .info-title {
        color: #265044 !important; 
        font-weight: bold !important;
    }

    .info-cell {
        color: #265044 !important; 
        font-weight: 400 !important;
    }

</style>
