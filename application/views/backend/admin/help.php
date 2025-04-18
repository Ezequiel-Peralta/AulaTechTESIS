<div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"> <?php echo ucfirst(get_phrase('downloadable_material'));?></h4>
            <br>
            <ul class="info-list">
                <li>
                    <a class="dt-button btn buttons-html5 btn-white btn-sm btn-green-hover" title="<?php echo ucfirst(get_phrase('download'));?>" href="#" download><span><i class="fa fa-file"></i> <?php echo ucfirst(get_phrase('user_manual'));?></span></a>
                </li>
                <li>
                    <a class="dt-button btn buttons-html5 btn-white btn-sm btn-orange-hover" title="<?php echo ucfirst(get_phrase('download'));?>" href="#" download><span><i class="fa fa-file"></i> <?php echo ucfirst(get_phrase('documentation'));?></span></a>
                </li>
            </ul>
        </div>
    </div>



    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;">
                <?php echo ucfirst(get_phrase('aula_tech_team')); ?>
            </h4>
            <br>

            <?php
            $admins = $this->db->order_by('lastname', 'ASC')->get('admin_details')->result_array();

            foreach ($admins as $admin):
            ?>
                <div class="member-entry">
                    <a href="<?php echo base_url();?>index.php?admin/admin_profile/<?php echo $admin['admin_id'];?>" class="member-img">
                        <img src="<?php echo $admin['photo']; ?>" class="img-rounded">
                        <i class="entypo-user" title="<?php echo ucfirst(get_phrase('view_profile'));?>"></i>
                    </a>
                    <div class="member-details">
                        <h4>
                            <a href="<?php echo base_url();?>index.php?admin/admin_profile/<?php echo $admin['admin_id'];?>"><?php echo $admin['lastname'] . ', ' . $admin['firstname']; ?></a>
                        </h4>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>




    <style>
    .img-rounded {
        border-radius: 20px;
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
        margin: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
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
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        padding: 0;
        list-style: none;
    }

    .profile-details .info-list li {
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .profile-details .info-list li strong, 
    .profile-details .info-list li span {
        width: auto; 
        text-align: center; 
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

    .member-entry {
        border: 0px solid #ebebeb !important;
        padding: 15px;
        margin-top: 0px !important;
        margin-bottom: 0px !important;
        box-shadow: 1px 1px 1px rgba(0, 1, 1, 0.02);
        transition: all 300ms ease-in-out;
        border-radius: 3px;
        background-clip: padding-box;
        
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .member-img {
        margin-bottom: 15px;
    }

    .member-entry .member-details h4 {
        font-size: 18px;
        margin-left: 0px; 
        color: #265044 !important;
        font-weight: 600 !important;
    }

    .member-entry .member-img {
        position: relative !important;
    }

    .member-entry .member-img i {
        background-color: rgba(176, 223, 204, 0.7) !important; /* Fondo semitransparente */
        position: absolute !important;
        display: flex !important;;
        justify-content: center !important;
        align-items: center !important;;
        left: 12.5px !important;
        top: 13px !important;
        width: 100% !important;
        height: 100% !important;
        color: #FFF;
        font-size: 25px;
        opacity: 0;
        transform: scale(0.5) !important;
        transition: all 300ms ease-in-out;
        border-radius: 20px !important;
    }

    .member-entry .member-img:hover i {
        opacity: 1 !important; 
        transform: scale(1) !important;
    }

    .member-entry:hover, .member-details {
        background-color: #fff !important;
        border: 0px solid #fff !important;
    }

    .member-details {
        border: 0px solid #fff !important;
    }


</style>
