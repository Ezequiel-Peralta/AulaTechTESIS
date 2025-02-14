<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');

// Obtener la fecha actual
$current_date = date('Y-m-d');
$current_date_formatted = date('d/m/Y');

// Calcular el inicio y fin de la semana actual
$current_week_end = $current_date;
$current_week_start = date('Y-m-d', strtotime($current_date . ' -6 days'));

// Año actual
$current_year = date('Y');

$current_month_number = date('m'); // 01, 02, ..., 12

$current_month_name = date('F'); // January, February, ..., December

$current_month_start = date('F', strtotime('first day of last month'));
$current_month_end = date('F', strtotime('last day of this month'));


$this->db->from('student_details');
$this->db->where('user_status_id', 1);
$this->db->where('section_id', $section_id);

$query = $this->db->get();
$all_student_count = $query->num_rows();

if ($all_student_count === 0) {
    $this->db->from('academic_history');
    $this->db->where('new_section_id', $section_id);

    $query = $this->db->get();
    $all_student_count = $query->num_rows();
}

$this->db->where('section_id', $section_id);
$total_students = $this->db->count_all_results('student_details');

if ($total_students === 0) {
    $this->db->where('new_section_id', $section_id);
    $total_students = $this->db->count_all_results('academic_history');
}

if (empty($attendance_student_presente)) {
    $attendance_student_presente = $this->crud_model->get_attendance_student_section_amount2($section_id, 1, 'daily', $current_date);
}

// Verifica y obtiene el valor de 'ausente'
if (empty($attendance_student_ausente)) {
    $attendance_student_ausente = $this->crud_model->get_attendance_student_section_amount2($section_id, 2, 'daily', $current_date);
}

// Verifica y obtiene el valor de 'tardanza'
if (empty($attendance_student_tardanza)) {
    $attendance_student_tardanza = $this->crud_model->get_attendance_student_section_amount2($section_id, 3, 'daily', $current_date);
}

// Verifica y obtiene el valor de 'ausencia justificada'
if (empty($attendance_student_ausencia_justificada)) {
    $attendance_student_ausencia_justificada = $this->crud_model->get_attendance_student_section_amount2($section_id, 4, 'daily', $current_date);
}

$total_attendance = $attendance_student_presente + $attendance_student_ausente + $attendance_student_tardanza + $attendance_student_ausencia_justificada;

if ($total_attendance > 0) {
    $percentage_presente = number_format(($attendance_student_presente / $total_attendance) * 100, 2);
    $percentage_ausente = number_format(($attendance_student_ausente / $total_attendance) * 100, 2);
    $percentage_tardanza = number_format(($attendance_student_tardanza / $total_attendance) * 100, 2);
    $percentage_justificados = number_format(($attendance_student_ausencia_justificada / $total_attendance) * 100, 2);
} else {
    $percentage_presente = 0;
    $percentage_ausente = 0;
    $percentage_tardanza = 0;
    $percentage_justificados = 0;
}

if ($total_students > 0):
    $this->db->where('section_id', $section_id);
    $students = $this->db->get('student_details')->result_array();

    // Obtener el nombre de la sección
    $section_name = $this->crud_model->get_section_name2($section_id);
else:
    // Buscar en academic_history si no se encontraron estudiantes en student_details
    $this->db->where('new_section_id', $section_id);
    $academic_history_students = $this->db->get('academic_history')->result_array();

        $student_ids = array_column($academic_history_students, 'student_id');

        if (!empty($student_ids)) {
            $this->db->where_in('student_id', $student_ids);
            $students = $this->db->get('student_details')->result_array();
        }

        $section_name = $this->crud_model->get_section_name2($section_id);
endif;

$titleEN = 'Student report - ' . $section_name . ' - ' . date('d-m-Y');
$titleES = 'Reporte de Estudiantes - ' . $section_name . ' - ' . date('d-m-Y');
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
                                <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/summary_attendance_student/<?php echo $row['section_id']; ?>"
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
                                <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/summary_attendance_student/<?php echo $row['section_id']; ?>"
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
        <div class="tile-stats tile-white title-info" style="margin-top: 20px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 15px; border: 1px solid #ebebeb; border-radius: 5px;">
                <h2 style="font-weight: 600; margin: 0;"><?php echo ucfirst(get_phrase('class_attendance')); ?> - <?php echo ucfirst(get_phrase('percentage_graph')); ?></h2>
                <div class="sidebuttons text-right">
                    <?php if (!$used_section_history): ?>
                        <a class="btn btn-info" href="<?php echo base_url(); ?>index.php?admin/manage_attendance_student/<?php echo date("d"); ?>/<?php echo date("m"); ?>/<?php echo date("Y"); ?>/<?php echo $section_id; ?>">
                            <i class="entypo-pencil"></i> <?php echo ucfirst(get_phrase('register_attendance')); ?> 
                        </a>
                    <?php endif; ?>
                </div>
            </div>
      
            <div class="row d-flex justify-content-center align-items-center" style="padding: 20px 50px 0px 50px;">
                <div class="col-md-3 viewType">
                    <div class="form-group text-center">
                        <select name="viewType" class="form-control text-center customSelect" id="viewType" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                            <option value="daily"><?php echo ucfirst(get_phrase('daily')); ?> </option>
                            <option value="weekly"><?php echo ucfirst(get_phrase('weekly')); ?> </option>
                            <option value="monthly"><?php echo ucfirst(get_phrase('monthly')); ?> </option>
                            <option value="yearly"><?php echo ucfirst(get_phrase('yearly')); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 dailyView" style="display: none;">
                    <div class="form-group">
                        <input type="date" id="daily" class="form-control text-center customInput" value="<?php echo $current_date; ?>">
                    </div>
                </div>
                <div class="col-md-4 weeklyView" style="display: none;">
                    <div class="form-group">
                        <input type="date" id="startDate" class="form-control text-center customInput" value="<?php echo $current_week_start; ?>">
                    </div>
                </div>
                <div class="col-md-4 weeklyView" style="display: none;">
                    <div class="form-group">
                        <input type="date" id="endDate" class="form-control text-center customInput" value="<?php echo $current_week_end; ?>">
                    </div>
                </div>
                <div class="col-md-6 monthlyView" style="display: none;">
                    <div class="form-group">
                        <select id="monthly" class="form-control text-center customSelect">
                            <?php
                            // Lista de meses en inglés
                            $months = [
                                "January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"
                            ];
                            foreach ($months as $month) {
                                $selected = ($month === $current_month_name) ? 'selected' : '';
                                echo "<option value='{$month}' {$selected}>" . ucfirst(get_phrase($month)) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 yearlyView" style="display: none;">
                    <div class="form-group">
                        <select id="startDateYearly" class="form-control text-center customSelect">
                            <?php
                            $months = [
                                "January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"
                            ];
                            foreach ($months as $month) {
                                $selected = ($month === $current_month_start) ? 'selected' : '';
                                echo "<option value='{$month}' {$selected}>" . ucfirst(get_phrase($month)) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 yearlyView" style="display: none;">
                    <div class="form-group">
                        <select id="endDateYearly" class="form-control text-center customSelect">
                            <?php
                            $months = [
                                "January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"
                            ];
                            foreach ($months as $month) {
                                $selected = ($month === $current_month_end) ? 'selected' : '';
                                echo "<option value='{$month}' {$selected}>" . ucfirst(get_phrase($month)) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>




            <div class="row" style="padding: 0px 0px 20px 0px;">
             
             
    
                
                <div class="col-md-12 text-center">
                    <button id="applyFilters" class="btn btn-info">
                        <?php echo ucfirst(get_phrase('accept')); ?>
                    </button>
                </div>
              
            </div>

            <div class="button-container">
                <button id="downloadPdf" class="btn buttons-html5 btn-white btn-sm btn-danger-hover" title="<?php echo ucfirst(get_phrase('download_pdf')); ?>"><i class="fa fa-file-pdf-o"></i> PDF</button>
                <button id="downloadPng" class="btn buttons-html5 btn-white btn-sm btn-green-hover" title="<?php echo ucfirst(get_phrase('download_png')); ?>"><i class="fa fa-file-image-o"></i> PNG</button>
                <button id="downloadJpeg" class="btn buttons-html5 btn-white btn-sm btn-orange-hover" title="<?php echo ucfirst(get_phrase('download_jpeg')); ?>"><i class="fa fa-file-image-o"></i> JPEG</button>
            </div>

            <table class="table table-attendance-title">
                <tr>
                    <td width="100%" style="border-left: 0px solid #ebebeb !important;">
                        <strong></strong>
                        <br />
                        <div id="chartContainer">
                        <div id="chartAttendance" style="height: 250px"></div>
                        </div>
                    </td>
                </tr>
            </table>
            <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px; margin-bottom: 20px;">
                <span id="presentes" class="btn btn-green btn-icon icon-left" style="background-color: #55FFA8 !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('students_present')); ?>&nbsp; <span id="presentes-count"><?php echo $attendance_student_presente ?></span>
                    <i style="color: white !important; padding-top: 10px;">P</i>
                </span>
                <span id="ausentes" class="btn btn-danger btn-icon icon-left" style="background-color: #FF6C6C !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('absences')); ?>&nbsp; <span id="ausentes-count"><?php echo $attendance_student_ausente ?></span>
                    <i style="color: white !important; padding-top: 10px;">A</i>
                </span>
                <span id="tardanzas" class="btn btn-orange btn-icon icon-left" style="background-color: #FFBB5A !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('tardies')); ?>&nbsp; <span id="tardanzas-count"><?php echo $attendance_student_tardanza ?></span>
                    <i style="color: white !important; padding-top: 10px;">T</i>
                </span>
                <span id="justificados" class="btn btn-blue btn-icon icon-left" style="background-color: #52BBFF !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('justified_absences')); ?>&nbsp; <span id="justificados-count"><?php echo $attendance_student_ausencia_justificada ?></span>
                    <i style="color: white !important; padding-top: 10px;">AJ</i>
                </span>
            </div>
        </div>
    </div>




    
    <div class="col-md-12">
        <div class="tile-stats tile-white title-info" style="margin-top: 20px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 15px; border: 1px solid #ebebeb; border-radius: 5px;">
                <h2 style="font-weight: 600; margin: 0;"><?php echo ucfirst(get_phrase('class_attendance')); ?> - <?php echo ucfirst(get_phrase('quantity_graph')); ?></h2>
                <div class="sidebuttons text-right">
                    <?php if (!$used_section_history): ?>
                        <a class="btn btn-info" href="<?php echo base_url(); ?>index.php?admin/manage_attendance_student/<?php echo date("d"); ?>/<?php echo date("m"); ?>/<?php echo date("Y"); ?>/<?php echo $section_id; ?>">
                            <i class="entypo-pencil"></i> <?php echo ucfirst(get_phrase('register_attendance')); ?> 
                        </a>
                    <?php endif; ?>  
                </div>
            </div>
      
            <div class="row d-flex justify-content-center align-items-center" style="padding: 20px 50px 0px 50px;">
                <div class="col-md-3 viewType2">
                    <div class="form-group text-center">
                        <select name="viewType2" class="form-control text-center customSelect" id="viewType2" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                            <option value="daily"><?php echo ucfirst(get_phrase('daily')); ?> </option>
                            <option value="weekly"><?php echo ucfirst(get_phrase('weekly')); ?> </option>
                            <option value="monthly"><?php echo ucfirst(get_phrase('monthly')); ?> </option>
                            <option value="yearly"><?php echo ucfirst(get_phrase('yearly')); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 dailyView2" style="display: none;">
                    <div class="form-group">
                        <input type="date" id="daily2" class="form-control text-center customInput" value="<?php echo $current_date; ?>">
                    </div>
                </div>
                <div class="col-md-4 weeklyView2" style="display: none;">
                    <div class="form-group">
                        <input type="date" id="startDate2" class="form-control text-center customInput" value="<?php echo $current_week_start; ?>">
                    </div>
                </div>
                <div class="col-md-4 weeklyView2" style="display: none;">
                    <div class="form-group">
                        <input type="date" id="endDate2" class="form-control text-center customInput" value="<?php echo $current_week_end; ?>">
                    </div>
                </div>
                <div class="col-md-6 monthlyView2" style="display: none;">
                    <div class="form-group">
                        <select id="monthly2" class="form-control text-center customSelect">
                            <?php
                            // Lista de meses en inglés
                            $months = [
                                "January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"
                            ];
                            foreach ($months as $month) {
                                $selected = ($month === $current_month_name) ? 'selected' : '';
                                echo "<option value='{$month}' {$selected}>" . ucfirst(get_phrase($month)) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 yearlyView2" style="display: none;">
                    <div class="form-group">
                        <select id="startDateYearly2" class="form-control text-center customSelect">
                            <?php
                            $months = [
                                "January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"
                            ];
                            foreach ($months as $month) {
                                $selected = ($month === $current_month_start) ? 'selected' : '';
                                echo "<option value='{$month}' {$selected}>" . ucfirst(get_phrase($month)) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 yearlyView2" style="display: none;">
                    <div class="form-group">
                        <select id="endDateYearly2" class="form-control text-center customSelect">
                            <?php
                            $months = [
                                "January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"
                            ];
                            foreach ($months as $month) {
                                $selected = ($month === $current_month_end) ? 'selected' : '';
                                echo "<option value='{$month}' {$selected}>" . ucfirst(get_phrase($month)) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>



            <div class="row" style="padding: 0px 0px 20px 0px;">
                <div class="col-md-12 text-center">
                    <button id="applyFilters2" class="btn btn-info">
                        <?php echo ucfirst(get_phrase('accept')); ?>
                    </button>
                </div>
              
            </div>

            <div class="button-container">
                <button id="downloadPdf2" class="btn buttons-html5 btn-white btn-sm btn-danger-hover" title="<?php echo ucfirst(get_phrase('download_pdf')); ?>"><i class="fa fa-file-pdf-o"></i> PDF</button>
                <button id="downloadPng2" class="btn buttons-html5 btn-white btn-sm btn-green-hover" title="<?php echo ucfirst(get_phrase('download_png')); ?>"><i class="fa fa-file-image-o"></i> PNG</button>
                <button id="downloadJpeg2" class="btn buttons-html5 btn-white btn-sm btn-orange-hover" title="<?php echo ucfirst(get_phrase('download_jpeg')); ?>"><i class="fa fa-file-image-o"></i> JPEG</button>
            </div>

            <table class="table table-attendance-title">
                <tr>
                    <td width="100%" style="border-left: 0px solid #ebebeb !important;">
                        <strong></strong>
                        <br />
                        <div id="chartContainer2">
                        <div id="chartAttendance2" style="height: 250px"></div>
                        </div>
                    </td>
                </tr>
            </table>
            <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px; margin-bottom: 20px;">
                <span id="presentes" class="btn btn-green btn-icon icon-left" style="background-color: #55FFA8 !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('students_present')); ?>&nbsp; <span id="presentes-count2"><?php echo $attendance_student_presente ?></span>
                    <i style="color: white !important; padding-top: 10px;">P</i>
                </span>
                <span id="ausentes" class="btn btn-danger btn-icon icon-left" style="background-color: #FF6C6C !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('absences')); ?>&nbsp; <span id="ausentes-count2"><?php echo $attendance_student_ausente ?></span>
                    <i style="color: white !important; padding-top: 10px;">A</i>
                </span>
                <span id="tardanzas" class="btn btn-orange btn-icon icon-left" style="background-color: #FFBB5A !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('tardies')); ?>&nbsp; <span id="tardanzas-count2"><?php echo $attendance_student_tardanza ?></span>
                    <i style="color: white !important; padding-top: 10px;">T</i>
                </span>
                <span id="justificados" class="btn btn-blue btn-icon icon-left" style="background-color: #52BBFF !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('justified_absences')); ?>&nbsp; <span id="justificados-count2"><?php echo $attendance_student_ausencia_justificada ?></span>
                    <i style="color: white !important; padding-top: 10px;">AJ</i>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <!-- <i class="entypo-menu"></i>  -->
                    <?php echo ucfirst(get_phrase('all')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_student_count; ?>
                    </span>
                </a>
            </li>
      
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                      
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="all_student_table">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo ucfirst(get_phrase('students')); ?></th>
                            <th class="text-center"  width="120" style="background-color: #55FFA8 !important; font-weight: 500 !important;  border-radius: 10px !important; color: black !important;">
                                <span style="background-color: #00a651 !important; color: white !important; padding: 0px 10px 0 10px; border-radius: 10px !important; ">P</span> 
                                <?php echo ucfirst(get_phrase('present')); ?>
                            </th>
                            <th class="text-center" width="120" style="background-color: #FF6C6C !important; padding: 0px 0px 8px 0px; font-weight: 500 !important;  border-radius: 10px !important; color: black !important;">
                                <span style="background-color: #cc2424 !important; color: white !important; padding: 0px 10px 0 10px; border-radius: 10px !important; ">A</span>     
                                <?php echo ucfirst(get_phrase('absences')); ?>
                            </th>
                            <th class="text-center" width="120" style="background-color: #FFBB5A !important; padding: 0px 0px 8px 0px; font-weight: 500 !important;  border-radius: 10px !important; color: black !important;">
                                <span style="background-color: #ff9600 !important; color: white !important; padding: 0px 10px 0 10px; border-radius: 10px !important; ">T</span> 
                                <?php echo ucfirst(get_phrase('tardies')); ?>
                            </th>
                            <th class="text-center" width="120" style="background-color: #52BBFF !important; padding: 0px 0px 8px 0px; font-weight: 500 !important;  border-radius: 10px !important; color: black !important;">
                                <span style="background-color: #0072bc !important; color: white !important; padding: 0px 10px 0 10px; border-radius: 10px !important; ">AJ</span> 
                                <?php echo ucfirst(get_phrase('justified_absences')); ?>
                            </th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('total_absences')); ?></th>
                            <th class="text-center" width="60"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $count = 1;
                        foreach ($students as $student):
                            $attendance_status = $this->db->select('status, COUNT(status) as count')
                                                            ->where('student_id', $student['student_id'])
                                                            ->group_by('status')
                                                            ->get('attendance_student')
                                                            ->result_array();

                                                            if (empty($attendance_status)) {
                                                                $attendance_status = $this->db->select('status, COUNT(status) as count')
                                                                                              ->where('student_id', $student['student_id'])
                                                                                              ->group_by('status')
                                                                                              ->get('attendance_student_history')
                                                                                              ->result_array();
                                                            }
                            
                            $present_count = $absent_count = $late_count = $justified_absent_count = 0;
                            
                            if (!empty($attendance_status)) { 
                                foreach ($attendance_status as $status) {
                                    if ($status['status'] == '1') {
                                        $present_count = $status['count'];
                                    } elseif ($status['status'] == '2') {
                                        $absent_count = $status['count'];
                                    } elseif ($status['status'] == '3') {
                                        $late_count = $status['count'];
                                    } elseif ($status['status'] == '4') {
                                        $justified_absent_count = $status['count'];
                                    }
                                }
                            }
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $student['lastname'];?>, <?php echo $student['firstname'];?></td>
                            <td class="text-center">
                                <span style="background-color: #00a651 !important; font-weight: bold !important; color: white !important; padding: 5px 10px 5px 10px; border-radius: 10px !important; ">
                                <?php echo $present_count;?></span>
                            </td>
                            <td class="text-center">
                                <span style="background-color: #cc2424 !important; font-weight: bold !important; color: white !important; padding: 5px 10px 5px 10px; border-radius: 10px !important; ">
                                <?php echo $absent_count;?></span>
                            </td>
                            <td class="text-center">
                                <span style="background-color: #ff9600 !important; font-weight: bold !important; color: white !important; padding: 5px 10px 5px 10px; border-radius: 10px !important; ">
                                <?php echo $late_count;?></span>
                            </td>
                            <td class="text-center" style=" width: 200px !important;">
                                <span style="background-color: #0072bc !important; font-weight: bold !important; color: white !important; padding: 5px 10px 5px 10px; border-radius: 10px !important; ">
                                <?php echo $justified_absent_count;?></span>
                            </td>
                            <td class="text-center">
                                <span style="">
                                    <?php 
                                        $total_absences = $absent_count + $justified_absent_count + ($late_count / 4);
                                        echo $total_absences;
                                    ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/details_attendance_student/<?php echo $student['student_id'];?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view_attendance_details')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                            </td>
                         
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>
       

        </div>
        
        
    </div>
   
</div>


<script type="text/javascript">
        function get_sections(academic_period_id) {
            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_section_content_by_academic_period/' + academic_period_id + '/summary_attendance_student',
                success: function(response) {
                    const emptyOption = '<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>';
                    jQuery('#class_select').html(emptyOption + response);
                }
            });

        }
   
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script type="text/javascript">
	jQuery(document).ready(function($) {
        var languagePreference = '<?php echo $this->session->userdata('language_preference'); ?>';

        var $allStudentDataTable = jQuery("#all_student_table");

        var rowCount = $('#all_student_table tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var allStudentDataTable = $allStudentDataTable.DataTable({
                "order": [[1, "asc"], [2, "asc"]],
                "language": {
                    "search": "",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "Showing 0 to 0 of 0 entries",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    },
                    "aria": {
                        "sortAscending": ": Activate to sort column ascending",
                        "sortDescending": ": Activate to sort column descending"
                    }
                },
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValue, 
                "scrollCollapse": $(window).width() <= 767 ? true : "",
                "fixedHeader": $(window).width() <= 767 ? true : "",
                "autoWidth": false,
                "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-file-text-o"></i> Copy',
                        className: 'btn btn-white btn-sm btn-info-hover',
                        title: null,
                        exportOptions: {
                            columns: ':not(:eq(0)):not(:eq(6)):not(:eq(15))' 
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        className: 'btn btn-white btn-sm btn-green-hover',
                        filename: '<?php echo $titleEN; ?>', 
                        title: null, 
                        exportOptions: {
                            columns: ':not(:eq(0)):not(:eq(6)):not(:eq(15))' 
                        }
                    },
                    {
                        text: '<i class="fa fa-print"></i> Print / <i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover',
                        action: function (e, dt, node, config) {
                            window.location.href = '<?php echo base_url(); ?>index.php?admin/printStudentTableEN/<?php echo $section_id;?>';
                        }
                    }
                ],
                colReorder: true, 
                initComplete: function() {
                    $('#all_student_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
    var allStudentDataTable = $allStudentDataTable.DataTable({
        "order": [[1, "asc"], [2, "asc"]],
        "language": {
                    "search": "", 
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": Activar para ordenar la columna ascendente",
                        "sortDescending": ": Activar para ordenar la columna descendente"
                    }
                },
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValue, 
                "scrollCollapse": $(window).width() <= 767 ? true : "",
                "fixedHeader": $(window).width() <= 767 ? true : "",
                "autoWidth": false,
                "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-file-text-o"></i> Copiar',
                        className: 'btn btn-white btn-sm btn-info-hover',
                        title: null,
                        exportOptions: {
                            columns: ':not(:eq(0)):not(:eq(6)):not(:eq(15))' 
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        className: 'btn btn-white btn-sm btn-green-hover',
                        filename: '<?php echo $titleES; ?>', 
                        title: null, 
                        exportOptions: {
                            columns: ':not(:eq(0)):not(:eq(6)):not(:eq(15))' 
                        }
                    },
                    {
                        text: '<i class="fa fa-print"></i> Imprimir / <i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover',
                        action: function (e, dt, node, config) {
                            window.location.href = '<?php echo base_url(); ?>index.php?admin/printStudentTableES/<?php echo $section_id;?>';
                        }
                    }
                ],
                colReorder: true, 
                initComplete: function() {
                    $('#all_student_table_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

    
}

        $allStudentDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $allStudentDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }

     

         Morris.Donut({
             element: 'chartAttendance', 
             data: [
                 {value: '<?php echo $attendance_student_presente;?>', label: '<?php echo ucfirst(get_phrase('students_present'));?>', formatted: '<?php echo $percentage_presente;?>%' },
                 {value: '<?php echo $attendance_student_ausente;?>', label: '<?php echo ucfirst(get_phrase('absences'));?>', formatted: '<?php echo $percentage_ausente;?>%' },
                 {value: '<?php echo $attendance_student_tardanza;?>', label: '<?php echo ucfirst(get_phrase('tardies'));?>', formatted: '<?php echo $percentage_tardanza;?>%' },
                 {value: '<?php echo $attendance_student_ausencia_justificada;?>', label: '<?php echo ucfirst(get_phrase('justified_absences'));?>', formatted: '<?php echo $percentage_justificados;?>%' }
             ],
             formatter: function (x, data) { return data.formatted; },
             labelColor: 'black', 
             colors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF'] 
         });


         Morris.Bar({
				element: 'chartAttendance2',
				axes: true,
				data: [
					{x: '<?php echo $current_date_formatted;?>', y: <?php echo $attendance_student_presente;?>, z: <?php echo $attendance_student_ausente;?>, a: <?php echo $attendance_student_tardanza;?>, e: <?php echo $attendance_student_ausencia_justificada;?>},
				],
				xkey: 'x',
				ykeys: ['y', 'z', 'a', 'e'],
                labels: ['<?php echo ucfirst(get_phrase('present'));?>', '<?php echo ucfirst(get_phrase('absent'));?>', '<?php echo ucfirst(get_phrase('tardy'));?>', '<?php echo ucfirst(get_phrase('justified_absence'));?>'],
				barColors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF'] 
			});


        var day_data = <?php echo json_encode($day_data); ?>;

        // Morris.Line({
        //     element: 'chartAttendanceDays', // ID del elemento donde se mostrará el gráfico
        //     data: day_data, // Datos para el gráfico
        //     xkey: 'elapsed', // Clave para el eje x (días del mes de abril)
        //     ykeys: ['presente', 'ausente', 'tardanza', 'justificado'], // Claves para las líneas del gráfico
        //     labels: ['presente', 'ausente', 'tardanza', 'justificado'], // Etiquetas para las líneas del gráfico
        //     parseTime: false, // No se requiere análisis de tiempo para el eje x
        //     lineColors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF'] // Colores de las líneas
        // });


       

	});

		
</script>

<script type="text/javascript">
    
    function reload_ajax() {
        location.reload(); 
    }

    $(document).ready(function () {

        document.getElementById('downloadPdf').addEventListener('click', function () {
            // Esperar a que el gráfico esté completamente renderizado
            setTimeout(() => {
                const chartContainer = document.getElementById('chartContainer');
                const viewType = document.getElementById('viewType').value;

                let viewTypeText = "";
                let viewTypeDetails = "";

                if (viewType === 'daily') {
                    viewTypeText = '<?php echo ucfirst(get_phrase('daily'));?>';
                    let dailyDate = document.getElementById('daily').value; 
                    let dateParts = dailyDate.split('-'); 
                    let formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`; 
                    viewTypeDetails = formattedDate;
                } else if (viewType === 'weekly') {
                    viewTypeText = '<?php echo ucfirst(get_phrase('weekly'));?>';  
                    const startDate = document.getElementById('startDate').value;
                    const endDate = document.getElementById('endDate').value;
                    
                    let startDateParts = startDate.split('-'); 
                    let formattedStartDate = `${startDateParts[2]}/${startDateParts[1]}/${startDateParts[0]}`;
                    
                    let endDateParts = endDate.split('-'); 
                    let formattedEndDate = `${endDateParts[2]}/${endDateParts[1]}/${endDateParts[0]}`; 
                    
                    viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${formattedStartDate} <?php echo get_phrase('to'); ?> ${formattedEndDate}`;  
                } else if (viewType === 'monthly') {
                    viewTypeText = '<?php echo ucfirst(get_phrase('monthly'));?>'; 
                    const month = document.getElementById('monthly').value;

                    if (month === 'January') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("january")); ?>';
                    } else if (month === 'February') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                    } else if (month === 'March') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                    } else if (month === 'April') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                    } else if (month === 'May') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                    } else if (month === 'June') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                    } else if (month === 'July') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                    } else if (month === 'August') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                    } else if (month === 'September') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                    } else if (month === 'October') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                    } else if (month === 'November') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                    } else if (month === 'December') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                    }

                    viewTypeDetails = `${translatedMonth}`;  // Solo 'from' porque se selecciona un mes
                } else if (viewType === 'yearly') {
                    viewTypeText = '<?php echo ucfirst(get_phrase('yearly'));?>';  // Usar traducción para 'yearly'
                    const startMonth = document.getElementById('startDateYearly').value;
                    const endMonth = document.getElementById('endDateYearly').value;

                    if (startMonth === 'January') {
                            translatedStartDate = '<?php echo ucfirst(get_phrase("january")); ?>';
                        } else if (startMonth === 'February') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                        } else if (startMonth === 'March') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                        } else if (startMonth === 'April') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                        } else if (startMonth === 'May') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                        } else if (startMonth === 'June') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                        } else if (startMonth === 'July') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                        } else if (startMonth === 'August') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                        } else if (startMonth === 'September') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                        } else if (startMonth === 'October') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                        } else if (startMonth === 'November') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                        } else if (startMonth === 'December') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                        }

                        if (endMonth === 'January') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("january")); ?>';
                        } else if (endMonth === 'February') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                        } else if (endMonth === 'March') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                        } else if (endMonth === 'April') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                        } else if (endMonth === 'May') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                        } else if (endMonth === 'June') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                        } else if (endMonth === 'July') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                        } else if (endMonth === 'August') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                        } else if (endMonth === 'September') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                        } else if (endMonth === 'October') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                        } else if (endMonth === 'November') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                        } else if (endMonth === 'December') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                        }

                    viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${translatedStarMonth} <?php echo get_phrase('to'); ?> ${translatedEndMonth}`;  // Usar las palabras "from" y "to" traducidas
                }

                // Obtener los valores de los spans
                const presentesCount = parseInt(document.getElementById('presentes-count').textContent, 10);
                const ausentesCount = parseInt(document.getElementById('ausentes-count').textContent, 10);
                const tardanzasCount = parseInt(document.getElementById('tardanzas-count').textContent, 10);
                const justificadosCount = parseInt(document.getElementById('justificados-count').textContent, 10);

                const totalCount = presentesCount + ausentesCount + tardanzasCount + justificadosCount;

                // Calcular los porcentajes
                const presentesPercent = ((presentesCount / totalCount) * 100).toFixed(2);
                const ausentesPercent = ((ausentesCount / totalCount) * 100).toFixed(2);
                const tardanzasPercent = ((tardanzasCount / totalCount) * 100).toFixed(2);
                const justificadosPercent = ((justificadosCount / totalCount) * 100).toFixed(2);

                if (chartContainer) {
                    html2canvas(chartContainer).then(canvas => {
                        // Convertir a PDF
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF();
            const imgData = canvas.toDataURL('image/png');

            // Hacer el gráfico más grande ajustando las dimensiones
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

            const scaleFactor = 1.2; // Aumenta el valor si quieres hacerlo más grande
            const scaledPdfHeight = pdfHeight * scaleFactor;
            const scaledPdfWidth = pdfWidth * scaleFactor;

            // Añadir el título al PDF
            pdf.setFont("helvetica", "bold");
            pdf.setFontSize(16);
            pdf.text("<?php echo ucfirst(get_phrase('class_attendance')); ?> <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('percentage_graph')); ?>", 10, 20);

            const chartYPosition = 30; // Cambia este valor para bajar el gráfico

            // Agregar la imagen del gráfico al PDF
            pdf.addImage(imgData, 'PNG', 0, chartYPosition, pdfWidth, pdfHeight );

            // Añadir texto debajo del gráfico
            const textYPosition = chartYPosition + pdfHeight + 10; // Ajustar la posición para que esté después del gráfico

            pdf.setFont("helvetica", "normal");
            pdf.setFontSize(12);

            pdf.text(`<?php echo ucfirst(get_phrase('view_type')); ?>: ${viewTypeText}`, 10, textYPosition);
            pdf.text(`<?php echo ucfirst(get_phrase('details')); ?>: ${viewTypeDetails}`, 10, textYPosition + 10);

            // Mostrar los conteos y porcentajes para cada categoría
            pdf.text(`<?php echo ucfirst(get_phrase('students_present')); ?>: ${presentesCount} (${presentesPercent}%)`, 10, textYPosition + 20);
            pdf.text(`<?php echo ucfirst(get_phrase('absences')); ?>: ${ausentesCount} (${ausentesPercent}%)`, 10, textYPosition + 30);
            pdf.text(`<?php echo ucfirst(get_phrase('tardies')); ?>: ${tardanzasCount} (${tardanzasPercent}%)`, 10, textYPosition + 40);
            pdf.text(`<?php echo ucfirst(get_phrase('justified_absences')); ?>: ${justificadosCount} (${justificadosPercent}%)`, 10, textYPosition + 50);

            // Guardar el PDF
            pdf.save("<?php echo ucfirst(get_phrase('class_attendance')); ?> <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('percentage_graph')); ?> - <?php echo $current_date_formatted; ?>.pdf");
                    });
                } else {
                    console.error('El contenedor del gráfico no se encontró.');
                }
            }, 500); // 500ms para garantizar que el gráfico esté listo
        });

        document.getElementById('downloadPng').addEventListener('click', function () {
            setTimeout(() => {
                const chartContainer = document.getElementById('chartContainer');
                        const viewType = document.getElementById('viewType').value;

                        let viewTypeText = "";
                        let viewTypeDetails = "";

                        if (viewType === 'daily') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('daily'));?>';
                            let dailyDate = document.getElementById('daily').value; 
                            let dateParts = dailyDate.split('-'); 
                            let formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`; 
                            viewTypeDetails = formattedDate;
                        } else if (viewType === 'weekly') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('weekly'));?>';  
                            const startDate = document.getElementById('startDate').value;
                            const endDate = document.getElementById('endDate').value;
                            
                            let startDateParts = startDate.split('-'); 
                            let formattedStartDate = `${startDateParts[2]}/${startDateParts[1]}/${startDateParts[0]}`;
                            
                            let endDateParts = endDate.split('-'); 
                            let formattedEndDate = `${endDateParts[2]}/${endDateParts[1]}/${endDateParts[0]}`; 
                            
                            viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${formattedStartDate} <?php echo get_phrase('to'); ?> ${formattedEndDate}`;  
                        } else if (viewType === 'monthly') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('monthly'));?>'; 
                            const month = document.getElementById('monthly').value;

                            if (month === 'January') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("january")); ?>';
                            } else if (month === 'February') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                            } else if (month === 'March') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                            } else if (month === 'April') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                            } else if (month === 'May') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                            } else if (month === 'June') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                            } else if (month === 'July') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                            } else if (month === 'August') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                            } else if (month === 'September') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                            } else if (month === 'October') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                            } else if (month === 'November') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                            } else if (month === 'December') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                            }

                            viewTypeDetails = `${translatedMonth}`;  // Solo 'from' porque se selecciona un mes
                        } else if (viewType === 'yearly') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('yearly'));?>';  // Usar traducción para 'yearly'
                            const startMonth = document.getElementById('startDateYearly').value;
                            const endMonth = document.getElementById('endDateYearly').value;

                            if (startMonth === 'January') {
                                    translatedStartDate = '<?php echo ucfirst(get_phrase("january")); ?>';
                                } else if (startMonth === 'February') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                                } else if (startMonth === 'March') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                                } else if (startMonth === 'April') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                                } else if (startMonth === 'May') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                                } else if (startMonth === 'June') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                                } else if (startMonth === 'July') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                                } else if (startMonth === 'August') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                                } else if (startMonth === 'September') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                                } else if (startMonth === 'October') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                                } else if (startMonth === 'November') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                                } else if (startMonth === 'December') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                                }

                                if (endMonth === 'January') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("january")); ?>';
                                } else if (endMonth === 'February') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                                } else if (endMonth === 'March') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                                } else if (endMonth === 'April') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                                } else if (endMonth === 'May') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                                } else if (endMonth === 'June') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                                } else if (endMonth === 'July') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                                } else if (endMonth === 'August') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                                } else if (endMonth === 'September') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                                } else if (endMonth === 'October') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                                } else if (endMonth === 'November') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                                } else if (endMonth === 'December') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                                }

                            viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${translatedStarMonth} <?php echo get_phrase('to'); ?> ${translatedEndMonth}`;  // Usar las palabras "from" y "to" traducidas
                        }

                        // Obtener los valores de los spans
                        const presentesCount = parseInt(document.getElementById('presentes-count').textContent, 10);
                        const ausentesCount = parseInt(document.getElementById('ausentes-count').textContent, 10);
                        const tardanzasCount = parseInt(document.getElementById('tardanzas-count').textContent, 10);
                        const justificadosCount = parseInt(document.getElementById('justificados-count').textContent, 10);

                        const totalCount = presentesCount + ausentesCount + tardanzasCount + justificadosCount;

                        // Calcular los porcentajes
                        const presentesPercent = ((presentesCount / totalCount) * 100).toFixed(2);
                        const ausentesPercent = ((ausentesCount / totalCount) * 100).toFixed(2);
                        const tardanzasPercent = ((tardanzasCount / totalCount) * 100).toFixed(2);
                        const justificadosPercent = ((justificadosCount / totalCount) * 100).toFixed(2);

                if (chartContainer) {
                    html2canvas(chartContainer).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        
                        const textCanvas = document.createElement('canvas');
                        const ctx = textCanvas.getContext('2d');
                        textCanvas.width = canvas.width;
                        textCanvas.height = canvas.height + 0;// Más espacio para texto (ajusta según sea necesario)
                        ctx.drawImage(canvas, 0, 0);

                        ctx.font = "16px Helvetica";
                        ctx.fillText("<?php echo ucfirst(get_phrase('class_attendance')); ?> <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('percentage_graph')); ?>", 10, 20);
                        ctx.font = "12px Helvetica";
                        ctx.fillText(`<?php echo ucfirst(get_phrase('view_type')); ?>: ${viewTypeText}`, 10, 40);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('details')); ?>: ${viewTypeDetails}`,10, 60);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('students_present')); ?>: ${presentesCount} (${presentesPercent}%)`, 10, 80);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('absences')); ?>: ${ausentesCount} (${ausentesPercent}%)`, 10, 100);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('tardies')); ?>: ${tardanzasCount} (${tardanzasPercent}%)`, 10, 120);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('justified_absences')); ?>: ${justificadosCount} (${justificadosPercent}%)`, 10, 140);

                        const finalImgData = textCanvas.toDataURL('image/png');
                        const link = document.createElement('a');
                        link.href = finalImgData;
                        link.download = "<?php echo ucfirst(get_phrase('class_attendance')); ?> <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('percentage_graph')); ?> - <?php echo $current_date_formatted; ?>.png";
                        link.click();
                    });
                }
            }, 500);
        });

        document.getElementById('downloadJpeg').addEventListener('click', function () {
            setTimeout(() => {
                const chartContainer = document.getElementById('chartContainer');
                        const viewType = document.getElementById('viewType').value;

                        let viewTypeText = "";
                        let viewTypeDetails = "";

                        if (viewType === 'daily') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('daily'));?>';
                            let dailyDate = document.getElementById('daily').value; 
                            let dateParts = dailyDate.split('-'); 
                            let formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`; 
                            viewTypeDetails = formattedDate;
                        } else if (viewType === 'weekly') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('weekly'));?>';  
                            const startDate = document.getElementById('startDate').value;
                            const endDate = document.getElementById('endDate').value;
                            
                            let startDateParts = startDate.split('-'); 
                            let formattedStartDate = `${startDateParts[2]}/${startDateParts[1]}/${startDateParts[0]}`;
                            
                            let endDateParts = endDate.split('-'); 
                            let formattedEndDate = `${endDateParts[2]}/${endDateParts[1]}/${endDateParts[0]}`; 
                            
                            viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${formattedStartDate} <?php echo get_phrase('to'); ?> ${formattedEndDate}`;  
                        } else if (viewType === 'monthly') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('monthly'));?>'; 
                            const month = document.getElementById('monthly').value;

                            if (month === 'January') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("january")); ?>';
                            } else if (month === 'February') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                            } else if (month === 'March') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                            } else if (month === 'April') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                            } else if (month === 'May') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                            } else if (month === 'June') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                            } else if (month === 'July') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                            } else if (month === 'August') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                            } else if (month === 'September') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                            } else if (month === 'October') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                            } else if (month === 'November') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                            } else if (month === 'December') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                            }

                            viewTypeDetails = `${translatedMonth}`;  // Solo 'from' porque se selecciona un mes
                        } else if (viewType === 'yearly') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('yearly'));?>';  // Usar traducción para 'yearly'
                            const startMonth = document.getElementById('startDateYearly').value;
                            const endMonth = document.getElementById('endDateYearly').value;

                            if (startMonth === 'January') {
                                    translatedStartDate = '<?php echo ucfirst(get_phrase("january")); ?>';
                                } else if (startMonth === 'February') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                                } else if (startMonth === 'March') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                                } else if (startMonth === 'April') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                                } else if (startMonth === 'May') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                                } else if (startMonth === 'June') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                                } else if (startMonth === 'July') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                                } else if (startMonth === 'August') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                                } else if (startMonth === 'September') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                                } else if (startMonth === 'October') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                                } else if (startMonth === 'November') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                                } else if (startMonth === 'December') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                                }

                                if (endMonth === 'January') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("january")); ?>';
                                } else if (endMonth === 'February') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                                } else if (endMonth === 'March') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                                } else if (endMonth === 'April') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                                } else if (endMonth === 'May') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                                } else if (endMonth === 'June') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                                } else if (endMonth === 'July') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                                } else if (endMonth === 'August') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                                } else if (endMonth === 'September') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                                } else if (endMonth === 'October') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                                } else if (endMonth === 'November') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                                } else if (endMonth === 'December') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                                }

                            viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${translatedStarMonth} <?php echo get_phrase('to'); ?> ${translatedEndMonth}`;  // Usar las palabras "from" y "to" traducidas
                        }

                        // Obtener los valores de los spans
                        const presentesCount = parseInt(document.getElementById('presentes-count').textContent, 10);
                        const ausentesCount = parseInt(document.getElementById('ausentes-count').textContent, 10);
                        const tardanzasCount = parseInt(document.getElementById('tardanzas-count').textContent, 10);
                        const justificadosCount = parseInt(document.getElementById('justificados-count').textContent, 10);

                        const totalCount = presentesCount + ausentesCount + tardanzasCount + justificadosCount;

                        // Calcular los porcentajes
                        const presentesPercent = ((presentesCount / totalCount) * 100).toFixed(2);
                        const ausentesPercent = ((ausentesCount / totalCount) * 100).toFixed(2);
                        const tardanzasPercent = ((tardanzasCount / totalCount) * 100).toFixed(2);
                        const justificadosPercent = ((justificadosCount / totalCount) * 100).toFixed(2);

                if (chartContainer) {
                    html2canvas(chartContainer).then(canvas => {
                        const imgData = canvas.toDataURL('image/jpeg');
                        
                        const textCanvas = document.createElement('canvas');
                        const ctx = textCanvas.getContext('2d');
                        textCanvas.width = canvas.width;
                        textCanvas.height = canvas.height + 0; // Más espacio para texto (ajusta según sea necesario)
                        ctx.drawImage(canvas, 0, 0);

                        ctx.font = "16px Helvetica";
                        ctx.fillText("<?php echo ucfirst(get_phrase('class_attendance')); ?> <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('percentage_graph')); ?>", 10, 20);
                        ctx.font = "12px Helvetica";
                        ctx.fillText(`<?php echo ucfirst(get_phrase('view_type')); ?>: ${viewTypeText}`, 10, 40);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('details')); ?>: ${viewTypeDetails}`,10, 60);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('students_present')); ?>: ${presentesCount} (${presentesPercent}%)`, 10, 80);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('absences')); ?>: ${ausentesCount} (${ausentesPercent}%)`, 10, 100);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('tardies')); ?>: ${tardanzasCount} (${tardanzasPercent}%)`, 10, 120);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('justified_absences')); ?>: ${justificadosCount} (${justificadosPercent}%)`, 10, 140);

                        const finalImgData = textCanvas.toDataURL('image/jpeg');
                        const link = document.createElement('a');
                        link.href = finalImgData;
                        link.download = "<?php echo ucfirst(get_phrase('class_attendance')); ?> <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('percentage_graph')); ?> - <?php echo $current_date_formatted; ?>.jpeg";
                        link.click();
                    });
                }
            }, 500);
        });


        document.getElementById('downloadPdf2').addEventListener('click', function () {
            // Esperar a que el gráfico esté completamente renderizado
            setTimeout(() => {
                const chartContainer = document.getElementById('chartContainer2');
                const viewType = document.getElementById('viewType2').value;

                let viewTypeText = "";
                let viewTypeDetails = "";

                if (viewType === 'daily') {
                    viewTypeText = '<?php echo ucfirst(get_phrase('daily'));?>';
                    let dailyDate = document.getElementById('daily2').value; 
                    let dateParts = dailyDate.split('-'); 
                    let formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`; 
                    viewTypeDetails = formattedDate;
                } else if (viewType === 'weekly') {
                    viewTypeText = '<?php echo ucfirst(get_phrase('weekly'));?>';  
                    const startDate = document.getElementById('startDate2').value;
                    const endDate = document.getElementById('endDate2').value;
                    
                    let startDateParts = startDate.split('-'); 
                    let formattedStartDate = `${startDateParts[2]}/${startDateParts[1]}/${startDateParts[0]}`;
                    
                    let endDateParts = endDate.split('-'); 
                    let formattedEndDate = `${endDateParts[2]}/${endDateParts[1]}/${endDateParts[0]}`; 
                    
                    viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${formattedStartDate} <?php echo get_phrase('to'); ?> ${formattedEndDate}`;  
                } else if (viewType === 'monthly') {
                    viewTypeText = '<?php echo ucfirst(get_phrase('monthly'));?>'; 
                    const month = document.getElementById('monthly2').value;

                    if (month === 'January') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("january")); ?>';
                    } else if (month === 'February') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                    } else if (month === 'March') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                    } else if (month === 'April') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                    } else if (month === 'May') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                    } else if (month === 'June') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                    } else if (month === 'July') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                    } else if (month === 'August') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                    } else if (month === 'September') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                    } else if (month === 'October') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                    } else if (month === 'November') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                    } else if (month === 'December') {
                        translatedMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                    }

                    viewTypeDetails = `${translatedMonth}`;  // Solo 'from' porque se selecciona un mes
                } else if (viewType === 'yearly') {
                    viewTypeText = '<?php echo ucfirst(get_phrase('yearly'));?>';  // Usar traducción para 'yearly'
                    const startMonth = document.getElementById('startDateYearly2').value;
                    const endMonth = document.getElementById('endDateYearly2').value;

                    if (startMonth === 'January') {
                            translatedStartDate = '<?php echo ucfirst(get_phrase("january")); ?>';
                        } else if (startMonth === 'February') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                        } else if (startMonth === 'March') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                        } else if (startMonth === 'April') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                        } else if (startMonth === 'May') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                        } else if (startMonth === 'June') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                        } else if (startMonth === 'July') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                        } else if (startMonth === 'August') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                        } else if (startMonth === 'September') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                        } else if (startMonth === 'October') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                        } else if (startMonth === 'November') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                        } else if (startMonth === 'December') {
                            translatedStarMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                        }

                        if (endMonth === 'January') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("january")); ?>';
                        } else if (endMonth === 'February') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                        } else if (endMonth === 'March') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                        } else if (endMonth === 'April') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                        } else if (endMonth === 'May') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                        } else if (endMonth === 'June') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                        } else if (endMonth === 'July') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                        } else if (endMonth === 'August') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                        } else if (endMonth === 'September') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                        } else if (endMonth === 'October') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                        } else if (endMonth === 'November') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                        } else if (endMonth === 'December') {
                            translatedEndMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                        }

                    viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${translatedStarMonth} <?php echo get_phrase('to'); ?> ${translatedEndMonth}`;  // Usar las palabras "from" y "to" traducidas
                }

                // Obtener los valores de los spans
                const presentesCount = parseInt(document.getElementById('presentes-count2').textContent, 10);
                const ausentesCount = parseInt(document.getElementById('ausentes-count2').textContent, 10);
                const tardanzasCount = parseInt(document.getElementById('tardanzas-count2').textContent, 10);
                const justificadosCount = parseInt(document.getElementById('justificados-count2').textContent, 10);

                const totalCount = presentesCount + ausentesCount + tardanzasCount + justificadosCount;

                // Calcular los porcentajes
                const presentesPercent = ((presentesCount / totalCount) * 100).toFixed(2);
                const ausentesPercent = ((ausentesCount / totalCount) * 100).toFixed(2);
                const tardanzasPercent = ((tardanzasCount / totalCount) * 100).toFixed(2);
                const justificadosPercent = ((justificadosCount / totalCount) * 100).toFixed(2);

                if (chartContainer) {
                    html2canvas(chartContainer).then(canvas => {
                        // Convertir a PDF
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF();
            const imgData = canvas.toDataURL('image/png');

            // Hacer el gráfico más grande ajustando las dimensiones
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

            const scaleFactor = 1.2; // Aumenta el valor si quieres hacerlo más grande
            const scaledPdfHeight = pdfHeight * scaleFactor;
            const scaledPdfWidth = pdfWidth * scaleFactor;

            // Añadir el título al PDF
            pdf.setFont("helvetica", "bold");
            pdf.setFontSize(16);
            pdf.text("<?php echo ucfirst(get_phrase('class_attendance')); ?> <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('quantity_graph')); ?>", 10, 20);

            const chartYPosition = 30; // Cambia este valor para bajar el gráfico

            // Agregar la imagen del gráfico al PDF
            pdf.addImage(imgData, 'PNG', 0, chartYPosition, pdfWidth, pdfHeight );

            // Añadir texto debajo del gráfico
            const textYPosition = chartYPosition + pdfHeight + 10; // Ajustar la posición para que esté después del gráfico

            pdf.setFont("helvetica", "normal");
            pdf.setFontSize(12);

            pdf.text(`<?php echo ucfirst(get_phrase('view_type')); ?>: ${viewTypeText}`, 10, textYPosition);
            pdf.text(`<?php echo ucfirst(get_phrase('details')); ?>: ${viewTypeDetails}`, 10, textYPosition + 10);

            // Mostrar los conteos y porcentajes para cada categoría
            pdf.text(`<?php echo ucfirst(get_phrase('students_present')); ?>: ${presentesCount} (${presentesPercent}%)`, 10, textYPosition + 20);
            pdf.text(`<?php echo ucfirst(get_phrase('absences')); ?>: ${ausentesCount} (${ausentesPercent}%)`, 10, textYPosition + 30);
            pdf.text(`<?php echo ucfirst(get_phrase('tardies')); ?>: ${tardanzasCount} (${tardanzasPercent}%)`, 10, textYPosition + 40);
            pdf.text(`<?php echo ucfirst(get_phrase('justified_absences')); ?>: ${justificadosCount} (${justificadosPercent}%)`, 10, textYPosition + 50);

            // Guardar el PDF
            pdf.save("<?php echo ucfirst(get_phrase('class_attendance')); ?> <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('quantity_graph')); ?> - <?php echo $current_date_formatted; ?>.pdf");
                    });
                } else {
                    console.error('El contenedor del gráfico no se encontró.');
                }
            }, 500); // 500ms para garantizar que el gráfico esté listo
        });




        document.getElementById('downloadPng2').addEventListener('click', function () {
            setTimeout(() => {
                const chartContainer = document.getElementById('chartContainer2');
                        const viewType = document.getElementById('viewType2').value;

                        let viewTypeText = "";
                        let viewTypeDetails = "";

                        if (viewType === 'daily') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('daily'));?>';
                            let dailyDate = document.getElementById('daily2').value; 
                            let dateParts = dailyDate.split('-'); 
                            let formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`; 
                            viewTypeDetails = formattedDate;
                        } else if (viewType === 'weekly') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('weekly'));?>';  
                            const startDate = document.getElementById('startDate2').value;
                            const endDate = document.getElementById('endDate2').value;
                            
                            let startDateParts = startDate.split('-'); 
                            let formattedStartDate = `${startDateParts[2]}/${startDateParts[1]}/${startDateParts[0]}`;
                            
                            let endDateParts = endDate.split('-'); 
                            let formattedEndDate = `${endDateParts[2]}/${endDateParts[1]}/${endDateParts[0]}`; 
                            
                            viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${formattedStartDate} <?php echo get_phrase('to'); ?> ${formattedEndDate}`;  
                        } else if (viewType === 'monthly') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('monthly'));?>'; 
                            const month = document.getElementById('monthly2').value;

                            if (month === 'January') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("january")); ?>';
                            } else if (month === 'February') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                            } else if (month === 'March') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                            } else if (month === 'April') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                            } else if (month === 'May') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                            } else if (month === 'June') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                            } else if (month === 'July') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                            } else if (month === 'August') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                            } else if (month === 'September') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                            } else if (month === 'October') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                            } else if (month === 'November') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                            } else if (month === 'December') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                            }

                            viewTypeDetails = `${translatedMonth}`;  // Solo 'from' porque se selecciona un mes
                        } else if (viewType === 'yearly') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('yearly'));?>';  // Usar traducción para 'yearly'
                            const startMonth = document.getElementById('startDateYearly2').value;
                            const endMonth = document.getElementById('endDateYearly2').value;

                            if (startMonth === 'January') {
                                    translatedStartDate = '<?php echo ucfirst(get_phrase("january")); ?>';
                                } else if (startMonth === 'February') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                                } else if (startMonth === 'March') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                                } else if (startMonth === 'April') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                                } else if (startMonth === 'May') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                                } else if (startMonth === 'June') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                                } else if (startMonth === 'July') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                                } else if (startMonth === 'August') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                                } else if (startMonth === 'September') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                                } else if (startMonth === 'October') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                                } else if (startMonth === 'November') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                                } else if (startMonth === 'December') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                                }

                                if (endMonth === 'January') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("january")); ?>';
                                } else if (endMonth === 'February') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                                } else if (endMonth === 'March') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                                } else if (endMonth === 'April') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                                } else if (endMonth === 'May') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                                } else if (endMonth === 'June') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                                } else if (endMonth === 'July') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                                } else if (endMonth === 'August') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                                } else if (endMonth === 'September') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                                } else if (endMonth === 'October') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                                } else if (endMonth === 'November') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                                } else if (endMonth === 'December') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                                }

                            viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${translatedStarMonth} <?php echo get_phrase('to'); ?> ${translatedEndMonth}`;  // Usar las palabras "from" y "to" traducidas
                        }

                        // Obtener los valores de los spans
                        const presentesCount = parseInt(document.getElementById('presentes-count2').textContent, 10);
                        const ausentesCount = parseInt(document.getElementById('ausentes-count2').textContent, 10);
                        const tardanzasCount = parseInt(document.getElementById('tardanzas-count2').textContent, 10);
                        const justificadosCount = parseInt(document.getElementById('justificados-count2').textContent, 10);

                        const totalCount = presentesCount + ausentesCount + tardanzasCount + justificadosCount;

                        // Calcular los porcentajes
                        const presentesPercent = ((presentesCount / totalCount) * 100).toFixed(2);
                        const ausentesPercent = ((ausentesCount / totalCount) * 100).toFixed(2);
                        const tardanzasPercent = ((tardanzasCount / totalCount) * 100).toFixed(2);
                        const justificadosPercent = ((justificadosCount / totalCount) * 100).toFixed(2);

                if (chartContainer) {
                    html2canvas(chartContainer).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        
                        const textCanvas = document.createElement('canvas');
                        const ctx = textCanvas.getContext('2d');
                        textCanvas.width = canvas.width;
                        textCanvas.height = canvas.height + 0;// Más espacio para texto (ajusta según sea necesario)
                        ctx.drawImage(canvas, 0, 0);

                        ctx.font = "16px Helvetica";
                        ctx.fillText("<?php echo ucfirst(get_phrase('class_attendance')); ?> <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('quantity_graph')); ?>", 10, 20);
                        ctx.font = "12px Helvetica";
                        ctx.fillText(`<?php echo ucfirst(get_phrase('view_type')); ?>: ${viewTypeText}`, 10, 40);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('details')); ?>: ${viewTypeDetails}`,10, 60);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('students_present')); ?>: ${presentesCount} (${presentesPercent}%)`, 10, 80);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('absences')); ?>: ${ausentesCount} (${ausentesPercent}%)`, 10, 100);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('tardies')); ?>: ${tardanzasCount} (${tardanzasPercent}%)`, 10, 120);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('justified_absences')); ?>: ${justificadosCount} (${justificadosPercent}%)`, 10, 140);

                        const finalImgData = textCanvas.toDataURL('image/png');
                        const link = document.createElement('a');
                        link.href = finalImgData;
                        link.download = "<?php echo ucfirst(get_phrase('class_attendance')); ?> <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('quantity_graph')); ?> - <?php echo $current_date_formatted; ?>.png";
                        link.click();
                    });
                }
            }, 500);
        });

        document.getElementById('downloadJpeg2').addEventListener('click', function () {
            setTimeout(() => {
                const chartContainer = document.getElementById('chartContainer2');
                        const viewType = document.getElementById('viewType2').value;

                        let viewTypeText = "";
                        let viewTypeDetails = "";

                        if (viewType === 'daily') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('daily'));?>';
                            let dailyDate = document.getElementById('daily2').value; 
                            let dateParts = dailyDate.split('-'); 
                            let formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`; 
                            viewTypeDetails = formattedDate;
                        } else if (viewType === 'weekly') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('weekly'));?>';  
                            const startDate = document.getElementById('startDate2').value;
                            const endDate = document.getElementById('endDate2').value;
                            
                            let startDateParts = startDate.split('-'); 
                            let formattedStartDate = `${startDateParts[2]}/${startDateParts[1]}/${startDateParts[0]}`;
                            
                            let endDateParts = endDate.split('-'); 
                            let formattedEndDate = `${endDateParts[2]}/${endDateParts[1]}/${endDateParts[0]}`; 
                            
                            viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${formattedStartDate} <?php echo get_phrase('to'); ?> ${formattedEndDate}`;  
                        } else if (viewType === 'monthly') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('monthly'));?>'; 
                            const month = document.getElementById('monthly2').value;

                            if (month === 'January') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("january")); ?>';
                            } else if (month === 'February') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                            } else if (month === 'March') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                            } else if (month === 'April') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                            } else if (month === 'May') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                            } else if (month === 'June') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                            } else if (month === 'July') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                            } else if (month === 'August') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                            } else if (month === 'September') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                            } else if (month === 'October') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                            } else if (month === 'November') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                            } else if (month === 'December') {
                                translatedMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                            }

                            viewTypeDetails = `${translatedMonth}`;  // Solo 'from' porque se selecciona un mes
                        } else if (viewType === 'yearly') {
                            viewTypeText = '<?php echo ucfirst(get_phrase('yearly'));?>';  // Usar traducción para 'yearly'
                            const startMonth = document.getElementById('startDateYearly2').value;
                            const endMonth = document.getElementById('endDateYearly2').value;

                            if (startMonth === 'January') {
                                    translatedStartDate = '<?php echo ucfirst(get_phrase("january")); ?>';
                                } else if (startMonth === 'February') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                                } else if (startMonth === 'March') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                                } else if (startMonth === 'April') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                                } else if (startMonth === 'May') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                                } else if (startMonth === 'June') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                                } else if (startMonth === 'July') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                                } else if (startMonth === 'August') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                                } else if (startMonth === 'September') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                                } else if (startMonth === 'October') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                                } else if (startMonth === 'November') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                                } else if (startMonth === 'December') {
                                    translatedStarMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                                }

                                if (endMonth === 'January') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("january")); ?>';
                                } else if (endMonth === 'February') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("february")); ?>';
                                } else if (endMonth === 'March') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("march")); ?>';
                                } else if (endMonth === 'April') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("april")); ?>';
                                } else if (endMonth === 'May') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("may")); ?>';
                                } else if (endMonth === 'June') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("june")); ?>';
                                } else if (endMonth === 'July') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("july")); ?>';
                                } else if (endMonth === 'August') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("august")); ?>';
                                } else if (endMonth === 'September') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("september")); ?>';
                                } else if (endMonth === 'October') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("october")); ?>';
                                } else if (endMonth === 'November') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("november")); ?>';
                                } else if (endMonth === 'December') {
                                    translatedEndMonth = '<?php echo ucfirst(get_phrase("december")); ?>';
                                }

                            viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${translatedStarMonth} <?php echo get_phrase('to'); ?> ${translatedEndMonth}`;  // Usar las palabras "from" y "to" traducidas
                        }

                        // Obtener los valores de los spans
                        const presentesCount = parseInt(document.getElementById('presentes-count2').textContent, 10);
                        const ausentesCount = parseInt(document.getElementById('ausentes-count2').textContent, 10);
                        const tardanzasCount = parseInt(document.getElementById('tardanzas-count2').textContent, 10);
                        const justificadosCount = parseInt(document.getElementById('justificados-count2').textContent, 10);

                        const totalCount = presentesCount + ausentesCount + tardanzasCount + justificadosCount;

                        // Calcular los porcentajes
                        const presentesPercent = ((presentesCount / totalCount) * 100).toFixed(2);
                        const ausentesPercent = ((ausentesCount / totalCount) * 100).toFixed(2);
                        const tardanzasPercent = ((tardanzasCount / totalCount) * 100).toFixed(2);
                        const justificadosPercent = ((justificadosCount / totalCount) * 100).toFixed(2);

                if (chartContainer) {
                    html2canvas(chartContainer).then(canvas => {
                        const imgData = canvas.toDataURL('image/jpeg');
                        
                        const textCanvas = document.createElement('canvas');
                        const ctx = textCanvas.getContext('2d');
                        textCanvas.width = canvas.width;
                        textCanvas.height = canvas.height + 0; // Más espacio para texto (ajusta según sea necesario)
                        ctx.drawImage(canvas, 0, 0);

                        ctx.font = "16px Helvetica";
                        ctx.fillText("<?php echo ucfirst(get_phrase('class_attendance')); ?> <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('quantity_graph')); ?>", 10, 20);
                        ctx.font = "12px Helvetica";
                        ctx.fillText(`<?php echo ucfirst(get_phrase('view_type')); ?>: ${viewTypeText}`, 10, 40);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('details')); ?>: ${viewTypeDetails}`,10, 60);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('students_present')); ?>: ${presentesCount} (${presentesPercent}%)`, 10, 80);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('absences')); ?>: ${ausentesCount} (${ausentesPercent}%)`, 10, 100);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('tardies')); ?>: ${tardanzasCount} (${tardanzasPercent}%)`, 10, 120);
                        ctx.fillText(`<?php echo ucfirst(get_phrase('justified_absences')); ?>: ${justificadosCount} (${justificadosPercent}%)`, 10, 140);

                        const finalImgData = textCanvas.toDataURL('image/jpeg');
                        const link = document.createElement('a');
                        link.href = finalImgData;
                        link.download = "<?php echo ucfirst(get_phrase('class_attendance')); ?> <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('quantity_graph')); ?> - <?php echo $current_date_formatted; ?>.jpeg";
                        link.click();
                    });
                }
            }, 500);
        });




        $('#viewType').on('change', function () {
            const selected = $(this).val();
            $('.dailyView, .weeklyView, .monthlyView, .yearlyView').hide(); // Ocultar todas las vistas

            const viewTypeContainer = $('.viewType');

            // Mostrar solo la vista seleccionada
            if (selected === 'daily') {
                $('.dailyView').fadeIn();
                viewTypeContainer.removeClass('col-md-3 col-md-4').addClass('col-md-6');
            } else if (selected === 'weekly') {
                $('.weeklyView').fadeIn();
                viewTypeContainer.removeClass('col-md-3 col-md-6').addClass('col-md-4');
            } else if (selected === 'monthly') {
                $('.monthlyView').fadeIn();
                viewTypeContainer.removeClass('col-md-3 col-md-4').addClass('col-md-6');
            } else if (selected === 'yearly') {
                $('.yearlyView').fadeIn();
                viewTypeContainer.removeClass('col-md-3 col-md-6').addClass('col-md-4');
            }
        });

        // Mostrar la vista diaria por defecto
        $('#viewType').trigger('change');

        $('#viewType2').on('change', function () {
            const selected = $(this).val();
            $('.dailyView2, .weeklyView2, .monthlyView2, .yearlyView2').hide(); // Ocultar todas las vistas

            const viewTypeContainer2 = $('.viewType2');

            // Mostrar solo la vista seleccionada
            if (selected === 'daily') {
                $('.dailyView2').fadeIn();
                viewTypeContainer2.removeClass('col-md-3 col-md-4').addClass('col-md-6');
            } else if (selected === 'weekly') {
                $('.weeklyView2').fadeIn();
                viewTypeContainer2.removeClass('col-md-3 col-md-6').addClass('col-md-4');
            } else if (selected === 'monthly') {
                $('.monthlyView2').fadeIn();
                viewTypeContainer2.removeClass('col-md-3 col-md-4').addClass('col-md-6');
            } else if (selected === 'yearly') {
                $('.yearlyView2').fadeIn();
                viewTypeContainer2.removeClass('col-md-3 col-md-6').addClass('col-md-4');
            }
        });

        // Mostrar la vista diaria por defecto
        $('#viewType2').trigger('change');


        $('#applyFilters').on('click', function () {
            console.log("Click en apply filters");
            const section_id = <?php echo $section_id; ?>;
            const filter_type = $('#viewType').val();
            let date = $('#daily').val();
            let start_date = $('#startDate').val();
            let end_date = $('#endDate').val();
            let dateMoth = $('#monthly').val();
            let start_date_yearly = $('#startDateYearly').val();
            let end_date_yearly = $('#endDateYearly').val();

            if (filter_type === 'daily') {
                date = date || 'null';
                start_date = 'null';
                end_date = 'null';
                dateMoth = 'null';
                start_date_yearly = 'null';
                end_date_yearly = 'null';
            } else if (filter_type === 'weekly') {
                date = 'null';
                start_date = start_date || 'null';
                end_date = end_date || 'null';
                dateMoth = 'null';
                start_date_yearly = 'null';
                end_date_yearly = 'null';
            } else if (filter_type === 'monthly') { 
                date = 'null';
                start_date = 'null';
                end_date = 'null';
                dateMoth = dateMoth || 'null';
                start_date_yearly = 'null';
                end_date_yearly = 'null';
            } else if (filter_type === 'yearly') { 
                date = 'null';
                start_date = 'null';
                end_date = 'null';
                dateMoth = 'null';
                start_date_yearly = start_date_yearly || 'null';
                end_date_yearly = end_date_yearly || 'null';
            }

            const url = `<?php echo base_url("index.php?admin/filter_attendance"); ?>/${section_id}/${filter_type}/${date}/${start_date}/${end_date}/${dateMoth}/${start_date_yearly}/${end_date_yearly}`;

            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json', // Especificar que esperamos datos JSON
                success: function (response) {
                    console.log(response); // Mostrar los datos recibidos en la consola para depuración

                    // Asignar valores predeterminados en caso de que los datos estén vacíos o nulos
                    const attendance_presente = response.attendance_student_presente || 0;
                    const attendance_ausente = response.attendance_student_ausente || 0;
                    const attendance_tardanza = response.attendance_student_tardanza || 0;
                    const attendance_ausencia_justificada = response.attendance_student_ausencia_justificada || 0;
                    const percentage_presente = response.percentage_presente || 0;
                    const percentage_ausente = response.percentage_ausente || 0;
                    const percentage_tardanza = response.percentage_tardanza || 0;
                    const percentage_justificados = response.percentage_justificados || 0;

                    // Actualizar los valores en el DOM con los valores predeterminados si es necesario
                    $('#presentes-count').text(attendance_presente);
                    $('#ausentes-count').text(attendance_ausente);
                    $('#tardanzas-count').text(attendance_tardanza);
                    $('#justificados-count').text(attendance_ausencia_justificada);

                    // Limpiar el contenedor del gráfico antes de renderizar uno nuevo
                    $('#chartAttendance').empty();

                    // Crear el gráfico Morris Donut con los datos recibidos
                    Morris.Donut({
                        element: 'chartAttendance',
                        data: [
                            {value: attendance_presente, label: '<?php echo ucfirst(get_phrase('students_present'));?>', formatted: percentage_presente + '%' },
                            {value: attendance_ausente, label: '<?php echo ucfirst(get_phrase('absences'));?>', formatted: percentage_ausente + '%' },
                            {value: attendance_tardanza, label: '<?php echo ucfirst(get_phrase('tardies'));?>', formatted: percentage_tardanza + '%' },
                            {value: attendance_ausencia_justificada, label: '<?php echo ucfirst(get_phrase('justified_absences'));?>', formatted: percentage_justificados + '%' }
                        ],
                        formatter: function (x, data) { return data.formatted; },
                        labelColor: 'black',
                        colors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF']
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Error al cargar datos:', error);
                }
            });

        });


        $('#applyFilters2').on('click', function () {
            console.log("Click en apply filters 2");
            const section_id = <?php echo $section_id; ?>;
            const filter_type = $('#viewType2').val();
            let date = $('#daily2').val();
            let start_date = $('#startDate2').val();
            let end_date = $('#endDate2').val();
            let dateMoth = $('#monthly2').val();
            let start_date_yearly = $('#startDateYearly2').val();
            let end_date_yearly = $('#endDateYearly2').val();

            if (filter_type === 'daily') {
                date = date || 'null';
                start_date = 'null';
                end_date = 'null';
                dateMoth = 'null';
                start_date_yearly = 'null';
                end_date_yearly = 'null';
            } else if (filter_type === 'weekly') {
                date = 'null';
                start_date = start_date || 'null';
                end_date = end_date || 'null';
                dateMoth = 'null';
                start_date_yearly = 'null';
                end_date_yearly = 'null';
            } else if (filter_type === 'monthly') {
                date = 'null';
                start_date = 'null';
                end_date = 'null';
                dateMoth = dateMoth || 'null';
                start_date_yearly = 'null';
                end_date_yearly = 'null';
            } else if (filter_type === 'yearly') { 
                date = 'null';
                start_date = 'null';
                end_date = 'null';
                dateMoth = 'null';
                start_date_yearly = start_date_yearly || 'null';
                end_date_yearly = end_date_yearly || 'null';
            }

            const urlBase = `<?php echo base_url("index.php?admin/filter_attendance"); ?>/${section_id}`;

            // Limpia el contenedor antes de crear un nuevo gráfico
            $('#chartAttendance2').empty();

            const fetchAttendanceByType = (filterType, date, startDate, endDate) => {
                return new Promise((resolve, reject) => {
                    const url = `${urlBase}/${filterType}/${date}/${startDate}/${endDate}`;
                    console.log(`Fetching data from: ${url}`);
                    $.ajax({
                        url: url,
                        method: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            console.log(response);
                            resolve(response || { attendance_student_presente: 0, attendance_student_ausente: 0, attendance_student_tardanza: 0, attendance_student_ausencia_justificada: 0 });
                        },
                        error: function (xhr, status, error) {
                            resolve({ attendance_student_presente: 0, attendance_student_ausente: 0, attendance_student_tardanza: 0, attendance_student_ausencia_justificada: 0 });
                        }
                    });
                });
            };

            const fetchDataForRange = async (startDate, endDate) => {
                let currentDate = new Date(startDate); // Mantén el formato yyyy-mm-dd
                let end = new Date(endDate);
                let chartData = [];

                while (currentDate <= end) {
                    const formattedDate = currentDate.toISOString().split('T')[0]; // Formato yyyy-mm-dd

                    // Consultar datos solo una vez por día para todos los tipos de asistencia
                    const response = await fetchAttendanceByType('daily', formattedDate, 'null', 'null');
                    
                    chartData.push({
                        x: formattedDate,
                        y: parseInt(response.attendance_student_presente) || 0,
                        z: parseInt(response.attendance_student_ausente) || 0,
                        a: parseInt(response.attendance_student_tardanza) || 0,
                        e: parseInt(response.attendance_student_ausencia_justificada) || 0
                    });

                    currentDate.setDate(currentDate.getDate() + 1); // Avanzar al siguiente día
                }

                return chartData;
            };

            let chartData = [];
            if (filter_type === 'weekly') {
                fetchDataForRange(start_date, end_date).then((data) => {
                    chartData = data;
                    console.log("Chart data for weekly:", chartData);

                    // Inicializar los totales
                    let totalPresentes = 0;
                    let totalAusentes = 0;
                    let totalTardanzas = 0;
                    let totalJustificados = 0;

                    // Calcular los totales iterando sobre los datos obtenidos
                    chartData.forEach((dayData) => {
                        totalPresentes += dayData.y || 0;
                        totalAusentes += dayData.z || 0;
                        totalTardanzas += dayData.a || 0;
                        totalJustificados += dayData.e || 0;
                    });

                    // Mostrar los totales en los elementos HTML correspondientes
                    $('#presentes-count2').text(totalPresentes);
                    $('#ausentes-count2').text(totalAusentes);
                    $('#tardanzas-count2').text(totalTardanzas);
                    $('#justificados-count2').text(totalJustificados);

                    // Crear el gráfico de barras
                    Morris.Bar({
                        element: 'chartAttendance2',
                        axes: true,
                        data: chartData,
                        xkey: 'x',
                        ykeys: ['y', 'z', 'a', 'e'],
                        labels: ['<?php echo ucfirst(get_phrase('present'));?>', '<?php echo ucfirst(get_phrase('absent'));?>', '<?php echo ucfirst(get_phrase('tardy'));?>', '<?php echo ucfirst(get_phrase('justified_absence'));?>'],
                        barColors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF']
                    });
                });
            } else if (filter_type === 'daily') {
                // Crear el objeto dailyData antes de la consulta AJAX
                const dailyData = {
                    x: date,
                    y: 0,
                    z: 0,
                    a: 0,
                    e: 0 // Valores predeterminados si no hay datos
                };

                // Hacer la consulta AJAX directamente
                $.ajax({
                    url: `${urlBase}/daily/${date}/null/null`, // Consulta para el filtro diario
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        console.log("Datos recibidos:", response);

                        // Actualizar los valores de dailyData con la respuesta obtenida
                        dailyData.y = response.attendance_student_presente || 0;
                        dailyData.z = response.attendance_student_ausente || 0;
                        dailyData.a = response.attendance_student_tardanza || 0;
                        dailyData.e = response.attendance_student_ausencia_justificada || 0;

                        $('#presentes-count2').text(dailyData.y);
                        $('#ausentes-count2').text(dailyData.z);
                        $('#tardanzas-count2').text(dailyData.a);
                        $('#justificados-count2').text(dailyData.e);

                        // Crear el gráfico con los datos obtenidos
                        Morris.Bar({
                            element: 'chartAttendance2',
                            axes: true,
                            data: [dailyData], // Aquí pasamos los datos directamente
                            xkey: 'x',
                            ykeys: ['y', 'z', 'a', 'e'],
                            labels: ['<?php echo ucfirst(get_phrase('present'));?>', '<?php echo ucfirst(get_phrase('absent'));?>', '<?php echo ucfirst(get_phrase('tardy'));?>', '<?php echo ucfirst(get_phrase('justified_absence'));?>'],
                            barColors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF']
                        });

                        console.log("Gráfico actualizado con los datos diarios:", dailyData);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al obtener los datos:", error);

                        // En caso de error, mostrar valores predeterminados
                        Morris.Bar({
                            element: 'chartAttendance2',
                            axes: true,
                            data: [dailyData], // Pasamos los datos predeterminados en caso de error
                            xkey: 'x',
                            ykeys: ['y', 'z', 'a', 'e'],
                            labels: ['<?php echo ucfirst(get_phrase('present'));?>', '<?php echo ucfirst(get_phrase('absent'));?>', '<?php echo ucfirst(get_phrase('tardy'));?>', '<?php echo ucfirst(get_phrase('justified_absence'));?>'],
                            barColors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF']
                        });
                    }
                });
            } else if (filter_type === 'monthly') {
                // Crear el objeto dailyData antes de la consulta AJAX
                const monthlyData = {
                    x: dateMoth,
                    y: 0,
                    z: 0,
                    a: 0,
                    e: 0 // Valores predeterminados si no hay datos
                };

                // Hacer la consulta AJAX directamente
                $.ajax({
                    url: `${urlBase}/monthly/null/null/null/${dateMoth}`, // Consulta para el filtro diario
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        console.log("Datos recibidos:", response);

                        // Actualizar los valores de dailyData con la respuesta obtenida
                        monthlyData.y = response.attendance_student_presente || 0;
                        monthlyData.z = response.attendance_student_ausente || 0;
                        monthlyData.a = response.attendance_student_tardanza || 0;
                        monthlyData.e = response.attendance_student_ausencia_justificada || 0;

                        $('#presentes-count2').text(monthlyData.y);
                        $('#ausentes-count2').text(monthlyData.z);
                        $('#tardanzas-count2').text(monthlyData.a);
                        $('#justificados-count2').text(monthlyData.e);

                        // Crear el gráfico con los datos obtenidos
                        Morris.Bar({
                            element: 'chartAttendance2',
                            axes: true,
                            data: [monthlyData], // Aquí pasamos los datos directamente
                            xkey: 'x',
                            ykeys: ['y', 'z', 'a', 'e'],
                            labels: ['<?php echo ucfirst(get_phrase('present'));?>', '<?php echo ucfirst(get_phrase('absent'));?>', '<?php echo ucfirst(get_phrase('tardy'));?>', '<?php echo ucfirst(get_phrase('justified_absence'));?>'],
                            barColors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF']
                        });

                        console.log("Gráfico actualizado con los datos diarios:", monthlyData);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al obtener los datos:", error);

                        // En caso de error, mostrar valores predeterminados
                        Morris.Bar({
                            element: 'chartAttendance2',
                            axes: true,
                            data: [monthlyData], // Pasamos los datos predeterminados en caso de error
                            xkey: 'x',
                            ykeys: ['y', 'z', 'a', 'e'],
                            labels: ['<?php echo ucfirst(get_phrase('present'));?>', '<?php echo ucfirst(get_phrase('absent'));?>', '<?php echo ucfirst(get_phrase('tardy'));?>', '<?php echo ucfirst(get_phrase('justified_absence'));?>'],
                            barColors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF']
                        });
                    }
                });
            } 
            if (filter_type === 'yearly') {
                

                let translatedStartDate = '';
                let translatedEndDate = '';

                if (start_date_yearly === 'January') {
                    translatedStartDate = '<?php echo ucfirst(get_phrase("january")); ?>';
                } else if (start_date_yearly === 'February') {
                    translatedStartDate = '<?php echo ucfirst(get_phrase("february")); ?>';
                } else if (start_date_yearly === 'March') {
                    translatedStartDate = '<?php echo ucfirst(get_phrase("march")); ?>';
                } else if (start_date_yearly === 'April') {
                    translatedStartDate = '<?php echo ucfirst(get_phrase("april")); ?>';
                } else if (start_date_yearly === 'May') {
                    translatedStartDate = '<?php echo ucfirst(get_phrase("may")); ?>';
                } else if (start_date_yearly === 'June') {
                    translatedStartDate = '<?php echo ucfirst(get_phrase("june")); ?>';
                } else if (start_date_yearly === 'July') {
                    translatedStartDate = '<?php echo ucfirst(get_phrase("july")); ?>';
                } else if (start_date_yearly === 'August') {
                    translatedStartDate = '<?php echo ucfirst(get_phrase("august")); ?>';
                } else if (start_date_yearly === 'September') {
                    translatedStartDate = '<?php echo ucfirst(get_phrase("september")); ?>';
                } else if (start_date_yearly === 'October') {
                    translatedStartDate = '<?php echo ucfirst(get_phrase("october")); ?>';
                } else if (start_date_yearly === 'November') {
                    translatedStartDate = '<?php echo ucfirst(get_phrase("november")); ?>';
                } else if (start_date_yearly === 'December') {
                    translatedStartDate = '<?php echo ucfirst(get_phrase("december")); ?>';
                }

                if (end_date_yearly === 'January') {
                    translatedEndDate = '<?php echo ucfirst(get_phrase("january")); ?>';
                } else if (end_date_yearly === 'February') {
                    translatedEndDate = '<?php echo ucfirst(get_phrase("february")); ?>';
                } else if (end_date_yearly === 'March') {
                    translatedEndDate = '<?php echo ucfirst(get_phrase("march")); ?>';
                } else if (end_date_yearly === 'April') {
                    translatedEndDate = '<?php echo ucfirst(get_phrase("april")); ?>';
                } else if (end_date_yearly === 'May') {
                    translatedEndDate = '<?php echo ucfirst(get_phrase("may")); ?>';
                } else if (end_date_yearly === 'June') {
                    translatedEndDate = '<?php echo ucfirst(get_phrase("june")); ?>';
                } else if (end_date_yearly === 'July') {
                    translatedEndDate = '<?php echo ucfirst(get_phrase("july")); ?>';
                } else if (end_date_yearly === 'August') {
                    translatedEndDate = '<?php echo ucfirst(get_phrase("august")); ?>';
                } else if (end_date_yearly === 'September') {
                    translatedEndDate = '<?php echo ucfirst(get_phrase("september")); ?>';
                } else if (end_date_yearly === 'October') {
                    translatedEndDate = '<?php echo ucfirst(get_phrase("october")); ?>';
                } else if (end_date_yearly === 'November') {
                    translatedEndDate = '<?php echo ucfirst(get_phrase("november")); ?>';
                } else if (end_date_yearly === 'December') {
                    translatedEndDate = '<?php echo ucfirst(get_phrase("december")); ?>';
                }

                const yearlyData = {
                    x: translatedStartDate + ' <?php echo get_phrase("to"); ?> ' + translatedEndDate,
                    y: 0,
                    z: 0,
                    a: 0,
                    e: 0
                };

                $.ajax({
                    url: `${urlBase}/yearly/null/null/null/null/${start_date_yearly}/${end_date_yearly}`, // Consulta para el filtro diario
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        console.log("Datos recibidos:", response);

                        // Actualizar los valores de dailyData con la respuesta obtenida
                        yearlyData.y = response.attendance_student_presente || 0;
                        yearlyData.z = response.attendance_student_ausente || 0;
                        yearlyData.a = response.attendance_student_tardanza || 0;
                        yearlyData.e = response.attendance_student_ausencia_justificada || 0;

                        $('#presentes-count2').text(yearlyData.y);
                        $('#ausentes-count2').text(yearlyData.z);
                        $('#tardanzas-count2').text(yearlyData.a);
                        $('#justificados-count2').text(yearlyData.e);

                        // Crear el gráfico con los datos obtenidos
                        Morris.Bar({
                            element: 'chartAttendance2',
                            axes: true,
                            data: [yearlyData], // Aquí pasamos los datos directamente
                            xkey: 'x',
                            ykeys: ['y', 'z', 'a', 'e'],
                            labels: ['<?php echo ucfirst(get_phrase('present'));?>', '<?php echo ucfirst(get_phrase('absent'));?>', '<?php echo ucfirst(get_phrase('tardy'));?>', '<?php echo ucfirst(get_phrase('justified_absence'));?>'],
                            barColors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF']
                        });

                        console.log("Gráfico actualizado con los datos diarios:", yearlyData);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al obtener los datos:", error);

                        // En caso de error, mostrar valores predeterminados
                        Morris.Bar({
                            element: 'chartAttendance2',
                            axes: true,
                            data: [yearlyData], // Pasamos los datos predeterminados en caso de error
                            xkey: 'x',
                            ykeys: ['y', 'z', 'a', 'e'],
                            labels: ['<?php echo ucfirst(get_phrase('present'));?>', '<?php echo ucfirst(get_phrase('absent'));?>', '<?php echo ucfirst(get_phrase('tardy'));?>', '<?php echo ucfirst(get_phrase('justified_absence'));?>'],
                            barColors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF']
                        });
                    }
                });
            }

        });




    });


</script> 


<style>
    .button-container {
        display: flex;
        justify-content: center; 
        align-items: center;   
        gap: 10px;            
        margin: -5px 0px 20px 0px;            
    }

    .customInput, .customSelect {
        background-color: var(--color-forty) !important; 
        color: var(--color-primary)  !important;
        border: 2px solid var(--color-secondary) !important; 
        padding: 0px !important; 
        border-radius: 5px !important; 
        transition: all 0.3s ease !important; ; 
    }

    .customInput:focus, .customSelect:focus {
        background-color: var(--color-white) !important; 
        border-color: var(--color-terciary) !important;  
        outline: none !important; 
    }   .customInput:hover, .customSelect:hover {
        background-color: var(--color-white) !important; 
        border-color: var(--color-terciary) !important;  
        outline: none !important; 
    }

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
    


    .col-md-12 .tile-stats {
        padding: 0px 0px 0px 0px !important;
    }

    .tile-stats.tile-white:hover {
        background: #fff !important;
    }

    table.table:hover {
        background-color: #fff !important;
    }

    .table tbody tr td:hover {
        background-color: #fff !important;
    }

    .display-column {
        display: none !important;
    }

    #all_student_table_filter {
        margin-top: 5px !important;
        
    }  #all_student_table_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
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
