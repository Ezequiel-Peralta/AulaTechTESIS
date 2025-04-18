<?php 
$exam_types = isset($exam_types) ? $exam_types : array();
$edit_data = isset($edit_data) ? $edit_data : array();

foreach ($edit_data as $row):
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel" data-collapsed="0">
			<div class="panel-body">
				<?php echo form_open(base_url() . 'index.php?admin/exams/update/' . $param2 , array('class' => 'form-wizard validate', 'enctype' => 'multipart/form-data'));?>
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
							<a href="#tab2-3" data-toggle="tab"><span>3</span><?php echo ucfirst(get_phrase('files')); ?></a>
						</li>
                        <li>
							<a href="#tab2-4" data-toggle="tab"><span>4</span><?php echo ucfirst(get_phrase('confirmation')); ?></a>
						</li>
					</ul>
                   
					<div class="tab-content">
						<div class="tab-pane active" id="tab2-1">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="name"><?php echo ucfirst(get_phrase('name')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="name" id="name" data-validate="required" value="<?php echo $row['name'];?>"  placeholder="" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" autofocus/>
									</div>
								</div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="exam_type"><?php echo ucfirst(get_phrase('type')); ?><span class="required-value">&nbsp;*</span></label>
                                        <select name="exam_type" class="form-control" id="exam_type" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                                            <option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
                                            <?php foreach ($exam_types as $exam_type): ?>
                                                <option value="<?php echo $exam_type['id']; ?>" <?php echo ($row['exam_type_id'] == $exam_type['id']) ? 'selected' : ''; ?>>
                                                    <?php echo ucfirst(get_phrase($exam_type['name'])); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
							</div>
                          
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="date"><?php echo ucfirst(get_phrase('date')); ?><span class="required-value">&nbsp;*</span></label>
                                        <input type="date" class="form-control text-center" name="date" id="date" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" value="<?php echo $row['date']; ?>" />
                                    </div>
                                </div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="status_id"><?php echo ucfirst(get_phrase('status')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="status_id" class="form-control" id="status_id" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
											<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>    
											<option value="0" <?php echo $row['status_id'] == "0" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('inactive')); ?></option>
											<option value="1" <?php echo $row['status_id'] == "1" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('active')); ?></option>
										</select>
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
						<?php 
							$files = isset($row['files']) ? json_decode($row['files'], true) : [];
						?>

						<div class="tab-pane" id="tab2-3">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group text-center">
										<label for="field-1" class="control-label"><?php echo ucfirst(get_phrase('files')); ?></label>
										<br><br>
											<div id="file-input-container">
												<?php foreach ($files as $file): ?>
													<div class="fileinput fileinput-exists" data-provides="fileinput">
														<div class="input-group">
															<div class="form-control uneditable-input" data-trigger="fileinput">
																<span class="fileinput-filename"><?= $file; ?></span>
															</div>
															<a href="#" class="input-group-addon btn btn-default fileinput-exists" 
															onclick="removeExistingFile(event, '<?= $file; ?>')"><?= ucfirst(get_phrase('delete')); ?></a>

														</div>
														<input type="hidden" name="existing_files[]" value="<?= $file; ?>">
													</div>
													<br>
												<?php endforeach; ?>
											</div>
											<br>
											<button type="button" class="btn btn-primary" id="add-file-btn">
												<i class="glyphicon glyphicon-plus"></i> <?= ucfirst(get_phrase('add_another_file')); ?>
											</button>
									</div>
								</div>
							</div>	
						</div>
						<input type="hidden" name="files_to_delete[]" id="files_to_delete" value="">
						<div class="tab-pane" id="tab2-4">
							<div class="form-group text-center">
								<button type="submit" class="btn btn-info"><?php echo ucfirst(get_phrase('complete_registration')); ?></button>
							</div>
						</div>
						<ul class="pager wizard">
							<li class="previous">
								<a href="#"><i class="entypo-left-open"></i>  <?php echo ucfirst(get_phrase('back')); ?></a>
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
    const classId = '<?php echo $row['class_id']; ?>';
    get_class_sections(classId);
});

function get_class_sections(class_id) {
    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_class_sections/' + class_id,
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

            if (sectionId) {
                get_section_subjects(sectionId);
            }
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

<script>
    function addNewFileInput() {
        const container = document.getElementById('file-input-container');

        const fileInputWrapper = document.createElement('div');
        fileInputWrapper.classList.add('fileinput', 'fileinput-new');
        fileInputWrapper.setAttribute('data-provides', 'fileinput');

        fileInputWrapper.innerHTML = `
			<br>
				<div class="input-group">
					<div class="form-control uneditable-input" data-trigger="fileinput">
						<span class="fileinput-filename"></span>
					</div>
					<span class="input-group-addon btn btn-default btn-file">
						<span class="fileinput-new"><?= ucfirst(get_phrase('select_file')); ?></span>
						<span class="fileinput-exists"><?= ucfirst(get_phrase('change')); ?></span>
						<input type="file" name="attachments[]" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
					</span>
					<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">
						<?= ucfirst(get_phrase('remove')); ?>
					</a>
				</div>
			<br>
        `;

        container.appendChild(fileInputWrapper);
    }

    document.getElementById('add-file-btn').addEventListener('click', addNewFileInput);

	function removeExistingFile(event, filename) {
        event.preventDefault(); 
        const filesToDeleteInput = document.getElementById('files_to_delete');

        let currentFiles = filesToDeleteInput.value ? filesToDeleteInput.value.split(',') : [];
        if (!currentFiles.includes(filename)) {
            currentFiles.push(filename);
            filesToDeleteInput.value = currentFiles.join(','); 
        }

        const fileInputGroup = event.target.closest('.fileinput');
        fileInputGroup.remove();
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


</style>
