<?php
require '../../App/authentication.php';
include('../../Data/Requisicion.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Insumos</title>
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
                <a href="../General/ayudaGeneral.php">
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
    <script>
        function showVideo(id) {
            var videoHtml = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' + id + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            document.getElementById('videoContainer').innerHTML = videoHtml;
            document.getElementById('videoModal').style.display = 'block';
        }

        function hideVideo() {
            document.getElementById('videoModal').style.display = 'none';
            document.getElementById('videoContainer').innerHTML = '';
        }
    </script>

</body>

<?php
$api_key = "AIzaSyDkgs6lBULyToQucU2FmK0WdnsSLYd7dAo"; // Ingresar tu Api Key
$channel_id = "UCKiZ7pwwBc48t0UcSxJIRoA"; // El Id del canal
$max_results = "6"; // Resultados a mostrar

// LLamar a la API para obtener la lista de videos en JSON
$query = "https://www.googleapis.com/youtube/v3/search?key=$api_key&channelId=$channel_id&part=snippet,id&order=date&maxResults=" . $max_results;
$videoList = file_get_contents($query);


// Convertir el JSON a Array
$results = json_decode($videoList, true);

// Para debugear
// echo "<pre>";
// print_r($results); 
// echo "</pre>";

// Recorrer los resultados
?>
<main class="mainE">
    <div class="containerF">
        <h1>Videos de Ayuda</h1>
    </div>
    <div class="videos-contenedor">
        <?php
        foreach ($results['items'] as $items) {
            $id = $items['id']['videoId']; //  Id del video
            $title = $items['snippet']['title']; // Titulo del video
            $description = $items['snippet']['description']; // Descripcion del video
            $published_at = $items['snippet']['publishedAt']; // Fecha de publicacion
            $channel_title = $items['snippet']['channelTitle']; // Titulo del canal
            $thumbnail = $items['snippet']['thumbnails']['default']['url']; // Imagen miniatura, 3 valores: default, medium, high
            ?>
            <div class="video-container" onclick="showVideo('<?php echo $id; ?>')">
                <img src="<?php echo $thumbnail; ?>">
                <h3><?php echo $title; ?></h3>
                <p><?php echo $description; ?></p>
                <!-- <p><i><?php echo $published_at; ?></i></p> -->
                <!-- <p>Por <b><?php echo $channel_title; ?></b></p> -->
            </div>
            <?php
        }
        ?>
    </div>

    <div id="videoModal" style="display:none;">
        <div style="position:fixed;top:50%;left:50%;transform:translate(-50%, -50%);z-index:1000;background-color:#fff;padding:20px;box-shadow:0 4px 8px rgba(0,0,0,.05);">
            <span onclick="hideVideo()" style="cursor:pointer;float:right;">&times;</span>
            <div id="videoContainer"></div>
        </div>
        <div style="position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5);z-index:999;"></div>
    </div>
</main>
</body>
