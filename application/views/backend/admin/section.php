<?php
// Primero, obtenemos las secciones que están activas y tienen un periodo académico activo
$this->db->select('section.class_id'); // Seleccionamos el class_id de las secciones
$this->db->from('section'); // Usamos la tabla section
$this->db->join('academic_period', 'section.academic_period_id = academic_period.id'); // Unimos con la tabla academic_period
$this->db->where('academic_period.status_id', 1); // Filtramos solo las secciones con periodo académico activo
$active_sections = $this->db->get()->result_array(); // Obtenemos los resultados

// Ahora, extraemos los class_id de las secciones activas
$class_ids = array_column($active_sections, 'class_id'); // Extraemos los class_id

$all_classes_count = count($active_sections);

?>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <?php echo ucfirst(get_phrase('all')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_classes_count; ?>
                    </span>
                </a>
            </li>
            <?php foreach ($classes as $class): 
                    $this->db->select('section.*'); 
                    $this->db->from('section'); 
                    $this->db->join('academic_period', 'section.academic_period_id = academic_period.id'); 
                    $this->db->where('section.class_id', $class['class_id']); 
                    $this->db->where('academic_period.status_id', 1); 
                    $query = $this->db->get(); 
                    $section_class_count = $query->num_rows(); 
                ?>
            <li>
                <a href="#class_<?php echo $class['class_id'];?>" data-toggle="tab">
                    <?php echo $class['name'];?>°
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $section_class_count; ?>
                    </span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_section_add/');" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="all_class_table">
                    <thead>
                        <tr>
							<th class="text-center" width="40"><?php echo ucfirst(get_phrase('class')); ?></th>
                            <th class="text-center" width="40"><?php echo ucfirst(get_phrase('section')); ?></th>
							<th class="text-center" width="40"><?php echo ucfirst(get_phrase('shift')); ?></th>
                            <th class="text-center" width="80"><?php echo ucfirst(get_phrase('teacher_aide')); ?></th>
                            <th class="text-center" width="270"><?php echo ucfirst(get_phrase('teachers')); ?></th>
                            <th class="text-center"  width="40"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="80"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach ($classes as $class):
                                $this->db->select('section.*'); 
                                $this->db->from('section'); 
                                $this->db->join('academic_period', 'section.academic_period_id = academic_period.id'); 
                                $this->db->where('section.class_id', $class['class_id']); 
                                $this->db->where('academic_period.status_id', 1); 
                                $sections = $this->db->get()->result_array(); 

                                foreach ($sections as $section):

                                    if ($section['status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
						?>
                        <tr>
                            <td class="text-center"><?php echo $class['name'];?>°</td>
                            <td class="text-center"><?php echo $section['letter_name']; ?></td>
							<td class="text-center">
								<?php 
								if ($section['shift_id'] == 1) {
									echo '<span class="label label-status label-warning style="background-color: #FFFF99 !important;"><i class="fa fa-sun-o" aria-hidden="true"></i> '. ucfirst(get_phrase('morning')) .'</span>';
								} else {
									echo '<span class="label label-status label-info style="background-color: #FFA07A !important;"><i class="fa fa-sun-o" aria-hidden="true"></i> '. ucfirst(get_phrase('afternoon')) .'</span>';
								}
								?>
							</td>
                            <td class="text-center">
								<?php 
									$teacher_aide_info = $this->crudTeacher->get_teacher_aide_info_per_id($section['teacher_aide_id']);
									foreach ($teacher_aide_info as $teacher_aide):
								?>
								<a href="#">
									<img src="<?php echo $teacher_aide['photo']; ?>" class="img-circle" width="30" height="30" title="<?php echo $teacher_aide['lastname']; ?>, <?php echo $teacher_aide['firstname']; ?>"/>
								</a>
								<?php endforeach;?>
							</td> 
                        
                            <td class="text-center">
                                <?php 
                                    // Verificar el estado del período académico
                                    $this->db->where('id', $section['academic_period_id']);
                                    $this->db->where('status_id', 1);
                                    $academic_period = $this->db->get('academic_period')->row_array();

                                    // Determinar la tabla a usar
                                    $subject_table = ($academic_period) ? 'subject' : 'subject_history';

                                    // Obtener los registros de la tabla correspondiente para el section_id
                                    $this->db->where('section_id', $section['section_id']);
                                    $subjects = $this->db->get($subject_table)->result_array();

                                    // Arreglo auxiliar para evitar duplicados de teacher_id
                                    $processed_teachers = [];

                                    foreach ($subjects as $subject) {
                                        // Verificar si el teacher_id ya fue procesado
                                        if (!in_array($subject['teacher_id'], $processed_teachers)) {
                                            // Obtener información del profesor desde teacher_details
                                            $this->db->where('teacher_id', $subject['teacher_id']);
                                            $teacher = $this->db->get('teacher_details')->row_array();

                                            if ($teacher): 
                                                $firstname = isset($teacher['firstname']) ? $teacher['firstname'] : '';
                                                $lastname = isset($teacher['lastname']) ? $teacher['lastname'] : '';
                                                $photo = isset($teacher['photo']) ? $teacher['photo'] : '';
                                                ?>
                                                <a href="<?php echo base_url(); ?>index.php?admin/teacher_profile/<?php echo $teacher['teacher_id']; ?>">
                                                    <img src="<?php echo $photo; ?>" class="img-circle" width="30" height="30" title="<?php echo $lastname; ?>, <?php echo $firstname; ?>."/>
                                                </a>
                                                <?php
                                                // Marcar este teacher_id como procesado
                                                $processed_teachers[] = $subject['teacher_id'];
                                            endif;
                                        }
                                    }
                                ?>
                            </td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/section_profile/<?php echo $section['section_id'];?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a  href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_section_edit/<?php echo $section['section_id'];?>');" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <?php if ($section['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/sections/disable_section/<?php echo $section['section_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($section['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/sections/enable_section/<?php echo $section['section_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                          
                        </tr>
                        <?php 
                            endforeach;
                        endforeach; 
                        ?>
                    </tbody>
                </table>
            </div>


		    <?php foreach ($classes as $class): ?>
    <div class="tab-pane" id="class_<?php echo $class['class_id'];?>">
        <br>
        <div class="mt-2 mb-4">
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_section_add/');" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
            <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
            <div class="pull-right">
            </div>
        </div>
        <br>
        <table class="table table-bordered datatable table-hover table-striped" id="classDataTable_<?php echo $class['class_id'];?>">
            <thead>
                <tr>
                    <th class="text-center" width="40"><?php echo ucfirst(get_phrase('section')); ?></th>
					<th class="text-center" width="40"><?php echo ucfirst(get_phrase('shift')); ?></th>
                    <th class="text-center" width="80"><?php echo ucfirst(get_phrase('teacher_aide')); ?></th>
                    <th class="text-center" width="260"><?php echo ucfirst(get_phrase('teachers')); ?></th>
                    <th class="text-center"  width="40"><?php echo ucfirst(get_phrase('status')); ?></th>
                    <th class="text-center" width="80"><?php echo ucfirst(get_phrase('action')); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php 
                                $this->db->select('section.*'); 
                                $this->db->from('section'); 
                                $this->db->join('academic_period', 'section.academic_period_id = academic_period.id'); 
                                $this->db->where('section.class_id', $class['class_id']); 
                                $this->db->where('academic_period.status_id', 1); 
                                $sections = $this->db->get()->result_array(); 

                                foreach ($sections as $section):
                                    if ($section['status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
                            ?>
                <tr>
                    <td class="text-center"><?php echo $section['letter_name']; ?></td>
					<td class="text-center">
						<?php 
							if ($section['shift_id'] == 1) {
								echo '<span class="label label-status label-warning style="background-color: #FFFF99 !important;"><i class="fa fa-sun-o" aria-hidden="true"></i>'. ucfirst(get_phrase('morning')) .'</span>';
							} else {
								echo '<span class="label label-status label-info style="background-color: #FFA07A !important;"><i class="fa fa-sun-o" aria-hidden="true"></i>'. ucfirst(get_phrase('afternoon')) .'</span>';
							}
						?>
					</td>
					<td class="text-center">
                        <?php 
                        $teacher_aide_info = $this->crudTeacher->get_teacher_aide_info($section['teacher_aide_id']);
                        foreach ($teacher_aide_info as $teacher_aide):
                        ?>
                        <img src="<?php echo $teacher_aide['photo']; ?>" class="img-circle" width="30" height="30" title="<?php echo $teacher_aide['lastname']; ?>, <?php echo $teacher_aide['firstname']; ?>"/>
                        <?php endforeach; ?>
                    </td> 
                    <td class="text-center">
                        <?php 
                            // Verificar el estado del período académico
                            $this->db->where('id', $section['academic_period_id']);
                            $this->db->where('status_id', 1);
                            $academic_period = $this->db->get('academic_period')->row_array();

                            // Determinar la tabla a usar
                            $subject_table = ($academic_period) ? 'subject' : 'subject_history';

                            // Obtener los registros de la tabla correspondiente para el section_id
                            $this->db->where('section_id', $section['section_id']);
                            $subjects = $this->db->get($subject_table)->result_array();

                            // Arreglo auxiliar para evitar duplicados de teacher_id
                            $processed_teachers = [];

                            foreach ($subjects as $subject) {
                                // Verificar si el teacher_id ya fue procesado
                                if (!in_array($subject['teacher_id'], $processed_teachers)) {
                                    // Obtener información del profesor desde teacher_details
                                    $this->db->where('teacher_id', $subject['teacher_id']);
                                    $teacher = $this->db->get('teacher_details')->row_array();

                                    if ($teacher): 
                                        $firstname = isset($teacher['firstname']) ? $teacher['firstname'] : '';
                                        $lastname = isset($teacher['lastname']) ? $teacher['lastname'] : '';
                                        $photo = isset($teacher['photo']) ? $teacher['photo'] : '';
                                        ?>
                                        <a href="<?php echo base_url(); ?>index.php?admin/teacher_profile/<?php echo $teacher['teacher_id']; ?>">
                                            <img src="<?php echo $photo; ?>" class="img-circle" width="30" height="30" title="<?php echo $lastname; ?>, <?php echo $firstname; ?>."/>
                                        </a>
                                        <?php
                                        // Marcar este teacher_id como procesado
                                        $processed_teachers[] = $subject['teacher_id'];
                                    endif;
                                }
                            }
                        ?>
                    </td>

                    <td class="text-center"><?php echo $status_label; ?></td>
                    <td class="text-center">
                        <a href="<?php echo base_url();?>index.php?admin/section_profile/<?php echo $section['section_id'];?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                            <i class="entypo-eye"></i>
                        </a>
                        <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_section_edit/<?php echo $section['section_id']; ?>');" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                            <i class="entypo-pencil"></i>
                        </a>
                        <?php if ($section['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/sections/disable_section/<?php echo $section['section_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($section['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/sections/enable_section/<?php echo $section['section_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                    </td>
                  
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php 
    endforeach;
?>
        </div>
    </div>
</div>




<script type="text/javascript">
	jQuery(document).ready(function($) {
        var languagePreference = '<?php echo $this->session->userdata('language_preference'); ?>';

        var $allClassDataTable = jQuery("#all_class_table");

        if (languagePreference === 'english') {
            var allClassDataTable = $allClassDataTable.DataTable({
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
                "autoWidth": false, 
                "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-file-text-o"></i> Copy',
                        className: 'btn btn-white btn-sm btn-info-hover'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        className: 'btn btn-white btn-sm btn-green-hover'
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> CSV',
                        className: 'btn btn-white btn-sm btn-green-hover'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn btn-white btn-sm btn-danger-hover'
                    }
                ],
                colReorder: true, 
                initComplete: function() {
                    $('#all_class_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var allClassDataTable = $allClassDataTable.DataTable({
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
                //  "scrollX": true, 
                "scrollX": $(window).width() <= 767,
                "autoWidth": false,
                "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-file-text-o"></i> Copiar',
                        className: 'btn btn-white btn-sm btn-info-hover'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        className: 'btn btn-white btn-sm btn-green-hover'
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> CSV',
                        className: 'btn btn-white btn-sm btn-green-hover'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Imprimir',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn btn-white btn-sm btn-danger-hover'
                    }
                ],
                colReorder: true, 
                initComplete: function() {
                    $('#all_class_table_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

        }

        $allClassDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $allClassDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }

       

        <?php 
            $query = $this->db->get('class');
            if ($query->num_rows() > 0):
                $classes2 = $query->result_array();
                foreach ($classes2 as $row):
        ?>

            var $classDataTable_<?php echo $row['class_id'];?> = jQuery("#classDataTable_<?php echo $row['class_id'];?>");

            if (languagePreference === 'english') {
                var classDataTable_<?php echo $row['class_id'];?> = $classDataTable_<?php echo $row['class_id'];?>.DataTable({
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
                    "autoWidth": false, 
                    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    buttons: [
                        {
                            extend: 'copyHtml5',
                            text: '<i class="fa fa-file-text-o"></i> Copy',
                            className: 'btn btn-white btn-sm btn-info-hover'
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fa fa-file-excel-o"></i> Excel',
                            className: 'btn btn-white btn-sm btn-green-hover'
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="fa fa-file-excel-o"></i> CSV',
                            className: 'btn btn-white btn-sm btn-green-hover'
                        },
                        {
                            extend: 'print',
                            text: '<i class="fa fa-print"></i> Print',
                            className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover'
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="fa fa-file-pdf-o"></i> PDF',
                            className: 'btn btn-white btn-sm btn-danger-hover'
                        }
                    ],
                    colReorder: true, 
                    initComplete: function() {
                        $('#classDataTable_<?php echo $row['class_id'];?>_filter input[type="search"]').attr('placeholder', 'Search');
                    }
                });

            } else if (languagePreference === 'spanish') {
                var classDataTable_<?php echo $row['class_id'];?> = $classDataTable_<?php echo $row['class_id'];?>.DataTable({
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
                    //  "scrollX": true, 
                    "scrollX": $(window).width() <= 767,
                    "autoWidth": false,
                    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                    dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    buttons: [
                        {
                            extend: 'copyHtml5',
                            text: '<i class="fa fa-file-text-o"></i> Copiar',
                            className: 'btn btn-white btn-sm btn-info-hover'
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fa fa-file-excel-o"></i> Excel',
                            className: 'btn btn-white btn-sm btn-green-hover'
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="fa fa-file-excel-o"></i> CSV',
                            className: 'btn btn-white btn-sm btn-green-hover'
                        },
                        {
                            extend: 'print',
                            text: '<i class="fa fa-print"></i> Imprimir',
                            className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover'
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="fa fa-file-pdf-o"></i> PDF',
                            className: 'btn btn-white btn-sm btn-danger-hover'
                        }
                    ],
                    colReorder: true, 
                    initComplete: function() {
                        $('#classDataTable_<?php echo $row['class_id'];?>_filter input[type="search"]').attr('placeholder', 'Buscar');
                    }
                });

            }

                // Inicializar Select2 después de crear DataTables
                $classDataTable_<?php echo $row['class_id'];?>.closest('.dataTables_wrapper').find('select').select2({
                    minimumResultsForSearch: -1
                });

                if ($(window).width() > 767) {
                    $classDataTable_<?php echo $row['class_id'];?>.colResizable({
                        liveDrag: true,
                        resizeMode: 'fit',
                        partialRefresh: true,
                        headerOnly: true
                    });
                }

              


               
        <?php endforeach;?>
        <?php endif;?>

       

	});

		
</script>

<script type="text/javascript">
    
    function reload_ajax() {
        location.reload(); 
    }

</script> 


<style>

#all_class_table_filter {
        margin-top: 5px !important;
        
    }  #all_class_table_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }
    #classDataTable_1_filter {
        margin-top: 5px !important;
    }  #classDataTable_1_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }  #classDataTable_2_filter {
        margin-top: 5px !important;
    }  #classDataTable_2_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    } #classDataTable_3_filter {
        margin-top: 5px !important;
    }  #classDataTable_3_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }

	.label-warning {
    	background-color: #FFFF99 !important;
	} .label-info {
		background-color: #ffd0be !important;
	}
</style>