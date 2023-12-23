import React, { useState } from 'react'
import { Head, router } from '@inertiajs/react'
import { type Board, type PageProps } from '@/types'
import MainLayout from '@/Layouts/MainLayout'
import BoardCard from '@/Components/BoardCard'
import BoardTable from '@/Components/BoardTable'
import CreateBoardModal from '@/Pages/Home/Partials/CreateBoardModal'
import EditBoardModal from '@/Pages/Home/Partials/EditBoardModal'
import { checkIsLastOfHours, differenceByMillis } from '@/Utils/datetime'

export type HomePageProps = PageProps<{
  boards: Board[]
}>

enum ActiveModal {
  None,
  CreateBoard,
  EditBoard
}

export default function Index ({ auth, boards }: HomePageProps): JSX.Element {
  const [activeModal, setActiveModal] = useState(ActiveModal.None)
  const [updatingBoard, setUpdatingBoard] = useState('')

  const recentBoards = boards
    .filter(value => checkIsLastOfHours(value.openedAt, 24))
    .toSorted((a, b) => differenceByMillis(a.openedAt, b.openedAt))
    .slice(0, 7)

  const handleDelete = (id: string): void => {
    router.delete(route('web.boards.destroy', id))
  }

  const editBoardModal = (id: string): void => {
    setActiveModal(ActiveModal.EditBoard)
    setUpdatingBoard(id)
  }

  return (
    <MainLayout user={auth.user}>
      <Head title="Index"/>
      {recentBoards.length !== 0 && (
        <section className="my-6">
          <header className="px-6 py-2">
            <h2>Recent Boards</h2>
          </header>
          <div className="px-6 py-9 flex flex-row gap-4 overflow-x-auto">
            {recentBoards.map((board) => (
              <BoardCard
                key={board.id}
                id={board.id}
                aliasId={board.aliasId}
                name={board.name}
                owner={board.user.name}
                thumbnailUrl={board.thumbnailUrl}
                openedAt={board.openedAt}
                onClickEditHandler={editBoardModal}
                onClickDeleteHandler={handleDelete}
              />
            ))}
          </div>
        </section>
      )}
      <section className="my-6 px-6">
        <header className="py-2 flex justify-between items-center">
          <h2>My Boards</h2>
          <button
            className="btn btn-neutral btn-sm"
            onClick={() => {
              setActiveModal(ActiveModal.CreateBoard)
            }}
          >Create New
          </button>
        </header>
        <div className="py-2 space-y-4">
          <div>
            <BoardTable
              boards={boards}
              onClickEditHandler={editBoardModal}
              onClickDeleteHandler={handleDelete}
            />
          </div>
        </div>
      </section>
      <CreateBoardModal
        active={activeModal === ActiveModal.CreateBoard}
        onClickCloseHandler={() => {
          setActiveModal(ActiveModal.None)
        }}
      />
      <EditBoardModal
        active={activeModal === ActiveModal.EditBoard}
        close={() => {
          setActiveModal(ActiveModal.None)
          setUpdatingBoard('')
        }}
        updatingBoardId={updatingBoard}
      />
    </MainLayout>
  )
}
