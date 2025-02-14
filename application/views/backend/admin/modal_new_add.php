<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo ('Añadir noticia');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/news/create/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
	
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo ('Titulo');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="notice_title" data-validate="required" data-message-required="<?php echo ('Value Required');?>" value="" autofocus>
						</div>
					</div>
                    <div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo ('Cuerpo');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="notice_body" data-validate="required" data-message-required="<?php echo ('Value Required');?>" value="" autofocus>
						</div>
					</div>
                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo ('Fecha');?></label>
                        <div class="col-sm-5">
							<input type="text" class="form-control datepicker" name="date" value="" data-start-view="2">
						</div> 
                    </div>
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo ('Foto');?></label>
                        
						<div class="col-sm-5">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
									<img src="http://placehold.it/200x200" alt="...">
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
								<div>
									<span class="btn btn-white btn-file">
										<span class="fileinput-new">Seleccionar imagen</span>
										<span class="fileinput-exists">Cambiar</span>
										<input type="file" name="userfile" accept="image/*">
									</span>
									<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remover</a>
								</div>
							</div>
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