<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">
    <i class="entypo-doc-text-inv"></i><?php echo 'Ver archivo de imagen'; ?>
    </h4>
</div>

<div class="modal-body" style="height: 600px; overflow:auto; background-color: #ebebeb;">

<div class="tab-pane box active" id="edit" style="padding: 5px">
    <div class="box-content">
            <table class="table">
                <tr>
                    <td style="width: 100%; border-top: 0px solid #ebebeb; border-bottom: 0px solid #ebebeb;">
                        <img src="<?php echo $param2 . '/' . $param3 . '/' . $param4 . '/' . $param5;?>" alt="archivo de imagen" style="width: 100%; height: 180px;">
                        
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px solid #ebebeb; border-bottom: 3px solid #ebebeb;">
                        <h2 class="text-center"><?php echo $param5;?></h2>
                    </td>
                </tr>
            </table>
    </div>
</div>
</div>

