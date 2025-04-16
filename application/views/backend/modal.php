    <script type="text/javascript">
	function showAjaxModal(url)
	{
		jQuery('#modal_ajax .modal-content').html('<div style="text-align:center;margin-top:200px;"><img src="assets/images/loader.gif" /></div>');
		
		jQuery('#modal_ajax').modal('show', {backdrop: 'static'});
		
		$.ajax({
			url: url,
			success: function(response)
			{
				jQuery('#modal_ajax .modal-content').html(response);
			}
		});
	}
	</script>
    
    <div class="modal fade" id="modal_ajax"  data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                
               
            </div>
        </div>
    </div>
    
    
    
    
    <script type="text/javascript">
	function confirm_modal(delete_url)
	{
		jQuery('#modal-4').modal('show', {backdrop: 'static'});
		document.getElementById('delete_link').setAttribute('href' , delete_url);
	}
	</script>
    
    <div class="modal fade" id="modal-4">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:100px;">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo $system_name;?></h4>
                </div>
                
                <h4 class="modal-text" style="text-align:center;">¿Estas seguro de desactivar esta información?</h4>
                
                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                    <button type="button" class="btn btn-success" data-dismiss="modal"><?php echo ('Cancelar');?></button>
                    <a href="#" class="btn btn-danger" id="delete_link"><?php echo ('Desactivar');?></a>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    function confirm_disable_sweet_modal(url) {
        Swal.fire({
            title: "¿<?php echo ucfirst(get_phrase('are_you_sure_you_want_to_disable_this_element')); ?>?",
            icon: "warning",
            iconColor: "#d33",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            cancelButtonText: "<?php echo ucfirst(get_phrase('cancel')); ?>",
            confirmButtonText: "<?php echo ucfirst(get_phrase('accept')); ?>",
            reverseButtons: true,
            customClass: {
                popup: 'custom-swal-modal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    function confirm_sweet_modal(url) {
        Swal.fire({
            title: "¿<?php echo ucfirst(get_phrase('are_you_sure_you_want_to_do_this')); ?>?",
            icon: "warning",
            iconColor: "#d33",
            showCancelButton: true,
            confirmButtonColor: "#27ae60",
            cancelButtonColor: "#3085d6",
            cancelButtonText: "<?php echo ucfirst(get_phrase('cancel')); ?>",
            confirmButtonText: "<?php echo ucfirst(get_phrase('accept')); ?>",
            reverseButtons: true,
            customClass: {
                popup: 'custom-swal-modal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    function confirm_academic_period_sweet_modal(url) {
        Swal.fire({
            title: "¿Estas seguro de añadir un nuevo periodo académico?",
            text: "El periodo actual será desactivado, y los datos asociados serán archivados para su consulta futura.",
            icon: "warning",
            iconColor: "#d33",
            showCancelButton: true,
            confirmButtonColor: "#27ae60",
            cancelButtonColor: "#3085d6",
            cancelButtonText: "<?php echo ucfirst(get_phrase('cancel')); ?>",
            confirmButtonText: "<?php echo ucfirst(get_phrase('accept')); ?>",
            reverseButtons: true,
            customClass: {
                popup: 'custom-swal-modal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }


    function confirm_enable_sweet_modal(url) {
        Swal.fire({
            title: "¿<?php echo ucfirst(get_phrase('are_you_sure_you_want_to_enable_this_element')); ?>?",
            icon: "warning",
            iconColor: "#d33",
            showCancelButton: true,
            confirmButtonColor: "#27ae60",
            cancelButtonColor: "#3085d6",
            cancelButtonText: "<?php echo ucfirst(get_phrase('cancel')); ?>",
            confirmButtonText: "<?php echo ucfirst(get_phrase('accept')); ?>",
            reverseButtons: true,
            customClass: {
                popup: 'custom-swal-modal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    function sweet_modal_trash_message_thread(url) {
        Swal.fire({
            title: "¿Qué acción desea realizar?",
            icon: "question",
            iconColor: "#d33",
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: "#d33", 
            cancelButtonColor: "#3085d6",
            denyButtonColor: "#f39c12",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Enviar a la papelera para todos", 
            denyButtonText: "Enviar a la papelera para mí",
            reverseButtons: true,  // Cambia el orden de los botones a: cancelar, confirmar, denegar
            customClass: {
                popup: 'custom-swal-modal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Opción de enviar a la papelera solo para el usuario
                window.location.href = url + "/trash_for_all_user_message_thread_owner";
            } else if (result.isDenied) {
                // Opción de eliminar para todos los usuarios
                window.location.href = url + "/trash_for_user_message_thread_owner";
            }
        });
    }

    function sweet_modal_message_move_to(url) {
        Swal.fire({
            title: "¿Dónde quiere mover el hilo de conversación?",
            icon: "question",
            iconColor: "#d33",
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: "#d33", 
            cancelButtonColor: "#f39c12", 
            denyButtonColor: "#3085d6", 
            cancelButtonText: '<i class="fa fa-arrow-circle-left"></i> Cancelar',
            confirmButtonText: '<i class="fa fa-trash"></i> Papelera', 
            denyButtonText: '<i class="fa fa-archive"></i> Archivados', 
            reverseButtons: true, 
            customClass: {
                popup: 'custom-swal-modal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url + "/trash/messages_trash";
            } else if (result.isDenied) {
                window.location.href = url + "/draft/messages_draft";
            }
        });
    }

    function sweet_modal_payment_method(url) {
        Swal.fire({
            title: "¿Como desea realizar el pago?",
            icon: "question",
            iconColor: "#d33",
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: "#d33", 
            cancelButtonColor: "#f39c12", 
            denyButtonColor: "#3085d6",
            cancelButtonText: '<i class="fa fa-arrow-circle-left"></i> Cancelar',
            confirmButtonText: '<i class="entypo-money"></i> Efectivo', 
            denyButtonText: '<i class="entypo-money"></i> Mercado pago', 
            reverseButtons: true, 
            customClass: {
                popup: 'custom-swal-modal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url + "/trash/messages_trash";
            } else if (result.isDenied) {
                window.location.href = url + "/draft/messages_draft";
            }
        });
    }



</script>

<script type="text/javascript">
    function confirm_disable_sweet_modal_bulk(delete_url) {
        Swal.fire({
            title: "¿<?php echo ucfirst(get_phrase('are_you_sure_you_want_to_disable_the_items')); ?>?",
            icon: "warning",
            iconColor: "#d33",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            cancelButtonText: "<?php echo ucfirst(get_phrase('cancel')); ?>",
            confirmButtonText: "<?php echo ucfirst(get_phrase('accept')); ?>",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = delete_url;
            }
        });
    }

    function confirm_enable_sweet_modal_bulk(delete_url) {
        Swal.fire({
            title: "¿<?php echo ucfirst(get_phrase('are_you_sure_you_want_to_enable_the_items')); ?>?",
            icon: "warning",
            iconColor: "#d33",
            showCancelButton: true,
            confirmButtonColor: "#27ae60",
            cancelButtonColor: "#3085d6",
            cancelButtonText: "<?php echo ucfirst(get_phrase('cancel')); ?>",
            confirmButtonText: "<?php echo ucfirst(get_phrase('accept')); ?>",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = delete_url;
            }
        });
    }
</script>


<script type="text/javascript">
    function confirm_sweet_modal_delete_message_thread_bulk(delete_url) {
        Swal.fire({
            title: "¿Estás seguro de enviar a la papelera estas conversaciones?",
            icon: "warning",
            iconColor: "#d33",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Aceptar",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = delete_url;
            }
        });
    }
</script>

<script type="text/javascript">
    function confirm_sweet_modal_draft_message_thread_bulk(delete_url) {
        Swal.fire({
            title: "¿Estás seguro de archivar estas conversaciones?",
            icon: "warning",
            iconColor: "#d33",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Archivar",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = delete_url;
            }
        });
    }
</script>


<script type="text/javascript">
    function confirm_sweet_modal_read_message_thread_bulk(delete_url) {
        Swal.fire({
            title: "¿Estás seguro de marcar como visto estas conversaciones?",
            icon: "warning",
            iconColor: "#d33",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Aceptar",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = delete_url;
            }
        });
    }
</script>

<script type="text/javascript">
    function confirm_sweet_modal_unread_message_thread_bulk(delete_url) {
        Swal.fire({
            title: "¿Estás seguro de marcar como no visto estas conversaciones?",
            icon: "warning",
            iconColor: "#d33",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Aceptar",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = delete_url;
            }
        });
    }
</script>

<script type="text/javascript">
    function confirm_sweet_modal_add_favorite_message_thread_bulk(delete_url) {
        Swal.fire({
            title: "¿Estás seguro de marcar como favorito estas conversaciones?",
            icon: "warning",
            iconColor: "#d33",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Aceptar",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = delete_url;
            }
        });
    }
</script>

<script type="text/javascript">
    function confirm_sweet_modal_remove_favorite_message_thread_bulk(delete_url) {
        Swal.fire({
            title: "¿Estás seguro de marcar como no favorito estas conversaciones?",
            icon: "warning",
            iconColor: "#d33",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Aceptar",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = delete_url;
            }
        });
    }
</script>

    <style>
        .modal-header {
            background-color: #B0DFCC !important;
        } .modal-title {
            color: #265044 !important;
            font-weight: bold !important;
        } .modal-text, .btn-default {
            color: black !important;
            font-weight: 500 !important;
        } 
        @media (min-width: 768px) {
            .form-horizontal .control-label {
                margin-bottom: 8px;
            }
        }

        @media (min-width: 768px) {
            .modal-dialog {
                width: 600px;
                /* margin: 60px auto !important; */
                margin: 15px auto !important;
            }
        }

        @media (max-width: 767px) { 
            .modal-dialog {
                /* margin: 60px 10px !important; */
                margin: 20px 10px !important;
            }
        }

    </style>

<style>
	 /* .panel-primary, .panel-heading {
        border-color: #265044 !important;
    }  .form-groups-bordered > .form-group {
        border-color: white !important;
    }

    .panel-title {
        color: #265044 !important;
        font-weight: bold !important;
    } */

    .panel-body .form-group label {
        color: #484848 !important;
		font-weight: bolder !important;
    }

    .modal-content {
        border: 7px solid #B0DFCC !important;
        border-radius: 10px !important;
    }

    .modal .modal-header .close {
        color: #265044 !important;
        background: #fff !important;
    }

    .custom-swal-modal, .swal2-container {
        z-index: 9999999999999 !important; 
    }
</style>

