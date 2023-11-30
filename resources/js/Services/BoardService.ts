import { type Link } from '@/types'
import type Board from '@/types'
import http from '../httpClient'

interface BoardResponseObject {
  id: string
  name: string
  thumbnail_url: string
  user: {
    id: string
    name: string
  }
  opened_at: string
  created_at: string
  updated_at: string
  _links: Record<string, Link>
}

const getBoards = async (): Promise<Board[]> => {
  return await new Promise((resolve, reject) => {
    const boards = http.get('/boards')
      .then(({ data: responseBody }) => {
        return responseBody.data.map((board: BoardResponseObject): Board => ({
          id: board.id,
          name: board.name,
          thumbnailUrl: board.thumbnail_url,
          owner: board.user.name,
          openedAt: board.opened_at,
          links: board._links,
          createdAt: board.created_at,
          updatedAt: board.updated_at
        }))
      })
      .catch((e: Error) => {
        reject(e)
      })

    resolve(boards)
  })
}

export { getBoards }
