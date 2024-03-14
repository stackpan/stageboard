import React, { type FormEventHandler, type JSX } from 'react'
import BoardSettingSectionLayout from '@/Layouts/BoardSettingSectionLayout'
import { useForm, usePage } from '@inertiajs/react'
import { type BoardSettingsProps } from '@/Pages/Board/Settings'

interface Props {
  className?: string
}

export default function GeneralBoardSettingsForm ({ className = '' }: Props): JSX.Element {
  const { board } = usePage<BoardSettingsProps>().props

  const { data, setData, patch, errors } = useForm({
    name: board.name,
    isPublic: board.isPublic
  })

  const handleSubmit: FormEventHandler = (e) => {
    e.preventDefault()

    patch(route('web.boards.update', board.id))
  }

  return (
    <BoardSettingSectionLayout name="General" className={className}>
      <div className="space-y-4">
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

        <div className="form-control">
          <label className="label cursor-pointer justify-start gap-4">
            <input
              type="checkbox"
              className="checkbox"
              checked={data.isPublic}
              onChange={(e) => {
                e.preventDefault()
                patch(route('web.boards.update', board.id), {
                  onBefore: () => {
                    setData('isPublic', e.target.checked)
                  }
                })
              }}
            />
            <span className="label-text">Public</span>
          </label>
        </div>
      </div>
    </BoardSettingSectionLayout>
  )
}
