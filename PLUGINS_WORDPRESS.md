# Plugins Necesarios para ProTrabajo WordPress

## 📦 Plugins a Instalar

### 1. Advanced Custom Fields (ACF)
**Plugin recomendado**: Advanced Custom Fields PRO (o versión gratuita)

#### Instalación:
1. En WordPress admin, ve a **Plugins → Añadir nuevo**
2. Busca: `Advanced Custom Fields`
3. Instala y activa **Advanced Custom Fields** (versión gratuita)
   - O si tienes licencia PRO: sube el archivo ZIP de ACF PRO

**Alternativa gratuita**: Si no tienes ACF PRO, puedes usar la versión gratuita, pero necesitarás crear los campos manualmente en cada post.

### 2. Custom Post Type UI (CPT UI)
**Plugin**: Custom Post Type UI

#### Instalación:
1. En WordPress admin, ve a **Plugins → Añadir nuevo**
2. Busca: `Custom Post Type UI`
3. Instala y activa **Custom Post Type UI**

**Alternativa**: Puedes crear los Custom Post Types manualmente con código (ver más abajo).

---

## 🔧 Configuración Paso a Paso

### OPCIÓN A: Usando Plugins (Recomendado para principiantes)

#### Paso 1: Crear Custom Post Type "Servicios"

1. Ve a **CPT UI → Add/Edit Post Types**
2. Configura:
   - **Post Type Slug**: `servicio`
   - **Plural Label**: `Servicios`
   - **Singular Label**: `Servicio`
3. En **Settings**:
   - ✅ Marca: **Has Archive**
   - ✅ Marca: **Show in REST API** (MUY IMPORTANTE)
   - **REST API base slug**: `servicio`
4. En **Supports**:
   - ✅ Title
   - ✅ Editor
   - ✅ Featured Image
   - ✅ Excerpt
5. Click **Add Post Type**

#### Paso 2: Crear Custom Post Type "Testimonios"

1. Ve a **CPT UI → Add/Edit Post Types**
2. Configura:
   - **Post Type Slug**: `testimonio`
   - **Plural Label**: `Testimonios`
   - **Singular Label**: `Testimonio`
3. En **Settings**:
   - ✅ Marca: **Has Archive**
   - ✅ Marca: **Show in REST API** (MUY IMPORTANTE)
   - **REST API base slug**: `testimonio`
4. En **Supports**:
   - ✅ Title
   - ✅ Editor
   - ✅ Featured Image
5. Click **Add Post Type**

#### Paso 3: Configurar Campos ACF para Testimonios

1. Ve a **ACF → Field Groups → Add New**
2. Nombre del grupo: `Datos del Testimonio`
3. Agrega campo:
   - **Field Label**: `Cargo`
   - **Field Name**: `cargo`
   - **Field Type**: `Text`
4. En **Location Rules**:
   - Post Type **is equal to** `testimonio`
5. En **Settings**:
   - ✅ Marca: **Show in REST API** (key: `acf`)
6. Click **Publish**

#### Paso 4: Configurar Options Page para Contacto (ACF PRO)

**Si tienes ACF PRO:**

1. Ve a **ACF → Options Pages → Add New**
2. Configura:
   - **Page Title**: `Información de Contacto`
   - **Menu Title**: `Contacto`
   - **Menu Slug**: `contact-info`
3. Guarda

4. Ve a **ACF → Field Groups → Add New**
5. Nombre del grupo: `Datos de Contacto`
6. Agrega estos campos:
   - **Email**
     - Field Label: `Email`
     - Field Name: `email`
     - Field Type: `Email`
   - **Teléfono**
     - Field Label: `Teléfono`
     - Field Name: `telefono`
     - Field Type: `Text`
   - **Dirección**
     - Field Label: `Dirección`
     - Field Name: `direccion`
     - Field Type: `Text Area`
   - **Horario**
     - Field Label: `Horario`
     - Field Name: `horario`
     - Field Type: `Text`
7. En **Location Rules**:
   - Options Page **is equal to** `contact-info`
8. En **Settings**:
   - ✅ Marca: **Show in REST API**
9. Click **Publish**

**Si NO tienes ACF PRO:**
Puedes usar el plugin **Options Framework** o crear una página normal de WordPress con los datos de contacto.

---

### OPCIÓN B: Usando Código (Para desarrolladores)

Si prefieres no usar plugins, agrega este código al `functions.php` de tu tema:

```php
<?php
// Crear Custom Post Types
function protrabajo_register_post_types() {
    // Servicios
    register_post_type('servicio', array(
        'labels' => array(
            'name' => 'Servicios',
            'singular_name' => 'Servicio',
            'add_new' => 'Añadir Servicio',
            'add_new_item' => 'Añadir Nuevo Servicio',
            'edit_item' => 'Editar Servicio',
            'all_items' => 'Todos los Servicios',
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'rest_base' => 'servicio',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-portfolio',
        'rewrite' => array('slug' => 'servicios'),
    ));

    // Testimonios
    register_post_type('testimonio', array(
        'labels' => array(
            'name' => 'Testimonios',
            'singular_name' => 'Testimonio',
            'add_new' => 'Añadir Testimonio',
            'add_new_item' => 'Añadir Nuevo Testimonio',
            'edit_item' => 'Editar Testimonio',
            'all_items' => 'Todos los Testimonios',
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'rest_base' => 'testimonio',
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-testimonial',
        'rewrite' => array('slug' => 'testimonios'),
    ));
}
add_action('init', 'protrabajo_register_post_types');

// Exponer ACF en REST API
function protrabajo_expose_acf_to_rest() {
    // Para testimonios
    register_rest_field('testimonio', 'acf', array(
        'get_callback' => function($object) {
            return get_fields($object['id']);
        },
        'schema' => null,
    ));
}
add_action('rest_api_init', 'protrabajo_expose_acf_to_rest');

// Endpoint personalizado para información de contacto
function protrabajo_contact_info_endpoint() {
    register_rest_route('wp/v2', '/contact-info', array(
        'methods' => 'GET',
        'callback' => function() {
            return array(
                'acf' => array(
                    'email' => get_field('email', 'option'),
                    'telefono' => get_field('telefono', 'option'),
                    'direccion' => get_field('direccion', 'option'),
                    'horario' => get_field('horario', 'option'),
                )
            );
        },
        'permission_callback' => '__return_true'
    ));
}
add_action('rest_api_init', 'protrabajo_contact_info_endpoint');

// Habilitar CORS (si es necesario)
function protrabajo_enable_cors() {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function($value) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        return $value;
    });
}
add_action('rest_api_init', 'protrabajo_enable_cors', 15);
?>
```

---

## ✅ Verificar que Todo Funciona

### 1. Verificar Custom Post Types
- En el admin de WordPress deberías ver:
  - **Servicios** en el menú lateral
  - **Testimonios** en el menú lateral

### 2. Verificar REST API

Abre en tu navegador (ajusta la URL según tu instalación):

**Servicios:**
```
http://localhost:8888/wp-json/wp/v2/servicio
```

**Testimonios:**
```
http://localhost:8888/wp-json/wp/v2/testimonio
```

**Contacto (si configuraste ACF PRO):**
```
http://localhost:8888/wp-json/wp/v2/contact-info
```

Si ves JSON, ¡está funcionando! 🎉

### 3. Crear Contenido de Prueba

#### Servicios:
1. Ve a **Servicios → Añadir nuevo**
2. Título: "Asesoría Legal Laboral"
3. Contenido: Descripción del servicio
4. Imagen destacada: Sube una imagen
5. Publica

Crea al menos 3-4 servicios para ver el grid completo.

#### Testimonios:
1. Ve a **Testimonios → Añadir nuevo**
2. Título: "Juan Pérez" (nombre del cliente)
3. Contenido: "Excelente servicio, muy profesionales..."
4. Imagen destacada: Foto del cliente (opcional)
5. Campo ACF "Cargo": "CEO, Empresa ABC"
6. Publica

Crea al menos 3 testimonios.

#### Información de Contacto (ACF PRO):
1. Ve a **Contacto** en el menú lateral
2. Completa:
   - Email: contacto@protrabajo.cl
   - Teléfono: +56 9 1234 5678
   - Dirección: Av. Providencia 123, Santiago
   - Horario: Lunes a Viernes 9:00 - 18:00
3. Guarda

---

## 🚨 Solución de Problemas

### Problema: No veo "Show in REST API" en CPT UI
**Solución**: Actualiza el plugin CPT UI a la última versión.

### Problema: Los campos ACF no aparecen en la API
**Solución**: 
1. Verifica que marcaste "Show in REST API" en la configuración del Field Group
2. Agrega el código de `functions.php` para exponer ACF manualmente

### Problema: Error 404 en los endpoints
**Solución**: 
1. Ve a **Ajustes → Enlaces permanentes**
2. Click en **Guardar cambios** (esto regenera las reglas de reescritura)

### Problema: CORS errors
**Solución**: Agrega el código de CORS del `functions.php` mostrado arriba.

---

## 📚 Recursos Adicionales

- [Custom Post Type UI Documentation](https://docs.pluginize.com/category/custom-post-type-ui/)
- [ACF Documentation](https://www.advancedcustomfields.com/resources/)
- [WordPress REST API Handbook](https://developer.wordpress.org/rest-api/)
