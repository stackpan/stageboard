import React, { type FormEvent, type ChangeEvent, useEffect } from 'react'
import { router, useForm } from '@inertiajs/react'
import axios from 'axios'
import { type Board } from '@/types'

interface Props {
  active: boolean
  closeHandler: () => void
  boardId: string
}

interface RequestBody {
  name: string
}

export default function EditBoardModal ({ active, closeHandler, boardId }: Props): JSX.Element {
  // eslint-disable-next-line @typescript-eslint/unbound-method
  const { data, setData, setDefaults, patch, reset, isDirty, processing } = useForm<RequestBody>({
    name: ''
  })

  useEffect(() => {
    if (active) {
      axios.get<Board>(route('web.boards.show', boardId))
        .then((response) => {
          if (response.status === 200) {
            const board = response.data
            const formData = {
              name: board.name
            }

            setDefaults(formData)
            setData(formData)
          }
        })
        .catch((e) => {
          console.log(e)
        })
    }
  }, [active])

  const handleChangeName = (e: ChangeEvent<HTMLInputElement>): void => {
    setData((previousData: RequestBody) => ({
      ...previousData,
      name: e.target.value
    }))
  }

  const handleSubmit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()

    patch(route('web.boards.update', boardId), {
      onSuccess: () => {
        router.reload()
      },
      onError: (e) => {
        console.log(e)
      },
      onFinish: () => {
        closeHandler()
      }
    })
  }

  return (
    <dialog className={'modal' + (active ? ' modal-open' : '')}>
      <section className="modal-box">
        <form method="dialog">
          <button
            onClick={() => {
              closeHandler()
              reset()
            }}
            className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Edit Board</h3>
        </header>
        <form className="flex flex-col gap-4 mt-4" onSubmit={handleSubmit}>
          <div>
            <input
              name="name"
              type="text"
              placeholder="Type the board name"
              className="input input-sm input-bordered w-full"
              value={data.name}
              onChange={handleChangeName}
              maxLength={32}
              autoComplete="off"
              required
              />
          </div>
          <div className="flex justify-end">
            <button type="submit" className="btn btn-neutral btn-sm" disabled={data.name === '' || !isDirty || processing}>Save</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
