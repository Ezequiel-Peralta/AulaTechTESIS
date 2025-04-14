<?php
$parent_info	=	$this->Guardians_model->get_parent_info($param2);
foreach($parent_info as $row):?>

<div class="profile-env">
	
<header class="row profile-header" style="background: url('<?php echo base_url();?>assets/images/profile-header.jpg') !important; background-size: cover !important; background-position: center center !important;">
		<div class="col-sm-3">
			
			<a href="#" class="profile-picture">
				<img src="<?php echo $this->crud_model->get_image_url('parent',$row['name'],$row['dni']);?>" 
                	class="img-responsive img-circle" / style="width: 100px !important; height: 100px !important;">
			</a>
			
		</div>
		
		<div class="col-sm-9">
			
			<ul class="profile-info-sections">
				<li style="padding:0px; margin:0px;">
					<div class="profile-name">
							<h3><?php echo $row['name'];?></h3>
					</div>
				</li>
			</ul>
			
		</div>
		
		
	</header>
	
	<section class="profile-info-tabs">
		
		<div class="row">
			
			<div class="">
            		<br>
                <table class="table table-bordered table-hover table-striped">
                
                    <?php if($row['dni'] != ''):?>
                        <tr>
                            <td>DNI</td>
                            <td><b><?php echo $row['dni'];?></b></td>
                        </tr>
                    <?php endif;?>

                    <?php if($row['birthday'] != ''):?>
                    <tr>
                        <td>Fecha de nacimiento</td>
                        <td><b><?php echo $row['birthday'];?></b></td>
                    </tr>
                    <?php endif;?>

                    <?php if($row['gender'] != ''):?>
                    <tr>
                        <td>Genero</td>
                        <!-- <td><b><?php echo $row['gender'];?></b></td> -->
                        <td><b><?php echo ($row['gender'] == 'Male') ? 'Hombre' : 'Mujer'; ?></b></td>
                    </tr>
                    <?php endif;?>

                    <?php if($row['address'] != ''):?>
                    <tr>
                        <td>Dirección</td>
                        <td><b><?php echo $row['address'];?></b>
                        </td>
                    </tr>
                    <?php endif;?>
                
                    <?php if($row['phone_cel'] != ''):?>
                    <tr>
                        <td>Celular</td>
                        <td><b><?php echo $row['phone_cel'];?></b></td>
                    </tr>
                    <?php endif;?>

                    <?php if($row['phone_fij'] != ''):?>
                    <tr>
                        <td>Teléfono fijo</td>
                        <td><b><?php echo $row['phone_fij'];?></b></td>
                    </tr>
                    <?php endif;?>    
                
                    <?php if($row['email'] != ''):?>
                    <tr>
                        <td>Email</td>
                        <td><b><?php echo $row['email'];?></b></td>
                    </tr>
                    <?php endif;?>
                   
                </table>
			</div>
		</div>		
	</section>
	
	
	
</div>


<?php endforeach;?>

<style>
    .profile-header {
        background: url() !important;
    }

    .profile-env > header {
        margin-top: 0px !important;
    }

    .profile-name h3 {
        color: #fff !important;
        background-color: #5B5B5B !important;
        padding: 5px !important;
        font-weight: bold !important;
    }

    .modal-content {
        border: 6px solid #891818 !important;
    }

    .table tbody tr td {
        background-color: #fff !important;
        color: black !important;
    }

    b, strong {
        font-weight: 600 !important;
    }
</style>