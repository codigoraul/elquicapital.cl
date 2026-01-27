# Configuración del Sistema de Contacto

## 📧 Sistema de Envío de Correos

El formulario de contacto de ProTrabajo utiliza PHP para enviar correos electrónicos. Este sistema está basado en el mismo que usa SERGEN.

## 📁 Archivos Creados

1. **`public/contacto.php`** - Script PHP que procesa el formulario y envía correos
2. **`public/contacto-config.php.example`** - Archivo de ejemplo para configuración personalizada
3. **`src/components/ContactInfo.astro`** - Componente actualizado con integración PHP

## ⚙️ Configuración

### Configuración Básica (Hardcoded)

Por defecto, el sistema usa estos valores (definidos en `contacto.php`):

```php
$TO_EMAILS_BASE = 'contacto@protrabajo.cl';
$FROM_EMAIL = 'contacto@protrabajo.cl';
$FROM_NAME = 'ProTrabajo';
```

### Configuración Personalizada (Recomendado)

Para configuración personalizada en producción:

1. Copia el archivo de ejemplo:
   ```bash
   cp public/contacto-config.php.example public/contacto-config.php
   ```

2. Edita `public/contacto-config.php` con tus datos:
   ```php
   <?php
   return [
     'SITE_URL' => 'https://protrabajo.cl',
     'TO_EMAIL' => 'contacto@protrabajo.cl, rodrigo@protrabajo.cl',
     'FROM_EMAIL' => 'contacto@protrabajo.cl',
     'FROM_NAME' => 'ProTrabajo - Abogados Laborales',
     'BCC_EMAILS' => 'codigoraul@gmail.com', // Opcional
   ];
   ```

3. **Importante:** Agrega `contacto-config.php` al `.gitignore` para no subir credenciales:
   ```
   public/contacto-config.php
   ```

### Variables de Entorno (Opcional)

También puedes usar variables de entorno:

```bash
SITE_URL=https://protrabajo.cl
CONTACT_TO_EMAIL=contacto@protrabajo.cl
CONTACT_FROM_EMAIL=contacto@protrabajo.cl
CONTACT_FROM_NAME=ProTrabajo
CONTACT_BCC_EMAILS=codigoraul@gmail.com
```

## 🔒 Protección Anti-Spam

El formulario incluye un campo honeypot (`_gotcha`) que protege contra bots:

```html
<input type="text" name="_gotcha" class="honeypot" tabindex="-1" autocomplete="off" />
```

Si un bot llena este campo, el formulario redirige a success sin enviar el correo.

## 📝 Campos del Formulario

- **nombre** (requerido) - Nombre completo del cliente
- **email** (requerido) - Email del cliente
- **telefono** (opcional) - Teléfono de contacto
- **asunto** (opcional) - Asunto de la consulta
- **mensaje** (requerido) - Mensaje o descripción del caso

## ✅ Estados de Respuesta

El sistema redirige con parámetros de estado:

- `?status=success` - Mensaje enviado correctamente
- `?status=missing_fields` - Faltan campos obligatorios
- `?status=invalid_email` - Email inválido
- `?status=mail_failed` - Error al enviar el correo

## 🧪 Testing

Para verificar la configuración:

```
https://tudominio.cl/contacto.php?debug=1
```

Esto mostrará la configuración actual en formato JSON (sin enviar correos).

## 🚀 Despliegue

### Requisitos del Servidor

- PHP 7.4 o superior
- Función `mail()` habilitada
- Permisos de escritura en el directorio (para logs si los implementas)

### Build de Astro

El archivo `contacto.php` debe estar en la carpeta `public/` para que se copie automáticamente a `dist/` durante el build:

```bash
npm run build
```

Esto copiará:
- `public/contacto.php` → `dist/contacto.php`
- `public/contacto-config.php.example` → `dist/contacto-config.php.example`

### Subir a Producción

1. Sube todos los archivos del `dist/`
2. Crea `contacto-config.php` en el servidor con tus credenciales
3. Verifica que PHP tenga permisos para enviar correos
4. Prueba el formulario

## 🔧 Troubleshooting

### El correo no llega

1. Verifica que la función `mail()` esté habilitada en PHP
2. Revisa los logs del servidor
3. Verifica que el dominio tenga registros SPF/DKIM configurados
4. Prueba con un servicio SMTP externo si es necesario

### Error "mail_failed"

- El servidor no pudo enviar el correo
- Verifica la configuración de PHP
- Contacta a tu proveedor de hosting

### Los correos van a spam

- Configura registros SPF, DKIM y DMARC en tu dominio
- Usa un email del mismo dominio como remitente
- Considera usar un servicio SMTP como SendGrid, Mailgun, etc.

## 📧 Formato del Correo

El correo se envía en formato HTML y texto plano (multipart):

**Asunto:** "Nueva consulta desde protrabajo.cl" o "Consulta: [asunto]"

**Contenido:**
- Nombre del cliente
- Email
- Teléfono (si se proporciona)
- Asunto (si se proporciona)
- Mensaje

**Reply-To:** Se configura automáticamente al email del cliente

## 🎨 Personalización

Para personalizar el diseño del correo, edita la variable `$bodyHtml` en `contacto.php` (líneas 189-205).

## 📚 Recursos

- Basado en el sistema de SERGEN (`/SGN landing/landing SERGEN`)
- Compatible con Astro static site generation
- Funciona con cualquier hosting que soporte PHP
