import { type CardColor, type ColumnColor, type Permission } from '@/Enums'

export interface User {
  id: string
  name: string
  firstName: string
  lastName: string
  email: string
  email_verified_at: string
}

type Collaborator = User & {
  permission: Permission
}

export interface Board {
  id: string
  aliasId: string
  name: string
  thumbnailUrl: string
  isPublic: boolean
  openedAt: string
  createdAt: string
  updatedAt: string
  user: Pick<User, 'id' | 'name'>
}

export interface Column {
  id: string
  name: string
  order: number
  color: ColumnColor
  createdAt: string
  updatedAt: string
}

export interface Card {
  id: string
  body: string
  color: CardColor
  createdAt: string
  updatedAt: string
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User
  }
}

export interface PermissionLevelData {
  enumeration: Permission
  label: string
  description: string
  level: number
}
