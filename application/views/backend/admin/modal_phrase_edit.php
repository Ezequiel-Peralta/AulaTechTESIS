<?php 
$phrase_id = $param2; 
$phrase_name = $param3; 
?>


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo ('Editar frase');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/language_settings/edit_phrase/'. $phrase_id , array('class' => 'form-horizontal form-groups-bordered validate'));?>
	
                <div class="form-group">
                    <label class="control-label"><?php echo ('Frase');?></label>
                    <input type="text" class="form-control" name="phrase" data-validate="required" value="<?php echo htmlspecialchars($phrase_name, ENT_QUOTES, 'UTF-8'); ?>" data-message-required="<?php echo ('Value Required'); ?>"/>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-info"><?php echo ('Editar frase');?></button>
				</div>
                      
                <?php echo form_close();?> 
            </div>
        </div>
    </div>
</div>