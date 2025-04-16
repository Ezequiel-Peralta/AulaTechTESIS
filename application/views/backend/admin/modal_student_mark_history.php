<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="entypo-vcard"></i>&nbsp;&nbsp;<?php echo 'Ver historial académico'?></h4>
</div>

<div class="modal-body" style="height:500px; overflow:auto;">
    <div class="row">
    <?php
$student_info = $this->Students_model->get_student_info($param2);
foreach ($student_info as $row1):
    ?>
    <center>
        <div style="font-size: 20px; font-weight: 200; margin: 10px;"><?php echo $row1['lastname']; ?>, <?php echo $row1['firstname']; ?></div>

        <div class="panel-group joined" id="accordion-test-1">
            <?php
            $sections = $this->crud_model->get_section_info2($row1['section_id']);
            foreach ($sections as $row4):
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion-test-1" href="#collapse-section<?php echo $row4['section_id']; ?>">
                                <i class="entypo-graduation-cap"></i>  <?php echo $row4['name']; ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-section<?php echo $row4['section_id']; ?>" class="panel-collapse collapse">
                        <div class="panel-body">
                            <center>
                                <table class="table table-bordered table-hover table-striped" style="border: 2px solid black !important;">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="subject-cell text-center">Materia</th>
                                            <?php for ($i = 1; $i <= 7; $i++): ?>
                                                <th colspan="2" class="evaluation-cell text-center">Evaluacion <?php echo $i; ?></th>
                                            <?php endfor; ?>
                                            <th colspan="2" class="evaluation-cell text-center">JIIS 1°</th>
                                            <th colspan="2" class="evaluation-cell text-center">JIIS 2°</th>
                                            <th colspan="1" class="evaluation-cell text-center">Colq. Dic</th>
                                            <th colspan="1" class="evaluation-cell text-center">Colq. Feb</th>
                                            <th colspan="1" class="evaluation-cell text-center">Calf. Def</th>
                                            <th colspan="2" class="evaluation-cell text-center">Exam. Previo</th>
                                        </tr>
                                        <tr>
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
                                        $subjects = $this->Subjects_model->get_subjects_by_section($row1['section_id']);
                                        foreach ($subjects as $row2):
                                            ?>
                                            <tr>
                                                <td class="text-center subject-element-cell"><?php echo $row2['name']; ?></td>
                                                <?php
                                                // Obtener las calificaciones para esta materia
                                                $marks = $this->Marks_model->get_marks_by_student_subject($row1['student_id'], $row2['subject_id']); 

                                                  // Create an associative array to store all marks
                                                $all_marks = array(
                                                    'E1' => '', 'R1' => '', 'E2' => '', 'R2' => '', 'E3' => '', 'R3' => '', 'E4' => '', 'R4' => '',
                                                    'E5' => '', 'R5' => '', 'E6' => '', 'R6' => '', 'E7' => '', 'R7' => '',
                                                    'JIIS1' => '', 'JIIS1-R' => '', 'JIIS2' => '', 'JIIS2-R' => '',
                                                    'COL-DIC' => '', 'COL-FEB' => '', 'CAL-DEF' => '', 'EXAM-PREV' => '', 'EXAM-PREV-DATE' => ''
                                                );
                                                $mark_ids = array();
                                                
                                                // Crear un array asociativo para mapear cada tipo de evaluación y recuperación con su mark_obtained
                                                $mark_array = array();
                                                foreach ($marks as $mark) {
                                                    $exam_type_id = $mark['exam_type_id'];
                                                    $exam_type_info = $this->Exams_model->get_exam_type_info($exam_type_id);
                                                    
                                                    if (!empty($exam_type_info) && isset($exam_type_info[0]['short_name'])) {
                                                        $short_name = $exam_type_info[0]['short_name'];
                                                        if (array_key_exists($short_name, $all_marks)) {
                                                            $all_marks[$short_name] = $mark['mark_obtained'];
                                                            $mark_ids[$short_name] = $mark['mark_id'];
                                                            if ($short_name == 'EXAM-PREV') {
                                                                $all_marks['EXAM-PREV-DATE'] = $mark['date'];
                                                                $examPrevMarkId = $mark['mark_id']; 
                                                            }
                                                        }
                                                    }
                                                }

                                                // Iterar sobre cada tipo de evaluación y recuperación posible
                                                for ($i = 1; $i <= 7; $i++) {
                                                    $exam_type = 'E' . $i;
                                                    $recovery_type = 'R' . $i;
                                                    ?>
                                                    <td class="text-center evaluation-cell">
                                                        <?php echo isset($all_marks[$exam_type]) ? $all_marks[$exam_type] : ''; ?>
                                                    </td>
                                                    <td class="text-center recovery-cell">
                                                        <?php echo isset($all_marks[$recovery_type]) ? $all_marks[$recovery_type] : ''; ?>
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
                                                    <td class="evaluation-cell text-center">
                                                            <?php echo $all_marks[$exam_type]; ?>
                                                    </td>
                                                    <?php
                                                }
                                                ?>

<td class="date-cell">
    <?php 
    $examDate = $all_marks['EXAM-PREV-DATE'];
    
    // Verifica si la fecha no está vacía ni es "0000-00-00"
    if (!empty($examDate) && $examDate !== '0000-00-00') {
        // Convierte al formato dd/mm/yyyy
        echo date('d/m/Y', strtotime($examDate));
    } else {
        // Si la fecha es vacía o "0000-00-00", no muestra nada
        echo '';
    }
    ?>
</td>


                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </center>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </center>
<?php endforeach; ?>
    </div>
</div>

<div class="modal-footer text-center" style="text-align: center;">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Volver</button>
</div>



<style>
    /* .modal-content {
        width: 1200px !important;
    } */

    @media screen and (min-width: 768px) {
        .modal-dialog {
            width: 1400px !important;
            /* padding-top: 30px;
            padding-bottom: 30px; */
            padding-top: 40px;
            padding-bottom: 0px;
        }
    }

    /* .subject-cell {
        border-right: 1px solid black !important;
        border-bottom: 1px solid black !important;
    }
    .subject-element-cell {
        border-right: 1px solid black !important;
    }
    .evaluation-cell {
        border-left: 1px solid black !important;
        border-bottom: 1px solid black !important;
    }
    .recovery-cell {
        border-right: 1px solid black !important;
        border-bottom: 1px solid black !important;
    } */
</style>
