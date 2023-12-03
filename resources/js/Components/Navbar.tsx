import { type User } from '@/types'
import { router } from '@inertiajs/react'
import React from 'react'

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
              <img alt="Tailwind CSS Navbar component" src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" />
            </div>
          </div>
          <ul className="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
            <li>
              <a className="justify-between">
                Profile
                <span className="badge">New</span>
              </a>
            </li>
            <li><a>Settings</a></li>
            <li><a>Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  )
}
