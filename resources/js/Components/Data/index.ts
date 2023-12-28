import { PermissionLevel } from '@/Enums'
import { type PermissionLevelData } from '@/types'

export const permissionLevelData: PermissionLevelData[] = [
  {
    enumeration: PermissionLevel.FullAccess,
    label: 'Full Access',
    description: 'Intended to board owner. Every board operations are allowed.',
    level: 0
  },
  {
    enumeration: PermissionLevel.LimitedAccess,
    label: 'Limited Access',
    description: 'Normal collaborator permissions. Permits every board content operations.',
    level: 1
  },
  {
    enumeration: PermissionLevel.CardOperator,
    label: 'Card Operator',
    description: 'Every collaborators who assigned in this level cannot manage columns operation. But still allowed to manage cards.',
    level: 2
  },
  {
    enumeration: PermissionLevel.LimitedCardOperator,
    label: 'Limited Card Operator',
    description: 'Only permits move card operation.',
    level: 3
  },
  {
    enumeration: PermissionLevel.ReadOnly,
    label: 'Read Only',
    description: 'Restrict every board and its content operations but still can access the board.',
    level: 4
  }
]
