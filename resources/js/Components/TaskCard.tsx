import { type CardColor, ColumnPosition, SwapDirection } from '@/Enums'
import { ChevronLeftIcon, ChevronRightIcon, EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import React from 'react'
import { type SelectingCard } from '@/Pages/Board/Show'
import { convertCardColor } from '@/Utils/color'

interface Props {
  id: string
  body: string
  color: CardColor
  columnPosition: ColumnPosition
  onCLickEditHandler: (card: SelectingCard) => void
  onClickMoveHandler: (id: string, direction: SwapDirection) => void
  onClickDeleteHandler: (id: string) => void
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
  const atFirstColumn = columnPosition === ColumnPosition.First
  const atLastColumn = columnPosition === ColumnPosition.Last

  const handleClickMoveLeft = (): void => {
    onClickMoveHandler(id, SwapDirection.Left)
  }
  const handleClickMoveRight = (): void => {
    onClickMoveHandler(id, SwapDirection.Right)
  }
  const handleClickEdit = (): void => {
    onCLickEditHandler({ id, body, color })
  }
  const handleClickDelete = (): void => {
    onClickDeleteHandler(id)
  }

  return (
    <div className={'card card-compact shadow-md border border-neutral ' + convertCardColor(color)}>
      <div className="card-body !p-2">
        <header className="flex justify-between">
          <div className="space-x-2">
            <button
              type="button"
              className={'btn btn-ghost btn-square btn-xs ' + (atFirstColumn ? '!bg-transparent' : '')}
              onClick={handleClickMoveLeft}
              disabled={atFirstColumn}
            >
              <ChevronLeftIcon className="h-6 w-6" />
            </button>
            <button
              type="button"
              className={'btn btn-ghost btn-square btn-xs ' + (atLastColumn ? '!bg-transparent' : '')}
              onClick={handleClickMoveRight}
              disabled={atLastColumn}
            >
              <ChevronRightIcon className="h-6 w-6" />
            </button>
          </div>
          <div className="dropdown dropdown-end">
            <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
              <EllipsisVerticalIcon className="h-6 w-6" />
            </div>
            <ul className="p-0 shadow-sm menu menu-sm dropdown-content z-[1] bg-base-100 rounded-box w-36 border">
              <li>
                <button onClick={handleClickEdit}>Edit</button>
              </li>
              <li>
                <button
                  className={atFirstColumn ? 'disabled' : ''}
                  onClick={handleClickMoveRight}
                  disabled={atFirstColumn}
                >Move to Right</button>
              </li>
              <li>
                <button
                  className={atLastColumn ? 'disabled' : ''}
                  onClick={handleClickMoveLeft}
                  disabled={atLastColumn}
                >Move to Left</button>
              </li>
              <li>
                <button className="text-error" onClick={handleClickDelete}>Delete</button>
              </li>
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
