<?php 
$edit_data		=	$this->db->get_where('exam' , array('exam_id' => $param2) )->result_array();
foreach ( $edit_data as $row):

    $exam_types = array(
        array('value' => 'E1', 'label' => '1° evaluación'),
        array('value' => 'E2', 'label' => '2° evaluación'),
        array('value' => 'E3', 'label' => '3° evaluación'),
        array('value' => 'E4', 'label' => '4° evaluación'),
        array('value' => 'E5', 'label' => '5° evaluación'),
        array('value' => 'E6', 'label' => '6° evaluación'),
        array('value' => 'E7', 'label' => '7° evaluación'),
        array('value' => 'R1', 'label' => '1° recuperatorio'),
        array('value' => 'R2', 'label' => '2° recuperatorio'),
        array('value' => 'R3', 'label' => '3° recuperatorio'),
        array('value' => 'R4', 'label' => '4° recuperatorio'),
        array('value' => 'R5', 'label' => '5° recuperatorio'),
        array('value' => 'R6', 'label' => '6° recuperatorio'),
        array('value' => 'R7', 'label' => '7° recuperatorio'),
        array('value' => 'JIIS-1', 'label' => '1° JIIS'),
        array('value' => 'JIIS-2', 'label' => '2° JIIS'),
        array('value' => 'JIIS-1-R', 'label' => '1° recuperatorio JIIS'),
        array('value' => 'JIIS-2-R', 'label' => '2° recuperatorio JIIS')
    );
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/exams/'.$row['class_id'].'/do_update/'.$row['exam_id'] , array('class' => 'form-wizard validate', 'enctype' => 'multipart/form-data'));?>
					<div class="steps-progress">
						<div class="progress-indicator"></div>
					</div>
					
					<ul>
						<li class="active">
							<a href="#tab2-1" data-toggle="tab"><span>1</span>Información básica</a>
						</li>
						<li>
							<a href="#tab2-2" data-toggle="tab"><span>2</span>Información académica</a>
						</li>
                        <li>
							<a href="#tab2-3" data-toggle="tab"><span>3</span>Confirmación</a>
						</li>
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane active" id="tab2-1">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="name">Nombre</label>
										<input class="form-control" name="name" id="name" data-validate="required" value="<?php echo $row['name'];?>" autofocus/>
									</div>
								</div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="exam_type">Tipo</label>
                                        <select name="exam_type" class="select2" id="exam_type" data-allow-clear="true" data-placeholder="Seleccionar un tipo de examen" data-validate="required">
                                            <option></option>
                                            <?php foreach ($exam_types as $exam_type): ?>
                                                <option value="<?php echo $exam_type['value']; ?>" <?php echo ($row['exam_type'] == $exam_type['value']) ? 'selected' : ''; ?>>
                                                    <?php echo $exam_type['label']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group text-center">
										<label class="control-label" for="date">Fecha</label>
										<input class="form-control text-center" name="date" id="date" data-validate="required" data-mask="date" value="<?php echo $row['date'];?>" placeholder="" />
									</div>
								</div>
							</div>
							
						</div>
						<div class="tab-pane" id="tab2-2">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ('Curso');?></label>
                                        
                                        <select name="class_id" class="form-control" data-validate="required" id="class_id" value="<?php echo $row['exam_type'];?>" 
                                            data-message-required="<?php echo ('Valor requerido');?>"
                                            onchange="get_class_sections(this.value);">
											<option value=""><?php echo ('Seleccionar');?></option>
											<?php 
												$classes = $this->db->get('class')->result_array();
												foreach($classes as $row2):
													?>
												<option value="<?php echo $row2['class_id'];?>"
                                                    <?php if($row['class_id'] == $row2['class_id'])echo 'selected';?>>
                                                            <?php echo $row2['name'];?>
                                                        </option>
												<?php
												endforeach;
											?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ('Division');?></label>
										<select name="section_id" class="form-control" id="section_selector_holder"  data-validate="required" data-message-required="<?php echo ('Valor requerido');?>"
                                                onchange="get_section_subjects();"> 
                                                <!--  -->
											<option value=""><?php echo ('Primero seleccionar el año');?></option>
										</select>
									</div>				
								</div>
							</div>
                            <div class="row">
								<div class="col-md-12">
									<div class="form-group">
                                        <label for="field-2" class="control-label"><?php echo ('Materia');?></label>
										<select name="subject_id" class="form-control" id="section_selector_holder_subject"  data-validate="required" data-message-required="<?php echo ('Valor requerido');?>">
											<option value=""><?php echo ('Primero seleccionar el año y división');?></option>
                                            
										</select>
									</div>
								</div>
                            </div>
						</div>
						<div class="tab-pane" id="tab2-3">
							<div class="form-group text-center">
								<button type="submit" class="btn btn-info"><?php echo ('Finalizar registro');?></button>
							</div>
						</div>
						
						<ul class="pager wizard">
							<li class="previous">
								<a href="#"><i class="entypo-left-open"></i> Volver</a>
							</li>
							
							<li class="next">
								<a href="#" onclick=" get_section_subjects();">Siguiente <i class="entypo-right-open"></i></a>
							</li>
						</ul>
					</div>
				
                    
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<?php
endforeach;
?>



<script type="text/javascript">

    $(document).ready(function() {
        get_section_subjects();
    });

    
	function get_class_sections(class_id) {
    	$.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_sections/' + class_id ,
            success: function(response)
            {
                // jQuery('#section_selector_holder').html(response);
                var select = $('#section_selector_holder');
                select.empty(); 
                select.append($('<option>', { value: '', text : '<?php echo ("Seleccionar"); ?>' })); 
                select.append(response); 
            }
        });
    }

    var class_id = $("#class_id").val();
    
    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_class_sections/' + class_id ,
        success: function(response)
        {
            jQuery('#section_selector_holder').html(response);
        }
    });

    // function get_section_subjects(section_id) {
    //     $.ajax({
    //         url: '<?php echo base_url();?>index.php?admin/get_section_subjects/' + section_id,
    //         success: function(response) {
    //             var select = $('#section_selector_holder_subject');
    //             select.empty(); 
    //             select.append($('<option>', { value: '', text : '<?php echo ("Seleccionar"); ?>' })); 
    //             select.append(response);

    //             var subjectId = '<?php echo $row['subject_id']; ?>';

    //             select.find('option').each(function() {
    //                 if ($(this).val() == subjectId) {
    //                     $(this).prop('selected', true);
    //                 }
    //             });
    //         }
    //     });
    // }

    function get_section_subjects() {
         var section_id = $("#section_selector_holder").val(); 
        // var section_id = $("#section_selector_holder").value; 
        // console.log(section_id);
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_section_subjects/' + section_id,
            success: function(response) {
                var select = $('#section_selector_holder_subject');
                select.empty(); 
                select.append($('<option>', { value: '', text : '<?php echo ("Seleccionar"); ?>' })); 
                select.append(response);

                var subjectId = '<?php echo $row['subject_id']; ?>';

                select.find('option').each(function() {
                    if ($(this).val() == subjectId) {
                        $(this).prop('selected', true);
                    }
                });
            }
        });
    }


</script>


</script>

<style>
	 .panel-primary, .panel-heading {
        border-color: #891818 !important;
    }  .form-groups-bordered > .form-group {
        border-color: white !important;
    }

    .panel-title {
        color: #891818 !important;
        font-weight: bold !important;
    }

    .panel-body .form-group label {
        color: #484848 !important;
		font-weight: bolder !important;
    }

	.has-switch span.switch-right.switch-right {
		background-color: #CC00C0 !important;
		color: #fff !important;
	} .has-switch span.switch-right.switch-right:hover {
		color: #fff !important;
	}

	.has-switch span.switch-small, .has-switch label.switch-small, .has-switch span.switch-sm, .has-switch label.switch-sm {
		padding-left: 10px !important;
		padding-right: 10px !important;
	}

	/* .modal-content {
		width: 700px !important;
	}

	.modal-body {
		height: auto !important;
	} */
</style>
