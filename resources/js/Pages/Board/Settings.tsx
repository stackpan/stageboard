import React, {type JSX, ReactNode, useState} from 'react'
import { type Board, type Collaborator, type PageProps } from '@/types'
import MainLayout from '@/Layouts/MainLayout'
import { Head, Link } from '@inertiajs/react'
import GeneralBoardSettingsForm from '@/Pages/Board/Partials/GeneralBoardSettingsForm'
import CollaborationBoardSettings from '@/Pages/Board/Partials/CollaborationBoardSettings'
import { ArrowLeftIcon } from '@heroicons/react/24/outline'
import DeleteBoardSettings from "@/Pages/Board/Partials/DeleteBoardSettings";

export type BoardSettingsProps = PageProps<{
  board: Board
  collaborators: Collaborator[]
}>

const Content = ({ activeSection }: { activeSection: number }): ReactNode => {
  switch (activeSection) {
    case 0:
      return <GeneralBoardSettingsForm className="max-w-2xl"/>
    case 1:
      return <CollaborationBoardSettings className="max-w-2xl"/>
    case 2:
      return <DeleteBoardSettings className="max-w-2xl" />
  }
}

export default function Settings ({ auth, board }: BoardSettingsProps): JSX.Element {
  const [activeSection, setActiveSection] = useState(0)

  return (
    <MainLayout user={auth.user} headerTitle={`${board.name} Settings`}>
      <Head title={`${board.name} - Edit`} />

      <div className="flex">
        <div>
          <div className="mx-3 my-4">
            <Link
              href={route('web.page.board.show', board.aliasId)}
              as="button"
              className="btn btn-ghost btn-sm text-gray-600"
            >
              <ArrowLeftIcon className="w-4" />
              <span>Back</span>
            </Link>
          </div>
          <ul className="menu w-96 rounded-box">
            <li>
              <button
                className={activeSection === 0 ? 'font-bold bg-base-200' : ''}
                onClick={() => {
                  setActiveSection(0)
                }}>General
              </button>
            </li>
            <li>
              <button
                className={activeSection === 1 ? 'font-bold bg-base-200' : ''}
                onClick={() => {
                  setActiveSection(1)
                }}>Collaboration
              </button>
            </li>
            <li></li>
            <li>
              <button
                onClick={() => {
                  setActiveSection(2)
                }}
                className={'text-red-500' + (activeSection === 2 ? ' font-bold bg-base-200' : '')}
              >Delete
              </button>
            </li>
          </ul>
        </div>
        <div className="flex-1 p-4">
          <Content activeSection={activeSection} />
        </div>
      </div>
    </MainLayout>
  )
}
