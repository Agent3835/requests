<?php
require '../../App/authentication.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Menu Supervisor</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../../CSS/normalize.css">
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
			<div id="name"><span>Supervisor</span></div>
		</div>
		<!---ITEMS-->
		<div id="menu-items-container">
			<div id="menu-items">
				<div class="item">
					<a href="../indexSupervisor.php">
						<div class="icon"><img src="../../img/homeImagen.png" alt=""></div>
						<div class="title"><span>Menú Principal</span></div>
					</a>
				</div>

				<div class="item">
					<a href="revisionSolicitudesSupervisor.php">
						<div class="icon"><img src="../../img/solicitudImagen.png" alt=""></div>
						<div class="title"><span>Revisar solicitudes</span></div>
					</a>
				</div>

				<div class="item separator"></div>

				<?php if ($_SESSION['departamento'] == '3') { ?>
					<div class="item">
						<a href="asignarTareasSupervisor.php">
							<div class="icon"><img src="../../img/tareasImagen.png" alt=""></div>
							<div class="title"><span>Asignar Tareas</span></div>
						</a>
					</div>
				<?php } ?>

				<div class="item separator"></div>
				<?php
				if ($_SESSION['category'] == '3' && $_SESSION['departamento'] == '3') { ?> <!--PRUEBAAAA-->
					<div class="item">
						<a href="historialSolicitudesTec.php">
							<div class="icon"><img src="../../img/soliTecnicasMant.png" alt=""></div>
							<div class="title"><span>Almacén</span></div>
						</a>
					</div>
				<?php } ?>

				<div class="item">
					<a href="notificacionesSupervisor.php">
						<div class="icon"><img src="../../img/notificacionesImagen.png" alt=""></div>
						<div class="title"><span>Notificaciones</span></div>
					</a>
				</div>

				<div class="item separator"></div>
				<div class="item">
					<a href="ayudaSupervisor.php">
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
	</div>
	<header>
		<div class="containerF">
			<h1>Solicitudes aprobadas</h1>
		</div>
		<br>
		<input type="button" value="Regresar" onclick="location.href='cataSoli.php'" class="cata">
		<br><br>
		<table class="containerT">
			<tr>
				<th>
					<h1>Solicitud</h1>
				</th>
				<th>
					<h1>Usuario</h1>
				</th>
				<th>
					<h1>Fecha</h1>
				</th>
				<th>
					<h1>Descripción</h1>
				</th>
				<th>
					<h1>Comentario</h1>
				</th>
			</tr>
			<?php
			include("../../Data/Requisicion.php");
			$myObjetoSelect = new Requisicion();
			$dataset = $myObjetoSelect->getAprobadasSupervisor();
			if ($dataset == "error") {
				echo "hay un error en la consulta";
			} else {
				while ($tupla = mysqli_fetch_assoc($dataset)) {
			?>
					<tr>
						<td> <?php echo $tupla['idSolicitud']; ?> </td>
						<td><?php echo $myObjetoSelect->getNombreCompletoUsuario($tupla['idSolicitudUser']) . "</td>"; ?> </td>
						<td> <?php echo $tupla['fechaSolicitud']; ?></td>
						<td> <?php echo $tupla['justificacion']; ?></td>
						<td> <?php echo $tupla['comentario']; ?></td>
					</tr>
			<?php
				}
			}
			?>
		</table>
</body>

</html>