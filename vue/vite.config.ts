import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'
import path from 'path'

export default defineConfig({
    base: './',
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
        base: '/',
        proxy: {
            '/api': {
                target: 'http://localhost:8080/ewl' ?? '',
                changeOrigin: true,
            },
            '/legacy': {
                target: 'http://localhost:8080/ewl' ?? '',
                changeOrigin: true,
            },
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
