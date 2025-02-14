
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_parent_add/');" 
                class="btn btn-primary pull-left">
                <i class="entypo-plus-circled"></i>
                <?php echo ('Añadir un nuevo padre');?>
                </a> 
                <br><br>
                <div class="tab-content">
                <table class="table table-bordered table-hover table-striped datatable" id="table_export">
                    <thead>
                        <tr>
                            <th style="color: white !important;">#</th>
                            <th><div><?php echo ('Nombre');?></div></th>
                            <th><div><?php echo ('Dni');?></div></th>
                            <th><div><?php echo ('Email');?></div></th>
                            <th><div><?php echo ('Celular');?></div></th>
                            <th><div><?php echo ('Teléfono fijo');?></div></th>
                            <th class="text-center"><div><?php echo ('Opciones');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $count = 1; 
                            $parents   =   $this->db->get('parent' )->result_array();
                            foreach($parents as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
                            <td><?php echo $row['name'];?></td>
                            <td><?php echo $row['dni'];?></td>
                            <td><?php echo $row['email'];?></td>
                            <td><?php echo $row['phone_cel'];?></td>
                            <td><?php echo $row['phone_fij'];?></td>
                            <td class="text-center">
                                
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Acción <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        
                                       <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_parent_profile/<?php echo $row['parent_id'];?>');">
                                                <i class="entypo-user"></i>
                                                    <?php echo ('Perfil');?>
                                            </a>
                                        </li>
                                        <!-- teacher EDITING LINK -->
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_parent_edit/<?php echo $row['parent_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                    <?php echo ('Editar');?>
                                                </a>
                                                        </li>
                                        <!-- <li class="divider"></li> -->
                                        
                                        <!-- teacher DELETION LINK -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/parent/delete/<?php echo $row['parent_id'];?>');">
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
                    //     "sExtends": "xls",
                    //     "mColumns": [1,2,3,4,5]
                    // },
                    // {
                    //     "sExtends": "pdf",
                    //     "mColumns": [1,2,3,4,5]
                    // },
                    {
                        "sExtends": "print",
                        "sButtonText": '<i class="entypo-print"></i> Imprimir',
                        "fnSetText"    : "Press 'esc' to return",
                        "fnClick": function (nButton, oConfig) {
                            datatable.fnSetColumnVis(5, false);
                            
                            this.fnPrint( true, oConfig );
                            
                            window.print();
                            
                            $(window).keyup(function(e) {
                                  if (e.which == 27) {
                                      datatable.fnSetColumnVis(5, true);
                                  }
                            });
                        },
                        
                    },
                ]
            },
            
        });
        
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

    .tab-content {
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
        font-weight: bold !important;
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

    .btn-group ul li a:hover {
        background-color: #A5B299 !important;
        border-radius: 0px !important;
    }

    .box-content {
        padding-top: 10px !important;
        background-color: white !important;
    }

    .table th {
        background-color: #265044 !important;
    } .table th div {
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

    .btn-primary {
        font-weight: 600 !important;
    }
</style>