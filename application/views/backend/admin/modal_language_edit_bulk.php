<?php 
// Obtener los segmentos de la URL
$segments = $this->uri->segment_array();
// Encontrar el índice del segmento que contiene 'modal_language_edit_bulk'
$index = array_search('modal_language_edit_bulk', $segments);

// Obtener los nombres de los lenguajes a partir del índice encontrado
$language_names = array_slice($segments, $index);
?>

<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="entypo-pencil"></i><?php echo 'Editar lenguajes'?></h4>
</div>

<?php echo form_open(base_url() . 'index.php?admin/language_settings/edit_language_bulk' , array('class' => 'form-horizontal form-groups-bordered validate'));?>

<div class="modal-body" style="height:500px; overflow:auto;">
    <div class="row">
        <div class="col-md-12">
            <div class="panel" data-collapsed="0">
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" style="color: #265044; font-weight: bold;">N°</th>
                                <th class="text-center" style="color: #265044; font-weight: bold;">Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($language_names as $index => $language_name) { ?>
                            <tr>
                                <td class="text-center"><?php echo $index + 1; ?></td>
                                <td class="text-center">
                                    <input type="text" class="form-control" name="language_names[]" value="<?php echo $language_name; ?>" required>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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
