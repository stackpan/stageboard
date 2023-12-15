import { type CardColor, type ColumnColor } from '@/Enums'

export const convertToBackgroundColor = (color: ColumnColor | CardColor): object => ({
  backgroundColor: color
})
