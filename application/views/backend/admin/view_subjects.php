<?php
$this->db->from('subject');

if (!empty($teacher_id)) {
    $this->db->where('teacher_id', $teacher_id);
} else {
    $this->db->where('section_id', $section_id);
}

$this->db->where('status_id', 1);

$query = $this->db->get();

$all_subjects_count = $query->num_rows();

if ($all_subjects_count == 0) {
    $this->db->from('subject_history');
    
    if (!empty($teacher_id)) {
        $this->db->where('teacher_id', $teacher_id);
    } else {
        $this->db->where('section_id', $section_id);
    }

    $this->db->where('status_id', 1);
    
    $query = $this->db->get();
    $all_subjects_count = $query->num_rows();
}

?>

<div class="row selectContent">
    <div class="col-md-6">
        <div class="form-group">
            <label for="academic_period_select" class="labelSelect">
                <?php echo ucfirst(get_phrase('you_are_viewing')); ?>
            </label> 
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <select id="academic_period_select" class="form-control selectElement" onchange="return get_sections(this.value)">
                <?php
                $academic_periods = $this->db->get('academic_period')->result_array();

                foreach ($academic_periods as $period):
                    // Verifica si $academic_period_id no está vacío y coincide con $period['id']
                    if (!empty($academic_period_id) && $academic_period_id == $period['id']) {
                        $selected = 'selected="selected"';
                    } elseif (empty($academic_period_id) && $period['status_id'] == 1) {
                        // Marca por defecto el período académico con status_id = 1 si $academic_period_id está vacío
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                ?>
                    <option value="<?php echo $period['id']; ?>" data-academic-period-id="<?php echo $period['id']; ?>" <?php echo $selected; ?>>
                        <?php echo $period['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<br>

<div class="row selectContent">
    <div class="col-md-6">
        <div class="form-group">
            <label for="class_select" class="labelSelect"><?php echo ucfirst(get_phrase('class')); ?>
            </label> 
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <select id="class_select" class="form-control selectElement" onchange="location = this.value;">
                <?php
                    if (isset($academic_period_id) && !empty($academic_period_id)) {
                        // Si $academic_period_id está definido y no está vacío, buscar por su ID
                        $active_academic_period = $this->db->get_where('academic_period', array('id' => $academic_period_id))->row();

                        if ($active_academic_period) { // Validar que el período existe
                            $this->db->where('academic_period_id', $active_academic_period->id);
                            $sections = $this->db->get('section_history')->result_array();

                            foreach ($sections as $row):
                    ?>
                                <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_student_mark/<?php echo $row['section_id']; ?>"
                                    <?php if ($section_id == $row['section_id'] && $academic_period_id == $row['academic_period_id']) echo 'selected="selected"'; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                    <?php 
                            endforeach;
                        }
                    } else {
                        // Si $academic_period_id no está definido o está vacío, buscar por el período activo
                        $active_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();

                        if ($active_academic_period) { // Validar que el período existe
                            $this->db->where('academic_period_id', $active_academic_period->id);
                            $sections = $this->db->get('section')->result_array();

                            foreach ($sections as $row):
                    ?>
                                <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_subjects/<?php echo $row['section_id']; ?>"
                                    <?php if ($section_id == $row['section_id']) echo 'selected="selected"'; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                    <?php 
                            endforeach;
                        } else {
                            // No hay períodos académicos activos ni seleccionados
                            echo '<option value="">No hay secciones disponibles</option>';
                        }
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
                <?php echo ucfirst(get_phrase('all')); ?></span>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_subjects_count; ?>
                    </span>
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_subject" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                
                <?php
$this->db->from('subject');

if (!empty($teacher_id)) {
    $this->db->where('teacher_id', $teacher_id);
} else {
    $this->db->where('section_id', $section_id);
}

$subjects = $this->db->get()->result_array();

if (empty($subjects)) {
    $this->db->from('subject_history');
    
    if (!empty($teacher_id)) {
        $this->db->where('teacher_id', $teacher_id);
    } else {
        $this->db->where('section_id', $section_id);
    }

    $subjects = $this->db->get()->result_array();
}
?>

<div class="row">
    <?php foreach ($subjects as $subject): ?>
        <div class="col-md-4 mb-4">
            <a href="<?php echo base_url(); ?>index.php?admin/subject_profile/<?php echo $subject['subject_id']; ?>">
                <div class="card shadow-sm">
                        <?php
                                $folder = ($used_section_history) ? 'subject_image_history' : 'subject_image';

                                $file_url = base_url() . 'uploads/' . $folder . '/' . $subject['image']; 
                            ?>
                    <img src="<?php echo $file_url; ?>" class="card-img-top img-fluid" alt="<?php echo ucfirst($subject['name']); ?>">
                    
                    <div class="card-body">
                        <h5 class="card-title text-center"><?php echo ucfirst($subject['name']); ?></h5>
                        
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-1"><i class="entypo-user"></i> <strong><?php echo ucfirst(get_phrase('teacher'));?>:</strong></p>
                                <p class="mb-1"><i class="entypo-clipboard"></i> <strong><?php echo ucfirst(get_phrase('class'));?>:</strong></p>
                                <p class="mb-1"><i class="entypo-graduation-cap"></i> <strong><?php echo ucfirst(get_phrase('students'));?>:</strong></p>
                            </div>
                            <div class="text-end">
                                <p class="mb-1">
                                    <?php 
                                    $teacher_details = $this->crudTeacher->get_teachers_info($subject['teacher_id']);
                                    echo $teacher_details['lastname'] . ', ' . $teacher_details['firstname'];
                                    ?>
                                </p>
                                <p class="mb-1">
                                    <?php
                                    $section_details = $this->crud_model->get_section_info4($subject['section_id']);
                                    echo $section_details['name'];
                                    ?>
                                </p>
                                <p class="mb-1">
                                    <?php
                                    $total_students = $this->crud_model->get_total_students_by_section_id2($subject['section_id']);
                                    echo $total_students;
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
<br>
   
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
        function get_sections(academic_period_id) {
            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_section_content_by_academic_period/' + academic_period_id + '/view_subjects',
                success: function(response) {
                    const emptyOption = '<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>';
                    jQuery('#class_select').html(emptyOption + response);
                }
            });

        }
   
</script>
		
<script type="text/javascript">
	jQuery(document).ready(function($) {
        var languagePreference = '<?php echo $this->session->userdata('language_preference'); ?>';

        var $allExamsDataTable = jQuery("#all_exams_table");

        if (languagePreference === 'english') {
            var allExamsDataTable = $allExamsDataTable.DataTable({
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
                    $('#all_exams_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var allExamsDataTable = $allExamsDataTable.DataTable({
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
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        background-color: #ffffff;
        transition: all 0.3s ease, background-color 0.3s ease;
        position: relative;
    }

    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        background-color: #B0DFCC !important;
        transform: translateY(-5px);
    }

    .card-img-top {
        width: 100%;
        height: 150px;
        object-fit: cover;
        transition: transform 0.3s ease; 
    }

    .card-title {
        background-color: #B0DFCC;
        color: #265044; 
        padding: 5px 10px;
        border-radius: 10px;
        text-align: center;
        transition: background-color 0.3s ease;
    }

    .card:hover .card-title {
        background-color: #ffffff; 
        color: #000000;
    }

    .card:hover .card-img-top {
        transform: scale(1.2); 
    }

    .card-body {
        padding: 15px;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .details {
        color: #555;
        font-size: 14px;
    }

    .details i {
        color: #888;
        margin-right: 5px;
    }

    .d-flex {
        display: flex;
        justify-content: space-between;
    }

    .text-end {
        text-align: right;
    }

    .mb-1 {
        margin-bottom: 5px;
        color: #265044 !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .shadow-sm {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }


    #all_exams_table_filter {
        margin-top: 5px !important;
        
    }  #all_exams_table_filter input {
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