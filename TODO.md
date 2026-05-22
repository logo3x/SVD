# TODO · SVD

Lista de tareas pendientes registradas durante el desarrollo. Marcar con `[x]` cuando se completen.

## Pendientes

- [ ] **Manual de usuario web** — Escribir la guía de uso del panel `/admin` y `/vendedor` para usuarios finales (no técnicos). Incluir:
    - Cómo crear un cliente nuevo y qué pasa con los productos por defecto.
    - Cómo aplicar un override de precio en `Clientes › Productos del Cliente`.
    - Cómo crear una remisión paso a paso (selección de cliente, productos, ruta, firma).
    - Cómo descargar el PDF y reenviar copia por email.
    - Cómo usar la página de Reportes con los filtros combinables.
    - Cómo gestionar usuarios, roles y permisos vía Shield.
    - Cómo cambiar la marca (logo, eslogan, emails) en `Configuración`.
    - Screenshots o GIFs cortos por sección.
    - Formato sugerido: `docs/manual-usuario.md` con capítulos numerados, o un PDF generado desde Markdown.

## Ideas futuras (no urgentes)

- Capacitación presencial / videos cortos.
- App móvil NativePHP (proyecto separado — ver `MOBILE-APP-CONTEXT.md`).
- Integración WhatsApp Business para envío de comprobantes.
- Dashboard de vendedor con sus propias métricas (cumplimiento de ruta, ventas vs meta).
- Bus::batch para envío masivo de emails si el volumen crece.
- Backup automatizado con `spatie/laravel-backup` (ver DEPLOY.md).
