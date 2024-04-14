<?php
include('Requisicion.php');

if (isset($_GET['idSolicitud']) && isset($_GET['idSolicitudUser'])) {
    $miobjeto = new Requisicion();
    $idSolicitud = $_GET['idSolicitud'];
    $id_user = $_GET['idSolicitudUser'];
    echo $idSolicitud;
    echo $id_user;
    $miobjeto->autorizarSolicitudUsuario($_GET['idSolicitud'], $_GET['idSolicitudUser']); // Llamada al método autorizarSolicitud
    header('Location: ../View/General/solicitudesEnCurso.php');
    exit();
}
