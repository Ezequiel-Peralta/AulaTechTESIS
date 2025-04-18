




<?php
if (empty($subject_id)) {
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
                                <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_students_mark/<?php echo $row['section_id']; ?>"
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
                                <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_students_mark/<?php echo $row['section_id']; ?>"
                                    <?php if ($section_id == $row['section_id']) echo 'selected="selected"'; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                    <?php 
                            endforeach;
                        } else {
                            // No hay períodos académicos activos ni seleccionados
                            echo '<option value="">No hay secciones disponibles</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <?php
} 
?>


<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
                <li class="active">
                    <a href="#tab-<?php echo $section_id; ?>" data-toggle="tab">
                        <?php echo $section_data['name']; ?>
                        <span class="badge badge-success badge-nav-tabs-quantity">
                            <?php
                                if (!empty($subject_id)) {
                                    echo $individual_section_subject_count;
                                } else {
                                    echo $section_subject_count;
                                }
                            ?>
                        </span>
                    </a>
                </li>
        </ul>
        
        <div class="tab-content">
                <div class="tab-pane active" id="tab-<?php echo $section_data['section_id']; ?>">
                    <br>
                    <div class="mt-2 mb-4">
                        <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    </div>
                    <br>
                    <div class="panel-group joined" id="accordion-test-1">
                        <?php
                        if (!empty($subject_id)) {
                            $this->db->where('subject_id', $subject_id);
                            $subject = $this->db->where('subject_id', $subject_id)->get('subject')->row_array();

                            if (empty($subject)) {
                                $subject = $this->db->where('subject_id', $subject_id)->get('subject_history')->row_array();
                            }

                            if ($subject): 
                                ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title text-center">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-1" href="#collapse-section<?php echo $subject['subject_id']; ?>">
                                                        <i class="entypo-graduation-cap"></i> <?php echo $subject['name']; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse-section<?php echo $subject['subject_id']; ?>" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                <center>
                                            <table class="table table-bordered datatable table-hover table-striped" style="border-radius: 10px !important;" data-subject-id="<?php echo $subject['subject_id']; ?>" id="dataTable_<?php echo $subject['subject_id']; ?>">
                                                <thead>
                                                    <tr>
                                                        <th class="subject-cell text-center">Estudiante</th>
                                                        <?php for ($i = 1; $i <= 7; $i++): ?>
                                                            <th colspan="2" class="evaluation-cell text-center">Eval. <?php echo $i; ?></th>
                                                        <?php endfor; ?>
                                                        <th colspan="2" class="evaluation-cell text-center">JIIS 1°</th>
                                                        <th colspan="2" class="evaluation-cell text-center">JIIS 2°</th>
                                                        <th colspan="1" class="evaluation-cell text-center">Colq. Dic</th>
                                                        <th colspan="1" class="evaluation-cell text-center">Colq. Feb</th>
                                                        <th colspan="1" class="evaluation-cell text-center">Calf. Def</th>
                                                        <th colspan="2" class="evaluation-cell text-center">Exam. Previo</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="evaluation-cell text-center"></th>
                                                        <?php for ($i = 1; $i <= 7; $i++): ?>
                                                            <th class="evaluation-cell text-center">N</th>
                                                            <th class="recovery-cell text-center">R</th>
                                                        <?php endfor; ?>
                                                        <th class="evaluation-cell text-center">N</th>
                                                        <th class="recovery-cell text-center">R</th>
                                                        <th class="evaluation-cell text-center">N</th>
                                                        <th class="recovery-cell text-center">R</th>
                                                        <th class="evaluation-cell text-center">N</th>
                                                        <th class="evaluation-cell text-center">N</th>
                                                        <th class="evaluation-cell text-center">N</th>
                                                        <th class="evaluation-cell text-center">N</th>
                                                        <th class="evaluation-cell text-center">Fecha</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
// Obtener los estudiantes directamente de la tabla student_details
$students = $this->db->get_where('student_details', array('section_id' => $section_data['section_id']))->result_array();

// Verificar si no se encontraron estudiantes
if (empty($students)) {
    // Buscar en academic_history según el section_id en la columna new_section_id
    $this->db->select('student_id');
    $this->db->where('new_section_id', $section_data['section_id']);
    $academic_history_students = $this->db->get('academic_history')->result_array();

    // Obtener solo los student_id
    $student_ids = array_column($academic_history_students, 'student_id');

    // Si se encontraron student_id, buscar en student_details
    if (!empty($student_ids)) {
        $this->db->where_in('student_id', $student_ids);
        $students = $this->db->get('student_details')->result_array();
    }
}

// Ahora $students contiene los registros, ya sea de student_details o basado en academic_history
foreach ($students as $student):
?>
        <tr class="text-center" id="<?php echo $student['student_id']; ?>">
            <td class="student-element-cell"><?php echo $student['lastname']; ?>, <?php echo $student['firstname']; ?></td>
            <?php
            if ($used_section_history || $used_subject_history) {
                $marks = $this->Marks_model->get_marks_by_student_subject2($student['student_id'], $subject['subject_id'], $academic_period_id);
            } else {
                $marks = $this->Marks_model->get_marks_by_student_subject($student['student_id'], $subject['subject_id']);
            }
            
            // Create an associative array to store all marks
            $all_marks = array(
                'E1' => '', 'R1' => '', 'E2' => '', 'R2' => '', 'E3' => '', 'R3' => '', 'E4' => '', 'R4' => '',
                'E5' => '', 'R5' => '', 'E6' => '', 'R6' => '', 'E7' => '', 'R7' => '',
                'JIIS1' => '', 'JIIS1-R' => '', 'JIIS2' => '', 'JIIS2-R' => '',
                'COL-DIC' => '', 'COL-FEB' => '', 'CAL-DEF' => '', 'EXAM-PREV' => '', 'EXAM-PREV-DATE' => ''
            );
            $mark_ids = array();

            // Process all marks
            foreach ($marks as $mark) {
                $exam_type_id = $mark['exam_type_id'];
                $exam_type_info = $this->Exams_model->get_exam_type_info($exam_type_id);
                
                if (!empty($exam_type_info) && isset($exam_type_info[0]['short_name'])) {
                    $short_name = $exam_type_info[0]['short_name'];
                    if (array_key_exists($short_name, $all_marks)) {
                       
                        if ($short_name === 'CAL-DEF') {
                            $all_marks[$short_name] = $mark['mark_obtained'];
                        } else {
                            $mark_obtained = floatval($mark['mark_obtained']);
                            $all_marks[$short_name] = $mark_obtained != 0 ? intval($mark_obtained) : '';
                        }

                        $mark_ids[$short_name] = $mark['mark_id'];
                        if ($short_name == 'EXAM-PREV') {
                            $all_marks['EXAM-PREV-DATE'] = $mark['date'];
                        }
                    }
                }
            }

            // Generate inputs for E1-E7 and R1-R7
            for ($i = 1; $i <= 7; $i++) {
                $exam_type = 'E' . $i;
                $recovery_type = 'R' . $i;
                ?>
                <td class="evaluation-cell">
                    <input type="text" 
                        value="<?php echo $all_marks[$exam_type]; ?>" 
                        class="mark-input <?php echo $exam_type; ?> input evaluation-input popover-primary" 
                        data-toggle="popover" data-trigger="hover" data-placement="top"
                        data-mark-id="<?php echo isset($mark_ids[$exam_type]) ? $mark_ids[$exam_type] : ''; ?>" 
                        data-class-id="<?php echo $student['class_id']; ?>" 
                        data-section-id="<?php echo $student['section_id']; ?>" 
                        data-subject-id="<?php echo $subject['subject_id']; ?>" 
                        data-student-id="<?php echo $student['student_id']; ?>"
                        data-exam-type="<?php echo $i; ?>"
                        maxlength="2"
                        <?php echo ($used_subject_history || $used_section_history) ? 'disabled' : ''; ?>/>
                </td>
                <td class="recovery-cell">
                    <input type="text" 
                        value="<?php echo $all_marks[$recovery_type]; ?>" 
                        class="mark-input <?php echo $recovery_type; ?> input recovery-input popover-primary" 
                        disabled
                        data-toggle="popover" data-trigger="hover" data-placement="top"
                        data-mark-id="<?php echo isset($mark_ids[$recovery_type]) ? $mark_ids[$recovery_type] : ''; ?>"
                        data-class-id="<?php echo $student['class_id']; ?>" 
                        data-section-id="<?php echo $student['section_id']; ?>" 
                        data-subject-id="<?php echo $subject['subject_id']; ?>" 
                        data-student-id="<?php echo $student['student_id']; ?>"
                        data-exam-type="<?php echo $i + 7; ?>" 
                        maxlength="2"
                        <?php echo ($used_subject_history || $used_section_history) ? 'disabled' : ''; ?>/>
                </td>
                <?php
            }

            // Generate inputs for special exam types
            $special_exams = array(
                'JIIS1' => 15, 'JIIS1-R' => 17, 'JIIS2' => 16, 'JIIS2-R' => 18,
                'COL-DIC' => 19, 'COL-FEB' => 20, 'CAL-DEF' => 21, 'EXAM-PREV' => 22
            );

            foreach ($special_exams as $exam_type => $exam_type_id) {
                $input_class = str_replace('-', '', strtolower($exam_type));
                ?>
                <td class="evaluation-cell">
                    <input type="text" 
                        value="<?php echo $all_marks[$exam_type]; ?>" 
                        class="mark-input <?php echo $exam_type; ?> input evaluation-input-<?php echo $input_class; ?> popover-primary" 
                        <?php if (in_array($exam_type, ['JIIS1-R', 'JIIS2-R', 'COL-DIC', 'COL-FEB', 'CAL-DEF', 'EXAM-PREV'])) echo 'disabled'; ?>
                        data-toggle="popover" data-trigger="hover" data-placement="top"
                        data-mark-id="<?php echo isset($mark_ids[$exam_type]) ? $mark_ids[$exam_type] : ''; ?>" 
                        data-class-id="<?php echo $student['class_id']; ?>" 
                        data-section-id="<?php echo $student['section_id']; ?>" 
                        data-subject-id="<?php echo $subject['subject_id']; ?>" 
                        data-student-id="<?php echo $student['student_id']; ?>"
                        data-exam-type="<?php echo $exam_type_id; ?>"
                        <?php if (in_array($exam_type, ['EXAM-PREV'])) echo 'data-date:'; ?>
                        maxlength="2"
                        <?php echo ($used_subject_history || $used_section_history) ? 'disabled' : ''; ?>/>
                </td>
                <?php
            }
            ?>
            <td class="date-cell">
                <input type="text" 
                    value="<?php echo $all_marks['EXAM-PREV-DATE']; ?>" 
                    class="date-mark-input mark-input D-EXAM-PREV input date-evaluation-input-exam-prev" 
                    disabled
                    data-mark-id="<?php echo isset($mark_ids['EXAM-PREV']) ? $mark_ids['EXAM-PREV'] : ''; ?>" 
                    data-class-id="<?php echo $student['class_id']; ?>" 
                    data-section-id="<?php echo $student['section_id']; ?>" 
                    data-subject-id="<?php echo $subject['subject_id']; ?>" 
                    data-student-id="<?php echo $student['student_id']; ?>"
                    data-exam-prev-date="<?php echo $all_marks['EXAM-PREV-DATE']; ?>" 
                    <?php echo ($used_subject_history || $used_section_history) ? 'disabled' : ''; ?>/>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                                            
                                            </table>

                                            <?php if ($used_subject_history || $used_section_history): ?>
                                            
                                            <?php else: ?>
                                                <button class="btn btn-table calculateButton" style="background-color: #fff !important; color: #265044; font-weight: bold;" id="calculateButton"  data-subject-id="<?php echo $subject['subject_id']; ?>"  >Calcular promedio</button>
                                            <?php endif; ?> 
      

                                            

                                        </center>
                                    </div>
                                </div>
                            </div>
                            <?php
                                endif;
                            } else {
                                $subjects = $this->Subjects_model->get_subjects_by_section2($section_data['section_id']);
                                foreach ($subjects as $subject):
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title text-center">
                                        <a data-toggle="collapse" data-parent="#accordion-test-1" href="#collapse-section<?php echo $subject['subject_id']; ?>">
                                            <i class="entypo-graduation-cap"></i>  <?php echo $subject['name']; ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-section<?php echo $subject['subject_id']; ?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <center>
                                            <table class="table table-bordered datatable table-hover table-striped" style="border-radius: 10px !important;" id="dataTable_<?php echo $subject['subject_id']; ?>">
                                                <thead>
                                                    <tr>
                                                        <th class="subject-cell text-center">Estudiante</th>
                                                        <?php for ($i = 1; $i <= 7; $i++): ?>
                                                            <th colspan="2" class="evaluation-cell text-center">Eval. <?php echo $i; ?></th>
                                                        <?php endfor; ?>
                                                        <th colspan="2" class="evaluation-cell text-center">JIIS 1°</th>
                                                        <th colspan="2" class="evaluation-cell text-center">JIIS 2°</th>
                                                        <th colspan="1" class="evaluation-cell text-center">Colq. Dic</th>
                                                        <th colspan="1" class="evaluation-cell text-center">Colq. Feb</th>
                                                        <th colspan="1" class="evaluation-cell text-center">Calf. Def</th>
                                                        <th colspan="2" class="evaluation-cell text-center">Exam. Previo</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="evaluation-cell text-center"></th>
                                                        <?php for ($i = 1; $i <= 7; $i++): ?>
                                                            <th class="evaluation-cell text-center">N</th>
                                                            <th class="recovery-cell text-center">R</th>
                                                        <?php endfor; ?>
                                                        <th class="evaluation-cell text-center">N</th>
                                                        <th class="recovery-cell text-center">R</th>
                                                        <th class="evaluation-cell text-center">N</th>
                                                        <th class="recovery-cell text-center">R</th>
                                                        <th class="evaluation-cell text-center">N</th>
                                                        <th class="evaluation-cell text-center">N</th>
                                                        <th class="evaluation-cell text-center">N</th>
                                                        <th class="evaluation-cell text-center">N</th>
                                                        <th class="evaluation-cell text-center">Fecha</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
// Obtener los estudiantes directamente de la tabla student_details
$students = $this->db->get_where('student_details', array('section_id' => $section_data['section_id']))->result_array();

// Verificar si no se encontraron estudiantes
if (empty($students)) {
    // Buscar en academic_history según el section_id en la columna new_section_id
    $this->db->select('student_id');
    $this->db->where('new_section_id', $section_data['section_id']);
    $academic_history_students = $this->db->get('academic_history')->result_array();

    // Obtener solo los student_id
    $student_ids = array_column($academic_history_students, 'student_id');

    // Si se encontraron student_id, buscar en student_details
    if (!empty($student_ids)) {
        $this->db->where_in('student_id', $student_ids);
        $students = $this->db->get('student_details')->result_array();
    }
}

// Ahora $students contiene los registros, ya sea de student_details o basado en academic_history
foreach ($students as $student):
?>
        <tr class="text-center" id="<?php echo $student['student_id']; ?>">
            <td class="student-element-cell"><?php echo $student['lastname']; ?>, <?php echo $student['firstname']; ?></td>
            <?php
            if ($used_section_history || $used_subject_history) {
                $marks = $this->Marks_model->get_marks_by_student_subject2($student['student_id'], $subject['subject_id'], $academic_period_id);
            } else {
                $marks = $this->Marks_model->get_marks_by_student_subject($student['student_id'], $subject['subject_id']);
            }
            
            // Create an associative array to store all marks
            $all_marks = array(
                'E1' => '', 'R1' => '', 'E2' => '', 'R2' => '', 'E3' => '', 'R3' => '', 'E4' => '', 'R4' => '',
                'E5' => '', 'R5' => '', 'E6' => '', 'R6' => '', 'E7' => '', 'R7' => '',
                'JIIS1' => '', 'JIIS1-R' => '', 'JIIS2' => '', 'JIIS2-R' => '',
                'COL-DIC' => '', 'COL-FEB' => '', 'CAL-DEF' => '', 'EXAM-PREV' => '', 'EXAM-PREV-DATE' => ''
            );
            $mark_ids = array();
            $examPrevMarkId = ''; 

            // Process all marks
            foreach ($marks as $mark) {
                $exam_type_id = $mark['exam_type_id'];
                $exam_type_info = $this->Exams_model->get_exam_type_info($exam_type_id);
                
                if (!empty($exam_type_info) && isset($exam_type_info[0]['short_name'])) {
                    $short_name = $exam_type_info[0]['short_name'];
                    if (array_key_exists($short_name, $all_marks)) {

                        if ($short_name === 'CAL-DEF') {
                            $all_marks[$short_name] = $mark['mark_obtained'];
                        } else {
                            $mark_obtained = floatval($mark['mark_obtained']);
                            $all_marks[$short_name] = $mark_obtained != 0 ? intval($mark_obtained) : '';
                        }

                        $mark_ids[$short_name] = $mark['mark_id'];
                        if ($short_name == 'EXAM-PREV') {
                            $all_marks['EXAM-PREV-DATE'] = $mark['date'];
                            $examPrevMarkId = $mark['mark_id']; 
                        }
                    }
                }
            }

            // Generate inputs for E1-E7 and R1-R7
            for ($i = 1; $i <= 7; $i++) {
                $exam_type = 'E' . $i;
                $recovery_type = 'R' . $i;
                ?>
                <td class="evaluation-cell">
                    <input type="text" 
                        value="<?php echo $all_marks[$exam_type]; ?>" 
                        class="mark-input <?php echo $exam_type; ?> input evaluation-input popover-primary" 
                        data-toggle="popover" data-trigger="hover" data-placement="top"
                        data-mark-id="<?php echo isset($mark_ids[$exam_type]) ? $mark_ids[$exam_type] : ''; ?>" 
                        data-class-id="<?php echo $student['class_id']; ?>" 
                        data-section-id="<?php echo $student['section_id']; ?>" 
                        data-subject-id="<?php echo $subject['subject_id']; ?>" 
                        data-student-id="<?php echo $student['student_id']; ?>"
                        data-exam-type="<?php echo $i; ?>"
                        maxlength="2"
                        <?php echo ($used_subject_history || $used_section_history) ? 'disabled' : ''; ?>/>
                </td>
                <td class="recovery-cell">
                    <input type="text" 
                        value="<?php echo $all_marks[$recovery_type]; ?>" 
                        class="mark-input <?php echo $recovery_type; ?> input recovery-input popover-primary" 
                        disabled
                        data-toggle="popover" data-trigger="hover" data-placement="top"
                        data-mark-id="<?php echo isset($mark_ids[$recovery_type]) ? $mark_ids[$recovery_type] : ''; ?>"
                        data-class-id="<?php echo $student['class_id']; ?>" 
                        data-section-id="<?php echo $student['section_id']; ?>" 
                        data-subject-id="<?php echo $subject['subject_id']; ?>" 
                        data-student-id="<?php echo $student['student_id']; ?>"
                        data-exam-type="<?php echo $i + 7; ?>" 
                        maxlength="2"
                        <?php echo ($used_subject_history || $used_section_history) ? 'disabled' : ''; ?>/>
                </td>
                <?php
            }

            // Generate inputs for special exam types
            $special_exams = array(
                'JIIS1' => 15, 'JIIS1-R' => 17, 'JIIS2' => 16, 'JIIS2-R' => 18,
                'COL-DIC' => 19, 'COL-FEB' => 20, 'CAL-DEF' => 21, 'EXAM-PREV' => 22
            );

            foreach ($special_exams as $exam_type => $exam_type_id) {
                $input_class = str_replace('-', '', strtolower($exam_type));
                ?>
                <td class="evaluation-cell">
                    <input type="text" 
                        value="<?php echo $all_marks[$exam_type]; ?>" 
                        class="mark-input <?php echo $exam_type; ?> input evaluation-input-<?php echo $input_class; ?> popover-primary" 
                        <?php if (in_array($exam_type, ['JIIS1-R', 'JIIS2-R', 'COL-DIC', 'COL-FEB', 'CAL-DEF', 'EXAM-PREV'])) echo 'disabled'; ?>
                        data-toggle="popover" data-trigger="hover" data-placement="top"
                        data-mark-id="<?php echo isset($mark_ids[$exam_type]) ? $mark_ids[$exam_type] : ''; ?>" 
                        data-class-id="<?php echo $student['class_id']; ?>" 
                        data-section-id="<?php echo $student['section_id']; ?>" 
                        data-subject-id="<?php echo $subject['subject_id']; ?>" 
                        data-student-id="<?php echo $student['student_id']; ?>"
                        data-exam-type="<?php echo $exam_type_id; ?>" 
                        maxlength="2"
                        <?php echo ($used_subject_history || $used_section_history) ? 'disabled' : ''; ?>/>
                </td>
                <?php
            }
            ?>
            <td class="date-cell">
                <input type="date" 
                    value="<?php echo $all_marks['EXAM-PREV-DATE']; ?>" 
                    class="date-mark-input mark-input D-EXAM-PREV input date-evaluation-input-exam-prev" 
                    disabled
                    data-mark-id="<?php echo isset($mark_ids['EXAM-PREV']) ? $mark_ids['EXAM-PREV'] : ''; ?>" 
                    data-class-id="<?php echo $student['class_id']; ?>" 
                    data-section-id="<?php echo $student['section_id']; ?>" 
                    data-subject-id="<?php echo $subject['subject_id']; ?>" 
                    data-student-id="<?php echo $student['student_id']; ?>"
                    data-exam-prev-date="<?php echo $all_marks['EXAM-PREV-DATE']; ?>" 
                    data-exam-prev-mark-id="<?php echo $examPrevMarkId; ?>"
                    <?php echo ($used_subject_history || $used_section_history) ? 'disabled' : ''; ?>/>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                                            
                                            </table>

                                            <?php if ($used_subject_history || $used_section_history): ?>
                                            
                                            <?php else: ?>
                                                <button class="btn btn-table calculateButton" style="background-color: #fff !important; color: #265044; font-weight: bold;" id="calculateButton"  data-subject-id="<?php echo $subject['subject_id']; ?>" >Calcular promedio</button>
                                            <?php endif; ?> 


                                        </center>
                                    </div>
                                </div>
                            </div>
                            <?php
                endforeach;
            }
            ?>
                    </div>
                </div>
        </div>
    </div>
</div>


<script type="text/javascript">
        function get_sections(academic_period_id) {
            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_sections_content_by_academic_period/' + academic_period_id + '/view_students_mark',
                success: function(response) {
                    const emptyOption = '<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>';
                    jQuery('#class_select').html(emptyOption + response);
                }
            });

        }
   
</script>

<script>
  $(document).ready(function() {
    let ajaxSent = false;

    function sendPageTracking() {
      if (!ajaxSent) {
        ajaxSent = true;

        let page_name = '<?php echo $page_name;?>'
        let user_id = '<?php echo $this->session->userdata('login_user_id');?>'
        let user_group = '<?php echo $this->session->userdata('login_type');?>'
         let page_id = '<?php echo $section_id;?>'

        $.ajax({
          url: 'index.php?admin/reset_page_tracking/' + page_name + '/' + page_id, 
          success: function(response) {
          },
          error: function(xhr, status, error) {
          }
        });
      }
    }

    // Captura el evento `beforeunload`
    $(window).on('beforeunload', function() {
      sendPageTracking();
    });
  });
</script>

<script type="text/javascript">
    function reload_ajax() {
        location.reload(); 
    }
</script>

<script>
  $(document).ready(function() {
    $('.input').on('keydown', function(e) {
        const inputs = $('.input');
        const index = inputs.index(this);

        function focusNextEnabledInput(startIndex, step) {
            let newIndex = startIndex + step;
            while (newIndex >= 0 && newIndex < inputs.length) {
                const newInput = inputs.eq(newIndex);
                if (!newInput.prop('disabled')) {
                    newInput.focus();
                    break;
                }
                newIndex += step;
            }
        }

        if (e.which === 37) { // left arrow key
            if (index > 0) {
                focusNextEnabledInput(index, -1);
            }
        } else if (e.which === 39) { // right arrow key
            if (index < inputs.length - 1) {
                focusNextEnabledInput(index, 1);
            }
        } else if (e.which === 38) { // up arrow key
            const cols = $(this).closest('tr').find('.input').length;
            if (index - cols >= 0) {
                focusNextEnabledInput(index, -cols);
            }
        } else if (e.which === 40) { // down arrow key
            const cols = $(this).closest('tr').find('.input').length;
            if (index + cols < inputs.length) {
                focusNextEnabledInput(index, cols);
            }
        }
    });

    function calculateAverageAndUpdate(studentRow) {
        console.log("Procesando fila...");
        
        const evaluationInputs = studentRow.find('.evaluation-input, .evaluation-input-jiis1, .evaluation-input-jiis2');
        const recoveryInputs = studentRow.find('.recovery-input, .JIIS1-R, .JIIS2-R');
        const colDicInput = studentRow.find('.COL-DIC');
        const colFebInput = studentRow.find('.COL-FEB');
        const examPrevInput = studentRow.find('.evaluation-input-examprev');
        const calDefInput = studentRow.find('.CAL-DEF');
        
        console.log("Inputs encontrados:");
        console.log("Evaluación:", evaluationInputs.map((i, el) => $(el).val()).get());
        console.log("Recuperación:", recoveryInputs.map((i, el) => $(el).val()).get());
        console.log("COL-DIC:", colDicInput.val());
        console.log("COL-FEB:", colFebInput.val());
        console.log("EXAM-PREV:", examPrevInput.val());
        console.log("CAL-DEF actual:", calDefInput.val());

        let approvedGrades = [];
        let finalGrade = null;
        let gradeSource = ""; // Origen del valor de `finalGrade`

        // Validar evaluaciones y recuperaciones
        evaluationInputs.each(function(index) {
            const evalInput = $(this);
            const recoveryInput = recoveryInputs.eq(index);
            console.log(`Procesando evaluación y recuperación en índice ${index}`);
            
            const evalValue = evalInput.val() === "" ? NaN : parseFloat(evalInput.val()); // Valores vacíos como NaN
            const recoveryValue = recoveryInput.val() === "" ? NaN : parseFloat(recoveryInput.val());
            
            if (!isNaN(evalValue)) {
                if (evalValue >= 7) {
                    approvedGrades.push(evalValue);
                    console.log(`Evaluación aprobada (>=7): ${evalValue}`);
                } else if (!isNaN(recoveryValue) && recoveryValue >= 7) {
                    approvedGrades.push(recoveryValue);
                    console.log(`Evaluación reprobada (${evalValue}), pero recuperación aprobada: ${recoveryValue}`);
                } else {
                    console.log(`Evaluación (${evalValue}) y recuperación (${recoveryValue}) no aprobadas.`);
                }
            } else {
                console.log(`Evaluación vacía, se considera no aprobada.`);
            }
        });

        console.log("Calificaciones aprobadas recopiladas:", approvedGrades);

        // Priorizar valores de COL-DIC, COL-FEB y EXAM-PREV si es necesario
        const colDicValue = parseFloat(colDicInput.val()) || NaN;
        const colFebValue = parseFloat(colFebInput.val()) || NaN;
        const examPrevValue = parseFloat(examPrevInput.val()) || NaN;

        // Nueva condición para determinar si se aprobaron todas las evaluaciones
        if (approvedGrades.length === evaluationInputs.filter((i, el) => $(el).val() !== "").length && approvedGrades.length > 0) {
            // Calcular el promedio solo de evaluaciones aprobadas
            finalGrade = approvedGrades.reduce((sum, grade) => sum + grade, 0) / approvedGrades.length;
            gradeSource = "Evaluaciones";
        } else if (!isNaN(colDicValue) && colDicValue >= 7) {
            finalGrade = colDicValue;
            gradeSource = "COL-DIC";
        } else if (!isNaN(colFebValue) && colFebValue >= 7) {
            finalGrade = colFebValue;
            gradeSource = "COL-FEB";
        } else if (!isNaN(examPrevValue) && examPrevValue >= 7) {
            finalGrade = examPrevValue;
            gradeSource = "EXAM-PREV";
        } else {
            finalGrade = null; // Asignar null si no hay notas o ninguna cumple los requisitos
            gradeSource = "Sin calificación definitiva";
        }

        // Mostrar el resultado
        console.log(`Promedio calculado de evaluaciones aprobadas: ${finalGrade}`);
        console.log(`Calificación definitiva asignada (${gradeSource}): ${finalGrade}`);

        // Asignar valor final a CAL-DEF
        calDefInput.val(finalGrade !== null ? finalGrade.toFixed(2) : "");
    }








    function validateInputsAndToggleFields(studentRow) {
        const evaluationInputs = studentRow.find('.evaluation-input, .evaluation-input-jiis1, evaluation-input-jiis2');
        const recoveryInputs = studentRow.find('.recovery-input, .JIIS1-R, .JIIS2-R');
        const colDicInput = studentRow.find('.evaluation-input-coldic');
        const colFebInput = studentRow.find('.evaluation-input-colfeb');
        const examPrevInput = studentRow.find('.evaluation-input-examprev');
        const examPrevDateInput = studentRow.find('.date-evaluation-input-exam-prev');

        function validateInput(input) {
            const value = parseInt(input.val(), 10);
            if (!isNaN(value) && (value < 1 || value > 10)) {
                input.css('border-color', 'red');
                input.attr({
                    'data-toggle': 'popover',
                    'data-trigger': 'hover',
                    'data-placement': 'top',
                    'data-content': value < 1 ? 'El valor mínimo de la calificación es 1' : 'El valor máximo de la calificación es 10'
                });
            } else {
                input.css('border-color', '');
                input.attr('data-content', '');
            }
        }

        let hasFailedRecovery = false;

        evaluationInputs.each(function(index) {
            const evalInput = $(this);
            const evalValue = parseInt(evalInput.val(), 10);
            const recoveryInput = recoveryInputs.eq(index);

            validateInput(evalInput);
            validateInput(recoveryInput);

            if (!isNaN(evalValue) && evalValue < 7) {
                <?php if ($used_subject_history || $used_section_history): ?>
                    recoveryInput.prop('disabled', true);
                <?php else: ?>
                    recoveryInput.prop('disabled', false);
                    <?php endif; ?>
                const recoveryValue = parseInt(recoveryInput.val(), 10);
                if (!isNaN(recoveryValue) && recoveryValue < 7) {
                    hasFailedRecovery = true;
                }
            } else {
                recoveryInput.prop('disabled', true);
                recoveryInput.val('');
            }
        });

        <?php if ($used_subject_history || $used_section_history): ?>

            <?php else: ?>
                colDicInput.prop('disabled', !hasFailedRecovery).val(!hasFailedRecovery ? '' : colDicInput.val());
                colFebInput.prop('disabled', !hasFailedRecovery).val(!hasFailedRecovery ? '' : colFebInput.val());
                examPrevInput.prop('disabled', !hasFailedRecovery).val(!hasFailedRecovery ? '' : examPrevInput.val());
                examPrevDateInput.prop('disabled', !hasFailedRecovery).val(!hasFailedRecovery ? '' : examPrevDateInput.val());
                    <?php endif; ?> 
      

        const colDicValue = parseInt(colDicInput.val(), 10);

        // Primero verificamos si el valor es menor a 1 o está vacío/NULL
        if (isNaN(colDicValue) || colDicValue < 1) {
            colFebInput.prop('disabled', true); // Deshabilita si está vacío o menor a 1
        } else if (colDicValue < 7) {
            <?php if ($used_subject_history || $used_section_history): ?>
                colFebInput.prop('disabled', true); // Deshabilita si cualquiera de las condiciones de historia es true
            <?php else: ?>
                colFebInput.prop('disabled', false); 
                <?php endif; ?> // Habilita si es menor a 7 pero mayor o igual a 1
        } else {
            colFebInput.prop('disabled', true); // Deshabilita si es mayor o igual a 7
        }

        const colFebValue = parseInt(colFebInput.val(), 10);

        // Primero verificamos si el valor es menor a 1 o está vacío/NULL
        if (isNaN(colFebValue) || colFebValue < 1) {
            examPrevInput.prop('disabled', true); // Deshabilita si está vacío o menor a 1
            examPrevDateInput.prop('disabled', true); // Deshabilita también el input de fecha
        } else if (colFebValue < 7) {
            <?php if ($used_subject_history || $used_section_history): ?>
                examPrevInput.prop('disabled', true); 
                examPrevDateInput.prop('disabled', true); // Deshabilita si cualquiera de las condiciones de historia es true
            <?php else: ?>
                examPrevInput.prop('disabled', false); 
                examPrevDateInput.prop('disabled', false); 
            <?php endif; ?>
        } else {
            examPrevInput.prop('disabled', true); // Deshabilita si es mayor o igual a 7
            examPrevDateInput.prop('disabled', true); // Deshabilita el input de fecha
        }

        function sendAjaxOnChange(input) {
            const classId = input.data('class-id');
            const sectionId = input.data('section-id');
            const subjectId = input.data('subject-id');
            const studentId = input.data('student-id');
            const examType = input.data('exam-type');
            let date = input.data('exam-prev-date'); 
            const markId = input.data('mark-id') || ''; // Si mark_id no existe, asigna un valor vacío
            let markObtained = input.val().trim() || 'NULL'; // Si el valor está vacío, asignar 'NULL'
            
            const operation = markId ? 'update' : 'create'; // Determina si la operación es 'update' o 'create'

            if (examType === 22) {
                const prevDateInput = $(`input[data-exam-prev-mark-id="${markId}"]`);
                if (prevDateInput.length > 0) {
                    date = prevDateInput.val(); 
                } else {
                    date = null;  
                }
            }

            $.ajax({
                url: `index.php?admin/marks/${operation}/${classId}/${sectionId}/${subjectId}/${studentId}/${examType}/${markObtained}/${date}/${markId}`,
                success: function(response) {
                    console.log('Operación realizada exitosamente.');
                },
                error: function(xhr, status, error) {
                    console.error('Error al realizar la operación:', error);
                }
            });
        }

        studentRow.find('.evaluation-input, .recovery-input, .evaluation-input-jiis1, .evaluation-input-jiis1r, .evaluation-input-jiis2, .evaluation-input-jiis2r, .evaluation-input-coldic, .evaluation-input-colfeb, .evaluation-input-examprev, .date-evaluation-input-exam-prev').on('change', function() {
            sendAjaxOnChange($(this));

        });


    }


    $('.mark-input').on('input', function() {
        const studentRow = $(this).closest('tr');
        validateInputsAndToggleFields(studentRow);
    });

    $('.calculateButton').off('click').on('click', function() {
        const subjectId = $(this).data('subject-id');

        $(`table tbody tr`).each(function() {
            calculateAverageAndUpdate($(this));
        });
        
        sendAjaxRequest(subjectId);
    });

    // Initialize calculations for all rows
    $('tbody tr').each(function() {
        validateInputsAndToggleFields($(this));
    });

    function sendAjaxRequest(subjectId) {
        // Arreglo para almacenar todas las solicitudes AJAX
        let requests = [];

        showLoading();

        // Selecciona todos los inputs que tengan el data-subject-id igual al btnSubjectId y que no tengan data-exam-prev-date
        $(`input[data-subject-id="${subjectId}"]:not([data-exam-prev-date])`).each(function() {
            const inputElement = $(this);

            const class_id = inputElement.data('class-id');
            const section_id = inputElement.data('section-id');
            const subject_id = inputElement.data('subject-id');
            const student_id = inputElement.data('student-id');
            const exam_type = inputElement.data('exam-type');
            let date = inputElement.data('exam-prev-date'); 
            const mark_id = inputElement.data('mark-id') || ''; // Si mark_id no existe, asigna un valor vacío

            let mark_obtained = inputElement.val().trim();
            mark_obtained = mark_obtained === '' ? 'NULL' : mark_obtained; // Si el valor está vacío, asigna 'NULL'

            const operation = mark_id ? 'update' : 'create'; // Determina si la operación es 'update' o 'create'

            // Si exam_type es 22, realizamos una acción especial
            if (exam_type === 22) {
                // Busca el input con el data-exam-prev-mark-id correspondiente
                const prevDateInput = $(`input[data-exam-prev-mark-id="${mark_id}"]`);
                
                // Verifica si se encontró el input correspondiente
                if (prevDateInput.length > 0) {
                    date = prevDateInput.val(); // Asigna el valor de date
                } else {
                    console.error(`No se encontró un input con data-exam-prev-mark-id="${mark_id}".`); // Mensaje de error
                    date = null;  // Asigna null si no se encuentra el input
                }
            }

            // Guarda cada solicitud AJAX en el arreglo requests
            const request = $.ajax({
                url: `index.php?admin/marks/${operation}/${class_id}/${section_id}/${subject_id}/${student_id}/${exam_type}/${mark_obtained}/${date}/${mark_id}`,
                success: function(response) {
                    console.log('Operación realizada exitosamente.');
                },
                error: function(xhr, status, error) {
                    console.error('Error al realizar la operación:', error);
                }
            });

            requests.push(request); // Agrega la solicitud al arreglo
        });

        $.when.apply($, requests).done(function() {
            Swal.close();
            location.reload(); 
        });
    }

    function showLoading() {
    Swal.fire({
        imageUrl: "assets/images/loading-ezgif.com-gif-maker.gif",
        imageWidth: 100,
        imageHeight: 100,
        imageAlt: "Cargando...",
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        background: 'transparent', // Fondo transparente
        customClass: {
            popup: 'transparent-popup'
        }
    });
}


   
});

</script>

<style>
    input:disabled {
        background-color: #DFDFDF !important;
        color: black !important;
        font-weight: 300 !important;
    }

    .btn-table {
        border-radius: 50% !important;
        border: 0PX solid #A5B299 !important;
        padding: 6px 8px;
        background-color: #fff !important;
    }

      .mark-input {
        width: 100%; 
        height: 100%;
        max-width: 40px;
        text-align: center; 
        box-sizing: border-box; 
        border: none; 
        padding-right: 0 !important;
        padding-left: 0 !important;
        margin: 0 !important; 
    } .date-mark-input {
        width: 100%; 
        max-width: 80px;
        text-align: center; 
        box-sizing: border-box; 
        border: none;
        margin: 0 !important; 
    } .date-cell {
        padding-right: 0 !important;
        padding-left: 0 !important;
    } .evaluation-cell {
        /* padding: 0 !important; */
        padding-right: 0 !important;
        padding-left: 0 !important;
    } .recovery-cell {
        padding-right: 0 !important;
        padding-left: 0 !important;
    }

    .evaluation-cell input,
    .recovery-cell input, .date-cell input {
        border: 1px solid #fff;
        padding: 5px;
    }
  
    .form-control {
        background-color: #ebebeb !important;
    }
    
    a[data-toggle="tab"] i {
        color: black !important;
    }

    .active a[data-toggle="tab"] i {
        color: #265044 !important;
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
        background-color: #fff !important;
    }

    .menuIcon {
        color: black;
    }
    .btn-group {
        text-align: center !important;
        align-items: center !important;
    }

    .nav-tabs.bordered + .tab-content {
        border: 5px solid white !important;
        border-top: 0;
        -webkit-border-radius: 0 0 3px 3px;
        -webkit-background-clip: padding-box;
        -moz-border-radius: 0 0 3px 3px;
        -moz-background-clip: padding;
        border-radius: 0 0 3px 3px;
        background-clip: padding-box;
        padding: 10px 15px;
        margin-bottom: 20px;
    }

    .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
        border: 5px solid white !important;
        border-bottom-color: transparent !important;
    }

    .nav-tabs .active a {
        color: #265044 !important;
        font-weight: bolder !important;
    }

    .nav-tabs li a {
        color: black !important;
        font-weight: bold !important;
    }

    .dataTables_wrapper {
        color: #484848 !important;
    }

    .dataTable thead tr th {
        color: #265044 !important;
        font-weight: bold !important;
    }

    .table-bordered > thead > tr > th, .table-bordered > thead > tr > td {
        color: #265044 !important;
        font-weight: bold !important;
    }

    .table-bordered {
        border-radius: 10px !important;
    }

    .padded label {
        color: #265044 !important;
        font-weight: bold !important;
    }
    .even {
        background-color: white !important;
    }

    .btn-info {
        font-weight: bold !important;
    }

    .btn-group ul li a {
        background-color: #265044 !important;
        color: white !important;
        border-radius: 0px !important;
        border-bottom: 2px solid rgba(69, 74, 84, 0.4);
    }

    /* Estilo para cambiar el color de fondo en hover */
    .btn-group ul li a:hover {
        background-color: #A5B299 !important;
        border-radius: 0px !important;
    }

    .box-content {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        background-color: white !important;
    }

    .row th {
        background-color: #B0DFCC !important;
    } .row th div {
        color: white !important;
        font-weight: 600 !important;
    }

    .dataTables_wrapper table thead tr th.sorting_asc:before,
    .dataTables_wrapper table thead tr th.sorting_desc:before {
    color: white !important;
    }

    .table tbody tr td {
        background-color: #fff !important;
    }  .table tbody tr:hover td {
        background-color: #f2f2f4 !important;
    }

    .nav-tabs li a:hover {
        background-color: #A5B299 !important;
    }  .nav-tabs li.active a:hover {
        background-color: #fff !important;
    }

    .tile-stats .icon {
        margin-bottom: 10px !important;
    }
    .tile-stats .icon i {
        font-size: 110px !important;
     
        margin-right: 0px !important;
        padding: 0px 90px 0px 10px;
    }

    .tile-stats {
        padding: 40px 0px 40px 0px!important;
    }

    .tile-stats .num, .sub-num {
        background-color: #A5B299;
    }
    .num {
        padding-left: 20px !important;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        z-index: 0 !important;
    }
    .sub-num {
        margin-top: -1px !important;
        padding-left: 20px !important;
        padding-bottom: 10px !important;
        border-bottom-right-radius: 5px;
        border-bottom-left-radius: 5px;
        z-index: 0 !important;
    }
</style>
