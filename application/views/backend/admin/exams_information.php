<?php
$this->db->from('exam');
$this->db->where('section_id', $section_id);
$query = $this->db->get();
$all_exams_count = $query->num_rows();
?>




<?php
if (empty($subject_id)) {
    ?>
    <div class="row selectContent">
        <div class="col-md-6">
            <div class="form-group">
                <label for="class_select" class="labelSelect"><?php echo ucfirst(get_phrase('you_are_viewing')); ?>
                </label> 
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <select id="class_select" class="form-control selectElement" onchange="location = this.value;">
                    <?php
                    $active_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();

                    if ($active_academic_period) {
                        $this->db->where('academic_period_id', $active_academic_period->id);
                        $sections = $this->db->get('section')->result_array();

                        foreach ($sections as $row):
                    ?>
                        <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/exams_information/<?php echo $row['section_id']; ?>"
                            <?php if ($section_id == $row['section_id']) echo 'selected="selected"'; ?>>
                            <?php echo $row['name']; ?>
                        </option>
                    <?php 
                        endforeach;
                    } else {
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <?php
} 
?>

<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                <?php echo ucfirst(get_phrase('all')); ?></span>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_exams_count; ?>
                    </span>
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/exam_add" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                        <a href="javascript:;" id="enabled_exam_bulk_btn" onclick="" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                            <i class="fa fa-check-circle-o" style="font-size: 15px; margin-top: 1px;"></i>
                        </a>
                        <a href="javascript:;" id="disabled_exam_bulk_btn" onclick="" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                            <i class="entypo-block"></i>
                        </a>
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="all_exams_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('name')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('type')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('subject')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('files')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                            <th class="text-center" width="50">
                                <input type="checkbox" id="chk-all-exams">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                if (!empty($subject_id)) {
                                    $exams = $this->db->get_where('exam', array('subject_id' => $subject_id))->result_array();
                                } else {
                                    $exams = $this->db->get_where('exam', array('section_id' => $section_id))->result_array();
                                }
                                foreach($exams as $row):
                                    $date = date("d/m/Y", strtotime($row['date']));
                                
                                    if ($row['status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
                                ?>
                        <tr>
                            <td class="text-center"><?php echo $row['name'];?></td>
                            <td class="text-center"><?php echo $date; ?></td>
                            <td class="text-center">
                                <?php 
                                    $exam_type_name = $this->db->get_where('exam_type', array('id' => $row['exam_type_id']))->row()->name; 
                                    echo ucfirst(get_phrase($exam_type_name)); 
                                ?>
                            </td>

                            <td class="text-center">
                                <?php if($row['subject_id'] != '' && $row['subject_id'] != 0):?>
                                    <?php echo $this->db->get_where('subject' , array('subject_id' => $row['subject_id']))->row()->name;?>
                                <?php endif;?>
                            </td>
                          
                            <td class="text-center" title="<?php 
                                $files = json_decode($row['files'], true);
                                if (is_array($files)) {
                                    echo implode(', ', $files); // Mostrar nombres completos en el title
                                }
                            ?>">
                                <?php 
                                $files = json_decode($row['files'], true);
                                if (is_array($files)) {
                                    // Limitar a 12 caracteres y añadir "..." si es necesario
                                    $shortened_files = array_map(function($file) {
                                        return strlen($file) > 12 ? substr($file, 0, 12) . '...' : $file; // Limitar a 12 caracteres
                                    }, $files);

                                    echo implode(', ', $shortened_files); // Unir y mostrar nombres cortos
                                }
                                ?>
                            </td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/exam_edit/<?php echo $row['exam_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/exam/disable_exam/<?php echo $row['exam_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/exam/enable_exam/<?php echo $row['exam_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="chk-exam" id="<?php echo $row['exam_id'];?>">
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
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
	jQuery(document).ready(function($) {
        var languagePreference = '<?php echo $this->session->userdata('language_preference'); ?>';

        var $allExamsDataTable = jQuery("#all_exams_table");

        var rowCount = $('#all_exams_table tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var allExamsDataTable = $allExamsDataTable.DataTable({
                "order": [[1, "desc"], [2, "asc"]],
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
                    $('#all_exams_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var allExamsDataTable = $allExamsDataTable.DataTable({
                "order": [[1, "desc"], [2, "asc"]],
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
                    $('#all_exams_table_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

        }

        $allExamsDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $allExamsDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }

        // Resaltar filas seleccionadas
        $('#all_exams_table tbody input[type=checkbox]').each(function(i, el) {
            var $this = $(el),
                $p = $this.closest('tr');
            
            $(el).on('change', function() {
                var is_checked = $this.is(':checked');
                $p[is_checked ? 'addClass' : 'removeClass']('highlight');

                allExamsDataTable.columns().every(function() {
                    var that = this;
                    that
                        .search('') 
                        .draw(); 
                });
            });
        });

        $('#chk-all-exams').on('change', function() {
            var is_checked = $(this).is(':checked');
            $('#all_exams_table tbody input[type=checkbox]').each(function(i, el) {
                $(el).prop('checked', is_checked).trigger('change');
            });
        });

        $('#disabled_exam_bulk_btn').on('click', function() {
            var selectedExams = [];
            $('#all_exams_table tbody input[type=checkbox]:checked').each(function() {
                selectedExams.push($(this).attr('id'));
            });

            if (selectedExams.length > 0) {
                var url = '<?php echo base_url();?>index.php?admin/exam/disable_exam_bulk/<?php echo $section_id;?>/' + selectedExams.join('/');
                console.log(url);
                confirm_disable_sweet_modal_bulk(url);
            } else {
                Swal.fire({
                    title: "¡<?php echo ucfirst(get_phrase('no_item_selected_to_disable')); ?>!",
                    text: "",
                    showCloseButton: true,
                    icon: "warning",
                    iconColor: "#d33",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "<?php echo ucfirst(get_phrase('accept')); ?>"
                })
            }
        });

        $('#enabled_exam_bulk_btn').on('click', function() {
            var selectedExams = [];
            $('#all_exams_table tbody input[type=checkbox]:checked').each(function() {
                selectedExams.push($(this).attr('id'));
            });

            if (selectedExams.length > 0) {
                var url = '<?php echo base_url();?>index.php?admin/exam/enable_exam_bulk/<?php echo $section_id;?>/' + selectedExams.join('/');
                console.log(url);
                confirm_enable_sweet_modal_bulk(url);
            } else {
                Swal.fire({
                    title: "¡<?php echo ucfirst(get_phrase('no_item_selected_to_enable')); ?>!",
                    text: "",
                    showCloseButton: true,
                    icon: "warning",
                    iconColor: "#d33",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "<?php echo ucfirst(get_phrase('accept')); ?>"
                })
            }
        });

	});

		
</script>

<script type="text/javascript">
    
    function reload_ajax() {
        location.reload(); 
    }

</script> 


<style>

    #all_exams_table_filter {
        margin-top: 5px !important;
        
    }  #all_exams_table_filter input {
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
    } .selectContent .labelSelect {
        font-size: 20px;
        margin-top: 12px;
    } .selectContent .selectElement {
        margin-top: 12px;
        background-color: #fff;
    }

</style> 