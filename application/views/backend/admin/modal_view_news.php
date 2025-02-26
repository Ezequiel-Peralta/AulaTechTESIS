<?php 
    $edit_data = $this->db->get_where('news', array('news_id' => $param2))->result_array();
    foreach($edit_data as $row):
?>

<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?php echo ucfirst(get_phrase('view_news'))?></h4>
</div>

<div class="modal-body modal-news-body" style="height: 500px; overflow: auto;">
    <div class="row mt-4 justify-content-center"> 
        <?php
        $images = json_decode($row['images'], true);
        $defaultImage = 'assets/images/default-news-img.png';

        if (!empty($images)) {
            $imageCount = count($images);
            foreach ($images as $image) {
                $imagePath = base_url() . 'uploads/news/' . $row['news_id'] . '/' . $image;
                ?>
                <div class="col-md-<?php echo ($imageCount > 1) ? '6' : '12'; ?> mb-3"> 
                    <div class="card card-modal modal-news-card">
                        <img src="<?php echo $imagePath; ?>" class="card-img-top modal-news-img" alt="News Image">
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="col-md-12 mb-3">
                <div class="card card-modal modal-news-card">
                    <img src="<?php echo base_url() . $defaultImage; ?>" class="card-img-top modal-news-img" alt="Default News Image">
                </div>
            </div>
            <?php
        }
        ?>
    </div>

    <div class="modal-news-details mt-4">
        <h5 class="modal-news-title"><?php echo ucfirst($row['title']); ?></h5>
        <p class="modal-news-date">
            <?php
            $date = DateTime::createFromFormat('Y-m-d', $row['date']);
            // Obtener día y mes en español o inglés
           

          
            if ($this->session->userdata('language_preference') === 'spanish') {
                $day = strftime('%A', $date->getTimestamp());
                $month = strftime('%B', $date->getTimestamp());
                echo DAYSMAP[$day] . ', ' . $date->format('d') . ' de ' . MONTHSMAP[$month] . ' del ' . $date->format('Y');
            } else {
                echo $date->format('l, d F Y');
            }
            ?>
        </p>
        <p class="modal-news-type">
            <span class="span-modal-news-type"><?php
            if (isset($row['news_type_id'])) {
                $this->db->select('name');
                $this->db->from('news_types');
                $this->db->where('news_type_id', $row['news_type_id']);
                $news_type = $this->db->get()->row_array();
                if ($news_type) {
                    echo ucfirst(get_phrase($news_type['name']));
                } 
            } 
        ?></span></p>
        <div class="modal-news-body">
            <p><?php echo nl2br($row['body']); ?></p>
        </div>
    </div>
</div>

<div class="modal-footer text-center">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Volver</button>
</div>

<?php endforeach; ?>

<style>
    .modal-news-body img {
        max-height: 400px;
        object-fit: contain;
        width: 100%;
    }

    .modal-news-card {
        border: none;
    }

    .card-modal {
        pointer-events: none !important; 
        transition: none !important;
    }

    .modal-news-details {
        text-align: center; 
        padding: 20px; 
    }

    .modal-news-title {
        font-size: 24px;
        font-weight: bolder;
        color: #265044;
    }

    .modal-news-date {
        font-size: 14px;
        color: #265044;
        margin: 10px 0;
    }

    .modal-news-type {
        font-size: 16px;
        margin-bottom: 20px;
      
    }

    .span-modal-news-type {
        background-color: #B0DFCC;
        font-weight: bold;
        color: #265044;
        padding: 5px 10px;
        border-radius: 10px;
    }

    .modal-news-body {
        font-size: 16px;
        color: #555;
    }

    @media (min-width: 768px) {
        .modal-dialog {
            width: 800px !important;
            margin: 30px auto;
        }
    }

    .modal-footer {
        padding: 15px;
        text-align: center !important;
        border-top: 1px solid #e5e5e5;
    }
</style>
