
<?php 
    $news_types = $this->db->get('news_types')->result_array();

    $edit_data = $this->db->get_where('news', array('news_id' => $param2))->result_array();
    foreach ($edit_data as $row):

?>

<div class="row">
	<div class="col-md-12">
		<div class="panel " data-collapsed="0">
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/news/update/' . $param2 , array('class' => 'form-wizard validate', 'enctype' => 'multipart/form-data'));?>
				
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
							<a href="#tab2-3" data-toggle="tab"><span>3</span><?php echo ucfirst(get_phrase('images')); ?></a>
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
										<label class="control-label" for="title"><?php echo ucfirst(get_phrase('title')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="title" id="title" value="<?php echo $row['title'];?>" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" autofocus/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="news_type_id"><?php echo ucfirst(get_phrase('type')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="news_type_id" class="form-control" id="news_type_id" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
											<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
                            <?php foreach ($news_types as $news_type): ?>
                                                <option value="<?php echo $news_type['news_type_id']; ?>" <?php echo (isset($row['news_type_id']) && $row['news_type_id'] == $news_type['news_type_id']) ? 'selected' : ''; ?>>
                                                    <?php echo  ucfirst(get_phrase($news_type['name'])); ?>
                                                </option>
                                            <?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label" for="body"><?php echo ucfirst(get_phrase('body')); ?><span class="required-value">&nbsp;*</span></label>
										<textarea style="resize: vertical;" class="form-control" name="body" id="body" rows="5" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>"><?php echo $row['body'];?></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
                                        <label class="control-label" for="date"><?php echo ucfirst(get_phrase('date')); ?><span class="required-value">&nbsp;*</span></label>
                                        <input type="date" value="<?php echo $row['date'];?>" class="form-control text-center" name="date" id="date" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" />
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
								<div class="col-md-12">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('recipient')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="user_type" class="form-control" id="user_type" 
											data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>"
											onchange="toggleClassSectionVisibility();">
											<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
											<option value="all" <?php echo $row['user_type'] == "all" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('all')); ?></option>    
											<option value="students" <?php echo $row['user_type'] == "students" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('students')); ?></option>
											<option value="guardians" <?php echo $row['user_type'] == "guardians" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('guardians')); ?></option>
											<option value="teachers" <?php echo $row['user_type'] == "teachers" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('teachers')); ?></option>
											<option value="teachers_aide" <?php echo $row['user_type'] == "teachers_aide" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('teachers_aide')); ?></option>
											<option value="secretaries" <?php echo $row['user_type'] == "secretaries" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('secretaries')); ?></option>
											<option value="principals" <?php echo $row['user_type'] == "principals" ? "selected" : ""; ?>><?php echo ucfirst(get_phrase('principals')); ?></option>
										</select>
									</div>
								</div>
							</div>
							<div class="row" id="class-section-container" style="<?php if (in_array($row['user_type'], ['students', 'guardians', 'teachers', 'teachers_aide'])) echo ''; else echo 'display: none;'; ?>">
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('class')); ?></label>
										<select name="class_id" class="form-control" id="class_id" 
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
										<select name="section_id" class="form-control" id="section_selector_holder">
										<?php
											if (empty($row['class_id'])) {
												?>
												<option value=""><?php echo ucfirst(get_phrase('first_select_the_class')); ?></option>
												<?php
											} else {
												?>
												<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
												<?php
												$all_sections = $this->db->get_where('section', array('class_id' => $row['class_id']))->result_array();

												$sections = [];
												foreach ($all_sections as $section) {
													$academic_period = $this->db->get_where('academic_period', array(
														'id' => $section['academic_period_id'],
														'status_id' => 1
													))->row_array();

													if (!empty($academic_period)) {
														$sections[] = $section;
													}
												}

												foreach ($sections as $section) {
													?>
													<option value="<?php echo $section['section_id']; ?>"
														<?php if ($row['section_id'] == $section['section_id']) echo 'selected'; ?>>
														<?php echo $section['name']; ?>
													</option>
													<?php
												}
											}
										?>
										</select>
									</div>                
								</div>
							</div>
						</div>

                        <?php 
							$files = isset($row['images']) ? json_decode($row['images'], true) : [];
						?>

						<div class="tab-pane" id="tab2-3">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group text-center">
										<label for="field-1" class="control-label"><?php echo ucfirst(get_phrase('image')); ?></label>
										<br>
										<div id="image-input-container">
											<?php foreach ($files as $file): ?>
												<div class="fileinput fileinput-exists" data-provides="fileinput">
													<div class="image-preview mt-2">
														<img src="<?= base_url() . 'uploads/news/' . $row['news_id'] . '/' . $file; ?>" alt="Image Preview" class="img-thumbnail" style="max-width: 150px;">
													</div>
													<div class="input-group">
														<div class="form-control uneditable-input" data-trigger="fileinput">
															<span class="fileinput-filename"><?= $file; ?></span>
														</div>
														<a href="#" class="input-group-addon btn btn-default fileinput-exists" 
														onclick="removeExistingFile(event, '<?= $file; ?>')"><?= ucfirst(get_phrase('delete')); ?></a>
													</div>

													

													<input type="hidden" name="existing_files[]" value="<?= $file; ?>">
												</div>
											<?php endforeach; ?>
											<br>
										</div>
										<br>
										<button type="button" id="add-image-btn" class="btn btn-success"><?php echo ucfirst(get_phrase('add_another_image')); ?></button>
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
    
	function get_class_sections(class_id) {
    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id,
        success: function(response) {
            const sectionSelect = $('#section_selector_holder');
            sectionSelect.empty().append(response);
        }
    });
}

	function toggleClassSectionVisibility() {
		var userType = document.getElementById("user_type").value;
		var classSectionContainer = document.getElementById("class-section-container");
		var classId = document.getElementById("class_id");
		var sectionId = document.getElementById("section_selector_holder");

		if (userType === "students" || userType === "teachers" || userType === "teachers_aide" || userType === "guardians") {
			classSectionContainer.style.display = "block";
		} else {
			classSectionContainer.style.display = "none";
			classId.value = "";
			sectionId.value = "";
		}
	}
 


</script>


<script>
	function addNewImageInput() {
    const container = document.getElementById('image-input-container');

    const imageInputWrapper = document.createElement('div');
    imageInputWrapper.classList.add('fileinput', 'fileinput-new');
    imageInputWrapper.setAttribute('data-provides', 'fileinput');

    imageInputWrapper.innerHTML = `
	<br>
        <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
            <img src="http://placehold.it/200x200" alt="...">
        </div>
        <div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px !important; height: 150px !important; max-width: 200px !important; max-height: 150px !important; border: 4px solid #ebebeb;"></div>
        <div>
            <span class="btn btn-info btn-file">
                <span class="fileinput-new"><?php echo ucfirst(get_phrase('select')); ?></span>
                <span class="fileinput-exists"><?php echo ucfirst(get_phrase('change')); ?></span>
                <input type="file" name="images[]" accept="image/*" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
            </span>
            <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo ucfirst(get_phrase('remove')); ?></a>
        </div>
		<br>
    `;

    container.appendChild(imageInputWrapper);
}

document.getElementById('add-image-btn').addEventListener('click', addNewImageInput);


    function removeExistingFile(event, filename) {
        event.preventDefault(); // Previene el comportamiento predeterminado del enlace
        const filesToDeleteInput = document.getElementById('files_to_delete');

        // Agregar el nombre del archivo al input oculto
        let currentFiles = filesToDeleteInput.value ? filesToDeleteInput.value.split(',') : [];
        if (!currentFiles.includes(filename)) {
            currentFiles.push(filename);
            filesToDeleteInput.value = currentFiles.join(','); // Actualizar el valor del input
        }

        // Eliminar el archivo de la vista
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

	/* .modal-content {
		width: 700px !important;
	}

	.modal-body {
		height: auto !important;
	} */
</style>
