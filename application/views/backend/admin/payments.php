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
							<a href="#tab2-1" data-toggle="tab"><span>1</span>Buscar estudiante</a>
						</li>
						<li>
							<a href="#tab2-2" data-toggle="tab"><span>2</span>Estado de matricula</a>
						</li>
						<!-- <li>
							<a href="#tab2-3" data-toggle="tab"><span>3</span>Medios de pago</a>
						</li>
						<li>
							<a href="#tab2-4" data-toggle="tab"><span>4</span>Pago</a>
						</li> -->
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane active" id="tab2-1">
							<div class="row">
								<div class="col-md-12">
                                    <div class="form-group">
                                        <label for="student">Buscar estudiante:</label>
                                        <br> <br>
                                        <select name="student" id="student" class="select2 form-control" tabindex="1" data-allow-clear="true" required data-placeholder="Seleccionar...">
                                            <option></option>
                                        </select>
                                    </div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab2-2">
    <div class="row">
        <!-- Nombre del estudiante -->
        <div class="col-md-12 text-center">
            <h1 id="student-name">Nombre del estudiante</h1>
        </div>
    </div>

    <div class="row">
        <!-- Matricula, Pago, Debe, Monto -->
        <div class="col-md-3 text-center">
            <p>Matrícula:</p>
        </div>
        <div class="col-md-3 text-center">
            <p>Pago</p>
            <input type="checkbox" id="payment-status" checked>
        </div>
        <div class="col-md-3 text-center">
            <p>Debe</p>
            <input type="checkbox" id="debt-status">
        </div>
        <div class="col-md-3 text-center">
            <p>Monto</p>
            <input type="number" id="amount" value="1500" readonly>
        </div>
    </div>

    <!-- Lista de meses con checkbox -->
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Seleccionar Mes:</label>
                <div class="checkbox">
                    <label><input type="checkbox" value="Marzo"> Marzo</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" value="Abril"> Abril</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" value="Mayo"> Mayo</label>
                </div>
                <!-- Agrega todos los meses hasta diciembre -->
                <div class="checkbox">
                    <label><input type="checkbox" value="Diciembre"> Diciembre</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Total a pagar -->
    <div class="row">
        <div class="col-md-12 text-center">
            <p>Total a pagar:</p>
            <input type="text" id="total-to-pay" value="0" readonly>
        </div>
    </div>

    <!-- Botón de ingresar pago -->
    <div class="row">
        <div class="col-md-12 text-center">
            <button class="btn btn-primary" href="javascript:;" onclick="event.preventDefault(); sweet_modal_payment_method('<?php echo base_url();?>index.php?admin/payment/');">Ingresar Pago</button>
        </div>
    </div>
</div>

						
						<!-- <div class="tab-pane" id="tab2-3">
							
							
						</div>
						<div class="tab-pane" id="tab2-4">			
							
							
						</div> -->

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



<script type="text/javascript">

	$(document).ready(function() {
        // $('#text-input').inputmask({ regex: "[a-zA-Z\\s]*" });
		// $('#text-input').inputmask({ regex: "[a-zA-Z\\s]+" });

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


		
    });


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


<script type="text/javascript">
    $(document).ready(function() {

     
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_all_students/',
            success: function(response) {
                $('#student').empty();
                $('#student').html(response);
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
