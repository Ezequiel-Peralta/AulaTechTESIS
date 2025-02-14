<div class="row">
    <div class="col-md-12">
        <div class="panel-group joined" id="accordion-test-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion-attendance" href="#collapse-attendance" class="collapsed">
                        Estadísticas de asistencia
                    </a>
                </h4>
                </div>
                <div id="collapse-attendance" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div>
                            <div id="chartBarAttendance" style="height: 250px"></div>
                        </div>
                        <div class="text-center">
                            <button id="btnViewDetailsAttendance" class="btn btn-primary">Ver detalles</button>
                        </div>
                        <br />
                        <div id="detailsPanelGroupAttendance" class="panel-group joined" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#detailsPanelGroupAttendance" href="#collapse-percentages" class="collapsed">
                                            Porcentajes
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-percentages" class="panel-collapse collapse">
                                    <div class="panel-body">
                                    <?php foreach ($class_ids as $class_id): ?>
                                        <br>
                                        <div class="text-center">
                                        
                                            <h4><?php echo $class_id; ?>°</h4>
                                            <div id="chartDonutAttendance<?php echo $class_id; ?>" style="height: 250px"></div>
                                        </div>
                                    <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#detailsPanelGroupAttendance" href="#collapse-data" class="collapsed">
                                            Datos
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-data" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="text-center" style="margin-bottom: 10px !important;">
                                            <h4 style="font-weight: bold;">De 10 a 19 inasistencias</h4>
                                        </div>
                                        <br>
                                        <div class="tab-content">

                                            <div class="tab-pane active" id="home">
                                                <br>
                                                <div class="mt-2 mb-4">
                                                    <div class="pull-right"> 
                                                    
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-bordered datatable table-hover table-striped" id="student_attendance_10_19">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('photo')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('lastname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('firstname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('class')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('quantity')); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                                foreach($students_attendance_data_10_19 as $row):
                                                                ?>
                                                        <tr>
                                                            <td class="text-center"><img src="<?php echo $row['photo'];?>" class="img-circle" width="30" height="30"/></td>
                                                            <td class="text-center"><?php echo $row['lastname'];?></td>
                                                            <td class="text-center"><?php echo $row['firstname'];?></td>
                                                            <td class="text-center"><?php echo $row['class'];?></td>
                                                            <td class="text-center"><?php echo $row['quantity'];; ?></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                                    
                                            </div>
                                    

                                        </div>
                                        <br>
                                        <div class="text-center" style="margin-bottom: 10px !important;">
                                            <h4 style="font-weight: bold;">De 20 a 25 inasistencias</h4>
                                        </div>
                                        <br>
                                        <div class="tab-content">

                                            <div class="tab-pane active" id="home">
                                                <br>
                                                <div class="mt-2 mb-4">
                                                    <div class="pull-right"> 
                                                    
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-bordered datatable table-hover table-striped" id="student_attendance_20_25">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('photo')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('lastname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('firstname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('class')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('quantity')); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                                foreach($students_attendance_data_20_25 as $row):
                                                                ?>
                                                        <tr>
                                                            <td class="text-center"><img src="<?php echo $row['photo'];?>" class="img-circle" width="30" height="30"/></td>
                                                            <td class="text-center"><?php echo $row['lastname'];?></td>
                                                            <td class="text-center"><?php echo $row['firstname'];?></td>
                                                            <td class="text-center"><?php echo $row['class'];?></td>
                                                            <td class="text-center"><?php echo $row['quantity'];; ?></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                                    
                                            </div>
                                    

                                        </div>
                                        <br>
                                        <div class="text-center" style="margin-bottom: 10px !important;">
                                            <h4 style="font-weight: bold;">+25 inasistencias</h4>
                                        </div>
                                        <br>
                                        <div class="tab-content">

                                            <div class="tab-pane active" id="home">
                                                <br>
                                                <div class="mt-2 mb-4">
                                                    <div class="pull-right"> 
                                                    
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-bordered datatable table-hover table-striped" id="student_attendance_more_25">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('photo')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('lastname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('firstname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('class')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('quantity')); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                                foreach($students_attendance_data_more_25 as $row):
                                                                ?>
                                                        <tr>
                                                            <td class="text-center"><img src="<?php echo $row['photo'];?>" class="img-circle" width="30" height="30"/></td>
                                                            <td class="text-center"><?php echo $row['lastname'];?></td>
                                                            <td class="text-center"><?php echo $row['firstname'];?></td>
                                                            <td class="text-center"><?php echo $row['class'];?></td>
                                                            <td class="text-center"><?php echo $row['quantity'];; ?></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                                    
                                            </div>
                                    

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion-new-students" href="#collapse-new-students" class="collapsed">
                            Estadísticas de estudiantes nuevos
                        </a>
                    </h4>
                </div>
                <div id="collapse-new-students" class="panel-collapse collapse">
                    <div class="panel-body">
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion-pass-students" href="#collapse-pass-students" class="collapsed">
                            Estadísticas de estudiantes salidos
                        </a>
                    </h4>
                </div>
                <div id="collapse-pass-students" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div id="chartBarPassStudents" style="height: 250px">
                        </div>
                        <div class="text-center">
                            <button id="btnViewDetailsPassStudents" class="btn btn-primary">Ver detalles</button>
                        </div>
                        <br />
                        <div id="detailsPanelGroupPassStudents" class="panel-group joined" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#detailsPanelGroupPassStudents" href="#collapse-percentages-pass-students" class="collapsed">
                                            Porcentajes
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-percentages-pass-students" class="panel-collapse collapse">
                                    <div class="panel-body">
                                    <?php foreach ($class_ids as $class_id): ?>
                                        <br>
                                        <div class="text-center">
                                        
                                            <h4><?php echo $class_id; ?>°</h4>
                                            <div id="chartDonutPassStudents<?php echo $class_id; ?>" style="height: 250px"></div>
                                        </div>
                                    <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#detailsPanelGroupPassStudents" href="#collapse-data-pass-students" class="collapsed">
                                            Datos
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-data-pass-students" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="text-center" style="margin-bottom: 10px !important;">
                                            <h4 style="font-weight: bold;">Estudiantes salidos</h4>
                                        </div>
                                        <br>
                                        <div class="tab-content">

                                            <div class="tab-pane active" id="home">
                                                <br>
                                                <div class="mt-2 mb-4">
                                                    <div class="pull-right"> 
                                                    
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-bordered datatable table-hover table-striped" id="student_pass">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('photo')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('lastname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('firstname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('class')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                                foreach($student_pass_no_pass as $row):
                                                                ?>
                                                        <tr>
                                                            <td class="text-center"><img src="<?php echo $row['photo'];?>" class="img-circle" width="30" height="30"/></td>
                                                            <td class="text-center"><?php echo $row['lastname'];?></td>
                                                            <td class="text-center"><?php echo $row['firstname'];?></td>
                                                            <td class="text-center"><?php echo $row['class'];?></td>
                                                            <td class="text-center"><?php echo ucfirst(get_phrase($row['status_reason']));?></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                                    
                                            </div>
                                    

                                        </div>
                                        
                                    

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion-graduate-students" href="#collapse-graduate-students" class="collapsed">
                            Estadísticas de egreso efectivo
                        </a>
                    </h4>
                </div>
                <div id="collapse-graduate-students" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div id="chartBarGraduateStudents" style="height: 250px">
                        </div>
                        <div class="text-center">
                            <button id="btnViewDetailsGraduateStudents" class="btn btn-primary">Ver detalles</button>
                        </div>
                        <br />
                        <div id="detailsPanelGroupGraduateStudents" class="panel-group joined" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#detailsPanelGroupGraduateStudents" href="#collapse-percentages-graduate-students" class="collapsed">
                                            Porcentajes
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-percentages-graduate-students" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php foreach ($academic_periods as $academic_period): ?>
                                            <br>
                                            <div class="text-center">
                                                <h4><?php echo $academic_period['name']; ?></h4>
                                                <div id="chartDonutGraduateStudents<?php echo $academic_period['academic_period_id']; ?>" style="height: 250px"></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#detailsPanelGroupGraduateStudents" href="#collapse-data-graduate-students" class="collapsed">
                                            Datos
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-data-graduate-students" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="text-center" style="margin-bottom: 10px !important;">
                                            <h4 style="font-weight: bold;">Egreso efectivo</h4>
                                        </div>
                                        <br>
                                        <div class="tab-content">

                                            <div class="tab-pane active" id="home">
                                                <br>
                                                <div class="mt-2 mb-4">
                                                    <div class="pull-right"> 
                                                    
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-bordered datatable table-hover table-striped" id="student_graduate">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('photo')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('lastname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('firstname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('class')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                                foreach($students_graduate as $row):
                                                                ?>
                                                        <tr>
                                                            <td class="text-center"><img src="<?php echo $row['photo'];?>" class="img-circle" width="30" height="30"/></td>
                                                            <td class="text-center"><?php echo $row['lastname'];?></td>
                                                            <td class="text-center"><?php echo $row['firstname'];?></td>
                                                            <td class="text-center"><?php echo $row['class'];?></td>
                                                            <td class="text-center"><?php echo $row['status'];?></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                                    
                                            </div>
                                    

                                        </div>
                                        
                                    

                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion-repeater-students" href="#collapse-repeater-students" class="collapsed">
                            Estadísticas de repitentes
                        </a>
                    </h4>
                </div>
                <div id="collapse-repeater-students" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div id="chartBarRepeaterStudents" style="height: 250px">
                        </div>
                        <div class="text-center">
                            <button id="btnViewDetailsRepeaterStudents" class="btn btn-primary">Ver detalles</button>
                        </div>
                        <br />
                        <div id="detailsPanelGroupRepeaterStudents" class="panel-group joined" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#detailsPanelGroupRepeaterStudents" href="#collapse-percentages-repeater-students" class="collapsed">
                                            Porcentajes
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-percentages-repeater-students" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php foreach ($sections as $section): ?>
                                            <br>
                                            <div class="text-center">
                                                <h4><?php echo $section['name']; ?></h4>
                                                <div id="chartDonutRepeaterStudents<?php echo $section['section_id']; ?>" style="height: 250px"></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#detailsPanelGroupRepeaterStudents" href="#collapse-data-repeater-students" class="collapsed">
                                            Datos
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-data-repeater-students" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="text-center" style="margin-bottom: 10px !important;">
                                            <h4 style="font-weight: bold;">Repitentes</h4>
                                        </div>
                                        <br>
                                        <div class="tab-content">

                                            <div class="tab-pane active" id="home">
                                                <br>
                                                <div class="mt-2 mb-4">
                                                    <div class="pull-right"> 
                                                    
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-bordered datatable table-hover table-striped" id="student_repeater">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('photo')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('lastname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('firstname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('class')); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                                foreach($students_repeater as $row):
                                                                ?>
                                                        <tr>
                                                            <td class="text-center"><img src="<?php echo $row['photo'];?>" class="img-circle" width="30" height="30"/></td>
                                                            <td class="text-center"><?php echo $row['lastname'];?></td>
                                                            <td class="text-center"><?php echo $row['firstname'];?></td>
                                                            <td class="text-center"><?php echo $row['class'];?></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                                    
                                            </div>
                                    

                                        </div>
                                        
                                    

                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion-promoted-students" href="#collapse-promoted-students" class="collapsed">
                            Estadísticas de promovidos
                        </a>
                    </h4>
                </div>
                <div id="collapse-promoted-students" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div id="chartBarPromotedStudents" style="height: 250px">
                        </div>
                        <div class="text-center">
                            <button id="btnViewDetailsPromotedStudents" class="btn btn-primary">Ver detalles</button>
                        </div>
                        <br />
                        <div id="detailsPanelGroupPromotedStudents" class="panel-group joined" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#detailsPanelGroupPromotedStudents" href="#collapse-percentages-promoted-students" class="collapsed">
                                            Porcentajes
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-percentages-promoted-students" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php foreach ($sections as $section): ?>
                                            <br>
                                            <div class="text-center">
                                                <h4><?php echo $section['name']; ?></h4>
                                                <div id="chartDonutPromotedStudents<?php echo $section['section_id']; ?>" style="height: 250px"></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#detailsPanelGroupPromotedStudents" href="#collapse-data-promoted-students" class="collapsed">
                                            Datos
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-data-promoted-students" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="text-center" style="margin-bottom: 10px !important;">
                                            <h4 style="font-weight: bold;">Promovidos</h4>
                                        </div>
                                        <br>
                                        <div class="tab-content">

                                            <div class="tab-pane active" id="home">
                                                <br>
                                                <div class="mt-2 mb-4">
                                                    <div class="pull-right"> 
                                                    
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-bordered datatable table-hover table-striped" id="student_promoted">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('photo')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('lastname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('firstname')); ?></th>
                                                            <th class="text-center"><?php echo ucfirst(get_phrase('class')); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                                foreach($students_promoted as $row):
                                                                ?>
                                                        <tr>
                                                            <td class="text-center"><img src="<?php echo $row['photo'];?>" class="img-circle" width="30" height="30"/></td>
                                                            <td class="text-center"><?php echo $row['lastname'];?></td>
                                                            <td class="text-center"><?php echo $row['firstname'];?></td>
                                                            <td class="text-center"><?php echo $row['section_name'];?></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                                    
                                            </div>
                                    

                                        </div>
                                        
                                    

                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
	jQuery(document).ready(function($) {
        var languagePreference = '<?php echo $this->session->userdata('language_preference'); ?>';

        var $allStudent1019DataTable = jQuery("#student_attendance_10_19");

        var rowCount = $('#student_attendance_10_19 tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var allStudent1019DataTable = $allStudent1019DataTable.DataTable({
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
                colReorder: true, 
                initComplete: function() {
                    $('#student_attendance_10_19_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
    var allStudent1019DataTable = $allStudent1019DataTable.DataTable({
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
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'><'col-sm-3'f>>" + 
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                colReorder: true, 
                initComplete: function() {
                    $('#student_attendance_10_19_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

    
}

        $allStudent1019DataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $allStudent1019DataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }

     

        var $allStudent2025DataTable = jQuery("#student_attendance_20_25");

        var rowCount = $('#student_attendance_20_25 tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var allStudent2025DataTable = $allStudent2025DataTable.DataTable({
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
                colReorder: true, 
                initComplete: function() {
                    $('#student_attendance_20_25_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
        var allStudent2025DataTable = $allStudent2025DataTable.DataTable({
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
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'><'col-sm-3'f>>" + 
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                colReorder: true, 
                initComplete: function() {
                    $('#student_attendance_20_25_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });


        }

        $allStudent2025DataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $allStudent2025DataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }


        var $allStudentMore25DataTable = jQuery("#student_attendance_more_25");

        var rowCount = $('#student_attendance_more_25 tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var allStudentMore25DataTable = $allStudentMore25DataTable.DataTable({
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
                colReorder: true, 
                initComplete: function() {
                    $('#student_attendance_more_25_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
        var allStudentMore25DataTable = $allStudentMore25DataTable.DataTable({
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
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'><'col-sm-3'f>>" + 
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                colReorder: true, 
                initComplete: function() {
                    $('#student_attendance_more_25_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });


        }

        $allStudentMore25DataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $allStudentMore25DataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }





        var $allStudentPassDataTable = jQuery("#student_pass");

        var rowCount = $('#student_pass tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var allStudentPassDataTable = $allStudentPassDataTable.DataTable({
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
                colReorder: true, 
                initComplete: function() {
                    $('#student_pass_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
    var allStudentPassDataTable = $allStudentPassDataTable.DataTable({
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
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'><'col-sm-3'f>>" + 
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                colReorder: true, 
                initComplete: function() {
                    $('#student_pass_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

    
}

        $allStudentPassDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $allStudentPassDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }



        var $allStudentGraduateDataTable = jQuery("#student_graduate");

        var rowCount = $('#student_graduate tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var allStudentGraduateDataTable = $allStudentGraduateDataTable.DataTable({
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
                colReorder: true, 
                initComplete: function() {
                    $('#student_graduate_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
        var allStudentGraduateDataTable = $allStudentGraduateDataTable.DataTable({
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
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'><'col-sm-3'f>>" + 
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                colReorder: true, 
                initComplete: function() {
                    $('#student_graduate_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });


        }

        $allStudentGraduateDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $allStudentGraduateDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }



        var $allStudentRepeaterDataTable = jQuery("#student_repeater");

        var rowCount = $('#student_repeater tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var allStudentRepeaterDataTable = $allStudentRepeaterDataTable.DataTable({
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
                colReorder: true, 
                initComplete: function() {
                    $('#student_repeater_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
    var allStudentRepeaterDataTable = $allStudentRepeaterDataTable.DataTable({
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
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'><'col-sm-3'f>>" + 
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                colReorder: true, 
                initComplete: function() {
                    $('#student_repeater_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

    
}

        $allStudentRepeaterDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $allStudentRepeaterDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }






        var $allStudentPromotedDataTable = jQuery("#student_promoted");

        var rowCount = $('#student_promoted tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var allStudentPromotedDataTable = $allStudentPromotedDataTable.DataTable({
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
                colReorder: true, 
                initComplete: function() {
                    $('#student_promoted_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var allStudentPromotedDataTable = $allStudentPromotedDataTable.DataTable({
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
                        dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'><'col-sm-3'f>>" + 
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                        colReorder: true, 
                        initComplete: function() {
                            $('#student_promoted_filter input[type="search"]').attr('placeholder', 'Buscar');
                        }
                    });

            
        }

        $allStudentPromotedDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $allStudentPromotedDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }



       
       

	});

		
</script>


<script type="text/javascript">
  $(document).ready(function() {
    var chartDataAttendance = <?php echo $chart_data_attendance; ?>;
    var donutDataAttendance = <?php echo $donut_data_attendance; ?>;
    var barChartInitialized = false; // Variable de control para el gráfico de barras

    $('#collapse-attendance').on('shown.bs.collapse', function() {
        if (!barChartInitialized) { // Renderiza el gráfico de barras solo si no está inicializado
            Morris.Bar({
                element: 'chartBarAttendance',
                data: chartDataAttendance,
                xkey: 'x',
                ykeys: ['y', 'z', 'a'],
                labels: ['10-19 Inasistencias', '20-25 Inasistencias', 'Más de 25 Inasistencias'],
                barColors: ['#707f9b', '#455064', '#242d3c']
            });
            barChartInitialized = true; // Marca el gráfico como inicializado
        }
    });

    $('#btnViewDetailsAttendance').click(function() {
        $('#detailsPanelGroupAttendance').slideDown();
    });

    // Renderizar gráficos de dona solo cuando se expande la sección "Porcentajes"
    $('#collapse-percentages').on('shown.bs.collapse', function() {
        <?php foreach ($class_ids as $class_id): ?>
            if ($('#chartDonutAttendance<?php echo $class_id; ?> svg').length === 0) { // Evita renderizar si ya existe
                Morris.Donut({
                    element: 'chartDonutAttendance<?php echo $class_id; ?>',
                    data: donutDataAttendance['<?php echo $class_id; ?>'],
                    formatter: function(x) {
                        return x + '%';
                    },
                    colors: ['#b92527', '#d13c3e', '#ff6264', '#ffaaab']
                });
            }
        <?php endforeach; ?>
    });

    var chartDataPass= <?php echo $chart_data_pass; ?>;
    var donutDataPass = <?php echo $donut_data_pass; ?>;
    var barChartPassInitialized = false; // Variable de control para el gráfico de barras

    $('#collapse-pass-students').on('shown.bs.collapse', function() {
        if (!barChartPassInitialized) { // Renderiza el gráfico de barras solo si no está inicializado
            Morris.Bar({
                element: 'chartBarPassStudents',
                data: chartDataPass,
                xkey: 'x',
                ykeys: ['pass', 'no_pass'],
                labels: ['Pase', 'Sin pase'],
                barColors: ['#707f9b']
            });
            barChartPassInitialized = true; // Marca el gráfico como inicializado
        }
    });

    $('#btnViewDetailsPassStudents').click(function() {
        $('#detailsPanelGroupPassStudents').slideDown();
    });

    // Renderizar gráficos de dona solo cuando se expande la sección "Porcentajes"
    $('#collapse-percentages-pass-students').on('shown.bs.collapse', function() {
        <?php foreach ($class_ids as $class_id): ?>
            if ($('#chartDonutPassStudents<?php echo $class_id; ?> svg').length === 0) { // Evita renderizar si ya existe
                Morris.Donut({
                    element: 'chartDonutPassStudents<?php echo $class_id; ?>',
                    data: donutDataPass['<?php echo $class_id; ?>'],
                    formatter: function(x) {
                        return x + '%';
                    },
                    colors: ['#1abc9c', '#e74c3c']
                });
            }
        <?php endforeach; ?>
    });

    var chartDataGraduate= <?php echo $chart_data_graduate; ?>;
    var donutDataGraduate = <?php echo $donut_data_graduate; ?>;
    var barChartGraduateInitialized = false; // Variable de control para el gráfico de barras

   

    $('#collapse-graduate-students').on('shown.bs.collapse', function() {
        if (!barChartGraduateInitialized) { // Renderiza el gráfico de barras solo si no está inicializado
            Morris.Bar({
                element: 'chartBarGraduateStudents',
                data: chartDataGraduate,
                xkey: 'x',
                ykeys: ['y', 'z'],
                labels: ['Sin finalizar', 'Egreso efectivo'],
                barColors: ['#707f9b', '#707f9b']
            });
            barChartGraduateInitialized = true; // Marca el gráfico como inicializado
        }
    });

    $('#btnViewDetailsGraduateStudents').click(function() {
        $('#detailsPanelGroupGraduateStudents').slideDown();
    });

    $('#collapse-percentages-graduate-students').on('shown.bs.collapse', function() {
        <?php foreach ($academic_periods as $academic_period): ?>
            if ($('#chartDonutGraduateStudents<?php echo $academic_period['academic_period_id']; ?> svg').length === 0) { // Evita renderizar si ya existe
                Morris.Donut({
                    element: 'chartDonutGraduateStudents<?php echo $academic_period['academic_period_id']; ?>',
                    data: donutDataGraduate['<?php echo $academic_period['academic_period_id']; ?>'],
                    formatter: function(x) {
                        return x + '%';
                    },
                    colors: ['#b92527', '#d13c3e']
                });
            }
        <?php endforeach; ?>
    });



    var chartDataRepeater= <?php echo $chart_data_repeater; ?>;
    var donutDataRepeater = <?php echo $donut_data_repeater; ?>;
    var barChartRepeaterInitialized = false; // Variable de control para el gráfico de barras

    $('#collapse-repeater-students').on('shown.bs.collapse', function() {
        if (!barChartRepeaterInitialized) {
            if (chartDataRepeater && chartDataRepeater.length > 0) {
                Morris.Bar({
                    element: 'chartBarRepeaterStudents',
                    data: chartDataRepeater,
                    xkey: 'x',
                    ykeys: ['y'],
                    labels: ['Repitentes'],
                    barColors: ['#707f9b']
                });
                barChartRepeaterInitialized = true;
            } else {
                console.error('No hay datos para el gráfico de barras de repetidores');
            }
        }
    });

    $('#btnViewDetailsRepeaterStudents').click(function() {
        $('#detailsPanelGroupRepeaterStudents').slideDown();
    });

    $('#collapse-percentages-repeater-students').on('shown.bs.collapse', function() {
        <?php foreach ($sections as $section): ?>
            var sectionData = donutDataRepeater['<?php echo $section['section_id']; ?>'];
            if (sectionData && sectionData.length > 0) {
                if ($('#chartDonutRepeaterStudents<?php echo $section['section_id']; ?> svg').length === 0) { 
                    Morris.Donut({
                        element: 'chartDonutRepeaterStudents<?php echo $section['section_id']; ?>',
                        data: sectionData,
                        formatter: function(x) {
                            return x + '%';
                        },
                        colors: ['#b92527', '#6f6']
                    });
                }
            } else {
                console.error('No hay datos para el gráfico Donut de la sección: <?php echo $section["name"]; ?>');
            }
        <?php endforeach; ?>
    });

    var chartDataPromoted = <?php echo $chart_data_promoted; ?>;
    var donutDataPromoted = <?php echo $donut_data_promoted; ?>;
    var barChartPromotedInitialized = false; // Variable de control para el gráfico de barras


    $('#collapse-promoted-students').on('shown.bs.collapse', function() {
        if (!barChartPromotedInitialized) {
            if (chartDataPromoted && chartDataPromoted.length > 0) {
                Morris.Bar({
                    element: 'chartBarPromotedStudents',
                    data: chartDataPromoted,
                    xkey: 'x',
                    ykeys: ['y'],
                    labels: ['Promovidos'],
                    barColors: ['#707f9b']
                });
                barChartPromotedInitialized = true;
            }
        }
    });

    $('#btnViewDetailsPromotedStudents').click(function() {
        $('#detailsPanelGroupPromotedStudents').slideDown();
    });

    $('#collapse-percentages-promoted-students').on('shown.bs.collapse', function() {
        <?php foreach ($sections as $section): ?>
            var sectionData = donutDataPromoted['<?php echo $section['section_id']; ?>'];
            if (sectionData && sectionData.length > 0) {
                if ($('#chartDonutPromotedStudents<?php echo $section['section_id']; ?> svg').length === 0) { 
                    Morris.Donut({
                        element: 'chartDonutPromotedStudents<?php echo $section['section_id']; ?>',
                        data: sectionData,
                        formatter: function(x) {
                            return x + '%';
                        },
                        colors: ['#b92527', '#6f6']
                    });
                }
            } 
        <?php endforeach; ?>
    });


  });
</script>
