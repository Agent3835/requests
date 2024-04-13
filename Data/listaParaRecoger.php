<?php
if (isset($_GET['idSolicitud']) && isset($_GET['user_id'])) {
    $idSolicitud = $_GET['idSolicitud'];
    $user_id = $_GET['user_id'];
    $miobjeto = new Requisicion();
    $miobjeto->autorizarSolicitud($idSolicitud, $user_id); // Llamada al método autorizarSolicitud
    header('Location: ../View/Supervisor/revisionSolicitudesSupervisor.php');
    exit();
}

?>