import { type CardColor, type ColumnColor } from '@/Enums'

export const convertColumnColor = (color: ColumnColor): string => {
  const colorVariants = {
    '#f87171': 'bg-red-400',
    '#fbbf24': 'bg-amber-400',
    '#a3e635': 'bg-lime-400',
    '#34d399': 'bg-emerald-400',
    '#22d3ee': 'bg-cyan-400',
    '#60a5fa': 'bg-blue-400',
    '#a78bfa': 'bg-violet-400',
    '#e879f9': 'bg-fuchsia-400'
  }

  return colorVariants[color]
}

export const convertCardColor = (color: CardColor): string => {
  const colorVariants = {
    '#f5f5f4': 'bg-stone-100',
    '#fee2e2': 'bg-red-100',
    '#fef3c7': 'bg-amber-100',
    '#ecfccb': 'bg-lime-100',
    '#d1fae5': 'bg-emerald-100',
    '#cffafe': 'bg-cyan-100',
    '#dbeafe': 'bg-blue-100',
    '#ede9fe': 'bg-violet-100',
    '#fae8ff': 'bg-fuchsia-100',
    '#ffe4e6': 'bg-rose-100'
  }

  return colorVariants[color]
}
