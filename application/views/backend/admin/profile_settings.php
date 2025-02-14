<div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"> <i class="entypo-user"></i> <?php echo ucfirst(get_phrase('profile'));?></h4>
            <br>
            <?php 
                    foreach($edit_data as $row):
                        ?>
                        <?php echo form_open(base_url() . 'index.php?admin/profile_settings/update_profile_info' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top' , 'enctype' => 'multipart/form-data'));?>
                            <div style="background-color: #fff; padding: 20px 100px;">   
                                <div class="form-group">
                                    <div class="group">
                                        <input required="" type="text" class="input" name="firstname" value="<?php echo $row['firstname'];?>" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                                        <span class="bar"></span>
                                        <label><?php echo ucfirst(get_phrase('firstname'));?><span class="required-value">&nbsp;*</span></label>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="group">
                                        <input required="" type="text" class="input" name="lastname" value="<?php echo $row['lastname'];?>" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                                        <span class="bar"></span>
                                        <label><?php echo ucfirst(get_phrase('lastname'));?><span class="required-value">&nbsp;*</span></label>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="group">
                                        <input required="" type="text" class="input" name="email" value="<?php echo $row['email'];?>" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                                        <span class="bar"></span>
                                        <label><?php echo ucfirst(get_phrase('email'));?><span class="required-value">&nbsp;*</span></label>
                                    </div>
                                </div>
                                <div class="form-group text-center">
										<br>
										<div class="fileinput fileinput-new" data-provides="fileinput">
											<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                                                <img src="<?php echo $row['photo'];?>"  alt="...">
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px !important; height: 150px !important; max-width: 200px !important; max-height: 150px !important; border: 4px solid #ebebeb;"></div>
											<div>
												<span class="btn btn-info btn-file">
													<span class="fileinput-new"><?php echo ucfirst(get_phrase('select')); ?></span>
													<span class="fileinput-exists"><?php echo ucfirst(get_phrase('change')); ?></span>
													<input type="file" name="userfile" accept="image/*" value="<?php echo $row['photo'];?>">
												</span>
												<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo ucfirst(get_phrase('remove')); ?></a>
											</div>
										</div>
                                </div>
                                <div class="form-group text-center" style="padding-top: 30px;">
                                    <button type="submit" class="btn btn-info"><?php echo ucfirst(get_phrase('update_profile'));?></button>
                                </div>
                            </div>
                        </form>
						<?php
                    endforeach;
                    ?>
        </div>
    </div>





    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"> <i class="entypo-lock"></i> <?php echo ucfirst(get_phrase('password'));?></h4>
            <br>
            <?php 
                    foreach($edit_data as $row):
                        ?>
                        <?php echo form_open(base_url() . 'index.php?admin/profile_settings/change_password' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top' , 'enctype' => 'multipart/form-data'));?>
                            <div style="background-color: #fff; padding: 20px 100px;">   
                                <div class="form-group">
                                    <div class="group">
                                        <input required="" type="password" class="input" name="password" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                                        <span class="bar"></span>
                                        <label><?php echo ucfirst(get_phrase('password'));?><span class="required-value">&nbsp;*</span></label>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="group">
                                        <input required="" type="password" class="input" name="new_password" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                                        <span class="bar"></span>
                                        <label><?php echo ucfirst(get_phrase('new_password'));?><span class="required-value">&nbsp;*</span></label>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="group">
                                        <input required="" type="password" class="input" name="confirm_new_password" data-validate="required" data-message-required="<?php echo ucfirst(get_phrase('required_value')); ?>">
                                        <span class="bar"></span>
                                        <label><?php echo ucfirst(get_phrase('confirm_new_password'));?><span class="required-value">&nbsp;*</span></label>
                                    </div>
                                </div>
                                <div class="form-group text-center" style="padding-top: 30px;">
                                    <button type="submit" class="btn btn-info"><?php echo ucfirst(get_phrase('update_password'));?></button>
                                </div>
                            </div>
                        </form>
						<?php
                    endforeach;
                    ?>
        </div>
    </div>


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
        color: #265044;
        font-size: 12px;
        font-weight: bolder;
        position: absolute;
        pointer-events: none;
        left: 5px;
        top: 10px;
        transition: 0.2s ease all;
        -moz-transition: 0.2s ease all;
        -webkit-transition: 0.2s ease all;
    }

    .input:focus ~ label, .input:valid ~ label {
        top: -25px;
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

    .profile-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center; 
        gap: 20px; 
    }

    .profile-card {
        background-color: #efefef;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        width: 200px; 
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    }  .profile-card:hover {
        background-color: #B0DFCC;
        transform: scale(1.05);
    }

    .profile-card-subject {
        height: 160px; 

    }

    .profile-card-subject h3 {
        font-weight: 600;
        text-align: center;
        font-size: 16px;
        margin: 0; /* Eliminar márgenes adicionales */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap; /* Evita que el texto se desborde */
    }

    .profile-card img {
        border-radius: 50%;
        margin-bottom: 10px;
    }


    .status-dot {
        display: inline-block;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        margin-top: 2px;
        margin-left: 5px;
    }

    .status-dot.online {
        background-color: #4CAF50; 
    }

    .status-dot.offline {
        background-color: #F44336; 
    }

    .status-dot.away {
        background-color: #FFC107; 
    }

    .status-dot.busy {
        background-color: #FF5722; 
    }

    .card-body-guardian {
        padding: 10px 10px !important;
        border-radius: 15px !important;
    }

    .profile-header {
        background-color: #fff;
        text-align: center;
        position: relative;
        border-radius: 15px;
    }
    .profile-header img.img-fluid {
        border-radius: 50%;
        margin-top: -77px;
        border: 7px solid white;
    }
    .profile-header .cover-photo {
        width: 100%;
        height: 180px;
        object-fit: cover;
        object-position: 10% 30%; 
        border-radius: 15px;
        
    }
    

    
    .profile-info {
        margin-top: 10px;
    }
    .profile-buttons {
        margin-top: 20px;
    }
    .profile-buttons .btn {
        margin-top: 10px;
        margin-right: 5px;
        border-radius: 10px;
    }

    .profile-buttons .profile-button-active {
        background-color: #fff !important;
        color: #265044 !important;
        border: 1px solid #265044 !important;
    }

    .profile-description {
        margin-top: 20px;
        padding: 0 20px;
        text-align: center;
    }
    .profile-details {
        margin-top: 20px;
    }
    .profile-details .info-list {
        display: block;
        padding: 0;
        list-style: none;
    }
    .profile-details .info-list li {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .profile-details .info-list li strong {
        width: 50%;
        text-align: left;
    }
    .profile-details .info-list li span {
        width: 50%;
        text-align: right;
    }

    .dropdown-menu {
        border-radius: 15px !important;
        background-color: #B0DFCC !important;
    }

    .info-title {
        color: #265044 !important; 
        font-weight: bold !important;
    }

    .info-cell {
        color: #265044 !important; 
        font-weight: 400 !important;
    }

</style>