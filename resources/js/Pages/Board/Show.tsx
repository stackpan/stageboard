import ColumnCard from '@/Components/ColumnCard'
import { ColumnPosition, Permission } from '@/Enums'
import MainLayout from '@/Layouts/MainLayout'
import { type Board, type Card, type Column, type PageProps } from '@/types'
import { Head, Link, router } from '@inertiajs/react'
import React, { type JSX, useState } from 'react'

import CreateColumnModal from '@/Components/Modal/CreateColumnModal'
import EditColumnModal from '@/Components/Modal/EditColumnModal'
import CreateCardModal from '@/Components/Modal/CreateCardModal'
import EditCardModal from '@/Components/Modal/EditCardModal'
import TaskCard from '@/Components/TaskCard'
import EditBoardModal from '@/Components/Modal/EditBoardModal'
import { getPermissionLevel } from '@/Utils'

export type BoardShowProps = PageProps<{
  board: Board
  columns: ColumnWithCards[]
  permission: Permission
}>

type ColumnWithCards = Column & { cards: Card[] }

enum ActiveModal {
  None,
  EditBoard,
  CreateColumn,
  EditColumn,
  CreateCard,
  EditCard,
}

export default function Show ({ auth, board, columns, permission }: BoardShowProps): JSX.Element {
  const [activeModal, setActiveModal] = useState(ActiveModal.None)
  const [editingColumn, setEditingColumn] = useState('')
  const [editingCard, setEditingCard] = useState('')

  const sortedColumns = columns.toSorted((a, b) => a.order - b.order)

  const permissionLevel = getPermissionLevel(permission)

  const getColumnPosition = (order: number): ColumnPosition => {
    if (order === 0) return ColumnPosition.First
    if (order === columns.length - 1) return ColumnPosition.Last
    return ColumnPosition.Middle
  }

  return (
    <MainLayout user={auth.user}>
      <Head title={board.name}/>
      <section className="flex-1 flex flex-col">
        <header className="px-6 pt-8 pb-2 flex justify-between">
          <h1 className="font-bold text-2xl">{board.name}</h1>
          <div className="space-x-2">
            {permissionLevel <= getPermissionLevel(Permission.FullAccess) && (
              <Link
                href={route('web.page.board.edit', board.aliasId)}
                className="btn btn-outline btn-sm"
                as="button"
              >Settings</Link>
            )}
            {permissionLevel <= getPermissionLevel(Permission.LimitedAccess) && (
              <button
                className="btn btn-neutral btn-sm"
                onClick={() => {
                  setActiveModal(ActiveModal.CreateColumn)
                }}
              >Add Column
              </button>
            )}
          </div>
        </header>
        <div className="p-6 flex gap-4 items-start flex-1 flex-nowrap overflow-x-auto overflow-y-hidden">
          {columns.length === 0
            ? (
              <div className="flex-1 self-stretch flex flex-col gap-4 justify-center items-center">
                <p className="text-sm text-gray-600">Your board is empty</p>
                <button
                  className="btn btn-neutral btn-outline btn-sm"
                  onClick={(e) => {
                    e.preventDefault()

                    router.post(route('web.boards.columns.generate', board.id))
                  }}
                >Generate Starter
                </button>
              </div>
              )
            : sortedColumns.map((column) => (
              <ColumnCard
                key={column.id}
                columnId={column.id}
                name={column.name}
                order={column.order}
                position={getColumnPosition(column.order)}
                cards={column.cards}
                color={column.color}
                permissionLevel={permissionLevel}
                clickEditHandler={(id) => {
                  setEditingColumn(id)
                  setActiveModal(ActiveModal.EditColumn)
                }}
                clickCreateCardHandler={(columnId) => {
                  setEditingColumn(columnId)
                  setActiveModal(ActiveModal.CreateCard)
                }}
              >{column.cards.map((card) => (
                <TaskCard
                  key={card.id}
                  taskId={card.id}
                  body={card.body}
                  color={card.color}
                  columnId={column.id}
                  columnPosition={getColumnPosition(column.order)}
                  permissionLevel={permissionLevel}
                  clickEditHandler={(id) => {
                    setEditingCard(id)
                    setActiveModal(ActiveModal.EditCard)
                  }}
                />
              ))}
              </ColumnCard>
            ))
          }
        </div>
        <EditBoardModal
          active={activeModal === ActiveModal.EditBoard}
          closeHandler={() => {
            setActiveModal(ActiveModal.None)
          }}
          boardId={board.id}
        />
        <CreateColumnModal
          active={activeModal === ActiveModal.CreateColumn}
          closeHandler={() => {
            setActiveModal(ActiveModal.None)
          }}
        />
        <EditColumnModal
          active={activeModal === ActiveModal.EditColumn}
          closeHandler={() => {
            setActiveModal(ActiveModal.None)
            setEditingColumn('')
          }}
          columnId={editingColumn}
        />
        <CreateCardModal
          active={activeModal === ActiveModal.CreateCard}
          closeHandler={() => {
            setActiveModal(ActiveModal.None)
          }}
          selectingColumnId={editingColumn}
        />
        <EditCardModal
          active={activeModal === ActiveModal.EditCard}
          closeHandler={() => {
            setActiveModal(ActiveModal.None)
            setEditingCard('')
          }}
          cardId={editingCard}
        />
      </section>
    </MainLayout>
  )
}
