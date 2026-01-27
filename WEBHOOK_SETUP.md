# Configuración de Webhooks para rebuilds automáticos

## Para GitHub Actions (actual):

1. **Crear webhook en WordPress:**
   - Instala plugin "WP Webhooks"
   - Configura un nuevo webhook:
     - **Evento:** `post_updated`
     - **URL destino:** `https://api.github.com/repos/codigoraul/protrabajo.cl/dispatches`
     - **Método:** POST
     - **Payload:**
       ```json
       {
         "event_type": "wordpress-update"
       }
       ```
   - Agrega autenticación con token de GitHub

2. **Actualizar workflow de GitHub Actions:**
   - Agrega un trigger para el evento `repository_dispatch`
   ```yaml
   on:
     repository_dispatch:
       types: [wordpress-update]
   ```

## Para Netlify (recomendado):

1. **Crear build hook en Netlify:**
   - Ve a `Site settings` > `Build & deploy` > `Build hooks`
   - Crea un hook y copia su URL

2. **Configurar webhook en WordPress:**
   - Con el plugin "WP Webhooks", crea un webhook que envíe una solicitud POST a la URL del build hook de Netlify cuando se actualice contenido.
