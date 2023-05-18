import legacy from '@vitejs/plugin-legacy'

export default {
  base: './',
  plugins: legacy({
	targets: ['defaults', 'not IE 11'],
  })
}