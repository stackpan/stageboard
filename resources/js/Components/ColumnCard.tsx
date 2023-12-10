import { EllipsisVerticalIcon, PlusIcon } from '@heroicons/react/24/outline'
import React from 'react'
import TaskCard from './TaskCard'
import { type Card } from '@/types'
import { type ColumnColor, ColumnPosition, SwapDirection } from '@/Enums'
import { type SelectingCard, type SelectingColumn } from '@/Pages/Board/Show'
import { convertColumnColor } from '@/Utils/color'

interface Props {
  id: string
  name: string
  order: number
  position: ColumnPosition
  color: ColumnColor
  cards: Card[]
  onClickEditHandler: (column: SelectingColumn) => void
  onClickSwapHandler: (id: string, currentOrder: number, direction: SwapDirection) => void
  onClickDeleteHandler: (id: string) => void
  onClickCreateCardHandler: (column: SelectingColumn) => void
  onClickEditCardHandler: (card: SelectingCard) => void
  onClickMoveCardHandler: (columnId: string, cardId: string, direction: SwapDirection) => void
  onClickDeleteCardHandler: (id: string) => void
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
  onClickEditCardHandler,
  onClickMoveCardHandler,
  onClickDeleteCardHandler
}: Props): JSX.Element {
  const isFirstColumn = position === ColumnPosition.First
  const isLastColumn = position === ColumnPosition.Last

  const handleClickCreateCard = (): void => {
    onClickCreateCardHandler({ id, name, color })
  }
  const handleClickEdit = (): void => {
    onClickEditHandler({ id, name, color })
  }
  const handleClickSwapToRight = (): void => {
    onClickSwapHandler(id, order, SwapDirection.Right)
  }
  const handleClickSwapToLeft = (): void => {
    onClickSwapHandler(id, order, SwapDirection.Left)
  }
  const handleClickDelete = (): void => {
    onClickDeleteHandler(id)
  }

  return (
    <div className="flex-none card card-compact w-72 bg-base-100 shadow-md border border-neutral rounded space-y-4">
      <div className={'h-2 ' + convertColumnColor(color)}></div>
      <div className="card-body !mt-0">
        <div className="flex justify-between items-start">
          <h2 className="card-title">{name}</h2>
          <div className="space-x-2">
            <div className="btn btn-ghost btn-square btn-xs" onClick={handleClickCreateCard}>
              <PlusIcon className="h-6 w-6" />
            </div>
            <div className="dropdown dropdown-end">
              <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
                <EllipsisVerticalIcon className="h-6 w-6" />
              </div>
              <ul tabIndex={0} className="p-0 shadow menu menu-sm dropdown-content z-30 bg-base-100 rounded-box w-36 border">
                <li>
                  <button onClick={handleClickEdit}>Edit</button>
                </li>
                <li className={isLastColumn ? 'disabled' : ''}>
                  <button onClick={handleClickSwapToRight} disabled={isLastColumn}>Swap to Right</button>
                </li>
                <li className={isFirstColumn ? 'disabled' : ''}>
                  <button onClick={handleClickSwapToLeft} disabled={isFirstColumn}>Swap to Left</button>
                </li>
                <li>
                  <button className="text-error" onClick={handleClickDelete}>Delete</button>
                </li>
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
              onCLickEditHandler={onClickEditCardHandler}
              onClickMoveHandler={(cardId, direction) => {
                onClickMoveCardHandler(id, cardId, direction)
              }}
              onClickDeleteHandler={onClickDeleteCardHandler}
            />
          ))}
        </div>
      </div>
    </div>
  )
}
