<div class="mail-env">
		
			<!-- compose new email button -->
			<div class="mail-sidebar-row visible-xs">
				<a href="mailbox-compose.html" class="btn btn-success btn-icon btn-block">
					Enviar mensaje
					<i class="entypo-pencil"></i>
				</a>
			</div>
			
			
			<!-- Mail Body -->
			<div class="mail-body">
				
				<div class="mail-header">
					<!-- title -->
					<div class="mail-title">
                        Enviar mensaje <i class="entypo-pencil"></i>
					</div>
					
					<!-- links -->
					<div class="mail-links">
					
						
					</div>
				</div>
				
				
				<div class="mail-compose">
				
                <?php echo form_open_multipart(base_url() . 'index.php?admin/message_new/send_new/', array('class' => 'form-horizontal form-groups-bordered validate')); ?>
						
                    <div class="form-group">
                        <label for="to">Destinatario:</label>
                        <br> <br>
                        <select name="to" id="to" class="select2 form-control" tabindex="1" data-allow-clear="true" data-placeholder="Seleccionar un destinatario...">
                            <option></option>
                        </select>
                        
                        <div class="field-options">
                            <a href="javascript:;" onclick="$(this).hide(); $('#cc').parent().removeClass('hidden'); $('#cc').focus();">CC</a>
                            <a href="javascript:;" onclick="$(this).hide(); $('#bcc').parent().removeClass('hidden'); $('#bcc').focus();">BCC</a>
                        </div>
                    </div>

                    <div class="form-group hidden">
                        <label for="cc">CC:</label>
                        <br>
                        <select name="cc[]" id="cc" class="select2 form-control" tabindex="2" multiple>
                            <option></option>
                            <optgroup label="United States">
                                <option value="1">Alabama</option>
                                <option value="2">Boston</option>
                                <option value="3">Ohaio</option>
                                <option value="4">New York</option>
                                <option value="5">Washington</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group hidden">
                        <label for="bcc">BCC:</label>
                        <br>
                        <select name="bcc[]" id="bcc" class="select2 form-control" tabindex="3" multiple>
                            <option></option>
                            <optgroup label="United States">
                                <option value="1">Alabama</option>
                                <option value="2">Boston</option>
                                <option value="3">Ohaio</option>
                                <option value="4">New York</option>
                                <option value="5">Washington</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject">Asunto:</label>
                        <br>
                        <input type="text" class="form-control" name="subject" id="subject" tabindex="4" />
                    </div>

                    <div class="form-group">
                        <label for="tags">Etiquetas:</label>
                        <br> 
                        <select name="tags[]" id="tags" class="select2 form-control" tabindex="2" multiple>
                            <option></option>
                            <optgroup label="Etiquetas">
                                <option value="important"><?php echo ucfirst(get_phrase('important')); ?></option>
                                <option value="urgent"><?php echo ucfirst(get_phrase('urgent')); ?></option>
                                <option value="homework"><?php echo ucfirst(get_phrase('homework')); ?></option>
                                <option value="announcement"><?php echo ucfirst(get_phrase('announcement')); ?></option>
                                <option value="meeting"><?php echo ucfirst(get_phrase('meeting')); ?></option>
                                <option value="event"><?php echo ucfirst(get_phrase('event')); ?></option>
                                <option value="reminder"><?php echo ucfirst(get_phrase('reminder')); ?></option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group">
    <!-- <label class="col-sm-3 control-label">Subir Archivos</label> -->
    <div class="col-md-12">
        <div id="file-input-container">
            <!-- Aquí se agregarán dinámicamente nuevos inputs de archivo -->
        </div>
        <button type="button" class="btn btn-primary" id="add-file-btn">
            <i class="glyphicon glyphicon-plus"></i> Añadir otro archivo
        </button>
    </div>
</div>



                    <div class="compose-message-editor">
                        <textarea class="form-control wysihtml5" tabindex="5" data-stylesheet-url="assets/css/wysihtml5-color.css" name="message" id="message"></textarea>
                    </div>
                    <div class="text-center" style="margin-top: 10px;">
                    <button type="submit" class="btn btn-success"><i class="entypo-mail" style="color: #fff !important;"></i> Enviar </button>

                    </div>

						
                    <?php echo form_close(); ?> 
				
				</div>
				
			</div>
			
			<!-- Sidebar -->
			<div class="mail-sidebar">
				
                <div class="mail-sidebar-row hidden-xs">
					<a href="<?php echo base_url(); ?>index.php?admin/message_new/" class="btn btn-success btn-icon btn-block">
						Enviar correo
						<i class="entypo-pencil"></i>
					</a>
				</div>
				
				<!-- menu -->
				<ul class="mail-menu">
					<li>
						<a href="<?php echo base_url(); ?>index.php?admin/message/">
							<?php if ($unread_count > 0): ?>
								<span class="badge badge-gray badge-tag badge-mail-menu pull-right"><?php echo $unread_count; ?></span>
							<?php endif; ?>
							<i class="fa fa-inbox"></i>
							<span class="item-menu-txt">Buzón</span>
						</a>
					</li>
					
					<li>
						<a href="<?php echo base_url(); ?>index.php?admin/message_sent/">
							<?php if ($sent_count > 0): ?>
								<span class="badge badge-gray badge-tag badge-mail-menu pull-right"><?php echo $sent_count; ?></span>
							<?php endif; ?>
							<i class="fa fa-envelope"></i>
							<span class="item-menu-txt">Enviados</span>
						</a>
					</li>

					<li>
						<a href="<?php echo base_url(); ?>index.php?admin/message_favorite/">
							<?php if ($favorite_count > 0): ?>
								<span class="badge badge-gray badge-tag badge-mail-menu pull-right"><?php echo $favorite_count; ?></span> 
							<?php endif; ?>
							<i class="fa fa-star"></i>
							<span class="item-menu-txt">Favoritos</span>
						</a>
					</li>
					
					<li>
						<a href="<?php echo base_url(); ?>index.php?admin/message_trash/">
							<?php if ($trash_count > 0): ?>
								<span class="badge badge-gray badge-tag badge-mail-menu pull-right"><?php echo $trash_count; ?></span>
							<?php endif; ?>
							<i class="fa fa-trash"></i>    
							<span class="item-menu-txt">Papelera</span>
						</a>
					</li>

                    <li>
						<a href="<?php echo base_url(); ?>index.php?admin/message_draft/">
							<?php if ($draft_count > 0): ?>
								<span class="badge badge-gray badge-tag badge-mail-menu pull-right"><?php echo $draft_count; ?></span>
							<?php endif; ?>
							<i class="fa fa-archive"></i>   
							<span class="item-menu-txt">Archivados</span>
						</a>
					</li>
				</ul>
				
				<div class="mail-distancer"></div>
				
				<h4>Etiquetas</h4>
				
				<ul class="mail-menu">
					<?php 
						$tag_data_map = [];
						foreach ($result_message_tag as $tag_data) {
							$tag_data_map[$tag_data['name']] = [
								'badge' => $tag_data['badge'], 
								'name' => $tag_data['name']
							];
						}

					?>

					<?php foreach ($tag_data_map as $tag_name => $tag_info): ?>
						<li>
							<a href="<?php echo base_url(); ?>index.php?admin/message_tag/<?php echo $tag_info['name']; ?>">
								<span class="badge badge-tag <?php echo $tag_info['badge']; ?> badge-roundless pull-left">
									<?php echo isset($message_counts[$tag_name]) ? $message_counts[$tag_name] : ''; ?>
								</span>
								<span class="tag-txt">	<?php echo ucfirst(get_phrase($tag_name)); ?></span>
							
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
				
			</div>
			
		</div>
		
		
	</div>


<script type="text/javascript">
    $(document).ready(function() {
        // Almacenar archivos seleccionados en un array
        let selectedFiles = [];

        // Evento para adjuntar archivos
        $('#attachments').on('change', function () {
            var files = $(this)[0].files;

            if (files.length > 0) {
                console.log('Nuevos archivos seleccionados:');
                
                // Agregar archivos seleccionados al array `selectedFiles`
                for (var i = 0; i < files.length; i++) {
                    selectedFiles.push(files[i]);
                }

                // Mostrar todos los archivos acumulados en `selectedFiles`
                console.log('Archivos acumulados:');
                for (var i = 0; i < selectedFiles.length; i++) {
                    var file = selectedFiles[i];
                    console.log(`Archivo ${i + 1}:`);
                    console.log('Nombre:', file.name);
                    console.log('Tamaño:', (file.size / 1024).toFixed(2), 'KB');
                    console.log('Tipo:', file.type);
                }
            } else {
                console.log('No se seleccionaron archivos.');
            }
        });

     
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_all_users/',
            success: function(response) {
                $('#to').empty();
                $('#to').html(response);
            }
        });

        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_all_users2/',
            success: function(response) {
                $('#cc').empty();
                $('#cc').html(response);
            }
        });

        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_all_users3/',
            success: function(response) {
                $('#bcc').empty();
                $('#bcc').html(response);
            }
        });
       

        $('#to').change(function() {
            var user_option = $(this).val();

            console.log('cambio en to', user_option);
            
        });

       
        $('#cc').on('change', function() {
    var selectedOptions = $(this).find('option:selected');  // Capturar las opciones seleccionadas
    var selectedValues = [];
    var selectedGroups = [];

    selectedOptions.each(function() {
        var value = $(this).val();
        var group = $(this).data('cc-group'); // Obtener el grupo del atributo data-cc-group

        if (value) {
            selectedValues.push(value);
            selectedGroups.push(group); // Agregar el grupo correspondiente
        }
    });

    // Ver en consola los valores y grupos seleccionados
    console.log("Valores seleccionados: ", selectedValues);
    console.log("Grupos seleccionados: ", selectedGroups);

    // Aquí puedes continuar con tu lógica de manejo de los valores y grupos seleccionados
});




        $('#bcc').change(function() {
            var user_option = $(this).val();

            console.log('cambio en bcc', user_option);
            
        });



    });

</script>

<script>
    // Función para agregar un nuevo input de archivo
function addNewFileInput() {
    const container = document.getElementById('file-input-container');

    // Crear un nuevo div con la clase fileinput y las opciones de archivo
    const fileInputWrapper = document.createElement('div');
    fileInputWrapper.classList.add('fileinput', 'fileinput-new');
    fileInputWrapper.setAttribute('data-provides', 'fileinput');

    fileInputWrapper.innerHTML = `
        <div class="input-group">
            <div class="form-control uneditable-input" data-trigger="fileinput">
                <span class="fileinput-filename"></span>
            </div>
            <span class="input-group-addon btn btn-default btn-file">
                <span class="fileinput-new">Seleccionar archivo</span>
                <span class="fileinput-exists">Cambiar</span>
                <input type="file" name="attachments[]">
            </span>
            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Eliminar</a>
        </div>
    `;

    // Agregar el nuevo input de archivo al contenedor
    container.appendChild(fileInputWrapper);
}

// Evento para agregar el primer input de archivo al cargar la página
document.getElementById('add-file-btn').addEventListener('click', addNewFileInput);

// Agregar un primer input al cargar la página
document.addEventListener('DOMContentLoaded', addNewFileInput);
</script>