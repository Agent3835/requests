<?php
include ('Requisicion.php');
$miobjeto = new Requisicion();
//verificacion de que el $POST esta siendo recibida
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    exit('Invalid request method.');
}
$miobjeto->setFecha_Solicitud($_POST['fechaActual']);
$miobjeto->setDepartamento($_POST['numDepartamento']); 
$miobjeto->setPrioridad($_POST['listPrioridades']);
$miobjeto->setJustificacion($_POST['txtJustificacion']);
$miobjeto->setComentario($_POST['txtComentario']);
$miobjeto->setIdSolicitudProducto($_POST['listOpciones']);
$miobjeto->setIdSolicitudUser($_POST['numUser']);
$miobjeto->setRequisicionDocs();

header('Location: ../View/indexGeneral.php');
?>