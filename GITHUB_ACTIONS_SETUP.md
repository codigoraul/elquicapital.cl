# Configuración de GitHub Actions para ProTrabajo

## 🚀 Despliegue Automático con GitHub Actions

Este proyecto está configurado para desplegarse automáticamente a tu servidor FTP cada vez que hagas push a la rama `main`.

## 📋 Workflow Configurado

**Archivo:** `.github/workflows/deploy.yml`

**Se ejecuta cuando:**
- Haces push a la rama `main`
- Ejecutas manualmente desde GitHub Actions

**Proceso:**
1. ✅ Descarga el código del repositorio
2. ✅ Instala Node.js 20
3. ✅ Instala dependencias con `npm ci`
4. ✅ Hace build de Astro con la URL de WordPress remoto
5. ✅ Sube los archivos de `dist/` a tu servidor FTP

## 🔐 Configurar Secrets en GitHub

Para que el workflow funcione, necesitas configurar estos secrets en tu repositorio:

### 1. Accede a la configuración de Secrets

1. Ve a tu repositorio: `https://github.com/codigoraul/protrabajo.cl`
2. Click en **Settings** (Configuración)
3. En el menú lateral, click en **Secrets and variables → Actions**
4. Click en **New repository secret**

### 2. Agrega estos Secrets

#### `WORDPRESS_API_URL`
- **Nombre:** `WORDPRESS_API_URL`
- **Valor:** `https://protrabajo.cl/admin/wp-json/wp/v2`
- **Descripción:** URL de la API REST de WordPress

#### `FTP_SERVER`
- **Nombre:** `FTP_SERVER`
- **Valor:** `ftp.tuservidor.com` o la IP de tu servidor FTP
- **Descripción:** Servidor FTP donde se desplegará la landing

#### `FTP_USERNAME`
- **Nombre:** `FTP_USERNAME`
- **Valor:** Tu usuario FTP
- **Descripción:** Usuario para conectarse al FTP

#### `FTP_PASSWORD`
- **Nombre:** `FTP_PASSWORD`
- **Valor:** Tu contraseña FTP
- **Descripción:** Contraseña del usuario FTP

#### `FTP_SERVER_DIR`
- **Nombre:** `FTP_SERVER_DIR`
- **Valor:** `/public_html/prueba/` o `/prueba/` (depende de tu servidor)
- **Descripción:** Directorio en el servidor donde se subirán los archivos

**IMPORTANTE:** El directorio debe terminar con `/`

## 📝 Ejemplo de Valores

```
WORDPRESS_API_URL=https://protrabajo.cl/admin/wp-json/wp/v2
FTP_SERVER=ftp.protrabajo.cl
FTP_USERNAME=usuario@protrabajo.cl
FTP_PASSWORD=tu_contraseña_segura
FTP_SERVER_DIR=/public_html/prueba/
```

## 🎯 Cómo Funciona

### Despliegue Automático

Cada vez que hagas:

```bash
git add .
git commit -m "Actualización de la landing"
git push origin main
```

GitHub Actions automáticamente:
1. Construirá tu sitio Astro
2. Conectará los datos desde WordPress remoto
3. Subirá todo a `https://protrabajo.cl/prueba/`

### Despliegue Manual

También puedes ejecutar el workflow manualmente:

1. Ve a tu repositorio en GitHub
2. Click en **Actions**
3. Selecciona el workflow "Deploy ProTrabajo Landing to FTP"
4. Click en **Run workflow**
5. Selecciona la rama `main`
6. Click en **Run workflow**

## 📊 Monitorear Despliegues

Para ver el estado de los despliegues:

1. Ve a tu repositorio en GitHub
2. Click en **Actions**
3. Verás la lista de todos los workflows ejecutados
4. Click en cualquiera para ver los detalles y logs

## ✅ Verificar que Funciona

Después de configurar los secrets:

1. Haz un pequeño cambio en el código
2. Commit y push a `main`
3. Ve a **Actions** en GitHub
4. Verás el workflow ejecutándose
5. Cuando termine (✅ verde), visita `https://protrabajo.cl/prueba/`

## 🔧 Configuración Avanzada

### Cambiar la Rama de Despliegue

Si quieres desplegar desde otra rama, edita `.github/workflows/deploy.yml`:

```yaml
on:
  push:
    branches:
      - production  # Cambia 'main' por tu rama
```

### Desplegar Solo Archivos Específicos

Si quieres excluir ciertos archivos del despliegue, edita la sección `exclude`:

```yaml
exclude: |
  **/.git*
  **/.git*/**
  **/node_modules/**
  **/*.md
  **/WORDPRESS_*.md
```

### Agregar Notificaciones

Puedes agregar notificaciones de Slack, Discord, etc. al workflow.

## 🐛 Troubleshooting

### El workflow falla en "Install dependencies"

- Verifica que `package.json` y `package-lock.json` estén en el repositorio
- Asegúrate de que no haya errores en las dependencias

### El workflow falla en "Build Astro site"

- Verifica que `WORDPRESS_API_URL` esté configurado correctamente en los secrets
- Verifica que WordPress remoto esté accesible públicamente
- Revisa los logs del workflow para ver el error específico

### El workflow falla en "Deploy to FTP"

- Verifica que todos los secrets FTP estén configurados correctamente
- Verifica que el usuario FTP tenga permisos de escritura en el directorio
- Verifica que `FTP_SERVER_DIR` termine con `/`
- Prueba las credenciales FTP con un cliente FTP (FileZilla, etc.)

### Los archivos se suben pero la página no funciona

- Verifica que `FTP_SERVER_DIR` apunte al directorio correcto
- Verifica que el servidor web esté configurado para servir desde ese directorio
- Revisa los permisos de los archivos (deben ser 644 para archivos, 755 para directorios)

### Error: "No se encontró ninguna ruta que coincida"

- WordPress remoto no tiene los custom post types configurados
- Sigue la guía en `WORDPRESS_REMOTO_SETUP.md`

## 📦 Estructura del Despliegue

Después del despliegue, tu servidor tendrá:

```
/public_html/prueba/
├── index.html
├── _astro/
│   ├── *.css
│   └── *.js
├── images/
│   ├── fondo-hero.jpg
│   ├── foto-abogado.png
│   └── logo-Protrabajo.png
├── contacto.php
├── contacto-config.php.example
└── favicon.svg
```

## 🔄 Flujo de Trabajo Recomendado

1. **Desarrollo Local:**
   ```bash
   npm run dev
   # Prueba en http://localhost:4321
   ```

2. **Commit y Push:**
   ```bash
   git add .
   git commit -m "Descripción del cambio"
   git push origin main
   ```

3. **GitHub Actions se ejecuta automáticamente**

4. **Verifica en producción:**
   ```
   https://protrabajo.cl/prueba/
   ```

## 🎨 Personalización del Workflow

El archivo `.github/workflows/deploy.yml` es totalmente personalizable. Puedes:

- Agregar tests antes del deploy
- Agregar linting
- Agregar notificaciones
- Agregar múltiples ambientes (staging, production)
- Agregar cache para acelerar builds

## 📚 Recursos

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [FTP-Deploy-Action](https://github.com/SamKirkland/FTP-Deploy-Action)
- [Astro Deployment Guide](https://docs.astro.build/en/guides/deploy/)

## 🔒 Seguridad

- ✅ Nunca subas credenciales al repositorio
- ✅ Usa GitHub Secrets para información sensible
- ✅ Los secrets están encriptados y solo accesibles en el workflow
- ✅ Revisa los logs del workflow antes de compartir (pueden contener información sensible)

## 📞 Soporte

Si tienes problemas con el despliegue:

1. Revisa los logs en **Actions** en GitHub
2. Verifica que todos los secrets estén configurados
3. Prueba las credenciales FTP manualmente
4. Verifica que WordPress remoto esté funcionando
