<?php
$this->db->from('schedule');
$this->db->where('section_id', $section_id);
$this->db->where('status_id', 1);
$query = $this->db->get();
$all_schedules_count = $query->num_rows();

// Contador para el lunes (day_id = 2)
$this->db->from('schedule');
$this->db->where('section_id', $section_id);
$this->db->where('status_id', 1);
$this->db->where('day_id', 2);
$monday_schedules_count = $this->db->count_all_results();

// Contador para el martes (day_id = 3)
$this->db->from('schedule');
$this->db->where('section_id', $section_id);
$this->db->where('status_id', 1);
$this->db->where('day_id', 3);
$tuesday_schedules_count = $this->db->count_all_results();

// Contador para el miércoles (day_id = 4)
$this->db->from('schedule');
$this->db->where('section_id', $section_id);
$this->db->where('status_id', 1);
$this->db->where('day_id', 4);
$wednesday_schedules_count = $this->db->count_all_results();

// Contador para el jueves (day_id = 5)
$this->db->from('schedule');
$this->db->where('section_id', $section_id);
$this->db->where('status_id', 1);
$this->db->where('day_id', 5);
$thursday_schedules_count = $this->db->count_all_results();

// Contador para el viernes (day_id = 6)
$this->db->from('schedule');
$this->db->where('section_id', $section_id);
$this->db->where('status_id', 1);
$this->db->where('day_id', 6);
$friday_schedules_count = $this->db->count_all_results();
?>

<div class="row selectContent">
    <div class="col-md-6">
        <div class="form-group">
            <label for="class_select" class="labelSelect"><?php echo ucfirst(get_phrase('you_are_viewing')); ?>
            </label> 
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <select id="class_select" class="form-control selectElement" onchange="location = this.value;">
                <?php
                $active_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();

                if ($active_academic_period) {
                    $this->db->where('academic_period_id', $active_academic_period->id);
                    $sections = $this->db->get('section')->result_array();
                
                foreach ($sections as $row):
                ?>
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/schedules_information/<?php echo $row['section_id']; ?>"
                        <?php if ($section_id == $row['section_id']) echo 'selected="selected"'; ?>>
                        <?php echo $row['name']; ?>
                    </option>
                    <?php 
                    endforeach;
                } else {
                    // echo '<li><span>No hay periodos activos disponibles</span></li>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                <?php echo ucfirst(get_phrase('all')); ?></span>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_schedules_count; ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="#day_2" data-toggle="tab">
                    <?php echo ucfirst(get_phrase('mon')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $monday_schedules_count; ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="#day_3" data-toggle="tab">
                    <?php echo ucfirst(get_phrase('tue')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $tuesday_schedules_count; ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="#day_4" data-toggle="tab">
                    <?php echo ucfirst(get_phrase('wed')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $wednesday_schedules_count; ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="#day_5" data-toggle="tab">
                    <?php echo ucfirst(get_phrase('thu')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $thursday_schedules_count; ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="#day_6" data-toggle="tab">
                    <?php echo ucfirst(get_phrase('fri')); ?>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $friday_schedules_count; ?>
                    </span>
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_schedules" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="all_exams_table">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo ucfirst(get_phrase('subject')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('day')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('schedule')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                       $schedules = $this->db->select('schedule.*, subject.name as subject_name')
                                            ->from('schedule')
                                            ->join('subject', 'subject.subject_id = schedule.subject_id', 'left') 
                                            ->where('schedule.section_id', $section_id)
                                            ->where_not_in('schedule.day_id', [1, 7]) 
                                            ->get()
                                            ->result_array();

                        foreach ($schedules as $row):
                            if ($row['status_id'] == 1) {
                                $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                            } else {
                                $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                            }
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php if($row['subject_id'] != ''):?>
                                    <?php echo ucfirst($row['subject_name']); ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php echo ucfirst(get_phrase( $this->db->get_where('days_of_week' , array('day_id' => $row['day_id']))->row()->name)); ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $time_start = $row['time_start']; 
                                $time_end = $row['time_end'];
                                
                                list($start_hour, $start_minute) = explode(':', $time_start);
                                list($end_hour, $end_minute) = explode(':', $time_end);

                                $start_period = ($start_hour >= 12 && $start_hour < 24) ? 'PM' : 'AM';
                                $end_period = ($end_hour >= 12 && $end_hour < 24) ? 'PM' : 'AM';

                                $start_hour_12 = $start_hour % 12; 
                                $end_hour_12 = $end_hour % 12;

                                if ($start_hour_12 == 0) $start_hour_12 = 12;
                                if ($end_hour_12 == 0) $end_hour_12 = 12;

                                $time_start_formatted = sprintf('%02d:%s %s', $start_hour_12, $start_minute, $start_period);
                                $time_end_formatted = sprintf('%02d:%s %s', $end_hour_12, $end_minute, $end_period);

                                echo $time_start_formatted . ' - ' . $time_end_formatted;
                                ?>
                            </td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_schedules/<?php echo $row['section_id'];?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_schedules/<?php echo $row['schedule_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/disable_schedule/<?php echo $row['schedule_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/enable_schedule/<?php echo $row['schedule_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                    
            </div>
            <div class="tab-pane" id="day_2">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_schedules" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="dayDataTable_2">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo ucfirst(get_phrase('subject')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('schedule')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $schedules = $this->db->order_by('day_id', 'ASC')
                                                ->order_by('time_start', 'ASC')
                                                ->where('section_id', $section_id)
                                                ->where('day_id', 2)
                                                ->get('schedule')
                                                ->result_array();

                            foreach($schedules as $row):
                                if ($row['status_id'] == 1) {
                                    $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                } else {
                                    $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                }
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php if($row['subject_id'] != ''):?>
                                    <?php echo ucfirst($this->db->get_where('subject' , array('subject_id' => $row['subject_id']))->row()->name);?>
                                <?php endif;?>
                            </td>
                            <td class="text-center">
                                <?php
                                $time_start = $row['time_start']; 
                                $time_end = $row['time_end'];
                                
                                list($start_hour, $start_minute) = explode(':', $time_start);
                                list($end_hour, $end_minute) = explode(':', $time_end);

                                $start_period = ($start_hour >= 12 && $start_hour < 24) ? 'PM' : 'AM';
                                $end_period = ($end_hour >= 12 && $end_hour < 24) ? 'PM' : 'AM';

                                $start_hour_12 = $start_hour % 12; 
                                $end_hour_12 = $end_hour % 12;

                                if ($start_hour_12 == 0) $start_hour_12 = 12;
                                if ($end_hour_12 == 0) $end_hour_12 = 12;

                                $time_start_formatted = sprintf('%02d:%s %s', $start_hour_12, $start_minute, $start_period);
                                $time_end_formatted = sprintf('%02d:%s %s', $end_hour_12, $end_minute, $end_period);

                                echo $time_start_formatted . ' - ' . $time_end_formatted;
                                ?>
                            </td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_schedules/<?php echo $row['schedule_id'];?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_schedules/<?php echo $row['schedule_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/disable_schedule/<?php echo $row['schedule_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/enable_schedule/<?php echo $row['schedule_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                           
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>


            <div class="tab-pane" id="day_3">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_schedules" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="dayDataTable_3">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo ucfirst(get_phrase('subject')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('schedule')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $schedules = $this->db->order_by('day_id', 'ASC')
                                                ->order_by('time_start', 'ASC')
                                                ->where('section_id', $section_id)
                                                ->where('day_id', 3)
                                                ->get('schedule')
                                                ->result_array();

                            foreach($schedules as $row):
                                if ($row['status_id'] == 1) {
                                    $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                } else {
                                    $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                }
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php if($row['subject_id'] != ''):?>
                                    <?php echo ucfirst($this->db->get_where('subject' , array('subject_id' => $row['subject_id']))->row()->name);?>
                                <?php endif;?>
                            </td>
                            <td class="text-center">
                                <?php
                                $time_start = $row['time_start']; 
                                $time_end = $row['time_end'];
                                
                                list($start_hour, $start_minute) = explode(':', $time_start);
                                list($end_hour, $end_minute) = explode(':', $time_end);

                                $start_period = ($start_hour >= 12 && $start_hour < 24) ? 'PM' : 'AM';
                                $end_period = ($end_hour >= 12 && $end_hour < 24) ? 'PM' : 'AM';

                                $start_hour_12 = $start_hour % 12; 
                                $end_hour_12 = $end_hour % 12;

                                if ($start_hour_12 == 0) $start_hour_12 = 12;
                                if ($end_hour_12 == 0) $end_hour_12 = 12;

                                $time_start_formatted = sprintf('%02d:%s %s', $start_hour_12, $start_minute, $start_period);
                                $time_end_formatted = sprintf('%02d:%s %s', $end_hour_12, $end_minute, $end_period);

                                echo $time_start_formatted . ' - ' . $time_end_formatted;
                                ?>
                            </td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_schedules/<?php echo $row['schedule_id'];?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_schedules/<?php echo $row['schedule_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/disable_schedule/<?php echo $row['schedule_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/enable_schedule/<?php echo $row['schedule_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                           
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>

            <div class="tab-pane" id="day_4">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_schedules" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="dayDataTable_4">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo ucfirst(get_phrase('subject')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('schedule')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $schedules = $this->db->order_by('day_id', 'ASC')
                                                ->order_by('time_start', 'ASC')
                                                ->where('section_id', $section_id)
                                                ->where('day_id', 4)
                                                ->get('schedule')
                                                ->result_array();

                            foreach($schedules as $row):
                                if ($row['status_id'] == 1) {
                                    $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                } else {
                                    $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                }
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php if($row['subject_id'] != ''):?>
                                    <?php echo ucfirst($this->db->get_where('subject' , array('subject_id' => $row['subject_id']))->row()->name);?>
                                <?php endif;?>
                            </td>
                            <td class="text-center">
                                <?php
                                $time_start = $row['time_start']; 
                                $time_end = $row['time_end'];
                                
                                list($start_hour, $start_minute) = explode(':', $time_start);
                                list($end_hour, $end_minute) = explode(':', $time_end);

                                $start_period = ($start_hour >= 12 && $start_hour < 24) ? 'PM' : 'AM';
                                $end_period = ($end_hour >= 12 && $end_hour < 24) ? 'PM' : 'AM';

                                $start_hour_12 = $start_hour % 12; 
                                $end_hour_12 = $end_hour % 12;

                                if ($start_hour_12 == 0) $start_hour_12 = 12;
                                if ($end_hour_12 == 0) $end_hour_12 = 12;

                                $time_start_formatted = sprintf('%02d:%s %s', $start_hour_12, $start_minute, $start_period);
                                $time_end_formatted = sprintf('%02d:%s %s', $end_hour_12, $end_minute, $end_period);

                                echo $time_start_formatted . ' - ' . $time_end_formatted;
                                ?>
                            </td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_schedules/<?php echo $row['schedule_id'];?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_schedules/<?php echo $row['schedule_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/disable_schedule/<?php echo $row['schedule_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/enable_schedule/<?php echo $row['schedule_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                           
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>

            <div class="tab-pane" id="day_5">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_schedules" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="dayDataTable_5">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo ucfirst(get_phrase('subject')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('schedule')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $schedules = $this->db->order_by('day_id', 'ASC')
                                                ->order_by('time_start', 'ASC')
                                                ->where('section_id', $section_id)
                                                ->where('day_id', 5)
                                                ->get('schedule')
                                                ->result_array();

                            foreach($schedules as $row):
                                if ($row['status_id'] == 1) {
                                    $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                } else {
                                    $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                }
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php if($row['subject_id'] != ''):?>
                                    <?php echo ucfirst($this->db->get_where('subject' , array('subject_id' => $row['subject_id']))->row()->name);?>
                                <?php endif;?>
                            </td>
                            <td class="text-center">
                                <?php
                                $time_start = $row['time_start']; 
                                $time_end = $row['time_end'];
                                
                                list($start_hour, $start_minute) = explode(':', $time_start);
                                list($end_hour, $end_minute) = explode(':', $time_end);

                                $start_period = ($start_hour >= 12 && $start_hour < 24) ? 'PM' : 'AM';
                                $end_period = ($end_hour >= 12 && $end_hour < 24) ? 'PM' : 'AM';

                                $start_hour_12 = $start_hour % 12; 
                                $end_hour_12 = $end_hour % 12;

                                if ($start_hour_12 == 0) $start_hour_12 = 12;
                                if ($end_hour_12 == 0) $end_hour_12 = 12;

                                $time_start_formatted = sprintf('%02d:%s %s', $start_hour_12, $start_minute, $start_period);
                                $time_end_formatted = sprintf('%02d:%s %s', $end_hour_12, $end_minute, $end_period);

                                echo $time_start_formatted . ' - ' . $time_end_formatted;
                                ?>
                            </td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_schedules/<?php echo $row['schedule_id'];?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_schedules/<?php echo $row['schedule_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/disable_schedule/<?php echo $row['schedule_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/enable_schedule/<?php echo $row['schedule_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                           
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>

            <div class="tab-pane" id="day_6">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_schedules" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="dayDataTable_6">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo ucfirst(get_phrase('subject')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('schedule')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $schedules = $this->db->order_by('day_id', 'ASC')
                                                ->order_by('time_start', 'ASC')
                                                ->where('section_id', $section_id)
                                                ->where('day_id', 6)
                                                ->get('schedule')
                                                ->result_array();

                            foreach($schedules as $row):
                                if ($row['status_id'] == 1) {
                                    $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                } else {
                                    $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                }
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php if($row['subject_id'] != ''):?>
                                    <?php echo ucfirst($this->db->get_where('subject' , array('subject_id' => $row['subject_id']))->row()->name);?>
                                <?php endif;?>
                            </td>
                            <td class="text-center">
                                <?php
                                $time_start = $row['time_start']; 
                                $time_end = $row['time_end'];
                                
                                list($start_hour, $start_minute) = explode(':', $time_start);
                                list($end_hour, $end_minute) = explode(':', $time_end);

                                $start_period = ($start_hour >= 12 && $start_hour < 24) ? 'PM' : 'AM';
                                $end_period = ($end_hour >= 12 && $end_hour < 24) ? 'PM' : 'AM';

                                $start_hour_12 = $start_hour % 12; 
                                $end_hour_12 = $end_hour % 12;

                                if ($start_hour_12 == 0) $start_hour_12 = 12;
                                if ($end_hour_12 == 0) $end_hour_12 = 12;

                                $time_start_formatted = sprintf('%02d:%s %s', $start_hour_12, $start_minute, $start_period);
                                $time_end_formatted = sprintf('%02d:%s %s', $end_hour_12, $end_minute, $end_period);

                                echo $time_start_formatted . ' - ' . $time_end_formatted;
                                ?>
                            </td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_schedules/<?php echo $row['schedule_id'];?>" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_schedules/<?php echo $row['schedule_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/disable_schedule/<?php echo $row['schedule_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/schedules/enable_schedule/<?php echo $row['schedule_id'];?>/<?php echo $row['section_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                           
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.icheck-2').iCheck({
            checkboxClass: 'icheckbox_square-green'
        });
    });
</script>
		
<script type="text/javascript">
	jQuery(document).ready(function($) {
        var languagePreference = '<?php echo $this->session->userdata('language_preference'); ?>';

        jQuery.fn.dataTable.ext.type.order['day-sort-pre'] = function(a) {
    var daysOrder = {
        "monday": 0,
        "tuesday": 1,
        "wednesday": 2,
        "thursday": 3,
        "friday": 4,
        "saturday": 5,
        "sunday": 6
    };

    var day = a.toLowerCase(); // Convierte el día a minúsculas para asegurar coincidencia

    // Devuelve el valor del día en el orden correspondiente
    return daysOrder[day] !== undefined ? daysOrder[day] : -1;
};


var $allExamsDataTable = jQuery("#all_exams_table");

var rowCount = $('#all_exams_table tbody tr').length;
var scrollYValue = rowCount >= 11 ? "500px" : ""; 

        var $allExamsDataTable = jQuery("#all_exams_table");

        if (languagePreference === 'english') {
            var allExamsDataTable = $allExamsDataTable.DataTable({
                "order": [[1, "asc"]], 
                "columnDefs": [
                {
                    "targets": 1,
                    "type": "day-sort"
                }
            ],
                "language": {
                    "search": "",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "Showing 0 to 0 of 0 entries",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    },
                    "aria": {
                        "sortAscending": ": Activate to sort column ascending",
                        "sortDescending": ": Activate to sort column descending"
                    }
                },
                 //  "scrollX": true, 
                 "scrollX": $(window).width() <= 767,
                // "scrollY": $(window).width() >= 767 ? "500px" : "",
                "scrollY": scrollYValue, 
                "scrollCollapse": $(window).width() <= 767 ? true : "",
                "fixedHeader": $(window).width() <= 767 ? true : "",
                "autoWidth": false,
                "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-file-text-o"></i> Copy',
                        className: 'btn btn-white btn-sm btn-info-hover'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        className: 'btn btn-white btn-sm btn-green-hover'
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> CSV',
                        className: 'btn btn-white btn-sm btn-green-hover'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn btn-white btn-sm btn-danger-hover'
                    }
                ],
                colReorder: true, 
                initComplete: function() {
                    $('#all_exams_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var allExamsDataTable = $allExamsDataTable.DataTable({
                "order": [[1, "asc"]], 
                "columnDefs": [
                {
                    "targets": 1,
                    "type": "day-sort"
                }
            ],
                "language": {
                    "search": "", 
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": Activar para ordenar la columna ascendente",
                        "sortDescending": ": Activar para ordenar la columna descendente"
                    }
                },
                //  "scrollX": true, 
                "scrollX": $(window).width() <= 767,
                // "scrollY": $(window).width() >= 767 ? "500px" : "",
                "scrollY": scrollYValue, 
                "scrollCollapse": $(window).width() <= 767 ? true : "",
                "fixedHeader": $(window).width() <= 767 ? true : "",
                "autoWidth": false,
                "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-file-text-o"></i> Copiar',
                        className: 'btn btn-white btn-sm btn-info-hover'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        className: 'btn btn-white btn-sm btn-green-hover'
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> CSV',
                        className: 'btn btn-white btn-sm btn-green-hover'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Imprimir',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn btn-white btn-sm btn-danger-hover'
                    }
                ],
                colReorder: true, 
                initComplete: function() {
                    $('#all_exams_table_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

        } 

        $allExamsDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $allExamsDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }








        for (let dayId = 2; dayId <= 6; dayId++) {
            let $dataTableElement = jQuery(`#dayDataTable_${dayId}`);

            var rowCount2 = $(`#dayDataTable_${dayId} tbody tr`).length;
            var scrollYValue2 = rowCount2 >= 11 ? "500px" : "";

            let variableName = `day${dayId}DataTable`;
            
            window[variableName] = $dataTableElement.DataTable({
                "order": [[1, "asc"]], 
                "language": languagePreference === 'english' ? {
                    "search": "",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "Showing 0 to 0 of 0 entries",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    },
                    "aria": {
                        "sortAscending": ": Activate to sort column ascending",
                        "sortDescending": ": Activate to sort column descending"
                    }
                } : {
                    "search": "",
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": Activar para ordenar la columna ascendente",
                        "sortDescending": ": Activar para ordenar la columna descendente"
                    }
                },
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValue2, 
                "scrollCollapse": $(window).width() <= 767 ? true : "",
                "fixedHeader": $(window).width() <= 767 ? true : "",
                "autoWidth": false,
                "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, languagePreference === 'english' ? "All" : "Todos"]],
                dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: languagePreference === 'english' ? '<i class="fa fa-file-text-o"></i> Copy' : '<i class="fa fa-file-text-o"></i> Copiar',
                        className: 'btn btn-white btn-sm btn-info-hover'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        className: 'btn btn-white btn-sm btn-green-hover'
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> CSV',
                        className: 'btn btn-white btn-sm btn-green-hover'
                    },
                    {
                        extend: 'print',
                        text: languagePreference === 'english' ? '<i class="fa fa-print"></i> Print' : '<i class="fa fa-print"></i> Imprimir',
                        className: 'btn buttons-html5 btn-white btn-sm btn-danger-hover'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'btn btn-white btn-sm btn-danger-hover'
                    }
                ],
                colReorder: true,
                initComplete: function() {
                    $(`#dayDataTable_${dayId}_filter input[type="search"]`).attr('placeholder', languagePreference === 'english' ? 'Search' : 'Buscar');
                }
            });

            $dataTableElement.closest('.dataTables_wrapper').find('select').select2({
                minimumResultsForSearch: -1
            });

            if ($(window).width() > 767) {
                $dataTableElement.colResizable({
                    liveDrag: true,
                    resizeMode: 'fit',
                    partialRefresh: true,
                    headerOnly: true
                });
            }
        }










	});

		
</script>

<script type="text/javascript">
    
    function reload_ajax() {
        location.reload(); 
    }

</script> 


<style>

    #all_exams_table_filter {
        margin-top: 5px !important;
        
    }  #all_exams_table_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }


    #dayDataTable_2_filter, #dayDataTable_3_filter, #dayDataTable_4_filter, #dayDataTable_5_filter, #dayDataTable_6_filter {
        margin-top: 5px !important;
        
    }  #dayDataTable_2_filter input, #dayDataTable_3_filter input, #dayDataTable_4_filter input, #dayDataTable_5_filter input, #dayDataTable_6_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }

    .selectContent {
		background-color: #B0DFCC !important;
		border-radius: 5px;
		font-weight: bold;
        margin-right: 0px;
        margin-left: 0px;
        color: #265044;
    } .selectContent .labelSelect {
        font-size: 20px;
        margin-top: 12px;
    } .selectContent .selectElement {
        margin-top: 12px;
        background-color: #fff;
    }

</style> 