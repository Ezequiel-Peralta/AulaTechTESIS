<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo ('Añadir materia');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/subject/create/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
	
                <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ('Nombre');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo ('Value Required');?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ('Año');?></label>
                                <div class="col-sm-5">
                                <select name="class_id" class="form-control" style="width:100%;" data-validate="required" id="class_id" 
								data-message-required="<?php echo ('Valor requerido');?>"
									onchange="return get_class_sections(this.value)">
                                        <option value=""><?php echo ('Seleccionar');?></option>
                                        <?php 
										$classes = $this->db->get('class')->result_array();
										foreach($classes as $row):
										?>
                                    		<option value="<?php echo $row['class_id'];?>"><?php echo $row['name'];?></option>
                                        <?php
										endforeach;
										?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ('Curso');?></label>
                                <div class="col-sm-5">
                                    <select name="section_id" class="form-control" id="section_selector_holder"  data-validate="required" data-message-required="<?php echo ('Valor requerido');?>">
                                        <option value=""><?php echo ('Primero seleccionar el año');?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ('Profesor');?></label>
                                <div class="col-sm-5">
                                    <select name="teacher_id" class="form-control" style="width:100%;">
                                    	<?php 
										$teachers = $this->db->get('teacher')->result_array();
										foreach($teachers as $row):
										?>
                                    		<option value="<?php echo $row['teacher_id'];?>"><?php echo $row['name'];?></option>
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

<script type="text/javascript">
    function get_class_sections(class_id) {

        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id ,
            success: function(response)
            {
                jQuery('#section_selector_holder').html(response);
            }
        });

    }
</script>