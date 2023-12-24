import React, { type ChangeEvent, useRef, useState } from 'react'
import { type User } from '@/types'
import axios from 'axios'
import { UserCircleIcon } from '@heroicons/react/24/solid'
import { router, usePage } from '@inertiajs/react'
import { MinusCircleIcon } from '@heroicons/react/24/outline'
import { type BoardShowProps } from '@/Pages/BoardPage'

interface Props {
  active: boolean
  closeHandler: () => void
}

export default function CollaboratorsModal ({ active, closeHandler }: Props): JSX.Element {
  const { collaborators, auth, board } = usePage<BoardShowProps>().props

  const [searchResults, setSearchResults] = useState<User[]>([])

  const searchInputRef = useRef<HTMLInputElement>(null)

  const handleChangeSearch = (e: ChangeEvent<HTMLInputElement>): void => {
    const keyword = e.target.value

    axios.get<User[]>(route('web.users.search', {
      _query: {
        q: keyword ?? ''
      }
    })).then((response) => {
      setSearchResults(response.data)
    }).catch((error) => {
      console.log(error)
    })
  }

  const handleClickSearchResultItem = (user: User): void => {
    const payload = {
      userId: user.id
    }

    router.post(route('web.boards.collaborators.store', board.id), payload, {
      onSuccess: () => {
        setSearchResults([])
        router.reload({ only: ['collaborators'] })
      },
      onError: (e) => {
        console.log(e)
      },
      onFinish: () => {
        if (searchInputRef.current !== null) {
          searchInputRef.current.value = ''
        }
      }
    })
  }

  const handleClickCollaboratorItemRemove = (user: User): void => {
    const payload = {
      userId: user.id
    }

    router.visit(route('web.boards.collaborators.destroy', board.id), {
      method: 'delete',
      data: payload,
      preserveState: true,
      onSuccess: () => {
        router.reload({ only: ['collaborators'] })
      }
    })
  }

  return (
    <dialog className={'modal' + (active ? ' modal-open' : '')}>
      <section className="modal-box w-11/12 max-w-xl h-96">
        <form method="dialog">
          <button onClick={closeHandler} className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Collaborators</h3>
        </header>
        <div className="mt-4 space-y-4">
          {auth.user.id === board.user.id && (
            <div className="flex gap-2">
              <div className="w-full dropdown dropdown-bottom">
                <input
                  name="search"
                  type="text"
                  ref={searchInputRef}
                  placeholder="Search for username or email to add"
                  className="input input-sm input-bordered w-full"
                  onChange={handleChangeSearch}
                  autoComplete="off"
                />
                {searchResults.length !== 0 &&
                  <ul className="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-full">
                    {searchResults.map((user) => (
                      <li key={user.id}>
                        <button onClick={() => { handleClickSearchResultItem(user) }}>
                          <UserCircleIcon className="w-8" />
                          <div>
                            <p>
                              <span className="font-bold">{user.name}</span> <span>({`${user.firstName}${user.lastName !== undefined && ' ' + user.lastName}`})</span>
                              {user.id === auth.user.id && <> <span>(You)</span></>}
                            </p>
                            <p className="text-xs">{user.email}</p>
                          </div>
                        </button>
                      </li>
                    ))}
                  </ul>
                }
              </div>
            </div>
          )}
          <div>
            <ul>
              {collaborators.map((user, index) => (
                <li key={user.id}>
                  <div className={`py-2 flex flex-row items-center ${index !== collaborators.length - 1 && 'border-b'}`}>
                    <div className="flex-1">
                      <p className="inline-block space-x-2">
                      <span>
                        <span className="font-bold">{`${user.firstName}${user.lastName !== undefined && ' ' + user.lastName}`}</span>
                        {user.id === auth.user.id && <> <span>(You)</span></>}
                      </span>
                        {user.id === board.user.id && <span className="badge badge-sm">owner</span>}
                      </p>
                      <p className="text-xs">{user.name}</p>
                    </div>
                    {(auth.user.id === board.user.id && user.id !== board.user.id) && (
                      <div className="w-8">
                        <button className="btn btn-circle btn-ghost btn-xs" onClick={() => { handleClickCollaboratorItemRemove(user) }}>
                          <MinusCircleIcon className="w-6"/>
                        </button>
                      </div>
                    )}
                  </div>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </section>
    </dialog>
  )
}
