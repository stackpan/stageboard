import React, { type JSX } from 'react'
import { formatFromNow, formatToDate } from '@/Utils/datetime'
import { type Board } from '@/types'
import { router, usePage } from '@inertiajs/react'
import { type HomePageProps } from '@/Pages/Home'
import BoardOptionDropdown from '@/Components/BoardOptionDropdown'

export default function BoardTable (): JSX.Element {
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
              <td className="cursor-pointer" onClick={() => { handleVisit(board) }}>{board.user.name + (board.user.id === auth.user.id ? ' (You)' : '')}</td>
              <td className="cursor-pointer" onClick={() => { handleVisit(board) }}>{formatToDate(board.createdAt)}</td>
              <td className="cursor-pointer" onClick={() => { handleVisit(board) }}>{formatFromNow(board.updatedAt)}</td>
              <td className="float-right">
                <BoardOptionDropdown boardId={board.id} boardAliasId={board.aliasId} className="dropdown-end" />
              </td>
            </tr>
          ))
          }
        </tbody>
      </table>
    </div>
  )
}
