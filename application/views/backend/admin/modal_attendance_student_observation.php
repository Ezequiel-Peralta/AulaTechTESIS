<?php 
$edit_data		=	$this->db->get_where('attendance_student' , array('student_id' => $param2, 'date' => $param3) )->result_array();
foreach ( $edit_data as $row):
?>

<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="entypo-plus"></i><?php echo 'Añadir ausente justificado'?></h4>
</div>

<div class="modal-body" style="height:500px; overflow:auto;">
    <div class="row">
        <div class="col-md-12">
            <div class="panel" data-collapsed="0">
			<div class="panel-body">
                <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-1" class="control-label"><?php echo ('Observación');?></label>
                            <textarea class="form-control" name="observation"  id="observation_textarea" style="resize: none;" value="<?php echo $row['observation'];?>"><?php echo $row['observation'];?></textarea>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="student_id" id="student_id" value="<?php echo $row['student_id'];?>">
                        </div>

                        <!-- <div class="form-group">
                            <label for="field-1" class="control-label"><?php echo ('Imagen de justificado');?></label>
                            <br>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                                        <img src="http://placehold.it/200x200" alt="...">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                            <span class="fileinput-new">Seleccionar imagen</span>
                                            <span class="fileinput-exists">Cambiar</span>
                                            <input type="file" name="userfile" accept="image/*" >
                                        </span>
                                        <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                    </div>
                                </div>
                        </div> -->
                        <!-- <div class="form-group text-center">
                            <button type="submit" class="btn btn-info"  data-dismiss="modal"><?php echo ('Aceptar');?></button>
                        </div> -->
                        
                </div>
            </div>

       </div>
        </div>
    </div>
</div>

<div class="modal-footer text-center" style="text-align: center;">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Volver</button>
    <button type="submit" class="btn btn-success" data-dismiss="modal"><i class="entypo-floppy"></i> Guardar</button>
</div>

<?php
endforeach;
?>

<script>

$(document).ready(function() {
    $('button[type="submit"]').click(function() {
        var observationValue = $('#observation_textarea').val(); // Utiliza el id correcto aquí
        
        var studentId = $('#student_id').val();

        $('#observation_' + studentId).val(observationValue);

        console.log(observationValue);
        console.log(studentId);
    });
});



</script>
