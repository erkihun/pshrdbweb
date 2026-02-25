import { copyFile, mkdir, rm } from 'node:fs/promises';
import { readdir } from 'node:fs/promises';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const srcDir = join(__dirname, '../node_modules/tinymce');
const destDir = join(__dirname, '../public/vendor/tinymce');
const customPluginsDir = join(__dirname, '../resources/tinymce-plugins');

async function copyDir(src, dest) {
    await mkdir(dest, { recursive: true });
    const entries = await readdir(src, { withFileTypes: true });

    for (const entry of entries) {
        const srcPath = join(src, entry.name);
        const destPath = join(dest, entry.name);

        if (entry.isDirectory()) {
            await copyDir(srcPath, destPath);
        } else if (entry.isFile()) {
            await copyFile(srcPath, destPath);
        }
    }
}

async function main() {
    await rm(destDir, { recursive: true, force: true });
    await copyDir(srcDir, destDir);
    await copyCustomPlugins();
}

async function copyCustomPlugins() {
    try {
        const entries = await readdir(customPluginsDir, { withFileTypes: true });
        for (const entry of entries) {
            if (!entry.isDirectory()) {
                continue;
            }

            const srcPath = join(customPluginsDir, entry.name);
            const destPath = join(destDir, 'plugins', entry.name);
            await copyDir(srcPath, destPath);
        }
    } catch (error) {
        if (error.code !== 'ENOENT') {
            throw error;
        }
    }
}

main().catch((error) => {
    console.error('Failed to copy TinyMCE assets:', error);
    process.exit(1);
});
