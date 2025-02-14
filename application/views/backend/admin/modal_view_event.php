<?php 
   $edit_data = $this->db->select('events.*, event_visibility.visible_to, event_visibility.visible_to_category , event_visibility.visible_to_id, event_visibility.event_visibility_id, event_visibility.visibility_for_creator, event_visibility.visible_edit, event_visibility.visible_delete')
   ->from('events')
   ->join('event_visibility', 'events.event_id = event_visibility.event_id', 'left')
   ->where('events.event_id', $param2)
   ->get()
   ->result_array();

foreach ($edit_data as $row):
?>

<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">
    <i class="entypo-doc-text-inv"></i><?php echo 'Ver evento'; ?>
    </h4>
</div>



<div class="modal-body" style="height: 600px; overflow:auto; background-color: #ebebeb;">

<div class="tab-pane box active" id="edit" style="padding: 5px; background-color: #fff;">
    <div class="box-content">
            <table class="table">
            <tr>
                <td style="width: 100%; border-top: 0px solid #ebebeb; border-bottom: 0px solid #ebebeb;"  class="text-center">
                    <i class="
                        <?php 
                            switch ($row['type']) {
                                case 'meeting':
                                    echo 'fa fa-users'; // Icono para reuniones
                                    break;
                                case 'extracurricular-activity':
                                    echo 'fa fa-child'; // Icono para actividades extracurriculares
                                    break;
                                case 'classes-lessons':
                                    echo 'fa fa-book'; // Icono para clases / lecciones
                                    break;
                                case 'assignments-exams':
                                    echo 'fa fa-pencil-square-o'; // Icono para trabajos / exámenes
                                    break;
                                case 'holidays-vacations':
                                    echo 'fa fa-suitcase'; // Icono para días festivos / vacaciones
                                    break;
                                case 'special-event':
                                    echo 'fa fa-star'; // Icono para eventos especiales
                                    break;
                                case 'tutoring-advising':
                                    echo 'fa fa-graduation-cap'; // Icono para tutorías / asesorías
                                    break;
                                case 'deadline':
                                    echo 'fa fa-clock-o'; // Icono para fechas límite
                                    break;
                                case 'excursions-trips':
                                    echo 'fa fa-bus'; // Icono para excursiones / salidas
                                    break;
                                default:
                                    echo 'fa fa-calendar'; // Icono por defecto
                            }
                        ?>" alt="Icon type" style="font-size: 40px;"></i>
                </td>
            </tr>


                <tr>
                    <td style="border-top: 0px solid #ebebeb; border-bottom: 3px solid #ebebeb;">
                        <h2 class="text-center"><?php echo ucfirst($row['title']);?></h2>
                    </td>
                </tr>
                <tr>
    <td style="border-top: 0px solid #ebebeb; border-bottom: 3px solid #ebebeb;">
        <h4 class="text-center">
            <?php
            // Configurar la localización a español
            setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish_Spain', 'es');

            // Verificar si la fecha está definida
            if (!is_null($row['date'])) {
                // Convertir la fecha al formato amigable
                $date = new DateTime($row['date']);
                echo "Fecha del evento: " . $date->format('d') . " de " . strftime('%B', $date->getTimestamp()) . " del " . $date->format('Y');
            } elseif (is_null($row['date']) && is_null($row['end'])) {
                // Si 'date' es null y 'end' también es null, mostrar 'start' con la hora
                $start = new DateTime($row['start']);
                echo "El " . $start->format('d') . " de " . strftime('%B', $start->getTimestamp()) . " del " . $start->format('Y') .
                     " a las " . $start->format('H:i:s') . " hs";
            } elseif (is_null($row['date']) && !is_null($row['end'])) {
                // Si 'date' es null pero 'end' no lo es
                $start = new DateTime($row['start']);
                $end = new DateTime($row['end']);
                
                // Verificar si start y end son el mismo día
                if ($start->format('Y-m-d') == $end->format('Y-m-d')) {
                    echo "El " . $start->format('d') . " de " . strftime('%B', $start->getTimestamp()) . " desde las " . 
                         $start->format('H:i') . " hs hasta las " . $end->format('H:i') . " hs";
                } else {
                    // Si tienen diferente día
                    echo "El " . $start->format('d') . " de " . strftime('%B', $start->getTimestamp()) . " a las " . 
                         $start->format('H:i') . " hs hasta el " . $end->format('d') . " de " . 
                         strftime('%B', $end->getTimestamp()) . " a las " . $end->format('H:i') . " hs";
                }
            }
            ?>
        </h4>
    </td>
</tr>


                <tr>
                    <td colspan="2" class="text-center">
                        </br>
                        <p><?php echo $row['body'];?></p>
                    </td>
                </tr>
            </table>
    </div>
</div>
</div>

<?php endforeach;?>
