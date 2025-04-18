<?php
$this->db->from('teacher_aide_details');
$query = $this->db->get();
$all_teacher_aide_count = $query->num_rows();
?>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#all_teacher" data-toggle="tab">
                    <!-- <i class="entypo-menu"></i> -->
                    <?php echo ucfirst(get_phrase('all')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_teacher_aide_count; ?>
                    </span>
                </a>
            </li>
        </ul>

<div class="tab-content">
    <div class="tab-pane active" id="all_teacher">
    <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_teachers_aide"  class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                       
                    </div>
                </div>
                <br>
               <table class="table table-bordered datatable table-hover table-striped" id="table_teacher">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('photo')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('lastname')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('firstname')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('dni')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('email')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach($teachers_aide as $row):
                                if ($row['user_status_id'] == 1) {
                                    $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                } else {
                                    $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                }
                            ?>
                        <tr>
                            <td class="text-center"><img src="<?php echo $row['photo'];?>" class="img-circle" width="30" height="30"/></td>
                            <td class="text-center" style="font-weight: bold;"><?php echo $row['lastname'];?></td>
                            <td class="text-center" style="font-weight: bold;"><?php echo $row['firstname'];?></td>
                            <td class="text-center"><?php echo $row['dni'];?></td>
                            <td class="text-center"><?php echo $row['email'];?></td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/teachers_aide_profile/<?php echo $row['teacher_aide_id'];?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view_profile')); ?>">
                                    <i class="entypo-user"></i>
                                </a>
                                <a  href="<?php echo base_url();?>index.php?admin/edit_teachers_aide/<?php echo $row['teacher_aide_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                               
                                <?php if ($row['user_status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/teachers_aide/disable_teacher_aide/<?php echo $row['teacher_aide_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['user_status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/teachers_aide/enable_teacher_aide/<?php echo $row['teacher_aide_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
    </div>
               
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
        var languagePreference = '<?php echo $this->session->userdata('language_preference'); ?>';

        var $table_teacher = jQuery("#table_teacher");

        var rowCount = $('#table_teacher tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var table_teacher = $table_teacher.DataTable({
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
                    $('#table_teacher_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var table_teacher = $table_teacher.DataTable({
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
                    $('#table_teacher_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

        }

        $table_teacher.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $table_teacher.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }
     
	});
</script>

<script type="text/javascript">
    
    function reload_ajax() {
        location.reload(); 
    }

</script> 


<style>
     #table_teacher_filter {
        margin-top: 5px !important;
        
    }  #table_teacher_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }
   
</style>