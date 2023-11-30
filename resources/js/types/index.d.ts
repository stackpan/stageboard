export interface User {
  id: string
  name: string
  email: string
  email_verified_at: string
}

export default interface Board {
  id: string
  name: string
  thumbnailUrl: string
  owner: string
  openedAt: string
  createdAt: string
  updatedAt: string
  links: Record<string, Link>
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User
  }
}

interface Link {
  href: string
  rel: string
  method: string
}
