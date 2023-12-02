import { EllipsisVerticalIcon, PlusIcon } from '@heroicons/react/24/outline'
import React from 'react'
import TaskCard from './TaskCard'
import { type Card, type Links } from '@/types'
import { ColumnPosition } from '@/Enums'
import { showModal } from '@/Utils/dom'

interface Props {
  id: string
  name: string
  position: ColumnPosition
  cards: Array<Card & Links>
}

export default function ColumnCard ({ id, name, position, cards, links }: Props & Links): JSX.Element {
  return (
    <div className="card card-compact w-72 bg-base-100 shadow-md border border-neutral rounded space-y-4">
      <div className="card-body">
        <div className="flex justify-between items-start">
          <h2 className="card-title">{name}</h2>
          <div className="space-x-2">
            <div className="btn btn-ghost btn-square btn-xs" onClick={() => { showModal('createColumnModal') }}>
              <PlusIcon className="h-6 w-6" />
            </div>
            <div className={'dropdown' + (position === ColumnPosition.Last ? ' dropdown-end' : '')}>
              <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
                <EllipsisVerticalIcon className="h-6 w-6" />
              </div>
              <ul tabIndex={0} className="p-0 shadow menu menu-sm dropdown-content z-30 bg-base-100 rounded-box w-36 border">
                <li><a>Rename</a></li>
                {position !== ColumnPosition.First
                  ? <li><a>Move to Right</a></li>
                  : <li className="disabled"><a>Move to Right</a></li>}
                {position !== ColumnPosition.Last
                  ? <li><a>Move to Left</a></li>
                  : <li className="disabled"><a>Move to Left</a></li>
                }
                <li><a className="text-error">Delete</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div className="flex flex-col gap-4">
          {cards.map((card) => (
            <TaskCard key={card.id} id={card.id} body={card.body} links={card.links} columnPosition={position} />
          ))}
        </div>
      </div>
    </div>
  )
}
