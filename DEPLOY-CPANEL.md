# Deploy en cPanel (sin terminal)

Flujo: `git push` a `main` → GitHub Actions compila `vendor/` y `public/build/` y los publica en la rama **`cpanel`** → cPanel clona/actualiza esa rama → `setup.php` ejecuta los comandos artisan desde el navegador.

## Requisitos del hosting

- **PHP 8.4 o superior** (Symfony 8 lo exige). En cPanel: *Select PHP Version* / *MultiPHP Manager*.
- Extensiones: `bcmath, ctype, fileinfo, gd, intl, mbstring, openssl, pdo_mysql, tokenizer, xml, zip`.
- MySQL/MariaDB y la herramienta *Git™ Version Control* de cPanel.

## 1. Generar la rama `cpanel` (una vez)

1. Haz push de este repo a GitHub (`main`).
2. En GitHub → *Actions* → **Build rama cpanel** → verifica que termine en verde (también se puede lanzar manualmente con *Run workflow*).
3. Confirma que existe la rama `cpanel`.

## 2. Base de datos

cPanel → *MySQL® Databases*: crea la base (ej. `usuario_svd`), un usuario y asígnalo con **ALL PRIVILEGES**.

## 3. Clonar desde cPanel

cPanel → *Git™ Version Control* → *Create*:

- **Clone URL:** `https://github.com/logo3x/SVD.git`
  (si el repo es privado usa la URL SSH `git@github.com:logo3x/SVD.git` y agrega la clave pública de cPanel — *SSH Access → Manage Keys* — como *Deploy key* en GitHub).
- **Repository Path:** `/home/USUARIO/svd` (fuera de `public_html`).
- Después de clonar: *Manage* → pestaña *Basic Information* → **Checked-Out Branch: `cpanel`**.

## 4. Apuntar el dominio a `public/`

- **Recomendado:** cPanel → *Domains* → crea/edita el dominio o subdominio con **Document Root** `/home/USUARIO/svd/public`.
- **Si no puedes cambiar el document root** (dominio principal en `public_html`): clona directamente en `public_html` (debe estar vacío); el `.htaccess` de la raíz redirige todo a `public/` y bloquea el resto.

## 5. Crear el `.env`

*Administrador de archivos* → en `/home/USUARIO/svd` copia `.env.cpanel.example` como `.env` (activa *Mostrar archivos ocultos*) y completa:

- `APP_URL`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, datos de correo.
- `SETUP_TOKEN` = una cadena aleatoria de 16+ caracteres.

Permisos: `storage/` y `bootstrap/cache/` en 755 (o 775 si da error de escritura).

## 6. Instalar desde el navegador

Abre `https://tu-dominio.com/setup.php?token=TU_SETUP_TOKEN`:

1. Revisa la tabla **Diagnóstico** (todo en ✔).
2. Pulsa **Instalación inicial completa** (genera APP_KEY, migra, siembra roles/usuarios/catálogo, `storage:link`, cachés).
3. Entra a `/admin` con `superadmin@svd.test` y **cambia la contraseña**.
4. Vacía `SETUP_TOKEN=` en el `.env` para desactivar el asistente.

## Actualizar (cada nueva versión)

1. `git push` a `main` y espera a que la Action termine.
2. cPanel → *Git™ Version Control* → *Manage* → *Pull or Deploy* → **Update from Remote**.
3. Pon de nuevo un `SETUP_TOKEN`, abre `setup.php` y pulsa **Después de actualizar desde Git**.
4. Vacía `SETUP_TOKEN`.

> Si editas el `.env` con la config ya cacheada, usa **Limpiar cachés** y luego **Optimizar**.

## Notas

- **Colas:** sin worker usa `QUEUE_CONNECTION=sync`. **Scheduler:** si lo necesitas, cPanel → *Cron Jobs*:
  `* * * * * /usr/local/bin/php /home/USUARIO/svd/artisan schedule:run >> /dev/null 2>&1`
  (la ruta de PHP 8.4 puede ser `/opt/cpanel/ea-php84/root/usr/bin/php`).
- **`storage:link` falla:** el hosting tiene `symlink()` deshabilitado; pide al soporte que lo habilite o crea el enlace `public/storage → ../storage/app/public` desde un cron de una sola ejecución: `ln -s /home/USUARIO/svd/storage/app/public /home/USUARIO/svd/public/storage`.
- **Error 500:** revisa `storage/logs/laravel.log` en el Administrador de archivos.
