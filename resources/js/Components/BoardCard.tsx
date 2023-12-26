import React from 'react'
import { EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import { formatFromNow } from '@/Utils/datetime'
import { router } from '@inertiajs/react'

interface Props {
  id: string
  aliasId: string
  name: string
  thumbnailUrl: string
  owner: string
  openedAt: string
  onClickEditHandler: (id: string) => void
  onClickDeleteHandler: (id: string) => void
}

export default function BoardCard ({ id, aliasId, name, thumbnailUrl, owner, openedAt, onClickEditHandler, onClickDeleteHandler }: Props): JSX.Element {
  return (
    <div className="card card-compact w-72 bg-base-100 shadow-md flex-none">
      <figure onClick={() => { router.get(route('web.page.board.show', aliasId)) }} className="cursor-pointer h-32">
        {thumbnailUrl !== null
          ? <img src={thumbnailUrl} alt={`${name} thumbnail`} />
          : <img src='/img/board-thumbnail-fallback.png' alt="Fallback thumbnail" />
        }
      </figure>
      <div className="card-body">
        <div className="flex justify-between">
          <h2 className="card-title">{name}</h2>
          <div className="dropdown">
            <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
              <EllipsisVerticalIcon className="h-6 w-6" />
            </div>
            <ul className="p-0 shadow menu menu-sm dropdown-content z-10 bg-base-100 rounded-box w-36">
              <li><a target="_blank" href={route('web.page.board.show', aliasId)} rel="noreferrer">Open in New Tab</a></li>
              <li><button onClick={() => { onClickEditHandler(id) }}>Edit</button></li>
              <li><button onClick={() => { onClickDeleteHandler(id) }} className="text-error">Delete</button></li>
            </ul>
          </div>
        </div>
        <p><span>{owner}</span> - <span>{formatFromNow(openedAt)}</span></p>
      </div>
    </div>
  )
}
