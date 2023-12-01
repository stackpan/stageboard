import httpClient from '@/httpClient'
import { type Links, type Board, type Link, type ResponseBodyWithData, type User, type Column, type ResponseBody, type Card } from '@/types'
import { type AxiosResponse, type AxiosInstance } from 'axios'

export type Boards = Array<Board & Links & { user: Pick<User, 'id' | 'name'> }>
export type BoardDetail = Omit<Board, 'openedAt' | 'thumbnailUrl'>
& Links
& {
  user: Pick<User, 'id' | 'name'>
  columns: Array<Column & Links & { cards: Array<Card & Links> } >
}

type GetAllResponse = ResponseBodyWithData<Array<{
  id: string
  name: string
  user: Pick<User, 'id' | 'name'>
  thumbnail_url: string
  opened_at: string
  created_at: string
  updated_at: string
  _links: Record<string, Link>
}>>

type GetResponse = ResponseBodyWithData<{
  id: string
  name: string
  created_at: string
  updated_at: string
  user: Pick<User, 'id' | 'name'>
  columns: Array<Column & {
    _links: Record<string, Link>
    cards: Array<Card & { _links: Record<string, Link> }>
  }>
  _links: Record<string, Link>
}>

class BoardService {
  readonly #http: AxiosInstance

  constructor (httpClient: AxiosInstance) {
    this.#http = httpClient
  }

  async getAll (): Promise<Boards> {
    try {
      const { data: responseBody }: AxiosResponse<GetAllResponse, any> = await this.#http.get('/boards')
      const boards = responseBody.data

      return boards.map((board) => ({
        id: board.id,
        name: board.name,
        thumbnailUrl: board.thumbnail_url,
        user: board.user,
        openedAt: board.opened_at,
        createdAt: board.created_at,
        updatedAt: board.updated_at,
        links: board._links
      }))
    } catch (e: any) {
      return e
    }
  }

  async get (id: string): Promise<BoardDetail> {
    try {
      const { data: responseBody }: AxiosResponse<GetResponse, any> = await this.#http.get(`/boards/${id}`)
      const board = responseBody.data

      return {
        id: board.id,
        name: board.name,
        user: board.user,
        createdAt: board.created_at,
        updatedAt: board.updated_at,
        columns: board.columns.map((column) => ({
          id: column.id,
          name: column.name,
          order: column.order,
          links: column._links,
          cards: column.cards.map((card) => ({
            id: card.id,
            body: card.body,
            links: card._links
          }))
        })),
        links: board._links
      }
    } catch (e: any) {
      return e
    }
  }

  async create (data: { name: string }): Promise<string> {
    try {
      const { data: responseBody }: AxiosResponse<ResponseBodyWithData<{ board: Pick<Board, 'id'> }>, any> = await this.#http.post('/boards', data)

      return responseBody.data.board.id
    } catch (e: any) {
      return e
    }
  }

  async edit (id: string, data: { name: string }): Promise<boolean> {
    try {
      const { data: responseBody }: AxiosResponse<ResponseBody, any> = await this.#http.patch(`/boards/${id}`, data)

      return responseBody.message !== null
    } catch (e: any) {
      return e
    }
  }

  async delete (id: string): Promise<boolean> {
    try {
      const { data: responseBody }: AxiosResponse<ResponseBody, any> = await this.#http.delete(`/boards/${id}`)

      return responseBody.message !== null
    } catch (e: any) {
      return e
    }
  }
}

const boardService = new BoardService(httpClient)

export { boardService }
