import React, { type JSX } from 'react'
import { EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import { formatFromNow } from '@/Utils/datetime'
import { Link } from '@inertiajs/react'

interface Props {
  id: string
  aliasId: string
  name: string
  thumbnailUrl: string
  owner: string
  openedAt: string
  onClickDeleteHandler: (id: string) => void
}

export default function BoardCard ({ id, aliasId, name, owner, openedAt, onClickDeleteHandler }: Props): JSX.Element {
  return (
    <Link
      as="div"
      href={route('web.page.board.show', aliasId)}
      className="card card-compact w-72 bg-base-100 shadow-md flex-none rounded hover:bg-base-200 active:bg-base-300 cursor-pointer"
    >
      <div className="h-3 rounded-t bg-neutral"></div>
      <div className="card-body">
        <div className="flex justify-between">
          <h2 className="card-title">{name}</h2>
          <div className="dropdown">
            <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs z-10" onClick={(e) => { e.stopPropagation() }}>
              <EllipsisVerticalIcon className="h-6 w-6" />
            </div>
            <ul className="p-0 shadow menu menu-sm dropdown-content z-20 bg-base-100 rounded-box w-36">
              <li><a target="_blank" href={route('web.page.board.show', aliasId)} rel="noreferrer">Open in New Tab</a></li>
              <li><Link as="button" href={route('web.page.board.edit', aliasId)}>Edit</Link></li>
              <li><button onClick={() => { onClickDeleteHandler(id) }} className="text-error">Delete</button></li>
            </ul>
          </div>
        </div>
        <p><span>{owner}</span> - <span>{formatFromNow(openedAt)}</span></p>
      </div>
    </Link>
  )
}
