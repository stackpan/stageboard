import React, { useState, type JSX } from 'react'
import { Head } from '@inertiajs/react'
import { type Board, type PageProps } from '@/types'
import MainLayout from '@/Layouts/MainLayout'
import BoardCard from '@/Components/BoardCard'
import BoardTable from '@/Components/BoardTable'
import CreateBoardModal from '@/Components/Modal/CreateBoardModal'
import { checkIsLastOfHours, differenceByMillis } from '@/Utils/datetime'
import { QuestionMarkCircleIcon } from '@heroicons/react/24/outline'

export type HomePageProps = PageProps<{
  boards: Board[]
}>

enum ActiveModal {
  None,
  CreateBoard
}

export default function Home ({ auth, boards }: HomePageProps): JSX.Element {
  const [activeModal, setActiveModal] = useState(ActiveModal.None)

  const recentBoards = boards
    .filter(value => checkIsLastOfHours(value.openedAt, 24))
    .toSorted((a, b) => differenceByMillis(a.openedAt, b.openedAt))
    .slice(0, 7)

  return (
    <MainLayout user={auth.user}>
      <Head title="Home"/>
      {recentBoards.length !== 0 && (
        <section className="my-6">
          <header className="px-6 py-2">
            <h2>Recent Boards</h2>
          </header>
          <div className="px-6 pt-4 pb-10 flex flex-row gap-4 overflow-x-auto">
            {recentBoards.map((board) => (
              <BoardCard
                key={board.id}
                id={board.id}
                aliasId={board.aliasId}
                name={board.name}
                owner={board.user.name + (board.user.id === auth.user.id ? ' (You)' : '')}
                thumbnailUrl={board.thumbnailUrl}
                openedAt={board.openedAt}
              />
            ))}
          </div>
        </section>
      )}
      {boards.length !== 0
        ? (
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
                <BoardTable />
              </div>
            </div>
          </section>
          )
        : (
          <section className="flex-1 flex flex-col justify-center items-center gap-4">
            <div className="flex flex-col items-center text-gray-600 text-sm">
              <QuestionMarkCircleIcon className="w-24"/>
              <p>You don&apos;t have a board</p>
            </div>
            <button
              className="btn btn-neutral btn-sm"
              onClick={() => {
                setActiveModal(ActiveModal.CreateBoard)
              }}
            >Create New
            </button>
          </section>
          )
      }
      <CreateBoardModal
        active={activeModal === ActiveModal.CreateBoard}
        closeHandler={() => {
          setActiveModal(ActiveModal.None)
        }}
      />
    </MainLayout>
  )
}
