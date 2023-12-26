import React, { type LabelHTMLAttributes, type ReactNode } from 'react'

export default function InputLabel ({
  value,
  className = '',
  children,
  ...props
}: LabelHTMLAttributes<HTMLLabelElement> & { value?: string }): ReactNode {
  return (
    <label {...props} className={'block font-medium text-sm text-gray-700 ' + className}>
      {value ?? children}
    </label>
  )
}
