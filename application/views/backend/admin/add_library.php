<?php
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $current_date = date('Y-m-d'); // Formato compatible con el input date
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/library/create/' , array('class' => 'form-wizard validate', 'enctype' => 'multipart/form-data'));?>
				
			
					<div class="steps-progress">
						<div class="progress-indicator"></div>
					</div>
					
					<ul>
						<li class="active">
							<a href="#tab2-1" data-toggle="tab"><span>1</span><?php echo ucfirst(get_phrase('information'));?></a>
						</li>
						<li>
							<a href="#tab2-2" data-toggle="tab"><span>2</span><?php echo ucfirst(get_phrase('files'));?></a>
						</li>
						<li>
							<a href="#tab2-3" data-toggle="tab"><span>3</span><?php echo ucfirst(get_phrase('confirmation')); ?></a>
						</li>
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane active" id="tab2-1">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="filename"><?php echo ucfirst(get_phrase('file_name')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="filename" id="filename" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" placeholder="" autofocus"/>
									</div>
								</div>
                                <div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="date"><?php echo ucfirst(get_phrase('date')); ?><span class="required-value">&nbsp;*</span></label>
										<input class="form-control" name="date" type="date" id="date" 
											value="<?php echo $current_date; ?>" 
											data-validate="required" 
											data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" 
											 />
									</div>
								</div>
							</div>
                            <div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('class')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="class_id" class="form-control" data-validate="required" id="class_id" 
											data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>"
												onchange="return get_class_sections(this.value)">
											<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>
											<?php 
												$classes = $this->db->get('class')->result_array();
												foreach($classes as $row3):
													?>
                                                    <option value="<?php echo $row3['class_id'];?>">
                                                        <?php echo $row3['name'];?>°
                                                    </option>
												<?php
												endforeach;
											?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('section')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="section_id" class="form-control" id="section_selector_holder"  data-validate="required" 
												onchange="get_section_subjects(this.value);" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
											<option value="" selected disabled><?php echo ucfirst(get_phrase('first_select_the_class')); ?></option>
											
										</select>
									</div>				
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
                                        <label for="field-2" class="control-label"><?php echo ucfirst(get_phrase('subject')); ?><span class="required-value">&nbsp;*</span></label>
										<select name="subject_id" class="form-control" id="section_selector_holder_subject"  data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
											<option value=""><?php echo ucfirst(get_phrase('first_select_the_class_and_section')); ?></option>
										</select>
									</div>
								</div>
                            </div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label" for="description"><?php echo ucfirst(get_phrase('description')); ?></label>
										<textarea class="form-control" style="resize: vertical;" name="description" id="description" rows="5" ></textarea>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab2-2">			
							<div class="row">
								<div class="col-md-12">
									<div class="form-group text-center">
										<label for="field-1" class="control-label"><?php echo ucfirst(get_phrase('file')); ?><span class="required-value">&nbsp;*</span></label>
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
													<input type="file" name="library_file" 
														accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain"
														data-validate="required" 
														data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
												</span>
												<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo ucfirst(get_phrase('remove')); ?></a>
											</div>
										</div>
									</div> 
								</div>
							</div>
						</div>

						
						<div class="tab-pane" id="tab2-3">
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
    

	function get_class_sections(class_id) {

        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_section_content_by_class/' + class_id ,
            success: function(response) {
                const emptyOption = '<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>';
                jQuery('#section_selector_holder').html(emptyOption + response);
            }
        });

    }

	function get_section_subjects(section_id) {
		$.ajax({
			url: '<?php echo base_url();?>index.php?admin/get_section_subjects/' + section_id,
			success: function(response) {
				var select = $('#section_selector_holder_subject');
				select.empty();
				
				select.append($('<option>', { 
					value: '', 
					text: '<?php echo ucfirst(get_phrase('select')); ?>', 
					selected: true, 
					disabled: true 
				}));
				
				select.append(response);
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
