import React, { type ChangeEvent, type FormEvent } from 'react'

interface Props {
  id: string
  nameData: string
  onClickCloseHandler: () => void
  onChangeNameHandler: (e: ChangeEvent<HTMLInputElement>) => void
  onSubmitHandler: (e: FormEvent<HTMLFormElement>) => void
  submitDisabler: boolean
}

export default function EditColumnModal ({
  id,
  nameData,
  onClickCloseHandler,
  onChangeNameHandler,
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
          <h3 className="font-bold text-lg">Edit Column</h3>
        </header>
        <form className="flex flex-col gap-4 mt-4" onSubmit={onSubmitHandler}>
          <div>
            <input
              name="name"
              type="text"
              placeholder="Type the Column name"
              className="input input-sm input-bordered w-full"
              value={nameData}
              onChange={onChangeNameHandler}
              maxLength={24}
              autoComplete="off"
              required
              />
          </div>
          <div className="flex justify-end">
            <button className="btn btn-neutral btn-sm" type="submit" disabled={submitDisabler}>Save</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
