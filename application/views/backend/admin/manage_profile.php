<?php
$admin_info = $this->crud_model->get_admin_info($user_id);
foreach($admin_info as $row):?>

    <div class="profile-header" style="border-radius: 15px;">
        <img src="assets/images/photo-header.png" class="cover-photo" alt="Cover Photo">
        <img src="<?php echo $row['photo'];?>" class="img-fluid" alt="Profile Picture" width="150" height="150">
        <div class="profile-info">
            <h2 style="font-weight: 600;">
            <?php echo $row['lastname'];?>, <?php echo $row['firstname'];?>.
            </h2>
        </div>
        <div class="profile-buttons">
            <a class="btn btn-secondary profile-button-active"><i class="entypo-vcard"></i> <?php echo ucfirst(get_phrase('information'));?></a>
        </div>
        <br>
    </div>

    <div class="profile-details card mt-4" style="background-color: #fff; border-radius: 15px; padding: 10px 20px 20px 20px;">
        <div class="card-body">
            <h4 class="card-title" style="font-weight: bold;"> <?php echo ucfirst(get_phrase('personal_information'));?></h4>
            <br>
            <ul class="info-list">
                <li>
                    <strong class="info-title"><i class="entypo-mail"></i> Email</strong>
                    <span class="info-cell"><?php echo $row['email'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> <?php echo ucfirst(get_phrase('user_name'));?></strong>
                    <span class="info-cell"><?php echo $row['username'];?></span>
                </li>
                <li>
                    <strong class="info-title"><i class="entypo-user"></i> <?php echo ucfirst(get_phrase('user_type'));?></strong>
                    <span class="info-cell"><?php echo ucfirst(get_phrase('admin'));?></span>
                </li>
            </ul>
        </div>
    </div>


    




    <?php endforeach;?>



    <style>
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
