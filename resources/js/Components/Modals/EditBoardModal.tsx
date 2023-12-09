import React, { type FormEvent, type ChangeEvent } from 'react'

interface Props {
  id: string
  nameData: string
  onChangeNameHandler: (e: ChangeEvent<HTMLInputElement>) => void
  onSubmitHandler: (e: FormEvent<HTMLFormElement>) => void
  submitDisabler: boolean
}

export default function EditBoardModal ({ id, nameData, onChangeNameHandler, onSubmitHandler, submitDisabler }: Props): JSX.Element {
  return (
    <dialog id={id} className="modal">
      <section className="modal-box">
        <form method="dialog">
          <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Rename Board</h3>
        </header>
        <form className="flex flex-col gap-4 mt-4" onSubmit={onSubmitHandler}>
          <div>
            <input
              name="name"
              type="text"
              placeholder="Type the board name"
              className="input input-sm input-bordered w-full"
              value={nameData}
              onChange={onChangeNameHandler}
              maxLength={32}
              autoComplete="off"
              required
              />
          </div>
          <div className="flex justify-end">
            <button type="submit" className="btn btn-neutral btn-sm" disabled={submitDisabler}>Save</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
