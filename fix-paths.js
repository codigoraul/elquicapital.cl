import { readFileSync, writeFileSync } from 'fs';
import { readdirSync, statSync } from 'fs';
import { join } from 'path';

const distDir = './dist';
const basePath = process.env.BASE_PATH || '';
const normalizedBasePath = basePath && basePath !== '/' ? `/${basePath.replace(/^\/+|\/+$/g, '')}` : '';

function prefixRootPaths(content) {
  if (!normalizedBasePath) return content;

  const base = normalizedBasePath;
  content = content.replace(/href="\/(?!\/|#)([^"]*)"/g, `href="${base}/$1"`);
  content = content.replace(/src="\/(?!\/)([^"]*)"/g, `src="${base}/$1"`);
  content = content.replace(/srcset="\/(?!\/)([^"]*)"/g, `srcset="${base}/$1"`);
  content = content.replace(/action="\/(?!\/)([^"]*)"/g, `action="${base}/$1"`);
  content = content.replace(/url\('\/(?!\/)([^']*)'\)/g, `url('${base}/$1')`);
  content = content.replace(/url\("\/(?!\/)([^"]*)"\)/g, `url("${base}/$1")`);
  content = content.replace(/url\(\/(?!\/)([^)]*)\)/g, `url(${base}/$1)`);
  return content;
}

function walkDir(dirPath, files = []) {
  for (const entry of readdirSync(dirPath)) {
    const fullPath = join(dirPath, entry);
    const stats = statSync(fullPath);
    if (stats.isDirectory()) {
      walkDir(fullPath, files);
    } else {
      files.push(fullPath);
    }
  }
  return files;
}

const files = walkDir(distDir);
let changedCount = 0;

for (const filePath of files) {
  if (!filePath.endsWith('.html') && !filePath.endsWith('.css')) continue;
  const original = readFileSync(filePath, 'utf-8');
  const updated = prefixRootPaths(original);
  if (updated !== original) {
    writeFileSync(filePath, updated, 'utf-8');
    changedCount += 1;
  }
}

if (normalizedBasePath) {
  console.log(`✅ Rutas prefijadas con base: ${normalizedBasePath} (archivos modificados: ${changedCount})`);
} else {
  console.log('ℹ️ BASE_PATH no definido, no se modificaron rutas');
}
