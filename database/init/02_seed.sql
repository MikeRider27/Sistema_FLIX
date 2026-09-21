-- Usuario inicial. CAMBIAR LA CLAVE después del primer ingreso (menú Usuarios).
-- bcrypt generado con pgcrypto; PHP password_verify() lo acepta y lo re-hashea al ingresar.
INSERT INTO usuarios (nombre, usuario, clave_hash, rol)
VALUES ('Administrador', 'admin', crypt('admin123', gen_salt('bf', 10)), 'admin');

INSERT INTO planes (nombre, descripcion, duracion_dias, precio) VALUES
    ('Pase diario', 'Acceso por un día',            1,    20000),
    ('Mensual',     'Acceso libre 30 días',         30,   150000),
    ('Trimestral',  'Acceso libre 90 días',         90,   400000),
    ('Semestral',   'Acceso libre 180 días',        180,  750000),
    ('Anual',       'Acceso libre 365 días',        365,  1400000);
