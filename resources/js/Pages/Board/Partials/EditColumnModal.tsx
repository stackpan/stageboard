import React, { type ChangeEvent, type FormEvent, useEffect } from 'react'
import { ColumnColor } from '@/Enums'
import { convertToBackgroundColor } from '@/Utils/color'
import { router, useForm } from '@inertiajs/react'
import axios from 'axios'
import { type Column } from '@/types'

interface Props {
  active: boolean
  closeHandler: () => void
  selectingColumnId: string
}

export default function EditColumnModal ({ active, closeHandler, selectingColumnId }: Props): JSX.Element {
  // eslint-disable-next-line @typescript-eslint/unbound-method
  const { data, setData, patch, setDefaults, isDirty, processing } = useForm({
    name: '',
    color: ColumnColor.Red
  })

  useEffect(() => {
    if (selectingColumnId !== '') {
      axios.get<Column>(route('web.columns.show', selectingColumnId))
        .then((response) => {
          if (response.status === 200) {
            const formData = response.data

            setDefaults(formData)
            setData(formData)
          }
        })
        .catch((e) => {
          console.log(e)
        })
    }
  }, [selectingColumnId])

  const handleChangeName = (e: ChangeEvent<HTMLInputElement>): void => {
    setData((previousData) => ({
      ...previousData,
      name: e.target.value
    }))
  }

  const selectColor = (color: ColumnColor): void => {
    setData((previousData) => ({
      ...previousData,
      color
    }))
  }

  const handleSubmit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault()

    patch(route('web.columns.update', selectingColumnId), {
      onFinish: () => {
        closeHandler()

        router.reload({ only: ['columns'] })
      }
    })
  }

  const submitDisabler = data.name === '' || !isDirty || processing

  return (
    <dialog className={'modal' + (active ? ' modal-open' : '')}>
      <section className="modal-box">
        <form method="dialog">
          <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onClick={closeHandler}>✕</button>
        </form>
        <header>
          <h3 className="font-bold text-lg">Edit Column</h3>
        </header>
        <form className="flex flex-col gap-4 mt-4" onSubmit={handleSubmit}>
          <div>
            <input
              name="name"
              type="text"
              placeholder="Type the Column name"
              className="input input-sm input-bordered w-full"
              value={data.name}
              onChange={handleChangeName}
              maxLength={24}
              autoComplete="off"
              required
              />
          </div>
          <div className="flex gap-2">
            {Object.values(ColumnColor).map((color) => (
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
      </section>
    </dialog>
  )
}
