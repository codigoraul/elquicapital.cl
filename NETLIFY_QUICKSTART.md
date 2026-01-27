# Guía rápida para Netlify

1. **Registro:**
   - Ve a [https://app.netlify.com/signup](https://app.netlify.com/signup)
   - Elige "Sign up with GitHub"

2. **Conectar repositorio:**
   - Click en "Add new site" > "Import an existing project"
   - Selecciona GitHub > `codigoraul/protrabajo.cl`

3. **Configuración del sitio:**
   - **Build command:** `npm run build`
   - **Publish directory:** `dist`
   - Click en "Show advanced" y agrega:
     - **New variable:** `WORDPRESS_API_URL`
     - **Value:** `https://protrabajo.cl/admin/wp-json/wp/v2`

4. **Despliegue:**
   - Click en "Deploy site"

5. **Webhook para WordPress (opcional):**
   - Ve a "Site settings" > "Build & deploy" > "Build hooks"
   - Crea un build hook y copia la URL
   - Configúralo en WordPress con un plugin de webhooks

¡Listo! Tu sitio estará en: `https://[nombre-autogenerado].netlify.app`
