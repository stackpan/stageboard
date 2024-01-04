import React, { type JSX, useEffect, useState } from 'react'
import type { User } from '@/types'
import { usePage } from '@inertiajs/react'
import { type BoardShowProps } from '@/Pages/Board/Show'

export default function ActiveUser (): JSX.Element {
  const { auth, board } = usePage<BoardShowProps>().props

  const [activeUsers, setActiveUsers] = useState<User[]>([])

  useEffect(() => {
    window.Echo.join(`board.${board.id}`)
      .here((users: User[]) => {
        setActiveUsers(users.filter((user) => user.id !== auth.user.id))
      })
      .joining((user: User) => {
        console.log(user.name)
      })
      .leaving((user: User) => {
        console.log(user.name)
      })
      .error((error: User) => {
        console.error(error)
      })
  }, [])

  return (
    <div className="space-x-1 rtl:space-x-reverse cursor-default">
      {activeUsers.map((user) => (
        <div key={user.id} className="dropdown dropdown-end dropdown-hover">
          <div tabIndex={0} key={user.id} className="avatar placeholder">
            <div tabIndex={0} className={`text-neutral-content rounded-full w-8 ${user.id === board.user.id ? 'bg-primary' : 'bg-secondary'}`}>
              <span>{user.name.slice(0, 2).toUpperCase()}</span>
            </div>
          </div>
          <div tabIndex={0} className="card compact dropdown-content z-[1] shadow bg-base-100 rounded-box w-max">
            <div tabIndex={0} className="card-body">
              <p>{user.name} ({`${user.firstName}${user.lastName !== undefined && ' ' + user.lastName}`})</p>
            </div>
          </div>
        </div>
      ))}
    </div>
  )
}
