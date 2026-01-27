<?php
/**
 * Webhook para disparar GitHub Actions cuando se actualiza contenido en WordPress
 * 
 * Instrucciones:
 * 1. Reemplaza 'TU_TOKEN_GITHUB_AQUI' con tu Personal Access Token de GitHub
 * 2. Copia este código completo
 * 3. Pégalo al final del archivo functions.php de tu tema de WordPress
 */

function trigger_github_rebuild($post_id) {
    // Evitar que se dispare en auto-saves o revisiones
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }
    
    // URL del repositorio de GitHub
    $github_url = 'https://api.github.com/repos/codigoraul/protrabajo.cl/dispatches';
    
    // Tu Personal Access Token de GitHub (reemplaza esto)
    $github_token = 'TU_TOKEN_GITHUB_AQUI';
    
    // Payload que GitHub espera
    $payload = json_encode([
        'event_type' => 'wordpress-update'
    ]);
    
    // Headers de autenticación
    $headers = [
        'Authorization: Bearer ' . $github_token,
        'Accept: application/vnd.github.v3+json',
        'Content-Type: application/json',
        'User-Agent: WordPress-Webhook'
    ];
    
    // Hacer la petición a GitHub
    $ch = curl_init($github_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Log para debugging (opcional)
    if ($http_code === 204) {
        error_log('GitHub webhook disparado exitosamente para post ID: ' . $post_id);
    } else {
        error_log('Error al disparar GitHub webhook. HTTP Code: ' . $http_code . ' Response: ' . $result);
    }
}

// Disparar cuando se actualiza un post
add_action('save_post', 'trigger_github_rebuild', 10, 1);

// Disparar cuando se actualizan campos ACF
add_action('acf/save_post', 'trigger_github_rebuild', 20);
