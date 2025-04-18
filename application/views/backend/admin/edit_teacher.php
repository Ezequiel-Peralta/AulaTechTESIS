<?php 

$edit_data = $this->crudTeacher->get_teacher_info($teacher_id);
foreach ($edit_data as $row):

?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/teacher/update/'.$teacher_id , array('class' => 'form-wizard validate', 'enctype' => 'multipart/form-data'));?>
				
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
							<a href="#tab2-6" data-toggle="tab"><span>6</span><?php echo ucfirst(get_phrase('academic_information')); ?></a>
						</li>
						
						<li>
							<a href="#tab2-7" data-toggle="tab"><span>7</span><?php echo ucfirst(get_phrase('confirmation')); ?></a>
						</li>
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane active" id="tab2-1">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="firstname"><?php echo ucfirst(get_phrase('firstname')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="firstname" id="firstname" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" placeholder="" autofocus value="<?php echo $row['firstname'];?>"/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="lastname"><?php echo ucfirst(get_phrase('lastname')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="lastname" id="lastname" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" placeholder="" value="<?php echo $row['lastname'];?>"/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="dni">DNI<span class="required-value">&nbsp;*</span></label>

										<input class="form-control" name="dni" id="dni" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" data-mask="99999999" placeholder="" value="<?php echo $row['dni'];?>"/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="birthday"><?php echo ucfirst(get_phrase('birthday')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="birthday" type="date" id="birthday" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" value="<?php echo $row['birthday'];?>"/>
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
											<option value="" disabled <?php echo ($row['gender_id'] === null || $row['gender_id'] === '') ? 'selected' : ''; ?> ><?php echo ucfirst(get_phrase('select')); ?></option>
											<option value="0" <?php echo ($row['gender_id'] === 0) ? 'selected' : ''; ?> ><?php echo ucfirst(get_phrase('male')); ?></option>
											<option value="1" <?php echo ($row['gender_id'] === 1) ? 'selected' : ''; ?> ><?php echo ucfirst(get_phrase('female')); ?></option>
											<option value="2" <?php echo ($row['gender_id'] === 2) ? 'selected' : ''; ?> ><?php echo ucfirst(get_phrase('other')); ?></option>
										</select>
                                    </div>
                                </div>
                            </div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="state"><?php echo ucfirst(get_phrase('state')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="state" class="form-control" id="state" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" readonly>
											<optgroup label="<?php echo ucfirst(get_phrase('states')); ?>" id="state-group">
												<option value="Córdoba" selected>Córdoba</option>
											</optgroup>
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="postalcode"><?php echo ucfirst(get_phrase('postalcode')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="postalcode" class="select2" id="postalcode" data-placeholder="<?php echo $row['postalcode']; ?>" value="<?php echo $row['postalcode'];?>"  data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
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
										<option value="<?php echo $row['locality']; ?>" <?php echo ($row['locality'] !== '') ? 'selected' : ''; ?>><?php echo empty($row['locality']) ? ucfirst(get_phrase('select')) : $row['locality']; ?></option>
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
										<input class="form-control" name="neighborhood" id="neighborhood"  data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" value="<?php echo $row['neighborhood']; ?>"/> 
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="address"><?php echo ucfirst(get_phrase('address')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="address" id="address"  data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" value="<?php echo $row['address']; ?>"/> 
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="address_line"><?php echo ucfirst(get_phrase('address_line')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="address_line" id="address_line"  data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" value="<?php echo $row['address_line']; ?>"/> 
									</div>
								</div>
							
							</div>
						</div>
						
						<div class="tab-pane" id="tab2-3">
							
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="phone_fij"><?php echo ucfirst(get_phrase('landline')); ?></label>
										<input class="form-control" name="phone_fij" id="phone_fij" data-mask="9999999" placeholder="" autofocus value="<?php echo $row['phone_fij']; ?>"/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="phone_cel"><?php echo ucfirst(get_phrase('cell_phone')); ?></label>
										<input type="text" name="phone_cel" id="phone_cel" class="form-control" data-mask="+54 999 9999999" value="<?php echo $row['phone_cel']; ?>"/>
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
                                                <img src="<?php echo $row['photo'];?>"  alt="...">
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px !important; height: 150px !important; max-width: 200px !important; max-height: 150px !important; border: 4px solid #ebebeb;"></div>
											<div>
												<span class="btn btn-info btn-file">
													<span class="fileinput-new"><?php echo ucfirst(get_phrase('select')); ?></span>
													<span class="fileinput-exists"><?php echo ucfirst(get_phrase('change')); ?></span>
													<input type="file" name="userfile" accept="image/*" value="<?php echo $row['photo'];?>">
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
										<label class="control-label"><?php echo ucfirst(get_phrase('email')); ?><span class="required-value">&nbsp;*</span></label>
										
										<div class="input-group">
											<div class="input-group-addon">
												<i class="entypo-mail"></i>
											</div>
											<input type="text" class="form-control" name="email" id="email" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" value="<?php echo $row['email'];?>"/>
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
											<input type="text" class="form-control" name="username" id="username" value="<?php echo $row['username'];?>"/>
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
											
											<input type="password" class="form-control" name="password" id="password" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" value="<?php echo $row['password'];?>"/>
										</div>
									</div>
								</div>
	
							</div>
						</div>

						<?php
							$all_sections = $this->db->select('section.section_id, section.name')
							->from('section')
							->join('academic_period', 'section.academic_period_id = academic_period.id')
							->where('academic_period.status_id', 1)
							->get()
							->result_array();
						?>

						<div class="tab-pane" id="tab2-6">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('class_and_section')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="section_id[]" class="select2" multiple>
											<?php 
											foreach ($all_sections as $section):
												// Verificar si el section_id actual está en selected_section_ids
												$selected = in_array($section['section_id'], $selected_section_ids) ? 'selected' : '';
											?>
												<option value="<?php echo $section['section_id']; ?>" <?php echo $selected; ?>>
													<?php echo $section['name']; ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab2-7">
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

<script>
    const guardians = <?php echo json_encode($this->db->get('guardian_details')->result_array()); ?>;
</script>

<script type="text/javascript">
	 function addGuardianEntry() {
    const container = document.getElementById('guardian_container');
    const entry = document.createElement('div');
    entry.className = 'guardian-entry';
    entry.innerHTML = `
        <select name="guardian_id[]" class="form-control" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
            <option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
            ${guardians.map(guardian => `<option value="${guardian.guardian_id}">${guardian.firstname} ${guardian.lastname}</option>`).join('')}
        </select>
        <select name="relationship[]" class="form-control" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
            <option value="" selected disabled><?php echo ucfirst(get_phrase('select_relationship')); ?></option>
            <option value="1"><?php echo ucfirst(get_phrase('father')); ?></option>
            <option value="2"><?php echo ucfirst(get_phrase('mother')); ?></option>
            <option value="3"><?php echo ucfirst(get_phrase('uncle')); ?></option>
            <option value="4"><?php echo ucfirst(get_phrase('aunt')); ?></option>
            <option value="5"><?php echo ucfirst(get_phrase('grandfather')); ?></option>
            <option value="6"><?php echo ucfirst(get_phrase('grandmother')); ?></option>
            <option value="7"><?php echo ucfirst(get_phrase('other')); ?></option>
        </select>
        <button type="button" class="btn btn-danger" title="<?php echo ucfirst(get_phrase('remove')); ?>" onclick="removeGuardianEntry(this)">-</button>
        <br>
    `;
    container.appendChild(entry);
}


    function removeGuardianEntry(button) {
        const entry = button.parentElement;
        entry.remove();
    }

	$(document).ready(function() {
        // $('#text-input').inputmask({ regex: "[a-zA-Z\\s]*" });
		// $('#text-input').inputmask({ regex: "[a-zA-Z\\s]+" });

		function updateFileName(input) {
        const fileNameElement = document.getElementById('file-name');
        if (input.files && input.files[0]) {
            fileNameElement.textContent = input.files[0].name;
        } else {
            fileNameElement.textContent = "<?php echo ucfirst(get_phrase('no_file_uploaded')); ?>";
        }
    }

		$('.make-switch').on('switch-change', function (e, data) {
			var currentValue = $('#gender_id').val();
			var newValue = (currentValue == 1) ? 0 : 1; // Alternar entre 0 y 1
			$('#gender_id').val(newValue);

			console.log('Switch changed. New gender_id value: ' + newValue);
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

		var selectedPostalCode = '<?php echo $row["postalcode"]; ?>'; // Asegúrate de definir esta variable con PHP
        get_postalcode(selectedPostalCode);
		// get_guardians();

		$('#postalcode').change(function() {
			var codigo_postal = $(this).val();
			get_localidades(codigo_postal);
    	});

        // $('#parent').on('change', function() {
        //     var selectedValue = $(this).val();
        //     $('#tags-input').tagsinput('add', selectedValue);
        // });

		$('#parent').on('change', function() {
			// Obtiene el texto de la opción seleccionada en el select de tutores
			var selectedText = $(this).find('option:selected').text();
			// Agrega el texto seleccionado al input de tagsinput
			$('#tags-input').tagsinput('add', selectedText);
		});

		
    });
    


	function get_localidades(codigo_postal) {
		$.ajax({
			url: '<?php echo base_url();?>index.php?admin/get_postalcode_localidad/' + codigo_postal,
			success: function(response) {
				// Reemplazar el contenido del optgroup con las opciones recibidas
				$('#locality-group').html(response);
			}
		});
	}

	function get_postalcode(selectedPostalCode) {
		$.ajax({
			url: '<?php echo base_url();?>index.php?admin/get_postal_codes/',
			success: function(response) {
				// Reemplazar el contenido del optgroup con las opciones recibidas
				$('#postal-codes-group').html(response);

				if (selectedPostalCode) {
                $('#postal-codes-group option').each(function() {
                    if ($(this).val() == selectedPostalCode) {
                        $(this).prop('selected', true);
                        return false; // Salir del bucle al encontrar el valor
                    }
                });
            }
			}
		});
	}

	// function get_guardians() {
	// 	$.ajax({
	// 		url: '<?php echo base_url();?>index.php?admin/get_guardians/',
	// 		success: function(response) {
	// 			$('#guardian-group').html(response);
	// 		}
	// 	});
	// }


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