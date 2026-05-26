# TODO · SVD

Lista de tareas pendientes registradas durante el desarrollo. Marcar con `[x]` cuando se completen.

## Pendientes

- [ ] **Deploy público de SVD** — ⏸️ EN PAUSA (decisión del usuario, sesión 26-may-2026). Retomar eligiendo plataforma: Railway (recomendada), Render o VPS. InfinityFree descartado. La app móvil funciona contra Wamp local mientras tanto. Tenemos el ZIP `svd-deploy.zip` (28.9 MB) listo en el escritorio del usuario:
    - InfinityFree free **no es viable** para Laravel: no hay SSH (no se puede correr `php artisan migrate`, `storage:link`, `key:generate`), File Manager limitado y `htdocs/` sigue vacío (solo placeholders `index2.html` + `files for your website should be uploaded here!`).
    - Lo que ya está hecho:
        - ✅ `svd-dump.sql` (269 KB) en el escritorio para importar por phpMyAdmin
        - ✅ `svd-deploy.zip` (28.9 MB) con vendor sin dev en el escritorio
        - ✅ `cloud.yaml` + `DEPLOY-CLOUD.md` en el repo (config Laravel Cloud)
        - ✅ Contrato móvil versión `3e03c7a92cc4` regenerado en el escritorio
    - Decisión pendiente: elegir entre 3 alternativas y ejecutar:
        - **A. Railway** — gratis hasta cierto uso, deploy desde GitHub, ~15 min. Recomendada.
        - **B. Render** — similar a Railway, free tier disponible.
        - **C. VPS Hetzner / Contabo** — ~$4-5/mes, control total, ideal para producción real.
        - **D. Laravel Cloud** — pide tarjeta aunque tenga $5 free; ya está `cloud.yaml` listo.
    - InfinityFree queda descartado salvo que el usuario insista, en cuyo caso necesitamos resolver:
        1. Confirmar que el ZIP subió y se extrajo (último estado: `htdocs/` vacío).
        2. Borrar `index2.html` y `files for your website should be uploaded here!`.
        3. Crear `.env` y `.htaccess` desde File Manager.
        4. Importar SQL por phpMyAdmin (DB ya creada `if0_42019134_svd`).
        5. Acepar que Laravel probablemente no arrancará por falta de cache/migrate.


- [x] **Manual de usuario web** — Hecho: `docs/manual-usuario.md` con 14 capítulos
    cubriendo acceso, roles, clientes + productos por defecto, precios especiales,
    catálogo, creación de remisión (incl. firma en pantalla), PDF/reenvío, reportes,
    usuarios/roles/impersonación, branding, módulo app móvil y panel vendedor + FAQ.
    Pendiente opcional (no urgente): añadir screenshots/GIFs y generar versión PDF.

## Ideas futuras (no urgentes)

- Capacitación presencial / videos cortos.
- App móvil NativePHP (proyecto separado — ver `MOBILE-APP-CONTEXT.md`).
- Integración WhatsApp Business para envío de comprobantes.
- Dashboard de vendedor con sus propias métricas (cumplimiento de ruta, ventas vs meta).
- Bus::batch para envío masivo de emails si el volumen crece.
- Backup automatizado con `spatie/laravel-backup` (ver DEPLOY.md).
