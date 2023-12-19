import React, { type ChangeEvent, useState } from 'react'
import { type User } from '@/types'
import axios from 'axios'
import { UserCircleIcon } from '@heroicons/react/24/solid'

interface Props {
  id: string
}

export default function CollaboratorsModal ({ id }: Props): JSX.Element {
  const [searchResults, setSearchResults] = useState<User[]>([])

  const handleChangeSearch = (e: ChangeEvent<HTMLInputElement>): void => {
    const keyword = e.target.value

    axios.get<{ users: User[] }>(route('web.users.search', {
      _query: {
        q: keyword ?? ''
      }
    })).then((response) => {
      setSearchResults(response.data.users)
    }).catch((error) => {
      console.log(error)
    })
  }

  return (
    <dialog id={id} className="modal">
      <section className="modal-box w-11/12 max-w-xl h-96">
        <form method="dialog">
          <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Collaborators</h3>
        </header>
        <div className="mt-4">
          <div className="flex gap-2">
            <div className="w-full dropdown dropdown-bottom">
              <input
                name="search"
                type="text"
                placeholder="Search for username or email"
                className="input input-sm input-bordered w-full"
                onChange={handleChangeSearch}
                autoComplete="off"
              />
              {searchResults.length !== 0 &&
                <ul className="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-full">
                  {searchResults.map((user) => (
                    <li key={user.id}>
                      <a>
                        <UserCircleIcon className="w-8" />
                        <div>
                          <p><span className="font-bold">{user.name}</span> <span>({`${user.firstName}${user.lastName !== undefined && ' ' + user.lastName}`})</span></p>
                          <p className="text-xs">{user.email}</p>
                        </div>
                      </a>
                    </li>
                  ))}
                </ul>
              }
            </div>
            <button type="submit" className="btn btn-neutral btn-sm">Add</button>
          </div>
        </div>
      </section>
    </dialog>
  )
}
