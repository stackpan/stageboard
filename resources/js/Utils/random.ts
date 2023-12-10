import { CardColor, ColumnColor } from '@/Enums'

export const getRandomColumnColor = (): ColumnColor => {
  const values = Object.values(ColumnColor)
  const randomIndex = Math.floor(Math.random() * values.length)
  return values[randomIndex]
}

export const getRandomCardColor = (): CardColor => {
  const values = Object.values(CardColor)
  const randomIndex = Math.floor(Math.random() * values.length)
  return values[randomIndex]
}
