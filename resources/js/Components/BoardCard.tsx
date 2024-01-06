import React, { type JSX } from 'react'
import { formatFromNow } from '@/Utils/datetime'
import { router } from '@inertiajs/react'
import BoardOptionDropdown from '@/Components/BoardOptionDropdown'

interface Props {
  id: string
  aliasId: string
  name: string
  thumbnailUrl: string
  owner: string
  openedAt: string
}

export default function BoardCard ({ id, aliasId, name, owner, openedAt }: Props): JSX.Element {
  return (
    <div
      onClick={() => { router.visit(route('web.page.board.show', aliasId)) }}
      className="card card-compact w-72 bg-base-100 shadow-md flex-none rounded hover:bg-base-200 active:bg-base-300 cursor-pointer"
    >
      <div className="h-3 rounded-t bg-neutral"></div>
      <div className="card-body">
        <div className="flex justify-between">
          <h2 className="card-title">{name}</h2>
          <BoardOptionDropdown boardId={id} boardAliasId={aliasId} />
        </div>
        <p><span>{owner}</span> - <span>{formatFromNow(openedAt)}</span></p>
      </div>
    </div>
  )
}
