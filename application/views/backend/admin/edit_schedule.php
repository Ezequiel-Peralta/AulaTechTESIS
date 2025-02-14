
<?php 
    $edit_data = $this->db->get_where('schedule', array('schedule_id' => $schedule_id))->result_array();
    foreach ($edit_data as $row):
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel " data-collapsed="0">
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/schedules/update/' . $schedule_id , array('class' => 'form-wizard validate', 'enctype' => 'multipart/form-data'));?>
				
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
                                            <option value="2" <?php echo $row['day_id'] == "2" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('monday')); ?></option>
                                            <option value="3" <?php echo $row['day_id'] == "3" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('tuesday')); ?></option>
                                            <option value="4" <?php echo $row['day_id'] == "4" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('wednesday')); ?></option>
                                            <option value="5" <?php echo $row['day_id'] == "5" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('thursday')); ?></option>
                                            <option value="6" <?php echo $row['day_id'] == "6" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('friday')); ?></option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="time_start"><?php echo ucfirst(get_phrase('time_start')); ?><span class="required-value">&nbsp;*</span></label>
                                        <input type="time" value="<?php echo $row['time_start'];?>" class="form-control text-center" name="time_start" id="time_start" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="time_end"><?php echo ucfirst(get_phrase('time_end')); ?><span class="required-value">&nbsp;*</span></label>
                                        <input type="time" value="<?php echo $row['time_end'];?>" class="form-control text-center" name="time_end" id="time_end" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" />
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="tab-pane" id="tab2-2">
						<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('class')); ?></label>
                                        
                                        <select name="class_id" class="form-control" data-validate="required"  id="class_id" value="<?php echo $row['class_id'];?>" 
											data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>"
                                            onchange="get_class_sections(this.value);">
											<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
											<?php 
												$classes = $this->db->get('class')->result_array();
												foreach($classes as $row2):
													?>
												<option value="<?php echo $row2['class_id'];?>"
                                                    <?php if($row['class_id'] == $row2['class_id'])echo 'selected';?>>
                                                            <?php echo $row2['name'];?>°
                                                        </option>
												<?php
												endforeach;
											?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('section')); ?></label>
										<select name="section_id" class="form-control" id="section_selector_holder" data-validate="required" onchange="get_section_subjects(this.value);">
											<option value=""><?php echo ucfirst(get_phrase('first_select_the_class')); ?></option>
										</select>
									</div>				
								</div>
							</div>
                            <div class="row">
								<div class="col-md-12">
									<div class="form-group">
                                        <label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('subject')); ?></label>
										<select name="subject_id" class="form-control" id="section_selector_holder_subject"  data-validate="required" data-message-required="<?php echo ('Valor requerido');?>">
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


<?php
endforeach;
?>


<script>
  $(document).ready(function() {
    let ajaxSent = false;

    function sendPageTracking() {
      if (!ajaxSent) {
        ajaxSent = true;

        let page_name = '<?php echo $page_name;?>'
        let user_id = '<?php echo $this->session->userdata('login_user_id');?>'
        let user_group = '<?php echo $this->session->userdata('login_type');?>'

        $.ajax({
          url: 'index.php?admin/reset_page_tracking/' + page_name, 
          success: function(response) {
          },
          error: function(xhr, status, error) {
          }
        });
      }
    }

    // Captura el evento `beforeunload`
    $(window).on('beforeunload', function() {
      sendPageTracking();
    });
  });
</script>

<script type="text/javascript">

$(document).ready(function() {
    
});

const classId = '<?php echo $row['class_id']; ?>';
    get_class_sections(classId);

	const sectionId = '<?php echo $row['section_id']; ?>';
	get_section_subjects(sectionId);

function get_class_sections(class_id) {
    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id,
        success: function(response) {
            const sectionSelect = $('#section_selector_holder');
            sectionSelect.empty();
			
			sectionSelect.append($('<option>', {
				value: '',
				text: '<?php echo ucfirst(get_phrase('select')); ?>',
				selected: true,
				disabled: true
			}));

			sectionSelect.append(response);
	
            const sectionId = '<?php echo $row['section_id']; ?>';
            sectionSelect.find(`option[value="${sectionId}"]`).prop('selected', true);

			const sectionSelectSubject = $('#section_selector_holder_subject');
            sectionSelectSubject.empty();
			
			sectionSelectSubject.append($('<option>', {
				value: '',
				text: '<?php echo ucfirst(get_phrase('select')); ?>',
				selected: true,
				disabled: true
			}));

        
        }
    });
}

function get_section_subjects(section_id) {
    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_section_subjects/' + section_id,
        success: function(response) {
            var select = $('#section_selector_holder_subject');
            select.empty();

			select.append($('<option>', {
				value: '',
				text: '<?php echo ucfirst(get_phrase('select')); ?>',
				selected: true,
				disabled: true
			}));

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
