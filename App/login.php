<?php
include('../Data/Usuario.php');

if (isset($_POST['submit'])) {
    $myUser = new Usuario();
    $myUser->setNick($_POST['txtUser']);
    $myUser->setContraseña($_POST['txtPassword']);

    if (isset($_POST['g-recaptcha-response'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $captcha = $_POST['g-recaptcha-response'];
        $secretkey = '6LdMvrYpAAAAACKe-Z1Rb7MohFdR8kwWwzzdYxlH';

        $respuesta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$captcha&remoteip=$ip");
        $atributos = json_decode($respuesta, TRUE);

        if (!$atributos['success']) {
            header('Location: ../View/formLogin.php?error=captcha');
            exit;
        }
    } 

    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    $dataset = $myUser->getUsuario();
    if ($dataset !== null) {
        $count = mysqli_num_rows($dataset);
        if ($count == 1) {
            session_start();
            $tupla = mysqli_fetch_assoc($dataset);
            $_SESSION['nick'] = $tupla['nickname'];
            $_SESSION['first_name'] = $tupla['first_name'];
            $_SESSION['last_name'] = $tupla['last_name'];
            $_SESSION['user_id'] =  $tupla['user_id'];
            $_SESSION['mail'] = $tupla['email'];
            $_SESSION['profile_pic'] = $tupla['profile_pic'];
            $_SESSION['numeroTel'] = $tupla['numTel'];
            $_SESSION['departamento'] = $tupla['idDepaUsuario'];
            $_SESSION['estado_user'] = $tupla['status'];

            $rol = $tupla['category'];
            $_SESSION['logged'] = true;
            $_SESSION['category'] = $rol;
            if ($_SESSION['estado_user'] == 'activo') {
                if ($rol == '1') {
                    header('Location: ../View/indexAdmin.php');
                } elseif ($rol == '4') {
                    header('Location: ../View/indexTech.php');
                } elseif ($rol == '2') {
                    header('Location: ../View/indexGeneral.php');
                } elseif ($rol == '3') {
                    header('Location: ../View/indexSupervisor.php');
                }
                require '../App/authentication.php';
            }
        }
    }
    if ((session_status() == PHP_SESSION_NONE) || ($_SESSION['estado_user'] == 'inactivo')) {
        header('Location: ../View/formLogin.php?error=error');
    } 
}
