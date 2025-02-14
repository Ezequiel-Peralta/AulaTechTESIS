<?php
$is_bcc_user_me = false; 
$user_firstname = $this->session->userdata('firstname');
$user_lastname = $this->session->userdata('lastname');
$user_email = $this->session->userdata('email');
$user_id = $this->session->userdata('login_user_id');
$user_group = $this->session->userdata('login_type');
$photo = $this->session->userdata('photo');


foreach ($messages2[$message_thread_code]['messages'] as $mymessage): 

// BCC (Copia Oculta)
if (!empty($mymessage['mt_bcc'])) {
    foreach ($mymessage['mt_bcc'] as $bcc_user) {
        if (
            htmlspecialchars($bcc_user['firstname']) == $user_firstname &&
            htmlspecialchars($bcc_user['lastname']) == $user_lastname &&
            htmlspecialchars($bcc_user['email']) == $user_email
        ) {
            $is_bcc_user_me = true;
            break; 
        }
    }
}


endforeach?>

<div class="mail-env">

    <!-- compose new email button -->
    <div class="mail-sidebar-row visible-xs">
        <a href="<?php echo base_url(); ?>index.php?admin/message_new/" class="btn btn-success btn-icon btn-block">
            Enviar correo
            <i class="entypo-pencil"></i>
        </a>
    </div>

    <!-- Mail Body -->
    <div class="mail-body">

    <?php 
// Asegúrate de que tu array tenga mensajes
if (!empty($messages2) && isset($messages2[$message_thread_code]['messages'])): 

    // Obtén los tags fuera del foreach
    $tags = isset($messages2[$message_thread_code]['tags']) ? $messages2[$message_thread_code]['tags'] : [];

?>

<?php 
// Asegúrate de que tu array tenga mensajes
if (!empty($messages) && isset($messages[$message_thread_code]['messages'])): 

    // Ordenar los mensajes por timestamp de menor a mayor
    usort($messages[$message_thread_code]['messages'], function($a, $b) {
        return strtotime($a['timestamp']) - strtotime($b['timestamp']);
    });

    // Obtén los tags fuera del foreach
    // $tags = isset($messages[$message_thread_code]['tags']) ? $messages[$message_thread_code]['tags'] : []; 
 
?>

<script>
    console.log('tags:', <?php echo json_encode($tags); ?>);
    console.log('Datos de cada mensaje:', <?php echo json_encode($messages[$message_thread_code]['messages']); ?>);
    console.log('Datos del primer mensaje:', <?php echo json_encode($messages2[$message_thread_code]['messages']); ?>);
</script>

<!-- Mostrar la cabecera solo una vez -->
<div class="mail-header">
    <div class="mail-links">
        <?php
        $user_id = $this->session->userdata('login_user_id'); 
        $user_group = $this->session->userdata('login_type');

        if ($mt_sender_id == $user_id && $mt_sender_group == $user_group) {
            ?>
            <a href="<?php echo base_url();?>index.php?admin/message_settings/<?php echo $message_thread_code;?>/<?php echo ($is_trash_thread == 1) ? 'remove' : 'add'; ?>/trash_for_all_user_message_thread_owner" class="btn <?php echo ($is_trash_thread == 1) ? 'btn-active' : 'btn-inactive'; ?>" title="<?php echo ($is_trash_thread == 1) ? 'Traer de papelera para todos los usuarios de la conversación' : 'Enviar a papelera para todos los usuarios de la conversación'; ?>">
                <i class="entypo-trash"></i>
            </a>
            <?php
        } 
        ?>
        <a href="<?php echo base_url(); ?>index.php?admin/message_settings/favorite/message_read/<?php echo $message_thread_code; ?>/<?php echo ($is_favorite == 1) ? 'remove' : 'add'; ?>" class="btn <?php echo ($is_favorite == 1) ? 'btn-active' : 'btn-inactive'; ?>" title="<?php echo ($is_favorite == 1) ? 'Eliminar de favoritos' : 'Añadir a favoritos'; ?>">
            <i class="entypo-star"></i>
        </a>
        <a href="<?php echo base_url(); ?>index.php?admin/message_settings/draft/message_draft/<?php echo $message_thread_code; ?>/<?php echo ($is_draft == 1) ? 'remove' : 'add'; ?>" class="btn <?php echo ($is_draft == 1) ? 'btn-active' : 'btn-inactive'; ?>" title="<?php echo ($is_trash == 1) ? 'Deshacer archivado de conversación' : 'Archivar conversación'; ?>">
            <i class="fa fa-archive"></i> <!--<i class="entypo-bookmark"></i> <i class="entypo-box"></i> -->
        </a>
      

        <a href="<?php echo base_url(); ?>index.php?admin/message_settings/trash/message/<?php echo $message_thread_code; ?>/<?php echo ($is_trash == 1) ? 'remove' : 'add'; ?>/trash_for_user_message_thread" class="btn <?php echo ($is_trash == 1) ? 'btn-active' : 'btn-inactive'; ?>" title="<?php echo ($is_trash == 1) ? 'Traer de papelera' : 'Enviar a papelera'; ?>">
                <i class="entypo-trash"></i>
            </a>

        <!-- <a href="#" class="btn btn-default" title="enviar al borrador">
            <i class="entypo-box"></i>
        </a> -->
        <?php if (!$is_bcc_user_me): ?>
    <a class="btn btn-primary btn-icon" href="#mail-reply" onclick="$('#fake-form').hide(); $('#to').parent().removeClass('hidden'); $('#to').focus();">
        Responder
        <i class="entypo-reply"></i>
    </a>
<?php endif; ?>
    </div>
    <div class="mail-title">
        <?php echo htmlspecialchars($messages2[$message_thread_code]['messages'][0]['mt_subject']); ?>
        <?php if (!empty($tags)): // Verificamos que $tags no esté vacío ?>
            <br>
            <?php foreach ($tags as $tag): ?> <!-- Usamos la variable $tags definida antes -->
                <span class="label 
                    <?php 
                    switch ($tag) {
                        case 'important':
                            echo 'label-warning';
                            break;
                        case 'urgent':
                            echo 'label-danger';
                            break;
                        case 'homework':
                            echo 'label-info';
                            break;
                        case 'announcement':
                            echo 'label-primary';
                            break;
                        case 'meeting':
                            echo 'label-success';
                            break;
                        case 'event':
                            echo 'label-info';
                            break;
                        case 'reminder':
                            echo 'label-default';
                            break;
                        default:
                            echo 'label-default'; // Etiqueta por defecto
                            break;
                    }
                    ?>">
                    <?php echo ucfirst(get_phrase($tag)); ?>
                </span>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

  

    <div class="mail-info-users">
        <div class="panel-group joined" id="accordion-test-2">
				
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseOne-2" class="collapsed" aria-expanded="false">
                                Usuarios en esta conversación
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne-2" class="panel-collapse collapse "> <!--in-->
                        <div class="panel-body">
                        <div class="mail-details dropdown">
    <?php foreach ($messages2[$message_thread_code]['messages'] as $mymessage): ?>
        <!-- Remitente (Sender) -->
        <div class="message-sender"> 
            <span>Remitente: </span>
            <div class="user-info-mail">
            <?php
                // Obtener los datos del usuario de la sesión
                $user_firstname = $this->session->userdata('firstname');
                $user_lastname = $this->session->userdata('lastname');
                $user_email = $this->session->userdata('email');
                $user_id = $this->session->userdata('login_user_id');
                $user_group = $this->session->userdata('login_type');
                $photo = $this->session->userdata('photo');

                // Comprobar si el remitente es el usuario actual
                $is_sender_me = (
                    htmlspecialchars($mymessage['mt_sender_firstname']) == $user_firstname &&
                    htmlspecialchars($mymessage['mt_sender_lastname']) == $user_lastname &&
                    htmlspecialchars($mymessage['mt_sender_email']) == $user_email
                );

                // Mostrar "yo" si el remitente coincide con el usuario actual
                if ($is_sender_me) {
                    echo '<a href="#" class="dropdown-toggle" id="'. $user_id . '-'. $user_group .'" data-toggle="dropdown" title="' . htmlspecialchars($user_firstname . ' ' . $user_lastname) . '">';
                    echo '<img src="' .$photo . '" class="img-circle" width="30" /> ' .
                                htmlspecialchars($user_email)  . ' [Yo]';
                    echo '</a> <br>';
                } else {
                    // Mostrar los detalles del remitente (sender) con el title
                    echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' . htmlspecialchars($mymessage['mt_sender_firstname'] . ' ' . $mymessage['mt_sender_lastname']) . '">';
                    echo '<img src="' . $mymessage['mt_sender_photo'] . '" class="img-circle" width="30" /> ' .
                        htmlspecialchars($mymessage['mt_sender_email']);
                    echo '</a> <br>';
                }
            ?>
            </div>
        </div> 

        <?php
        // Verificar si el usuario está en Receiver o CC
        $is_receiver_me = (
            htmlspecialchars($mymessage['mt_receiver_firstname']) == $user_firstname &&
            htmlspecialchars($mymessage['mt_receiver_lastname']) == $user_lastname &&
            htmlspecialchars($mymessage['mt_receiver_email']) == $user_email
        );

        $is_cc_user_me = false;
        if (!empty($mymessage['mt_cc'])) {
            foreach ($mymessage['mt_cc'] as $cc_user) {
                if (htmlspecialchars($cc_user['firstname']) == $user_firstname &&
                    htmlspecialchars($cc_user['lastname']) == $user_lastname &&
                    htmlspecialchars($cc_user['email']) == $user_email) {
                    $is_cc_user_me = true;
                    break;
                }
            }
        }

        // Si el usuario está en Receiver o CC, no mostrar BCC
        $can_see_bcc = !($is_receiver_me || $is_cc_user_me);
        ?>

        <?php
        // Mostrar los destinatarios si el usuario no está en Receiver o CC
        if (!empty($mymessage['mt_receiver_id'])) { 
        ?>
            <div class="message-receiver">
                <span>Destinatario: </span>
                <div class="user-info-mail">
                <?php
                    if ($is_receiver_me) {
                        echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' . htmlspecialchars($user_firstname . ' ' . $user_lastname) . '">';
                        echo '<img src="' .  $photo . '" class="img-circle" width="30" /> ' .
                            htmlspecialchars($user_email);
                        echo '</a> <br>';
                    } else {
                        echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' . htmlspecialchars($mymessage['mt_receiver_lastname'] . ' ' . $mymessage['mt_receiver_firstname']) . '">';
                        echo '<img src="' .  $mymessage['mt_receiver_photo'] .  '" class="img-circle" width="30" /> ' .
                            htmlspecialchars($mymessage['mt_receiver_email']);
                        echo '</a> <br>';
                    }
                ?>
                </div>
            </div>
        <?php
        }
        ?>

        <?php
        // Destinatarios CC
        if (!empty($mymessage['mt_cc'])) { 
        ?>
            <div class="message-cc">
                <span>CC: </span>
                <div class="user-info-mail">
                <?php
                    foreach ($mymessage['mt_cc'] as $cc_user) {
                        if (htmlspecialchars($cc_user['firstname']) == $user_firstname &&
                            htmlspecialchars($cc_user['lastname']) == $user_lastname &&
                            htmlspecialchars($cc_user['email']) == $user_email) {
                            echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' . htmlspecialchars($user_firstname . ' ' . $user_lastname) . '">';
                            echo '<img src="' . $photo . '" class="img-circle" width="30" /> ' .
                                htmlspecialchars($user_email) . ' [Yo]';
                            echo '</a>';
                        } else {
                            echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' . htmlspecialchars($cc_user['lastname'] . ' ' . $cc_user['firstname']) . '">';
                            echo '<img src="' . $cc_user['photo'] . '" class="img-circle" width="30" /> ' .
                                htmlspecialchars($cc_user['email']);
                            echo '</a>';
                        }
                    }
                ?>
                </div>
            </div>
        <?php
        }
        ?>

        <?php
        // Mostrar destinatarios BCC solo si el usuario no está en Receiver o CC
        if ($can_see_bcc && !empty($mymessage['mt_bcc'])) { 
        ?>
            <div class="message-bcc">
                <span>BCC: </span>
                <div class="user-info-mail">
                <?php
                    foreach ($mymessage['mt_bcc'] as $bcc_user) {
                        if (htmlspecialchars($bcc_user['firstname']) == $user_firstname &&
                            htmlspecialchars($bcc_user['lastname']) == $user_lastname &&
                            htmlspecialchars($bcc_user['email']) == $user_email) {
                            echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' . htmlspecialchars($user_firstname . ' ' . $user_lastname) . '">';
                            echo '<img src="' . $photo . '" class="img-circle" width="30" /> ' .
                                htmlspecialchars($user_email) . ' [Yo]';
                            echo '</a>';
                        } else {
                            echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' . htmlspecialchars($bcc_user['lastname'] . ' ' . $bcc_user['firstname']) . '">';
                            echo '<img src="' . $bcc_user['photo'] . '" class="img-circle" width="30" /> ' .
                                htmlspecialchars($bcc_user['email']);
                            echo '</a>';
                        }
                    }
                ?>
                </div>
            </div>
        <?php
        }
        ?>
    <?php endforeach; ?>
</div>

                        </div>
                    </div>
                </div>
                </div>
            
        </div>
    </div>

<?php foreach ($messages[$message_thread_code]['messages'] as $mymessage): ?>
    <!-- mail-info div -->
    <div class="mail-info">
        <div class="mail-sender dropdown">
            <a href="javascript;:" class="dropdown-toggle" data-toggle="dropdown">

                <?php
                // Obtener los datos del usuario de la sesión
                $user_firstname = $this->session->userdata('firstname');
                $user_lastname = $this->session->userdata('lastname');
                $user_email = $this->session->userdata('email');
                $user_id = $this->session->userdata('login_user_id');
                $user_group = $this->session->userdata('login_type');
                $photo = $this->session->userdata('photo');

                // Verificar si los datos del remitente coinciden con los datos de la sesión
                if (
                    htmlspecialchars($mymessage['sender_firstname']) == $user_firstname &&
                    htmlspecialchars($mymessage['sender_lastname']) == $user_lastname &&
                    htmlspecialchars($mymessage['sender_email']) == $user_email
                ) {
                    echo ' <img src="'. $photo .'" class="img-circle" width="30" /> ';
                    // Si coinciden, mostrar "me" antes de los datos del remitente
                    echo '<span>yo</span> a ' . htmlspecialchars($mymessage['receiver_firstname']) . ' ' . htmlspecialchars($mymessage['receiver_lastname']);
                } else {
                    echo ' <img src="'. $mymessage['sender_photo'] .'" class="img-circle" width="30" /> ';
                    // Si no coinciden, mostrar como estaba antes
                    echo htmlspecialchars($mymessage['sender_firstname']) . ' ' . htmlspecialchars($mymessage['sender_lastname']) . ' a <span>mi</span>';
                }
                ?>
            </a>

            <!-- <ul class="dropdown-menu dropdown-red">
                <li>
                    <a href="#">
                        <i class="entypo-user"></i>
                        Add to Contacts
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="entypo-menu"></i>
                        Show other messages
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <i class="entypo-star"></i>
                        Star this message
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="entypo-reply"></i>
                        Reply
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="entypo-right"></i>
                        Forward
                    </a>
                </li>
            </ul> -->
        </div>

        <div class="mail-date">
            <?php
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $timestamp = strtotime($mymessage['timestamp']);
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
        </div>
    </div>

    <!-- mail-text div -->
    <div class="mail-text">
        <?php echo nl2br(htmlspecialchars($mymessage['message'])); ?>
    </div>

    <div class="mail-attachments">
    <ul>
    <?php
    $image_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    foreach ($attachments as $files) {
        if (!empty($files)) {
            foreach ($files as $file) {
                $file_name = basename($file);
                
                $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                
                $is_image = in_array($extension, $image_extensions);
                
                $thumb_class = $is_image ? 'thumb' : 'thumb download';
                ?>
                <li>
                    <!-- Verificación para imagen o no -->
                    <a 
                        href="<?php echo $is_image ? 'javascript:;' : $file; ?>" 
                        class="<?php echo $thumb_class; ?> text-center"
                        <?php if ($is_image): ?>
                            onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_file_image_view/<?php echo $file;?>/<?php echo $file_name;?>');"
                        <?php else: ?>
                            download
                        <?php endif; ?>
                    >
                        <img src="assets/images/attach-1.png" alt="Icono de archivo" class="img-rounded" />
                    </a>
                    
                    <a href="javascript:;" class="name">
                        <?php echo $file_name; ?>
                    </a>
                    
                    <div class="links text-center">
                        <?php if ($is_image): ?>
                            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_file_image_view/<?php echo $file;?>/<?php echo $file_name;?>');">Ver</a> |
                        <?php endif; ?>
                        <a href="<?php echo $file; ?>" download>Descargar</a>
                    </div>
                </li>
                <?php
            }
        }
    }
    ?>
    </ul>
</div>



    <hr /> <!-- Separator for messages -->
<?php endforeach; ?>
<?php else: ?>
    <p>No hay mensajes para mostrar.</p>
<?php endif; ?>
<?php endif; ?>

        <div class="mail-reply" id="mail-reply">
            <?php echo form_open_multipart(base_url() . 'index.php?admin/message_new/send_reply/' . $message_thread_code, array('class' => 'form-horizontal form-groups-bordered validate')); ?>

            <?php if ($is_bcc_user_me): ?>
        <!-- Si el usuario está en BCC, muestra el mensaje de no disponible -->
        <div class="disabled-for-bcc-form">
            <div>
                No disponible para responder.
            </div>
        </div>
            <?php elseif ($is_trash == 1): ?>
            <div class="disabled-form" id="disabled-form">
                <div>
                    Sacar de la papelera la conversación para poder responderla.
                </div>
            </div>
        <?php else: ?>
            <div class="fake-form" id="fake-form" href="javascript:;" onclick="$(this).hide(); $('#to').parent().removeClass('hidden'); $('#to').focus();">
                <div>
                    Responder a esta conversación...

                </div>
            </div>

            <?php endif ?>
                    <div class="form-group hidden">
                        <label for="to">Para:</label>
                        <select name="to" id="to" class="select2 form-control" tabindex="1" data-allow-clear="true" data-placeholder="Seleccionar un destinatario...">
                                <option></option>
                                <!-- <optgroup label="United States">
                                    <option value="1">Alabama</option>
                                    <option value="2">Boston</option>
                                    <option value="3">Ohaio</option>
                                    <option value="4">New York</option>
                                    <option value="5">Washington</option>
                                </optgroup> -->
                                <?php foreach ($messages2[$message_thread_code]['messages'] as $mymessage): ?>

<?php
    // Obtener los datos del usuario de la sesión
    $user_firstname = $this->session->userdata('firstname');
    $user_lastname = $this->session->userdata('lastname');
    $user_email = $this->session->userdata('email');
    $user_id = $this->session->userdata('login_user_id');
    $user_group = $this->session->userdata('login_type');
    $photo = $this->session->userdata('photo');

    // Comprobar si el remitente es el usuario actual
    $is_sender_me = (
        htmlspecialchars($mymessage['mt_sender_id']) == $user_id &&
        htmlspecialchars($mymessage['mt_sender_group']) == $user_group
    );

    // Mostrar "yo" si el remitente coincide con el usuario actual
    if (!$is_sender_me) {
        echo '<option value="' . htmlspecialchars($mymessage['mt_sender_group']) . '-' . htmlspecialchars($mymessage['mt_sender_id']) . '">';
        echo  htmlspecialchars($mymessage['mt_sender_email']);
        echo '</option>';
    }

    // Destinatarios (Receiver)
    if (!empty($mymessage['mt_receiver_id'])) {
        $is_receiver_me = (
            htmlspecialchars($mymessage['mt_receiver_id']) == $user_id &&
            htmlspecialchars($mymessage['mt_receiver_group']) == $user_group
        );
        if (!$is_receiver_me) {
            echo '<option value="' . htmlspecialchars($mymessage['mt_receiver_group']) . '-' . htmlspecialchars($mymessage['mt_receiver_id']) . '">';
            echo htmlspecialchars($mymessage['mt_receiver_email']);
            echo '</option>';
        }
    }

    // CC (Copia Carbon)
    if (!empty($mymessage['mt_cc'])) {
        foreach ($mymessage['mt_cc'] as $cc_user) {
            $is_cc_user_me = (
                htmlspecialchars($cc_user['firstname']) == $user_firstname &&
                htmlspecialchars($cc_user['lastname']) == $user_lastname &&
                htmlspecialchars($cc_user['email']) == $user_email
            );
            if (!$is_cc_user_me) {
                echo '<option value="' . htmlspecialchars($cc_user['group']) . '-' . htmlspecialchars($cc_user['id']) . '">';
                echo htmlspecialchars($cc_user['email']);
                echo '</option>';
            }
        }
    }
    

    if (!empty($mymessage['mt_bcc'])) {
        foreach ($mymessage['mt_bcc'] as $bcc_user) {
            if (
                htmlspecialchars($bcc_user['firstname']) == $user_firstname &&
                htmlspecialchars($bcc_user['lastname']) == $user_lastname &&
                htmlspecialchars($bcc_user['email']) == $user_email
            ) {
               
            } else {
                echo '<option value="' . htmlspecialchars($bcc_user['group']) . '-' . htmlspecialchars($bcc_user['id']) . '">';
                echo htmlspecialchars($bcc_user['email']);
                echo '</option>';
            }
        }
    }
?>
<?php endforeach; ?>

                        </select>
                        <div class="compose-message-editor" style="margin: 50px 0px 50px 0px;">
                            <textarea class="form-control wysihtml5" tabindex="5" data-stylesheet-url="assets/css/wysihtml5-color.css" name="message" id="message"></textarea>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div id="file-input-container">
                                </div>
                                <button type="button" class="btn btn-primary" id="add-file-btn">
                                    <i class="glyphicon glyphicon-plus"></i> Añadir otro archivo
                                </button>
                            </div>
                        </div>

                        <div class="text-center" style="margin: 10px 0px;">
                            <button type="submit" class="btn btn-success"><i class="entypo-direction"></i> Enviar</button>
                        </div>

						
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
					<li class="active">
						<a href="<?php echo base_url(); ?>index.php?admin/message/">
							<?php if ($unread_count > 0): ?>
								<span class="badge badge-danger badge-tag badge-mail-menu pull-right"><?php echo $unread_count; ?></span>
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
								<span class="badge badge-grey badge-tag badge-mail-menu pull-right"><?php echo $trash_count; ?></span>
							<?php endif; ?>
							<i class="fa fa-trash"></i>    
							<span class="item-menu-txt">Papelera</span>
						</a>
					</li>

                    <li>
						<a href="<?php echo base_url(); ?>index.php?admin/message_draft/">
							<?php if ($draft_count > 0): ?>
								<span class="badge badge-grey badge-tag badge-mail-menu pull-right"><?php echo $draft_count; ?></span>
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

						$message_counts = [
							'urgent' => 5,
							'homework' => 3,
							'announcement' => 4,
							'meeting' => 0,
							'event' => 0,
							'reminder' => 0,
							'grade_report' => 0,
							'exam' => 0,
							'behavior' => 0,
							'important' => 0
						];
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


<script>
    // Función para agregar un nuevo input de archivo
function addNewFileInput() {
    const container = document.getElementById('file-input-container');

    // Crear un nuevo div con la clase fileinput y las opciones de archivo
    const fileInputWrapper = document.createElement('div');
    fileInputWrapper.classList.add('fileinput', 'fileinput-new');
    fileInputWrapper.setAttribute('data-provides', 'fileinput');

    fileInputWrapper.innerHTML = `
    <br>

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

        <br>
    `;

    // Agregar el nuevo input de archivo al contenedor
    container.appendChild(fileInputWrapper);
}

// Evento para agregar el primer input de archivo al cargar la página
document.getElementById('add-file-btn').addEventListener('click', addNewFileInput);

// Agregar un primer input al cargar la página
document.addEventListener('DOMContentLoaded', addNewFileInput);
</script>