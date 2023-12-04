import { type AxiosResponse, type AxiosInstance } from 'axios'
import httpClient from '@/httpClient'
import { type Color } from '@/Enums'
import { Link, type Column, type ResponseBodyWithData, Card, Links } from '@/types'

interface CreateColumnData {
  name: string
  order: number
  color: Color
}

type GetAllColumnResponse = ResponseBodyWithData<Array<Column & {
  _links: Record<string, Link>
  cards: Array<Card & { _links: Record<string, Link> }>
}>>
type CreateColumnResponse = ResponseBodyWithData<{ column: Pick<Column, 'id'> }>

type GetAllData = Array<Column & Links & {
  cards: Array<Card & Links>
}>

class ColumnService {
  readonly #http: AxiosInstance

  constructor (httpClient: AxiosInstance) {
    this.#http = httpClient
  }

  async getAll (boardId: string): Promise<GetAllData> {
    try {
      const { data: responseBody }: AxiosResponse<GetAllColumnResponse, any> = await this.#http.get(`/boards/${boardId}`)

      return responseBody.data.map((column) => ({
        ...column,
        links: column._links,
        cards: column.cards.map((card) => ({
          ...card,
          links: card._links
        }))
      }))
    } catch (e: any) {
      return e
    }
  }

  get (id: string) {

  }

  edit () {

  }

  delete () {

  }

  move () {

  }
}

export const columnService = new ColumnService(httpClient)
