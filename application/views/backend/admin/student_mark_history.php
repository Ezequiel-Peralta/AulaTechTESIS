<?php
$this->db->from('student_details');
$this->db->where('class_id', $class_id);
$query = $this->db->get();
$all_student_count = $query->num_rows();
?>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <?php echo ('Todos los estudiantes'); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_student_count; ?>
                    </span>
                </a>
            </li>
            <?php 
            $query = $this->db->get_where('section', array('class_id' => $class_id));
            if ($query->num_rows() > 0):
                $sections = $query->result_array();
                foreach ($sections as $row):
                    $this->db->from('student_details');
                    $this->db->where('section_id', $row['section_id']);
                    $query = $this->db->get();
                    $section_student_count = $query->num_rows();
            ?>
            <li>
                <a href="#tab-<?php echo $row['section_id']; ?>" data-toggle="tab">
                    <?php echo $row['name']; ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $section_student_count; ?>
                    </span>
                </a>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                    <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_language_add/');" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="all_student_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('class')); ?></th>
                            <th class="text-center" width="80"><?php echo ucfirst(get_phrase('photo')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('name')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('dni')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('enrollment')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $students = $this->db->get_where('student_details', array('class_id' => $class_id))->result_array();
                        foreach ($students as $row):
                        ?>
                        <tr>
                            <td class="text-center">
                                    <?php echo $this->db->get_where('section', array('section_id' => $row['section_id']))->row()->name; ?>
                            </td>
                            <td class="text-center"><img src="<?php echo $row['photo']; ?>" class="img-circle" width="30" height="30"/></td>
                            <td class="text-center"><?php echo $row['lastname']; ?>, <?php echo $row['firstname']; ?></td>
                            <td class="text-center"><?php echo $row['dni']; ?></td>
                            <td class="text-center"><?php echo $row['enrollment']; ?></td>
                            <td class="text-center">
                                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_student_mark_history/<?php echo $row['student_id']; ?>');" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-vcard"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php 
            $query = $this->db->get_where('section', array('class_id' => $class_id));
            if ($query->num_rows() > 0):
                $sections = $query->result_array();
                foreach ($sections as $row):
            ?>
            <div class="tab-pane" id="tab-<?php echo $row['section_id']; ?>">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/students_add/" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <a href="#" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('import')); ?>" style="padding: 6px 10px;"><i class="fa fa-upload"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="sectionDataTable_<?php echo $row['section_id']; ?>">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ('N°'); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('photo')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('name')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('dni')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('enrollment')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                            <th class="text-center" width="50">
                                <input type="checkbox" id="chk-all-section-<?php echo $row['section_id']; ?>">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $students = $this->db->get_where('student_details', array('section_id' => $row['section_id']))->result_array();
                        $count = 1;
                        foreach ($students as $row):
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $count++; ?></td>
                            <td class="text-center"><img src="<?php echo $row['photo']; ?>" class="img-circle" width="30" height="30"/></td>
                            <td class="text-center"><?php echo $row['lastname']; ?>, <?php echo $row['firstname']; ?></td>
                            <td class="text-center"><?php echo $row['dni']; ?></td>
                            <td class="text-center"><?php echo $row['enrollment']; ?></td>
                            <td class="text-center">
                                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_student_mark_history/<?php echo $row['student_id']; ?>');" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-vcard"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="chk-student" id="<?php echo $row['student_id']; ?>">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
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
	jQuery(document).ready(function($) {
        var languagePreference = '<?php echo $this->session->userdata('language_preference'); ?>';

        var $allStudentDataTable = jQuery("#all_student_table");

        if (languagePreference === 'english') {
            var allStudentDataTable = $allStudentDataTable.DataTable({
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
                    $('#all_student_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var allStudentDataTable = $allStudentDataTable.DataTable({
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

        // Resaltar filas seleccionadas
        $('#all_student_table tbody input[type=checkbox]').each(function(i, el) {
            var $this = $(el),
                $p = $this.closest('tr');
            
            $(el).on('change', function() {
                var is_checked = $this.is(':checked');
                $p[is_checked ? 'addClass' : 'removeClass']('highlight');

                allStudentDataTable.columns().every(function() {
                    var that = this;
                    that
                        .search('') 
                        .draw(); 
                });
            });
        });

        $('#chk-all-student').on('change', function() {
            var is_checked = $(this).is(':checked');
            $('#all_student_table tbody input[type=checkbox]').each(function(i, el) {
                $(el).prop('checked', is_checked).trigger('change');
            });
        });

        <?php 
            $query = $this->db->get_where('section' , array('class_id' => $class_id));
            if ($query->num_rows() > 0):
                $sections = $query->result_array();
                foreach ($sections as $row):
        ?>

            var $sectionDataTable_<?php echo $row['section_id'];?> = jQuery("#sectionDataTable_<?php echo $row['section_id'];?>");

            if (languagePreference === 'english') {
                var sectionDataTable_<?php echo $row['section_id'];?> = $sectionDataTable_<?php echo $row['section_id'];?>.DataTable({
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
                        $('#sectionDataTable_<?php echo $row['section_id'];?>_filter input[type="search"]').attr('placeholder', 'Search');
                    }
                });

            } else if (languagePreference === 'spanish') {
                var sectionDataTable_<?php echo $row['section_id'];?> = $sectionDataTable_<?php echo $row['section_id'];?>.DataTable({
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
                        $('#sectionDataTable_<?php echo $row['section_id'];?>_filter input[type="search"]').attr('placeholder', 'Buscar');
                    }
                });

            }

                // Inicializar Select2 después de crear DataTables
                $sectionDataTable_<?php echo $row['section_id'];?>.closest('.dataTables_wrapper').find('select').select2({
                    minimumResultsForSearch: -1
                });

                if ($(window).width() > 767) {
                    $sectionDataTable_<?php echo $row['section_id'];?>.colResizable({
                        liveDrag: true,
                        resizeMode: 'fit',
                        partialRefresh: true,
                        headerOnly: true
                    });
                }

                // Resaltar filas seleccionadas
                $('#sectionDataTable_<?php echo $row['section_id'];?> tbody input[type=checkbox]').each(function(i, el) {
                    var $this = $(el),
                        $p = $this.closest('tr');
                    
                    $(el).on('change', function() {
                        var is_checked = $this.is(':checked');
                        $p[is_checked ? 'addClass' : 'removeClass']('highlight');

                        sectionDataTable_<?php echo $row['section_id'];?>.columns().every(function() {
                            var that = this;
                            that
                                .search('') 
                                .draw(); 
                        });
                    });
                });

                $('#chk-all-section-<?php echo $row['section_id'];?>').on('change', function() {
                    var is_checked = $(this).is(':checked');
                    $('#sectionDataTable_<?php echo $row['section_id'];?> tbody input[type=checkbox]').each(function(i, el) {
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
    #all_student_table_filter {
        margin-top: 5px !important;
        
    }  #all_student_table_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }
    #sectionDataTable_1_filter {
        margin-top: 5px !important;
    }  #sectionDataTable_1_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }  #sectionDataTable_2_filter {
        margin-top: 5px !important;
    }  #sectionDataTable_2_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    } 
  
</style>