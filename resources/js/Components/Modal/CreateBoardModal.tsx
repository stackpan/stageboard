import { router, useForm } from '@inertiajs/react'
import React, { type FormEvent, type ChangeEvent } from 'react'

interface Props {
  active: boolean
  closeHandler: () => void
}

interface Form {
  name: string
}

export default function CreateBoardModal ({ active, closeHandler }: Props): JSX.Element {
  const { data, setData, post, processing, reset } = useForm<Form>({
    username: ''
  })

  const handleChangeName = (e: ChangeEvent<HTMLInputElement>): void => {
    setData((previousData: Form) => ({
      ...previousData,
      name: e.target.value
    }))
  }

  const handleSubmit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()

    post(route('web.boards.store'), {
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

  return (
    <dialog className={'modal' + (active ? ' modal-open' : '')}>
      <section className="modal-box">
        <form method="dialog">
          <button onClick={closeHandler} className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Create New Board</h3>
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
            <button type="submit" className="btn btn-neutral btn-sm" disabled={data.name === '' || processing}>Create</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
