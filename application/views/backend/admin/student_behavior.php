
<?php
$this->db->from('behavior');
$this->db->where('student_id', $student_id);

$query = $this->db->get();
$all_student_behavior_count = $query->num_rows();
?>

<div class="profile-header" style="border-radius: 15px;">
        <img src="assets/images/profile-header.jpg" class="cover-photo" alt="Cover Photo">
        <img src="<?php echo $student['photo'];?>" class="img-fluid" alt="Profile Picture" width="120" height="120">
        <div class="profile-info">
            <h2 style="font-weight: 600;">
            <?php echo $student['lastname'];?>, <?php echo $student['firstname'];?>.
            </h2>
        </div>
        <br>
    </div>

<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <!-- <i class="entypo-menu"></i>  -->
                    <?php echo ucfirst(get_phrase('all')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_student_behavior_count; ?>
                    </span>
                </a>
            </li>
      
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_behaviors/<?php echo $student_id;?>/<?php echo $student['class_id'];?>/<?php echo $student['section_id'];?>" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                      
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="all_student_table">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo ucfirst(get_phrase('type')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('comment')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $count = 1;
                                foreach($behaviors as $row):
                                    if ($row['status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
                                ?>
                        <tr>
                            <td class="text-center" style="font-weight: bold;">
                                <?php 
                                    $behavior_type_name = $this->db->get_where('behavior_type', array('behavior_type_id' => $row['behavior_type_id']))->row()->name; 
                                    echo ucfirst(get_phrase($behavior_type_name)); 
                                ?>
                            </td>
                            <td class="text-center" style="font-weight: bold;">
                                <?php echo date('d/m/Y', strtotime($row['date'])); ?>
                            </td>
                            <td class="text-center"><?php echo $row['comment'];?></td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a  href="<?php echo base_url();?>index.php?admin/edit_behavior/<?php echo $row['behavior_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/behaviors_information/disable_behavior/<?php echo $row['behavior_id'];?>/<?php echo $row['student_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/behaviors_information/enable_behavior/<?php echo $row['behavior_id'];?>/<?php echo $row['student_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
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
                "order": [[1, "desc"], [0, "asc"]],
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
        "order": [[1, "desc"], [0, "asc"]],
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

    // Asignar acciones a los botones dentro del dropdown
    $('.copyBtn').on('click', function() {
        allStudentDataTable.button('.buttons-copy').trigger();
    });
    $('.excelBtn').on('click', function() {
        allStudentDataTable.button('.buttons-excel').trigger();
    });
    $('.csvBtn').on('click', function() {
        allStudentDataTable.button('.buttons-csv').trigger();
    });
    $('.printBtn').on('click', function() {
        allStudentDataTable.button('.buttons-print').trigger();
    });
    $('.pdfBtn').on('click', function() {
        allStudentDataTable.button('.buttons-pdf').trigger();
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



<style>
    .status-dot {
        display: inline-block;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        margin-top: 2px;
        margin-left: 5px;
    }

    .status-dot.online {
        background-color: #4CAF50; 
    }

    .status-dot.offline {
        background-color: #F44336; 
    }

    .status-dot.away {
        background-color: #FFC107; 
    }

    .status-dot.busy {
        background-color: #FF5722; 
    }

    .card-body-guardian {
        border: 3px solid #ebebeb !important;
        padding: 10px 10px !important;
        border-radius: 15px !important;
    }

    .profile-header {
        background-color: #fff;
        text-align: center;
        position: relative;
        border-radius: 15px;
    }
    .profile-header img.img-fluid {
        border-radius: 50%;
        margin-top: -60px;
        border: 7px solid white;
    }
    .profile-header .cover-photo {
        width: 100%;
        height: 110px;
        object-fit: cover;
        border-radius: 15px;
    }
    .profile-info {
        margin-top: 10px;
    }
    .profile-buttons {
        margin-top: 20px;
    }
    .profile-buttons .btn {
        margin-right: 5px;
        border-radius: 10px;
    }

    .profile-buttons .profile-button-active {
        background-color: #fff !important;
        color: #265044 !important;
        border: 1px solid #265044 !important;
    }

    .profile-description {
        margin-top: 20px;
        padding: 0 20px;
        text-align: center;
    }
    .profile-details {
        margin-top: 20px;
    }
    .profile-details .info-list {
        display: block;
        padding: 0;
        list-style: none;
    }
    .profile-details .info-list li {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .profile-details .info-list li strong {
        width: 50%;
        text-align: left;
    }
    .profile-details .info-list li span {
        width: 50%;
        text-align: right;
    }

    .dropdown-menu {
        border-radius: 15px !important;
        background-color: #B0DFCC !important;
    }

    .info-title {
        color: #265044 !important; 
        font-weight: bold !important;
    }

    .info-cell {
        color: #265044 !important; 
        font-weight: 400 !important;
    }

</style>
