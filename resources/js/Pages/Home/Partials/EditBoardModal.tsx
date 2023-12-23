import React, { type FormEvent, type ChangeEvent, useEffect } from 'react'
import { router, useForm, usePage } from '@inertiajs/react'
import { type HomePageProps } from '@/Pages/Home/Index'

interface Props {
  active: boolean
  close: () => void
  updatingBoardId: string
}

interface RequestBody {
  name: string
}

export default function EditBoardModal ({ active, close, updatingBoardId }: Props): JSX.Element {
  // eslint-disable-next-line @typescript-eslint/unbound-method
  const { data, setData, setDefaults, patch, reset, isDirty, processing } = useForm<RequestBody>({
    name: ''
  })

  const boards = usePage<HomePageProps>().props.boards

  useEffect(() => {
    const board = boards.find(value => value.id === updatingBoardId)

    const formData = (board === undefined)
      ? { name: '' }
      : { name: board.name }

    setDefaults(formData)
    setData(formData)
  }, [updatingBoardId])

  const handleChangeName = (e: ChangeEvent<HTMLInputElement>): void => {
    setData((previousData: RequestBody) => ({
      ...previousData,
      name: e.target.value
    }))
  }

  const handleSubmit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()

    patch(route('web.boards.update', updatingBoardId))

    close()
    reset()

    router.reload({ only: ['boards'] })
  }

  return (
    <dialog className={'modal' + (active ? ' modal-open' : '')}>
      <section className="modal-box">
        <form method="dialog">
          <button
            onClick={() => {
              close()
              reset()
            }}
            className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Rename Board</h3>
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
