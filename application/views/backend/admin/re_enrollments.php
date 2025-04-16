

<?php
$all_student_count = count($students);

?>


<div class="row selectContent">
    <div class="col-md-6">
        <div class="form-group">
            <label for="class_select" class="labelSelect"><?php echo ucfirst(get_phrase('you_are_viewing'));?></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <select id="class_select" class="form-control selectElement" onchange="location = this.value;">
                <?php
                foreach ($sections as $row):
                ?>
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/re_enrollments/<?php echo $row['section_id']; ?>"
                        <?php if ($section_id == $row['section_id']) echo 'selected="selected"'; ?>>
                        <?php echo $row['name']; ?>  -  <?php echo $this->crud_model->get_academic_period_name_per_section_history($row['section_id']);?>
                    </option>
                    <?php 
                    endforeach;
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
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>

                    <div class="pull-right"> 
                        <select class="form-control customSelect text-center" id="re_enrollment_student_bulk_btn">
                            <option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
                            <?php 
                                $old_section_id = $section_id;

                                $old_section = $this->db->get_where('section_history', array('section_id' => $old_section_id))->row();

                                $new_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();
                                $new_academic_period_id = $new_academic_period->id;

                                $this->db->where('academic_period_id', $new_academic_period_id);
                                $this->db->where('class_id', $old_section->class_id + 1);
                                $sections = $this->db->get('section')->result_array();
                        
                                if (!empty($sections)) {
                                    foreach ($sections as $section_row): ?>
                                        <option value="<?php echo $section_row['section_id']; ?>" data-class-id="<?php echo $section_row['class_id']; ?>">
                                            <?php echo $section_row['name'];?>
                                        </option>
                                    <?php endforeach; 
                                } else {
                                    echo "<option value='' disabled>No hay cursos disponibles</option>";
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
                            <th class="text-center"><?php echo ucfirst(get_phrase('dni')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('enrollment')); ?></th> 
                            <th class="text-center">Deshabilitar</th> 
                            <th class="text-center" width="200"><?php echo ucfirst(get_phrase('action')); ?></th>
                            <th class="text-center" width="50">
                                <input type="checkbox" id="chk-all-students">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
    <?php 
    foreach($students as $row):
        $old_section_id = $section_id;

        $old_section = $this->db->get_where('section_history', array('section_id' => $old_section_id))->row();

        $new_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();
        $new_academic_period_id = $new_academic_period->id;

        $this->db->where('academic_period_id', $new_academic_period_id);
        $this->db->where('class_id', $old_section->class_id + 1);
        $sections = $this->db->get('section')->result_array();
    ?>
    <tr>
        <td class="text-center">
            <a href="<?php echo base_url();?>index.php?admin/students_profile/<?php echo $row['student_id']; ?>">
                <img src="<?php echo $row['photo']; ?>" class="img-circle" width="30" height="30" />
            </a>
        </td>
        <td class="text-center" style="font-weight: bold;">
            <a href="<?php echo base_url();?>index.php?admin/students_profile/<?php echo $row['student_id']; ?>" style="text-decoration: none; color: inherit;">
                <?php echo $row['lastname']; ?>
            </a>
        </td>
        <td class="text-center" style="font-weight: bold;">
            <a href="<?php echo base_url();?>index.php?admin/students_profile/<?php echo $row['student_id']; ?>" style="text-decoration: none; color: inherit;">
                <?php echo $row['firstname']; ?>
            </a>
        </td>
        <td class="text-center"><?php echo $row['dni']; ?></td>
        <td class="text-center"><?php echo $row['enrollment']; ?></td>
        <td class="text-center">
                    <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_inactive_student/<?php echo $row['student_id'];?>/');" class="btn btn-table btn-white btn-danger-hover" title=" <?php echo ucfirst(get_phrase('disabled')); ?>">
                                    <i class="entypo-block"></i>
                                </a>
                            </td>
        <td class="text-center">
            <select class="form-control customSelect course-select text-center" id="select_section_<?php echo $row['student_id']; ?>" data-student-id="<?php echo $row['student_id']; ?>">
                <option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
                <?php 
               
                    if (!empty($sections)) {
                        foreach ($sections as $section_row): ?>
                            <option value="<?php echo $section_row['section_id']; ?>">
                                <?php echo $section_row['name'];?>
                            </option>
                        <?php endforeach; 
                    } else {
                        echo "<option value='' disabled>No hay cursos disponibles</option>";
                    }
                ?>
            </select>

            <button id="inscription-btn-<?php echo $row['student_id']; ?>" class="btn btn-secondary inscription-btn" style="display:none;">
                <?php echo ucfirst(get_phrase('re_enrollment'));?>
            </button>
        </td>
        <td class="text-center">
                                <input type="checkbox" class="chk-student" id="<?php echo $row['student_id'];?>">
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
                var url = '<?php echo base_url(); ?>index.php?admin/re_enrollments_student/re_enrollment_bulk/' + selectedClassId + '/' + selectedSectionId + '/' + selectedStudents.join('/');
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

<script type="text/javascript">
    
    function reload_ajax() {
        location.reload(); 
    }

</script> 

<script>
$(document).ready(function () {
    // Mostrar botón cuando se selecciona un curso
    $('.course-select').on('change', function () {
        var selectedValue = $(this).val();
        var studentId = $(this).data('student-id');
        $('.inscription-btn').hide();  // Ocultar todos los botones de inscripción
        if (selectedValue !== "") {
            $('#inscription-btn-' + studentId).show();  // Mostrar el botón correspondiente
        }
    });

    // Realizar la reinscripción
    $('.inscription-btn').on('click', function () {
        var studentId = $(this).attr('id').split('-')[2];
        var sectionId = $('#select_section_' + studentId).val();

        if (sectionId !== "") {
                $.ajax({
                    url: '<?php echo base_url(); ?>index.php?admin/re_enrollments_student/create/' + studentId + '/' + sectionId,
                    success: function (response) {
                        console.log('Operación realizada exitosamente.');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
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

<style>

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
