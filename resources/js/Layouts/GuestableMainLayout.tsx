import React, { type PropsWithChildren } from 'react'
import GuestableNavbar from '@/Components/GuestableNavbar'

interface Props {
  headerTitle?: string
}

export default function GuestableMainLayout ({ headerTitle, children }: PropsWithChildren<Props>): JSX.Element {
  return (
    <div className="min-h-screen flex flex-col">
      <GuestableNavbar />

      {headerTitle !== undefined && (
        <header className="px-6 pt-8 pb-2">
          <h1 className="text-2xl font-bold capitalize">{headerTitle}</h1>
        </header>
      )}

      <main className="flex-1 flex flex-col">{children}</main>
    </div>
  )
}
