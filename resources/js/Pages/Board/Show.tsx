import ColumnCard from '@/Components/ColumnCard'
import CreateColumnModal from '@/Components/Modals/CreateColumnModal'
import { Color, ColumnPosition, type SwapDirection } from '@/Enums'
import MainLayout from '@/Layouts/MainLayout'
import { closeModal, showModal } from '@/Utils/dom'
import { type Board, type Card, type Column, type PageProps } from '@/types'
import { Head, router, useForm } from '@inertiajs/react'
import React, { type ChangeEvent, useState } from 'react'

import UpdateColumnModal from '@/Components/Modals/UpdateColumnModal'
import CreateCardModal from '@/Components/Modals/CreateCardModal'

type Props = PageProps<{
  board: Board
  columns: Array<Column & { cards: Card[] }>
}>

interface UpdateColumnForm {
  name: string
  color: Color
}

export type SelectingColumn = Pick<Column, 'id' | 'name' | 'color'>

const CREATE_COLUMN_MODAL_ID = 'createColumnModal'
const UPDATE_COLUMN_MODAL_ID = 'updateColumnModal'
const CREATE_CARD_MODAL_ID = 'createCardModal'

export default function Show ({ auth, board, columns }: Props): JSX.Element {
  const initialSelectingColumnValue = {
    id: '',
    name: '',
    color: Color.Stone
  }
  const [selectingColumn, setSelectingColumn] = useState<SelectingColumn>(initialSelectingColumnValue)

  const updateColumnForm = useForm<UpdateColumnForm>({
    name: '',
    color: Color.Stone
  })

  const getColumnPosition = (order: number): ColumnPosition => {
    if (order === 0) return ColumnPosition.First
    if (order === columns.length - 1) return ColumnPosition.Last
    return ColumnPosition.Middle
  }

  const handleEditColumn = (column: SelectingColumn): void => {
    updateColumnForm.setData({
      name: column.name,
      color: column.color
    })
    setSelectingColumn(column)

    showModal(UPDATE_COLUMN_MODAL_ID)
  }

  const handleUpdateColumnChangeName = (e: ChangeEvent<HTMLInputElement>): void => {
    updateColumnForm.setData((previousData) => ({
      ...previousData,
      name: e.target.value
    }))
  }

  const handleUpdateColumn = (): void => {
    updateColumnForm.patch(route('web.columns.update', selectingColumn.id), {
      onFinish: () => {
        router.reload({ only: ['columns'] })

        closeModal(UPDATE_COLUMN_MODAL_ID)
        updateColumnForm.reset()
        setSelectingColumn(initialSelectingColumnValue)
      }
    })
  }

  const closeUpdateColumnModal = (): void => {
    closeModal(UPDATE_COLUMN_MODAL_ID)
    updateColumnForm.reset()
    setSelectingColumn(initialSelectingColumnValue)
  }

  const handleDeleteColumn = (id: string): void => {
    router.delete(route('web.columns.destroy', id), {
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

  const handleOpenCreateCardModal = (column: SelectingColumn): void => {
    setSelectingColumn(column)
    showModal(CREATE_CARD_MODAL_ID)
  }

  const handleCloseCreateCardModal = (): void => {
    setSelectingColumn(initialSelectingColumnValue)
    closeModal(CREATE_CARD_MODAL_ID)
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
                onClickEditHandler={handleEditColumn}
                onClickSwapHandler={handleSwapColumn}
                onClickDeleteHandler={handleDeleteColumn}
                onClickCreateCardHandler={handleOpenCreateCardModal}
                onClickMoveCardHandler={handleMoveCard}
                onClickDeleteCardHandler={handleDeleteCard}
              />
            ))}
        </div>
      </section>
      <CreateColumnModal
        id={CREATE_COLUMN_MODAL_ID}
        boardId={board.id}
        lastIndex={columns.length}
      />
      <UpdateColumnModal
        id={UPDATE_COLUMN_MODAL_ID}
        nameData={updateColumnForm.data.name}
        onClickCloseHandler={closeUpdateColumnModal}
        onChangeNameHandler={handleUpdateColumnChangeName}
        onSubmitHandler={handleUpdateColumn}
        submitDisabler={updateColumnForm.data.name === '' || updateColumnForm.data.name === selectingColumn.name || updateColumnForm.processing}
      />
      <CreateCardModal
        id={CREATE_CARD_MODAL_ID}
        columnId={selectingColumn.id}
        onClickCloseHandler={handleCloseCreateCardModal}
      />
    </MainLayout>
  )
}
