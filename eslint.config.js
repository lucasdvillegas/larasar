import pluginVue from 'eslint-plugin-vue';
import globals from 'globals';

// Desactivar esto     // eslint-disable-next-line vue/require-default-prop


export default [
  // 1. Configuración base para archivos de JavaScript y Vue
  {
    files: ['**/*.{js,mjs,cjs,vue}'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        ...globals.browser,
        ...globals.node,
        route: 'readonly', // Esto evita que ESLint marque la función route() de Ziggy/Laravel como un error
      },
    },
  },

  // 2. Aplicar las reglas recomendadas para Vue 3
  ...pluginVue.configs['flat/recommended'],

  // 3. Tus reglas personalizadas
  {
    rules: {
      // Desactivamos esta regla porque en Inertia/Laravel es muy común usar nombres de componentes únicos (ej. Home.vue)
      'vue/multi-word-component-names': 'off', 
      
      // Te avisa si dejas variables declaradas sin usar (ideal para limpiar código)
      'no-unused-vars': 'warn',
      
      // Regla cómoda para el diseño con Quasar/Tailwind
      'vue/max-attributes-per-line': 'off',

      'vue/require-default-prop': 'off', // Desactivamos esta regla porque en Inertia/Laravel es común tener props opcionales sin valor por defecto

      'vue/no-dupe-keys': 'off', // Desactivamos esta regla porque a veces necesitamos definir campos con el mismo nombre (ej. email en ResetPassword.vue)
    },
  },

  // 4. Carpetas que ESLint debe ignorar por completo
  {
    ignores: [
      'public/**',
      'bootstrap/ssr/**',
      'vendor/**',
      'node_modules/**'
    ],
  },
];