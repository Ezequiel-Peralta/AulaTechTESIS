<?php 
$current_editing_language = $param2;
?>


<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="entypo-doc-text-inv"></i><?php echo 'Definiciones del lenguaje'?></h4>
</div>

<?php echo form_open(base_url() . 'index.php?admin/language_settings/edit_phrase_language/'.$current_editing_language ,  array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>

<div class="modal-body" style="height:500px; overflow:auto;">
    <div class="row">
        <div class="col-md-12">
            <div class="tab-pane active" id="edit" style="padding: 5px">
                <div class="">
                    <div class="row">
                        
                        <?php
                        $count = 1;
                        $language_phrases = $this->db->query("SELECT `phrase_id`, `phrase`, `$current_editing_language` FROM `language`")->result_array();
                        foreach ($language_phrases as $row):
                            $count++;
                            $phrase_id = $row['phrase_id'];
                            $phrase = $row['phrase'];
                            $phrase_language = $row[$current_editing_language];
                        ?>
                            <div class="col-sm-3">
                                <div class="tile-stats tile-gray" style="background-image: url('assets/images/phraseBackground2.png'); background-size: cover;">
                                <h3 style="padding: 0px 0px 0px 10px; color: #fff; font-weight: 600; background-color: rgba(0, 0, 0, 0.3);"><?php echo $phrase; ?></h3>
                                    <p>
                                        <input type="text" style="background-color: #fff;" name="phrase<?php echo $phrase_id; ?>" value="<?php echo $phrase_language; ?>" class="form-control" />
                                    </p>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                    <input type="hidden" name="total_phrase" value="<?php echo $count; ?>" />
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer text-center" style="text-align: center;">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Volver</button>
    <button type="submit" class="btn btn-success"><i class="entypo-floppy"></i> Guardar</button>
</div>

<?php echo form_close();?> 








<style>
    @media screen and (min-width: 768px) {
        .modal-dialog {
            width: 1400px !important;
            /* padding-top: 30px;
            padding-bottom: 30px; */
            padding-top: 0px;
            padding-bottom: 0px;
        }
    }
</style>
