// Salin CSS hasil `vite build` ke public/css/app-production-full.css.
// Production memuat file statis ini (lihat resources/views/components/asset-loader.blade.php),
// jadi setiap ada class Tailwind baru di Blade, jalankan: npm run build:css
import { readFileSync, copyFileSync, existsSync } from 'node:fs';

const manifestPaths = ['public/build/manifest.json', 'public/build/.vite/manifest.json'];
const manifestPath = manifestPaths.find((p) => existsSync(p));
if (!manifestPath) {
    console.error('Manifest Vite tidak ditemukan. Jalankan "vite build" dulu.');
    process.exit(1);
}

const manifest = JSON.parse(readFileSync(manifestPath, 'utf8'));
const css = manifest['resources/css/app.css']?.file;
if (!css) {
    console.error('Entry resources/css/app.css tidak ada di manifest.');
    process.exit(1);
}

copyFileSync(`public/build/${css}`, 'public/css/app-production-full.css');
console.log(`public/build/${css} -> public/css/app-production-full.css`);
