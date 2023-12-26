import Navbar from '@/Components/Navbar'
import { type User } from '@/types'
import React, { type PropsWithChildren } from 'react'

interface Props {
  user: User
  headerTitle?: string
}

export default function MainLayout ({ user, headerTitle, children }: PropsWithChildren<Props>): JSX.Element {
  return (
    <div className="min-h-screen flex flex-col">
      <Navbar user={user} />

      {headerTitle !== undefined && (
        <header className="px-6 py-4">
          <h1 className="text-2xl font-bold">{headerTitle}</h1>
        </header>
      )}

      <main className="flex-1 flex flex-col">{children}</main>
    </div>
  )
}
