import { EllipsisVerticalIcon, PlusIcon } from '@heroicons/react/24/outline'
import React from 'react'
import TaskCard from './TaskCard'
import { type Card } from '@/types'
import { type Color, ColumnPosition, SwapDirection } from '@/Enums'
import { type SelectingColumn } from '@/Pages/Board/Show'

interface Props {
  id: string
  name: string
  order: number
  position: ColumnPosition
  color: Color
  cards: Card[]
  onClickEditHandler: (column: SelectingColumn) => void
  onClickSwapHandler: (id: string, currentOrder: number, direction: SwapDirection) => void
  onClickDeleteHandler: (id: string) => void
  onClickCreateCardHandler: (column: SelectingColumn) => void
  onClickDeleteCardHandler: (id: string) => void
}

const stripColorVariants = {
  stone: 'bg-stone-400',
  red: 'bg-red-400',
  amber: 'bg-amber-400',
  lime: 'bg-lime-400',
  emerald: 'bg-emerald-400',
  cyan: 'bg-cyan-400',
  blue: 'bg-blue-400',
  violet: 'bg-violet-400',
  fuchsia: 'bg-fuchsia-400',
  rose: 'bg-rose-400'
}

export default function ColumnCard ({
  id,
  name,
  order,
  position,
  cards,
  color,
  onClickEditHandler,
  onClickDeleteHandler,
  onClickSwapHandler,
  onClickCreateCardHandler,
  onClickDeleteCardHandler
}: Props): JSX.Element {
  return (
    <div className="card card-compact w-72 bg-base-100 shadow-md border border-neutral rounded space-y-4">
      <div className={'h-2 ' + stripColorVariants[color]}></div>
      <div className="card-body !mt-0">
        <div className="flex justify-between items-start">
          <h2 className="card-title">{name}</h2>
          <div className="space-x-2">
            <div className="btn btn-ghost btn-square btn-xs" onClick={() => { onClickCreateCardHandler({ id, name, color }) }}>
              <PlusIcon className="h-6 w-6" />
            </div>
            <div className={'dropdown' + (position === ColumnPosition.Last ? ' dropdown-end' : '')}>
              <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
                <EllipsisVerticalIcon className="h-6 w-6" />
              </div>
              <ul tabIndex={0} className="p-0 shadow menu menu-sm dropdown-content z-30 bg-base-100 rounded-box w-36 border">
                <li><button onClick={() => { onClickEditHandler({ id, name, color }) }}>Edit</button></li>
                {position !== ColumnPosition.Last
                  ? <li><button onClick={() => { onClickSwapHandler(id, order, SwapDirection.Right) }}>Swap to Right</button></li>
                  : <li className="disabled"><button>Swap to Right</button></li>}
                {position !== ColumnPosition.First
                  ? <li><button onClick={() => { onClickSwapHandler(id, order, SwapDirection.Left) }}>Swap to Left</button></li>
                  : <li className="disabled"><button>Swap to Left</button></li>
                }
                <li><button className="text-error" onClick={() => { onClickDeleteHandler(id) }}>Delete</button></li>
              </ul>
            </div>
          </div>
        </div>
        <div className="flex flex-col gap-4">
          {cards.map((card) => (
            <TaskCard
              key={card.id}
              id={card.id}
              body={card.body}
              color={card.color}
              columnPosition={position}
              onClickDeleteHandler={onClickDeleteCardHandler}
            />
          ))}
        </div>
      </div>
    </div>
  )
}
