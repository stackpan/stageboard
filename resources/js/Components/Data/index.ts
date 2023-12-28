import { Permission } from '@/Enums'
export const permissionData = {
  FULL_ACCESS: {
    enumeration: Permission.FullAccess,
    label: 'Full Access',
    description: 'Intended to board owner. Every board operations are allowed.',
    level: 0
  },
  LIMITED_ACCESS: {
    enumeration: Permission.LimitedAccess,
    label: 'Limited Access',
    description: 'Normal collaborator permissions. Permits every board content operations.',
    level: 1
  },
  CARD_OPERATOR: {
    enumeration: Permission.CardOperator,
    label: 'Card Operator',
    description: 'Every collaborators who assigned in this level cannot manage columns operation. But still allowed to manage cards.',
    level: 2
  },
  LIMITED_CARD_OPERATOR: {
    enumeration: Permission.LimitedCardOperator,
    label: 'Limited Card Operator',
    description: 'Only permits move card operation.',
    level: 3
  },
  READ_ONLY: {
    enumeration: Permission.ReadOnly,
    label: 'Read Only',
    description: 'Restrict every board and its content operations but still can access the board.',
    level: 4
  }
}
