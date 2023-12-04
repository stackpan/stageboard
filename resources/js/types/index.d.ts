import { type Color } from '@/Enums'

export interface User {
  id: string
  name: string
  email: string
  email_verified_at: string
}

export interface Board {
  id: string
  aliasId: string
  name: string
  thumbnailUrl: string
  openedAt: string
  createdAt: string
  updatedAt: string
  user: Pick<User, 'id' | 'name'>
}

export interface Column {
  id: string
  name: string
  order: number
  color: Color
  createdAt: string
  updatedAt: string
}

export interface Card {
  id: string
  body: string
  color: Color
  createdAt: string
  updatedAt: string
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User
  }
}

export interface Link {
  href: string
  rel: string
  method: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
}

export interface Links {
  links: Record<string, Link>
}

export interface ResponseBody {
  message: string
}

export interface ResponseBodyWithData<T> extends ResponseBody {
  data: T
}
