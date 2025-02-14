<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $system_name	=	"Sistema de información educativo";
        $system_title	=	"AulaTech IPDF";
	?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Neon Admin Panel" />
	<meta name="author" content="" />
	
    <title><?php echo ('Login');?> | <?php echo $system_title;?></title>
	

    <link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="assets/css/neon-core.css">
	<link rel="stylesheet" href="assets/css/neon-theme.css">
	<link rel="stylesheet" href="assets/css/neon-forms.css">
	<link rel="stylesheet" href="assets/css/custom.css">

	<script src="assets/js/jquery-1.11.0.min.js"></script>

	<!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

	<link rel="shortcut icon" href="assets/images/favicon.png">
	
</head>

<script type="text/javascript">
var baseurl = '<?php echo base_url();?>';
</script>

<body class="page-body login-page is-lockscreen login-form-fall" data-url="http://neon.dev">

<div class="login-container">
	
	<div class="login-header logo-container">
		
		<div class="login-content">
			
            <a href="#" class="logo">
                <!-- <img src="assets/images/logos/LogoIndex.png" height="80" width="120" alt="" /> -->
                <!-- <img src="assets/images/logos/AulaTechLogo.png" height="150" width="150" alt="" /> -->
                 <img src="assets/images/logos/AulaTechLogo.png" height="120" width="120" alt="" />
            </a>
            <p class="description">
            	<h3 class="description-txt" style="text-transform: uppercase; color: #A5B299; font-weight: bolder; font-size:16px;">
                    <?php echo $system_name;?>
                </h3>
            </p> 
			
			<!-- progress bar indicator -->
			<div class="login-progressbar-indicator">
				<h3>0%</h3>
				<span>logging in...</span>
			</div>
		</div>
		
	</div>
	
	<div class="login-form">
		
		<div class="login-content">
			
        <!-- <form method="post" role="form" id="form_lockscreen" action="<?php echo base_url('index.php/login_in/ajax_login/'); ?>"> -->
        <?php echo form_open(base_url() . 'index.php?login_in/ajax_login/', array('enctype' => 'multipart/form-data')); ?> <!--'id' => 'form_lockscreen',  -->

				<div class="form-group lockscreen-input">
					
					<div class="lockscreen-thumb">
						<img src="<?php echo $this->session->userdata('photo');?>" width="140" class="img-circle" />
						
						<div class="lockscreen-progress-indicator">0%</div>
					</div>
					
					<div class="lockscreen-details">
						<h4><?php echo $this->session->userdata('name');?></h4>
						<span data-login-text="logging in...">Sesión cerrada</span>
					</div>
					
				</div>
				
				<div class="form-group">
					
					<div class="input-group  input-form">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						<input type="text" class="form-control" name="email" id="email" placeholder="Email" autocomplete="off" data-mask="email" style="display: none;" value="<?php echo $this->session->userdata('email');?>" />
                        <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" autocomplete="off" />
                    </div>
				
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block btnLogin">
						<i class="entypo-login"></i>
					</button>
				</div>
				
			<!-- </form>  -->
            <?php echo form_close();?>
			
			
			<div class="login-bottom-links">
				
				<a href="<?php echo base_url(); ?>" class="link">Iniciar sesión usando una cuenta diferente <i class="entypo-right-open"></i></a>
				
				<br />
				
			</div>
			
		</div>
		
	</div>
	
</div>


<style>
        *{
            font-family: 'Open Sans', 'Source Sans 3', sans-serif !important;
        }
        
        body {
            background-color: white !important; 
        }

        .input-form {
            background-color: #eee !important;
            border: none !important;
            border-radius: 8px !important;
            outline: none !important;
        } .input-form input::placeholder {
            color: #A5B299 !important;
            font-weight: bold;
        } .input-form input {
            color: black !important; 
        } 
        .input-form:focus-within, 
        .input-form input:focus {
            box-shadow: 0 0 0 1pt #265044 !important;
            transition: all 200ms ease-in !important;
        }

        .input-form input:focus {
            box-shadow: none !important;
        }

        .logo-container {
            /* margin-top: 40px !important; */
            /* padding: 60px 0 !important; */
            padding: 70px 0 !important;
        } 

        .entypo-user, .entypo-key {
            color: #265044 !important;
        }
      
        .btnLogin {
            color: #fff !important;
            /* font-size: 12px !important; */
            border: 1px solid transparent !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            cursor: pointer !important;
            background-color: #265044 !important;
            border-color: #fff !important;
            position: relative !important;
        }

        .btnLogin:hover {
            background: #fff !important;
            color: #265044 !important;
            border: 1px solid  #265044 !important;
        }

        .description h3 {
            font-family: 'Anton', sans-serif;
        }

        .login-header {
            background-color: #265044 !important; 
            /* height: 150px !important; */
        }

        .login-page .login-header.login-caret:after {
            border-color: #265044 transparent transparent transparent; 
        }

        .form-title {
            color: black !important;
            /* font-size: 28px !important; */
            font-weight: bolder !important;
        }  
        .link {
            color: #949494 !important;
        }  .link:hover {
            color: #265044 !important;
        }

        .login-progressbar-indicator h3, .login-progressbar-indicator span  {
            color: black !important;
        }

        .login-page.logging-in .login-progressbar {
            background: #265044 !important;
            height: 2px;
        }

        .login-progressbar-indicator h3, .login-progressbar-indicator span {
            color: #265044 !important;
        }

        .lockscreen-details h4 {
            color: #000 !important;
            font-weight: 600 !important;
        }

        @media screen and (max-width: 991px) {
            .logo-container {
                padding: 10px 0 !important;
            } 
        }

    </style>


    <!-- Bottom scripts (common) -->
    <script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/jquery.validate.min.js"></script>
	<script src="assets/js/neon-login.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>


<script>
    function copy( email , password)
    {
        document.getElementById("email").value  =   email;
        document.getElementById("password").value  =   password;
    }

   
</script>

</body>
</html>