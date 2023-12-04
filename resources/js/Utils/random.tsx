import { Color } from '@/Enums'

export const getRandomColorValue = (): Color => {
  const values = Object.values(Color)
  const randomIndex = Math.floor(Math.random() * values.length)
  return values[randomIndex]
}
