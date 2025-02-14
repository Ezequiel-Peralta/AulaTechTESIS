<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/student_add/');" 
    class="btn btn-primary pull-left">
        <i class="entypo-plus-circled"></i>
        <?php echo ('Añadir nuevo administrador');?>
    </a> 
<br> 

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-users"></i></span>
                    <span class="hidden-xs"><i class="entypo-menu"></i><?php echo ('Todos los administradores');?></span>
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                
                <table class="table table-bordered datatable table-hover table-striped" id="table_export">
                    <thead>
                        <tr>
                            <th width="80"><div><?php echo ('#');?></div></th>
                            <th><div><?php echo ('Foto');?></div></th>
                            <th><div><?php echo ('Nombre');?></div></th>
                            <th><div><?php echo ('Usuario');?></div></th>
                            <th><div><?php echo ('Email');?></div></th>
                            <th><div><?php echo ('Ultima conexión');?></div></th>
                            <th><div><?php echo ('Estado');?></div></th>
                            <th  class="text-center"><div><?php echo ('Opciones');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $admins   =   $this->db->get_where('admin')->result_array();
                                $count = 1;
                                foreach($admins as $row):?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td class="text-center"><img src="<?php echo $this->crud_model->get_image_url('admin', $row['name'], 'ID' . $row['admin_id']);?>" class="img-circle" width="35" height="35"/></td>
                            <td><?php echo $row['name'];?></td>
                            <td><?php echo $row['email'];?></td>
                            <td><?php echo $row['last_login'];?></td>
                            <td class="text-center">
                                <?php
                                    switch ($row['login_status']) {
                                        case 1:
                                            echo '<span style="background-color: #00a651 !important; font-weight: bold !important; color: white !important; padding: 5px 10px 5px 10px; border-radius: 10px !important;">En linea</span>';
                                            break;
                                        case 2:
                                            echo '<span style="background-color: #cc2424 !important; font-weight: bold !important; color: white !important; padding: 5px 10px 5px 10px; border-radius: 10px !important;">Desconectado</span>';
                                            break;
                                        case 3:
                                            echo '<span style="background-color: #ff9600 !important; font-weight: bold !important; color: white !important; padding: 5px 10px 5px 10px; border-radius: 10px !important;">Ausente</span>';
                                            break;
                                        case 4:
                                            echo '<span style="background-color: #0072bc !important; font-weight: bold !important; color: white !important; padding: 5px 10px 5px 10px; border-radius: 10px !important;">Ocupado</span>';
                                            break;
                                        default:
                                            echo '<span style="background-color: #999 !important; font-weight: bold !important; color: white !important; padding: 5px 10px 5px 10px; border-radius: 10px !important;">No reconocido</span>';
                                    }
                                ?>
                            </td>
                            <td class="text-center">
                                
                                <div class="btn-group text-center">
                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Acción <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right text-center" role="menu">
                                        
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_admin_edit/<?php echo $row['admin_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                    <?php echo ('Editar');?>
                                                </a>
                                        </li>
                                        <!-- <li class="divider"></li> -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/admin/delete/<?php echo $row['admin_id'];?>');">
                                                <i class="entypo-trash"></i>
                                                    <?php echo ('Eliminar');?>
                                                </a>
                                        </li>
                                    </ul>
                                </div>
                                
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_export").dataTable({
			"sPaginationType": "bootstrap",
            "oLanguage": {
                "oAria": {
                    "sSortAscending": ": activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": activar para ordenar la columna de manera descendente"
                },
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "sEmptyTable": "No hay datos disponibles en la tabla",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
                "sInfoFiltered": "(filtrado de _MAX_ entradas en total)",
                "sInfoPostFix": "",
                "sInfoThousands": ",",
                "sLengthMenu": "Mostrar _MENU_ entradas",
                "sLoadingRecords": "Cargando...",
                "sProcessing": "Procesando...",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sZeroRecords": "No se encontraron registros coincidentes"
            },
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [
					
					// {
					// 	"sExtends": "xls",
					// 	"mColumns": [0, 2, 3, 4]
					// },
					// {
					// 	"sExtends": "pdf",
					// 	"mColumns": [0, 2, 3, 4]
					// },
					{
						"sExtends": "print",
                        "sButtonText": '<i class="entypo-print"></i> Imprimir',
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(1, false);
							datatable.fnSetColumnVis(5, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(1, true);
									  datatable.fnSetColumnVis(5, true);
								  }
							});
						},
						
					},
				]
			},
			
		});

        <?php 
            $query = $this->db->get_where('section' , array('class_id' => $class_id));
            if ($query->num_rows() > 0):
                $sections = $query->result_array();
                foreach ($sections as $row):
        ?>
            var datatable_<?php echo $row['section_id'];?> = $("#table_export_<?php echo $row['section_id'];?>").dataTable({
                "sPaginationType": "bootstrap",
            "oLanguage": {
                "oAria": {
                    "sSortAscending": ": activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": activar para ordenar la columna de manera descendente"
                },
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "sEmptyTable": "No hay datos disponibles en la tabla",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
                "sInfoFiltered": "(filtrado de _MAX_ entradas en total)",
                "sInfoPostFix": "",
                "sInfoThousands": ",",
                "sLengthMenu": "Mostrar _MENU_ entradas",
                "sLoadingRecords": "Cargando...",
                "sProcessing": "Procesando...",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sZeroRecords": "No se encontraron registros coincidentes"
            },
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [
					
					// {
					// 	"sExtends": "xls",
					// 	"mColumns": [0, 2, 3, 4]
					// },
					// {
					// 	"sExtends": "pdf",
					// 	"mColumns": [0, 2, 3, 4]
					// },
					{
						"sExtends": "print",
                        "sButtonText": '<i class="entypo-print"></i> Imprimir',
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(1, false);
							datatable.fnSetColumnVis(5, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(1, true);
									  datatable.fnSetColumnVis(5, true);
								  }
							});
						},
						
					},
				]
			},
            });
        <?php endforeach;?>
        <?php endif;?>

		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});

	});

    jQuery(document).ready(function($) {
        $('.DTTT_button_print').addClass('btn-info');
    });



		
</script>


<style>
  
    .form-control {
        background-color: #ebebeb !important;
    }
    
    a[data-toggle="tab"] i {
        color: black !important;
    }

    .active a[data-toggle="tab"] i {
        color: #265044 !important;
    }

    .menuIcon {
        color: black;
    }
    .btn-group {
        text-align: center !important;
        align-items: center !important;
    }

    .nav-tabs.bordered + .tab-content {
        border: 5px solid white !important;
        border-top: 0;
        -webkit-border-radius: 0 0 3px 3px;
        -webkit-background-clip: padding-box;
        -moz-border-radius: 0 0 3px 3px;
        -moz-background-clip: padding;
        border-radius: 0 0 3px 3px;
        background-clip: padding-box;
        padding: 10px 15px;
        margin-bottom: 20px;
    }

    .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
        border: 5px solid white !important;
        border-bottom-color: transparent !important;
    }

    .nav-tabs .active a {
        color: #265044 !important;
        font-weight: bolder !important;
    }

    .nav-tabs li a {
        color: black !important;
        font-weight: bold !important;
    }

    .dataTables_wrapper {
        color: #484848 !important;
    }

    .dataTable thead tr th {
        color: #265044 !important;
        font-weight: bold !important;
    }

    .padded label {
        color: #265044 !important;
        font-weight: bold !important;
    }
    .even {
        background-color: white !important;
    }

    .btn-info {
        font-weight: bold !important;
    }

    .btn-group ul li a {
        background-color: #265044 !important;
        color: white !important;
        border-radius: 0px !important;
        border-bottom: 2px solid rgba(69, 74, 84, 0.4);
    }

    /* Estilo para cambiar el color de fondo en hover */
    .btn-group ul li a:hover {
        background-color: #A5B299 !important;
        border-radius: 0px !important;
    }

    .box-content {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        background-color: white !important;
    }

    .row th {
        background-color: #265044 !important;
    } .row th div {
        color: white !important;
        font-weight: 600 !important;
    }

    .dataTables_wrapper table thead tr th.sorting_asc:before,
    .dataTables_wrapper table thead tr th.sorting_desc:before {
    color: white !important;
    }

    .table tbody tr td {
        background-color: #fff !important;
    }  .table tbody tr:hover td {
        background-color: #f2f2f4 !important;
    }

    .nav-tabs li a:hover {
        background-color: #A5B299 !important;
    }  .nav-tabs li.active a:hover {
        background-color: #fff !important;
    }

    .tile-stats .icon {
        margin-bottom: 10px !important;
    }
    .tile-stats .icon i {
        font-size: 110px !important;
     
        margin-right: 0px !important;
        padding: 0px 90px 0px 10px;
    }

    .tile-stats {
        padding: 40px 0px 40px 0px!important;
    }

    .tile-stats .num, .sub-num {
        background-color: #A5B299;
    }
    .num {
        padding-left: 20px !important;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        z-index: 0 !important;
    }
    .sub-num {
        margin-top: -1px !important;
        padding-left: 20px !important;
        padding-bottom: 10px !important;
        border-bottom-right-radius: 5px;
        border-bottom-left-radius: 5px;
        z-index: 0 !important;
    }
</style>
