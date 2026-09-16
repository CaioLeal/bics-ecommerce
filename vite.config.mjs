import { defineConfig } from 'vite'
import htmlMinifier from 'vite-plugin-html-minifier-terser'

export default defineConfig({
  plugins: [
    htmlMinifier({
      removeComments: true,
      collapseWhitespace: true,
      minifyCSS: true,
      minifyJS: true,
    }),
  ],

  build: {
    minify: 'terser',

    terserOptions: {
      format: {
        comments: false,
      },
    },
  },
})