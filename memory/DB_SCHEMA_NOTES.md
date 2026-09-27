# Database Schema Notes

Mapa de entidades y relaciones clave no obvias en la base de datos (MySQL principal y tablas sincronizadas desde Firebird).

## Dispositivos y Catálogo Central (`devices`, `employees`, `device_employee`)
- **Propósito:** Gestión multi-dispositivo de empleados y relojes checadores biométricos ZKTeco.
- **Relaciones clave no evidentes:**
  - `employees` y `devices` están asociados mediante la tabla pivote `device_employee` (`employee_id`, `device_id`, con marcas de tiempo e `ignored_at`).
  - La relación 1 a 1 legacy de empleado-dispositivo fue reemplazada por este catálogo global centralizado.
- **Particularidades:**
  - Índices de rendimiento en `status_actual` para búsquedas frecuentes de empleados activos.
  - La migración histórica eliminó columnas legadas en `employees` (ej. `device_id`, `uid`).

## Asistencias y Huellas (`attendances`, `fingerprints`)
- **Propósito:** Registro de eventos de checada biométrica y plantillas dactilares.
- **Relaciones clave:**
  - `attendances.employee_id` referencia a `employees.id`.
  - `fingerprints.employee_id` referencia a `employees.id`.
- **Particularidades:**
  - `attendances` cuenta con índice compuesto en `[type, recorded_at]` y restricción única para prevenir checadas duplicadas en el mismo segundo/minuto por dispositivo.
  - Campos de academia vinculan la asistencia con asignaturas y sesiones en caso de personal docente.

## Sincronización Institucional Firebird (`firebird_syncs`, `firebird_sync_items`)
- **Propósito:** Trazabilidad, auditoría e idempotencia en la sincronización de catálogos desde el sistema institucional legado Firebird 2.5 hacia MySQL.
- **Mecanismo:**
  - Cada ejecución de sincronización crea una cabecera en `firebird_syncs` y el desglose de cada registro en `firebird_sync_items`.
  - Clasifica las operaciones como `INSERT`, `UPDATE`, `UNCHANGED` o `ERROR`.
  - Firebird es tratado como solo lectura: la sincronización nunca escribe en Firebird. Estrategias en `app/Services/SyncStrategies/` (`FullSync`, `CatalogSmartSync`, `CycleDirectSync`, `CustomSync`).
  - En `CycleDirectSync`, `ALUMNOS` se sincroniza primero usando los IDs obtenidos de `ALUMNOS_GRUPOS`; después se sincronizan `ALUMNOS_NIVELES` y `ALUMNOS_GRUPOS`. Esta última tabla materializa `INICIAL`, `FINAL` y `PERIODO` a partir de los grupos del ciclo porque el flujo requiere una clave compuesta local.
  - `alumnos_niveles` conserva el nivel/grado del alumno por ciclo; `alumnos_grupos` conserva la inscripción concreta a un grupo por ciclo. La carrera no se deriva de `alumnos_niveles`: cuando existe, debe provenir de los datos del alumno/grupo o del catálogo de planes.
  - `FullSyncStrategy` tenía un fallo en el post-proceso: pasaba `$mysql` sin inicializar a `propagateOrigenHorario`; ahora obtiene explícitamente la conexión PDO antes de ejecutar ese paso.

## Catálogo Espejo Académico (UTE)
- **Tablas:** `ciclos`, `niveles`, `sedes`, `turnos`, `planes`, `materias`, `profesores`, `alumnos`, `grupos`, `cursos`, `cursos_det`, `horarios_det`.
- **Modelos:** Ubicados en `app/Models/Academia/` (`Grupo`, `Plan`, `Materia`, `Alumno`, etc.) y raíz de `app/Models/` (`Ciclo`, `Profesor`).
- **Particularidades:**
  - Espejo relacional en MySQL de los datos de Firebird.
  - Mantiene integridad referencial local con foreign keys para permitir consultas de horarios y asistencias docentes sin impactar la base legacy.
  - Servicios auxiliares resuelven estados activos: `CicloActualService` (identifica ciclo vigente), `HorarioResolver` (mapea cruces de horarios y profesores) y `PersonaContratosResolver`.

## Recursos Humanos e Incidencias (`areas`, `puestos`, `incidencias`, `incidencia_approvals`)
- **Propósito:** Estructura jerárquica de la universidad y ciclo de vida de justificaciones e incidencias laborales.
- **Relaciones clave:**
  - `areas` tiene auto-relación jerárquica (`parent_id`) para modelar departamentos y coordinaciones.
  - `incidencias` se enlaza con `employees` y genera registros en `incidencia_approvals` para firmas de aprobación jerárquica.
- **Particularidades:**
  - Soporte de visualización y firma digital de incidencias (`viewed_at`, `signed_at`).
  - Bitácora de cambios en `audit_logs` y observaciones en `attendance_observations`.
