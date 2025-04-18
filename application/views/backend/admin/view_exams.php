<?php
$all_exams_count = count($exams);
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
                                <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_students_mark/<?php echo $row['section_id']; ?>"
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
                                <option id="actualSectionId" value="<?php echo base_url(); ?>index.php?admin/view_exams/<?php echo $row['section_id']; ?>"
                                    <?php if ($section_id == $row['section_id']) echo 'selected="selected"'; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                    <?php 
                            endforeach;
                        } else {
                            echo '<option value="">No hay secciones disponibles</option>';
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
                    <option value="<?php echo base_url(); ?>index.php?admin/view_exams/<?php echo $section_id; ?>/<?php echo $subject['subject_id']; ?>"
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
                        <?php echo $all_exams_count; ?>
                    </span>
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <br>
                <div class="mt-2 mb-4">
                    <a href="<?php echo base_url(); ?>index.php?admin/exams_add" class="btn btn-table btn-white btn-green-hover" title=" <?php echo ucfirst(get_phrase('add')); ?>" style="padding: 6px 10px;"><i class="fa fa-plus"></i></a>
                    <button type="button" onclick="reload_ajax()" class="btn btn-table btn-white btn-warning-hover" title="<?php echo ucfirst(get_phrase('reload')); ?>" style="padding: 6px 10px;"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right"> 
                       
                    </div>
                </div>
                <br>
                
                <div class="row">
                    <?php foreach ($exams as $exam): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-img-top img-fluid collage-container" style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 10px;">
                                    <?php
                                    // Decodificar el JSON de archivos
                                    $files = json_decode($exam['files'], true);
                                    $file_count_by_extension = [];

                                    if (is_array($files) && !empty($files)) {
                                        foreach ($files as $file) {
                                            $extension = pathinfo($file, PATHINFO_EXTENSION);
                                            if (!isset($file_count_by_extension[$extension])) {
                                                $file_count_by_extension[$extension] = 0;
                                            }
                                            $file_count_by_extension[$extension]++;
                                        }

                                        // Definir las imágenes según las extensiones, sin repetir
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

                                        $unique_extensions_displayed = [];

                                        foreach ($file_count_by_extension as $extension => $count) {
                                            if (isset($image_map[$extension]) && !in_array($extension, $unique_extensions_displayed)) {
                                                $unique_extensions_displayed[] = $extension;
                                                $image_url = $image_map[$extension];
                                                ?>
                                                <div style="position: relative; display: inline-block; text-align: center;">
                                                    <img src="<?php echo $image_url; ?>" style="width: 50px; height: 50px; object-fit: cover;" alt="File Image">
                                                    <?php if ($count > 1): ?>
                                                        <span style="position: absolute; top: -10px; right: -14px; background-color: #265044; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px;">
                                                            <?php echo $count; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php }
                                        }
                                    } else {
                                        echo '<p style="color: #265044; font-size: 15px; font-weight: bold; text-align: center;">' . ucfirst(get_phrase('no_files_available')) . '</p>';
                                    }
                                    ?>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title text-center"><?php echo ucfirst($exam['name']); ?></h5>

                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="mb-1"><i class="entypo-date"></i> <strong><?php echo ucfirst(get_phrase('date')); ?>:</strong></p>
                                            <p class="mb-1"><i class="entypo-date"></i> <strong><?php echo ucfirst(get_phrase('type')); ?>:</strong></p>
                                            <p class="mb-1"><i class="entypo-date"></i> <strong><?php echo ucfirst(get_phrase('subject')); ?>:</strong></p>
                                            <p class="mb-1"><i class="entypo-date"></i> <strong><?php echo ucfirst(get_phrase('teacher')); ?>:</strong></p>
                                        </div>
                                        <div class="text-end">
                                           
                                            <p class="mb-1">
                                                <?php echo date('d/m/Y', strtotime($exam['date'])); ?>
                                            </p>
                                            <p class="mb-1">
                                                <?php
                                                $this->db->select('name'); // Asumiendo que la columna que contiene el nombre es 'name'
                                                $this->db->from('exam_type');
                                                $this->db->where('id', $exam['exam_type_id']);
                                                $exam_type = $this->db->get()->row_array();

                                                echo ucfirst(get_phrase($exam_type['name']));
                                                ?>
                                            </p>
                                            <?php
                                                $this->db->from('subject');
                                                $this->db->where('subject_id', $exam['subject_id']);
                                                $subject = $this->db->get()->row();

                                                if (empty($subject)) {
                                                    $this->db->from('subject_history');
                                                    $this->db->where('subject_id', $exam['subject_id']);
                                                    $subject = $this->db->get()->row();
                                                }

                                                $subject_name = $subject ? $subject->name : '';

                                                $this->db->from('teacher_details');
                                                $this->db->where('teacher_id', $exam['teacher_id']);
                                                $teacher = $this->db->get()->row(); 

                                                $teacher_fullname = $teacher->lastname . ', ' . $teacher->firstname;

                                            ?>
                                            <p class="mb-1">
                                                <?php echo ucfirst($subject_name); ?>
                                            </p>
                                            <p class="mb-1">
                                                <?php echo ucfirst($teacher_fullname); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <p class="mb-1 text-center">
                                        <strong><?php echo ucfirst(get_phrase('files_count')); ?>:</strong> <?php echo count($files); ?>
                                    </p>

                                    <?php if (!empty($exam['files'])): ?>
                                        <div class="text-center">
                                            <a id="downloadAllBtn-<?php echo $exam['exam_id']; ?>" href="javascript:void(0);" title="<?php echo ucfirst(get_phrase('download_all_files')); ?>" class="btn btn-primary-hover">
                                                <i class="fa fa-download"></i> 
                                            </a>
                                        </div>

                                        <div style="display: none;">
                                            <?php
                                             $folder = ($used_section_history || $used_subject_history) ? 'exams_history' : 'exams';

                                            if (is_array($files) && !empty($files)) {
                                                foreach ($files as $file) {
                                                    $file_url = base_url() . 'uploads/' . $folder . '/' . $exam['exam_id'] . '/' . $file; 
                                                    echo '<a class="file-link-' . $exam['exam_id'] . '" href="' . $file_url . '" download></a>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <br>
   
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
        function get_sections(academic_period_id) {
            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_sections_content_by_academic_period/' + academic_period_id + '/view_exams',
                success: function(response) {
                    const emptyOption = '<option value="" selected disabled><?php echo ucfirst(get_phrase('select')); ?></option>';
                    jQuery('#class_select').html(emptyOption + response);
                }
            });

        }
   
</script>


<script>
    $(document).ready(function() {
        // Seleccionar todos los botones de descarga
        $('[id^=downloadAllBtn-]').each(function() {
            var downloadAllButton = $(this);

            // Agregar el evento de clic
            downloadAllButton.on('click', function() {
                console.log('click adentro del listener para el examen ID: ' + downloadAllButton.attr('id'));

                // Obtener el exam_id a partir del ID del botón
                var examId = downloadAllButton.attr('id').split('-')[1];
                const fileLinks = document.querySelectorAll('.file-link-' + examId);

                // Iniciar la descarga de cada archivo
                fileLinks.forEach(link => {
                    link.click();
                });
            });
        });
    });
</script>
		

<script type="text/javascript">
    
    function reload_ajax() {
        location.reload(); 
    }

</script> 


<style>
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