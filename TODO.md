# TODO · SVD

Lista de tareas pendientes registradas durante el desarrollo. Marcar con `[x]` cuando se completen.

## Pendientes

- [ ] **🔴 URGENTE — Login en producción (svd.sytes.net) en bucle** — Tras intentar
    ver/descargar el reporte Excel, dejó de poder entrar `admin@svd.test` y
    `vendedor@svd.test`. Síntoma: el login **parpadea y vuelve al login SIN mensaje**,
    también en incógnito. Estado del diagnóstico (27-may-2026):
    - ✅ Usuarios existen con sus roles (super_admin / admin / seller).
    - ✅ `admin@svd.test` + `admin` → Hash::check da **true** (credencial correcta).
    - ✅ 91 permisos Shield generados.
    - ✅ Config OK: APP_KEY len 51, SESSION_DRIVER=database, SESSION_DOMAIN=null,
      SESSION_SECURE_COOKIE=false, APP_URL=http://svd.sytes.net, cookie=svd-session.
    - ✅ Tabla `sessions` existe; hay 3 sesiones guardadas (el server SÍ autentica).
    - ✅ Permisos storage + bootstrap/cache concedidos a IIS_IUSRS.
    - ✅ Log de Laravel SIN errores al intentar entrar.
    - ❌ Incógnito tampoco entra (descarta cookie vieja del navegador).
    - **Acción tomada (commit `d955c45`):** se quitó el middleware
      `AuthenticateSession` de ambos paneles (admin + vendedor) — era sospechoso
      porque invalida la sesión si el hash de contraseña cambió tras migrate:fresh.
      **PENDIENTE: confirmar en el servidor si esto lo resolvió** (hacer git pull +
      optimize:clear + caches + truncate sessions, y probar login).
    - **Si AÚN falla tras `d955c45`**, hipótesis siguientes a investigar:
      1. El commit del Excel (`f147a10`) creó `storage/app/temp`; revisar si algo
         del StreamedResponse o el `mkdir` interfiere con la respuesta del login.
      2. Revisar `bootstrap/cache/config.php` viejo en el server (un `config:cache`
         con un APP_KEY distinto al `.env` actual rompería el descifrado de la cookie
         de sesión → parpadeo). Probar: `php artisan config:clear` y dejar SIN cachear
         config temporalmente para ver si entra.
      3. Probar `SESSION_DRIVER=file` temporalmente: si con file entra pero con
         database no, el problema es escritura en la tabla sessions desde IIS.
      4. Revisar el commit de las gráficas (`12c6f6c`): Dashboard custom +
         VentasOverview con query de Product. Si el Dashboard truena al cargar tras
         login, Filament puede rebotar al login. Probar entrar a `/admin` con un
         usuario y mirar si el 500 ocurre en el Dashboard (revisar log con
         APP_DEBUG=true en el momento exacto del parpadeo).
    - **Quick win a probar primero la próxima sesión:** en el server,
      `php artisan config:clear` (sin volver a cachear) + truncate sessions +
      probar login. Si entra → era el config cache con key desincronizada.

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
