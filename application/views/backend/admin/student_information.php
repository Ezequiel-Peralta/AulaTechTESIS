

<?php
$this->db->from('student_details');
$this->db->where('user_status_id', 1);
$this->db->where('section_id', $section_id);

$query = $this->db->get();
$all_student_count = $query->num_rows();

$section_name = $this->crud_model->get_section_name($section_id);

$titleEN = 'Student report - ' . $section_name . ' - ' . date('d-m-Y');
$titleES = 'Reporte de Estudiantes - ' . $section_name . ' - ' . date('d-m-Y');
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
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/student_information/<?php echo $row['section_id']; ?>"
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
                    <a href="<?php echo base_url(); ?>index.php?admin/student_add" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <a href="<?php echo base_url(); ?>index.php?admin/student_bulk_add" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('import')); ?>" style="padding: 6px 10px;"><i class="fa fa-upload"></i></a>
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
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('gender')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('dni')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('enrollment')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('email')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('birthday')); ?></th> 
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('cell_phone')); ?></th> 
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('landline')); ?></th> 
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('state')); ?></th> 
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('postalcode')); ?></th>
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('locality')); ?></th>
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('neighborhood')); ?></th>
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('address')); ?></th>
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('address_line')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                foreach($students as $row):
                                    if ($row['user_status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
                                    if ($row['gender_id'] == 0) {
                                        $gender = ucfirst(get_phrase('male'));
                                    } else if ($row['gender_id'] == 1) {
                                        $gender = ucfirst(get_phrase('female'));
                                    } else if ($row['gender_id'] == 2) {
                                        $gender = ucfirst(get_phrase('other'));
                                    }

                                ?>
                        <tr>
                            <td class="text-center"><img src="<?php echo $row['photo'];?>" class="img-circle" width="30" height="30"/></td>
                            <td class="text-center"><?php echo $row['lastname'];?></td>
                            <td class="text-center"><?php echo $row['firstname'];?></td>
                            <td class="text-center display-column"><?php echo $gender; ?></td> 
                            <td class="text-center"><?php echo $row['dni'];?></td>
                            <td class="text-center"><?php echo $row['enrollment'];?></td>
                            <td class="text-center"><?php echo $row['email'];?></td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center display-column"><?php echo $row['birthday']; ?></td> 
                            <td class="text-center display-column"><?php echo $row['phone_cel']; ?></td> 
                            <td class="text-center display-column"><?php echo $row['phone_fij']; ?></td> 
                            <td class="text-center display-column"><?php echo $row['state']; ?></td> 
                            <td class="text-center display-column"><?php echo $row['postalcode']; ?></td> 
                            <td class="text-center display-column"><?php echo $row['locality']; ?></td> 
                            <td class="text-center display-column"><?php echo $row['neighborhood']; ?></td> 
                            <td class="text-center display-column"><?php echo $row['address']; ?></td> 
                            <td class="text-center display-column"><?php echo $row['address_line']; ?></td> 
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/student_profile/<?php echo $row['student_id'];?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view_profile')); ?>">
                                    <i class="entypo-user"></i>
                                </a>
                                <a  href="<?php echo base_url();?>index.php?admin/student_edit/<?php echo $row['student_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                               
                                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_inactive_student/<?php echo $row['student_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title=" <?php echo ucfirst(get_phrase('disabled')); ?>">
                                    <i class="entypo-block"></i>
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

        var rowCount = $('#all_student_table tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : ""; 

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
                        className: 'btn btn-white btn-sm btn-info-hover',
                        title: null,
                        exportOptions: {
                            columns: ':not(:eq(0)):not(:eq(6)):not(:eq(15))' 
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        className: 'btn btn-white btn-sm btn-green-hover',
                        filename: '<?php echo $titleEN; ?>', 
                        title: null, 
                        exportOptions: {
                            columns: ':not(:eq(0)):not(:eq(6)):not(:eq(15))' 
                        }
                    },
                    {
                        text: '<i class="fa fa-print"></i> Print / <i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover',
                        action: function (e, dt, node, config) {
                            window.location.href = '<?php echo base_url(); ?>index.php?admin/printStudentTableEN/<?php echo $section_id;?>';
                        }
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
                        className: 'btn btn-white btn-sm btn-info-hover',
                        title: null,
                        exportOptions: {
                            columns: ':not(:eq(0)):not(:eq(6)):not(:eq(15))' 
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        className: 'btn btn-white btn-sm btn-green-hover',
                        filename: '<?php echo $titleES; ?>', 
                        title: null, 
                        exportOptions: {
                            columns: ':not(:eq(0)):not(:eq(6)):not(:eq(15))' 
                        }
                    },
                    {
                        text: '<i class="fa fa-print"></i> Imprimir / <i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover',
                        action: function (e, dt, node, config) {
                            window.location.href = '<?php echo base_url(); ?>index.php?admin/printStudentTableES/<?php echo $section_id;?>';
                        }
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

     

        

     


       
       

	});

		
</script>

<script type="text/javascript">
    
    function reload_ajax() {
        location.reload(); 
    }

</script> 


<style>

    .display-column {
        display: none !important;
    }

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
