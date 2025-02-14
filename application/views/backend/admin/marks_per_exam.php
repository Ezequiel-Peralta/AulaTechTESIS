<div class="row">
	<div class="col-md-12">
    
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo ('Administrar exámenes');?>
                </a>
            </li>
		</ul>
	
        <div class="tab-pane <?php if(!isset($edit_data) && !isset($personal_profile) && !isset($academic_result) ) echo 'active';?>" id="list">
			<center>
                <?php echo form_open(base_url() . 'index.php?admin/marks_per_exam');?>
                <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-hover table-striped">
                	<tr>
                        <td><?php echo ('Curso');?></td>
                        <td><?php echo ('División');?></td>
                        <td><?php echo ('Materia');?></td>
                        <td><?php echo ('Examen');?></td>
                        <td>&nbsp;</td>
                	</tr>
                	<tr>
                        <td>
                            <select name="class_id" class="form-control" 
                                data-message-required="<?php echo ('Valor requerido');?>"
                                onchange="get_class_sections(this.value);">
								<option value="" selected><?php echo ('Seleccionar');?></option>
								<?php 
                                        $classes = $this->db->get('class')->result_array();
                                        foreach($classes as $row):
                                    ?>
                                       <option value="<?php echo $row['class_id'];?>">
                                             <?php echo $row['name'];?>
                                        </option>
                                    <?php endforeach; ?>
							</select>
                        </td>
                        <td>
                            <select name="section_id" class="form-control" id="section_selector_holder" onchange="get_section_subjects(this.value);"> 
							    <option value=""><?php echo ('Primero seleccionar el curso');?></option>
							</select>   
                        </td>
                        <td>
                            <select name="subject_id" class="form-control" id="section_selector_holder_subject" 
                                    onchange="get_subject_exams(this.value);">
                                <option value=""><?php echo ('Primero seleccionar la division');?></option>
                            </select>     
                        </td>
                        <td>
                            <select name="exam_id" class="form-control" id="section_selector_holder_exam" >
                                <option value=""><?php echo ('Primero seleccionar la materia');?></option> 
                            </select>  
                        </td>
                        <td>
                        	<input type="hidden" name="operation" value="selection" />
                    		<input type="submit" value="<?php echo ('Aceptar');?>" class="btn btn-info" />
                        </td>
                	</tr>
                </table>
                </form>
                </center>
                
                <br /><br />
                
                <?php if($exam_id >0 && $class_id >0 && $section_id >0 && $subject_id >0 ):?>
                <?php 
						$students = $this->crud_model->get_students_per_section($section_id);
						foreach($students as $row):
							$verify_data = array(	
                                'exam_id' => $exam_id,
								'class_id' => $class_id,
                                'section_id' => $section_id,
								'subject_id' => $subject_id, 
								'student_id' => $row['student_id']
                            );
							$query = $this->db->get_where('mark', $verify_data);
							
							if($query->num_rows() < 1)
								$this->db->insert('mark', $verify_data);
						 endforeach;
				?>

                <?php echo form_open(base_url() . 'index.php?admin/marks_per_exam'); ?>

                <center>
                    <h3><?php echo ($this->crud_model->get_class_name($class_id));?>° <?php echo ($this->crud_model->get_section_letter_name($section_id));?></h3>
                </center>
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <td class="text-center"><?php echo ('Estudiante');?></td>
                            <td class="text-center"><?php echo ('Tipo de examen');?></td>
                            <td class="text-center"><?php echo ('Calificación');?></td>
                            <!-- <td></td> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $students = $this->crud_model->get_students_per_section($section_id);
                        foreach($students as $row):
                            $verify_data = array(
                                'exam_id' => $exam_id,
                                'class_id' => $class_id,
                                'section_id' => $section_id,
                                'subject_id' => $subject_id,
                                'student_id' => $row['student_id']
                            );
                            $query = $this->db->get_where('mark', $verify_data);							 
                            $marks = $query->result_array();
                            foreach($marks as $row2):
                        ?>
                        <tr>
                            <td>
                                <?php echo $row['lastname'];?>, <?php echo $row['firstname'];?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    $exam_type_id = ''; // Inicializar variable de tipo de examen
                                    $exams = $this->crud_model->get_exam_info($exam_id);
                                    foreach($exams as $row3):
                                        $exam_type_id = $row3['exam_type_id'];
                                        $exam_type_info = $this->crud_model->get_exam_type_info($exam_type_id);
                                        if (!empty($exam_type_info)) {
                                            echo $exam_type_info[0]['short_name']; 
                                        }
                                    endforeach;
                                ?>
                            </td>
                            <td class="text-center">
                                <input type="number" value="<?php echo $row2['mark_obtained'];?>" name="marks[<?php echo $row2['mark_id']; ?>]" class="form-control text-center"  />
                            </td>
                            <td class="hiddenElement">
                                <input type="hidden" name="exam_id" value="<?php echo $exam_id;?>" />
                                <input type="hidden" name="exam_type_id" value="<?php echo $exam_type_id;?>" />
                                <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
                                <input type="hidden" name="section_id" value="<?php echo $section_id;?>" />
                                <input type="hidden" name="subject_id" value="<?php echo $subject_id;?>" />
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <input type="hidden" name="operation" value="update_all" />
                    <button type="submit" class="btn btn-info"><?php echo ('Aceptar'); ?></button>
                </div>
                
                <?php echo form_close(); ?>
            <?php endif;?>
        </div>
	</div>
</div>


<script type="text/javascript">
  
    // function get_class_sections(class_id) {
    // 	$.ajax({
    //         url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id ,
    //         success: function(response)
    //         {
    //             jQuery('#section_selector_holder').html(response);
    //         }
    //     });
    // }

     function consoleLogs() {
         console.log('curso: ' + class_id);
         console.log('division: ' + section_id);
         console.log('materia: ' + subject_id);
         console.log('examen: ' + exam_id);
     }

    function get_class_sections(class_id) {
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id,
            success: function(response) {
                var select = $('#section_selector_holder');
                select.empty();
                select.append($('<option>', { value: '', text: '<?php echo ("Seleccionar"); ?>', selected: 'selected' }));
                select.append(response);
            }
        });
    }

    function get_section_subjects(section_id) {
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_section_subjects/' + section_id,
            success: function(response) {
                // jQuery('#section_selector_holder_subject').html(response);
                var select = $('#section_selector_holder_subject');
                select.empty();
                select.append($('<option>', { value: '', text: '<?php echo ("Seleccionar"); ?>', selected: 'selected' }));
                select.append(response);
            }
        });
    }

    function get_subject_exams(subject_id) {
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_subject_exams/' + subject_id,
            success: function(response) {
                // jQuery('#section_selector_holder_subject').html(response);
                var select = $('#section_selector_holder_exam');
                select.empty();
                // select.append($('<option>', { value: '', text: '<?php echo ("Seleccionar"); ?>', selected: 'selected' }));
                select.append(response);
            }
        });
    }



</script> 


<style>
  
    .hiddenElement {
        display: none !important;
    }
    .form-control {
        background-color: #ebebeb !important;
    }
    
    a[data-toggle="tab"] i {
        color: black !important;
    }

    .active a[data-toggle="tab"] i {
        color: #265044 !important;
    }

    .menuIcon {
        color: black;
    }
    .btn-group {
        text-align: center !important;
        align-items: center !important;
    }

    .nav-tabs.bordered + .tab-content {
        border: 5px solid white !important;
        border-top: 0;
        -webkit-border-radius: 0 0 3px 3px;
        -webkit-background-clip: padding-box;
        -moz-border-radius: 0 0 3px 3px;
        -moz-background-clip: padding;
        border-radius: 0 0 3px 3px;
        background-clip: padding-box;
        padding: 10px 15px;
        margin-bottom: 20px;
    }

    .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
        border: 5px solid white !important;
        border-bottom-color: transparent !important;
    }

    .nav-tabs .active a {
        color: #265044 !important;
        font-weight: bolder !important;
    }

    .nav-tabs li a {
        color: black !important;
        font-weight: bold !important;
    }

    .dataTables_wrapper {
        color: #484848 !important;
    }

    .dataTable thead tr th {
        color: #265044 !important;
        font-weight: bold !important;
    }

    .padded label {
        color: #265044 !important;
        font-weight: bold !important;
    }
    .even {
        background-color: white !important;
    }

    .btn-info {
        font-weight: bold !important;
    }

    .btn-group ul li a {
        background-color: #265044 !important;
        color: white !important;
        border-radius: 0px !important;
        border-bottom: 2px solid rgba(69, 74, 84, 0.4);
    }

    /* Estilo para cambiar el color de fondo en hover */
    .btn-group ul li a:hover {
        background-color: #A5B299 !important;
        border-radius: 0px !important;
    }

    .box-content {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        background-color: white !important;
    }

    .row th {
        background-color: #265044 !important;
    } .row th div {
        color: white !important;
        font-weight: 600 !important;
    }

    .dataTables_wrapper table thead tr th.sorting_asc:before,
    .dataTables_wrapper table thead tr th.sorting_desc:before {
    color: white !important;
    }

    .table tbody tr td {
        background-color: #fff !important;
    }  .table tbody tr:hover td {
        background-color: #f2f2f4 !important;
    }

    .nav-tabs li a:hover {
        background-color: #A5B299 !important;
    }  .nav-tabs li.active a:hover {
        background-color: #fff !important;
    }

    .tile-stats .icon {
        margin-bottom: 10px !important;
    }
    .tile-stats .icon i {
        font-size: 110px !important;
     
        margin-right: 0px !important;
        padding: 0px 90px 0px 10px;
    }

    .tile-stats {
        padding: 40px 0px 40px 0px!important;
    }

    .tile-stats .num, .sub-num {
        background-color: #A5B299;
    }
    .num {
        padding-left: 20px !important;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        z-index: 0 !important;
    }
    .sub-num {
        margin-top: -1px !important;
        padding-left: 20px !important;
        padding-bottom: 10px !important;
        border-bottom-right-radius: 5px;
        border-bottom-left-radius: 5px;
        z-index: 0 !important;
    }
</style>
