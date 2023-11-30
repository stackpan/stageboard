import './bootstrap'
import '../css/app.css'

import React from 'react'
import { createRoot } from 'react-dom/client'
import { createInertiaApp } from '@inertiajs/react'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

// eslint-disable-next-line @typescript-eslint/strict-boolean-expressions
const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

// eslint-disable-next-line @typescript-eslint/no-floating-promises
createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: async (name) => await resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
  setup ({ el, App, props }) {
    const root = createRoot(el)

    root.render(<App {...props} />)
  },
  progress: {
    color: '#4B5563'
  }
})
