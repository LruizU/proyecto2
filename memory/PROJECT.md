# Project Memory

## Stack
- **Lenguaje / Runtime:** PHP ^8.1 (Composer require) · Entorno local y lineamiento objetivo: **PHP 8.3** (PHP 8.3.33 cli activo).
- **Framework Principal:** Laravel 10.x — Versión bloqueada exacta: **v10.50.3** (`composer.lock`).
- **Gestores de Dependencias:** Composer (backend PHP) y npm (assets frontend).
- **Frontend & UI:** 
  - Laravel Blade + Bootstrap 5.3.3 + Bootstrap Icons 1.11.3 (cargados mediante CDN de jsDelivr en `resources/views/layouts/admin.blade.php`, no como paquete npm).
  - Bundler: Vite 4.x (versión bloqueada: `vite 4.5.14`, `laravel-vite-plugin 0.7.8`).
  - Cliente HTTP: Axios (versión bloqueada: `axios 1.19.0`).
  - Módulos interactivos en `resources/js/app.js`: selector de tema Claro/Oscuro/Sistema, Heartbeat AJAX (cada 20s para KPIs y badge de notificaciones), Sidebar colapsable (`Ctrl+B`), Toasts, modales de confirmación (`data-confirm`) y paleta de comandos (`Ctrl+K`).
- **Integración Hardware:** ZKTeco Biometrics (`coding-libs/zkteco-php: v0.0.35`) para comunicación con terminales biométricas por sockets UDP/IP.
- **Herramientas de Desarrollo y Calidad:**
  - PHPUnit 10.5.64 (`phpunit/phpunit`)
  - Laravel Pint 1.20.0 (`laravel/pint`)
  - Laravel Sanctum 3.3.3 (`laravel/sanctum`)
  - Laravel Tinker 2.11.1 (`laravel/tinker`)
  - Spatie Laravel Ignition 2.9.1 (`spatie/laravel-ignition`)
  - Laravel Sail 1.67.0 (`laravel/sail`)

## Arquitectura
- **Tipo de Aplicación:** Sistema institucional de Recursos Humanos, Catálogo Central de Empleados, Control de Asistencias y Sincronización Biométrica (Proyecto UTE / *rh-reloj*).
- **Patrón:** MVC extendido de Laravel:
  - Controladores HTTP en `app/Http/Controllers/` (incluye subespacio `Academia/`).
  - Modelos Eloquent en `app/Models/` (con subespacio `Academia/`).
  - Capa de Servicios de negocio en `app/Services/` (`CicloActualService`, `HorarioResolver`, `KardexCalculator`, `PermissionResolver`, `EmployeeDeviceSyncService`, `EmployeeCatalogMover`, `SobranteService`, `FirebirdReader`, `SyncStrategies/*`, `ZktecoService`).
  - Tareas en segundo plano y colas en `app/Jobs/` (despacho asíncrono para operaciones de red y sincronizaciones pesadas).
  - Políticas de autorización en `app/Policies/` y middleware granular `RequireModulePermission`.
  - DTOs / Clases de datos en `app/Data/` y Enums en `app/Enums/`.
  - Vistas organizadas modularmente en Blade bajo `resources/views/` con layout administrativo `layouts/admin.blade.php` compuesto por `app/View/Composers/AdminLayoutComposer.php`.
- **Navegación y Permisos:** Sistema de permisos dinámico con soporte de módulos, grupos de permisos y catálogo de navegación en base de datos (`navigation_items`, `modules`, `permissions`, `permission_groups`).
- **Contrato de Rutas Oficial:** 190 rutas activas confirmadas por `php artisan route:list` y verificadas contra `.ai/baseline/routes.json`.
- **Procesamiento Asíncrono:** Soporte para colas y workers mediante Supervisor (`deploy.sh` soporta `USE_SUPERVISOR=1`).

## Bases de datos / Integraciones externas
- **MySQL (Principal):**
  - Motor de almacenamiento transaccional de la aplicación (`rh-reloj` en producción, configurable vía `DB_DATABASE`).
  - Entorno de testing configurado en `phpunit.xml` sobre MySQL (`rh_reloj_testing`), buscando paridad estricta con producción (no usa SQLite).
  - Tablas para empleados, huellas, dispositivos biométricos, asistencias, incidencias, usuarios, auditoría y catálogos espejeados.
- **Firebird 2.5 (Sistema Legado Institucional UTE / SICEE):**
  - Conexión configurada en `config/database.php` bajo la clave `firebird` (vía PDO con DSN `FIREBIRD_DSN`).
  - Rol de **solo lectura** para extracción y sincronización periódica de datos académicos y de personal institucional (`ciclos`, `materias`, `profesores`, `alumnos`, `horarios`).
- **Dispositivos Biométricos ZKTeco:**
  - Comunicación directa en red con terminales de huella dactilar para sincronizar usuarios, huellas y descargar marcas de asistencia.

## CI/CD e Infraestructura
- **CI/CD Remoto:** No configurado en repositorio (sin directorio `.github/` ni `.gitlab-ci.yml`).
- **Herramientas de Control de Calidad Local:** Script wrapper `ia.cmd` y scripts en `scripts/` (`baseline.php`, `comparar_rutas.php`, `impacto.php`, `verificar.php`, `verificar_referencias.php`, `limpiar.php`).
- **Despliegue de Producción:** Automatizado mediante script local/servidor `deploy.sh`:
  - Verificación de preflight (`APP_ENV=production`, conectividad a Supervisor).
  - Git pull fast-forward.
  - Composer install (`--no-dev --optimize-autoloader`).
  - Modo mantenimiento con página de renderizado 503.
  - Detención y reinicio controlado de workers de colas (`supervisorctl` / `queue:restart`).
  - Respaldo previo con `mysqldump`.
  - Ejecución de migraciones (`php artisan migrate --force`).
  - Optimización de caché de rutas, vistas y configuración (`optimize`, `event:cache`).

## Reglas permanentes
- No inventar patrones si el repositorio ya tiene uno funcional.
- Mantener cambios pequeños y verificables.
- Tratar Firebird estrictamente como base de datos de **SOLO LECTURA**.
- No ejecutar migraciones destructivas ni resets en la base de datos sin autorización explícita del usuario.
- Las pruebas automatizadas deben ejecutarse contra `rh_reloj_testing`, nunca contra la base de datos principal de desarrollo o producción.
- Seguridad no es opcional en cambios de autenticación, permisos, entrada de datos o manejo de datos sensibles biométricos y personales.
