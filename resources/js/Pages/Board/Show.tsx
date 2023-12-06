import ColumnCard from '@/Components/ColumnCard'
import CreateColumnModal from '@/Components/CreateColumnModal'
import { ColumnPosition, type SwapDirection } from '@/Enums'
import MainLayout from '@/Layouts/MainLayout'
import { showModal } from '@/Utils/dom'
import { type Column, type PageProps, type Card, type Board } from '@/types'
import { Head, router } from '@inertiajs/react'
import React, { useEffect } from 'react'

type Props = PageProps<{
  board: Board
  columns: Array<Column & { cards: Card[] }>
}>

export default function Show ({ auth, board, columns }: Props): JSX.Element {
  const CREATE_COLUMN_MODAL_ID = 'createColumnModal'

  const getColumnPosition = (order: number): ColumnPosition => {
    if (order === 0) return ColumnPosition.First
    if (order === columns.length - 1) return ColumnPosition.Last
    return ColumnPosition.Middle
  }

  const handleDeleteColumn = (id: string): void => {
    router.delete(route('web.columns.destroy', id), {
      onFinish: () => {
        router.reload({ only: ['columns'] })
      }
    })
  }

  const handleSwapColumn = (id: string, currentOrder: number, direction: SwapDirection): void => {
    const data = {
      order: currentOrder + direction
    }

    router.patch(route('web.columns.swap', id), data, {
      onFinish: () => {
        router.reload({ only: ['columns'] })
      }
    })
  }

  return (
    <MainLayout user={auth.user}>
      <Head title={board.name} />
      <section className="flex-1 flex flex-col">
        <header className="px-6 pt-8 pb-2 flex justify-between">
          <h1 className="font-bold text-2xl">{board.name}</h1>
          <button
            className="btn btn-neutral btn-sm"
            onClick={() => { showModal(CREATE_COLUMN_MODAL_ID) }}
          >Add Column</button>
        </header>
        <div className="p-6 flex gap-4 items-start flex-1 flex-nowrap overflow-auto">
          {columns
            .map((column) => (
              <ColumnCard
                key={column.id}
                id={column.id}
                name={column.name}
                order={column.order}
                position={getColumnPosition(column.order)}
                cards={column.cards}
                color={column.color}
                onClickDeleteHandler={handleDeleteColumn}
                onClickSwapHandler={handleSwapColumn}
              />
            ))}
        </div>
      </section>
      <CreateColumnModal
        id={CREATE_COLUMN_MODAL_ID}
        boardId={board.id}
        lastIndex={columns.length}
      />
    </MainLayout>
  )
}
