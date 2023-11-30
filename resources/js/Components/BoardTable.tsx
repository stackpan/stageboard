import React from 'react'
import { EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import type Board from '@/types'
import { formatFromNow, formatToDate } from '@/Utils/datetime'

interface Props {
  boards: Board[]
}

export default function BoardTable ({ boards }: Props): JSX.Element {
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
        {boards.map((board: Board) => (
          <tr key={board.id}>
            <td>{board.name}</td>
            <td>{board.owner}</td>
            <td>{formatToDate(board.createdAt)}</td>
            <td>{formatFromNow(board.updatedAt)}</td>
            <td className="float-right">
              <div className="dropdown dropdown-end">
                <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
                  <EllipsisVerticalIcon className="h-6 w-6" />
                </div>
                <ul className="p-0 shadow menu menu-sm dropdown-content z-[1] bg-base-100 rounded-box w-36">
                  <li><a>Open in New Tab</a></li>
                  <li><a>Rename</a></li>
                  <li><a className="text-error">Delete</a></li>
                </ul>
              </div>
            </td>
          </tr>
        ))}
      </tbody>
    </table>
    </div>
  )
}
