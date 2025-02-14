<?php 
$edit_data		=	$this->db->get_where('acd_session' , array('id' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-pencil"></i>
					<?php echo ('Editar ciclo escolar');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/acd_session/do_update/'.$row['id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo ('Nombre periodo');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="name" value="<?php echo $row['name'];?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo ('Fecha de inicio');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="datepicker form-control" name="strt_dt" value="<?php echo $row['strt_dt'];?>"/>
                        </div>
                    </div>
                    
                        <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo ('Fecha de finalización');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="datepicker form-control" name="end_dt" value="<?php echo $row['end_dt'];?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo ('Activo');?></label>
                        <div class="col-sm-5">
                            <select name="is_open" class="form-control">
							
                                        <option <?php echo ($row['is_open']=='0')?'selected':'';?> value="0">No</option>
										 <option <?php echo ($row['is_open']=='1')?'selected':'';?> value="1">Si</option>
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
    </div>
</div>

<?php
endforeach;
?>


<style>
    .panel-primary, .panel-heading {
        border-color: #891818 !important;
    }  .form-groups-bordered > .form-group {
        border-color: white !important;
    }

    .panel-title {
        color: #891818 !important;
        font-weight: bold !important;
    }

    .panel-body {
        color: #484848 !important;
    }
</style>