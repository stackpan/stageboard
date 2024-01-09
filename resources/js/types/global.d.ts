import { type AxiosInstance } from 'axios'
import { type Config as ZiggyConfig } from 'ziggy-js'
import type ziggyRoute from 'ziggy-js'
import type Echo from 'laravel-echo'
import type Pusher from 'pusher-js'

declare global {
  interface Window {
    axios: AxiosInstance
    Echo: Echo
    Pusher: Pusher
  }

  let route: typeof ziggyRoute
  let Ziggy: ZiggyConfig
}

declare module 'daisyui'
