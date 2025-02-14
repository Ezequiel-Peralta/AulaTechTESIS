<?php 
	$edit_data = $this->db->get_where('section' , array(
		'section_id' => $param2
	))->result_array();
	foreach ($edit_data as $row):
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-pencil"></i>
					<?php echo ('Añadir curso');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/sections/edit/' . $row['section_id'] , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
	
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo ('Nombre');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo ('Value Required');?>" 
								value="<?php echo $row['name'];?>">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo ('Nombre letra');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="letter_name" 
								value="<?php echo $row['letter_name'];?>" >
						</div> 
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo ('Año');?></label>
                        
						<div class="col-sm-5">
							<select name="class_id" class="form-control" data-validate="required" data-message-required="<?php echo ('Value Required');?>">
                              <option value=""><?php echo ('Seleccionar');?></option>
                              <?php 
									$classes = $this->db->get('class')->result_array();
									foreach($classes as $row2):
										?>
                                		<option value="<?php echo $row2['class_id'];?>"
                                			<?php if ($row['class_id'] == $row2['class_id'])
                                				echo 'selected';?>>
													<?php echo $row2['name'];?>
                                        </option>
                                    <?php
									endforeach;
								?>
                          </select>
						</div> 
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo ('Preceptor');?></label>
                        
						<div class="col-sm-5">
							<select name="teacher_aide_id" class="form-control">
                              <option value=""><?php echo ('Seleccionar');?></option>
                              <?php 
									$teachers_aide = $this->db->get('teacher_aide')->result_array();
									foreach($teachers_aide as $row3):
										?>
                                		<option value="<?php echo $row3['teacher_aide_id'];?>"
                                			<?php if ($row['teacher_aide_id'] == $row3['teacher_aide_id'])
                                				echo 'selected';?>>
													<?php echo $row3['name'];?>
                                        </option>
                                    <?php
									endforeach;
								?>
                          </select>
						</div> 
					</div>
                    
                    <div class="form-group text-center">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo ('Aceptar');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<?php endforeach;?>


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

    .panel-body {
        color: #484848 !important;
    }
</style>