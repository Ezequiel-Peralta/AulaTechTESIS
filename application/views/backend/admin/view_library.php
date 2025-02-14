<?php
// Verificar si hay registros en 'subject', si no, usar 'subject_history'
$this->db->from('subject');
$this->db->where('section_id', $section_id);

if (!empty($subject_id)) {
    $this->db->where('subject_id', $subject_id);
}
$this->db->where('status_id', 1);
$subjects = $this->db->get()->result_array();

// Si no hay registros en 'subject', consultar en 'subject_history'
if (empty($subjects)) {
    $this->db->from('subject_history');
    $this->db->where('section_id', $section_id);

    if (!empty($subject_id)) {
        $this->db->where('subject_id', $subject_id);
    }
    $this->db->where('status_id', 1);
    $subjects = $this->db->get()->result_array();
}

// Verificar si hay registros en 'library', si no, usar 'library_history'
$this->db->from('library');
$this->db->where('section_id', $section_id);

if (!empty($subject_id)) {
    $this->db->where('subject_id', $subject_id);
}
$this->db->where('status_id', 1);
$file_count = $this->db->count_all_results();

// Si no hay registros en 'library', consultar en 'library_history'
if ($file_count == 0) {
    $this->db->from('library_history');
    $this->db->where('section_id', $section_id);

    if (!empty($subject_id)) {
        $this->db->where('subject_id', $subject_id);
    }
    $this->db->where('status_id', 1);
    $file_count = $this->db->count_all_results();
}
?>


<div class="row selectContent">
        <div class="col-md-6">
            <div class="form-group">
                <label for="academic_period_select" class="labelSelect">
                    <?php echo ucfirst(get_phrase('you_are_viewing')); ?>
                </label> 
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <select id="academic_period_select" class="form-control selectElement" onchange="return get_sections(this.value)">
                    <?php
                    $academic_periods = $this->db->get('academic_period')->result_array();

                    foreach ($academic_periods as $period):
                        // Verifica si $academic_period_id no está vacío y coincide con $period['id']
                        if (!empty($academic_period_id) && $academic_period_id == $period['id']) {
                            $selected = 'selected="selected"';
                        } elseif (empty($academic_period_id) && $period['status_id'] == 1) {
                            // Marca por defecto el período académico con status_id = 1 si $academic_period_id está vacío
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                    ?>
                        <option value="<?php echo $period['id']; ?>" data-academic-period-id="<?php echo $period['id']; ?>" <?php echo $selected; ?>>
                            <?php echo $period['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <br>

    <div class="row selectContent">
    <div class="col-md-6">
        <div class="form-group">
            <label for="class_select" class="labelSelect"><?php echo ucfirst(get_phrase('class')); ?>
            </label> 
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <select id="class_select" class="form-control selectElement" onchange="location = this.value;">
                <?php
                    if (isset($academic_period_id) && !empty($academic_period_id)) {
                        // Si $academic_period_id está definido y no está vacío, buscar por su ID
                        $active_academic_period = $this->db->get_where('academic_period', array('id' => $academic_period_id))->row();

                        if ($active_academic_period) { // Validar que el período existe
                            $this->db->where('academic_period_id', $active_academic_period->id);
                            $sections = $this->db->get('section_history')->result_array();

                            foreach ($sections as $row):
                    ?>
                                <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_student_mark/<?php echo $row['section_id']; ?>"
                                    <?php if ($section_id == $row['section_id'] && $academic_period_id == $row['academic_period_id']) echo 'selected="selected"'; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                    <?php 
                            endforeach;
                        }
                    } else {
                        // Si $academic_period_id no está definido o está vacío, buscar por el período activo
                        $active_academic_period = $this->db->get_where('academic_period', array('status_id' => 1))->row();

                        if ($active_academic_period) { // Validar que el período existe
                            $this->db->where('academic_period_id', $active_academic_period->id);
                            $sections = $this->db->get('section')->result_array();

                            foreach ($sections as $row):
                    ?>
                                <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_library/<?php echo $row['section_id']; ?>"
                                    <?php if ($section_id == $row['section_id']) echo 'selected="selected"'; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                    <?php 
                            endforeach;
                        } else {
                            echo '<option value="">No hay cursos disponibles</option>';
                        }
                    }
                    ?>
            </select>
        </div>
    </div>
</div>


<br>

<div class="row selectContent">
    <div class="col-md-6">
        <div class="form-group">
            <label for="subject_select" class="labelSelect"><?php echo ucfirst(get_phrase('subject')); ?></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <select id="subject_select" class="form-control selectElement" onchange="location = this.value;">
                <option selected disabled value=""><?php echo ucfirst(get_phrase('select')); ?></option>
                <?php
                // Buscar en la tabla 'subject'
                $this->db->where('section_id', $section_id);
                $subjects = $this->db->get('subject')->result_array();

                // Si no encuentra registros en 'subject', buscar en 'subject_history'
                if (empty($subjects)) {
                    $this->db->where('section_id', $section_id);
                    $subjects = $this->db->get('subject_history')->result_array();
                }

                foreach ($subjects as $subject): ?>
                    <option value="<?php echo base_url(); ?>index.php?admin/view_library/<?php echo $section_id; ?>/<?php echo $subject['subject_id']; ?>"
                        <?php if ($subject_id == $subject['subject_id']) echo 'selected="selected"'; ?>>
                        <?php echo $subject['name']; ?>
                    </option>
                <?php endforeach; ?>
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
                        <?php echo $file_count; ?>
                    </span>
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_library" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                
                <div class="row">
                <?php
        // Consultar en 'subject' primero
        $this->db->from('subject');
        $this->db->where('section_id', $section_id);

        if (!empty($subject_id)) {
            $this->db->where('subject_id', $subject_id);
        }

        $this->db->where('status_id', 1);
        $this->db->order_by('name', 'ASC'); 
        $subjects = $this->db->get()->result_array();

        if (empty($subjects)) {
            $this->db->from('subject_history');
            $this->db->where('section_id', $section_id);

            if (!empty($subject_id)) {
                $this->db->where('subject_id', $subject_id);
            }

            $this->db->where('status_id', 1);
            $this->db->order_by('name', 'ASC'); 
            $subjects = $this->db->get()->result_array();
        }
        ?>
    <?php foreach ($subjects as $subject): ?>
        <h4 class="text-center"><span class="span"><?php echo ucfirst($subject['name']); ?></span></h4>
        <br>
        <div class="row">
            <?php
            // Obtener archivos de la tabla 'library' para cada subject_id, ordenados por fecha descendente
                $this->db->from('library');
                if (!empty($subject_id)) {
                    $this->db->where('subject_id', $subject_id);
                } else {
                    $this->db->where('subject_id', $subject['subject_id']);
                }
                $this->db->where('status_id', 1);
                $this->db->order_by('date', 'DESC'); // Ordenar los archivos por fecha de forma descendente
                $library_files = $this->db->get()->result_array();

                // Si no se encuentran registros en 'library', consultar en 'library_history'
                if (empty($library_files)) {
                    $this->db->from('library_history');
                    if (!empty($subject_id)) {
                        $this->db->where('subject_id', $subject_id);
                    } else {
                        $this->db->where('subject_id', $subject['subject_id']);
                    }
                    $this->db->where('status_id', 1);
                    $this->db->order_by('date', 'DESC'); // Ordenar los archivos por fecha de forma descendente
                    $library_files = $this->db->get()->result_array();
                }
            
            if (!empty($library_files)):
                foreach ($library_files as $file):
                    $file_url = base_url() . 'assets/libraries/' . $file['url_file'];
                    $file_extension = pathinfo($file['url_file'], PATHINFO_EXTENSION);
                    
                    // Mapa de imágenes según las extensiones
                    $image_map = [
                        'xls' => 'assets/images/img-excel.jpg',
                        'xlsx' => 'assets/images/img-excel.jpg',
                        'csv' => 'assets/images/img-excel.jpg',
                        'doc' => 'assets/images/img-word.jpg',
                        'docx' => 'assets/images/img-word.jpg',
                        'pdf' => 'assets/images/img-pdf.jpg',
                        'jpg' => 'assets/images/img-img.jpg',
                        'jpeg' => 'assets/images/img-img.jpg',
                        'png' => 'assets/images/img-img.jpg',
                        'gif' => 'assets/images/img-img.jpg',
                        'txt' => 'assets/images/img-txt.jpg'
                    ];
                    
                    // Determinar la imagen a mostrar
                    $image_url = isset($image_map[$file_extension]) ? $image_map[$file_extension] : 'assets/images/img-default.jpg';
            ?>
                <div class="col-md-4 mb-4" style="margin-left: 40px;">
                    <div class="card shadow-sm">
                        <div class="card-img-top img-fluid" style="display: flex; justify-content: center; align-items: center;">
                            <img src="<?php echo $image_url; ?>" style="width: 50px; height: 50px; object-fit: cover;" alt="File Image">
                        </div>

                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo ucfirst($file['file_name']); ?></h5>
                            <p class="mb-1"><strong><?php echo ucfirst(get_phrase('date')); ?>:</strong> <?php echo date('d/m/Y', strtotime($file['date'])); ?></p>
                            <p class="mb-1"><?php echo ucfirst($file['description']); ?></p>
                            <div class="text-center">
                                <?php
                                    $folder = ($used_section_history || $used_subject_history) ? 'library_history' : 'library';

                                    $section_letter_name = $this->crud_model->get_section_letter_name2($file['section_id']);

                                    $file_url = base_url() . 'uploads/' . $folder . '/' . $file['class_id'] . '-' . $section_letter_name . '/' . 'subject_' . $file['subject_id'] . '/' . $file['url_file']; 
                                ?>
                                <a id="downloadAllBtn" href="<?php echo $file_url;?>" download title="<?php echo ucfirst(get_phrase('download')); ?>" class="btn btn-primary-hover">
                                    <i class="fa fa-download"></i> 
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php else: ?>
                <div class="col-md-12 text-center">
                    <p style="color: #265044; font-size: 15px; font-weight: bold;">
                        <?php echo ucfirst(get_phrase('no_files_available')); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    <br>
    <br>
    <?php endforeach; ?>
</div>

<script type="text/javascript">
        function get_sections(academic_period_id) {
            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_section_content_by_academic_period/' + academic_period_id + '/view_library',
                success: function(response) {
                    const emptyOption = '<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>';
                    jQuery('#class_select').html(emptyOption + response);
                }
            });

        }
   
</script>
		

<script type="text/javascript">
    
    function reload_ajax() {
        location.reload(); 
    }

</script> 


<style>

    h4.text-center span {
        background-color: #B0DFCC !important;
        padding: 5px 12px;
        border-radius: 10px;
        color: #265044;
        font-weight: bold;
    }
   
   @media (min-width: 992px) {
        .col-md-4 {
            width: 29.333333% !important;
        }
    }

    .btn-primary-hover:hover {
        background-color: #265044 !important; 
        border-color: #265044 !important;
        color: #B0DFCC !important;
    }

    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        background-color: #ffffff;
        transition: all 0.3s ease, background-color 0.3s ease;
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
        transition: transform 0.3s ease; 
    }

    .card-title {
        background-color: #B0DFCC;
        color: #265044; 
        padding: 5px 10px;
        border-radius: 10px;
        text-align: center;
        transition: background-color 0.3s ease;
    }

    .card:hover .card-title {
        background-color: #ffffff; 
        color: #000000;
    }

    .card:hover .card-img-top {
        transform: scale(1.2); 
    }

    .card-body {
        padding: 15px;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
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