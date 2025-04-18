<?php
$this->db->from('teacher_aide');
$query = $this->db->get();
$all_teacher_aide_count = $query->num_rows();
?>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#all_teacher_aide" data-toggle="tab">
                    <i class="entypo-menu"></i>
                    <?php echo ucfirst(get_phrase('all')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_teacher_aide_count; ?>
                    </span>
                </a>
            </li>
        </ul>

<div class="tab-content">
    <div class="tab-pane active" id="all_teacher_aide">
    <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/teachers_aide_add"  class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <a href="#" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('import')); ?>" style="padding: 6px 10px;"><i class="fa fa-upload"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                        <a href="javascript:;" id="delete_teacher_aide_bulk_btn" onclick="" class="btn btn-table btn-white btn-danger-hover" title="desactivar">
                            <i class="entypo-block"></i>
                        </a>
                    </div>
                </div>
                <br>
               <table class="table table-bordered datatable table-hover table-striped" id="table_teacher_aide">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ('N°');?></th>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('photo')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('name')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('dni')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('email')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                            <th class="text-center" width="50">
                                <input type="checkbox" id="chk-all-teacher-aide">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
						   	$count = 1; 
                            foreach($teachers_aide as $row):?>
                        <tr>
							<td class="text-center"><?php echo $count++;?></td>
							<td class="text-center"><img src="<?php echo $row['photo'];?>" class="img-circle" width="30" height="30"/></td>
                            <td class="text-center"><?php echo $row['firstname'];?> <?php echo $row['lastname'];?></td>
							<td class="text-center"><?php echo $row['dni'];?></td>
                            <td class="text-center"><?php echo $row['email'];?></td>
                            <td class="text-center">
                                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_teacher_aide_profile/<?php echo $row['teacher_aide_id'];?>');" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('profile')); ?>">
                                    <i class="entypo-user"></i>
                                </a>
                                <a  href="<?php echo base_url();?>index.php?admin/teachersAide_edit/<?php echo $row['teacher_aide_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <a  href="javascript:;" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/teachersAide/delete/<?php echo $row['teacher_aide_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="desactivar">
                                    <i class="entypo-block"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="chk-teacher-aide" id="<?php echo $row['teacher_aide_id'];?>">
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

        var $table_teacher_aide = jQuery("#table_teacher_aide");

        if (languagePreference === 'english') {
            var table_teacher_aide = $table_teacher_aide.DataTable({
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
                    $('#table_teacher_aide_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var table_teacher_aide = $table_teacher_aide.DataTable({
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
                    $('#table_teacher_aide_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

        }

        $table_teacher_aide.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $table_teacher_aide.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }

        // Resaltar filas seleccionadas
        $('#table_teacher_aide tbody input[type=checkbox]').each(function(i, el) {
            var $this = $(el),
                $p = $this.closest('tr');
            
            $(el).on('change', function() {
                var is_checked = $this.is(':checked');
                $p[is_checked ? 'addClass' : 'removeClass']('highlight');

                table_teacher_aide.columns().every(function() {
                    var that = this;
                    that
                        .search('') 
                        .draw(); 
                });
            });
        });

        $('#chk-all-teacher-aide').on('change', function() {
            var is_checked = $(this).is(':checked');
            $('#table_teacher_aide tbody input[type=checkbox]').each(function(i, el) {
                $(el).prop('checked', is_checked).trigger('change');
            });
        });

        $('#delete_teacher_aide_bulk_btn').on('click', function() {
            var selectedLanguages = [];
            $('#table_teacher_aide tbody input[type=checkbox]:checked').each(function() {
                selectedLanguages.push($(this).attr('id'));
            });

            if (selectedLanguages.length > 0) {
                var url = '<?php echo base_url();?>index.php?admin/teachersAide/delete_teachers_aide_bulk/' + selectedLanguages.join('/');
                confirm_sweet_modal_bulk(url);
                console.log(url);
            } else {
                Swal.fire({
                    title: "¡Ningún elemento seleccionado para eliminar!",
                    text: "",
                    showCloseButton: true,
                    icon: "warning",
                    iconColor: "#d33",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Aceptar"
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
     #table_teacher_aide_filter {
        margin-top: 5px !important;
        
    }  #table_teacher_aide_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }
   
</style>