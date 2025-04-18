<?php

// Consultar en la tabla 'schedule'
$this->db->from('schedule');

if (!empty($teacher_id)) {
    $this->db->where('teacher_id', $teacher_id);
} else {
    $this->db->where('section_id', $section_id);
}

$this->db->where('status_id', 1);
$query = $this->db->get();

// Verificar si no hay resultados en 'schedule'
if ($query->num_rows() === 0) {
    // Consultar en la tabla 'schedule_history'
    $this->db->from('schedule_history');
    
    if (!empty($teacher_id)) {
        $this->db->where('teacher_id', $teacher_id);
    } else {
        $this->db->where('section_id', $section_id);
    }
    
    $this->db->where('status_id', 1);
    $query = $this->db->get();
}

// Contar todos los registros encontrados (en cualquiera de las tablas)
$all_schedules_count = $query->num_rows();

// Obtener los registros como array
$schedules = $query->result_array();

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$hours = [];

// Configurar las horas de inicio y fin
$start_time = strtotime('07:30 AM');
$end_time = strtotime('02:15 PM');
$recess_times = [
    '08:45 AM' => 'PRIMER RECESO',
    '10:10 AM' => 'SEGUNDO RECESO',
    '11:30 AM' => 'TERCER RECESO',
    '12:55 PM' => 'CUARTO RECESO'
];

// Generar intervalos de 30 minutos
while ($start_time <= $end_time) {
    $hour = date('h:i A', $start_time);

    // Agregar intervalos de receso específicos si no están ya en $hours
    if (!in_array($hour, array_keys($recess_times))) {
        $hours[] = $hour;
    }

    $start_time = strtotime('+30 minutes', $start_time);
}

// Agregar los horarios de receso en el orden correcto
foreach ($recess_times as $recess_time => $label) {
    if (!in_array($recess_time, $hours)) {
        $hours[] = $recess_time;
    }
}

// Ordenar los horarios para asegurarnos de que estén en el orden correcto
usort($hours, function ($a, $b) {
    return strtotime($a) - strtotime($b);
});

// Inicializar el horario por día
$schedule_by_day = array_fill_keys($days, []);

foreach ($schedules as $schedule) {
    $day_index = array_search($schedule['day_id'], range(2, 6));

    $subject = $this->db->get_where('subject', ['subject_id' => $schedule['subject_id']])->row();
    if (!$subject) { 
        $subject = $this->db->get_where('subject_history', ['subject_id' => $schedule['subject_id']])->row();
    }
    
    $teacher = $this->db->get_where('teacher_details', ['teacher_id' => $subject->teacher_id])->row();

    $sectionTeacher = $this->db->get_where('section', ['section_id' => $subject->section_id])->row();
    if (!$sectionTeacher && !empty($subject->section_id)) { 
        $sectionTeacher = $this->db->get_where('section_history', ['section_id' => $subject->section_id])->row();
    }

    if ($subject && $teacher && $day_index !== false) {
        $subject_name = (strlen($subject->name) > 20) ? substr($subject->name, 0, 20) . '...' : $subject->name;
        $full_subject_name = $subject->name;
        $schedule_info = [
            'subject_name' => $subject_name,
            'full_subject_name' => $full_subject_name,
            'section_name' => $sectionTeacher->name,
            'teacher_firstname' => $teacher->firstname,
            'teacher_lastname' => $teacher->lastname,
            'time_start' => date('h:i A', strtotime($schedule['time_start'])),
            'time_end' => date('h:i A', strtotime($schedule['time_end'])),
            'subject_id' => $subject->subject_id,
            'schedule_id' => $schedule['schedule_id'],
        ];
        $schedule_by_day[$days[$day_index]][] = $schedule_info;
    }
}
?>

<div class="row selectContent">
        <div class="col-md-6">
            <div class="form-group">
                <label for="academic_period_select" class="labelSelect">
                    <?php echo ucfirst(get_phrase('you_are_viewing')); ?>
                </label> 
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <select id="academic_period_select" class="form-control selectElement" onchange="return get_sections(this.value)">
                    <?php
                    $academic_periods = $this->db->get('academic_period')->result_array();

                    foreach ($academic_periods as $period):
                        // Verifica si $academic_period_id no está vacío y coincide con $period['id']
                        if (!empty($academic_period_id) && $academic_period_id == $period['id']) {
                            $selected = 'selected="selected"';
                        } elseif (empty($academic_period_id) && $period['status_id'] == 1) {
                            // Marca por defecto el período académico con status_id = 1 si $academic_period_id está vacío
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                    ?>
                        <option value="<?php echo $period['id']; ?>" data-academic-period-id="<?php echo $period['id']; ?>" <?php echo $selected; ?>>
                            <?php echo $period['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <br>

    <div class="row selectContent">
    <div class="col-md-6">
        <div class="form-group">
            <label for="class_select" class="labelSelect"><?php echo ucfirst(get_phrase('class')); ?>
            </label> 
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <select id="class_select" class="form-control selectElement" onchange="location = this.value;">
                <?php
                    if (isset($academic_period_id) && !empty($academic_period_id)) {
                        // Si $academic_period_id está definido y no está vacío, buscar por su ID
                        $active_academic_period = $this->db->get_where('academic_period', array('id' => $academic_period_id))->row();

                        if ($active_academic_period) { // Validar que el período existe
                            $this->db->where('academic_period_id', $active_academic_period->id);
                            $sections = $this->db->get('section_history')->result_array();

                            foreach ($sections as $row):
                    ?>
                                <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_schedules/<?php echo $row['section_id']; ?>"
                                    <?php if ($section_id == $row['section_id'] && $academic_period_id == $row['academic_period_id']) echo 'selected="selected"'; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                    <?php 
                            endforeach;
                        }
                    } else {
                        // Si $academic_period_id no está definido o está vacío, buscar por el período activo
                        $active_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();

                        if ($active_academic_period) { // Validar que el período existe
                            $this->db->where('academic_period_id', $active_academic_period->id);
                            $sections = $this->db->get('section')->result_array();

                            foreach ($sections as $row):
                    ?>
                                <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_schedules/<?php echo $row['section_id']; ?>"
                                    <?php if ($section_id == $row['section_id']) echo 'selected="selected"'; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                    <?php 
                            endforeach;
                        } else {
                            echo '<option value="">No hay secciones disponibles</option>';
                        }
                    }
                    ?>
            </select>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <?php echo ucfirst(get_phrase('all')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_schedules_count; ?>
                    </span>
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_schedules" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;">
                        <i class="fa fa-plus"></i>
                    </a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;">
                        <i class="fa fa-refresh"></i>
                    </button>
                </div>
                <div class="container schedule-container">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th><?php echo ucfirst(get_phrase('time')); ?></th>
                                    <th><?php echo ucfirst(get_phrase('monday')); ?></th>
                                    <th><?php echo ucfirst(get_phrase('tuesday')); ?></th>
                                    <th><?php echo ucfirst(get_phrase('wednesday')); ?></th>
                                    <th><?php echo ucfirst(get_phrase('thursday')); ?></th>
                                    <th><?php echo ucfirst(get_phrase('friday')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($hours as $hour): ?>
                                    <tr>
                                        <td><?php echo $hour; ?></td>
                                        <?php if (array_key_exists($hour, $recess_times)): ?>
                                            <!-- Mostrar bloque de receso alineado -->
                                            <td colspan="5" class="recess-block">
                                                <?php echo $recess_times[$hour]; ?>
                                            </td>
                                        <?php else: ?>
                                            <?php foreach ($days as $day): ?>
                                                <td>
                                                <div class="class-container-wrapper">
                                                    <?php foreach ($schedule_by_day[$day] as $class): ?>
                                                        <?php
                                                        $time_start = strtotime($class['time_start']);
                                                        $time_end = strtotime($class['time_end']);
                                                        $current_time = strtotime($hour);
                                                        $next_time = strtotime('+15 minutes', $current_time);

                                                        if (
                                                            ($current_time >= $time_start && $current_time < $time_end) ||
                                                            ($next_time > $time_start && $next_time <= $time_end) ||
                                                            ($current_time <= $time_start && $next_time >= $time_end)
                                                        ):
                                                        ?>
                                                            <div class="class-container text-center">
                                                                <a href="<?php echo base_url(); ?>index.php?admin/subjects_profile/<?php echo $class['subject_id']; ?>">
                                                                    <div class="class-block" title="<?php echo ucfirst($class['full_subject_name']); ?>">
                                                                        <span class="subject-name"><?php echo ucfirst($class['subject_name']); ?></span><br>
                                                                        <?php if (!empty($teacher_id)): ?>
                                                                            <span style="font-weight: bold;"><?php echo $class['section_name']; ?></span><br>
                                                                        <?php endif; ?>
                                                                        <?php if (empty($teacher_id)): ?>
                                                                            <?php echo $class['teacher_lastname'] . ', ' . $class['teacher_firstname']; ?><br>
                                                                        <?php endif; ?>
                                                                        <?php echo $class['time_start'] . ' - ' . $class['time_end']; ?>
                                                                    </div>
                                                                                    <div class="action-buttons text-center">
                                                                                        <a href="<?php echo base_url(); ?>index.php?admin/subjects_profile/<?php echo $class['subject_id']; ?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view_profile')); ?>">
                                                                                            <i class="entypo-eye"></i>
                                                                                        </a>
                                                                                        <a href="<?php echo base_url(); ?>index.php?admin/edit_schedules/<?php echo $class['schedule_id']; ?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                                                                            <i class="entypo-pencil"></i> 
                                                                                        </a>
                                                                                        <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/disable_schedule/<?php echo $class['schedule_id'];?>/<?php echo $section_id;?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                                                                            <i class="entypo-block"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                    </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    </div>
                                                </td>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br><br>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
        function get_sections(academic_period_id) {
            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_sections_content_by_academic_period/' + academic_period_id + '/view_schedules',
                success: function(response) {
                    const emptyOption = '<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>';
                    jQuery('#class_select').html(emptyOption + response);
                }
            });

        }
   
</script>

<script type="text/javascript">
    
    function reload_ajax() {
        location.reload(); 
    }

  

</script> 


<style>
     thead th {
        color: #265044 !important;
        font-weight: bolder;
            width: 150px; /* Ajusta este valor según lo necesario */
        max-width: 150px;
        text-align: center;
        }

    .table-responsive {
        border-radius: 15px; 
        overflow: hidden; 
    }

    .table {
        border-collapse: separate; 
        border-spacing: 0; 
    }

    .table.table-bordered {
        border: 1px solid #dee2e6; 
        border-radius: 10px; 
    }

    .table th, .table td {
        border: 1px solid #dee2e6; 
    }

    .table.table-bordered {
        border-radius: 15px !important;
        color: #265044 !important;
    }

    .schedule-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-top: 20px;
    }

    .class-block {
        background-color: #fff;
        border-radius: 4px;
        padding: 10px;
        margin-bottom: 5px;
        font-size: 12px;
        font-weight: 500;
        overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    }


    .subject-name {
        font-size: 20px;
        font-weight: bolder; 
        color: #265044;
    }

    .class-container-wrapper {
        display: flex; 
        gap: 10px; 
    }

    tbody tr td.recess-block {
        font-weight: bold;
        color: #265044 !important;
        background-color: #B0DFCC !important; 
        font-size: 15px;
    }

    .table > tbody > tr td.recess-block {
        background-color: #B0DFCC !important; 
    }

    .table > tbody > tr td.recess-block ~ td,
    .table > tbody > tr td ~ td.recess-block {
        background-color: #B0DFCC !important;
    }

    .action-buttons {
        margin-top: 10px;
        margin-bottom: 5px; 
        display: flex; 
        justify-content: center; 
        gap: 10px; 
    }.action-buttons a {
        background-color: #fff !important;
    }

   

    .btn-table {
        border-radius: 6px; 
    }

        .class-container-wrapper {
            display: flex; 
            gap: 10px; 
            min-width: 150px; 
        }
        .class-container {
            background-color: #B0DFCC;
            border-radius: 10px;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            flex: 1;
            max-width: 200px; 
            min-width: 150px; 
            overflow: hidden; 
            text-overflow: ellipsis; 
            white-space: nowrap;
            transition: background-color 0.3s ease; 
        } .class-container:hover {
            background-color: #265353 !important;
        } .class-container:hover .class-block {
            background-color: #fff !important; 
        }
 
 

        .selectContent {
		background-color: #B0DFCC !important;
		border-radius: 5px;
		font-weight: bold;
        margin-right: 0px;
        margin-left: 0px;
        color: #265044;
    } .selectContent .labelSelect {
        font-size: 20px;
        margin-top: 12px;
    } .selectContent .selectElement {
        margin-top: 12px;
        background-color: #fff;
    }

</style> 