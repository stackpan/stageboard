import React, { type ChangeEvent, type FormEvent } from 'react'
import { CardColor } from '@/Enums'
import { convertToBackgroundColor } from '@/Utils/color'
import { router, useForm } from '@inertiajs/react'

interface Props {
  active: boolean
  closeHandler: () => void
  selectingColumnId: string
}

export default function CreateCardModal ({
  active,
  closeHandler,
  selectingColumnId
}: Props): JSX.Element {
  const { data, setData, post, processing, reset } = useForm({
    body: '',
    color: CardColor.Blue
  })

  const handleChangeBody = (e: ChangeEvent<HTMLTextAreaElement>): void => {
    setData((previousData) => ({
      ...previousData,
      body: e.target.value
    }))
  }

  const selectColor = (color: CardColor): void => {
    setData((previousData) => ({
      ...previousData,
      color
    }))
  }

  const handleSubmit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()

    post(route('web.columns.cards.store', selectingColumnId), {
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

  const submitDisabler = data.body === '' || processing

  return (
    <dialog className={'modal' + (active ? ' modal-open' : '')}>
      <section className="modal-box">
        <form method="dialog">
          <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onClick={closeHandler}>✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Create New Card</h3>
        </header>
        <form className="flex flex-col gap-4 mt-4" onSubmit={handleSubmit}>
          <div>
            <textarea
              name="body"
              className="textarea textarea-bordered w-full"
              style={convertToBackgroundColor(data.color)}
              placeholder="Type something you want to do ..."
              value={data.body}
              onChange={handleChangeBody}
              maxLength={255}
              autoComplete="off"
              autoCapitalize="on"
              required
            ></textarea>
          </div>
          <div className="flex gap-2">
            {Object.values(CardColor).map((color, index) => (
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
            <button className="btn btn-neutral btn-sm" type="submit" disabled={submitDisabler}>Create</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
