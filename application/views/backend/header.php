<?php

$this->db->where('user_type', $login_type);
$this->db->where('user_id', $login_user_id);
$this->db->where('status_id', 1);
$count_pending_tasks = $this->db->count_all_results('task');

$this->db->where('user_type', $login_type);
$this->db->where('user_id', $login_user_id);
$this->db->where('status_id', 1);
$tasks = $this->db->get('task')->result_array();

// Obtener todas las columnas de la tabla 'languages'
$columns = $this->db->list_fields('language');

// Filtrar las columnas 'phrase_id' y 'phrase'
$languages = array_filter($columns, function($column) {
    return !in_array($column, ['phrase_id', 'phrase']);
});

// Obtener la preferencia de idioma del usuario
$language_preference = $this->session->userdata('language_preference');

// Determinar la imagen de la bandera por defecto basada en la preferencia del usuario
$language_preference_flag = 'default.png'; // Valor por defecto en caso de que no coincida ninguna preferencia
switch ($language_preference) {
    case 'spanish':
        $language_preference_flag = 'spanish.png';
        break;
    case 'english':
        $language_preference_flag = 'english.png';
        break;
    case 'portuguese':
        $language_preference_flag = 'portuguese.png';
        break;
    default:
        $language_preference_flag = 'default.png';
        break;
}

$user_id = $this->session->userdata('login_user_id');
$user_group = $this->session->userdata('login_type');

$this->db->where('user_id', $user_id);
$this->db->where('user_group', $user_group);
$this->db->where('new_messages_count >', 0); 
$user_new_messages = $this->db->get('user_message_status')->result_array();


?>

<div class="row row-header">
			<div class="col-md-12 col-sm-8 clearfix profile-info-container">
				<ul class="user-info pull-left pull-left-xs pull-none-xsm">
		
					<!-- <li class="notifications dropdown">
		
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
							<i class="entypo-attention"></i>
							<span class="badge badge-info">6</span>
						</a>
		
						<ul class="dropdown-menu">
							<li class="top">
								<p class="small">
									<a href="#" class="pull-right">Mark all Read</a>
									You have <strong>3</strong> new notifications.
								</p>
							</li>
							
							<li>
								<ul class="dropdown-menu-list scroller">
									<li class="unread notification-success">
										<a href="#">
											<i class="entypo-user-add pull-right"></i>
											
											<span class="line">
												<strong>New user registered</strong>
											</span>
											
											<span class="line small">
												30 seconds ago
											</span>
										</a>
									</li>
									
									<li class="unread notification-secondary">
										<a href="#">
											<i class="entypo-heart pull-right"></i>
											
											<span class="line">
												<strong>Someone special liked this</strong>
											</span>
											
											<span class="line small">
												2 minutes ago
											</span>
										</a>
									</li>
									
									<li class="notification-primary">
										<a href="#">
											<i class="entypo-user pull-right"></i>
											
											<span class="line">
												<strong>Privacy settings have been changed</strong>
											</span>
											
											<span class="line small">
												3 hours ago
											</span>
										</a>
									</li>
									
									<li class="notification-danger">
										<a href="#">
											<i class="entypo-cancel-circled pull-right"></i>
											
											<span class="line">
												John cancelled the event
											</span>
											
											<span class="line small">
												9 hours ago
											</span>
										</a>
									</li>
									
									<li class="notification-info">
										<a href="#">
											<i class="entypo-info pull-right"></i>
											
											<span class="line">
												The server is status is stable
											</span>
											
											<span class="line small">
												yesterday at 10:30am
											</span>
										</a>
									</li>
									
									<li class="notification-warning">
										<a href="#">
											<i class="entypo-rss pull-right"></i>
											
											<span class="line">
												New comments waiting approval
											</span>
											
											<span class="line small">
												last week
											</span>
										</a>
									</li>
								</ul>
							</li>
							
							<li class="external">
								<a href="#">View all notifications</a>
							</li>
						</ul>
		
					</li> -->
		
					<!-- Message Notifications -->
					<li class="notifications dropdown">
		
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<i class="entypo-mail"></i>
						<?php 
						$user_message_count = 0; 

						foreach ($user_new_messages as $user_message): 
							$this->db->where('message_thread_code', $user_message['message_thread_code']);
							$this->db->where('user_id', $user_id);
							$this->db->where('user_group', $user_group);
							$this->db->where('is_trash', 0);
							$this->db->where('is_draft', 0);
							$thread_status = $this->db->get('user_message_thread_status')->row_array();

							if ($thread_status) {
								$user_message_count++; 
							}
						endforeach; 
						?>
						<span class="badge badge-secondary"><?php echo $user_message_count; ?></span>
					</a>

		
						<ul class="dropdown-menu">
							<li>
								<ul class="dropdown-menu-list scroller">
									<?php foreach ($user_new_messages as $user_message): 
										// Verificar si el hilo del mensaje no está en la papelera ni en borradores
										$this->db->where('message_thread_code', $user_message['message_thread_code']);
										$this->db->where('user_id', $user_id);
										$this->db->where('user_group', $user_group);
										$this->db->where('is_trash', 0);
										$this->db->where('is_draft', 0);
										$thread_status = $this->db->get('user_message_thread_status')->row_array();

										if ($thread_status): // Solo procesar si las condiciones se cumplen
											// Obtener el mensaje más reciente según el message_thread_code
											$this->db->where('message_thread_code', $user_message['message_thread_code']);
											$this->db->order_by('timestamp', 'desc');
											$message = $this->db->get('message')->row_array(); // El mensaje más reciente

											// Obtener los detalles del remitente desde la tabla correspondiente
											$this->db->where($message['sender_group'] . '_id', $message['sender_id']);
											$sender = $this->db->get($message['sender_group'] . '_details')->row_array();

											// Configurar la zona horaria de Buenos Aires
											date_default_timezone_set('America/Argentina/Buenos_Aires');

											// Obtener la fecha y hora actual
											$current_date = date('Y-m-d'); // Fecha actual en formato YYYY-MM-DD
											$current_time = date('H:i');  // Hora actual en formato HH:mm

											// Convertir el timestamp del mensaje en formato fecha y hora
											$message_timestamp = $message['timestamp']; // Ejemplo: "2024-11-24 14:30:00"
											$message_date = substr($message_timestamp, 0, 10); // Extraer YYYY-MM-DD
											$message_time = substr($message_timestamp, 11, 5); // Extraer HH:mm

											// Comparar la fecha del mensaje con la actual
											if ($message_date === $current_date) {
												// Si es hoy
												$formatted_date = "Hoy a las " . $message_time;
											} elseif ($message_date === date('Y-m-d', strtotime('-1 day'))) {
												// Si fue ayer
												$formatted_date = "Ayer a las " . $message_time;
											} else {
												// Si es una fecha anterior
												$formatted_date = date('d/m/Y', strtotime($message_date)) . " a las " . $message_time;
											}
											?>

											<li class="active">
												<a href="<?php echo base_url(); ?>index.php?admin/message/">
													<span class="image pull-right">
														<img src="<?php echo $sender['photo']; ?>" width="44" alt="" class="img-circle" />
													</span>
													
													<span class="line">
														<strong><?php echo $sender['lastname'] . ', ' . $sender['firstname']; ?></strong>
														- <?php echo $formatted_date; ?>
													</span>
													
													<span class="line desc small" title="<?php echo $message['message']; ?>">
														<?php 
															echo strlen($message['message']) > 45 
																? substr($message['message'], 0, 45) . '...' 
																: $message['message']; 
														?>
													</span>
												</a>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>

							</li>

							<?php 
								$user_message_count = 0; 
								foreach ($user_new_messages as $user_message): 
									$this->db->where('message_thread_code', $user_message['message_thread_code']);
									$this->db->where('user_id', $user_id);
									$this->db->where('user_group', $user_group);
									$this->db->where('is_trash', 0);
									$this->db->where('is_draft', 0);
									$thread_status = $this->db->get('user_message_thread_status')->row_array();

									if ($thread_status) {
										$user_message_count++; 
									}
								endforeach; 

								if ($user_message_count > 0): ?>
									<li class="external text-center">
										<a href="<?php echo base_url(); ?>index.php?admin/message/">Ver todos los mensajes</a>
									</li>
								<?php endif; ?>
							
							
						</ul>

		
					</li>
		
					<!-- Task Notifications -->
					<li class="notifications dropdown">
		
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
							<i class="entypo-list"></i>
							<span class="badge badge-orange"><?php echo $count_pending_tasks;?></span>
						</a>
		
						<ul class="dropdown-menu">
							<li class="top text-center">
								<p>Tienes <?php echo $count_pending_tasks;?> tareas pendientes</p>
							</li>
							<li>
								<ul class="dropdown-menu-list scroller">
									<?php foreach ($tasks as $task): ?>
									<li>
										<a href="#">
											<span class="task">
												<span class="desc"><?php echo $task['title']; ?></span>
												<span class="percent"><?php echo $task['progress']; ?>%</span>
											</span>
											<span class="progress">
												<span style="width:<?php echo $task['progress']; ?>%;" class="progress-bar progress-bar-<?php echo ($task['task_style'])?>">
													<span class="sr-only"><?php echo $task['progress']; ?>% Completado</span>
												</span>
											</span>
										</a>
									</li>
									<?php endforeach; ?>
								</ul>
							</li>
							
							<!-- <li class="external text-center">
								<a href="#">Ver todas las tareas</a>
							</li> -->
						</ul>
		
					</li>
		
				</ul>

				<ul class="user-info pull-right pull-right-xs pull-none-xsm">
					<li class="dropdown language-selector" style="margin-bottom: 0px; margin-top: 2px; margin-right: -4px;">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
							<!-- Mostrar la bandera basada en la preferencia del usuario -->
							<img src="assets/images/flag/<?php echo $language_preference_flag; ?>" width="23" height="23" />
						</a>
						<ul class="dropdown-menu pull-right">
							<?php foreach ($languages as $language): ?>
								<?php
									$flag = '';
									$displayName = '';

									// Determinar la imagen de la bandera y el nombre de visualización basado en el idioma
									switch ($language) {
										case 'spanish':
											$flag = 'spanish.png';
											$displayName = 'Español';
											break;
										case 'english':
											$flag = 'english.png';
											$displayName = 'English';
											break;
										case 'portuguese':
											$flag = 'portuguese.png';
											$displayName = 'Português';
											break;
										default:
											$flag = 'default.png'; // Imagen por defecto si no coincide con ninguno
											$displayName = ucwords($language); // Capitalizar el nombre del idioma
											break;
									}
								?>
								 <li class="language-option <?php echo ($language === $language_preference) ? 'active' : ''; ?>" data-language="<?php echo $language; ?>">
									<a href="javascript:;">
										<img src="assets/images/flag/<?php echo $flag; ?>" width="23" height="23" />
										<span><?php echo $displayName; ?></span>
									</a>
								</li> 
								<!-- <?php if ($language === 'spanish') : ?>
									<li class="language-option <?php echo ($language === $language_preference) ? 'active' : ''; ?>" data-language="<?php echo $language; ?>">
										<a href="javascript:;">
											<img src="assets/images/flag/<?php echo $flag; ?>" width="23" height="23" />
											<span><?php echo $displayName; ?></span>
										</a>
									</li>
								<?php endif; ?> -->

							<?php endforeach; ?>
						</ul>
					</li>
					<!-- <li class="sep"></li> -->
					<li class="theme-page" style="margin-top: 6px; margin-right: 5px;">
						<a href="javascript:;" id="<?php echo $this->session->userdata('theme_preference') === 'light_mode' ? 'dark_mode' : 'light_mode'; ?>">
							<?php echo $this->session->userdata('theme_preference') === 'light_mode' ? '<i class="entypo-moon"></i>' : '<i class="entypo-flash"></i>'; ?>
						</a>
					</li>
		
					<li class="sep"></li> 

					<li class="profile-info dropdown pull-right">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<img src="<?php echo $this->session->userdata('photo');?>" alt="" class="img-circle" width="44" style="margin-top: 1px;"/>
						</a>
						<ul class="dropdown-menu" style="background-color: white; width: 300px;">
							<li class="caret"></li>
							<div class="row" style="padding: 10px 0;">
								<div class="col-sm-12 text-center" style="padding-bottom: 10px;">
									<a href="javascript:;" class="profile-picture">
										<img src="<?php echo $this->session->userdata('photo');?>" alt="" class="img-circle" width="84" style="margin-bottom: 10px;"/>		
									</a>
									<p class="user-name" style="margin: 0; font-size: 16px; font-weight: bold;"><?php echo ucfirst($this->session->userdata('lastname'));?>, <?php echo ucfirst($this->session->userdata('firstname'));?>. </p>
									<p class="user-type" style="margin: 0; color: gray;"><?php echo ucfirst($this->session->userdata('login_type'));?></p>
								</div>
							</div>
							<li> 
								<a type="button" style=" border-radius: 0px !important; font-weight: bold !important;" href="<?php echo base_url();?>index.php?<?php echo $account_type;?>/manage_profile/<?php echo ucfirst($this->session->userdata('login_user_id'));?>" class="btn btn-default btn-icon icon-left btn-hover-custom">
									<?php echo ucfirst(get_phrase('profile'));?>
									<i class="entypo-user"></i>
								</a>
							</li>
							<li>
								<a type="button" style=" border-radius: 0px !important; font-weight: bold !important;" href="<?php echo base_url();?>index.php?<?php echo $account_type;?>/profile_settings/<?php echo ucfirst($this->session->userdata('login_user_id'));?>" class="btn btn-default btn-icon icon-left btn-hover-custom">
									<?php echo ucfirst(get_phrase('setting'));?>
									<i class="entypo-cog"></i>
								</a>
							</li>
							<li>
								<a type="button" style="color: #fff; border-radius: 0px !important; font-weight: bold !important;" href="<?php echo base_url();?>index.php?login_in/logout" class="btn btn-danger btn-icon icon-left btn-hover-custom">
									<?php echo ucfirst(get_phrase('logout'));?>
									<i class="entypo-logout" style="color: #fff !important;"></i>
								</a>
							</li>
						</ul>
					</li>

				</ul>
			</div>
		</div>


<style>
	.progress-bar-tile-default {
    	background-color: #265044 !important;
	} .progress-bar-tile-black {
		background-color: #000 !important;
	} .progress-bar-tile-blue {
		background-color: #0073b7 !important;
	} .progress-bar-tile-brown {
		background-color: #6c541e !important;
	} .progress-bar-tile-cyan {
		background-color: #00b29e !important;
	} .progress-bar-tile-gray {
		background-color: #f5f5f5 !important;
	} .progress-bar-tile-aqua {
		background-color: #00c0ef !important;
	} .progress-bar-tile-green {
		background-color: #00a65a !important;
	} .progress-bar-tile-orange {
		background-color: #ffa812 !important;
	} .progress-bar-tile-pink {
		background-color: #ec3b83 !important;
	} .progress-bar-tile-plum {
		background-color: #701c1c !important;
	} .progress-bar-tile-purple {
		background-color: #ba79cb !important;
	} .progress-bar-tile-red {
		background-color: #f56954 !important;
	}	
</style>


<script type="text/javascript">
	$(".theme-page").on('click', 'a', function () {
		// Obtener el nuevo tema del ID del enlace
		// var theme = '';
		var theme = this.id;

		// Obtener el ID del administrador de la sesión
		var admin_id = <?php echo $this->session->userdata('admin_id');?>;

		console.log("click en el a de cambiar skin");
		console.log("Admin ID: " + admin_id);
		console.log("Nuevo tema: " + theme);

		// Realizar la solicitud AJAX para cambiar el tema
		$.ajax({
			url: '<?php echo base_url();?>index.php?admin/change_theme/'+ admin_id + '/' + theme,
				success: function() {
				// Recargar la página después de cambiar el tema
				window.location.reload();
        }
		});
	});

	$(".language-option").on('click', 'a', function () {
        var language = $(this).closest('.language-option').data('language');

        // Obtener el ID del administrador de la sesión
        var admin_id = <?php echo $this->session->userdata('admin_id'); ?>;

        console.log("click en el a de cambiar idioma");
        console.log("Admin ID: " + admin_id);
        console.log("Nuevo idioma: " + language);

        // Realizar la solicitud AJAX para cambiar el idioma
        $.ajax({
            url: '<?php echo base_url(); ?>index.php?admin/change_language/' + admin_id + '/' + language,
            success: function() {
				// Recargar la página después de cambiar el idioma
				window.location.reload();
            }
        });
    });



	
</script>

		<style>
		 .badge-orange {
			background-color: #f0ad4e;
			color: #fff;
		 }

		 .btn-hover-custom:hover {
        background-color: #B0DFCC !important;
    }

		</style>