import { type Color, ColumnPosition, SwapDirection } from '@/Enums'
import { ChevronLeftIcon, ChevronRightIcon, EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import React from 'react'
import { type SelectingCard } from '@/Pages/Board/Show'

interface Props {
  id: string
  body: string
  color: Color
  columnPosition: ColumnPosition
  onCLickEditHandler: (card: SelectingCard) => void
  onClickMoveHandler: (id: string, direction: SwapDirection) => void
  onClickDeleteHandler: (id: string) => void
}

const backgroundColorVariants = {
  stone: 'bg-stone-100',
  red: 'bg-red-100',
  amber: 'bg-amber-100',
  lime: 'bg-lime-100',
  emerald: 'bg-emerald-100',
  cyan: 'bg-cyan-100',
  blue: 'bg-blue-100',
  violet: 'bg-violet-100',
  fuchsia: 'bg-fuchsia-100',
  rose: 'bg-rose-100'
}

export default function TaskCard ({
  id,
  body,
  color,
  columnPosition,
  onCLickEditHandler,
  onClickMoveHandler,
  onClickDeleteHandler
}: Props): JSX.Element {
  return (
    <div className={'card card-compact shadow-md border border-neutral ' + backgroundColorVariants[color]}>
      <div className="card-body !p-2">
        <header className="flex justify-between">
          <div className="space-x-2">
            {columnPosition !== ColumnPosition.First
              ? (
                <button type="button" className="btn btn-ghost btn-square btn-xs" onClick={() => { onClickMoveHandler(id, SwapDirection.Left) }}>
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
                <button type="button" className="btn btn-ghost btn-square btn-xs" onClick={() => { onClickMoveHandler(id, SwapDirection.Right) }}>
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
              <li><button onClick={() => { onCLickEditHandler({ id, body, color }) }}>Edit</button></li>
              {columnPosition !== ColumnPosition.Last
                ? <li><button onClick={() => { onClickMoveHandler(id, SwapDirection.Right) }}>Move to Right</button></li>
                : <li className="disabled"><a>Move to Right</a></li>
              }
              {columnPosition !== ColumnPosition.First
                ? <li><button onClick={() => { onClickMoveHandler(id, SwapDirection.Left) }}>Move to Left</button></li>
                : <li className="disabled"><a>Move to Left</a></li>
              }
              <li><button className="text-error" onClick={() => { onClickDeleteHandler(id) }}>Delete</button></li>
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
