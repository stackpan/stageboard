import React, { type ChangeEvent } from 'react'
import { type CardColor } from '@/Enums'

interface Props {
  id: string
  bodyData: string
  selectedColorData: CardColor
  onClickCloseHandler: () => void
  onChangeBodyHandler: (e: ChangeEvent<HTMLTextAreaElement>) => void
  onClickColorHandler: (color: CardColor) => void
  onSubmitHandler: (e: ChangeEvent<HTMLFormElement>) => void
  submitDisabler: boolean
}

export default function CreateCardModal ({
  id,
  bodyData,
  onClickCloseHandler,
  onChangeBodyHandler,
  onClickColorHandler,
  onSubmitHandler,
  submitDisabler
}: Props): JSX.Element {
  return (
    <dialog id={id} className="modal">
      <section className="modal-box">
        <form method="dialog">
          <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onClick={onClickCloseHandler}>✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Create New Card</h3>
        </header>
        <form className="flex flex-col gap-4 mt-4" onSubmit={onSubmitHandler}>
          <div>
            <textarea
              name="body"
              className="textarea textarea-bordered w-full"
              placeholder="Type something you want to do ..."
              value={bodyData}
              onChange={onChangeBodyHandler}
              maxLength={255}
              autoComplete="off"
              autoCapitalize="on"
              required
            ></textarea>
          </div>
          <div className="flex justify-end">
            <button className="btn btn-neutral btn-sm" type="submit" disabled={submitDisabler}>Create</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
