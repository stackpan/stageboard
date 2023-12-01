import ColumnCard from '@/Components/ColumnCard'
import MainLayout from '@/Layouts/MainLayout'
import { boardService } from '@/Services/BoardService'
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

  return (
    <MainLayout user={auth.user}>
      <Head title={board?.name ?? name} />

      <section className="p-6">
        <header className="py-2">
          <h1 className="font-bold text-2xl">{name}</h1>
        </header>
        <div className="py-2 flex gap-4 items-start">
          {columns?.map((column) => (
            <ColumnCard key={column.id} id={column.id} name={column.name} cards={column.cards} links={column.links} />
          ))}
        </div>
      </section>
    </MainLayout>
  )
}
