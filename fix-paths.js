import { readFileSync, writeFileSync } from 'fs';

const distPath = './dist/index.html';
let html = readFileSync(distPath, 'utf-8');

function fixAbsolutePaths(content) {
  content = content.replace(/href="\/(?!\/|#)([^"]*)"/g, 'href="./$1"');
  content = content.replace(/src="\/(?!\/)([^"]*)"/g, 'src="./$1"');
  content = content.replace(/action="\/(?!\/)([^"]*)"/g, 'action="./$1"');
  content = content.replace(/url\('\/(?!\/)([^']*)'\)/g, "url('./$1')");
  content = content.replace(/url\("\/(?!\/)([^"]*)"\)/g, 'url("./$1")');
  content = content.replace(/url\(\/(?!\/)([^)]*)\)/g, 'url(./$1)');
  return content;
}

html = fixAbsolutePaths(html);
writeFileSync(distPath, html, 'utf-8');
console.log('✅ Todas las rutas absolutas convertidas a relativas');
