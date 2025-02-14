<?php
$this->db->select('id');
$this->db->from('academic_period');
$query = $this->db->get();
$period_count = $query->num_rows();
?>

<div class="row">
	<div class="col-md-12">
    
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
                    <?php echo ucfirst(get_phrase('list')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity" style="">
                        <?php echo $period_count; ?>
                    </span>
                </a>
            </li>
		</ul>
        
		<div class="tab-content">
            <div class="tab-pane box active" id="list">
            <br>
            <div class="mt-2 mb-4">
                <a href="javascript:;" onclick="confirm_academic_period_sweet_modal('<?php echo base_url();?>index.php?admin/academic_period_add');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('add')); ?>"><i class="fa fa-plus"></i></a>
                <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style=""><i class="fa fa-refresh"></i></button>
                <div class="pull-right tab-side-elements">
                   
                </div>
            </div>
            <br>
                <table class="table table-bordered datatable table-hover table-striped" id="table_academic_period">
                	<thead>
                		<tr>
                            <th class="text-center"><?php echo ucfirst(get_phrase('period')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date_start')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date_end')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center"  width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                           
						</tr>
					</thead>
                    <tbody>
                    	<?php 
                            $count = 1;
                            foreach($academic_period as $row):

                            if ($row['status_id'] == 1) {
                                $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                            } else {
                                $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                            }
                        ?>
                        <tr>
							<td class="text-center"><?php echo $row['name'];?></td>
							<td class="text-center">
                                <?php echo date('d/m/Y', strtotime($row['start_date']));?>
                            </td>
							<td class="text-center">
                                <?php echo date('d/m/Y', strtotime($row['end_date']));?>
                            </td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a  href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_edit_academic_period/<?php echo $row['id'];?>');" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <?php if ($row['status_id'] == 1): ?>
                                                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/academic_period/disable_academic_period/<?php echo $row['id'];?>/');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                                                        <i class="entypo-block"></i>
                                                                    </a>
                                                                <?php elseif ($row['status_id'] == 0): ?>
                                                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/academic_period/enable_academic_period/<?php echo $row['id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
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
	</div>
</di>



<script type="text/javascript">
jQuery(document).ready(function($) {
    var languagePreference = '<?php echo $this->session->userdata('language_preference'); ?>';

    var $table_academic_period = jQuery("#table_academic_period");
    
    if (languagePreference === 'english') {
        var table_academic_period = $table_academic_period.DataTable({
            "order": [[0, "desc"]],
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
            "autoWidth": false, // Disable automatic column width calculation
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
                // Change the placeholder of the search input
                $('#table_academic_period_filter input[type="search"]').attr('placeholder', 'Search');
            }
        });

    } else if (languagePreference === 'spanish') {
        var table_academic_period = $table_academic_period.DataTable({
            "order": [[0, "desc"]],
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
                
                // Cambia el placeholder del input de búsqueda
                $('#table_academic_period_filter input[type="search"]').attr('placeholder', 'Buscar');
            }
        });

    }
    
    // Inicializar Select2 después de crear DataTables
    $table_academic_period.closest('.dataTables_wrapper').find('select').select2({
        minimumResultsForSearch: -1
    });

    if ($(window).width() > 767) {
        $table_academic_period.colResizable({
            liveDrag: true,
            resizeMode: 'fit',
            partialRefresh: true,
            headerOnly: true
        });
    }

    // Resaltar filas seleccionadas
    $('#table_academic_period tbody input[type=checkbox]').each(function(i, el) {
        var $this = $(el),
            $p = $this.closest('tr');
        
        $(el).on('change', function() {
            var is_checked = $this.is(':checked');
            $p[is_checked ? 'addClass' : 'removeClass']('highlight');

            // Deshabilitar temporalmente la búsqueda para evitar que se active al seleccionar una fila
            table3.columns().every(function() {
                var that = this;
                that
                    .search('') // Limpiar la búsqueda
                    .draw(); // Redibujar la tabla después de limpiar la búsqueda
            });
        });
    });

    // Manejar la selección de todas las filas
    $('#chk-all-academic-period').on('change', function() {
        var is_checked = $(this).is(':checked');
        $('#table_academic_period tbody input[type=checkbox]').each(function(i, el) {
            $(el).prop('checked', is_checked).trigger('change');
        });
    });

     $('#edit_academic_period_bulk_btn').on('click', function() {
        var selectedIds = [];
        $('#table_academic_period tbody input[type=checkbox]:checked').each(function() {
            selectedIds.push($(this).attr('id'));
        });

        if (selectedIds.length > 0) {
            var url = '<?php echo base_url();?>index.php?modal/popup/modal_academic_period_edit_bulk/' + selectedIds.join('/');
            showAjaxModal(url);
            console.log(url);
        } else {
            Swal.fire({
                title: "¡Ningún elemento seleccionado para editar!",
                text: "",
                showCloseButton: true,
                icon: "warning",
                iconColor: "#fd7e14",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Aceptar"
            })
        }
    });

    $('#delete_academic_period_bulk_btn').on('click', function() {
        var selectedLanguages = [];
        $('#table_academic_period tbody input[type=checkbox]:checked').each(function() {
            selectedLanguages.push($(this).attr('id'));
        });

        if (selectedLanguages.length > 0) {
            var url = '<?php echo base_url();?>index.php?admin/academic_period/delete_academic_period_bulk/' + selectedLanguages.join('/');
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
    #table_academic_period_filter {
        margin-top: 6px !important;
        
    }  #table_academic_period_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }

</style> 