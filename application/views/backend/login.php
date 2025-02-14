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
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="assets/css/neon-core.css">
	<link rel="stylesheet" href="assets/css/neon-theme.css">
	<link rel="stylesheet" href="assets/css/neon-forms.css">
	<link rel="stylesheet" href="assets/css/custom.css">

	<script src="assets/js/jquery-1.11.3.min.js"></script>

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

<body class="page-body login-page login-form-fall" data-url="http://neon.dev">

<script type="text/javascript">
var baseurl = '<?php echo base_url();?>';
</script>

<div class="login-container">
	<div class="login-header login-caret">
		<div class="login-content logo-container">
			<a href="index.html" class="logo">
				<!-- <img src="assets/images/logos/AulaTechLogo3.png" width="180" alt="" /> -->
                <!-- <img src="assets/images/logos/AulaTechLogo6.png" height="100" width="150" alt="" /> -->
                
                
                <!-- <img src="assets/images/logos/LogoIndex.png" height="80" width="120" alt="" /> -->
                <img src="assets/images/logos/AulaTechLogo.png" height="120" width="120" alt="" style="margin-bottom: 15px; margin-right: 5px;" />
            </a>
			<!-- <p class="description"><span class="marked">Eficiencia</span> en la administración, <span class="marked">innovación</span> en la enseñanza.</p>  -->
            <p class="description">
            	<h3 class="description-txt" style="text-transform: uppercase; color: #A5B299; font-weight: bolder; font-size:16px;">
                    <?php echo $system_name;?>
                </h3>
            </p> 
            <!-- progress bar indicator -->
            <div class="login-progressbar-indicator">
                <h3  style="font-weight: bolder; ">43%</h3>
                <span style="font-size: 20px; font-weight: bolder; ">Iniciando sesión...</span>
            </div>
        </div>

	</div>

    <div class="login-progressbar">
		<div></div>
	</div>

	<div class="login-form">
        <!-- <div class="image-content">
            <img src="assets/images/sidebar-image.jpg" height="100" width="150" alt="" />
        </div> -->
		<div class="login-content login-wrap">
			<div class="form-login-error">
				<h3>Ingreso inválido</h3>
				<p>El <strong>Email</strong> y/o <strong>Contraseña</strong> no son correctos.</p>
			</div>
           <h3 class="form-title">Iniciar sesión</h3>
			<form method="post" role="form" id="form_login">
				<div class="form-group">
					<div class="input-group input-form">
						<div class="input-group-addon">
							<i class="entypo-user"></i>
						</div>
						<input type="text" class="form-control" name="email" id="email" placeholder="Email" autocomplete="off" data-mask="email" />
					</div>
				</div>
				<div class="form-group">
					<div class="input-group input-form">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						<input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" autocomplete="off" />
					</div>
				</div>
                <div class="form-group">
			    </div>
              
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block btnLogin">
						<i class="entypo-login"></i>
					</button>
				</div>
               
			</form>
        
		</div>
	</div>
</div>



    <style>
        *{
            font-family: 'Open Sans', 'Source Sans 3', sans-serif !important;
            /* background-color: white !important;
            background: white !important; */
        }
        
        body {
            /* background: linear-gradient(to right, #e2e2e2, #c9d6ff) !important;   */
            /* background: linear-gradient(to right, #fff, #265044) !important; */
           /* background: linear-gradient(to right, #5f5f5f, #fff) !important;  */
            /* background: linear-gradient(to right, #fff, #fff) !important; */
            /* background-color: #265044 !important;  */
            background-color: white !important; 
        }

        .input-form {
            background-color: #eee !important;
            border: none !important;
            /* margin: 8px 0;
            padding: 10px 15px; */
            /* font-size: 13px; */
            border-radius: 8px !important;
            /* width: 350px; */
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
            padding: 0 auto !important;
            /* margin-top: -70px !important; */
            margin-top: -90px !important;
        }

        .entypo-user, .entypo-key {
            color: #265044 !important;
        }
      
        .btnLogin {
            color: #fff !important;
            font-size: 12px !important;
            padding: 10px 45px !important;
            border: 1px solid transparent !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            margin-top: 10px !important;
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



    @keyframes moveBackground {
        0% {
            background-position: left;
        }

        100% {
            background-position: right;
        }
    }

        .login-container {
        }

        .description h3 {
            font-family: 'Anton', sans-serif;
        } 
     
        .description {
            margin-top: -20px !important;
        }    .login-page.logging-in .login-container .login-header .login-content .description {
            margin-top: 50px !important;
        }

        .login-header {
            /* background-color: black !important;  */
            background-color: #265044 !important; 
            /* background-color: white !important;  */
            height: 150px !important;
        }

        .login-page .login-header.login-caret:after {
            /* border-color: white transparent transparent transparent;  */
            border-color: #265044 transparent transparent transparent; 
            /* border-color: #04ACDC transparent transparent transparent; */
        }

        @media screen and (max-width: 991px) {
            .logo-container {
                /* margin-top: -20px !important;  */
                margin-top: -40px !important; 
            }
            
            .login-page.logging-in .login-container .login-header .login-content .description {
                margin-top: -20px !important;
            } .login-page.logging-in .login-container .login-header  .logo-container {
                margin-top: -55px !important;
            }
           
        }

        .form-title {
            padding-bottom: 20px !important;
            color: black !important;
            font-size: 28px !important;
            font-weight: bolder !important;
            /* margin-left: 25px !important; */
        }  

        .login-form {
            /* background-color: blue !important; */
        }

        .login-wrap {
            /* background-color: white !important; 
            width: 500px !important;
            padding: 0 90px !important; */
        }

        .link {
            color: #949494 !important;
            font-style: italic !important;
        }  .link:hover {
            color: #265044 !important;
            font-weight: bold !important;
        }

        .hiddenElement {
            display: none !important;
        }

        .login-page.logging-in .login-form .form-title {
            display: none !important;
        }


        .login-progressbar-indicator h3, .login-progressbar-indicator span  {
            color: black !important;
        }

        .login-page.logging-in .login-header {
            padding-top: 270px !important;
            padding-bottom: 90px !important;
        }

        .login-page.logging-in .login-progressbar {
            background: #265044 !important;
            height: 2px;
        }

        .login-page .login-progressbar-indicator {
            margin-top: 10px !important;
        }

        .login-progressbar-indicator h3, .login-progressbar-indicator span {
            color: #265044 !important;
        }

        body.logging-in .logo img {
            height: 120px !important;
            /* width: 180px !important; */
            width: 130px !important;
            margin-right: 5px;
            margin-top: 10px !important;
        }

        .login-page.logging-in .login-header .description-txt {
            display: none !important; 
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

	<!-- JavaScripts initializations and stuff -->
	<script src="assets/js/neon-custom.js"></script>

	<!-- Demo Settings -->
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