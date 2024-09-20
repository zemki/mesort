import laravel from "laravel-vite-plugin";
import fs from "fs";
import {defineConfig} from "vite";
import {homedir} from "os";
import {resolve} from "path";
import vue from '@vitejs/plugin-vue2'
import postcss from './postcss.config.js';
import vitePluginRequire from "vite-plugin-require";

let host = "mesort.test";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/js/app.js",
                "resources/sass/app.scss",
                "resources/sass/app_dompdf.scss",
                "resources/sass/app_lingua.scss",
            ], refresh: true
        }),
        vue(),
        vitePluginRequire.default(),
    ],
    css: postcss,
    server: detectServerConfig(host),
    build: {
        commonjsOptions: {transformMixedEsModules: true}
    },
    resolve: {
        alias: {
            vue: "vue/dist/vue.js",
            'jquery-ui': 'jquery-ui-dist/jquery-ui.js',

        },
    },
});

function detectServerConfig(host) {
    let keyPath = resolve(homedir(), `.config/valet/Certificates/${host}.key`);
    let certificatePath = resolve(
        homedir(),
        `.config/valet/Certificates/${host}.crt`
    );

    if (!fs.existsSync(keyPath)) {
        return {};
    }

    if (!fs.existsSync(certificatePath)) {
        return {};
    }

    return {
        hmr: {host},
        host: true,
        cors: true,
        https: {
            key: fs.readFileSync(keyPath),
            cert: fs.readFileSync(certificatePath),
        },
    };
}
