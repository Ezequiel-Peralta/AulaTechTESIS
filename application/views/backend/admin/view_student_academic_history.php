<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
                <li class="active">
                    <a href="#tab-<?php echo $student_id; ?>" data-toggle="tab">
                        <?php echo $student_data['lastname']; ?>, <?php echo $student_data['firstname']; ?>
                        
                    </a>
                </li>
        </ul>
        
        <div class="tab-content">
                <div class="tab-pane active" id="tab-<?php echo $student_data['student_id']; ?>">
                    <br>
                    <div class="mt-2 mb-4">
                        <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    </div>
                    <br>
                    <div class="panel-group joined" id="accordion-test-1">
                        <?php
                        // Obtener el historial académico del estudiante
                        $sections_history = $this->Students_model->get_academic_history_by_student($student_id);

                        if (empty($sections_history)) {
                            $sections_history = $this->Students_model->get_academic_by_student($student_id);
                        }


                        // Verificar si el historial de secciones no está vacío
                        if (!empty($sections_history)) {
                            // Iterar sobre el historial académico
                            foreach ($sections_history as $history_entry) {
                                // Obtener el ID de la nueva sección
                                $new_section_id = $history_entry['new_section_id'];

                                // Obtener la información de la sección relacionada con el historial académico
                                $sections = $this->crud_model->get_section_info3($new_section_id);

                                // Verificar si se encontró alguna sección
                                if (!empty($sections)) {
                                    foreach ($sections as $section):
                        ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title text-center">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-1" href="#collapse-section<?php echo $section['section_id']; ?>">
                                                        <i class="entypo-graduation-cap"></i>  <?php echo $section['name']; ?> &nbsp;
                                                        <?php 
                                                            if ($student_data['section_id'] == $section['section_id']) {
                                                                echo ' <span class="badge badge-success">' . ucfirst(get_phrase('actual_class')) . '</span>';
                                                            }
                                                        ?>
                                                    </a>
                                                </h4>

                                            </div>
                                            <div class="button-container">
                                                <button id="reportCard" 
                                                    onclick="window.open('<?php echo base_url('index.php?admin/printReportCardES/'.$student_id . '/' . $section['section_id']); ?>', '_blank');" 
                                                    class="btn buttons-html5 btn-white btn-sm btn-danger-hover" 
                                                    title="<?php echo ucfirst(get_phrase('report_card')); ?>">
                                                    <i class="fa fa-file-pdf-o"></i> <?php echo ucfirst(get_phrase('report_card')); ?>
                                                </button>
                                            </div>
                                            <div id="collapse-section<?php echo $section['section_id']; ?>" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <center>
                                                   
                                            <table class="table table-bordered datatable table-hover table-striped" style="border-radius: 10px !important;" id="dataTable_<?php echo $section['section_id']; ?>">
                                                <thead>
                                                    <tr>
                                                        <th class="subject-cell text-center">Asignaturas</th>
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
     $subjects = $this->Subjects_model->get_subjects_by_section2($section['section_id']);
     foreach ($subjects as $subject):
    ?>
        <tr class="text-center" id="<?php echo $subject['subject_id']; ?>">
            <td class="student-element-cell"><?php echo $subject['name']; ?></td>
            <?php
            $marks = $this->Marks_model->get_marks_by_student_subject2($student_data['student_id'], $subject['subject_id'], $section['academic_period_id']);
            
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
                
                if (!empty($exam_type_info) && isset($exam_type_info['short_name'])) {
                    $short_name = $exam_type_info['short_name'];
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
                        disabled
                        data-toggle="popover" data-trigger="hover" data-placement="top"
                        data-mark-id="<?php echo isset($mark_ids[$exam_type]) ? $mark_ids[$exam_type] : ''; ?>" 
                        data-class-id="<?php echo $section['class_id']; ?>" 
                        data-section-id="<?php echo $section['section_id']; ?>" 
                        data-subject-id="<?php echo $subject['subject_id']; ?>" 
                        data-student-id="<?php echo $student_data['student_id']; ?>"
                        data-exam-type="<?php echo $i; ?>"
                        maxlength="2"/>
                </td>
                <td class="recovery-cell">
                    <input type="text" 
                        value="<?php echo $all_marks[$recovery_type]; ?>" 
                        class="mark-input <?php echo $recovery_type; ?> input recovery-input popover-primary" 
                        disabled
                        data-toggle="popover" data-trigger="hover" data-placement="top"
                        data-mark-id="<?php echo isset($mark_ids[$recovery_type]) ? $mark_ids[$recovery_type] : ''; ?>"
                        data-class-id="<?php echo $section['class_id']; ?>" 
                        data-section-id="<?php echo $section['section_id']; ?>" 
                        data-subject-id="<?php echo $subject['subject_id']; ?>" 
                        data-student-id="<?php echo $student_data['student_id']; ?>"
                        data-exam-type="<?php echo $i + 7; ?>" 
                        maxlength="2"/>
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
                        <?php if (in_array($exam_type, ['JIIS1', 'JIIS1-R','JIIS2', 'JIIS2-R', 'COL-DIC', 'COL-FEB', 'CAL-DEF', 'EXAM-PREV'])) echo 'disabled'; ?>
                        data-toggle="popover" data-trigger="hover" data-placement="top"
                        data-mark-id="<?php echo isset($mark_ids[$exam_type]) ? $mark_ids[$exam_type] : ''; ?>" 
                        data-class-id="<?php echo $section['class_id']; ?>" 
                        data-section-id="<?php echo $section['section_id']; ?>" 
                        data-subject-id="<?php echo $subject['subject_id']; ?>" 
                        data-student-id="<?php echo $student_data['student_id']; ?>"
                        data-exam-type="<?php echo $exam_type_id; ?>" 
                        maxlength="2"/>
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
                    data-class-id="<?php echo $section['class_id']; ?>" 
                    data-section-id="<?php echo $section['section_id']; ?>" 
                    data-subject-id="<?php echo $subject['subject_id']; ?>" 
                    data-student-id="<?php echo $student_data['student_id']; ?>"
                    data-exam-prev-date="<?php echo $all_marks['EXAM-PREV-DATE']; ?>" 
                    data-exam-prev-mark-id="<?php echo $examPrevMarkId; ?>"
                    />
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                                            
                                            </table>

                                            <button class="btn btn-table calculateButton" style="background-color: #fff !important; color: #265044; font-weight: bold;" id="calculateButton"  data-section-id="<?php echo $section['section_id']; ?>" >Calcular promedio</button>

                                            </center>
                            </div>
                        </div>
                    </div>
                    <?php
                                endforeach; // End of foreach ($sections as $section)
                            }
                        } // End of foreach ($sections_history as $history_entry)
                    } // End of if (!empty($sections_history))
                    ?>
            
                                                    </center>

                                                </div>
                                            </div>
                                        </div>
                    
                    </div>
                </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.icheck-2').iCheck({
            checkboxClass: 'icheckbox_square-green'
        });
    });
</script>

<script type="text/javascript">
 
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
    const evaluationInputs = studentRow.find('.evaluation-input, .evaluation-input-jiis1, .evaluation-input-jiis2');
    const recoveryInputs = studentRow.find('.recovery-input, .JIIS1-R, .JIIS2-R');
    const colDicInput = studentRow.find('.COL-DIC');
    const colFebInput = studentRow.find('.COL-FEB');
    const examPrevInput = studentRow.find('.evaluation-input-examprev');
    const calDefInput = studentRow.find('.CAL-DEF');

    let approvedGrades = [];
    let finalGrade = null;
    let gradeSource = ""; // Origen del valor de `finalGrade`

    // Información adicional para AJAX
    const class_id = calDefInput.data('class-id');
    const section_id = calDefInput.data('section-id');
    const subject_id = calDefInput.data('subject-id');
    const student_id = calDefInput.data('student-id');
    const mark_id = calDefInput.data('mark-id');

    function validateAndProcessInput(evalInput, recoveryInput) {
        const evalValue = parseFloat(evalInput.val());
        const recoveryValue = parseFloat(recoveryInput.val());

        if (isNaN(evalValue)) return;

        if (evalValue >= 7) {
            approvedGrades.push(evalValue);
        } else if (!isNaN(recoveryValue) && recoveryValue >= 7) {
            approvedGrades.push(recoveryValue);
        }
    }

    evaluationInputs.each(function(index) {
        const evalInput = $(this);
        const recoveryInput = recoveryInputs.eq(index);
        validateAndProcessInput(evalInput, recoveryInput);
    });

    const colDicValue = parseFloat(colDicInput.val());
    const colFebValue = parseFloat(colFebInput.val());
    const examPrevValue = parseFloat(examPrevInput.val());

    if (!isNaN(colDicValue) && colDicValue >= 7) {
        finalGrade = colDicValue;
        gradeSource = "COL-DIC";
    } else if (!isNaN(colFebValue) && colFebValue >= 7) {
        finalGrade = colFebValue;
        gradeSource = "COL-FEB";
    } else if (!isNaN(examPrevValue) && examPrevValue >= 7) {
        finalGrade = examPrevValue;
        gradeSource = "EXAM-PREV";
    }

    if (finalGrade === null && approvedGrades.length > 0) {
        const average = approvedGrades.reduce((a, b) => a + b, 0) / approvedGrades.length;
        finalGrade = average;
        gradeSource = "Evaluaciones";
    }

    if (finalGrade === null) {
        calDefInput.val('');
    } else {
        calDefInput.val(finalGrade);
    }

    // Llamada AJAX para guardar el `finalGrade`
    if (finalGrade !== null) {
        const operation = mark_id ? 'update' : 'create';
        const url = `index.php?admin/mark_history/${operation}/${class_id}/${section_id}/${subject_id}/${student_id}/21/${finalGrade}/NULL/${mark_id}`;

        console.log('Enviando solicitud DE FINAL GRADE a URL:', url);

        $.ajax({
            url: url,
            success: function(response) {
                console.log('Calificación definitiva guardada exitosamente:', finalGrade);
            },
            error: function(xhr, status, error) {
                console.error('Error al guardar la calificación definitiva:', error);
            }
        });
    }
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
                recoveryInput.prop('disabled', true);
                const recoveryValue = parseInt(recoveryInput.val(), 10);
                if (!isNaN(recoveryValue) && recoveryValue < 7) {
                    hasFailedRecovery = true;
                }
            } else {
                recoveryInput.prop('disabled', true);
            }
        });


        const colDicValue = parseInt(colDicInput.val(), 10);

        // Primero verificamos si el valor es menor a 1 o está vacío/NULL
        if (isNaN(colDicValue) || colDicValue < 1) {
            colFebInput.prop('disabled', true); // Deshabilita si está vacío o menor a 1
        } else if (colDicValue < 7) {
            colFebInput.prop('disabled', true); // Habilita si es menor a 7 pero mayor o igual a 1
        } else {
            colFebInput.prop('disabled', true); // Deshabilita si es mayor o igual a 7
        }

        const colFebValue = parseInt(colFebInput.val(), 10);

        // Primero verificamos si el valor es menor a 1 o está vacío/NULL
        if (isNaN(colFebValue) || colFebValue < 1) {
            examPrevInput.prop('disabled', true); // Deshabilita si está vacío o menor a 1
            examPrevDateInput.prop('disabled', true); // Deshabilita también el input de fecha
        } else if (colFebValue < 7) {
            examPrevInput.prop('disabled', false); // Habilita si es menor a 7 pero mayor o igual a 1
            examPrevDateInput.prop('disabled', false); // Habilita el input de fecha también
        } else {
            examPrevInput.prop('disabled', true); // Deshabilita si es mayor o igual a 7
            examPrevDateInput.prop('disabled', true); // Deshabilita el input de fecha
        }

    }


    $('.mark-input').on('input', function() {
        const studentRow = $(this).closest('tr');
        validateInputsAndToggleFields(studentRow);
    });

    $('#calculateButton').off('click').on('click', function() {
        const sectionId = $(this).data('section-id');
       

        $('table tbody tr').each(function() {
            calculateAverageAndUpdate($(this));
        });

        
        sendAjaxRequest(sectionId);
    });

    // Initialize calculations for all rows
    $('tbody tr').each(function() {
        validateInputsAndToggleFields($(this));
    });

    function sendAjaxRequest(btnSectionId) {
    // Arreglo para almacenar todas las solicitudes AJAX
    let requests = [];

    // Selecciona todos los inputs que tengan el data-subject-id igual al btnSubjectId y que no tengan data-exam-prev-date
    $(`input[data-section-id="${btnSectionId}"]:not([data-exam-prev-date])`).each(function() {
    const inputElement = $(this);

    const class_id = inputElement.data('class-id');
    const section_id = inputElement.data('section-id');
    const subject_id = inputElement.data('subject-id');
    const student_id = inputElement.data('student-id');
    const exam_type = inputElement.data('exam-type');
    let date = inputElement.data('exam-prev-date') || null; 
    const mark_id = inputElement.data('mark-id'); // Si mark_id no existe, asigna un valor vacío


    if (exam_type == 22 || exam_type == '22') {

        console.log(`exam_type tipo 22 su mark id es: ${mark_id}`);

    }

    let mark_obtained = inputElement.val().trim();
    mark_obtained = mark_obtained === '' ? 'NULL' : mark_obtained; // Si el valor está vacío, asigna 'NULL'

    const operation = mark_id ? 'update' : 'create'; // Determina si la operación es 'update' o 'create'

    // Solo ejecuta el código si la operación es 'update'
    if (operation === 'update' && (exam_type === 22 || exam_type === '22')) {
        // Verificar que el mark_id es correcto
        console.log(`mark_id desde el input: ${mark_id}`);

        // Si exam_type es 22, realizamos una acción especial
        if (exam_type === 22 || exam_type === '22') {
            // Busca el input con el data-exam-prev-mark-id correspondiente
            const prevDateInput = $(`input[data-exam-prev-mark-id="${mark_id}"]`);

            // Verifica si se encontró el input correspondiente
            if (prevDateInput.length > 0) {
                date = prevDateInput.val().trim(); // Asigna el valor de date
                if (date === '') {
                    console.error(`El valor del input con data-exam-prev-mark-id="${mark_id}" está vacío.`);
                    date = null;  // Si el valor está vacío, asigna null
                } else {
                    console.log(`El date es: "${date}".`);  // Muestra el valor de date
                }
            } else {
                console.error(`No se encontró un input con data-exam-prev-mark-id="${mark_id}".`); // Mensaje de error
                date = null;  // Asigna null si no se encuentra el input
            }
        }

        // Construye la URL de la solicitud AJAX
        const url = `index.php?admin/mark_history/${operation}/${class_id}/${section_id}/${subject_id}/${student_id}/${exam_type}/${mark_obtained}/${date}/${mark_id}`;

        // Imprime la URL en consola
        console.log('Enviando solicitud a URL:', url);

        // Guarda cada solicitud AJAX en el arreglo requests
        const request = $.ajax({
            url: url,
            success: function(response) {
                console.log('Operación realizada exitosamente.');
            },
            error: function(xhr, status, error) {
                console.error('Error al realizar la operación:', error);
            }
        });

        requests.push(request); // Agrega la solicitud al arreglo
    }
});

    $.when.apply($, requests).done(function() {
         location.reload(); 
     });


  
}


   
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

    .badge-success {
        background-color: #B0DFCC !important; /* Cambiar el color de fondo a verde */
        color: #265044 !important; /* Cambiar el color del texto a blanco */
        padding: 8px 12px; /* Aumentar el padding (espaciado) */
        border-radius: 12px; /* Bordes redondeados */
        font-weight: bold; /* Hacer el texto en negrita */
        text-align: center; /* Centrar el texto */
        display: inline-block; /* Asegurar que se vea como un elemento en línea */
    }

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
