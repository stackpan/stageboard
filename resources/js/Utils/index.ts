import { type Permission } from '@/Enums'
import { type PermissionLevelData } from '@/types'
import { permissionData } from '@/Components/Data'

export const mapPermission = (enumeration: Permission): PermissionLevelData => {
  return permissionData[enumeration]
}

export const getPermissionLevel = (enumeration: Permission): number => {
  return mapPermission(enumeration).level
}
