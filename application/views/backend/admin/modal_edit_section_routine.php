<?php 
$edit_data		=	$this->db->get_where('section_routine' , array('section_routine_id' => $param2) )->result_array();
?>

<?php foreach($edit_data as $row):?>

<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="entypo-pencil"></i><?php echo 'Editar horario'?></h4>
</div>

<?php echo form_open(base_url() . 'index.php?admin/section_routine/update/'.$row['section_routine_id'], array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>

<div class="modal-body" style="height:500px; overflow:auto;">
<div class="tab-pane box active" id="edit" style="padding: 5px">
    <div class="box-content">
       
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo ('Curso');?></label>
                    <div class="col-sm-5">
                        <select name="class_id" class="form-control"  onchange="return get_class_section2(this.value)">
                            <?php 
                            $classes = $this->db->get('class')->result_array();
                            foreach($classes as $row2):
                            ?>
                                <option value="<?php echo $row2['class_id'];?>" <?php if($row['class_id']==$row2['class_id'])echo 'selected';?>>
                                    <?php echo $row2['name'];?></option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo ('División');?></label>
                    <div class="col-sm-5">
                        <select name="section_id" class="form-control" id="section_selection_holder2" onchange="return get_section_subjects2(this.value)">
                            <?php 
                            $sections = $this->db->get('section')->result_array();
                            foreach($sections as $row2):
                            ?>
                                <option value="<?php echo $row2['section_id'];?>" <?php if($row['section_id']==$row2['section_id'])echo 'selected';?>>
                                    <?php echo $row2['name'];?></option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo ('Materia');?></label>
                    <div class="col-sm-5">
                        <select name="subject_id" class="form-control" id="subject_selection_holder2">
                            <?php 
                            $subjects = $this->db->get('subject')->result_array();
                            foreach($subjects as $row2):
                            ?>
                                <option value="<?php echo $row2['subject_id'];?>" <?php if($row['subject_id']==$row2['subject_id'])echo 'selected';?>>
                                    <?php echo $row2['name'];?></option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo ('Dia');?></label>
                    <div class="col-sm-5">
                        <select name="day_id" class="form-control">
                            <option value="1" 		<?php if($row['day_id']=='1')echo 'selected="selected"';?>>Domingo</option>
                            <option value="2" 		<?php if($row['day_id']=='2')echo 'selected="selected"';?>>Lunes</option>
                            <option value="3" 	<?php if($row['day_id']=='3')echo 'selected="selected"';?>>Martes</option>
                            <option value="4" 	<?php if($row['day_id']=='4')echo 'selected="selected"';?>>Miercoles</option>
                            <option value="5" 	<?php if($row['day_id']=='5')echo 'selected="selected"';?>>Jueves</option>
                            <option value="6" 		<?php if($row['day_id']=='6')echo 'selected="selected"';?>>Viernes</option>
                            <option value="7" 	<?php if($row['day_id']=='7')echo 'selected="selected"';?>>Sabado</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo ('Inicio');?></label>
                    <div class="col-sm-5">
                        <?php 
                            if($row['time_start'] < 13)
                            {
                                $time_start		=	$row['time_start'];
                                $starting_ampm	=	1;
                            }
                            else if($row['time_start'] > 12)
                            {
                                $time_start		=	$row['time_start'] - 12;
                                $starting_ampm	=	2;
                            }
                            
                        ?>
                        <select name="time_start" class="form-control">
                            <?php for($i = 0; $i <= 12 ; $i++):?>
                                <option value="<?php echo $i;?>" <?php if($i ==$time_start)echo 'selected="selected"';?>>
                                    <?php echo $i;?></option>
                            <?php endfor;?>
                        </select>
                        <select name="starting_ampm" class="form-control">
                            <option value="1" <?php if($starting_ampm	==	'1')echo 'selected="selected"';?>>am</option>
                            <option value="2" <?php if($starting_ampm	==	'2')echo 'selected="selected"';?>>pm</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo ('Fin');?></label>
                    <div class="col-sm-5">
                        
                        
                        <?php 
                            if($row['time_end'] < 13)
                            {
                                $time_end		=	$row['time_end'];
                                $ending_ampm	=	1;
                            }
                            else if($row['time_end'] > 12)
                            {
                                $time_end		=	$row['time_end'] - 12;
                                $ending_ampm	=	2;
                            }
                            
                        ?>
                        <select name="time_end" class="form-control">
                            <?php for($i = 0; $i <= 12 ; $i++):?>
                                <option value="<?php echo $i;?>" <?php if($i ==$time_end)echo 'selected="selected"';?>>
                                    <?php echo $i;?></option>
                            <?php endfor;?>
                        </select>
                        <select name="ending_ampm" class="form-control">
                            <option value="1" <?php if($ending_ampm	==	'1')echo 'selected="selected"';?>>am</option>
                            <option value="2" <?php if($ending_ampm	==	'2')echo 'selected="selected"';?>>pm</option>
                        </select>
                    </div>
                </div>
        </form>
    </div>
</div>
<div class="modal-footer text-center" style="text-align: center;">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Volver</button>
    <button type="submit" class="btn btn-success"><i class="entypo-floppy"></i> Guardar</button>
</div>

<?php echo form_close();?> 
<?php endforeach;?>


</div>

<script type="text/javascript">
     function get_class_section2(class_id) {
        console.log("hola");
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_sections/' + class_id ,
            success: function(response)
            {
                jQuery('#section_selection_holder2').html(response);
            }
        });
    }

    function get_section_subjects2(section_id) {
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_section_subjects/' + section_id ,
            success: function(response)
            {
                jQuery('#subject_selection_holder2').html(response);
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
