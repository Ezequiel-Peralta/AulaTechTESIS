<div class="row">
	<div class="col-md-12">
		<div class="panel" data-collapsed="0">
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/students_bulk_add/import_excel/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
	
					<div class="form-group text-center">
						<label for="field-1" class="col-sm-3 control-label"><?php echo ucfirst(get_phrase('select_excel_file')); ?></label>
                        
						<div class="col-sm-5">
                        	<input type="file" name="userfile" class="form-control" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                            <br>
							<?php 
							$language_preference = $this->session->userdata('language_preference');

							if ($language_preference == 'spanish') { ?>
								<a href="<?php echo base_url();?>uploads/bulk_student_template_es.xlsx" target="_blank"  
									class="btn btn-info btn-sm"><i class="entypo-download"></i> <?php echo ucfirst(get_phrase('download_excel_template')); ?>
								</a>
							<?php } elseif ($language_preference == 'english') { ?>
								<a href="<?php echo base_url();?>uploads/bulk_student_template_en.xlsx" target="_blank"  
									class="btn btn-info btn-sm"><i class="entypo-download"></i> <?php echo ucfirst(get_phrase('download_excel_template')); ?>
								</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>uploads/bulk_student_template_en.xlsx" target="_blank"  
									class="btn btn-info btn-sm"><i class="entypo-download"></i> <?php echo ucfirst(get_phrase('download_excel_template')); ?>
								</a>
							<?php } 
							?>

						</div>
					</div>
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo ucfirst(get_phrase('class')); ?></label>
                        
						<div class="col-sm-5">
							<select name="class_id" class="form-control" onchange="return get_class_sections(this.value)">
                              <option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
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
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo ucfirst(get_phrase('section')); ?></label>
		                    <div class="col-sm-5">
		                        <select name="section_id" class="form-control" id="section_selector_holder"   >
		                            <option value=""><?php echo ucfirst(get_phrase('first_select_the_class')); ?></option>
			                        
			                    </select>
			                </div>
					</div>
					
                    <div class="form-group text-center">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo ucfirst(get_phrase('accept')); ?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

	function get_class_sections(class_id) {

		$.ajax({
			url: '<?php echo base_url();?>index.php?admin/get_sections_content_by_class/' + class_id ,
			success: function(response) {
				const emptyOption = '<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>';
				jQuery('#section_selector_holder').html(emptyOption + response);
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
</style>