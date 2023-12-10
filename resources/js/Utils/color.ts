import { type ColumnColor } from '@/Enums'

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
