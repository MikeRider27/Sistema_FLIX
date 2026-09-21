CREATE EXTENSION IF NOT EXISTS pgcrypto;
CREATE EXTENSION IF NOT EXISTS btree_gist;

CREATE TABLE usuarios (
    id_usuario  SERIAL PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    usuario     VARCHAR(50)  NOT NULL UNIQUE,
    clave_hash  VARCHAR(255) NOT NULL,
    rol         VARCHAR(20)  NOT NULL CHECK (rol IN ('admin', 'recepcion')),
    activo      BOOLEAN      NOT NULL DEFAULT TRUE,
    creado_en   TIMESTAMPTZ  NOT NULL DEFAULT now()
);

CREATE TABLE socios (
    id_socio             SERIAL PRIMARY KEY,
    ci                   VARCHAR(20)  NOT NULL UNIQUE,
    nombre               VARCHAR(80)  NOT NULL,
    apellido             VARCHAR(80)  NOT NULL,
    fecha_nacimiento     DATE,
    sexo                 CHAR(1) CHECK (sexo IN ('M', 'F', 'O')),
    telefono             VARCHAR(30),
    email                VARCHAR(120),
    direccion            VARCHAR(200),
    contacto_emergencia  VARCHAR(150),
    notas                TEXT,
    estado               VARCHAR(10) NOT NULL DEFAULT 'activo' CHECK (estado IN ('activo', 'inactivo')),
    creado_en            TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE planes (
    id_plan        SERIAL PRIMARY KEY,
    nombre         VARCHAR(80)   NOT NULL UNIQUE,
    descripcion    VARCHAR(250),
    duracion_dias  INTEGER       NOT NULL CHECK (duracion_dias > 0),
    precio         NUMERIC(12,2) NOT NULL CHECK (precio >= 0),
    activo         BOOLEAN       NOT NULL DEFAULT TRUE
);

CREATE TABLE entrenadores (
    id_entrenador  SERIAL PRIMARY KEY,
    ci             VARCHAR(20)  NOT NULL UNIQUE,
    nombre         VARCHAR(80)  NOT NULL,
    apellido       VARCHAR(80)  NOT NULL,
    telefono       VARCHAR(30),
    email          VARCHAR(120),
    especialidad   VARCHAR(100),
    activo         BOOLEAN      NOT NULL DEFAULT TRUE
);

CREATE TABLE membresias (
    id_membresia   SERIAL PRIMARY KEY,
    id_socio       INTEGER       NOT NULL REFERENCES socios(id_socio),
    id_plan        INTEGER       NOT NULL REFERENCES planes(id_plan),
    id_entrenador  INTEGER       REFERENCES entrenadores(id_entrenador),
    fecha_inicio   DATE          NOT NULL,
    fecha_fin      DATE          NOT NULL,
    precio         NUMERIC(12,2) NOT NULL CHECK (precio >= 0),
    estado         VARCHAR(10)   NOT NULL DEFAULT 'activa' CHECK (estado IN ('activa', 'cancelada')),
    creado_en      TIMESTAMPTZ   NOT NULL DEFAULT now(),
    CHECK (fecha_fin >= fecha_inicio),
    -- Un socio no puede tener dos membresías activas que se solapen en fechas
    EXCLUDE USING gist (
        id_socio WITH =,
        daterange(fecha_inicio, fecha_fin, '[]') WITH &&
    ) WHERE (estado = 'activa')
);

CREATE TABLE pagos (
    id_pago       SERIAL PRIMARY KEY,
    id_membresia  INTEGER       NOT NULL REFERENCES membresias(id_membresia),
    monto         NUMERIC(12,2) NOT NULL CHECK (monto > 0),
    metodo        VARCHAR(15)   NOT NULL CHECK (metodo IN ('efectivo', 'tarjeta', 'transferencia')),
    referencia    VARCHAR(100),
    fecha_pago    TIMESTAMPTZ   NOT NULL DEFAULT now(),
    id_usuario    INTEGER       NOT NULL REFERENCES usuarios(id_usuario)
);

CREATE TABLE asistencias (
    id_asistencia  SERIAL PRIMARY KEY,
    id_socio       INTEGER     NOT NULL REFERENCES socios(id_socio),
    entrada        TIMESTAMPTZ NOT NULL DEFAULT now(),
    id_usuario     INTEGER     REFERENCES usuarios(id_usuario)
);

CREATE INDEX idx_membresias_socio  ON membresias(id_socio);
CREATE INDEX idx_membresias_fin    ON membresias(fecha_fin);
CREATE INDEX idx_pagos_membresia   ON pagos(id_membresia);
CREATE INDEX idx_pagos_fecha       ON pagos(fecha_pago);
CREATE INDEX idx_asistencias_socio ON asistencias(id_socio);
CREATE INDEX idx_asistencias_fecha ON asistencias(entrada);

-- Membresías con datos relacionados, total pagado, saldo y estado real
CREATE VIEW v_membresias AS
SELECT m.id_membresia, m.id_socio, m.id_plan, m.id_entrenador,
       m.fecha_inicio, m.fecha_fin, m.precio, m.estado, m.creado_en,
       s.nombre || ' ' || s.apellido AS socio,
       s.ci,
       p.nombre AS plan,
       e.nombre || ' ' || e.apellido AS entrenador,
       COALESCE(SUM(pg.monto), 0)             AS pagado,
       m.precio - COALESCE(SUM(pg.monto), 0)  AS saldo,
       CASE
           WHEN m.estado = 'cancelada'          THEN 'cancelada'
           WHEN m.fecha_fin < CURRENT_DATE      THEN 'vencida'
           WHEN m.fecha_inicio > CURRENT_DATE   THEN 'pendiente'
           ELSE 'vigente'
       END AS estado_real
FROM membresias m
JOIN socios s  ON s.id_socio = m.id_socio
JOIN planes p  ON p.id_plan  = m.id_plan
LEFT JOIN entrenadores e ON e.id_entrenador = m.id_entrenador
LEFT JOIN pagos pg       ON pg.id_membresia = m.id_membresia
GROUP BY m.id_membresia, s.id_socio, p.id_plan, e.id_entrenador;
