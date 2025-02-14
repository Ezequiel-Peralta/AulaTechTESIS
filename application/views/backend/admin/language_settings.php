<?php
$fields_language = $this->db->list_fields('language');
$column_count_language = 0;
foreach ($fields_language as $field_language) {
    if ($field_language != 'phrase_id' && $field_language != 'phrase') {
        $column_count_language++;
    }
}

// Contar el número de elementos en la columna 'phrase'
$this->db->select('phrase');
$this->db->from('language');
$query = $this->db->get();
$phrase_count = $query->num_rows();
?>

<div class="row">
	<div class="col-md-12">
		<ul class="nav nav-tabs bordered" style="">
			<li class="<?php if(!isset($edit_profile))echo 'active';?>" style="">
                <a href="#list_language" data-toggle="tab" style=""><i class="entypo-menu"></i> 
                    <?php echo ucfirst(get_phrase('languages')); ?>
                    <!-- <span class="badge badge-success" style="background-color: #265044; border-radius: 5px; padding: 5px 10px; color: white; margin: 0px 0px 0px 5px;"> -->
                    <span class="badge badge-success badge-nav-tabs-quantity" style="">
                        <?php echo $column_count_language; ?>
                    </span>
                </a>
            </li>
			<li class="">
            	<a href="#list_phrase" data-toggle="tab"><i class="entypo-menu"></i> 
                    <?php echo ucfirst(get_phrase('phrases')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity" style="">
                        <?php echo $phrase_count; ?>
                    </span>
                </a>
            </li>
		</ul>
	
		<div class="tab-content">
            <div class="tab-pane <?php if(!isset($edit_profile))echo 'active';?>" id="list_language">
            <br>
            <div class="mt-2 mb-4">
                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_language_add/');" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style=""><i class="fa fa-plus"></i></a>
                <a href="#" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('import')); ?>" style=""><i class="fa fa-upload"></i></a>
                <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style=""><i class="fa fa-refresh"></i></button>
                <div class="pull-right tab-side-elements">
                    <a href="javascript:;" id="edit_language_bulk_btn" onclick="" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>" style=""><i class="fa fa-pencil"></i></a>
                    <a href="javascript:;" id="delete_language_bulk_btn" onclick="" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('delete')); ?>">
                        <i class="entypo-trash"></i>
                    </a>
                </div>
            </div>
            <br>
            <table class="table table-bordered datatable table-hover table-striped" id="table_list_language">
                    <thead>
                        <tr>
                            <th class="text-center"  width="50"><div><?php echo ('N°');?></div></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('language')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                        	<th class="text-center"  width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                            <th class="text-center"  width="50">
                                <!-- <input tabindex="5" type="checkbox" class="icheck-2" id="chk-all"> -->
                                <input type="checkbox" id="chk-all-language">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
$fields = $this->db->list_fields('language');
$count = 1;

foreach ($fields as $field) {
    if ($field == 'phrase_id' || $field == 'phrase') continue;

    // Obtener el estado del lenguaje
    $this->db->select('status_id');
    $this->db->from('language_status');
    $this->db->where('language_name', $field);
    $query = $this->db->get();
    $result = $query->row();
    $status = isset($result->status_id) ? $result->status_id : 0; // 0 por defecto si no se encuentra

    // Determinar la etiqueta según el estado
    if ($status == 1) {
        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
    } else {
        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
    }
    ?>
    <tr>
        <td class="text-center"><?php echo $count++; ?></td>
        <td class="text-center" style="font-weight: bold;"><?php echo ucwords($field); ?></td>
        <td class="text-center"><?php echo $status_label; ?></td>
        <td class="text-center">
           
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_phrase_language_edit/<?php echo $field;?>');" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view_definitions')); ?>">
                <i class="entypo-eye"></i>
            </a>
            <a  href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_language_edit/<?php echo $field;?>');" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                <i class="entypo-pencil"></i>
            </a>
            <a  href="javascript:;" onclick="confirm_sweet_modal('<?php echo base_url();?>index.php?admin/language_settings/delete_language/<?php echo $field;?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('delete')); ?>">
                <i class="entypo-trash"></i>
            </a>
        </td>
        <td class="text-center">
            <input type="checkbox" class="chk-language" id="<?php echo ($field); ?>">
        </td>
    </tr>
    <?php
}
?>
                    </tbody>
                 
                </table>
                 <!-- </div>  -->
			</div>
            
			<div class="tab-pane box" id="list_phrase" style="padding: 5px">
                <br>
                <div class="mt-2 mb-4">
                    <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_phrase_add/');" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style=""><i class="fa fa-plus"></i></a>
                    <a href="#" class="btn btn-table btn-white btn-info-hover" title=" <?php echo ucfirst(get_phrase('import')); ?>" style=""><i class="fa fa-upload"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title=" <?php echo ucfirst(get_phrase('reload')); ?>" style=""><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                        <a href="javascript:;" id="edit_phrase_bulk_btn" class="btn btn-table btn-white btn-orange-hover" title=" <?php echo ucfirst(get_phrase('edit')); ?>" style=""><i class="fa fa-pencil"></i></a>
                        <a href="javascript:;" id="delete_phrase_bulk_btn" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('delete')); ?>"><i class="entypo-trash"></i></a>
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="table_list_phrase">
                    <thead>
                        <tr>
                            <th class="text-center"  width="50"><?php echo ('N°');?></th>
                            <th class="text-center" width="300"> <?php echo ucfirst(get_phrase('phrase')); ?></th>
                        	<th class="text-center" width="100"> <?php echo ucfirst(get_phrase('action')); ?></th>
                            <th class="text-center" width="50">
                                <input type="checkbox" id="chk-all-phrase">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $this->db->select('phrase_id, phrase');
                        $query = $this->db->get('language');
                        $phrases = $query->result_array();
                                $count = 1;
                                foreach ($phrases as $phrase) 
								{

                                    ?>
                    	<tr>
                            <td class="text-center"><?php echo $count++; ?></td>
                            <td class="text-center"><?php echo $phrase['phrase']; ?></td>
                            <td class="text-center">
                                <a type="button" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_phrase_edit/<?php echo $phrase['phrase_id'];?>/<?php echo urlencode($phrase['phrase']); ?>');" class="btn btn-table btn-white btn-orange-hover" title=" <?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <button type="button" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/language_settings/delete_phrase/<?php echo $phrase['phrase_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title=" <?php echo ucfirst(get_phrase('delete')); ?>">
                                    <i class="entypo-trash"></i>
                                </button>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" id="<?php echo $phrase['phrase']; ?>">
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
			</div>
		</div>
	</div>
</div>

<script>
    $(document).ready(function() {
        $('#btn-sucessOpen').click(function() {
            Swal.fire({
                title: 'Good job!',
                text: 'You clicked the button!',
                icon: 'success'
            });
        });

        $('.icheck-2').iCheck({
            checkboxClass: 'icheckbox_square-green'
        });

        
    });
</script>


<script type="text/javascript">
jQuery(document).ready(function($) {
    var languagePreference = '<?php echo $this->session->userdata('language_preference'); ?>';

    var $table3 = jQuery("#table_list_language");

    
    if (languagePreference === 'english') {
        var table3 = $table3.DataTable({
            "language": {
                "search": "", // Hide the "Search:" label
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
                $('#table_list_language_filter input[type="search"]').attr('placeholder', 'Search');
            }
        });

    } else if (languagePreference === 'spanish') {
        var table3 = $table3.DataTable({
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
                $('#table_list_language_filter input[type="search"]').attr('placeholder', 'Buscar');
            }
        });

    }
    
    // Inicializar Select2 después de crear DataTables
    $table3.closest('.dataTables_wrapper').find('select').select2({
        minimumResultsForSearch: -1
    });

    if ($(window).width() > 767) {
        $table3.colResizable({
            liveDrag: true,
            resizeMode: 'fit',
            partialRefresh: true,
            headerOnly: true
        });
    }

    // Resaltar filas seleccionadas
    $('#table_list_language tbody input[type=checkbox]').each(function(i, el) {
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
    $('#chk-all-language').on('change', function() {
        var is_checked = $(this).is(':checked');
        $('#table_list_language tbody input[type=checkbox]').each(function(i, el) {
            $(el).prop('checked', is_checked).trigger('change');
        });
    });

     $('#edit_language_bulk_btn').on('click', function() {
        var selectedIds = [];
        $('#table_list_language tbody input[type=checkbox]:checked').each(function() {
            selectedIds.push($(this).attr('id'));
        });

        if (selectedIds.length > 0) {
            var url = '<?php echo base_url();?>index.php?modal/popup/modal_language_edit_bulk/' + selectedIds.join('/');
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

    $('#delete_language_bulk_btn').on('click', function() {
        var selectedLanguages = [];
        $('#table_list_language tbody input[type=checkbox]:checked').each(function() {
            selectedLanguages.push($(this).attr('id'));
        });

        if (selectedLanguages.length > 0) {
            var url = '<?php echo base_url();?>index.php?admin/language_settings/delete_languages_bulk/' + selectedLanguages.join('/');
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


    var $table4 = jQuery("#table_list_phrase");

    if (languagePreference === 'english') {
        var table4 = $table4.DataTable({
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
                $('#table_list_phrase_filter input[type="search"]').attr('placeholder', 'Search');
            }
        });
    } else if (languagePreference === 'spanish') {
        var table4 = $table4.DataTable({
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
                $('#table_list_phrase_filter input[type="search"]').attr('placeholder', 'Buscar');
            }
        });
    }

    // Inicializar Select2 después de crear DataTables
    $table4.closest('.dataTables_wrapper').find('select').select2({
        minimumResultsForSearch: -1
    });

    if ($(window).width() > 767) {
        $table4.colResizable({
            liveDrag: true,
            resizeMode: 'fit',
            partialRefresh: true,
            headerOnly: true
        });
    }

    // Resaltar filas seleccionadas
    $('#table_list_phrase tbody input[type=checkbox]').each(function(i, el) {
        var $this = $(el),
            $p = $this.closest('tr');
        
        $(el).on('change', function() {
            var is_checked = $this.is(':checked');
            $p[is_checked ? 'addClass' : 'removeClass']('highlight');

            table4.columns().every(function() {
                var that = this;
                that
                    .search('') 
                    .draw(); 
            });
        });
    });

    // Manejar la selección de todas las filas
    $('#chk-all-phrase').on('change', function() {
        var is_checked = $(this).is(':checked');
        $('#table_list_phrase tbody input[type=checkbox]').each(function(i, el) {
            $(el).prop('checked', is_checked).trigger('change');
        });
    });
   
    $('#edit_phrase_bulk_btn').on('click', function() {
        var selectedIds = [];
        $('#table_list_phrase tbody input[type=checkbox]:checked').each(function() {
            selectedIds.push($(this).attr('id'));
        });

        if (selectedIds.length > 0) {
            var url = '<?php echo base_url();?>index.php?modal/popup/modal_phrase_edit_bulk/' + selectedIds.join('/');
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

    $('#delete_phrase_bulk_btn').on('click', function() {
        var selectedLanguages = [];
        $('#table_list_phrase tbody input[type=checkbox]:checked').each(function() {
            selectedLanguages.push($(this).attr('id'));
        });

        if (selectedLanguages.length > 0) {
            var url = '<?php echo base_url();?>index.php?admin/language_settings/delete_phrase_bulk/' + selectedLanguages.join('/');
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


    <!-- <script type="text/javascript">
        jQuery(document).ready(function($) {
            var sidebarMenu = $('.sidebar-menu');
            
            if (sidebarMenu.hasClass('fixed')) {
                sidebarMenu.removeClass('fixed');
            }
        });
    </script> -->

<style>
    #table_list_language_filter {
        margin-top: 6px !important;
        
    }  #table_list_language_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }

    #table_list_phrase_filter {
        margin-top: 6px !important;
        
    }  #table_list_phrase_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }
</style> 
