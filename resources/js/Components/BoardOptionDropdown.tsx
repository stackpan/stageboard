import React, { type JSX } from 'react'
import { EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import { Link } from '@inertiajs/react'

interface Props {
  boardId: string
  boardAliasId: string
  className?: string
}

export default function BoardOptionDropdown ({ boardId, boardAliasId, className = '' }: Props): JSX.Element {
  return (
    <div className={`dropdown ${className}`}>
      <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs z-10" onClick={(e) => {
        e.stopPropagation()
      }}>
        <EllipsisVerticalIcon className="h-6 w-6"/>
      </div>
      <ul className="p-0 shadow menu menu-sm dropdown-content z-20 bg-base-100 rounded-box w-36">
        <li onClick={(e) => { e.stopPropagation() }}>
          <a target="_blank" href={route('web.page.board.show', boardAliasId)} rel="noreferrer">Open in New Tab</a>
        </li>
        <li>
          <Link as="button" href={route('web.page.board.edit', boardAliasId)}>Edit</Link>
        </li>
        <li>
          <Link
            as="button"
            method="delete"
            href={route('web.boards.destroy', boardId)}
            className="text-error"
          >
            Delete
          </Link>
        </li>
      </ul>
    </div>
  )
}
