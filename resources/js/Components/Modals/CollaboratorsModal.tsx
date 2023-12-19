import React, { type ChangeEvent, useEffect, useRef, useState } from 'react'
import { type Board, type Card, type Column, type PageProps, type User } from '@/types'
import axios from 'axios'
import { UserCircleIcon } from '@heroicons/react/24/solid'
import { router, usePage } from '@inertiajs/react'
import { MinusCircleIcon } from '@heroicons/react/24/outline'

interface Props {
  id: string
}

export default function CollaboratorsModal ({ id }: Props): JSX.Element {
  const [searchResults, setSearchResults] = useState<User[]>([])
  const [collaborators, setCollaborators] = useState<User[]>([])

  const [shouldRenderCollaborators, setShouldRenderCollaborators] = useState(true)

  const searchInputRef = useRef<HTMLInputElement>(null)

  const pageProps = usePage<PageProps<{
    board: Board
    columns: Array<Column & { cards: Card[] }>
  }>>().props

  useEffect(() => {
    if (shouldRenderCollaborators) {
      axios.get<{ users: User[] }>(route('web.boards.collaborators.index', pageProps.board.id))
        .then((response) => {
          setCollaborators(response.data.users)
        })
        .catch((error) => {
          console.log(error)
        })
      setShouldRenderCollaborators(false)
    }
  })

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

  const handleClickSearchResultItem = (user: User): void => {
    const payload = {
      userId: user.id
    }

    router.post(route('web.boards.collaborators.add', pageProps.board.id), payload, {
      onSuccess: () => {
        setShouldRenderCollaborators(true)
        setSearchResults([])
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

    router.visit(route('web.boards.collaborators.remove', pageProps.board.id), {
      method: 'delete',
      data: payload,
      preserveState: true,
      onSuccess: () => {
        setShouldRenderCollaborators(true)
      }
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
        <div className="mt-4 space-y-4">
          {pageProps.auth.user.id === pageProps.board.user.id && (
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
                              {user.id === pageProps.auth.user.id && <> <span>(You)</span></>}
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
                        {user.id === pageProps.auth.user.id && <> <span>(You)</span></>}
                      </span>
                        {user.id === pageProps.board.user.id && <span className="badge badge-sm">owner</span>}
                      </p>
                      <p className="text-xs">{user.name}</p>
                    </div>
                    {(pageProps.auth.user.id === pageProps.board.user.id && user.id !== pageProps.board.user.id) && (
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
