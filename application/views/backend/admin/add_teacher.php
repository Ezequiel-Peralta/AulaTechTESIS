<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/teacher/create/' , array('class' => 'form-wizard validate', 'enctype' => 'multipart/form-data'));?>
				
			
					<div class="steps-progress">
						<div class="progress-indicator"></div>
					</div>
					
					<ul>
						<li class="active">
							<a href="#tab2-1" data-toggle="tab"><span>1</span><?php echo ucfirst(get_phrase('personal_Information'));?></a>
						</li>
						<li>
							<a href="#tab2-2" data-toggle="tab"><span>2</span><?php echo ucfirst(get_phrase('personal_Information'));?> 2</a>
						</li>
						<li>
							<a href="#tab2-3" data-toggle="tab"><span>3</span><?php echo ucfirst(get_phrase('contact_information')); ?></a>
						</li>
						<li>
							<a href="#tab2-4" data-toggle="tab"><span>4</span><?php echo ucfirst(get_phrase('photo')); ?></a>
						</li>
						<li>
							<a href="#tab2-5" data-toggle="tab"><span>5</span><?php echo ucfirst(get_phrase('login_information')); ?></a>
						</li>
						
						<li>
							<a href="#tab2-6" data-toggle="tab"><span>6</span><?php echo ucfirst(get_phrase('confirmation')); ?></a>
						</li>
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane active" id="tab2-1">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="firstname"><?php echo ucfirst(get_phrase('firstname')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="firstname" id="firstname" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" placeholder="" autofocus"/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="lastname"><?php echo ucfirst(get_phrase('lastname')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="lastname" id="lastname" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" placeholder="""/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="dni">DNI<span class="required-value">&nbsp;*</span></label>
										
										<input class="form-control" name="dni" id="dni" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" data-mask="99999999" placeholder="" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="birthday"><?php echo ucfirst(get_phrase('birthday')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="birthday" type="date" id="birthday" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" placeholder="" />
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab2-2">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group text-center">
										<label class="control-label" for="gender_id"><?php echo ucfirst(get_phrase('gender')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="gender_id" class="form-control" id="gender_id" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
											<option value="" selected disabled ><?php echo ucfirst(get_phrase('select')); ?></option>
											<option value="0"><?php echo ucfirst(get_phrase('male')); ?></option>
											<option value="1"><?php echo ucfirst(get_phrase('female')); ?></option>
											<option value="2"><?php echo ucfirst(get_phrase('other')); ?></option>
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="state"><?php echo ucfirst(get_phrase('state')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="state" class="form-control" id="state" data-allow-clear="true" readonly data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" >
											<optgroup label="<?php echo ucfirst(get_phrase('states')); ?>" id="state-group">
												<option value="Córdoba" selected>Cordoba</option>
											</optgroup>
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="postalcode"><?php echo ucfirst(get_phrase('postalcode')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="postalcode" class="select2" data-allow-clear="true" data-placeholder="<?php echo ucfirst(get_phrase('select')); ?>" id="postalcode">
											<option value="" selected disabled></option> 
											<optgroup label="<?php echo ucfirst(get_phrase('postal_codes')); ?>" id="postal-codes-group">
											</optgroup>
										</select>

									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="locality"><?php echo ucfirst(get_phrase('locality')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="locality" class="form-control" id="locality" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
											<option value="" selected disabled ><?php echo ucfirst(get_phrase('select')); ?></option>
											<optgroup label="<?php echo ucfirst(get_phrase('localities')); ?>" id="locality-group"> 
											</optgroup>
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="neighborhood"><?php echo ucfirst(get_phrase('neighborhood')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="neighborhood" id="neighborhood"  data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>"/> 
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="address"><?php echo ucfirst(get_phrase('address')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="address" id="address"  data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>"/> 
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="address_line"><?php echo ucfirst(get_phrase('address_line')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="address_line" id="address_line"  data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>"/> 
									</div>
								</div>
							
							</div>
						</div>
						
						<div class="tab-pane" id="tab2-3">
							
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="phone_fij"><?php echo ucfirst(get_phrase('landline')); ?></label>
										<input class="form-control" name="phone_fij" id="phone_fij" data-mask="9999999" placeholder="" autofocus/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="phone_cel"><?php echo ucfirst(get_phrase('cell_phone')); ?></label>
										<input type="text" name="phone_cel" id="phone_cel" class="form-control" data-mask="+54 999 9999999" />
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab2-4">			
							
							<div class="row">
								<div class="col-md-12">
									<div class="form-group text-center">
										<label for="field-1" class="control-label"><?php echo ucfirst(get_phrase( 'photo')); ?></label>
										<br>
										<div class="fileinput fileinput-new" data-provides="fileinput">
											<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
												<img src="http://placehold.it/200x200" alt="...">
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px !important; height: 150px !important; max-width: 200px !important; max-height: 150px !important; border: 4px solid #ebebeb;"></div>
											<div>
												<span class="btn btn-info btn-file">
													<span class="fileinput-new"><?php echo ucfirst(get_phrase('select')); ?></span>
													<span class="fileinput-exists"><?php echo ucfirst(get_phrase('change')); ?></span>
													<input type="file" name="userfile" accept="image/*"  >
												</span>
												<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo ucfirst(get_phrase('remove')); ?></a>
											</div>
										</div>
									</div> 
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab2-5">
							<div class="row">
								
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">Email<span class="required-value">&nbsp;*</span></label>
										
										<div class="input-group">
											<div class="input-group-addon">
												<i class="entypo-mail"></i>
											</div>
											<input type="text" class="form-control" name="email" id="email" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>"/>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label"><?php echo ucfirst(get_phrase('user_name')); ?></label>
										
										<div class="input-group">
											<div class="input-group-addon">
												<i class="entypo-user"></i>
											</div>
											<input type="text" class="form-control" name="username" id="username"/>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row">
								
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label"><?php echo ucfirst(get_phrase('password')); ?><span class="required-value">&nbsp;*</span></label>
										
										<div class="input-group">
											<div class="input-group-addon">
												<i class="entypo-key"></i>
											</div>
											
											<input type="password" class="form-control" name="password" id="password" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" />
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab2-6">
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

		$(document).on('keydown', 'input, select, textarea', function(e) {
			var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
			if (e.keyCode === 37) { // flecha izquierda
				focusable = form.find('input,select,textarea, li:not(.disabled) a').filter(':visible');
				next = focusable.eq(focusable.index(this) - 1);

				if (next.length) {
					next.focus();
				}
				return false;
			} else if (e.keyCode === 39) { // flecha derecha
				focusable = form.find('input,select ,textarea, li:not(.disabled) a').filter(':visible');
				next = focusable.eq(focusable.index(this) + 1);

				if (next.length) {
					next.focus();
				}
				return false;
			}
    	});

		get_postalcode();
		get_class_sections();

		$('#postalcode').change(function() {
			var codigo_postal = $(this).val();
			get_localidades(codigo_postal);
    	});
		
    });
    

	function get_class_sections() {

    	$.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_all_section/' ,
            success: function(response)
            {
                jQuery('#section_selector_holder').html(response);
            }
        });

    }

	function get_localidades(codigo_postal) {
		$.ajax({
			url: '<?php echo base_url();?>index.php?admin/get_postalcode_localidad/' + codigo_postal,
			success: function(response) {
				// Reemplazar el contenido del optgroup con las opciones recibidas
				$('#locality-group').html(response);
			}
		});
	}

	function get_postalcode() {
		$.ajax({
			url: '<?php echo base_url();?>index.php?admin/get_postal_codes/',
			success: function(response) {
				// Reemplazar el contenido del optgroup con las opciones recibidas
				$('#postal-codes-group').html(response);
			}
		});
	}


</script>

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