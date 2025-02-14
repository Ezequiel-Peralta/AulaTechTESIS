<?php 

$edit_data = $this->db->select('events.*, event_visibility.visible_to, event_visibility.visible_to_category , event_visibility.visible_to_id, event_visibility.event_visibility_id, event_visibility.visibility_for_creator, event_visibility.visible_edit, event_visibility.visible_delete')
                     ->from('events')
                     ->join('event_visibility', 'events.event_id = event_visibility.event_id', 'left')
                     ->where('events.event_id', $param2)
                     ->get()
                     ->result_array();

foreach ($edit_data as $row):
?>

<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="entypo-pencil"></i><?php echo 'Editar evento' ?></h4>
</div>

<?php echo form_open(base_url() . 'index.php?admin/events/update/' . $row['event_id'], array('class' => 'form-horizontal form-groups-bordered validate')); ?>

<div class="modal-body" style="height:500px; overflow:auto;">
    <div class="row">
        <div class="col-md-12">
            <div class="panel" data-collapsed="0">
                <div class="panel-body">
                    <!-- Nombre del Evento -->
                    <div class="form-group">
                        <div class="group">
                            <input required="" type="text" class="input" name="title" value="<?php echo $row['title']; ?>" data-message-required="<?php echo ('Value Required'); ?>">
                            <span class="bar"></span>
                            <label>Titulo</label>
                        </div>
                    </div>
                    <br>

                    <div class="form-group">
                        <div class="group">
                            <input required="" type="text" class="input" name="body" value="<?php echo $row['body']; ?>" data-message-required="<?php echo ('Value Required'); ?>">
                            <span class="bar"></span>
                            <label>Cuerpo</label>
                        </div>
                    </div>
                    <br>

                    <!-- Fecha o Fecha/Hora de Inicio/Fin -->
                    <?php if (!is_null($row['date'])): ?>
                    <!-- Evento de un día -->
                    <div class="form-group">
                        <div class="group">
                            <input required="" type="date" class="input" name="date" value="<?php echo date('Y-m-d', strtotime($row['date'])); ?>" data-message-required="<?php echo ('Value Required'); ?>">
                            <span class="bar"></span>
                            <label class="label-date">Fecha</label>
                        </div>
                    </div>

                    <?php elseif (!is_null($row['start']) && is_null($row['end'])): ?>
                    <!-- Evento con solo hora de inicio -->
                    <div class="form-group">
                        <div class="group">
                            <input required="" type="datetime-local" class="input" name="start" id="start" value="<?php echo date('Y-m-d\TH:i', strtotime($row['start'])); ?>" data-message-required="<?php echo ('Value Required'); ?>">
                            <span class="bar"></span>
                            <label class="label-date">Fecha y Hora de inicio</label>
                        </div>
                    </div>

                    <?php elseif (!is_null($row['start']) && !is_null($row['end'])): ?>
                    <!-- Evento con inicio y fin -->
                    <div class="form-group">
                        <div class="group">
                            <input required="" type="datetime-local" class="input" name="start" id="start" value="<?php echo date('Y-m-d\TH:i', strtotime($row['start'])); ?>" data-message-required="<?php echo ('Value Required'); ?>">
                            <span class="bar"></span>
                            <label class="label-date">Fecha y Hora de inicio</label>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="group">
                            <input required="" type="datetime-local" class="input" name="end" id="end" value="<?php echo date('Y-m-d\TH:i', strtotime($row['end'])); ?>" data-message-required="<?php echo ('Value Required'); ?>">
                            <span class="bar"></span>
                            <label class="label-date">Fecha y Hora de finalización</label>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Selección de color -->
                    <div class="form-group">
                        <div class="group">
                            <label>Estilo de color</label>
                            <br><br>
                            <select name="color" id="color" class="form-control select">
                                <option value="random" <?php echo ($row['color'] == 'random' || empty($row['color'])) ? 'selected' : ''; ?>>Seleccionar</option>
                                <option value="color-blue" <?php echo ($row['color'] == 'color-blue') ? 'selected' : ''; ?>>Azul</option>
                                <option value="color-orange" <?php echo ($row['color'] == 'color-orange') ? 'selected' : ''; ?>>Naranja</option>
                                <option value="color-green" <?php echo ($row['color'] == 'color-green') ? 'selected' : ''; ?>>Verde</option>
                                <option value="color-red" <?php echo ($row['color'] == 'color-red') ? 'selected' : ''; ?>>Rojo</option>
                                <option value="color-yellow" <?php echo ($row['color'] == 'color-yellow') ? 'selected' : ''; ?>>Amarillo</option>
                                <option value="color-purple" <?php echo ($row['color'] == 'color-purple') ? 'selected' : ''; ?>>Púrpura</option>
                                <option value="color-pink" <?php echo ($row['color'] == 'color-pink') ? 'selected' : ''; ?>>Rosa</option>
                                <option value="color-lightblue" <?php echo ($row['color'] == 'color-lightblue') ? 'selected' : ''; ?>>Celeste</option>
                                <option value="color-gray" <?php echo ($row['color'] == 'color-gray') ? 'selected' : ''; ?>>Gris</option>
                                <option value="color-brown" <?php echo ($row['color'] == 'color-brown') ? 'selected' : ''; ?>>Marrón</option>
                            </select>
                            <span class="bar"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="group">
                            <label>Tipo de evento</label>
                            <br><br>
                            <select name="type" id="type" class="form-control select">
                                <option value="meeting" <?php echo ($row['type'] == 'meeting') ? 'selected' : ''; ?>>Reunion</option>
                                <option value="extracurricular-activity" <?php echo ($row['type'] == 'extracurricular-activity') ? 'selected' : ''; ?>>Actividad extracurricular</option>
                                <option value="classes-lessons" <?php echo ($row['type'] == 'classes-lessons') ? 'selected' : ''; ?>>Clases / lecciones</option>
                                <option value="assignments-exams" <?php echo ($row['type'] == 'assignments-exams') ? 'selected' : ''; ?>>Trabajos / examenes</option>
                                <option value="holidays-vacations" <?php echo ($row['type'] == 'holidays-vacations') ? 'selected' : ''; ?>>Dias festivos / vacaciones</option>
                                <option value="special-event" <?php echo ($row['type'] == 'special-event') ? 'selected' : ''; ?>>Evento especial</option>
                                <option value="tutoring-advising" <?php echo ($row['type'] == 'tutoring-advising') ? 'selected' : ''; ?>>Tutorias / asesorias</option>
                                <option value="deadline" <?php echo ($row['type'] == 'deadline') ? 'selected' : ''; ?>>Fecha limite</option>
                                <option value="excursions-trips" <?php echo ($row['type'] == 'excursions-trips') ? 'selected' : ''; ?>>Excursiones / salidas</option>
                            </select>
                            <span class="bar"></span>
                        </div>
                    </div>

                    <!-- Selección de visibilidad -->
                    <div class="form-group" id="form-group-users-list-modal">
                        <div class="group">
                            <label for="users-list-modal">Tipo de Usuario</label>
                            <br><br>
                            <select name="users-list-modal" id="users-list-modal" class="form-control select">
                                <option value="my_account" <?php echo ($row['visible_to'] == 'my_account') ? 'selected' : ''; ?> >Mi cuenta</option>
                                <option value="admin" <?php echo ($row['visible_to'] == 'admin') ? 'selected' : ''; ?> >Administradores</option>
                                <option value="students" <?php echo ($row['visible_to'] == 'students') ? 'selected' : ''; ?> >Estudiantes</option>
                                <option value="guardians" <?php echo ($row['visible_to'] == 'guardians') ? 'selected' : ''; ?> >Padres</option>
                                <option value="teachers" <?php echo ($row['visible_to'] == 'teachers') ? 'selected' : ''; ?> >Profesores</option>
                                <option value="teachers_aide" <?php echo ($row['visible_to'] == 'teachers_aide') ? 'selected' : ''; ?> >Preceptores</option>
                            </select>
                            <span class="bar"></span>
                        </div>
                       
                    </div>

                    <div class="form-group" id="form-group-user-student-option-modal" style="<?php echo ($row['visible_to_category'] == 'All' || $row['visible_to_category'] == 'PerClass' || $row['visible_to_category'] == 'PerSection') ? '' : 'display:none;'; ?>">
                        <div class="group">
                            <label for="user-student-option-modal">Opciones Estudiantes</label>
                            <br><br>
                            <select name="user-student-option-modal" id="user-student-option-modal" class="form-control select" style="<?php echo ($row['visible_to_category'] == 'All' || $row['visible_to_category'] == 'PerClass' || $row['visible_to_category'] == 'PerSection') ? '' : 'display:none;'; ?>">
                                <option value="">Seleccionar</option>
                                <option value="All" <?php echo ($row['visible_to_category'] == 'All') ? 'selected' : ''; ?> >Todos</option>
                                <option value="PerClass" <?php echo ($row['visible_to_category'] == 'PerClass') ? 'selected' : ''; ?> >Por curso</option>
                                <option value="PerSection" <?php echo ($row['visible_to_category'] == 'PerSection') ? 'selected' : ''; ?> >Por división</option>
                            </select>
                            <span class="bar"></span>
                        </div>
                    </div>

                    <div class="form-group" id="form-group-user-admin-option-modal" style="<?php echo ($row['visible_to_category'] == 'All' && $row['visible_to'] == 'admin' || $row['visible_to_category'] == 'PerUser' && $row['visible_to'] == 'admin') ? '' : 'display:none;'; ?>">
                        <div class="group">
                            <label for="user-admin-option-modal">Opciones Administradores</label>
                            <br><br>
                            <select name="user-admin-option-modal" id="user-admin-option-modal" class="form-control select" style="<?php echo ($row['visible_to_category'] == 'All' && $row['visible_to'] == 'admin' || $row['visible_to_category'] == 'PerUser' && $row['visible_to'] == 'admin') ? '' : 'display:none;'; ?>">
                                <option value="">Seleccionar</option>
                                <option value="All" <?php echo ($row['visible_to_category'] == 'All' && $row['visible_to'] == 'admin') ? 'selected' : ''; ?> >Todos</option>
                                <option value="PerUser" <?php echo ($row['visible_to_category'] == 'PerUser' && $row['visible_to'] == 'admin') ? 'selected' : ''; ?> >Por usuario</option>
                            </select>
                            <span class="bar"></span>
                        </div>
                        
                    </div>

                    <div class="form-group" id="form-group-content-class-list-modal" style="<?php echo ($row['visible_to_category'] == 'PerClass') ? '' : 'display:none;'; ?>">
                        <div class="group">
                            <label for="content-class-list-modal">Lista de Clases</label>
                            <br><br>
                            <select name="content-class-list-modal" id="content-class-list-modal" class="form-control select" style="<?php echo ($row['visible_to_category'] == 'PerClass') ? '' : 'display:none;'; ?>"></select>
                            <span class="bar"></span>
                        </div>
                        
                    </div>

                    <div class="form-group" id="form-group-content-sections-list-modal" style="<?php echo ($row['visible_to_category'] == 'PerSection') ? '' : 'display:none;'; ?>">
                        <div class="group">
                            <label for="content-sections-list-modal">Lista de Divisiones</label>
                            <br><br>
                            <select name="content-sections-list-modal" id="content-sections-list-modal" class="form-control select" style="<?php echo ($row['visible_to_category'] == 'PerSection') ? '' : 'display:none;'; ?>"></select>
                            <span class="bar"></span>
                        </div>
                       
                    </div>

                    <div class="form-group" id="form-group-content-admin-list-modal" style="<?php echo ($row['visible_to'] == 'admin' && $row['visible_to_category'] == 'PerUser') ? '' : 'display:none;'; ?>">
                        <div class="group">
                            <label for="content-admin-list-modal">Lista de Administradores</label>
                            <br><br>
                            <select name="content-admin-list-modal" id="content-admin-list-modal" class="form-control select" style="<?php echo ($row['visible_to'] == 'admin' && $row['visible_to_category'] == 'PerUser') ? '' : 'display:none;'; ?>"></select>
                            <span class="bar"></span>
                        </div>
                 
                    </div>

                    <div class="form-group" id="form-group-visibility-for-creator">
                        <div class="group">
                            <label for="content-visibility-for-creator-modal">Visible para el creador del evento</label>
                            <br><br>
                            <input type="checkbox" class="input" name="content-visibility-for-creator-modal" id="content-visibility-for-creator-modal" 
                            <?php echo ($row['visibility_for_creator'] == 1) ? 'checked' : ''; ?>>
                        </div>
                    </div>

                    <div class="form-group" id="form-group-visible-edit">
                        <div class="group">
                            <label for="content-visible-edit-modal">El que ve el evento puede editar</label>
                            <br><br>
                            <input type="checkbox" class="input" name="content-visible-edit-modal" id="content-visible-edit-modal" 
                            <?php echo ($row['visible_edit'] == 1) ? 'checked' : ''; ?>>
                        </div>
                    </div>

                    <div class="form-group" id="form-group-visible-delete">
                        <div class="group">
                            <label for="content-visible-delete-modal">El que ve el evento puede eliminar</label>
                            <br><br>
                            <input type="checkbox" class="input" name="content-visible-delete-modal" id="content-visible-delete-modal" 
                            <?php echo ($row['visible_delete'] == 1) ? 'checked' : ''; ?>>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer text-center" style="text-align: center;">
    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload();"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Volver</button>
    <button type="submit" class="btn btn-success"><i class="entypo-floppy"></i> Guardar</button>
</div>

<?php echo form_close(); ?> 

<?php endforeach; ?>




<script type="text/javascript">
$(document).ready(function() {
    $('#users-list-modal').change(function() {
        var user_type = $(this).val();

        console.log('cambio en tipo de usuario, el valor es: ', user_type);
        
        if (user_type === 'students' || user_type === 'guardians' || user_type === 'teachers' || user_type === 'teachers_aide') {
            $('#user-student-option-modal').css('display', 'block'); 
            $('#form-group-user-student-option-modal').css('display', 'block'); 
            $('#user-admin-option-modal').css('display', 'none'); 
            $('#form-group-user-admin-option-modal').css('display', 'none'); 
        } else if(user_type === 'admin') {
            $('#user-student-option-modal').css('display', 'none'); 
            $('#form-group-user-student-option-modal').css('display', 'none');
            $('#user-admin-option-modal').css('display', 'block'); 
            $('#form-group-user-admin-option-modal').css('display', 'block'); 
        } else {
            $('#user-student-option-modal').css('display', 'none');
            $('#user-admin-option-modal').css('display', 'none'); 
            $('#content-class-list-modal').css('display', 'none'); 
            $('#content-section-list-modal').css('display', 'none'); 
            $('#content-admin-list-modal').css('display', 'none'); 

            $('#form-group-user-student-option-modal').css('display', 'none');
            $('#form-group-user-admin-option-modal').css('display', 'none'); 
            $('#form-group-content-class-list-modal').css('display', 'none'); 
            $('#form-group-content-sections-list-modal').css('display', 'none'); 
            $('#form-group-content-admin-list-modal').css('display', 'none'); 
        }
    });

    $('#user-student-option-modal').change(function() {
        var user_type = $('#users-list-modal').val();
        var user_option = $(this).val();

        console.log('cambio en opciones de usuario, el valor es: ', user_option);
        
        if (user_option === 'PerClass') {
            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_class_content2/',
                success: function(response) {
                    $('#content-class-list-modal').html(response).css('display', 'block');
                    $('#form-group-content-class-list-modal').css('display', 'block');

                    // Suponiendo que row['visible_to_id'] es una variable de PHP accesible aquí
                    var visibleToId = '<?php echo $row['visible_to_id']; ?>';

                    // Recorremos todas las opciones dentro del select en 'content-class-list-modal'
                    $('#content-class-list-modal select option').each(function() {
                        if ($(this).val() === visibleToId) {
                            // Marcamos la opción como seleccionada si coincide
                            $(this).prop('selected', true);
                        }
                    });
                }
            });
        } else {
            $('#content-class-list-modal').css('display', 'none'); 
            $('#form-group-content-class-list-modal').css('display', 'none'); 
        }

        if (user_option === 'PerSection') {
            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_section_content2/',
                success: function(response) {
                    $('#content-sections-list-modal').html(response).css('display', 'block');
                    $('#form-group-content-sections-list-modal').css('display', 'block');
                }
            });
        } else {
            $('#content-sections-list-modal').css('display', 'none'); 
            $('#form-group-content-sections-list-modal').css('display', 'none'); 
        }
    });

    $('#user-admin-option-modal').change(function() {
        var user_type = $('#users-list-modal').val();
        var user_option = $(this).val();

        console.log('cambio en opciones de admin, el valor es: ', user_option);
        
        if (user_option === 'PerUser') {
            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_admin_users_content/',
                success: function(response) {
                    $('#content-admin-list-modal').html(response).css('display', 'block');
                    $('#form-group-content-admin-list-modal').css('display', 'block');
                }
            });
        } else {
            $('#content-admin-list-modal').css('display', 'none'); 
            $('#form-group-content-admin-list-modal').css('display', 'none'); 
        }
       
    });

    var visibleTo = '<?php echo $row['visible_to']; ?>';
    var visibleToCategory = '<?php echo $row['visible_to_category']; ?>';
    var visibleToId = '<?php echo $row['visible_to_id']; ?>';

    if (visibleTo === 'admin' && visibleToCategory === 'PerUser') {
        $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_admin_users_content/',
                success: function(response) {
                    $('#content-admin-list-modal').html(response).css('display', 'block');
                    $('#form-group-content-admin-list-modal').css('display', 'block');

                    $('#content-admin-list-modal option').each(function() {
                        if ($(this).val() === visibleToId) {
                            $(this).prop('selected', true);
                        }
                    });
                }
            });
    } else if (visibleToCategory === 'PerClass') {
        console.log('se ejecuto el if de per class');
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_content2/',
            success: function(response) {
                $('#content-class-list-modal').html(response).css('display', 'block');
                $('#form-group-content-class-list-modal').css('display', 'block');

                console.log('se introduce las class en el select');

                // Recorremos todas las opciones dentro del select en 'content-class-list-modal'
                $('#content-class-list-modal option').each(function() {
                    if ($(this).val() === visibleToId) {
                        // Marcamos la opción como seleccionada si coincide
                        $(this).prop('selected', true);

                        console.log('se encontro una coincidencia en el select de class');
                    }
                });
            }
        });
    } else if (visibleToCategory === 'PerSection') {
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_section_content2/',
            success: function(response) {
                $('#content-sections-list-modal').html(response).css('display', 'block');
                $('#form-group-content-sections-list-modal').css('display', 'block');

                $('#content-sections-list-modal option').each(function() {
                    if ($(this).val() === visibleToId) {
                        $(this).prop('selected', true);
                    }
                });
            }
        });
    } 

});

</script>


<style>
    .group {
        position: relative;
    }

    .input {
        font-size: 12px;
        color: #555555;
        padding: 10px 10px 10px 7px;
        display: block;
        width: 100%;
        border: none;
        border-bottom: 0px solid #515151;
        background: #eaf0ee;
        border-radius: 12px;
        transition: border-radius 0.2s ease; 
    }

    .select {
        font-size: 12px;
        color: #555555;
        padding: 0px 10px 0px 10px;
        display: block;
        width: 100%;
        border: none;
        border-bottom: 0px solid #515151;
        background: #eaf0ee;
        border-radius: 12px;
        transition: border-radius 0.2s ease; 
    }

    .select:focus {
        outline: none;
        border-radius: 0; 
        color: #265044;
        font-weight: bold; 
    }

    .input:focus {
        outline: none;
        border-radius: 0; 
        color: #265044;
        font-weight: bold; 
    }

    label {
        color: #999;
        font-size: 12px;
        font-weight: normal;
        position: absolute;
        pointer-events: none;
        left: 5px;
        top: 10px;
        transition: 0.2s ease all;
        -moz-transition: 0.2s ease all;
        -webkit-transition: 0.2s ease all;
    }

    .input:focus ~ label, .input:valid ~ label {
        top: -20px;
        font-size: 12px;
    }

    .bar {
        position: relative;
        display: block;
        width: 100%; /* Make sure the bar takes the full width of the input */
    }

    .bar:before, .bar:after {
        content: '';
        height: 2px;
        width: 0;
        bottom: 0px;
        position: absolute;
        background: #265044;
        transition: 0.2s ease all;
        -moz-transition: 0.2s ease all;
        -webkit-transition: 0.2s ease all;
    }

    .bar:before {
        left: 50%;
    }

    .bar:after {
        right: 50%;
    }

    .input:focus ~ .bar:before, .input:focus ~ .bar:after {
        width: 50%;
    }

    .highlight-input {
        position: absolute;
        height: 60%;
        width: 100px;
        top: 25%;
        left: 0;
        pointer-events: none;
        opacity: 0.5;
    }

    .input:focus ~ .highlight-input {
        animation: inputhighlight-inputer 0.3s ease;
    }

    .select:focus ~ .highlight-input {
        animation: inputhighlight-inputer 0.3s ease;
    }

    @keyframes inputhighlight-inputer {
        from {
            background: #5264AE;
        }

        to {
            width: 0;
            background: transparent;
        }
    }

    
</style>
