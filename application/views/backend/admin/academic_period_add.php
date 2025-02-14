<div class="row">
	<div class="col-md-12">
		<div class="panel " data-collapsed="0">
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/academic_period/create/' , array('class' => 'form-wizard validate', 'enctype' => 'multipart/form-data'));?>
				
					<div class="steps-progress">
						<div class="progress-indicator"></div>
					</div>
					
					<ul>
						<li class="active">
							<a href="#tab2-1" data-toggle="tab"><span>1</span><?php echo ucfirst(get_phrase('basic_information')); ?></a>
						</li>
                        <li>
							<a href="#tab2-2" data-toggle="tab"><span>2</span><?php echo ucfirst(get_phrase('confirmation')); ?></a>
						</li>
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane active" id="tab2-1">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label" for="name"><?php echo ucfirst(get_phrase('name_of_period')); ?><span class="required-value">&nbsp;*</span></label>

										<input class="form-control" name="name" id="name" data-validate="required" placeholder="" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" autofocus/>
									</div>
								</div>
								
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
                                        <label class="control-label" for="start_date"><?php echo ucfirst(get_phrase('start_date')); ?><span class="required-value">&nbsp;*</span></label>
                                        <input type="date" class="form-control text-center" name="start_date" id="start_date" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" />
                                    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
                                        <label class="control-label" for="end_date"><?php echo ucfirst(get_phrase('end_date')); ?><span class="required-value">&nbsp;*</span></label>
                                        <input type="date" class="form-control text-center" name="end_date" id="end_date" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>" />
                                    </div>
								</div>
							</div>
							
						</div>
						<div class="tab-pane" id="tab2-2">
							<div class="form-group text-center">
								<button type="submit" class="btn btn-info"><?php echo ucfirst(get_phrase('complete_registration')); ?></button>
							</div>
						</div>
						<ul class="pager wizard">
							<li class="previous">
								<a href="#"><i class="entypo-left-open"></i> <?php echo ucfirst(get_phrase('back')); ?></a>
							</li>
							
							<li class="next">
								<a href="#"  class="btn btn-secondary"><?php echo ucfirst(get_phrase('next')); ?> <i class="entypo-right-open"></i></a>
							</li>
						</ul>
					</div>
				
                    
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

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

    .panel-body .form-group label {
        color: #484848 !important;
		font-weight: bolder !important;
    }

	.has-switch span.switch-right.switch-right {
		background-color: #CC00C0 !important;
		color: #fff !important;
	} .has-switch span.switch-right.switch-right:hover {
		color: #fff !important;
	}

	.has-switch span.switch-small, .has-switch label.switch-small, .has-switch span.switch-sm, .has-switch label.switch-sm {
		padding-left: 10px !important;
		padding-right: 10px !important;
	}

	/* .modal-content {
		width: 700px !important;
	}

	.modal-body {
		height: auto !important;
	} */
</style>
