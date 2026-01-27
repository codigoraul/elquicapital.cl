# Configuración de dominio personalizado en Netlify

## Pasos en Netlify:
1. Ve a `Site settings` > `Domain management` > `Custom domains`
2. Haz clic en **"Add custom domain"**
3. Ingresa `protrabajo.cl`
4. Sigue las instrucciones para verificar el dominio

## Configuración DNS por registrador:

### GoDaddy
1. Inicia sesión en GoDaddy
2. Ve a `Mis dominios` > `Administrar DNS`
3. Cambia los nameservers a:
   ```
   dns1.p01.nsone.net
   dns2.p01.nsone.net
   dns3.p01.nsone.net
   dns4.p01.nsone.net
   ```

### Namecheap
1. Inicia sesión en Namecheap
2. Ve a `Domain List` > `MANAGE` > `Advanced DNS`
3. En `Nameservers`, selecciona `Custom DNS`
4. Agrega los nameservers de Netlify

### Cloudflare
1. Inicia sesión en Cloudflare
2. Selecciona tu dominio
3. Ve a `DNS` > `Records`
4. Asegúrate de que solo hay registros DNS (sin proxy)
5. Agrega los registros A/CNAME que Netlify te indique

## Verificación:
- Usa [https://dnschecker.org](https://dnschecker.org) para comprobar propagación DNS
- Netlify emitirá un certificado SSL automáticamente
