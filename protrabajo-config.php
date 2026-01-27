<?php
/**
 * Plugin Name: ProTrabajo Configuración
 * Description: Configuración para WordPress Headless con Astro
 * Version: 1.0
 * Author: ProTrabajo
 */

// Crear página de opciones para información de contacto
add_action('admin_menu', function() {
    add_menu_page(
        'Información de Contacto',
        'Contacto',
        'manage_options',
        'protrabajo-contacto',
        'protrabajo_contacto_page',
        'dashicons-phone',
        30
    );
});

// Función para mostrar la página de opciones
function protrabajo_contacto_page() {
    if (isset($_POST['protrabajo_save_contact']) && check_admin_referer('protrabajo_contact_nonce')) {
        update_option('protrabajo_email', sanitize_email($_POST['email']));
        update_option('protrabajo_telefono', sanitize_text_field($_POST['telefono']));
        update_option('protrabajo_direccion', sanitize_textarea_field($_POST['direccion']));
        update_option('protrabajo_horario', sanitize_text_field($_POST['horario']));
        echo '<div class="notice notice-success is-dismissible"><p><strong>¡Información guardada!</strong></p></div>';
    }
    
    $email = get_option('protrabajo_email', 'info@protrabajo.cl');
    $telefono = get_option('protrabajo_telefono', '+56 9 1234 5678');
    $direccion = get_option('protrabajo_direccion', 'Santiago, Chile');
    $horario = get_option('protrabajo_horario', 'Lunes a Viernes 9:00 - 18:00');
    ?>
    <div class="wrap">
        <h1>📞 Información de Contacto</h1>
        <p>Edita los datos de contacto que se mostrarán en tu sitio web.</p>
        <form method="post">
            <?php wp_nonce_field('protrabajo_contact_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="email">Email de Contacto</label></th>
                    <td>
                        <input type="email" name="email" id="email" value="<?php echo esc_attr($email); ?>" class="regular-text" required>
                        <p class="description">Email principal para contacto</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="telefono">Teléfono</label></th>
                    <td>
                        <input type="text" name="telefono" id="telefono" value="<?php echo esc_attr($telefono); ?>" class="regular-text" required>
                        <p class="description">Número con código de país (ej: +56 9 1234 5678)</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="direccion">Dirección</label></th>
                    <td>
                        <textarea name="direccion" id="direccion" rows="3" class="large-text" required><?php echo esc_textarea($direccion); ?></textarea>
                        <p class="description">Dirección física de la oficina</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="horario">Horario de Atención</label></th>
                    <td>
                        <input type="text" name="horario" id="horario" value="<?php echo esc_attr($horario); ?>" class="regular-text" required>
                        <p class="description">Horario de atención al público</p>
                    </td>
                </tr>
            </table>
            <p class="submit"><input type="submit" name="protrabajo_save_contact" class="button-primary" value="Guardar Cambios"></p>
        </form>
    </div>
    <?php
}

// Exponer campos ACF en REST API para Testimonios
add_action('rest_api_init', function() {
    register_rest_field('testimonio', 'acf', array(
        'get_callback' => function($object) {
            return get_fields($object['id']);
        },
    ));
});

// Endpoint para información de contacto
add_action('rest_api_init', function() {
    register_rest_route('wp/v2', '/contact-info', array(
        'methods' => 'GET',
        'callback' => function() {
            return array(
                'acf' => array(
                    'email' => get_option('protrabajo_email', 'info@protrabajo.cl'),
                    'telefono' => get_option('protrabajo_telefono', '+56 9 1234 5678'),
                    'direccion' => get_option('protrabajo_direccion', 'Santiago, Chile'),
                    'horario' => get_option('protrabajo_horario', 'Lunes a Viernes 9:00 - 18:00'),
                )
            );
        },
        'permission_callback' => '__return_true'
    ));
});

// Habilitar CORS
add_action('rest_api_init', function() {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function($value) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        return $value;
    });
}, 15);

// Tamaños de imagen personalizados
add_action('after_setup_theme', function() {
    add_image_size('servicio-thumb', 600, 400, true);
    add_image_size('testimonio-thumb', 300, 300, true);
});
