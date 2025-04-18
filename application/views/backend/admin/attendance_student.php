<?php
$query = $this->db->get('class');
if ($query->num_rows() > 0):
    $classes = $query->result_array();
?>
<?php
$query = $this->db->get('section');
if ($query->num_rows() > 0):
    $all_classes_count = $query->num_rows();
endif;
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
                     $this->db->from('section');
                     $this->db->where('class_id', $class['class_id']);
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
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="all_class_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ('N°');?></th>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('class')); ?></th>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('section')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('shift')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('students')); ?></th> 
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 1;
                        foreach ($classes as $class):
                            $sections = $this->db->get_where('section', array('class_id' => $class['class_id']))->result_array();
                            foreach ($sections as $section):
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $count++; ?></td>
                            <td class="text-center"><?php echo $class['name'];?></td>
                            <td class="text-center"><?php echo $section['letter_name']; ?></td>
                            <td class="text-center">
                                <?php 
                                $shift = $section['shift_id'];
                                if ($shift == '1') {
                                    echo 'Mañana';
                                } elseif ($shift == '2') {
                                    echo 'Tarde';
                                } else {
                                    echo $shift;
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                $student_count = $this->db->get_where('student_details', array('class_id' => $class['class_id'], 'section_id' => $section['section_id']))->num_rows();
                                echo $student_count;
                                ?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo base_url(); ?>index.php?admin/summary_attendance_student/<?php echo $section['section_id']; ?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('Resumen')); ?>">
                                    <i class="entypo-clipboard"></i>
                                </a>
                                <a href="<?php echo base_url(); ?>index.php?admin/manage_attendance_student/<?php echo date("d"); ?>/<?php echo date("m"); ?>/<?php echo date("Y"); ?>/<?php echo $section['section_id']; ?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('Asistencia')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
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
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="classDataTable_<?php echo $class['class_id'];?>">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ('N°');?></th>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('class')); ?></th>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('section')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('shift')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('students')); ?></th> 
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 1;
                        $sections = $this->db->get_where('section', array('class_id' => $class['class_id']))->result_array();
                        foreach ($sections as $section):
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $count++; ?></td>
                            <td class="text-center"><?php echo $class['name'];?></td>
                            <td class="text-center"><?php echo $section['letter_name']; ?></td>
                            <td class="text-center">
                                <?php 
                                $shift = $section['shift_id'];
                                if ($shift == '1') {
                                    echo 'Mañana';
                                } elseif ($shift == '2') {
                                    echo 'Tarde';
                                } else {
                                    echo $shift;
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                $student_count = $this->db->get_where('student_details', array('class_id' => $class['class_id'], 'section_id' => $section['section_id']))->num_rows();
                                echo $student_count;
                                ?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo base_url(); ?>index.php?admin/summary_attendance_student/<?php echo $section_id;?>; ?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('Resumen')); ?>">
                                    <i class="entypo-clipboard"></i>
                                </a>
                                <a href="<?php echo base_url(); ?>index.php?admin/manage_attendance_student/<?php echo date("d/m/Y"); ?>" class="btn btn-table btn-white btn-orange-hover" title=" <?php echo ucfirst(get_phrase('asistencia')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <a href="javascript:;" onclick="" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('historico')); ?>">
                                    <i class="entypo-calendar"></i>
                                </a>
                            </td>
                           
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('.icheck-2').iCheck({
            checkboxClass: 'icheckbox_square-green'
        });
    });
</script>


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

        // Resaltar filas seleccionadas
        $('#all_class_table tbody input[type=checkbox]').each(function(i, el) {
            var $this = $(el),
                $p = $this.closest('tr');
            
            $(el).on('change', function() {
                var is_checked = $this.is(':checked');
                $p[is_checked ? 'addClass' : 'removeClass']('highlight');

                allClassDataTable.columns().every(function() {
                    var that = this;
                    that
                        .search('') 
                        .draw(); 
                });
            });
        });

        $('#chk-all-classes').on('change', function() {
            var is_checked = $(this).is(':checked');
            $('#all_class_table tbody input[type=checkbox]').each(function(i, el) {
                $(el).prop('checked', is_checked).trigger('change');
            });
        });

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

                // Resaltar filas seleccionadas
                $('#classDataTable_<?php echo $row['class_id'];?> tbody input[type=checkbox]').each(function(i, el) {
                    var $this = $(el),
                        $p = $this.closest('tr');
                    
                    $(el).on('change', function() {
                        var is_checked = $this.is(':checked');
                        $p[is_checked ? 'addClass' : 'removeClass']('highlight');

                        // Deshabilitar temporalmente la búsqueda para evitar que se active al seleccionar una fila
                        classDataTable_<?php echo $row['class_id'];?>.columns().every(function() {
                            var that = this;
                            that
                                .search('') // Limpiar la búsqueda
                                .draw(); // Redibujar la tabla después de limpiar la búsqueda
                        });
                    });
                });

                // Manejar la selección de todas las filas
                $('#chk-all-class-<?php echo $row['class_id'];?>').on('change', function() {
                    var is_checked = $(this).is(':checked');
                    $('#classDataTable_<?php echo $row['class_id'];?> tbody input[type=checkbox]').each(function(i, el) {
                        $(el).prop('checked', is_checked).trigger('change');
                    });
                });

               
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

</style> 


