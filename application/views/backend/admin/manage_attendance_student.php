<hr />
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th><?php echo ('Seleccionar fecha'); ?></th>
            <th><?php echo ('Seleccionar curso'); ?></th>
            <th><?php echo ('Seleccionar division'); ?></th>
        </tr>
    </thead>
    <tbody>
        <form method="post" action="<?php echo base_url(); ?>index.php?admin/manage_attendances_student_selector" class="form">
            <tr class="gradeA">
                <td>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="entypo-calendar"></i></span>
                        <input type="text" name="date" class="form-control" value="<?php echo isset($date) ? $date . '/' . $month . '/' . $year : ''; ?>">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="entypo-graduation-cap"></i></span>
                        <select name="class_id" id="class_select" class="form-control">
                            <option value="">Seleccionar</option>
                            <?php
                            $class_id_selected = $this->db->get_where('section', ['section_id' => $section_id])->row('class_id');
                            $classes = $this->db->get('class')->result_array();
                            foreach ($classes as $row) : ?>
                                <option value="<?php echo $row['class_id']; ?>" <?php if ($class_id_selected == $row['class_id']) echo 'selected="selected"'; ?>>
                                    <?php echo $row['name'] . '°'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="entypo-graduation-cap"></i></span>
                        <select name="section_id" class="form-control" id="section_selector">
                            <option value="">Seleccionar</option>
                            <?php
                            $sections = $this->db->get_where('section', array('class_id' => $class_id_selected))->result_array();
                            foreach ($sections as $section) {
                                echo '<option value="' . $section['section_id'] . '"';
                                if (isset($section_id) && $section_id == $section['section_id']) echo ' selected="selected"';
                                echo '>' . $section['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </td>
                <td align="center"><input type="submit" value="<?php echo ('Aceptar'); ?>" class="btn btn-info" /></td>
            </tr>
        </form>
    </tbody>
</table>

<table class="table table-calendar">
    <tr>
        <td width="20%" style="border-left: 0px solid #ebebeb !important; background-color: #fff !important; border-top-left-radius: 15px; border-bottom-left-radius: 15px;">
            <div class="calendar-sidebar">
                <div class="calendar-sidebar-row">
                    <ul class="list-unstyled list-calendar-elements">
                        <li><span class="color-dot orange"></span> Feriado</li>
                        <li><span class="color-dot yellow"></span> Día no laborable</li>
                    </ul>
                </div>
            </div>
        </td>
        <td width="80%" style="border-left: 5px solid #ebebeb !important; padding-left:40px !important; background-color: #fff !important; border-top-right-radius: 15px; border-bottom-right-radius: 15px;">
            <div class="calendar-env">
                <div class="calendar-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </td>
    </tr>
</table>
<hr />

<?php if ($date != '' && $month != '' && $year != '' && $section_id != '') : ?>
    <?php
    $full_date = $year . '-' . $month . '-' . $date;
    $timestamp = strtotime($full_date);
    $day = strtolower(date('l', $timestamp));
    ?>
    <form method="post" action="<?php echo base_url(); ?>index.php?admin/manage_attendance_students/<?php echo $date . '/' . $month . '/' . $year . '/' . $section_id; ?>">
        <div class="col-sm-offset-3 col-md-6">
            <table class="table table-bordered table-data table-hover table-striped">
                <thead>
                    <tr class="gradeA">
                        <th class="text-center"><?php echo ('Estudiante'); ?></th>
                        <th class="text-center"><?php echo ('Asistencia'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $students = $this->db->get_where('student_details', array('section_id' => $section_id))->result_array();
                    foreach ($students as $row) :
                        $verify_data = array(
                            'student_id' => $row['student_id'],
                            'date' => $full_date
                        );
                        $query = $this->db->get_where('attendance_student', $verify_data);
                        if ($query->num_rows() < 1) $this->db->insert('attendance_student', $verify_data);
                        $attendance = $this->db->get_where('attendance_student', $verify_data)->row();
                        $status = $attendance->status;
                        $observation = $attendance->observation;
                    ?>
                        <tr class="gradeA">
                            <td><?php echo $row['lastname'] . ', ' . $row['firstname']; ?></td>
                            <td class="text-center">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn <?php echo ($status == 0 || $status == 1) ? 'active' : ''; ?>" data-value="p">
                                        <input type="radio" name="status_<?php echo $row['student_id']; ?>" value="1" <?php if ($status == 0 || $status == 1) echo 'checked'; ?>> P
                                    </label>
                                    <label class="btn <?php if ($status == 2) echo 'active'; ?>" data-value="a">
                                        <input type="radio" name="status_<?php echo $row['student_id']; ?>" value="2" <?php if ($status == 2) echo 'checked'; ?>> A
                                    </label>
                                    <label class="btn <?php if ($status == 3) echo 'active'; ?>" data-value="t">
                                        <input type="radio" name="status_<?php echo $row['student_id']; ?>" value="3" <?php if ($status == 3) echo 'checked'; ?>> T
                                    </label>
                                    <label class="btn <?php if ($status == 4) echo 'active'; ?>" data-value="aj" onclick="showAjaxModal('<?php echo base_url(); ?>index.php?modal/popup/modal_attendance_student_observation/<?php echo $row['student_id']; ?>/<?php echo $full_date; ?>');">
                                        <input type="radio" name="status_<?php echo $row['student_id']; ?>" value="4" <?php if ($status == 4) echo 'checked'; ?>> AJ
                                    </label>
                                </div>
                                <input type="hidden" name="observation_<?php echo $row['student_id']; ?>" id="observation_<?php echo $row['student_id']; ?>" value="<?php echo ($status == 4) ? $observation : ''; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="hidden" name="date" value="<?php echo $full_date; ?>" />
            <div class="text-center">
                <input type="submit" class="btn btn-info" value="Guardar asistencia">
            </div>
        </div>
    </form>
<?php endif; ?>



<style>
    .hiddenElement {
        display: none;
    }

    input[readonly]:hover {
        cursor: default;
    }

    .table-calendar {
        position: absolute;
        opacity: 0; /* Oculta el calendario inicialmente */
        z-index: -1;
        overflow: hidden; /* Oculta cualquier contenido dentro del calendario */
    }

    .input-group-addon {
        background-color: #265044 !important;
        color: #fff !important;
        border: 2px solid #265044 !important;
    }

    .form-control {
        border: 2px solid #265044 !important;
    }

    .btn-group .btn {
        background-color: #ebebeb;
        margin-right: 15px;
        border-radius: 50% !important;
        font-weight: 500;
        border-color: transparent;
    } .btn-group .btn:hover {
        border-color: black;
    } 

    .btn.active[data-value="p"] {
        background-color: #00a651 !important;
        color: white !important;
    }  .btn.active[data-value="p"]:hover {
        border-color: #008d45 !important;
    }
    .btn.active[data-value="a"] {
        background-color: #a91e1e !important;
        color: white !important;
    }  .btn.active[data-value="a"]:hover {
        border-color: #981b1b !important;
    }
    .btn.active[data-value="t"] {
        background-color: #ff9600 !important;
        color: white !important;
    }  .btn.active[data-value="t"]:hover {
        border-color: #f0c706 !important;
    }
    .btn.active[data-value="aj"] {
        background-color: #0072bc !important;
        color: white !important;
    }  .btn.active[data-value="j"]:hover {
        border-color: #0072bc !important;
    }

    tbody tr td {
        color: black !important;
    }

    /* label.active {
        background-color: green !important;
        color: blue !important;
    } */

    /* .btn-warning:hover, .btn-warning:focus, .btn-warning:active, .btn-warning.active, .open .dropdown-toggle.btn-warning {
        background-color: #f9d011 !important;
        border-color: #f0c706 !important;
    } .btn-danger:hover, .btn-danger:focus, .btn-danger:active, .btn-danger.active, .open .dropdown-toggle.btn-danger {
        background-color: #a91e1e !important;
        border-color: #981b1b !important;
    } */


    /* .btn-success, .btn-green {
        background-color: #00a651 !important;
    } .btn-success:hover {
        border-color: #008d45 !important;
    } */

    .dataTables_wrapper {
        color: #484848 !important;
    }

    .dataTable thead tr th {
        color: #265044 !important;
        font-weight: bold !important;
    }

    .even {
        background-color: white !important;
    }

    .table-bordered > thead > tr > th, .table-bordered > thead > tr > td {
        background-color: #265044 !important;
        color: white !important;
    }

    .table-data tbody tr td {
        background-color: #fff !important;
    }  .table-data tbody tr:hover td {
        background-color: #f2f2f4 !important;
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

    body div.datepicker table tr .day.active {
        /* background-color: #265044 !important;
        color: #fff !important; */
    }

    .ui-datepicker-calendar tbody tr td a.ui-state-active {
        /* background-color: #265044 !important;
        color: #fff !important; */
    } .ui-datepicker-calendar tbody tr td a.ui-state-default {
        
    } .ui-datepicker-calendar  {
        width: 500px !important;
    }

    body div.datepicker table tr .day.disabled {
        background-color: #D2D2D2 !important;
        color: #fff !important;
        /* border-radius: 50% !important; */
        border-radius: 10px !important;
        padding: 0px !important;
        /* width: 10px !important;
        height: 10px !important; */
    }

        .color-dot {
            display: inline-block;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            margin-right: 5px;
            border: 1px solid black !important;
        }

        .orange {
            background-color: #ff9600;
        }

        .yellow {
            background-color: #ebebeb;
        }

        .list-calendar-elements {
            font-size: 20px !important;
        }

        .fc-day {
            /* border-bottom: 1px solid #AAAAAA !important; */
        }
        .fc-day.fc-sun, .fc-day.fc-sat {
            background-color: #E5E5E5 !important;
        } .fc-day.fc-mon, .fc-day.fc-tue, .fc-day.fc-wed, .fc-day.fc-thu, .fc-day.fc-fri {
            /* border-left: 1px solid #AAAAAA !important; */
            border-left: 1px solid #ebebeb !important;
        }  .fc-day.fc-mon:hover, .fc-day.fc-tue:hover, .fc-day.fc-wed:hover, .fc-day.fc-thu:hover, .fc-day.fc-fri:hover {
            cursor: pointer;
        }

        .calendar-body table td[data-date="2024-01-01"] {
            background-color: #ff9600 !important; /* Año Nuevo */
        }
        .calendar-body table td[data-date="2024-02-12"],
        .calendar-body table td[data-date="2024-02-13"] {
            background-color: #ff9600 !important; /* Carnaval */
        }
        .calendar-body table td[data-date="2024-03-24"] {
            background-color: #ff9600 !important; /* Día Nacional de la Memoria por la Verdad y la Justicia */
        }
        .calendar-body table td[data-date="2024-03-29"] {
            background-color: #ff9600 !important; /* Viernes Santo */
        }
        .calendar-body table td[data-date="2024-04-01"] {
            background-color: #ff9600 !important; /* Feriado turístico */
        }
        .calendar-body table td[data-date="2024-04-02"] {
            background-color: #ff9600 !important; /* Día del Veterano y de los Caídos en la Guerra de Malvinas */
        }
        .calendar-body table td[data-date="2024-05-01"] {
            background-color: #ff9600 !important; /* Día del Trabajo */
        }
        .calendar-body table td[data-date="2024-05-25"] {
            background-color: #ff9600 !important; /* Día de la Revolución de Mayo */
        }
        .calendar-body table td[data-date="2024-06-20"] {
            background-color: #ff9600 !important; /* Paso a la Inmortalidad del General Don Manuel Belgrano */
        }
        .calendar-body table td[data-date="2024-06-21"] {
            background-color: #ff9600 !important; /* Feriado turístico */
        }
        .calendar-body table td[data-date="2024-07-09"] {
            background-color: #ff9600 !important; /* Día de la Independencia */
        }
        .calendar-body table td[data-date="2024-10-11"] {
            background-color: #ff9600 !important; /* Feriado turístico */
        }
        .calendar-body table td[data-date="2024-12-08"] {
            background-color: #ff9600 !important; /* Día de la Inmaculada Concepción de María */
        }
        .calendar-body table td[data-date="2024-12-25"] {
            background-color: #ff9600 !important; /* Navidad */
        }

        /* Establecer el cursor predeterminado en los días feriados */
        .calendar-body table td[data-date="2024-01-01"],
        .calendar-body table td[data-date="2024-02-12"],
        .calendar-body table td[data-date="2024-02-13"],
        .calendar-body table td[data-date="2024-03-24"],
        .calendar-body table td[data-date="2024-03-29"],
        .calendar-body table td[data-date="2024-04-01"],
        .calendar-body table td[data-date="2024-04-02"],
        .calendar-body table td[data-date="2024-05-01"],
        .calendar-body table td[data-date="2024-05-25"],
        .calendar-body table td[data-date="2024-06-20"],
        .calendar-body table td[data-date="2024-06-21"],
        .calendar-body table td[data-date="2024-07-09"],
        .calendar-body table td[data-date="2024-10-11"],
        .calendar-body table td[data-date="2024-12-08"],
        .calendar-body table td[data-date="2024-12-25"] {
            cursor: default !important;
        }


</style>