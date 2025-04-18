<?php
$section_info = $this->crud_model->get_section_info4($section_id)?>

    <div class="profile-header" style="border-radius: 15px;">
        <img src="assets/images/photo-header.png" class="cover-photo" alt="Cover Photo">
        <img src="assets/images/classroom-bg.jpg" class="img-fluid" alt="Profile Picture" width="120" height="120">
        <div class="profile-info">
            <h2 style="font-weight: 600;">
            <?php echo $section_info['name'];?>
            </h2>
        </div>
        <div class="profile-buttons">
            <a class="btn btn-secondary profile-button-active"><i class="entypo-vcard"></i> <?php echo ucfirst(get_phrase('information'));?></a>
            <a href="<?php echo base_url(); ?>index.php?admin/view_subjects/<?php echo $section_info['section_id'];?>" class="btn btn-secondary"><i class="entypo-docs" style="font-size: 12px;"></i> <?php echo ucfirst(get_phrase('subjects'));?></a>
            <a href="<?php echo base_url(); ?>index.php?admin/view_students_mark/<?php echo $section_info['section_id'];?>" class="btn btn-secondary">
                <i class="entypo-vcard"></i>
                <?php echo ucfirst(get_phrase('marks_section'));?>
            </a> 
            <a href="<?php echo base_url(); ?>index.php?admin/view_exams/<?php echo $section_info['section_id'];?>" class="btn btn-secondary">
                <i class="entypo-doc-text-inv"></i>
                <?php echo ucfirst(get_phrase('exams_section'));?>
            </a> 
            <a href="<?php echo base_url(); ?>index.php?admin/view_schedules/<?php echo $section_info['section_id'];?>" class="btn btn-secondary">
                <i class="entypo-clock"></i>
                <?php echo ucfirst(get_phrase('schedules'));?>
            </a> 
            <a href="<?php echo base_url(); ?>index.php?admin/view_library/<?php echo $section_info['section_id'];?>" class="btn btn-secondary">
                <i class="entypo-book"></i>
                <?php echo ucfirst(get_phrase('library'));?>
            </a> 
          
            <a href="<?php echo base_url(); ?>index.php?admin/summary_attendance_students/<?php echo $section_info['class_id'];?>/<?php echo $section_info['section_id'];?>" class="btn btn-secondary"><i class="entypo-pencil" style="font-size: 12px;"></i> <?php echo ucfirst(get_phrase('attendance'));?></a>
          
        </div>
        <br>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"> <?php echo ucfirst(get_phrase('information'));?></h4>
            <br>
            <ul class="info-list">
                <li>
                    <strong class="info-title"><i class="entypo-doc-text-inv"></i> <?php echo ucfirst(get_phrase('name'));?></strong>
                    <span class="info-cell"><?php echo $section_info['letter_name'];?></span>
                </li>
               
                <li>
                    <strong class="info-title"><i class="entypo-graduation-cap"></i> <?php echo ucfirst(get_phrase('class'));?></strong>
                    <span class="info-cell"><?php echo $this->crud_model->get_class_name_numeric($section_info['class_id']);?>°</span>
                </li>
          
                <li>
                    <strong class="info-title"><i class="entypo-graduation-cap"></i> <?php echo ucfirst(get_phrase('academic_period'));?></strong>
                    <span class="info-cell"><?php echo $this->crud_model->get_academic_period_name_per_section($section_info['section_id']);?></span>
                </li>
                <li>
                    <?php 
						if ($section_info['shift_id'] == 1) {
                            echo '<strong class="info-title"><i class="fa fa-sun-o" aria-hidden="true"></i>'. ucfirst(get_phrase('shift')) .'</strong>';
                            echo '<span class="info-cell">'. ucfirst(get_phrase('morning')) .'</span>';
						} else {
                            echo '<strong class="info-title"><i class="fa fa-sun-o" aria-hidden="true"></i>'. ucfirst(get_phrase('shift')) .'</strong>';
                            echo '<span class="info-cell">'. ucfirst(get_phrase('afternoon')) .'</span>';
                        }
					?>
                </li>
            </ul>
        </div>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"><?php echo ucfirst(get_phrase('teacher_aide')); ?></h4>
            <br>
                <div class="profile-container"> 
                    <?php
                        $teacher_aide_info = $this->TeachersAide_model->get_teacher_aide_info_per_section($section_info['section_id']);
                        foreach ($teacher_aide_info as $row2): ?>
                    <a href="<?php echo base_url(); ?>index.php?admin/teachers_aide_profile/<?php echo $row2['teacher_aide_id']; ?>">
                        <div class="profile-card">
                            <img src="<?php echo $row2['photo']; ?>" class="img-circle" alt="Profile Picture" width="80" height="80">
                            <h3 style="font-weight: 600;"><?php echo $row2['lastname']; ?>, <?php echo $row2['firstname']; ?>.</h3>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
        </div>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"><?php echo ucfirst(get_phrase('teachers')); ?></h4>
            <br>
            <div class="profile-container">
                <?php
                    // Verificar el estado del período académico
                    $this->db->where('id', $section_info['academic_period_id']);
                    $this->db->where('status_id', 1);
                    $academic_period = $this->db->get('academic_period')->row_array();

                    // Determinar la tabla a usar
                    $subject_table = ($academic_period) ? 'subject' : 'subject_history';

                    // Obtener los registros de la tabla correspondiente
                    $this->db->where('section_id', $section_info['section_id']);
                    $subjects = $this->db->get($subject_table)->result_array();

                    // Arreglo auxiliar para evitar duplicados de teacher_id
                    $processed_teachers = [];

                    foreach ($subjects as $subject) {
                        // Verificar si el teacher_id ya fue procesado
                        if (!in_array($subject['teacher_id'], $processed_teachers)) {
                            // Obtener información del profesor desde teacher_details
                            $this->db->where('teacher_id', $subject['teacher_id']);
                            $teacher = $this->db->get('teacher_details')->row_array();

                            if ($teacher): ?>
                                <div class="profile-card"> 
                                    <a href="<?php echo base_url(); ?>index.php?admin/teachers_profile/<?php echo $teacher['teacher_id']; ?>">
                                        <img src="<?php echo $teacher['photo']; ?>" class="img-circle" alt="Profile Picture" width="80" height="80">
                                        <h3 style="font-weight: 600;">
                                            <?php 
                                            echo ucwords($teacher['lastname']) . ', ' . ucwords($teacher['firstname']) . '.';
                                            ?>
                                        </h3>
                                    </a>
                                    <a href="<?php echo base_url(); ?>index.php?admin/subjects_profile/<?php echo $subject['subject_id']; ?>">
                                        <p style="color: #265044; font-weight: 600;">
                                            <i class="entypo-docs"></i> <?php echo ucfirst(get_phrase('subject')); ?>: <span style="color: #265044; font-weight: 400;"><?php echo ucfirst($subject['name']); ?></span>
                                        </p>
                                    </a>
                                </div>
                                <?php
                                // Marcar este teacher_id como procesado
                                $processed_teachers[] = $subject['teacher_id'];
                            endif;
                        }
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"><?php echo ucfirst(get_phrase('subjects')); ?></h4>
            <br>
            <div class="profile-container">
                <?php
                    // Obtener registros de la tabla subject
                    $this->db->where('section_id', $section_info['section_id']);
                    $subjects = $this->db->get('subject')->result_array();

                    // Si no se encuentran registros, buscar en subject_history
                    if (empty($subjects)) {
                        $this->db->where('section_id', $section_info['section_id']);
                        $subjects = $this->db->get('subject_history')->result_array();
                    }

                    // Recorre cada registro y busca el nombre de la sección
                    foreach ($subjects as $subject):
                        // Consulta el nombre de la sección según el section_id del subject
                        $this->db->where('section_id', $subject['section_id']);
                        $section = $this->db->get('section')->row_array();
                        $section_name = !empty($section) ? $section['name'] : ucfirst(get_phrase('unknown'));
                        $section_shift_id = !empty($section) ? $section['shift_id'] : ucfirst(get_phrase('unknown'));
                ?>
                        <a href="<?php echo base_url(); ?>index.php?admin/subjects_profile/<?php echo $subject['subject_id']; ?>">
                            <div class="profile-card" style="min-height: 240px !important;"> 
                                <img src="<?php 
                                    $imagePath = 'uploads/subject_image/' . $subject['image'];
                                    if (!file_exists($imagePath)) {
                                        $imagePath = 'uploads/subject_image_history/' . $subject['image'];
                                    }
                                    echo $imagePath;
                                ?>" class="img-circle" alt="Profile Picture" width="80" height="80">
                                <h3 style="font-weight: 600;">
                                    <?php echo ucfirst($subject['name']); ?>
                                </h3>
                                <p style="color: #265044; font-weight: 600;">
                                    <i class="entypo-clipboard"></i> 
                                    <?php echo ucfirst(get_phrase('class')); ?>: 
                                    <span style="color: #265044; font-weight: 400;">
                                        <?php echo ucfirst($section_name); ?>
                                    </span>
                                </p>
                                <p style="color: #265044; font-weight: 600;">
                                    <i class="fa fa-sun-o"></i> 
                                    <?php echo ucfirst(get_phrase('shift')); ?>: 
                                        <span style="color: #265044; font-weight: 400;">
                                            <?php 
                                                if ($section_shift_id == 1) {
                                                    echo ucfirst(get_phrase('morning'));
                                                } else if ($section_shift_id == 2) {
                                                    echo ucfirst(get_phrase('afternoon'));
                                                } else {
                                                    echo ucfirst(get_phrase('unknown')); // Puedes ajustar esto según necesites
                                                }
                                            ?>
                                        </span>
                                </p>
                            </div>
                        </a>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"> <?php echo ucfirst(get_phrase('students')); ?></h4>
            <br>
                <div class="profile-container"> 
                    <?php
                        $student_info = $this->Students_model->get_student_info_per_section($section_info['section_id']);
                        foreach ($student_info as $student): ?>
                        <a href="<?php echo base_url(); ?>index.php?admin/students_profile/<?php echo $student['student_id'];?>">
                            <div class="profile-card" style="min-height: 240px !important;"> 
                                    <img src="<?php echo $student['photo']; ?>" class="img-circle" alt="Profile Picture" width="80" height="80">
                                    <h3 style="font-weight: 600;">
                                        <?php
                                        echo ucwords($student['lastname']) . ', ' . ucwords($student['firstname']) . '.';
                                        ?>
                                    </h3>
                                <p style="color: #265044; font-weight: 600;"><i class="entypo-info-circled"></i> <?php echo ucfirst(get_phrase('enrollment')); ?>: <span style="color: #265044; font-weight: 400;"><?php echo ucfirst($student['enrollment']); ?></span></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
        </div>
    </div>

   
   


<style>
    .profile-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center; 
        gap: 20px; 
    }

    .profile-card {
        background-color: #efefef;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        width: 200px; 
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    }  .profile-card:hover {
        background-color: #B0DFCC;
        transform: scale(1.05);
    }

    .profile-card-subject {
        height: 160px; 

    }

    .profile-card-subject h3 {
        font-weight: 600;
        text-align: center;
        font-size: 16px;
        margin: 0; /* Eliminar márgenes adicionales */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap; /* Evita que el texto se desborde */
    }

    .profile-card img {
        border-radius: 50%;
        margin-bottom: 10px;
    }


    .status-dot {
        display: inline-block;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        margin-top: 2px;
        margin-left: 5px;
    }

    .status-dot.online {
        background-color: #4CAF50; 
    }

    .status-dot.offline {
        background-color: #F44336; 
    }

    .status-dot.away {
        background-color: #FFC107; 
    }

    .status-dot.busy {
        background-color: #FF5722; 
    }

    .card-body-guardian {
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
        margin-top: -77px;
        border: 7px solid white;
    }
    .profile-header .cover-photo {
        width: 100%;
        height: 180px;
        object-fit: cover;
        object-position: 10% 30%; 
        border-radius: 15px;
        
    }
    

    
    .profile-info {
        margin-top: 10px;
    }
    .profile-buttons {
        margin-top: 20px;
    }
    .profile-buttons .btn {
        margin-top: 10px;
        margin-right: 5px;
        border-radius: 10px;
    }

    .profile-buttons .profile-button-active {
        background-color: #fff !important;
        color: #265044 !important;
        border: 1px solid #265044 !important;
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
