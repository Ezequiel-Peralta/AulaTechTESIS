<?php
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $current_date = date('Y-m-d');
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/behavior_information/create/' , array('class' => 'form-wizard validate', 'enctype' => 'multipart/form-data'));?>
				
			
					<div class="steps-progress">
						<div class="progress-indicator"></div>
					</div>
					
					<ul>
						<li class="active">
							<a href="#tab2-1" data-toggle="tab"><span>1</span><?php echo ucfirst(get_phrase( 'information'));?></a>
						</li>
						<li>
							<a href="#tab2-2" data-toggle="tab"><span>9</span><?php echo ucfirst(get_phrase( 'confirmation'));?></a>
						</li>
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane active" id="tab2-1">
                            <div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('class')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="class_id" class="form-control" data-validate="required" id="class_id" 
											data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>"
												onchange="return get_class_sections(this.value)">
											<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
											<?php 
												$classes = $this->db->get('class')->result_array();
												foreach($classes as $row3):
													?>
                                                    <option value="<?php echo $row3['class_id'];?>"
                                                        <?php if($row3['class_id'] == $class_id)echo 'selected';?>>
                                                        <?php echo $row3['name'];?>°
                                                    </option>
												<?php
												endforeach;
											?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('section')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="section_id" class="form-control" id="section_selector_holder"  data-validate="required" 
                                                onchange="get_section_students(this.value);" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                                                <option value="" selected disabled>
                                                    <?php echo ucfirst(get_phrase(empty($class_id) ? 'first_select_the_class' : 'select')); ?>
                                                </option>

                                            <?php 
											$sections = $this->crud_model->get_section_content_by_class($class_id);
											foreach ($sections as $section):
												$selected = ($section['section_id'] == $section_id) ? 'selected' : '';
											?>
											<option value="<?php echo $section['section_id']; ?>"
												<?php if($section['section_id'] == $section_id)echo 'selected';?>>
												<?php echo $section['name']; ?>
											</option>
											<?php endforeach; ?>
										</select>
									</div>				
								</div>
                                <div class="col-md-4">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('student')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="student_id" class="form-control" id="section_selector_holder_student"  data-validate="required"
                                                data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                                                <option value="" selected disabled>
                                                    <?php echo ucfirst(get_phrase(empty($class_id) ? 'first_select_the_class_and_section' : 'select')); ?>
                                                </option>
											<?php 
											$students = $this->crudStudent->get_student_info_per_section($section_id);
											foreach ($students as $student):
												$selected = ($student['student_id'] == $student_id) ? 'selected' : '';
											?>
											<option value="<?php echo $student['student_id']; ?>"
                                                   <?php echo $selected;?> >
                                                   <?php echo $student['lastname']; ?>, <?php echo $student['firstname']; ?>.
											</option>
											<?php endforeach; ?>
										</select>
									</div>				
								</div>
							</div>
							<div class="row">
                                <div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="type"><?php echo ucfirst(get_phrase( 'type'));?><span class="required-value">&nbsp;*</span></label>
										<select name="type" required class="form-control" id="type" data-allow-clear="true" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value'));?>">
											<optgroup label="<?php echo ucfirst(get_phrase( 'behavior_types'));?>">
                                                    <option value="" selected disabled><?php echo ucfirst(get_phrase( 'select'));?></option>
                                                <?php 
                                                    $behavior_types = $this->db->get('behavior_type')->result_array();
                                                    foreach($behavior_types as $row):
                                                ?>
                                                <option value="<?php echo $row['behavior_type_id'];?>">
                                                    <?php echo ucfirst(get_phrase($row['name']));?> 
                                                </option>
                                                <?php endforeach;?>
											</optgroup>
										</select>
									</div>
								</div>
                                <div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="date"><?php echo ucfirst(get_phrase( 'date'));?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="date" type="date" id="date" value="<?php echo $current_date; ?>" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value'));?>"  />
							
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label" for="comment"><?php echo ucfirst(get_phrase( 'comment'));?><span class="required-value">&nbsp;*</span></label>
										<textarea class="form-control" style="resize: vertical;" name="comment" id="comment" rows="5" placeholder=""></textarea>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab2-2">
							<div class="form-group text-center">
								<button type="submit" class="btn btn-info"><?php echo ucfirst(get_phrase( 'complete_registration'));?></button>
							</div>
						</div>
						
						<ul class="pager wizard">
							<li class="previous">
								<a href="#"><i class="entypo-left-open"></i> <?php echo ucfirst(get_phrase( 'back'));?></a>
							</li>
							
							<li class="next">
								<a href="#" class="btn btn-secondary"><?php echo ucfirst(get_phrase( 'next'));?> <i class="entypo-right-open"></i></a>
							</li>
						</ul>
					</div>
				
                    
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
		$(document).on('keydown', 'input, select, textarea', function(e) {
			var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
			if (e.keyCode === 37) { // flecha izquierda
				// focusable = form.find('li:not(.disabled) a').filter(':visible');
				focusable = form.find('input,select,textarea, li:not(.disabled) a').filter(':visible');
				next = focusable.eq(focusable.index(this) - 1);

				if (next.length) {
					next.focus();
				}
				return false;
			} else if (e.keyCode === 39) { // flecha derecha
				// focusable = form.find('li:not(.disabled) a').filter(':visible');
				focusable = form.find('input,select ,textarea, li:not(.disabled) a').filter(':visible');
				next = focusable.eq(focusable.index(this) + 1);

				if (next.length) {
					next.focus();
				}
				return false;
			}
    	});

          
	function get_class_sections(class_id) {
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_section_content_by_class/' + class_id ,
            success: function(response) {
                var select = $('#section_selector_holder_student');
                select.empty(); 

                const emptyOption = '<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>';
                jQuery('#section_selector_holder').html(emptyOption + response);
            }
        });

    }

    function get_section_students(section_id) {
     	$.ajax({
         url: '<?php echo base_url();?>index.php?admin/get_students_content_by_section/' + section_id ,
             success: function(response)
             {
                const emptyOption = '<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>';
                jQuery('#section_selector_holder_student').html(emptyOption + response);
             }
         });
     }

</script>

<script>
$(document).ready(function() {
	$(document).on('keydown', 'input, select, textarea', function(e) {
        var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
        if (e.keyCode === 37) { // flecha izquierda
            // focusable = form.find('li:not(.disabled) a').filter(':visible');
			focusable = form.find('input,select,textarea, li:not(.disabled) a').filter(':visible');
            next = focusable.eq(focusable.index(this) - 1);

            if (next.length) {
                next.focus();
            }
            return false;
        } else if (e.keyCode === 39) { // flecha derecha
            // focusable = form.find('li:not(.disabled) a').filter(':visible');
			focusable = form.find('input,select ,textarea, li:not(.disabled) a').filter(':visible');
            next = focusable.eq(focusable.index(this) + 1);

            if (next.length) {
                next.focus();
            }
            return false;
        }
    });

  


});

</script>


<style>
	 .form-groups-bordered > .form-group {
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
