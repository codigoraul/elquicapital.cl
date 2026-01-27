# Secrets para GitHub Actions - ProTrabajo

## 🔐 Configuración de Secrets

Ve a: `https://github.com/codigoraul/protrabajo.cl/settings/secrets/actions`

Click en "New repository secret" y agrega cada uno de estos 5 secrets:

---

### 1. FTP_SERVER

**Name:** `FTP_SERVER`

**Secret:**
```
ftp.protrabajo.cl
```

---

### 2. FTP_USERNAME

**Name:** `FTP_USERNAME`

**Secret:**
```
conexion@protrabajo.cl
```

---

### 3. FTP_PASSWORD

**Name:** `FTP_PASSWORD`

**Secret:**
```
conexiongithub2025
```

---

### 4. FTP_SERVER_DIR

**Name:** `FTP_SERVER_DIR`

**Secret:**
```
/home/protraba/prueba/
```

⚠️ **IMPORTANTE:** Debe terminar con `/`

---

### 5. WORDPRESS_API_URL

**Name:** `WORDPRESS_API_URL`

**Secret:**
```
https://protrabajo.cl/admin/wp-json/wp/v2
```

---

## ✅ Verificación

Después de agregar los secrets:

1. Haz un pequeño cambio en el código
2. Commit y push:
   ```bash
   git add .
   git commit -m "Test deploy"
   git push origin main
   ```
3. Ve a: `https://github.com/codigoraul/protrabajo.cl/actions`
4. Verás el workflow ejecutándose
5. Cuando termine (✅ verde), visita: `https://protrabajo.cl/prueba/`

## 📋 Configuración FTP Completa

- **Usuario:** conexion@protrabajo.cl
- **Servidor:** ftp.protrabajo.cl
- **Puerto:** 21
- **Directorio:** /home/protraba/prueba
- **Cuota:** 0 / 1000 MB

## 🚀 Resultado Final

Una vez configurado, cada push a `main` desplegará automáticamente a:

**https://protrabajo.cl/prueba/**
