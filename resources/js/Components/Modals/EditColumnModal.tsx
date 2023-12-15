import React, { type ChangeEvent, type FormEvent } from 'react'
import { ColumnColor } from '@/Enums'
import { convertToBackgroundColor } from '@/Utils/color'

interface Props {
  id: string
  nameData: string
  selectedColorData: ColumnColor
  onClickCloseHandler: () => void
  onChangeNameHandler: (e: ChangeEvent<HTMLInputElement>) => void
  onClickColorHandler: (color: ColumnColor) => void
  onSubmitHandler: (e: FormEvent<HTMLFormElement>) => void
  submitDisabler: boolean
}

export default function EditColumnModal ({
  id,
  nameData,
  selectedColorData,
  onClickCloseHandler,
  onClickColorHandler,
  onChangeNameHandler,
  onSubmitHandler,
  submitDisabler
}: Props): JSX.Element {
  const selectColor = (color: ColumnColor): void => {
    onClickColorHandler(color)
  }

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
          <div className="flex gap-2">
            {Object.values(ColumnColor).map((color, index) => (
              <div key={color}>
                <div
                  className={`w-6 h-6 rounded-full border-4 ${color === selectedColorData ? 'border-gray-600' : ''}`}
                  style={convertToBackgroundColor(color)}
                  onClick={() => { selectColor(color) }}
                />
              </div>
            ))}
          </div>
          <div className="flex justify-end">
            <button className="btn btn-neutral btn-sm" type="submit" disabled={submitDisabler}>Save</button>
          </div>
        </form>
      </section>
    </dialog>
  )
}
