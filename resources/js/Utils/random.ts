import { ColumnColor } from '@/Enums'

export const getRandomColumnColorValue = (): ColumnColor => {
  const values = Object.values(ColumnColor)
  const randomIndex = Math.floor(Math.random() * values.length)
  return values[randomIndex]
}
