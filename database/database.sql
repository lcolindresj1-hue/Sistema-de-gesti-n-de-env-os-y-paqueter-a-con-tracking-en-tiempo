DROP DATABASE IF EXISTS sistema_envios;
CREATE DATABASE sistema_envios
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE sistema_envios;

CREATE TABLE rol (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    id_rol INT NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    estado VARCHAR(20) NOT NULL DEFAULT 'activo',
    FOREIGN KEY (id_rol) REFERENCES rol(id_rol)
);

CREATE TABLE estado (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre_estado VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    orden_estado INT NOT NULL,
    es_final TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE envio (
    id_envio INT AUTO_INCREMENT PRIMARY KEY,
    codigo_guia VARCHAR(40) NOT NULL UNIQUE,
    id_usuario_remitente INT NOT NULL,
    nombre_destinatario VARCHAR(100) NOT NULL,
    telefono_destinatario VARCHAR(20) NOT NULL,
    direccion_destinatario VARCHAR(255) NOT NULL,
    descripcion_paquete VARCHAR(255) NOT NULL,
    peso DECIMAL(10,2) NOT NULL,
    es_fragil TINYINT(1) NOT NULL DEFAULT 0,
    tipo_paquete VARCHAR(80),
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado_actual_id INT NOT NULL,
    observaciones VARCHAR(255),
    instrucciones_entrega VARCHAR(255),
    FOREIGN KEY (id_usuario_remitente) REFERENCES usuario(id_usuario),
    FOREIGN KEY (estado_actual_id) REFERENCES estado(id_estado)
);

CREATE TABLE historia_estado (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_envio INT NOT NULL,
    id_estado INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    comentario VARCHAR(255),
    FOREIGN KEY (id_envio) REFERENCES envio(id_envio),
    FOREIGN KEY (id_estado) REFERENCES estado(id_estado),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

INSERT INTO rol (id_rol, nombre) VALUES
(1, 'Administrador');

INSERT INTO usuario (
    id_usuario,
    id_rol,
    nombres,
    apellidos,
    correo,
    password_hash,
    telefono,
    direccion,
    estado
) VALUES (
    1,
    1,
    'Admin',
    'Sistema',
    'admin@sistema.com',
    '123456',
    '00000000',
    'Sistema',
    'activo'
);

INSERT INTO estado (
    id_estado,
    nombre_estado,
    descripcion,
    orden_estado,
    es_final
) VALUES
(1, 'Paquete registrado', 'El paquete fue registrado en el sistema', 1, 0),
(2, 'En oficina', 'El paquete se encuentra en oficina', 2, 0),
(3, 'En proceso de ruta', 'El paquete está siendo preparado para salir a ruta', 3, 0),
(4, 'En ruta', 'El paquete va camino al destino', 4, 0),
(5, 'Entregado a usuario', 'El paquete fue entregado al destinatario', 5, 1),
(6, 'En sede para recoger', 'El paquete está disponible para recoger', 5, 0),
(7, 'Entregado en sede', 'El usuario recogió el paquete en sede', 6, 1),
(8, 'Cancelado', 'El envío fue cancelado', 99, 1);

INSERT INTO envio (
    id_envio,
    codigo_guia,
    id_usuario_remitente,
    nombre_destinatario,
    telefono_destinatario,
    direccion_destinatario,
    descripcion_paquete,
    peso,
    es_fragil,
    tipo_paquete,
    estado_actual_id,
    observaciones,
    instrucciones_entrega
) VALUES (
    1,
    'ENV-TEST-001',
    1,
    'Cliente Prueba',
    '55555555',
    'Ciudad de Guatemala',
    'Caja de prueba',
    2.50,
    1,
    'Caja',
    1,
    'Registro de prueba',
    'Entregar en horario laboral'
);

INSERT INTO historia_estado (
    id_envio,
    id_estado,
    id_usuario,
    comentario
) VALUES (
    1,
    1,
    1,
    'Envío registrado en el sistema'
);
