import { PlusIcon, EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import React from 'react'
import TaskCard from './TaskCard'
import { type Card, type Links } from '@/types'

interface Props {
  id: string
  name: string
  cards: Array<Card & Links>
}

export default function ColumnCard ({ id, name, cards, links }: Props & Links): JSX.Element {
  return (
    <section className="w-72 p-4 border-2 border-neutral rounded drop-shadow-md space-y-4">
      <header className="flex justify-between items-start">
        <h2 className="font-bold">{name}</h2>
        <div className="space-x-2">
          <div role="button" className="btn btn-ghost btn-square btn-xs">
            <PlusIcon className="h-6 w-6" />
          </div>
          <div className="dropdown">
            <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
              <EllipsisVerticalIcon className="h-6 w-6" />
            </div>
            <ul className="p-0 shadow menu menu-sm dropdown-content z-[1] bg-base-100 rounded-box w-36">
              <li><a>Rename</a></li>
              <li><a>Move to Right</a></li>
              <li><a>Move to Left</a></li>
              <li><a className="text-error">Delete</a></li>
            </ul>
          </div>
        </div>
      </header>
      <div className="flex flex-col gap-4">
        {cards.map((card) => (
          <TaskCard key={card.id} id={card.id} body={card.body} links={card.links} />
        ))}
      </div>
    </section>
  )
}
