import React, { type JSX, useState } from 'react'
import type { User } from '@/types'
import { usePage } from '@inertiajs/react'
import { type BoardShowProps } from '@/Pages/Board/Show'

export default function ActiveUser ({ users }: { users: User[] }): JSX.Element {
  const { board } = usePage<BoardShowProps>().props

  const [rendererUsers, restUsersCount] = [users.slice(0, 6), users.slice(6).length]

  return (
    <div className="flex flex-row-reverse gap-1 cursor-default">
      {rendererUsers.map((user) => (
        <div key={user.id} className="dropdown dropdown-end dropdown-hover">
          <div tabIndex={0} key={user.id} className="avatar placeholder">
            <div tabIndex={0}
                 className={`text-neutral-content rounded-full w-8 ${user.id === board.user.id ? 'bg-primary' : 'bg-secondary'}`}>
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
      {restUsersCount > 0 && (
        <div className="avatar placeholder">
          <div className="text-secondary-content border-2 border-secondary rounded-full w-8">
            <span>+{restUsersCount}</span>
          </div>
        </div>
      )}
    </div>
  )
}
