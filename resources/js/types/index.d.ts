export interface User {
  id: string
  name: string
  email: string
  email_verified_at: string
}

export interface Board {
  id: string
  name: string
  thumbnailUrl: string
  openedAt: string
  createdAt: string
  updatedAt: string
}

export interface Column {
  id: string
  name: string
  order: number
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
