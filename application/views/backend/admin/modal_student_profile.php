<?php
$student_info = $this->crud_model->get_student_info($param2);
foreach($student_info as $row):?>


<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">
    <i class="entypo-user"></i>
    <?php
        if ($param4 == 'teacher_aide') {
            $info = $this->crud_model->get_teacher_aide_info($param3);
            foreach($info as $row9):
    ?>
                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_teacher_aide_profile/<?php echo $row9['teacher_aide_id'];?>');" class="breadcrumb-link">
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
            $info = $this->crud_model->get_teacher_info($param3);
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
        } elseif ($param4 == 'guardian') {
            $info = $this->crud_model->get_guardian_info($param3);
            foreach($info as $row9):
    ?>
                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_guardian_profile/<?php echo $row9['guardian_id'];?>');" class="breadcrumb-link">
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
        } elseif ($param4 == 'subject') {
            $info = $this->crud_model->get_subject_info($param3);
            foreach($info as $row9):
    ?>
                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_subject_profile/<?php echo $row9['subject_id'];?>');" class="breadcrumb-link">
                <?php
                    echo ucfirst($row9['name']);
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
        <img src="<?php echo $row['photo'];?>" class="img-fluid" alt="Profile Picture" width="120" height="120">
        <div class="profile-info">
            <h2 style="font-weight: 600;">
            <?php echo $row['lastname'];?>, <?php echo $row['firstname'];?>.
                <span class="status-dot offline" title="desconectado"></span> 
            </h2>
            <!-- <p style="color: #265044; font-weight: 400;">@JuliRP484 <span style="color: #265044; font-weight: bolder;">|</span> <?php echo $row['email'];?></p> -->
            <p style="color: #265044; font-weight: 400;"><span style="color: #265044; font-weight: bold;">Ultima conexión:</span> hace 26 minutos.</p>
        </div>
        <div class="profile-buttons">
            <button class="btn btn-secondary"><i class="entypo-pencil"></i> Modificar</button>
            <button class="btn btn-secondary"><i class="entypo-chat" style="font-size: 12px;"></i> Mensaje</button>
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
            <h4 class="card-title" style="font-weight: bold;">Información personal</h4>
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
                    <strong class="info-title"><i class="entypo-user"></i> Usuario</strong>
                    <span class="info-cell"><?php echo $row['username'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="fa fa-phone"></i> Teléfono celular</strong>
                    <span class="info-cell"><?php echo $row['phone_cel'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="fa fa-phone"></i> Teléfono fijo</strong>
                    <span class="info-cell"><?php echo $row['phone_fij'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-calendar"></i> Fecha de nacimiento</strong>
                    <span class="info-cell"><?php echo $row['birthday'];?></span>
                </li>
                <li>
                    <strong class="info-title"><?php echo ($row['gender_id'] == '0') ? '<i class="fa fa-mars"></i>' : '<i class="fa fa-venus"></i>'; ?> Genero</strong>
                    <span class="info-cell"><?php echo ($row['gender_id'] == '0') ? 'Hombre' : 'Mujer'; ?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-location"></i> Localidad</strong>
                    <span class="info-cell"><?php echo $row['locality'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-location"></i> Barrio</strong>
                    <span class="info-cell"><?php echo $row['neighborhood'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-location"></i> Calle</strong>
                    <span class="info-cell"><?php echo $row['address_line'];?></span>
                </li>
            </ul>
        </div>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;">Información académica</h4>
            <br>
            <ul class="info-list">
                <li>
                    <strong class="info-title"><i class="entypo-graduation-cap"></i> Matricula</strong>
                    <span class="info-cell"><?php echo $row['enrollment'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> Curso</strong>
                    <span class="info-cell"><?php echo $this->crud_model->get_class_name_numeric($row['class_id']);?>°</span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> División</strong>
                    <span class="info-cell"><?php echo $this->db->get_where('section' , array('section_id' => $row['section_id']))->row()->letter_name;?></span>
                </li>
                <li>
                    <?php
                        $teacher_aide_info = $this->crud_model->get_teacher_aide_info_per_section($row['section_id']);
                        foreach($teacher_aide_info as $row2):?>
                    <strong class="info-title"><i class="entypo-user"></i> Preceptor</strong>
                </li>
                <div>
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card-body text-center">
                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_teacher_aide_profile/<?php echo $row2['teacher_aide_id'];?>/<?php echo $row['student_id'];?>/<?php echo 'student';?>');">
                    <img src="<?php echo $row2['photo'];?>" class="img-circle" alt="Profile Picture" width="80" height="80">
                    <h3 style="font-weight: 600;"><?php echo $row2['lastname'];?>, <?php echo $row2['firstname'];?>.</h3>
                </a>
            </div>
        </div>
    </div>
</div>

                <?php endforeach;?>
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> Profesores</strong>
                </li>
                <?php
                    $teacher_info = $this->crud_model->get_teacher_info_per_section($row['section_id']);
                    foreach($teacher_info as $teacher):?>
                    <div>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="card-body text-center">
                                    <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_teacher_profile/<?php echo $teacher['teacher_id'];?>/<?php echo $row['student_id'];?>/<?php echo 'student';?>');">
                                        <img src="<?php echo $teacher['photo']; ?>" class="img-circle" alt="Profile Picture" width="80" height="80">
                                        <h3 style="font-weight: 600;">
                                            <?php 
                                            echo ucwords(strtolower($teacher['lastname'])) . ', ' . ucwords(strtolower(trim($teacher['firstname']))) . '.';
                                            ?>
                                        </h3>
                                    </a>
                                    <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_subject_profile/<?php echo $teacher['subject_id'];?>/<?php echo $row['student_id'];?>/<?php echo 'student';?>');"> 
                                        <p style="color: #265044; font-weight: 600;"><i class="entypo-docs"></i> Materia: <span style="color: #265044; font-weight: 400;"><?php echo ucfirst($teacher['subject_name']); ?></span></p>
                                    </a>  
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <h4 class="card-title" style="font-weight: bold;">Información de tutores</h4>
        <br>
        <?php
            $guardians_info = $this->crud_model->get_guardian_info_per_student($row['student_id']);
            foreach($guardians_info as $guardian):?>
        <div class="card-body card-body-guardian">
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_guardian_profile/<?php echo $guardian['guardian_id'];?>/<?php echo $row['student_id'];?>/<?php echo 'student';?>');">
            <div class="text-center">
                <img src="<?php echo $guardian['photo'];?>" class="img-circle" alt="Profile Picture" width="80" height="80">
            </div>
            <div class="text-center">
                <h3 style="font-weight: 600;"><?php echo $guardian['lastname'];?>, <?php echo $guardian['firstname'];?>.</h3>
            </a>   
                <p style="color: #265044; font-weight: 600;"><i class="entypo-users"></i>Tipo de relación: 
                    <span style="color: #265044; font-weight: 400;">
                        <?php 
                            if ($guardian['guardian_type_id'] == 1) {
                                echo 'Padre';
                            } elseif ($guardian['guardian_type_id'] == 2) {
                                echo 'Madre';
                            } elseif ($guardian['guardian_type_id'] == 3) {
                                echo 'Tío';
                            } elseif ($guardian['guardian_type_id'] == 4) {
                                echo 'Tía';
                            } elseif ($guardian['guardian_type_id'] == 5) {
                                echo 'Abuelo';
                            } elseif ($guardian['guardian_type_id'] == 6) {
                                echo 'Abuela';
                            } elseif ($guardian['guardian_type_id'] == 7) {
                                echo 'Otro';
                            } else {
                                echo 'Desconocido';
                            }
                        ?>
                    </span>
                </p>

            </div>
            <br>
            <ul class="info-list">
                <li>
                    <strong class="info-title"><i class="fa fa-address-card"></i> Dni</strong>
                    <span class="info-cell"><?php echo $guardian['dni'];?></span>
                </li>
               
                <li>
                    <strong class="info-title"><i class="entypo-mail"></i> Email</strong>
                    <span class="info-cell"><?php echo $guardian['email'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> Usuario</strong>
                    <span class="info-cell"><?php echo $guardian['username'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="fa fa-phone"></i> Teléfono celular</strong>
                    <span class="info-cell"><?php echo $guardian['phone_cel'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="fa fa-phone"></i> Teléfono fijo</strong>
                    <span class="info-cell"><?php echo $guardian['phone_fij'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-calendar"></i> Fecha de nacimiento</strong>
                    <span class="info-cell"><?php echo $guardian['birthday'];?></span>
                </li>
                <li>
                    <strong class="info-title"><?php echo ($guardian['gender_id'] == '0') ? '<i class="fa fa-mars"></i>' : '<i class="fa fa-venus"></i>'; ?> Genero</strong>
                    <span class="info-cell"><?php echo ($guardian['gender_id'] == '0') ? 'Hombre' : 'Mujer'; ?></span>
            </ul>
        </div>
        <br>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal-footer text-center" style="text-align: center;">
    <!-- <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Cerrar</button> -->
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="entypo-cancel"></i>&nbsp;&nbsp;Cerrar</button>
</div>

<?php endforeach;?>

<style>
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
