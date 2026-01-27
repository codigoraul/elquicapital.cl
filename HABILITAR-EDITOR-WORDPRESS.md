# Habilitar Editor de Temas en WordPress

## El problema:
El editor de temas está deshabilitado en tu WordPress.

## Solución:

### Opción 1: Editar wp-config.php (RECOMENDADO)

1. **Accede por FTP o cPanel al servidor de WordPress**

2. **Busca el archivo `wp-config.php`** en la raíz de WordPress (mismo nivel que wp-content/)

3. **Abre wp-config.php** y busca esta línea:
   ```php
   define('DISALLOW_FILE_EDIT', true);
   ```

4. **Cámbiala a:**
   ```php
   define('DISALLOW_FILE_EDIT', false);
   ```

5. **O simplemente elimina/comenta esa línea:**
   ```php
   // define('DISALLOW_FILE_EDIT', true);
   ```

6. **Guarda el archivo**

7. **Recarga WordPress** - Ahora en Apariencia → Editor debería aparecer

---

### Opción 2: Si no existe esa línea en wp-config.php

Si no encuentras `DISALLOW_FILE_EDIT` en wp-config.php, entonces el editor está deshabilitado por el hosting.

**Contacta a tu proveedor de hosting** y pídeles que habiliten el editor de archivos de WordPress.

---

### Opción 3: Agregar código directamente por FTP

Si tienes acceso FTP pero no puedes editar wp-config.php:

1. Descarga el archivo `functions.php` del tema activo vía FTP:
   - Ruta: `wp-content/themes/NOMBRE-DEL-TEMA/functions.php`

2. Abre con editor de texto

3. Pega el código del archivo `webhook-github.php` al final

4. Sube el archivo de vuelta por FTP

---

## ¿Qué hacer después?

Una vez habilitado el editor:
- Ve a Apariencia → Editor
- Abre functions.php
- Pega el código del webhook al final
- Guarda
