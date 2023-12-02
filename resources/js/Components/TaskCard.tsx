import { ColumnPosition } from '@/Enums'
import { type Links } from '@/types'
import { ChevronLeftIcon, ChevronRightIcon, EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import React from 'react'

interface Props {
  id: string
  body: string
  columnPosition: ColumnPosition
}

export default function TaskCard ({ id, body, columnPosition, links }: Props & Links): JSX.Element {
  return (
    <div className="card card-compact bg-base-100 shadow-md border border-neutral">
      <div className="card-body !p-2">
        <header className="flex justify-between">
          <div className="space-x-2">
            {columnPosition !== ColumnPosition.First
              ? (
                <button type="button" className="btn btn-ghost btn-square btn-xs">
                  <ChevronLeftIcon className="h-6 w-6" />
                </button>
                )
              : (
                <button type="button" className="btn btn-ghost btn-square btn-xs !bg-transparent" disabled>
                  <ChevronLeftIcon className="h-6 w-6" />
                </button>

                )
            }
            {columnPosition !== ColumnPosition.Last
              ? (
                <button type="button" className="btn btn-ghost btn-square btn-xs">
                  <ChevronRightIcon className="h-6 w-6" />
                </button>
                )
              : (
                <button type="button" className="btn btn-ghost btn-square btn-xs !bg-transparent" disabled>
                  <ChevronRightIcon className="h-6 w-6" />
                </button>

                )
            }
          </div>
          <div className={'dropdown' + (columnPosition === ColumnPosition.Last ? ' dropdown-end' : '')}>
            <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
              <EllipsisVerticalIcon className="h-6 w-6" />
            </div>
            <ul className="p-0 shadow-sm menu menu-sm dropdown-content z-[1] bg-base-100 rounded-box w-36 border">
              <li><a>Edit</a></li>
              {columnPosition !== ColumnPosition.First
                ? <li><a>Move to Right</a></li>
                : <li className="disabled"><a>Move to Right</a></li>
              }
              {columnPosition !== ColumnPosition.Last
                ? <li><a>Move to Left</a></li>
                : <li className="disabled"><a>Move to Left</a></li>
              }
              <li><a className="text-error">Delete</a></li>
            </ul>
          </div>
        </header>
        <div className="p-1">
          <p>{body}</p>
        </div>
      </div>
    </div>
  )
}
