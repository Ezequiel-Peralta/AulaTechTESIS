<div class="row">
	<div class="col-md-12">
		<div class="row">            
            <div class="col-md-4">
                <a href="<?php echo base_url(); ?>index.php?admin/student_information/17">
                    <div class="tile-stats tile-white">
                        <div class="icon">
                            <i class="entypo-graduation-cap student-icon" style="color:transparent; background-image: url('assets/images/light_mode/studentIcon.png'); background-size: cover;"></i>
                        </div>
                        <div class="num" data-start="0" data-end="<?php echo $this->db->count_all('student');?>" 
                                data-postfix="" data-duration="1500" data-delay="0">0</div>
                        <h3 class="sub-num"><?php echo ucfirst(get_phrase('students'));?></h3>
                    </div>
                </a>
            </div>
          
            <div class="col-md-4">
                <a href="<?php echo base_url(); ?>index.php?admin/teachers_information/">
                    <div class="tile-stats tile-white">
                        <div class="icon">
                            <i class="entypo-graduation-cap teacher-icon" style="color:transparent; background-image: url('assets/images/light_mode/teacherIcon.png'); background-size: cover;"></i>
                        </div>
                        <div class="num" data-start="0" data-end="<?php echo $this->db->count_all('teacher');?>" 
                            data-postfix="" data-duration="800" data-delay="0">0</div>
                        <h3 class="sub-num"><?php echo ucfirst(get_phrase('teachers'));?></h3>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="<?php echo base_url(); ?>index.php?admin/teachers_aide_information/">
                    <div class="tile-stats tile-white">
                        <div class="icon">
                            <i class="entypo-graduation-cap teacherAide-icon" style="color:transparent; background-image: url('assets/images/light_mode/teacherAideIcon.png'); background-size: cover;"></i>
                        </div>
                        <div class="num" data-start="0" data-end="<?php echo $this->db->count_all('teacher_aide');?>" 
                            data-postfix="" data-duration="800" data-delay="0">0</div>
                        <h3 class="sub-num"><?php echo ucfirst(get_phrase('teachers_aide'));?></h3>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="<?php echo base_url(); ?>index.php?admin/secretaries_information/">
                    <div class="tile-stats tile-white">
                        <div class="icon">
                            <i class="entypo-graduation-cap secretary-icon" style="color:transparent; background-image: url('assets/images/light_mode/secretaryIcon.png'); background-size: cover;"></i>
                        </div>
                        <div class="num" data-start="0" data-end="<?php echo $this->db->count_all('secretary');?>" 
                            data-postfix="" data-duration="500" data-delay="0">0</div>
                        <h3 class="sub-num"><?php echo ucfirst(get_phrase('secretaries'));?></h3>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="<?php echo base_url(); ?>index.php?admin/principal_information/">
                    <div class="tile-stats tile-white">
                        <div class="icon">
                            <i class="entypo-graduation-cap principal-icon" style="color:transparent; background-image: url('assets/images/light_mode/principalIcon.png'); background-size: cover;"></i>
                        </div>
                        <div class="num" data-start="0" data-end="<?php echo $this->db->count_all('principal');?>" 
                            data-postfix="" data-duration="500" data-delay="0">0</div>
                        <h3 class="sub-num"><?php echo ucfirst(get_phrase('principals'));?></h3>
                    </div>
                </a>
            </div>

    	</div>
    </div>
    </br>
    <div class="col-md-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="calendar-env">
                    <!-- Calendar Body -->
                    <div class="calendar-body">
                        
                        <div id="calendar"></div>
                        
                    </div>
                
                <!-- Sidebar -->
                <div class="calendar-sidebar">
                    
                    <!-- new task form -->
                    <div class="calendar-sidebar-row">
                    <h2 class="text-center"><?php echo ucfirst(get_phrase('event_panel')); ?></h2> 
                    <br>
                    <div class="panel-group joined panel-event" id="accordion-test-2">
    <!-- Acordión 1 -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseOne-2">
                    <?php echo ucfirst(get_phrase('event_creation')); ?>
                </a>
            </h4>
        </div>
        <div id="collapseOne-2" class="panel-collapse collapse in">
            <div class="panel-body">
                <form role="form" id="add_event_form">
                    <div class="input-group minimal" style="background-color: transparent;">
                        <br>
                        <label for="color-style-event" class="text-center"><?php echo ucfirst(get_phrase('select_a_color_style_for_the_event')); ?></label>
                        <p class="text-center" style="font-style:italic;"><?php echo ucfirst(get_phrase('if_you_do_not_select_any_value_it_will_be_chosen_randomly')); ?></p>
                        
                        <select name="color-style-event" id="color-style-event" class="form-control myselect">
                            <option value="random"><?php echo ucfirst(get_phrase('select')); ?></option>
                            <option value="color-blue"><?php echo ucfirst(get_phrase('blue')); ?></option>
                            <option value="color-orange"><?php echo ucfirst(get_phrase('orange')); ?></option>
                            <option value="color-green"><?php echo ucfirst(get_phrase('green')); ?></option>
                            <option value="color-red"><?php echo ucfirst(get_phrase('red')); ?></option>
                            <option value="color-yellow"><?php echo ucfirst(get_phrase('yellow')); ?></option>
                            <option value="color-purple"><?php echo ucfirst(get_phrase('purple')); ?></option>
                            <option value="color-pink"><?php echo ucfirst(get_phrase('pink')); ?></option>
                            <option value="color-gray"><?php echo ucfirst(get_phrase('gray')); ?></option>
                            <option value="color-brown"><?php echo ucfirst(get_phrase('brown')); ?></option>
                        </select>
                        <span class="bar"></span>
                        
                        <label for="event-type" class="text-center" style="margin: 10px 0px 10px 8px;"><?php echo ucfirst(get_phrase('select_event_type')); ?></label>
                        <select name="event-type" id="event-type" class="form-control myselect" >
                            <option value="meeting"><?php echo ucfirst(get_phrase('meeting')); ?></option>
                            <option value="extracurricular-activity"><?php echo ucfirst(get_phrase('extracurricular_activity')); ?></option>
                            <option value="classes-lessons"><?php echo ucfirst(get_phrase('lessons')); ?></option>
                            <option value="assignments-exams"><?php echo ucfirst(get_phrase('assignments_or_exams')); ?></option>
                            <option value="holidays-vacations"><?php echo ucfirst(get_phrase('holidays_or_vacations')); ?></option>
                            <option value="special-event"><?php echo ucfirst(get_phrase('special_event')); ?></option>
                            <option value="tutoring-advising"><?php echo ucfirst(get_phrase('tutoring_or_advising')); ?></option>
                            <option value="deadline"><?php echo ucfirst(get_phrase('deadline')); ?></option>
                            <option value="excursions-trips"><?php echo ucfirst(get_phrase('excursions_or_trips')); ?></option>
                        </select>
                        <span class="bar"></span>
                        
                        <!-- <label class="text-center" style="margin: 20px 0px 5px 0px;">Introduzca el título del evento:</label>
                        <input type="text" class="form-control" placeholder="Título..." id="event-title" /> -->

                        <div class="form-group" style="margin: 60px 0px 0px 0px;">
                            <div class="group">
                                <input required="" type="text" class="myinput" name="event-title" id="event-title">
                                <span class="bar"></span>
                                <label class="mylabel"><?php echo ucfirst(get_phrase('title')); ?></label>
                            </div>
                        </div>

                        <div class="form-group" style="margin: 30px 0px 0px 0px;">
                            <div class="group">
                                <input required="" type="text" class="myinput" name="event-body" id="event-body">
                                <span class="bar"></span>
                                <label class="mylabel"><?php echo ucfirst(get_phrase('body')); ?></label>
                            </div>
                        </div>

                        <input type="hidden" id="hiddenTitle" value="">
                        <input type="hidden" id="hiddenBody" value="">
                        <input type="hidden" id="hiddenType" value="">

                        <button type="submit" class="form-control btn btn-event" style="margin: 30px 0px 0px 0px;"><?php echo ucfirst(get_phrase('add')); ?></button>

                        <ul class="events-list" id="draggable_events" style="margin: 10px 0px 0px 0px;">
                            <li><p class="text-center" style="font-style:italic;">&nbsp;&nbsp;&nbsp; </p></li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Acordión 2 -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseTwo-2" class="collapsed">
                    <?php echo ucfirst(get_phrase('permissions_and_visibility')); ?>
                </a>
            </h4>
        </div>
        <div id="collapseTwo-2" class="panel-collapse collapse">
            <div class="panel-body">
                <form role="form" id="send_event_form">
                    <div class="input-group minimal" style="background-color: transparent;">
                        <select name="users-list" id="users-list" class="form-control myselect">
                            <option value="my_account"><?php echo ucfirst(get_phrase('my_account')); ?></option>
                            <option value="admin"><?php echo ucfirst(get_phrase('admins')); ?></option>
                            <option value="students"><?php echo ucfirst(get_phrase('students')); ?></option>
                            <option value="guardians"><?php echo ucfirst(get_phrase('guardians')); ?></option>
                            <option value="teachers"><?php echo ucfirst(get_phrase('teachers')); ?></option>
                            <option value="teachers_aide"><?php echo ucfirst(get_phrase('teachers_aide')); ?></option>
                        </select>
                        <span class="bar"></span>

                        <select name="user-student-option" id="user-student-option" class="form-control myselect" style="display:none !important; margin: 15px 0px 0px 0px;">
                            <option value=""><?php echo ucfirst(get_phrase('select')); ?></option>
                            <option value="All"><?php echo ucfirst(get_phrase('all')); ?></option>
                            <option value="PerClass"><?php echo ucfirst(get_phrase('per_class')); ?></option>
                            <option value="PerSection"><?php echo ucfirst(get_phrase('per_section')); ?></option>
                        </select>
                        <span class="bar"></span>

                        <select name="user-admin-option" id="user-admin-option" class="form-control myselect" style="display:none !important; margin: 15px 0px 0px 0px;">
                            <option value=""><?php echo ucfirst(get_phrase('select')); ?></option>
                            <option value="All"><?php echo ucfirst(get_phrase('all')); ?></option>
                            <option value="PerUser"><?php echo ucfirst(get_phrase('per_user')); ?></option>
                        </select>

                        <select name="content-class-list" id="content-class-list" class="form-control myselect" style="display:none !important; margin: 15px 0px 0px 0px;"></select>
                        <select name="content-section-list" id="content-section-list" class="form-control myselect" style="display:none !important; margin: 15px 0px 0px 0px;"></select>
                        <select name="content-admin-list" id="content-admin-list" class="form-control myselect" style="display:none !important; margin: 15px 0px 0px 0px;"></select>
                        <br>
                        <br>
                        <br>
                        <div class="custom-checkbox" style="margin: 100px 0px 0px 0px;">
                            <input type="checkbox" name="visibilityForMyUser" id="visibilityForMyUser">
                            <label for="visibilityForMyUser"><?php echo ucfirst(get_phrase('visible_to_my_user')); ?></label>
                        </div> 
                        <div class="custom-checkbox" style="margin: 10px 0px 0px 0px;">
                            <input type="checkbox" name="visibleEdit" id="visibleEdit">
                            <label for="visibleEdit"><?php echo ucfirst(get_phrase('recipients_will_be_able_to_edit')); ?></label>
                        </div>

                        <div class="custom-checkbox" style="margin: 10px 0px 0px 0px;">
                            <input type="checkbox" name="visibleDelete" id="visibleDelete">
                            <label for="visibleDelete"><?php echo ucfirst(get_phrase('recipients_will_be_able_to_delete')); ?></label>
                        </div>
                        

                    </div>
                    <button class="form-control btn btn-event" style="margin: 20px 0px 0px 0px;"><?php echo ucfirst(get_phrase('accept')); ?></button>
                </form>
            </div>
        </div>
    </div>

    <!-- Acordión 3 -->
    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseThree-2" class="collapsed">
                                    <?php echo ucfirst(get_phrase('list_of_active_events')); ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree-2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <!-- <label for="color-style-event" class="text-center">Lista de eventos previamente guardados:</label> -->

                                <ul class="prev-events-list events-list" id="prev-events-list" style="margin: 10px 0px 10px 0px;">
                    <?php 
                    // Decodificar el JSON para convertirlo en un array asociativo
                    $eventArray = json_decode($events, true);

                    // Función para traducir y formatear la fecha
                    function formatDateTime($dateString, $includeTime = true) {
                        // Crear un objeto DateTime con el string recibido
                        $date = new DateTime($dateString);

                        // Array con los meses en español
                        $months = [
                            '01' => ucfirst(get_phrase('january')),
                            '02' => ucfirst(get_phrase('february')),
                            '03' => ucfirst(get_phrase('march')),
                            '04' => ucfirst(get_phrase('april')),
                            '05' => ucfirst(get_phrase('may')),
                            '06' => ucfirst(get_phrase('june')),
                            '07' => ucfirst(get_phrase('july')),
                            '08' => ucfirst(get_phrase('august')),
                            '09' => ucfirst(get_phrase('september')),
                            '10' => ucfirst(get_phrase('october')),
                            '11' => ucfirst(get_phrase('november')),
                            '12' => ucfirst(get_phrase('december')),
                        ];

                        // Formatear la fecha: Día de Mes del Año
                        $formattedDate = $date->format('d') . ' de ' . $months[$date->format('m')] . ' del ' . $date->format('Y');

                        // Agregar la hora si se solicita
                        if ($includeTime && $dateString != null) {
                            $formattedTime = $date->format('H:i');
                            $am_pm = $date->format('H') >= 12 ? 'PM' : 'AM';
                            $formattedDate .= ' a las ' . $formattedTime . ' ' . $am_pm;
                        }

                        return $formattedDate;
                    }

                    // Verificar que $eventArray es un array
                    if (is_array($eventArray) && !empty($eventArray)): ?>
                    
                        <?php foreach ($eventArray as $event): ?>

                            <?php if ($event['status_id'] == 1) : ?>
                            <li>
                                <a href="javascript:;" class="<?php echo isset($event['className']) ? htmlspecialchars($event['className']) : ''; ?>">
                                    <?php echo htmlspecialchars($event['title']); ?> -
                                    <?php 
                                    // Si el evento tiene una fecha de inicio, formatear la fecha
                                    if (!empty($event['start'])) {
                                        if (!empty($event['end'])) {
                                            // Mostrar rango de fechas si hay fecha de inicio y fin
                                            echo ' del ' . formatDateTime($event['start'], true) . ' al ' . formatDateTime($event['end'], true);
                                        } else {
                                            // Mostrar solo la fecha de inicio
                                            echo formatDateTime($event['start'], true);
                                        }
                                    }
                                    ?>
                                    <br>
                                    <br>
                                        <div class="text-center" style="padding-right: 15px;">
                                            <button onclick="event.preventDefault(); showAjaxModal('<?php echo base_url(); ?>index.php?modal/popup/modal_event_edit/<?php echo $event['event_id'];?>');" title="<?php echo ucfirst(get_phrase('edit'));?>" class="btn btn-default btn-xs" style="margin-left: 10px;">
                                                <i class="entypo-pencil"></i>
                                            </button>

                                            <button onclick="event.preventDefault(); confirm_disable_sweet_modal('<?php echo base_url(); ?>index.php?admin/events/disable/<?php echo $event['event_id'];?>');" class="btn btn-default btn-xs" title="<?php echo ucfirst(get_phrase('disable'));?>" style="margin-left: 10px;">
                                                <i class="entypo-block"></i>
                                            </button>
                                        </div>
                                    <br>
                                </a>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><?php echo ucfirst(get_phrase('no_events_available')); ?></li>
                    <?php endif; ?>
                </ul>
                            </div>
                        </div>
    </div>














    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseThree-3" class="collapsed">
                                    <?php echo ucfirst(get_phrase('list_of_inactive_events')); ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree-3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <!-- <label for="color-style-event" class="text-center">Lista de eventos previamente guardados:</label> -->

                                <ul class="prev-events-list events-list" id="prev-events-list" style="margin: 10px 0px 10px 0px;">
                    <?php 
                    // Decodificar el JSON para convertirlo en un array asociativo
                    $eventArray = json_decode($disabledEvents, true);

                    // Función para traducir y formatear la fecha
                    function formatDateTime2($dateString, $includeTime = true) {
                        // Crear un objeto DateTime con el string recibido
                        $date = new DateTime($dateString);

                        // Array con los meses en español
                        $months = [
                            '01' => ucfirst(get_phrase('january')),
                            '02' => ucfirst(get_phrase('february')),
                            '03' => ucfirst(get_phrase('march')),
                            '04' => ucfirst(get_phrase('april')),
                            '05' => ucfirst(get_phrase('may')),
                            '06' => ucfirst(get_phrase('june')),
                            '07' => ucfirst(get_phrase('july')),
                            '08' => ucfirst(get_phrase('august')),
                            '09' => ucfirst(get_phrase('september')),
                            '10' => ucfirst(get_phrase('october')),
                            '11' => ucfirst(get_phrase('november')),
                            '12' => ucfirst(get_phrase('december')),
                        ];

                        // Formatear la fecha: Día de Mes del Año
                        $formattedDate = $date->format('d') . ' de ' . $months[$date->format('m')] . ' del ' . $date->format('Y');

                        // Agregar la hora si se solicita
                        if ($includeTime && $dateString != null) {
                            $formattedTime = $date->format('H:i');
                            $am_pm = $date->format('H') >= 12 ? 'PM' : 'AM';
                            $formattedDate .= ' a las ' . $formattedTime . ' ' . $am_pm;
                        }

                        return $formattedDate;
                    }

                    // Verificar que $eventArray es un array
                    if (is_array($eventArray) && !empty($eventArray)): ?>

                           <?php foreach ($eventArray as $event): ?>

                            <?php if ($event['status_id'] == 0) : ?>
                            <li>
                                <a href="javascript:;" class="<?php echo isset($event['className']) ? htmlspecialchars($event['className']) : ''; ?>">
                                    <?php echo htmlspecialchars($event['title']); ?> -
                                    <?php 
                                    // Si el evento tiene una fecha de inicio, formatear la fecha
                                    if (!empty($event['start'])) {
                                        if (!empty($event['end'])) {
                                            // Mostrar rango de fechas si hay fecha de inicio y fin
                                            echo ' del ' . formatDateTime($event['start'], true) . ' al ' . formatDateTime($event['end'], true);
                                        } else {
                                            // Mostrar solo la fecha de inicio
                                            echo formatDateTime($event['start'], true);
                                        }
                                    }
                                    ?>
                                    <br>
                                    <br>
                                        <div class="text-center" style="padding-right: 15px;">
                                            <button onclick="event.preventDefault(); showAjaxModal('<?php echo base_url(); ?>index.php?modal/popup/modal_event_edit/<?php echo $event['event_id'];?>');" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit'));?>" style="margin-left: 10px;">
                                                <i class="entypo-pencil"></i>
                                            </button>

                                            <button onclick="event.preventDefault(); confirm_enable_sweet_modal('<?php echo base_url(); ?>index.php?admin/events/enable/<?php echo $event['event_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable'));?>" style="margin-left: 10px;">
                                                <i class="fa fa-check-circle-o"></i>
                                            </button>
                                        </div>
                                    <br>
                                </a>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><?php echo ucfirst(get_phrase('no_events_available')); ?></li>
                    <?php endif; ?>
                </ul>
                            </div>
                        </div>
    </div>








</div>

                    </div>
                
                
                 
                </div>
                
                </div>
			</div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12"> 
                <div class="notes-env notes-container">
                    <div class="notes-header">
                        <h2><?php echo ucfirst(get_phrase('tasks')); ?></h2>
                        <div class="right">
                        <a class="btn btn-primary btn-icon icon-left" id="add-task" href="javascript:;" onclick="scrollToTop(); showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_task_add/');">
                                <i class="entypo-pencil"></i>
                                <?php echo ucfirst(get_phrase('new_task')); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-group joined" id="accordion-enable-task">

            <div class="panel panel-default panel-tasks">
                <div class="panel-heading">
                    <h4 class="panel-title text-center">
                        <a data-toggle="collapse" style="font-weight: 600 !important;" data-parent="#accordion-enable-task" href="#collapse-enable-task" aria-expanded="true" class="">
                            <?php 
                                $query = $this->db->get_where('task', array(
                                    'user_id' => $this->session->userdata('admin_id'), 
                                    'user_type' => $this->session->userdata('login_type'), 
                                    'status_id' => 1
                                ));
                                $active_tasks_count = $query->num_rows();
                            ?>
                            <?php echo ucfirst(get_phrase('list_of_active_tasks'));?>   <span class="badge badge-primary" style="color: #fff; background-color: #265044; padding: 7px 10px;"><?php echo $active_tasks_count; ?></span>    
                        </a>
                    </h4>
                </div>
                <div id="collapse-enable-task" class="panel-collapse collapse in" aria-expanded="true" style="">
                    <div class="panel-body">
                        <div class="row row-tasks" style="padding-top: 20px !important;">
                            <?php 
                            $query = $this->db->get_where('task', array('user_id' => $this->session->userdata('admin_id'), 'user_type' => $this->session->userdata('login_type'), 'status_id' => 1));
                            if ($query->num_rows() > 0):
                                $tasks = $query->result_array();
                                foreach ($tasks as $row2):
                                    $task_id = $row2['task_id'];
                                    $task_items_query = $this->db->get_where('task_items', array(
                                            'task_id' => $task_id,
                                            'status' => 'enabled'
                                        ));
                                    $task_items_query_disabled = $this->db->get_where('task_items', array(
                                        'task_id' => $task_id,
                                        'status' => 'disabled'
                                    ));

                                    $task_items = $task_items_query->result_array();

                                    $task_items_disabled = $task_items_query_disabled->result_array();
                                    
                                    // Separar las tareas en incompletas y completas
                                    $incomplete_task_items = [];
                                    $complete_task_items = [];
                                    
                                    foreach ($task_items as $item) {
                                        if ($item['status_id'] == 0) {
                                            $incomplete_task_items[] = $item;
                                        } else {
                                            $complete_task_items[] = $item;
                                        }
                                    }

                                    $incomplete_task_items = array_reverse($incomplete_task_items);
                                    $complete_task_items =  array_reverse($complete_task_items);
                                    
                                    // Combinar las tareas, priorizando las incompletas
                                    $prioritized_task_items = array_merge($incomplete_task_items, $complete_task_items);
                                    
                                    // Limitar la cantidad de elementos a mostrar a un máximo de 5
                                    $displayed_task_items = array_slice($prioritized_task_items, 0, 5);
                            ?>
                            <div class="col-sm-4">
                                <div class="tile-block todo_tasks <?php echo $row2['task_style']; ?>" id="<?php echo $row2['task_id']; ?>" style="min-height: 423.75px;">
                                    
                                    <div class="tile-header">
                                        <div class="second-tile-header">
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="transform: rotate(90deg); padding: 5px;">
                                                    <i class="entypo-dot-3" style="font-size: 15px;"></i>
                                                </button>
                                                <ul class="dropdown-menu task-dropdown-menu dropdown-default pull-right" role="menu" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <a href="javascript:;" class="btn btn-default task-option-a btn-icon icon-left" style="color: #fff !important;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_task_edit/<?php echo $row2['task_id'];?>/<?php echo $row2['task_id'];?>');">
                                                            <?php echo ucfirst(get_phrase('edit')); ?>  
                                                            <i class="entypo-pencil"></i> 
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" class="btn btn-default task-option-a btn-icon icon-left" style="color: #fff !important;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/task/disable/<?php echo $row2['task_id']; ?>');">
                                                            <?php echo ucfirst(get_phrase('disable')); ?>
                                                            <i class="entypo-block"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <a href="#">
                                            <?php echo $row2['title']; ?>
                                            <span><?php echo ucfirst(get_phrase('pending_task_list')); ?></span>
                                        </a>
                                    </div>
                                    
                                    <div class="tile-content">
                                        <input type="text" class="form-control" placeholder="Añadir tarea" />
                                        
                                        <ul class="todo-list">
                                            <?php foreach ($displayed_task_items as $item): ?>
                                                <li>
                                                    <div class="checkbox checkbox-replace color-white">
                                                        <input id="<?php echo $item['task_item_id']; ?>" class="task_items_id" type="checkbox" <?php echo ($item['status_id'] == 1) ? 'checked' : ''; ?> />
                                                        <label><?php echo $item['description']; ?></label>
                                                    
                                                    </div>
                                                    <a href="#" class="btn task-label-a btn-default" onclick="confirm_sweet_modal('<?php echo base_url();?>index.php?admin/task/disabledIndividualTaskItem/<?php echo $item['task_item_id']; ?>');">
                                                                <i class="entypo-trash"></i>
                                                            </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    
                                    <?php if (count($task_items) > 5): ?>
                                        <div class="remaining-items-task text-center">
                                            <span><?php echo count($task_items) - 5; ?> <?php echo ucfirst(get_phrase('remaining')); ?></span>       
                                        </div>

                                        <div class="tile-footer text-center">
                                            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_task_view/<?php echo $row2['task_id'];?>');"><?php echo ucfirst(get_phrase('view_all_tasks')); ?></a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (count($task_items_disabled) >= 1): ?>
                                        <div class="remaining-items-task text-center">
                                            <span><?php echo count($task_items_disabled); ?> <?php echo ucfirst(get_phrase('disabled')); ?></span>       
                                        </div>

                                        <div class="tile-footer text-center">
                                            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_task_disabled_view/<?php echo $row2['task_id'];?>');"><?php echo ucfirst(get_phrase('view_disabled_tasks')); ?></a>
                                        </div>
                                    <?php endif; ?>
                                    
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="panel-group joined" id="accordion-disable-task">

            <div class="panel panel-default panel-tasks">
                <div class="panel-heading">
                    <h4 class="panel-title text-center">
                        <a data-toggle="collapse" style="font-weight: 600 !important;" data-parent="#accordion-disable-task" href="#collapse-disable-task" class="collapsed" aria-expanded="false">
                            <?php 
                                $query = $this->db->get_where('task', array(
                                    'user_id' => $this->session->userdata('admin_id'), 
                                    'user_type' => $this->session->userdata('login_type'), 
                                    'status_id' => 0
                                ));
                                $inactive_tasks_count = $query->num_rows();
                            ?>
                            <?php echo ucfirst(get_phrase('list_of_inactive_tasks'));?>   <span class="badge badge-primary" style="color: #fff; background-color: #265044; padding: 7px 10px;"><?php echo $inactive_tasks_count; ?></span>
                        </a>
                    </h4>
                </div>
                <div id="collapse-disable-task" class="panel-collapse collapse" aria-expanded="false">
                    <div class="panel-body">
                        <div class="row row-tasks" style="padding-top: 20px !important;">
                            <?php 
                            $query = $this->db->get_where('task', array('user_id' => $this->session->userdata('admin_id'), 'user_type' => $this->session->userdata('login_type'), 'status_id' => 0));
                            if ($query->num_rows() > 0):
                                $tasks = $query->result_array();
                                foreach ($tasks as $row2):
                                    $task_id = $row2['task_id'];
                                    $task_items_query = $this->db->get_where('task_items', array(
                                            'task_id' => $task_id,
                                            'status' => 'enabled'
                                        ));
                                    $task_items_query_disabled = $this->db->get_where('task_items', array(
                                        'task_id' => $task_id,
                                        'status' => 'disabled'
                                    ));

                                    $task_items = $task_items_query->result_array();

                                    $task_items_disabled = $task_items_query_disabled->result_array();
                                    
                                    // Separar las tareas en incompletas y completas
                                    $incomplete_task_items = [];
                                    $complete_task_items = [];
                                    
                                    foreach ($task_items as $item) {
                                        if ($item['status_id'] == 0) {
                                            $incomplete_task_items[] = $item;
                                        } else {
                                            $complete_task_items[] = $item;
                                        }
                                    }

                                    $incomplete_task_items = array_reverse($incomplete_task_items);
                                    $complete_task_items =  array_reverse($complete_task_items);
                                    
                                    // Combinar las tareas, priorizando las incompletas
                                    $prioritized_task_items = array_merge($incomplete_task_items, $complete_task_items);
                                    
                                    // Limitar la cantidad de elementos a mostrar a un máximo de 5
                                    $displayed_task_items = array_slice($prioritized_task_items, 0, 5);
                            ?>
                            <div class="col-sm-4">
                                <div class="tile-block todo_tasks <?php echo $row2['task_style']; ?>" id="<?php echo $row2['task_id']; ?>" style="min-height: 423.75px;">
                                    
                                    <div class="tile-header">
                                        <div class="second-tile-header">
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="transform: rotate(90deg); padding: 5px;">
                                                    <i class="entypo-dot-3" style="font-size: 15px;"></i>
                                                </button>
                                                <ul class="dropdown-menu task-dropdown-menu dropdown-default pull-right" role="menu" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <a href="javascript:;" class="btn btn-default task-option-a btn-icon icon-left" style="color: #fff !important;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/task/enable/<?php echo $row2['task_id']; ?>');">
                                                            <?php echo ucfirst(get_phrase('enable')); ?>
                                                            <i class="fa fa-check-circle-o"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <a href="#">
                                            <?php echo $row2['title']; ?>
                                            <span><?php echo ucfirst(get_phrase('pending_task_list')); ?></span>
                                        </a>
                                    </div>
                                    
                                    <div class="tile-content">
                                        <ul class="todo-list">
                                            <?php foreach ($displayed_task_items as $item): ?>
                                                <li>
                                                    <div class="checkbox checkbox-replace color-white">
                                                        <label><?php echo $item['description']; ?></label>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    
                                    <?php if (count($task_items) > 5): ?>
                                        <div class="remaining-items-task text-center">
                                            <span><?php echo count($task_items) - 5; ?> <?php echo ucfirst(get_phrase('remaining')); ?></span>       
                                        </div>

                                        <div class="tile-footer text-center">
                                            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_task_view/<?php echo $row2['task_id'];?>');"><?php echo ucfirst(get_phrase('view_all_tasks')); ?></a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (count($task_items_disabled) >= 1): ?>
                                        <div class="remaining-items-task text-center">
                                            <span><?php echo count($task_items_disabled); ?> <?php echo ucfirst(get_phrase('disabled')); ?></span>       
                                        </div>

                                        <div class="tile-footer text-center">
                                            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_task_disabled_view/<?php echo $row2['task_id'];?>');"><?php echo ucfirst(get_phrase('view_disabled_tasks')); ?></a>
                                        </div>
                                    <?php endif; ?>
                                    
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        


    </div>
</div>



<script type="text/javascript">


    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth' 
        });
    }


		jQuery(document).ready(function($) 
		{

 
        // $("#calendar").fullCalendar({
		// 	header: {
		// 		left: '',
		// 		right: '',
		// 	},
				
		// 	firstDay: 1,
		// 	height: 250,
		// });

        var $todo_tasks = $(".todo_tasks");
        var $todo_btn = $("#add-task");

        $todo_tasks.find('input[type="text"]').on('keydown', function(ev) {
            if (ev.keyCode == 13) {
                ev.preventDefault();

                // Obtener el ID de la tarea
                var task_id = $(this).closest('.todo_tasks').attr('id');

                if ($.trim($(this).val()).length) {
                    var $todo_entry = $('<li><div class="checkbox checkbox-replace color-white"><input type="checkbox" /><label style="margin-left: 10px;">' + $(this).val() + '</label></div></li>');
                    var item = $(this).val();

                    $(this).val('');

                    $(this).closest('.tile-block').find('.todo-list').prepend($todo_entry);

                    $todo_entry.hide().slideDown('fast');
                    replaceCheckboxes();

                    $.ajax({
                        url: 'index.php?admin/task/createIndividualTaskItem/' + task_id + '/' + item,
                        success: function(response) {
                            console.log('Nueva tarea creada exitosamente.');
                            location.reload();
                            // var opts = {
                            //     "closeButton": true,
                            //     "debug": false,
                            //     "positionClass": "toast-top-right",
                            //     "onclick": null,
                            //     "showDuration": "300",
                            //     "hideDuration": "1000",
                            //     "timeOut": "5000",
                            //     "extendedTimeOut": "1000",
                            //     "showEasing": "swing",
                            //     "hideEasing": "linear",
                            //     "showMethod": "fadeIn",
                            //     "hideMethod": "fadeOut"
                            // };

                            // toastr.success("tarea agregada exitosamente!", opts);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al crear la nueva tarea:', error);
                        }
                    });
                }
            }
        });

        var $task_items_checkbox = $(".task_items_id");

        $($task_items_checkbox).on('click', function(ev) {
            // Obtener el ID de la tarea
            var task_id = $(this).closest('.todo_tasks').attr('id');
            
            // Obtener el ID del ítem de la tarea
            var task_item_id = $(this).attr('id');
            
            // Determinar si el checkbox está marcado o no
            var checked = $(this).is(':checked');
            
            // Determinar el tipo de acción (marcar o desmarcar)
            var checkType = checked ? '1' : '0';

            // Realizar la solicitud AJAX
            $.ajax({
                url: 'index.php?admin/task/checkUncheckIndividualTaskItem/' + task_id + '/' + task_item_id + '/' + checkType,
                success: function(response) {
                    console.log('Operación realizada exitosamente.', 'index.php?admin/task/checkUncheckIndividualTaskItem/' + task_id + '/' + task_item_id + '/' + checkType);
                },
                error: function(xhr, status, error) {
                    console.error('Error al realizar la operación:', error);
                }
            });
        });





});

</script>



<script type="text/javascript">
$(document).ready(function() {
    // Mostrar el segundo select cuando se selecciona 'students'
    $('#users-list').change(function() {
    var user_type = $(this).val();
    
    if (user_type === 'students' || user_type === 'guardians' || user_type === 'teachers' || user_type === 'teachers_aide') {
        $('#user-student-option').attr('style', 'display: block !important; margin: 15px 0px 0px 0px;'); 
        $('#user-admin-option').attr('style', 'display: none !important').val(''); // Ocultar y resetear
        $('#content-class-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
        $('#content-admin-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
    } else if (user_type === 'admin') {
        $('#user-student-option').attr('style', 'display: none !important').val(''); // Ocultar y resetear
        $('#user-admin-option').attr('style', 'display: block !important; margin: 15px 0px 0px 0px;'); 
        $('#content-class-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
        $('#content-section-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
    } else {
        $('#user-student-option').attr('style', 'display: none !important').val(''); // Ocultar y resetear
        $('#user-admin-option').attr('style', 'display: none !important').val(''); // Ocultar y resetear
        $('#content-class-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
        $('#content-section-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
        $('#content-admin-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
    }
});

$('#user-student-option').change(function() {
    var user_option = $(this).val();
    
    if (user_option === 'PerClass') {
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_content2/',
            success: function(response) {
                $('#content-class-list').html(response).attr('style', 'display: block !important; margin: 15px 0px 0px 0px;');
                $('#content-section-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
                $('#content-admin-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
            }
        });
    } else {
        $('#content-class-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
    }

    if (user_option === 'PerSection') {
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_section_content2/',
            success: function(response) {
                $('#content-section-list').html(response).attr('style', 'display: block !important; margin: 15px 0px 0px 0px;');
                $('#content-admin-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
                $('#content-class-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
            }
        });
    } else {
        $('#content-section-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
    }
});

$('#user-admin-option').change(function() {
    var user_option = $(this).val();
    
    if (user_option === 'PerUser') {
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_admin_users_content/',
            success: function(response) {
                $('#content-admin-list').html(response).attr('style', 'display: block !important; margin: 15px 0px 0px 0px;');
                $('#content-class-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
                $('#content-section-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
                $('#user-student-option').attr('style', 'display: none !important').val(''); // Ocultar y resetear
            }
        });
    } else {
        $('#content-admin-list').attr('style', 'display: none !important').html(''); // Ocultar y resetear contenido
    }
});


});

$("body").on('submit', '#send_event_form', function(ev) {
    ev.preventDefault();

    var user_type = $('#users-list').val();

    if ($('#user-student-option').val()) {
        var user_option = $('#user-student-option').val();
    } else if($('#user-admin-option').val()) {
        var user_option = $('#user-admin-option').val();
    }

    // Obtenemos el último evento agregado al calendario
    var selected_event = $('#calendar').fullCalendar('clientEvents').pop();
    console.log(selected_event); // Verifica el contenido

    if (!selected_event) {
        console.error("No se ha encontrado ningún evento seleccionado.");
        return;
    }

    var event_title =   $("#hiddenTitle").val();
    var event_body =   $("#hiddenBody").val();
    var isAllDay = selected_event.allDay; // true o false
    var event_start = selected_event.start;
    var event_end = selected_event.end || null; // Si no hay end, es null
    var event_color = selected_event.className || '#000000';
    var visibilityForCreator = $('#visibilityForMyUser').is(':checked') ? true : false;
    var visibleEdit = $('#visibleEdit').is(':checked') ? true : false;
    var visibleDelete = $('#visibleDelete').is(':checked') ? true : false;
    var event_type = $("#hiddenType").val();

    var user_id = <?php echo $this->session->userdata('login_user_id'); ?> 

    if (event_end && event_start.getDate() !== event_end.getDate()) {
        isAllDay = false;
    }

    console.log(event_title); 
    console.log(event_body);

    console.log('valor de allDay', isAllDay)

    // Formatea la fecha de inicio
    var formatted_start_date = event_start.getFullYear() + '-' + 
                               ('0' + (event_start.getMonth() + 1)).slice(-2) + '-' + 
                               ('0' + event_start.getDate()).slice(-2);

    // Formatea la fecha de fin si existe
    var formatted_end_date = event_end ? (event_end.getFullYear() + '-' + 
                          ('0' + (event_end.getMonth() + 1)).slice(-2) + '-' + 
                          ('0' + event_end.getDate()).slice(-2)) : null;

    // Si el evento NO es de todo el día y las fechas de inicio y fin son diferentes
    if (!isAllDay && event_end && event_start.getDate() !== event_end.getDate()) {
        // Deja la fecha de inicio sin la hora
        formatted_start_date = event_start.getFullYear() + '-' + 
                               ('0' + (event_start.getMonth() + 1)).slice(-2) + '-' + 
                               ('0' + event_start.getDate()).slice(-2);

        // Añade la hora final 23:59:00 para la fecha de fin
        formatted_end_date = event_end.getFullYear() + '-' + 
                             ('0' + (event_end.getMonth() + 1)).slice(-2) + '-' + 
                             ('0' + event_end.getDate()).slice(-2) + ' 23:59:00';
    } 
    // Si el evento NO es de todo el día y las fechas de inicio y fin son iguales
    else if (!isAllDay && event_end && event_start.getDate() === event_end.getDate()) {
        formatted_start_date += ' ' + ('0' + event_start.getHours()).slice(-2) + ':' + 
                                      ('0' + event_start.getMinutes()).slice(-2) + ':00';
        
        formatted_end_date += ' ' + ('0' + event_end.getHours()).slice(-2) + ':' + 
                                      ('0' + event_end.getMinutes()).slice(-2) + ':00';
    }
    // Si el evento NO es de todo el día y tiene hora de inicio pero no tiene hora de fin
    else if (!isAllDay && event_start && !event_end) {
        // Incluimos la hora de inicio
        formatted_start_date += ' ' + ('0' + event_start.getHours()).slice(-2) + ':' + 
                                      ('0' + event_start.getMinutes()).slice(-2) + ':00';
        
        // La fecha de fin se deja como null, ya que no se seleccionó hora de finalización
        formatted_end_date = null;
    }
    // Si el evento ES de todo el día y tiene una fecha de fin
    else if (isAllDay && event_end) {
        // Formateamos el fin del evento como "todo el día" con el final del día de la fecha de fin
        formatted_end_date = event_end.getFullYear() + '-' + 
                             ('0' + (event_end.getMonth() + 1)).slice(-2) + '-' + 
                             ('0' + event_end.getDate()).slice(-2) + ' 23:59:59';
    }
    // Si es un evento de todo el día y no tiene fecha de fin (evento de un solo día)
    else if (isAllDay && !event_end) {
        formatted_start_date = event_start.getFullYear() + '-' + 
                               ('0' + (event_start.getMonth() + 1)).slice(-2) + '-' + 
                               ('0' + event_start.getDate()).slice(-2) + ' 00:00:00';
        formatted_end_date = formatted_start_date + ' 23:59:59';
    }

    console.log('Día de inicio del evento: ', formatted_start_date);
    console.log('Día de fin del evento: ', formatted_end_date);
    console.log('Quien lo va a ver: ', user_type);
    console.log('Opción de quienes: ', user_option);
    console.log('ID de visibilidad: ', visibility_id);
    console.log('Color del evento: ', event_color);

    if (event_title.length == 0) return false;

    if (user_type === 'my_account') {
        var visibility_id = (user_type === 'my_account') ? user_id : null; // ID del usuario logueado o nulo

        user_option = 'my_account';

        // Enviamos los datos por la URL en la solicitud AJAX
        var ajaxUrl = '<?php echo base_url();?>index.php?admin/events/create/' + 
                    encodeURIComponent(event_title) + '/' +
                    encodeURIComponent(event_body) + '/' +  
                    encodeURIComponent(formatted_start_date) + '/' + 
                    (formatted_end_date ? encodeURIComponent(formatted_end_date) : 'null') + '/' + // Manejar null en URL
                    encodeURIComponent(user_type) + '/' + 
                    encodeURIComponent(user_option) + '/' + 
                    encodeURIComponent(visibility_id) + '/' + 
                    encodeURIComponent(isAllDay) + '/' + 
                    encodeURIComponent(event_color)  + '/' + 
                    encodeURIComponent(visibilityForCreator) + '/' + 
                    encodeURIComponent(visibleEdit) + '/' + 
                    encodeURIComponent(visibleDelete) + '/'  +
                    encodeURIComponent(event_type);

        console.log(ajaxUrl);

        $.ajax({
            url: ajaxUrl,
            success: function(response) {
                console.log('Operación realizada exitosamente.');
                 location.reload(); 
            },
            error: function(xhr, status, error) {
                console.error('Error al realizar la operación:', error);
            }
        });
    } else if (user_type === 'students') {

        if (user_option === 'All') {
            var visibility_id = null; 
        } else if (user_option === 'PerClass') {
            var visibility_id = $('#content-class-list').val();
        } else if (user_option === 'PerSection') {
            var visibility_id = $('#content-section-list').val();
        }

        // Enviamos los datos por la URL en la solicitud AJAX
        var ajaxUrl = '<?php echo base_url();?>index.php?admin/events/create/' + 
        encodeURIComponent(event_title) + '/' +
        encodeURIComponent(event_body) + '/' +  
                    encodeURIComponent(formatted_start_date) + '/' + 
                    (formatted_end_date ? encodeURIComponent(formatted_end_date) : 'null') + '/' + // Manejar null en URL
                    encodeURIComponent(user_type) + '/' + 
                    encodeURIComponent(user_option) + '/' + 
                    encodeURIComponent(visibility_id) + '/' + 
                    encodeURIComponent(isAllDay) + '/' + 
                    encodeURIComponent(event_color)  + '/' + 
                    encodeURIComponent(visibilityForCreator) + '/' + 
                    encodeURIComponent(visibleEdit) + '/' + 
                    encodeURIComponent(visibleDelete) + '/'  +
                    encodeURIComponent(event_type);

        console.log(ajaxUrl);

        $.ajax({
            url: ajaxUrl,
            success: function(response) {
                console.log('Operación realizada exitosamente.');
                location.reload(); 
            },
            error: function(xhr, status, error) {
                console.error('Error al realizar la operación:', error);
            }
        });
    } else if (user_type === 'guardians') {

        if (user_option === 'All') {
            var visibility_id = null; 
        } else if (user_option === 'PerClass') {
            var visibility_id = $('#content-class-list').val();
        } else if (user_option === 'PerSection') {
            var visibility_id = $('#content-section-list').val();
        }

        // Enviamos los datos por la URL en la solicitud AJAX
        var ajaxUrl = '<?php echo base_url();?>index.php?admin/events/create/' + 
        encodeURIComponent(event_title) + '/' +
        encodeURIComponent(event_body) + '/' +  
                    encodeURIComponent(formatted_start_date) + '/' + 
                    (formatted_end_date ? encodeURIComponent(formatted_end_date) : 'null') + '/' + // Manejar null en URL
                    encodeURIComponent(user_type) + '/' + 
                    encodeURIComponent(user_option) + '/' + 
                    encodeURIComponent(visibility_id) + '/' + 
                    encodeURIComponent(isAllDay) + '/' + 
                    encodeURIComponent(event_color)  + '/' + 
                    encodeURIComponent(visibilityForCreator) + '/' + 
                    encodeURIComponent(visibleEdit) + '/' + 
                    encodeURIComponent(visibleDelete) + '/'  +
                    encodeURIComponent(event_type);

        console.log(ajaxUrl);

        $.ajax({
            url: ajaxUrl,
            success: function(response) {
                console.log('Operación realizada exitosamente.');
                 location.reload(); 
            },
            error: function(xhr, status, error) {
                console.error('Error al realizar la operación:', error);
            }
        });
    } else if (user_type === 'teachers') {
        if (user_option === 'All') {
            var visibility_id = null; 
        } else if (user_option === 'PerClass') {
            var visibility_id = $('#content-class-list').val();
        } else if (user_option === 'PerSection') {
            var visibility_id = $('#content-section-list').val();
        }

        // Enviamos los datos por la URL en la solicitud AJAX
        var ajaxUrl = '<?php echo base_url();?>index.php?admin/events/create/' + 
        encodeURIComponent(event_title) + '/' +
        encodeURIComponent(event_body) + '/' +  
                    encodeURIComponent(formatted_start_date) + '/' + 
                    (formatted_end_date ? encodeURIComponent(formatted_end_date) : 'null') + '/' + // Manejar null en URL
                    encodeURIComponent(user_type) + '/' + 
                    encodeURIComponent(user_option) + '/' + 
                    encodeURIComponent(visibility_id) + '/' + 
                    encodeURIComponent(isAllDay) + '/' + 
                    encodeURIComponent(event_color)  + '/' + 
                    encodeURIComponent(visibilityForCreator) + '/' + 
                    encodeURIComponent(visibleEdit) + '/' + 
                    encodeURIComponent(visibleDelete) + '/'  +
                    encodeURIComponent(event_type);

        console.log(ajaxUrl);

        $.ajax({
            url: ajaxUrl,
            success: function(response) {
                console.log('Operación realizada exitosamente.');
                location.reload(); 
            },
            error: function(xhr, status, error) {
                console.error('Error al realizar la operación:', error);
            }
        });
    } else if (user_type === 'teachers_aide') {
        if (user_option === 'All') {
            var visibility_id = null; 
        } else if (user_option === 'PerClass') {
            var visibility_id = $('#content-class-list').val();
        } else if (user_option === 'PerSection') {
            var visibility_id = $('#content-section-list').val();
        }

        // Enviamos los datos por la URL en la solicitud AJAX
        var ajaxUrl = '<?php echo base_url();?>index.php?admin/events/create/' + 
        encodeURIComponent(event_title) + '/' +
        encodeURIComponent(event_body) + '/' +  
                    encodeURIComponent(formatted_start_date) + '/' + 
                    (formatted_end_date ? encodeURIComponent(formatted_end_date) : 'null') + '/' + // Manejar null en URL
                    encodeURIComponent(user_type) + '/' + 
                    encodeURIComponent(user_option) + '/' + 
                    encodeURIComponent(visibility_id) + '/' + 
                    encodeURIComponent(isAllDay) + '/' + 
                    encodeURIComponent(event_color)  + '/' + 
                    encodeURIComponent(visibilityForCreator) + '/' + 
                    encodeURIComponent(visibleEdit) + '/' + 
                    encodeURIComponent(visibleDelete) + '/'  +
                    encodeURIComponent(event_type);

        console.log(ajaxUrl);

        $.ajax({
            url: ajaxUrl,
            success: function(response) {
                console.log('Operación realizada exitosamente.');
                location.reload(); 
            },
            error: function(xhr, status, error) {
                console.error('Error al realizar la operación:', error);
            }
        });
    } else if (user_type === 'admin') {
        if (user_option === 'All') {
            var visibility_id = null; 
        } else if (user_option === 'PerUser') {
            var visibility_id = $('#content-admin-list').val();
        } 

        // Enviamos los datos por la URL en la solicitud AJAX
        var ajaxUrl = '<?php echo base_url();?>index.php?admin/events/create/' + 
        encodeURIComponent(event_title) + '/' +
        encodeURIComponent(event_body) + '/' +  
                    encodeURIComponent(formatted_start_date) + '/' + 
                    (formatted_end_date ? encodeURIComponent(formatted_end_date) : 'null') + '/' + // Manejar null en URL
                    encodeURIComponent(user_type) + '/' + 
                    encodeURIComponent(user_option) + '/' + 
                    encodeURIComponent(visibility_id) + '/' + 
                    encodeURIComponent(isAllDay) + '/' + 
                    encodeURIComponent(event_color)  + '/' + 
                    encodeURIComponent(visibilityForCreator) + '/' + 
                    encodeURIComponent(visibleEdit) + '/' + 
                    encodeURIComponent(visibleDelete) + '/'  +
                    encodeURIComponent(event_type); 

        console.log(ajaxUrl);

        $.ajax({
            url: ajaxUrl,
            success: function(response) {
                console.log('Operación realizada exitosamente.');
                 location.reload(); 
            },
            error: function(xhr, status, error) {
                console.error('Error al realizar la operación:', error);
            }
        });
    }
});


</script>

<script type="text/javascript">
    var neonCalendar = neonCalendar || {};

    ;(function($, window, undefined) {
        "use strict";

        $(document).ready(function() {
            neonCalendar.$container = $(".calendar-env");

            $.extend(neonCalendar, {
                isPresent: neonCalendar.$container.length > 0
            });

            // Mail Container Height fit with the document
            if(neonCalendar.isPresent) {
                neonCalendar.$sidebar = neonCalendar.$container.find('.calendar-sidebar');
                neonCalendar.$body = neonCalendar.$container.find('.calendar-body');

                // Setup Calendar
                if($.isFunction($.fn.fullCalendar)) {
                    var calendar = $('#calendar');

                    // Aquí integras los eventos desde PHP
                    var events = <?php echo $events; ?>; // Asegúrate de que esto esté en formato JSON

                    console.log(events);

                    calendar.fullCalendar({
    header: {
        left: 'title',
        right: 'month,agendaWeek,agendaDay today prev,next'
    },

    editable: true,
    events: events, // Usar los eventos desde la base de datos
    firstDay: 1,
    height: 600,
    droppable: true,
    isRTL: false,
	firstDay: 0,
    monthNames: ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'],
    monthNamesShort: ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'],
    dayNames: ['DOMINGO', 'LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO'],
    dayNamesShort: ['DOM', 'LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB'],
    buttonText: {
        prev: "<span class='fc-text-arrow'>&lsaquo;</span>",
        next: "<span class='fc-text-arrow'>&rsaquo;</span>",
        prevYear: "<span class='fc-text-arrow'>&laquo;</span>",
        nextYear: "<span class='fc-text-arrow'>&raquo;</span>",
        today: 'hoy',
        month: 'mes',
        week: 'semana',
        day: 'día'
    },

    
    // viewRender: function(view, element) {
   
    // $('.fc-week .fc-first').each(function() {
      
    //     $(this).find('div:first').css({
    //         'height': '140px',
    //         'min-height': '140px', 
    //         'max-height': '140px'  
    //     });
    //     $(this).css({
    //         'min-height': '140px', 
    //         'max-height': '140px'  
    //     });
    // });

    // $('.fc-week .fc-widget-content').each(function() {
    //     $(this).find('div:first').css({
    //         'height': '140px',
    //         'min-height': '140px', 
    //         'max-height': '140px'  
    //     });
    //     $(this).css({
    //         'height': '140px',
    //         'min-height': '140px', 
    //         'max-height': '140px' 
    //     });
    // });

    // $('.fc-event .fc-event-inner').each(function() {
    //     $(this).css({
    //         'height': '87px',
    //         'min-height': '87px', 
    //         'max-height': '87px' 
    //     });
    // });

    
// },


    drop: function(date, allDay) {
        var $this = $(this),
            eventObject = {
                title: $this.text(),
                start: date,
                allDay: allDay,
                className: $this.data('event-class')
            };

        calendar.fullCalendar('renderEvent', eventObject, true);
        $this.remove();
    },
    
    eventRender: function(event, element) {
    // Variable para almacenar la clase del icono según el tipo de evento
    var iconClass = '';

    // Asigna la clase del icono según el tipo de evento
    switch (event.type) {
        case 'meeting': 
            iconClass = 'fa fa-users'; // Icono para reuniones
            break;
        case 'extracurricular-activity': 
            iconClass = 'fa fa-child'; // Icono para actividades extracurriculares
            break;
        case 'classes-lessons': 
            iconClass = 'fa fa-book'; // Icono para clases / lecciones
            break;
        case 'assignments-exams': 
            iconClass = 'fa fa-pencil-square-o'; // Icono para trabajos / exámenes
            break;
        case 'holidays-vacations': 
            iconClass = 'fa fa-suitcase'; // Icono para días festivos / vacaciones
            break;
        case 'special-event': 
            iconClass = 'fa fa-star'; // Icono para eventos especiales
            break;
        case 'tutoring-advising': 
            iconClass = 'fa fa-graduation-cap'; // Icono para tutorías / asesorías
            break;
        case 'deadline': 
            iconClass = 'fa fa-clock-o'; // Icono para fechas límite
            break;
        case 'excursions-trips': 
            iconClass = 'fa fa-bus'; // Icono para excursiones / salidas
            break;
        default:
            iconClass = 'fa fa-calendar'; // Icono por defecto
    }

    if (!event.allDay && event.start) {
    function formatTime(date) {
        var hours = date.getHours();
        var minutes = ('0' + date.getMinutes()).slice(-2);
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        return hours + ':' + minutes + ' ' + ampm;
    }

    var time = formatTime(event.start) + ' ';
    if (event.end) {
        time += ' - ' + formatTime(event.end) + ' ';
    }

    // Truncar el título si es mayor a "evento para todos los estudiantes"
    var maxTitleLength = "evento para todos los estudiantes".length;
    var title = event.title.length > maxTitleLength ? event.title.substring(0, maxTitleLength - 3) + '...' : event.title;

    element.find('.fc-event-title').text(title);
    element.find('.fc-event-time').text(time);
} else if (event.allDay) {
    var maxTitleLength = "evento para todos los estudiantes".length;
    var title = event.title.length > maxTitleLength ? event.title.substring(0, maxTitleLength - 3) + '...' : event.title;

    element.find('.fc-event-title').text(title);
    element.find('.fc-event-time').remove();
}

// Selecciona el div actual
var eventInner = element.find('.fc-event-inner');
var eventId = event.event_id; 

var newAnchor = $('<div>', {
    href: "javascript:;",
    onclick: "event.preventDefault(); showAjaxModal('<?php echo base_url(); ?>index.php?modal/popup/modal_view_event/" + eventId + "');",
    class: eventInner.attr('class'), 
    style: eventInner.attr('style') 
});

newAnchor.html(eventInner.html());

eventInner.replaceWith(newAnchor);

    element.find('.fc-event-title').prepend('<span class="event-icon"><i class="' + iconClass + '"></i></span> <br>');
    
}

});




                    $("#draggable_events li a").draggable({
                        zIndex: 999,
                        revert: true,
                        revertDuration: 0
                    }).on('click', function() {
                        return false;
                    });
                } else {
                    alert("Please include full-calendar script!");
                }

                $("body").on('submit', '#add_event_form', function(ev) {
    ev.preventDefault();

    var title = $("#event-title").val();
    var body = $("#event-body").val();
    var type = $("#event-type").val();

    $("#hiddenTitle").val(title);
    $("#hiddenBody").val(body);
    $("#hiddenType").val(type);

    var selected_color = $('#color-style-event').val();

    console.log('Color seleccionado: ', selected_color);

    // Verificar que ambos campos no estén vacíos
    if (title.length === 0 || body.length === 0) {
        alert("Por favor, complete todos los campos.");
        return false;
    }

      // Lista de colores aleatorios
      var colors = [
        'color-blue',
        'color-orange',
        'color-green',
        'color-red',
        'color-yellow',
        'color-purple',
        'color-pink',
        'color-gray',
        'color-brown'
    ];

    // Si el valor es "random", seleccionar un color aleatorio
    if (selected_color === 'random') {
        selected_color = colors[Math.floor(Math.random() * colors.length)];
    }

    // Definir el ícono según el tipo de evento
    var iconClass;
    switch (type) {
        case 'meeting': 
            iconClass = 'fa fa-users'; // Icono para reuniones
            break;
        case 'extracurricular-activity': 
            iconClass = 'fa fa-child'; // Icono para actividades extracurriculares
            break;
        case 'classes-lessons': 
            iconClass = 'fa fa-book'; // Icono para clases / lecciones
            break;
        case 'assignments-exams': 
            iconClass = 'fa fa-pencil-square-o'; // Icono para trabajos / exámenes
            break;
        case 'holidays-vacations': 
            iconClass = 'fa fa-suitcase'; // Icono para días festivos / vacaciones
            break;
        case 'special-event': 
            iconClass = 'fa fa-star'; // Icono para eventos especiales
            break;
        case 'tutoring-advising': 
            iconClass = 'fa fa-graduation-cap'; // Icono para tutorías / asesorías
            break;
        case 'deadline': 
            iconClass = 'fa fa-clock-o'; // Icono para fechas límite
            break;
        case 'excursions-trips': 
            iconClass = 'fa fa-bus'; // Icono para excursiones / salidas
            break;
        default:
            iconClass = 'fa fa-calendar'; // Icono por defecto
    }

   // Crear el nuevo evento con el color seleccionado o aleatorio
var $event = $('<li class="fc-event-inner2"></li>');

// Definir el HTML para el ícono y el texto
var iconHTML = '<span class="event-icon"><i class="' + iconClass + '"></i></span><br>';
var eventTitleHTML = '<span class="fc-event-title2">' + iconHTML + title + '</span><br>' + body;

// Usar un div para contener el contenido del evento y aplicar el color
var eventContainerHTML = '<div class="fc-event2 ' + selected_color + '">' + eventTitleHTML + '</div>';

// Insertar el HTML del contenedor dentro del $event
$event.html(eventContainerHTML).addClass(selected_color).attr('data-event-class', selected_color);

// Agregar el evento a la lista de eventos arrastrables
$event.appendTo($("#draggable_events"));

// Hacer que los eventos sean arrastrables
$("#draggable_events li").draggable({
    zIndex: 999,
    revert: true,
    revertDuration: 0
}).on('click', function() {
    return false;
});

});


            }
        });

    

    })(jQuery, window);
</script>


