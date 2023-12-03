import Navbar from '@/Components/Navbar'
import { type User } from '@/types'
import React, { type PropsWithChildren } from 'react'

interface Props {
  user: User
}

export default function MainLayout ({ user, children }: PropsWithChildren<Props>): JSX.Element {
  return (
    <div className="min-h-screen flex flex-col">
      <header>
        <Navbar user={user} />
      </header>
      <main className="flex-1 flex flex-col">
        {children}
      </main>
    </div>
  )
}
