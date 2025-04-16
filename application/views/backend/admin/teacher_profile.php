<?php
$teacher_info = $this->Teachers_model->get_teacher_info2($param2);
foreach($teacher_info as $row):?>

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
            <a href="<?php echo base_url(); ?>index.php?admin/view_exams/0/0/<?php echo $row['teacher_id']; ?>" class="btn btn-secondary">
                <i class="entypo-doc-text-inv"></i>
                <?php echo ucfirst(get_phrase('exams'));?>
            </a> 
            <a href="<?php echo base_url(); ?>index.php?admin/view_schedules/0/<?php echo $row['teacher_id']; ?>" class="btn btn-secondary">
                <i class="entypo-clock"></i>
                <?php echo ucfirst(get_phrase('schedules'));?>
            </a> 
            <a href="<?php echo base_url(); ?>index.php?admin/view_subjects/0/<?php echo $row['teacher_id']; ?>" class="btn btn-secondary">
                <i class="entypo-docs"></i>
                <?php echo ucfirst(get_phrase('subjects'));?>
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
                    <strong class="info-title"><i class="entypo-calendar"></i> <?php echo ucfirst(get_phrase('birthday'));?></strong>
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
            <h4 class="card-title" style="font-weight: bold;"><?php echo ucfirst(get_phrase('academic_information')); ?></h4>
            <br>
                <div class="profile-container">
                    <?php
                        // Obtiene las secciones asociadas al teacher_id desde la tabla subject
                        $this->db->where('teacher_id', $row['teacher_id']);
                        $subjects_teacher = $this->db->get('subject')->result_array();

                        // Arreglo auxiliar para evitar duplicados
                        $processed_sections = [];

                        foreach ($subjects_teacher as $subject_teacher) {
                            // Obtiene el section_id de cada registro
                            $section_id = $subject_teacher['section_id'];

                            // Verifica si esta sección ya fue procesada
                            if (!in_array($section_id, $processed_sections)) {
                                // Busca la información de la sección en la tabla section
                                $this->db->where('section_id', $section_id);
                                $section = $this->db->get('section')->row_array();

                                if ($section): ?>
                                    <a href="<?php echo base_url(); ?>index.php?admin/sections_profile/<?php echo $section['section_id']; ?>">
                                        <div class="profile-card">
                                            <h3 style="font-weight: 600;">
                                                <?php echo ucfirst($section['name']); ?>
                                            </h3>
                                        </div>
                                    </a>
                                    <?php
                                    // Marca esta sección como procesada
                                    $processed_sections[] = $section_id;
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
                    // Obtiene los subjects asociados al teacher_id desde la tabla subject
                    $this->db->where('teacher_id', $row['teacher_id']);
                    $subjects = $this->db->get('subject')->result_array();

                    // Recorre cada subject y busca el nombre de la sección
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
