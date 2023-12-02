import React, { type ChangeEvent } from 'react'

interface Props {
  id: string
  onInputNameChange: (e: ChangeEvent<HTMLInputElement>) => void
}

export default function ColumnModal ({ id, onInputNameChange }: Props): JSX.Element {
  return (
    <dialog id={id} className="modal">
      <section className="modal-box">
        <form method="dialog">
          <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Create New Column</h3>
        </header>
        <form method="post" className="flex flex-col gap-4 mt-4">
          <div>
            <input
              type="text"
              placeholder="Type the Column name"
              className="input input-sm input-bordered w-full"
              onChange={(e) => { onInputNameChange(e) }}
              />
          </div>
          <div className="flex justify-end">
            <button className="btn btn-neutral btn-sm">Create</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
