<?php
require '../../App/authentication.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Menu Administrador</title>
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
			<div id="name"><span>Administrador</span></div>
		</div>
		<!---ITEMS-->
		<div id="menu-items">
			<div class="item">
				<a href="../indexAdmin.php">
					<div class="icon"><img src="../../img//homeImagen.png" alt=""></div>
					<div class="title"><span>Menu Principal</span></div>
				</a>
			</div>

			<div class="item">
				<a href="gestionUsuarios.php">
					<div class="icon"><img src="../../img/gestionUsuarioImagen.png" alt=""></div>
					<div class="title"><span>Gestión de usuarios</span></div>
				</a>
			</div>

			<div class="item separator"></div>

			<div class="item">
				<a href="controlTecnico.php">
					<div class="icon"><img src="../../img/controlTecnicoImagen.png" alt=""></div>
					<div class="title"><span>Control tecnico</span></div>
				</a>
			</div>

			<div class="item separator"></div>

			<div class="item">
				<a href="solicitudes.php">
					<div class="icon"><img src="../../img/requisicionesImagen.png" alt=""></div>
					<div class="title"><span>Requisiciones</span></div>
				</a>
			</div>

			<!---
			<div class="item">
				<a href="adminCategorias.php">
					<div class="icon"><img src="../../img/administrarCategoriasImagen.png" alt=""></div>
					<div class="title"><span>Administrar categorias</span></div>
				</a>
			</div>
			---->

			<div class="item">
				<a href="catalogo.php">
					<div class="icon"><img src="../../img/catalogo.png" alt=""></div>
					<div class="title"><span>Catálogos</span></div>
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
			<h1>Control de Solicitudes Técnico</h1>
		</div>
		<?php include('SolTecnica.php');
		$miobjeto = new SolTecnica();
		$dataset = $miobjeto->getAllSolicitudTecAsJson();
		?>
		<div class="containerH">
			<h2>Solicitudes Técnicas</h2>
		</div>
		<table class="containerT">
			<thead>
				<tr>
					<th>
						<h1>Número de Solicitud</h1>
					</th>
					<th>
						<h1>Fecha de la Solicitud</h1>
					</th>
					<th>
						<h1>Estado de la Solicitud</h1>
					</th>
					<th>
						<h1>Descripción</h1>
					</th>
					<th>
						<h1>Evidencia</h1>
					</th>
					<th>
						<h1>Departamento Ubicado</h1>
					</th>
					<th>
						<h1>Usuario Solicitante</h1>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				// Decodificar el JSON para obtener un array asociativo
				$resultados = json_decode($dataset, true);

				// Verificar si hay resultados y recorrerlos para mostrarlos en la tabla
				if (!empty($resultados)) {
					foreach ($resultados as $tupla) {
						// Convertir el número de ubicación a un nombre legible
						switch ($tupla['ubicacion']) {
							case 1:
								$ubi = "Area 1";
								break;
							case 2:
								$ubi = "Area 2";
								break;
							case 3:
								$ubi = "Area 3";
								break;
							default:
								$ubi = "Area desconocida";
								break;
						}
				?>
						<tr>
							<td><?php echo $tupla['idSolicitudesTec']; ?></td>
							<td><?php echo $tupla['fechaSolicitud']; ?></td>
							<td><?php echo $tupla['estado']; ?></td>
							<td><?php echo $tupla['descripcion']; ?></td>
							<td><?php echo $tupla['evidencia']; ?></td>
							<td><?php echo $ubi; ?></td>
							<td><?php echo $miobjeto->getNombreCompletoUsuario($tupla['idUserSolicitudTec']); ?></td>
						</tr>
				<?php
					}
				} else {
					// Manejar el caso donde no hay resultados
					echo "<tr><td colspan='7'>No hay solicitudes técnicas disponibles.</td></tr>";
				}
				?>

			</tbody>
		</table>
	</main>
</body>

</html>