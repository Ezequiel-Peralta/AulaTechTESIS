<?php

class PrintT extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');

        date_default_timezone_set('America/Argentina/Buenos_Aires');
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		
    }

    public function printReportCardES($student_id = '', $section_id = '')
    {
        $student_data = $this->crud_model->get_student_info2($student_id);
        $section = $this->crud_model->get_section_info4($section_id);
        $class_name = $this->crud_model->get_class_name($section['class_id']);
        $academic_period = $this->crud_model->get_academic_period_name_per_section2($section['section_id']);
        $section_letter_name = $section['letter_name'];
        $subjects = $this->crud_model->get_subjects_by_section2($section_id);
        $shift = ($section['shift_id'] == 1) ? ucfirst(get_phrase('morning')) : ucfirst(get_phrase('afternoon'));
        $academic_period_id = $section['academic_period_id'];

        // Inicializamos las variables
        $absent_count = 0;
        $justified_absent_count = 0;

        // Consultar la tabla attendance_student
        $this->db->select('status');
        $this->db->where('student_id', $student_id);
        $this->db->where('section_id', $section_id);
        $query = $this->db->get('attendance_student');
        $attendance_records = $query->result_array();

        // Sumar ausentes e injustificados
        foreach ($attendance_records as $record) {
            if ($record['status'] == 2) {
                $absent_count++;
            } elseif ($record['status'] == 4) {
                $justified_absent_count++;
            }
        }

        // Si no se encontraron registros, buscar en attendance_student_history
        if ($absent_count == 0 && $justified_absent_count == 0) {
            $this->db->select('status');
            $this->db->where('student_id', $student_id);
            $this->db->where('section_id', $section_id);
            $this->db->where('academic_period_id', $section['academic_period_id']);
            $query_history = $this->db->get('attendance_student_history');
            $attendance_records_history = $query_history->result_array();

            foreach ($attendance_records_history as $record) {
                if ($record['status'] == 2) {
                    $absent_count++;
                } elseif ($record['status'] == 4) {
                    $justified_absent_count++;
                }
            }
        }

        // Determinar la condición de asistencia
        $total_absences = $absent_count + $justified_absent_count;
        $attendance_condition = ($total_absences > 25) ? 'T.E.A' : 'REGULAR';

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libreta de Calificaciones del Estudiante</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            margin: 0;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 10px;
            border-bottom: 0.5px solid #000;
        }

        .school-name {
            font-weight: bold;
            font-size: 12px;
        }

        .student-info {
            justify-content: space-between;
            font-size: 12px;
             text-align: center;
                 line-height: 1.8;
            margin-top: 250px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
            font-size: 10px;
        }

        .main-table th {
            background-color: #fff;
            font-weight: bold;
        }

        .subject-column {
            text-align: left !important;
            width: 25%;
        }

        .eval-column {
            width: 30px;
        }

        .bottom-section {
        position: relative;
        bottom: 0;
        width: 100%;
        }
        
        .bottom-section table {
            background-color: white;
        }
        
        /* Ensure second page starts on new page */
        .cover-page {
            page-break-before: always;
        }

        .attendance-box {
            border: 1px solid #000;
            padding: 5px;
            width: 150px;
        }

        .observations-box {
            border: 1px solid #000;
            flex-grow: 1;
            padding: 5px;
        }

      
        .school-logo {
            width: 60px !important;
            height: 80px !important;
            margin-top: 30px;
            margin-bottom: 0px;
        }

        .institution-header {
            margin-bottom: 40px;
            line-height: 1.5;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0;
        }

        .coloquio-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .coloquio-table th, .coloquio-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .coloquio-title {
            font-weight: bold;
            margin: 30px 0 10px;
        }

        .hr {
            width: 100%;
            height: 2px;
            color: black;
        }

        .left-side {
            width: 41%;
            padding: 0px 14px !important;
            border: 2px solid #000;
            margin-left: 32px;
            margin-top: 40px;
        }

        .right-side {
            width: 51%;
            text-align: center;
              border: 2px solid #000 !important;
                      margin-right: 32px;
                             margin-left: 30px;
               margin-top: 40px;
        }

        .evaluation-table {
            width: 100%;
            border-collapse: collapse;
        }

        .evaluation-table td {
            border: 1px solid black;
            padding: 1px;
            text-align: left;
        } .evaluation-table th {
            border: 1px solid black;
            text-align: center;
        }

        .evaluation-table th {
            font-weight: bolder !important;
            font-size: 9px !important;
        }

        .period-title {
            font-weight: bold;
            font-size: 12px;
            margin: 20px 0 5px;
            text-align: center;
        }

        .school-logo {
            width: 120px;
            margin-bottom: 5px;
        }

        .header-text {
            text-align: center;
            margin-bottom: 40px;
        }

        .header-text .bold {
            font-weight: bold;
        }

        .report-title {
            font-size: 16px;
            font-weight: bolder;
            text-align: center;
            margin: 55px 0;
            line-height: 1.5;
        }
      
    </style>
</head>
<body>
    <!-- First Page -->
    <div class="header">
        <div>
            <div class="school-name" style="margin-bottom: 3px; font-size: 12px;">NOMBRE DE LA INSTITUCIÓN</div>
            <div style="margin-bottom: 5px; font-size: 12px;">Dirección</div>
        </div>
        <div style="text-align: right;">
            <div style="font-weight: bolder; font-size: 14px;">INFORME DE PROGRESO ESCOLAR</div>
           
        </div>
    </div>

    <div style="margin-bottom: 12px;  border-bottom: 0.5px solid #000;">
            <div style="text-align: left; margin-top: -12px; font-size: 12px;  margin-bottom: 5px;">
                <div>
                    Plan: <span style="font-weight: bold;"> Sin Modalidad </span><br>
                </div>
                <div style="margin-top: 3px;">
                    Estudiante: <span style="font-weight: bold;">' . $student_data['lastname'] . ', ' . $student_data['firstname'] . ' </span>
                    <span style="margin-left: 20px;"> Tipo y N° doc: <span style="font-weight: bold;">DNI - 47475089 </span></span>
                </div>
            </div>
           
        <div style="text-align: right; margin-top: -37px; margin-bottom: 5px;">
            <div style="font-size: 12px;">
                Curso: <span style="font-weight: bold; margin-right: 5px;">' . $section['class_id'] . '</span>  División: <span style="font-weight: bold; margin-right: 5px;">' . ucfirst($section['letter_name']) . '</span> Turno: <span style="font-weight: bold; margin-right: 5px;">' . $shift . '</span> Ciclo Lectivo: <span style="font-weight: bold; margin-right: 5px;">' . $academic_period . '</span><br>
                <div style="margin-top: 3px; margin-right: 5px;">
                    Versión IPE: <span style="font-weight: bold;">Preliminar</span>
                </div>
            </div>
        </div>
    </div>

    

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2" class="subject-column text-center" style="font-size: 12px !important; font-weight: bolder !important; vertical-align: middle !important; text-align: center !important;">Espacios Curriculares (E.C.)</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 1</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 2</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 3</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 4</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 5</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 6</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">Eval 7</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">JIIS 1</th>
                <th colspan="2" style="font-size: 11px !important; font-weight: bolder !important;">JIIS 2</th>
                <th style="font-size: 11px !important; font-weight: bolder !important;">Coloquio Dic.</th>
                <th style="font-size: 11px !important; font-weight: bolder !important;">Coloquio Feb.</th>
                <th style="font-size: 11px !important; font-weight: bolder !important;">Prom. Final</th>
            </tr>
            <tr>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th>N</th>
                <th>R1</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>';
    
        foreach ($subjects as $subject) {
            // Obtener las marcas del estudiante para la asignatura
            $marks = $this->crud_model->get_marks_by_student_subject2($student_id, $subject['subject_id'], $section['academic_period_id']);
    
            // Inicializar un array para almacenar los marks organizados por exam_type_id
            $marks_by_exam_type = array_fill(1, 21, ''); // Rellenar todas las posiciones con valores vacíos
    
            // Rellenar los valores existentes en $marks_by_exam_type
            foreach ($marks as $mark) {
                if ($mark['exam_type_id'] >= 1 && $mark['exam_type_id'] <= 21) {
                    // Validar si mark_obtained no está vacío
                    if (!empty($mark['mark_obtained'])) {
                        $mark_value = floatval($mark['mark_obtained']);
                        // Si el valor es 0.00, establecer como vacío; si no, usar el valor correspondiente
                        $marks_by_exam_type[$mark['exam_type_id']] = ($mark_value === 0.00) ? '' : $mark_value;
                    }
                }
            }
        
            // Generar la fila HTML
            $html .= '<tr>';
            $html .= '<td class="subject-column">' . $subject['name'] . '</td>';
        
            for ($exam_type_id = 1; $exam_type_id <= 21; $exam_type_id++) {
                // Usar directamente el valor almacenado en $marks_by_exam_type
                $value = $marks_by_exam_type[$exam_type_id];
                $html .= '<td>' .  ($value !== '' ? $value : '') . '</td>';
            }
        
            $html .= '</tr>';
        }

    



        $html .= '
        </tbody>
    </table>

    <div class="bottom-section" style="display: flex; gap: 10px; margin-top: 30px;">
        <div style="width: 100px;">
           <table style="width: 80%; border-collapse: collapse;">
                <tr>
                    <th rowspan="2" style="border: 1px solid #000; padding: 7px 4px; font-size: 7px; font-weight: bolder; text-align: center;">
                        INASISTENCIAS<br>DIARIAS
                    </th>
                    <td style="border: 1px solid #000; font-size: 7px; padding: 1px 4px; font-weight: bolder; text-align: center; width: 25%;">
                        Just.
                    </td>
                    <td style="border: 1px solid #000; font-size: 7px; padding: 1px 4px; font-weight: bolder; text-align: center; width: 25%;">
                        Inj.
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000; font-size: 7px; text-align: center;">
                        ' . $justified_absent_count .'
                    </td>
                    <td style="border: 1px solid #000; font-size: 7px; text-align: center;">
                       ' . $absent_count .'
                    </td>
                </tr>
                <tr>
                    <td colspan="1" style="border: 1px solid #000; padding: 3px 0px; font-size: 7px; text-align: center; font-weight: bold;">
                        ESTADO
                    </td>
                    <td colspan="2" style="border: 1px solid #000; padding: 3px 0px; font-size: 7px; text-align: center;">
                        ' . $attendance_condition . '
                    </td>
                </tr>
                
            </table>
        </div>
        
        <div style="flex: 1; padding: 8px;  border: 2px solid #000; margin-left: 3px; margin-top: -10px;">
            <div style="font-weight: bolder; margin-top: -4px; padding-bottom: 4px; text-align: center !important; margin-left: -8px; margin-right: -8px; border-bottom: 1px solid #000; font-size: 7px;">OBSERVACIONES</div>
            <div style="min-height: 40px;"></div>
        </div>
        


        <div style="flex: 1; border: 2px solid #000; margin-left: -7px; margin-top: -10px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 7px; height: 100%;">
                <thead>
                    <tr>
                        <th style="text-align: center; padding: 4px 0px; font-weight: bolder; border-bottom: 0px solid #000; colspan="3">
                            E.C. EN CONTRATURNO EN ESTADO T.E.A.
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border-top: 1px solid #000;"></td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #000;"></td>

                    <tr>
                    </tr>
                        <td style="border-top: 1px solid #000;"></td>
                    </tr>
                
                </tbody>
            </table>
        </div>

        
     

         <div style="flex: 1; border: 2px solid #000; margin-left: -7px; margin-top: -10px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 7px; height: 100%;">
                <thead>
                    <tr>
                        <th style="text-align: center; padding: 4px 0px; font-weight: bolder; border-bottom: 1px solid #000; colspan="3">
                            E.C. PREVIOS.
                        </th>
                    </tr>
                </thead>
                <tbody>';

                $rowCount = 0;

                // Recorre las asignaturas y sus marcas
                foreach ($subjects as $subject) {
                    // Obtener las marcas para el subject con exam_type_id = 21
                    $marks = $this->crud_model->get_marks_by_student_subject3($student_id, $subject['subject_id'], $section['academic_period_id']);
                
                    foreach ($marks as $mark) {
                        if ($mark['exam_type_id'] == 21 && ($mark['date'] !== null && $mark['date'] !== '' && $mark['mark_obtained'] < 7)) {
                            // Convertir `mark_obtained` a entero y verificar que sea mayor a 0
                            $mark_obtained = intval($mark['mark_obtained']);
                            if ($mark_obtained > 0) {
                                $rowCount++; // Incrementa el contador de filas generadas
                
                                // Generar la fila HTML
                                $html .= '<tr style="text-align: center;">';
                                $html .= '<td style="border-top: 1px solid #000;">' . htmlspecialchars($subject['name'])
                                . '&nbsp; - &nbsp;' . $section['name'];
                                $html .= '</td> </tr>';
                            }
                        }
                    }
                }
                
                // Completar las filas faltantes hasta alcanzar el mínimo de 3
                while ($rowCount < 3) {
                    $html .= '<tr>';
                    $html .= '<td style="border-top: 1px solid #000;">&nbsp;</td>'; // Celda vacía para el nombre
                    $html .= '<td style="border-top: 1px solid #000;">&nbsp;</td>'; // Celda vacía para la nota
                    $html .= '<td style="border-top: 1px solid #000;">&nbsp;</td>'; // Celda vacía para la fecha
                    $html .= '</tr>';
                    $rowCount++;
                }
                
                $html .= '
                </tbody>
            </table>
        </div>

       
    </div>

    <div style="margin-top: 220px; text-align: center; width: 100%;">
            <div style="display: flex; justify-content: center; gap: 200px;">
                <div style="text-align: center;">
                    <div style="border-bottom: 1px dotted #000; width: 200px; margin-bottom: 5px;">
                        &nbsp;
                    </div>
                    <div style="font-size: 11px;">
                        Firma del Padre, Madre o Tutor
                    </div>
                </div>
                
                <div style="text-align: center;">
                    <div style="border-bottom: 1px dotted #000; width: 200px; margin-bottom: 5px;">
                        &nbsp;
                    </div>
                    <div style="font-size: 11px;">
                        Firma del/la Director/a
                    </div>
                </div>
            </div>
        </div>

    <!-- Second Page -->
    <div style="page-break-before: always;"></div>

    <div style="font-family: Arial, sans-serif;
            display: flex;
            justify-content: space-between;">
    
        <div class="left-side" >
            <div class="period-title">PERIODO DE EVALUACIÓN: COLOQUIO DICIEMBRE</div>
            <table class="evaluation-table" style="margin-bottom: 30px !important;">
                <tr>
                    <th rowspan="2" style="width: 11%; padding: 15px 0px !important;">DISCIPLINA</th>
                    <th rowspan="2" style="width: 8%; padding: 15px 0px !important;">FECHA</th>
                    <th colspan="2" style="width: 11%; padding: 6px 0px !important;">CALIFICACIÓN</th>
                    <th rowspan="2" style="width: 44%; padding: 15px 0px !important;">FIRMA DEL PROFESOR</th>
                </tr>
                <tr>
                    <th style="padding: 7px 5px;">N°</th>
                    <th style="padding: 7px 0px;">LETRA</th>
                </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
            </table>

            <div class="period-title">PERIODO DE EVALUACIÓN: COLOQUIO FEBRERO</div>
            <table class="evaluation-table" style="margin-bottom: 10px !important;">
                <tr>
                    <th rowspan="2" style="width: 11%; padding: 15px 0px !important;">DISCIPLINA</th>
                    <th rowspan="2" style="width: 8%; padding: 15px 0px !important;">FECHA</th>
                    <th colspan="2" style="width: 11%; padding: 6px 0px !important;">CALIFICACIÓN</th>
                    <th rowspan="2" style="width: 44%; padding: 15px 0px !important;">FIRMA DEL PROFESOR</th>
                </tr>
                <tr>
                    <th style="padding: 7px 5px;">N°</th>
                    <th style="padding: 7px 0px;">LETRA</th>
                </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
            </table>
        </div>

        <div class="right-side">
            <img src="' . base_url('assets/images/favicon2.png') . '" alt="NOMBRE DE LA INSTITUCIÓN" class="school-logo">
            
            <div class="header-text">
                <div class="" style="margin-bottom: 5px; font-weight: bolder !important;">GOBIERNO DE CÓRDOBA</div>
                <div>MINISTERIO DE EDUCACIÓN</div>
                <div>SECRETARÍA DE ESTADO DE EDUCACIÓN</div>
                <div>DIRECCIÓN GENERAL DE INSTITUTOS PRIVADOS DE ENSEÑANZAS</div>
                <br>
                <div>NOMBRE DE LA INSTITUCIÓN</div>
                <div style="font-size: 12px;">Nombre del centro educativo</div>
                <br>
                <div>Localidad: CORDOBA</div>
                <div>Departamento: CAPITAL</div>
            </div>

            <div class="report-title">
                LIBRETA DE CALIFICACIONES DEL ESTUDIANTE<br>
                PRIMER CICLO
            </div>

            <div class="student-info">
                Curso: <span style="font-weight: bolder;">';
                
                switch ($section['class_id']) {
                    case '1':
                        $html .= 'PRIMER AÑO';
                        break;
                    case '2':
                        $html .= 'SEGUNDO AÑO';
                        break;
                    case '3':
                        $html .= 'TERCER AÑO';
                        break;
                    case '4':
                        $html .= 'CUARTO AÑO';
                        break;
                    case '5':
                        $html .= 'QUINTO AÑO';
                        break;
                    case '6':
                        $html .= 'SEXTO AÑO';
                        break;
                    default:
                        echo ' '; 
                        break;
                }
                
                $html .= '</span>&nbsp;&nbsp;
                División: <span style="font-weight: bolder;">' . ucfirst($section['letter_name']) . '</span>&nbsp;&nbsp;
                Turno: <span style="font-weight: bolder;">' . $shift . '</span>
                <br>
                Estudiante: <span style="font-weight: bolder;">' . $student_data['lastname'] . ', ' . $student_data['firstname'] . '</span>
                <br>
                Tipo y N° doc: <span style="font-weight: bolder;">DNI - ' . $student_data['dni'] . '</span>
                <br>
                <span style="font-weight: bolder; font-size: 14px;">AÑO LECTIVO ' . $academic_period . '</span>
            </div>
        </div>

    </div>

    <script>window.print();</script>
</body>
</html>';

        // Mostrar el HTML
        echo $html;
    }


    public function printStudentTableES($section_id = '')
    {
        // Obtener datos de los estudiantes y la sección
        $student_data = $this->crud_model->get_students_per_section($section_id);
        $section = $this->crud_model->get_section_info($section_id);
        $class_name = $this->crud_model->get_class_name($section['class_id']);
        $academic_period = $this->crud_model->get_academic_period_name_per_section($section_id);
        $section_letter_name = $this->crud_model->get_section_letter_name($section_id);
        $shift = ($section['shift_id'] == 1) ? ucfirst(get_phrase('morning')) : ucfirst(get_phrase('afternoon'));

        // Configuración de estudiantes por página
        $studentsPerPage = 13;
        $totalPages = ceil(count($student_data) / $studentsPerPage);

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Reporte de Estudiantes</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0px 0 10px 0px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }
                .info-item {
                    display: inline-block;
                    width: 23%;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .table td:nth-child(5), .table td:nth-child(6) {
                    word-wrap: break-word;
                    word-break: break-all;
                    max-width: 150px;
                }
                .text-left {
                    text-align: left;
                }
                .page-footer {
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                .page-break {
                    page-break-after: always;
                }
            </style>
        </head>
        <body>';

        // Generar encabezado común
        $header = '
        <div class="page-header">
            <div class="header-top">
                <div class="logo-container">
                    <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                </div>
                <div class="report-title">
                    <strong>Reporte de Estudiantes</strong> <br>
                    <span style="font-size: 16px;">' . date('d/m/Y') . '</span>
                </div>
            </div>
            <div class="course-info">
                <div class="info-item"><strong>Curso:</strong> ' . $class_name . '</div>
                <div class="info-item"><strong>División:</strong> ' . $section_letter_name . '</div>
                <div class="info-item"><strong>Turno:</strong> ' . $shift . '</div>
                <div class="info-item"><strong>Ciclo lectivo:</strong> ' . $academic_period . '</div>
            </div>
        </div>';

        // Paginación de estudiantes
        for ($page = 1; $page <= $totalPages; $page++) {
            if ($page > 1) {
                $html .= '<div class="page-break"></div>';
            }
            $html .= $header;
            
            $html .= '<div class="container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Apellido</th>
                                    <th>Nombre</th>
                                    <th>Género</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th>Usuario</th>
                                    <th>Fecha Nac.</th>
                                </tr>
                            </thead>
                            <tbody>';

            // Calcular el rango de estudiantes para esta página
            $startIndex = ($page - 1) * $studentsPerPage;
            $studentsOnPage = array_slice($student_data, $startIndex, $studentsPerPage);

            foreach ($studentsOnPage as $student) {
                $birthday = date('d/m/Y', strtotime($student['birthday']));
                
                $html .= '<tr>
                            <td class="text-left">' . $student['lastname'] . '</td>
                            <td class="text-left">' . $student['firstname'] . '</td>
                            <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                            <td>' . $student['dni'] . '</td>
                            <td>' . htmlspecialchars($student['email']) . '</td>
                            <td>' . $student['username'] . '</td>
                            <td>' . $birthday . '</td>
                        </tr>';
            }

            $html .= '</tbody></table></div>';

            // Pie de página con el número de página
            $html .= '<div class="page-footer">
                        <strong>Página ' . $page . ' de ' . $totalPages . '</strong>
                    </div>';
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        // Mostrar el HTML
        echo $html;
    }
        

    public function printStudentTableEN($section_id = '')
    {
        // Obtener datos de los estudiantes y secciones
        $student_data = $this->crud_model->get_students_per_section($section_id);
        $section = $this->crud_model->get_section_info($section_id);
        $section_letter_name = $this->crud_model->get_section_letter_name($section_id);
        $class_name = $this->crud_model->get_class_name($section['class_id']);
        $academic_period = $this->crud_model->get_academic_period_name_per_section($section_id);
    
        // Turno
        $shift = ($section['shift_id'] == 1) ? ucfirst(get_phrase('morning')) : ucfirst(get_phrase('afternoon'));
    
        // Número máximo de estudiantes por página
        $studentsPerPage = 16;
        
        // Calcular el número de páginas necesarias
        $totalPages = ceil(count($student_data) / $studentsPerPage);
    
        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Student report - ' . $class_name . ' ' . $section_letter_name . ' - ' . date('d-m-Y') . '</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                .page-header {
                    padding: 5px 20px;
                    width: 100%;
                    border-bottom: 1px solid #ddd;
                }
    
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-top: -10px;
                    margin-bottom: 0px;
                }
    
                .logo {
                    width: 50px;
                    height: auto;
                }
    
                .report-title {
                    font-size: 18px;
                    text-align: right;
                }
    
                .course-info {
                    display: flex;
                    justify-content: center;
                    gap: 20px;
                    font-size: 14px;
                    margin-top: 0px;
                    margin-bottom: 5px;
                    text-align: center;
                }
    
                .table thead th {
                    vertical-align: middle;
                    text-align: center;
                    font-size: 14px;
                }
    
                .table tbody td {
                    vertical-align: middle;
                    text-align: center;
                }
    
                .page-footer {
                    font-size: 12px;
                    text-align: center;
                    padding-top: 0px;
                }
    
                @media print {
                    @page { margin: 0; size: auto; }
                    .page-header {
                        position: fixed;
                        top: 0;
                        width: 100%;
                        z-index: 100;
                    }
                    .page-footer {
                   font-size: 12px;
        text-align: center;
        padding-top: 0px;
        margin-top: 20px; 

                    }
                    .container {
                        margin-top: 135px;
                    }
                    .table {
                        margin-bottom: 0px;
                    }
                }
            </style>
        </head>
        <body>';
    
        // Encabezado común en todas las páginas
        $header = '
        <div class="page-header">
                    <div class="header-top">
                        <div class="logo-container">
                            <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                        </div>
                        <div class="report-title">
                            <strong> Student report </strong> <br>
                            <span style="font-size: 14px;">' . date('d/m/Y') . '</span>
                        </div>
                    </div>
                    <div class="course-info">
                        <div><strong>Class:</strong> ' . $class_name . '</div>
                        <div><strong>Section:</strong> ' . $section_letter_name . '</div>
                        <div><strong>Shift:</strong> ' . $shift . '</div>
                        <div><strong>Academic period:</strong> ' . $academic_period . '</div>
                    </div>
                </div>
                ';
    
        // Paginación de estudiantes
        for ($page = 1; $page <= $totalPages; $page++) {
            $html .= $header; // Agregar el encabezado en cada página
            $html .= '<div class="container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Lastname</th>
                                    <th>Firstname</th>
                                    <th>Gender</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>B. Date</th>
                                </tr>
                            </thead>
                            <tbody>';
    
            // Agregar los estudiantes correspondientes a la página actual
            $startIndex = ($page - 1) * $studentsPerPage;
            $endIndex = min($startIndex + $studentsPerPage, count($student_data));
            for ($i = $startIndex; $i < $endIndex; $i++) {
                $student = $student_data[$i];
                $birthday = date('d/m/Y', strtotime($student['birthday']));
    
                $html .= '<tr>
                            <td class="text-left">' . $student['lastname'] . '</td>
                            <td class="text-left">' . $student['firstname'] . '</td>
                            <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                            <td>' . $student['dni'] . '</td>
                            <td>' . htmlspecialchars($student['email']) . '</td>
                            <td>' . $student['username'] . '</td>
                            <td>' . $birthday . '</td>
                        </tr>';
            }
    
            $html .= '</tbody></table></div>';
    
            // Pie de página con número de página
            $html .= '<div class="page-footer">
                        <strong> Page ' . $page . ' of ' . $totalPages . ' </strong>
                    </div>';
    
            // Divisor de página (agrega un salto de página en la impresión)
            if ($page < $totalPages) {
                $html .= '<div style="page-break-after: always;"></div>';
            }
        }
    
        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';
    
        // Mostrar el HTML
        echo $html;
    }
    

    public function printAllStudentTableES()
    {
        // Obtener los datos de las secciones activas y de los estudiantes
        $sections = $this->crud_model->get_all_sections(); 
        $student_data = $this->crud_model->get_all_students_info();

        // Configuración de estudiantes por página
        $studentsPerPage = 13; 

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Reporte de Estudiantes</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                    .page-header {
                     margin-top: 5px;

                     }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                   
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0px 0 10px 0px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }
                .info-item {
                    display: inline-block;
                    width: 23%; /* Ajustar según el espacio necesario */
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                    .table td:nth-child(5), /* Email */
                    .table td:nth-child(6) { /* Usuario */
                        word-wrap: break-word;
                        word-break: break-all;
                        max-width: 150px; /* Ajusta el ancho máximo según sea necesario */
                    }


                .text-left {
                    text-align: left;
                }
                .page-footer {
                    text-align: center;
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                /* Saltos de página */
                .page-break {
                    page-break-after: always;
                }
            </style>
        </head>
        <body>';

        // Para cada sección, generamos su reporte
        foreach ($sections as $section) {
            // Obtener datos específicos de la sección
            $class_name = $this->crud_model->get_class_name($section['class_id']);
            $section_letter_name = $section['letter_name'];
            $academic_period = $this->crud_model->get_academic_period_name_per_section($section['section_id']);

            // Determinar el turno según shift_id
            $shift = '';
            if ($section['shift_id'] == 1) {
                $shift = ucfirst(get_phrase('morning'));
            } elseif ($section['shift_id'] == 2) {
                $shift = ucfirst(get_phrase('afternoon'));
            }

            // Filtrar estudiantes por sección actual
            $students_in_section = array_filter($student_data, function($student) use ($section) {
                return $student['section_id'] == $section['section_id'];
            });

            // Número total de páginas para esta sección
            $totalPages = ceil(count($students_in_section) / $studentsPerPage);

            // Encabezado para la sección (incluido en cada página)
            $header = '
            <div class="page-header">
                <div class="header-top">
                    <div class="logo-container">
                        <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                    </div>
                    <div class="report-title">
                        <strong>Reporte de Estudiantes</strong> <br>
                        <span class="text-right" style="font-size: 16px;">' . date('d/m/Y') . '</span>
                    </div>
                </div>
                <div class="course-info">
                    <div class="info-item"><strong>Curso:</strong> ' . $class_name . '</div>
                    <div class="info-item"><strong>División:</strong> ' . $section_letter_name . '</div>
                    <div class="info-item"><strong>Turno:</strong> ' . $shift . '</div>
                    <div class="info-item"><strong>Ciclo lectivo:</strong> ' . $academic_period . '</div>
                </div>
            </div>';

            // Paginación de estudiantes para esta sección
            for ($page = 1; $page <= $totalPages; $page++) {
                // Evita duplicar saltos de página innecesarios
                if ($page > 1 || $section !== reset($sections)) {
                    $html .= '<div class="page-break"></div>';
                }
                $html .= $header; // Encabezado por página
                
                // Tabla de estudiantes para la página actual
                $html .= '<div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Apellido</th>
                                        <th>Nombre</th>
                                        <th>Género</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                        <th>Usuario</th>
                                        <th>Fecha Nac.</th>
                                    </tr>
                                </thead>
                                <tbody>';

                // Calcular el rango de estudiantes para esta página
                $startIndex = ($page - 1) * $studentsPerPage;
                $studentsOnPage = array_slice($students_in_section, $startIndex, $studentsPerPage);

                foreach ($studentsOnPage as $student) {
                    $birthday = date('d/m/Y', strtotime($student['birthday']));
                    
                    $html .= '<tr>
                                <td class="text-left">' . $student['lastname'] . '</td>
                                <td class="text-left">' . $student['firstname'] . '</td>
                                <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                                <td>' . $student['dni'] . '</td>
                                <td>' . htmlspecialchars($student['email']) . '</td>
                                <td>' . $student['username'] . '</td>
                                <td>' . $birthday . '</td>
                            </tr>';
                }

                $html .= '</tbody></table></div>';

                // Pie de página con el número de página
                $html .= '<div class="page-footer">
                            <strong>Página ' . $page . ' de ' . $totalPages . '</strong>
                        </div>';
            }
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        // Mostrar el HTML
        echo $html;
    }


    public function printAllStudentTableEN()
    {
        // Obtener los datos de las secciones activas y de los estudiantes
        $sections = $this->crud_model->get_all_sections(); 
        $student_data = $this->crud_model->get_all_students_info();

        // Configuración de estudiantes por página
        $studentsPerPage = 13; 

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Reporte de Estudiantes</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                    .page-header {
                     margin-top: 5px;

                     }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                   
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0px 0 10px 0px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }
                .info-item {
                    display: inline-block;
                    width: 23%; /* Ajustar según el espacio necesario */
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                    .table td:nth-child(5), /* Email */
                    .table td:nth-child(6) { /* Usuario */
                        word-wrap: break-word;
                        word-break: break-all;
                        max-width: 150px; /* Ajusta el ancho máximo según sea necesario */
                    }


                .text-left {
                    text-align: left;
                }
                .page-footer {
                    text-align: center;
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                /* Saltos de página */
                .page-break {
                    page-break-after: always;
                }
            </style>
        </head>
        <body>';

        // Para cada sección, generamos su reporte
        foreach ($sections as $section) {
            // Obtener datos específicos de la sección
            $class_name = $this->crud_model->get_class_name($section['class_id']);
            $section_letter_name = $section['letter_name'];
            $academic_period = $this->crud_model->get_academic_period_name_per_section($section['section_id']);

            // Determinar el turno según shift_id
            $shift = '';
            if ($section['shift_id'] == 1) {
                $shift = ucfirst(get_phrase('morning'));
            } elseif ($section['shift_id'] == 2) {
                $shift = ucfirst(get_phrase('afternoon'));
            }

            // Filtrar estudiantes por sección actual
            $students_in_section = array_filter($student_data, function($student) use ($section) {
                return $student['section_id'] == $section['section_id'];
            });

            // Número total de páginas para esta sección
            $totalPages = ceil(count($students_in_section) / $studentsPerPage);

            // Encabezado para la sección (incluido en cada página)
            $header = '
            <div class="page-header">
                <div class="header-top">
                    <div class="logo-container">
                        <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                    </div>
                    <div class="report-title">
                        <strong>Student Report</strong> <br>
                        <span class="text-right" style="font-size: 16px;">' . date('d/m/Y') . '</span>
                    </div>
                </div>
                <div class="course-info">
                    <div class="info-item"><strong>Class:</strong> ' . $class_name . '</div>
                    <div class="info-item"><strong>Section:</strong> ' . $section_letter_name . '</div>
                    <div class="info-item"><strong>Shift:</strong> ' . $shift . '</div>
                    <div class="info-item"><strong>Academic period:</strong> ' . $academic_period . '</div>
                </div>
            </div>';

            // Paginación de estudiantes para esta sección
            for ($page = 1; $page <= $totalPages; $page++) {
                // Evita duplicar saltos de página innecesarios
                if ($page > 1 || $section !== reset($sections)) {
                    $html .= '<div class="page-break"></div>';
                }
                $html .= $header; // Encabezado por página
                
                // Tabla de estudiantes para la página actual
                $html .= '<div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Lastname</th>
                                        <th>Firstname</th>
                                        <th>Gender</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>B. Date</th>
                                    </tr>
                                </thead>
                                <tbody>';

                // Calcular el rango de estudiantes para esta página
                $startIndex = ($page - 1) * $studentsPerPage;
                $studentsOnPage = array_slice($students_in_section, $startIndex, $studentsPerPage);

                foreach ($studentsOnPage as $student) {
                    $birthday = date('d/m/Y', strtotime($student['birthday']));
                    
                    $html .= '<tr>
                                <td class="text-left">' . $student['lastname'] . '</td>
                                <td class="text-left">' . $student['firstname'] . '</td>
                                <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                                <td>' . $student['dni'] . '</td>
                                <td>' . htmlspecialchars($student['email']) . '</td>
                                <td>' . $student['username'] . '</td>
                                <td>' . $birthday . '</td>
                            </tr>';
                }

                $html .= '</tbody></table></div>';

                // Pie de página con el número de página
                $html .= '<div class="page-footer">
                            <strong>Page ' . $page . ' of ' . $totalPages . '</strong>
                        </div>';
            }
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        // Mostrar el HTML
        echo $html;
    }

    public function printClassStudentTableES($class_id = '')
    {
        // Obtener los datos de las secciones activas y de los estudiantes según la clase
        $sections = $this->crud_model->get_all_sections_per_class($class_id);
        $student_data = $this->crud_model->get_all_students_info();

        // Configuración de estudiantes por página
        $studentsPerPage = 13;

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Reporte de Estudiantes</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                     .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0px 0 10px 0px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .page-break {
                    page-break-after: always;
                }
            </style>
        </head>
        <body>';

        // Para cada sección de la clase especificada, generamos su reporte
        foreach ($sections as $section) {
            $class_name = $this->crud_model->get_class_name($section['class_id']);
            $section_letter_name = $section['letter_name'];
            $academic_period = $this->crud_model->get_academic_period_name_per_section($section['section_id']);

            $shift = '';
            if ($section['shift_id'] == 1) {
                $shift = ucfirst(get_phrase('morning'));
            } elseif ($section['shift_id'] == 2) {
                $shift = ucfirst(get_phrase('afternoon'));
            }

            $students_in_section = array_filter($student_data, function($student) use ($section) {
                return $student['section_id'] == $section['section_id'];
            });

            $totalPages = ceil(count($students_in_section) / $studentsPerPage);

            $header = '
            <div class="page-header">
                <div class="header-top">
                    <div class="logo-container">
                        <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                    </div>
                    <div class="report-title">
                        <strong>Reporte de Estudiantes</strong> <br>
                        <span class="text-right" style="font-size: 16px;">' . date('d/m/Y') . '</span>
                    </div>
                </div>
                <div class="course-info">
                    <div class="info-item"><strong>Curso:</strong> ' . $class_name . '</div>
                    <div class="info-item"><strong>División:</strong> ' . $section_letter_name . '</div>
                    <div class="info-item"><strong>Turno:</strong> ' . $shift . '</div>
                    <div class="info-item"><strong>Ciclo lectivo:</strong> ' . $academic_period . '</div>
                </div>
            </div>';

            for ($page = 1; $page <= $totalPages; $page++) {
                if ($page > 1 || $section !== reset($sections)) {
                    $html .= '<div class="page-break"></div>';
                }
                $html .= $header;

                $html .= '<div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Apellido</th>
                                        <th>Nombre</th>
                                        <th>Género</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                        <th>Usuario</th>
                                        <th>Fecha Nac.</th>
                                    </tr>
                                </thead>
                                <tbody>';

                $startIndex = ($page - 1) * $studentsPerPage;
                $studentsOnPage = array_slice($students_in_section, $startIndex, $studentsPerPage);

                foreach ($studentsOnPage as $student) {
                    $birthday = date('d/m/Y', strtotime($student['birthday']));

                    $html .= '<tr>
                                <td class="text-left">' . $student['lastname'] . '</td>
                                <td class="text-left">' . $student['firstname'] . '</td>
                                <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                                <td>' . $student['dni'] . '</td>
                                <td>' . htmlspecialchars($student['email']) . '</td>
                                <td>' . $student['username'] . '</td>
                                <td>' . $birthday . '</td>
                            </tr>';
                }

                $html .= '</tbody></table></div>';
                $html .= '<div class="page-footer">
                            <strong>Página ' . $page . ' de ' . $totalPages . '</strong>
                        </div>';
            }
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        echo $html;
    }


    public function printClassStudentTableEN($class_id = '')
    {
        // Obtener los datos de las secciones activas y de los estudiantes según la clase
        $sections = $this->crud_model->get_all_sections_per_class($class_id);
        $student_data = $this->crud_model->get_all_students_info();

        // Configuración de estudiantes por página
        $studentsPerPage = 13;

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Student Report</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                     .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0px 0 10px 0px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .page-break {
                    page-break-after: always;
                }
            </style>
        </head>
        <body>';

        // Para cada sección de la clase especificada, generamos su reporte
        foreach ($sections as $section) {
            $class_name = $this->crud_model->get_class_name($section['class_id']);
            $section_letter_name = $section['letter_name'];
            $academic_period = $this->crud_model->get_academic_period_name_per_section($section['section_id']);

            $shift = '';
            if ($section['shift_id'] == 1) {
                $shift = ucfirst(get_phrase('morning'));
            } elseif ($section['shift_id'] == 2) {
                $shift = ucfirst(get_phrase('afternoon'));
            }

            $students_in_section = array_filter($student_data, function($student) use ($section) {
                return $student['section_id'] == $section['section_id'];
            });

            $totalPages = ceil(count($students_in_section) / $studentsPerPage);

            $header = '
            <div class="page-header">
                <div class="header-top">
                    <div class="logo-container">
                        <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                    </div>
                    <div class="report-title">
                        <strong>Reporte de Estudiantes</strong> <br>
                        <span class="text-right" style="font-size: 16px;">' . date('d/m/Y') . '</span>
                    </div>
                </div>
                <div class="course-info">
                    <div class="info-item"><strong>Class:</strong> ' . $class_name . '</div>
                    <div class="info-item"><strong>Section:</strong> ' . $section_letter_name . '</div>
                    <div class="info-item"><strong>Shift:</strong> ' . $shift . '</div>
                    <div class="info-item"><strong>Academic period:</strong> ' . $academic_period . '</div>
                </div>
            </div>';

            for ($page = 1; $page <= $totalPages; $page++) {
                if ($page > 1 || $section !== reset($sections)) {
                    $html .= '<div class="page-break"></div>';
                }
                $html .= $header;

                $html .= '<div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Lastname</th>
                                        <th>Firstnmae</th>
                                        <th>Gender</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>B. Date</th>
                                    </tr>
                                </thead>
                                <tbody>';

                $startIndex = ($page - 1) * $studentsPerPage;
                $studentsOnPage = array_slice($students_in_section, $startIndex, $studentsPerPage);

                foreach ($studentsOnPage as $student) {
                    $birthday = date('d/m/Y', strtotime($student['birthday']));

                    $html .= '<tr>
                                <td class="text-left">' . $student['lastname'] . '</td>
                                <td class="text-left">' . $student['firstname'] . '</td>
                                <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                                <td>' . $student['dni'] . '</td>
                                <td>' . htmlspecialchars($student['email']) . '</td>
                                <td>' . $student['username'] . '</td>
                                <td>' . $birthday . '</td>
                            </tr>';
                }

                $html .= '</tbody></table></div>';
                $html .= '<div class="page-footer">
                            <strong>Page ' . $page . ' of ' . $totalPages . '</strong>
                        </div>';
            }
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        echo $html;
    }

    public function exportStudentTableExcelES()
    {
        $student_data = $this->crud_model->exportStudentTableExcelES();
        
        echo json_encode($student_data);
    }


    public function exportStudentTableExcelEN()
    {
        $student_data = $this->crud_model->exportStudentTableExcelEN();
        
        echo json_encode($student_data);
    }

    public function exportClassStudentTableExcelES($class_id = '')
    {
        $student_data = $this->crud_model->exportClassStudentTableExcelES($class_id);
        
        echo json_encode($student_data);
    }

    public function printStudentAdmissionsTableES()
    {
        // Obtener datos de los estudiantes y la sección
        $student_data = $this->crud_model->get_students_admissions();
        $academic_period = $this->crud_model->get_active_academic_period_name();

        $studentsPerPage = 13;
        $totalPages = ceil(count($student_data) / $studentsPerPage);

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>&nbsp;</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 10px 10px 10px 10px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: center; 
                    align-items: center; 
                    text-align: center;   
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                .info-item {
                    display: inline-block;
                    width: 23%;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .table td:nth-child(5), .table td:nth-child(6) {
                    word-wrap: break-word;
                    word-break: break-all;
                    max-width: 150px;
                }
                .text-left {
                    text-align: left;
                }
                .page-footer {
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                .page-break {
                    page-break-after: always;
                }

                @media print {
                    @page { margin: 0;
                    size: auto; }
                }
            </style>
        </head>
        <body>';

        // Generar encabezado común
        $header = '
        <div class="page-header">
            <div class="header-top">
                <div class="logo-container">
                    <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                </div>
                <div class="report-title">
                    <strong>Reporte de Estudiantes en admisiones</strong> <br>
                    <span style="font-size: 16px;">' . date('d/m/Y') . '</span>
                </div>
            </div>
            <div class="course-info">
                <div class="info-item"><strong>Ciclo lectivo:</strong> ' . $academic_period . '</div>
            </div>
        </div>';

        // Paginación de estudiantes
        for ($page = 1; $page <= $totalPages; $page++) {
            if ($page > 1) {
                $html .= '<div class="page-break"></div>';
            }
            $html .= $header;
            
            $html .= '<div class="container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Apellido</th>
                                    <th>Nombre</th>
                                    <th>Género</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th>Usuario</th>
                                    <th>Fecha Nac.</th>
                                    <th>Motivo</th>
                                </tr>
                            </thead>
                            <tbody>';

            // Calcular el rango de estudiantes para esta página
            $startIndex = ($page - 1) * $studentsPerPage;
            $studentsOnPage = array_slice($student_data, $startIndex, $studentsPerPage);

            foreach ($studentsOnPage as $student) {
                $birthday = date('d/m/Y', strtotime($student['birthday']));
                
                $html .= '<tr>
                            <td class="text-left">' . $student['lastname'] . '</td>
                            <td class="text-left">' . $student['firstname'] . '</td>
                            <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                            <td>' . $student['dni'] . '</td>
                            <td>' . htmlspecialchars($student['email']) . '</td>
                            <td>' . $student['username'] . '</td>
                            <td>' . $birthday . '</td>
                            <td>' . ucfirst(get_phrase($student['status_reason'])) . '</td>
                        </tr>';
            }

            $html .= '</tbody></table></div>';

            // Pie de página con el número de página
            $html .= '<div class="page-footer">
                        <strong>Página ' . $page . ' de ' . $totalPages . '</strong>
                    </div>';
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        // Mostrar el HTML
        echo $html;
    }


    public function printStudentAdmissionsTableEN()
    {
        $student_data = $this->crud_model->get_students_admissions();
        $academic_period = $this->crud_model->get_active_academic_period_name();
    
        // Número máximo de estudiantes por página
        $studentsPerPage = 16;
        
        // Calcular el número de páginas necesarias
        $totalPages = ceil(count($student_data) / $studentsPerPage);
    
        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>&nbsp;</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                 body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 10px 10px 10px 10px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: center; 
                    align-items: center; 
                    text-align: center;   
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                .info-item {
                    display: inline-block;
                    width: 23%;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .table td:nth-child(5), .table td:nth-child(6) {
                    word-wrap: break-word;
                    word-break: break-all;
                    max-width: 150px;
                }
                .text-left {
                    text-align: left;
                }
                .page-footer {
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                .page-break {
                    page-break-after: always;
                }

                @media print {
                    @page { margin: 0;
                    size: auto; }
                }
            </style>
        </head>
        <body>';
    
        // Encabezado común en todas las páginas
        $header = '
        <div class="page-header">
                    <div class="header-top">
                        <div class="logo-container">
                            <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                        </div>
                        <div class="report-title">
                            <strong> Student report in admissions </strong> <br>
                            <span style="font-size: 14px;">' . date('d/m/Y') . '</span>
                        </div>
                    </div>
                    <div class="course-info">
                        <div><strong>Academic period:</strong> ' . $academic_period . '</div>
                    </div>
                </div>
                ';
    
        // Paginación de estudiantes
        for ($page = 1; $page <= $totalPages; $page++) {
            $html .= $header; // Agregar el encabezado en cada página
            $html .= '<div class="container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Lastname</th>
                                    <th>Firstname</th>
                                    <th>Gender</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>B. Date</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>';
    
            // Agregar los estudiantes correspondientes a la página actual
            $startIndex = ($page - 1) * $studentsPerPage;
            $endIndex = min($startIndex + $studentsPerPage, count($student_data));
            for ($i = $startIndex; $i < $endIndex; $i++) {
                $student = $student_data[$i];
                $birthday = date('d/m/Y', strtotime($student['birthday']));
    
                $html .= '<tr>
                            <td class="text-left">' . $student['lastname'] . '</td>
                            <td class="text-left">' . $student['firstname'] . '</td>
                            <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                            <td>' . $student['dni'] . '</td>
                            <td>' . htmlspecialchars($student['email']) . '</td>
                            <td>' . $student['username'] . '</td>
                            <td>' . $birthday . '</td>
                            <td>' . ucfirst(get_phrase($student['status_reason'])) . '</td>
                        </tr>';
            }
    
            $html .= '</tbody></table></div>';
    
            // Pie de página con número de página
            $html .= '<div class="page-footer">
                        <strong> Page ' . $page . ' of ' . $totalPages . ' </strong>
                    </div>';
    
            // Divisor de página (agrega un salto de página en la impresión)
            if ($page < $totalPages) {
                $html .= '<div style="page-break-after: always;"></div>';
            }
        }
    
        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';
    
        // Mostrar el HTML
        echo $html;
    }

    public function printStudentPreEnrollmentsTableES()
    {
        // Obtener datos de los estudiantes y la sección
        $student_data = $this->crud_model->get_students_pre_enrollments();
        $academic_period = $this->crud_model->get_active_academic_period_name();

        $studentsPerPage = 13;
        $totalPages = ceil(count($student_data) / $studentsPerPage);

        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>&nbsp;</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 10px 10px 10px 10px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: center; 
                    align-items: center; 
                    text-align: center;   
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                .info-item {
                    display: inline-block;
                    width: 23%;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .table td:nth-child(5), .table td:nth-child(6) {
                    word-wrap: break-word;
                    word-break: break-all;
                    max-width: 150px;
                }
                .text-left {
                    text-align: left;
                }
                .page-footer {
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                .page-break {
                    page-break-after: always;
                }

                @media print {
                    @page { margin: 0;
                    size: auto; }
                }
            </style>
        </head>
        <body>';

        // Generar encabezado común
        $header = '
        <div class="page-header">
            <div class="header-top">
                <div class="logo-container">
                    <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                </div>
                <div class="report-title">
                    <strong>Reporte de Estudiantes en Matriculación</strong> <br>
                    <span style="font-size: 16px;">' . date('d/m/Y') . '</span>
                </div>
            </div>
            <div class="course-info">
                <div class="info-item"><strong>Ciclo lectivo:</strong> ' . $academic_period . '</div>
            </div>
        </div>';

        // Paginación de estudiantes
        for ($page = 1; $page <= $totalPages; $page++) {
            if ($page > 1) {
                $html .= '<div class="page-break"></div>';
            }
            $html .= $header;
            
            $html .= '<div class="container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Apellido</th>
                                    <th>Nombre</th>
                                    <th>Género</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th>Usuario</th>
                                    <th>Fecha Nac.</th>
                                </tr>
                            </thead>
                            <tbody>';

            // Calcular el rango de estudiantes para esta página
            $startIndex = ($page - 1) * $studentsPerPage;
            $studentsOnPage = array_slice($student_data, $startIndex, $studentsPerPage);

            foreach ($studentsOnPage as $student) {
                $birthday = date('d/m/Y', strtotime($student['birthday']));
                
                $html .= '<tr>
                            <td class="text-left">' . $student['lastname'] . '</td>
                            <td class="text-left">' . $student['firstname'] . '</td>
                            <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                            <td>' . $student['dni'] . '</td>
                            <td>' . htmlspecialchars($student['email']) . '</td>
                            <td>' . $student['username'] . '</td>
                            <td>' . $birthday . '</td>
                        </tr>';
            }

            $html .= '</tbody></table></div>';

            // Pie de página con el número de página
            $html .= '<div class="page-footer">
                        <strong>Página ' . $page . ' de ' . $totalPages . '</strong>
                    </div>';
        }

        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';

        // Mostrar el HTML
        echo $html;
    }
        

    public function printStudentPreEnrollmentsTableEN()
    {
        $student_data = $this->crud_model->get_students_pre_enrollments();
        $academic_period = $this->crud_model->get_active_academic_period_name();
    
        // Número máximo de estudiantes por página
        $studentsPerPage = 16;
        
        // Calcular el número de páginas necesarias
        $totalPages = ceil(count($student_data) / $studentsPerPage);
    
        // Generar el HTML para el reporte
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>&nbsp;</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="' . base_url('assets/css/bootstrap.css') . '">
            <style>
                 body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                }
                .page-header{
                    margin-top: 0px;
                }
                .page-header, .page-footer {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 10px 10px 10px 10px;
                    border-bottom: 2px solid #ddd;
                }
                .logo-container img {
                    height: 60px !important;
                }
                .report-title {
                    font-size: 15px;
                    font-weight: bold;
                }
                .course-info {
                    font-size: 14px;
                    display: flex;
                    justify-content: center; 
                    align-items: center; 
                    text-align: center;   
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                .info-item {
                    display: inline-block;
                    width: 23%;
                }
                .container {
                    margin-top: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .table thead th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .table td:nth-child(5), .table td:nth-child(6) {
                    word-wrap: break-word;
                    word-break: break-all;
                    max-width: 150px;
                }
                .text-left {
                    text-align: left;
                }
                .page-footer {
                    font-size: 14px;
                    color: #555;
                    margin-top: 10px;
                }
                .page-break {
                    page-break-after: always;
                }

                @media print {
                    @page { margin: 0;
                    size: auto; }
                }
            </style>
        </head>
        <body>';
    
        // Encabezado común en todas las páginas
        $header = '
        <div class="page-header">
                    <div class="header-top">
                        <div class="logo-container">
                            <img src="' . base_url('assets/images/favicon2.png') . '" class="logo" alt="Logo">
                        </div>
                        <div class="report-title">
                            <strong> Student report in Enrollments </strong> <br>
                            <span style="font-size: 14px;">' . date('d/m/Y') . '</span>
                        </div>
                    </div>
                    <div class="course-info">
                        <div><strong>Academic period:</strong> ' . $academic_period . '</div>
                    </div>
                </div>
                ';
    
        // Paginación de estudiantes
        for ($page = 1; $page <= $totalPages; $page++) {
            $html .= $header; // Agregar el encabezado en cada página
            $html .= '<div class="container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Lastname</th>
                                    <th>Firstname</th>
                                    <th>Gender</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>B. Date</th>
                                </tr>
                            </thead>
                            <tbody>';
    
            // Agregar los estudiantes correspondientes a la página actual
            $startIndex = ($page - 1) * $studentsPerPage;
            $endIndex = min($startIndex + $studentsPerPage, count($student_data));
            for ($i = $startIndex; $i < $endIndex; $i++) {
                $student = $student_data[$i];
                $birthday = date('d/m/Y', strtotime($student['birthday']));
    
                $html .= '<tr>
                            <td class="text-left">' . $student['lastname'] . '</td>
                            <td class="text-left">' . $student['firstname'] . '</td>
                            <td>' . ucfirst(get_phrase($student['gender_id'] == 0 ? 'male' : ($student['gender_id'] == 1 ? 'female' : 'other'))) . '</td>
                            <td>' . $student['dni'] . '</td>
                            <td>' . htmlspecialchars($student['email']) . '</td>
                            <td>' . $student['username'] . '</td>
                            <td>' . $birthday . '</td>
                        </tr>';
            }
    
            $html .= '</tbody></table></div>';
    
            // Pie de página con número de página
            $html .= '<div class="page-footer">
                        <strong> Page ' . $page . ' of ' . $totalPages . ' </strong>
                    </div>';
    
            // Divisor de página (agrega un salto de página en la impresión)
            if ($page < $totalPages) {
                $html .= '<div style="page-break-after: always;"></div>';
            }
        }
    
        $html .= '<script>window.print();</script>';
        $html .= '</body></html>';
    
        // Mostrar el HTML
        echo $html;
    }
    




}