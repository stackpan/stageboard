import ColumnCard from '@/Components/ColumnCard'
import ColumnModal from '@/Components/ColumnModal'
import { ColumnPosition } from '@/Enums'
import MainLayout from '@/Layouts/MainLayout'
import { boardService } from '@/Services/BoardService'
import { showModal } from '@/Utils/dom'
import { type Links, type Column, type PageProps, type Card } from '@/types'
import { Head } from '@inertiajs/react'
import React, { useEffect, useState } from 'react'

export default function Board ({ auth, id, name }: PageProps<{ id: string, name: string }>): JSX.Element {
  const [board, setBoard] = useState<{ id: string, name: string }>()
  const [columns, setColumns] = useState<Array<Column & Links & { cards: Array<Card & Links> } >>()

  useEffect(() => {
    boardService.get(id)
      .then((board) => {
        setBoard({
          id: board.name,
          name: board.name
        })

        const columns = board.columns
        columns.sort((a, b) => a.order - b.order)

        setColumns(columns)
      })
      .catch((e: Error) => {
        console.log(e)
      })
  }, [])

  const getColumnPosition = (order: number): ColumnPosition => {
    if (order === 0) return ColumnPosition.First
    // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
    if (order === columns!.length - 1) return ColumnPosition.Last
    return ColumnPosition.Middle
  }

  return (
    <MainLayout user={auth.user}>
      <Head title={board?.name ?? name} />

      <section className="p-6">
        <header className="py-2 flex justify-between">
          <h1 className="font-bold text-2xl">{name}</h1>
          <button
              className="btn btn-neutral btn-sm"
              onClick={() => { showModal('createColumnModal') }}
              >Add Column</button>
        </header>
        <div className="py-2 flex gap-4 items-start">
          {columns?.map((column) => (
            <ColumnCard
                key={column.id}
                id={column.id}
                name={column.name}
                position={getColumnPosition(column.order)}
                cards={column.cards}
                links={column.links}
                />
          ))}
        </div>
      </section>

      <ColumnModal id="createColumnModal" onInputNameChange={(e) => { console.log(e.target.value) }} />
    </MainLayout>
  )
}
