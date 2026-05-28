# Registro de Despliegues — SVD

Bitácora de qué commit está probado OK en producción (`svd.sytes.net`, IIS).
Si algo se rompe, volver al último commit marcado ✅ con:

```powershell
cd C:\inetpub\wwwroot\SVD\SVD
git reset --hard <hash>
php artisan optimize:clear
php artisan config:cache
```

> Regla: tras cada deploy a producción, **probar login + una acción clave** y
> anotar aquí el resultado antes de seguir con más cambios.

---

## Historial

| Fecha | Commit | Estado | Notas |
|-------|--------|--------|-------|
| 2026-05-27 ~10:15 | `b54b273` | ✅ probado OK | Último punto estable confirmado por el usuario antes del incidente. |
| 2026-05-27 noche | `86e9275` | ✅ **RESUELVE LOGIN** | Quita `<add segment="vendor"/>` del web.config que bloqueaba `/vendor/livewire/livewire.js` (HTTP 404.8) → era la causa real del bucle de login en IIS. |
| 2026-05-27 noche | (este commit) | ⏳ por probar | Reaplica: fix Excel (storage/app/temp para OpenSpout en IIS) + Dashboard custom de 3 columnas. Probar: login + descargar reporte Excel + ver dashboard. |

---

## Lección aprendida (incidente del bucle de login)

**Síntoma:** el login parpadeaba y volvía al formulario vacío, sin mensaje, solo en IIS (por `php artisan serve` funcionaba).

**Causa real:** el `web.config` tenía `<add segment="vendor"/>` en `hiddenSegments`.
Livewire (motor del formulario de login) carga su JS desde la ruta virtual
`/vendor/livewire/livewire.js`. IIS la bloqueaba con **404.8** → sin ese JS, el
login no funcionaba.

**Cómo se diagnosticó:** la pestaña Network del navegador mostró el 404.8 sobre
`livewire.js`. La evidencia del navegador fue lo que cerró el caso — no las
suposiciones de servidor/sesión/permisos que se probaron antes sin éxito.

**Para la próxima:** ante un login que "parpadea" en IIS, mirar PRIMERO la pestaña
Network del navegador (¿algún 404/403 en assets JS de livewire/filament?).
