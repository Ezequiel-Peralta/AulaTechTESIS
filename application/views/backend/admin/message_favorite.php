<div class="mail-env">
		
			<!-- compose new email button -->
			<div class="mail-sidebar-row visible-xs">
				<a href="mailbox-compose.html" class="btn btn-success btn-icon btn-block">
					Enviar correo
					<i class="entypo-pencil"></i>
				</a>
			</div>
			
			
			<!-- Mail Body -->
			<div class="mail-body">
				
				<div class="mail-header">
					<!-- title -->
					<h3 class="mail-title">
						Favoritos
						<?php if ($favorite_count > 0): ?>
							<span class="count">(<?php echo $favorite_count; ?>)</span>
						<?php else: ?>
							<span class="count">Vacío</span>
						<?php endif; ?>
					</h3>
					
					
				</div>
				
				
				<!-- mail table -->
				<table class="table mail-table" id="table-message-thread">
					<!-- mail table header -->
					<thead>
						<tr>
							<th width="5%">
								<div class="checkbox checkbox-replace">
									<input type="checkbox" id="chk-all-message-thread" />
								</div>
							</th>
							<th colspan="5">
								
								<div class="mail-select-options"> 
									<a type="button" class="btn btn-table btn-white btn-orange-hover popover-white" 
									href="javascript:;" 
									onclick="location.reload();" 
									data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Refrescar">
										<i class="fa fa-refresh"></i>    
									</a>
								</div>
								<div class="mail-select-options"> 
									<a type="button" class="btn btn-table btn-white btn-yellow-hover popover-white" href="javascript:;" id="add_favorite_message_Thread_bulk_btn"
										data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Marcar como favorito">
										<i class="fa fa-star"></i>    
									</a>
								</div>
								<div class="mail-select-options"> 
									<a type="button" class="btn btn-table btn-white btn-yellow-hover popover-white" href="javascript:;" id="remove_favorite_message_Thread_bulk_btn"
										data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Marcar como no favorito">
										<i class="fa fa-star-o"></i>    
									</a>
								</div>

								<div class="mail-select-options"> 
									<a type="button" class="btn btn-table btn-white btn-info-hover popover-white" href="javascript:;" id="read_message_Thread_bulk_btn"
										data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Marcar como visto">
										<i class="fa fa-eye"></i>    
									</a>
								</div>
								<div class="mail-select-options">
									<a type="button" class="btn btn-table btn-white btn-info-hover popover-white" href="javascript:;" id="unread_message_Thread_bulk_btn"
										data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Marcar como no visto">
										<i class="fa fa-eye-slash"></i>    
									</a>
								</div>
								<div class="mail-select-options">
									<a type="button" class="btn btn-table btn-white btn-green-hover popover-white" href="javascript:;" id="draft_message_Thread_bulk_btn"
										data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Archivar">
										<i class="fa fa-archive"></i>    
									</a>
								</div>
								<div class="mail-select-options">
									<a type="button" class="btn btn-table btn-white btn-danger-hover popover-white" href="javascript:;" id="delete_message_Thread_bulk_btn"
										data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Papelera">
										<i class="fa fa-trash"></i>    
									</a>
								</div>

								<div class="mail-pagination" colspan="2">
									
								</div>
							</th>
						</tr>
					</thead>
					
					<!-- email list -->
                    <tbody>
    <?php foreach ($messages as $message): ?>
		<tr class="<?php echo $message['new_message_count'] > 0 ? 'unread' : ''; ?>">
            <td>
                <div class="checkbox checkbox-replace">
                    <input type="checkbox" id="<?php echo $message['message_thread_code']; ?>"/>
                </div>
            </td>
            <td class="col-name">
                <a href="<?php echo base_url(); ?>index.php?admin/message_settings/favorite/message/<?php echo $message['message_thread_code']; ?>/<?php echo ($message['is_favorite'] == 1) ? 'remove' : 'add'; ?>" class="star popover-white" 
					data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo ($message['is_favorite'] == 1) ? 'Eliminar de favoritos' : 'Añadir a favoritos'; ?>">
                    <i class="<?php echo ($message['is_favorite'] == 1) ? 'fa fa-star' : 'fa fa-star-o'; ?>"></i>
                </a>
                <a href="<?php echo base_url(); ?>index.php?admin/message_read/<?php echo $message['message_thread_code']; ?>" class="col-name popover-white" 
				data-toggle="popover" 
				data-trigger="hover" 
				data-placement="top" 
				data-content="<?php
						echo $message['last_sender_lastname'] . ", " . $message['last_sender_firstname'] . ". ";
					?>" 
				data-original-title="Última actividad">
				<img src="<?php echo $message['last_sender_photo']; ?>" class="img-circle" width="30" height="30" style="margin-right: 5px;">
					<?php
						// Construir el texto a mostrar en el enlace
						$text = "Últ. Act. " . $message['last_sender_lastname'] . ", " . $message['last_sender_firstname'] . ". ";

						// Establecer la longitud máxima para mostrar
						$maxLength = 35; 

						// Truncar el texto si es necesario
						if (strlen($text) > $maxLength) {
							$text = substr($text, 0, $maxLength - 3) . '...'; // Restar 3 por los puntos suspensivos
						}

						echo $text;
					?>
				</a>
				<a href="">
					<?php if ($message['new_message_count'] > 0): ?>
						<span class="badge-primary badge"><?php echo $message['new_message_count']; ?></span>
					<?php endif; ?>
				</a>
            </td>
            <td class="col-subject">
			<a href="javascript:;" style="cursor: default;">
			<span class="popover-white" data-toggle="popover" data-trigger="hover" data-placement="top" 
    data-content="<?php echo implode(', ', array_map(function($tag) { return ucfirst(get_phrase($tag)); }, $message['tags'])) . '.'; ?>" 
    data-original-title="Etiquetas">
								
							
                    <!-- Muestra las etiquetas -->
					<?php if (!empty($message['tags'])): ?>
						<?php 
							$maxTags = 1; // El máximo de etiquetas que se mostrarán
							$totalTags = count($message['tags']); // Contar cuántas etiquetas hay en total
							$visibleTags = array_slice($message['tags'], 0, $maxTags); // Mostrar solo las primeras etiquetas
							$remainingTags = $totalTags - $maxTags; // Calcular cuántas etiquetas quedan sin mostrar

							$tag_styles = [];
							foreach ($result_message_tag as $tag_data) {
								$tag_styles[$tag_data['name']] = $tag_data['label']; 
							}
						?>

						<?php foreach ($visibleTags as $tag): ?>
							<?php 
								$label_class = isset($tag_styles[$tag]) ? $tag_styles[$tag] : 'label-default'; 
							?>
							<span class="label label-tag <?php echo $label_class; ?>">
								<?php echo ucfirst(get_phrase($tag)); ?>
							</span>
						<?php endforeach; ?>

						<?php if ($remainingTags > 0): ?>
							<span class="label label-tag label-default">+<?php echo $remainingTags; ?></span>
						<?php endif; ?>
					<?php endif; ?>
					</span>


					<span class="popover-white  subject-span" 
							data-toggle="popover" data-trigger="hover" data-placement="top" 
							data-content="<?php echo ucfirst($message['subject']); ?>" 
							data-original-title="Asunto">
						<?php 
							$maxLength = 25; 
							$subject = ucfirst($message['subject']); 

							if (strlen($subject) > $maxLength) {
								echo substr($subject, 0, $maxLength) . '...';
							} else {
								echo $subject;
							}
						?>
					</span>
                </a>
            </td>
            <td class="col-options">
				<span class="popover-white attachments" data-toggle="popover" data-trigger="hover" data-placement="top" 
										data-content="<?php
								$elements = [];
								if ($message['has_text']) $elements[] = 'texto/s';
								if ($message['has_image']) $elements[] = 'imagen/es';
								if ($message['has_video']) $elements[] = 'video/s';
								if ($message['has_audio']) $elements[] = 'audio/s';
								if ($message['has_document']) $elements[] = 'documento/s';
								echo implode(', ', $elements); 
							?>." data-original-title="Elementos adjuntos">
				<?php 
					$maxItems = 2; // Número máximo de íconos a mostrar
					$shownItems = 0;
					$remainingItems = count($elements) - $maxItems; // Calcular cuántos quedan sin mostrar

					// Mostrar los primeros íconos
					if ($message['has_text'] && $shownItems < $maxItems): $shownItems++; ?>
						<a href="javascript:;">
							<i class="entypo-doc"></i>
						</a>
					<?php endif; ?>

					<?php if ($message['has_image'] && $shownItems < $maxItems): $shownItems++; ?>
						<a href="javascript:;">
							<i class="entypo-picture"></i>
						</a>
					<?php endif; ?>

					<?php if ($message['has_video'] && $shownItems < $maxItems): $shownItems++; ?>
						<a href="javascript:;">
							<i class="entypo-video"></i>
						</a>
					<?php endif; ?>

					<?php if ($message['has_audio'] && $shownItems < $maxItems): $shownItems++; ?>
						<a href="javascript:;">
							<i class="entypo-music"></i>
						</a>
					<?php endif; ?>

					<?php if ($message['has_document'] && $shownItems < $maxItems): $shownItems++; ?>
						<a href="javascript:;">
							<i class="entypo-doc-text-inv"></i>
						</a>
					<?php endif; ?>

					

				<!-- Mostrar +n si quedan elementos sin mostrar -->
				<?php if ($remainingItems > 0): ?>
					<span class="attachments-remaining-items">+<?php echo $remainingItems; ?></span>
				<?php endif; ?>
				</span>
            </td>

            <td class="col-time">
                <?php
                date_default_timezone_set('America/Argentina/Buenos_Aires');
                $timestamp = strtotime($message['last_message_timestamp']);
                $hoy = date('Y-m-d');
                $ayer = date('Y-m-d', strtotime('yesterday'));
                $fecha_mensaje = date('Y-m-d', $timestamp);

                if ($fecha_mensaje == $hoy) {
                    echo "Hoy " . date('h:i a', $timestamp);
                } elseif ($fecha_mensaje == $ayer) {
                    echo "Ayer " . date('h:i a', $timestamp);
                } else {
                    echo date('j F h:i a', $timestamp);
                }
                ?>
            </td>
			<td class="col-options">
				
				<a type="button" class="btn btn-table btn-white btn-info-hover popover-white" href="<?php echo base_url(); ?>index.php?admin/message_settings/user_message_status/<?php echo $message['message_thread_code']; ?>/<?php echo ($message['new_message_count']) > 0 ? 'read' : 'unread'; ?>/message"
					data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo $message['new_message_count'] > 0 ? 'Marcar como visto' : 'Marcar como no visto'; ?>">
                    <i class="<?php echo $message['new_message_count'] > 0 ? 'fa fa-eye' : 'fa fa-eye-slash'; ?>"></i>    
                </a>

				<button type="button" class="btn btn-table btn-white btn-green-hover popover-white" href="javascript:;" onclick="sweet_modal_message_move_to('<?php echo base_url();?>index.php?admin/message_settings/move_to/<?php echo $message['message_thread_code'];?>');"
				data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Mover a">
					<i class="entypo-shuffle"></i>
				</button>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>


					
					<!-- mail table footer -->
					<tfoot>
						<tr>
							<th width="5%">
								<div class="checkbox checkbox-replace">
									<input type="checkbox" />
								</div>
							</th>
							<th colspan="5">
								
								<div class="mail-pagination" colspan="2">
								
								</div>
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
			
			<!-- Sidebar -->
			<div class="mail-sidebar">
				
				<!-- compose new email button -->
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

					<li  class="active">
						<a href="<?php echo base_url(); ?>index.php?admin/message_favorite/">
							<?php if ($favorite_count > 0): ?>
								<span class="badge badge-danger badge-tag badge-mail-menu pull-right"><?php echo $favorite_count; ?></span> 
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
				
				<h4>Por etiqueta</h4>
				
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
						<li class="<?php echo $tag_info['name'] == $active_tag ? 'active' : ''; ?>">
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

        <script>
    var messages = <?php echo json_encode($messages); ?>;
    console.log(messages);
</script>


<script type="text/javascript">
$(document).ready(function() {
	$('#chk-all-message-thread').on('change', function() {
        var is_checked = $(this).is(':checked');
        $('#table-message-thread tbody input[type=checkbox]').each(function(i, el) {
            $(el).prop('checked', is_checked).trigger('change');
        });
    });

	$('#delete_message_Thread_bulk_btn').on('click', function(e) {
		e.preventDefault(); 


		console.log("click");
        var selectedMessageThread = [];
		
		$('#table-message-thread input[type="checkbox"]:checked').each(function() {
            selectedMessageThread.push($(this).attr('id'));
        });

        if (selectedMessageThread.length > 0) {
            var url = '<?php echo base_url();?>index.php?admin/message_settings/delete_message_thread_bulk/message/' + selectedMessageThread.join('-');
			confirm_sweet_modal_delete_message_thread_bulk(url);
			console.log(url);
        } else {
            Swal.fire({
                title: "¡Ninguna conversacion seleccionada para enviar a la papelera!",
                text: "",
                showCloseButton: true,
                icon: "warning",
                iconColor: "#d33",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Aceptar"
            })
        }
    });

	$('#draft_message_Thread_bulk_btn').on('click', function(e) {
		e.preventDefault(); 


		console.log("click");
        var selectedMessageThread = [];
		
		$('#table-message-thread input[type="checkbox"]:checked').each(function() {
            selectedMessageThread.push($(this).attr('id'));
        });

        if (selectedMessageThread.length > 0) {
            var url = '<?php echo base_url();?>index.php?admin/message_settings/draft_message_thread_bulk/message/' + selectedMessageThread.join('-');
			confirm_sweet_modal_draft_message_thread_bulk(url);
			console.log(url);
        } else {
            Swal.fire({
                title: "¡Ninguna conversacion seleccionada para archivar!",
                text: "",
                showCloseButton: true,
                icon: "warning",
                iconColor: "#d33",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Aceptar"
            })
        }
    });

	$('#read_message_Thread_bulk_btn').on('click', function(e) {
		e.preventDefault(); 


		console.log("click");
        var selectedMessageThread = [];
		
		$('#table-message-thread input[type="checkbox"]:checked').each(function() {
            selectedMessageThread.push($(this).attr('id'));
        });

        if (selectedMessageThread.length > 0) {
            var url = '<?php echo base_url();?>index.php?admin/message_settings/read_message_thread_bulk/message/' + selectedMessageThread.join('-');
			confirm_sweet_modal_read_message_thread_bulk(url);
			console.log(url);
        } else {
            Swal.fire({
                title: "¡Ninguna conversacion seleccionada para marcar como visto!",
                text: "",
                showCloseButton: true,
                icon: "warning",
                iconColor: "#d33",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Aceptar"
            })
        }
    });

	$('#unread_message_Thread_bulk_btn').on('click', function(e) {
		e.preventDefault(); 


		console.log("click");
        var selectedMessageThread = [];
		
		$('#table-message-thread input[type="checkbox"]:checked').each(function() {
            selectedMessageThread.push($(this).attr('id'));
        });

        if (selectedMessageThread.length > 0) {
            var url = '<?php echo base_url();?>index.php?admin/message_settings/unread_message_thread_bulk/message/' + selectedMessageThread.join('-');
			confirm_sweet_modal_unread_message_thread_bulk(url);
			console.log(url);
        } else {
            Swal.fire({
                title: "¡Ninguna conversacion seleccionada para marcar como no visto!",
                text: "",
                showCloseButton: true,
                icon: "warning",
                iconColor: "#d33",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Aceptar"
            })
        }
    });

	$('#add_favorite_message_Thread_bulk_btn').on('click', function(e) {
		e.preventDefault(); 

        var selectedMessageThread = [];
		
		$('#table-message-thread input[type="checkbox"]:checked').each(function() {
            selectedMessageThread.push($(this).attr('id'));
        });

        if (selectedMessageThread.length > 0) {
            var url = '<?php echo base_url();?>index.php?admin/message_settings/add_favorite_message_thread_bulk/message_sent/' + selectedMessageThread.join('-');
			confirm_sweet_modal_add_favorite_message_thread_bulk(url);
			console.log(url);
        } else {
            Swal.fire({
                title: "¡Ninguna conversacion seleccionada para marcar como favorito!",
                text: "",
                showCloseButton: true,
                icon: "warning",
                iconColor: "#d33",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Aceptar"
            })
        }
    });

	$('#remove_favorite_message_Thread_bulk_btn').on('click', function(e) {
		e.preventDefault(); 

        var selectedMessageThread = [];
		
		$('#table-message-thread input[type="checkbox"]:checked').each(function() {
            selectedMessageThread.push($(this).attr('id'));
        });

        if (selectedMessageThread.length > 0) {
            var url = '<?php echo base_url();?>index.php?admin/message_settings/remove_favorite_message_thread_bulk/message_sent/' + selectedMessageThread.join('-');
			confirm_sweet_modal_remove_favorite_message_thread_bulk(url);
			console.log(url);
        } else {
            Swal.fire({
                title: "¡Ninguna conversacion seleccionada para marcar como no favorito!",
                text: "",
                showCloseButton: true,
                icon: "warning",
                iconColor: "#d33",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Aceptar"
            })
        }
    });
	
});

</script>