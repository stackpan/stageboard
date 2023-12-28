import React, { type JSX, type PropsWithChildren } from 'react'

interface Props {
  name: string
  className?: string
}

export default function BoardSettingSectionLayout ({ name, className = '', children }: PropsWithChildren<Props>): JSX.Element {
  return (
    <section className={className}>
      <header>
        <h2 className="text-xl">{name}</h2>
        <div className="divider"></div>
      </header>
      <div>{children}</div>
    </section>
  )
}
