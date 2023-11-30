import Navbar from '@/Components/Navbar'
import { type User } from '@/types'
import React, { type PropsWithChildren } from 'react'

interface Props {
  user: User
}

export default function MainLayout ({ user, children }: PropsWithChildren<Props>): JSX.Element {
  return (
    <div className="min-h-screen">
      <header>
        <Navbar user={user} />
      </header>
      <main>
        {children}
      </main>
    </div>
  )
}
