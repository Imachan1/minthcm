import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'
import path from 'path'

// eslint-disable-next-line @typescript-eslint/ban-ts-comment
//@ts-ignore
process.env = {
    ...process.env,
    ...loadEnv(process.env.NODE_ENV as string, process.cwd(), ''),
}

export default defineConfig({
    base: '/',
    plugins: [
        vue(),
        vuetify({
            autoImport: true,
        }),
    ],
    define: {
        'process.env': {},
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'src'),
        },
    },
    server: {
        proxy: {
            '/index.php': process.env.PROXY_URL ?? '',
            '/themes': process.env.PROXY_URL ?? '',
            '/include': process.env.PROXY_URL ?? '',
            '/cache': process.env.PROXY_URL ?? '',
            '/custom': process.env.PROXY_URL ?? '',
            '/modules': process.env.PROXY_URL ?? '',
        },
    },
    /* remove the need to specify .vue files https://vitejs.dev/config/#resolve-extensions
  resolve: {
    extensions: [
      '.js',
      '.json',
      '.jsx',
      '.mjs',
      '.ts',
      '.tsx',
      '.vue',
    ]
  },
  */
})
