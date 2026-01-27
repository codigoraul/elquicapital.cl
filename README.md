# Elqui Capital Landing Page

Landing page autoadministrable para **Elqui Capital** con **Astro** + **WordPress Headless**.

## 🎯 Características

- ✅ **WordPress Headless**: Gestión de contenido desde WordPress
- ✅ **Servicios Dinámicos**: Custom Post Type `servicio` con páginas individuales
- ✅ **Testimonios**: Custom Post Type `testimonio` con fotos y cargos
- ✅ **Información de Contacto Editable**: Email, teléfono, ubicación y horario desde WordPress
- ✅ **Diseño Moderno**: UI profesional y responsive
- ✅ **Rutas Relativas**: Script automático para compatibilidad con hosting tradicional
- ✅ **Responsive**: Optimizado para todos los dispositivos

## 📁 Estructura del Proyecto

```
elquicapital.cl/
├── src/
│   ├── components/
│   │   ├── ServiciosGrid.astro    # Grid de servicios
│   │   ├── Testimonios.astro      # Carrusel de testimonios
│   │   └── ContactInfo.astro      # Datos de contacto
│   ├── lib/
│   │   └── wordpress.js           # Utilidades API WordPress
│   ├── pages/
│   │   ├── index.astro            # Página principal
│   │   └── servicios/
│   │       └── [slug].astro       # Páginas dinámicas de servicios
│   └── layouts/
│       └── Layout.astro           # Layout base
├── fix-paths.js                   # Script para rutas relativas
├── .env.example                   # Ejemplo de variables de entorno
└── WORDPRESS_SETUP.md             # Guía de configuración WordPress
```

## 🚀 Inicio Rápido

### 1. Configurar Variables de Entorno

```bash
cp .env.example .env
```

Edita `.env` con tu URL de WordPress local:

```env
WORDPRESS_API_URL=http://localhost:10174/wp-json/wp/v2
```

### 2. Instalar Dependencias

```bash
npm install
```

### 3. Configurar WordPress

Sigue las instrucciones en `WORDPRESS_SETUP.md` para:
- Crear Custom Post Types (servicios, testimonios)
- Configurar campos ACF
- Exponer datos en REST API

### 4. Ejecutar en Desarrollo

```bash
npm run dev
```

Abre [http://localhost:4321](http://localhost:4321)

### 5. Build para Producción

```bash
npm run build
```

El script `fix-paths.js` convierte automáticamente todas las rutas absolutas a relativas.

## 🛠️ Comandos Disponibles

| Comando | Acción |
|---------|--------|
| `npm install` | Instala dependencias |
| `npm run dev` | Servidor de desarrollo en `localhost:4321` |
| `npm run build` | Build de producción en `./dist/` |
| `npm run preview` | Preview del build localmente |

## 📝 Contenido en WordPress

### Servicios
- Título del servicio
- Descripción completa (editor)
- Imagen destacada
- Slug para URL

### Testimonios
- Nombre del cliente (título)
- Testimonio (contenido)
- Foto del cliente (imagen destacada)
- Cargo (campo ACF)

### Información de Contacto
- Email
- Teléfono
- Dirección
- Horario de atención

## 🎨 Personalización de Diseño

El diseño usa gradientes morado/azul (`#667eea` → `#764ba2`). Para cambiar los colores según el logo final del cliente:

1. Edita `src/pages/index.astro` - sección `.hero`
2. Edita `src/components/ServiciosGrid.astro` - botones y cards
3. Edita `src/components/Testimonios.astro` - fondo de cards

## 🌐 Deployment

El proyecto genera archivos estáticos en `/dist` que pueden desplegarse en:
- Netlify
- Vercel
- GitHub Pages
- FTP (hosting tradicional)

Las rutas relativas están configuradas automáticamente para máxima compatibilidad.

## 📚 Documentación

- [Astro Documentation](https://docs.astro.build)
- [WordPress REST API](https://developer.wordpress.org/rest-api/)
- [Advanced Custom Fields](https://www.advancedcustomfields.com/)
