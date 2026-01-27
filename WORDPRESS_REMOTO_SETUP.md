# Configuración de WordPress Remoto para ProTrabajo

## 🎯 URL Configurada

- **WordPress Headless:** `https://protrabajo.cl/admin/`
- **API REST:** `https://protrabajo.cl/admin/wp-json/wp/v2`
- **Landing Page:** `https://protrabajo.cl/prueba/`

## ✅ Estado Actual

✅ WordPress remoto está funcionando
✅ API REST está activa
❌ Custom Post Types no están configurados aún
❌ Datos no están migrados

## 📋 Pasos para Completar la Configuración

### 1. Instalar Plugins Necesarios

Accede a tu WordPress en `https://protrabajo.cl/admin/wp-admin` e instala:

#### A. Custom Post Type UI
1. Ve a **Plugins → Añadir nuevo**
2. Busca "Custom Post Type UI"
3. Instala y activa

#### B. Advanced Custom Fields (ACF) PRO
1. Descarga ACF PRO desde tu cuenta
2. Ve a **Plugins → Añadir nuevo → Subir plugin**
3. Sube el ZIP de ACF PRO
4. Activa el plugin

### 2. Crear Custom Post Types

#### A. Crear Post Type "Servicio"

1. Ve a **CPT UI → Añadir/Editar Post Types**
2. Configura:
   - **Slug:** `servicio`
   - **Plural Label:** `Servicios`
   - **Singular Label:** `Servicio`
   - **Tiene archivo:** `true`
   - **Mostrar en REST API:** `true` ✅ (MUY IMPORTANTE)
   - **REST API base slug:** `servicio`

3. En "Soportes", marca:
   - ✅ Título
   - ✅ Editor
   - ✅ Imagen destacada
   - ✅ Extracto

4. Guarda el Post Type

#### B. Crear Post Type "Testimonio"

1. Ve a **CPT UI → Añadir/Editar Post Types**
2. Configura:
   - **Slug:** `testimonio`
   - **Plural Label:** `Testimonios`
   - **Singular Label:** `Testimonio`
   - **Tiene archivo:** `false`
   - **Mostrar en REST API:** `true` ✅ (MUY IMPORTANTE)
   - **REST API base slug:** `testimonio`

3. En "Soportes", marca:
   - ✅ Título
   - ✅ Editor
   - ✅ Imagen destacada

4. Guarda el Post Type

#### C. Crear Post Type "Contact Info"

1. Ve a **CPT UI → Añadir/Editar Post Types**
2. Configura:
   - **Slug:** `contact-info`
   - **Plural Label:** `Contact Info`
   - **Singular Label:** `Contact Info`
   - **Tiene archivo:** `false`
   - **Mostrar en REST API:** `true` ✅ (MUY IMPORTANTE)
   - **REST API base slug:** `contact-info`

3. En "Soportes", marca:
   - ✅ Título
   - ✅ Editor

4. Guarda el Post Type

### 3. Configurar Campos ACF

#### A. Campos para "Servicio"

1. Ve a **ACF → Grupos de campos → Añadir nuevo**
2. Título: "Campos de Servicio"
3. Agrega estos campos:
   - **icono** (Text)
   - **descripcion_corta** (Textarea)
   - **orden** (Number)

4. En "Ubicación", selecciona:
   - Tipo de entrada = Servicio

5. En "Configuración", marca:
   - ✅ Mostrar en REST API

6. Guarda

#### B. Campos para "Testimonio"

1. Ve a **ACF → Grupos de campos → Añadir nuevo**
2. Título: "Campos de Testimonio"
3. Agrega estos campos:
   - **nombre** (Text)
   - **cargo** (Text)
   - **empresa** (Text)
   - **testimonio** (Textarea)
   - **foto** (Image)

4. En "Ubicación", selecciona:
   - Tipo de entrada = Testimonio

5. En "Configuración", marca:
   - ✅ Mostrar en REST API

6. Guarda

#### C. Campos para "Contact Info"

1. Ve a **ACF → Grupos de campos → Añadir nuevo**
2. Título: "Información de Contacto"
3. Agrega estos campos:
   - **email** (Email)
   - **telefono** (Text)
   - **direccion** (Text)
   - **horario** (Textarea)

4. En "Ubicación", selecciona:
   - Tipo de entrada = Contact Info

5. En "Configuración", marca:
   - ✅ Mostrar en REST API

6. Guarda

### 4. Migrar Datos desde WordPress Local

#### Opción A: Exportar/Importar (Recomendado)

**En WordPress Local:**
1. Ve a **Herramientas → Exportar**
2. Selecciona "Todo el contenido"
3. Descarga el archivo XML

**En WordPress Remoto:**
1. Ve a **Herramientas → Importar**
2. Instala "WordPress Importer" si no lo tienes
3. Sube el archivo XML
4. Marca "Descargar e importar archivos adjuntos"
5. Ejecuta la importación

#### Opción B: Copiar Manualmente

Si tienes pocos elementos, créalos manualmente:

**Servicios:**
1. Ve a **Servicios → Añadir nuevo**
2. Crea cada servicio con su título, descripción e icono

**Testimonios:**
1. Ve a **Testimonios → Añadir nuevo**
2. Crea cada testimonio con nombre, cargo, testimonio y foto

**Contact Info:**
1. Ve a **Contact Info → Añadir nuevo**
2. Crea un único elemento con email, teléfono, dirección y horario

### 5. Verificar la API

Abre estas URLs en tu navegador para verificar:

```
https://protrabajo.cl/admin/wp-json/wp/v2/servicio
https://protrabajo.cl/admin/wp-json/wp/v2/testimonio
https://protrabajo.cl/admin/wp-json/wp/v2/contact-info
```

Deberías ver JSON con tus datos.

### 6. Configurar CORS (Importante)

Como WordPress está en `/admin/` y la landing en `/prueba/`, necesitas habilitar CORS.

Agrega esto al `functions.php` de tu tema activo:

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

### 7. Probar Localmente

```bash
# En tu proyecto Astro
npm run dev

# Visita http://localhost:4321
# Deberías ver los servicios y testimonios cargados desde el WordPress remoto
```

### 8. Build y Deploy a /prueba/

```bash
# Build del proyecto
npm run build

# Los archivos estarán en dist/
# Sube todo el contenido de dist/ a https://protrabajo.cl/prueba/
```

## 🔧 Configuración de Astro para Subdirectorio

Si vas a desplegar en `/prueba/`, necesitas configurar el base path en Astro:

**`astro.config.mjs`:**
```javascript
export default defineConfig({
  base: '/prueba',
  // ... resto de configuración
});
```

Luego ejecuta el script `fix-paths.js` después del build (ya está configurado en `package.json`).

## 📝 Archivos de Configuración Actualizados

✅ **`.env`** - Creado con la URL correcta (no se sube a git)
✅ **`.env.example`** - Actualizado con la URL remota
✅ **`src/lib/wordpress.js`** - Ya está configurado para usar la variable de entorno

## 🧪 Testing Endpoints

Para verificar cada endpoint:

```bash
# Servicios
curl https://protrabajo.cl/admin/wp-json/wp/v2/servicio

# Testimonios
curl https://protrabajo.cl/admin/wp-json/wp/v2/testimonio

# Contact Info
curl https://protrabajo.cl/admin/wp-json/wp/v2/contact-info
```

## ⚠️ Checklist Final

Antes de hacer el deploy, verifica:

- [ ] Custom Post Types creados y con REST API habilitada
- [ ] Campos ACF configurados y con REST API habilitada
- [ ] Datos migrados o creados
- [ ] CORS habilitado en functions.php
- [ ] Endpoints de API funcionando (prueba con curl o navegador)
- [ ] `.env` configurado localmente
- [ ] `astro.config.mjs` con base: '/prueba' si es necesario
- [ ] Build exitoso: `npm run build`
- [ ] Archivos de `dist/` subidos a `/prueba/`

## 🚀 URLs Finales

Una vez completado:

- **WordPress Admin:** https://protrabajo.cl/admin/wp-admin
- **API REST:** https://protrabajo.cl/admin/wp-json/wp/v2
- **Landing Page:** https://protrabajo.cl/prueba/

## 📞 Troubleshooting

### Los custom post types no aparecen en la API

- Verifica que "Mostrar en REST API" esté en `true`
- Ve a **Ajustes → Enlaces permanentes** y haz clic en "Guardar cambios" (esto regenera las reglas de reescritura)

### Error 404 en los endpoints

- Regenera los permalinks (Ajustes → Enlaces permanentes → Guardar)
- Verifica que los slugs sean exactos: `servicio`, `testimonio`, `contact-info`

### Los campos ACF no aparecen en la API

- Verifica que "Mostrar en REST API" esté marcado en la configuración del grupo de campos
- Actualiza ACF a la última versión

### Error de CORS

- Agrega el código de CORS al functions.php
- Limpia la caché del navegador
- Verifica que el código esté en el functions.php del tema activo

## 📚 Recursos

- [WordPress REST API Handbook](https://developer.wordpress.org/rest-api/)
- [Custom Post Type UI Documentation](https://docs.pluginize.com/article/93-custom-post-type-ui)
- [ACF REST API Documentation](https://www.advancedcustomfields.com/resources/wp-rest-api-integration/)
