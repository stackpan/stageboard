import React, { type ChangeEvent, type FormEvent } from 'react'
import { router, useForm } from '@inertiajs/react'
import { closeModal } from '@/Utils/dom'
import { ColumnColor } from '@/Enums'
import { getRandomColumnColorValue } from '@/Utils/random'

interface Props {
  id: string
  boardId: string
  lastIndex: number
}

interface Form {
  name: string
  order: number
  color: ColumnColor
}

const colorVariants = {
  '#f87171': 'bg-red-400',
  '#fbbf24': 'bg-amber-400',
  '#a3e635': 'bg-lime-400',
  '#34d399': 'bg-emerald-400',
  '#22d3ee': 'bg-cyan-400',
  '#60a5fa': 'bg-blue-400',
  '#a78bfa': 'bg-violet-400',
  '#e879f9': 'bg-fuchsia-400'
}

export default function CreateColumnModal ({ id, boardId, lastIndex }: Props): JSX.Element {
  const {
    data,
    setData,
    post,
    processing,
    reset,
    // eslint-disable-next-line @typescript-eslint/unbound-method
    setDefaults
  } = useForm<Form>({
    name: '',
    order: lastIndex,
    color: getRandomColumnColorValue()
  })

  const handleChangeName = (e: ChangeEvent<HTMLInputElement>): void => {
    setData((previousData) => ({
      ...previousData,
      name: e.target.value
    }))
  }

  const handleClickColor = (color: ColumnColor): void => {
    setData((previousData) => ({
      ...previousData,
      color
    }))
  }

  const handleSubmit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()

    post(route('web.boards.columns.store', boardId), {
      onFinish: () => {
        reset()
        router.reload({ only: ['columns'] })
        setDefaults('order', lastIndex + 1)
        handleClose()
      }
    })
  }

  const handleClose = (): void => {
    setDefaults('color', getRandomColumnColorValue())
    reset()
    closeModal(id)
  }

  return (
    <dialog id={id} className="modal">
      <section className="modal-box">
        <form method="dialog">
          <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onClick={handleClose}>✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Create New Column</h3>
        </header>
        <form className="flex flex-col gap-4 mt-4" onSubmit={handleSubmit}>
          <div>
            <input
              name="name"
              type="text"
              placeholder="Type the Column name"
              className="input input-sm input-bordered w-full"
              value={data.name}
              maxLength={24}
              onChange={handleChangeName}
              autoComplete="off"
              required
              />
          </div>
          <div className="flex gap-2">
            {Object.values(ColumnColor).map((color, index) => (
              <div key={color}>
                {color === data.color
                  ? <div className={'w-6 h-6 rounded-full border-4 border-gray-600 ' + colorVariants[color]} />
                  : <div className={'w-6 h-6 rounded-full border-4 ' + colorVariants[color]} onClick={() => { handleClickColor(color) }} />
                }
              </div>
            ))}
          </div>
          <div className="flex justify-end">
            <button className="btn btn-neutral btn-sm " type="submit" disabled={data.name === '' || processing}>Create</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
