import React, { type ChangeEvent, type JSX, useRef, useState } from 'react'
import BoardSettingSectionLayout from '@/Layouts/BoardSettingSectionLayout'
import { Link, router, usePage } from '@inertiajs/react'
import { type BoardSettingsProps } from '@/Pages/Board/Settings'
import { MinusCircleIcon } from '@heroicons/react/24/outline'
import { UserCircleIcon } from '@heroicons/react/24/solid'
import type {Board, Collaborator, User} from '@/types'
import axios from 'axios'
import { Permission } from '@/Enums'
import { mapPermission } from '@/Utils'

interface Props {
  className?: string
}

export default function CollaborationBoardSettings ({ className = '' }: Props): JSX.Element {
  const { board, collaborators, auth } = usePage<BoardSettingsProps>().props

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

  return (
    <BoardSettingSectionLayout name="Collaboration" className={className}>
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
                <button onClick={() => {
                  handleClickSearchResultItem(user)
                }}>
                  <UserCircleIcon className="w-8"/>
                  <div>
                    <p>
                      <span className="font-bold">{user.name}</span>
                      <span>({`${user.firstName}${user.lastName !== undefined && ' ' + user.lastName}`})</span>
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
      <ul className="mt-4">
        {collaborators.map((user, index) => (
          <li key={user.id}
              className={`p-2 flex flex-row items-center hover:bg-base-200 ${index !== collaborators.length - 1 && 'border-b'}`}>
            <div className="flex-1">
              <p className="inline-block space-x-2">
                <span>
                  <span
                    className="font-bold">{`${user.firstName}${user.lastName !== undefined && ' ' + user.lastName}`}</span>
                  {user.id === auth.user.id && <> <span>(You)</span></>}
                </span>
                {user.id === board.user.id && <span className="badge badge-sm">owner</span>}
              </p>
              <p className="text-xs">{user.name}</p>
            </div>
            <div className="flex gap-4">
              <div className="dropdown dropdown-end">
                {user.permission !== Permission.FullAccess
                  ? (
                    <>
                      <div tabIndex={0} role="button"
                           className="btn btn-xs">{mapPermission(user.permission).label}</div>
                      <ul tabIndex={0} className="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-96">
                        {Object.values(Permission).map((permission) => (
                          <PermissionDropdownItem key={permission} permission={permission} user={user} board={board} />
                        ))}
                      </ul>
                    </>
                    )
                  : <div className="btn btn-xs cursor-default">{mapPermission(user.permission).label}</div>
                }
              </div>
              {(auth.user.id === board.user.id && user.id !== board.user.id) && (
                <div className="w-8">
                  <Link
                    href={route('web.boards.collaborators.destroy', board.id)}
                    className="btn btn-circle btn-ghost btn-xs"
                    method="delete"
                    data={{
                      userId: user.id
                    }}
                    as="button"
                  >
                    <MinusCircleIcon className="w-6"/>
                  </Link>
                </div>
              )}
            </div>
          </li>
        ))}
      </ul>
    </BoardSettingSectionLayout>
  )
}

const PermissionDropdownItem = ({ permission, user, board }: {
  permission: Permission,
  user: Collaborator,
  board: Board
}) => {
  const permissionData = mapPermission(permission)

  const disabled = permissionData.enumeration === Permission.FullAccess
  const selected = user.permission === permissionData.enumeration

  return (
    <li className={(selected ? 'bg-base-200' : '') + (disabled ? ' disabled' : '')}>
      {!(disabled || selected)
        ? (
          <Link
            href={route('web.boards.collaborators.update', board.id)}
            method="patch"
            as="button"
            data={{
              userId: user.id,
              permission: permissionData.enumeration
            }}
            className="flex flex-col items-start"
          >
            <p className="font-bold">{permissionData.label}</p>
            <p className="text-gray-500 text-xs">{permissionData.description}</p>
          </Link>
        )
        : (
          <div className="flex flex-col items-start">
            <p className="font-bold">{permissionData.label}</p>
            <p className="text-gray-500 text-xs">{permissionData.description}</p>
          </div>
        )
      }
    </li>
  )
}
