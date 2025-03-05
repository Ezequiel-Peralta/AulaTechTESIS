<?php
$student_info = $this->Student_model->get_student_info($param2);
foreach ($student_info as $row1):
    ?>
    <center>
        <div style="font-size: 20px;font-weight: 200;margin: 10px;"><?php echo $row1['name']; ?></div>
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
                        <th colspan="1" class="evaluation-cell text-center">Coloquio Diciembre</th>
                        <th colspan="1" class="evaluation-cell text-center">Coloquio Febrero</th>
                        <th colspan="1" class="evaluation-cell text-center">Calificacion Definitiva</th>
                        <th colspan="2" class="evaluation-cell text-center">Examen</th>
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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $subjects = $this->Subject_model->get_subjects_by_section($row1['section_id']);
                    foreach ($subjects as $row2):
                        ?>
                        <tr class="text-center">
                            <td class="subject-element-cell"><?php echo $row2['name']; ?></td>
                            <?php
                            // Obtener las calificaciones para esta materia
                            $marks = $this->Mark_model->get_marks_by_student_subject($row1['student_id'], $row2['subject_id']);
                            
                            // Crear un array asociativo para mapear cada tipo de evaluación y recuperación con su mark_obtained
                            $mark_array = array();
                            foreach ($marks as $row3) {
                                $exam_type = $row3['exam_type'];
                                $mark_obtained = $row3['mark_obtained'];
                                $mark_array[$exam_type] = $mark_obtained;
                            }

                            // Iterar sobre cada tipo de evaluación y recuperación posible
                            for ($i = 1; $i <= 7; $i++) {
                                $exam_type = 'E' . $i;
                                $recovery_type = 'R' . $i;
                                ?>
                                <td class="evaluation-cell">
                                    <input type="text" value="<?php echo isset($mark_array[$exam_type]) ? $mark_array[$exam_type] : ''; ?>" class="mark-input" maxlength="2"/>
                                </td>
                                <td class="recovery-cell">
                                    <input type="text" value="<?php echo isset($mark_array[$recovery_type]) ? $mark_array[$recovery_type] : ''; ?>" class="mark-input" maxlength="2"/>
                                </td>
                                <?php
                            }
                            ?>
                            <td class="evaluation-cell">
                                <input type="text" value="<?php echo isset($mark_array['JIIS1']) ? $mark_array['JIIS1'] : ''; ?>" class="mark-input" maxlength="2"/>
                            </td>
                            <td class="recovery-cell">
                                <input type="text" value="<?php echo isset($mark_array['JIIS1-R']) ? $mark_array['JIIS1-R'] : ''; ?>" class="mark-input" maxlength="2"/>
                            </td>
                            <td class="evaluation-cell">
                                <input type="text" value="<?php echo isset($mark_array['JIIS2']) ? $mark_array['JIIS2'] : ''; ?>" class="mark-input" maxlength="2"/>
                            </td>
                            <td class="recovery-cell">
                                <input type="text" value="<?php echo isset($mark_array['JIIS2-R']) ? $mark_array['JIIS2-R'] : ''; ?>" class="mark-input" maxlength="2"/>
                            </td>
                            <td class="evaluation-cell">
                                <input type="text" value="<?php echo isset($mark_array['Diciembre']) ? $mark_array['Diciembre'] : ''; ?>" class="mark-input" maxlength="2"/>
                            </td>
                            <td class="evaluation-cell">
                                <input type="text" value="<?php echo isset($mark_array['Febrero']) ? $mark_array['Febrero'] : ''; ?>" class="mark-input" maxlength="2"/>
                            </td>
                            <td class="evaluation-cell">
                                <input type="text" value="<?php echo isset($mark_array['Definitiva']) ? $mark_array['Definitiva'] : ''; ?>" class="mark-input" maxlength="2"/>
                            </td>
                            <td class="evaluation-cell">
                                <input type="text" value="<?php echo isset($mark_array['Examen']) ? $mark_array['Examen'] : ''; ?>" class="mark-input" maxlength="2"/>
                            </td>
                            <td class="date-evaluation-cell">
                                <input type="text" value="<?php echo isset($mark_array['Examen']) ? $mark_array['Examen'] : ''; ?>" class="mark-input" maxlength="2"/>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </center>
        </div>
    </center>
<?php endforeach; ?>

<style>
    @media screen and (min-width: 768px) {
        .modal-dialog {
            width: 1400px !important;
            padding-top: 30px;
            padding-bottom: 30px;
        }
    }

    .mark-input {
        width: 100%; 
        max-width: 30px;
        text-align: center; 
        box-sizing: border-box; 
        border: none; 
    }

    .evaluation-cell input,
    .recovery-cell input {
        border: 1px solid #000;
        padding: 5px;
    }
</style>

<script>
    $(function() {
        $('.mark-input').keyup(function(e) {
            if (e.keyCode == 37 || e.keyCode == 38) // Flecha izquierda o arriba
                mover(e, -1);
            if (e.keyCode == 39 || e.keyCode == 40) // Flecha derecha o abajo
                mover(e, 1);
        });
    });

    function mover(event, to) {
        let list = $('.mark-input');
        let index = list.index($(event.target));
        index = (index + to) % list.length;
        list.eq(index).focus();
    }
</script>