<div class="row">
	<div class="col-md-12">
    
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo ('Lista de horarios');?>
                </a>
            </li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo ('Añadir horario');?>
                </a>
            </li>
		</ul>
	
		<div class="tab-content">
            <div class="tab-pane active" id="list">
				<div class="panel-group joined" id="accordion-test-2">
                	<?php 
					$toggle = true;
					$sections = $this->db->get('section')->result_array();
					foreach($sections as $row):
						?>
                        
                
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                	<div class="panel-title">
                                        <h4>
                                            <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapse<?php echo $row['section_id'];?>">
                                                <i class="entypo-clipboard"></i> <?php echo $row['name'];?>
                                            </a>
                                            <!-- <a  class="text-right" href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
                                            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                            <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a> -->
                                        </h4>
                                    </div>
                                </div>
                
                                <div id="collapse<?php echo $row['section_id'];?>" class="panel-collapse collapse <?php if($toggle){echo 'in';$toggle=false;}?>">
                                    <div class="panel-body">
                                        <table cellpadding="0" cellspacing="0" border="0"  class="table table-hover table-striped table-bordered">
                                            <tbody>
                                                <?php 
                                                for($d=1;$d<=7;$d++):
                                                
                                                    if ($d == 1) {
                                                        $day = '1';
                                                        $day_spanish = 'DOMINGO';
                                                    } else if ($d == 2) {
                                                        $day = '2';
                                                        $day_spanish = 'LUNES';
                                                    } else if ($d == 3) {
                                                        $day = '3';
                                                        $day_spanish = 'MARTES';
                                                    } else if ($d == 4) {
                                                        $day = '4';
                                                        $day_spanish = 'MIÉRCOLES';
                                                    } else if ($d == 5) {
                                                        $day = '5';
                                                        $day_spanish = 'JUEVES';
                                                    } else if ($d == 6) {
                                                        $day = '6';
                                                        $day_spanish = 'VIERNES';
                                                    } else if ($d == 7) {
                                                        $day = '7';
                                                        $day_spanish = 'SÁBADO';
                                                    }
                                                ?>
                                                <tr class="gradeA">
                                                    <td width="100" class="text-center vertical-center" style="background-color: #ebebeb !important; color: black !important; font-weight: bold;"><?php echo strtoupper($day_spanish);?></td>
                                                    <td>
                                                    	<?php
                                                            $this->db->order_by("time_start", "asc");
                                                            $this->db->where('day_id' , $day);
                                                            $this->db->where('section_id' , $row['section_id']);
                                                            $routines	=	$this->db->get('section_routine')->result_array();
														foreach($routines as $row2):
														?>
														<div class="btn-group">
															<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                            	<?php echo $this->crudSubject->get_subject_name_by_id($row2['subject_id']);?>
																<?php echo '('.$row2['time_start'].':00 a '.$row2['time_end'].':00)';?>
                                                            	<span class="caret"></span>
                                                            </button>
															<ul class="dropdown-menu">
																<li>
                                                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_edit_section_routine/<?php echo $row2['section_routine_id'];?>');">
                                                                    <i class="entypo-pencil"></i>
                                                                        <?php echo ('Editar');?>
                                                                    			</a>
                                                         </li>
                                                         
                                                         <li>
                                                            <a href="#" onclick="confirm_sweet_modal('<?php echo base_url();?>index.php?admin/section_routine/delete/<?php echo $row2['section_routine_id'];?>');">
                                                                <i class="entypo-trash"></i>
                                                                    <?php echo ('Eliminar');?>
                                                                </a>
                                                    		</li>
															</ul>
														</div>
														<?php endforeach;?>

                                                    </td>
                                                </tr>
                                                <?php endfor;?>
                                                
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                            </div>
						<?php
					endforeach;
					?>
  				</div>
			</div>
            
            
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url() . 'index.php?admin/section_routine/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ('Curso');?></label>
                                <div class="col-sm-5">
                                    <select name="class_id" class="form-control" style="width:100%;"
                                        onchange="return get_class_section(this.value)">
                                        <option value=""><?php echo ('Seleccionar curso');?></option>
                                    	<?php 
										$classes = $this->db->get('class')->result_array();
										foreach($classes as $row):
										?>
                                    		<option value="<?php echo $row['class_id'];?>"><?php echo $row['name'];?></option>
                                        <?php
										endforeach;
										?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ('División');?></label>
                                <div class="col-sm-5">
                                    <select name="section_id" class="form-control" style="width:100%;" id="section_selection_holder"
                                        onchange="return get_section_subjects(this.value)">
                                        <option value=""><?php echo ('Primero seleccionar división');?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ('Materia');?></label>
                                <div class="col-sm-5">
                                    <select name="subject_id" class="form-control" style="width:100%;" id="subject_selection_holder">
                                        <option value=""><?php echo ('Primero seleccionar año');?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ('Dia');?></label>
                                <div class="col-sm-5">
                                    <select name="day_id" class="form-control" style="width:100%;">
                                        <option value="1">Domingo</option>
                                        <option value="2">Lunes</option>
                                        <option value="3">Martes</option>
                                        <option value="4">Miercoles</option>
                                        <option value="5">Jueves</option>
                                        <option value="6">Viernes</option>
                                        <option value="7">Sabado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ('Inicio');?></label>
                                <div class="col-sm-5">
                                    <select name="time_start" class="form-control" style="width:100%;">
                                        <?php for($i = 0; $i <= 11 ; $i++):?>
                                            <option value="<?php echo sprintf("%02d", $i);?>"><?php echo sprintf("%02d", $i);?></option>
                                        <?php endfor;?>
                                    </select>
                                    </select>
                                    <select name="starting_ampm" class="form-control" style="width:100%">
                                    	<option value="1">am</option>
                                    	<option value="2">pm</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ('Fin');?></label>
                                <div class="col-sm-5">
                                    <select name="time_end" class="form-control" style="width:100%;">
                                        <?php for($i = 0; $i <= 11 ; $i++):?>
                                            <option value="<?php echo sprintf("%02d", $i);?>"><?php echo sprintf("%02d", $i);?></option>
                                        <?php endfor;?>
                                        <!-- <?php for($i = 0; $i <= 11 ; $i++):?>
                                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php endfor;?> -->
                                    </select>
                                    <select name="ending_ampm" class="form-control" style="width:100%">
                                    	<option value="1">am</option>
                                    	<option value="2">pm</option>
                                    </select>
                                </div>
                            </div>
                        <div class="form-group text-center">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo ('Aceptar');?></button>
                              </div>
							</div>
                    </form>                
                </div>                
			</div>
			<!----CREATION FORM ENDS-->
            
		</div>
	</div>
</div>

<script type="text/javascript">
     function get_class_section(class_id) {
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id ,
            success: function(response)
            {
                jQuery('#section_selection_holder').html(response);
            }
        });
    }

    function get_section_subjects(section_id) {
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_section_subjects/' + section_id ,
            success: function(response)
            {
                jQuery('#subject_selection_holder').html(response);
            }
        });
    }

    $(document).ready(function(){
        $('select[name="starting_ampm"]').change(function(){
            var ampm = $(this).val();
            var select = $('select[name="time_start"]');
            select.empty();

            if(ampm == '1'){ // AM
                for(var i = 0; i <= 11; i++){
                    var display = i < 10 ? '0' + i : i; 
                    select.append($('<option>', { value: i, text: display }));
                }
            }else if(ampm == '2'){ // PM
                for(var i = 12; i <= 23; i++){
                    select.append($('<option>', { value: i, text: i }));
                }
            }
        });
    });

    $(document).ready(function(){
        $('select[name="starting_ampm"], select[name="ending_ampm"]').change(function(){
            var ampm = $(this).val();
            var select = $(this).parent().find('select[name^="time_"]');
            select.empty();

            if(ampm == '1'){ // AM
                for(var i = 0; i <= 11; i++){
                    var display = i < 10 ? '0' + i : i; 
                    select.append($('<option>', { value: i, text: display }));
                }
            }else if(ampm == '2'){ // PM
                for(var i = 12; i <= 23; i++){
                    select.append($('<option>', { value: i, text: i }));
                }
            }
        });
    });

</script>


<style>
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
        font-weight: bold !important;
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
        color: black !important;
    }  .table tbody tr:hover td {
        background-color: #f2f2f4 !important;
    }

    .panel-title {
        background-color: #265044 !important;
        color: white !important;
    } .panel-title a {
        color: white !important;
    }

    .panel-group.joined > .panel > .panel-heading h4 a:before {
        color: white !important;
    }

    .vertical-center {
        vertical-align: middle !important;
    }

    .nav-tabs li a:hover {
        background-color: #A5B299 !important;
    }  .nav-tabs li.active a:hover {
        background-color: #fff !important;
    }
</style>
