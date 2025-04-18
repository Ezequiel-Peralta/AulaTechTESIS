<?php
    $this->db->from('news');
    
    if (!empty($user_type)) {
        $this->db->where('user_type', $user_type);
    }
    
    $this->db->where('status_id', 1);
    $query = $this->db->get();
    $all_news_count = $query->num_rows();
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
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_news/"
                        <?php if ($user_type == '') echo 'selected="selected"'; ?>>
                        <?php echo ucfirst(get_phrase('show_all_news')) ?>
                    </option>
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_news/all"
                        <?php if ($user_type == 'all') echo 'selected="selected"'; ?>>
                        <?php echo ucfirst(get_phrase('for')) ?> <?php echo ucfirst(get_phrase('all')) ?>
                    </option>
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_news/students"
                        <?php if ($user_type == 'students') echo 'selected="selected"'; ?>>
                        <?php echo ucfirst(get_phrase('for')) ?> <?php echo ucfirst(get_phrase('students')) ?>
                    </option>
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_news/guardians"
                        <?php if ($user_type == 'guardians') echo 'selected="selected"'; ?>>
                        <?php echo ucfirst(get_phrase('for')) ?> <?php echo ucfirst(get_phrase('guardians')) ?>
                    </option>
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_news/teachers"
                        <?php if ($user_type == 'teachers') echo 'selected="selected"'; ?>>
                        <?php echo ucfirst(get_phrase('for')) ?> <?php echo ucfirst(get_phrase('teachers')) ?>
                    </option>
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_news/teachers_aide"
                        <?php if ($user_type == 'teachers_aide') echo 'selected="selected"'; ?>>
                        <?php echo ucfirst(get_phrase('for')) ?> <?php echo ucfirst(get_phrase('teachers_aide')) ?>
                    </option>
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_news/secretaries"
                        <?php if ($user_type == 'secretaries') echo 'selected="selected"'; ?>>
                        <?php echo ucfirst(get_phrase('for')) ?> <?php echo ucfirst(get_phrase('secretaries')) ?>
                    </option>
                    <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_news/principals"
                        <?php if ($user_type == 'principals') echo 'selected="selected"'; ?>>
                        <?php echo ucfirst(get_phrase('for')) ?> <?php echo ucfirst(get_phrase('principals')) ?>
                    </option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                <?php echo ucfirst(get_phrase('list')); ?></span>
                    <span class="badge badge-success badge-nav-tabs-quantity">
                        <?php echo $all_news_count; ?>
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
                
                <?php
$this->db->from('news');
if (!empty($user_type)) {
    $this->db->where('user_type', $user_type);
}
$this->db->where('status_id', 1);
$news = $this->db->get()->result_array();
?>

<div class="row">
    <?php foreach ($news as $new): ?>
        <div class="col-md-4 mb-4">
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_view_news/<?php echo $new['news_id'];?>');">
                <div class="card shadow-sm">
                <?php
                    $images = json_decode($new['images'], true);
                    $image_src = !empty($images) && $images[0] !== 'assets/images/default-news-img.png' ? 
                        base_url() . 'uploads/news/' . $new['news_id'] . '/' . $images[0] : 
                        base_url() . 'assets/images/default-news-img.png';
                    ?>
                    <div class="position-relative">
                        <img src="<?php echo $image_src; ?>" class="card-img-top img-fluid" alt="News Image">
                        <div class="date-overlay">
                            <?php
                            $date = DateTime::createFromFormat('Y-m-d', $new['date']);

                            if ($this->session->userdata('language_preference') === 'spanish') {
                                $day = strftime('%A', $date->getTimestamp());
                                $month = strftime('%B', $date->getTimestamp());

                                $daysMap = unserialize(DAYSMAP);
                                $monthsMap = unserialize(MONTHSMAP);

                                echo $daysMap[$day] . ', ' . $date->format('d') . ' de ' . $monthsMap[$month] . ' del ' . $date->format('Y');
                            } else {
                                echo $date->format('l, d F Y');
                            }
                            ?>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <?php
                        $title = strlen($new['title']) > 20 ? substr($new['title'], 0, 20) . "..." : $new['title'];
                        ?>
                        <br>
                        <h5 class="card-title">
                            <span class="span-news-title">
                                <?php echo ucfirst($title); ?>
                            </span>
                        </h5>

                        <?php
                        $this->db->select('name');
                        $this->db->from('news_types');
                        $this->db->where('news_type_id', $new['news_type_id']);
                        $news_type = $this->db->get()->row_array();
                        ?>
                        <p class="news-type">
                                <?php echo ucfirst(get_phrase($news_type['name'])); ?>
                            
                        </p>

                        <?php
                        $body = strlen($new['body']) > 20 ? substr($new['body'], 0, 20) . "..." : $new['body'];
                        ?>
                        <p class="card-text"><?php echo $body; ?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>


<br>
   
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

        if (languagePreference === 'english') {
            var allExamsDataTable = $allExamsDataTable.DataTable({
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

   

       

	});

		
</script>

<script type="text/javascript">
    
    function reload_ajax() {
        location.reload(); 
    }

</script> 


<style>
   .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        background-color: #ffffff;
        position: relative;
    }

    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        background-color: #B0DFCC !important;
        transform: translateY(-5px);
    }

    .card-img-top {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .date-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(0, 0, 0, 0.6);
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px !important;
        margin-bottom: 10px;
        color: #265044;
    }

    .news-type {
        font-size: 14px;
        color: #555;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .span-news-title {
        color: #265044;
        background-color: #B0DFCC;
        padding: 5px 10px;
        border-radius: 10px;
    }

    .card:hover .span-news-title {
        background-color: #fff !important; 
    }

    .card-text {
        color: #555;
        font-size: 14px;
        margin-top: 10px;
    }

    .details {
        color: #555;
        font-size: 14px;
    }

    .details i {
        color: #888;
        margin-right: 5px;
    }

    .d-flex {
        display: flex;
        justify-content: space-between;
    }

    .text-end {
        text-align: right;
    }

    .mb-1 {
        margin-bottom: 5px;
        color: #265044 !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .shadow-sm {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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