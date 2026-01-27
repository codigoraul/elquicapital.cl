# Migración de WordPress Local a Remoto

## 📋 Guía Paso a Paso

### 1. Preparar WordPress Remoto

Antes de migrar, asegúrate de que tu WordPress remoto tenga:

✅ **Plugins instalados:**
- Advanced Custom Fields (ACF) PRO
- Custom Post Type UI (para custom post types)

✅ **Custom Post Types creados:**
- `servicio` (Servicios)
- `testimonio` (Testimonios)
- `contact-info` (Información de Contacto)

✅ **Campos ACF configurados:**
- Para `servicio`: título, descripción, icono
- Para `testimonio`: nombre, cargo, testimonio, foto
- Para `contact-info`: email, teléfono, dirección, horario

### 2. Exportar Datos de WordPress Local

#### Opción A: Usar WordPress Export (Recomendado)

1. En tu WordPress local, ve a **Herramientas → Exportar**
2. Selecciona "Todo el contenido" o específicamente:
   - Servicios
   - Testimonios
   - Contact Info
3. Descarga el archivo XML

#### Opción B: Usar Plugin de Migración

Instala **All-in-One WP Migration** en ambos WordPress (local y remoto):
1. En local: **All-in-One WP Migration → Export**
2. Descarga el archivo
3. En remoto: **All-in-One WP Migration → Import**
4. Sube el archivo

### 3. Importar Datos en WordPress Remoto

1. En WordPress remoto, ve a **Herramientas → Importar**
2. Instala el importador de WordPress si no lo tienes
3. Sube el archivo XML exportado
4. Asigna los autores
5. Marca "Descargar e importar archivos adjuntos" (para imágenes)
6. Ejecuta la importación

### 4. Verificar la API REST de WordPress Remoto

Visita estas URLs en tu navegador para verificar que la API funciona:

```
https://TU-DOMINIO.cl/wp-json/wp/v2/servicio
https://TU-DOMINIO.cl/wp-json/wp/v2/testimonio
https://TU-DOMINIO.cl/wp-json/wp/v2/contact-info
```

Deberías ver JSON con tus datos.

### 5. Configurar Variables de Entorno en Astro

#### Para Desarrollo Local:

Crea un archivo `.env` en la raíz del proyecto:

```bash
# .env
WORDPRESS_API_URL=https://TU-DOMINIO.cl/wp-json/wp/v2
```

**IMPORTANTE:** Agrega `.env` al `.gitignore` para no subir credenciales:

```
# .gitignore
.env
```

#### Para Producción:

Configura la variable de entorno en tu servicio de hosting:

**Netlify:**
- Site settings → Environment variables
- Agrega: `WORDPRESS_API_URL` = `https://TU-DOMINIO.cl/wp-json/wp/v2`

**Vercel:**
- Project Settings → Environment Variables
- Agrega: `WORDPRESS_API_URL` = `https://TU-DOMINIO.cl/wp-json/wp/v2`

**GitHub Actions (para FTP):**
- Repository Settings → Secrets and variables → Actions
- Agrega: `WORDPRESS_API_URL` = `https://TU-DOMINIO.cl/wp-json/wp/v2`

### 6. Actualizar .env.example

Actualiza el archivo `.env.example` con la nueva URL:

```bash
# WordPress API Configuration
WORDPRESS_API_URL=https://TU-DOMINIO.cl/wp-json/wp/v2
```

### 7. Probar Localmente

```bash
# Instalar dependencias si es necesario
npm install

# Modo desarrollo
npm run dev

# Verificar que los servicios y testimonios se cargan correctamente
```

### 8. Build y Deploy

```bash
# Build del proyecto
npm run build

# El build generará archivos estáticos en dist/
# Sube estos archivos a tu hosting
```

## 🔧 Configuración de CORS (si es necesario)

Si tu WordPress está en un dominio diferente al de la landing, necesitas habilitar CORS.

Agrega esto al `functions.php` de tu tema en WordPress:

```php
// Habilitar CORS para la API REST
add_action('rest_api_init', function() {
  remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
  add_filter('rest_pre_serve_request', function($value) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Authorization, Content-Type');
    return $value;
  });
}, 15);
```

## 📝 URLs de Ejemplo

### WordPress en Subdominio:
```
WORDPRESS_API_URL=https://admin.protrabajo.cl/wp-json/wp/v2
```

### WordPress en Subdirectorio:
```
WORDPRESS_API_URL=https://protrabajo.cl/wp/wp-json/wp/v2
```

### WordPress en Dominio Diferente:
```
WORDPRESS_API_URL=https://cms.protrabajo.cl/wp-json/wp/v2
```

## 🧪 Testing

Para verificar que todo funciona:

1. **Desarrollo local:**
   ```bash
   npm run dev
   # Visita http://localhost:4321
   ```

2. **Verificar servicios:**
   - Deberías ver los servicios en la sección "Nuestros Servicios"

3. **Verificar testimonios:**
   - Deberían aparecer 3 testimonios

4. **Verificar info de contacto:**
   - Email, teléfono, dirección y horario correctos

## ⚠️ Troubleshooting

### Los datos no se cargan

1. Verifica que la URL de la API sea correcta
2. Verifica que WordPress esté accesible públicamente
3. Revisa la consola del navegador para errores
4. Verifica que los custom post types estén registrados en WordPress

### Error de CORS

- Agrega el código de CORS al `functions.php` (ver arriba)
- O usa un plugin como "WP CORS"

### Imágenes no se cargan

- Verifica que las imágenes se hayan importado correctamente
- Verifica que las URLs de las imágenes sean absolutas
- Revisa los permisos de la carpeta `wp-content/uploads/`

## 📚 Archivos de Configuración

Los archivos que usa el sistema:

- **`src/lib/wordpress.js`** - Cliente de la API de WordPress
- **`.env`** - Variables de entorno (local, no se sube a git)
- **`.env.example`** - Ejemplo de configuración
- **`astro.config.mjs`** - Configuración de Astro

## 🔐 Seguridad

- ✅ Nunca subas el archivo `.env` a git
- ✅ Usa HTTPS para la API de WordPress
- ✅ Considera usar autenticación si los datos son sensibles
- ✅ Limita el acceso a la API REST si es necesario

## 📞 Soporte

Si tienes problemas con la migración:

1. Verifica los logs de WordPress
2. Usa las herramientas de desarrollo del navegador
3. Prueba las URLs de la API directamente en el navegador
4. Revisa la documentación de WordPress REST API
