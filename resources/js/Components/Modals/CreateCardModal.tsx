import React, { type ChangeEvent, type FormEvent } from 'react'
import { router, useForm } from '@inertiajs/react'
import { closeModal } from '@/Utils/dom'

interface Props {
  id: string
  columnId: string
  onClickCloseHandler: () => void
}

interface Form {
  body: string
}

export default function CreateCardModal ({ id, columnId, onClickCloseHandler }: Props): JSX.Element {
  const {
    data,
    setData,
    post,
    processing,
    reset
  } = useForm<Form>({
    body: ''
  })

  const handleChangeBody = (e: ChangeEvent<HTMLTextAreaElement>): void => {
    setData((previousData) => ({
      ...previousData,
      body: e.target.value
    }))
  }

  const handleSubmit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()

    post(route('web.columns.cards.store', columnId), {
      onFinish: () => {
        reset()
        router.reload({ only: ['columns'] })
        closeModal(id)
      }
    })
  }

  const handleClose = (): void => {
    reset()
    onClickCloseHandler()
  }

  return (
    <dialog id={id} className="modal">
      <section className="modal-box">
        <form method="dialog">
          <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onClick={handleClose}>✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Create New Card</h3>
        </header>
        <form className="flex flex-col gap-4 mt-4" onSubmit={handleSubmit}>
          <div>
            <textarea
              className="textarea textarea-bordered w-full"
              placeholder="Type something you want to do ..."
              value={data.body}
              onChange={handleChangeBody}
              maxLength={255}
              required
            ></textarea>
          </div>
          <div className="flex justify-end">
            <button className="btn btn-neutral btn-sm" type="submit" disabled={data.body === '' || processing}>Create</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
