import React, { type ChangeEvent, type FormEvent, useState } from 'react'
import { Head, router, useForm } from '@inertiajs/react'
import { type Board, type PageProps } from '@/types'
import MainLayout from '@/Layouts/MainLayout'
import BoardCard from '@/Components/BoardCard'
import BoardTable from '@/Components/BoardTable'
import CreateBoardModal from '@/Components/Modals/CreateBoardModal'
import { closeModal, showModal } from '@/Utils/dom'
import UpdateBoardModal from '@/Components/Modals/UpdateBoardModal'

type Props = PageProps<{
  boards: Board[]
}>

interface UpdateBoardForm {
  name: string
}

export default function Home ({ auth, boards }: Props): JSX.Element {
  const CREATE_BOARD_MODAL_ID = 'createBoardModal'
  const UPDATE_BOARD_MODAL_ID = 'updateBoardModal'

  const [updatingBoard, setUpdatingBoard] = useState<Pick<Board, 'id' | 'name'>>({
    id: '',
    name: ''
  })

  const updateBoardForm = useForm<UpdateBoardForm>({
    name: ''
  })

  const showUpdateBoardModal = (board: Board): void => {
    updateBoardForm.setData((previousData: UpdateBoardForm) => ({
      ...previousData,
      id: board.id,
      name: board.name
    }))

    setUpdatingBoard({
      id: board.id,
      name: board.name
    })

    showModal(UPDATE_BOARD_MODAL_ID)
  }

  const handleUpdateBoardChangeName = (e: ChangeEvent<HTMLInputElement>): void => {
    updateBoardForm.setData((previousData: UpdateBoardForm) => ({
      ...previousData,
      name: e.target.value
    }))
  }

  const handleUpdate = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()

    updateBoardForm.patch(route('web.boards.update', updatingBoard.id))

    updateBoardForm.reset()
    closeModal(UPDATE_BOARD_MODAL_ID)
    router.reload({ only: ['boards'] })
  }

  const handleDelete = (id: string): void => {
    router.delete(route('web.boards.destroy', id))
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
                aliasId={board.aliasId}
                name={board.name}
                owner={board.user.name}
                thumbnailUrl={board.thumbnailUrl}
                openedAt={board.openedAt}
                onClickRenameHandler={(id) => {
                  const board = boards.find((board) => board.id === id)

                  if (board !== undefined) {
                    showUpdateBoardModal(board)
                  }
                }}
                onClickDeleteHandler={handleDelete}
              />
          ))
          }
        </div>
      </section>
      <section className="m-6">
        <header className="py-2 flex justify-between items-center">
          <h2>My Boards</h2>
          <button
            className="btn btn-neutral btn-sm"
            onClick={() => { showModal(CREATE_BOARD_MODAL_ID) }}
          >Create New</button>
        </header>
        <div className="py-2 space-y-4">
          <div className="flex justify-end">
            <CreateBoardModal id={CREATE_BOARD_MODAL_ID} />
          </div>
          <div>
            <BoardTable
              boards={boards}
              onClickRenameHandler={showUpdateBoardModal}
              onClickDeleteHandler={handleDelete}
            />
          </div>
        </div>
      </section>
      <UpdateBoardModal
        id={UPDATE_BOARD_MODAL_ID}
        nameData={updateBoardForm.data.name}
        onChangeNameHandler={handleUpdateBoardChangeName}
        onSubmitHandler={handleUpdate}
        submitDisabler={updateBoardForm.data.name === '' || updateBoardForm.data.name === updatingBoard.name || updateBoardForm.processing}
      />
    </MainLayout>
  )
}
