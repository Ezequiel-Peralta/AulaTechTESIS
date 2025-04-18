<?php 
$student_id = $param2; 
$section_id = $param3; 
?>

<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?php echo ucfirst(get_phrase('disable_student'))?></h4>
</div>

<?php echo form_open(base_url() . 'index.php?admin/students/inactive_student/'.$student_id . '/' .$section_id  , array('class' => 'form-horizontal form-groups-bordered validate'));?>

<div class="modal-body" style="height:500px; overflow:auto;">
    <div class="row">
        <div class="col-md-12">
            <div class="panel" data-collapsed="0">
                <div class="panel-body">
                    <div class="form-group">
                        <label for="reason"><?php echo ucfirst(get_phrase('reason_for_deactivation'));?></label>
                        <br>
                        <select class="form-control " name="reason" id="reason" required data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                            <option value="" disabled selected><?php echo ucfirst(get_phrase('select_reason')); ?></option>
                            <option value="pass"><?php echo ucfirst(get_phrase('pass')); ?></option>
                            <option value="no_pass"><?php echo ucfirst(get_phrase('no_pass')); ?></option>
                            <option value="other"><?php echo ucfirst(get_phrase('other')); ?></option>
                        </select>
                    </div>
                    <br>
                    <div class="form-group" id="other-reason-group" style="display: none;">
                        <div class="group">
                            <input type="text" class="input" name="other_reason" id="other_reason" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                            <span class="bar"></span>
                            <label><?php echo get_phrase('other_reason'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer text-center" style="text-align: center;">
    <button type="button" onclick="location.reload();" class="btn btn-default" data-dismiss="modal"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;<?php echo ucfirst(get_phrase('back')); ?></button>
    <button type="submit" class="btn btn-success"><i class="entypo-floppy"></i> <?php echo ucfirst(get_phrase('save')); ?></button>
</div>

<?php echo form_close();?>

<script>
    $(document).ready(function() {
        $('#reason').on('change', function() {
            if ($(this).val() === 'other') {
                $('#other-reason-group').show(); 
                $('#other_reason').prop('required', true); 
            } else {
                $('#other-reason-group').hide(); 
                $('#other_reason').prop('required', false); 
            }
        });
    });
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
        width: 100%; 
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
