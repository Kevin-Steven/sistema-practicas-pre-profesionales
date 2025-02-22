create database gestionPracticas;
use gestionPracticas;

-- REGISTRAR E INICIAR SESION
create table usuarios (
	id int auto_increment primary key,
	email VARCHAR(60) NOT NULL,
    contraseña VARCHAR(60) NOT NULL,
    rol VARCHAR(20) NOT NULL DEFAULT 'visitante',
	nombres VARCHAR(30) NOT NULL,
	carrera VARCHAR(25) NULL,
    nivel VARCHAR(15)  NULL,
    apellidos VARCHAR(30) NOT NULL,
    estado VARCHAR(20) NULL,
    entidad VARCHAR(50) NULL,
    pertenece VARCHAR(60) NULL
);


create table registrar_usuarios (
	id int auto_increment primary key,
	nombres VARCHAR(30) NOT NULL,
	apellidos VARCHAR(30) NOT NULL,
	cedula VARCHAR(10) NOT NULL,
	telefono VARCHAR(10) NOT NULL,
	email VARCHAR(60) NOT NULL,
    contraseña VARCHAR(60) NOT NULL,
    carrera VARCHAR(25) NULL,
    nivel VARCHAR(15) NULL,
    rol VARCHAR(50) NOT NULL DEFAULT 'visitante',
    estado VARCHAR(20) NULL,
	entidad VARCHAR(50) NULL,
    pertenece VARCHAR(60) NULL
);
-- REGISTRAR E INICIAR SESION


-- TABLAS MODULO ESTUDIANTE  --
create table informe_estudiante (
	id int auto_increment primary key,
    estudiante_id INT,
    titulo varchar(250) not null,
    descripcion text,
    fecha date not null,
    hora TIME,
    archivo varchar(100) not null,
    ruta varchar(250) not null,
    fecha_subida timestamp default current_timestamp,
    estadoHoras VARCHAR(20) DEFAULT 'pendiente',
    estadoVistoEntidad VARCHAR(20) DEFAULT 'pendiente',
    FOREIGN KEY (estudiante_id) REFERENCES registrar_usuarios(id)
);
-- TABLAS MODULO ESTUDINATE  --



-- TABLA CONVENIOS ENTIDAD
CREATE TABLE convenios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_empresa VARCHAR(100) NOT NULL,
    cupo_disponible INT NOT NULL,
    entidad_id INT,
    UNIQUE KEY (nombre_empresa),
    FOREIGN KEY (entidad_id) REFERENCES usuarios(id)
);

CREATE TABLE renovacion_convenio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    renovacion INT NOT NULL,
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE informes_entidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entidad_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    archivo_path VARCHAR(255) NOT NULL,
    fecha_informe DATE NOT NULL,
    estadoVistoGestor VARCHAR(20) DEFAULT 'pendiente',
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (entidad_id) REFERENCES usuarios(id)
);

-- TABLA CONVENIOS 

-- TABLA COORDINADOR
CREATE TABLE fechas_asignadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha_inicial DATE NOT NULL,
    fecha_final DATE NOT NULL,
    entidad_id INT NOT NULL,
    CONSTRAINT fk_entidad
	FOREIGN KEY (entidad_id) REFERENCES registrar_usuarios(id)
);

-- GESTOR DE PRACTICAS PREPROFESIONALES --
CREATE TABLE informes_gestor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(250) NOT NULL,
    fecha DATE NOT NULL,
    entidad_id INT NOT NULL,
    archivo_path VARCHAR(255) NOT NULL,
    estadoVistoEntidad VARCHAR(20) DEFAULT 'pendiente',
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (entidad_id) REFERENCES registrar_usuarios(id)
);

DELIMITER //

CREATE TRIGGER actualizar_usuarios_trigger AFTER UPDATE ON registrar_usuarios
FOR EACH ROW
BEGIN
    UPDATE usuarios
    SET 
        email = NEW.email,
        contraseña = NEW.contraseña,
        rol = NEW.rol,
        nombres = NEW.nombres,
        apellidos = NEW.apellidos,
        carrera = NEW.carrera,
        nivel = NEW.nivel,
        estado = NEW.estado,
        entidad = NEW.entidad,
        pertenece = NEW.pertenece
    WHERE id = NEW.id;
END;
//

DELIMITER ;

-- Crear un disparador después de la inserción en la tabla registrar_usuarios
DELIMITER //
CREATE TRIGGER copiar_datos_usuario AFTER INSERT ON registrar_usuarios
FOR EACH ROW
BEGIN
    INSERT INTO usuarios (email, contraseña, rol, nombres, apellidos, carrera, nivel, estado, entidad, pertenece)
    VALUES (NEW.email, NEW.contraseña, NEW.rol, NEW.nombres, NEW.apellidos, NEW.carrera, NEW.nivel, NEW.estado, NEW.entidad, NEW.pertenece)
    ON DUPLICATE KEY UPDATE
    email = NEW.email,
    contraseña = NEW.contraseña,
    rol = NEW.rol,
    nombres = NEW.nombres,
    apellidos = NEW.apellidos,
    carrera = NEW.carrera,
    nivel = NEW.nivel,
    estado = NEW.estado,
    entidad = NEW.entidad,
    pertenece = NEW.pertenece;
END//
DELIMITER ;
-- REGISTRAR E INICIAR SESION

-- Insertar un usuario con rol 'gestor'
INSERT INTO registrar_usuarios (nombres, apellidos, cedula, telefono, email, contraseña, rol)
VALUES ('Jorge', 'Plaza', '0958898933', '9876543210', 'gestor@gmail.com', '1', 'gestor');

-- Insertar un usuario con rol 'entidad'
INSERT INTO registrar_usuarios (nombres, apellidos, cedula, telefono, email, contraseña, rol)
VALUES ('Fundacion', 'Nikols', '0958898932', '0123456789', 'entidad@gmail.com', '1', 'entidad');

-- Insertar un usuario con rol 'administrador'
INSERT INTO registrar_usuarios (nombres, apellidos, cedula, telefono, email, contraseña, rol)
VALUES ('Luis', 'Gómez', '9876543210', '0321098765', 'coordinador@gmail.com', '1', 'coordinador');

-- Insertar un usuario con rol 'entidad'
INSERT INTO registrar_usuarios (nombres, apellidos, cedula, telefono, email, contraseña, rol)
VALUES ('GAD', 'Laurel', '0958898932', '0123456789', 'laurel@gmail.com', '1', 'entidad');

-- Insertar un usuario con rol 'entidad'
INSERT INTO registrar_usuarios (nombres, apellidos, cedula, telefono, email, contraseña, rol)
VALUES ('GAD', 'Daule', '0958898932', '0123456789', 'daule@gmail.com', '1', 'entidad');

-- Insertar visitante 
INSERT INTO registrar_usuarios (nombres, apellidos, cedula, telefono, email, contraseña, rol)
VALUES ('Joel', 'Delgado', '0900000000', '0923456000', 'joel@gmail.com', '1', 'visitante');

INSERT INTO registrar_usuarios (nombres, apellidos, cedula, telefono, email, contraseña, rol)
VALUES ('Gabriel', 'Alvarado', '0929399989', '0999456789', 'gabriel@gmail.com', '1', 'visitante');

INSERT INTO registrar_usuarios (nombres, apellidos, cedula, telefono, email, contraseña, rol)
VALUES ('Kevin', 'Barzola', '0958898911', '0923456780', 'kevin@gmail.com', '1', 'visitante');



