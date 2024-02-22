import React, { type JSX, useState } from 'react'
import BoardSettingSectionLayout from '@/Layouts/BoardSettingSectionLayout'
import { router, usePage } from '@inertiajs/react'
import { type BoardSettingsProps } from '@/Pages/Board/Settings'

interface Props {
  className?: string
}

export default function DeleteBoardSettings ({ className = '' }: Props): JSX.Element {
  const { board } = usePage<BoardSettingsProps>().props

  const [activeModal, setActiveModal] = useState(false)

  const handleDeleteConfirmation = (): void => {
    router.visit(route('web.page.home'), {
      onFinish: () => {
        router.delete(route('web.boards.destroy', board.id))
      }
    })
  }

  return (
    <BoardSettingSectionLayout name="Delete Board" className={className}>
      <p className="font-bold">Once you delete this board, there is no going back. Please be certain!</p>
      <button
        type="button"
        className="btn btn-error btn-outline btn-sm mt-4"
        onClick={() => {
          setActiveModal(true)
        }}
      >Delete Board
      </button>

      <dialog className={'modal' + (activeModal ? ' modal-open' : '')}>
        <div className="modal-box">
          <h3 className="font-bold text-lg">Confirmation</h3>
          <p className="py-4">Are you sure want to delete <span className="font-bold">{board.name}</span>?</p>
          <div className="modal-action">
            <button onClick={() => { setActiveModal(false) }} className="btn btn-sm btn-outline">Cancel</button>
            <button onClick={handleDeleteConfirmation} className="btn btn-sm btn-error">Yes, I&apos;m sure</button>
          </div>
        </div>
      </dialog>
    </BoardSettingSectionLayout>
  )
}
