import { type CardColor, ColumnPosition, SwapDirection } from '@/Enums'
import { ChevronLeftIcon, ChevronRightIcon, EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import React from 'react'
import { convertToBackgroundColor } from '@/Utils/color'
import { router, usePage } from '@inertiajs/react'
import { type BoardShowProps } from '@/Pages/BoardPage'

interface Props {
  columnId: string
  taskId: string
  body: string
  color: CardColor
  columnPosition: ColumnPosition
  clickEditHandler: (id: string) => void
}

export default function TaskCard ({
  columnId,
  taskId,
  body,
  color,
  columnPosition,
  clickEditHandler
}: Props): JSX.Element {
  const { columns } = usePage<BoardShowProps>().props

  const atFirstColumn = columnPosition === ColumnPosition.First
  const atLastColumn = columnPosition === ColumnPosition.Last

  const handleMove = (direction: SwapDirection): void => {
    const column = columns.find((value) => value.id === columnId)
    if (column === undefined) return

    const destinationColumn = columns.find((value) => value.order === column.order + direction)
    if (destinationColumn === undefined) return

    const data = {
      columnId: destinationColumn.id
    }

    router.patch(route('web.cards.move', taskId), data, {
      onFinish: () => {
        router.reload({ only: ['columns'] })
      }
    })
  }

  const handleClickDelete = (): void => {
    router.delete(route('web.cards.destroy', taskId), {
      onFinish: () => {
        router.reload({ only: ['columns'] })
      }
    })
  }

  return (
    <div className="card card-compact shadow-md border border-neutral" style={convertToBackgroundColor(color)}>
      <div className="card-body !p-2">
        <header className="flex justify-between">
          <div className="space-x-2">
            <button
              type="button"
              className={'btn btn-ghost btn-square btn-xs ' + (atFirstColumn ? '!bg-transparent' : '')}
              onClick={() => { handleMove(SwapDirection.Left) }}
              disabled={atFirstColumn}
            >
              <ChevronLeftIcon className="h-6 w-6" />
            </button>
            <button
              type="button"
              className={'btn btn-ghost btn-square btn-xs ' + (atLastColumn ? '!bg-transparent' : '')}
              onClick={() => { handleMove(SwapDirection.Right) }}
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
                <button onClick={() => { clickEditHandler(taskId) }}>Edit</button>
              </li>
              <li className={atLastColumn ? 'disabled' : ''}>
                <button
                  onClick={() => { handleMove(SwapDirection.Right) }}
                  disabled={atLastColumn}
                >Move to Right</button>
              </li>
              <li className={atFirstColumn ? 'disabled' : ''}>
                <button
                  onClick={() => { handleMove(SwapDirection.Left) }}
                  disabled={atFirstColumn}
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
