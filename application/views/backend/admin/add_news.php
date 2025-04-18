<?php
	date_default_timezone_set('America/Argentina/Buenos_Aires');

	$date = date('Y-m-d');

?>

<div class="row">
	<div class="col-md-12">
		<div class="panel " data-collapsed="0">
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/news/create/' , array('class' => 'form-wizard validate', 'enctype' => 'multipart/form-data'));?>
				
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
										<input class="form-control" name="title" id="title" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" autofocus/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="news_type_id"><?php echo ucfirst(get_phrase('type')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="news_type_id" class="form-control" id="news_type_id" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
											<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
											<?php 
												$news_types = $this->db->get('news_types')->result_array();
												foreach($news_types as $news_type):
													?>
													<option value="<?php echo $news_type['news_type_id'];?>">
															<?php echo ucfirst(get_phrase( $news_type['name']));?>
													</option>
												<?php
												endforeach;
											?>
										</select>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label" for="body"><?php echo ucfirst(get_phrase('body')); ?><span class="required-value">&nbsp;*</span></label>
										<textarea style="resize: vertical;" class="form-control" name="body" id="body" rows="5" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
                                        <label class="control-label" for="date"><?php echo ucfirst(get_phrase('date')); ?><span class="required-value">&nbsp;*</span></label>
                                        <input type="date" value="<?php echo $date;?>" class="form-control text-center" name="date" id="date" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" />
                                    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
                                        <label class="control-label" for="status_id"><?php echo ucfirst(get_phrase('status')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="status_id" class="form-control" id="status_id" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
											<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>	
											<option value="0"><?php echo ucfirst(get_phrase('inactive')); ?></option>
											<option value="1"><?php echo ucfirst(get_phrase('active')); ?></option>
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
											<option value="all"><?php echo ucfirst(get_phrase('all')); ?></option>    
											<option value="students"><?php echo ucfirst(get_phrase('students')); ?></option>
											<option value="guardians"><?php echo ucfirst(get_phrase('guardians')); ?></option>
											<option value="teachers"><?php echo ucfirst(get_phrase('teachers')); ?></option>
											<option value="teachers_aide"><?php echo ucfirst(get_phrase('teachers_aide')); ?></option>
											<option value="secretaries"><?php echo ucfirst(get_phrase('secretaries')); ?></option>
											<option value="principals"><?php echo ucfirst(get_phrase('principals')); ?></option>
										</select>
									</div>
								</div>
							</div>
							<div class="row" id="class-section-container" style="display: none;">
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('class')); ?></label>
										<select name="class_id" class="form-control" id="class_id" 
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
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('section')); ?></label>
										<select name="section_id" class="form-control" id="section_selector_holder" 
											
											onchange="get_section_subjects(this.value);">
											<option value=""><?php echo ucfirst(get_phrase('first_select_the_class')); ?></option>
										</select>
									</div>                
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab2-3">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group text-center">
										<label for="field-1" class="control-label"><?php echo ucfirst(get_phrase('image')); ?></label>
										<br>
										<div id="image-input-container">
											<div class="fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
													<img src="http://placehold.it/200x200" alt="...">
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px !important; height: 150px !important; max-width: 200px !important; max-height: 150px !important; border: 4px solid #ebebeb;"></div>
												<div>
													<span class="btn btn-info btn-file">
														<span class="fileinput-new"><?php echo ucfirst(get_phrase('select')); ?></span>
														<span class="fileinput-exists"><?php echo ucfirst(get_phrase('change')); ?></span>
														<input type="file" name="images[]" accept="image/*" >
													</span>
													<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo ucfirst(get_phrase('remove')); ?></a>
												</div>
											</div>
											<br>
										</div>
										<br>
										<button type="button" id="add-image-btn" class="btn btn-success"><?php echo ucfirst(get_phrase('add_another_image')); ?></button>
									</div>
								</div>
							</div>
						</div>
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

	function toggleClassSectionVisibility() {
		var userType = document.getElementById("user_type").value;
		var classSectionContainer = document.getElementById("class-section-container");
		var classId = document.getElementById("class_id");
		var sectionId = document.getElementById("section_selector_holder");

		// Check if userType is one of the types that should display class and section
		if (userType === "students" || userType === "teachers" || userType === "teachers_aide" || userType === "guardians") {
			classSectionContainer.style.display = "block";
		} else {
			classSectionContainer.style.display = "none";
			// Clear the selected values for class and section
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
