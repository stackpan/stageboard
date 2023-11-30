import React, { useEffect, useState } from 'react'
import { Head } from '@inertiajs/react'
import type Board from '@/types'
import { type PageProps } from '@/types'
import MainLayout from '@/Layouts/MainLayout'
import BoardCard from '@/Components/BoardCard'
import { getBoards } from '@/Services/BoardService'
import BoardTable from '@/Components/BoardTable'

export default function Home ({ auth }: PageProps): JSX.Element {
  const [boards, setBoards] = useState<Board[]>([])

  useEffect(() => {
    getBoards()
      .then((boards: Board[]) => {
        setBoards(boards)
      })
      .catch((e: Error) => {
        console.log(e)
      })
  }, [])

  return (
    <MainLayout user={auth.user}>
      <Head title="Dashboard" />

      <section className="m-6">
        <header className="py-2">
          <h2>Recent Boards</h2>
        </header>
        <div className="py-2 flex flex-row gap-4">
          {boards.map((board) => (
            <BoardCard key={board.id}
              id={board.id}
              name={board.name}
              owner={board.owner}
              thumbnailUrl={board.thumbnailUrl}
              openedAt={board.openedAt}
              links={board.links}
            />
          ))}
        </div>
      </section>
      <section className="m-6">
        <header className="py-2">
          <h2>My Boards</h2>
        </header>
        <div className="py-2">
          <BoardTable boards={boards} />
        </div>
      </section>
    </MainLayout>
  )
}
