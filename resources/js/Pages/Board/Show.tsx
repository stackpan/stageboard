import ColumnCard from '@/Components/ColumnCard'
import { CardColor, ColumnColor, ColumnPosition, type SwapDirection } from '@/Enums'
import MainLayout from '@/Layouts/MainLayout'
import { closeModal, showModal } from '@/Utils/dom'
import { type Board, type Card, type Column, type PageProps } from '@/types'
import { Head, router, useForm } from '@inertiajs/react'
import React, { type ChangeEvent, type FormEvent, useState } from 'react'

import CreateColumnModal from '@/Components/Modals/CreateColumnModal'
import EditColumnModal from '@/Components/Modals/EditColumnModal'
import CreateCardModal from '@/Components/Modals/CreateCardModal'
import EditCardModal from '@/Components/Modals/EditCardModal'
import { getRandomColumnColorValue } from '@/Utils/random'

type Props = PageProps<{
  board: Board
  columns: Array<Column & { cards: Card[] }>
}>

interface CreateColumnForm {
  name: string
  order: number
  color: ColumnColor
}
interface UpdateColumnForm {
  name: string
  color: ColumnColor
}
interface UpdateCardForm {
  body: string
  color: CardColor
}

export type SelectingColumn = Pick<Column, 'id' | 'name' | 'color'>
export type SelectingCard = Pick<Card, 'id' | 'body' | 'color'>

const CREATE_COLUMN_MODAL_ID = 'createColumnModal'
const EDIT_COLUMN_MODAL_ID = 'editColumnModal'
const CREATE_CARD_MODAL_ID = 'createCardModal'
const EDIT_CARD_MODAL_ID = 'editCardModal'

const initialSelectingColumnValue = {
  id: '',
  name: '',
  color: ColumnColor.Red
}
const initialSelectingCardValue = {
  id: '',
  body: '',
  color: CardColor.Stone
}

export default function Show ({ auth, board, columns }: Props): JSX.Element {
  const [selectingColumn, setSelectingColumn] = useState<SelectingColumn>(initialSelectingColumnValue)
  const [selectingCard, setSelectingCard] = useState<SelectingCard>(initialSelectingCardValue)

  const createColumnForm = useForm<CreateColumnForm>({
    name: '',
    order: columns.length,
    color: getRandomColumnColorValue()
  })
  const updateColumnForm = useForm<UpdateColumnForm>({
    name: '',
    color: ColumnColor.Red
  })
  const updateCardForm = useForm<UpdateCardForm>({
    body: '',
    color: CardColor.Stone
  })

  const getColumnPosition = (order: number): ColumnPosition => {
    if (order === 0) return ColumnPosition.First
    if (order === columns.length - 1) return ColumnPosition.Last
    return ColumnPosition.Middle
  }

  // Create Column Modal
  const handleShowCreateColumnModal = (): void => {
    createColumnForm.setData((previousData) => ({
      ...previousData,
      color: getRandomColumnColorValue()
    }))

    showModal(CREATE_COLUMN_MODAL_ID)
  }

  const handleCloseCreateColumnModal = (): void => {
    closeModal(CREATE_COLUMN_MODAL_ID)

    updateColumnForm.reset()
  }

  const handleCreateColumnChangeName = (e: ChangeEvent<HTMLInputElement>): void => {
    createColumnForm.setData((previousData) => ({
      ...previousData,
      name: e.target.value
    }))
  }

  const handleCreateColumnClickColor = (color: ColumnColor): void => {
    createColumnForm.setData((previousData) => ({
      ...previousData,
      color
    }))
  }

  // Edit Column Modal
  const handleShowEditColumnModal = (column: SelectingColumn): void => {
    updateColumnForm.setData({
      name: column.name,
      color: column.color
    })
    setSelectingColumn(column)

    showModal(EDIT_COLUMN_MODAL_ID)
  }
  const handleCloseEditColumnModal = (): void => {
    closeModal(EDIT_COLUMN_MODAL_ID)

    updateColumnForm.reset()
    setSelectingColumn(initialSelectingColumnValue)
  }
  const handleEditColumnModalChangeName = (e: ChangeEvent<HTMLInputElement>): void => {
    updateColumnForm.setData((previousData) => ({
      ...previousData,
      name: e.target.value
    }))
  }

  // Column Operations
  const handleCreateColumn = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()

    createColumnForm.post(route('web.boards.columns.store', board.id), {
      onFinish: () => {
        createColumnForm.reset()
        router.reload({ only: ['columns'] })
        handleCloseCreateColumnModal()
      }
    })
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
  const handleUpdateColumn = (): void => {
    updateColumnForm.patch(route('web.columns.update', selectingColumn.id), {
      onFinish: () => {
        router.reload({ only: ['columns'] })
        handleCloseEditColumnModal()
      }
    })
  }

  // Create Card Modal
  const handleShowCreateCardModal = (column: SelectingColumn): void => {
    setSelectingColumn(column)
    showModal(CREATE_CARD_MODAL_ID)
  }
  const handleCloseCreateCardModal = (): void => {
    setSelectingColumn(initialSelectingColumnValue)
    closeModal(CREATE_CARD_MODAL_ID)
  }

  // Edit Card Modal
  const handleShowEditCardModal = (card: SelectingCard): void => {
    updateCardForm.setData({
      body: card.body,
      color: card.color
    })
    setSelectingCard(card)

    showModal(EDIT_CARD_MODAL_ID)
  }
  const handleCloseEditCardModel = (): void => {
    closeModal(EDIT_CARD_MODAL_ID)

    updateCardForm.reset()
    setSelectingCard(initialSelectingCardValue)
  }
  const handleEditCardModalChangeBody = (e: ChangeEvent<HTMLTextAreaElement>): void => {
    updateCardForm.setData((previousData) => ({
      ...previousData,
      body: e.target.value
    }))
  }

  // Card Operations
  const handleUpdateCard = (): void => {
    updateCardForm.patch(route('web.cards.update', selectingCard.id), {
      onSuccess: () => {
        router.reload({ only: ['columns'] })
      },
      onError: (e) => {
        console.log(e)
      },
      onFinish: () => {
        handleCloseEditCardModel()
      }
    })
  }
  const handleMoveCard = (columnId: string, cardId: string, direction: SwapDirection): void => {
    const column = columns.find((column) => column.id === columnId)
    if (column === undefined) return

    const destinationColumn = columns.find((value) => value.order === column.order + direction)
    if (destinationColumn === undefined) return

    const data = {
      columnId: destinationColumn.id
    }

    router.patch(route('web.cards.move', cardId), data, {
      onFinish: () => {
        router.reload({ only: ['columns'] })
      }
    })
  }
  const handleDeleteCard = (id: string): void => {
    router.delete(route('web.cards.destroy', id), {
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
            onClick={handleShowCreateColumnModal}
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
                onClickEditHandler={handleShowEditColumnModal}
                onClickSwapHandler={handleSwapColumn}
                onClickDeleteHandler={handleDeleteColumn}
                onClickCreateCardHandler={handleShowCreateCardModal}
                onClickEditCardHandler={handleShowEditCardModal}
                onClickMoveCardHandler={handleMoveCard}
                onClickDeleteCardHandler={handleDeleteCard}
              />
            ))}
        </div>
      </section>
      <CreateColumnModal
        id={CREATE_COLUMN_MODAL_ID}
        name={createColumnForm.data.name}
        selectedColor={createColumnForm.data.color}
        onChangeNameHandler={handleCreateColumnChangeName}
        onClickColorHandler={handleCreateColumnClickColor}
        onClickCloseHandler={handleCloseCreateColumnModal}
        onSubmitHandler={handleCreateColumn}
        submitDisabler={createColumnForm.data.name === '' || createColumnForm.processing}
      />
      <EditColumnModal
        id={EDIT_COLUMN_MODAL_ID}
        nameData={updateColumnForm.data.name}
        onClickCloseHandler={handleCloseEditColumnModal}
        onChangeNameHandler={handleEditColumnModalChangeName}
        onSubmitHandler={handleUpdateColumn}
        submitDisabler={
          updateColumnForm.data.name === '' ||
          updateColumnForm.data.name === selectingColumn.name ||
          updateColumnForm.processing
        }
      />
      <CreateCardModal
        id={CREATE_CARD_MODAL_ID}
        columnId={selectingColumn.id}
        onClickCloseHandler={handleCloseCreateCardModal}
      />
      <EditCardModal
        id={EDIT_CARD_MODAL_ID}
        bodyData={updateCardForm.data.body}
        onClickCloseHandler={handleCloseEditCardModel}
        onChangeBodyHandler={handleEditCardModalChangeBody}
        onSubmitHandler={handleUpdateCard}
        submitDisabler={
          updateCardForm.data.body === '' ||
          updateCardForm.data.body === selectingCard.body ||
          updateCardForm.processing
        } />
    </MainLayout>
  )
}
