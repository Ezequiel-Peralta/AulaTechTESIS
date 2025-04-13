<?php
$student_info = $this->Students_model->get_student_info($param2);
foreach($student_info as $row):?>

    <div class="profile-header" style="border-radius: 15px;">
        <img src="assets/images/photo-header.png" class="cover-photo" alt="Cover Photo">
        <img src="<?php echo $row['photo'];?>" class="img-fluid" alt="Profile Picture" width="150" height="150">
        <div class="profile-info">
            <h2 style="font-weight: 600;">
            <?php echo $row['lastname'];?>, <?php echo $row['firstname'];?>.
            </h2>
        </div>
        <div class="profile-buttons">
            <a class="btn btn-secondary profile-button-active"><i class="entypo-vcard"></i> <?php echo ucfirst(get_phrase('information'));?></a>
            <a href="<?php echo base_url(); ?>index.php?admin/student_behavior/<?php echo $row['student_id'];?>" class="btn btn-secondary"><i class="entypo-info-circled" style="font-size: 12px;"></i> <?php echo ucfirst(get_phrase('behavior'));?></a>
            <a href="<?php echo base_url(); ?>index.php?admin/view_student_mark/<?php echo $row['section_id'];?>" class="btn btn-secondary"><i class="entypo-doc-text-inv"></i> <?php echo ucfirst(get_phrase('marks'));?></a>
            <a href="<?php echo base_url(); ?>index.php?admin/view_exams/<?php echo $row['section_id']; ?>" class="btn btn-secondary">
                <i class="entypo-doc-text-inv"></i>
                <?php echo ucfirst(get_phrase('exams'));?>
            </a> 
            <a href="<?php echo base_url(); ?>index.php?admin/view_student_academic_history/<?php echo $row['student_id']; ?>" class="btn btn-secondary">
                <i class="entypo-vcard"></i>
                <?php echo ucfirst(get_phrase('academic_history'));?>
            </a>
            <a href="<?php echo base_url(); ?>index.php?admin/details_attendance_student/<?php echo $row['student_id'];?>" class="btn btn-secondary"><i class="entypo-pencil" style="font-size: 12px;"></i> <?php echo ucfirst(get_phrase('attendance'));?></a>
            <a href="<?php echo base_url(); ?>index.php?admin/view_schedules/<?php echo $row['section_id']; ?>" class="btn btn-secondary">
                <i class="entypo-clock"></i>
                <?php echo ucfirst(get_phrase('schedules'));?>
            </a> 
            <a href="<?php echo base_url(); ?>index.php?admin/view_subjects/<?php echo $row['section_id']; ?>" class="btn btn-secondary">
                <i class="entypo-docs"></i>
                <?php echo ucfirst(get_phrase('subjects'));?>
            </a> 
            <a href="<?php echo base_url(); ?>index.php?admin/view_library/<?php echo $row['section_id']; ?>" class="btn btn-secondary">
                <i class="entypo-book"></i>
                <?php echo ucfirst(get_phrase('library'));?>
            </a> 
        </div>
        <br>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"> <?php echo ucfirst(get_phrase('personal_information'));?></h4>
            <br>
            <ul class="info-list">
                <li>
                    <strong class="info-title"><i class="fa fa-address-card"></i> Dni</strong>
                    <span class="info-cell"><?php echo $row['dni'];?></span>
                </li>
               
                <li>
                    <strong class="info-title"><i class="entypo-mail"></i> Email</strong>
                    <span class="info-cell"><?php echo $row['email'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> <?php echo ucfirst(get_phrase('user_name'));?></strong>
                    <span class="info-cell"><?php echo $row['username'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="fa fa-phone"></i> <?php echo ucfirst(get_phrase('cell_phone'));?></strong>
                    <span class="info-cell"><?php echo $row['phone_cel'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="fa fa-phone"></i> <?php echo ucfirst(get_phrase('landline'));?></strong>
                    <span class="info-cell"><?php echo $row['phone_fij'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-calendar"></i> <?php echo ucfirst(get_phrase('birthday')); ?></strong>
                    <span class="info-cell">
                        <?php 
                            $original_date = $row['birthday'];
                            $formatted_date = date("d/m/Y", strtotime($original_date));
                            echo $formatted_date;
                        ?>
                    </span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> <?php echo ucfirst(get_phrase('gender'));?></strong>
                    <span class="info-cell">
                        <?php 
                            if ($row['gender_id'] == '0') {
                                echo ucfirst(get_phrase('male'));
                            } elseif ($row['gender_id'] == '1') {
                                echo ucfirst(get_phrase('female'));
                            } elseif ($row['gender_id'] == '2') {
                                echo ucfirst(get_phrase('other'));
                            } 
                        ?>
                    </span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-location"></i> <?php echo ucfirst(get_phrase('locality'));?></strong>
                    <span class="info-cell"><?php echo $row['locality'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-location"></i> <?php echo ucfirst(get_phrase('neighborhood'));?></strong>
                    <span class="info-cell"><?php echo $row['neighborhood'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-location"></i> <?php echo ucfirst(get_phrase('address'));?></strong>
                    <span class="info-cell"><?php echo $row['address'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-location"></i> <?php echo ucfirst(get_phrase('address_line'));?></strong>
                    <span class="info-cell"><?php echo $row['address_line'];?></span>
                </li>
            </ul>
        </div>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"> <?php echo ucfirst(get_phrase('academic_information'));?></h4>
            <br>
            <ul class="info-list">
                <li>
                    <strong class="info-title"><i class="entypo-graduation-cap"></i> <?php echo ucfirst(get_phrase('enrollment'));?></strong>
                    <span class="info-cell"><?php echo $row['enrollment'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> <?php echo ucfirst(get_phrase('class'));?></strong>
                    <span class="info-cell"><?php echo $this->crud_model->get_class_name_numeric($row['class_id']);?>°</span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> <?php echo ucfirst(get_phrase('section'));?></strong>
                    <span class="info-cell"><?php echo $this->db->get_where('section' , array('section_id' => $row['section_id']))->row()->letter_name;?></span>
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
                    $teacher_aide_info = $this->Teachers_model->get_teacher_aide_info_per_section($row['section_id']);
                    if (!empty($teacher_aide_info)) {
                        foreach ($teacher_aide_info as $row2): ?>
                        <a href="<?php echo base_url(); ?>index.php?admin/teacher_aide_profile/<?php echo $row2['teacher_aide_id']; ?>">
                            <div class="profile-card">
                                <img src="<?php echo $row2['photo']; ?>" class="img-circle" alt="Profile Picture" width="80" height="80">
                                <h3 style="font-weight: 600;"><?php echo $row2['lastname']; ?>, <?php echo $row2['firstname']; ?>.</h3>
                            </div>
                        </a>
                    <?php endforeach;
                    } else {
                        echo '<h4 style="color: #265044; font-weight: bold;">' . ucfirst(get_phrase('no_teacher_aide_data_found')) . '</h4>';
                    }
                ?>
                </div>
        </div>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"><?php echo ucfirst(get_phrase('teachers')); ?></h4>
            <br>
            <div class="profile-container">
                <?php
                    $section_info = $this->crud_model->get_section_info4($row['section_id']);

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
                                    <a href="<?php echo base_url(); ?>index.php?admin/teacher_profile/<?php echo $teacher['teacher_id']; ?>">
                                        <img src="<?php echo $teacher['photo']; ?>" class="img-circle" alt="Profile Picture" width="80" height="80">
                                        <h3 style="font-weight: 600;">
                                            <?php 
                                            echo ucwords($teacher['lastname']) . ', ' . ucwords($teacher['firstname']) . '.';
                                            ?>
                                        </h3>
                                    </a>
                                    <a href="<?php echo base_url(); ?>index.php?admin/subject_profile/<?php echo $subject['subject_id']; ?>">
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
                    $subject_info = $this->Subjects_model->get_subjects_by_section($row['section_id']);
                    if (!empty($subject_info)) {
                        foreach ($subject_info as $subject): ?>
                        <a href="<?php echo base_url(); ?>index.php?admin/subject_profile/<?php echo $subject['subject_id'];?>">
                            <div class="profile-card profile-card-subject" title=" <?php 
                                        echo ucfirst($subject['name']);
                                        ?>"> 
                                <img src="uploads/subject_image/<?php echo $subject['image']; ?>" class="img-circle" alt="Profile Picture" width="80" height="80">
                                <h3 style="font-weight: 600;">
                                    <?php 
                                    echo ucfirst($subject['name']);
                                    ?>
                                </h3>
                            </div>
                        </a>
                    <?php endforeach;
                    } else {
                        echo '<h4 style="color: #265044; font-weight: bold;">' . ucfirst(get_phrase('no_subject_data_found')) . '</h4>';
                    }
                ?>
            </div>
        </div>
    </div>


    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"><?php echo ucfirst(get_phrase('guardians')); ?></h4>
            <br>
            <div class="profile-container">
                <?php
                    $guardian_info = $this->Guardians_model->get_guardian_info_per_student($row['student_id']);
                    if (!empty($guardian_info)) {
                        foreach ($guardian_info as $guardian): ?>
                            <a href="<?php echo base_url(); ?>index.php?admin/guardian_profile/<?php echo $guardian['guardian_id'];?>">
                                <div class="profile-card"> 
                                    <img src="<?php echo $guardian['photo']; ?>" class="img-circle" alt="Profile Picture" width="80" height="80">
                                    <h3 style="font-weight: 600;">
                                        <?php 
                                        echo ucwords($guardian['lastname']) . ', ' . ucwords($guardian['firstname']) . '.';
                                        ?>
                                    </h3>
                                </div>
                            </a>
                        <?php endforeach; 
                    } else {
                        echo '<h4 style="color: #265044; font-weight: bold;">' . ucfirst(get_phrase('no_guardian_data_found')) . '</h4>';
                    }
                ?>
            </div>
        </div>
    </div>



  
    </div>


    <?php endforeach;?>



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
