# Current Task State

Describe SOLO la tarea activa. Al cerrarla, se resetea a "pendiente" — el detalle histórico va a DECISIONS.md o KNOWN_ISSUES.md.

- Objetivo: Preparar el proyecto Laravel para subirlo a un servidor.
- Estado: Preparado y verificado
- Roles activados: Lead, Implementer, QA, DevOps
- Archivos relevantes: .env.example, .gitignore, deploy.sh, database/migrations/2026_09_26_140851_create_ofertas_table.php
- Último paso verificado: La migración corregida pasó `php artisan migrate --force`; las cachés de vistas/rutas y el build Vite finalizaron correctamente; se añadieron exclusiones para secretos, logs, respaldos y cachés generadas.
- Bloqueos: No se ejecutó un despliegue real porque requiere credenciales y servidor de destino.
- Próximo paso: Copiar el código al servidor, crear `.env` real con secretos fuera del repositorio, configurar DocumentRoot en `public/` y ejecutar `deploy.sh` después de revisar migraciones pendientes.
