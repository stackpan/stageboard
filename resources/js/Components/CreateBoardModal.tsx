import React, { type FormEvent, type ChangeEvent } from 'react'

interface Props {
  id: string
  inputName: string
  onClickCloseHandler: () => void
  onChangeNameHandler: (e: ChangeEvent<HTMLInputElement>) => void
  onSubmitHandler: (e: FormEvent<HTMLFormElement>) => void
}

export default function CreateBoardModal ({ id, inputName, onClickCloseHandler, onChangeNameHandler, onSubmitHandler }: Props): JSX.Element {
  return (
    <dialog id={id} className="modal">
      <section className="modal-box">
        <form method="dialog">
          <button onClick={onClickCloseHandler} className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Create New Board</h3>
        </header>
        <form className="flex flex-col gap-4 mt-4" onSubmit={onSubmitHandler}>
          <div>
            <input
              type="text"
              placeholder="Type the board name"
              className="input input-sm input-bordered w-full"
              value={inputName}
              onChange={onChangeNameHandler}
              />
          </div>
          <div className="flex justify-end">
            <button className="btn btn-neutral btn-sm" disabled={inputName === ''}>Create</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
