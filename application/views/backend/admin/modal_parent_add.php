<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title">
            		<i class="entypo-plus-circled"></i>
					<?php echo ('Añadir información de padre');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/parent/create/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                    
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo ('Nombre');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo ('Valor requerido');?>"  autofocus
                            	value="">
						</div>
					</div>
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo ('Dni');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="dni" data-validate="required" data-message-required="<?php echo ('Valor requerido');?>"  autofocus
                            	value="">
						</div>
					</div>
                    <div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo ('Genero');?></label>
                        
						<div class="col-sm-5">
							<select name="gender" class="form-control"  data-validate="required" data-message-required="<?php echo ('Valor requerido');?>">
                              <option value=""><?php echo ('Seleccionar');?></option>
                              <option value="Male"><?php echo ('Hombre');?></option>
                              <option value="Female"><?php echo ('Mujer');?></option>
                          </select>
						</div> 
					</div>
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo ('Email');?></label>
						<div class="col-sm-5">
							<input type="text" class="form-control" name="email" 
                            	value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo ('Contraseña');?></label>
                        
						<div class="col-sm-5">
							<input type="password" class="form-control" name="password" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo ('Celular');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="phone_cel" value="">
						</div>
					</div>
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo ('Teléfono fijo');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="phone_fij" value="">
						</div>
					</div>
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo ('Dirección');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="address" value="">
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