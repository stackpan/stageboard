import React, { type ChangeEvent, type FormEvent } from 'react'
import { ColumnColor } from '@/Enums'

interface Props {
  id: string
  name: string
  selectedColor: ColumnColor
  onClickCloseHandler: () => void
  onChangeNameHandler: (e: ChangeEvent<HTMLInputElement>) => void
  onClickColorHandler: (color: ColumnColor) => void
  onSubmitHandler: (e: FormEvent<HTMLFormElement>) => void
  submitDisabler: boolean
}

const colorVariants = {
  '#f87171': 'bg-red-400',
  '#fbbf24': 'bg-amber-400',
  '#a3e635': 'bg-lime-400',
  '#34d399': 'bg-emerald-400',
  '#22d3ee': 'bg-cyan-400',
  '#60a5fa': 'bg-blue-400',
  '#a78bfa': 'bg-violet-400',
  '#e879f9': 'bg-fuchsia-400'
}

export default function CreateColumnModal ({
  id,
  name,
  selectedColor,
  onClickCloseHandler,
  onChangeNameHandler,
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
          <h3 className="font-bold text-lg">Create New Column</h3>
        </header>
        <form className="flex flex-col gap-4 mt-4" onSubmit={onSubmitHandler}>
          <div>
            <input
              name="name"
              type="text"
              placeholder="Type the Column name"
              className="input input-sm input-bordered w-full"
              value={name}
              maxLength={24}
              onChange={onChangeNameHandler}
              autoComplete="off"
              required
              />
          </div>
          <div className="flex gap-2">
            {Object.values(ColumnColor).map((color, index) => (
              <div key={color}>
                {color === selectedColor
                  ? <div className={'w-6 h-6 rounded-full border-4 border-gray-600 ' + colorVariants[color]} />
                  : <div className={'w-6 h-6 rounded-full border-4 ' + colorVariants[color]} onClick={() => { onClickColorHandler(color) }} />
                }
              </div>
            ))}
          </div>
          <div className="flex justify-end">
            <button className="btn btn-neutral btn-sm " type="submit" disabled={submitDisabler}>Create</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
