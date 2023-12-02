import React from 'react'
import { EllipsisVerticalIcon } from '@heroicons/react/24/outline'
import { formatFromNow, formatToDate } from '@/Utils/datetime'
import { type Boards } from '@/Services/BoardService'

interface Props {
  boards: Boards
  isLoading: boolean
  onClickRenameHandler: (id: string, name: string) => void
  onClickDeleteHandler: (id: string) => void
}

export default function BoardTable ({ boards, isLoading, onClickRenameHandler, onClickDeleteHandler }: Props): JSX.Element {
  const RowSkeleton = (): JSX.Element => (
    <tr>
      <td><div className="skeleton h-6 w-28"></div></td>
      <td><div className="skeleton h-6 w-28"></div></td>
      <td><div className="skeleton h-6 w-28"></div></td>
      <td><div className="skeleton h-6 w-28"></div></td>
      <td></td>
    </tr>
  )

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
          {isLoading
            ? <>
                <RowSkeleton />
                <RowSkeleton />
                <RowSkeleton />
                <RowSkeleton />
              </>
            : boards.map((board) => (
              <tr key={board.id}>
                <td>{board.name}</td>
                <td>{board.user.name}</td>
                <td>{formatToDate(board.createdAt)}</td>
                <td>{formatFromNow(board.updatedAt)}</td>
                <td className="float-right">
                  <div className="dropdown dropdown-end">
                    <div tabIndex={0} role="button" className="btn btn-ghost btn-square btn-xs">
                      <EllipsisVerticalIcon className="h-6 w-6" />
                    </div>
                    <ul className="p-0 shadow menu menu-sm dropdown-content z-[1] bg-base-100 rounded-box w-36">
                      <li><a target="_blank" href={`http://localhost/board/${board.id}`} rel="noreferrer">Open in New Tab</a></li>
                      <li><button onClick={() => { onClickRenameHandler(board.id, board.name) }}>Rename</button></li>
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
