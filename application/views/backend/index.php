<?php
	$login_user_id = $this->session->userdata('login_user_id');
	$login_type = $this->session->userdata('login_type');
	
	$system_name        =	$this->db->get_where('settings' , array('type'=>'system_name'))->row()->description;
	$system_title       =	$this->db->get_where('settings' , array('type'=>'system_title'))->row()->description;
	$text_align         =	$this->db->get_where('settings' , array('type'=>'text_align'))->row()->description;
	$account_type       =	$this->session->userdata('login_type');
	
	// $theme_mode        =  $this->db->get_where($login_type, array($login_type . '_id' => $login_user_id))->row('theme_preference');
    $theme_mode        =  $this->session->userdata('theme_preference');
	
	?>
<!DOCTYPE html>
<html lang="en" dir="<?php if ($text_align == 'right-to-left') echo 'rtl';?>">
<head>
	
	<title><?php echo $page_title;?> | <?php echo $system_name;?></title>
    
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<?php include 'includes_top.php';?>
	
</head>
 <body class="page-body <?php echo ($theme_mode == 'light_mode' ? 'light_mode' : 'dark_mode'); ?>" onload=""> <!--show_loading_bar(100); showLoading(); -->

	<div class="page-container <?php if ($text_align == 'right-to-left') echo 'right-sidebar';?>" >
		<?php include $account_type.'/navigation.php';?>	
		<div class="main-content">
		
			<?php include 'header.php';?>

			<!-- <h3 class="breadcrumb" style="background-color: #265044; color: white;">
				<i class="<?php echo $page_icon;?>"></i> 
				<?php echo $page_title;?>
           	</h3> -->
           		<!-- <i class="entypo-right-circled"></i>  -->

			<h3 class="breadcrumb" style="background-color: #265044;">
				<?php foreach ($breadcrumb as $index => $item): ?>
					<?php if ($index > 0): ?>
						<!-- <i class="entypo-right-open"></i>  -->
						&nbsp;/&nbsp;
					<?php endif; ?>
					<a href="<?php echo $item['url']; ?>" <?php if ($index === count($breadcrumb) - 1) echo 'class="last-item"'; ?>><?php echo $item['text']; ?></a>
				<?php endforeach; ?>
			</h3>

		

			<?php include $account_type.'/'.$page_name.'.php';?>

			<?php include 'footer.php';?>

		</div>
		<?php include 'chat.php';?>
        	
		

	</div>

	<!-- <div class="scroll-to-top navbar-fixed-top"> 
		 <button onclick="scrollToTop()">
			<i class="fas fa-arrow-up"></i>
		</button> 
		
	</div>  -->
<!-- <span class="scroll-icon">
			<i class="fas fa-arrow-up"></i>
		</span> -->


    <?php include 'modal.php';?>
    <?php include 'includes_bottom.php';?>
	


</body>


<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        var lastItem = document.querySelector('.last-item');
        lastItem.addEventListener('click', function (event) {
            event.preventDefault();
        });
    });
</script> -->


<script>
    var $toast;
    var $chatToast;

    // Mostrar siempre el toast del chatbot
    $(document).ready(function() {
        if (!$chatToast) {
            var chatOpts = {
                "closeButton": false,
                "debug": false,
                "positionClass": "toast-fixed-bottom-right", 
                "onclick": function() {
                  
                },
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "0",
                "extendedTimeOut": "0", 
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "iconClass": 'toast-custom-chat-icon' 
            };

            // $chatToast = toastr.info('<div class="text-center" style="background-color: #265044 !important;  width: 190px; padding: 5px 10px; border-radius:15px;">hola, ¿Necesitas ayuda?</div><img width="55" src="assets/images/chat-bot-2.png" style="margin-left:10px;" />', null, chatOpts);
        }
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            if (!$toast) {
                var opts = {
                    "closeButton": false,
                    "debug": false,
                    "positionClass": "toast-bottom-right", 
                    "onclick": function() {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    },
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "0", 
                    "extendedTimeOut": "0", 
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut",
                    "iconClass": 'toast-custom-icon' 
                };

                $toast = toastr.warning("", null, opts);
                if ($toast.find('.custom-icon').length === 0) {
                    $toast.append('<i class="entypo-up custom-icon"></i>');
                }
            }
        } else {
            if ($toast) {
                toastr.clear($toast);
                $toast = null;
            }
        }
    });


</script>

<style>
	.page-body {
		font-family: 'Open Sans', 'Source Sans 3', sans-serif !important;
	}

	

	.neon-loading-bar {
		position: fixed;
		left: 0;
		top: 0;
		right: 0;
		background: rgba(48, 54, 65, 0.3);
		height: 5px;
		z-index: 10000;
		top: 0px;
		-webkit-opacity: 1;
		-moz-opacity: 1;
		opacity: 1;
		filter: alpha(opacity = 100);
		-moz-transition: all 300ms ease-in-out;
		-o-transition: all 300ms ease-in-out;
		-webkit-transition: all 300ms ease-in-out;
		transition: all 300ms ease-in-out;
	}

	.neon-loading-bar span {
		display: block;
		position: absolute;
		left: 0;
		top: 0;
		bottom: 0;
		width: 0%;
		background: #7CE8A4 !important;
	}

	/* body {
		background-color: #F7D060 !important;
	} */

	.breadcrumb {
		background-color: #265044 !important;
		border-radius: 5px;
		font-weight: bold;
	}

	.breadcrumb .last-item {
    	cursor: default !important;
	} 
	

	/* .toast-custom-icon .toast-message {
            display: none; 
        }

        .toast-custom-icon {
            width: 50px !important;
            height: 50px !important;
            padding: 0 !important;
            display: flex;
            justify-content: center !important;
            align-items: center !important;
        }

        .custom-icon {
            font-size: 24px !important;
            cursor: pointer !important;
        }

        .toast-bottom-right > .toast-custom-icon {
            width: 50px !important;
            padding: 0px 0px 0px 0px !important;
            background-color: #265044 !important;
        } */

	.toast-custom-icon {
        width: 50px !important;
        height: 50px !important;
        padding: 0 !important;
        display: flex;
        justify-content: center !important;
        align-items: center !important;
		margin-left: 0px !important;
		background-color: #265044 !important;
		border-radius: 50% !important;
		margin-bottom: -5px !important;
    }

    .custom-icon {
        font-size: 24px !important;
        cursor: pointer !important;
    }

    .toast-bottom-right > .toast-custom-icon {
        width: 50px !important;
        padding: 0px !important;
        background-color: #265044 !important;
    }

    .toast-custom-chat-icon .toast-message {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 100%;
        width: 300px !important;
    }

    .toast-custom-chat-icon {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: transparent !important;
        margin-right: 0 !important; /* Asegúrate de que no se mueva */
    }

    .toast-custom-chat-icon img {
        margin-left: 10px;
    }

    .toast-custom-chat-icon div {
        color: #fff;
        font-size: 14px;
        margin-left: 0px;
    }

    .right-icon {
        float: right !important;
    }

    .toast-fixed-bottom-right {
        position: fixed;
        /* bottom: 15px; */
        bottom: -10px;
        /* right: -15px; */
        right: -35px;
        z-index: 9999;
    }

    .toast-bottom-right {
       	bottom: 70px; 
    }

    .right-icon {
        float: right !important; 
    }

    #toast-container > .toast-custom-chat-icon {
        padding: 15px 15px 15px 0px !important;
    }
</style>

</html>