import React, { type ChangeEvent, useEffect, useState } from 'react'
import { Head } from '@inertiajs/react'
import { type PageProps } from '@/types'
import MainLayout from '@/Layouts/MainLayout'
import BoardCard from '@/Components/BoardCard'
import BoardTable from '@/Components/BoardTable'
import { type Boards, boardService } from '@/Services/BoardService'
import CreateBoardModal from '@/Components/CreateBoardModal'

export default function Home ({ auth }: PageProps): JSX.Element {
  const [boards, setBoards] = useState<Boards>([])

  useEffect(() => {
    boardService.getAll()
      .then((boards) => {
        setBoards(boards)
      })
      .catch((e: Error) => {
        console.log(e)
      })
  }, [])

  const showCreateBoardModal = (id: string): void => {
    const modal: any = document.getElementById(id)
    modal.showModal()
  }

  const onInputNameChange = (e: ChangeEvent<HTMLInputElement>): void => {
    console.log(e.target.value)
  }

  return (
    <MainLayout user={auth.user}>
      <Head title="Home" />
      <section className="m-6">
        <header className="py-2">
          <h2>Recent Boards</h2>
        </header>
        <div className="py-2 flex flex-row gap-4">
          {boards.map((board) => (
            <BoardCard key={board.id}
              id={board.id}
              name={board.name}
              owner={board.user.name}
              thumbnailUrl={board.thumbnailUrl}
              openedAt={board.openedAt}
              links={board.links}
            />
          ))}
        </div>
      </section>
      <section className="m-6">
        <header className="py-2 flex justify-between items-center">
          <h2>My Boards</h2>
          <button
              className="btn btn-neutral btn-sm"
              onClick={() => { showCreateBoardModal('createBoardModal') }}
              >Create New</button>
        </header>
        <div className="py-2 space-y-4">
          <div className="flex justify-end">
            <CreateBoardModal
              id="createBoardModal"
              onInputNameChange={onInputNameChange}
            />
          </div>
          <div>
            <BoardTable boards={boards} />
          </div>
        </div>
      </section>
    </MainLayout>
  )
}
