import React from 'react'
import { EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import { formatFromNow, formatToDate } from '@/Utils/datetime'
import { type Board } from '@/types'
import { router, usePage } from '@inertiajs/react'
import { type HomePageProps } from '@/Pages/Home'

interface Props {
  onClickEditHandler: (id: string) => void
  onClickDeleteHandler: (id: string) => void
}

export default function BoardTable ({ onClickEditHandler, onClickDeleteHandler }: Props): JSX.Element {
  const { auth, boards } = usePage<HomePageProps>().props

  const handleVisit = (board: Board): void => {
    router.visit(route('web.page.board.show', board.aliasId))
  }

  return (
    <div>
      <table className="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Author</th>
            <th>Created At</th>
            <th>Last Updated</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {boards.map((board) => (
            <tr key={board.id} className="hover:bg-base-200">
              <td className="cursor-pointer" onClick={() => { handleVisit(board) }}>{board.name}</td>
              <td className="cursor-pointer" onClick={() => { handleVisit(board) }}>{board.user.name + (board.user.id === auth.user.id && ' (You)')}</td>
              <td className="cursor-pointer" onClick={() => { handleVisit(board) }}>{formatToDate(board.createdAt)}</td>
              <td className="cursor-pointer" onClick={() => { handleVisit(board) }}>{formatFromNow(board.updatedAt)}</td>
              <td className="float-right">
                <div className="dropdown dropdown-end">
                  <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
                    <EllipsisVerticalIcon className="h-6 w-6" />
                  </div>
                  <ul className="p-0 shadow menu menu-sm dropdown-content z-[1] bg-base-100 rounded-box w-36">
                    <li><a target="_blank" href={route('web.page.board.show', board.aliasId)} rel="noreferrer">Open in New Tab</a></li>
                    <li><button onClick={() => { onClickEditHandler(board.id) }}>Edit</button></li>
                    <li><button onClick={() => { onClickDeleteHandler(board.id) }} className="text-error">Delete</button></li>
                  </ul>
                </div>
              </td>
            </tr>
          ))
          }
        </tbody>
      </table>
    </div>
  )
}
