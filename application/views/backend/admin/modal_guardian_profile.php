<?php
$guardian_info = $this->Guardians_model->get_guardian_info($param2);
foreach($guardian_info as $row):?>


<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">
        <i class="entypo-user"></i>
        <?php
        if ($param4 == 'student') {
            $info = $this->Students_model->get_student_info($param3);
            foreach ($info as $row9):
                $lastname = !empty($row9['lastname']) ? $row9['lastname'] : '';
                $firstname_initial = !empty($row9['firstname']) ? mb_substr($row9['firstname'], 0, 1) . '.' : '';
                ?>
                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_student_profile/<?php echo $row9['student_id'];?>');" class="breadcrumb-link">
                    <?php echo $lastname . ($lastname && $firstname_initial ? ', ' : '') . $firstname_initial; ?>
                </a>
                &nbsp; / &nbsp;
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
        <h4 class="card-title" style="font-weight: bold;">Información de hijos</h4>
        <br>
        <?php
            $students_info = $this->Students_model->get_student_info_per_guardian($row['guardian_id']);
            foreach($students_info as $student):?>
        <div class="card-body card-body-guardian">
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_student_profile/<?php echo $student['student_id'];?>/<?php echo $row['guardian_id'];?>/<?php echo 'guardian';?>');">
            <div class="text-center">
                <img src="<?php echo $student['photo'];?>" class="img-circle" alt="Profile Picture" width="80" height="80">
            </div>
            <div class="text-center">
                <h3 style="font-weight: 600;"><?php echo $student['lastname'];?>, <?php echo $student['firstname'];?>.</h3>
            </a>   
            </div>
            <br>
            <ul class="info-list">
                <li>
                    <strong class="info-title"><i class="fa fa-address-card"></i> Dni</strong>
                    <span class="info-cell"><?php echo $student['dni'];?></span>
                </li>
               
                <li>
                    <strong class="info-title"><i class="entypo-mail"></i> Email</strong>
                    <span class="info-cell"><?php echo $student['email'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> Usuario</strong>
                    <span class="info-cell"><?php echo $student['username'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="fa fa-phone"></i> Teléfono celular</strong>
                    <span class="info-cell"><?php echo $student['phone_cel'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="fa fa-phone"></i> Teléfono fijo</strong>
                    <span class="info-cell"><?php echo $student['phone_fij'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-calendar"></i> Fecha de nacimiento</strong>
                    <span class="info-cell"><?php echo $student['birthday'];?></span>
                </li>
                <li>
                    <strong class="info-title"><?php echo ($student['gender_id'] == '0') ? '<i class="fa fa-mars"></i>' : '<i class="fa fa-venus"></i>'; ?> Genero</strong>
                    <span class="info-cell"><?php echo ($student['gender_id'] == '0') ? 'Hombre' : 'Mujer'; ?></span>
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
