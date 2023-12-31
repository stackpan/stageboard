import { type User } from '@/types'
import { Link, router } from '@inertiajs/react'
import React from 'react'
import { UserCircleIcon } from '@heroicons/react/24/solid'

interface Props {
  user: User
}

export default function Navbar ({ user }: Props): JSX.Element {
  return (
    <div className="navbar bg-base-100 shadow-md z-10">
      <div className="flex-1">
        {!route().current('home')
          ? <a className="btn btn-ghost text-xl" onClick={() => { router.get('/home') }}>Stageboard</a>
          : <a className="btn btn-ghost text-xl">Stageboard</a>
        }
      </div>

      <div className="flex-none">
        <div className="dropdown dropdown-end">
          <div tabIndex={0} role="button" className="btn btn-ghost avatar">
            <p>{user.name}</p>
            <div className="w-10 rounded-full">
              <UserCircleIcon />
            </div>
          </div>
          <ul className="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
            <li>
              <Link href={route('profile.edit')} as="button">Profile</Link>
            </li>
            <li>
              <Link href={route('web.page.home')} as="button">Home</Link>
            </li>
            <li></li>
            <li>
              <Link href={route('logout')} method="post" as="button">Logout</Link>
            </li>
          </ul>
        </div>
      </div>
    </div>
  )
}
