<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="entypo-plus"></i><?php echo 'Añadir lenguaje'?></h4>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<!-- <div class="text-center">
				<h4>Alta de estudiante</h4>
				<hr />
			</div> -->
				
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/students/create/' , array('class' => 'form-wizard validate', 'enctype' => 'multipart/form-data'));?>
				
			
					<div class="steps-progress">
						<div class="progress-indicator"></div>
					</div>
					
					<ul>
						<li class="active">
							<a href="#tab2-1" data-toggle="tab"><span>1</span>Información personal</a>
						</li>
						<li>
							<a href="#tab2-2" data-toggle="tab"><span>2</span>Información personal 2</a>
						</li>
						<li>
							<a href="#tab2-3" data-toggle="tab"><span>3</span>Información de contacto</a>
						</li>
						<li>
							<a href="#tab2-4" data-toggle="tab"><span>4</span>Foto</a>
						</li>
						<li>
							<a href="#tab2-5" data-toggle="tab"><span>5</span>Información de Inicio de Sesión</a>
						</li>
						<li>
							<a href="#tab2-6" data-toggle="tab"><span>8</span>Confirmación</a>
						</li>
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane active" id="tab2-1">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="firstname">Nombre</label>
										<input class="form-control" name="firstname" id="firstname" data-validate="required" placeholder="" autofocus/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="lastname">Apellido</label>
										<input class="form-control" name="lastname" id="lastname" data-validate="required" placeholder=""/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="dni">DNI</label>
										<!-- <input type="text" class="form-control" data-mask="99999999" placeholder="" data-validate="required"/> -->
										<input class="form-control" name="dni" id="dni" data-validate="required" data-mask="99999999" placeholder="" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="birthday">Fecha de nacimiento</label>
										<!-- <input type="text" class="form-control" data-mask="date" data-validate="required"/> -->
										<input class="form-control" name="birthday" id="birthday" data-validate="required" data-mask="date" placeholder="" />
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab2-2">
							<div class="row">
								<div class="col-md-12">
									<!--data-validate="required" data-message-required="<?php echo ('Valor requerido');?>" -->
									<!-- <div class="form-group">
										<label class="control-label" for="gender">Género</label>
										<select name="gender" class="form-control" autofocus> 
											<option value="" disabled selected><?php echo ('Seleccionar');?></option> 
											<option value="Male"><?php echo ('Hombre');?></option>
											<option value="Female"><?php echo ('Mujer');?></option>
										</select>
									</div> -->
									<div class="form-group text-center">
										<label class="control-label">Genero</label>
										<br />
										<div class="make-switch switch-small" data-on-label="Hombre" data-off-label="Mujer">
											<input type="checkbox" checked>
										</div>
									</div>	
								</div>
							</div>
						
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="provincia">Provincia</label>
										<!-- <input class="form-control" name="provincia" id="provincia" data-validate="required" value="Cordoba" disabled/> -->
										<select name="test" class="select2" id="provincia-select" data-allow-clear="true" disabled>
											<optgroup label="Provincias" id="provincia-group">
												<option selected>Cordoba</option>
											</optgroup>
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="postalcode">Código postal</label>
										<select name="test" class="select2" id="postalcode" data-allow-clear="true" data-placeholder="Seleccionar un código postal"  data-validate="required" data-message-required="Por favor, seleccione un código postal">
											<option></option>
											<optgroup label="Códigos Postales" id="postal-codes-group">
												<!-- Opciones de códigos postales cargadas dinámicamente -->
											</optgroup>
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="localidad">Localidad</label>
										<select name="test" class="select2" id="localidad" data-allow-clear="true" data-placeholder="Seleccionar una localidad"  data-validate="required" data-message-required="Por favor, seleccione una localidad">
											<option></option>
											<optgroup label="Localidades" id="localidades-group">
												<!-- Opciones de localidades cargadas dinámicamente -->
											</optgroup>
										</select>
									</div>
								</div>

							

							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="neighborhood">Barrio</label>
										<input class="form-control" name="neighborhood" id="neighborhood"  data-validate="required"/> 
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="street">Calle y altura</label>
										<input class="form-control" name="street" id="street"  data-validate="required"/> <!--data-validate="required"-->
									</div>
								</div>
							
							</div>
						</div>
						
						<div class="tab-pane" id="tab2-3">
							
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="phone_fij">Teléfono fijo</label>
										<input class="form-control" name="phone_fij" id="phone_fij" data-mask="9999999" placeholder="" data-validate="required" data-message-required="<?php echo ('Valor requerido');?>" autofocus/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="phone_cel">Teléfono celular</label>
										<input type="text" name="phone_cel" id="phone_cel" class="form-control" data-mask="+54 999 9999999" data-validate="required" data-message-required="<?php echo ('Valor requerido');?>"/>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab2-4">			
							
							<div class="row">
								<div class="col-md-12">
									<div class="form-group text-center">
										<label for="field-1" class="control-label"><?php echo ('Foto');?></label>
										<br>
										<div class="fileinput fileinput-new" data-provides="fileinput">
											<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
												<img src="http://placehold.it/200x200" alt="...">
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px !important; height: 150px !important; max-width: 200px !important; max-height: 150px !important; border: 4px solid #ebebeb;"></div>
											<div>
												<span class="btn btn-info btn-file">
													<span class="fileinput-new">Seleccionar imagen</span>
													<span class="fileinput-exists">Cambiar</span>
													<input type="file" name="userfile" accept="image/*"  data-validate="required" data-message-required="<?php echo ('Valor requerido');?>">
												</span>
												<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Quitar</a>
											</div>
										</div>
									</div> 
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tab2-5">
										
							<div class="form-group">
								<label class="control-label">Email</label>
								
								<div class="input-group">
									<div class="input-group-addon">
										<i class="entypo-mail"></i>
									</div>
									<!-- <input type="text" class="form-control" name="username" id="username" data-validate="required,minlength[5]" data-message-minlength="Username must have minimum of 5 chars." /> -->
									<input type="text" class="form-control" name="email" id="email" data-validate="email" />
								</div>
							</div>
							
							<div class="row">
								
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">Contraseña</label>
										
										<div class="input-group">
											<div class="input-group-addon">
												<i class="entypo-key"></i>
											</div>
											
											<input type="password" class="form-control" name="password" id="password" data-validate="required"  />
										</div>
									</div>
								</div>
								<div class="col-md-6">						
									<div class="form-group">
										<label class="control-label">Repetir Contraseña</label>
										<div class="input-group">
											<div class="input-group-addon">
												<i class="entypo-cw"></i>
											</div>
											<input type="password" class="form-control" name="password" id="password" data-validate="required,equalTo[#password]" data-message-equal-to="Passwords doesn't match."  />
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab2-6">
							<div class="form-group text-center">
								<button type="submit" class="btn btn-info"><?php echo ('Finalizar registro');?></button>
							</div>
						</div>
						
						<ul class="pager wizard">
							<li class="previous">
								<a href="#"><i class="entypo-left-open"></i> Volver</a>
							</li>
							
							<li class="next">
								<a href="#">Siguiente <i class="entypo-right-open"></i></a>
							</li>
						</ul>
					</div>
				
                    
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
	$.extend($.validator.messages, {
        required: "Este campo es obligatorio.",
        remote: "Por favor, rellena este campo.",
        email: "Por favor, escribe una dirección de correo válida",
        url: "Por favor, escribe una URL válida.",
        date: "Por favor, escribe una fecha válida.",
        dateISO: "Por favor, escribe una fecha (ISO) válida.",
        number: "Por favor, escribe un número válido.",
        digits: "Por favor, escribe solo dígitos.",
        creditcard: "Por favor, escribe un número de tarjeta válido.",
        equalTo: "Por favor, escribe el mismo valor de nuevo.",
        extension: "Por favor, escribe un valor con una extensión aceptada.",
        maxlength: $.validator.format("Por favor, no escribas más de {0} caracteres."),
        minlength: $.validator.format("Por favor, no escribas menos de {0} caracteres."),
        rangelength: $.validator.format("Por favor, escribe un valor entre {0} y {1} caracteres."),
        range: $.validator.format("Por favor, escribe un valor entre {0} y {1}."),
        max: $.validator.format("Por favor, escribe un valor menor o igual a {0}."),
        min: $.validator.format("Por favor, escribe un valor mayor o igual a {0}.")
    });

	$(document).on('keydown', 'input, select, textarea', function(e) {
        var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
        if (e.keyCode === 37) { // flecha izquierda
            // focusable = form.find('li:not(.disabled) a').filter(':visible');
			focusable = form.find('input,select,textarea, li:not(.disabled) a').filter(':visible');
            next = focusable.eq(focusable.index(this) - 1);

            if (next.length) {
                next.focus();
            }
            return false;
        } else if (e.keyCode === 39) { // flecha derecha
            // focusable = form.find('li:not(.disabled) a').filter(':visible');
			focusable = form.find('input,select ,textarea, li:not(.disabled) a').filter(':visible');
            next = focusable.eq(focusable.index(this) + 1);

            if (next.length) {
                next.focus();
            }
            return false;
        }
    });
});

</script>

<style>
	 .form-groups-bordered > .form-group {
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
