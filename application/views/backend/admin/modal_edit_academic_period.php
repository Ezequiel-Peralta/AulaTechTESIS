<?php 
$edit_data		=	$this->db->get_where('academic_period' , array('id' => $param2) )->result_array();

foreach ( $edit_data as $row):
?>

<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="entypo-pencil"></i><?php echo 'Editar ciclo escolar'?></h4>
</div>

<?php echo form_open(base_url() . 'index.php?admin/academic_period/update/'.$row['id'] , array('class' => 'form-horizontal form-groups-bordered validate'));?>

<div class="modal-body" style="height:500px; overflow:auto;">
    <div class="row">
        <div class="col-md-12">
            <div class="panel" data-collapsed="0">
                <div class="panel-body">
                    <div class="form-group">
                        <div class="group">
                            <input type="text" class="input" name="name" value="<?php echo $row['name'];?>" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                            <span class="bar"></span>
                            <label>Nombre del ciclo<span class="required-value">&nbsp;*</span></label>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="group">
                            <input type="date" class="input" name="start_date" id="start_date" value="<?php echo $row['start_date'];?>" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                            <span class="bar"></span>
                            <label class="label-date">Fecha de inicio<span class="required-value">&nbsp;*</span></label>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="group">
                            <input type="date" class="input" name="end_date" id="end_date" value="<?php echo $row['end_date'];?>" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                            <span class="bar"></span>
                            <label class="label-date">Fecha de finalización<span class="required-value">&nbsp;*</span></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="group">
                            <label><?php echo get_phrase(('status'));?><span class="required-value">&nbsp;*</span></label>
                            <br><br>
                            <select name="status_id" id="status_id" class="form-control select" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                                <option value="" selected disabled><?php echo get_phrase(('select'));?></option>
                                <option <?php echo ($row['status_id']=='0')?'selected':'';?> value="0"><?php echo ucfirst(get_phrase('inactive'));?></option>
                                <option <?php echo ($row['status_id']=='1')?'selected':'';?> value="1"><?php echo ucfirst(get_phrase('active'));?></option>
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
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Volver</button>
    <button type="submit" class="btn btn-success"><i class="entypo-floppy"></i> Guardar</button>
</div>
<?php echo form_close();?> 

<?php
endforeach;
?>


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
