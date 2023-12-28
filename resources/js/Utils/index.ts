import { type PermissionLevel } from '@/Enums'
import { permissionLevelData } from '@/Components/Data'
import { type PermissionLevelData } from '@/types'

export const mapPermissionLevel = (enumeration: PermissionLevel): PermissionLevelData => {
  // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
  return permissionLevelData.find(value => value.enumeration === enumeration)!
}
