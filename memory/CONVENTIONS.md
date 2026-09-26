# Project Conventions

Registra únicamente convenciones confirmadas por el repositorio (no inventadas).

## Naming
- **Modelos:** Singular PascalCase (`Employee`, `Device`, `Attendance`, `Area`, `Puesto`, `Incidencia`, `Profesor`, `Alumno`, `Grupo`, `Plan`).
- **Tablas:** Plural snake_case (`devices`, `employees`, `attendances`, `device_employee`, `incidencias`, `horarios_laborales`, `ciclos`, `materias`).
- **Controladores:** PascalCase con sufijo `Controller` en `app/Http/Controllers/` y `app/Http/Controllers/Academia/`.
- **Servicios:** PascalCase con sufijo `Service` o `Resolver` en `app/Services/`.
- **Migraciones:** Formato estándar Laravel `YYYY_MM_DD_HHMMSS_<accion>_<tabla>_table.php`.
- **Propiedad de Modelos:** `$casts` declarado como propiedad (`protected $casts = [...]`), no como método (`.ai/guidelines/00-reglas.md:61`).

## Arquitectura
- **Capa de Servicios:** Lógica de negocio pesada (sincronizaciones ZKTeco, sincronización Firebird, resolución de horarios, cálculo de puntualidad) reside en `app/Services/`, manteniendo controladores delgados (< 120 líneas).
- **Colas y Tareas:** Procesamiento asíncrono para operaciones de red y sincronización en `app/Jobs/`.
- **Autorización:** Manejo de permisos mediante políticas (`app/Policies/`) y middleware `RequireModulePermission` (`module_permission:<modulo>,<accion>`).
- **Composición de Layout:** Datos globales del dashboard (`$dash`) ensamblados mediante `app/View/Composers/AdminLayoutComposer.php`.

## Tests y Quality Gates
- **Comando principal de tests:** `php artisan test --compact` (o `vendor\bin\phpunit`).
- **Filtro específico:** `php artisan test --compact --filter=<NombreDePrueba>`
- **Requisito obligatorio para tests:** MySQL local (XAMPP) en ejecución con la base de datos `rh_reloj_testing` creada. NUNCA ejecutar pruebas contra la base de datos principal (`rh-reloj`).
- **Comandos de Verificación del Repositorio (`ia.cmd` / `scripts/`):**
  - `php scripts/baseline.php` (o `ia baseline`): Congela el contrato público de rutas, permisos, vistas y componentes en `.ai/baseline/`.
  - `php scripts/comparar_rutas.php` (o `ia rutas`): Valida que las 190 rutas del contrato permanezcan idénticas.
  - `php scripts/impacto.php` (o `ia impacto`): Analiza qué consumidores son afectados por cambios en el código.
  - `php scripts/verificar_referencias.php` (o `ia refs`): Comprueba integridad de llamadas `route()`, `@include` y vistas Blade.
  - `php scripts/verificar.php` (o `ia verificar`): Puerta de calidad de 7 pasos antes de cerrar módulos. Admite `--rapido` para omitir pruebas de BD si MySQL no está activo.
  - `vendor/bin/pint --dirty`: Formateo de código según el estándar del proyecto.

## Base de Datos
- **MySQL:** Gestor relacional principal. Integridad referencial enforced con foreign keys e índices de rendimiento en campos de búsqueda (`status`, `recorded_at`, `employee_id`).
- **Firebird 2.5:** Conexión de solo lectura. No realizar operaciones INSERT, UPDATE o DELETE sobre Firebird.
- **Transaccionalidad:** Envolver operaciones multi-tabla en `DB::transaction()` para garantizar atomicidad.

## UI / Frontend
- **Framework CSS:** Bootstrap 5.3.3 + Bootstrap Icons 1.11.3 (cargados exclusivamente por CDN en `layouts/admin.blade.php`; no introducir Tailwind CSS ni instalar Bootstrap vía npm).
- **Compilación de Assets:** Vite 4 (`npm run dev` para desarrollo, `npm run build` para producción).
- **Layout Base:** `resources/views/layouts/admin.blade.php`.
- **Manejo de Tema:** Soporte de tema Claro / Oscuro / Sistema sincronizado con `data-theme` y persistido en `localStorage['dash-theme']`.
- **Feedback al usuario:**
  - Toasts mediante `window.dashToast({ type, title, message })` o `window.showToast(type, message)`.
  - Modales de confirmación con `window.dashConfirm(opts)` o atributo `data-confirm` en formularios destructivos.
  - Paleta de comandos accesible con atajo `Ctrl+K`.
  - Heartbeat AJAX: sondeo cada 20 segundos hacia `/kpis/json` para actualizar contadores en vivo.

## Git
- Commits atómicos y descriptivos.
- Prohibido el uso de `git reset --hard`, `git checkout .`, `git clean -fd` o `git push` sin autorización explícita escrita del usuario.
