import React, { type PropsWithChildren } from 'react'
import GuestNavbar from '@/Components/GuestNavbar'
import { type User } from '@/types'
import Navbar from '@/Components/Navbar'

interface Props {
  user?: User
  headerTitle?: string
  className?: string
}

export default function GuestableMainLayout ({ headerTitle, children, user, className }: PropsWithChildren<Props>): JSX.Element {
  return (
    <div className="min-h-screen flex flex-col">
      {user !== undefined ? <Navbar user={user} /> : <GuestNavbar />}

      {headerTitle !== undefined && (
        <header className="px-6 pt-8 pb-2">
          <h1 className="text-2xl font-bold capitalize">{headerTitle}</h1>
        </header>
      )}

      <main className={`flex-1 flex flex-col ${className}`}>{children}</main>
    </div>
  )
}
