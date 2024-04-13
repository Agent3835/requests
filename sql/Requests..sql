CREATE DATABASE IF NOT EXISTS `requests` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `requests`;

CREATE TABLE departamentos (
    idDepartamento INT AUTO_INCREMENT PRIMARY KEY,
    nomDepartamento VARCHAR (30) NOT NULL,
    descripcion VARCHAR (200) NOT NULL,
    numTel CHAR (15) NOT NULL UNIQUE
);

CREATE TABLE roles (
    idRol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR (20)
);

CREATE TABLE areas (
    idArea INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR (30) NOT NULL,
    ubicacion VARCHAR (200) NOT NULL,
    idDepartamento INT,
    FOREIGN KEY (idDepartamento) REFERENCES departamentos(idDepartamento)
);

CREATE TABLE categorias (
    idCategoria INT AUTO_INCREMENT PRIMARY KEY,
    nomCategoria VARCHAR (30) NOT NULL,
    idCategoriaDepa INT,
    FOREIGN KEY (idCategoriaDepa) REFERENCES departamentos(idDepartamento)
);
CREATE TABLE usuarios (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    email VARCHAR(50),
    password VARCHAR(20) NOT NULL, 
    numTel VARCHAR(15) NOT NULL UNIQUE,
    category INT,
    nickname VARCHAR (20) NOT NULL,
    status VARCHAR (20) DEFAULT "Pendiente",
    profile_pic VARCHAR(200),
    fechaRegistro DATETIME NOT NULL,
    cantidadTrabajosAsignados INT DEFAULT 0,
    idDepaUsuario INT,
    FOREIGN KEY (category) REFERENCES roles(idRol),
    FOREIGN KEY (idDepaUsuario) REFERENCES departamentos(idDepartamento)
);
CREATE TABLE tipoSolicitado (
    idTipo INT AUTO_INCREMENT PRIMARY KEY,
    nomTipo VARCHAR (60) NOT NULL,
    descripcion VARCHAR (250) NOT NULL
);

CREATE TABLE productos (
    idProducto INT AUTO_INCREMENT PRIMARY KEY,
    nomProducto VARCHAR (60) NOT NULL,
    descripcion VARCHAR (250) NOT NULL,
    tipoEntrega varchar(20) not null,
    idCatProducto INT,
    idDepaProducto INT,
    idTipoProducto INT,
    FOREIGN KEY (idTipoProducto) REFERENCES tipoSolicitado(idTipo),
    FOREIGN KEY (idCatProducto) REFERENCES categorias(idCategoria),
    FOREIGN KEY (idDepaProducto) REFERENCES departamentos(idDepartamento)
);

CREATE TABLE prioridades (
    idPrioridad INT AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(20)
);

CREATE TABLE solicitudes (
    idSolicitud INT AUTO_INCREMENT PRIMARY KEY,
    fechaSolicitud DATETIME NOT NULL,
    estadoSolicitud VARCHAR(30) NOT NULL,
    justificacion VARCHAR (150) NOT NULL,
    comentario VARCHAR(250),
    comentarioDenegado  VARCHAR(250),
    cantidad INT,
    idSolicitudUser INT,
    idSolicitudProducto INT,
    idPrioridad INT,
    estadoEntrega varchar(20) not null,
    FOREIGN KEY (idPrioridad) REFERENCES prioridades (idPrioridad),
    FOREIGN KEY (idSolicitudUser) REFERENCES usuarios(user_id),
    FOREIGN KEY (idSolicitudProducto) REFERENCES productos(idProducto)
);

CREATE TABLE solicitudes_tec(
    idSolicitudesTec INT AUTO_INCREMENT PRIMARY KEY,
    fechaSolicitud DATETIME NOT NULL,
    fechaResolucion DATETIME,
    fechFin DATETIME,
    estado VARCHAR (20) DEFAULT "Pendiente", 
    descripcion VARCHAR (200) NOT NULL,
    evidencia VARCHAR (100),
    ubicacion INT,
    idUserSolicitudTec INT,
    idTecnicoAsignado INT,
    idPrioridad int, 
    comentarioDeTecnico VARCHAR (200) DEFAULT "Sin comentarios", 
    foreign key (idPrioridad) references prioridades (idPrioridad),
    FOREIGN KEY (ubicacion) REFERENCES areas (idArea),
    FOREIGN KEY (idUserSolicitudTec) REFERENCES usuarios (user_id),
    FOREIGN key (idTecnicoAsignado) REFERENCES usuarios (user_id)
);

CREATE TABLE solicitudesTec_usuarios (
    idSolicitudTec INT,
    user_idSolTec INT,
    PRIMARY KEY (idSolicitudTec, user_idSolTec),
    FOREIGN KEY (idSolicitudTec) REFERENCES solicitudes_tec(idSolicitudesTec),
    FOREIGN KEY (user_idSolTec) REFERENCES usuarios(user_id)
);

CREATE VIEW vista_solicitudes_usuario AS
SELECT
    u.user_id AS id_Usuario,
    u.first_name AS nombre_usuario,
    u.last_name AS apellido_usuario,
    d.nomDepartamento AS departamento,
    p.nomProducto AS producto_solicitado,
    s.idSolicitud AS id_Solicitud,
    s.fechaSolicitud AS fecha,
    s.estadoSolicitud AS estado,
    s.justificacion AS justificacion,
    s.comentario AS comentario,
    s.comentarioDenegado AS comentarioDenegado,
    s.cantidad AS cantidad_productos
FROM
    solicitudes s
JOIN
    usuarios u ON s.idSolicitudUser = u.user_id
JOIN
    productos p ON s.idSolicitudProducto = p.idProducto
JOIN
    departamentos d ON u.idDepaUsuario = d.idDepartamento;

CREATE VIEW vista_filtrada_solicitudes_tec AS
SELECT 
    st.idSolicitudesTec,
    st.fechaSolicitud,
    st.estado,
    st.descripcion,
    st.evidencia,
    st.ubicacion,
    st.idUserSolicitudTec,
    st.idTecnicoAsignado,
    st.idPrioridad,
    st.comentarioDeTecnico
FROM 
    solicitudes_tec st
ORDER BY 
    CASE st.idPrioridad
        WHEN 1 THEN 1  
        WHEN 2 THEN 2  
        WHEN 3 THEN 3  
        ELSE 4         
    END;

CREATE VIEW vista_filtrada_por_fecha_actual AS
SELECT 
    st.idSolicitudesTec,
    st.fechaSolicitud,
    st.estado,
    st.descripcion,
    st.evidencia,
    st.ubicacion,
    st.idUserSolicitudTec,
    st.idTecnicoAsignado,
    st.idPrioridad
FROM 
    solicitudes_tec st
WHERE
    st.fechaSolicitud >= CURDATE() 
ORDER BY
    st.fechaSolicitud DESC; 

DELIMITER //

CREATE TRIGGER AntesDeInsertarSolicitud
BEFORE INSERT ON solicitudes FOR EACH ROW
BEGIN
   SET NEW.fechaSolicitud = CURRENT_TIMESTAMP;
END;

//
DELIMITER ;
DELIMITER //

CREATE TRIGGER AntesDeInsertarSolicitudTec
BEFORE INSERT ON solicitudes_Tec FOR EACH ROW
BEGIN
   SET NEW.fechaSolicitud = CURRENT_TIMESTAMP;
END;

//
DELIMITER ;

DELIMITER //

CREATE TRIGGER registroFechaUsuario
BEFORE INSERT ON usuarios FOR EACH ROW
BEGIN
   SET NEW.fechaRegistro = CURRENT_TIMESTAMP;
END;

//
DELIMITER ;


INSERT INTO departamentos (idDepartamento, nomDepartamento, descripcion, numTel) VALUES
(1, 'Recursos Humanos', 'Departamento de Recursos Humanos y gestión administrativa', '661-233-2323'),
(2, 'Almacén', 'Almacén encargado de la gestión de material para producción e insumos para el funcionamiento', '661-332-3232'),
(3, 'Mantenimiento', 'Departamento encargado del mantenimiento de instalaciones', '661-112-4555');

INSERT INTO roles (nombre) VALUES
('Admin'),
('Empleado General'),
('Supervisor'),
('Tecnico');


INSERT INTO categorias (idCategoria, nomCategoria, idCategoriaDepa) VALUES
(1, 'Documentos', 1),
(2, 'Interpersonal', 1),
(3, 'Suministros', 2),
(4, 'Mantenimiento', 3);

INSERT INTO `usuarios` (`user_id`, `first_name`, `last_name`, `email`, `password`, `numTel`, `category`, `nickname`, `status`, `profile_pic`, `fechaRegistro`, `cantidadTrabajosAsignados`, `idDepaUsuario`) VALUES
(1, 'Fabian', 'Mendoza', 'fab@gmail.com', '123', '664-123-4455', 1, 'fabi', 'activo', 'admin.png', '2023-11-23 11:42:12', 0, 1),
(2, 'Luis', 'Mendoza', 'luis@gmail.com', '123', '664-111-2222', 2, 'luis', 'activo', 'usuario.png', '2023-11-23 11:42:12', 0, 1),
(3, 'Sisac', 'Orrantia', 'sisac@gmail.com', '123', '664-222-3333', 4, 'sog', 'activo', 'tecnico.png', '2023-11-23 11:42:12', 3, 3),
(4, 'Emmanuel', 'Cruz', 'cruz@gmail.com', '123', '663-123-3322', 3, 'emma', 'activo', 'supervisor.png', '2023-11-23 11:42:12', 0, 2),
(5, 'Perdomo', 'Garcia', 'perdomo@gmail.com', '123', '123-321-2323', 3, 'perdo1', 'activo', 'supervisor.png', '2023-11-23 11:46:32', 0, 3),
(6, 'Efrain', 'Leiva', 'efra@gmail.com', '123', '133-213-4412', 2, 'efra1', 'activo', 'usuario.png', '2023-11-23 11:47:38', 0, 2),
(7, 'Ernesto', 'Valenzuela', 'ernesto@gmail.com', '123', '661-236-6691', 3, 'ernesto1', 'activo', 'supervisor.png', '2023-11-23 11:48:44', 0, 1),
(8, 'Emmanuel', 'Rodriguez', 'emma@gmail.com', '123', '123-322-6778', 4, 'emma1', 'activo', 'tecnico.png', '2023-11-23 11:56:42', 0, 3),
(9, 'Faustino', 'Lopez', 'faustino@gmail.com', '123', '789-584-8648', 4, 'faus1', 'activo', 'tecnico.png', '2023-11-23 11:59:40', 0, 3),
(10, 'Camilo', 'Hernandez', 'camilo@gmail.com', '123', '111-222-2332', 4, 'camilo1', 'activo', 'tecnico.png', '2023-11-23 12:00:34', 1, 3);

INSERT INTO productos (idProducto, nomProducto, descripcion, tipoEntrega, idCatProducto, idDepaProducto) VALUES
(1, 'Pizarra Electrica', 'Una pizarra digital para escribir', 'Retornable', 3, 2),
(2, 'Teclado PC', 'equipo de informatica basica', 'No retornable', 3, 2),
(3, 'Botiquín', 'productos de aseo personal e higiene','No retornable',  3, 2),
(4, 'Llave inglesa', 'equipo para la reparacion y mantenimiento de estaciones de trabajo','Retornable', 3, 2),
(5, 'Lentes de Protección', 'materiales para produccion','No retornable',  2, 2),
(6, 'Casco', 'casco de proteccion', 'No retornable', 3, 2),
(7, 'Ratón PC', 'Solicita un equipo informatico','No retornable', 3, 2),
(8, 'Solicitud de beneficios medicos', 'Generar un documento oara conocer los beneficios medicos, aseguranza y descuentos.','No retornable', 1, 1),
(9, 'Inscripcion en cursos de formacion y desarrollo', 'Solicitar capacitacion en algun curso ofertado o por ofertar.', 'No retornable',1, 1),
(10, 'Carta de Recomendación', 'Solicitar dias inhabiles por parte del empleado o para solicitar/organizar vacasiones','No retornable', 1, 1),
(11, 'Solicitud de certificado de trabajo', 'Generar una constancia de que el empleado en cuestion esta trabajando actualmente en la empresa.', 'No retornable',1, 1),
(12, 'Cambios en la informacion personal o bancaria', 'Solicitud de edicion de informacion bancaria o actualizacion de informacion relevante perteneciente al empleado dentro de la empresa.', 'No retornable',1, 1),
(13, 'Reporte de conflictos laborales o ambiente de trabajo', 'Solicitud para informar acerca de un prroblema dentro del ambiente laboral de la empresa relacionado a el personal.','No retornable', 1, 1),
(14, 'Solicitud de voletin informativo', 'una solicitud para conocer todos los anuncios por parte de la empresa.','No retornable', 1, 1);

INSERT INTO areas (nombre,ubicacion,idDepartamento) values
('Area 1 - 2P','Segunda Planta',1),
('Area 1 - 1P','Primera Planta',2),
('Area 1 - 3P','Tercera Planta',3),
('Area 2 - 2P','Segunda Planta',1),
('Area 2 - 1P','Primera Planta',2),
('Area 2 - 3P','Tercera Planta',3),
('Area 3 - 2P','Segunda Planta',1),
('Area 3 - 1P','Primera Planta',2),
('Area 3 - 3P','Tercera Planta',3);

INSERT INTO prioridades (nombre) values
('alta'),
('media'),
('baja');

INSERT INTO tipoSolicitado (nomTipo) values
('Producto'),
('Servicio');

CREATE table notificaciones (
    idNotificacion INT AUTO_INCREMENT PRIMARY KEY,
    asunto VARCHAR(500) not null,
    estado VARCHAR(30) not null,
    fecha DATETIME,
    idRemitente INT,
    idDestinatario INT,
    idSolicitud INT,
    idSolicitudesTec INT,
    FOREIGN KEY (idRemitente) REFERENCES usuarios(user_id),
    FOREIGN KEY (idSolicitud) REFERENCES solicitudes(idSolicitud),
    FOREIGN KEY (idSolicitudesTec) REFERENCES solicitudes_tec(idSolicitudesTec),
    FOREIGN KEY (idDestinatario) REFERENCES usuarios(user_id)
);

DELIMITER //

CREATE TRIGGER AntesInsertarNotiEstado
BEFORE INSERT ON notificaciones FOR EACH ROW
BEGIN
   SET NEW.estado = "No leído";
   SET NEW.fecha = CURRENT_TIMESTAMP;
END;

//
DELIMITER ;

DELIMITER //

CREATE TRIGGER horaFinTecnico
BEFORE INSERT ON notificaciones FOR EACH ROW
BEGIN
   SET NEW.estado = "No leído";
   SET NEW.fecha = CURRENT_TIMESTAMP;
END;

//
DELIMITER ;
INSERT INTO `solicitudes` (`idSolicitud`, `fechaSolicitud`, `estadoSolicitud`, `justificacion`, `comentario`, `comentarioDenegado`, `cantidad`, `idSolicitudUser`, `idSolicitudProducto`, `idPrioridad`, `estadoEntrega`) VALUES
(1, '2023-11-29 10:50:56', 'Pendiente', 'Es para un reunion', '', NULL, 1, 2, 1, 1, ''),
(2, '2023-11-29 10:51:28', 'Pendiente', 'Lo necesito para la linea de producción', '', NULL, 1, 2, 5, 2, ''),
(3, '2023-11-29 10:52:10', 'Pendiente', 'El de la oficina no funciona más', 'Ya lo tiré.', NULL, 1, 2, 2, 2, ''),
(4, '2023-11-29 10:53:54', 'Pendiente', 'Saldré a un trabajo a la zona de contrucción', 'Tambien uno para llevarme a casa', NULL, 2, 2, 6, 2, ''),
(5, '2023-11-29 10:55:12', 'Pendiente', 'El mio no serve más.', 'uno con cable largo.', NULL, 5, 2, 7, 3, ''),
(6, '2023-11-29 10:55:53', 'Pendiente', 'Buscare un segundo trabajao.', 'Cuanto antes, por favor.', NULL, NULL, 2, 10, 1, ''),
(7, '2023-11-29 10:56:23', 'Pendiente', 'Me preocupa mi salud.', 'Para el fin de semana, por favor.', NULL, NULL, 2, 8, 2, ''),
(8, '2023-11-29 11:00:06', 'Pendiente', 'Quiero informar que cumplo años.', 'Me gustarian muchos regalos.', NULL, NULL, 2, 14, 1, ''),
(9, '2023-11-29 11:01:13', 'Pendiente', 'Quiero otra cuenta para fiscalizar.', '', NULL, NULL, 2, 12, 2, ''),
(10, '2023-11-29 11:19:07', 'Pendiente', 'Necesito apretar un tornillo', '', NULL, 1, 6, 4, 2, ''),
(11, '2023-11-29 11:20:42', 'Pendiente', 'Necesito presentar unas ideas a la mesa directiva.', 'Es para hoy.', NULL, 3, 6, 1, 1, ''),
(12, '2023-11-29 11:21:12', 'Pendiente', 'Me preocupa mi salid.', 'Mi salud peligra', NULL, NULL, 6, 8, 1, ''),
(13, '2023-11-29 11:22:35', 'Pendiente', 'Me duelen los ojos cuando trabajo en la linea.', 'los segundos son de repuesto.', NULL, 2, 6, 5, 1, ''),
(14, '2023-11-29 11:23:22', 'Pendiente', 'El mio ya no hace clic', 'el segundo es para mi casa, porque tampoco sirve.', NULL, 2, 6, 7, 1, ''),
(15, '2023-11-29 11:24:26', 'Pendiente', 'Me corté.', '', NULL, 1, 6, 3, 1, ''),
(16, '2023-11-29 11:25:14', 'Pendiente', 'Buscare otro trabajo.', 'No me gusta aqui.', NULL, NULL, 6, 10, 2, ''),
(17, '2023-11-29 11:25:47', 'Pendiente', 'Quiero mejorar mis habilidades administrativas.', 'Quiero mejorar.', NULL, NULL, 6, 9, 1, '');

INSERT INTO `solicitudes_tec` (`idSolicitudesTec`, `fechaSolicitud`, `fechaResolucion`, `fechFin`, `estado`, `descripcion`, `evidencia`, `ubicacion`, `idUserSolicitudTec`, `idTecnicoAsignado`, `idPrioridad`, `comentarioDeTecnico`) VALUES
(1, '2023-11-29 11:12:20', NULL, NULL, 'Pendiente', 'Se descompuso la impresora.', '11-29-23_impresora1.jpeg', 4, 2, NULL, 1, 'Sin comentarios'),
(2, '2023-11-29 11:12:55', NULL, NULL, 'Pendiente', 'Se descompuso la maqina de golosinas.', '11-29-23_maquina6.jpeg', 1, 2, NULL, 1, 'Sin comentarios'),
(3, '2023-11-29 11:15:53', NULL, NULL, 'Pendiente', 'la pc no enciende', '11-29-23_teclado.jpg', 1, 2, NULL, 1, 'Sin comentarios'),
(4, '2023-11-29 11:16:35', NULL, NULL, 'Pendiente', 'La maquina tiene sonidos extraños', '11-29-23_maquina4.jpeg', 1, 2, NULL, 1, 'Sin comentarios'),
(5, '2023-11-29 11:17:22', NULL, NULL, 'Pendiente', 'Escucho un sonido extraño proveniente del interior de la maquina.', '11-29-23_maquina3.jpeg', 1, 2, NULL, 1, 'Sin comentarios'),
(6, '2023-11-29 11:26:30', NULL, NULL, 'Pendiente', 'Estoy en almacén y la maquina #15 ya no arranca. ayer todo estaba bien.', '11-29-23_maquina2.jpeg', 2, 6, NULL, 1, 'Sin comentarios'),
(7, '2023-11-29 11:27:09', NULL, NULL, 'Pendiente', 'La silla esta rota desde ayer.', '11-29-23_sillaDescompuesta.jpeg', 2, 6, NULL, 1, 'Sin comentarios'),
(8, '2023-11-29 11:27:42', NULL, NULL, 'Pendiente', 'La PC no quiso encender.', '11-29-23_maquina5.jpeg', 2, 6, NULL, 1, 'Sin comentarios'),
(9, '2023-11-29 11:29:01', NULL, NULL, 'Pendiente', 'En almacén la maquina #4 está presentando sonidos extraños.', '11-29-23_maquina1.jpeg', 8, 6, NULL, 1, 'Sin comentarios'),
(10, '2023-11-29 11:29:54', NULL, NULL, 'Pendiente', 'La PC de almacén se apagó y no quiso encender más.', '11-29-23_teclado.jpg', 5, 6, NULL, 1, 'Sin comentarios');


INSERT INTO `notificaciones` (`idNotificacion`, `asunto`, `estado`, `fecha`, `idRemitente`, `idDestinatario`, `idSolicitud`, `idSolicitudesTec`) VALUES
(1, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 10:50:56', 2, 4, NULL, NULL),
(2, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 10:51:28', 2, 4, NULL, NULL),
(3, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 10:52:10', 2, 4, NULL, NULL),
(4, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 10:53:54', 2, 4, NULL, NULL),
(5, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 10:55:12', 2, 4, NULL, NULL),
(6, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 10:55:53', 2, 7, NULL, NULL),
(7, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 10:56:23', 2, 7, NULL, NULL),
(8, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 11:00:06', 2, 7, NULL, NULL),
(9, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 11:01:13', 2, 7, NULL, NULL),
(10, 'Mantenimiento Pendiente de Aprobación', 'No leído', '2023-11-29 11:12:20', 2, 5, NULL, NULL),
(11, 'Mantenimiento Pendiente de Aprobación', 'No leído', '2023-11-29 11:12:55', 2, 5, NULL, NULL),
(12, 'Mantenimiento Pendiente de Aprobación', 'No leído', '2023-11-29 11:15:53', 2, 5, NULL, NULL),
(13, 'Mantenimiento Pendiente de Aprobación', 'No leído', '2023-11-29 11:16:36', 2, 5, NULL, NULL),
(14, 'Mantenimiento Pendiente de Aprobación', 'No leído', '2023-11-29 11:17:22', 2, 5, NULL, NULL),
(15, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 11:19:07', 6, 4, NULL, NULL),
(16, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 11:20:42', 6, 4, NULL, NULL),
(17, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 11:21:12', 6, 7, NULL, NULL),
(18, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 11:22:35', 6, 4, NULL, NULL),
(19, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 11:23:22', 6, 4, NULL, NULL),
(20, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 11:24:26', 6, 4, NULL, NULL),
(21, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 11:25:14', 6, 7, NULL, NULL),
(22, 'Solicitud Pendiente de Revisión', 'No leído', '2023-11-29 11:25:47', 6, 7, NULL, NULL),
(23, 'Mantenimiento Pendiente de Aprobación', 'No leído', '2023-11-29 11:26:30', 6, 5, NULL, NULL),
(24, 'Mantenimiento Pendiente de Aprobación', 'No leído', '2023-11-29 11:27:09', 6, 5, NULL, NULL),
(25, 'Mantenimiento Pendiente de Aprobación', 'No leído', '2023-11-29 11:27:42', 6, 5, NULL, NULL),
(26, 'Mantenimiento Pendiente de Aprobación', 'No leído', '2023-11-29 11:29:01', 6, 5, NULL, NULL),
(27, 'Mantenimiento Pendiente de Aprobación', 'No leído', '2023-11-29 11:29:54', 6, 5, NULL, NULL);
