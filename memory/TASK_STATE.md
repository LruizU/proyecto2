# Current Task State

Describe SOLO la tarea activa. Al cerrarla, se resetea a "pendiente" — el detalle histórico va a DECISIONS.md o KNOWN_ISSUES.md.

- Objetivo: Integrar horarios propios de cursos/estadías y filtros de tipo en la asistencia.
- Estado: Implementado y verificado
- Roles activados: Lead, Implementer, QA
- Archivos relevantes: app/Http/Controllers/Academia/HorarioController.php, app/Services/HorarioResolver.php, app/Services/AttendanceCaptureAuthorization.php, app/Models/Academia/CursoDet.php, resources/views/academia/horarios/clase.blade.php
- Último paso verificado: El filtro de solo cursos responde HTTP 200; PHP, Blade y diagnósticos compilan sin errores. Día y fecha solo son editables para administradores y el servidor fuerza la fecha actual para otros usuarios.
- Bloqueos: MySQL local (XAMPP) actualmente no está en ejecución, impidiendo correr la suite de pruebas completa con RefreshDatabase.
- Próximo paso: Listo para recibir la siguiente tarea de desarrollo o mantenimiento.
