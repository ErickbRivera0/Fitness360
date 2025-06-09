create database fitness;
use fitness;

CREATE TABLE Miembros(
IDMiembro INT auto_increment PRIMARY KEY,
NombreCompleto VARCHAR(255) NOT NULL,
CorreoElectronico  VARCHAR(255) unique not null,
NumeroTelefono VARCHAR(20),
FechaRegistro timestamp default current_timestamp,
Rol VARCHAR(20) DEFAULT 'usuario'
);

INSERT INTO  Miembros(NombreCompleto,CorreoElectronico,NumeroTelefono,FechaRegistro)
values('Juan Antonio Lopez','juanTony@gmail.com','99898988','2025-05-17'),
('Martha Alcia Valladares','marth_Ali@gmail.com','32445323','2025-02-13'),
('Andy Simon Oliva','Andy_simo@gmail.com','89876443','2025-02-10');
('Eric Bonilla','Erickbonilla@admin.com','89876443','2025-02-10');

CREATE TABLE Clases(
IDClase INT auto_increment PRIMARY key,
NombreClase VARCHAR(255) NOT NULL,
DescripcionClase TEXT,
Horario time NOT NULL,
Duracion INT NOT NULL,
Instructor VARCHAR(255),
CapacidadMaxima INT NOT NULL,
Nivel varchar(50),
UbicacionSala VARCHAR(100),
EsActiva boolean default TRUE
);

INSERT INTO Clases (NombreClase, DescripcionClase, Horario, Duracion, Instructor, CapacidadMaxima, Nivel, UbicacionSala, EsActiva)
VALUES
  ('Yoga para principiantes', 'Clase de yoga enfocada en posturas básicas y relajación.', '08:30:00', 60, 'Ana Gómez', 20, 'Principiante', 'Sala A', TRUE),
  ('Pilates intermedio', 'Fortalecimiento muscular y flexibilidad para nivel intermedio.', '10:00:00', 45, 'Carlos Pérez', 15, 'Intermedio', 'Sala B', TRUE),
  ('Cardio avanzado', 'Entrenamiento cardiovascular intenso para niveles avanzados.', '18:00:00', 50, 'María López', 25, 'Avanzado', 'Sala C', TRUE);

CREATE TABLE Reserva(
IDReserva INT auto_increment PRIMARY KEY,
IDMiembro INT NOT NULL,
IDClase INT NOT NULL,
FechaHoraReserva timestamp default current_timestamp,
EstadoReserva VARCHAR(50) default 'ACTIVA',
foreign key(IDMiembro) references Miembros(IDMiembro),
foreign key(IDClase)references Clases(IDClase)
);

INSERT INTO Reserva (IDMiembro, IDClase, FechaHoraReserva)
VALUES
  (1, 2, '2025-03-16 10:00:00'),
  (2, 1, '2025-05-18 15:30:00'),
  (3, 3, '2025-06-01 09:45:00');

CREATE TABLE Progreso(
IDProgreso INT auto_increment PRIMARY KEY,
IDMiembro INT NOT NULL,
TipoMetrica VARCHAR(100) NOT NULL,
Valor VARCHAR(255),
FechaRegistroProgreso DATE NOT NULL,
Notas TEXT,
foreign key(IDMiembro)references Miembros(IDMiembro)
);

CREATE TABLE Pagos(
IDPago INT auto_increment PRIMARY key,
IDMiembro INT NOT NULL,
DescripcionPago varchar(255) NOT NULL,
 Monto DECIMAL(10, 2) NOT NULL,
    FechaPago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    MetodoPago VARCHAR(50),
    EstadoPago VARCHAR(50),
    IDTransaccion VARCHAR(255),
    FechaInicioPeriodo DATE,
    FechaFinPeriodo DATE,
    FOREIGN KEY (IDMiembro) REFERENCES Miembros(IDMiembro)
);