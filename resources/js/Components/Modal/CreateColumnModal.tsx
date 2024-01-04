import React, { type ChangeEvent, type FormEvent, type JSX } from 'react'
import { ColumnColor } from '@/Enums'
import { convertToBackgroundColor } from '@/Utils/color'
import { router, useForm, usePage } from '@inertiajs/react'
import { type BoardShowProps } from '@/Pages/Board/Show'

interface Props {
  active: boolean
  closeHandler: () => void
}

export default function CreateColumnModal ({
  active,
  closeHandler
}: Props): JSX.Element {
  const { columns, board } = usePage<BoardShowProps>().props

  const { data, setData, post, processing, reset } = useForm({
    name: '',
    order: columns.length,
    color: ColumnColor.Red
  })

  const handleSubmit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()

    post(route('web.boards.columns.store', board.id), {
      onSuccess: () => {
        router.reload()
      },
      onError: (e) => {
        console.log(e)
      },
      onFinish: () => {
        closeHandler()
        reset()
      }
    })
  }

  const handleChangeName = (e: ChangeEvent<HTMLInputElement>): void => {
    setData((previousData) => ({
      ...previousData,
      name: e.target.value
    }))
  }

  const selectColor = (color: ColumnColor): void => {
    setData((previousData) => ({
      ...previousData,
      color
    }))
  }

  const submitDisabler = data.name === '' || processing

  return (
    <dialog className={'modal' + (active ? ' modal-open' : '')}>
      <section className="modal-box">
        <form method="dialog">
          <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onClick={closeHandler}>✕</button>
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
            {Object.values(ColumnColor).map((color) => (
              <button
                key={color}
                className={`w-6 h-6 rounded-full border-4 ${color === data.color ? 'border-gray-600' : ''}`}
                style={convertToBackgroundColor(color)}
                onClick={() => { selectColor(color) }}
                disabled={color === data.color}
              />
            ))}
          </div>
          <div className="flex justify-end">
            <button className="btn btn-neutral btn-sm " type="submit" disabled={submitDisabler}>Create</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
