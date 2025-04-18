<?php

if (!empty($subject_info)) {
    $subject = $subject_info[0]; 
} 
?>

    <div class="profile-header" style="border-radius: 15px;">
        <img src="assets/images/photo-header.png" class="cover-photo" alt="Cover Photo">
        <img 
    src="<?php 
        $imagePath = 'uploads/subject_image/' . $subject['image'];
        if (!file_exists($imagePath)) {
            $imagePath = 'uploads/subject_image_history/' . $subject['image'];
        }
        echo $imagePath;
    ?>" class="img-fluid" alt="Profile Picture" width="120" height="120">
        <div class="profile-info">
            <h2 style="font-weight: 600;">
            <?php echo ucfirst($subject['name']);?>
            </h2>
        </div>
        <div class="profile-buttons">
            <a class="btn btn-secondary profile-button-active"><i class="entypo-vcard"></i> <?php echo ucfirst(get_phrase('information'));?></a>
            <a href="<?php echo base_url(); ?>index.php?admin/view_students_mark/<?php echo $subject['section_id']; ?>/<?php echo $subject['subject_id']; ?>" style="font-size: 12px;" class="btn btn-secondary">
                <i class="entypo-vcard"></i>
                <?php echo ucfirst(get_phrase('marks_section'));?>
            </a> 
            <a href="<?php echo base_url(); ?>index.php?admin/view_exams/<?php echo $subject['section_id']; ?>/<?php echo $subject['subject_id']; ?>" style="font-size: 12px;" class="btn btn-secondary">
                <i class="entypo-doc-text-inv"></i>
                <?php echo ucfirst(get_phrase('exams_section'));?>
            </a> 
            <a href="<?php echo base_url(); ?>index.php?admin/view_schedules/<?php echo $subject['section_id']; ?>" class="btn btn-secondary">
                <i class="entypo-clock"></i>
                <?php echo ucfirst(get_phrase('schedules'));?>
            </a> 
            <a href="<?php echo base_url(); ?>index.php?admin/view_library/<?php echo $subject['section_id']; ?>/<?php echo $subject['subject_id']; ?>" style="font-size: 12px;" class="btn btn-secondary">
                <i class="entypo-book"></i>
                <?php echo ucfirst(get_phrase('library'));?>
            </a> 
          
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
                    <span class="info-cell"><?php echo ucfirst($subject['name']);?></span>
                </li>
               
                <li>
                    <strong class="info-title"><i class="entypo-graduation-cap"></i> <?php echo ucfirst(get_phrase('class'));?></strong>
                    <span class="info-cell"><?php echo $this->crud_model->get_section_name2($subject['section_id']);?></span>
                </li>
          
                <li>
                    <strong class="info-title"><i class="entypo-graduation-cap"></i> <?php echo ucfirst(get_phrase('academic_period'));?></strong>
                    <span class="info-cell"><?php echo $this->crud_model->get_academic_period_name_per_section2($subject['section_id']);?></span>
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
                    $teacher_aide_info = $this->TeachersAide_model->get_teacher_aide_info_per_section($subject['section_id']);
                    if (!empty($teacher_aide_info)) {
                        foreach ($teacher_aide_info as $row2): ?>
                        <a href="<?php echo base_url(); ?>index.php?admin/teachers_aide_profile/<?php echo $row2['teacher_aide_id']; ?>">
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
            <h4 class="card-title" style="font-weight: bold;"><?php echo ucfirst(get_phrase('teacher')); ?></h4>
            <br>
            <a href="<?php echo base_url(); ?>index.php?admin/teachers_profile/<?php echo $subject['teacher_id'];?>">
                <div class="profile-container">
                    <?php
                        $teacher_info = $this->Teachers_model->get_teacher_info_per_subject($subject['subject_id']);
                        ?>
                        <div class="profile-card"> 
                
                                <img src="<?php echo $teacher_info['photo']; ?>" class="img-circle" alt="Profile Picture" width="80" height="80">
                                <h3 style="font-weight: 600;">
                                    <?php 
                                    echo ucwords(($teacher_info['lastname'])) . ', ' . ucwords(($teacher_info['firstname'])) . '.';
                                    ?>
                                </h3>
                        </div>
                </div>
            </a>
        </div>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"> <?php echo ucfirst(get_phrase('students')); ?></h4>
            <br>
                <div class="profile-container"> 
                    <?php
                        $student_info = $this->Students_model->get_student_info_per_section2($subject['section_id']);
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
