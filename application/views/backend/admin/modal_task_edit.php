<?php 
$edit_data		=	$this->db->get_where('task' , array('task_id' => $param2) )->result_array();
foreach ( $edit_data as $row):

?>



<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="entypo-pencil"></i><?php echo 'Editar tarea'?></h4>
</div>

<?php echo form_open(base_url() . 'index.php?admin/task/update/'.$row['task_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>

<div class="modal-body" style="height:500px; overflow:auto;">
   
<div class="row">
                    <div class="col-md-12 col-xs-12"> 
                        <div class="panel">
                            <div class="panel-heading" style="border-color: #265044 !important;">
                                <div class="panel-title">
                                    <?php echo ('Titulo');?>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="task_title" id="task_title" data-validate="required" placeholder="" value="<?php echo $row['title'];?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-md-12 col-xs-12"> 
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <?php echo ('Estilo');?>
                                </div>
                            </div>
                            </br>
                            <center>
                                <div class="alert alert-info">
                                    <i class="entypo-info-circled"></i>
                                    <strong>Importante!</strong> Haz clic sobre un tema para seleccionarlo. Los temas seleccionados se mostrarán con una transparencia sutil.
                                </div>
                            </center>
                            <div class="panel-body">
                            <div class="gallery-env">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-primary">
                                                        <img src="assets/images/skins/tasks/default.png"
                                                        class = "<?php if ($row['task_style'] == 'tile-primary') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="default">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Por defecto');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-black">
                                                        <img src="assets/images/skins/tasks/black.png"  
                                                        class = "<?php if ($row['task_style'] == 'tile-black') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="black">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Seleccionar estilo');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-red">
                                                        <img src="assets/images/skins/tasks/red.png"
                                                        class = "<?php if ($row['task_style'] == 'tile-red') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="red">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Seleccionar estilo');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-aqua">
                                                        <img src="assets/images/skins/tasks/aqua.png"
                                                        class = "<?php if ($row['task_style'] == 'tile-aqua') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="aqua">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Seleccionar estilo');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-blue">
                                                        <img src="assets/images/skins/tasks/blue.png"
                                                        class = "<?php if ($row['task_style'] == 'tile-blue') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="blue">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Seleccionar estilo');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-cyan">
                                                        <img src="assets/images/skins/tasks/cyan.png"
                                                        class = "<?php if ($row['task_style'] == 'tile-cyan') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="cyan">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Seleccionar estilo');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                    </div>
                                    

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-purple">
                                                        <img src="assets/images/skins/tasks/purple.png"
                                                        class = "<?php if ($row['task_style'] == 'tile-purple') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="purple">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Seleccionar estilo');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-pink">
                                                        <img src="assets/images/skins/tasks/pink.png"
                                                        class = "<?php if ($row['task_style'] == 'tile-pink') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="pink">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Seleccionar estilo');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-orange">
                                                        <img src="assets/images/skins/tasks/orange.png" 
                                                        class = "<?php if ($row['task_style'] == 'tile-orange') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="orange">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Seleccionar estilo');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-green">
                                                        <img src="assets/images/skins/tasks/green.png" 
                                                        class = "<?php if ($row['task_style'] == 'tile-green') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="green">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Seleccionar estilo');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-brown">
                                                        <img src="assets/images/skins/tasks/brown.png"
                                                        class = "<?php if ($row['task_style'] == 'tile-brown') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="brown">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Seleccionar estilo');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-plum">
                                                        <img src="assets/images/skins/tasks/plum.png"
                                                        class = "<?php if ($row['task_style'] == 'tile-plum') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="plum">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Seleccionar estilo');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                        <div class="col-sm-4">
                                            <article class="album">
                                                <header>
                                                    <a href="#" class="album-options-select" id="tile-gray">
                                                        <img src="assets/images/skins/tasks/gray.png"
                                                        class = "<?php if ($row['task_style'] == 'tile-gray') echo 'selected-skin';?>" />
                                                    </a>
                                                    <a href="#" class="album-options" id="gray">
                                                        <i class="entypo-check"></i>
                                                        <?php echo ('Seleccionar estilo');?>
                                                    </a>
                                                </header>
                                            </article>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>

                <input type="hidden" id="task_style" name="task_style" value="<?php echo $row['task_style'];?>">
                <input type="hidden" id="user_id" name="user_id" value="<?php echo $this->session->userdata('admin_id');?>">
                
                <div class="row">
                    <div class="col-md-12 col-xs-12"> 
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <?php echo ('Elementos de la tarea');?>
                                </div>
                            </div>
                            <br>
                            <center>
                                <div class="alert alert-info">
                                    <i class="entypo-info-circled"></i>
                                    <strong>¡Importante!</strong> Puedes editar el nombre de los elementos de la tarea aquí. Para marcar un elemento como completo o incompleto, por favor utiliza la interfaz de la tarea.
                                </div>
                            </center>

                            <?php foreach ($edit_data as $row): ?>
                                <div class="panel-body">
                                    <?php 
                                    // Obtener los elementos de task_items relacionados con la tarea actual
                                    $task_items = $this->db->get_where('task_items', array('task_id' => $row['task_id']))->result_array();
                                    
                                    foreach ($task_items as $item): ?>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo $item['description']; ?>" class="form-control" name="task_items[]" data-task-item-id="<?php echo $item['task_item_id']; ?>" />
                                            <input type="hidden" value="<?php echo $item['task_item_id']; ?>" name="task_item_ids[]" />
                                        </div>
                                    <?php endforeach; ?>
                                    
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
</div>
<div class="modal-footer text-center" style="text-align: center;">
    <button type="button" class="btn btn-default" onclick="location.reload();" data-dismiss="modal"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Volver</button>
    <button type="submit" class="btn btn-success"><i class="entypo-floppy"></i> Guardar</button>
</div>
<?php echo form_close();?> 

<?php
endforeach;
?>



<script type="text/javascript">
    $(".gallery-env").on('click', '.album-options-select', function (e) {
        // Obtener el ID del estilo desde el atributo 'id' del enlace
        var selectedStyleId = $(this).attr("id");

         // Remueve la clase de todas las imágenes
         $(".gallery-env a img").removeClass("selected-skin");
        // Agrega la clase solo a la imagen que se ha seleccionado
        $(this).find("img").addClass("selected-skin");
        
        // Actualizar el valor del campo de entrada oculto con el ID del estilo seleccionado
        $("#task_style").val(selectedStyleId);
        
        console.log('Estilo seleccionado:', selectedStyleId); // Mostrar el ID del estilo seleccionado en la consola
    });

    // $(document).ready(function() {
    //     $('#btnAceptar').click(function() {
    //         var taskTitle = $('#task_title').val();
    //         var taskStyle = $('#task_style').val();
    //         var taskItems1 = $('.tagsinput').val();

    //         console.log('Título de la tarea:', taskTitle);
    //         console.log('Estilo de la tarea:', taskStyle);
    //         console.log('Elementos de la tarea 1:', taskItems1);
          
    //     });
        
    // });
</script>


<style>
    .panel-primary, .panel-heading {
        border-color: #265044 !important;
    }  .form-groups-bordered > .form-group {
        border-color: white !important;
    }

    .panel-title {
        color: #265044 !important;
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
