# Known Issues

Formato recomendado:
## ISSUE-ID — #tag Título
- Síntoma:
- Causa conocida:
- Archivos:
- Workaround:
- Estado: Abierto | En progreso | Resuelto (fecha)
- Cómo verificar:

Regla de poda: issue "Resuelto" se mantiene completo solo 5 tareas después del cierre. Luego se condensa a:
`RESUELTO — <título> — <fecha> — ref commit/PR si aplica`

---

## ISSUE-01 — #deuda-tecnica #infra Paridad de pruebas requiere MySQL local activo
- Síntoma: `php artisan test` y el paso 7/7 de `php scripts/verificar.php` fallan o se cuelgan por timeout de socket si el servidor MySQL local (XAMPP) no está activo en `127.0.0.1:3306`.
- Causa conocida: `phpunit.xml` define intencionalmente `DB_CONNECTION=mysql` apuntando a la base de datos `rh_reloj_testing` para asegurar paridad estricta de migraciones y tipos con producción (no utiliza SQLite en memoria).
- Archivos: `phpunit.xml`, `tests/TestCase.php`
- Workaround: Iniciar el servicio de MySQL desde el panel de control de XAMPP y asegurar la existencia de la base de datos `rh_reloj_testing` (`CREATE DATABASE IF NOT EXISTS rh_reloj_testing;`). Para validaciones rápidas que no tocan BD, usar `php scripts/verificar.php --rapido`.
- Estado: Abierto (comportamiento documentado por diseño)
- Cómo verificar: Comprobar conectividad con `Test-NetConnection -ComputerName 127.0.0.1 -Port 3306` y ejecutar `php artisan test`.

## ISSUE-02 — #seguridad #infra Dependencia de conectividad de red para terminales ZKTeco
- Síntoma: Fallos de timeout o excepción de socket UDP al intentar comunicarse con los relojes biométricos si están apagados o fuera de alcance.
- Causa conocida: La librería `coding-libs/zkteco-php` realiza sockets UDP directos por IP hacia el puerto del dispositivo configurado en `devices`.
- Archivos: `app/Services/ZktecoService.php`, `app/Services/DeviceSyncService.php`
- Workaround: Manejo de excepciones en los jobs de sincronización con registro de incidencias en tabla `device_syncs` y logs de Laravel.
- Estado: Abierto (inherente a hardware físico)
- Cómo verificar: Verificar ping a la IP del reloj antes de sincronizar masivamente.

## ISSUE-03 — #deuda-tecnica Estilo de código: 5 archivos no pasan validación estricta de Laravel Pint
- Síntoma: `vendor/bin/pint --test` y `php scripts/verificar.php` reportan fallo en el paso 5/7 (Estilo Pint).
- Causa conocida: Diferencias menores de formato (espaciado de operadores, llaves o imports no ordenados) en 5 archivos preexistentes:
  - `app/Http/Controllers/Academia/AlumnoController.php`
  - `app/Http/Controllers/Academia/GrupoController.php`
  - `app/Http/Controllers/Academia/PlanController.php`
  - `routes/web.php`
  - `tests/Feature/AcademiaDashboardTest.php`
- Archivos: Los 5 archivos indicados.
- Workaround: Ejecutar `vendor/bin/pint --dirty` cuando el usuario solicite o autorice explícitamente aplicar cambios de formateo.
- Estado: Abierto.
- Cómo verificar: Ejecutar `vendor/bin/pint --test`.

## ISSUE-04 — #deuda-tecnica Vistas huérfanas detectadas en subdirectorio de academia
- Síntoma: `php scripts/verificar_referencias.php` detecta 2 vistas sin referencias cruzadas directas encontradas en controladores ni directivas `@include`:
  - `resources/views/academia/dashboard/_materias-module.blade.php`
  - `resources/views/academia/dashboard/_planes-module.blade.php`
- Causa conocida: Fragmentos de dashboard posiblemente extraídos durante una refactorización previa o dejados como stubs.
- Archivos: `resources/views/academia/dashboard/_materias-module.blade.php`, `resources/views/academia/dashboard/_planes-module.blade.php`.
- Workaround: No eliminarlas sin validación humana explícita (`.ai/guidelines/00-reglas.md`).
- Estado: Abierto.
- Cómo verificar: Ejecutar `php scripts/verificar_referencias.php`.
