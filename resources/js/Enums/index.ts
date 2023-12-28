export enum ColumnPosition {
  First,
  Middle,
  Last,
}

export enum ColumnColor {
  Red = '#f87171',
  Amber = '#fbbf24',
  Lime = '#a3e635',
  Emerald = '#34d399',
  Cyan = '#22d3ee',
  Blue = '#60a5fa',
  Violet = '#a78bfa',
  Fuchsia = '#e879f9',
}

export enum CardColor {
  Stone = '#f5f5f4',
  Red = '#fee2e2',
  Amber = '#fef3c7',
  Lime = '#ecfccb',
  Emerald = '#d1fae5',
  Cyan = '#cffafe',
  Blue = '#dbeafe',
  Violet = '#ede9fe',
  Fuchsia = '#fae8ff',
  Rose = '#ffe4e6',
}

export enum SwapDirection {
  Left = -1,
  Right = 1
}

export enum PermissionLevel {
  FullAccess = 'FULL_ACCESS',
  LimitedAccess = 'LIMITED_ACCESS',
  CardOperator = 'CARD_OPERATOR',
  LimitedCardOperator = 'LIMITED_CARD_OPERATOR',
  ReadOnly = 'READ_ONLY'
}
