<?php
require '../../App/authentication.php';
include('../../Data/Requisicion.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Solicitud de Mantenimiento</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../../CSS/normalize.css">
	<link rel="stylesheet" href="../../CSS/form.css">
	<link rel="stylesheet" href="../../CSS/sidemenu.css">
</head>

<body>
	<div id="sidemenu" class="menu-collapsed">
		<div id="header">
			<div id="title"><span>Menu</span></div>
			<div id="menu-btn">
				<div class="btn-hamburger"></div>
				<div class="btn-hamburger"></div>
				<div class="btn-hamburger"></div>
			</div>
		</div>
		<!-- PROFILE-->
		<div id="profile">
			<?php
			if (!isset($_SESSION['profile_pic'])) {
				echo '<div id="photo"><img src="../../img/profiles_pics/usuario.png" alt="Usuario"></div>';
			} else {
				echo '<div id="photo"><img src="../../img/profiles_pics/' . $_SESSION['profile_pic'] . '" alt="Usuario"></div>';
			}
			?>
			<div id="name"><span>Usuario</span></div>
		</div>
		<!---ITEMS-->
		<div id="menu-items">
			<div class="item separator"></div>
			<div class="item">
				<a href="../indexGeneral.php">
					<div class="icon"><img src="../../img/homeImagen.png" alt=""></div>
					<div class="title"><span>Menú Principal</span></div>
				</a>
			</div>

			<div class="item">
				<a href="generarSolicitudEmpleado.php">
					<div class="icon"><img src="../../img/generarSolicitudImagen.png" alt=""></div>
					<div class="title"><span></span>Generar Solicitud</div>
				</a>
			</div>

			<div class="item">
				<a href="historialSolicitudEmpleado.php">
					<div class="icon"><img src="../../img/historialSolicitudesImagen.png" alt=""></div>
					<div class="title"><span>Historial de Solicitudes</span></div>
				</a>
			</div>

			<div class="item">
				<a href="notificacionesUsuario.php">
					<div class="icon"><img src="../../img/notificacionesImagen.png" alt=""></div>
					<div class="title"><span>Notificaciones</span></div>
				</a>
			</div>

			<div class="item">
				<a href="miarea.php">
					<div class="icon"><img src="../../img/MiArea.png" alt=""></div>
					<div class="title"><span>Mi Area</span></div>
				</a>
			</div>


			<div class="item">
				<a href="miareaHistorial.php">
					<div class="icon"><img src="../../img/miAreaHistorial.png" alt=""></div>
					<div class="title"><span>Mi Area Historial</span></div>
				</a>
			</div>

			<div class="item">
				<a href="ayudaGeneral.php">
					<div class="icon"><img src="../../img/ayuda.png" alt=""></div>
					<div class="title"><span>Ayuda</span></div>
				</a>
			</div>

			<div class="item">
				<a href="../../App/logout.php">
					<div class="icon"><img src="../../img/logout.png" alt=""></div>
					<div class="title"><span>Cerrar Sesión</span></div>
				</a>
			</div>

			<script>
				const btn = document.querySelector('#menu-btn');
				const menu = document.querySelector('#sidemenu');
				btn.addEventListener('click', e => {
					menu.classList.toggle("menu-expanded");
					menu.classList.toggle("menu-collapsed");

					document.querySelector('body').classList.toggle('body-expanded');
				});
			</script>
		</div>
	</div>

	<main class="mainE">
		<div class="containerF">
			<h1>Solicitud de Mantenimiento</h1>
		</div>

		<!--Cuerpo de la página, boton de regreso-->
		<div class="req-selection">
			<div class="container">
				<form class="form" method="post" action="../../Data/addRequisicionTech.php" enctype="multipart/form-data">
					<label for="remitente">Remitente:</label>
					<?php
					echo '<input type="text" name="txtUsuario" value="' . $_SESSION['first_name'] . " " . $_SESSION['last_name'] . '" readonly class="readonly">';
					?>
					<br><label for="fecha">Fecha de Alta:</label>
					<?php
					// Fecha en formato 'YYYY-MM-DD' para la BD
					$fechaActual = date('Y-m-d');
					?>
					<input type="date" name="fechaActual" value="<?php echo $fechaActual; ?>" readonly class="readonly">

					<br><label for="departamento">Departamento:</label>
					<?php
					$myRequisicion = new Requisicion();
					$nomDepa = $myRequisicion->getNombreDepartamento($_SESSION['departamento']);
					echo '<input type="text" name="txtDepartamento" value="' . $nomDepa . '" readonly class="readonly">';
					?>
					<br>
					<label for="listUbicaciones">Ubicación:</label>
					<select name="listUbicaciones" id=opcionesUbicacion>
						<?php
						$dataset = $myRequisicion->getAllUbicaciones();
						while ($tupla = mysqli_fetch_assoc($dataset)) {
							echo "<option name = \"listUbicaciones\" value=\"" . $tupla['idArea'] . "\" title=\"" . $tupla['ubicacion'] . "\">" . $tupla['nombre'] . "</option>";
						}
						?>
					</select>
					<br><label for="listPrioridades">Nivel de prioridad:</label>
					<select name="listPrioridades" id=opcionesProducto>
						<?php
						$dataset = $myRequisicion->getAllPrioridades();
						while ($tupla = mysqli_fetch_assoc($dataset)) {
							echo "<option name = \"listPrioridades\" value=\"" . $tupla['idPrioridad'] . "\" title=\"" . $tupla['nombre'] . "\">" . $tupla['nombre'] . "</option>";
						}
						?>
					</select>
					<br>
					<label for="descripcion">Descripción:</label>
					<textarea id="descripcion" name="txtDescripcion" title="Motivo de la solicitud" maxlength="500" minlength="2" class="coments" placeholder="Describa el motivo de la siguiente solicitud" required></textarea>

					<!--<input type="file" name="evidencia" id="evidencia">-->
					<br><label for="evidencia">Evidencia:</label> <input type="file" accept="image/jpeg" name="evidencia" id="evidencia" required>
					<br>
					<?php
					echo '<input type="hidden" name="numUser" value="' .  $_SESSION['user_id'] . '" readonly class="readonly">';
					echo '<input type="hidden" name="numDepartamento" value="' .  $_SESSION['departamento'] . '" readonly class="readonly">';

					?>
					<input id='btnSubmit' type="submit" value="Enviar">
					<a href="../General/generarSolicitudEmpleado.php">
						<div class="go-to-button">Regresar</div>
					</a>
				</form>
			</div>
		</div>
</body>

</html>