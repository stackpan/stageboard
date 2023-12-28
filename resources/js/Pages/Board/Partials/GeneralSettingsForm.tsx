import React, { type FormEventHandler, type JSX } from 'react'
import BoardSettingSectionLayout from '@/Layouts/BoardSettingSectionLayout'
import { useForm, usePage } from '@inertiajs/react'
import { type EditBoardProps } from '@/Pages/Board/Edit'

interface Props {
  className?: string
}

export default function GeneralSettingsForm ({ className = '' }: Props): JSX.Element {
  const { board } = usePage<EditBoardProps>().props

  const { data, setData, patch, errors } = useForm({
    name: board.name
  })

  const handleSubmit: FormEventHandler = (e) => {
    e.preventDefault()

    patch(route('web.boards.update', board.id))
  }

  return (
    <BoardSettingSectionLayout name="General" className={className}>
      <form onSubmit={handleSubmit}>
        <label className="form-control w-full">
          <div className="label">
            <span className="label-text font-bold">Name</span>
          </div>

          <div className="flex gap-4">
            <input
              type="text"
              name="firstName"
              value={data.name}
              className="input input-bordered w-full"
              autoComplete="none"
              onChange={(e) => {
                setData('name', e.target.value)
              }}
              required
            />
            <button type="submit" className="btn btn-outline">Rename</button>
          </div>

          {errors.name !== undefined && (
            <div className="label">
              <span className="label-text-alt text-red-500">{errors.name}</span>
            </div>
          )}
        </label>
      </form>
    </BoardSettingSectionLayout>
  )
}
