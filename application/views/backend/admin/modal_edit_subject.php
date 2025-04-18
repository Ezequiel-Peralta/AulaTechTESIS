<?php 
$edit_data		=	$this->db->get_where('subject' , array('subject_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>

<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="entypo-pencil"></i><?php echo 'Editar asignatura' ?></h4>
</div>

<?php echo form_open(base_url() . 'index.php?admin/subjects/update/'.$row['subject_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>

<div class="modal-body" style="height:500px; overflow:auto;">
    <div class="row">
        <div class="col-md-12">
            <div class="panel" data-collapsed="0">
                <div class="panel-body">

                <div class="form-group">
                        <div class="group">
                            <input required="" type="text" class="input" name="name" value="<?php echo $row['name']; ?>" data-message-required="<?php echo ('Value Required'); ?>">
                            <span class="bar"></span>
                            <label>Nombre</label>
                        </div>
                    </div>
                
                <div class="form-group">
                        <div class="group">
                            <label for="class_id">Curso</label>
                            <br><br>
                            <select name="class_id" class="form-control select" data-validate="required" id="class_id" 
								data-message-required="<?php echo ('Valor requerido');?>"
									onchange="return get_class_sections(this.value)">
                                    <?php 
                                    $classes = $this->db->get('class')->result_array();
                                    foreach($classes as $row2):
                                    ?>
                                        <option value="<?php echo $row2['class_id'];?>"
                                            <?php if($row['class_id'] == $row2['class_id'])echo 'selected';?>>
                                                <?php echo $row2['name'];?>
                                                    </option>
                                    <?php
                                    endforeach;
                                    ?>
                            </select>
                            <span class="bar"></span>
                        </div>
                       
                    </div>


                    <div class="form-group">
                        <div class="group">
                            <label for="section_selector_holder">División</label>
                            <br><br>
                            <select name="section_selector_holder" class="form-control select" data-validate="required" id="section_selector_holder" 
								data-message-required="<?php echo ('Valor requerido');?>">
                                    <option value=""><?php echo ('Primero seleccionar el año');?></option>
                                    <?php 
                                        $sections = $this->db->get('section')->result_array();
                                        foreach($sections as $row2):
                                        ?>
                                            <option value="<?php echo $row2['section_id'];?>"
                                                <?php if($row['section_id'] == $row2['section_id'])echo 'selected';?>>
                                                    <?php echo $row2['letter_name'];?>
                                                        </option>
                                        <?php
                                        endforeach;
                                    ?>
                            </select>
                            <span class="bar"></span>
                        </div>
                       
                    </div>

                <div class="form-group">
                        <div class="group">
                            <label for="teacher_id">Profesor</label>
                            <br><br>
                            <select name="teacher_id" class="form-control select" data-validate="required" id="teacher_id" 
								data-message-required="<?php echo ('Valor requerido');?>">
                                    <option value=""><?php echo ('Seleccionar');?></option>
                                    <?php 
                                        $teachers = $this->db->get('teacher_details')->result_array();
                                        foreach($teachers as $row2):
                                        ?>
                                            <option value="<?php echo $row2['teacher_id'];?>"
                                                <?php if($row['teacher_id'] == $row2['teacher_id'])echo 'selected';?>>
                                                    <?php echo $row2['lastname'] . ', ' . $row2['firstname'];?>
                                                        </option>
                                        <?php
                                        endforeach;
                                    ?>
                            </select>
                            <span class="bar"></span>
                        </div>
                       
                    </div>

                
                
        		
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal-footer text-center" style="text-align: center;">
    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload();"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Volver</button>
    <button type="submit" class="btn btn-success"><i class="entypo-floppy"></i> Guardar</button>
</div>

<?php echo form_close(); ?> 

<?php
endforeach;
?>

<script>
     function get_class_sections(class_id) {

$.ajax({
    url: '<?php echo base_url();?>index.php?admin/get_class_sections/' + class_id ,
    success: function(response)
    {
        jQuery('#section_selector_holder').html(response);
    }
});

}

</script>


<style>
    .group {
        position: relative;
    }

    .input {
        font-size: 12px;
        color: #555555;
        padding: 10px 10px 10px 7px;
        display: block;
        width: 100%;
        border: none;
        border-bottom: 0px solid #515151;
        background: #eaf0ee;
        border-radius: 12px;
        transition: border-radius 0.2s ease; 
    }

    .select {
        font-size: 12px;
        color: #555555;
        padding: 0px 10px 0px 10px;
        display: block;
        width: 100%;
        border: none;
        border-bottom: 0px solid #515151;
        background: #eaf0ee;
        border-radius: 12px;
        transition: border-radius 0.2s ease; 
    }

    .select:focus {
        outline: none;
        border-radius: 0; 
        color: #265044;
        font-weight: bold; 
    }

    .input:focus {
        outline: none;
        border-radius: 0; 
        color: #265044;
        font-weight: bold; 
    }

    label {
        color: #999;
        font-size: 12px;
        font-weight: normal;
        position: absolute;
        pointer-events: none;
        left: 5px;
        top: 10px;
        transition: 0.2s ease all;
        -moz-transition: 0.2s ease all;
        -webkit-transition: 0.2s ease all;
    }

    .input:focus ~ label, .input:valid ~ label {
        top: -20px;
        font-size: 12px;
    }

    .bar {
        position: relative;
        display: block;
        width: 100%; /* Make sure the bar takes the full width of the input */
    }

    .bar:before, .bar:after {
        content: '';
        height: 2px;
        width: 0;
        bottom: 0px;
        position: absolute;
        background: #265044;
        transition: 0.2s ease all;
        -moz-transition: 0.2s ease all;
        -webkit-transition: 0.2s ease all;
    }

    .bar:before {
        left: 50%;
    }

    .bar:after {
        right: 50%;
    }

    .input:focus ~ .bar:before, .input:focus ~ .bar:after {
        width: 50%;
    }

    .highlight-input {
        position: absolute;
        height: 60%;
        width: 100px;
        top: 25%;
        left: 0;
        pointer-events: none;
        opacity: 0.5;
    }

    .input:focus ~ .highlight-input {
        animation: inputhighlight-inputer 0.3s ease;
    }

    .select:focus ~ .highlight-input {
        animation: inputhighlight-inputer 0.3s ease;
    }

    @keyframes inputhighlight-inputer {
        from {
            background: #5264AE;
        }

        to {
            width: 0;
            background: transparent;
        }
    }

    
</style>

