<div class="row">
	<div class="col-md-12">
		<div class="panel " data-collapsed="0">
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/schedules/create/' , array('class' => 'form-wizard validate', 'enctype' => 'multipart/form-data'));?>
				
					<div class="steps-progress">
						<div class="progress-indicator"></div>
					</div>
					
					<ul>
						<li class="active">
							<a href="#tab2-1" data-toggle="tab"><span>1</span><?php echo ucfirst(get_phrase('basic_information')); ?></a>
						</li>
						<li>
							<a href="#tab2-2" data-toggle="tab"><span>2</span><?php echo ucfirst(get_phrase('academic_information')); ?></a>
						</li>
                        <li>
							<a href="#tab2-3" data-toggle="tab"><span>3</span><?php echo ucfirst(get_phrase('confirmation')); ?></a>
						</li>
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane active" id="tab2-1">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label" for="day_id"><?php echo ucfirst(get_phrase('day')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="day_id" class="form-control" id="day_id" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
											<option selected disabled value=""><?php echo ucfirst(get_phrase('select')); ?></option>
                                            <option value="2"><?php echo ucfirst(get_phrase('monday')); ?></option>
                                            <option value="3"><?php echo ucfirst(get_phrase('tuesday')); ?></option>
                                            <option value="4"><?php echo ucfirst(get_phrase('wednesday')); ?></option>
                                            <option value="5"><?php echo ucfirst(get_phrase('thursday')); ?></option>
                                            <option value="6"><?php echo ucfirst(get_phrase('friday')); ?></option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="time_start"><?php echo ucfirst(get_phrase('time_start')); ?><span class="required-value">&nbsp;*</span></label>
                                        <input type="time" class="form-control text-center" name="time_start" id="time_start" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="time_end"><?php echo ucfirst(get_phrase('time_end')); ?><span class="required-value">&nbsp;*</span></label>
                                        <input type="time" class="form-control text-center" name="time_end" id="time_end" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" />
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="tab-pane" id="tab2-2">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('class')); ?><span class="required-value">&nbsp;*</span></label>
                                        <select name="class_id" class="form-control" data-validate="required" id="class_id" 
                                            data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>"
                                            onchange="get_class_sections(this.value);">
											<option value=""><?php echo ucfirst(get_phrase('select')); ?></option>
											<?php 
												$classes = $this->db->get('class')->result_array();
												foreach($classes as $row):
													?>
													<option value="<?php echo $row['class_id'];?>">
															<?php echo $row['name'];?>°
															</option>
												<?php
												endforeach;
											?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('section')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="section_id" class="form-control" id="section_selector_holder"  data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>"
                                                onchange="get_section_subjects(this.value);"> 
											<option value=""><?php echo ucfirst(get_phrase('first_select_the_class')); ?></option>
										</select>
									</div>				
								</div>
							</div>
                            <div class="row">
								<div class="col-md-12">
									<div class="form-group">
                                        <label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('subject')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="subject_id" class="form-control" id="section_selector_holder_subject"  data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
											<option value=""><?php echo ucfirst(get_phrase('first_select_the_class_and_section')); ?></option>
										</select>
									</div>
								</div>
                            </div>
						</div>
						<div class="tab-pane" id="tab2-3">
							<div class="form-group text-center">
								<button type="submit" class="btn btn-info"><?php echo ucfirst(get_phrase('complete_registration')); ?></button>
							</div>
						</div>
						
						<ul class="pager wizard">
							<li class="previous">
								<a href="#"><i class="entypo-left-open"></i> <?php echo ucfirst(get_phrase('back')); ?></a>
							</li>
							
							<li class="next">
								<a href="#" class="btn btn-secondary"><?php echo ucfirst(get_phrase('next')); ?> <i class="entypo-right-open"></i></a>
							</li>
						</ul>
					</div>
				
                    
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">

	$(document).ready(function() {


    });
    
	function get_class_sections(class_id) {
    	$.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_sections/' + class_id ,
            success: function(response)
            {
                // jQuery('#section_selector_holder').html(response);
                var select = $('#section_selector_holder');
                select.empty(); 
                select.append($('<option>', { value: '', text : '<?php echo ucfirst(get_phrase('select')); ?>' })); 
                select.append(response); 
            }
        });
    }

     function get_section_subjects(section_id) {
     	$.ajax({
         url: '<?php echo base_url();?>index.php?admin/get_section_subjects/' + section_id ,
             success: function(response)
             {
                 jQuery('#section_selector_holder_subject').html(response);
                //  $('#section_selector_holder_subject').val(response);
                var select = $('#section_selector_holder_subject');
                select.empty(); 
                select.append($('<option>', { value: '', text : '<?php echo ucfirst(get_phrase('select')); ?>' })); 
                select.append(response); 
             }
         });
     }


</script>


<script>
	function addNewFileInput() {
		const container = document.getElementById('file-input-container');

		const fileInputWrapper = document.createElement('div');
		fileInputWrapper.classList.add('fileinput', 'fileinput-new');
		fileInputWrapper.setAttribute('data-provides', 'fileinput');

		fileInputWrapper.innerHTML = `
			<div class="input-group">
				<div class="form-control uneditable-input" data-trigger="fileinput">
					<span class="fileinput-filename"></span>
				</div>
				<span class="input-group-addon btn btn-default btn-file">
					<span class="fileinput-new"><?= ucfirst(get_phrase('select_file')); ?></span>
					<span class="fileinput-exists"><?= ucfirst(get_phrase('change')); ?></span>
					<input type="file" name="attachments[]">
				</span>
					
				<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput"><?= ucfirst(get_phrase('remove')); ?></a>
			</div>
		`;

		container.appendChild(fileInputWrapper);
		container.appendChild(document.createElement('br'));
	}


	document.getElementById('add-file-btn').addEventListener('click', addNewFileInput);

	document.addEventListener('DOMContentLoaded', addNewFileInput);
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
