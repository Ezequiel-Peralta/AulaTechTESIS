<?php
$this->db->from('news');
$query = $this->db->get();
$all_elements_news_count = $query->num_rows();

$this->db->from('news');
$this->db->where('user_type', 'all');
$query = $this->db->get();
$all_news_count = $query->num_rows();

$this->db->from('news');
$this->db->where('user_type', 'students');
$query = $this->db->get();
$all_news_students_count = $query->num_rows();

$this->db->from('news');
$this->db->where('user_type', 'guardians');
$query = $this->db->get();
$all_news_guardians_count = $query->num_rows();

$this->db->from('news');
$this->db->where('user_type', 'teachers');
$query = $this->db->get();
$all_news_teachers_count = $query->num_rows();

$this->db->from('news');
$this->db->where('user_type', 'teachers_aide');
$query = $this->db->get();
$all_news_teachers_aide_count = $query->num_rows();

$this->db->from('news');
$this->db->where('user_type', 'secretaries');
$query = $this->db->get();
$all_news_secretaries_count = $query->num_rows();

$this->db->from('news');
$this->db->where('user_type', 'principals');
$query = $this->db->get();
$all_news_principals_count = $query->num_rows();

?>

<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                <?php echo ucfirst(get_phrase('list')); ?></span>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_elements_news_count; ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="#all" data-toggle="tab">
                <?php echo ucfirst(get_phrase('all')); ?></span>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_news_count; ?>
                    </span>
                </a>
            </li>
            <li class="">
                <a href="#students" data-toggle="tab">
                <?php echo ucfirst(get_phrase('students')); ?></span>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_news_students_count; ?> 
                    </span>
                </a>
            </li>
            <li class="">
                <a href="#guardians" data-toggle="tab">
                <?php echo ucfirst(get_phrase('guardians')); ?></span>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_news_guardians_count; ?>
                    </span>
                </a>
            </li>
            <li class="">
                <a href="#teachers" data-toggle="tab">
                <?php echo ucfirst(get_phrase('teachers')); ?></span>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_news_teachers_count; ?>
                    </span>
                </a>
            </li>
            <li class="">
                <a href="#teachers_aide" data-toggle="tab">
                <?php echo ucfirst(get_phrase('teachers_aide')); ?></span>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_news_teachers_aide_count; ?> 
                    </span>
                </a>
            </li>
            <li class="">
                <a href="#secretaries" data-toggle="tab">
                <?php echo ucfirst(get_phrase('secretaries')); ?></span>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_news_secretaries_count; ?> 
                    </span>
                </a>
            </li>
            <li class="">
                <a href="#principals" data-toggle="tab">
                <?php echo ucfirst(get_phrase('principals')); ?></span>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_news_principals_count; ?> 
                    </span>
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_news" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="all_exams_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('title')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('body')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('images')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('type')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('recipient')); ?></th> 
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('class')); ?></th> 
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('section')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $news = $this->db->get('news')->result_array(); 
                                foreach($news as $row):
                                    $date = date("d/m/Y", strtotime($row['date']));

                                    $title = strlen($row['title']) > 20 ? substr($row['title'], 0, 20) . "..." : $row['title'];

                                    $body = strlen($row['body']) > 20 ? substr($row['body'], 0, 20) . "..." : $row['body'];
                               
                                    $this->db->where('class_id', $row['class_id']);
                                    $class = $this->db->get('class')->row_array();
                                    $class_name = !empty($class) ? $class['name'] . '°' : '';

                                    $this->db->where('section_id', $row['section_id']);
                                    $section = $this->db->get('section')->row_array();
                                    $section_name = !empty($section) ? $section['name'] : '';

                                    
                                    $this->db->where('news_type_id', $row['news_type_id']);
                                    $news_types = $this->db->get('news_types')->row_array();
                                    $news_type = !empty($section) ? $news_types['name'] : '';

                                    $images = json_decode($row['images'], true);

                                    if ($row['status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
                                ?>
                        <tr>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['title']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("title"));?>">
                                    <?php echo $title;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['body']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("body"));?>">
                                    <?php echo $body;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($images)): ?>
                                    <?php 
                                        $displayed_images = array_slice($images, 0, 2);
                                        $remaining_count = count($images) - count($displayed_images);
                                    ?>
                                    <?php foreach ($displayed_images as $image): ?>
                                        <span class="popover-image" 
                                            data-toggle="popover" data-trigger="hover" data-placement="top" 
                                            data-content='<div class="popover-image-container">
                                                <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-responsive" style="width: 100px; height: 100px; margin-left: 40px;" />
                                            </div>' 
                                            data-original-title="<?php echo ucfirst(get_phrase("image_preview")); ?>">
                                            <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-circle" width="40" height="40" />
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if ($remaining_count > 0): ?>
                                        <span class="remaining-images">
                                            +<?php echo $remaining_count; ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo $date;?></td>
                            <td class="text-center"><?php echo ucfirst(get_phrase( $news_type));?></td>
                            <td class="text-center"><?php echo ucfirst(get_phrase( $row['user_type']));?></td>
                            <td class="text-center"><?php echo $class_name;?> </td>
                            <td class="text-center"><?php echo $section_name;?></td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_news/" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_news/<?php echo $row['news_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/news/disable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/news/enable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                           
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>


            <div class="tab-pane" id="all">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_news" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="all_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('title')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('body')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('images')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('type')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $this->db->where('user_type', 'all');
                                $news = $this->db->get('news')->result_array(); 
                                foreach($news as $row):
                                    $date = date("d/m/Y", strtotime($row['date']));

                                    $title = strlen($row['title']) > 20 ? substr($row['title'], 0, 20) . "..." : $row['title'];

                                    $body = strlen($row['body']) > 20 ? substr($row['body'], 0, 20) . "..." : $row['body'];
                               
                                    $this->db->where('class_id', $row['class_id']);
                                    $class = $this->db->get('class')->row_array();
                                    $class_name = !empty($class) ? $class['name'] : '';

                                    $this->db->where('section_id', $row['section_id']);
                                    $section = $this->db->get('section')->row_array();
                                    $section_name = !empty($section) ? $section['name'] : '';

                                    $this->db->where('news_type_id', $row['news_type_id']);
                                    $news_types = $this->db->get('news_types')->row_array();
                                    $news_type = !empty($section) ? $news_types['name'] : '';

                                    $images = json_decode($row['images'], true);

                                    if ($row['status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
                                ?>
                        <tr>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['title']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("title"));?>">
                                    <?php echo $title;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['body']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("body"));?>">
                                    <?php echo $body;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($images)): ?>
                                    <?php 
                                        $displayed_images = array_slice($images, 0, 2);
                                        $remaining_count = count($images) - count($displayed_images);
                                    ?>
                                    <?php foreach ($displayed_images as $image): ?>
                                        <span class="popover-image" 
                                            data-toggle="popover" data-trigger="hover" data-placement="top" 
                                            data-content='<div class="popover-image-container">
                                                <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-responsive" style="width: 100px; height: 100px; margin-left: 40px;" />
                                            </div>' 
                                            data-original-title="<?php echo ucfirst(get_phrase("image_preview")); ?>">
                                            <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-circle" width="40" height="40" />
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if ($remaining_count > 0): ?>
                                        <span class="remaining-images">
                                            +<?php echo $remaining_count; ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo $date;?></td>
                            <td class="text-center"><?php echo ucfirst(get_phrase( $news_type));?></td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_news/" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_news/<?php echo $row['news_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/news/disable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/news/enable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                           
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>

            <div class="tab-pane" id="students">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_news" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="students_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('title')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('body')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('images')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('type')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('class')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('section')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $this->db->where('user_type', 'students');
                                $news = $this->db->get('news')->result_array(); 
                                foreach($news as $row):
                                    $date = date("d/m/Y", strtotime($row['date']));

                                    $title = strlen($row['title']) > 20 ? substr($row['title'], 0, 20) . "..." : $row['title'];

                                    $body = strlen($row['body']) > 20 ? substr($row['body'], 0, 20) . "..." : $row['body'];
                               
                                    $this->db->where('class_id', $row['class_id']);
                                    $class = $this->db->get('class')->row_array();
                                    $class_name = !empty($class) ? $class['name'] . '°' : '';

                                    $this->db->where('section_id', $row['section_id']);
                                    $section = $this->db->get('section')->row_array();
                                    $section_name = !empty($section) ? $section['name'] : '';

                                    $this->db->where('news_type_id', $row['news_type_id']);
                                    $news_types = $this->db->get('news_types')->row_array();
                                    $news_type = !empty($section) ? $news_types['name'] : '';

                                    $images = json_decode($row['images'], true);

                                    if ($row['status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
                                ?>
                        <tr>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['title']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("title"));?>">
                                    <?php echo $title;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['body']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("body"));?>">
                                    <?php echo $body;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($images)): ?>
                                    <?php 
                                        $displayed_images = array_slice($images, 0, 2);
                                        $remaining_count = count($images) - count($displayed_images);
                                    ?>
                                    <?php foreach ($displayed_images as $image): ?>
                                        <span class="popover-image" 
                                            data-toggle="popover" data-trigger="hover" data-placement="top" 
                                            data-content='<div class="popover-image-container">
                                                <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-responsive" style="width: 100px; height: 100px; margin-left: 40px;" />
                                            </div>' 
                                            data-original-title="<?php echo ucfirst(get_phrase("image_preview")); ?>">
                                            <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-circle" width="40" height="40" />
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if ($remaining_count > 0): ?>
                                        <span class="remaining-images">
                                            +<?php echo $remaining_count; ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo $date;?></td>
                            <td class="text-center"><?php echo ucfirst(get_phrase( $news_type));?></td>
                            <td class="text-center"><?php echo $class_name;?></td>
                            <td class="text-center"><?php echo $section_name;?></td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_news/" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_news/<?php echo $row['news_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/news/disable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/news/enable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                           
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>

            <div class="tab-pane" id="guardians">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_news" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="guardians_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('title')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('body')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('images')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('type')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('class')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('section')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $this->db->where('user_type', 'guardians');
                                $news = $this->db->get('news')->result_array(); 
                                foreach($news as $row):
                                    $date = date("d/m/Y", strtotime($row['date']));

                                    $title = strlen($row['title']) > 20 ? substr($row['title'], 0, 20) . "..." : $row['title'];

                                    $body = strlen($row['body']) > 20 ? substr($row['body'], 0, 20) . "..." : $row['body'];
                               
                                    $this->db->where('class_id', $row['class_id']);
                                    $class = $this->db->get('class')->row_array();
                                    $class_name = !empty($class) ? $class['name'] . '°' : '';

                                    $this->db->where('section_id', $row['section_id']);
                                    $section = $this->db->get('section')->row_array();
                                    $section_name = !empty($section) ? $section['name'] : '';

                                    $this->db->where('news_type_id', $row['news_type_id']);
                                    $news_types = $this->db->get('news_types')->row_array();
                                    $news_type = !empty($section) ? $news_types['name'] : '';

                                    $images = json_decode($row['images'], true);

                                    if ($row['status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
                                ?>
                        <tr>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['title']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("title"));?>">
                                    <?php echo $title;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['body']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("body"));?>">
                                    <?php echo $body;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($images)): ?>
                                    <?php 
                                        $displayed_images = array_slice($images, 0, 2);
                                        $remaining_count = count($images) - count($displayed_images);
                                    ?>
                                    <?php foreach ($displayed_images as $image): ?>
                                        <span class="popover-image" 
                                            data-toggle="popover" data-trigger="hover" data-placement="top" 
                                            data-content='<div class="popover-image-container">
                                                <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-responsive" style="width: 100px; height: 100px; margin-left: 40px;" />
                                            </div>' 
                                            data-original-title="<?php echo ucfirst(get_phrase("image_preview")); ?>">
                                            <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-circle" width="40" height="40" />
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if ($remaining_count > 0): ?>
                                        <span class="remaining-images">
                                            +<?php echo $remaining_count; ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo $date;?></td>
                            <td class="text-center"><?php echo ucfirst(get_phrase( $news_type));?></td>
                            <td class="text-center"><?php echo $class_name;?></td>
                            <td class="text-center"><?php echo $section_name;?></td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_news/" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_news/<?php echo $row['news_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/news/disable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/news/enable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                           
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>

            <div class="tab-pane" id="teachers">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_news" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="teachers_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('title')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('body')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('images')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('type')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('class')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('section')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $this->db->where('user_type', 'teachers');
                                $news = $this->db->get('news')->result_array(); 
                                foreach($news as $row):
                                    $date = date("d/m/Y", strtotime($row['date']));

                                    $title = strlen($row['title']) > 20 ? substr($row['title'], 0, 20) . "..." : $row['title'];

                                    $body = strlen($row['body']) > 20 ? substr($row['body'], 0, 20) . "..." : $row['body'];
                               
                                    $this->db->where('class_id', $row['class_id']);
                                    $class = $this->db->get('class')->row_array();
                                    $class_name = !empty($class) ? $class['name'] . '°' : '';

                                    $this->db->where('section_id', $row['section_id']);
                                    $section = $this->db->get('section')->row_array();
                                    $section_name = !empty($section) ? $section['name'] : '';

                                    $this->db->where('news_type_id', $row['news_type_id']);
                                    $news_types = $this->db->get('news_types')->row_array();
                                    $news_type = !empty($section) ? $news_types['name'] : '';

                                    $images = json_decode($row['images'], true);

                                    if ($row['status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
                                ?>
                        <tr>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['title']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("title"));?>">
                                    <?php echo $title;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['body']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("body"));?>">
                                    <?php echo $body;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($images)): ?>
                                    <?php 
                                        $displayed_images = array_slice($images, 0, 2);
                                        $remaining_count = count($images) - count($displayed_images);
                                    ?>
                                    <?php foreach ($displayed_images as $image): ?>
                                        <span class="popover-image" 
                                            data-toggle="popover" data-trigger="hover" data-placement="top" 
                                            data-content='<div class="popover-image-container">
                                                <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-responsive" style="width: 100px; height: 100px; margin-left: 40px;" />
                                            </div>' 
                                            data-original-title="<?php echo ucfirst(get_phrase("image_preview")); ?>">
                                            <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-circle" width="40" height="40" />
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if ($remaining_count > 0): ?>
                                        <span class="remaining-images">
                                            +<?php echo $remaining_count; ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo $date;?></td>
                            <td class="text-center"><?php echo ucfirst(get_phrase( $news_type));?></td>
                            <td class="text-center"><?php echo $class_name;?></td>
                            <td class="text-center"><?php echo $section_name;?></td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_news/" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_news/<?php echo $row['news_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/news/disable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/news/enable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                           
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>

            <div class="tab-pane" id="teachers_aide">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_news" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="teachers_aide_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('title')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('body')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('images')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('type')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('class')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('section')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $this->db->where('user_type', 'teachers_aide');
                                $news = $this->db->get('news')->result_array(); 
                                foreach($news as $row):
                                    $date = date("d/m/Y", strtotime($row['date']));

                                    $title = strlen($row['title']) > 20 ? substr($row['title'], 0, 20) . "..." : $row['title'];

                                    $body = strlen($row['body']) > 20 ? substr($row['body'], 0, 20) . "..." : $row['body'];
                               
                                    $this->db->where('class_id', $row['class_id']);
                                    $class = $this->db->get('class')->row_array();
                                    $class_name = !empty($class) ? $class['name'] . '°' : '';

                                    $this->db->where('section_id', $row['section_id']);
                                    $section = $this->db->get('section')->row_array();
                                    $section_name = !empty($section) ? $section['name'] : '';

                                    $this->db->where('news_type_id', $row['news_type_id']);
                                    $news_types = $this->db->get('news_types')->row_array();
                                    $news_type = !empty($section) ? $news_types['name'] : '';

                                    $images = json_decode($row['images'], true);

                                    if ($row['status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
                                ?>
                        <tr>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['title']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("title"));?>">
                                    <?php echo $title;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['body']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("body"));?>">
                                    <?php echo $body;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($images)): ?>
                                    <?php 
                                        $displayed_images = array_slice($images, 0, 2);
                                        $remaining_count = count($images) - count($displayed_images);
                                    ?>
                                    <?php foreach ($displayed_images as $image): ?>
                                        <span class="popover-image" 
                                            data-toggle="popover" data-trigger="hover" data-placement="top" 
                                            data-content='<div class="popover-image-container">
                                                <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-responsive" style="width: 100px; height: 100px; margin-left: 40px;" />
                                            </div>' 
                                            data-original-title="<?php echo ucfirst(get_phrase("image_preview")); ?>">
                                            <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-circle" width="40" height="40" />
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if ($remaining_count > 0): ?>
                                        <span class="remaining-images">
                                            +<?php echo $remaining_count; ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo $date;?></td>
                            <td class="text-center"><?php echo ucfirst(get_phrase( $news_type));?></td>
                            <td class="text-center"><?php echo $class_name;?></td>
                            <td class="text-center"><?php echo $section_name;?></td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_news/" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_news/<?php echo $row['news_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/news/disable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/news/enable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                           
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>

            <div class="tab-pane" id="secretaries">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_news" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="secretaries_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('title')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('body')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('images')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('type')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $this->db->where('user_type', 'secretaries');
                                $news = $this->db->get('news')->result_array(); 
                                foreach($news as $row):
                                    $date = date("d/m/Y", strtotime($row['date']));

                                    $title = strlen($row['title']) > 20 ? substr($row['title'], 0, 20) . "..." : $row['title'];

                                    $body = strlen($row['body']) > 20 ? substr($row['body'], 0, 20) . "..." : $row['body'];
                               
                                    $this->db->where('class_id', $row['class_id']);
                                    $class = $this->db->get('class')->row_array();
                                    $class_name = !empty($class) ? $class['name'] . '°' : '';

                                    $this->db->where('section_id', $row['section_id']);
                                    $section = $this->db->get('section')->row_array();
                                    $section_name = !empty($section) ? $section['name'] : '';

                                    $this->db->where('news_type_id', $row['news_type_id']);
                                    $news_types = $this->db->get('news_types')->row_array();
                                    $news_type = !empty($section) ? $news_types['name'] : '';

                                    $images = json_decode($row['images'], true);

                                    if ($row['status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
                                ?>
                        <tr>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['title']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("title"));?>">
                                    <?php echo $title;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['body']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("body"));?>">
                                    <?php echo $body;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($images)): ?>
                                    <?php 
                                        $displayed_images = array_slice($images, 0, 2);
                                        $remaining_count = count($images) - count($displayed_images);
                                    ?>
                                    <?php foreach ($displayed_images as $image): ?>
                                        <span class="popover-image" 
                                            data-toggle="popover" data-trigger="hover" data-placement="top" 
                                            data-content='<div class="popover-image-container">
                                                <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-responsive" style="width: 100px; height: 100px; margin-left: 40px;" />
                                            </div>' 
                                            data-original-title="<?php echo ucfirst(get_phrase("image_preview")); ?>">
                                            <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-circle" width="40" height="40" />
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if ($remaining_count > 0): ?>
                                        <span class="remaining-images">
                                            +<?php echo $remaining_count; ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo $date;?></td>
                            <td class="text-center"><?php echo ucfirst(get_phrase( $news_type));?></td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_news/" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_news/<?php echo $row['news_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/news/disable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/news/enable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
                                        <i class="fa fa-check-circle-o"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                           
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>


            <div class="tab-pane" id="principals">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_news" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                <table class="table table-bordered datatable table-hover table-striped" id="principals_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50"><?php echo ucfirst(get_phrase('title')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('body')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('images')); ?></th>
                            <th class="text-center"><?php echo ucfirst(get_phrase('date')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('type')); ?></th> 
                            <th class="text-center"><?php echo ucfirst(get_phrase('status')); ?></th>
                            <th class="text-center" width="120"><?php echo ucfirst(get_phrase('action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $this->db->where('user_type', 'principals');
                                $news = $this->db->get('news')->result_array(); 
                                foreach($news as $row):
                                    $date = date("d/m/Y", strtotime($row['date']));

                                    $title = strlen($row['title']) > 20 ? substr($row['title'], 0, 20) . "..." : $row['title'];

                                    $body = strlen($row['body']) > 20 ? substr($row['body'], 0, 20) . "..." : $row['body'];
                               
                                    $this->db->where('class_id', $row['class_id']);
                                    $class = $this->db->get('class')->row_array();
                                    $class_name = !empty($class) ? $class['name'] : '';

                                    $this->db->where('section_id', $row['section_id']);
                                    $section = $this->db->get('section')->row_array();
                                    $section_name = !empty($section) ? $section['name'] : '';

                                    $this->db->where('news_type_id', $row['news_type_id']);
                                    $news_types = $this->db->get('news_types')->row_array();
                                    $news_type = !empty($section) ? $news_types['name'] : '';

                                    $images = json_decode($row['images'], true);

                                    if ($row['status_id'] == 1) {
                                        $status_label = '<span class="label label-status label-success">'. ucfirst(get_phrase('active')) .'</span>';
                                    } else {
                                        $status_label = '<span class="label label-status label-danger">'. ucfirst(get_phrase('inactive')) . '</span>';
                                    }
                                ?>
                        <tr>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['title']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("title"));?>">
                                    <?php echo $title;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="popover-white" 
                                data-toggle="popover" data-trigger="hover" data-placement="top" 
                                data-content="<?php echo ucfirst($row['body']);?>" 
                                data-original-title="<?php echo ucfirst(get_phrase("body"));?>">
                                    <?php echo $body;?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($images)): ?>
                                    <?php 
                                        $displayed_images = array_slice($images, 0, 2);
                                        $remaining_count = count($images) - count($displayed_images);
                                    ?>
                                    <?php foreach ($displayed_images as $image): ?>
                                        <span class="popover-image" 
                                            data-toggle="popover" data-trigger="hover" data-placement="top" 
                                            data-content='<div class="popover-image-container">
                                                <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-responsive" style="width: 100px; height: 100px; margin-left: 40px;" />
                                            </div>' 
                                            data-original-title="<?php echo ucfirst(get_phrase("image_preview")); ?>">
                                            <img src="<?php echo base_url('uploads/news/' . $row['news_id'] . '/' . $image); ?>" class="img-circle" width="40" height="40" />
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if ($remaining_count > 0): ?>
                                        <span class="remaining-images">
                                            +<?php echo $remaining_count; ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo $date;?></td>
                            <td class="text-center"><?php echo ucfirst(get_phrase( $news_type));?></td>
                            <td class="text-center"><?php echo $status_label; ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url();?>index.php?admin/view_news/" class="btn btn-table btn-white btn-info-hover" title="<?php echo ucfirst(get_phrase('view')); ?>">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?admin/edit_news/<?php echo $row['news_id'];?>" class="btn btn-table btn-white btn-orange-hover" title="<?php echo ucfirst(get_phrase('edit')); ?>">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <?php if ($row['status_id'] == 1): ?>
                                    <a href="javascript:;" onclick="confirm_disable_sweet_modal('<?php echo base_url();?>index.php?admin/news/disable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-danger-hover" title="<?php echo ucfirst(get_phrase('disable')); ?>">
                                        <i class="entypo-block"></i>
                                    </a>
                                <?php elseif ($row['status_id'] == 0): ?>
                                    <a href="javascript:;" onclick="confirm_enable_sweet_modal('<?php echo base_url();?>index.php?admin/news/enable_news/<?php echo $row['news_id'];?>');" class="btn btn-table btn-white btn-green-hover" title="<?php echo ucfirst(get_phrase('enable')); ?>">
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

        var $allExamsDataTable = jQuery("#all_exams_table");

        var rowCount = $('#all_exams_table tbody tr').length;
        var scrollYValue = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var allExamsDataTable = $allExamsDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
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
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
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


        var $allDataTable = jQuery("#all_table");

        
        var rowCountAll = $('#all_table tbody tr').length;
        var scrollYValueAll = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var allDataTable = $allDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValueAll, 
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
                    $('#all_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var allDataTable = $allDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValueAll, 
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
                    $('#all_table_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

        }

        $allDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $allDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }

        var $studentsDataTable = jQuery("#students_table");

        var rowCountStudent = $('#students_table tbody tr').length;
        var scrollYValueStudent = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var studentsDataTable = $studentsDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValueStudent, 
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
                    $('#students_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var studentsDataTable = $studentsDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValueStudent, 
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
                    $('#students_table_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

        }

        $studentsDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $studentsDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }


        var $guardiansDataTable = jQuery("#guardians_table");

        var rowCountGuardian = $('#guardians_table tbody tr').length;
        var scrollYValueGuardian = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var guardiansDataTable = $guardiansDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValueGuardian, 
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
                    $('#guardians_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var guardiansDataTable = $guardiansDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValueGuardian, 
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
                    $('#guardians_table_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

        }

        $guardiansDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $guardiansDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }



        var $teachersDataTable = jQuery("#teachers_table");

        var rowCountTeacher = $('#teachers_table tbody tr').length;
        var scrollYValueTeacher = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var teachersDataTable = $teachersDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValueTeacher, 
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
                    $('#teachers_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var teachersDataTable = $teachersDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValueTeacher, 
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
                    $('#teachers_table_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

        }

        $teachersDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $teachersDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }


        var $teachersAideDataTable = jQuery("#teachers_aide_table");

        var rowCountTeachersAide = $('#teachers_aide_table tbody tr').length;
        var scrollYValueTeachersAide = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var teachersAideDataTable = $teachersAideDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValueTeachersAide, 
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
                    $('#teacher_aide_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var teachersAideDataTable = $teachersAideDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValueTeachersAide, 
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
                    $('#teachers_aide_table_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

        }

        $teachersAideDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $teachersAideDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }


        var $secretariesDataTable = jQuery("#secretaries_table");

        var rowCountSecretaries = $('#secretaries_table tbody tr').length;
        var scrollYValueSecretaries = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var secretariesDataTable = $secretariesDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValueSecretaries, 
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
                    $('#secretaries_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var secretariesDataTable = $secretariesDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValueSecretaries, 
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
                    $('#secretaries_table_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

        }

        $secretariesDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $secretariesDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }

        
        var $principalsDataTable = jQuery("#principals_table");

        var rowCountPrincipals = $('#principals_table tbody tr').length;
        var scrollYValuePrincipals = rowCount >= 11 ? "500px" : ""; 

        if (languagePreference === 'english') {
            var principalsDataTable = $principalsDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValuePrincipals, 
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
                    $('#principals_table_filter input[type="search"]').attr('placeholder', 'Search');
                }
            });

        } else if (languagePreference === 'spanish') {
            var principalsDataTable = $principalsDataTable.DataTable({
                "order": [[3, "desc"]], 
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
                "scrollX": $(window).width() <= 767,
                "scrollY": scrollYValuePrincipals, 
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
                    $('#principals_table_filter input[type="search"]').attr('placeholder', 'Buscar');
                }
            });

        }

        $principalsDataTable.closest('.dataTables_wrapper').find('select').select2({
            minimumResultsForSearch: -1
        });

        if ($(window).width() > 767) {
            $principalsDataTable.colResizable({
                liveDrag: true,
                resizeMode: 'fit',
                partialRefresh: true,
                headerOnly: true
            });
        }





	});

		
</script>

<script type="text/javascript">
    
    function reload_ajax() {
        location.reload(); 
    }

    $(document).ready(function() {
        $('[data-toggle="popover"]').popover({
            html: true // Permite el uso de HTML en el contenido del popover
        });
    });

</script> 


<style>
    .remaining-images {
        display: inline-block;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #B0DFCC;
        color: #265044;
        text-align: center;
        font-weight: bold;
        margin-top: 5px; 
        line-height: 40px; 
    }

    .popover-image-container {
        display: flex !important; 
        justify-content: center !important; /* Centra horizontalmente */
        align-items: center !important; /* Centra verticalmente */
        width: 100px !important; /* Ancho fijo del popover */
        height: 100px !important; /* Alto fijo del popover */
    }


    #all_exams_table_filter {
        margin-top: 5px !important;
        
    }  #all_exams_table_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }
    #sectionDataTable_9_filter {
        margin-top: 5px !important;
    }  #sectionDataTable_9_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }  #sectionDataTable_10_filter {
        margin-top: 5px !important;
    }  #sectionDataTable_10_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    } #sectionDataTable_11_filter {
        margin-top: 5px !important;
    }  #sectionDataTable_11_filter input {
        border: 1px solid #9A9A9A !important;
        border-radius: 10px !important;
    }

    

</style> 