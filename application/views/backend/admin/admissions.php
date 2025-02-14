<?php
$this->db->from('student_details');

$this->db->where('student_details.user_status_id', 0);
$this->db->where('class_id IS NULL');
$this->db->or_where('class_id', '');
$this->db->where('section_id IS NULL');
$this->db->or_where('section_id', '');

$query = $this->db->get();
$all_student_count = $query->num_rows();

$titleEN = 'Student Report in admissions - ' . date('d-m-Y');
$titleES = 'Reporte de Estudiantes en admisiones - ' . date('d-m-Y');

?>

<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
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
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;">
                        <i class="fa fa-refresh"></i>
                    </button>
                    <div class="pull-right"> 
                        <select class="form-control customSelect text-center" id="re_enrollment_student_bulk_btn">
                            <option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
                            <?php 
                                $new_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();
                                $new_academic_period_id = $new_academic_period->id;
                                $new_academic_period_name = $new_academic_period->name; 
                                
                                // Obtener las secciones disponibles para el nuevo período académico
                                $this->db->where('academic_period_id', $new_academic_period_id);
                                $sections = $this->db->get('section')->result_array();
                        
                                if (!empty($sections)) {
                                    foreach ($sections as $section_row): ?>
                                        <option value="<?php echo $section_row['section_id']; ?>" data-class-id="<?php echo $section_row['class_id']; ?>">
                                            <?php echo $section_row['name'];?>
                                        </option>
                                    <?php endforeach; 
                                } else {
                                    echo "<option value='' disabled>" . ucfirst(get_phrase('no_class_available')) . "</option>";
                                }
                            ?>
                        </select>

                        <button id="inscription-btn-bulk" class="btn btn-secondary inscription-btn-bulk" style="display:none;">
                            <?php echo ucfirst(get_phrase('re_enrollment'));?>
                        </button>
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
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('birthday')); ?></th> 
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('cell_phone')); ?></th> 
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('landline')); ?></th>
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('state')); ?></th> 
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('postalcode')); ?></th>
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('locality')); ?></th>
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('neighborhood')); ?></th>
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('address')); ?></th>
                            <th class="text-center display-column"><?php echo ucfirst(get_phrase('address_line')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('reason')); ?></th>
                            <th class="text-center" width="200"><?php echo ucfirst(get_phrase('action')); ?></th>
                            <th class="text-center" width="50">
                                <input type="checkbox" id="chk-all-students">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
    <?php 
    foreach($students as $row):
        $new_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();
        $new_academic_period_id = $new_academic_period->id;
        $new_academic_period_name = $new_academic_period->name; 
        
        // Obtener las secciones disponibles para el nuevo período académico
        $this->db->where('academic_period_id', $new_academic_period_id);
        $sections = $this->db->get('section')->result_array();

        $status_reason = strtolower($row['status_reason']);
        $is_pass_or_expulsion = ($status_reason === 'pass' || $status_reason === 'expulsion');

        if ($row['gender_id'] == 0) {
            $gender = ucfirst(get_phrase('male'));
        } else if ($row['gender_id'] == 1) {
            $gender = ucfirst(get_phrase('female'));
        } else if ($row['gender_id'] == 2) {
            $gender = ucfirst(get_phrase('other'));
        }
    ?>
    <tr>
        <td class="text-center">
            <a href="<?php echo base_url();?>index.php?admin/student_profile/<?php echo $row['student_id']; ?>">
                <img src="<?php echo $row['photo']; ?>" class="img-circle" width="30" height="30" />
            </a>
        </td>
        <td class="text-center" style="font-weight: bold;">
            <a href="<?php echo base_url();?>index.php?admin/student_profile/<?php echo $row['student_id']; ?>" style="text-decoration: none; color: inherit;">
                <?php echo $row['lastname']; ?>
            </a>
        </td>
        <td class="text-center" style="font-weight: bold;">
            <a href="<?php echo base_url();?>index.php?admin/student_profile/<?php echo $row['student_id']; ?>" style="text-decoration: none; color: inherit;">
                <?php echo $row['firstname']; ?>
            </a>
        </td>
        <td class="text-center display-column"><?php echo $gender; ?></td> 
        <td class="text-center"><?php echo $row['dni']; ?></td>
        <td class="text-center"><?php echo $row['enrollment']; ?></td>
        <td class="text-center"><?php echo $row['email'];?></td>
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
            <?php echo ucfirst(get_phrase($row['status_reason'])); ?>
        </td>
        <td class="text-center">
            <div class="course-select-wrapper" id="course-select-wrapper-<?php echo $row['student_id']; ?>" style="display: <?php echo $is_pass_or_expulsion ? 'block' : 'none'; ?>;">
                <select class="form-control customSelect course-select text-center" id="select_section_<?php echo $row['student_id']; ?>" data-student-id="<?php echo $row['student_id']; ?>">
                    <option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
                    <?php 
                    if (!empty($sections)) {
                        foreach ($sections as $section_row): ?>
                            <option value="<?php echo $section_row['section_id']; ?>">
                                <?php echo $section_row['name']; ?> 
                            </option>
                        <?php endforeach; 
                    } else {
                        echo "<option value='' disabled>" . ucfirst(get_phrase('no_class_available')) . "</option>";
                    }
                    
                    ?>
                </select>
                <button id="inscription-btn-<?php echo $row['student_id']; ?>" class="btn btn-secondary inscription-btn" style="display:none;">
                    <?php echo ucfirst(get_phrase('re_enrollment'));?>
                </button>
            </div>
        </td>
        <td class="text-center">
            <input type="checkbox" class="chk-student" id="<?php echo $row['student_id'];?>" <?php echo $is_pass_or_expulsion ? '' : 'disabled'; ?>>
        </td>
    </tr>
    <?php endforeach; ?>
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
                            columns: ':not(:eq(0)):not(:eq(16)):not(:eq(17))' 
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        className: 'btn btn-white btn-sm btn-green-hover',
                        filename: '<?php echo $titleEN; ?>',  
                        title: null, 
                        exportOptions: {
                            columns: ':not(:eq(0)):not(:eq(16)):not(:eq(17))' 
                        }
                    },
                    {
                        text: '<i class="fa fa-print"></i> Print / <i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover',
                        action: function (e, dt, node, config) {
                            window.location.href = '<?php echo base_url(); ?>index.php?admin/printStudentAdmissionsTableEN/';
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
                            columns: ':not(:eq(0)):not(:eq(16)):not(:eq(17))' 
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        className: 'btn btn-white btn-sm btn-green-hover',
                        filename: '<?php echo $titleES; ?>', 
                        title: null, 
                        exportOptions: {
                            columns: ':not(:eq(0)):not(:eq(16)):not(:eq(17))' 
                        }
                    },
                    {
                        text: '<i class="fa fa-print"></i> Imprimir / <i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover',
                        action: function (e, dt, node, config) {
                            window.location.href = '<?php echo base_url(); ?>index.php?admin/printStudentAdmissionsTableES/';
                        }
                    }
                ],
        colReorder: true, 
        initComplete: function() {
            $('#all_student_table_filter input[type="search"]').attr('placeholder', 'Buscar');
        }
    });

}

        $allStudentDataTable.closest('.dataTables_wrapper').find('select').not('.course-select').select2({
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

        // Inscripción en masa cuando se hace clic en el botón "Inscribir al curso"
        $('#inscription-btn-bulk').on('click', function () {
            var selectedSectionId = $('#re_enrollment_student_bulk_btn').val(); // Obtiene el section_id
            var selectedClassId = $('#re_enrollment_student_bulk_btn option:selected').data('class-id'); // Obtiene el class_id asociado al section_id
            var selectedStudents = [];

            // Obtener los student_id de los checkboxes seleccionados
            $('.chk-student:checked').each(function () {
                selectedStudents.push($(this).attr('id')); // El id del checkbox debe ser el student_id
            });

            if (selectedStudents.length > 0) {
                var url = '<?php echo base_url(); ?>index.php?admin/admissions_student/re_enrollment_bulk/' + selectedClassId + '/' + selectedSectionId + '/' + selectedStudents.join('/');
                console.log(url); // Para depurar y ver cómo queda la URL
                confirm_sweet_modal(url);
            } else {
                Swal.fire({
                    title: "¡<?php echo ucfirst(get_phrase('no_students_selected')); ?>!",
                    text: "",
                    showCloseButton: true,
                    icon: "warning",
                    iconColor: "#d33",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "<?php echo ucfirst(get_phrase('accept')); ?>"
                })
            }
        });

        $('#chk-all-students').on('change', function() {
            var is_checked = $(this).is(':checked');
            $('#all_student_table tbody input[type=checkbox]').each(function(i, el) {
                $(el).prop('checked', is_checked).trigger('change');
            });
        });


       
       

	});

		
</script>

<script>
$(document).ready(function () {
    $('.course-select').on('change', function () {
        var selectedValue = $(this).val();
        var studentId = $(this).data('student-id'); 

        $('.inscription-btn').hide();

        if (selectedValue !== "") {
            $('#inscription-btn-' + studentId).show();
        }
    });

    $('.inscription-btn').on('click', function () {
        var studentId = $(this).attr('id').split('-')[2];
        var sectionId = $('#select_section_' + studentId).val(); 

        if (sectionId !== "") {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php?admin/admissions_student' + '/' + 'create' + '/' + studentId + '/' + sectionId,
                success: function (response) {
                    console.log('Operación realizada exitosamente.');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error al realizar la operación:', error);
                    location.reload();
                }
            });
        }
    });

    $('#re_enrollment_student_bulk_btn').on('change', function () {
        var selectedSectionId = $(this).val();
        if (selectedSectionId !== "") {
            $('#inscription-btn-bulk').show();
        } else {
            $('#inscription-btn-bulk').hide();
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
      .display-column {
        display: none !important;
    }

     .inscription-btn, .inscription-btn-bulk {
        margin-top: 10px;
        color: #265044 !important;

    }

    .customSelect {
        background-color: var(--color-white) !important;
        color: var(--color-primary) !important;
        font-weight: 500;
        border: 4px solid var(--color-forty) !important;
        padding: 0px !important;
        border-radius: 5px !important;
        transition: all 0.3s ease !important;
    }

    .customSelect:focus {
        background-color: var(--color-forty) !important; 
        border-color: var(--color-terciary) !important;  
        outline: none !important; 
    }  .customSelect:hover {
        background-color: var(--color-forty) !important; 
        border-color: var(--color-terciary) !important;  
        outline: none !important; 
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
