import React, { type FormEvent, type ChangeEvent } from 'react'

interface Props {
  id: string
  inputNameValue: string
  onChangeNameHandler: (e: ChangeEvent<HTMLInputElement>) => void
  onSubmitHandler: (e: FormEvent<HTMLFormElement>) => void
}

export default function ColumnModal ({ id, inputNameValue, onChangeNameHandler, onSubmitHandler }: Props): JSX.Element {
  return (
    <dialog id={id} className="modal">
      <section className="modal-box">
        <form method="dialog">
          <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Create New Column</h3>
        </header>
        <form className="flex flex-col gap-4 mt-4" onSubmit={onSubmitHandler}>
          <div>
            <input
              type="text"
              placeholder="Type the Column name"
              className="input input-sm input-bordered w-full"
              value={inputNameValue}
              onChange={onChangeNameHandler}
              />
          </div>
          <div className="flex justify-end">
            <button className="btn btn-neutral btn-sm" type="submit" disabled={inputNameValue === ''}>Create</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
