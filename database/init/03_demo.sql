-- Datos de demostración (borrar este archivo para arrancar con la base limpia)
INSERT INTO usuarios (nombre, usuario, clave_hash, rol)
VALUES ('Recepción', 'recepcion', crypt('recepcion123', gen_salt('bf', 10)), 'recepcion');

INSERT INTO entrenadores (ci, nombre, apellido, telefono, email, especialidad) VALUES
    ('3456789', 'Carlos', 'Benítez', '0981 111 222', 'carlos@flix.test', 'Musculación'),
    ('4567890', 'Laura',  'Gómez',   '0982 333 444', 'laura@flix.test',  'Funcional y cardio');

INSERT INTO socios (ci, nombre, apellido, fecha_nacimiento, sexo, telefono, email, direccion, contacto_emergencia) VALUES
    ('1234567', 'Ana',    'Martínez', '1994-03-12', 'F', '0971 100 100', 'ana@ejemplo.com',    'Asunción', 'Pedro Martínez 0971 200 200'),
    ('2345678', 'Luis',   'Fernández','1988-07-30', 'M', '0972 100 100', 'luis@ejemplo.com',   'Luque',    NULL),
    ('5678901', 'Marta',  'Ortiz',    '2001-11-05', 'F', '0973 100 100', NULL,                 'Lambaré',  NULL),
    ('6789012', 'Diego',  'Romero',   '1979-01-22', 'M', '0974 100 100', 'diego@ejemplo.com',  'San Lorenzo', NULL),
    ('7890123', 'Sofía',  'Acosta',   '1999-09-09', 'F', '0975 100 100', NULL,                 'Asunción', NULL);

-- Ana: vigente y pagada. Luis: vigente con saldo. Marta: por vencer. Diego: vencida. Sofía: sin membresía.
INSERT INTO membresias (id_socio, id_plan, id_entrenador, fecha_inicio, fecha_fin, precio) VALUES
    (1, 2, 1, CURRENT_DATE - 10, CURRENT_DATE + 19, 150000),
    (2, 3, 2, CURRENT_DATE - 30, CURRENT_DATE + 59, 400000),
    (3, 2, NULL, CURRENT_DATE - 27, CURRENT_DATE + 2, 150000),
    (4, 2, NULL, CURRENT_DATE - 60, CURRENT_DATE - 31, 150000);

INSERT INTO pagos (id_membresia, monto, metodo, id_usuario) VALUES
    (1, 150000, 'efectivo',      1),
    (2, 200000, 'transferencia', 1),
    (3, 150000, 'tarjeta',       1),
    (4, 150000, 'efectivo',      1);

INSERT INTO asistencias (id_socio, entrada, id_usuario) VALUES
    (1, now() - interval '3 hours', 1),
    (2, now() - interval '1 hour',  1),
    (1, now() - interval '1 day',   1),
    (3, now() - interval '2 days',  1);
