<?php 
$edit_data		=	$this->db->get_where('attendance_student', array('date' => $param3, 'student_id' => $param2))->result_array();
foreach ( $edit_data as $row):
?>

<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?php echo ucfirst(get_phrase('edit_student_attendance'));?></h4>
</div>

<?php echo form_open(base_url() . 'index.php?admin/edit_attendance_student/'.$row['student_id'].'/'.$row['date'] , array('class' => 'form-horizontal form-groups-bordered validate'));?>

<div class="modal-body" style="height:500px; overflow:auto;">
    <div class="row">
        <div class="col-md-12">
            <div class="panel" data-collapsed="0">
                <div class="panel-body">
                    <div class="form-group">
                        <div class="group">
                            <label><?php echo ucfirst(get_phrase(('attendance_type')));?><span class="required-value">&nbsp;*</span></label>
                            <br><br>
                            <select name="status" id="status" class="form-control select" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                                <option value="" selected disabled><?php echo get_phrase(('select'));?></option>
                                <option value="1" <?php if($row['status'] == '1') echo 'selected';?> ><?php echo ucfirst(get_phrase('present'));?></option>
                                <option value="2" <?php if($row['status'] == '2') echo 'selected';?> ><?php echo ucfirst(get_phrase('absence'));?></option>
                                <option value="3" <?php if($row['status'] == '3') echo 'selected';?> ><?php echo ucfirst(get_phrase('tardie'));?></option>
                                <option value="4" <?php if($row['status'] == '4') echo 'selected';?> ><?php echo ucfirst(get_phrase('justifield_absence'));?></option>
                            </select>
                            <span class="bar"></span>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="group">
                            <input required="" type="date" class="input" name="date" id="date" value="<?php echo $row['date'];?>" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value'));?>">
                            <span class="bar"></span>
                            <label class="label-date"><?php echo ucfirst(get_phrase('date'));?> <span class="required-value">&nbsp;*</span></label>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="group">
                            <input type="text" class="input" name="observation" id="observation" value="<?php echo $row['observation'];?>">
                            <span class="bar"></span>
                            <label><?php echo ucfirst(get_phrase('observation'));?> </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer text-center" style="text-align: center;">
    <button type="button" class="btn btn-default" onclick="location.reload();" data-dismiss="modal"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;<?php echo ucfirst(get_phrase('back'));?></button>
    <button type="submit" class="btn btn-success"><i class="entypo-floppy"></i> <?php echo ucfirst(get_phrase('save'));?></button>
</div>

<?php echo form_close();?> 

<?php endforeach; ?>

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
    } .input[date] ~ label {
        top: -20px !important;
        font-size: 12px !important;
    }

    .label-date {
        top: -20px !important;
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