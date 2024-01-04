import {EllipsisVerticalIcon, PlusIcon} from '@heroicons/react/24/outline'
import React, {type PropsWithChildren} from 'react'
import {type Card} from '@/types'
import {type ColumnColor, ColumnPosition, Permission, SwapDirection} from '@/Enums'
import {convertToBackgroundColor} from '@/Utils/color'
import {router} from '@inertiajs/react'
import {getPermissionLevel} from "@/Utils";

interface Props {
  columnId: string
  name: string
  order: number
  position: ColumnPosition
  color: ColumnColor
  cards: Card[]
  permissionLevel: number
  clickEditHandler: (id: string) => void
  clickCreateCardHandler: (columnId: string) => void
}

export default function ColumnCard ({
  columnId,
  name,
  order,
  position,
  color,
  permissionLevel,
  clickEditHandler,
  children,
  clickCreateCardHandler
}: PropsWithChildren<Props>): JSX.Element {
  const isFirstColumn = position === ColumnPosition.First
  const isLastColumn = position === ColumnPosition.Last

  const handleSwap = (direction: SwapDirection): void => {
    const data = {
      order: order + direction
    }

    router.patch(route('web.columns.swap', columnId), data, {
      onFinish: () => {
        router.reload({ only: ['columns'] })
      }
    })
  }

  const handleClickDelete = (): void => {
    router.delete(route('web.columns.destroy', columnId), {
      onFinish: () => {
        router.reload({ only: ['columns'] })
      }
    })
  }

  return (
    <div className="flex-none card card-compact w-80 mb-8 bg-base-100 shadow-md border border-neutral rounded space-y-4">
      <div className="h-2" style={convertToBackgroundColor(color)}></div>
      <div className="card-body !mt-0">
        <div className="flex justify-between items-start">
          <h2 className="card-title">{name}</h2>
          <div className="space-x-2">
            {permissionLevel <= getPermissionLevel(Permission.CardOperator) && (
              <div className="btn btn-ghost btn-square btn-xs" onClick={() => {
                clickCreateCardHandler(columnId)
              }}>
                <PlusIcon className="h-6 w-6"/>
              </div>
            )}
            {permissionLevel <= getPermissionLevel(Permission.LimitedAccess) && (
              <div className="dropdown dropdown-end">
                <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
                  <EllipsisVerticalIcon className="h-6 w-6"/>
                </div>
                <ul tabIndex={0}
                    className="p-0 shadow menu menu-sm dropdown-content z-30 bg-base-100 rounded-box w-36 border">
                  <li>
                    <button onClick={() => {
                      clickEditHandler(columnId)
                    }}>Edit
                    </button>
                  </li>
                  <li className={isLastColumn ? 'disabled' : ''}>
                    <button onClick={() => {
                      handleSwap(SwapDirection.Right)
                    }} disabled={isLastColumn}>Swap to Right
                    </button>
                  </li>
                  <li className={isFirstColumn ? 'disabled' : ''}>
                    <button onClick={() => {
                      handleSwap(SwapDirection.Left)
                    }} disabled={isFirstColumn}>Swap to Left
                    </button>
                  </li>
                  <li>
                    <button className="text-error" onClick={handleClickDelete}>Delete</button>
                  </li>
                </ul>
              </div>
            )}
          </div>
        </div>
        <div className="flex flex-col gap-4">{children}</div>
      </div>
    </div>
  )
}
