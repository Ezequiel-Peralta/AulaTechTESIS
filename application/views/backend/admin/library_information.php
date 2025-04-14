
<div class="row selectContent">
    <div class="col-md-6">
        <div class="form-group">
            <label for="class_select" class="labelSelect"><?php echo ucfirst(get_phrase('you_are_viewing')); ?>
            </label> 
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
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/library_information/<?php echo $row['section_id']; ?>"
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
                    <a href="#tab-<?php echo $section_id; ?>" data-toggle="tab">
                        <?php echo $section_data['name']; ?>
                        <span class="badge badge-success badge-nav-tabs-quantity">
                            <?php echo $section_subject_count; ?>
                        </span>
                    </a>
                </li>
        </ul>
        
        <div class="tab-content">
                <div class="tab-pane active" id="tab-<?php echo $section_data['section_id']; ?>">
                    <br>
                    <div class="mt-2 mb-4">
                        <a href="<?php echo base_url(); ?>index.php?admin/add_library" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                        <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    </div>
                    <br>
                    <div class="panel-group joined" id="accordion-test-1">
                        <?php
                        $subjects = $this->Subjects_model->get_subjects_and_library_by_section($section_data['section_id']);
                        foreach ($subjects as $subject):
                        ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title text-center">
                                        <a data-toggle="collapse" data-parent="#accordion-test-1" href="#collapse-section<?php echo $subject['subject_id']; ?>">
                                            <i class="entypo-graduation-cap"></i>  <?php echo ucfirst($subject['subject_name']); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-section<?php echo $subject['subject_id']; ?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <center>
                                            <table class="table table-bordered datatable table-hover table-striped" id="dataTable_<?php echo $subject['subject_id']; ?>">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center"><?php echo ucfirst(get_phrase('name')); ?></th>
                                                        <th class="text-center"><?php echo ucfirst(get_phrase('description')); ?></th>
                                                        <th class="text-center"><?php echo ucfirst(get_phrase('date')); ?></th>
                                                        <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                                                        <th class="text-center"><?php echo ucfirst(get_phrase('action')); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($subject['files'] as $file): ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo ucfirst($file['file_name']); ?></td>
                                                            <td class="text-center"><?php echo ucfirst($file['file_description']); ?></td>
                                                            <td class="text-center"><?php echo date('d/m/Y', strtotime($file['file_date'])); ?></td>
                                                            <td class="text-center">
                                                                <?php echo ($file['file_status_id'] == 1) ? '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>' : '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>'; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php if (!empty($file['url_file'])): ?>
                                                                    <a href="<?php echo $file['url_file']; ?>" download class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('download')); ?>">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                                <a href="<?php echo base_url(); ?>index.php?admin/edit_library/<?php echo $file['library_id']; ?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                                                    <i class="entypo-pencil"></i>
                                                                </a>
                                                                <?php if ($file['file_status_id'] == 1): ?>
                                                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/library/disable_file/<?php echo $file['library_id'];?>/<?php echo $section_data['section_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                                                        <i class="entypo-block"></i>
                                                                    </a>
                                                                <?php elseif ($file['file_status_id'] == 0): ?>
                                                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/library/enable_file/<?php echo $file['library_id'];?>/<?php echo $section_data['section_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                                                        <i class="fa fa-check-circle-o"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function reload_ajax() {
        location.reload(); 
    }
</script>

<script type="text/javascript">
	jQuery(document).ready(function($) {
        var languagePreference = '<?php echo $this->session->userdata('language_preference'); ?>';

            <?php
                        $subjects = $this->Subjects_model->get_subjects_by_section($section_data['section_id']);
                        foreach ($subjects as $row):
                        ?>

            var $SubjectDataTable_<?php echo $row['subject_id'];?> = jQuery("#dataTable_<?php echo $row['subject_id'];?>");

            if (languagePreference === 'english') {
                var SubjectDataTable_<?php echo $row['subject_id'];?> = $SubjectDataTable_<?php echo $row['subject_id'];?>.DataTable({
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
                    }
                });

            } else if (languagePreference === 'spanish') {
                var SubjectDataTable_<?php echo $row['subject_id'];?> = $SubjectDataTable_<?php echo $row['subject_id'];?>.DataTable({
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
                    }
                });

            }

                // Inicializar Select2 después de crear DataTables
                $SubjectDataTable_<?php echo $row['subject_id'];?>.closest('.dataTables_wrapper').find('select').select2({
                    minimumResultsForSearch: -1
                });


               
        <?php endforeach;?>

       

	});

		
</script>

<script>
  $(document).ready(function() {
    $('.input').on('keydown', function(e) {
        const inputs = $('.input');
        const index = inputs.index(this);

        function focusNextEnabledInput(startIndex, step) {
            let newIndex = startIndex + step;
            while (newIndex >= 0 && newIndex < inputs.length) {
                const newInput = inputs.eq(newIndex);
                if (!newInput.prop('disabled')) {
                    newInput.focus();
                    break;
                }
                newIndex += step;
            }
        }

        if (e.which === 37) { // left arrow key
            if (index > 0) {
                focusNextEnabledInput(index, -1);
            }
        } else if (e.which === 39) { // right arrow key
            if (index < inputs.length - 1) {
                focusNextEnabledInput(index, 1);
            }
        } else if (e.which === 38) { // up arrow key
            const cols = $(this).closest('tr').find('.input').length;
            if (index - cols >= 0) {
                focusNextEnabledInput(index, -cols);
            }
        } else if (e.which === 40) { // down arrow key
            const cols = $(this).closest('tr').find('.input').length;
            if (index + cols < inputs.length) {
                focusNextEnabledInput(index, cols);
            }
        }
    });

});

</script>

<style>
    input:disabled {
        background-color: #DFDFDF !important;
        color: black !important;
        font-weight: 300 !important;
    }

      .mark-input {
        width: 100%; 
        height: 100%;
        max-width: 40px;
        text-align: center; 
        box-sizing: border-box; 
        border: none; 
        padding-right: 0 !important;
        padding-left: 0 !important;
        margin: 0 !important; 
    } .date-mark-input {
        width: 100%; 
        max-width: 80px;
        text-align: center; 
        box-sizing: border-box; 
        border: none;
        margin: 0 !important; 
    } .date-cell {
        padding-right: 0 !important;
        padding-left: 0 !important;
    } .evaluation-cell {
        /* padding: 0 !important; */
        padding-right: 0 !important;
        padding-left: 0 !important;
    } .recovery-cell {
        padding-right: 0 !important;
        padding-left: 0 !important;
    }

    .evaluation-cell input,
    .recovery-cell input, .date-cell input {
        border: 1px solid #fff;
        padding: 5px;
    }
  
    .form-control {
        background-color: #ebebeb !important;
    }
    
    a[data-toggle="tab"] i {
        color: black !important;
    }

    .active a[data-toggle="tab"] i {
        color: #265044 !important;
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
        background-color: #fff !important;
    }

    .menuIcon {
        color: black;
    }
    .btn-group {
        text-align: center !important;
        align-items: center !important;
    }

    .nav-tabs.bordered + .tab-content {
        border: 5px solid white !important;
        border-top: 0;
        -webkit-border-radius: 0 0 3px 3px;
        -webkit-background-clip: padding-box;
        -moz-border-radius: 0 0 3px 3px;
        -moz-background-clip: padding;
        border-radius: 0 0 3px 3px;
        background-clip: padding-box;
        padding: 10px 15px;
        margin-bottom: 20px;
    }

    .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
        border: 5px solid white !important;
        border-bottom-color: transparent !important;
    }

    .nav-tabs .active a {
        color: #265044 !important;
        font-weight: bolder !important;
    }

    .nav-tabs li a {
        color: black !important;
        font-weight: bold !important;
    }

    .dataTables_wrapper {
        color: #484848 !important;
    }

    .dataTable thead tr th {
        color: #265044 !important;
        font-weight: bold !important;
    }

    .padded label {
        color: #265044 !important;
        font-weight: bold !important;
    }
    .even {
        background-color: white !important;
    }

    .btn-info {
        font-weight: bold !important;
    }

    .btn-group ul li a {
        background-color: #265044 !important;
        color: white !important;
        border-radius: 0px !important;
        border-bottom: 2px solid rgba(69, 74, 84, 0.4);
    }

    /* Estilo para cambiar el color de fondo en hover */
    .btn-group ul li a:hover {
        background-color: #A5B299 !important;
        border-radius: 0px !important;
    }

    .box-content {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        background-color: white !important;
    }

    .row th {
        background-color: #B0DFCC !important;
    } .row th div {
        color: white !important;
        font-weight: 600 !important;
    }

    .dataTables_wrapper table thead tr th.sorting_asc:before,
    .dataTables_wrapper table thead tr th.sorting_desc:before {
    color: white !important;
    }

    .table tbody tr td {
        background-color: #fff !important;
    }  .table tbody tr:hover td {
        background-color: #f2f2f4 !important;
    }

    .nav-tabs li a:hover {
        background-color: #A5B299 !important;
    }  .nav-tabs li.active a:hover {
        background-color: #fff !important;
    }

    .tile-stats .icon {
        margin-bottom: 10px !important;
    }
    .tile-stats .icon i {
        font-size: 110px !important;
     
        margin-right: 0px !important;
        padding: 0px 90px 0px 10px;
    }

    .tile-stats {
        padding: 40px 0px 40px 0px!important;
    }

    .tile-stats .num, .sub-num {
        background-color: #A5B299;
    }
    .num {
        padding-left: 20px !important;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        z-index: 0 !important;
    }
    .sub-num {
        margin-top: -1px !important;
        padding-left: 20px !important;
        padding-bottom: 10px !important;
        border-bottom-right-radius: 5px;
        border-bottom-left-radius: 5px;
        z-index: 0 !important;
    }
</style>
