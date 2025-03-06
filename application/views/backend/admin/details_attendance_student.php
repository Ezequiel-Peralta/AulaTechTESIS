<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');

function translateDayToSpanish($day)
{
    $daysMap = array(
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sábado',
        'Sunday' => 'Domingo'
    );

    if (array_key_exists($day, $daysMap)) {
        return $daysMap[$day];
    } else {
        return $day;
    }
}

function translateMonthToSpanish($month)
{
    $monthsMap = array(
        'January' => 'Enero',
        'February' => 'Febrero',
        'March' => 'Marzo',
        'April' => 'Abril',
        'May' => 'Mayo',
        'June' => 'Junio',
        'July' => 'Julio',
        'August' => 'Agosto',
        'September' => 'Septiembre',
        'October' => 'Octubre',
        'November' => 'Noviembre',
        'December' => 'Diciembre'
    );

    if (array_key_exists($month, $monthsMap)) {
        return $monthsMap[$month];
    } else {
        return $month;
    }
}

function getStatusClass($status)
{
    switch ($status) {
        case 1:
            return 'presente'; // Presente
        case 2:
            return 'ausente'; // Ausente
        case 3:
            return 'tardanza'; // Tardanza
        case 4:
            return 'justificado'; // Ausencia justificada
        default:
            return 'no-tomada'; // No tomada
    }
}

?>



<?php
$student_info = $this->Student_model->get_student_info($student_id);
foreach ($student_info as $row): ?>

    <div class="profile-header" style="border-radius: 15px;">
        <img src="assets/images/photo-header.png" class="cover-photo" alt="Cover Photo">
        <img src="<?php echo $row['photo']; ?>" class="img-fluid" alt="Profile Picture" width="150" height="150">
        <div class="profile-info">
            <h2 style="font-weight: 600;">
                <?php echo $row['lastname']; ?>, <?php echo $row['firstname']; ?>.
            </h2>
        </div>
        <br>
    </div>
<?php endforeach; ?>

<div class="row">
    <div class="col-md-12">
        <div class="tile-stats tile-white title-info" style="margin-top: 20px; margin-bottom: 20px;">
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: 15px 15px; border: 1px solid #ebebeb; border-radius: 5px;">
                <h2 style="font-weight: 600; margin: 0;"><?php echo ucfirst(get_phrase('student_attendance')); ?> -
                    <?php echo ucfirst(get_phrase('percentage_graph')); ?></h2>
                <div class="sidebuttons text-right">

                </div>
            </div>

            <div class="row d-flex justify-content-center align-items-center" style="padding: 20px 50px 0px 50px;">
                <div class="col-md-3 viewType">
                    <div class="form-group text-center">
                        <select name="viewType" class="form-control text-center customSelect" id="viewType"
                            data-validate="required"
                            data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                            <option value="daily"><?php echo ucfirst(get_phrase('daily')); ?></option>
                            <option value="weekly"><?php echo ucfirst(get_phrase('weekly')); ?></option>
                            <option value="monthly"><?php echo ucfirst(get_phrase('monthly')); ?></option>
                            <option value="yearly"><?php echo ucfirst(get_phrase('yearly')); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 dailyView" style="display: none;">
                    <div class="form-group">
                        <input type="date" id="daily" class="form-control text-center customInput"
                            value="<?php echo $current_date; ?>">
                    </div>
                </div>
                <div class="col-md-4 weeklyView" style="display: none;">
                    <div class="form-group">
                        <input type="date" id="startDate" class="form-control text-center customInput"
                            value="<?php echo $current_week_start; ?>">
                    </div>
                </div>
                <div class="col-md-4 weeklyView" style="display: none;">
                    <div class="form-group">
                        <input type="date" id="endDate" class="form-control text-center customInput"
                            value="<?php echo $current_week_end; ?>">
                    </div>
                </div>
                <div class="col-md-6 monthlyView" style="display: none;">
                    <div class="form-group">
                        <select id="monthly" class="form-control text-center customSelect">
                            <?php
                            // Lista de meses en inglés
                            //SE SUPLANTA POR MONTHSENGLISH
                            foreach (MONTHSENGLISH as $month) {
                                $selected = ($month === $current_month_name) ? 'selected' : '';
                                echo "<option value='{$month}' {$selected}>{$month}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 yearlyView" style="display: none;">
                    <div class="form-group">
                        <select id="startDateYearly" class="form-control text-center customSelect">
                            <?php

                            foreach (MONTHSENGLISH as $month) {
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

                            foreach (MONTHSENGLISH as $month) {
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
                <button id="downloadPdf" class="btn buttons-html5 btn-white btn-sm btn-danger-hover"
                    title="<?php echo ucfirst(get_phrase('download_pdf')); ?>"><i class="fa fa-file-pdf-o"></i>
                    PDF</button>
                <button id="downloadPng" class="btn buttons-html5 btn-white btn-sm btn-green-hover"
                    title="<?php echo ucfirst(get_phrase('download_png')); ?>"><i class="fa fa-file-image-o"></i>
                    PNG</button>
                <button id="downloadJpeg" class="btn buttons-html5 btn-white btn-sm btn-orange-hover"
                    title="<?php echo ucfirst(get_phrase('download_jpeg')); ?>"><i class="fa fa-file-image-o"></i>
                    JPEG</button>
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
                <span id="presentes" class="btn btn-green btn-icon icon-left"
                    style="background-color: #55FFA8 !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('students_present')); ?>&nbsp; <span
                        id="presentes-count"><?php echo $attendance_student_presente ?></span>
                    <i style="color: white !important; padding-top: 10px;">P</i>
                </span>
                <span id="ausentes" class="btn btn-danger btn-icon icon-left"
                    style="background-color: #FF6C6C !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('absences')); ?>&nbsp; <span
                        id="ausentes-count"><?php echo $attendance_student_ausente ?></span>
                    <i style="color: white !important; padding-top: 10px;">A</i>
                </span>
                <span id="tardanzas" class="btn btn-orange btn-icon icon-left"
                    style="background-color: #FFBB5A !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('tardies')); ?>&nbsp; <span
                        id="tardanzas-count"><?php echo $attendance_student_tardanza ?></span>
                    <i style="color: white !important; padding-top: 10px;">T</i>
                </span>
                <span id="justificados" class="btn btn-blue btn-icon icon-left"
                    style="background-color: #52BBFF !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('justified_absences')); ?>&nbsp; <span
                        id="justificados-count"><?php echo $attendance_student_ausencia_justificada ?></span>
                    <i style="color: white !important; padding-top: 10px;">AJ</i>
                </span>
            </div>
        </div>
    </div>





    <div class="col-md-12">
        <div class="tile-stats tile-white title-info" style="margin-top: 20px; margin-bottom: 20px;">
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: 15px 15px; border: 1px solid #ebebeb; border-radius: 5px;">
                <h2 style="font-weight: 600; margin: 0;"><?php echo ucfirst(get_phrase('student_attendance')); ?> -
                    <?php echo ucfirst(get_phrase('quantity_graph')); ?></h2>
                <div class="sidebuttons text-right">

                </div>
            </div>

            <div class="row d-flex justify-content-center align-items-center" style="padding: 20px 50px 0px 50px;">
                <div class="col-md-3 viewType2">
                    <div class="form-group text-center">
                        <select name="viewType2" class="form-control text-center customSelect" id="viewType2"
                            data-validate="required"
                            data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                            <option value="daily"><?php echo ucfirst(get_phrase('daily')); ?></option>
                            <option value="weekly"><?php echo ucfirst(get_phrase('weekly')); ?></option>
                            <option value="monthly"><?php echo ucfirst(get_phrase('monthly')); ?></option>
                            <option value="yearly"><?php echo ucfirst(get_phrase('yearly')); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 dailyView2" style="display: none;">
                    <div class="form-group">
                        <input type="date" id="daily2" class="form-control text-center customInput"
                            value="<?php echo $current_date; ?>">
                    </div>
                </div>
                <div class="col-md-4 weeklyView2" style="display: none;">
                    <div class="form-group">
                        <input type="date" id="startDate2" class="form-control text-center customInput"
                            value="<?php echo $current_week_start; ?>">
                    </div>
                </div>
                <div class="col-md-4 weeklyView2" style="display: none;">
                    <div class="form-group">
                        <input type="date" id="endDate2" class="form-control text-center customInput"
                            value="<?php echo $current_week_end; ?>">
                    </div>
                </div>
                <div class="col-md-6 monthlyView2" style="display: none;">
                    <div class="form-group">
                        <select id="monthly2" class="form-control text-center customSelect">
                            <?php
                            // Lista de meses en inglés
                            
                            foreach (MONTHSENGLISH as $month) {
                                $selected = ($month === $current_month_name) ? 'selected' : '';
                                echo "<option value='{$month}' {$selected}>{$month}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 yearlyView2" style="display: none;">
                    <div class="form-group">
                        <select id="startDateYearly2" class="form-control text-center customSelect">
                            <?php

                            foreach (MONTHSENGLISH as $month) {
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

                            foreach (MONTHSENGLISH as $month) {
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
                <button id="downloadPdf2" class="btn buttons-html5 btn-white btn-sm btn-danger-hover"
                    title="<?php echo ucfirst(get_phrase('download_pdf')); ?>"><i class="fa fa-file-pdf-o"></i>
                    PDF</button>
                <button id="downloadPng2" class="btn buttons-html5 btn-white btn-sm btn-green-hover"
                    title="<?php echo ucfirst(get_phrase('download_png')); ?>"><i class="fa fa-file-image-o"></i>
                    PNG</button>
                <button id="downloadJpeg2" class="btn buttons-html5 btn-white btn-sm btn-orange-hover"
                    title="<?php echo ucfirst(get_phrase('download_jpeg')); ?>"><i class="fa fa-file-image-o"></i>
                    JPEG</button>
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
                <span id="presentes" class="btn btn-green btn-icon icon-left"
                    style="background-color: #55FFA8 !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('students_present')); ?>&nbsp; <span
                        id="presentes-count2"><?php echo $attendance_student_presente ?></span>
                    <i style="color: white !important; padding-top: 10px;">P</i>
                </span>
                <span id="ausentes" class="btn btn-danger btn-icon icon-left"
                    style="background-color: #FF6C6C !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('absences')); ?>&nbsp; <span
                        id="ausentes-count2"><?php echo $attendance_student_ausente ?></span>
                    <i style="color: white !important; padding-top: 10px;">A</i>
                </span>
                <span id="tardanzas" class="btn btn-orange btn-icon icon-left"
                    style="background-color: #FFBB5A !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('tardies')); ?>&nbsp; <span
                        id="tardanzas-count2"><?php echo $attendance_student_tardanza ?></span>
                    <i style="color: white !important; padding-top: 10px;">T</i>
                </span>
                <span id="justificados" class="btn btn-blue btn-icon icon-left"
                    style="background-color: #52BBFF !important; color: #265044 !important; font-weight: 600 !important; padding: 10px 30px; border-radius: 5px;">
                    <?php echo ucfirst(get_phrase('justified_absences')); ?>&nbsp; <span
                        id="justificados-count2"><?php echo $attendance_student_ausencia_justificada ?></span>
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
                        <?php echo $count_attendance_data; ?>
                    </span>
                </a>
            </li>

        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover"
                        title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i
                            class="fa fa-refresh"></i></button>
                    <div class="pull-right">

                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="all_student_table">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo ucfirst(get_phrase('type')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('observation')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($attendance_data)): ?>
                            <?php foreach ($attendance_data as $row): ?>
                                <tr>
                                    <td class="text-center">
                                        <?php
                                        switch ($row['status']) {
                                            case 1:
                                                echo '<span style="background-color: #55FFA8 !important; font-weight: 600 !important; color: #265044 !important; padding: 5px 10px 5px 10px; border-radius: 10px !important;">' . ucfirst(get_phrase('present')) . '</span>';
                                                break;
                                            case 2:
                                                echo '<span style="background-color: #FF6C6C !important; font-weight: 600 !important; color: #265044 !important; padding: 5px 10px 5px 10px; border-radius: 10px !important;">' . ucfirst(get_phrase('absent')) . '</span>';
                                                break;
                                            case 3:
                                                echo '<span style="background-color: #FFBB5A !important; font-weight: 600 !important; color: #265044 !important; padding: 5px 10px 5px 10px; border-radius: 10px !important;">' . ucfirst(get_phrase('tardy')) . '</span>';
                                                break;
                                            case 4:
                                                echo '<span style="background-color: #52BBFF !important; font-weight: 600 !important; color: #265044 !important; padding: 5px 10px 5px 10px; border-radius: 10px !important;">' . ucfirst(get_phrase('justified_absence')) . '</span>';
                                                break;
                                            default:
                                                echo '<span style="background-color: #cccccc !important; font-weight: 600 !important; color: #265044 !important; padding: 5px 10px 5px 10px; border-radius: 10px !important;">' . ucfirst(get_phrase('not_taken')) . '</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center" data-order="<?php echo $row['date']; ?>">
                                        <?php
                                        $englishDay = date('l', strtotime($row['date']));
                                        echo ucfirst(get_phrase($englishDay));
                                        ?>
                                        <?php echo date('j', strtotime($row['date'])); ?>
                                        <?php echo ucfirst(get_phrase('of')); ?>
                                        <?php
                                        $englishMonth = date('F', strtotime($row['date']));
                                        echo ucfirst(get_phrase($englishMonth));
                                        ?>
                                        <?php echo ucfirst(get_phrase('of')); ?>
                                        <?php echo date('Y', strtotime($row['date'])); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo !empty($row['observation']) ? $row['observation'] : ''; ?></td>
                                    <td class="text-center">
                                        <a href="javascript:;" class="btn btn-table btn-white btn-info-hover"
                                            title="<?php echo ucfirst(get_phrase('edit')); ?>"
                                            onclick="showAjaxModal('<?php echo base_url(); ?>index.php?modal/popup/modal_edit_attendance_student/<?php echo $student_id; ?>/<?php echo $row['date']; ?>');">
                                            <i class="entypo-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>


        </div>


    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        var languagePreference = '<?php echo $this->session->userdata('language_preference'); ?>';

        var $allStudentDataTable = jQuery("#all_student_table");

        var rowCount = $('#all_student_table tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : "";

        if (languagePreference === 'english') {
            var allStudentDataTable = $allStudentDataTable.DataTable({
                "order": [[0, "asc"]],
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
                        filename: '',
                        title: null,
                        exportOptions: {
                            columns: ':not(:eq(0)):not(:eq(6)):not(:eq(15))'
                        }
                    },
                    {
                        text: '<i class="fa fa-print"></i> Print / <i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover',
                        action: function (e, dt, node, config) {
                            window.location.href = '<?php echo base_url(); ?>index.php?admin/printStudentTableEN/';
                        }
                    }
                ],
                colReorder: true,
                initComplete: function () {
                    $('#all_student_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var allStudentDataTable = $allStudentDataTable.DataTable({
                "order": [[1, "desc"]],
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
                        filename: '',
                        title: null,
                        exportOptions: {
                            columns: ':not(:eq(0)):not(:eq(6)):not(:eq(15))'
                        }
                    },
                    {
                        text: '<i class="fa fa-print"></i> Imprimir / <i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover',
                        action: function (e, dt, node, config) {
                            window.location.href = '<?php echo base_url(); ?>index.php?admin/printStudentTableES/';
                        }
                    }
                ],
                colReorder: true,
                initComplete: function () {
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
                { value: '<?php echo $attendance_student_presente; ?>', label: '<?php echo ucfirst(get_phrase('students_present')); ?>', formatted: '<?php echo $percentage_presente; ?>%' },
                { value: '<?php echo $attendance_student_ausente; ?>', label: '<?php echo ucfirst(get_phrase('absences')); ?>', formatted: '<?php echo $percentage_ausente; ?>%' },
                { value: '<?php echo $attendance_student_tardanza; ?>', label: '<?php echo ucfirst(get_phrase('tardies')); ?>', formatted: '<?php echo $percentage_tardanza; ?>%' },
                { value: '<?php echo $attendance_student_ausencia_justificada; ?>', label: '<?php echo ucfirst(get_phrase('justified_absences')); ?>', formatted: '<?php echo $percentage_justificados; ?>%' }
            ],
            formatter: function (x, data) { return data.formatted; },
            labelColor: 'black',
            colors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF']
        });


        Morris.Bar({
            element: 'chartAttendance2',
            axes: true,
            data: [
                { x: '<?php echo $current_date_formatted; ?>', y: <?php echo $attendance_student_presente; ?>, z: <?php echo $attendance_student_ausente; ?>, a: <?php echo $attendance_student_tardanza; ?>, e: <?php echo $attendance_student_ausencia_justificada; ?> },
            ],
            xkey: 'x',
            ykeys: ['y', 'z', 'a', 'e'],
            labels: ['<?php echo ucfirst(get_phrase('present')); ?>', '<?php echo ucfirst(get_phrase('absent')); ?>', '<?php echo ucfirst(get_phrase('tardy')); ?>', '<?php echo ucfirst(get_phrase('justified_absence')); ?>'],
            barColors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF']
        });







    });


</script>

<script type="text/javascript">

    function downloadChart(format, chartContainerId, viewTypeId, dataSuffix) {
        setTimeout(() => {
            const chartContainer = document.getElementById(chartContainerId);
            const viewType = document.getElementById(viewTypeId).value;

            const data = {
                daily: document.getElementById(`daily${dataSuffix}`).value,
                startDate: document.getElementById(`startDate${dataSuffix}`).value,
                endDate: document.getElementById(`endDate${dataSuffix}`).value,
                monthly: document.getElementById(`monthly${dataSuffix}`).value,
                startDateYearly: document.getElementById(`startDateYearly${dataSuffix}`).value,
                endDateYearly: document.getElementById(`endDateYearly${dataSuffix}`).value
            };

            const { viewTypeText, viewTypeDetails } = getViewTypeDetails(viewType, data);

            // Obtener los valores de los spans
            const presentesCount = parseInt(document.getElementById(`presentes-count${dataSuffix}`).textContent, 10);
            const ausentesCount = parseInt(document.getElementById(`ausentes-count${dataSuffix}`).textContent, 10);
            const tardanzasCount = parseInt(document.getElementById(`tardanzas-count${dataSuffix}`).textContent, 10);
            const justificadosCount = parseInt(document.getElementById(`justificados-count${dataSuffix}`).textContent, 10);

            const totalCount = presentesCount + ausentesCount + tardanzasCount + justificadosCount;

            // Calcular los porcentajes
            const presentesPercent = ((presentesCount / totalCount) * 100).toFixed(2);
            const ausentesPercent = ((ausentesCount / totalCount) * 100).toFixed(2);
            const tardanzasPercent = ((tardanzasCount / totalCount) * 100).toFixed(2);
            const justificadosPercent = ((justificadosCount / totalCount) * 100).toFixed(2);

            if (chartContainer) {
                html2canvas(chartContainer).then(canvas => {
                    const imgData = canvas.toDataURL(`image/${format}`);

                    const textCanvas = document.createElement('canvas');
                    const ctx = textCanvas.getContext('2d');
                    textCanvas.width = canvas.width;
                    textCanvas.height = canvas.height + 160; // Más espacio para texto (ajusta según sea necesario)
                    ctx.drawImage(canvas, 0, 0);

                    ctx.font = "16px Helvetica";
                    ctx.fillText("<?php echo ucfirst(get_phrase('student_attendance')); ?> <?php echo $student_lastname_firstname; ?> - <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('percentage_graph')); ?>", 10, 20);
                    ctx.font = "12px Helvetica";
                    ctx.fillText(`<?php echo ucfirst(get_phrase('view_type')); ?>: ${viewTypeText}`, 10, canvas.height + 40);
                    ctx.fillText(`<?php echo ucfirst(get_phrase('details')); ?>: ${viewTypeDetails}`, 10, canvas.height + 60);
                    ctx.fillText(`<?php echo ucfirst(get_phrase('students_present')); ?>: ${presentesCount} (${presentesPercent}%)`, 10, canvas.height + 80);
                    ctx.fillText(`<?php echo ucfirst(get_phrase('absences')); ?>: ${ausentesCount} (${ausentesPercent}%)`, 10, canvas.height + 100);
                    ctx.fillText(`<?php echo ucfirst(get_phrase('tardies')); ?>: ${tardanzasCount} (${tardanzasPercent}%)`, 10, canvas.height + 120);
                    ctx.fillText(`<?php echo ucfirst(get_phrase('justified_absences')); ?>: ${justificadosCount} (${justificadosPercent}%)`, 10, canvas.height + 140);

                    const finalImgData = textCanvas.toDataURL(`image/${format}`);

                    if (format === 'pdf') {
                        const { jsPDF } = window.jspdf;
                        const pdf = new jsPDF();
                        const pdfWidth = pdf.internal.pageSize.getWidth();
                        const pdfHeight = (textCanvas.height * pdfWidth) / textCanvas.width;

                        pdf.addImage(finalImgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                        pdf.save(`<?php echo ucfirst(get_phrase('student_attendance')); ?> <?php echo $student_lastname_firstname; ?> - <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('percentage_graph')); ?> - <?php echo $current_date_formatted; ?>.pdf`);
                    } else {
                        const link = document.createElement('a');
                        link.href = finalImgData;
                        link.download = `<?php echo ucfirst(get_phrase('student_attendance')); ?> <?php echo $student_lastname_firstname; ?> - <?php echo $section_name; ?> - <?php echo ucfirst(get_phrase('percentage_graph')); ?> - <?php echo $current_date_formatted; ?>.${format}`;
                        link.click();
                    }
                });
            }
        }, 500);
    }

    function getViewTypeDetails(viewType, data) {
        let viewTypeText = "";
        let viewTypeDetails = "";

        switch (viewType) {
            case 'daily':
                viewTypeText = '<?php echo ucfirst(get_phrase('daily')); ?>';
                let dailyDate = data.daily;
                let dateParts = dailyDate.split('-');
                let formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
                viewTypeDetails = formattedDate;
                break;

            case 'weekly':
                viewTypeText = '<?php echo ucfirst(get_phrase('weekly')); ?>';
                const startDate = data.startDate;
                const endDate = data.endDate;

                let startDateParts = startDate.split('-');
                let formattedStartDate = `${startDateParts[2]}/${startDateParts[1]}/${startDateParts[0]}`;

                let endDateParts = endDate.split('-');
                let formattedEndDate = `${endDateParts[2]}/${endDateParts[1]}/${endDateParts[0]}`;

                viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${formattedStartDate} <?php echo get_phrase('to'); ?> ${formattedEndDate}`;
                break;

            case 'monthly':
                viewTypeText = '<?php echo ucfirst(get_phrase('monthly')); ?>';
                const month = data.monthly;

                let translatedMonth = monthTranslations[month] || month;
                viewTypeDetails = `${translatedMonth}`; // Solo 'from' porque se selecciona un mes
                break;

            case 'yearly':
                viewTypeText = '<?php echo ucfirst(get_phrase('yearly')); ?>'; // Usar traducción para 'yearly'
                const startMonth = data.startDateYearly;
                const endMonth = data.endDateYearly;

                let translatedStartDate = monthTranslations[startMonth] || startMonth;
                let translatedEndMonth = monthTranslations[endMonth] || endMonth;

                viewTypeDetails = `<?php echo ucfirst(get_phrase('from')); ?> ${translatedStartDate} <?php echo get_phrase('to'); ?> ${translatedEndMonth}`; // Usar las palabras "from" y "to" traducidas
                break;
        }

        return {
            viewTypeText,
            viewTypeDetails
        };
    }

    function reload_ajax() {
        location.reload();
    }

    const monthTranslations = {
        'January': '<?php echo ucfirst(get_phrase("january")); ?>',
        'February': '<?php echo ucfirst(get_phrase("february")); ?>',
        'March': '<?php echo ucfirst(get_phrase("march")); ?>',
        'April': '<?php echo ucfirst(get_phrase("april")); ?>',
        'May': '<?php echo ucfirst(get_phrase("may")); ?>',
        'June': '<?php echo ucfirst(get_phrase("june")); ?>',
        'July': '<?php echo ucfirst(get_phrase("july")); ?>',
        'August': '<?php echo ucfirst(get_phrase("august")); ?>',
        'September': '<?php echo ucfirst(get_phrase("september")); ?>',
        'October': '<?php echo ucfirst(get_phrase("october")); ?>',
        'November': '<?php echo ucfirst(get_phrase("november")); ?>',
        'December': '<?php echo ucfirst(get_phrase("december")); ?>'
    };


    $(document).ready(function () {

        document.getElementById('downloadPdf').addEventListener('click', function () {
            downloadChart('pdf', 'chartContainer', 'viewType', '');
        });

        document.getElementById('downloadPng').addEventListener('click', function () {
            downloadChart('png', 'chartContainer', 'viewType', '');
        });

        document.getElementById('downloadJpeg').addEventListener('click', function () {
            downloadChart('jpeg', 'chartContainer', 'viewType', '');
        });

        document.getElementById('downloadPdf2').addEventListener('click', function () {
            downloadChart('pdf', 'chartContainer2', 'viewType2', '2');
        });

        document.getElementById('downloadPng2').addEventListener('click', function () {
            downloadChart('png', 'chartContainer2', 'viewType2', '2');
        });

        document.getElementById('downloadJpeg2').addEventListener('click', function () {
            downloadChart('jpeg', 'chartContainer2', 'viewType2', '2');
        });


        // Mostrar/Ocultar las vistas según la selección
        $('#viewType').on('change', function () {
            const selected = $(this).val();
            $('.dailyView, .weeklyView, .monthlyView, .yearlyView').hide(); // Ocultar todas las vistas

            const viewTypeContainer = $('.viewType');

            switch (selected) {
                case 'daily':
                    $('.dailyView').fadeIn();
                    viewTypeContainer.removeClass('col-md-3 col-md-4').addClass('col-md-6');
                    break;
                case 'weekly':
                    $('.weeklyView').fadeIn();
                    viewTypeContainer.removeClass('col-md-3 col-md-6').addClass('col-md-4');
                    break;
                case 'monthly':
                    $('.monthlyView').fadeIn();
                    viewTypeContainer.removeClass('col-md-3 col-md-4').addClass('col-md-6');
                    break;
                case 'yearly':
                    $('.yearlyView').fadeIn();
                    viewTypeContainer.removeClass('col-md-3 col-md-6').addClass('col-md-4');
                    break;
            }
        });

        // Mostrar la vista diaria por defecto
        $('#viewType').trigger('change');

        $('#viewType2').on('change', function () {
            const selected = $(this).val();
            $('.dailyView2, .weeklyView2, .monthlyView2, .yearlyView2').hide(); // Ocultar todas las vistas

            const viewTypeContainer2 = $('.viewType2');

            switch (selected) {
                case 'daily':
                    $('.dailyView2').fadeIn();
                    viewTypeContainer2.removeClass('col-md-3 col-md-4').addClass('col-md-6');
                    break;
                case 'weekly':
                    $('.weeklyView2').fadeIn();
                    viewTypeContainer2.removeClass('col-md-3 col-md-6').addClass('col-md-4');
                    break;
                case 'monthly':
                    $('.monthlyView2').fadeIn();
                    viewTypeContainer2.removeClass('col-md-3 col-md-4').addClass('col-md-6');
                    break;
                case 'yearly':
                    $('.yearlyView2').fadeIn();
                    viewTypeContainer2.removeClass('col-md-3 col-md-6').addClass('col-md-4');
                    break;
            }
        });

        // Mostrar la vista diaria por defecto
        $('#viewType2').trigger('change');


        $('#applyFilters').on('click', function () {
            console.log("Click en apply filters");
            const student_id = <?php echo $student_id; ?>;
            const filter_type = $('#viewType').val();
            let date = $('#daily').val();
            let start_date = $('#startDate').val();
            let end_date = $('#endDate').val();
            let dateMoth = $('#monthly').val();
            let start_date_yearly = $('#startDateYearly').val();
            let end_date_yearly = $('#endDateYearly').val();

            switch (filter_type) {
                case 'daily':
                    date = date || 'null';
                    start_date = 'null';
                    end_date = 'null';
                    start_date_yearly = 'null';
                    end_date_yearly = 'null';
                    break;
                case 'weekly':
                    date = 'null';
                    start_date = start_date || 'null';
                    end_date = end_date || 'null';
                    start_date_yearly = 'null';
                    end_date_yearly = 'null';
                    break;
                case 'monthly':
                    date = 'null';
                    start_date = 'null';
                    end_date = 'null';
                    dateMoth = dateMoth || 'null';
                    start_date_yearly = 'null';
                    end_date_yearly = 'null';
                    break;
                case 'yearly':
                    date = 'null';
                    start_date = 'null';
                    end_date = 'null';
                    dateMoth = 'null';
                    start_date_yearly = start_date_yearly || 'null';
                    end_date_yearly = end_date_yearly || 'null';
                    break;
            }

            const url = `<?php echo base_url("index.php?admin/filter_attendance_student"); ?>/${student_id}/${filter_type}/${date}/${start_date}/${end_date}/${dateMoth}/${start_date_yearly}/${end_date_yearly}`;

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
                            { value: attendance_presente, label: '<?php echo ucfirst(get_phrase('students_present')); ?>', formatted: percentage_presente + '%' },
                            { value: attendance_ausente, label: '<?php echo ucfirst(get_phrase('absences')); ?>', formatted: percentage_ausente + '%' },
                            { value: attendance_tardanza, label: '<?php echo ucfirst(get_phrase('tardies')); ?>', formatted: percentage_tardanza + '%' },
                            { value: attendance_ausencia_justificada, label: '<?php echo ucfirst(get_phrase('justified_absences')); ?>', formatted: percentage_justificados + '%' }
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
            const student_id = <?php echo $student_id; ?>;
            const filter_type = $('#viewType2').val();
            let date = $('#daily2').val();
            let start_date = $('#startDate2').val();
            let end_date = $('#endDate2').val();
            let dateMoth = $('#monthly2').val();
            let start_date_yearly = $('#startDateYearly2').val();
            let end_date_yearly = $('#endDateYearly2').val();

            switch (filter_type) {
                case 'daily':
                    date = date || 'null';
                    start_date = 'null';
                    end_date = 'null';
                    dateMoth = 'null';
                    start_date_yearly = 'null';
                    end_date_yearly = 'null';
                    break;
                case 'weekly':
                    date = 'null';
                    start_date = start_date || 'null';
                    end_date = end_date || 'null';
                    dateMoth = 'null';
                    start_date_yearly = 'null';
                    end_date_yearly = 'null';
                    break;
                case 'monthly':
                    date = 'null';
                    start_date = 'null';
                    end_date = 'null';
                    dateMoth = dateMoth || 'null';
                    start_date_yearly = 'null';
                    end_date_yearly = 'null';
                    break;
                case 'yearly':
                    date = 'null';
                    start_date = 'null';
                    end_date = 'null';
                    dateMoth = 'null';
                    start_date_yearly = start_date_yearly || 'null';
                    end_date_yearly = end_date_yearly || 'null';
                    break;
            }

            const urlBase = `<?php echo base_url("index.php?admin/filter_attendance_student"); ?>/${student_id}`;

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
                        labels: ['<?php echo ucfirst(get_phrase('present')); ?>', '<?php echo ucfirst(get_phrase('absent')); ?>', '<?php echo ucfirst(get_phrase('tardy')); ?>', '<?php echo ucfirst(get_phrase('justified_absence')); ?>'],
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
                            labels: ['<?php echo ucfirst(get_phrase('present')); ?>', '<?php echo ucfirst(get_phrase('absent')); ?>', '<?php echo ucfirst(get_phrase('tardy')); ?>', '<?php echo ucfirst(get_phrase('justified_absence')); ?>'],
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
                            labels: ['<?php echo ucfirst(get_phrase('present')); ?>', '<?php echo ucfirst(get_phrase('absent')); ?>', '<?php echo ucfirst(get_phrase('tardy')); ?>', '<?php echo ucfirst(get_phrase('justified_absence')); ?>'],
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
                            labels: ['<?php echo ucfirst(get_phrase('present')); ?>', '<?php echo ucfirst(get_phrase('absent')); ?>', '<?php echo ucfirst(get_phrase('tardy')); ?>', '<?php echo ucfirst(get_phrase('justified_absence')); ?>'],
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
                            labels: ['<?php echo ucfirst(get_phrase('present')); ?>', '<?php echo ucfirst(get_phrase('absent')); ?>', '<?php echo ucfirst(get_phrase('tardy')); ?>', '<?php echo ucfirst(get_phrase('justified_absence')); ?>'],
                            barColors: ['#55FFA8', '#FF6C6C', '#FFBB5A', '#52BBFF']
                        });
                    }
                });
            } if (filter_type === 'yearly') {


                let translatedStartDate = monthTranslations[start_date_yearly] || start_date_yearly;
                let translatedEndMonth = monthTranslations[end_date_yearly] || end_date_yearly;

    

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
                            labels: ['<?php echo ucfirst(get_phrase('present')); ?>', '<?php echo ucfirst(get_phrase('absent')); ?>', '<?php echo ucfirst(get_phrase('tardy')); ?>', '<?php echo ucfirst(get_phrase('justified_absence')); ?>'],
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
                            labels: ['<?php echo ucfirst(get_phrase('present')); ?>', '<?php echo ucfirst(get_phrase('absent')); ?>', '<?php echo ucfirst(get_phrase('tardy')); ?>', '<?php echo ucfirst(get_phrase('justified_absence')); ?>'],
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

    .customInput,
    .customSelect {
        background-color: var(--color-forty) !important;
        color: var(--color-primary) !important;
        border: 2px solid var(--color-secondary) !important;
        padding: 0px !important;
        border-radius: 5px !important;
        transition: all 0.3s ease !important;
        ;
    }

    .customInput:focus,
    .customSelect:focus {
        background-color: var(--color-white) !important;
        border-color: var(--color-terciary) !important;
        outline: none !important;
    }

    .customInput:hover,
    .customSelect:hover {
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
    }

    .profile-card:hover {
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
        margin: 0;
        /* Eliminar márgenes adicionales */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        /* Evita que el texto se desborde */
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

    }

    #all_student_table_filter input {
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
    }

    .selectContent .labelSelect {
        font-size: 20px;
        margin-top: 12px;
    }

    .selectContent .selectElement {
        margin-top: 12px;
        background-color: #fff;
    }
</style>