import React, { type ChangeEvent, type FormEvent } from 'react'
import { router, useForm } from '@inertiajs/react'
import { closeModal } from '@/Utils/dom'

interface Props {
  id: string
  boardId: string
  lastIndex: number
}

interface Form {
  name: string
  order: number
}

export default function CreateColumnModal ({ id, boardId, lastIndex }: Props): JSX.Element {
  const {
    data,
    setData,
    post,
    processing,
    reset
  } = useForm<Form>({
    name: '',
    order: lastIndex
  })

  const handleChangeName = (e: ChangeEvent<HTMLInputElement>): void => {
    setData((previousData: Form) => ({
      ...previousData,
      name: e.target.value
    }))
  }

  const handleSubmit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()

    post(route('web.boards.columns.store', boardId), {
      onFinish: () => {
        reset()
        router.reload({ only: ['columns'] })
        closeModal(id)
      }
    })
  }

  const handleClose = (): void => {
    closeModal(id)
    reset()
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
          <div className="flex justify-end">
            <button className="btn btn-neutral btn-sm" type="submit" disabled={data.name === '' || processing}>Create</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
