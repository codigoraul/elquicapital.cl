const WORDPRESS_API_URL = (import.meta.env.WORDPRESS_API_URL || 'https://elquicapital.cl/admin/wp-json/wp/v2').replace(/\/+$/, '');
const YOAST_API_URL = 'https://elquicapital.cl/admin/wp-json/yoast/v1';

async function fetchAPI(endpoint) {
  const normalizedEndpoint = endpoint.startsWith('/') ? endpoint : `/${endpoint}`;
  const url = `${WORDPRESS_API_URL}${normalizedEndpoint}`;
  
  try {
    const response = await fetch(url);
    
    if (!response.ok) {
      console.error(`Error fetching ${url}: ${response.status}`);
      return null;
    }
    
    return await response.json();
  } catch (error) {
    console.error(`Error connecting to WordPress API: ${error.message}`);
    return null;
  }
}

async function fetchYoastData(slug) {
  if (!slug) return null;
  try {
    const response = await fetch(`${YOAST_API_URL}/get_head?url=https://elquicapital.cl/servicios/${slug}/`);
    if (!response.ok) return null;
    return await response.json();
  } catch (error) {
    return null;
  }
}

export async function getServicios() {
  const ordered = await fetchAPI('/servicio?_embed&per_page=100&orderby=menu_order&order=asc');
  if (ordered) return ordered;

  const servicios = (await fetchAPI('/servicio?_embed&per_page=100')) || [];

  if (!Array.isArray(servicios)) return [];

  return servicios.slice().sort((a, b) => {
    const aOrder = typeof a?.menu_order === 'number' ? a.menu_order : 0;
    const bOrder = typeof b?.menu_order === 'number' ? b.menu_order : 0;
    return aOrder - bOrder;
  });
}

export async function getServicioBySlug(slug) {
  const servicios = await fetchAPI(`/servicio?slug=${slug}&_embed`);
  const servicio = servicios?.[0] || null;
  if (!servicio) return null;
  const yoast = await fetchYoastData(slug);
  return { ...servicio, yoast };
}

export async function getTestimonios() {
  return await fetchAPI('/testimonio?_embed&per_page=100') || [];
}

export async function getContactInfo() {
  return await fetchAPI('/contact-info') || null;
}

export async function getPosts(perPage = 10) {
  return await fetchAPI(`/posts?_embed&per_page=${perPage}`) || [];
}
