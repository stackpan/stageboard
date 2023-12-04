import React, { type ChangeEvent, useEffect, useState, type FormEvent } from 'react'
import { Head, router } from '@inertiajs/react'
import { type PageProps } from '@/types'
import MainLayout from '@/Layouts/MainLayout'
import BoardCard from '@/Components/BoardCard'
import BoardTable from '@/Components/BoardTable'
import { type Boards, boardService } from '@/Services/BoardService'
import CreateBoardModal from '@/Components/CreateBoardModal'
import BoardCardSkeleton from '@/Components/BoardCardSkeleton'
import { closeModal, showModal } from '@/Utils/dom'
import UpdateBoardModal from '@/Components/UpdateBoardModal'

interface CreateBoardForm {
  name: string
}

interface UpdateBoardValue extends CreateBoardForm {
  id: string
  original: {
    name: string
  }
}

export default function Home ({ auth }: PageProps): JSX.Element {
  const CREATE_BOARD_MODAL_ID = 'createBoardModal'
  const UPDATE_BOARD_MODAL_ID = 'updateBoardModal'

  const [boards, setBoards] = useState<Boards>([])
  const [isLoading, setIsLoading] = useState<boolean>(true)

  const initialCreateBoardForm = {
    name: ''
  }

  const initialUpdateBoardValue = {
    ...initialCreateBoardForm,
    id: '',
    original: {
      name: ''
    }
  }

  const [createBoardForm, setCreateBoardForm] = useState<CreateBoardForm>(initialCreateBoardForm)
  const [updateBoardValue, setUpdateBoardValue] = useState<UpdateBoardValue>(initialUpdateBoardValue)

  const resetCreateBoardForm = (): void => {
    setCreateBoardForm(initialCreateBoardForm)
  }

  const resetUpdateBoardValue = (): void => {
    setUpdateBoardValue(initialUpdateBoardValue)
  }

  const fetchBoards = (callback?: () => void): void => {
    boardService.getAll()
      .then((boards) => {
        setBoards(boards)
        if (callback !== null) {
          callback?.()
        }
      })
      .catch((e: Error) => {
        console.log(e)
      })
  }

  useEffect(() => {
    fetchBoards(() => {
      setIsLoading(false)
    })
  }, [])

  const handleCreateBoardInputNameChange = (e: ChangeEvent<HTMLInputElement>): void => {
    setCreateBoardForm((value) => ({
      ...value,
      name: e.target.value
    }))
  }

  const handleUpdateBoardInputNameChange = (e: ChangeEvent<HTMLInputElement>): void => {
    setUpdateBoardValue((value) => ({
      ...value,
      name: e.target.value
    }))
  }

  const handleCreate = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()
    closeModal(CREATE_BOARD_MODAL_ID)

    boardService.create(createBoardForm)
      .then((response) => {
        const id = response.data.board.id
        resetCreateBoardForm()

        router.get(`/board/${id}`)
      })
      .catch((e: Error) => {
        console.log(e)
      })
  }

  const handleDelete = (id: string): void => {
    boardService.delete(id)
      .then(() => {
        fetchBoards()
      })
      .catch((e: Error) => {
        console.log(e)
      })
  }

  const handleUpdate = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()
    closeModal(UPDATE_BOARD_MODAL_ID)

    boardService.edit(updateBoardValue.id, { name: updateBoardValue.name })
      .then((response) => {
        resetUpdateBoardValue()

        fetchBoards()
      })
      .catch((e: Error) => {
        console.log(e)
      })
  }

  const showUpdateModal = (id: string, name: string): void => {
    setUpdateBoardValue({
      id,
      name,
      original: {
        name
      }
    })

    showModal(UPDATE_BOARD_MODAL_ID)
  }

  const handleCreateBoardModalClickClose = (): void => {
    resetCreateBoardForm()
  }

  return (
    <MainLayout user={auth.user}>
      <Head title="Home" />
      <section className="m-6">
        <header className="py-2">
          <h2>Recent Boards</h2>
        </header>
        <div className="py-2 flex flex-row gap-4">
          {isLoading
            ? (
              <>
                <BoardCardSkeleton />
                <BoardCardSkeleton />
                <BoardCardSkeleton />
              </>
              )
            : boards.map((board) => (
              <BoardCard key={board.id}
                id={board.id}
                aliasId={board.aliasId}
                name={board.name}
                owner={board.user.name}
                thumbnailUrl={board.thumbnailUrl}
                openedAt={board.openedAt}
                links={board.links}
                onClickRenameHandler={showUpdateModal}
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
            disabled={isLoading}
          >Create New</button>
        </header>
        <div className="py-2 space-y-4">
          <div className="flex justify-end">
            <CreateBoardModal
              id={CREATE_BOARD_MODAL_ID}
              inputName={createBoardForm.name}
              onClickCloseHandler={handleCreateBoardModalClickClose}
              onChangeNameHandler={handleCreateBoardInputNameChange}
              onSubmitHandler={handleCreate}
            />
          </div>
          <div>
            <BoardTable
              boards={boards}
              isLoading={isLoading}
              onClickRenameHandler={showUpdateModal}
              onClickDeleteHandler={handleDelete}
            />
          </div>
        </div>
      </section>
      <UpdateBoardModal
        id={UPDATE_BOARD_MODAL_ID}
        original={updateBoardValue.original}
        inputName={updateBoardValue.name}
        onChangeNameHandler={handleUpdateBoardInputNameChange}
        onSubmitHandler={handleUpdate}
      />
    </MainLayout>
  )
}
