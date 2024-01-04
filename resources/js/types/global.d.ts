import { type AxiosInstance } from 'axios'
import { type Config as ZiggyConfig } from 'ziggy-js'
import type ziggyRoute from 'ziggy-js'
import type Echo from 'laravel-echo'

declare global {
  interface Window {
    axios: AxiosInstance
    Echo: Echo
  }

  let route: typeof ziggyRoute
  let Ziggy: ZiggyConfig
}

declare module 'daisyui'
