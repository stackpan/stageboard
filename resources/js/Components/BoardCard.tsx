import React from 'react'
import { EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import { type Link } from '@/types'
import { formatFromNow } from '@/Utils/datetime'
import { router } from '@inertiajs/react'

interface Props {
  id: string
  name: string
  thumbnailUrl: string
  owner: string
  openedAt: string
  links: Record<string, Link>
}

export default function BoardCard ({ id, name, thumbnailUrl, owner, openedAt, links }: Props): JSX.Element {
  const openBoardPage = (): void => {
    router.get(`/board/${id}`)
  }

  return (
    <div className="card card-compact w-64 bg-base-100 shadow-xl">
      <figure onClick={openBoardPage} className="cursor-pointer">
        {thumbnailUrl !== null
          ? <img src={thumbnailUrl} alt={`${name} thumbnail`} />
          : <img src="https://daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.jpg" alt="Shoes" />
          }
      </figure>
      <div className="card-body">
        <div className="flex justify-between">
          <h2 className="card-title">{name}</h2>
          <div className="dropdown">
            <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
              <EllipsisVerticalIcon className="h-6 w-6" />
            </div>
            <ul className="p-0 shadow menu menu-sm dropdown-content z-[1] bg-base-100 rounded-box w-36">
              <li><a>Open in New Tab</a></li>
              <li><a>Rename</a></li>
              <li><a className="text-error">Delete</a></li>
            </ul>
          </div>
        </div>
        <p><span>{owner}</span> - <span>{formatFromNow(openedAt)}</span></p>
      </div>
    </div>
  )
}
