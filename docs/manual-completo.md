# Manual de Usuario — SVD
### Sistema de Ventas y Despachos

Bienvenido a SVD, la plataforma para registrar ventas, gestionar clientes y productos, y llevar el control de tus despachos. Este manual está escrito para cualquier persona, sin necesidad de conocimientos técnicos.

---

## Cuentas para entrar (demo)

Mientras el sistema está en pruebas, puedes usar estas cuentas:

| Para qué sirve | Dirección de entrada | Correo | Contraseña |
|----------------|----------------------|--------|------------|
| **Administrador** (ve y maneja todo) | `svd.sytes.net/admin` | `admin@svd.test` | `admin` |
| **Vendedor** (registra ventas en campo) | `svd.sytes.net/vendedor` | `vendedor@svd.test` | `vendedor` |
| **Super Administrador** | `svd.sytes.net/admin` | `superadmin@svd.test` | `Super/Admin?` |

> 🔒 **Importante:** estas son cuentas de prueba. Cuando el sistema entre en uso real, pide a tu administrador que te cree tu propia cuenta y **cambia estas contraseñas**.

---

## Índice

1. [¿Qué es SVD y para qué sirve?](#1-qué-es-svd)
2. [Entrar al sistema](#2-entrar-al-sistema)
3. [Conocer la pantalla principal (Tablero)](#3-el-tablero)
4. [Clientes: cómo registrarlos](#4-clientes)
5. [Precios especiales por cliente](#5-precios-especiales)
6. [Productos: el catálogo](#6-productos)
7. [Hacer una venta (remisión) paso a paso](#7-hacer-una-venta)
8. [Imprimir y reenviar el comprobante](#8-comprobante)
9. [Ver reportes y descargarlos a Excel](#9-reportes)
10. [Empleados](#10-empleados)
11. [Crear usuarios y dar permisos](#11-usuarios)
12. [Personalizar la marca de la empresa](#12-marca)
13. [La aplicación móvil](#13-app-movil)
14. [El vendedor en la calle](#14-vendedor)
15. [Preguntas frecuentes](#15-preguntas-frecuentes)

---

## 1. ¿Qué es SVD?

SVD es donde tu empresa lleva el control de:

- **Quiénes son tus clientes** y qué les vendes.
- **Qué productos** ofreces y a qué precio.
- **Cada venta** que haces (a esto le llamamos "remisión"): qué se vendió, a quién, quién lo vendió, cómo se pagó y la firma del cliente.
- **Reportes** para saber cómo va el negocio: quién vende más, qué cliente compra más, qué producto sale más.

Hay dos formas de usarlo:
- Desde el **computador** (administradores y, si quieren, vendedores).
- Desde el **celular** con la app móvil (vendedores en la calle).

---

## 2. Entrar al sistema

1. Abre tu navegador (Chrome, Edge, etc.).
2. Escribe la dirección según tu rol:
   - Administradores: **svd.sytes.net/admin**
   - Vendedores: **svd.sytes.net/vendedor**
3. Escribe tu **correo** y **contraseña**.
4. Haz clic en **Entrar**.

> Si te equivocas de contraseña varias veces, espera un minuto antes de volver a intentar (es una protección de seguridad).

> ¿Olvidaste tu contraseña? Pídele al administrador que te la restablezca.

---

## 3. El Tablero

Apenas entras como administrador, ves el **Tablero**: un resumen del negocio con un saludo y varias **gráficas**:

- **Ventas de los últimos 30 días**: una línea que sube y baja según las ventas de cada día.
- **Vendedores con más ventas**: quién está vendiendo más.
- **Empresas que más compran**: tus mejores clientes.
- **Productos más vendidos**: qué sale más.
- **Ventas por tipo de pago**: cuánto es de contado, crédito, etc.
- **Ventas por ruta**: qué ruta de entrega mueve más.
- **Últimas remisiones**: las ventas más recientes. Puedes hacer clic en cualquiera para verla.

El menú de la izquierda tiene todo lo demás, agrupado en: **Ventas**, **Catálogo**, **App móvil** y **Configuración**.

---

## 4. Clientes

Aquí registras a quién le vendes. Menú → **Catálogo → Clientes**.

### Registrar un cliente nuevo

1. Haz clic en **Nuevo cliente**.
2. Llena los datos:
   - **Nombre** y **NIT** (obligatorios).
   - Nombre del encargado, dirección, ciudad, teléfono, WhatsApp, correo.
   - **Punto de entrega** y **Tipo de pago** (eliges de una lista).
   - Puedes subir el **logo** del cliente y su **contrato** si lo tienes.
3. Haz clic en **Crear**.

### 💡 Algo importante

Cuando creas un cliente nuevo, **el sistema le asigna automáticamente todos los productos** que vendes normalmente, con su precio normal. Así puedes venderle de una vez, sin tener que agregar producto por producto.

---

## 5. Precios especiales

A veces un cliente tiene un precio distinto al normal (un descuento, una tarifa pactada). Para eso:

1. Entra a editar (o ver) el cliente.
2. Busca la sección **Productos del Cliente**.
3. Por cada producto puedes poner:
   - **Precio especial**: si lo dejas vacío, paga el precio normal. Si pones un valor, ese cliente paga ese precio.
   - **Nombre alternativo**: por si ese cliente llama distinto a un producto.
   - **Disponible**: apágalo si no quieres ofrecerle cierto producto.

### Ejemplo fácil de entender

- El Hielo de 5 kg vale $7.500 normalmente.
- Al "Cliente A" le pones precio especial de $6.500.
- Si mañana subes el precio normal a $8.000, **el Cliente A sigue pagando $6.500**, y todos los demás pasan a $8.000.

En pocas palabras: **el precio especial de un cliente nunca se ve afectado** cuando cambias el precio general.

---

## 6. Productos

Es tu catálogo: la lista de todo lo que vendes. Menú → **Catálogo → Catálogo de Productos**.

### Crear o cambiar un producto

1. Haz clic en **Nuevo producto** (o edita uno).
2. Llena: código, nombre, descripción, categoría (Hielo, Agua, etc.), unidad (unidad, paquete, caja, bolsa) y **precio**.
3. Si marcas **"Por defecto para clientes nuevos"**, ese producto se asignará solo a cada cliente nuevo.
4. Guarda.

> Para subir el precio de un producto a TODOS los clientes: cámbialo aquí una sola vez. No tienes que entrar cliente por cliente.

---

## 7. Hacer una venta

A una venta le llamamos **remisión**. Menú → **Ventas → Remisiones → Nueva remisión**.
(El vendedor lo hace desde su panel o desde el celular — ver secciones 13 y 14.)

1. **Elige el cliente.** El sistema carga los productos disponibles de ese cliente con su precio correcto.
2. Elige el **tipo de pago** (Contado, Crédito, etc.).
3. Elige la **ruta** de entrega.
4. La **fecha y hora** ya viene puesta; puedes cambiarla.
5. **Agrega los productos**: por cada uno eliges el producto y la cantidad. El precio y el subtotal se calculan solos. El **total** se va sumando.
6. Escribe **observaciones** si las necesitas.
7. **Firma del cliente**: tienes dos opciones:
   - Subir una **foto** de la firma.
   - **Firmar en la pantalla** (dibujar con el dedo o el mouse, con botones para deshacer y limpiar).
8. Haz clic en **Crear**.

### ¿Qué pasa después?

- La venta queda guardada con el precio de ese momento (aunque el precio cambie después, la venta conserva el precio que tenía).
- Se envía **automáticamente un correo con el comprobante** a la empresa y al cliente.

---

## 8. El comprobante

Cuando abres una remisión (Menú → Remisiones → clic en una, o el botón **Ver**):

- **Imprimir PDF**: descarga el comprobante. Si configuraste el logo de la empresa, aparece arriba; si no, sale uno genérico.
- **Reenviar copia**: abre una ventana para escribir un correo adicional y reenviar el comprobante. Esto **no crea otra venta**, solo reenvía el correo.

---

## 9. Reportes

Menú → **Ventas → Reportes**.

Una sola pantalla donde combinas filtros para ver justo lo que necesitas:

- **Desde** / **Hasta** (fechas).
- **Cliente**.
- **Vendedor**.
- **Tipo de pago**.
- **Estado**.

Luego **descargas el reporte en Excel**. Ejemplo: "todas las ventas a crédito del Cliente A en mayo".

---

## 10. Empleados

Menú → **Configuración → Empleados**. Aquí llevas la ficha de tu personal: cédula, cargo, teléfono, EPS, ARL, cuenta bancaria, documentos, etc.

> Un empleado **no necesita** tener una cuenta para entrar al sistema. Puedes registrar personal que no usa la plataforma: solo deja vacío el campo "Usuario del sistema" y escribe su nombre completo.

> Si el empleado sí usa el sistema (por ejemplo un vendedor), lo vinculas a su cuenta de acceso.

---

## 11. Usuarios

Menú → **Configuración → Usuarios**. Aquí están las cuentas que pueden entrar al sistema.

### Crear un usuario

1. **Nuevo usuario**.
2. Nombre, correo, contraseña.
3. Elige el **rol**: Vendedor, Administrador o Super Administrador.
4. Crear.

### ¿Qué puede hacer cada rol?

- **Vendedor**: solo registra y ve sus propias ventas; ve clientes y productos.
- **Administrador**: maneja todo el sistema.
- **Super Administrador**: igual que el administrador, y además puede "entrar como" otro usuario para ayudarlo a resolver un problema, sin pedirle la contraseña.

---

## 12. Marca de la empresa

Menú → **Configuración → Configuración**. Aquí defines cómo se ve tu empresa en los comprobantes y correos:

- Nombre y eslogan.
- Teléfonos, ciudad, dirección.
- **Logo** (sale en los PDF).
- A qué correos llega cada tipo de venta.

> Si no subes logo, los comprobantes usan un encabezado genérico.

---

## 13. La aplicación móvil

Los vendedores pueden trabajar desde el **celular** con la app móvil. Con ella:

- Ven la lista de clientes y sus productos con el precio correcto.
- Registran la venta en el momento, en casa del cliente.
- Capturan la **firma** en la pantalla del celular.
- Envían la venta al sistema, que manda el comprobante por correo.
- Pueden descargar sus reportes.

Los administradores pueden, además, desde la app: ver todas las ventas, gestionar clientes/productos, ver los dispositivos conectados y la configuración.

> La app se conecta al mismo sistema, así que todo lo que se registra en el celular aparece de inmediato en el computador.

---

## 14. El vendedor en la calle

Si eres vendedor, tu día con SVD es así:

1. Entras a **svd.sytes.net/vendedor** (o abres la app en el celular).
2. Ves directamente **tus ventas** y unas tarjetas con tus números: ventas de hoy, del mes y totales.
3. Tocas **Nueva remisión** para registrar una venta.
4. Eliges el cliente, agregas los productos, el cliente firma en la pantalla, y envías.
5. En **Mis Reportes** puedes filtrar tus ventas (por fecha, tipo de pago, cliente) y descargarlas a Excel.

Solo ves **tus** ventas, no las de otros vendedores.

---

## 15. Preguntas frecuentes

**Un cliente no ve cierto producto al hacerle una venta.**
Ese producto está marcado como "No disponible" para ese cliente, o está inactivo en el catálogo.

**Cambié el precio de un producto pero un cliente sigue con el precio viejo.**
Ese cliente tiene un precio especial. El precio general no afecta a quienes tienen precio especial.

**Hice una venta y el cliente no recibió el correo.**
Revisa con el administrador que los correos de la empresa estén bien configurados.

**Soy vendedor y no me deja entrar a svd.sytes.net/admin.**
Es normal: los vendedores entran por **svd.sytes.net/vendedor**.

**Quiero que un empleado deje de tener acceso.**
El administrador puede quitarle el acceso desde Usuarios; eso también lo desconecta del celular.

**¿Las ventas viejas cambian si subo los precios?**
No. Cada venta guarda el precio que tenía cuando se hizo.

---

*¿Necesitas la versión rápida de una página? Pide la **Guía Rápida** (`guia-rapida.md`).*
