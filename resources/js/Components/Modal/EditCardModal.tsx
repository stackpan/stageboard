import React, { type ChangeEvent, type FormEvent, useEffect, useState, type JSX } from 'react'
import { CardColor } from '@/Enums'
import { convertToBackgroundColor } from '@/Utils/color'
import { router, useForm } from '@inertiajs/react'
import axios from 'axios'
import { type Card } from '@/types'

interface Props {
  active: boolean
  closeHandler: () => void
  cardId: string
}

export default function EditCardModal ({ active, closeHandler, cardId }: Props): JSX.Element {
  const [displayed, setDisplayed] = useState(false)

  // eslint-disable-next-line @typescript-eslint/unbound-method
  const { data, setData, setDefaults, patch, isDirty, processing } = useForm({
    body: '',
    color: CardColor.Blue
  })

  useEffect(() => {
    if (cardId !== '') {
      axios.get<Card>(route('web.cards.show', cardId))
        .then((response) => {
          if (response.status === 200) {
            const card = response.data

            const formData = {
              body: card.body,
              color: card.color
            }

            setDefaults(formData)
            setData(formData)
            setDisplayed(true)
          }
        })
        .catch((e) => {
          console.log(e)
        })
    }
  }, [cardId])

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

    patch(route('web.cards.update', cardId), {
      onSuccess: () => {
        router.reload()
      },
      onError: (e) => {
        console.log(e)
      },
      onFinish: () => {
        handleClose()
      }
    })
  }

  const handleClose = (): void => {
    closeHandler()
    setDisplayed(false)
  }

  const submitDisabler = data.body === '' || !isDirty || processing

  return (
    <dialog className={'modal' + (active ? ' modal-open' : '')}>
      <section className="modal-box">
        <form method="dialog">
          <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onClick={handleClose}>✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Edit Card</h3>
        </header>
        {displayed
          ? (
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
                {Object.values(CardColor).map((color) => (
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
                <button className="btn btn-neutral btn-sm" type="submit" disabled={submitDisabler}>Save</button>
              </div>
            </form>
            )
          : (
            <div className="flex justify-center items-center py-4">
              <span className="loading loading-spinner loading-md"></span>
            </div>
            )}
      </section>
    </dialog>
  )
}
