<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
<link rel="stylesheet" href="assets/css/bootstrap.css">
<link rel="stylesheet" href="assets/css/neon-core.css">
<link rel="stylesheet" href="assets/css/neon-theme.css">
<link rel="stylesheet" href="assets/css/neon-forms.css">
<link rel="stylesheet" href="assets/css/custom.css">
<link rel="stylesheet" href="assets/css/font-icons/font-awesome/css/font-awesome.min.css">

<!--sweet alert-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="assets/js/jquery-1.11.0.min.js"></script>

<?php
if($page_name === 'dashboard'):?>



<?php endif;?>       

<?php
if($page_name === 'view_student_mark'):?>



<?php endif;?>    


<?php
if($page_name === 'language_settings'):?>
	<!-- <link rel="stylesheet" href="assets/js/datatables/datatables.css">
	<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="assets/js/select2/select2.css"> -->
    
    <!-- <link rel="stylesheet" href="assets/js/datatables/responsive/css/datatables.responsive.css"> -->
<?php endif;?> 
    

<!-- <link rel="stylesheet" href="assets/js/rickshaw/rickshaw.min.css"> 

<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet"> -->

    <!-- <link rel="stylesheet" href="assets/css/font-icons/font-awesome/css/font-awesome.min.css"> -->

<?php
	$login_user_id = $this->session->userdata('login_user_id');
	$login_type = $this->session->userdata('login_type');

    // $theme_mode  =  $this->db->get_where($login_type, array($login_type . '_id' => $login_user_id))->row('theme_preference');
    $theme_mode        =  $this->session->userdata('theme_preference');
	
    if ($theme_mode != ''):?>

        <link rel="stylesheet" href="assets/css/skins/<?php echo $theme_mode;?>.css">
        <link rel="stylesheet" href="assets/css/<?php echo $theme_mode;?>/navigation.css">
        <!-- <link rel="stylesheet" href="assets/css/<?php echo $theme_mode;?>/chat.css"> -->
        <!-- <link rel="stylesheet" href="assets/css/<?php echo $theme_mode;?>/footer.css"> -->
        <link rel="stylesheet" href="assets/css/<?php echo $theme_mode;?>/header.css">
        <!-- <link rel="stylesheet" href="assets/css/<?php echo $theme_mode;?>/modal.css"> -->
    <?php endif;?>

<?php
    $login_type = $this->session->userdata('login_type');
    if($page_name != ''):?>
        <link rel="stylesheet" href="assets/css/<?php echo $theme_mode;?>/<?php echo $login_type;?>/<?php echo $page_name;?>.css">
<?php endif;?>           

<?php if ($text_align == 'right-to-left') : ?>
    <link rel="stylesheet" href="assets/css/neon-rtl.css">
<?php endif; ?>

        <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<link rel="shortcut icon" href="assets/images/icon.png">

<!-- <link rel="stylesheet" href="assets/js/vertical-timeline/css/component.css"> -->
<!-- <link rel="stylesheet" href="assets/js/datatables/responsive/css/datatables.responsive.css"> -->

<!-- <script src="assets/js/neon-notes.js" type="text/javascript"></script> -->


<?php
if($page_name === 'summary_attendance_student' ||  $page_name === 'statistics' || $page_name === 'details_attendance_student'):?>
    <!--para graficos-->
    <script src="assets/js/rickshaw/rickshaw.min.js"></script>
    <script src="assets/js/raphael-min.js"></script>
    <script src="assets/js/morris.min.js"></script>
    <script src="assets/js/jquery.sparkline.min.js"></script>
    <script src="assets/js/jquery.peity.min.js"></script> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<?php endif;?>      



<?php
if($page_name === 'manage_attendance_student'):?>
	<script src="assets/js/bootstrap-datepicker.js"></script>
	<script src="assets/js/bootstrap-timepicker.min.js"></script>
	<script src="assets/js/bootstrap-colorpicker.min.js"></script>
	<script src="assets/js/moment.min.js"></script>
	<script src="assets/js/daterangepicker/daterangepicker.js"></script>
<?php endif;?>  

<?php
if($page_name === 'calendario'):?>
    <!--para calendario -->
    <script src="assets/js/select2/select2.min.js"></script>
	<script src="assets/js/bootstrap-tagsinput.min.js"></script>
	<script src="assets/js/typeahead.min.js"></script>
	<script src="assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
	<script src="assets/js/bootstrap-datepicker.js"></script>
	<script src="assets/js/bootstrap-timepicker.min.js"></script>
	<script src="assets/js/bootstrap-colorpicker.min.js"></script>
	<script src="assets/js/moment.min.js"></script>
	<script src="assets/js/daterangepicker/daterangepicker.js"></script>
	<script src="assets/js/jquery.multi-select.js"></script>
	<script src="assets/js/icheck/icheck.min.js"></script>
    
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet"/> 

<?php endif;?>      


<!-- <script src="assets/js/neon-charts.js"></script> BUGEA EL CODE -->

<?php
if($page_name === 'register'):?>
    <!-- register -->
    <script src="assets/js/jquery.bootstrap.wizard.min.js"></script>
	<script src="assets/js/jquery.validate.min.js"></script>
	<script src="assets/js/jquery.inputmask.bundle.js"></script>
	<script src="assets/js/bootstrap-switch.min.js"></script> 
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script> 
    
<?php endif;?>   

   
<script>
    function checkDelete()
    {
        var chk=confirm("Are You Sure To Delete This !");
        if(chk)
        {
          return true;  
        }
        else{
            return false;
        }
    }
</script>

<style>
    html {
  background:  #ebebeb !important;
}
</style>