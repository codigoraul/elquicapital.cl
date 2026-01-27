# Configuración ACF PRO para ProTrabajo

## 🎯 Ventajas de Usar ACF PRO

Con ACF PRO tienes acceso a:
- ✅ **Options Pages** - Perfecto para datos de contacto globales
- ✅ **Repeater Fields** - Por si necesitas múltiples items
- ✅ **Flexible Content** - Para layouts personalizados
- ✅ **Gallery Field** - Para galerías de imágenes
- ✅ **Clone Field** - Reutilizar grupos de campos

---

## 📋 Configuración Completa Paso a Paso

### **Paso 1: Instalar Plugins**

1. **Custom Post Type UI**
   - WordPress Admin → Plugins → Añadir nuevo
   - Buscar: `Custom Post Type UI`
   - Instalar y activar

2. **Advanced Custom Fields PRO**
   - Ya lo tienes instalado ✅

---

### **Paso 2: Crear Custom Post Type "Servicios"**

1. Ve a **CPT UI → Add/Edit Post Types**
2. Completa:

```
Post Type Slug: servicio
Plural Label: Servicios
Singular Label: Servicio
```

3. En la pestaña **Settings**:
   - ✅ Has Archive
   - ✅ Hierarchical: NO
   - ✅ Show in REST API: SÍ (MUY IMPORTANTE)
   - REST API base slug: `servicio`

4. En la pestaña **Supports**:
   - ✅ Title
   - ✅ Editor
   - ✅ Featured Image
   - ✅ Excerpt
   - ✅ Page Attributes (opcional, para ordenar)

5. En **Additional Labels** (opcional pero recomendado):
```
Add New: Añadir Servicio
Add New Item: Añadir Nuevo Servicio
Edit Item: Editar Servicio
View Item: Ver Servicio
All Items: Todos los Servicios
```

6. **Menu Icon**: Elige `dashicons-portfolio` o el que prefieras

7. Click **Add Post Type**

---

### **Paso 3: Crear Custom Post Type "Testimonios"**

1. Ve a **CPT UI → Add/Edit Post Types**
2. Completa:

```
Post Type Slug: testimonio
Plural Label: Testimonios
Singular Label: Testimonio
```

3. En la pestaña **Settings**:
   - ✅ Has Archive
   - ✅ Show in REST API: SÍ (MUY IMPORTANTE)
   - REST API base slug: `testimonio`

4. En la pestaña **Supports**:
   - ✅ Title
   - ✅ Editor
   - ✅ Featured Image
   - ❌ Excerpt (no necesario)

5. **Menu Icon**: Elige `dashicons-testimonial` o `dashicons-format-quote`

6. Click **Add Post Type**

---

### **Paso 4: Crear Options Page para Contacto (ACF PRO)**

1. Ve a **ACF → Options Pages**
2. Click **Add New**
3. Configura:

```
Page Title: Información de Contacto
Menu Title: Contacto
Menu Slug: contact-info
Parent Slug: (dejar vacío para que aparezca en el menú principal)
Capability: edit_posts
Icon URL: dashicons-phone
Position: 30
Update Button: Guardar Cambios
Updated Message: Información de contacto actualizada
```

4. Click **Publish**

---

### **Paso 5: Crear Campos ACF para Testimonios**

1. Ve a **ACF → Field Groups**
2. Click **Add New**
3. Título del grupo: `Datos del Testimonio`

4. Click **+ Add Field**:

```
Field Label: Cargo
Field Name: cargo
Field Type: Text
Instructions: Ej: CEO de Empresa ABC, Director de RRHH, etc.
Required: No
Default Value: (vacío)
Placeholder Text: CEO, Empresa ABC
```

5. En **Settings** (parte inferior):
   - ✅ **Show in REST API**: key = `acf`
   - Style: Standard (WP metabox)
   - Position: Normal (after content)
   - Label placement: Top aligned

6. En **Location** (reglas de ubicación):
   - Rule: **Post Type** is equal to **testimonio**

7. Click **Publish**

---

### **Paso 6: Crear Campos ACF para Información de Contacto**

1. Ve a **ACF → Field Groups**
2. Click **Add New**
3. Título del grupo: `Datos de Contacto`

4. Agregar estos 4 campos:

#### Campo 1: Email
```
Field Label: Email de Contacto
Field Name: email
Field Type: Email
Instructions: Email principal para contacto
Required: Sí
Default Value: info@protrabajo.cl
Placeholder Text: contacto@protrabajo.cl
```

#### Campo 2: Teléfono
```
Field Label: Teléfono
Field Name: telefono
Field Type: Text
Instructions: Número de teléfono con código de país
Required: Sí
Default Value: +56 9 1234 5678
Placeholder Text: +56 9 XXXX XXXX
```

#### Campo 3: Dirección
```
Field Label: Dirección
Field Name: direccion
Field Type: Text Area
Instructions: Dirección física de la oficina
Required: Sí
Rows: 3
Default Value: Santiago, Chile
Placeholder Text: Av. Providencia 123, Oficina 456, Santiago
```

#### Campo 4: Horario
```
Field Label: Horario de Atención
Field Name: horario
Field Type: Text
Instructions: Horario de atención al público
Required: Sí
Default Value: Lunes a Viernes 9:00 - 18:00
Placeholder Text: Lunes a Viernes 9:00 - 18:00
```

5. En **Settings** (parte inferior):
   - ✅ **Show in REST API**: key = `acf`

6. En **Location** (reglas de ubicación):
   - Rule: **Options Page** is equal to **contact-info**

7. Click **Publish**

---

### **Paso 7: Agregar Código al functions.php**

Agrega este código al archivo `functions.php` de tu tema activo:

```php
<?php
/**
 * ProTrabajo - Configuración WordPress Headless
 */

// Exponer campos ACF en REST API para Testimonios
add_action('rest_api_init', function() {
    register_rest_field('testimonio', 'acf', array(
        'get_callback' => function($object) {
            return get_fields($object['id']);
        },
        'schema' => null,
    ));
});

// Endpoint personalizado para información de contacto
add_action('rest_api_init', function() {
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
});

// Habilitar CORS para desarrollo local (opcional)
add_action('rest_api_init', function() {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function($value) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        return $value;
    });
}, 15);

// Agregar tamaños de imagen personalizados (opcional)
add_action('after_setup_theme', function() {
    add_image_size('servicio-thumb', 600, 400, true);
    add_image_size('testimonio-thumb', 300, 300, true);
});
?>
```

**¿Dónde está functions.php?**
- Ve a **Apariencia → Editor de archivos de tema**
- Selecciona `functions.php` en la lista de archivos
- Pega el código al final del archivo
- Click **Actualizar archivo**

---

### **Paso 8: Regenerar Permalinks**

Después de crear los Custom Post Types:

1. Ve a **Ajustes → Enlaces permanentes**
2. No cambies nada, solo click en **Guardar cambios**
3. Esto regenera las reglas de reescritura

---

## ✅ Verificar que Todo Funciona

### 1. Verificar Menú de WordPress

En el admin deberías ver:
- **Servicios** (con icono de portfolio)
- **Testimonios** (con icono de comillas)
- **Contacto** (con icono de teléfono)

### 2. Verificar REST API

Abre en tu navegador (ajusta la URL según tu instalación):

**Servicios:**
```
http://localhost:8888/wp-json/wp/v2/servicio
```
Deberías ver: `[]` (array vacío si no hay servicios aún)

**Testimonios:**
```
http://localhost:8888/wp-json/wp/v2/testimonio
```
Deberías ver: `[]` (array vacío si no hay testimonios aún)

**Información de Contacto:**
```
http://localhost:8888/wp-json/wp/v2/contact-info
```
Deberías ver:
```json
{
  "acf": {
    "email": "info@protrabajo.cl",
    "telefono": "+56 9 1234 5678",
    "direccion": "Santiago, Chile",
    "horario": "Lunes a Viernes 9:00 - 18:00"
  }
}
```

---

## 📝 Crear Contenido de Prueba

### Servicios (crear 4 servicios):

1. **Asesoría Legal Laboral**
   - Contenido: "Brindamos asesoría integral en derecho laboral..."
   - Imagen: Foto de abogado o oficina
   - Excerpt: "Asesoría profesional en temas laborales"

2. **Defensa en Juicios Laborales**
   - Contenido: "Representación legal en conflictos laborales..."
   - Imagen: Sala de tribunal
   - Excerpt: "Defensa experta en juicios laborales"

3. **Negociación Colectiva**
   - Contenido: "Apoyo en procesos de negociación colectiva..."
   - Imagen: Reunión de negociación
   - Excerpt: "Mediación en negociaciones colectivas"

4. **Auditoría de Cumplimiento**
   - Contenido: "Revisión de cumplimiento normativo laboral..."
   - Imagen: Documentos legales
   - Excerpt: "Auditoría legal empresarial"

### Testimonios (crear 3 testimonios):

1. **María González**
   - Contenido: "Excelente servicio, resolvieron mi caso laboral de manera profesional y rápida. Muy recomendados."
   - Imagen: Foto profesional (o usar avatar)
   - Campo Cargo: "Gerente de RRHH, Empresa ABC"

2. **Carlos Rodríguez**
   - Contenido: "Gracias a ProTrabajo logré una solución favorable en mi conflicto laboral. Equipo muy preparado."
   - Imagen: Foto profesional
   - Campo Cargo: "Director Comercial, XYZ Ltda."

3. **Ana Martínez**
   - Contenido: "Profesionales comprometidos y con amplio conocimiento. Me ayudaron en todo el proceso."
   - Imagen: Foto profesional
   - Campo Cargo: "Consultora Independiente"

### Información de Contacto:

1. Ve a **Contacto** en el menú
2. Completa:
   - Email: `contacto@protrabajo.cl`
   - Teléfono: `+56 9 8765 4321`
   - Dirección: `Av. Providencia 1234, Oficina 567, Providencia, Santiago`
   - Horario: `Lunes a Viernes 9:00 - 18:00 hrs`
3. Click **Guardar Cambios**

---

## 🎨 Tips Adicionales con ACF PRO

### Si quieres agregar más campos después:

**Para Servicios** (opcional):
- Precio (Number)
- Duración (Text)
- Icono (Image)
- Características (Repeater con sub-campos)

**Para Testimonios** (opcional):
- Calificación (Range: 1-5 estrellas)
- Empresa (Text)
- Fecha del testimonio (Date Picker)

**Para Contacto** (opcional):
- WhatsApp (Text)
- Redes sociales (Repeater: red social + URL)
- Mapa (Google Map - requiere API key)

---

## 🚨 Solución de Problemas

### No veo los Custom Post Types en el menú
- Verifica que guardaste correctamente en CPT UI
- Cierra sesión y vuelve a entrar
- Verifica permisos de usuario

### Los campos ACF no aparecen
- Verifica las reglas de ubicación (Location)
- Asegúrate de estar editando el tipo de post correcto

### La API devuelve error 404
- Regenera permalinks (Ajustes → Enlaces permanentes → Guardar)
- Verifica que marcaste "Show in REST API"

### Los campos ACF no aparecen en la API
- Verifica que marcaste "Show in REST API" en el Field Group
- Verifica que agregaste el código en functions.php
- Limpia la caché si usas algún plugin de caché

---

## ✅ Checklist Final

- [ ] Custom Post Type UI instalado y activado
- [ ] ACF PRO instalado y activado
- [ ] Custom Post Type "servicio" creado con REST API habilitado
- [ ] Custom Post Type "testimonio" creado con REST API habilitado
- [ ] Options Page "contact-info" creada
- [ ] Field Group "Datos del Testimonio" creado y asignado
- [ ] Field Group "Datos de Contacto" creado y asignado
- [ ] Código agregado a functions.php
- [ ] Permalinks regenerados
- [ ] Endpoints verificados en el navegador
- [ ] Contenido de prueba creado (4 servicios, 3 testimonios)
- [ ] Información de contacto completada

---

¡Listo! Ahora tu WordPress está completamente configurado para funcionar como headless CMS con Astro. 🚀
