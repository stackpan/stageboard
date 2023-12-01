import { type Links } from '@/types'
import { ChevronLeftIcon, ChevronRightIcon, EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import React from 'react'

interface Props {
  id: string
  body: string
}

export default function TaskCard ({ id, body, links }: Props & Links): JSX.Element {
  return (
    <article className="p-1 border border-neutral rounded drop-shadow-lg">
      <header className="flex justify-between">
        <div className="space-x-2">
          <button type="button" className="btn btn-ghost btn-square btn-xs">
            <ChevronLeftIcon className="h-6 w-6" />
          </button>
          <button type="button" className="btn btn-ghost btn-square btn-xs">
            <ChevronRightIcon className="h-6 w-6" />
          </button>
        </div>
        <div className="dropdown relative">
          <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
            <EllipsisVerticalIcon className="h-6 w-6" />
          </div>
          <ul className="p-0 shadow menu menu-sm dropdown-content z-[1] bg-base-100 rounded-box w-36">
            <li><a>Edit</a></li>
            <li><a>Move to Right</a></li>
            <li><a>Move to Left</a></li>
            <li><a className="text-error">Delete</a></li>
          </ul>
        </div>
      </header>
      <div className="p-1">
        <p>{ body }</p>
      </div>
    </article>
  )
}
