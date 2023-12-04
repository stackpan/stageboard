import ColumnCard from '@/Components/ColumnCard'
import ColumnModal from '@/Components/ColumnModal'
import { type Color, ColumnPosition } from '@/Enums'
import MainLayout from '@/Layouts/MainLayout'
import { boardService } from '@/Services/BoardService'
import { columnService } from '@/Services/ColumnService'
import { closeModal, showModal } from '@/Utils/dom'
import { getRandomColorValue } from '@/Utils/random'
import { type Links, type Column, type PageProps, type Card } from '@/types'
import { Head, useForm } from '@inertiajs/react'
import React, { type FormEvent, useEffect, useState, type ChangeEvent } from 'react'

interface CreateColumn {
  name: string
  order: number | undefined
  color: Color | undefined
}

export default function Board ({ auth, id, name }: PageProps<{ id: string, name: string }>): JSX.Element {
  const CREATE_COLUMN_MODAL_ID = 'createColumnModal'

  const [board, setBoard] = useState<{ id: string, name: string }>()
  const [columns, setColumns] = useState<Array<Column & Links & { cards: Array<Card & Links> }>>()
  const [isLoading, setIsLoading] = useState(true)

  const {
    data: createColumnData,
    setData: setCreateColumnData,
    post: postCreateColumnData,
    reset: resetCreateColumnData,
    transform: transformCreateColumnData,
  } = useForm<CreateColumn>({
    name: '',
    order: undefined,
    color: undefined
  })

  useEffect(() => {
    boardService.get(id)
      .then((board) => {
        setBoard({
          id: board.id,
          name: board.name
        })

        const columns = board.columns
        columns.sort((a, b) => a.order - b.order)

        setColumns(columns)
        setIsLoading(false)
      })
      .catch((e: Error) => {
        console.log(e)
      })
  }, [])

  const fetchColumns = (): void => {
    // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
    columnService.getAll(board!.id)
      .then((columns) => {
        setColumns(columns)
      })
      .catch((e: Error) => {
        console.log(e)
      })
  }

  const getColumnPosition = (order: number): ColumnPosition => {
    if (order === 0) return ColumnPosition.First
    // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
    if (order === columns!.length - 1) return ColumnPosition.Last
    return ColumnPosition.Middle
  }

  const handleCreateColumn = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()

    closeModal(CREATE_COLUMN_MODAL_ID)

    transformCreateColumnData((data) => ({
      ...data,
      order: data.order ?? columns?.length,
      color: data.color ?? getRandomColorValue()
    }))

    postCreateColumnData(`/api/boards/${board?.id}/columns`, {
      onSuccess: () => {
        fetchColumns()
        resetCreateColumnData()
      }
    })
  }

  const handleCreateColumnChangeName = (e: ChangeEvent<HTMLInputElement>): void => {
    setCreateColumnData((previousData) => ({
      ...previousData,
      name: e.target.value
    }))
  }

  return (
    <MainLayout user={auth.user}>
      <Head title={board?.name ?? name} />
      <section className="flex-1 flex flex-col">
        <header className="px-6 pt-8 pb-2 flex justify-between">
          {isLoading
            ? <div className="skeleton h-6 w-28"></div>
            : <h1 className="font-bold text-2xl">{name}</h1>
          }
          <button
            className="btn btn-neutral btn-sm"
            onClick={() => { showModal(CREATE_COLUMN_MODAL_ID) }}
            disabled={isLoading}
          >Add Column</button>
        </header>
        <div className="p-6 flex gap-4 items-start flex-1 flex-nowrap overflow-auto">
          {isLoading
            ? (
              <div className="w-full flex justify-center self-stretch">
                <span className="loading loading-dots loading-lg"></span>
              </div>
              )
            : columns?.map((column) => (
              <ColumnCard
                key={column.id}
                id={column.id}
                name={column.name}
                position={getColumnPosition(column.order)}
                cards={column.cards}
                links={column.links}
                color={column.color}
              />
            ))
          }
        </div>
      </section>
      <ColumnModal
        id={CREATE_COLUMN_MODAL_ID}
        inputNameValue={createColumnData.name}
        onChangeNameHandler={handleCreateColumnChangeName}
        onSubmitHandler={handleCreateColumn}
      />
    </MainLayout>
  )
}
