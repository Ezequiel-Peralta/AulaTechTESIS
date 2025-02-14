

<?php
$this->db->from('student_details');
$this->db->where('user_status_id', 1);
$this->db->where('section_id', $section_id);

$query = $this->db->get();
$all_student_count = $query->num_rows();
?>


<div class="row selectContent">
    <div class="col-md-6">
        <div class="form-group">
            <label for="class_select" class="labelSelect"><?php echo ucfirst(get_phrase('you_are_viewing')); ?></label>
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
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/behavior/<?php echo $row['section_id']; ?>"
                        <?php if ($section_id == $row['section_id']) echo 'selected="selected"'; ?>>
                        <?php echo $row['name']; ?>
                    </option>
                    <?php 
                    endforeach;
                } else {
                    // echo '<li><span>No hay periodos activos disponibles</span></li>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <!-- <i class="entypo-menu"></i>  -->
                    <?php echo ucfirst(get_phrase('all')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_student_count; ?>
                    </span>
                </a>
            </li>
      
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_behavior" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                      
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="all_student_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('photo')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('lastname')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('firstname')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('dni')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('email')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $count = 1;
                                foreach($students as $row):
                                ?>
                        <tr>
                            <td class="text-center"><img src="<?php echo $row['photo'];?>" class="img-circle" width="30" height="30"/></td>
                            <td class="text-center" style="font-weight: bold;"><?php echo $row['lastname'];?></td>
                            <td class="text-center" style="font-weight: bold;"><?php echo $row['firstname'];?></td>
                            <td class="text-center"><?php echo $row['dni'];?></td>
                            <td class="text-center"><?php echo $row['email'];?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/student_behavior/<?php echo $row['student_id'];?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view_behavior')); ?>">
                                    <i class="entypo-info-circled"></i>
                                </a>
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

        var $allStudentDataTable = jQuery("#all_student_table");

        if (languagePreference === 'english') {
            var allStudentDataTable = $allStudentDataTable.DataTable({
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

        $('#chk-all-students').on('change', function() {
            var is_checked = $(this).is(':checked');
            $('#all_student_table tbody input[type=checkbox]').each(function(i, el) {
                $(el).prop('checked', is_checked).trigger('change');
            });
        });

        $('#edit_language_bulk_btn').on('click', function() {
            var selectedIds = [];
            $('#all_student_table tbody input[type=checkbox]:checked').each(function() {
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
            $('#all_student_table tbody input[type=checkbox]:checked').each(function() {
                selectedLanguages.push($(this).attr('id'));
            });

            if (selectedLanguages.length > 0) {
                var url = '<?php echo base_url();?>index.php?admin/language_settings/delete_languages_bulk/' + selectedLanguages.join('/');
                confirm_sweet_modal_bulk(url);
                console.log(url);
            } else {
                Swal.fire({
                    title: "¡Ningún elemento seleccionado para deshabilitar!",
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

    #all_student_table_filter {
        margin-top: 5px !important;
        
    }  #all_student_table_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }
    #sectionDataTable_9_filter {
        margin-top: 5px !important;
    }  #sectionDataTable_9_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }  #sectionDataTable_10_filter {
        margin-top: 5px !important;
    }  #sectionDataTable_10_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    } #sectionDataTable_11_filter {
        margin-top: 5px !important;
    }  #sectionDataTable_11_filter input {
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
