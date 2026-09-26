# Security Notes

Nunca guardar aquí contraseñas, tokens, claves API ni datos personales reales — solo referencias a dónde viven y cómo se rotan.

## 2026-09-26 — Auditoría de Baseline Oficial de Arquitectura y Configuración
- **Qué se revisó:** Autenticación (Sanctum), middleware de autorización, protección CSRF, manejo de variables de entorno, protección de datos biométricos/personales y aislamiento de Firebird.
- **Hallazgos:**
  - **Autenticación y Tokens:** Laravel Sanctum bloqueado en versión `v3.3.3` (`composer.lock`), gestionando sesiones web con cookies seguras y tokens de acceso.
  - **Autorización y Roles:**
    - Estructura de roles en tabla `users` asegurada por check constraint a nivel de base de datos (`2026_09_05_202614_add_check_constraint_role_to_users_table.php`).
    - Middleware `RequireModulePermission` (`App\Http\Middleware\RequireModulePermission`) protege las rutas del sistema según permisos granulares (`permissions`, `permission_groups`, `modules`).
  - **Protección CSRF:** Implementada de forma global en solicitudes Blade (`@csrf`) y en peticiones AJAX (`X-CSRF-TOKEN` inyectado automáticamente en `resources/js/app.js` y `resources/js/bootstrap.js`).
  - **Datos Biométricos y Personales:**
    - La tabla `fingerprints` y `employees` contienen datos sensibles. Existe una migración previa de encriptación (`2024_01_01_000008_encrypt_sensitive_data.php`).
    - Regla estricta confirmada en `.ai/guidelines/00-reglas.md`: los datos biométricos y los datos de alumnos no deben salir en logs, respuestas JSON abiertas ni exportaciones sin necesidad justificada.
  - **Aislamiento de Firebird 2.5:** Conexión configurada en `config/database.php`. Debe mantenerse estrictamente como lectura (`SELECT`) para evitar corromper o bloquear el sistema institucional legacy UTE. Prohibido ejecutar `INSERT`, `UPDATE`, `DELETE` o `DROP` contra Firebird.
  - **Manejo de Secretos:** Almacenados exclusivamente en archivo local `.env` (ignorado por Git). En el commit de baseline `d403cf3` se eliminó `.env.example` para evitar exponer esquemas de credenciales en repositorios remotos.
- **Riesgos aceptados conscientemente:**
  - Comunicación con terminales ZKTeco en red local sin cifrado nativo de transporte (limitación del protocolo propietario ZK sobre UDP/IP). Mitigación: Las terminales deben ubicarse en una VLAN privada aislada del tráfico de usuarios generales.
- **Próxima revisión sugerida:**
  - Antes de publicar cualquier endpoint nuevo de exportación de datos de asistencias/alumnos, API externa o cambios al seeder de permisos (`ModulePermissionSeeder`).
