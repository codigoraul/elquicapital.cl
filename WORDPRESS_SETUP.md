# Configuración WordPress para ProTrabajo Landing

## 1. Custom Post Types Requeridos

Necesitas crear estos Custom Post Types en WordPress:

### A) Servicios (`servicio`)
```php
// Agregar en functions.php de tu tema o crear plugin
function create_servicio_post_type() {
    register_post_type('servicio',
        array(
            'labels' => array(
                'name' => 'Servicios',
                'singular_name' => 'Servicio'
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true, // IMPORTANTE para REST API
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'rewrite' => array('slug' => 'servicios'),
        )
    );
}
add_action('init', 'create_servicio_post_type');
```

### B) Testimonios (`testimonio`)
```php
function create_testimonio_post_type() {
    register_post_type('testimonio',
        array(
            'labels' => array(
                'name' => 'Testimonios',
                'singular_name' => 'Testimonio'
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true, // IMPORTANTE para REST API
            'supports' => array('title', 'editor', 'thumbnail'),
            'rewrite' => array('slug' => 'testimonios'),
        )
    );
}
add_action('init', 'create_testimonio_post_type');
```

### C) Información de Contacto (usando ACF Options Page)
```php
// Requiere Advanced Custom Fields Pro
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title' => 'Información de Contacto',
        'menu_title' => 'Contacto',
        'menu_slug' => 'contact-info',
        'capability' => 'edit_posts',
        'show_in_rest' => true, // IMPORTANTE para REST API
    ));
}
```

## 2. Campos ACF Necesarios

### Para Testimonios:
- **Grupo de campos**: "Datos del Testimonio"
- **Campo**: `cargo` (Text)
- **Ubicación**: Post Type = testimonio

### Para Información de Contacto:
- **Grupo de campos**: "Datos de Contacto"
- **Campos**:
  - `email` (Email)
  - `telefono` (Text)
  - `direccion` (Text Area)
  - `horario` (Text)
- **Ubicación**: Options Page = contact-info

## 3. Exponer ACF en REST API

Agregar en functions.php:

```php
// Exponer campos ACF en REST API
add_action('rest_api_init', function() {
    // Para testimonios
    register_rest_field('testimonio', 'acf', array(
        'get_callback' => function($object) {
            return get_fields($object['id']);
        }
    ));
    
    // Para options page de contacto
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
```

## 4. Configurar CORS (si es necesario)

Si tienes problemas de CORS, agregar en functions.php:

```php
add_action('rest_api_init', function() {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function($value) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        return $value;
    });
}, 15);
```

## 5. Configurar .env en Astro

Copia `.env.example` a `.env` y configura tu URL de WordPress:

```bash
cp .env.example .env
```

Edita `.env`:
```
WORDPRESS_API_URL=http://localhost:8888/wp-json/wp/v2
```

Ajusta la URL según tu configuración local (MAMP, XAMPP, Local by Flywheel, etc.)

## 6. Verificar Endpoints

Prueba estos endpoints en tu navegador:

- Servicios: `http://localhost:8888/wp-json/wp/v2/servicio`
- Testimonios: `http://localhost:8888/wp-json/wp/v2/testimonio`
- Contacto: `http://localhost:8888/wp-json/wp/v2/contact-info`

Si ves JSON, ¡está funcionando! 🎉

## 7. Contenido de Ejemplo

### Servicios:
- Asesoría Legal Laboral
- Defensa en Juicios Laborales
- Negociación Colectiva
- Auditoría de Cumplimiento

### Testimonios:
- Agregar nombre del cliente como título
- Contenido: testimonio del cliente
- Imagen destacada: foto del cliente (opcional)
- Campo cargo: "CEO, Empresa X"

### Información de Contacto:
- Email: contacto@protrabajo.cl
- Teléfono: +56 9 1234 5678
- Dirección: Av. Providencia 123, Santiago
- Horario: Lunes a Viernes 9:00 - 18:00
