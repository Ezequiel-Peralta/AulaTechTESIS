<?php
$task_info = $this->crud_model->get_task_info($param2);
foreach($task_info as $row):?>

<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="entypo-eye"></i><?php echo 'Ver tarea'?></h4>
</div>

<div class="modal-body" style="height:500px; overflow:auto;">

    <div class="row">
        <?php 
        $task_items_query = $this->db->get_where('task_items', array(
            'task_id' => $row['task_id'],
            'status' => 'disabled'
        ));
        $task_items = $task_items_query->result_array();

        // Separar las tareas en incompletas y completas
        $incomplete_task_items = [];
        $complete_task_items = [];

        foreach ($task_items as $item) {
            if ($item['status_id'] == 0) {
                $incomplete_task_items[] = $item;
            } else {
                $complete_task_items[] = $item;
            }
        }

        $incomplete_task_items = array_reverse($incomplete_task_items);
        $complete_task_items =  array_reverse($complete_task_items);

        // Combinar las tareas, priorizando las incompletas
        $prioritized_task_items = array_merge($incomplete_task_items, $complete_task_items);
        ?>
        <div class="col-md-12">
            <div class="tile-block todo_tasks <?php echo $row['task_style']; ?>" id="<?php echo $row['task_id']; ?>" style="min-height: 366.75px;">
                
                <div class="tile-header">
                    <a href="#">
                        <?php echo $row['title']; ?>
                        <span>Lista de tareas deshabilitadas.</span>
                    </a>
                </div>
                
                <div class="tile-content">
                    <ul class="todo-list">
                        <?php foreach ($prioritized_task_items as $item): ?>
                            <li>
                                <div class="checkbox checkbox-replace color-white">
                                    <input id="<?php echo $item['task_item_id']; ?>" class="task_items_id" type="checkbox" <?php echo ($item['status_id'] == 1) ? 'checked' : ''; ?> />
                                    <label><?php echo $item['description']; ?></label>
                                </div>
                                <a href="javascript:;" class="btn task-label-a btn-default" onclick="confirm_sweet_modal('<?php echo base_url();?>index.php?admin/tasks/enabledIndividualTaskItem/<?php echo $item['task_item_id']; ?>');">
                                    <i class="entypo-down-circled"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
            </div>
        </div>
    </div>

    <div class="modal-footer text-center" style="text-align: center;">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Volver</button>
    </div>
</div>

<?php endforeach; ?>


<script>
    $(document).ready(function() {
        $('.modal-footer .btn').click(function() {
            location.reload();
        });
        $('.modal-header .close').click(function() {
            location.reload();
        });
    });

    var $todo_tasks = $(".todo_tasks");
        var $todo_btn = $("#add-task");

        $todo_tasks.find('input[type="text"]').on('keydown', function(ev) {
            if (ev.keyCode == 13) {
                ev.preventDefault();

                // Obtener el ID de la tarea
                var task_id = $(this).closest('.todo_tasks').attr('id');

                if ($.trim($(this).val()).length) {
                    var $todo_entry = $('<li><div class="checkbox checkbox-replace color-white"><input type="checkbox" /><label style="margin-left: 10px;">' + $(this).val() + '</label></div></li>');
                    var item = $(this).val();

                    $(this).val('');

                    $(this).closest('.tile-block').find('.todo-list').prepend($todo_entry);

                    $todo_entry.hide().slideDown('fast');
                    replaceCheckboxes();

                    $.ajax({
                        url: 'index.php?admin/tasks/createIndividualTaskItem/' + task_id + '/' + item,
                        success: function(response) {
                            console.log('Nueva tarea creada exitosamente.');

                            var opts = {
                                "closeButton": true,
                                "debug": false,
                                "positionClass": "toast-top-right",
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            };

                            toastr.success("tarea agregada exitosamente!", opts);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al crear la nueva tarea:', error);
                        }
                    });
                }
            }
        });

    var $task_items_checkbox = $(".task_items_id");

    $($task_items_checkbox).on('click', function(ev) {
        // Obtener el ID de la tarea
        var task_id = $(this).closest('.todo_tasks').attr('id');
        
        // Obtener el ID del ítem de la tarea
        var task_item_id = $(this).attr('id');
        
        // Determinar si el checkbox está marcado o no
        var checked = $(this).is(':checked');
        
        // Determinar el tipo de acción (marcar o desmarcar)
        var checkType = checked ? '1' : '0';

        // Realizar la solicitud AJAX
        $.ajax({
            url: 'index.php?admin/tasks/checkUncheckIndividualTaskItem/' + task_id + '/' + task_item_id + '/' + checkType,
            success: function(response) {
                console.log('Operación realizada exitosamente.', 'index.php?admin/tasks/checkUncheckIndividualTaskItem/' + task_id + '/' + task_item_id + '/' + checkType);
                
                // Aquí puedes manejar cualquier respuesta del servidor si es necesario
            },
            error: function(xhr, status, error) {
                console.error('Error al realizar la operación:', error);
            }
        });
    });
</script>


<style>
    .panel-primary, .panel-heading {
        border-color: #891818 !important;
    }  .form-groups-bordered > .form-group {
        border-color: white !important;
    }

    .panel-title {
        color: #891818 !important;
        font-weight: bold !important;
    }

    .panel-body {
        color: #484848 !important;
    }

    @media screen and (min-width: 768px) {
        .modal-dialog {
            width: 800px !important;
            padding-top: 30px;
            padding-bottom: 30px;
        }
    }

    .selected-skin {
        background-color: black !important;
        opacity: 0.3 !important;
    }

    .panel-heading > .panel-title {
        padding: 5px 0px !important;
    } .principal-head > .panel-title {
        padding: 10px 12px !important;
    }
</style>