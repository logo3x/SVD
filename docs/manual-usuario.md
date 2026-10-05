# Manual de Usuario · SVD (Sistema de Ventas y Despachos)

Guía de uso para usuarios finales. Cubre el **panel administrativo** (`/admin`) y el **panel del vendedor** (`/vendedor`).

> Esta guía describe **qué** hace cada función y **cómo** usarla, sin tecnicismos. Si algo no coincide con lo que ves en pantalla, avisa al administrador del sistema — puede que la versión instalada sea distinta.

---

## Índice

1. [Acceso al sistema](#1-acceso-al-sistema)
2. [Roles y qué puede hacer cada uno](#2-roles-y-qué-puede-hacer-cada-uno)
3. [Panel Administrador — vista general](#3-panel-administrador--vista-general)
4. [Gestionar clientes](#4-gestionar-clientes)
5. [Productos del cliente y precios especiales](#5-productos-del-cliente-y-precios-especiales)
6. [Catálogo de productos](#6-catálogo-de-productos)
7. [Crear una remisión paso a paso](#7-crear-una-remisión-paso-a-paso)
8. [Descargar PDF y reenviar copia](#8-descargar-pdf-y-reenviar-copia)
9. [Reportes](#9-reportes)
10. [Usuarios, roles y permisos](#10-usuarios-roles-y-permisos)
11. [Configuración de marca (branding)](#11-configuración-de-marca-branding)
12. [Módulo de App móvil](#12-módulo-de-app-móvil)
13. [Panel del Vendedor](#13-panel-del-vendedor)
14. [Preguntas frecuentes](#14-preguntas-frecuentes)

---

## 1. Acceso al sistema

El sistema tiene **dos puertas de entrada** según tu rol:

| Panel | Dirección | Para quién |
|-------|-----------|------------|
| **Administrador** | `https://TU-DOMINIO/admin` | Administradores y Super Administradores |
| **Vendedor** | `https://TU-DOMINIO/vendedor` | Vendedores en campo (y también admins) |

1. Abre la dirección en tu navegador.
2. Escribe tu **correo** y **contraseña**.
3. Pulsa **Iniciar sesión**.

> Si olvidaste tu contraseña, pídele a un administrador que te la restablezca desde **Usuarios**.

---

## 2. Roles y qué puede hacer cada uno

| Rol | Etiqueta en pantalla | Qué puede hacer |
|-----|----------------------|-----------------|
| `super_admin` | **Super Administrador** | Todo, sin restricciones. |
| `admin` | **Administrador** | Todo en el panel admin + app móvil. |
| `seller` | **Vendedor** | Solo crear y ver sus propias remisiones, ver clientes y productos. |

---

## 3. Panel Administrador — vista general

Al entrar a `/admin` verás el **Tablero** (Dashboard) con un saludo y estadísticas rápidas: ventas de hoy, del mes, y la tabla de **Últimas remisiones** (puedes hacer clic en cualquier fila para abrir la remisión).

El menú lateral está organizado en grupos:

- **Ventas**: Remisiones, Reportes.
- **Catálogo**: Clientes, Catálogo de Productos.
- **App móvil**: Dispositivos móviles, Configuración móvil.
- **Configuración**: Usuarios, Empleados, Configuración (marca).

---

## 4. Gestionar clientes

Menú → **Catálogo › Clientes**.

### Crear un cliente nuevo

1. Pulsa **Nuevo cliente** (arriba a la derecha).
2. Completa el formulario. Campos principales:
   - **Nombre**, **NIT** (obligatorios).
   - **Nombre del encargado**, **Descripción**.
   - **Dirección**, **Ciudad**, **Teléfono**, **WhatsApp**, **Correo**.
   - **Redes sociales** (opcional).
   - **Punto de entrega** y **Tipo de pago** (listas desplegables).
   - **Inicio / Fin de contrato** (fechas, opcionales).
   - **Activo** (interruptor — déjalo encendido).
   - **Notas** (opcional).
   - **Logo** y **Contrato** (puedes subir imagen/PDF).
3. Pulsa **Crear**.

### ⭐ Qué pasa con los productos al crear un cliente

Al crear un cliente, el sistema le **asigna automáticamente todos los productos marcados como "por defecto"** del catálogo, con su **precio base** (sin recargo). No tienes que añadirlos uno por uno.

Esto significa que el cliente nuevo ya puede comprar cualquier producto estándar de inmediato. Si necesitas un **precio especial** para ese cliente, ve a la sección siguiente.

---

## 5. Productos del cliente y precios especiales

Dentro de un cliente (al **Editar** o **Ver** uno), encontrarás la pestaña/sección **Productos del Cliente**.

Ahí ves la lista de productos asignados a ese cliente. Por cada producto puedes editar:

- **Precio especial** (`custom_price`): si lo dejas **vacío**, el cliente paga el **precio base** del catálogo. Si pones un valor, ese cliente pagará ese precio en lugar del base.
- **Alias** (`custom_alias`): un nombre alternativo para ese producto, solo para este cliente (ej: "Hielo grande").
- **Disponible**: si lo apagas, ese producto no aparecerá al crear remisiones para este cliente.
- **Notas**.

### Ejemplo práctico

- El **Hielo 5 kg** cuesta $7.500 en el catálogo (precio base).
- Al "Cliente A" le das un precio especial de $6.500.
- Si mañana subes el precio base a $8.000, **el Cliente A sigue pagando $6.500** (su precio especial manda), pero todos los demás clientes pasan a $8.000 automáticamente.

> **Importante**: cambiar el precio base de un producto **NO** afecta a los clientes que tienen precio especial. Solo afecta a quienes usan el precio base.

---

## 6. Catálogo de productos

Menú → **Catálogo › Catálogo de Productos**.

Aquí vive **un solo registro por producto** (el catálogo maestro). Cambiar un precio aquí lo cambia para todos los clientes que usan el precio base.

### Crear / editar un producto

1. Pulsa **Nuevo producto** (o edita uno existente).
2. Campos:
   - **SKU** (código único), **Nombre**, **Descripción**.
   - **Categoría** (Hielo, Agua, Envase, Nevera, Otro).
   - **Unidad** (Unidad, Paquete, Caja, Bolsa).
   - **Precio base** (en pesos).
   - **Por defecto para clientes nuevos**: si lo activas, este producto se asigna automáticamente a cada cliente nuevo.
   - **Activo**: si lo apagas, deja de aparecer en remisiones.
3. Pulsa **Crear** / **Guardar**.

> Para cambiar el precio de un producto a todos los clientes: edita aquí el **Precio base** una sola vez. No hay que tocar cliente por cliente.

---

## 7. Crear una remisión paso a paso

Menú → **Ventas › Remisiones** → **Nueva remisión**.
(El vendedor lo hace desde su panel — ver sección 13.)

1. **Cliente**: selecciónalo de la lista. Al elegirlo, el sistema carga los productos disponibles de ese cliente con sus precios resueltos (especial o base).
2. **Tipo de pago**: Contado, Contado para facturación, Crédito, Obsequio, Otro.
3. **Ruta**: la ruta de entrega.
4. **Fecha y hora**: por defecto la actual; puedes ajustarla.
5. **Productos**: añade líneas con el botón de agregar. Por cada línea:
   - Elige el **Producto**.
   - Pon la **Cantidad**.
   - El **Precio unitario** se rellena solo con el precio del cliente; el **Subtotal** se calcula automático.
   - El **Total** de la remisión se suma en vivo.
6. **Observaciones** (opcional).
7. **GPS** (latitud/longitud, opcional — útil desde la app móvil).
8. **Firma digital del cliente**: tienes **dos opciones**:
   - **Subir imagen** de la firma.
   - **Firmar en pantalla**: dibuja la firma con el dedo/mouse en el recuadro (botones *Deshacer* y *Limpiar*).
9. Pulsa **Crear**.

### Qué pasa al crear una remisión confirmada

- Se guarda el **precio de cada producto en ese momento** (queda registrado aunque el precio cambie después).
- Se envía automáticamente un **correo con el PDF adjunto** a los buzones internos (según el tipo de pago) y al cliente.

---

## 8. Descargar PDF y reenviar copia

Abre una remisión (menú **Remisiones** → clic en la fila, o botón **Ver**).

- **Imprimir PDF**: descarga el comprobante en PDF. Si la empresa tiene logo configurado, aparece en el encabezado; si no, se usa un encabezado genérico.
- **Reenviar copia**: abre una ventana donde puedes escribir un **correo adicional (opcional)** y reenviar el comprobante. Esto **no crea una remisión nueva**, solo reenvía el correo.

---

## 9. Reportes

Menú → **Ventas › Reportes**.

Una sola pantalla con filtros combinables:

- **Desde** / **Hasta** (rango de fechas).
- **Cliente**.
- **Vendedor**.
- **Tipo de pago**.
- **Estado**.

Aplica los filtros que necesites y **descarga el reporte en Excel (XLSX)**. Los filtros se combinan (ej: "remisiones del Cliente A, a crédito, en mayo").

---

## 10. Usuarios, roles y permisos

Menú → **Configuración › Usuarios**.

### Crear un usuario

1. **Nuevo usuario**.
2. Nombre, correo, contraseña.
3. Asigna el **rol** (Vendedor / Administrador / Super Administrador).
4. **Crear**.

### Impersonar (entrar como otro usuario)

Solo el **Super Administrador** puede usar el botón **Iniciar sesión como** en un usuario. Sirve para reproducir un problema que reporta un vendedor sin pedirle la contraseña. Para volver a tu sesión, usa el enlace de "salir de la impersonación" que aparece arriba.

### Permisos

Los permisos se generan automáticamente por cada módulo. Un **Vendedor** solo ve lo mínimo (crear/ver sus remisiones, ver clientes y productos). Un **Administrador** ve todo. Si necesitas un rol intermedio personalizado, pídelo al equipo técnico.

---

## 11. Configuración de marca (branding)

Menú → **Configuración › Configuración**.

Aquí defines la identidad de la empresa que aparece en los PDF y correos:

- **Nombre de la empresa** y **eslogan**.
- **Teléfonos**, **ciudad**, **dirección**.
- **Logo** (se usa en el PDF de las remisiones).
- **Correos de enrutamiento**: a qué buzón llega cada tipo de venta (contado, crédito, facturación, buzón principal).

> Si no subes logo, los documentos usan un encabezado genérico con la inicial de la empresa.

---

## 12. Módulo de App móvil

Grupo **App móvil** en el menú.

### Dispositivos móviles

Lista de los dispositivos/sesiones conectados a la app móvil. Puedes:
- **Revocar** un dispositivo concreto (lo desconecta).
- **Revocar todos** (botón crítico — desconecta TODAS las sesiones móviles, incluida la tuya).

### Configuración móvil

Ajustes que la app móvil respeta sin necesidad de actualizarla:
- **Versión mínima recomendada** / **versión que fuerza actualización**.
- **Modo mantenimiento** + mensaje (bloquea la app temporalmente).
- **Anuncio** + mensaje (muestra un aviso en la app).
- **Base URL de la API** (a qué servidor se conecta la app).
- **TTL de tokens** (cuántos días dura una sesión).

Pulsa **Guardar cambios** para aplicar.

---

## 13. Panel del Vendedor

Dirección: `https://TU-DOMINIO/vendedor`.

Pensado para usar en campo (responsive, funciona en móvil/tablet). Al entrar verás directamente tus remisiones.

### Mis Remisiones

- Lista de **tus** remisiones (solo las tuyas).
- Tarjetas con estadísticas compactas: ventas de hoy, del mes, totales.
- Botón para **crear una nueva remisión** (mismo proceso que la sección 7).

### Mis Reportes

- Filtros por **fecha**, **tipo de pago**, **estado** y **cliente/negocio**.
- Descarga tus reportes en Excel — los mismos que también puedes bajar desde la app móvil.

---

## 14. Preguntas frecuentes

**¿Por qué un cliente no ve cierto producto al crear la remisión?**
Probablemente ese producto está marcado como **No disponible** para ese cliente (sección 5) o está **inactivo** en el catálogo (sección 6).

**Cambié el precio base de un producto y un cliente sigue con el precio viejo.**
Ese cliente tiene un **precio especial** configurado. El precio base solo afecta a quienes no tienen precio especial (sección 5).

**Creé una remisión y no llegó el correo.**
Revisa que el tipo de pago tenga un buzón configurado en **Configuración › Correos de enrutamiento** (sección 11). Si el problema persiste, avisa al equipo técnico (puede ser la cola de correos).

**Un vendedor no puede entrar al panel /admin.**
Es correcto: los vendedores solo entran a `/vendedor`. Solo Administradores y Super Administradores entran a `/admin`.

**Quiero que un vendedor deje de tener acceso.**
Desde **Usuarios**, quítale el rol o pídele al técnico que lo desactive (esto revoca también sus sesiones móviles).

---

*Documento de usuario final. Para detalles técnicos (API, despliegue, arquitectura) ver `README.md`, `DEPLOY.md` y `MOBILE-APP-CONTEXT.md`.*
