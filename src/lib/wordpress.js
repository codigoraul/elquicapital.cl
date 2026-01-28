const WORDPRESS_API_URL = (import.meta.env.WORDPRESS_API_URL || 'https://elquicapital.cl/admin/wp-json/wp/v2').replace(/\/+$/, '');

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

export async function getServicios() {
  return await fetchAPI('/servicio?_embed&per_page=100') || [];
}

export async function getServicioBySlug(slug) {
  const servicios = await fetchAPI(`/servicio?slug=${slug}&_embed`);
  return servicios?.[0] || null;
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
